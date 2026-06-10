import http from 'node:http';
import { readFile } from 'node:fs/promises';
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createHash } from 'node:crypto';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const PORT = Number(process.env.PORT || 8000);
const API_BASE = process.env.HELLOTICKETS_API_URL || 'https://api-live.hellotickets.com';
const API_KEY = process.env.HELLOTICKETS_PUBLIC_KEY || 'pub-bcaaca28-c7df-4fc1-9274-61a0f1439d13';
const IMPACT_URL = process.env.IMPACT_BASE_URL || 'https://hellotickets.sjv.io/MKNd7K';
const CURRENCY = process.env.HELLOTICKETS_CURRENCY || 'AED';
const SITE_NAME = process.env.SITE_NAME || 'TickedBus';
const DEFAULT_CITY_ID = 132;
const cacheDir = path.join(__dirname, 'storage', 'cache-preview');

const fallbackImages = {
  hero: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1800&q=80',
  activity: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1000&q=80',
  event: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80',
  Concerts: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1000&q=80',
  Theatre: 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=1000&q=80',
  Sports: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=1000&q=80',
  burj: 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1000&q=80',
  waterpark: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
  desert: 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=1000&q=80',
  aquarium: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
  cruise: 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1000&q=80',
};

if (!existsSync(cacheDir)) mkdirSync(cacheDir, { recursive: true });

function esc(value = '') {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function slugify(value) {
  return String(value).normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'tickets';
}

function idSlug(name, id) {
  return `${slugify(name)}-${id}`;
}

function idFromSlug(slug) {
  const match = String(slug).match(/-(\d+)$/);
  return Number(match ? match[1] : slug);
}

function b64url(value) {
  return Buffer.from(String(value)).toString('base64url');
}

function fromB64url(value) {
  try {
    return Buffer.from(String(value), 'base64url').toString('utf8');
  } catch {
    return '';
  }
}

function money(amount, currency = CURRENCY) {
  const num = Number(amount || 0);
  if (num <= 0) return 'Check price';
  const prefixes = { AED: 'AED ', EUR: 'EUR ', USD: '$', GBP: 'GBP ' };
  return `${prefixes[currency] || ''}${new Intl.NumberFormat('en-US', { maximumFractionDigits: Number.isInteger(num) ? 0 : 2 }).format(num)}${prefixes[currency] ? '' : ` ${currency}`}`;
}

function dateParams(kind) {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  let from = new Date(today);
  let to = new Date(today);
  if (kind === 'today') {
    // same day
  } else if (kind === 'tomorrow') {
    from.setDate(from.getDate() + 1);
    to = new Date(from);
  } else if (kind === 'weekend') {
    const day = today.getDay();
    const daysUntilSaturday = (6 - day + 7) % 7 || 7;
    from.setDate(from.getDate() + daysUntilSaturday);
    to = new Date(from);
    to.setDate(to.getDate() + 1);
  } else if (kind === 'month') {
    to.setDate(to.getDate() + 30);
  } else {
    to.setFullYear(to.getFullYear() + 1);
  }
  return {
    local_date_from: from.toISOString().slice(0, 10),
    local_date_to: to.toISOString().slice(0, 10),
  };
}

function formatDate(start = {}) {
  if (start.date_tba) return 'Date to be announced';
  if (!start.local_date) return 'Upcoming';
  const date = new Date(`${start.local_date}T${start.local_time || '00:00'}:00`);
  const dateText = new Intl.DateTimeFormat('en-US', { weekday: 'short', month: 'short', day: 'numeric' }).format(date);
  if (!start.local_time || start.time_tba) return dateText;
  const timeText = new Intl.DateTimeFormat('en-US', { hour: 'numeric', minute: '2-digit' }).format(date);
  return `${dateText} at ${timeText}`;
}

function image(item, type) {
  if (item.image) return item.image;
  const first = Array.isArray(item.images) ? item.images[0] : null;
  if (typeof first === 'string') return first;
  if (first?.url) return first.url;
  const name = String(item.title || item.name || '').toLowerCase();
  const keywordImages = {
    burj: ['burj', 'khalifa', 'dubai frame', 'skyscraper'],
    waterpark: ['waterpark', 'aquaventure', 'aqua', 'water park'],
    desert: ['desert', 'safari', 'dune', 'camel'],
    aquarium: ['aquarium', 'underwater', 'dolphin', 'seal'],
    cruise: ['cruise', 'boat', 'yacht', 'marina', 'dhow'],
  };
  for (const [key, needles] of Object.entries(keywordImages)) {
    if (needles.some(needle => name.includes(needle))) return fallbackImages[key];
  }
  if (item.category?.name && fallbackImages[item.category.name]) return fallbackImages[item.category.name];
  return fallbackImages[type] || fallbackImages.hero;
}

async function api(endpoint, params = {}, ttl = 3600) {
  const query = new URLSearchParams(Object.entries(params).filter(([, value]) => value !== undefined && value !== null && value !== ''));
  const url = `${API_BASE.replace(/\/$/, '')}${endpoint}${query.size ? `?${query}` : ''}`;
  const key = createHash('sha1').update(`${url}|${CURRENCY}`).digest('hex');
  const file = path.join(cacheDir, `${key}.json`);
  if (existsSync(file) && Date.now() - readFileSync(file, 'utf8').split('\n', 1)[0] < ttl * 1000) {
    return JSON.parse(readFileSync(file, 'utf8').split('\n').slice(1).join('\n'));
  }
  const response = await fetch(url, {
    headers: {
      Accept: 'application/json',
      'X-Public-Key': API_KEY,
      'X-Currency': CURRENCY,
      'Accept-Language': 'en-GB',
    },
  });
  if (!response.ok) throw new Error(`HelloTickets ${response.status}`);
  const data = await response.json();
  writeFileSync(file, `${Date.now()}\n${JSON.stringify(data)}`);
  return data;
}

function layout(title, description, body) {
  return `<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>${esc(title)}</title>
  <meta name="description" content="${esc(description)}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
  <header class="site-header">
    <a class="brand" href="/">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="display: block; width: 28px; height: 28px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path><line x1="9" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><line x1="15" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line></svg>
      <span>Ticked<em>Bus</em></span>
    </a>
    <div class="header-search">
      <form action="/search" method="get">
        <input type="search" name="q" placeholder="Search for Events, Attractions, Concerts, Theatre and Tours">
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="header-actions">
      <a class="header-city" href="/city/dubai-132">Dubai</a>
      <a class="header-cta" href="/attractions">Get Tickets</a>
      <button class="nav-toggle" type="button" data-nav-toggle aria-label="Open menu"><span></span><span></span><span></span></button>
    </div>
  </header>
  <div class="site-subnav">
    <div class="container">
      <nav class="site-nav" data-nav><a href="/events">Events</a><a href="/attractions">Attractions</a><a href="/category/concerts-2">Concerts</a><a href="/category/theatre-3">Theatre</a><a href="/category/sports-1">Sports</a></nav>
      <div class="subnav-side"><a href="/city/dubai-132">Dubai</a><a href="/city/abu-dhabi-256">Abu Dhabi</a><a href="/city/las-vegas-6">Las Vegas</a></div>
    </div>
  </div>
  <main>${body}</main>
  <footer class="site-footer"><div><strong><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 22px; height: 22px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></svg>Ticked<em style="font-style: normal; color: var(--red);">Bus</em></strong><p>Your guide to Dubai events, attractions and experiences. Prices and availability are live from our ticket partner, and checkout is completed securely on their site. We may earn a commission on bookings at no extra cost to you. &copy; ${new Date().getFullYear()} ${esc(SITE_NAME)}. All events, images and trademarks belong to their respective owners.</p></div><div class="footer-links"><a href="/events">Events</a><a href="/attractions">Attractions</a><a href="/city/dubai-132">Dubai</a><a href="/search">Search</a><a href="/sitemap.xml">Sitemap</a></div></footer>
  <script src="/assets/app.js" defer></script>
</body>
</html>`;
}

function eventPath(item) {
  return `/event/${idSlug(item.name || 'event', item.id)}`;
}

function activityPath(item) {
  return `/activity/${idSlug(item.title || 'activity', item.id)}`;
}

function goUrl(item, type) {
  return `/go?type=${type}&id=${item.id}&u=${b64url(item.url || '')}`;
}

function eventCard(item) {
  const dateStr = item.start_date?.local_date || '';
  let monthAbbr = 'TBA';
  let dayNum = '';
  if (dateStr) {
    const d = new Date(`${dateStr}T00:00:00`);
    if (!isNaN(d.getTime())) {
      monthAbbr = d.toLocaleString('en-US', { month: 'short' }).toUpperCase();
      dayNum = d.getDate();
    }
  }

  return `<article class="ticket-card">
    <a class="card-image" href="${eventPath(item)}">
      <img src="${esc(image(item, 'event'))}" alt="${esc(item.name || 'Event')}" loading="lazy">
      <div class="card-date-badge">
        <span class="month">${esc(monthAbbr)}</span>
        <span class="day">${esc(dayNum)}</span>
      </div>
      <div class="card-rating-strip">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
        4.9/5
        <span class="votes">${esc(item.category?.name || 'Event')}</span>
      </div>
    </a>
    <div class="card-body">
      <a class="card-title" href="${eventPath(item)}">${esc(item.name || 'Event')}</a>
      <p>${esc(formatDate(item.start_date))}</p>
      <p>${esc([item.venue?.name, item.venue?.city || 'Dubai'].filter(Boolean).join(', '))}</p>
      <p class="card-onwards">${esc(money(item.price_range?.min_price, item.price_range?.currency))}${Number(item.price_range?.min_price || 0) > 0 ? ' onwards' : ''}</p>
    </div>
  </article>`;
}

function activityCard(item) {
  const rating = item.reviews?.avg_rating ? Number(item.reviews.avg_rating).toFixed(1) : '4.8';
  const reviewsCount = item.reviews?.number_of_reviews ? Number(item.reviews.number_of_reviews) : null;
  return `<article class="ticket-card">
    <a class="card-image" href="${activityPath(item)}">
      <img src="${esc(image(item, 'activity'))}" alt="${esc(item.title || 'Experience')}" loading="lazy">
      <span class="category">${esc(item.city?.name || 'Attraction')}</span>
      <div class="card-rating-strip">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
        ${rating}/5
        ${reviewsCount !== null ? `<span class="votes">${new Intl.NumberFormat('en-US').format(reviewsCount)} votes</span>` : ''}
      </div>
    </a>
    <div class="card-body">
      <a class="card-title" href="${activityPath(item)}">${esc(item.title || 'Experience')}</a>
      <p>${esc(item.supplier_name || 'Ticket partner')}</p>
      <p class="card-onwards">${esc(money(item.from_price, item.currency))}${Number(item.from_price || 0) > 0 ? ' onwards' : ''}</p>
    </div>
  </article>`;
}

function cardSection(heading, href, items, type, variant = '') {
  if (!items?.length) return '';
  const cards = items.map(type === 'event' ? eventCard : activityCard).join('');
  return `<section class="section-band${variant ? ` ${variant}` : ''}"><div class="container"><div class="section-heading"><h2>${esc(heading)}</h2><a href="${href}">Show all</a></div><div class="rail-wrapper"><button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button><div class="rail">${cards}</div><button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button></div></div></section>`;
}

function grid(items, type) {
  const cards = items.map(type === 'event' ? eventCard : activityCard).join('');
  return `<section class="section-band"><div class="container">${cards ? `<div class="card-grid">${cards}</div>` : `<div class="empty-state"><h2>No tickets found</h2><p>Try a broader search.</p><a class="button-link" href="/">Back home</a></div>`}</div></section>`;
}

async function home() {
  const [eventsData, activitiesData, globalEventsData, categoriesData] = await Promise.all([
    api('/v1/performances', { limit: 12, page: 1, is_sellable: true, city_id: DEFAULT_CITY_ID, ...dateParams() }),
    api('/v1/activities', { limit: 12, page: 1, city_id: DEFAULT_CITY_ID }),
    api('/v1/performances', { limit: 12, page: 1, is_sellable: true, ...dateParams() }),
    api('/v1/categories', {}, 86400),
  ]);
  const categories = (categoriesData.categories || []).slice(0, 18).map(cat => `<a href="/category/${idSlug(cat.name, cat.id)}">${esc(cat.name)}</a>`).join('');
  return layout(`Dubai Events, Attractions & Tickets | ${SITE_NAME}`, 'Find Dubai attraction tickets, concerts, theatre, sports and experiences.', `
    <section class="hero">
      <div class="container">
        <div class="carousel" data-carousel>
          <div class="carousel-track" data-carousel-track>
            ${[
              { image: fallbackImages.hero, tag: 'Featured', title: 'Dubai events, attractions and experiences', text: 'Live prices and availability, with secure partner checkout.', href: '/attractions', cta: 'Book Now', h: 'h1' },
              { image: fallbackImages.burj, tag: 'Top Attraction', title: 'Burj Khalifa: At the Top', text: "Skip the queue with instant e-tickets to the world's tallest tower.", href: '/attractions?q=Burj%20Khalifa', cta: 'Get Tickets', h: 'h2' },
              { image: fallbackImages.desert, tag: 'Experiences', title: 'Desert safaris and dune adventures', text: 'Sunset drives, camel rides and Bedouin dinners under the stars.', href: '/attractions?q=Desert%20Safari', cta: 'Explore', h: 'h2' },
              { image: fallbackImages.Concerts, tag: 'Live Events', title: 'Concerts, theatre and sport in Dubai', text: "See what's playing this week across the city's biggest venues.", href: '/events', cta: 'See Events', h: 'h2' },
            ].map(slide => `<div class="carousel-slide" style="background-image: url('${slide.image}')">
              <div class="carousel-caption">
                <span class="slide-tag">${esc(slide.tag)}</span>
                <${slide.h}>${esc(slide.title)}</${slide.h}>
                <p>${esc(slide.text)}</p>
                <a class="slide-btn" href="${slide.href}">${esc(slide.cta)}</a>
              </div>
            </div>`).join('')}
          </div>
          <button class="carousel-btn prev" type="button" data-carousel-prev aria-label="Previous banner"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
          <button class="carousel-btn next" type="button" data-carousel-next aria-label="Next banner"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
          <div class="carousel-dots" data-carousel-dots></div>
        </div>
      </div>
    </section>
    <section class="section-band compact"><div class="container"><div class="filter-row"><a href="/events?date=today">Today</a><a href="/events?date=tomorrow">Tomorrow</a><a href="/events?date=weekend">This weekend</a><a href="/events?date=month">This month</a><a href="/category/concerts-2">Concerts</a><a href="/category/theatre-3">Theatre</a></div></div></section>
    ${cardSection('Recommended Attractions', '/attractions', activitiesData.activities || [], 'activity')}
    ${cardSection('Live Events in Dubai', '/events', eventsData.performances || [], 'event')}
    ${cardSection('Popular Events Worldwide', '/events', globalEventsData.performances || [], 'event', 'dark')}
    <section class="section-band muted"><div class="container split-section"><div><p class="eyebrow">Browse by category</p><h2>Concerts, theatre, sports and experiences</h2></div><div class="tag-grid">${categories}</div></div></section>`);
}

async function listing(url, type, categoryId = null) {
  const q = url.searchParams.get('q') || '';
  const date = url.searchParams.get('date') || 'upcoming';
  const page = Number(url.searchParams.get('page') || 1);
  let data;
  let title;
  if (type === 'event') {
    data = await api('/v1/performances', { limit: 24, page, is_sellable: true, city_id: DEFAULT_CITY_ID, performance: q, category_id: categoryId, ...dateParams(date) });
    title = `${q || 'Upcoming'} events in Dubai`;
    return layout(`${title} | ${SITE_NAME}`, 'Browse live event tickets in Dubai.', `<section class="listing-hero"><div class="container"><p class="eyebrow">Live inventory</p><h1>${esc(title)}</h1><form class="listing-toolbar" action="/events" method="get"><input type="search" name="q" value="${esc(q)}" placeholder="Search performer or event"><select name="date"><option value="upcoming">Upcoming</option><option value="month">This month</option><option value="today">Today</option><option value="tomorrow">Tomorrow</option><option value="weekend">This weekend</option></select><button type="submit">Search</button></form><div class="result-count">${esc(data.total_count || 0)} results</div></div></section>${grid(data.performances || [], 'event')}`);
  }
  data = await api('/v1/activities', { limit: 24, page, city_id: DEFAULT_CITY_ID, query: q });
  title = q ? `${q} tickets in Dubai` : 'Attractions and experiences in Dubai';
  return layout(`${title} | ${SITE_NAME}`, 'Compare Dubai attractions, tours and experiences.', `<section class="listing-hero"><div class="container"><p class="eyebrow">Experiences</p><h1>${esc(title)}</h1><form class="listing-toolbar" action="/attractions" method="get"><input type="search" name="q" value="${esc(q)}" placeholder="Search attraction or tour"><button type="submit">Search</button></form><div class="result-count">${esc(data.total_count || 0)} results</div></div></section>${grid(data.activities || [], 'activity')}`);
}

async function eventDetail(id) {
  const data = await api(`/v1/performances/${id}`, {}, 900);
  const item = data.performance || data;
  return layout(`${item.name} Tickets | ${SITE_NAME}`, `See dates, venue and ticket prices for ${item.name}.`, `
    <section class="detail-hero" style="--detail-image:url('${esc(image(item, 'event'))}')">
      <div class="container">
        <div class="detail-header">
          <p class="eyebrow">${esc(item.category?.name || 'Event')}</p>
          <h1>${esc(item.name)}</h1>
          <div class="detail-facts">
            <span>
              <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
              4.9 rating
            </span>
            <span>${esc(item.venue?.city || 'Dubai')}</span>
            <span>${esc(item.venue?.name || 'Venue TBA')}</span>
          </div>
        </div>

        <div class="detail-gallery" style="background-image:url('${esc(image(item, 'event'))}')"></div>

        <div class="detail-grid">
          <div class="detail-content">
            <h2>Event details</h2>
            <dl class="detail-list">
              <div><dt>Date</dt><dd>${esc(formatDate(item.start_date))}</dd></div>
              <div><dt>Venue</dt><dd>${esc(item.venue?.name || 'Venue TBA')}</dd></div>
              <div><dt>Address</dt><dd>${esc(`${item.venue?.address || ''}, ${item.venue?.city || ''}`)}</dd></div>
            </dl>
          </div>

          <aside class="checkout-panel">
            <span class="price-label">Tickets From</span>
            <strong>${esc(money(item.price_range?.min_price, item.price_range?.currency))}</strong>
            <a class="button-link wide" href="${goUrl(item, 'event')}" rel="sponsored nofollow">Find Tickets</a>
            <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
          </aside>
        </div>
      </div>
    </section>`);
}

async function activityDetail(id) {
  const item = await api(`/v1/activities/${id}`, {}, 1800);
  const rating = item.reviews?.avg_rating ? Number(item.reviews.avg_rating).toFixed(1) : '4.8';
  return layout(`${item.title} | ${SITE_NAME}`, `Book ${item.title} with current prices and reviews.`, `
    <section class="detail-hero" style="--detail-image:url('${esc(image(item, 'activity'))}')">
      <div class="container">
        <div class="detail-header">
          <p class="eyebrow">${esc(item.city?.name || 'Experience')}</p>
          <h1>${esc(item.title)}</h1>
          <div class="detail-facts">
            <span>
              <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
              ${rating} rating
            </span>
            <span>${esc(item.supplier_name || 'Ticket partner')}</span>
          </div>
        </div>

        <div class="detail-gallery" style="background-image:url('${esc(image(item, 'activity'))}')"></div>

        <div class="detail-grid">
          <div class="detail-content">
            <h2>Experience details</h2>
            <dl class="detail-list">
              <div><dt>City</dt><dd>${esc(item.city?.name || '')}</dd></div>
              <div><dt>Supplier</dt><dd>${esc(item.supplier_name || 'Ticket partner')}</dd></div>
              <div><dt>Cancellation</dt><dd>${esc(String(item.cancellation_policy || 'Check partner checkout for policy.').replace(/<[^>]+>/g, ''))}</dd></div>
            </dl>
          </div>

          <aside class="checkout-panel">
            <span class="price-label">Tickets From</span>
            <strong>${esc(money(item.from_price, item.currency))}</strong>
            <a class="button-link wide" href="${goUrl(item, 'activity')}" rel="sponsored nofollow">Check Availability</a>
            <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
          </aside>
        </div>
      </div>
    </section>`);
}

async function handle(req, res) {
  try {
    const url = new URL(req.url, `http://127.0.0.1:${PORT}`);
    if (url.pathname.startsWith('/assets/')) {
      const file = path.join(__dirname, url.pathname);
      const type = file.endsWith('.css') ? 'text/css' : 'application/javascript';
      res.writeHead(200, { 'content-type': `${type}; charset=utf-8` });
      res.end(await readFile(file));
      return;
    }
    if (url.pathname === '/go') {
      const destination = fromB64url(url.searchParams.get('u') || '');
      if (!/^https:\/\/www\.hellotickets\./.test(destination)) throw new Error('Bad outbound URL');
      const redirect = `${IMPACT_URL}${IMPACT_URL.includes('?') ? '&' : '?'}${new URLSearchParams({ u: destination, subId1: `${url.searchParams.get('type') || 'ticket'}-${url.searchParams.get('id') || 0}` })}`;
      res.writeHead(302, { location: redirect, 'x-robots-tag': 'noindex, nofollow' });
      res.end();
      return;
    }
    let html;
    if (url.pathname === '/') html = await home();
    else if (url.pathname === '/events') html = await listing(url, 'event');
    else if (url.pathname === '/attractions' || url.pathname === '/search') html = await listing(url, url.searchParams.get('type') === 'events' ? 'event' : 'activity');
    else if (url.pathname.startsWith('/event/')) html = await eventDetail(idFromSlug(url.pathname.split('/').pop()));
    else if (url.pathname.startsWith('/activity/')) html = await activityDetail(idFromSlug(url.pathname.split('/').pop()));
    else if (url.pathname.startsWith('/category/')) html = await listing(url, [1, 2, 3].includes(idFromSlug(url.pathname.split('/').pop())) ? 'event' : 'activity', idFromSlug(url.pathname.split('/').pop()));
    else if (url.pathname.startsWith('/city/')) html = await home();
    else if (url.pathname === '/sitemap.xml') {
      res.writeHead(200, { 'content-type': 'application/xml; charset=utf-8' });
      res.end(`<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>http://127.0.0.1:${PORT}/</loc></url><url><loc>http://127.0.0.1:${PORT}/events</loc></url><url><loc>http://127.0.0.1:${PORT}/attractions</loc></url></urlset>`);
      return;
    } else {
      res.writeHead(404, { 'content-type': 'text/html; charset=utf-8' });
      res.end(layout(`Not found | ${SITE_NAME}`, 'Not found', '<section class="section-band"><div class="container"><div class="empty-state"><h1>Page not found</h1><p>Try the home page.</p><a class="button-link" href="/">Back home</a></div></div></section>'));
      return;
    }
    res.writeHead(200, { 'content-type': 'text/html; charset=utf-8' });
    res.end(html);
  } catch (error) {
    console.error(error);
    res.writeHead(500, { 'content-type': 'text/html; charset=utf-8' });
    res.end(layout(`Server error | ${SITE_NAME}`, 'Server error', `<section class="section-band"><div class="container"><div class="empty-state"><h1>Could not load tickets</h1><p>${esc(error.message)}</p></div></div></section>`));
  }
}

http.createServer(handle).listen(PORT, '127.0.0.1', () => {
  console.log(`Preview server running at http://127.0.0.1:${PORT}`);
});
