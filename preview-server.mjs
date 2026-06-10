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
// UAE pair is hardcoded; all other market cities are derived from
// src/destinations-content.json in loadDestinationsContent() — the same single
// source of truth that src/config.php uses. "featured" controls picker/modal.
const MARKET_CITIES = [
  { id: 132, name: 'Dubai', country: 'United Arab Emirates', code: 'ARE', featured: true },
  { id: 256, name: 'Abu Dhabi', country: 'United Arab Emirates', code: 'ARE', featured: true },
];
const featuredCities = () => MARKET_CITIES.filter(c => c.featured);
const cityPath = city => `/city/${idSlugSafe(city.name, city.id)}`;
function idSlugSafe(name, id) {
  return `${String(name).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')}-${id}`;
}
function activeCityId(req) {
  const match = String(req.headers.cookie || '').match(/(?:^|;\s*)tb_city=(\d+)/);
  const id = match ? Number(match[1]) : 0;
  return MARKET_CITIES.some(c => c.id === id) ? id : DEFAULT_CITY_ID;
}
function cityForId(id) {
  return MARKET_CITIES.find(c => c.id === id) || MARKET_CITIES[0];
}
const cacheDir = path.join(__dirname, 'storage', 'cache-preview');

const fallbackImages = {
  hero: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1800&q=80',
  event: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80',
  Concerts: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1000&q=80',
  Theatre: 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=1000&q=80',
  Sports: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=1000&q=80',
  burj: 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1000&q=80',
  waterpark: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
  desert: 'https://images.unsplash.com/photo-1451337516015-6b6e9a44a8a3?auto=format&fit=crop&w=1000&q=80',
  aquarium: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
  cruise: 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1000&q=80',
  museum: 'https://images.unsplash.com/photo-1554907984-15263bfd63bd?auto=format&fit=crop&w=1000&q=80',
  mosque: 'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1000&q=80',
  mall: 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=1000&q=80',
  garden: 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=1000&q=80',
  helicopter: 'https://images.unsplash.com/photo-1474302770737-173ee21bab63?auto=format&fit=crop&w=1000&q=80',
  dinner: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1000&q=80',
};

// Location-neutral fallbacks for activities whose API listing returns no image
// (used across all city/country pages, so deliberately no Dubai/UAE landmarks).
const activityFallbacks = [
  'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=1000&q=80',
  'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=1000&q=80',
  'https://images.unsplash.com/photo-1597659840241-37e2b7c6e922?auto=format&fit=crop&w=1000&q=80',
  'https://images.unsplash.com/photo-1526495124232-a04e1849168c?auto=format&fit=crop&w=1000&q=80',
  'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
];

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
    burj: ['burj', 'khalifa', 'dubai frame', 'skyscraper', 'at the top', 'observation'],
    waterpark: ['waterpark', 'aquaventure', 'aqua', 'water park', 'wild wadi', 'splash'],
    desert: ['desert', 'safari', 'dune', 'camel', 'bedouin'],
    aquarium: ['aquarium', 'underwater', 'dolphin', 'seal', 'shark', 'sea life'],
    cruise: ['cruise', 'boat', 'yacht', 'marina', 'dhow', 'sailing'],
    museum: ['museum', 'gallery', 'exhibition', 'art ', 'heritage'],
    mosque: ['mosque', 'grand mosque', 'sheikh zayed'],
    mall: ['mall', 'shopping', 'souk', 'bazaar', 'market'],
    garden: ['garden', 'miracle', 'flower', 'park', 'butterfly', 'glow'],
    helicopter: ['helicopter', 'sky', 'flying', 'aerial', 'seaplane'],
    dinner: ['dinner', 'dining', 'food tour', 'culinary', 'restaurant', 'brunch'],
  };
  for (const [key, needles] of Object.entries(keywordImages)) {
    if (needles.some(needle => name.includes(needle))) return fallbackImages[key];
  }
  if (item.category?.name && fallbackImages[item.category.name]) return fallbackImages[item.category.name];
  if (type === 'activity' && item.id) return activityFallbacks[item.id % activityFallbacks.length];
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

function layout(title, description, body, activeCity = MARKET_CITIES[0], meta = {}) {
  return `<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>${esc(title)}</title>
  <meta name="description" content="${esc(description)}">
  ${meta.robots ? `<meta name="robots" content="${esc(meta.robots)}">` : ''}
  <meta property="og:title" content="${esc(title)}">
  <meta property="og:description" content="${esc(description)}">
  <meta property="og:type" content="website">
  <meta property="og:image" content="${esc(meta.image || fallbackImages.hero)}">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
      <div class="city-picker" data-city-picker>
        <button class="header-city" type="button" data-city-toggle aria-haspopup="true">${esc(activeCity.name)}</button>
        <div class="city-menu" data-city-menu>
          <button class="city-detect" type="button" data-city-detect>Detect my location</button>
          ${featuredCities().map(c => `<a href="${cityPath(c)}" data-city-id="${c.id}">${esc(c.name)}<span>${esc(c.code)}</span></a>`).join('')}
        </div>
      </div>
      <a class="header-cta" href="/attractions">Get Tickets</a>
      <button class="nav-toggle" type="button" data-nav-toggle aria-label="Open menu"><span></span><span></span><span></span></button>
    </div>
  </header>
  <div class="site-subnav">
    <div class="container">
      <nav class="site-nav" data-nav><a href="/events">Events</a><a href="/attractions">Attractions</a><a href="/dubai">Dubai</a><a href="/abu-dhabi">Abu Dhabi</a><a href="/usa">USA</a><a href="/canada">Canada</a><a href="/uk">UK</a><a href="/italy">Italy</a><a href="/spain">Spain</a><a href="/france">France</a><a href="/category/concerts-2">Concerts</a><a href="/category/theatre-3">Theatre</a><a href="/category/sports-1">Sports</a></nav>
      <div class="subnav-side">${featuredCities().slice(0, 3).map(c => `<a href="${cityPath(c)}">${esc(c.name)}</a>`).join('')}</div>
    </div>
  </div>
  <main>${body}</main>
  <footer class="site-footer">
    <div class="footer-partner">
      <div class="container">
        <p>Got an event, activity or experience? <strong>Partner with us &amp; get listed on ${esc(SITE_NAME)}</strong></p>
        <a class="footer-partner-btn" href="/contact">Contact Us</a>
      </div>
    </div>
    <div class="container">
      <div class="footer-care">
        <div>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
          24/7 Partner Support
        </div>
        <div>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path><line x1="13" y1="5" x2="13" y2="7"></line><line x1="13" y1="11" x2="13" y2="13"></line><line x1="13" y1="17" x2="13" y2="19"></line></svg>
          Live Prices &amp; Availability
        </div>
        <div>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
          Secure Partner Checkout
        </div>
      </div>
      <div class="footer-cols">
        <div>
          <h4>Destinations</h4>
          <a href="/dubai">Dubai</a><a href="/abu-dhabi">Abu Dhabi</a><a href="/usa">United States</a><a href="/canada">Canada</a><a href="/uk">United Kingdom</a><a href="/italy">Italy</a><a href="/spain">Spain</a><a href="/france">France</a>
        </div>
        <div>
          <h4>Categories</h4>
          <a href="/category/concerts-2">Concerts</a><a href="/category/theatre-3">Theatre</a><a href="/category/sports-1">Sports</a><a href="/attractions">Attractions &amp; Tours</a>
        </div>
        <div>
          <h4>Discover</h4>
          <a href="/events">All Events</a><a href="/events?date=weekend">This Weekend</a><a href="/search">Search Tickets</a><a href="/about">About Us</a><a href="/contact">Contact</a><a href="/how-we-make-money">How We Make Money</a><a href="/sitemap.xml">Sitemap</a>
        </div>
      </div>
      <div class="footer-bottom">
        <strong><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 22px; height: 22px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></svg>Ticked<em style="font-style: normal; color: var(--red);">Bus</em></strong>
        <p>Your guide to events, attractions and experiences in Dubai and top destinations worldwide. Prices and availability are live from our ticket partner, and checkout is completed securely on their site. We may earn a commission on bookings at no extra cost to you. &copy; ${new Date().getFullYear()} ${esc(SITE_NAME)}. All events, images and trademarks belong to their respective owners.</p>
      </div>
    </div>
  </footer>
  <div class="city-modal" data-city-modal data-default-city="${DEFAULT_CITY_ID}" hidden>
    <div class="city-modal-box">
      <h3>Where do you want tickets?</h3>
      <p>Pick your city to see local events, attractions and live prices.</p>
      <button class="city-detect-big" type="button" data-city-detect>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        Detect my location
      </button>
      <div class="city-modal-grid">
        ${featuredCities().map(c => `<a href="${cityPath(c)}" data-city-id="${c.id}"><strong>${esc(c.name)}</strong><span>${esc(c.country)}</span></a>`).join('')}
      </div>
      <button class="city-modal-close" type="button" data-city-close>Maybe later</button>
    </div>
  </div>
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

  const cardHref = item.url ? goUrl(item, 'event') : eventPath(item);
  return `<article class="ticket-card">
    <a class="card-image" href="${cardHref}" rel="sponsored nofollow">
      <img src="${esc(image(item, 'event'))}" alt="${esc(item.name || 'Event')}" loading="lazy">
      <div class="card-date-badge">
        <span class="month">${esc(monthAbbr)}</span>
        <span class="day">${esc(dayNum)}</span>
      </div>
      <div class="card-rating-strip">
        <span class="votes">${esc(item.category?.name || 'Event')}</span>
      </div>
    </a>
    <div class="card-body">
      <a class="card-title" href="${cardHref}" rel="sponsored nofollow">${esc(item.name || 'Event')}</a>
      <p>${esc(formatDate(item.start_date))}</p>
      <p>${esc([item.venue?.name, item.venue?.city || 'Dubai'].filter(Boolean).join(', '))}</p>
      <p class="card-onwards">${esc(money(item.price_range?.min_price, item.price_range?.currency))}${Number(item.price_range?.min_price || 0) > 0 ? ' onwards' : ''}</p>
    </div>
  </article>`;
}

function activityCard(item) {
  const rating = item.reviews?.avg_rating ? Number(item.reviews.avg_rating).toFixed(1) : null;
  const reviewsCount = item.reviews?.number_of_reviews ? Number(item.reviews.number_of_reviews) : null;
  const cardHref = item.url ? goUrl(item, 'activity') : activityPath(item);
  return `<article class="ticket-card">
    <a class="card-image" href="${cardHref}" rel="sponsored nofollow">
      <img src="${esc(image(item, 'activity'))}" alt="${esc(item.title || 'Experience')}" loading="lazy">
      <span class="category">${esc(item.city?.name || 'Attraction')}</span>
      ${rating !== null ? `<div class="card-rating-strip">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
        ${rating}/5
        ${reviewsCount !== null ? `<span class="votes">${new Intl.NumberFormat('en-US').format(reviewsCount)} votes</span>` : ''}
      </div>` : ''}
    </a>
    <div class="card-body">
      <a class="card-title" href="${cardHref}" rel="sponsored nofollow">${esc(item.title || 'Experience')}</a>
      <p>${esc(item.supplier_name || 'Ticket partner')}</p>
      <p class="card-onwards">${esc(money(item.from_price, item.currency))}${Number(item.from_price || 0) > 0 ? ' onwards' : ''}</p>
    </div>
  </article>`;
}

function cardSection(heading, href, items, type, variant = '') {
  if (!items?.length) return '';
  const cards = items.map(type === 'event' ? eventCard : activityCard).join('');
  return `<section class="section-band${variant ? ` ${variant}` : ''}"><div class="container"><div class="section-heading"><h2>${esc(heading)}</h2><a href="${href}">See All</a></div><div class="rail-wrapper"><button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button><div class="rail">${cards}</div><button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button></div></div></section>`;
}

function grid(items, type, cityName = 'Dubai') {
  const cards = items.map(type === 'event' ? eventCard : activityCard).join('');
  return `<section class="section-band"><div class="container">${cards ? `<div class="card-grid">${cards}</div>` : `<div class="empty-state"><h2>No tickets found</h2><p>Try a broader search or browse all ${esc(cityName)} listings.</p><a class="button-link" href="${type === 'event' ? '/events' : '/attractions'}">See All</a></div>`}</div></section>`;
}

function promoBanner() {
  return `<section class="promo-band"><div class="container"><div class="promo-banner"><div class="promo-copy"><span class="promo-kicker">Why ${esc(SITE_NAME)}</span><h2>Real tickets, instant delivery.</h2><p>Live prices, instant e-tickets and free cancellation on most experiences &mdash; booked securely through our official ticket partner.</p></div><a class="promo-btn" href="/attractions">Browse experiences</a></div></div></section>`;
}

function liveEventsBand() {
  const cards = [
    { title: 'Concerts & Gigs', sub: 'Live music nights', href: '/category/concerts-2', image: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=800&q=80' },
    { title: 'Theatre & Arts', sub: 'Plays & musicals', href: '/category/theatre-3', image: 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=800&q=80' },
    { title: 'Sports', sub: 'Matchday action', href: '/category/sports-1', image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80' },
    { title: 'Desert Safari', sub: 'Dunes & dinners', href: '/attractions?q=Desert%20Safari', image: 'https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?auto=format&fit=crop&w=800&q=80' },
    { title: 'Theme Parks', sub: 'Rides & waterparks', href: '/attractions?q=Theme%20Park', image: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80' },
    { title: 'Cruises', sub: 'Marina & dhow', href: '/attractions?q=Cruise', image: 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80' },
  ];
  return `<section class="live-band"><div class="container"><div class="section-heading"><h2>Browse by Category</h2><a href="/attractions">See All</a></div><div class="cat-grid">${cards.map(card => `<a class="cat-tile" href="${card.href}"><img src="${esc(card.image)}" alt="${esc(card.title)}" loading="lazy"><span class="cat-tile__body"><span class="cat-tile__title">${esc(card.title)}</span><span class="cat-tile__sub">${esc(card.sub)}</span></span></a>`).join('')}</div></div></section>`;
}

async function home(cityId = DEFAULT_CITY_ID) {
  const city = cityForId(cityId);
  const [eventsData, activitiesData] = await Promise.all([
    api('/v1/performances', { limit: 12, page: 1, is_sellable: true, city_id: cityId, ...dateParams() }),
    api('/v1/activities', { limit: 12, page: 1, city_id: cityId }),
  ]);
  const events = eventsData.performances || [];
  const activities = activitiesData.activities || [];
  let globalEvents = [];
  if (events.length < 6) {
    try { globalEvents = (await api('/v1/performances', { limit: 12, page: 1, is_sellable: true, ...dateParams() })).performances || []; } catch {}
  }
  // Never repeat a performance across the local and worldwide rails.
  const seenIds = events.map(p => Number(p.id || 0));
  globalEvents = globalEvents.filter(p => !seenIds.includes(Number(p.id || 0)));
  const homeCountries = Object.values(destinationsContent.countries || {});
  return layout(`${city.name} Events, Attractions & Tickets | ${SITE_NAME}`, `Find ${city.name} attraction tickets, concerts, theatre, sports and experiences with live prices from HelloTickets.`, `
    <h1 class="visually-hidden">${esc(city.name)} Events, Attractions &amp; Tickets</h1>
    <section class="hero">
      <div class="container">
        <div class="carousel" data-carousel>
          <div class="carousel-track" data-carousel-track>
            ${[
              { image: fallbackImages.hero, tag: 'Featured', title: 'Dubai events, attractions and experiences', text: 'Live prices and availability, with secure partner checkout.', href: '/attractions', cta: 'Book Now' },
              { image: fallbackImages.burj, tag: 'Top Attraction', title: 'Burj Khalifa: At the Top', text: "Skip the queue with instant e-tickets to the world's tallest tower.", href: '/attractions?q=Burj%20Khalifa', cta: 'Get Tickets' },
              { image: fallbackImages.desert, tag: 'Experiences', title: 'Desert safaris and dune adventures', text: 'Sunset drives, camel rides and Bedouin dinners under the stars.', href: '/attractions?q=Desert%20Safari', cta: 'Explore' },
              { image: fallbackImages.Concerts, tag: 'Live Events', title: 'Concerts, theatre and sport in Dubai', text: "See what's playing this week across the city's biggest venues.", href: '/events', cta: 'See Events' },
            ].map(slide => `<div class="carousel-slide" style="background-image: url('${slide.image}')">
              <div class="carousel-caption">
                <span class="slide-tag">${esc(slide.tag)}</span>
                <h2>${esc(slide.title)}</h2>
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
    <section class="section-band compact"><div class="container"><div class="filter-row"><a href="/events?date=today">Today</a><a href="/events?date=tomorrow">Tomorrow</a><a href="/events?date=weekend">This Weekend</a><a href="/events?date=month">This Month</a></div></div></section>
    ${cardSection(`Recommended in ${city.name}`, '/attractions', activities, 'activity')}
    ${cardSection(`Live Events in ${city.name}`, '/events', events, 'event')}
    ${liveEventsBand()}
    ${globalEvents.length >= 4 ? cardSection('Popular Events Worldwide', '/events', globalEvents, 'event') : ''}
    ${promoBanner()}
    ${homeCountries.length ? `<section class="section-band"><div class="container"><div class="section-heading"><h2>Explore by Destination</h2><a href="/attractions">See All</a></div><div class="home-destinations__grid">${homeCountries.map(c => `<a class="home-destinations__card" href="/${esc(c.slug)}" style="background-image: linear-gradient(180deg, rgba(0,0,0,.1) 0%, rgba(0,0,0,.7) 100%), url('${esc(c.hero_image || fallbackImages.hero)}')">${esc(c.name)}</a>`).join('')}</div></div></section>` : ''}
    <section class="section-band"><div class="container split-section"><div><p class="eyebrow">Browse by destination</p><h2>Popular Ticket Cities</h2></div><div class="city-grid">${featuredCities().slice(0, 8).map(c => `<a href="${cityPath(c)}"><strong>${esc(c.name)}</strong><span>${esc(c.country)}</span></a>`).join('')}</div></div></section>`, city);
}

async function listing(url, type, categoryId = null, cityId = DEFAULT_CITY_ID) {
  const q = url.searchParams.get('q') || '';
  const date = url.searchParams.get('date') || 'upcoming';
  const page = Number(url.searchParams.get('page') || 1);
  const city = cityForId(cityId);
  let data;
  let title;
  if (type === 'event') {
    data = await api('/v1/performances', { limit: 24, page, is_sellable: true, city_id: cityId, performance: q, category_id: categoryId, ...dateParams(date) });
    title = `${q || 'Upcoming'} events in ${city.name}`;
    return layout(`${title} | ${SITE_NAME}`, `Browse live event tickets in ${city.name} with dates, venues and prices.`, `<section class="listing-hero"><div class="container"><p class="eyebrow">Live inventory</p><h1>${esc(title)}</h1><form class="listing-toolbar" action="/events" method="get"><input type="search" name="q" value="${esc(q)}" placeholder="Search performer or event"><select name="date" aria-label="Date"><option value="upcoming">Upcoming</option><option value="month">This month</option><option value="today">Today</option><option value="tomorrow">Tomorrow</option><option value="weekend">This weekend</option></select><button type="submit">Search</button></form><div class="result-count">${new Intl.NumberFormat('en-US').format(Number(data.total_count || 0))} results</div></div></section>${grid(data.performances || [], 'event', city.name)}`, city);
  }
  data = await api('/v1/activities', { limit: 24, page, city_id: cityId, query: q });
  title = q ? `${q} tickets in ${city.name}` : `Attractions and experiences in ${city.name}`;
  return layout(`${title} | ${SITE_NAME}`, `Compare ${city.name} attractions, tours and experiences with current prices and partner checkout.`, `<section class="listing-hero"><div class="container"><p class="eyebrow">Experiences</p><h1>${esc(title)}</h1><form class="listing-toolbar" action="/attractions" method="get"><input type="search" name="q" value="${esc(q)}" placeholder="Search attraction or tour"><button type="submit">Search</button></form><div class="result-count">${new Intl.NumberFormat('en-US').format(Number(data.total_count || 0))} results</div></div></section>${grid(data.activities || [], 'activity', city.name)}`, city);
}

async function searchPage(url, cityId = DEFAULT_CITY_ID) {
  const q = (url.searchParams.get('q') || '').trim();
  const city = cityForId(cityId);
  const cityName = city.name;
  let events = [];
  let activities = [];
  if (q !== '') {
    try { events = (await api('/v1/performances', { limit: 8, page: 1, is_sellable: true, city_id: cityId, performance: q, ...dateParams() })).performances || []; } catch {}
    try { activities = (await api('/v1/activities', { limit: 8, page: 1, city_id: cityId, query: q })).activities || []; } catch {}
  }
  return layout(`${q !== '' ? `Search tickets for ${q}` : `Search Tickets in ${cityName}`} | ${SITE_NAME}`, `Search ${cityName} events, attractions and experiences.`, `
    <section class="listing-hero">
      <div class="container">
        <p class="eyebrow">Search</p>
        <h1>${q !== '' ? `Tickets for "${esc(q)}"` : `Search Tickets in ${esc(cityName)}`}</h1>
        <form class="listing-toolbar" action="/search" method="get">
          <input type="search" name="q" value="${esc(q)}" placeholder="Search ${esc(cityName)} tickets">
          <select name="type" aria-label="Search type"><option value="all">All tickets</option><option value="events">Events</option><option value="attractions">Attractions</option></select>
          <button type="submit">Search</button>
        </form>
      </div>
    </section>
    ${cardSection('Events', `/events?q=${encodeURIComponent(q)}`, events, 'event')}
    ${cardSection('Attractions', `/attractions?q=${encodeURIComponent(q)}`, activities, 'activity')}
    ${(q === '' || (!events.length && !activities.length)) ? `<section class="section-band"><div class="container"><div class="empty-state"><h2>No matches yet</h2><p>Browse ${esc(cityName)} attractions to see current inventory.</p><a class="button-link" href="/attractions">View attractions</a></div></div></section>` : ''}`, city, { robots: 'noindex, follow' });
}

async function eventDetail(id, activeCity) {
  const data = await api(`/v1/performances/${id}`, {}, 900);
  const item = data.performance || data;
  const heroImage = image(item, 'event');
  const localDate = String(item.start_date?.local_date || '');
  const isPast = localDate !== '' && localDate < new Date().toISOString().slice(0, 10);
  const crumbs = breadcrumb([
    { name: 'Home', url: '/' },
    { name: 'Events', url: '/events' },
    { name: item.name || 'Event', url: eventPath(item) },
  ]);
  let related = [];
  try {
    const categoryId = Number(item.category?.id || 0);
    related = (await api('/v1/performances', { limit: 8, page: 1, is_sellable: true, category_id: categoryId || undefined, ...dateParams() })).performances || [];
  } catch {}
  related = related.filter(p => Number(p.id || 0) !== Number(item.id));
  return layout(`${item.name} Tickets | ${SITE_NAME}`, `See dates, venue and ticket prices for ${item.name} in ${item.venue?.city || 'Dubai'}.`, `
    <section class="detail-hero" style="--detail-image:url('${esc(heroImage)}')">
      <div class="container">
        ${crumbs}
        <div class="detail-header">
          <p class="eyebrow">${esc(item.category?.name || 'Event')}</p>
          <h1>${esc(item.name)}</h1>
          <div class="detail-facts">
            <span>${esc(item.venue?.city || 'Dubai')}</span>
            <span>${esc(item.venue?.name || 'Venue TBA')}</span>
          </div>
        </div>

        <div class="detail-gallery" style="background-image:url('${esc(heroImage)}')"></div>

        <div class="detail-grid">
          <div class="detail-content">
            <h2>Event details</h2>
            <dl class="detail-list">
              <div><dt>Date</dt><dd>${esc(formatDate(item.start_date))}</dd></div>
              <div><dt>Venue</dt><dd>${esc(item.venue?.name || 'Venue TBA')}</dd></div>
              <div><dt>Address</dt><dd>${esc(`${item.venue?.address || ''}, ${item.venue?.city || ''}`)}</dd></div>
              <div><dt>Category</dt><dd>${esc(item.category?.name || 'Event')}</dd></div>
            </dl>
            ${(item.performers || []).length ? `<h2>Performers</h2><div class="tag-grid compact-tags">${(item.performers || []).map(p => `<span>${esc(p.name || '')}</span>`).join('')}</div>` : ''}
          </div>

          <aside class="checkout-panel">
            <span class="price-label">Tickets From</span>
            <strong>${esc(money(item.price_range?.min_price, item.price_range?.currency))}</strong>
            <a class="button-link wide" href="${goUrl(item, 'event')}" rel="sponsored nofollow">Find Tickets</a>
            <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
          </aside>
        </div>
      </div>
    </section>
    ${cardSection('More Events', '/events', related, 'event')}`, activeCity, { image: heroImage, robots: isPast ? 'noindex, follow' : null });
}

async function activityDetail(id, activeCity) {
  const item = await api(`/v1/activities/${id}`, {}, 1800);
  const heroImage = image(item, 'activity');
  const rating = item.reviews?.avg_rating ? Number(item.reviews.avg_rating).toFixed(1) : null;
  const crumbs = breadcrumb([
    { name: 'Home', url: '/' },
    { name: 'Attractions', url: '/attractions' },
    { name: item.title || 'Experience', url: activityPath(item) },
  ]);
  let related = [];
  try { related = (await api('/v1/activities', { limit: 8, page: 1, city_id: Number(item.city?.id || DEFAULT_CITY_ID) })).activities || []; } catch {}
  related = related.filter(a => Number(a.id || 0) !== Number(item.id));
  return layout(`${item.title} | ${SITE_NAME}`, `Book ${item.title} with current prices, reviews and available dates.`, `
    <section class="detail-hero" style="--detail-image:url('${esc(heroImage)}')">
      <div class="container">
        ${crumbs}
        <div class="detail-header">
          <p class="eyebrow">${esc(item.city?.name || 'Experience')}</p>
          <h1>${esc(item.title)}</h1>
          <div class="detail-facts">
            ${rating !== null ? `<span>
              <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
              ${rating} rating
            </span>` : ''}
            <span>${esc(item.supplier_name || 'Ticket partner')}</span>
            ${item.duration ? `<span>${esc(item.duration)}</span>` : ''}
          </div>
        </div>

        <div class="detail-gallery" style="background-image:url('${esc(heroImage)}')"></div>

        <div class="detail-grid">
          <div class="detail-content">
            <h2>Experience details</h2>
            <dl class="detail-list">
              <div><dt>City</dt><dd>${esc(item.city?.name || '')}</dd></div>
              <div><dt>Supplier</dt><dd>${esc(item.supplier_name || 'Ticket partner')}</dd></div>
              <div><dt>Cancellation</dt><dd>${esc(String(item.cancellation_policy || 'Check partner checkout for policy.').replace(/<[^>]+>/g, ''))}</dd></div>
            </dl>
            <h2>Upcoming dates</h2>
            <div class="date-grid">
              <p style="grid-column: 1 / -1; color: var(--muted); font-weight: 500;">Dates are confirmed during checkout.</p>
            </div>
          </div>

          <aside class="checkout-panel">
            <span class="price-label">Tickets From</span>
            <strong>${esc(money(item.from_price, item.currency))}</strong>
            <a class="button-link wide" href="${goUrl(item, 'activity')}" rel="sponsored nofollow">Check Availability</a>
            <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
          </aside>
        </div>
      </div>
    </section>
    ${cardSection(`More Attractions in ${item.city?.name || 'Dubai'}`, '/attractions', related, 'activity')}`, activeCity, { image: heroImage });
}

// ---------------------------------------------------------------------------
// Dubai content data (loaded from file when available, or minimal fallback)
// ---------------------------------------------------------------------------

let dubaiContent = { categories: [], attractions: [], hub_faqs: [] };
const dubaiContentPath = path.join(__dirname, 'src', 'dubai-content.json');
const dubaiContentPhpPath = path.join(__dirname, 'src', 'dubai-content.php');

function loadDubaiContent() {
  if (existsSync(dubaiContentPath)) {
    try { dubaiContent = JSON.parse(readFileSync(dubaiContentPath, 'utf8')); } catch {}
  } else if (existsSync(dubaiContentPhpPath)) {
    dubaiContent = { categories: [], attractions: [], hub_faqs: [], _note: 'PHP content file exists but Node mirror not yet generated' };
  }
}
loadDubaiContent();

// ---------------------------------------------------------------------------
// Destination content (countries + cities) — mirrors src/destinations-content.json
// ---------------------------------------------------------------------------
let destinationsContent = { countries: {}, cities: {} };
const destinationsContentPath = path.join(__dirname, 'src', 'destinations-content.json');

function loadDestinationsContent() {
  if (!existsSync(destinationsContentPath)) return;
  try {
    const d = JSON.parse(readFileSync(destinationsContentPath, 'utf8'));
    d.countries = d.countries || {};
    d.cities = d.cities || {};
    for (const slug of Object.keys(d.countries)) {
      const c = d.countries[slug];
      c.slug = c.slug || slug;
      c.cities = (c.city_slugs || []).map(cs => d.cities[cs]).filter(Boolean);
    }
    destinationsContent = d;
    // Rebuild market cities from the pack (keep the hardcoded UAE pair first).
    MARKET_CITIES.length = 2;
    for (const city of Object.values(d.cities)) {
      if (!city.city_id) continue;
      MARKET_CITIES.push({
        id: Number(city.city_id),
        name: String(city.name),
        country: String(city.country || ''),
        code: String(city.country_code || ''),
        featured: !!city.featured,
      });
    }
  } catch {}
}
loadDestinationsContent();

function destParagraphs(intro) {
  if (Array.isArray(intro)) return intro;
  return String(intro || '').split(/\n\n+/).map(s => s.trim()).filter(Boolean);
}

function destDisplayName(name) {
  return ['United States', 'United Kingdom', 'Netherlands', 'Czech Republic', 'United Arab Emirates', 'Philippines'].includes(name) ? 'the ' + name : name;
}

function breadcrumb(items) {
  const parts = items.map((c, i) => i < items.length - 1
    ? `<li><a href="${esc(c.url)}">${esc(c.name)}</a><span aria-hidden="true">/</span></li>`
    : `<li><span aria-current="page">${esc(c.name)}</span></li>`).join('');
  return `<nav class="dubai-breadcrumb" aria-label="Breadcrumb"><ol>${parts}</ol></nav>`;
}

function destFaq(faqs, heading) {
  if (!faqs?.length) return '';
  const items = faqs.map(f => `<details class="dubai-faq__item"><summary><h3>${esc(f.q)}</h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></summary><p>${esc(f.a)}</p></details>`).join('');
  return `<section class="dubai-faq"><div class="container"><h2>${esc(heading)}</h2><div class="dubai-faq__list">${items}</div></div></section>`;
}

async function countryHub(slug) {
  const country = destinationsContent.countries[slug];
  if (!country) return null;
  const cities = country.cities || [];
  const name = country.name || slug;
  const displayName = destDisplayName(name);
  const stats = country.stats || {};
  const heroImg = country.hero_image || fallbackImages.hero;
  const primary = cities[0];
  let topActivities = [];
  if (primary) {
    try { topActivities = (await api('/v1/activities', { limit: 8, page: 1, city_id: primary.city_id })).activities || []; } catch {}
  }
  const crumbs = breadcrumb([{ name: 'Home', url: '/' }, { name, url: '/' + slug }]);
  const statLabels = { attractions: 'Attractions & Tours', price_from: 'Prices From', support: 'Partner Support' };
  const statHtml = Object.entries(statLabels).filter(([k]) => stats[k]).map(([k, label]) => `<div class="destination-hub__stat"><strong>${esc(String(stats[k]))}</strong><span>${esc(label)}</span></div>`).join('');
  const cityCards = cities.map(c => `<a class="destination-hub__city-card" href="/${esc(slug)}/${esc(c.slug)}" style="background-image: linear-gradient(180deg, rgba(0,0,0,.15) 0%, rgba(0,0,0,.78) 100%), url('${esc(c.hero_image || fallbackImages.hero)}')"><span class="destination-hub__city-name">${esc(c.name)}</span><span class="destination-hub__city-sub">${esc((c.highlights && c.highlights[0]) || 'Tickets, tours & attractions')}</span></a>`).join('');
  const guide = destParagraphs(country.intro).map(p => `<p>${esc(p)}</p>`).join('');
  return layout(country.meta_title || `${name} Tickets, Tours & Attractions | ${SITE_NAME}`, country.meta_description || `Book tickets and tours across ${name}.`, `
    <section class="destination-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.6) 0%, rgba(0,0,0,.25) 100%), url('${esc(heroImg)}')">
      <div class="container">${crumbs}<h1>Things to Do in ${esc(displayName)} &mdash; Tickets, Tours &amp; Attractions</h1><p class="destination-hub__hero-sub">Skip-the-line tickets and instant confirmation for the best experiences in ${esc(displayName)}</p><form class="destination-hub__search" action="/search" method="get"><input type="search" name="q" placeholder="Search attractions, tours, tickets..." aria-label="Search ${esc(name)} attractions"><button type="submit">Search</button></form></div>
    </section>
    ${statHtml ? `<section class="destination-hub__stats"><div class="container"><div class="destination-hub__stats-grid">${statHtml}</div></div></section>` : ''}
    ${cityCards ? `<section class="destination-hub__cities section-band"><div class="container"><div class="section-heading"><h2>Top Destinations in ${esc(displayName)}</h2><a href="/attractions">See All</a></div><div class="destination-hub__city-grid">${cityCards}</div></div></section>` : ''}
    ${(topActivities.length && primary) ? cardSection(`Top Things to Do in ${primary.name}`, `/${slug}/${primary.slug}`, topActivities, 'activity', 'muted') : ''}
    ${guide ? `<section class="destination-hub__guide section-band"><div class="container"><div class="destination-hub__guide-content"><h2>Your Guide to ${esc(displayName)}</h2>${guide}</div></div></section>` : ''}
    <section class="destination-hub__trust section-band muted"><div class="container"><h2>Why Book With ${esc(SITE_NAME)}</h2><div class="destination-hub__trust-grid">
      <div class="destination-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg><h3>Free Cancellation on Many Tickets</h3><p>The exact policy for each ticket is shown at partner checkout before you pay.</p></div>
      <div class="destination-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg><h3>Instant E-Tickets</h3><p>Get your tickets delivered straight to your phone. No printing required, just show and go.</p></div>
      <div class="destination-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg><h3>24/7 Partner Support</h3><p>Our ticket partner's support team is available around the clock for bookings and changes.</p></div>
    </div></div></section>
    ${destFaq(country.faqs, `Frequently Asked Questions About ${displayName}`)}`);
}

async function cityHub(countrySlug, citySlug) {
  const country = destinationsContent.countries[countrySlug];
  const city = destinationsContent.cities[citySlug];
  if (!country || !city || city.country_slug !== countrySlug) return null;
  const cityName = city.name;
  const countryName = country.name;
  const countryDisplay = destDisplayName(countryName);
  const heroImg = city.hero_image || fallbackImages.hero;
  let activities = [], events = [];
  try { activities = (await api('/v1/activities', { limit: 12, page: 1, city_id: city.city_id })).activities || []; } catch {}
  try { events = (await api('/v1/performances', { limit: 12, page: 1, is_sellable: true, city_id: city.city_id, ...dateParams() })).performances || []; } catch {}
  const crumbs = breadcrumb([{ name: 'Home', url: '/' }, { name: countryName, url: '/' + countrySlug }, { name: cityName, url: `/${countrySlug}/${citySlug}` }]);
  const intro = destParagraphs(city.intro).map(p => `<p>${esc(p)}</p>`).join('');
  const highlights = (city.highlights || []).map(h => `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>${esc(h)}</li>`).join('');
  const tips = (city.tips || []).map(t => `<div class="destination-city__tip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><p>${esc(t)}</p></div>`).join('');
  const siblings = (country.cities || []).filter(c => c.slug !== citySlug);
  const siblingLinks = siblings.map(s => `<a class="dubai-hub__link-card" href="/${esc(countrySlug)}/${esc(s.slug)}"><strong>${esc(s.name)}</strong><span>${esc((s.highlights && s.highlights[0]) || 'Tickets & tours')}</span></a>`).join('') + `<a class="dubai-hub__link-card" href="/${esc(countrySlug)}"><strong>All of ${esc(countryName)}</strong><span>Browse every destination</span></a>`;
  return layout(city.meta_title || `${cityName} Tickets, Tours & Attractions | ${SITE_NAME}`, city.meta_description || `Book the best things to do in ${cityName}.`, `
    <section class="destination-city__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.58) 0%, rgba(0,0,0,.25) 100%), url('${esc(heroImg)}')">
      <div class="container">${crumbs}<h1>Things to Do in ${esc(cityName)}</h1><p class="destination-city__hero-sub">Tickets, tours and experiences in ${esc(cityName)}, ${esc(countryName)}</p></div>
    </section>
    ${intro ? `<section class="destination-city__intro section-band"><div class="container"><div class="destination-city__intro-content">${intro}</div></div></section>` : ''}
    ${cardSection(`Top Attractions in ${cityName}`, cityPath({ name: cityName, id: city.city_id }), activities, 'activity')}
    ${cardSection(`Events in ${cityName}`, cityPath({ name: cityName, id: city.city_id }), events, 'event', 'muted')}
    ${highlights ? `<section class="destination-city__highlights section-band"><div class="container"><h2>Highlights</h2><ul class="destination-city__highlights-list">${highlights}</ul></div></section>` : ''}
    ${tips ? `<section class="destination-city__tips section-band muted"><div class="container"><h2>Tips for Visiting ${esc(cityName)}</h2><div class="destination-city__tips-grid">${tips}</div></div></section>` : ''}
    ${destFaq(city.faqs, `Frequently Asked Questions About ${cityName}`)}
    ${siblings.length ? `<section class="destination-city__related section-band"><div class="container"><h2>More Destinations in ${esc(countryDisplay)}</h2><div class="dubai-hub__link-grid">${siblingLinks}</div></div></section>` : ''}`);
}

function categoryIcon(slug) {
  const icons = {
    'burj-khalifa': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 21V6l-3 3"/><path d="M15 21V6l3 3"/><path d="M12 21V3"/><path d="M6 12h12"/></svg>',
    'desert-safari': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20s3-6 8-6 6 4 10 4 4-4 4-4"/><circle cx="18" cy="5" r="3"/><path d="M7 8l2 4"/><path d="M5 10l4 2"/></svg>',
    'waterparks': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 15c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M2 19c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M9 7a3 3 0 1 0 6 0 3 3 0 0 0-6 0"/><path d="M12 10v2"/></svg>',
    'aquarium': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5 0 9-3 9-8 0-4-3-6-5-8-1 3-4 4-4 4s-3-1-4-4c-2 2-5 4-5 8 0 5 4 8 9 8z"/><circle cx="9" cy="15" r="1"/><circle cx="15" cy="15" r="1"/></svg>',
    'dubai-frame': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><rect x="9" y="8" width="6" height="8"/></svg>',
    'cruises': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20c2-1 4-1 6 0s4 1 6 0 4-1 6 0"/><path d="M4 17l2-10h12l2 10"/><path d="M12 7V3"/><path d="M8 7l4-4 4 4"/></svg>',
    'museum-of-the-future': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="12" rx="9" ry="8"/><ellipse cx="12" cy="12" rx="3.5" ry="2.5"/></svg>',
    'theme-parks': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15 15 0 0 1 4 10 15 15 0 0 1-4 10"/><path d="M12 2a15 15 0 0 0-4 10 15 15 0 0 0 4 10"/><path d="M2 12h20"/></svg>',
    'helicopter-tours': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M12 6V3"/><path d="M14 12h4l2 5H4l2-5h4"/><circle cx="12" cy="12" r="2"/><path d="M12 17v3"/><path d="M8 20h8"/></svg>',
    'jet-ski': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M5 14l5-6 3 4h6l-2 3H5z"/></svg>',
    'skydiving': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9a8 8 0 0 1 16 0"/><path d="M4 9l8 9 8-9"/><circle cx="12" cy="21" r="1.5"/></svg>',
    'hot-air-balloon': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 4-3 7-7 7s-7-3-7-7a7 7 0 0 1 7-7z"/><path d="M9 16l1 4h4l1-4"/></svg>',
    'city-tours': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="4" height="11"/><rect x="10" y="3" width="4" height="18"/><rect x="16" y="7" width="4" height="14"/><path d="M2 21h20"/></svg>',
    'night-tours': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>',
    'water-sports': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 19c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M12 15V3"/><path d="M12 3c5 1 7 5 7 9h-7"/></svg>',
    'fountain-show': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21V11"/><path d="M12 11c0-4-3-5-3-8"/><path d="M12 11c0-4 3-5 3-8"/><path d="M5 21h14"/><path d="M5 16c0 1 1 2 2 2"/><path d="M19 16c0 1-1 2-2 2"/></svg>',
    'sky-views': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>',
    'food-tours': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
  };
  return icons[slug] || '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>';
}

async function dubaiHub() {
  const activitiesData = await api('/v1/activities', { limit: 8, page: 1, city_id: DEFAULT_CITY_ID });
  const activities = activitiesData.activities || [];
  const categories = dubaiContent.categories || [];
  const faqs = dubaiContent.hub_faqs || [];
  const heroImg = dubaiContent.hub_hero_image || fallbackImages.hero;
  const crumbs = breadcrumb([{ name: 'Home', url: '/' }, { name: 'Dubai', url: '/dubai' }]);

  const categoryCards = categories.map(cat => `<a class="dubai-hub__category-card" href="/dubai/${esc(cat.slug)}">
    <span class="dubai-hub__category-icon" aria-hidden="true">${categoryIcon(cat.slug)}</span>
    <strong class="dubai-hub__category-title">${esc(cat.short_name || cat.name)}</strong>
    <span class="dubai-hub__category-sub">${esc(cat.subtitle || '')}</span>
    ${cat.activity_count ? `<span class="dubai-hub__category-count">${esc(String(cat.activity_count))}</span>` : ''}
  </a>`).join('');

  const activityCards = activities.map(activityCard).join('');

  const linkCards = (dubaiContent.attractions || []).filter(att => att.slug).map(att => `<a class="dubai-hub__link-card" href="/dubai/${esc(att.category_slug || 'attractions')}/${esc(att.slug)}"><strong>${esc(att.short_name || att.title)}</strong><span>${esc(att.category_short_name || att.category_name || '')}</span></a>`).join('') + `<a class="dubai-hub__link-card" href="/abu-dhabi"><strong>Abu Dhabi Day Trips</strong><span>Louvre, Grand Mosque &amp; more</span></a>`;

  return layout('Things to Do in Dubai — Tickets, Tours & Attractions | ' + SITE_NAME, 'Discover 100+ Dubai attractions. Book tickets for Burj Khalifa, desert safaris, theme parks, cruises and more. Instant e-tickets and free cancellation on most experiences.', `
    <section class="dubai-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('${esc(heroImg)}')">
      <div class="container">
        ${crumbs}
        <h1>Things to Do in Dubai &mdash; Tickets, Tours &amp; Attractions</h1>
        <p class="dubai-hub__hero-sub">Skip-the-line tickets and instant confirmation for Dubai's best experiences</p>
        <form class="dubai-hub__search" action="/search" method="get"><input type="search" name="q" placeholder="Search attractions, tours, tickets..." aria-label="Search Dubai attractions"><button type="submit">Search</button></form>
      </div>
    </section>
    <section class="dubai-hub__stats"><div class="container"><div class="dubai-hub__stats-grid">
      <div class="dubai-hub__stat"><strong>100+</strong><span>Attractions &amp; Tours</span></div>
      <div class="dubai-hub__stat"><strong>Instant</strong><span>E-Ticket Delivery</span></div>
      <div class="dubai-hub__stat"><strong>Free</strong><span>Cancellation on many tickets</span></div>
      <div class="dubai-hub__stat"><strong>24/7</strong><span>Partner Support</span></div>
    </div></div></section>
    <section class="dubai-hub__categories section-band"><div class="container"><div class="section-heading"><h2>Explore Dubai by Category</h2><a href="/attractions">See All</a></div><div class="dubai-hub__category-grid">${categoryCards}</div></div></section>
    ${activityCards ? `<section class="dubai-hub__featured section-band muted"><div class="container"><div class="section-heading"><h2>Top Dubai Attractions</h2><a href="/attractions">See All</a></div><div class="card-grid">${activityCards}</div></div></section>` : ''}
    <section class="dubai-hub__trust section-band"><div class="container"><h2>Why Book With ${esc(SITE_NAME)}</h2><div class="dubai-hub__trust-grid">
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg><h3>Free Cancellation on Many Tickets</h3><p>The exact policy for each ticket is shown at partner checkout before you pay.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg><h3>Instant E-Tickets</h3><p>Get your tickets delivered straight to your phone. No printing required, just show and go.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 1v4"/><path d="M12 19v4"/><circle cx="12" cy="12" r="7"/><path d="M8.5 12h5l-2-3"/><path d="M13.5 12h-5l2 3"/></svg><h3>Live Prices</h3><p>Prices and availability come straight from our ticket partner, so what you see is what you pay.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg><h3>24/7 Partner Support</h3><p>Our ticket partner's support team is available around the clock for bookings and changes.</p></div>
    </div></div></section>
    <section class="dubai-hub__guide section-band muted"><div class="container"><div class="dubai-hub__guide-content"><h2>Your Complete Guide to Dubai Attractions</h2>
      <p>Dubai has transformed from a quiet trading port into one of the world's most visited cities, welcoming over 16 million international tourists each year. The city's skyline, anchored by the 828-metre Burj Khalifa, is only part of the story. From vast desert landscapes just minutes from downtown to indoor ski slopes and record-breaking theme parks, Dubai packs an extraordinary range of experiences into a compact metropolitan area connected by metro, tram and water taxi.</p>
      <p>First-time visitors will want to start with the iconic observation decks: the At the Top experience at Burj Khalifa and the Sky Views Observatory offer dramatically different perspectives of the city. From there, the historic Al Fahidi District and Dubai Creek provide a window into the emirate's pearl-diving and spice-trading heritage, while the Dubai Mall, Museum of the Future and Dubai Frame bridge old and new in memorable ways.</p>
      <p>Adventure seekers should look beyond the city centre. Desert safaris combine dune bashing, sandboarding and traditional Bedouin dinners under star-filled skies. The coastline offers yacht charters, jet-ski tours and deep-sea fishing, while Hatta's mountain trails and kayaking routes provide a cooler escape in the winter months. For families, Atlantis Aquaventure, IMG Worlds of Adventure and LEGOLAND offer full days of entertainment with skip-the-line ticket options.</p>
      <p>Timing matters in Dubai. The peak tourist season runs from November to March when temperatures hover around a pleasant 25 degrees Celsius, making it ideal for outdoor attractions and desert excursions. Summer months (June to September) bring intense heat but also significant discounts on indoor attractions, hotels and dining. Ramadan offers a unique cultural experience, with special iftar events and a slower, more reflective pace of life across the city.</p>
    </div></div></section>
    <section class="dubai-hub__explore section-band"><div class="container"><h2>Popular Dubai Experiences</h2><div class="dubai-hub__link-grid">${linkCards}</div></div></section>
    ${destFaq(faqs, 'Frequently Asked Questions About Dubai')}`);
}

async function dubaiCategory(slug) {
  const categories = dubaiContent.categories || [];
  const cat = categories.find(c => c.slug === slug);
  if (!cat) return layout('Category Not Found | ' + SITE_NAME, '', '<section class="section-band"><div class="container"><div class="empty-state"><h1>Category not found</h1><a class="button-link" href="/dubai">Back to Dubai</a></div></div></section>');

  // Fetch the curated activities for this category one ID at a time
  let activities = [];
  for (const id of (cat.activity_ids || [])) {
    try {
      const a = await api(`/v1/activities/${Number(id)}`, {}, 1800);
      if (a?.id) activities.push(a);
    } catch {}
  }
  if (!activities.length) {
    try { activities = (await api('/v1/activities', { limit: 24, page: 1, city_id: DEFAULT_CITY_ID, query: cat.api_query || cat.name })).activities || []; } catch {}
  }
  const heroImg = cat.hero_image || fallbackImages.hero;
  const faqs = cat.faqs || [];
  const highlights = (cat.highlights || []).map(h => `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>${esc(h)}</li>`).join('');
  const tips = (cat.tips || []).map(t => `<div class="dubai-category__tip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><p>${esc(t)}</p></div>`).join('');
  const pageTitle = cat.title || (cat.name + ' in Dubai');
  const shortName = cat.short_name || cat.name;
  const crumbs = breadcrumb([{ name: 'Home', url: '/' }, { name: 'Dubai', url: '/dubai' }, { name: shortName, url: '/dubai/' + slug }]);

  const activityCards = activities.map(activityCard).join('');

  const relatedLinks = categories.filter(c => c.slug !== slug).slice(0, 8).map(c => `<a class="dubai-hub__link-card" href="/dubai/${esc(c.slug)}"><strong>${esc(c.short_name || c.name)}</strong><span>${esc(c.subtitle || '')}</span></a>`).join('');

  return layout(`${pageTitle} | ${SITE_NAME}`, cat.meta_description || `Find the best ${cat.name.toLowerCase()} in Dubai. Compare prices, read reviews and book online with instant confirmation.`, `
    <section class="dubai-category__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('${esc(heroImg)}')">
      <div class="container">${crumbs}<h1>${esc(pageTitle)}</h1>${cat.subtitle ? `<p class="dubai-category__hero-sub">${esc(cat.subtitle)}</p>` : ''}</div>
    </section>
    ${cat.intro ? `<section class="dubai-category__intro section-band"><div class="container"><div class="dubai-category__intro-content">${(Array.isArray(cat.intro) ? cat.intro : [cat.intro]).map(p => typeof p === 'object' && p.heading ? `<h2>${esc(p.heading)}</h2><p>${esc(p.text)}</p>` : `<p>${esc(p)}</p>`).join('')}</div></div></section>` : ''}
    ${activityCards ? `<section class="dubai-category__activities section-band muted"><div class="container"><div class="section-heading"><h2>Best ${esc(shortName)} in Dubai</h2><span>${activities.length} experiences</span></div><div class="card-grid">${activityCards}</div></div></section>` : ''}
    ${highlights ? `<section class="dubai-category__highlights section-band"><div class="container"><h2>Highlights</h2><ul class="dubai-category__highlights-list">${highlights}</ul></div></section>` : ''}
    ${tips ? `<section class="dubai-category__tips section-band muted"><div class="container"><h2>Tips for Visitors</h2><div class="dubai-category__tips-grid">${tips}</div></div></section>` : ''}
    ${destFaq(faqs, 'Frequently Asked Questions')}
    ${relatedLinks ? `<section class="dubai-category__related section-band"><div class="container"><h2>More Things to Do in Dubai</h2><div class="dubai-hub__link-grid">${relatedLinks}</div></div></section>` : ''}
    <section class="dubai-category__cta section-band muted"><div class="container"><div class="dubai-category__cta-box"><h2>Ready to Explore ${esc(shortName)} in Dubai?</h2><p>Book your tickets now with instant confirmation and secure checkout on our official ticket partner's site.</p><a class="button-link wide" href="/attractions">Browse All Attractions</a></div></div></section>`);
}

function starsSvg(rating, count = 0) {
  const full = Math.floor(rating);
  const half = (rating - full) >= 0.3;
  const star = '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width:14px;height:14px;fill:var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"/></svg>';
  let out = star.repeat(full);
  if (half) out += star;
  out += ` <strong>${esc(rating.toFixed(1))}</strong>`;
  if (count > 0) out += ` <span class="dubai-rating__count">(${new Intl.NumberFormat('en-US').format(count)} reviews)</span>`;
  return out;
}

async function dubaiAttraction(pathname) {
  const slug = pathname.split('/').pop();
  const attractions = dubaiContent.attractions || [];
  const att = attractions.find(a => a.slug === slug);
  if (!att) return layout('Attraction Not Found | ' + SITE_NAME, '', '<section class="section-band"><div class="container"><div class="empty-state"><h1>Attraction not found</h1><a class="button-link" href="/dubai">Back to Dubai</a></div></div></section>');

  const categorySlug = att.category_slug || 'attractions';
  const categoryName = att.category_name || 'Attractions';
  const categoryShort = att.category_short_name || categoryName;
  const attractionShort = att.short_name || att.title || '';

  let activity = {};
  if (att.activity_id) {
    try { activity = await api(`/v1/activities/${att.activity_id}`, {}, 1800); } catch {}
  }

  // Related activity IDs from same category, fetched one at a time
  let relatedActivities = [];
  for (const relatedId of (att.related_activity_ids || [])) {
    try {
      const a = await api(`/v1/activities/${Number(relatedId)}`, {}, 1800);
      if (a?.id) relatedActivities.push(a);
    } catch {}
  }
  if (!relatedActivities.length && categoryName) {
    try { relatedActivities = (await api('/v1/activities', { limit: 8, page: 1, city_id: 132, query: categoryName })).activities || []; } catch {}
  }
  const primaryId = Number(att.activity_id || 0);
  if (primaryId > 0) relatedActivities = relatedActivities.filter(a => Number(a.id || 0) !== primaryId);

  // Rating info — only render numbers the API actually supplied
  const rating = activity.reviews?.avg_rating ? Number(activity.reviews.avg_rating) : 0.0;
  const reviewCount = activity.reviews?.number_of_reviews ? Number(activity.reviews.number_of_reviews) : Number(att.review_count || 0);
  const price = activity.from_price || att.price_from || 0;
  const currency = activity.currency || CURRENCY;
  const heroImg = att.image || fallbackImages.hero;
  const gallery = att.gallery || [];
  const tips = att.tips || [];
  const relatedAttractions = attractions.filter(a => a.slug !== slug && (a.category_slug || '') === categorySlug);
  const crumbs = breadcrumb([
    { name: 'Home', url: '/' },
    { name: 'Dubai', url: '/dubai' },
    { name: categoryShort, url: '/dubai/' + categorySlug },
    { name: attractionShort, url: `/dubai/${categorySlug}/${slug}` },
  ]);
  const cancelPolicy = activity.cancellation_policy ? String(activity.cancellation_policy).replace(/<[^>]+>/g, '') : '';
  const variants = relatedActivities.slice(0, 4);

  return layout(`${att.title} — Tickets & Prices | ${SITE_NAME}`, att.meta_description || `Book ${att.title} tickets online. Skip the line with instant e-tickets and free cancellation on most experiences.`, `
    <section class="attraction-detail__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('${esc(heroImg)}')">
      <div class="container">
        ${crumbs}
        <h1>${esc(att.title)}</h1>
        <div class="attraction-detail__hero-meta">
          ${rating > 0 ? `<span class="attraction-detail__rating">${starsSvg(rating, reviewCount)}</span>` : ''}
          ${Number(price) > 0 ? `<span class="attraction-detail__price-badge">From ${esc(money(price, currency))}</span>` : ''}
        </div>
      </div>
    </section>
    ${gallery.length ? `<section class="attraction-detail__gallery section-band"><div class="container"><div class="attraction-detail__gallery-grid"><div class="attraction-detail__gallery-main"><img src="${esc(heroImg)}" alt="${esc(att.title)}" loading="lazy"></div>${gallery.slice(0, 4).map(g => `<div class="attraction-detail__gallery-thumb"><img src="${esc(g)}" alt="${esc(att.title)}" loading="lazy"></div>`).join('')}</div></div></section>` : ''}
    <section class="attraction-detail__main section-band"><div class="container"><div class="attraction-detail__grid">
      <div class="attraction-detail__content">
        ${att.what_to_expect?.length ? `<div class="attraction-detail__section"><h2>What to Expect</h2><ul class="attraction-detail__expect-list">${att.what_to_expect.map(p => `<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>${esc(p)}</li>`).join('')}</ul></div>` : ''}
        ${att.intro ? `<div class="attraction-detail__section"><h2>About ${esc(attractionShort)}</h2>${(Array.isArray(att.intro) ? att.intro : [att.intro]).map(p => typeof p === 'object' && p.heading ? `<h3>${esc(p.heading)}</h3><p>${esc(p.text)}</p>` : `<p>${esc(p)}</p>`).join('')}</div>` : ''}
        ${activity.id ? `<div class="attraction-detail__section"><h2>Ticket Options</h2><div class="attraction-detail__ticket-card"><div class="attraction-detail__ticket-info"><strong>${esc(activity.title || att.title)}</strong><p>${esc(activity.supplier_name || 'Official ticket partner')}</p>${cancelPolicy ? `<span class="attraction-detail__cancel-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:14px;height:14px"><polyline points="20 6 9 17 4 12"/></svg>${esc(cancelPolicy)}</span>` : ''}</div><div class="attraction-detail__ticket-action">${Number(price) > 0 ? `<span class="attraction-detail__ticket-price">From ${esc(money(price, currency))}</span>` : ''}<a class="button-link" href="${goUrl(activity, 'activity')}" rel="sponsored nofollow">Check Availability</a></div></div></div>` : ''}
        ${variants.length ? `<div class="attraction-detail__section"><h2>Related ${esc(categoryShort)} Tickets</h2><div class="attraction-detail__variants">${variants.map(v => `<div class="attraction-detail__variant-card"><div><strong><a href="${activityPath(v)}">${esc(v.title || '')}</a></strong><span>${esc(v.supplier_name || '')}</span></div><div class="attraction-detail__variant-action"><span>${esc(money(v.from_price, v.currency || currency))}</span><a class="button-link small" href="${goUrl(v, 'activity')}" rel="sponsored nofollow">Book</a></div></div>`).join('')}</div></div>` : ''}
        ${tips.length ? `<div class="attraction-detail__section"><h2>Tips for Visiting ${esc(attractionShort)}</h2><div class="dubai-category__tips-grid">${tips.map(t => `<div class="dubai-category__tip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><p>${esc(t)}</p></div>`).join('')}</div></div>` : ''}
        ${destFaq(att.faqs || [], 'Frequently Asked Questions')}
      </div>
      <aside class="attraction-detail__sidebar">
        <div class="attraction-detail__quick-facts"><h3>Quick Facts</h3><dl>
          ${Number(price) > 0 ? `<div><dt>Price from</dt><dd>${esc(money(price, currency))}</dd></div>` : ''}
          ${att.quick_facts?.duration ? `<div><dt>Duration</dt><dd>${esc(att.quick_facts.duration)}</dd></div>` : ''}
          ${att.quick_facts?.best_time ? `<div><dt>Best time</dt><dd>${esc(att.quick_facts.best_time)}</dd></div>` : ''}
          ${att.quick_facts?.location ? `<div><dt>Location</dt><dd>${esc(att.quick_facts.location)}</dd></div>` : ''}
          ${cancelPolicy ? `<div><dt>Cancellation</dt><dd>${esc(cancelPolicy)}</dd></div>` : ''}
          ${reviewCount > 0 ? `<div><dt>Rating</dt><dd>${esc(rating.toFixed(1))}/5 (${new Intl.NumberFormat('en-US').format(reviewCount)} reviews)</dd></div>` : ''}
        </dl></div>
        ${activity.id ? `<div class="attraction-detail__book-panel"><span class="price-label">Tickets From</span><strong>${esc(money(price, currency))}</strong><a class="button-link wide" href="${goUrl(activity, 'activity')}" rel="sponsored nofollow">Check Availability</a><p class="checkout-note">Secure checkout on our official ticket partner's site. Instant e-tickets delivered to your phone.</p></div>` : ''}
        ${relatedAttractions.length ? `<div class="attraction-detail__related-links"><h3>Related Attractions</h3><ul>${relatedAttractions.slice(0, 6).map(rel => `<li><a href="/dubai/${esc(rel.category_slug || categorySlug)}/${esc(rel.slug)}">${esc(rel.short_name || rel.title)}</a></li>`).join('')}</ul></div>` : ''}
        <div class="attraction-detail__category-link"><a href="/dubai/${esc(categorySlug)}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg> All ${esc(categoryShort)}</a></div>
      </aside>
    </div></div></section>
    ${relatedActivities.length ? cardSection(`More ${categoryShort} in Dubai`, '/dubai/' + categorySlug, relatedActivities, 'activity') : ''}`, MARKET_CITIES[0], { image: heroImg });
}

async function abuDhabiHub() {
  const activitiesData = await api('/v1/activities', { limit: 21, page: 1, city_id: 256 });
  const activities = activitiesData.activities || [];
  const heroImg = dubaiContent.abu_dhabi_hero_image || 'https://images.unsplash.com/photo-1587302912306-cf1ed9c33146?auto=format&fit=crop&w=1800&q=80';
  const faqs = dubaiContent.abu_dhabi_faqs || [
    { q: 'How far is Abu Dhabi from Dubai?', a: 'Abu Dhabi is approximately 130 km from Dubai, about a 90-minute drive via the E11 highway. Many tour operators offer convenient hotel pick-up and drop-off services from Dubai.' },
    { q: 'Can I visit Abu Dhabi on a day trip from Dubai?', a: 'Yes, a day trip is very popular. Most guided tours depart Dubai early morning and return by evening, covering top attractions like the Sheikh Zayed Grand Mosque, Louvre Abu Dhabi and Yas Island.' },
    { q: 'What is the best time to visit Abu Dhabi?', a: 'November to March offers the most comfortable weather with temperatures around 20-25 degrees Celsius. This is ideal for outdoor sightseeing and desert activities.' },
    { q: 'Do I need a separate visa for Abu Dhabi?', a: 'No, Abu Dhabi is in the same country as Dubai (UAE). Your Dubai visa or visa-free entry covers the entire UAE including Abu Dhabi.' },
  ];
  const activityCards = activities.map(activityCard).join('');
  const crumbs = breadcrumb([{ name: 'Home', url: '/' }, { name: 'Abu Dhabi', url: '/abu-dhabi' }]);

  return layout('Things to Do in Abu Dhabi — Tours, Tickets & Day Trips from Dubai | ' + SITE_NAME, 'Book Abu Dhabi tours and tickets from Dubai. Visit Sheikh Zayed Grand Mosque, Louvre Abu Dhabi, Ferrari World and more with instant e-tickets and free cancellation on most experiences.', `
    <section class="dubai-hub__hero abu-dhabi-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('${esc(heroImg)}')">
      <div class="container">${crumbs}<h1>Things to Do in Abu Dhabi &mdash; Tours, Tickets &amp; Day Trips</h1><p class="dubai-hub__hero-sub">Explore the UAE capital with skip-the-line tickets and guided tours from Dubai</p></div>
    </section>
    <section class="dubai-hub__stats"><div class="container"><div class="dubai-hub__stats-grid">
      ${activities.length > 0 ? `<div class="dubai-hub__stat"><strong>${activities.length}+</strong><span>Activities</span></div>` : ''}
      <div class="dubai-hub__stat"><strong>90 min</strong><span>From Dubai</span></div>
      <div class="dubai-hub__stat"><strong>Instant</strong><span>E-Tickets</span></div>
      <div class="dubai-hub__stat"><strong>Free</strong><span>Cancellation on many tickets</span></div>
    </div></div></section>
    ${activityCards ? `<section class="abu-dhabi-hub__activities section-band"><div class="container"><div class="section-heading"><h2>Top Abu Dhabi Attractions &amp; Tours</h2><span>${activities.length} experiences</span></div><div class="card-grid">${activityCards}</div></div></section>` : ''}
    <section class="dubai-hub__guide section-band muted"><div class="container"><div class="dubai-hub__guide-content"><h2>Visiting Abu Dhabi from Dubai</h2>
      <p>Abu Dhabi, the capital of the United Arab Emirates, offers a striking contrast to Dubai's glittering modernity. Just 90 minutes down the coast, the city balances world-class cultural institutions with dramatic desert landscapes and a thriving food scene. For Dubai visitors, a day trip to Abu Dhabi is one of the most rewarding excursions available.</p>
      <p>The Sheikh Zayed Grand Mosque is the undisputed highlight, with its 82 white domes, gold-plated chandeliers and the world's largest hand-knotted carpet. Nearby, the Louvre Abu Dhabi showcases centuries of art beneath Jean Nouvel's spectacular dome of light. For thrill-seekers, Yas Island delivers with Ferrari World (home to the world's fastest roller coaster), Yas Waterworld and Warner Bros. World.</p>
      <p>Most Abu Dhabi day tours from Dubai include hotel pick-up, air-conditioned transport and a guide who covers history, architecture and local customs. Prices start from around AED 100 for a basic mosque visit, rising to AED 400+ for full-day packages that combine multiple attractions with lunch. Booking online with instant confirmation guarantees your spot and often saves 10-20% compared to walk-up prices.</p>
    </div></div></section>
    <section class="dubai-hub__trust section-band"><div class="container"><h2>Why Book Abu Dhabi Tours With Us</h2><div class="dubai-hub__trust-grid">
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg><h3>Dubai Hotel Pick-up</h3><p>Most tours include convenient pick-up and drop-off from your Dubai hotel, so you can relax on the drive.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg><h3>Free Cancellation on Many Tickets</h3><p>The exact policy for each ticket is shown at partner checkout before you pay.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg><h3>Instant E-Tickets</h3><p>Your booking confirmation is sent immediately. Just show it on your phone at the meeting point.</p></div>
      <div class="dubai-hub__trust-card"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><h3>Skip the Line</h3><p>Pre-booked tickets let you bypass queues at Abu Dhabi's busiest attractions.</p></div>
    </div></div></section>
    <section class="abu-dhabi-hub__crosslink section-band muted"><div class="container"><div class="dubai-category__cta-box"><h2>Exploring Dubai Too?</h2><p>Browse 100+ attractions, theme parks, desert safaris and more across Dubai.</p><a class="button-link wide" href="/dubai">Things to Do in Dubai</a></div></div></section>
    ${destFaq(faqs, 'Abu Dhabi Travel FAQ')}`);
}

function staticPage(title, desc, body, activeCity = MARKET_CITIES[0]) {
  return layout(`${title} | ${SITE_NAME}`, desc, `
    <section class="section-band">
      <div class="container prose">
        <h1>${esc(title)}</h1>
        ${body}
      </div>
    </section>`, activeCity);
}

function aboutPage(activeCity) {
  return staticPage(`About ${SITE_NAME}`, `${SITE_NAME} is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations worldwide.`, `
    <p>${esc(SITE_NAME)} is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations across the United States, Canada, the United Kingdom, Italy, Spain and France.</p>
    <p>We list concerts, theatre, sports, tours and attractions with live prices and availability supplied by our official ticketing partner, HelloTickets. When you choose a ticket, you complete your booking securely on our partner's site &mdash; they handle payment, ticket delivery and customer support.</p>
    <p>${esc(SITE_NAME)} is operated by Town Media Labs. Questions? See our <a href="/contact">Contact</a> page.</p>`, activeCity);
}

function contactPage(activeCity) {
  return staticPage('Contact Us', `How to reach the ${SITE_NAME} team for partnerships, listings, feedback and corrections.`, `
    <p>The fastest way to reach us is email: <a href="mailto:townmedialabs@gmail.com"><strong>townmedialabs@gmail.com</strong></a></p>
    <ul>
      <li>Booking, payment or refund questions: these are handled by our ticketing partner &mdash; use the support links in your booking confirmation email.</li>
      <li>Partnerships and listings: email us with the subject "Partner with ${esc(SITE_NAME)}".</li>
      <li>Site feedback or corrections: email us and include the page link.</li>
    </ul>`, activeCity);
}

function howWeMakeMoneyPage(activeCity) {
  return staticPage('How We Make Money', `${SITE_NAME} is free to use. Here is how affiliate commissions fund the site without changing the price you pay.`, `
    <p>${esc(SITE_NAME)} is free to use. When you buy a ticket through a link on our site, our ticketing partner may pay us a commission. This never increases the price you pay &mdash; prices and availability come directly from the partner.</p>
    <p>We do not process payments, hold ticket inventory, or charge any fees. Commissions are how we fund the site.</p>`, activeCity);
}

async function cityPage(cityId) {
  const city = cityForId(cityId);
  let guidePath = null;
  for (const [citySlug, hubCity] of Object.entries(destinationsContent.cities || {})) {
    if (Number(hubCity.city_id || 0) === cityId && hubCity.country_slug) {
      guidePath = `/${hubCity.country_slug}/${citySlug}`;
      break;
    }
  }
  let events = [], activities = [];
  try { events = (await api('/v1/performances', { limit: 12, page: 1, is_sellable: true, city_id: cityId, ...dateParams() })).performances || []; } catch {}
  try { activities = (await api('/v1/activities', { limit: 12, page: 1, city_id: cityId })).activities || []; } catch {}
  return layout(`${city.name} Tickets, Events & Attractions | ${SITE_NAME}`, `Browse current tickets for ${city.name}, including attractions, tours, concerts, theatre and sports.`, `
    <section class="listing-hero city-hero">
      <div class="container">
        <p class="eyebrow">${esc(city.country || 'Destination')}</p>
        <h1>${esc(city.name)} tickets, events and attractions</h1>
        <div class="filter-row inverse">
          <a href="/events?date=today">Today</a>
          <a href="/events?date=weekend">This Weekend</a>
          <a href="/attractions">Attractions</a>
          <a href="/events">Events</a>
        </div>
        ${guidePath ? `<p class="city-guide-link"><a href="${esc(guidePath)}">Read the full ${esc(city.name)} guide &rarr;</a></p>` : ''}
      </div>
    </section>
    ${cardSection(`Events in ${city.name}`, '/events', events, 'event')}
    ${cardSection(`Attractions in ${city.name}`, '/attractions', activities, 'activity')}`, city);
}

async function handle(req, res) {
  try {
    const url = new URL(req.url, `http://127.0.0.1:${PORT}`);
    if (url.pathname !== '/' && url.pathname.endsWith('/')) {
      res.writeHead(301, { location: url.pathname.replace(/\/+$/, '') + (url.search || '') });
      res.end();
      return;
    }
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
    const extraHeaders = {};
    const reqCityId = activeCityId(req);
    const seg = url.pathname.slice(1).split('/');
    if (url.pathname === '/') html = await home(reqCityId);
    else if (url.pathname === '/dubai') html = await dubaiHub();
    else if (url.pathname === '/abu-dhabi') html = await abuDhabiHub();
    else if (url.pathname === '/about') html = aboutPage(cityForId(reqCityId));
    else if (url.pathname === '/contact') html = contactPage(cityForId(reqCityId));
    else if (url.pathname === '/how-we-make-money') html = howWeMakeMoneyPage(cityForId(reqCityId));
    else if (/^\/dubai\/[a-z0-9-]+\/[a-z0-9-]+$/.test(url.pathname)) {
      // 301 to the canonical path when the category segment in the URL is wrong
      const attSlug = url.pathname.split('/').pop();
      const att = (dubaiContent.attractions || []).find(a => a.slug === attSlug);
      const canonicalCat = att ? (att.category_slug || 'attractions') : null;
      if (att && url.pathname.split('/')[2] !== canonicalCat) {
        res.writeHead(301, { location: `/dubai/${canonicalCat}/${attSlug}` });
        res.end();
        return;
      }
      html = await dubaiAttraction(url.pathname);
    }
    else if (/^\/dubai\/[a-z0-9-]+$/.test(url.pathname)) html = await dubaiCategory(url.pathname.split('/').pop());
    else if (url.pathname === '/events') html = await listing(url, 'event', null, reqCityId);
    else if (url.pathname === '/attractions') html = await listing(url, 'activity', null, reqCityId);
    else if (url.pathname === '/search') {
      const searchType = url.searchParams.get('type');
      if (searchType === 'events' || searchType === 'attractions') {
        const q = url.searchParams.get('q') || '';
        res.writeHead(302, { location: `/${searchType === 'events' ? 'events' : 'attractions'}${q ? `?q=${encodeURIComponent(q)}` : ''}` });
        res.end();
        return;
      }
      html = await searchPage(url, reqCityId);
    }
    else if (url.pathname.startsWith('/event/')) html = await eventDetail(idFromSlug(url.pathname.split('/').pop()), cityForId(reqCityId));
    else if (url.pathname.startsWith('/activity/')) html = await activityDetail(idFromSlug(url.pathname.split('/').pop()), cityForId(reqCityId));
    else if (url.pathname.startsWith('/category/')) html = await listing(url, [1, 2, 3].includes(idFromSlug(url.pathname.split('/').pop())) ? 'event' : 'activity', idFromSlug(url.pathname.split('/').pop()), reqCityId);
    else if (url.pathname.startsWith('/city/')) {
      const cityId = idFromSlug(url.pathname.split('/').pop());
      if (MARKET_CITIES.some(c => c.id === cityId)) {
        extraHeaders['set-cookie'] = `tb_city=${cityId}; Max-Age=31536000; Path=/; SameSite=Lax`;
      }
      html = await cityPage(cityId);
    }
    else if (url.pathname === '/sitemap.xml') {
      const base = `http://127.0.0.1:${PORT}`;
      const urls = ['/', '/dubai', '/abu-dhabi', '/events', '/attractions', '/about', '/contact', '/how-we-make-money'];
      for (const cat of (dubaiContent.categories || [])) urls.push('/dubai/' + cat.slug);
      for (const att of (dubaiContent.attractions || [])) urls.push('/dubai/' + (att.category_slug || 'attractions') + '/' + att.slug);
      for (const cs of Object.keys(destinationsContent.countries)) {
        urls.push('/' + cs);
        for (const ci of (destinationsContent.countries[cs].cities || [])) urls.push('/' + cs + '/' + ci.slug);
      }
      for (const c of MARKET_CITIES) urls.push(cityPath(c));
      res.writeHead(200, { 'content-type': 'application/xml; charset=utf-8' });
      res.end(`<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${[...new Set(urls)].map(u => `<url><loc>${base}${u}</loc></url>`).join('')}</urlset>`);
      return;
    }
    else if (url.pathname === '/robots.txt') {
      res.writeHead(200, { 'content-type': 'text/plain; charset=utf-8' });
      res.end(`User-agent: *\nAllow: /\nDisallow: /go\nDisallow: /search\nSitemap: http://127.0.0.1:${PORT}/sitemap.xml\n`);
      return;
    }
    else if (seg.length === 2 && destinationsContent.cities[seg[1]]?.country_slug === seg[0]) html = await cityHub(seg[0], seg[1]);
    else if (seg.length === 1 && destinationsContent.countries[seg[0]]) html = await countryHub(seg[0]);
    else {
      res.writeHead(404, { 'content-type': 'text/html; charset=utf-8' });
      res.end(layout(`Not found | ${SITE_NAME}`, 'Not found', '<section class="section-band"><div class="container"><div class="empty-state"><h1>Page not found</h1><p>Try the home page.</p><a class="button-link" href="/">Back home</a></div></div></section>'));
      return;
    }
    res.writeHead(200, { 'content-type': 'text/html; charset=utf-8', ...extraHeaders });
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
