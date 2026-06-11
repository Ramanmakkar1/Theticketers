# TheTicketers — Aggregator SEO + Data Plan

**Pivot:** from "Dubai affiliate" to a **global ticket-discovery aggregator** that ranks on
artist / team / venue / event / attraction keywords by being **data-rich and always current**.

**Two data sources, one merged view:**
1. **HelloTickets** (primary — this is where our Impact commission is).
2. **Ticketmaster Discovery API** (fallback + fill — far bigger US inventory).

Rule: *HelloTickets first. If HelloTickets has no/weak data for an entity, pull Ticketmaster.
Merge both, de-dupe, show as much as possible. Never show past events.*

---

## 1. The three competitor keyword universes (what we're chasing)

| List | Source | Dominant clusters | Who serves it best |
|---|---|---|---|
| **List 1** | a tour aggregator (Songkick/Ents24-type, ~2M traffic) | `{artist} tickets/tour/concert/upcoming events`, music venues | HelloTickets has the artists |
| **List 2** | **HelloTickets.com** (our partner) | attraction tickets, EU football fixtures, travel guides | We have the same inventory via their API |
| **List 3** | **Ticketmaster.com** | US sports (NBA/NFL/MLB), US venues, Broadway, comedy, schedules | **Ticketmaster API** (HelloTickets is thin here) |

**Strategic takeaway:** List 1 + List 2 ≈ HelloTickets' strength (global concerts, EU football,
EU attractions). List 3 ≈ Ticketmaster's strength (US everything). The HT→TM fallback is exactly
what lets ONE site cover all three.

---

## 2. Keyword clusters → page types (the whole site map)

| Cluster | Example keywords | Page type | URL | Primary → Fallback |
|---|---|---|---|---|
| **Artists / concerts** | billie eilish tickets, morgan wallen tour, olivia dean concert | Performer page | `/artist/{slug}` *(exists)* | HT performer → TM attraction |
| **Sports teams** | yankees, lakers, real madrid, arsenal tickets | Team page (same template) | `/artist/{slug}` or `/team/{slug}` | HT performer → TM attraction |
| **Sports fixtures** | arsenal vs chelsea tickets, yankees vs mets | Event page | `/event/{slug}` *(exists)* | HT performance → TM event |
| **Venues** | madison square garden, red rocks, sphere las vegas, anfield | Venue page **(NEW)** | `/venue/{slug}` | TM venue → HT venue |
| **Broadway / theatre** | hamilton, wicked, moulin rouge | Show page (performer template) | `/artist/{slug}` | TM (Arts&Theatre) → HT |
| **Comedy** | nate bargatze, matt rife, bert kreischer | Performer page | `/artist/{slug}` | TM → HT |
| **Attractions** | british museum tickets, london eye, disneyland paris | Attraction page | `/attraction/{slug}` | HT activities **only** (TM has none) |
| **Schedules / leagues** | nba schedule, mlb games tonight, premier league | League hub **(NEW)** | `/nba`, `/mlb`, `/{league}` | TM events → HT |
| **Travel guides** | things to do in X, X weather, best time to visit X | Editorial guide **(NEW)** | `/guide/{slug}` | none — content only |

---

## 3. Data architecture — the HT→TM fallback engine

New layer in `src/providers/`:

```
interface EventSource {
    findEntity(name, type)        // artist | team | venue | show
    upcomingEvents(entityRef)     // future-dated only
    searchEvents(keyword, geo)
}
class HelloTicketsSource implements EventSource   // wraps existing HelloTicketsClient
class TicketmasterSource  implements EventSource   // wraps Discovery API (key needed)

class UnifiedCatalog {
    // 1. ask HelloTickets first
    // 2. if empty OR < N future rows -> ask Ticketmaster
    // 3. merge, de-dupe by (date + venue + city), filter date >= today, sort date asc
    // 4. tag each row: source = ht|tm, out = /go (Impact) | tm affiliate link
}
```

- **Caching:** every TM/HT response cached to `storage/cache` (TTL 1–6h). TM cap is 5 req/s,
  5,000/day — caching keeps us well under.
- **Image reuse:** `bin/fetch-tm-images.php` already downloads TM artwork to `assets/media`.
  Same key powers the live data layer.

### Evergreen / "current only" (hard requirement)
- Every list filters `date >= today` (HT: `local_date_from=today` + `is_sellable=true`;
  TM: `startDateTime=now`, `sort=date,asc`).
- Past events → `noindex` + removed from sitemap automatically.
- **No years baked into URLs or titles.** Title is evergreen ("Billie Eilish Tickets &
  Tour Dates") and the page always renders the *next* future shows. This means one page
  ranks for "...2025", "...2026", "tour dates", "next concert" forever — instead of dead
  year pages. We deliberately do NOT build "{artist} 2023/2024" pages.
- Sitemap inclusion gate: entity must have ≥1 future event **and** ≥40% unique on-page text.

---

## 4. Gap analysis — what we HAVE vs DON'T (verified against the live API)

**HAVE today via HelloTickets** (probed live):
- **7,420 performers** — artists + global sports teams. Confirmed present: Billie Eilish (#112),
  Bad Bunny, The Weeknd, Real Madrid, Chelsea FC, Arsenal, Man United, Liverpool, Juventus,
  Napoli, Bayern, Boston Red Sox.
- **103,938 performances** — concerts + fixtures, with venue + date + price.
- **Attractions** — British Museum, London Eye, Disneyland Paris all return results.
- Strong on: **global concerts, European football, European attractions** (= Lists 1 & 2).

**GAP — HelloTickets is thin, Ticketmaster fills it** (this is most of List 3):
- **US sports teams** — e.g. `New York Knicks → NONE` in HelloTickets. US NBA/NFL/MLB/NHL,
  college, minor league = Ticketmaster territory.
- **US venues** — MSG, Red Rocks, Sphere Las Vegas, Allegiant, MetLife, Fenway → Ticketmaster.
- **Broadway + US comedy + US festivals** → Ticketmaster.
- → **This is exactly why the TM fallback exists.** List 3 ≈ "the stuff HelloTickets doesn't have."

**NOT covered by EITHER ticket source (content gap):**
- Travel/info guides — "things to do in X", "X weather in November", "best time to visit X",
  "is X safe". Huge volume in List 2, **zero ticket inventory**. Pure editorial. Decision needed:
  invest in a `/guide/` content engine (compounding long-tail) or skip for now.

---

## 5. Build phases (recommended order)

- **Phase 0 (no TM key needed):** provider scaffolding + future-only filtering helper +
  `/venue/{slug}` pages from HelloTickets data + enhance `/artist` pages (merge-ready).
- **Phase 1 (needs TM key):** `TicketmasterSource` live + `UnifiedCatalog` fallback wired into
  artist + venue + event pages. One proven vertical slice = the headline feature working.
- **Phase 2:** sports/team coverage + league/schedule hubs (`/nba`, `/mlb`, `/premier-league`) +
  fixture pages ("arsenal vs chelsea", "yankees vs mets").
- **Phase 3:** attractions at scale (HT) + Broadway/comedy (TM).
- **Phase 4:** travel-guide editorial engine + programmatic sitemap expansion + internal linking.

## 6. Status / open decisions
- ✅ **TM monetization SOLVED.** Ticketmaster Impact affiliate link confirmed
  (`ticketmaster.evyy.net/c/7072456/264167/4272`, account RamanTML) — honours `?u=` deep links +
  `subId1` tracking. Wired into `/go` (routes by destination domain) and verified live: a TM click
  lands on the exact event page with the click tracked. **So TM fallback earns commission too.**
- ⛔ **Ticketmaster Discovery API key** — STILL NEEDED for Phase 1. This is a *separate* credential
  from the affiliate link above: the affiliate link is how clicks earn; the API key
  (`apikey` from developer.ticketmaster.com, what `bin/fetch-tm-images.php` already uses as
  `TICKETMASTER_API_KEY`) is how we *fetch the event data*. Without it the fallback has no data.
- ❓ **Travel guides** — build the editorial engine (Phase 4) or skip the no-inventory keywords?
