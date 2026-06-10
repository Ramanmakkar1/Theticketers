# TickedBus Redesign Spec (v1 — 2026-06-09)

Synthesized from 78 audit findings. Owner intent: keep the red/white BookMyShow look,
remove clutter and fake data, make every link and control actually work.
Hard constraints unchanged: vanilla PHP 8 + vanilla CSS; every markup change in `src/*.php`
MUST be mirrored in `preview-server.mjs` (verify with `node --check preview-server.mjs`).
PHP cannot run locally — write conservative, obviously-valid PHP.
NOTE: audit line numbers drift as you edit. Locate code by the quoted anchor strings, not line numbers.

---

## 1. KEEP

- Palette: red `#f84464`, white canvas, navy `#222539` subnav, black card rating strips, Roboto.
- Poster `.ticket-card` cards with date badge + black bottom strip (strip CONTENT changes, see §5).
- Hero carousel (4 slides), date filter chips, horizontal rails with arrow buttons.
- City system: `tb_city` cookie, header city-picker, first-visit modal, `data-city-id` binding in app.js.
- Sticky header + navy subnav, footer 3-icon care strip, footer columns.
- Dubai/Abu Dhabi/destination hub page structures (hero, stats band, category grid, FAQ, guide prose).
- "Explore by Destination" home section (exists in BOTH pages.php and preview-server.mjs — keep, it earns its slot).
- `/go` affiliate redirect flow, HelloTicketsClient caching.

## 2. CUT (decisive removals)

1. **All fabricated ratings** — every hardcoded `4.9/5`, `4.9 rating`, `4.8` fallback, `4.7` fallback,
   "4.7 Average Rating" / "4.8 Avg Rating" stat tiles, and the `"rating"` keys in destinations-content.json. (§5)
2. **Footer social icon row** (4 × `href="#"`) — delete entirely from pages.php + preview-server.mjs until real profiles exist.
3. **Concerts/Theatre chips in the home filter row** — keep it a pure date row (Today / Tomorrow / This Weekend / This Month).
4. **Bottom "Browse by category" 18-link tag-grid section** on home — delete the whole `section-band muted` block
   (the renamed arch band covers category browsing, §3).
5. **"Best Price Guarantee" trust card** everywhere (dubai-pages.php ×2, destinations.php) — replaced, see §8.
6. **Hardcoded `quick_facts['cancellation'] = 'Free cancellation up to 24h before'`** default in dubai-content.php
   normalization — only show the API's real `cancellation_policy`.
7. **Dead CSS**: `.dubai-cta-band` block, `.dubai-category__related-grid/-card` block,
   `.dubai-category__hero p` rule (self-conflicting), stale `/* Mobile slide-down nav */` block containing only a
   duplicate `.subnav-side{display:none}`. (§6)
8. **`max(count($activities), 21)`** inflated Abu Dhabi count — use real `count($activities)`.
9. **Duplicate "Popular Dubai Experiences" 18-category link-grid** on /dubai — repurpose to link the 23 attraction
   detail pages instead (deep pages currently unreachable from the hub).
10. **`<lastmod>` = today on all sitemap URLs** — drop lastmod where no real date exists.

## 3. HOME PAGE — final section order

All in `render_home_page()` (pages.php) and `home()` (preview-server.mjs). Pure block reordering + the cuts above.

1. **Hero carousel** — unchanged. Add a visually-hidden H1 just inside `<main>` content:
   `<h1 class="visually-hidden"><?= e($homeCity['name']) ?> Events, Attractions & Tickets</h1>`
   and demote slide-1 `<h1>` to `<h2>` (all slides become h2). Add `.visually-hidden` utility to styles.css.
2. **Date chips** (`section-band compact`) — date links only; rename "This weekend" → "This Weekend", add "This Month".
3. **Recommended in {City}** rail (activities).
4. **Live Events in {City}** rail (events). Merge dedup: collect `$seenIds` from `$events`; filter
   `$globalEvents = array_filter($globalEvents, fn($p) => !in_array($p['id'] ?? 0, $seenIds))`.
5. **Browse by Category** arch band (`render_live_events_band()` renamed in heading only):
   heading "Browse by Category", remove its "Show all" link, replace the 6 emoji with inline SVGs
   (1.8 stroke-width style matching header icons: music note, theatre masks, trophy, sun/dune, ferris wheel, ship).
6. **Popular Events Worldwide** rail (white — drop the `'dark'` variant) — render ONLY if ≥4 deduped
   `$globalEvents` remain; heading link → `/events`.
7. **Promo banner** (dark) — keep ONE dark promo strip but make it concrete:
   kicker "Featured", h2 "Burj Khalifa: At the Top", p "Skip the queue with instant e-tickets to the world's tallest tower.",
   button "Get Tickets" → `/attractions?q=Burj%20Khalifa`. (Drops the "Endless entertainment" slogan filler.)
8. **Explore by Destination** image-card grid (existing section, moved here from above the rails).
9. **Popular Ticket Cities** grid — `array_slice($config['market_cities'], 0, 8)` (was all 22/14).
10. Footer.

**FINAL ORDER (binding): hero → chips → Recommended → Live Events → arch band (dark) → Worldwide (WHITE — drop
the `'dark'` variant arg from `render_card_section`) → promo banner (dark) → Explore by Destination (white) →
Popular Ticket Cities (white) → footer.** This alternates content/dark correctly (never two dark bands adjacent),
puts inventory above marketing, and removes two full-width stripes (tag-grid, second promo) versus today.

## 4. GLOBAL FIXES

### 4.1 Static pages (new): /about, /contact, /how-we-make-money
Add ONE generic helper in pages.php: `function render_static_page(array $config, string $title, string $desc, string $path, callable $body)`
using `render_layout()`. Register routes in `dispatch()` **BEFORE** the `/{country}` destination catch-all regex.
Add all three URLs to `render_sitemap()`. Link from footer "Discover" column (About Us / Contact / How We Make Money).
Mirror routes + markup in preview-server.mjs. Copy (use verbatim, simple `<section class="section-band"><div class="container prose">` wrapper; add a minimal `.prose { max-width: 780px; margin: 0 auto; }` style):

**/about — "About TickedBus"**
> TickedBus is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top
> destinations across the United States, Canada, the United Kingdom, Italy, Spain and France.
> We list concerts, theatre, sports, tours and attractions with live prices and availability supplied by our
> official ticketing partner, HelloTickets. When you choose a ticket, you complete your booking securely on our
> partner's site — they handle payment, ticket delivery and customer support.
> TickedBus is operated by Town Media Labs. Questions? See our [Contact](/contact) page.

**/contact — "Contact Us"**
> The fastest way to reach us is email: **townmedialabs@gmail.com** (mailto link).
> • Booking, payment or refund questions: these are handled by our ticketing partner — use the support links in
>   your booking confirmation email.
> • Partnerships and listings: email us with the subject "Partner with TickedBus".
> • Site feedback or corrections: email us and include the page link.

**/how-we-make-money — "How We Make Money"**
> TickedBus is free to use. When you buy a ticket through a link on our site, our ticketing partner may pay us a
> commission. This never increases the price you pay — prices and availability come directly from the partner.
> We do not process payments, hold ticket inventory, or charge any fees. Commissions are how we fund the site.

### 4.2 Dead links
- Footer partner CTA: `href="#"` → `href="/contact"`, label "Contact Today" → "Contact Us".
- Footer social block: DELETE (see §2.2).
- Footer "24/7 Customer Care" badge → "24/7 Partner Support" (text only; icon stays).

### 4.3 Mobile menu + search + city picker (app.js + styles.css + pages.php)
The hamburger is dead UI and mobile users lose search AND city switching. Fix:
- **Decision: keep the hamburger; it reveals the search bar** (the navy subnav already handles nav).
  In the ≤640px media block of styles.css add:
  ```css
  .header-search.is-open { display: flex; position: absolute; top: 100%; left: 0; right: 0;
    padding: 10px 16px; background: #fff; box-shadow: var(--shadow-sm); z-index: 60; }
  ```
  In app.js, on toggle click also set `toggle.setAttribute('aria-expanded', String(isOpen))`.
  Stop toggling `.site-nav` (subnav strip is already usable) — toggle only `.header-search`.
- **City picker on mobile/tablet**: remove `.header-actions .city-picker { display: none; }` from the ≤980px block;
  instead hide `.header-cta` at ≤980px to make room. The picker button must remain tappable at all widths.
- **Carousel dots**: `.carousel-dots button { box-sizing: content-box; padding: 8px; background-clip: content-box; }`
  (24px hit area, same 8px visual).
- **City modal dismiss**: `.city-modal-close { min-height: 44px; padding: 10px 18px; display: inline-flex; align-items: center; }`

### 4.4 Geolocation coverage (app.js only)
Extend `MARKET_CITIES` to all 22 cities in config.php (path pattern `/city/{slug}-{id}`; ids in src/config.php lines 18-39:
Dubai 132, Abu Dhabi 256, Las Vegas 6, New York 1, London 2, Los Angeles 4, Orlando 5, San Francisco 7, Miami 3,
Toronto 28, Vancouver 100, Montreal 99, Edinburgh 205, Rome 124, Venice 126, Florence 123, Milan 135,
Barcelona 122, Madrid 121, Seville 144, Paris 125, Nice 174).
Extend `COUNTRY_FALLBACK` with `CA: 28, IT: 124, ES: 122, FR: 125` (keep AE/GB/US).

### 4.5 Misc routing
- Trailing-slash 301 must preserve query string:
  `$qs = (string)($_SERVER['QUERY_STRING'] ?? ''); header('Location: ' . rtrim($path, '/') . ($qs !== '' ? '?' . $qs : ''), true, 301);`
- `/dubai/{category}/{slug}`: after looking up the attraction, if `$match[1] !== ($attraction['category_slug'] ?? 'attractions')`
  emit a 301 to the canonical path.

## 5. INTEGRITY — ratings, stats, claims (hide, never fabricate)

**Rule: a number renders only if the API or content pack actually supplied it. No fallback constants.**

1. `event_card()` (pages.php): delete the star SVG + `4.9/5`; strip becomes
   `<div class="card-rating-strip"><span class="votes"><?= e($performance['category']['name'] ?? 'Event') ?></span></div>`.
   Keep the black strip styling.
2. `activity_card()`: `$rating = !empty($activity['reviews']['avg_rating']) ? number_format((float)$activity['reviews']['avg_rating'], 1) : null;`
   Wrap the whole `.card-rating-strip` in `if ($rating !== null)`. No '4.8'.
3. `render_event_detail_page()`: delete the entire `4.9 rating` span (svg + text) from `.detail-facts`. Keep city + venue.
4. `render_dubai_attraction()` (dubai-pages.php): `$rating = !empty($activity['reviews']['avg_rating']) ? (float)$activity['reviews']['avg_rating'] : 0.0;`
   (drop `?? ($attraction['rating'] ?? 4.7)`). Existing `$rating > 0` gates then work. JSON-LD aggregateRating already
   gated on `$reviewCount > 0` — leave as is.
5. Dubai hub stats band: replace "4.7 / Average Rating" tile with "Instant / E-Ticket Delivery".
   Replace "114+" with "100+ Attractions & Tours" (also in the /dubai meta description and the Abu Dhabi crosslink
   "Browse 114+ attractions"). Keep "AED 26 Prices From" only if changed to compute
   `min()` of fetched `from_price` values; otherwise replace tile with "Free / Cancellation on many tickets".
6. Abu Dhabi hub: count tile = `count($activities) . '+'` (no max() floor; hide tile if 0);
   "4.8 / Avg Rating" tile → "Instant / E-Tickets".
7. destinations-content.json: delete the `"rating"` key from all six countries' stats (renderer already skips missing keys).
8. Trust copy (see §8 for exact strings): no first-party refund/price-match/support promises anywhere.
9. preview-server.mjs must apply the identical null-guards (no `|| '4.8'`, `|| '4.7'` chains; gate "(N reviews)" on N > 0).

## 6. CSS CLEANUP (assets/styles.css)

1. Header comment: "TicketSouq" → "TickedBus".
2. Spacing scale: add `--section-pad: 32px` to `:root`; set `.section-band`, `.promo-band`, `.live-band` to
   `padding: var(--section-pad) 0`; in the ≤640px block set `--section-pad: 20px` once. Remove the
   `border-bottom` hairline from `.section-band.compact` (keep its smaller 12px padding).
3. Delete dead blocks: `.dubai-cta-band` (whole block incl. `.button-link` override),
   `.dubai-category__related-grid/-card/+hover/+icon`, `.dubai-category__hero p` rule (keep `__hero-sub`),
   the empty "Mobile slide-down nav" media block.
4. Remove all 9 `!important` flags. For `.card-onwards`, drop both `!important`s — `.section-band.dark .card-onwards`
   (0,3,0) beats `.card-onwards` (0,1,0) naturally.
5. Dedupe dubai/destination twins: comma-join each pair and delete the duplicate block —
   `.dubai-hub__search, .destination-hub__search {…}`; same for `__stats/__stats-grid/__stat`,
   `__trust-grid/__trust-card`, `__guide-content`, `__highlights-list` (dubai-category ↔ destination-city),
   `__tips-grid/__tip`, and the hero padding/size rules. (~140 lines removed; no markup change.)
6. Typography: in the dubai/destination half, convert px font sizes to the main rem scale
   (11→0.7rem, 12→0.74rem, 12.5→0.78rem, 13→0.8rem, 14→0.86rem, 15→0.95rem, 16→1rem) and all `font-weight: 900` → `800`.
7. FAQ alignment: `.dubai-faq h2 { max-width: 780px; margin: 0 auto 24px; }`.
8. Sticky offset: add `--sticky-offset: 118px` to `:root`; use in `.checkout-panel { top: var(--sticky-offset) }`
   and `.attraction-detail__sidebar { top: var(--sticky-offset) }` (fixes sidebar sliding under header).
9. Tokens: add `--green: #0a8a0a` and replace the 4 hardcoded greens; `.brand svg` radius → `var(--r-sm)`;
   either delete `--teal` or use it in `.arch-teal`; normalize dubai-section transitions to `0.15s ease`.
10. Drop the redundant `grid-template-columns` re-declaration in the 980px `.footer-cols` rule (keep `gap`).
11. New utilities: `.visually-hidden` (clip-pattern), `.prose` (§4.1), `.is-open` rules (§4.3).

## 7. SEO QUICK WINS

1. **Sitemap lastmod**: in `render_sitemap()`, stop emitting `<lastmod>` except for event URLs, where it is the
   performance `start_date.local_date` already in the payload.
2. **robots meta support**: in `render_layout()`, if `$meta['robots']` is set, print
   `<meta name="robots" content="…">`. Use it for: past events (`'noindex, follow'` when
   `start_date.local_date < today` in `render_event_detail_page()`), and `/search` pages (always `noindex, follow`).
   In `event_schema()`, when past, set offers availability `https://schema.org/SoldOut`.
   Add `Disallow: /search` to `render_robots()`.
3. **Canonicals**: listings with `q` present → canonical = bare path (no q). Pagination: include
   `'page' => $page > 1 ? $page : null` in the canonical query array so page 2+ self-canonicalizes.
4. **og:image per page**: `render_layout()` uses `$meta['image'] ?? $config['fallback_images']['hero']`;
   event/activity detail renderers pass `image_from_item(...)`. Add
   `<meta name="twitter:card" content="summary_large_image">` next to the og tags.
5. **Breadcrumbs on money pages** (cheap — helpers exist): event detail = Home > Events > {name};
   activity detail = Home > Attractions > {name}. Call `dubai_render_breadcrumbs()` at top of the detail hero and
   append `dubai_breadcrumb_schema()` to the schema array (render_layout already accepts arrays of schemas).
6. **City page cross-link**: on `/city/{slug}-{id}` pages for cities in the destinations pack, add a
   "Read the full {City} guide →" link to `/{country}/{city}` hub. (Skip canonical changes for now.)

## 8. COPY TABLE (old → new, exact strings)

| Where | Old | New |
|---|---|---|
| footer partner CTA | `Contact Today` / `href="#"` | `Contact Us` / `href="/contact"` |
| footer badge | `24/7 Customer Care` | `24/7 Partner Support` |
| footer disclaimer first sentence | `Your guide to Dubai events, attractions and experiences.` | `Your guide to events, attractions and experiences in Dubai and top destinations worldwide.` |
| all rail links (pages.php, dubai-pages.php "See all"/"View all attractions", destinations.php) | `Show all` etc. | `See All ›` |
| arch band heading | `The Best of Live Events` | `Browse by Category` |
| home cities heading | `Popular ticket cities` | `Popular Ticket Cities` |
| home chips + footer | `This weekend` | `This Weekend` |
| event detail rail | `More events` | `More Events` |
| activity detail rail | `More Dubai attractions` | `More Attractions in ' . ($activity['city']['name'] ?? 'Dubai')` |
| empty states / search page (pages.php) | `…main Dubai listings.` / `Search tickets for Dubai` / `Search Dubai events…` / `Search Dubai tickets` / `Browse the main Dubai pages…` | substitute active city: `…all ' . $cityName . ' listings.'` / `Search Tickets in {City}` / `Search {City} events, attractions and experiences.` / `Search {City} tickets` / `Browse {City} attractions to see current inventory.` (compute `$searchCity = city_for_id(active_city_id($config), $config)`) |
| trust card (dubai ×2 + destinations) | `Best Price Guarantee — Found it cheaper elsewhere? We match prices…` | `Live Prices — Prices and availability come straight from our ticket partner, so what you see is what you pay.` |
| trust card | `Free Cancellation — cancel up to 24 hours before and get a full refund` | `Free Cancellation on Many Tickets — exact policy for each ticket is shown at partner checkout.` |
| trust card | `24/7 Support — our customer care team is available round the clock` | `24/7 Partner Support — our ticket partner's support team is available around the clock for bookings and changes.` |
| dubai meta descriptions | `…free cancellation and best price guarantee` | `…instant e-tickets and free cancellation on most experiences.` |
| hub FAQs (dubai-content) | `Most tickets booked through our platform include free cancellation` | `Many tickets include free cancellation — the exact policy is shown at partner checkout before you pay.` |
| /dubai category card count | `<count> activities` (renders `11 experiences activities`) | value alone, hidden when empty |
| promo banner | `Why TickedBus / Endless entertainment. One ticket hub.` | `Featured / Burj Khalifa: At the Top` (§3.6) |
| dubai-content.json subtitles | `Dubai tickets & tours` (burj-khalifa, aquarium, skydiving) | `At the Top: Levels 124, 125 & 148` / `Tunnel Walk, Glass-Bottom Boat & Zoo` / `Palm Drop, iFly & XLine Zipline` |
| destinations.php city-card fallback | `'Tickets, tours &amp; attractions'` | `'Tickets, tours & attractions'` (let `e()` escape) |
| `114+` (3 places dubai-pages) | `114+` | `100+` |

## 9. FILE-BY-FILE WORK ORDERS

### (a) src/pages.php + src/helpers.php + assets/styles.css
1. Home reorder per §3 (exact final order listed there), dedup of `$globalEvents`, slice cities to 8,
   delete tag-grid section, delete Concerts/Theatre chips, hidden H1 + slide h2 demotion.
2. `event_card` / `activity_card` / `render_event_detail_page` rating fixes per §5.1-5.3.
3. `render_live_events_band`: heading "Browse by Category", remove "Show all", 6 inline SVGs replacing emoji.
4. `render_promo_banner`: Burj Khalifa campaign content (§3.6).
5. Footer: CTA → /contact, delete social block, badge + disclaimer copy (§8).
6. New `render_static_page()` + 3 routes + footer Discover links + sitemap entries (§4.1).
7. Routing: trailing-slash query preservation (§4.5); robots meta + past-event noindex/SoldOut;
   `/search` noindex + robots.txt Disallow; canonical q/page rules; og:image + twitter:card;
   breadcrumbs on event/activity detail; sitemap lastmod (§7).
8. Copy table rows touching pages.php (§8). Footer city links: if a Top Cities column exists, add
   `data-city-id` attributes so app.js updates the cookie.
9. ALL of §6 in styles.css (owner of that file).

### (b) src/dubai-pages.php + src/destinations.php + src/dubai-content.php + src/dubai-content.json + src/destinations-content.json
1. Curated IDs bug: in `render_dubai_category` fetch per-ID — `foreach ($activityIds as $id) { $a = api_result(fn() => $client->activity($id)); if (!empty($a['id'])) { $activities[] = $a; } }` — keep the existing query fallback when the list is empty. Same in `render_dubai_attraction` for related IDs; add `$att['related_activity_ids'] = $att['related_ids'] ?? [];` to the dubai-content.php normalization block.
2. Ratings/stats per §5.4-5.7 (attraction fallback 0.0, stat tile swaps, real counts, json rating keys deleted).
3. Trust copy per §8 (both hubs + destinations.php), meta descriptions de-guaranteed; remove hardcoded
   cancellation quick_fact default.
4. `dubai_category_icon()`: re-key `$icons` to the REAL slugs (burj-khalifa, waterparks, desert-safari, cruises,
   aquarium, museum-of-the-future, jet-ski, skydiving, hot-air-balloon, night-tours, water-sports, fountain-show,
   sky-views + existing 4 matches); reuse existing SVGs, add simple ones for the rest. No generic clock fallbacks visible on /dubai.
5. Add `short_name` to every category + attraction in dubai-content.json AND dubai-content.php (e.g. "Desert Safaris",
   "Burj Khalifa", "Waterparks", "Dubai Aquarium", "Cruises") and use it in ALL composed strings
   ("Best {x} in Dubai", "Ready to Explore {x} in Dubai?", "Related {x} Tickets", "All {x}", "More {x} in Dubai",
   breadcrumb label, hub card titles). Full SEO name stays only in category-page H1/<title>.
6. Category card count fix (`activity_count` rendered alone, hidden when empty); replace 43 stale "TicketSouq"
   meta_title strings in dubai-content.php with "TickedBus" (or delete the keys — nothing reads them).
7. /dubai hub: repurpose "Popular Dubai Experiences" grid to link the 23 attraction pages (§2.9).
8. destinations.php: city-hub rail "See All ›" hrefs → `city_path(['name' => $cityName, 'id' => $cityId])`
   (cookie-setting /city page, fixes wrong-city navigation); hub search forms get
   `<input type="hidden" name="city" value="{cityId}">` ONLY if /search honors it — otherwise leave forms as-is
   and rely on the rail fix; fix the double-escaped fallback string (§8 last row).
9. Attraction category-segment 301 (§4.5), reuse `dubai_render_faq()` in the attraction template instead of the
   inline duplicate markup.

### (c) assets/app.js
1. Extend MARKET_CITIES to all 22 cities + COUNTRY_FALLBACK CA/IT/ES/FR (§4.4 has every id).
2. Hamburger: toggle only `.header-search` `is-open`; set `aria-expanded`; keep existing city-picker/modal/carousel code.
3. No other behavior changes — do not break `tb_city` cookie flow.

### (d) preview-server.mjs (do LAST, after a-c land)
1. Mirror every markup/copy change from (a) and (b) 1:1: home order, card rating guards (remove `|| '4.8'`,
   `|| '4.7'`, unconditional `4.9`), footer (CTA, no social), static pages + routes, arch band SVGs, promo banner,
   trust copy, stat tiles, short_name usage, count fix, robots meta support, og:image, breadcrumbs on detail pages.
2. Fix existing drift: breadcrumb separator `<span>` moved inside `<li>` using `/`; gate `(N reviews)` on N > 0;
   activityDetail rating null-guard; dubaiCategory gets breadcrumbs/highlights/tips sections and per-ID activity fetch.
3. dubai-content.json: regenerate as the complete pack (all editorial fields from dubai-content.php, numeric or
   clean-string activity_count, short_name, related_ids) so preview pages match prod. If time-boxed, at minimum add
   short_name + fixed subtitles + counts so nothing renders broken.
4. Verify: `node --check preview-server.mjs` must pass; spot-check `/`, `/dubai`, one category, one attraction,
   `/events`, `/about` in the preview.

### Acceptance checklist (all owners)
- grep returns ZERO: `4.9/5`, `4.9 rating`, `'4.8'` fallback, `4.7` fallback, `href="#"`, `Best Price Guarantee`,
  `max(count($activities), 21)`, `TicketSouq` (except git history).
- Every section heading link reads `See All ›`. No section renders with fewer than 1 real item.
- Mobile 375px: hamburger opens search, city picker visible and tappable, carousel dots tappable.
- /about, /contact, /how-we-make-money render and are footer-linked; sitemap includes them.
