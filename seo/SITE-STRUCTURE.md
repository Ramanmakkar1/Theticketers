# Site Structure — TheTicketers

## Current (already built)
```
/                      — home (city-aware via tb_city cookie; crawlers see Dubai default)
/dubai                 — Dubai SEO hub          ← editorial content pack
/abu-dhabi             — Abu Dhabi SEO hub
/dubai/{category}      — Dubai category hubs (content pack)
/dubai/{cat}/{slug}    — Dubai attraction editorial pages
/{country}/{city}      — destination hubs (content pack)
/events                — live events listing (city-aware)
/attractions           — activities listing (city-aware)
/city/{slug}           — API-driven city pages (8)
/category/{slug}       — API category pages
/event/{slug}          — event detail (Event schema)
/activity/{slug}       — activity detail (Product schema)
/search, /go (noindex), /robots.txt, /sitemap.xml
```

## Additions needed (priority order)

1. **/dubai/events-this-weekend** (+ today, this-month) — static URLs for recurring searches; re-renders live data, intro updates weekly.
2. **/guide/{slug}** — editorial guides (see CONTENT-CALENDAR.md).
3. **/about, /contact, /how-we-make-money** — E-E-A-T trio; link from footer.
4. **Breadcrumbs** on detail pages + BreadcrumbList schema (Home › Dubai › Attractions › Burj Khalifa).
5. **FAQ blocks** on activity detail + category pages (FAQPage schema).

## Canonical & indexation rules
| Page | Rule |
|---|---|
| / | canonical /, content varies by cookie — crawlers always get Dubai (no cookie) ✓ |
| /events?date=*&q=* | canonical to /events (already partially done — verify q/date params) |
| /search | noindex, follow |
| /go | noindex + robots disallow ✓ |
| Past events | noindex + drop from sitemap (TODO in render_sitemap) |
| Activity pages | index only when quality gate passes (image + price + editorial block) |

## Internal linking model (hub & spoke)
- Home → city hubs → category hubs → detail pages (built)
- Every guide links to 3–5 money pages (detail/category) with descriptive anchors
- Every detail page links up to its category + city hub (breadcrumbs) and sideways to 8 related items (built)
- Footer: cities + categories (built)

## Sitemap strategy
- Split when >500 URLs: sitemap-pages.xml, sitemap-activities.xml, sitemap-events.xml, sitemap-guides.xml + index
- Events sitemap regenerated daily (cache TTL); lastmod = real change date, not today's date (current code stamps every URL with today — fix, it erodes crawler trust)
