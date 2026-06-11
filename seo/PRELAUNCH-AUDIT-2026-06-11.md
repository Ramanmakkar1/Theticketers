# Pre-Launch SEO Audit — TheTicketers
**Date:** 2026-06-11 · **Method:** 6 parallel specialist audits (technical, schema, sitemap, content/E-E-A-T, GEO/AI-search, performance) against http://localhost:8000 + source review.
**SEO Health Score: ~66/100** (technical 75 · content 60 · on-page 70 · schema 65 · performance 55 · AI-readiness 70 · images 50, per claude-seo weights). The fundamentals are genuinely strong — the score is dragged down by a handful of systematic, fixable issues.

---

## A. LAUNCH GATES — fix/verify before deploy

### A1. `SITE_URL` must be pinned in production (and the scheme detection has a proxy bug)
Every canonical, og:url, schema `@id`, Offer URL, sitemap `<loc>`, robots.txt `Sitemap:` line and llms.txt link is built from `site_url` (src/config.php:9-13). The fallback derives scheme only from `$_SERVER['HTTPS']` and **ignores `X-Forwarded-Proto`** — yet .htaccess:7-9 explicitly anticipates an SSL-terminating proxy. On exactly that shared-host setup, every absolute URL site-wide is generated as `http://…`, which .htaccess then 301s: all canonicals point at redirects, the sitemap lists redirecting URLs.
**Fix:** `SetEnv SITE_URL https://yourdomain.com` in .htaccess (or host panel). Optionally also read `X-Forwarded-Proto` in config.php as belt-and-braces. **Verify post-deploy:** curl one page + robots.txt + sitemap on the live host and confirm https + correct domain everywhere.
**Failed-if:** live canonical shows `http://` or wrong host.

### A2. /nba "23 upcoming NBA games" is factually false — flagship citable page contaminated
The league hub's count and ItemList include FIBA World Cup qualifiers, "Girls'/Boys' Semi-Finals" youth games, summer-league and G-League rows (unfiltered TM classification query). llms.txt markets these pages as "safe to cite"; About promises "honest answers". A verifiably false headline statistic on the page AI engines are explicitly invited to quote is a launch blocker for the AI-citation strategy.
**Fix:** filter league hub queries by proper TM classification/league id (NBA only), or derive the count from the filtered list. Check /nfl /mlb /nhl /mls for the same leak.
**Failed-if:** any non-league event appears in a league hub list, or count ≠ rendered rows.

### A3. Production web-server config (code sets none of these)
Measured on the dev server, must be verified live:
- **Compression**: no gzip/brotli → 79-92 KB HTML, 71 KB CSS ship uncompressed (~5× inflation). mod_deflate is in .htaccess — verify it's active on the host.
- **Cache headers on /assets/**: zero Cache-Control/ETag today. .htaccess has mod_expires rules (7d CSS/JS, 30d images) but no mod_headers fallback; if the host lacks mod_expires, a 71 KB stylesheet re-downloads every page view. Add `Cache-Control: public, max-age=31536000, immutable` + add a version fingerprint (`styles.css?v=<hash>`) in pages.php so it's safe.
- **HSTS**: absent. Add `Strict-Transport-Security: max-age=31536000; includeSubDomains` once SSL is confirmed.
- **expose_php = Off** (X-Powered-By: PHP/8.3.14 leaks on every response).

---

## B. HIGH — fix in code now (pre-launch or first days)

### B1. Duplicate indexable URLs for the same entity
- **Teams:** `/artist/golden-state-warriors` AND `/team/golden-state-warriors` both 200 + self-canonical + sitemapped (also lakers, heat, yankees, dodgers). 301 or canonicalize artist→team for team-named performers; drop from sitemap.
- **Events:** cross-source slug pairs, e.g. `/event/goose-toronto-2026-06-13` + `/event/goose-the-band-toronto-2026-06-13`. Dedupe HT/TM slugs before sitemap emission.

### B2. ItemList schema points at external ticketmaster.* URLs
On /nba (23/23), /events (12/13), /venue/msg (50/50), /team/knicks, /category/concerts — ListItem.url hands the entity to Ticketmaster, is carousel-ineligible (Google requires same-domain canonical URLs), and trains AI engines to cite TM instead of us. The Dubai pages do it right (internal /activity/ URLs). Restrict ItemList to internally-hosted items or drop the markup on TM-dominated lists. (Root: event_canonical_url() in helpers.php:779-785 feeding item_list_schema.)

### B3. Event startDate in UTC "Z" instead of venue-local offset
`2026-08-02T16:00:00Z` for an 8 PM Dubai show; the artist-page FAQ states "8:00 PM" while schema implies 4 PM. Google explicitly wants local time + offset (`2026-08-02T20:00:00+04:00`). Rich results will show wrong times and the self-contradiction is a data-quality strike.

### B4. Sitemap churn + dishonest lastmod
- 192/481 entries are /event/* and dozens are same-day; they flip to `noindex` within ~24h (pages.php:1269), training Google to distrust the sitemap. Gate event entries to dates ≥ ~3 days out.
- All `<lastmod>` = generation date (today, every day) → Google ignores lastmod entirely. Emit real change dates or drop the tag. 96 /artist/* entries have no lastmod at all (separate code path — unify).
- Sitemap is rebuilt from live APIs per request (only the generic 1h client cache behind it) — a Googlebot fetch on cold cache fans out into many upstream API calls. Cache the rendered sitemap.

### B5. Thin programmatic templates with triple-duplicated boilerplate
/team/new-york-knicks = 542 words, /event/def-leppard = 516 (198 in `<main>`); venue pages have no editorial at all. The same generated sentence is used as meta description AND intro AND first FAQ answer verbatim (pages.php:2736-2748 venue, 3089-3102 team). Replicated across every team/venue this is doorway territory pre-link-equity. Minimum fix: differentiate the three slots; better: add a short unique editorial block per league/venue tier, or inventory-gate the thin tail to noindex.

### B6. "San Antonio, New York" — city-implode garble
`implode(', ', array_slice($cities, 0, 4))` (pages.php:~3092) renders "3 upcoming New York Knicks games in San Antonio, New York" — in the SERP meta description, the intro, and the FAQ. Tiny fix ("in San Antonio and New York" / "across N cities"), outsized trust impact.

### B7. LCP + CLS structural issues (carry to production as-is)
- Hero on every template is a CSS `background-image` div — invisible to the preload scanner, no `fetchpriority`, and on grid pages the first cards are `loading="lazy"` (deprioritizing the actual LCP). Emit `<link rel="preload" as="image" fetchpriority="high">` for the known hero URL per template; make the first ~4 card images eager.
- 36-42 of ~44 imgs per page lack width/height → textbook CLS on card grids. Add dimensions or CSS aspect-ratio in the card renderer.
- No preconnect to image CDNs (imgix, cloudinary, tripadvisor, ticketm.net, unsplash) while fonts get two.
- A few home cards hot-link TM `_SOURCE` originals (1-5 MB masters in 300px cards) — route through the existing fetch-tm-images.php cache.

### B8. Stale/fabricated static numbers contradicting the live-data promise
/about: "every count, date and starting price … never from a static copy". But /dubai FAQ hard-codes "Aquaventure (AED 115)" while the live card on the same page says AED 110; "save 10-20% vs walk-up", "up to 50% off peak", "sunset slots cost AED 50-100 more" are unsourced. One editing pass: delete or live-source every static number in dubai-content.json / destinations-content.json FAQs.

### B9. Event/category metadata wastes the highest-intent templates
- Event titles: "Def Leppard Tickets | TheTicketers" (35 chars — no city/venue/date; collides across same-performer events). Make it "Def Leppard Tickets — Coca-Cola Arena, Dubai, Aug 2, 2026 | TheTicketers".
- Team/category/venue titles run 67-70 chars (truncation). 
- /category/concerts claims "across the US, Canada, the UK, Europe and the Middle East" but renders 11 Dubai-only results (session-city filter leaking into an indexable global page) — make indexable category pages city-neutral or honest about scope.

---

## C. MEDIUM — first weeks

1. **Freshness signals (GEO):** no dateModified/datePublished in any JSON-LD, no Last-Modified headers — in the most freshness-sensitive vertical. Add `dateModified` to Event/ItemList graphs; it's truthful (data is live).
2. **Entity corroboration:** Organization has no `sameAs`, site links zero social/external profiles; WebSite/Organization `@id`s only exist on the homepage while inner pages embed anonymous duplicate nodes. Reference `…/#organization` from inner pages; create + link 2-3 real profiles (X/Instagram/LinkedIn).
3. **Breadcrumbs missing** on venue/team/artist/category/league templates (helper exists, dubai-pages.php:40-55 — wire it in). BreadcrumbList present on event/dubai/city pages.
4. **Dubai attraction pages:** TouristAttraction + Product as two unlinked nodes with aggregateRating duplicated on both (partner-API reviews presented as page-entity rating — policy-gray), availability hardcoded InStock (dubai-pages.php:746-788). Link via `@id`, single rating, real availability.
5. **Event-page citability:** 198 words of chrome. Add a 2-3 sentence generated summary ("X plays {venue}, {city} on {date}; doors …; tickets from {price}") + 2 data-driven FAQs — same pattern as artist pages.
6. **Redirect/404 hygiene:** two-hop 301 chains (trailing-slash then case-fold, pages.php:7-22 — collapse to one); 404/error pages emit self-canonical + no robots meta (render_error_page, pages.php:2473-93 — drop canonical, add noindex); mixed-case /venue/ slugs hard-404 while every other section 301s.
7. **"Best Burj Khalifa in Dubai"** heading template (singular landmarks) + "events in Dubai" lowercase H1 + "Events In Dubai" title-case — polish the templates.
8. **"More Events" rail** on event pages is globally random (Dubai page recommends Flensburg/Cork shows) — filter by same city/country, then same category.
9. **Currency:** /artist/bad-bunny prices Madrid/London shows in AED (session-currency on indexable global pages). Price by event market on indexable pages.
10. **llms.txt:** blockquote hard-codes "6 countries" while the site has 10 (pages.php:2299 — derive from config['markets']); one stale venue slug 301s (santiago-bernabeu).
11. **CSS/JS/fonts:** minify styles.css (71 KB) + app.js (17 KB); trim 7 font weights to 3-4 or self-host woff2 subsets.
12. **Images:** media cache is 1,292 JPEGs / 0 WebP (432 files >100 KB) — add a WebP pass to resize-media.php; request CDN widths matching render size (w=1600 Unsplash in 400px tiles); og:image shared Unsplash fallback across MSG/Burj Khalifa/home — widen page-specific coverage; alt text on fallback stock images claims to depict the artist.
13. **HTML entities inside JSON-LD** ("Girls&#39;") — escape at template layer only, not in schema values.
14. **Icons/logos:** apple-touch-icon is SVG (iOS ignores → blank tile; ship 180×180 PNG); Organization logo is SVG (provide PNG for schema).
15. **AboutPage/ContactPage schema + Organization.contactPoint** on /about and /contact (currently zero JSON-LD — cheap E-E-A-T anchors).
16. **Verify .md/seo/ blocking on the live host** (REDESIGN-SPEC.md still contains TicketSouq/TheTicketers brand history; .htaccess blocks it on Apache — confirm prod actually enforces it).
17. **addressCountry** non-ISO ("United States Of America" vs "AE"); location.address as mangled flat strings ("13 5 Street , Dubai") instead of PostalAddress; SportsTeam `sport: "NBA"` should be "Basketball"; Event missing performer/organizer/endDate/description/offers.validFrom.

---

## D. What's already solid (verified passing)
Server-rendered content (zero JS dependency) · real HTTP 404s for unknown slugs · trailing-slash + case 301 normalization · param-stripped canonicals · /search noindex+crawlable and /go 302+noindex+disallow both correct (incl. no open redirect) · 13 AI crawlers welcomed coherently · valid JSON-LD on 13/13 sampled pages, no @id collisions · offers.url does NOT point at /go · sitemap xmllint-valid, zero noindex leakage, zero past events, all sampled URLs 200+self-canonical · affiliate disclosure stack exemplary (page + footer + at-CTA + rel="sponsored nofollow") · About names operator + both partners · city/Dubai editorial genuinely unique (1,100-1,600 words) · artist FAQs computed from live data · no analytics bloat, app.js deferred, display=swap, no placeholder text.

## E. Suggested execution order
1. A1-A3 (deploy gates + server checklist) — same day as deploy.
2. A2 league filter + B6 city-join + B3 timezone + B9 event titles — small PHP fixes, biggest trust/SERP wins.
3. B1 dupes + B4 sitemap gating/lastmod/caching — one pages.php session.
4. B2 ItemList policy + B7 LCP/CLS template fixes.
5. B5/B8 content pass (template differentiation + static-number purge).
6. Section C as a rolling backlog.

**Leading indicators post-launch:** GSC Coverage "Submitted URL marked noindex" should stay ~0 (B4); "Duplicate, Google chose different canonical" ~0 (B1); Event rich-result impressions trending up (B3/C17); CrUX LCP/CLS green within first field-data window (B7+A3).
