<?php
/**
 * FAQ pool: deterministic-unique questions per page type.
 *
 * Each page picks a slice from its bucket using a hash of the entity slug,
 * so every entity gets a different mix of questions while always rendering
 * the same set for its own page (cache-safe).
 *
 * Placeholder syntax: {name}, {city}, {country}, {category}, {month},
 *                     {count}, {city_count}, {venue_count}, {min_price},
 *                     {next_date}, {next_venue}, {site_name},
 *                     {top_venues}, {top_cities}, {top_artists}, {league_name}.
 *
 * Answers stay 1-3 sentences, evergreen (no specific year), hedged where
 * appropriate, and use placeholders for any data that varies per page.
 */

return [
    // ---------------------------------------------------------------------
    // ARTIST (30 entries)
    // ---------------------------------------------------------------------
    'artist' => [
        ['q' => 'Where can I buy {name} tickets?',
         'a' => 'Pick a date on this page and continue to secure checkout on our official ticketing partner. Every {name} listing on {site_name} is backed by live inventory and instant e-ticket delivery.'],

        ['q' => 'How much do {name} tickets cost?',
         'a' => '{name} tickets currently start from {min_price}, with prices varying by city, venue and seat tier. The figures on this page are pulled live from our official ticketing partner.'],

        ['q' => 'When is {name} on tour next?',
         'a' => 'The next confirmed {name} date is {next_date}. The full upcoming tour with cities, venues and live prices is listed on this page.'],

        ['q' => 'What is the cheapest way to get {name} tickets?',
         'a' => 'The lowest-priced {name} tickets currently start from {min_price}, usually for upper-tier general admission. Booking earlier in the on-sale window typically gives access to the widest selection of value seats before they sell out.'],

        ['q' => 'Why are {name} tickets so expensive?',
         'a' => '{name} ticket prices reflect live demand, seat location and venue capacity — popular dates and premium seats tend to command the highest prices. Booking earlier in the on-sale window often unlocks lower-tier options before they disappear.'],

        ['q' => 'Do {name} tickets sell out fast?',
         'a' => 'Popular {name} dates can sell out within hours of going on sale, particularly in headline markets. The schedule on this page refreshes in real time, so any remaining inventory across {city_count} cities is reflected immediately.'],

        ['q' => 'Is it better to buy {name} tickets early or last minute?',
         'a' => 'Buying early generally gives the widest seat selection and the best chance at lower price tiers for {name}. Last-minute drops do appear closer to the show as plans change, but availability is far less predictable.'],

        ['q' => 'How many cities is {name} playing on this tour?',
         'a' => '{name} has {count} confirmed shows currently on sale across {city_count} cities, including {top_cities}. Every date links to live seat availability and pricing.'],

        ['q' => 'Which venues is {name} performing at?',
         'a' => 'The current {name} tour spans {venue_count} venues. The complete venue list with dates and live ticket prices is on this page.'],

        ['q' => 'Are {name} tickets on {site_name} authentic?',
         'a' => 'Yes — every {name} listing on {site_name} links directly to our official ticketing partner\'s secure checkout, with tickets guaranteed authentic and delivered instantly by email.'],

        ['q' => 'How are {name} tickets delivered?',
         'a' => 'Tickets are issued as mobile e-tickets by email immediately after booking. Most venues accept the QR code on your phone at the entrance — no printing needed for {name} shows.'],

        ['q' => 'Can I get a refund on {name} tickets?',
         'a' => 'Refund policies for {name} are set by the ticketing partner and vary by event. If a show is cancelled, refunds are typically processed automatically to the original payment method; rescheduled dates are usually honoured on the new date.'],

        ['q' => 'What time do doors open at a {name} show?',
         'a' => 'Doors at {name} shows typically open 60 to 90 minutes before the headline performance, though the exact time varies by venue. The precise door time for your specific date is shown on the partner checkout once you select a show.'],

        ['q' => 'What songs does {name} play live?',
         'a' => '{name} live setlists vary by tour and night, typically mixing signature hits with newer material and occasional rarities. Recent setlist patterns from this tour can be checked on fan-tracked setlist sites for a strong indication of what to expect.'],

        ['q' => 'How long is a {name} concert?',
         'a' => 'A {name} headline performance typically runs 90 minutes to two hours, with the full evening — including support acts — running longer. The exact running order is confirmed by the venue closer to the show date.'],

        ['q' => 'Will {name} have a support act?',
         'a' => 'Most {name} headline shows feature a support act, though the lineup is usually announced closer to the tour date. The specific opener for your date is shown on the venue\'s programme nearer the show.'],

        ['q' => 'Are {name} tickets transferable?',
         'a' => 'Most {name} tickets can be transferred via the partner\'s ticket app if you cannot attend, though some tour-specific anti-resale policies may apply. Transfer terms are confirmed on the checkout page before purchase.'],

        ['q' => 'What is the best seat for a {name} show?',
         'a' => 'For a {name} show, lower-tier seats facing the stage offer the best sightlines, while standing/general admission floor sections give the closest proximity. Premium club and hospitality seats sit between the two on price.'],

        ['q' => 'Can I bring kids to a {name} concert?',
         'a' => 'Age policies for {name} shows are set by each venue and may vary by city. Many arenas allow children with a paying adult, while some shows enforce a minimum age — check the venue\'s policy on the partner checkout for your specific date.'],

        ['q' => 'Where can I find {name} tour merchandise?',
         'a' => 'Official {name} tour merchandise is typically sold at the venue and via the artist\'s online store on tour dates. Your event ticket does not include merch — buy on-site or via the official store.'],

        ['q' => 'Does {name} do meet and greets?',
         'a' => 'Meet-and-greet packages for {name}, when offered, are sold as separate VIP bundles rather than included with standard tickets. Availability varies tour to tour and city to city — premium packages, if listed for your date, appear on the partner checkout.'],

        ['q' => 'How do I know if {name} tickets are real?',
         'a' => 'Every {name} ticket sold via {site_name} routes through our official ticketing partner\'s secure checkout — tickets are guaranteed authentic and back-stopped by the partner\'s buyer protection. Avoid social-media resellers and unofficial marketplaces.'],

        ['q' => 'When does {name} usually tour?',
         'a' => '{name} tour cycles are tied to album releases and seasonal touring windows, with most legs running over a several-month stretch. New dates appear on this page automatically as soon as they go on sale.'],

        ['q' => 'How can I get tickets for a sold-out {name} show?',
         'a' => 'When a {name} date sells out at primary, last-minute resale inventory occasionally appears as fans return tickets. The live inventory on this page reflects whatever is currently available — refresh closer to the show for late releases.'],

        ['q' => 'Is there a presale for {name} tickets?',
         'a' => 'Presales for {name} tours typically run through artist fan clubs, partner credit cards or venues a few days before the general on-sale. Once the public sale opens, every released seat appears in the live inventory on this page.'],

        ['q' => 'What should I bring to a {name} concert?',
         'a' => 'Bring your phone with the {name} e-ticket loaded, a valid ID matching the ticket if required, and only essentials — most venues enforce small-bag policies. Specific bag rules are listed on the venue page.'],

        ['q' => 'Are {name} concerts seated or standing?',
         'a' => '{name} shows typically combine reserved seating in the lower and upper tiers with a standing general-admission floor section. Each ticket on the partner checkout indicates the section type before you buy.'],

        ['q' => 'Where is {name} playing in {top_cities}?',
         'a' => '{name} has confirmed dates in {top_cities} as part of the current tour. Pick any city above to see the specific venue, date and live ticket prices.'],

        ['q' => 'Can I resell {name} tickets if I cannot attend?',
         'a' => 'Most {name} tickets can be listed for resale via the partner\'s official resale platform, with proceeds returned once a buyer is found. Tour-specific anti-touting rules may restrict resale on some dates.'],

        ['q' => 'How early should I arrive at a {name} show?',
         'a' => 'Arrive 45 to 60 minutes before showtime for a {name} concert to allow for entry security, finding your seat or floor spot, and beating the bar queues. Larger arenas can take longer to clear at door open.'],
    ],

    // ---------------------------------------------------------------------
    // CITY (28 entries)
    // ---------------------------------------------------------------------
    'city' => [
        ['q' => 'What events are happening in {city} right now?',
         'a' => 'There are {count} live events currently on sale in {city}, covering concerts, sports, theatre and shows. Every listing on this page shows real-time availability and pricing.'],

        ['q' => 'What is the best month to visit {city} for live events?',
         'a' => '{city} runs a year-round calendar of concerts, sports and shows, with high seasons typically driven by touring schedules and local festivals. The monthly calendars on this page show exactly what is on sale for each month.'],

        ['q' => 'How do I buy event tickets in {city}?',
         'a' => 'Browse any event above and continue to secure checkout on our official ticketing partner. Tickets are delivered instantly by email and can be shown on your phone at the venue.'],

        ['q' => 'Are ticket prices in {city} accurate?',
         'a' => 'Yes — every price on this page is pulled live from our official ticketing partner\'s inventory in {city}. Prices reflect current availability and can change with demand and seat location.'],

        ['q' => 'What types of events can I find in {city}?',
         'a' => 'This page covers concerts, sports, theatre, comedy, festivals, family shows and classical performances in {city}. Use the category filters at the top to narrow the {count} events on sale.'],

        ['q' => 'Can I find last-minute tickets in {city}?',
         'a' => 'Yes — the Today and This Weekend filters at the top of this page surface {city} events with tickets still available. Partner inventory updates in real time as seats sell and as last-minute returns come back.'],

        ['q' => 'How much do tickets cost in {city}?',
         'a' => 'Ticket prices in {city} start from {min_price}, varying widely by event type, venue and seat tier. Concerts and major sports tend to sit at the higher end; theatre and family events typically run lower.'],

        ['q' => 'Where are the main event venues in {city}?',
         'a' => 'The most-listed venues in {city} include {top_venues}. Each links to its full schedule, with on-sale events sorted by date.'],

        ['q' => 'Is {city} safe to attend events at night?',
         'a' => 'Most major {city} venue districts are well-policed and well-lit, with public transport running until late on event nights. Standard urban precautions apply — use licensed taxis or ride-shares after large arena events.'],

        ['q' => 'How early should I book tickets in {city}?',
         'a' => 'For high-demand {city} concerts and sports fixtures, booking on the day tickets release usually gives the best selection. Smaller theatre, comedy and family events generally have wider availability closer to the date.'],

        ['q' => 'Do {city} events sell out fast?',
         'a' => 'Headline tours and big-game fixtures in {city} can sell out within hours of on-sale. The live inventory on this page reflects current availability in real time — if a date is sold out, last-minute returns occasionally appear closer to the show.'],

        ['q' => 'Can I get a refund on {city} event tickets?',
         'a' => 'If a {city} event is cancelled, refunds are typically processed automatically by the partner to the original payment method. Rescheduled events are usually honoured on the new date — check the partner\'s policy on the checkout page.'],

        ['q' => 'How do I get to events in {city}?',
         'a' => 'Most major {city} venues are accessible by public transport, with ride-shares and taxis available citywide. Larger arenas have dedicated drop-off zones; specific venue directions appear on the partner checkout page.'],

        ['q' => 'Is parking available at {city} venues?',
         'a' => 'Parking at {city} venues varies — most large arenas offer on-site or partner parking that can be reserved with the ticket, while inner-city theatres rely on nearby public car parks. Specific options are shown on the venue page.'],

        ['q' => 'Are there family-friendly events in {city}?',
         'a' => 'Yes — {city}\'s family-friendly schedule includes theatre, ice shows, family-oriented concerts and museum events. Use the Family or Theatre category filters above to surface child-suitable options.'],

        ['q' => 'What sports events are on in {city}?',
         'a' => 'Live sports in {city} cover league fixtures, derby games and one-off events across the major sports. The Sports category filter above lists every fixture currently on sale with date, venue and live ticket prices.'],

        ['q' => 'Are there concerts in {city} this weekend?',
         'a' => 'Use the This Weekend filter at the top of this page to see every concert and live show with on-sale tickets in {city} for the upcoming weekend. The list refreshes automatically as new dates go on sale.'],

        ['q' => 'How does {site_name} compare to other ticket sites in {city}?',
         'a' => '{site_name} aggregates live partner inventory rather than running its own checkout, so prices match the source. The advantage is browsing every category in {city} on one page with one consistent listing format.'],

        ['q' => 'What is the dress code for events in {city}?',
         'a' => 'Most {city} concerts and sports are smart casual; theatre, opera and gala events tend to skew dressier. Specific dress requirements, when they apply, are noted on the venue\'s page.'],

        ['q' => 'When is the best time to buy tickets in {city}?',
         'a' => 'For popular {city} dates, the on-sale day usually offers the widest seat selection. For less-hyped events, prices and selection can both improve in the week or two before the show.'],

        ['q' => 'Are there free events in {city}?',
         'a' => 'Free public events and outdoor festivals in {city} appear seasonally and are usually programmed by the city or sponsors — they sit outside our ticketed inventory. The {count} events on this page are paid, ticketed shows.'],

        ['q' => 'What is the biggest venue in {city}?',
         'a' => 'The largest event venues in {city} include {top_venues}. Capacity varies from a few thousand at theatres to tens of thousands at major arenas and stadiums.'],

        ['q' => 'How can I find theatre shows in {city}?',
         'a' => 'Use the Theatre category filter at the top of this page to see every theatre, musical and stage show in {city} currently on sale. The list includes both touring productions and resident shows.'],

        ['q' => 'Do tickets in {city} include taxes and fees?',
         'a' => 'Prices shown on this page are the partner\'s headline listed price for the seat; service, delivery and tax line items are itemised on the partner\'s checkout before payment. The final total is always confirmed before you pay.'],

        ['q' => 'What if my {city} event is rescheduled?',
         'a' => 'If a {city} event is rescheduled, your tickets are typically valid for the new date without any action needed. The partner\'s policy on cancellation, refund and rebooking is shown on the checkout page.'],

        ['q' => 'Are there age restrictions at {city} concerts?',
         'a' => 'Age policies for {city} concerts are set by each venue and event — many shows admit children with a paying adult, while late-night club events typically enforce 18+. The specific policy is listed on the venue\'s page.'],

        ['q' => 'How does seating work at {city} arenas?',
         'a' => 'Most {city} arenas use reserved lower- and upper-tier seating with a standing general-admission floor section for concerts. For sports, seating is fully reserved. The seat map on the partner checkout shows the exact section before purchase.'],

        ['q' => 'Why book {city} tickets through {site_name}?',
         'a' => '{site_name} surfaces live inventory across every category in {city} on one page, so you can compare concerts, sports, theatre and family events without bouncing between sites. Checkout completes on the official partner\'s secure checkout with no markup.'],
    ],

    // ---------------------------------------------------------------------
    // VENUE (28 entries)
    // ---------------------------------------------------------------------
    'venue' => [
        ['q' => 'What events are coming up at {name}?',
         'a' => '{name} has {count} upcoming events on sale, with the next being {next_date}. The full schedule with dates and live ticket prices is on this page.'],

        ['q' => 'Where is {name} located?',
         'a' => '{name} is in {city}. Specific street address and access points are shown on the venue page on the partner checkout.'],

        ['q' => 'How do I buy tickets for events at {name}?',
         'a' => 'Pick any event on this page and continue to secure checkout on our official ticketing partner. Tickets for {name} are delivered instantly by email and can be shown on your phone at the door.'],

        ['q' => 'Does {name} host concerts?',
         'a' => '{name} hosts concerts, sports, theatre and other live events throughout the year. Filter the schedule above by category to surface exactly the type of show you are looking for.'],

        ['q' => 'Where should I park near {name}?',
         'a' => 'Parking near {name} typically includes a mix of on-site or partner car parks and nearby public parking. Larger arenas often allow parking to be pre-booked at checkout — options are shown on the partner\'s venue page.'],

        ['q' => 'How do I get to {name} by public transport?',
         'a' => 'Most major venues in {city} are well-served by public transport, with frequent services on event nights. The partner checkout page lists the closest stations and recommended routes for {name}.'],

        ['q' => 'What is the seating capacity of {name}?',
         'a' => '{name}\'s capacity varies by configuration — concerts with a standing floor typically host the largest crowds, while fully seated theatre or sports configurations are smaller. Exact capacity per event is reflected in the seat map on checkout.'],

        ['q' => 'Are there food and drinks at {name}?',
         'a' => 'Yes — {name} offers concessions and bars on event nights, with a mix of casual food and drink options. Premium hospitality areas, where available, offer table service and upgraded menus.'],

        ['q' => 'Is {name} accessible for disabled guests?',
         'a' => 'Most major venues including {name} offer accessible seating, step-free access and assistance for guests with disabilities. Specific accessibility services should be requested via the partner checkout or venue accessibility team in advance.'],

        ['q' => 'What is the bag policy at {name}?',
         'a' => 'Most events at {name} enforce a small-bag policy with security screening on entry. Large backpacks and luggage are typically prohibited or must be checked in — bring only essentials.'],

        ['q' => 'Are tickets refundable at {name}?',
         'a' => 'If an event at {name} is cancelled, refunds are processed by the ticketing partner per its policy — usually returned automatically to the original payment method. Rescheduled events are typically honoured on the new date.'],

        ['q' => 'How early do doors open at {name}?',
         'a' => 'Doors at {name} typically open 60 to 90 minutes before the start of the headline performance. Exact door times are listed per event on the partner checkout page.'],

        ['q' => 'Who has played at {name}?',
         'a' => '{name} has hosted a wide mix of headline acts and major fixtures. The current schedule includes {top_artists}, with the full upcoming lineup on this page.'],

        ['q' => 'Can I take a camera into {name}?',
         'a' => 'Most events at {name} permit phones and small personal cameras but prohibit professional camera equipment, including detachable lenses and tripods. Specific event policies are listed on the partner checkout.'],

        ['q' => 'Is smoking allowed at {name}?',
         'a' => 'Smoking is prohibited inside {name}; many venues provide designated outdoor smoking areas accessible during intervals. Vaping rules are typically the same as smoking — check the venue\'s policy.'],

        ['q' => 'Are children allowed at {name}?',
         'a' => 'Age policies at {name} are set per event — many family and concert shows admit children with a paying adult, while late-night events typically enforce a minimum age. The specific policy is on each event\'s checkout page.'],

        ['q' => 'What\'s the closest hotel to {name}?',
         'a' => '{name} sits within a short distance of multiple hotels across price points — the closest properties are typically on-site or within walking distance. Search hotel inventory in {city} for full options and rates.'],

        ['q' => 'How do I find premium seating at {name}?',
         'a' => 'Premium seating at {name} — including club seats, suites and VIP hospitality — is shown alongside standard inventory on the partner checkout. Pick an event above and look for premium tiers in the seat map.'],

        ['q' => 'Are ticket prices at {name} fair?',
         'a' => 'All prices on this page come live from our official ticketing partner and reflect what the venue and event organiser have set. Prices fluctuate with demand and seat tier — booking early typically gives access to lower-priced tiers.'],

        ['q' => 'How safe is {name} for live events?',
         'a' => '{name} operates standard major-venue security including bag checks and ticket scanning. Follow venue safety guidance and assigned exits, and use licensed transport after late shows.'],

        ['q' => 'Can I upgrade my seats at {name}?',
         'a' => 'Seat upgrades at {name} depend on remaining inventory closer to the show. Partner inventory on this page is live, so you can compare upgrade options at any time and switch if a better seat appears.'],

        ['q' => 'Does {name} have a box office?',
         'a' => 'Most major venues run a box office for collection and same-day enquiries, though most ticketing now happens digitally. For purchases via {site_name}, e-tickets are delivered instantly — no box-office collection needed.'],

        ['q' => 'What is the dress code at {name}?',
         'a' => 'Dress at {name} is typically smart casual for concerts and sports, with theatre and opera leaning slightly dressier. Premium hospitality areas may require smart attire — check the specific event\'s page.'],

        ['q' => 'Are tickets at {name} cheaper at the door?',
         'a' => 'Last-minute door pricing at {name} is rare for high-demand events and not guaranteed for any. The partner inventory on this page is the most reliable source of live pricing and availability.'],

        ['q' => 'How big is the stage at {name}?',
         'a' => 'Stage configuration at {name} varies by event — concert tours often use in-the-round or end-stage setups, while theatre uses a fixed proscenium. The seat map on the partner checkout reflects the configuration for each date.'],

        ['q' => 'Are guide dogs allowed at {name}?',
         'a' => 'Most major venues including {name} welcome service animals on event nights, with seating arranged via the venue\'s accessibility team. Advance notice on the partner checkout helps the venue make appropriate arrangements.'],

        ['q' => 'How often is the {name} schedule updated?',
         'a' => 'Listings, dates and ticket prices for {name} are pulled live from our ticketing partner, so this page always reflects what is currently on sale.'],

        ['q' => 'What\'s the best section at {name}?',
         'a' => 'At {name}, lower-tier seats facing the stage offer the best sightlines for concerts, while general-admission floor sections sit closest to the action. Upper-tier seating typically offers the best price-to-view ratio.'],
    ],

    // ---------------------------------------------------------------------
    // CITY_CATEGORY (25 entries)
    // ---------------------------------------------------------------------
    'city_category' => [
        ['q' => 'What {category} events are coming up in {city}?',
         'a' => 'There are {count} {category} events currently on sale in {city}. Every listing on this page shows the date, venue and live ticket price from our official ticketing partner.'],

        ['q' => 'How much do {category} tickets cost in {city}?',
         'a' => '{category} tickets in {city} currently start from {min_price}, with prices varying by venue, seat tier and date. Premium and headline shows tend to command the highest prices.'],

        ['q' => 'Where are {category} events held in {city}?',
         'a' => '{category} events in {city} are hosted at venues including {top_venues}. Each venue listing links to its full schedule and live ticket prices.'],

        ['q' => 'When is the best time to buy {category} tickets in {city}?',
         'a' => 'For popular {category} shows in {city}, booking on the on-sale day typically gives the widest seat selection. For less-hyped events, last-minute availability often improves in the week or two before the show.'],

        ['q' => 'Can I get {category} tickets at face value in {city}?',
         'a' => 'All {category} tickets on this page are priced live by our official ticketing partner — there is no markup added by {site_name}. Prices reflect what the venue and event organiser have set for each seat tier.'],

        ['q' => 'Are these {category} listings official?',
         'a' => 'Yes — every {category} listing on this page links to our official ticketing partner\'s secure checkout. {site_name} may earn a commission, but no markup is added to the price you pay.'],

        ['q' => 'Do {category} events in {city} sell out quickly?',
         'a' => 'Popular {category} dates in {city} can sell out within hours of on-sale, particularly for headline acts and high-profile fixtures. Live inventory on this page reflects whatever is currently available in real time.'],

        ['q' => 'What is the biggest {category} venue in {city}?',
         'a' => 'The largest {category} venues in {city} include {top_venues}. Capacity varies from a few thousand at theatres up to tens of thousands at major arenas and stadiums.'],

        ['q' => 'Are there family-friendly {category} events in {city}?',
         'a' => 'Yes — {city} programmes a range of family-friendly {category} events, particularly during school holidays. Look for events tagged as suitable for all ages in the schedule above.'],

        ['q' => 'How do I get to {category} events in {city}?',
         'a' => 'Most major {city} venues hosting {category} events are accessible by public transport and ride-share, with on-site parking at larger arenas. Specific directions are shown on each event\'s checkout page.'],

        ['q' => 'What is the dress code for {category} in {city}?',
         'a' => 'Dress for {category} in {city} is typically smart casual; classical, opera and gala-format events lean slightly dressier. Premium hospitality areas may have stricter dress codes — check the specific event\'s policy.'],

        ['q' => 'How long do {category} events typically last in {city}?',
         'a' => '{category} events in {city} typically run two to three hours including any intervals or support acts. Exact running times are confirmed by the venue closer to the show.'],

        ['q' => 'What time do {category} events start in {city}?',
         'a' => '{category} events in {city} typically start in the evening, with weekday shows from around 7 to 8pm and weekend matinees from mid-afternoon. Exact start times are listed per event on the partner checkout.'],

        ['q' => 'Can I find last-minute {category} tickets in {city}?',
         'a' => 'Last-minute {category} availability in {city} depends on the event — popular dates may have only premium seats remaining, while less-hyped shows often have value tickets close to showtime. The partner inventory on this page updates live.'],

        ['q' => 'What\'s the best venue for {category} in {city}?',
         'a' => 'The top {category} venues in {city} include {top_venues}, each known for strong acoustics or sightlines for the format. Pick a venue above to see its specific schedule and live pricing.'],

        ['q' => 'Are there outdoor {category} events in {city}?',
         'a' => 'Outdoor {category} programming in {city} is seasonal — most outdoor venues run summer and warm-season schedules. The listings above include both indoor and outdoor events with the venue name shown for each.'],

        ['q' => 'How do I find cheap {category} tickets in {city}?',
         'a' => 'The cheapest {category} tickets in {city} currently start from {min_price}. Booking early in the on-sale window or selecting upper-tier seats typically gives the lowest prices.'],

        ['q' => 'Are {category} tickets in {city} refundable?',
         'a' => 'If a {category} event in {city} is cancelled, refunds are typically processed automatically to the original payment method. Rescheduled events are usually honoured on the new date — partner refund terms are listed on checkout.'],

        ['q' => 'How are {category} tickets in {city} delivered?',
         'a' => '{category} tickets in {city} are delivered as mobile e-tickets by email immediately after purchase. Most venues accept the QR code on your phone — no printing required.'],

        ['q' => 'What is the cheapest seat for {category} in {city}?',
         'a' => 'Upper-tier and rear-of-venue seats typically offer the lowest {category} prices in {city}, currently from {min_price}. Specific seat-level pricing is shown on the partner checkout for each event.'],

        ['q' => 'Are {category} events in {city} suitable for tourists?',
         'a' => 'Yes — {city}\'s {category} schedule is a strong draw for visitors, particularly during peak tourist seasons. Combining a show with a city visit is straightforward; book the ticket here and pair with hotels and travel separately.'],

        ['q' => 'When does the {category} season start in {city}?',
         'a' => '{category} season timing in {city} varies by format — touring concerts run year-round, while season-based programming follows local cycles. The schedule on this page reflects every dated event currently on sale.'],

        ['q' => 'How early should I arrive at {category} events in {city}?',
         'a' => 'Arrive 45 to 60 minutes before a {category} show in {city} to clear security and find your seat. Larger arenas can take longer to fill — earlier arrival also gives time at concessions before the start.'],

        ['q' => 'Where can I find {category} events in {city} this weekend?',
         'a' => 'The This Weekend filter at the top of this page surfaces every {category} show with on-sale tickets in {city} for the upcoming weekend. The list refreshes as new dates come on sale.'],

        ['q' => 'Are there {category} events in {city} for all budgets?',
         'a' => 'Yes — {category} pricing in {city} ranges from value upper-tier seats from {min_price} up to premium hospitality and front-row seats. The seat map on the partner checkout shows every available tier and price.'],
    ],

    // ---------------------------------------------------------------------
    // EVENT (25 entries)
    // ---------------------------------------------------------------------
    'event' => [
        ['q' => 'When does {name} start?',
         'a' => 'The published start time for {name} is shown on the event listing above and confirmed on the partner checkout. Doors typically open 60 to 90 minutes before the headline start.'],

        ['q' => 'How much are {name} tickets?',
         'a' => '{name} tickets currently start from {min_price}, with prices varying by seat tier and demand. The seat map on the partner checkout shows live availability and exact pricing per section.'],

        ['q' => 'Where is {name} taking place?',
         'a' => '{name} takes place at {next_venue} in {city}. The exact address and access information appear on the partner checkout page.'],

        ['q' => 'Are {name} tickets refundable?',
         'a' => 'Refund policies for {name} are set by the ticketing partner. Cancelled events typically refund automatically to the original payment method; rescheduled dates are usually honoured on the new date.'],

        ['q' => 'How are tickets for {name} delivered?',
         'a' => 'Tickets for {name} are delivered as mobile e-tickets by email immediately after booking. Most venues accept the QR code on your phone at the entrance.'],

        ['q' => 'Is it safe to buy {name} tickets on {site_name}?',
         'a' => 'Yes — every {name} listing on {site_name} links to our official ticketing partner\'s secure checkout, with payment, personal data and ticket authenticity handled by the partner.'],

        ['q' => 'How long does {name} last?',
         'a' => '{name} typically runs two to three hours including any support acts or intervals. Exact running time is confirmed by the venue closer to the date.'],

        ['q' => 'What time do doors open for {name}?',
         'a' => 'Doors for {name} typically open 60 to 90 minutes before the headline start time. The precise door time is listed on the partner checkout for this event.'],

        ['q' => 'Can I bring a bag to {name}?',
         'a' => '{name} is subject to the venue\'s standard bag policy — most enforce a small-bag rule with security screening on entry. Bring only essentials and check the venue\'s page for specific bag dimensions.'],

        ['q' => 'Is {name} suitable for children?',
         'a' => 'Age policies for {name} are set by the venue and may apply. Most family and concert events admit children with a paying adult, while late-night formats enforce a minimum age — the specific policy is on the partner checkout.'],

        ['q' => 'Where should I sit for {name}?',
         'a' => 'For {name}, lower-tier seats facing the stage offer the best sightlines; standing or floor sections are closest but unreserved. Upper-tier seats typically give the best price-to-view balance.'],

        ['q' => 'Can I resell {name} tickets if I cannot attend?',
         'a' => 'Most {name} tickets can be transferred or listed on the partner\'s official resale platform if you cannot attend. Specific transfer rules are confirmed on the checkout page before purchase.'],

        ['q' => 'How early should I arrive at {name}?',
         'a' => 'Arrive 45 to 60 minutes before {name}\'s start time to clear security, find your seat and queue at the bar if needed. Larger arenas typically take longer to fill.'],

        ['q' => 'Will {name} have a support act?',
         'a' => 'Most concert headline shows feature a support act, though it may not be confirmed until closer to the date. Check the venue\'s programme for {name} for the latest running order.'],

        ['q' => 'Is parking available for {name}?',
         'a' => 'Parking options for {name} depend on the venue — most large arenas offer on-site or partner parking that can be pre-booked. Inner-city theatres rely on nearby public car parks. Specific options are on the partner checkout.'],

        ['q' => 'What is the dress code for {name}?',
         'a' => 'Smart casual is the safe choice for {name} unless the venue states otherwise. Premium hospitality areas may have stricter requirements; specific policies appear on the partner checkout.'],

        ['q' => 'Are last-minute {name} tickets available?',
         'a' => 'Live partner inventory on this page reflects any last-minute availability for {name}. Late returns and additional releases occasionally appear closer to the show — refresh the listing as showtime nears.'],

        ['q' => 'How do I find the cheapest {name} seats?',
         'a' => 'The cheapest {name} tickets currently start from {min_price}, typically in upper-tier or rear sections. The seat map on the partner checkout shows every available tier and exact price.'],

        ['q' => 'Can I take photos at {name}?',
         'a' => 'Most events permit phone photography but prohibit professional cameras and detachable lenses. The specific policy for {name} is set by the venue and listed on the partner checkout.'],

        ['q' => 'What happens if {name} is cancelled?',
         'a' => 'If {name} is cancelled, the partner typically refunds tickets automatically to the original payment method. Rescheduled events are usually honoured on the new date — the partner\'s policy is shown on checkout.'],

        ['q' => 'Is the venue for {name} accessible?',
         'a' => '{next_venue} offers accessible seating, step-free access and assistance services for guests with disabilities. Specific accessibility services for {name} should be requested via the partner checkout in advance.'],

        ['q' => 'How do I get to {name}?',
         'a' => '{next_venue} in {city} is typically reachable by public transport, ride-share or taxi. Larger venues have dedicated drop-off zones; specific directions appear on the partner checkout.'],

        ['q' => 'Is food available at {name}?',
         'a' => '{next_venue} offers concessions and bars on event nights, with a mix of casual food and drink options. Premium hospitality, where available, offers table service.'],

        ['q' => 'When is the best time to book {name}?',
         'a' => 'For {name}, booking earlier in the on-sale window typically gives the widest seat selection across tiers. High-demand events can sell out quickly — the live inventory on this page reflects current availability.'],

        ['q' => 'How do I know my {name} ticket is real?',
         'a' => 'Every {name} ticket purchased via {site_name} routes through our official ticketing partner\'s secure checkout — tickets are guaranteed authentic and backed by the partner\'s buyer protection.'],
    ],

    // ---------------------------------------------------------------------
    // ARTIST_IN_CITY (18 entries)
    // ---------------------------------------------------------------------
    'artist_in_city' => [
        ['q' => 'Is {name} playing in {city}?',
         'a' => 'Yes — {name} has {count} upcoming show(s) in {city}. The full list with dates, venues and live ticket prices is on this page.'],

        ['q' => 'How much are {name} tickets in {city}?',
         'a' => '{name} tickets in {city} currently start from {min_price}, varying by seat tier and date. The figures on this page come live from our official ticketing partner.'],

        ['q' => 'Where does {name} play in {city}?',
         'a' => '{name} plays {next_venue} in {city} on the current tour. The seat map and live pricing are on the partner checkout.'],

        ['q' => 'How do I buy {name} tickets for {city}?',
         'a' => 'Pick the {city} date above and continue to secure checkout on our official ticketing partner. Tickets are delivered instantly by email — show the QR code on your phone at the venue.'],

        ['q' => 'When should I book {name} tickets in {city}?',
         'a' => 'For {name} in {city}, booking earlier in the on-sale window typically gives the best seat selection across tiers. Popular dates can sell out quickly.'],

        ['q' => 'How are {name} tickets in {city} delivered?',
         'a' => '{name} tickets for {city} are delivered as mobile e-tickets by email immediately after booking. Most venues accept the QR code on your phone — no printing needed.'],

        ['q' => 'What time does {name} start in {city}?',
         'a' => 'The published start time for the {name} show in {city} is shown on the listing above and confirmed on the partner checkout. Doors typically open 60 to 90 minutes earlier.'],

        ['q' => 'Are {name} tickets in {city} refundable?',
         'a' => 'If the {name} show in {city} is cancelled, refunds are typically processed automatically by the partner. Rescheduled dates are usually honoured on the new date.'],

        ['q' => 'Where should I sit for {name} in {city}?',
         'a' => 'For {name} at {next_venue}, lower-tier seats facing the stage offer the best sightlines, while standing or floor sections are closest. The seat map on checkout shows every tier.'],

        ['q' => 'Are {name} tickets in {city} authentic on {site_name}?',
         'a' => 'Yes — every {name} listing for {city} on {site_name} links to our official ticketing partner\'s secure checkout, with tickets guaranteed authentic.'],

        ['q' => 'How early should I arrive at the {name} show in {city}?',
         'a' => 'Arrive 45 to 60 minutes before the {name} show in {city} to clear security and find your seat. Larger arenas can take longer to fill.'],

        ['q' => 'Is there a presale for {name} in {city}?',
         'a' => 'Presales for {name} in {city} typically run through fan clubs, partner credit cards or the venue a few days before public on-sale. Once general sale opens, every released seat appears in the live inventory on this page.'],

        ['q' => 'How do I get to {next_venue} in {city}?',
         'a' => '{next_venue} in {city} is typically reachable by public transport, ride-share or taxi, with on-site parking at larger arenas. Specific directions appear on the partner checkout.'],

        ['q' => 'Is parking available for {name} in {city}?',
         'a' => 'Parking near {next_venue} varies — most large arenas offer on-site or partner parking that can be pre-booked. Inner-city venues typically rely on nearby public car parks.'],

        ['q' => 'Can I get last-minute {name} tickets in {city}?',
         'a' => 'Last-minute availability for {name} in {city} depends on demand — popular dates may have only premium seats remaining. The partner inventory on this page reflects current availability.'],

        ['q' => 'What\'s the cheapest seat for {name} in {city}?',
         'a' => 'The cheapest {name} tickets in {city} currently start from {min_price}, typically in upper-tier or rear sections. Specific seat-level pricing is on the partner checkout.'],

        ['q' => 'Is the {name} show in {city} all ages?',
         'a' => 'Age policies for {name} in {city} are set by the venue and may apply. Most concert shows admit children with a paying adult — the specific policy is on the partner checkout.'],

        ['q' => 'How long is the {name} show in {city}?',
         'a' => 'The {name} headline performance typically runs 90 minutes to two hours, with the full evening — including any support — running longer. The exact running time is confirmed by the venue closer to the date.'],
    ],

    // ---------------------------------------------------------------------
    // LEAGUE (18 entries)
    // ---------------------------------------------------------------------
    'league' => [
        ['q' => 'What {name} games are coming up?',
         'a' => 'There are {count} upcoming {name} games on sale. The full schedule with dates, arenas and live ticket prices is on this page.'],

        ['q' => 'How do I buy {name} tickets?',
         'a' => 'Pick any game on this page and continue to secure checkout on our official ticketing partner. {name} tickets are delivered instantly by email with live seat availability and pricing.'],

        ['q' => 'When is the {name} season?',
         'a' => 'The {name} regular season runs through the bulk of the calendar, with playoffs at the end of the season and the championship finals capping the year. Every confirmed dated game on sale is listed above.'],

        ['q' => 'How much do {name} tickets cost?',
         'a' => '{name} ticket prices vary widely by game, opponent, arena and seat tier. Live pricing for every listed game is shown on the partner checkout — playoff and rivalry games typically command higher prices than mid-season fixtures.'],

        ['q' => 'What seat tiers are available at {name} games?',
         'a' => '{name} seating typically spans upper-tier general admission, lower-bowl and courtside or sideline seats, plus premium club and suite options at most arenas. Specific tiers are shown live on the partner checkout.'],

        ['q' => 'Are {name} tickets refundable?',
         'a' => 'If a {name} game is cancelled, refunds are typically processed automatically by the partner. Rescheduled games are usually honoured on the new date — partner refund terms are on the checkout.'],

        ['q' => 'How are {name} tickets delivered?',
         'a' => '{name} tickets are delivered as mobile e-tickets by email immediately after booking. Show the QR code on your phone at the arena — no printing required.'],

        ['q' => 'When do {name} playoff tickets go on sale?',
         'a' => '{name} playoff tickets typically go on sale once teams clinch their playoff spot, with later rounds released as series advance. New games appear on this page automatically as soon as tickets are released.'],

        ['q' => 'Can I get cheap {name} tickets?',
         'a' => 'Upper-tier and standing-room {name} tickets typically offer the lowest prices, with mid-season regular-season games generally cheaper than rivalry or playoff fixtures. Live pricing is shown on the partner checkout.'],

        ['q' => 'How early should I arrive at a {name} game?',
         'a' => 'Arrive 45 to 60 minutes before tip-off, puck drop or first pitch at a {name} game to clear security, find your seat and queue at concessions. Pre-game events typically run for 30 minutes before the start.'],

        ['q' => 'Is parking available at {name} arenas?',
         'a' => 'Most {name} arenas offer on-site or partner parking that can be pre-booked with the ticket. Specific parking options and pricing are shown on each arena\'s page.'],

        ['q' => 'Are {name} tickets transferable?',
         'a' => 'Most {name} tickets can be transferred via the partner\'s ticket app, or listed on the official resale platform. Transfer terms are confirmed on the checkout page before purchase.'],

        ['q' => 'What\'s the best seat at a {name} game?',
         'a' => 'For {name}, lower-bowl seats give the closest action while club and premium seats add hospitality. Upper-tier seats typically offer the best price-to-view balance for casual fans.'],

        ['q' => 'How do I find {name} home games?',
         'a' => 'Home games for any {name} team are listed in the team-specific pages linked from this hub. Each team page shows dates, venues and the home arena.'],

        ['q' => 'Can I bring kids to a {name} game?',
         'a' => '{name} games are generally family-friendly — most arenas admit children with a paying adult and offer family-zone seating. Specific age and ticket policies are set by each arena.'],

        ['q' => 'What food is available at {name} games?',
         'a' => 'Concessions at {name} arenas typically offer a wide range of food and drink from casual snacks to local specialities. Premium and club areas add table service and upgraded menus.'],

        ['q' => 'How often is this {name} schedule updated?',
         'a' => 'Listings, dates and prices for {name} are pulled live from our partner, so this page always reflects what is currently on sale.'],

        ['q' => 'Are {name} game tickets authentic?',
         'a' => 'Yes — every {name} listing on {site_name} links to our official ticketing partner\'s secure checkout, with tickets guaranteed authentic and backed by buyer protection.'],
    ],

    // ---------------------------------------------------------------------
    // TEAM (18 entries)
    // ---------------------------------------------------------------------
    'team' => [
        ['q' => 'What {name} games are coming up?',
         'a' => 'There are {count} upcoming {name} games on sale, with the next being {next_date}. The full schedule with dates, venues and live ticket prices is on this page.'],

        ['q' => 'How much are {name} tickets?',
         'a' => '{name} ticket prices vary by date, opponent and seat tier — pick any game above to see live pricing and seat availability on our partner checkout.'],

        ['q' => 'How do I buy {name} tickets?',
         'a' => 'Pick any game on this page and continue to secure checkout on our official ticketing partner. {name} tickets are delivered instantly by email with live seat availability.'],

        ['q' => 'When are {name} home games?',
         'a' => '{name} home games are the dates played in their home city. The schedule above lists every game with city and venue, so home dates are easy to spot at a glance.'],

        ['q' => 'How do I buy {name} away game tickets?',
         'a' => 'Pick any game above where the city is not {name}\'s home city — that is an away fixture. Tickets are sold by the home venue\'s ticketer; checkout completes securely with instant e-ticket delivery.'],

        ['q' => 'Are season tickets available for {name}?',
         'a' => 'Season tickets for {name} are sold directly by the team, not through this listing. This page lists single-game tickets currently on sale — pick any game to see live pricing.'],

        ['q' => 'What time do {name} games start?',
         'a' => 'Start times for {name} games are listed per fixture on the schedule above and confirmed on the partner checkout. Doors and pre-game activities typically begin 60 to 90 minutes before the start.'],

        ['q' => 'Are {name} tickets refundable?',
         'a' => 'If a {name} game is cancelled, refunds are typically processed automatically by the partner. Rescheduled fixtures are usually honoured on the new date.'],

        ['q' => 'How are {name} tickets delivered?',
         'a' => '{name} tickets are delivered as mobile e-tickets by email immediately after booking. Show the QR code on your phone at the venue.'],

        ['q' => 'When do {name} playoff tickets go on sale?',
         'a' => '{name} playoff tickets are released once the team clinches its postseason spot. New games appear on this page automatically as soon as tickets are released.'],

        ['q' => 'What\'s the cheapest seat for a {name} game?',
         'a' => 'The cheapest {name} tickets are typically in the upper tier or rear of venue. Live seat-level pricing for every listed game is on the partner checkout.'],

        ['q' => 'Can I find last-minute {name} tickets?',
         'a' => 'Last-minute availability for {name} games depends on demand — popular fixtures may have only premium seats remaining. The partner inventory on this page reflects what is currently available.'],

        ['q' => 'How early should I arrive at a {name} game?',
         'a' => 'Arrive 45 to 60 minutes before start at a {name} game to clear security, find your seat and use concessions. Pre-game programming typically runs in the 30 minutes before the start.'],

        ['q' => 'Can I bring kids to a {name} game?',
         'a' => '{name} games are generally family-friendly — most home arenas admit children with a paying adult, with family-zone seating where available. Specific age policies are set by each venue.'],

        ['q' => 'What food is available at {name} home games?',
         'a' => 'Concessions at {name}\'s home arena typically offer a wide range of food and drink options. Premium and club areas add table service and upgraded menus.'],

        ['q' => 'How do I get to a {name} home game?',
         'a' => '{name}\'s home arena is typically accessible by public transport, ride-share and on-site parking. Specific directions and parking options are shown on the partner checkout.'],

        ['q' => 'Are {name} tickets transferable?',
         'a' => 'Most {name} tickets can be transferred via the partner\'s ticket app, or listed on the official resale platform if you cannot attend. Transfer terms are confirmed on the checkout.'],

        ['q' => 'How often is the {name} schedule updated?',
         'a' => 'Listings, dates and prices for {name} are pulled live from our partner so this page always reflects what is currently on sale.'],
    ],

    // ---------------------------------------------------------------------
    // MONTHLY_EVENTS (18 entries)
    // ---------------------------------------------------------------------
    'monthly_events' => [
        ['q' => 'What events are happening in {city} in {month}?',
         'a' => 'There are {count} confirmed events in {city} for {month}, covering concerts, sports, theatre and shows. Every listing shows date, venue and live starting price from our official ticketing partner.'],

        ['q' => 'How do I find {month} tickets in {city}?',
         'a' => 'Browse the full list above and click any event to see live seat availability and pricing. Checkout completes securely on the partner site and tickets are delivered by email instantly.'],

        ['q' => 'Why is {month} a good time to visit {city}?',
         'a' => '{city} typically programmes a mix of touring concerts, league sports fixtures and stage productions in {month}. The listings on this page are pulled live, so the schedule reflects what is actually on sale.'],

        ['q' => 'Are tickets refundable if a {month} event is cancelled?',
         'a' => 'If a {month} event in {city} is cancelled, refunds are handled by the ticket partner per its policy — usually returned to the original payment method automatically. Rescheduled events are typically honoured on the new date.'],

        ['q' => 'How often is this {month} schedule updated?',
         'a' => 'Listings, dates and prices are pulled live from our partner, so this page always reflects what is currently on sale for {city} in {month}.'],

        ['q' => 'What\'s the weather like in {city} in {month}?',
         'a' => 'Weather in {city} during {month} varies by season — check a local forecast closer to the date for outdoor events. The schedule above lists both indoor and outdoor events with the venue shown for each.'],

        ['q' => 'How much do tickets cost in {city} in {month}?',
         'a' => 'Ticket prices for {city} events in {month} currently start from {min_price}, varying by event type and seat tier. Concerts and major sports tend to sit higher; theatre and family events typically run lower.'],

        ['q' => 'Are there concerts in {city} in {month}?',
         'a' => 'Yes — {city}\'s {month} schedule includes concerts among the {count} events on sale. Use the category filter or browse the full list above to surface every concert with on-sale tickets.'],

        ['q' => 'Are there sports events in {city} in {month}?',
         'a' => 'Live sports in {city} for {month} cover league fixtures and one-off events across the major sports. The Sports category filter surfaces every fixture with on-sale tickets.'],

        ['q' => 'When should I book {month} tickets in {city}?',
         'a' => 'For popular {month} dates in {city}, booking on the on-sale day typically gives the widest seat selection. Less-hyped events often have improving availability and prices in the weeks before the show.'],

        ['q' => 'Are {city} events in {month} suitable for families?',
         'a' => 'Yes — {month} in {city} typically includes a mix of family-friendly theatre, family concerts and seasonal shows. Look for events tagged as suitable for all ages.'],

        ['q' => 'How early should I book hotels around {month} in {city}?',
         'a' => 'For major {city} event weekends in {month}, hotels close to the venue can sell out a few weeks ahead. Book ticket and hotel together for best availability.'],

        ['q' => 'What\'s the biggest event in {city} in {month}?',
         'a' => 'The headline events in {city} for {month} are listed at the top of the schedule above, sorted by date. Filter by category to surface the most-anticipated concerts, sports and shows.'],

        ['q' => 'Can I find theatre shows in {city} in {month}?',
         'a' => 'Yes — {city} typically programmes a mix of touring productions and resident shows in {month}. Use the Theatre category filter to surface every stage show with on-sale tickets.'],

        ['q' => 'How do I get around {city} for {month} events?',
         'a' => '{city}\'s public transport, ride-share and taxi network covers the main venue districts. On event nights, transport typically runs later — check post-show options before booking.'],

        ['q' => 'Are there outdoor events in {city} in {month}?',
         'a' => 'Outdoor programming in {city} for {month} depends on the season. The listings above include both indoor and outdoor events with the venue shown for each.'],

        ['q' => 'How early should I arrive at a {month} event in {city}?',
         'a' => 'Arrive 45 to 60 minutes before showtime for a {city} event in {month} to clear security and find your seat. Larger arenas typically take longer to fill at door-open.'],

        ['q' => 'Are {city} tickets in {month} on {site_name} authentic?',
         'a' => 'Yes — every {month} listing for {city} on {site_name} links to our official ticketing partner\'s secure checkout, with tickets guaranteed authentic and delivered instantly.'],
    ],

    // ---------------------------------------------------------------------
    // VENUE_CATEGORY (18 entries)
    // ---------------------------------------------------------------------
    'venue_category' => [
        ['q' => 'What {category} are coming up at {name}?',
         'a' => 'There are {count} upcoming {category} at {name}. The full list with dates and live ticket prices is on this page.'],

        ['q' => 'How do I buy {category} tickets at {name}?',
         'a' => 'Pick any event above and continue to secure checkout on our official ticketing partner. Tickets for {name} are delivered instantly by email so you can show them on your phone at the door.'],

        ['q' => 'How much are {category} tickets at {name}?',
         'a' => 'Prices for {category} at {name} vary by event, date and seat tier. Live pricing for every listed event is shown on the partner checkout.'],

        ['q' => 'How often is the {name} {category} schedule updated?',
         'a' => 'Listings, dates and prices for {category} at {name} are pulled live from our ticketing partner, so this page always reflects what is currently on sale.'],

        ['q' => 'Is there a dress code for {category} at {name}?',
         'a' => '{name} does not enforce a strict dress code for most {category} — smart casual is the safe choice. Premium hospitality areas may require smart attire.'],

        ['q' => 'Where should I park for {category} at {name}?',
         'a' => 'Parking near {name} typically includes on-site or partner parking that can be pre-booked at checkout, plus nearby public options. Specific arrangements are listed on each event\'s page.'],

        ['q' => 'How do I get to {name} for {category}?',
         'a' => '{name} is typically reachable by public transport, ride-share and on-site parking at larger venues. Specific directions appear on the partner checkout.'],

        ['q' => 'What is the best seat for {category} at {name}?',
         'a' => 'For {category} at {name}, lower-tier seats facing the stage offer the best sightlines. Upper-tier seats typically give the best price-to-view balance.'],

        ['q' => 'Are {category} tickets at {name} refundable?',
         'a' => 'If a {category} event at {name} is cancelled, refunds are typically processed automatically to the original payment method. Rescheduled events are usually honoured on the new date.'],

        ['q' => 'How are {category} tickets at {name} delivered?',
         'a' => '{category} tickets for {name} are delivered as mobile e-tickets by email immediately after purchase. Show the QR code on your phone at the door.'],

        ['q' => 'What time do doors open for {category} at {name}?',
         'a' => 'Doors at {name} typically open 60 to 90 minutes before the headline start of each event. Exact door times are confirmed on the partner checkout for each date.'],

        ['q' => 'Are children allowed at {category} at {name}?',
         'a' => 'Age policies for {category} at {name} are set per event — many shows admit children with a paying adult, while late-night formats enforce a minimum age. The specific policy is on each event\'s checkout page.'],

        ['q' => 'Are last-minute {category} tickets available at {name}?',
         'a' => 'Last-minute availability for {category} at {name} depends on demand — popular events may have only premium seats remaining. The live inventory on this page reflects current availability.'],

        ['q' => 'What is the closest public transport to {name}?',
         'a' => '{name} is typically served by nearby stations on the local transit network. Specific routes and the closest stop are shown on the partner checkout page.'],

        ['q' => 'Is {name} accessible for guests with disabilities?',
         'a' => '{name} offers accessible seating, step-free access and assistance services. Specific accessibility needs should be requested via the partner checkout in advance.'],

        ['q' => 'Can I bring food and drink to {name}?',
         'a' => 'Outside food and drink are typically not permitted at {name} — concessions and bars are available inside. Specific policies for the event are listed on the partner checkout.'],

        ['q' => 'What\'s the cheapest seat for {category} at {name}?',
         'a' => 'The cheapest {category} seats at {name} are typically upper-tier or rear sections. Live seat-level pricing is on the partner checkout.'],

        ['q' => 'Are {category} tickets at {name} authentic on {site_name}?',
         'a' => 'Yes — every {category} listing for {name} on {site_name} links to our official ticketing partner\'s secure checkout, with tickets guaranteed authentic.'],
    ],
];
