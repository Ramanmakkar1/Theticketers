<?php
declare(strict_types=1);

/**
 * Merge agent-authored artist intent content (JSON array of artist objects)
 * into src/artist-intent-content.php, keeping the existing hand-authored
 * entries (e.g. the Billie Eilish pilot) and adding/overwriting by slug.
 *
 * Usage: php bin/merge-intent-content.php <path-to-results.json>
 */

$root = dirname(__DIR__);
$jsonPath = $argv[1] ?? '';
if ($jsonPath === '' || !is_file($jsonPath)) {
    fwrite(STDERR, "Usage: php bin/merge-intent-content.php <results.json>\n");
    exit(1);
}

$incoming = json_decode((string) file_get_contents($jsonPath), true);
if (!is_array($incoming)) {
    fwrite(STDERR, "Could not parse JSON.\n");
    exit(1);
}

/** @var array<string,mixed> $existing */
$existing = require $root . '/src/artist-intent-content.php';

$added = 0;
foreach ($incoming as $obj) {
    if (!is_array($obj) || empty($obj['slug'])) {
        continue;
    }
    $slug = (string) $obj['slug'];
    $existing[$slug] = [
        'name'    => (string) ($obj['name'] ?? ''),
        'genre'   => (string) ($obj['genre'] ?? ''),
        'prices'  => $obj['prices'] ?? null,
        'tour'    => $obj['tour'] ?? null,
        'setlist' => $obj['setlist'] ?? null,
    ];
    $added++;
}

$header = <<<'PHP'
<?php
declare(strict_types=1);

/* =========================================================================
   Curated artist INTENT content — the only artists whose /artist/{slug}/
   {ticket-prices|tour-dates|setlist} pages we ask Google (and AI engines)
   to index. Every entry is hand-written/curated and unique to the artist;
   an artist not in this map 404s on the intent routes, so these can never
   become thin templated doorways (same rule as category-content.php).

   These pages are EVERGREEN: they render and rank even when no show is on
   sale. Live dates, live "from" prices and ticket cards are injected at
   render time from the API when available.

   This file is partly machine-assembled by bin/merge-intent-content.php.
   Shape per slug: name, genre, prices{range_low,range_high,currency,intro[],
   tiers[{name,desc}],why,faqs[{q,a}]}, tour{tour_name,intro[],faqs[{q,a}]},
   setlist{intro[],songs[],encore[],note,faqs[{q,a}]}.
   ========================================================================= */

return
PHP;

$body = var_export($existing, true);
// var_export emits "array (" — leave as-is (valid). Tidy numeric-array noise minimally.
$out = $header . ' ' . $body . ";\n";

// Atomic write + parse-check: the target is require()d live at render time, so a
// half-written file would fatal page renders. Write to a temp in the SAME dir (src/),
// lint it, then atomically rename into place.
$target = $root . '/src/artist-intent-content.php';
$tmp = $target . '.tmp.' . getmypid();
if (file_put_contents($tmp, $out) === false) {
    fwrite(STDERR, "Failed to write temp file\n");
    exit(1);
}
exec('php -l ' . escapeshellarg($tmp), $lintOut, $lintCode);
if ($lintCode !== 0) {
    @unlink($tmp);
    fwrite(STDERR, "Generated file failed php -l, aborting:\n" . implode("\n", $lintOut) . "\n");
    exit(1);
}
if (!rename($tmp, $target)) {
    @unlink($tmp);
    fwrite(STDERR, "Failed to rename temp file into place\n");
    exit(1);
}

fwrite(STDOUT, "Merged {$added} artists. Store now has " . count($existing) . " total.\n");
