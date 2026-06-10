# Implementation Roadmap — TickedBus

## Phase 0 — Pre-launch tech checklist (week 0)
- [ ] Buy domain, HTTPS, set SITE_URL env
- [ ] Google Search Console + Bing Webmaster verification
- [ ] GA4 (or Plausible) + outbound-click event tracking (clicks.log already exists — surface it)
- [ ] OG image per page type (currently one generic Unsplash hero for all pages)
- [ ] Favicon + web manifest
- [ ] Fix sitemap lastmod (real dates, not today) and add past-event expiry
- [ ] /about, /contact, /how-we-make-money pages
- [ ] Fill footer social URLs + partner "Contact Today" mailto
- [ ] Self-host the Roboto font files or add font-display swap check (CWV)
- [ ] Cache HTML output server-side (shared hosting: file cache per URL, 10-min TTL) — API-dependent pages can be slow

## Phase 1 — Foundation (weeks 1–4)
- Submit sitemap; request indexing for hubs
- Ship breadcrumbs + BreadcrumbList schema
- FAQ blocks on top-20 activity pages (FAQPage schema)
- Publish weeks 1–4 content
- llms.txt + verify robots allows AI crawlers
- Set up rank tracking (free: GSC; paid: SE Ranking ~$50/mo)

## Phase 2 — Expansion (weeks 5–12)
- Publish weeks 5–12 content (pillar pages live)
- Static date pages (/dubai/events-this-weekend …)
- Activity-page quality gate + editorial blocks on top 100 activities
- First links: UAE travel blogger outreach (5–10), HARO/Qwoted travel queries, Reddit r/dubai value-posts (no spam), Google Business-adjacent directories
- IndexNow pings on content publish (Bing/Copilot visibility)

## Phase 3 — Scale (months 4–6)
- Expand editorial coverage to all ~500 Dubai/AbuDhabi activities
- Arabic version of top-10 pages IF traffic justifies (hreflang)
- Digital PR: one data piece ("What Dubai tourists pay: ticket price index 2026") — these earn links passively
- Wire other-city hubs with content packs (London/NYC long-tail only)

## Phase 4 — Authority (months 7–12)
- Monthly price-index updates (recurring links + freshness)
- Newsletter capture ("This weekend in Dubai" email — owns repeat traffic)
- Review/UGC layer if feasible
- Double down on whatever GSC shows is working; prune zero-traffic thin pages quarterly

## Risks & mitigations
| Risk | Mitigation |
|---|---|
| Thin-affiliate classification | Quality gate + editorial layer before scale-indexing |
| API content = duplicate across affiliate sites | Never index raw API text alone; rewrite descriptions |
| New domain sandbox (~3–6 mo) | Front-load Bing/IndexNow (faster), social distribution, don't judge before month 4 |
| Shared-host slowness | HTML caching layer; CWV budget: LCP < 2.5s on 4G |
| Event pages expiring | Auto-noindex past events; 301 recurring events to artist/venue pages later |
