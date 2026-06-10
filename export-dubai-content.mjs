#!/usr/bin/env node
/**
 * export-dubai-content.mjs
 * ---------------------------------------------------------------------------
 * Regenerates src/dubai-content.json from src/dubai-content.php so the Node
 * preview mirror (preview-server.mjs) renders exactly what production PHP
 * renders. The PHP file stays the single source of truth; run this after any
 * edit to it:
 *
 *   node export-dubai-content.mjs
 *
 * It parses the PHP array literals ($data plus the four normalization maps)
 * with a small recursive-descent parser, replays the same normalization the
 * PHP file performs at the bottom (name/short_name/subtitle/activity_count/
 * api_query on categories; activity_id/image/category names/short_name/
 * related_activity_ids/quick_facts on attractions), and writes the result as
 * pretty-printed JSON.
 * ---------------------------------------------------------------------------
 */

import { readFileSync, writeFileSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const phpPath = path.join(__dirname, 'src', 'dubai-content.php');
const jsonPath = path.join(__dirname, 'src', 'dubai-content.json');

const src = readFileSync(phpPath, 'utf8');

// ---------------------------------------------------------------------------
// Minimal PHP array-literal parser (data files only: arrays, strings,
// numbers, booleans, null). No variables, concatenation or heredocs.
// ---------------------------------------------------------------------------

function parsePhpValue(text, start) {
  let i = start;

  function err(msg) {
    const line = text.slice(0, i).split('\n').length;
    throw new Error(`${msg} at offset ${i} (line ${line}): ...${text.slice(Math.max(0, i - 40), i + 40)}...`);
  }

  function skipWs() {
    for (;;) {
      while (i < text.length && /\s/.test(text[i])) i++;
      if (text.startsWith('//', i) || text.startsWith('#', i)) {
        while (i < text.length && text[i] !== '\n') i++;
      } else if (text.startsWith('/*', i)) {
        const end = text.indexOf('*/', i + 2);
        if (end === -1) err('Unterminated block comment');
        i = end + 2;
      } else {
        return;
      }
    }
  }

  function parseSingleQuoted() {
    i++; // opening '
    let out = '';
    while (i < text.length) {
      const ch = text[i];
      if (ch === '\\') {
        const next = text[i + 1];
        if (next === "'" || next === '\\') { out += next; i += 2; }
        else { out += ch; i++; } // PHP keeps the backslash literally
      } else if (ch === "'") {
        i++;
        return out;
      } else {
        out += ch;
        i++;
      }
    }
    err('Unterminated single-quoted string');
  }

  function parseDoubleQuoted() {
    i++; // opening "
    let out = '';
    while (i < text.length) {
      const ch = text[i];
      if (ch === '\\') {
        const next = text[i + 1];
        i += 2;
        switch (next) {
          case 'n': out += '\n'; break;
          case 't': out += '\t'; break;
          case 'r': out += '\r'; break;
          case 'v': out += '\v'; break;
          case 'f': out += '\f'; break;
          case 'e': out += '\x1b'; break;
          case '\\': out += '\\'; break;
          case '$': out += '$'; break;
          case '"': out += '"'; break;
          default: out += '\\' + next; // PHP keeps unknown escapes literally
        }
      } else if (ch === '"') {
        i++;
        return out;
      } else if (ch === '$' && /[a-zA-Z_{]/.test(text[i + 1] || '')) {
        err('Variable interpolation in double-quoted string is not supported');
      } else {
        out += ch;
        i++;
      }
    }
    err('Unterminated double-quoted string');
  }

  function parseNumber() {
    const m = /^[+-]?(0[xX][0-9a-fA-F]+|\d+\.\d+([eE][+-]?\d+)?|\d+([eE][+-]?\d+)?)/.exec(text.slice(i));
    if (!m) err('Invalid number');
    i += m[0].length;
    return Number(m[0]);
  }

  function parseArray() {
    // supports both [ ... ] and array( ... )
    let close;
    if (text[i] === '[') { close = ']'; i++; }
    else if (/^array\s*\(/i.test(text.slice(i))) { close = ')'; i = text.indexOf('(', i) + 1; }
    else err('Expected array');

    const entries = [];
    let isMap = false;
    for (;;) {
      skipWs();
      if (text[i] === close) { i++; break; }
      const keyOrValue = parseValue();
      skipWs();
      if (text.startsWith('=>', i)) {
        i += 2;
        skipWs();
        entries.push([keyOrValue, parseValue()]);
        isMap = true;
      } else {
        entries.push([null, keyOrValue]);
      }
      skipWs();
      if (text[i] === ',') { i++; continue; }
      if (text[i] === close) { i++; break; }
      err(`Expected ',' or '${close}' in array`);
    }

    if (!isMap) return entries.map(([, v]) => v);
    const obj = {};
    let autoIndex = 0;
    for (const [k, v] of entries) {
      obj[k === null ? autoIndex++ : k] = v;
    }
    return obj;
  }

  function parseValue() {
    skipWs();
    const ch = text[i];
    if (ch === "'") return parseSingleQuoted();
    if (ch === '"') return parseDoubleQuoted();
    if (ch === '[' || /^array\s*\(/i.test(text.slice(i))) return parseArray();
    if (/^true\b/i.test(text.slice(i))) { i += 4; return true; }
    if (/^false\b/i.test(text.slice(i))) { i += 5; return false; }
    if (/^null\b/i.test(text.slice(i))) { i += 4; return null; }
    if (/[0-9+.-]/.test(ch)) return parseNumber();
    err('Unexpected token');
  }

  const value = parseValue();
  return { value, end: i };
}

function extractAssignment(varName) {
  const re = new RegExp(`\\$${varName}\\s*=\\s*`);
  const m = re.exec(src);
  if (!m) throw new Error(`Assignment $${varName} = ... not found in ${phpPath}`);
  const { value, end } = parsePhpValue(src, m.index + m[0].length);
  const rest = src.slice(end).replace(/^\s*/, '');
  if (!rest.startsWith(';')) throw new Error(`Expected ';' after $${varName} literal`);
  return value;
}

// ---------------------------------------------------------------------------
// Load the raw data + normalization maps
// ---------------------------------------------------------------------------

const data = extractAssignment('data');
const categoryNames = extractAssignment('categoryNames');
const categorySubtitles = extractAssignment('categorySubtitles');
const categoryShortNames = extractAssignment('categoryShortNames');
const attractionShortNames = extractAssignment('attractionShortNames');

// ---------------------------------------------------------------------------
// Replay the normalization from the bottom of dubai-content.php
// ---------------------------------------------------------------------------

const ucwords = (s) => s.replace(/(^|\s)\S/g, (c) => c.toUpperCase());

for (const cat of data.categories || []) {
  const slug = cat.slug;
  cat.name = categoryNames[slug] ?? ucwords(slug.replace(/-/g, ' '));
  cat.short_name = categoryShortNames[slug] ?? cat.name;
  cat.subtitle = categorySubtitles[slug] ?? '';
  const idCount = (cat.activity_ids || []).length;
  cat.activity_count = idCount > 0 ? `${idCount} experiences` : '';
  cat.api_query = cat.api_query ?? cat.name;
}

const categoryNameMap = {};
const categoryShortMap = {};
for (const cat of data.categories || []) {
  categoryNameMap[cat.slug] = cat.name;
  categoryShortMap[cat.slug] = cat.short_name;
}

for (const att of data.attractions || []) {
  att.activity_id = att.id ?? 0;
  att.image = att.hero_image ?? '';
  att.category_name = categoryNameMap[att.category_slug ?? ''] ?? 'Attractions';
  att.category_short_name = categoryShortMap[att.category_slug ?? ''] ?? att.category_name;
  att.short_name = attractionShortNames[att.slug ?? ''] ?? (att.title ?? '');
  att.related_activity_ids = att.related_ids ?? [];
  att.quick_facts = {
    duration: att.duration ?? '',
    best_time: att.best_time ?? '',
    location: att.location ?? 'Dubai, UAE',
  };
}

// ---------------------------------------------------------------------------
// Sanity checks, then write
// ---------------------------------------------------------------------------

if (!Array.isArray(data.categories) || data.categories.length === 0) throw new Error('No categories parsed');
if (!Array.isArray(data.attractions) || data.attractions.length === 0) throw new Error('No attractions parsed');
if (!Array.isArray(data.hub_faqs) || data.hub_faqs.length === 0) throw new Error('No hub_faqs parsed');

writeFileSync(jsonPath, JSON.stringify(data, null, 2) + '\n', 'utf8');
console.log(`Wrote ${jsonPath}: ${data.categories.length} categories, ${data.attractions.length} attractions, ${data.hub_faqs.length} hub FAQs`);
