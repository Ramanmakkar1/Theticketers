# TickedBus SEO Strategy

**Goal:** 1,000–2,000 daily organic visitors. **Realistic timeline:** months 6–12 for a brand-new domain, IF the content plan below ships consistently. Months 1–3 are about foundations and indexation, not traffic.

**Positioning:** "BookMyShow-style discovery for Dubai (and 7 more cities) — compare live prices for attractions, concerts, theatre and sport, book on the official partner."

---

## 1. The honest constraint: affiliate sites and Google

Google's site reputation and "thin affiliate" policies punish sites that only re-list partner inventory. TickedBus already re-renders HelloTickets data — that alone will NOT rank. Every indexable page must add something the partner page doesn't have:

- Comparison context ("Burj Khalifa 124/125 vs 148 vs Sky — which level is worth it")
- Local logistics (best time slots, metro stop, dress code, Ramadan hours)
- Price history / "from AED X" freshness
- Editorial picks ("our pick", "skip this if…")

**Rule of thumb: no page goes in the sitemap unless ≥40% of its visible text is ours, not API data.**

## 2. Keyword universe (Dubai first — 80% of effort)

### Tier 1 — Money keywords (attraction tickets; high volume, high competition)
| Keyword pattern | Examples | Intent |
|---|---|---|
| {attraction} tickets | burj khalifa tickets, dubai aquarium tickets, aquaventure tickets, museum of the future tickets, dubai frame tickets, ain dubai, global village tickets | Transactional |
| {attraction} price / offers | burj khalifa ticket price, desert safari dubai price | Transactional |
| {experience} dubai | desert safari dubai, dhow cruise dubai, helicopter ride dubai, jet ski dubai | Transactional |

### Tier 2 — Discovery keywords (huge volume, feeds Tier 1)
- things to do in dubai (+ "at night", "with kids", "free", "this weekend", "in summer/indoors")
- dubai events / events in dubai today / concerts in dubai 2026
- dubai itinerary 3 days, dubai on a budget
- abu dhabi day trip from dubai, ferrari world tickets, louvre abu dhabi tickets

### Tier 3 — Event long-tail (programmatic; low competition, compounding)
- {artist} dubai tickets / {artist} coca-cola arena
- {show} dubai (La Perle, etc.)
- dubai events {month} {year}, new year's eve dubai fireworks tickets

### Other cities (20% of effort, later phases)
Same patterns: "vegas shows tonight", "broadway tickets cheap", "london theatre tickets", "universal orlando tickets". These are brutally competitive in the US/UK — treat them as programmatic long-tail only (specific event pages), not head terms.

## 3. Programmatic SEO plan (your scale lever)

| Page type | URL | Count | Quality gate |
|---|---|---|---|
| Activity detail | /activity/{slug} | ~500+ | Only index if it has image + price + ≥150 words of our copy (template: intro, "good to know", FAQ block) |
| Event detail | /event/{slug} | rotating | noindex past events; auto-expire from sitemap |
| City hub | /city/{slug} | 8 | Each needs 300+ words of editorial intro |
| Category × city | /category/{slug} | ~30 | Add intro paragraph + FAQ per category |
| Date pages | /events?date=weekend → make static /dubai/events-this-weekend | 4–6 | "This weekend in Dubai" is a recurring-search goldmine |
| Guides (blog) | /guide/{slug} | 2–3/week | Fully editorial, internal-link to money pages |

Auto-generate FAQ blocks (with FAQPage schema) from structured facts you already have: opening dates, cancellation policy, supplier, price-from. That's unique-enough assembly if phrased editorially.

## 4. E-E-A-T for an affiliate

- About page: who runs TickedBus, why Dubai expertise (photos, real name or brand persona)
- Visible "How we make money" disclosure page (you already have the footer line — make it a page, link it)
- Author byline + bio on every guide
- Last-updated dates on guides and price-bearing pages
- Real contact page (email), social profiles filled in (footer icons currently `#`)

## 5. GEO / AI search (ChatGPT, Perplexity, AI Overviews)

- llms.txt at root listing top guides + city hubs
- FAQ schema + direct-answer first paragraphs ("Burj Khalifa tickets cost from AED 191 …") — AI engines quote these
- Keep facts in tables (prices, hours) — highly citable
- Allow GPTBot/PerplexityBot in robots.txt (you currently allow all — keep it)

## 6. KPI targets

| Metric | Launch | Month 3 | Month 6 | Month 12 |
|---|---|---|---|---|
| Indexed pages | 50 | 300 | 700 | 1,200+ |
| Daily organic visits | 0 | 100–300 | 400–800 | 1,000–2,000 |
| Ranking keywords (top 100) | 0 | 500 | 2,500 | 8,000+ |
| Referring domains | 0 | 10 | 40 | 100+ |
| Outbound CTR (clicks→partner) | — | 8% | 12% | 15% |

Traffic math at month 12: ~1,200 pages × avg 1–2 visits/day on the long tail + 3–5 head pages ranking page-1 = 1,500–2,500/day. The plan works only with the content cadence in CONTENT-CALENDAR.md.
