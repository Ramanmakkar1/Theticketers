# TheTicketers — Production Readiness Plan

Status: **plan only, nothing implemented** (per request, while a parallel Dubai-hub build is editing `pages.php` / `styles.css` / `app.js`).
Author: diagnosis run against the **live HelloTickets API** and the **real PHP app** (PHP 8.3.14 now installed at `~/.local/bin/php`).

---

## 1. Evidence-based findings (measured, not guessed)

### A. The API has NO images — anywhere
Every endpoint (list **and** detail, activities **and** performances) returns these fields only:
- activity: `id, title, url, city, country, from_price, currency, cancellation_policy, reviews, status, supplier_name`
- performance: `id, name, url, ticket_groups_count, start_date, price_range, event_id, venue, performers, category, is_sellable`

There is **no `image`/`images`/`photo` field**. This is the entire reason every card shows a repeated stock photo — `image_from_item()` always falls through to keyword/category fallbacks.

**This is a data-source fact, not a bug in our code.**

### B. Real images ARE harvestable (verified)
HelloTickets is powered by Tiqets. Each item's public page (`item.url`) contains real photos on `aws-tiqets-cdn.imgix.net/images/content/<hash>.{jpg,jpeg,png,webp}`.
- `og:image` meta is JS-injected (not in raw HTML) → don't rely on it.
- Grep the first `aws-tiqets-cdn.imgix.net/images/content/...` URL in the HTML → reliable, unique hero per item. Verified across 3 items, 3 distinct correct photos.
- Bonus: imgix supports query params, so we can request exact poster crops: `?w=600&h=900&fit=crop&crop=edges&auto=format,compress`.

### C. The API is healthy but strict
- `/v1/performances` **requires** `local_date_from` + `local_date_to`, plus a valid `limit` and `page`. Missing/!valid → `400 {"error_message":"limit or page - incorrect"}`.
- `/v1/activities` supports only `city_id`, `query`, `limit`, `page`. It does **NOT** support `category_id` (→ 400).
- `/v1/cities` is **dead** (returns an error object). Cities must stay in static `config.php` (they already do).
- Categories are real: **Sports=1, Concerts=2, Theatre=3 are EVENTS**; every other id (Museums=15, Desert Experiences=29, Cruises=13, …) is an **ACTIVITY** category.

### D. Real route behaviour (real PHP + live API, measured today)
| Route | Cards | Verdict |
|---|---|---|
| `/` | 25 | OK |
| `/attractions` | 24 | OK |
| `/city/dubai-132` | 13 | OK — **city pages work in real PHP** (Node preview faked them) |
| `/city/abu-dhabi-256` | 20 | OK |
| `/activity/<id>` | detail + 7 related | OK |
| `/search?q=burj` | 9 | OK |
| `/events` | 1 | Thin inventory (data reality) |
| `/category/concerts-2` | 1 | Thin inventory (data reality) |
| `/category/desert-experiences-29` | 0 | **BUG** (activity categories) |
| `/dubai`, `/abu-dhabi` | 0 live cards | Parallel build, not yet wired to inventory |

### E. Dubai is attractions-rich, events-poor
- Dubai activities: **114**. Dubai sellable performances: **~1**.
- Product implication: lead Dubai with **attractions/experiences**; treat **events** as a global/worldwide section, not a Dubai headline. This is a product decision for the owner.

---

## 2. The activity-category bug (exact root cause + fix)
`render_category_page()` sends non-event categories to `render_activities_page()` using the category **name** as the search `query`:
- `query="Desert Experiences"` → **0** results (name doesn't appear in titles)
- `query="desert"` → **9** results (keyword works)

**Fix (no API support for activity categories, so use curated keywords):**
Add a map `activity category id/name → search keyword(s)`, e.g.
`29 Desert Experiences → "desert"`, `13 Cruises → "cruise"`, `15 Museums → "museum"`, `16 Landmarks and Skyscrapers → "burj khalifa"`, `10 Tours → "tour"`, etc.
For categories with no good keyword, hide the link rather than render an empty page.

---

## 3. Implementation plan (in priority order)

### P1 — Real image enrichment layer  *(biggest visible win)*
1. **`bin/enrich-images.php`** (CLI, runs on the host via cron or manually):
   - Pull current inventory: activities (per market city) + sellable performances (global + per city, with required date params).
   - For each unseen `type-id`, fetch `item.url` (1 req/sec, set a browser UA), extract first imgix content URL, normalise with poster params.
   - Write/merge `storage/images.json`: `{ "activity-2459": "https://aws-tiqets-cdn.imgix.net/.../x.jpeg?w=600&h=900&fit=crop&auto=format", "event-2435967": "..." }`.
   - Idempotent: skip ids already present unless `--refresh`. Log harvest hits/misses.
2. **`image_from_item()`** (helpers.php): load `storage/images.json` once per request (static cache); look up `type-id` first → real image; else keep existing keyword/category/stock fallback (graceful when harvest missed).
3. **Never fetch at render time.** Page render only reads the JSON map.
4. Refresh cadence: cron weekly (or on deploy). Document in README.
5. Node `preview-server.mjs` reads the **same** `storage/images.json` (keeps preview honest), if preview is kept at all (see P4).

### P2 — Fix data correctness
- **Activity categories:** implement the keyword map (section 2); hide link-less categories.
- **Standardise every `performances` call:** always include `local_date_from/to` + valid `limit`/`page`. Audit `render_events_page`, `render_category_page`, home `globalEvents`, sitemap — eliminate the `400 limit/page` errors seen in the log.
- **Events strategy:** when a city has < N sellable events, blend in global sellable events (home already does this; extend to `/events` and event-category pages) so they're never near-empty.

### P3 — Honest empty/thin states
- Where inventory is genuinely thin (Dubai events), show a purposeful module ("Popular events worldwide", "Browse Dubai attractions instead") rather than a 1-card section or a blank grid.

### P4 — Validation now that PHP runs
- Use the real app for validation: `php -S 127.0.0.1:8099 index.php` (PHP 8.3.14 installed).
- Add a `.claude/launch.json` entry for the PHP server so previews run the **real** code, not the Node mirror.
- Decide the fate of `preview-server.mjs`: keep as a quick static-design sandbox, or retire it to remove the "fake routes" confusion. Recommendation: **retire** for validation; the PHP server is now the source of truth.

### P5 — Deploy readiness (unchanged stack: vanilla PHP)
- Confirm `storage/` + `storage/cache/` writable; set real `SITE_URL`; cron for `bin/enrich-images.php`.
- The owner must still supply: real social URLs + partner contact link (currently `href="#"`), and confirm the events-vs-attractions positioning (section 1E).

---

## 4. Validation checklist (target after P1–P3)
- [ ] Every card on every route shows a **unique real photo** (or a sensible category fallback, logged as a miss).
- [ ] `/`, `/attractions`, `/events`, `/search`, all 8 `/city/*`, event categories (1,2,3), 6+ activity categories, `/activity/*`, `/event/*` → all 200, no near-empty grids, no `400` in the server log.
- [ ] `php -S` run clean (no warnings/notices) across the above.
- [ ] `images.json` refresh script runs idempotently and logs hit-rate.

---

## 5. Open decisions for the owner
1. **Positioning:** Dubai is attractions-first (114) vs events (~1). Confirm we lead with attractions and make events a worldwide section.
2. **Coordination:** the Dubai-hub build is editing the same files — I should own the image+data layer once that work lands (or it should adopt this spec) to avoid clobbering.
3. **Preview mirror:** retire `preview-server.mjs` for validation in favour of real PHP? (recommended)
