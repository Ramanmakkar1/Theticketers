<?php
declare(strict_types=1);

/* =========================================================================
   Curated category content — the ONLY /category/{slug} pages we ask Google
   to index. Every entry is hand-written and unique to its category; anything
   not in this map renders noindex,follow and stays out of the sitemap, so
   raw API categories ("vatican-city", "sintra-and-cascais", …) can never
   become thin doorway pages.

   Shape per slug:
     h1               page heading (global keyword form — inventory is global)
     meta_title       <title> without the site name suffix
     meta_description ~150-160 chars, keyword + value proposition
     intro            array of plain-text paragraphs (escaped on render)
     links            optional [label => href] internal-link chips
     faqs             array of ['q' =>, 'a' =>] — category-specific, no boilerplate
   ========================================================================= */

return [

    'concerts' => [
        'h1' => 'Concert Tickets',
        'meta_title' => 'Concert Tickets — Tour Dates, Prices & Cities',
        'meta_description' => 'Find concert tickets for artists on tour worldwide. Compare dates, venues and live prices, then check out securely on our official ticketing partner.',
        'intro' => [
            'Find tickets for artists on tour right now — stadium headliners, arena shows, festivals and intimate club gigs across the United States, Canada, the UK, Europe and the Middle East. Every listing shows the date, the venue and the current starting price, pulled live from our official ticketing partners, so what you see is what is actually on sale.',
            'Start with your city to see who is playing near you, or search for an artist to see their full tour in one place. Each artist page lists every confirmed date with seat prices, and new shows appear automatically the moment tickets are released.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'This weekend' => '/events?date=weekend',
            'Top venues' => '/venues',
        ],
        'faqs' => [
            ['q' => 'When do concert tickets go on sale?',
             'a' => 'Most tours announce dates a few months ahead, with general on-sale following presales by a few days. This page updates live, so a show appears here as soon as its tickets are released — checking the artist\'s page is the fastest way to catch a new date.'],
            ['q' => 'How can I find cheaper concert tickets?',
             'a' => 'Prices are usually lowest soon after on-sale and for mid-week shows. Listings here show the live starting price for every date, so comparing two cities on the same tour often reveals a much cheaper night for the same setlist.'],
            ['q' => 'Are these official concert tickets?',
             'a' => 'Yes — listings come from official ticketing partners, and your checkout completes on the partner\'s secure site with instant e-ticket delivery. We never resell or mark up tickets; prices come straight from the partner.'],
        ],
    ],

    'theatre' => [
        'h1' => 'Theatre Tickets',
        'meta_title' => 'Theatre Tickets — Musicals, Plays & Shows',
        'meta_description' => 'Theatre tickets for musicals, plays, opera and ballet — West End, Broadway and beyond. Live seat prices and secure checkout with instant e-tickets.',
        'intro' => [
            'Book seats for musicals, plays, opera, ballet and comedy — from long-running West End and Broadway productions to touring shows passing through your city. Listings carry the performance date, theatre name and live starting price, so you can compare a Tuesday evening against a Saturday matinee before you commit.',
            'Theatre inventory moves differently from concerts: popular productions run for months, but the best seats for weekend performances sell first. If your dates are flexible, mid-week performances of the same production are routinely cheaper for identical seats.',
        ],
        'links' => [
            'London shows' => '/uk/london',
            'New York shows' => '/usa/new-york',
            'All events' => '/events',
        ],
        'faqs' => [
            ['q' => 'Are matinee theatre tickets cheaper than evening shows?',
             'a' => 'Often, yes. Matinees and early-week evening performances usually start cheaper than Friday and Saturday nights for the same production and similar seats. Each listing here shows the live starting price per performance, so the difference is easy to spot.'],
            ['q' => 'How far in advance should I book theatre tickets?',
             'a' => 'For hit musicals in London or New York, two to six weeks ahead gives the best mix of choice and price. Touring productions in smaller cities can usually be booked closer to the date — the live availability on each listing tells you how much is left.'],
            ['q' => 'Do theatre tickets include seat selection?',
             'a' => 'Seat choice happens at checkout on our official ticketing partner\'s site, where you\'ll see the seating map, section prices and any restricted-view notes before paying. Tickets are delivered by email as soon as the booking confirms.'],
        ],
    ],

    'sports' => [
        'h1' => 'Sports Tickets',
        'meta_title' => 'Sports Tickets — NBA, NFL, MLB, NHL, Soccer & More',
        'meta_description' => 'Sports tickets for NBA, NFL, MLB, NHL, MLS and international soccer. Compare every fixture with live prices and book on our official ticketing partner.',
        'intro' => [
            'Tickets for game day: NBA basketball, NFL football, MLB baseball, NHL hockey, MLS and European soccer, plus boxing, motorsport and more. Every fixture lists the date, stadium or arena and live starting price, and schedules update automatically as new games go on sale.',
            'The league hubs are the quickest route to a specific matchup — each one carries the full upcoming slate, and every team page lists that club\'s home and away games together so you can catch them on the road if the home fixture is sold thin.',
        ],
        'links' => [
            'NBA schedule' => '/nba',
            'NFL schedule' => '/nfl',
            'MLB schedule' => '/mlb',
            'NHL schedule' => '/nhl',
            'All teams' => '/teams',
        ],
        'faqs' => [
            ['q' => 'When is the cheapest time to buy sports tickets?',
             'a' => 'For regular-season games, prices are typically friendliest well before game week, and weeknight fixtures against smaller opponents start lower than weekend rivalry games. Playoff and derby pricing is its own market — earlier is almost always better there.'],
            ['q' => 'Can I buy tickets for away games?',
             'a' => 'Yes. Team pages list every upcoming fixture — home and away — with the venue and city on each card, so following your team on the road just means picking a date in another city.'],
            ['q' => 'Are sports schedules on this site up to date?',
             'a' => 'Schedules, prices and availability are pulled live from our ticketing partners every time the page loads, so a game appears here as soon as its tickets are on sale and disappears when it has played.'],
        ],
    ],

    'museums' => [
        'h1' => 'Museum Tickets',
        'meta_title' => 'Museum Tickets — Skip the Line at Top Museums',
        'meta_description' => 'Skip-the-line museum tickets and guided tours — Vatican Museums, MoMA, Louvre and more. Compare entry options with live prices and instant e-tickets.',
        'intro' => [
            'Entry tickets and guided tours for the world\'s most-visited museums — the Vatican Museums and Sistine Chapel in Rome, MoMA and the Met in New York, the Louvre in Paris, the Prado in Madrid and dozens more. Listings compare standard entry, skip-the-line access and guided options side by side, each with live pricing.',
            'For the headline museums, timed-entry and fast-track tickets are the difference between walking in and queueing for an hour — Vatican and Louvre lines are notorious in summer. E-tickets arrive by email and most scan straight from your phone at the entrance.',
        ],
        'links' => [
            'Rome museums' => '/italy/rome',
            'Paris museums' => '/france/paris',
            'New York museums' => '/usa/new-york',
        ],
        'faqs' => [
            ['q' => 'What does a skip-the-line museum ticket actually skip?',
             'a' => 'It bypasses the ticket-purchase queue, which is the longest one at major museums — you still pass security screening. At the Vatican Museums or the Louvre in high season, that regularly saves 45 minutes or more.'],
            ['q' => 'Do I need to book museum tickets for a specific time slot?',
             'a' => 'Many top museums now use timed entry, and the time slots are chosen during checkout on our partner\'s site. Morning slots sell out first in peak season; late-afternoon entries are usually the easiest to get on short notice.'],
            ['q' => 'Is a guided museum tour worth it over plain entry?',
             'a' => 'For dense collections like the Vatican or the Uffizi, a licensed guide turns a three-hour wander into a 90-minute highlights route — and guided groups often use a separate entrance. For smaller museums, standard entry plus the free audio guide is usually enough.'],
        ],
    ],

    'tours' => [
        'h1' => 'Tours & Sightseeing',
        'meta_title' => 'Tours & Sightseeing Tickets — City Tours, Day Trips & Guides',
        'meta_description' => 'Book city tours, day trips, walking tours and sightseeing passes worldwide. Compare itineraries, durations and live prices with instant confirmation.',
        'intro' => [
            'Guided walking tours, hop-on-hop-off buses, day trips and private guides — bookable in every city we cover, from a two-hour old-town walk to a full-day excursion with hotel pickup. Each listing shows the duration, the supplier running it and the live price, with traveller ratings where reviews exist.',
            'Day trips are where booking ahead matters most: small-group tours to places like Sintra, the Amalfi Coast or the Grand Canyon cap their group sizes and sell out days ahead in season, while big-bus city tours can usually be booked the same morning.',
        ],
        'links' => [
            'All attractions' => '/attractions',
            'Dubai tours' => '/dubai/tours',
        ],
        'faqs' => [
            ['q' => 'What\'s the difference between a group tour and a private tour?',
             'a' => 'Group tours run on a fixed schedule at a per-person price; private tours flex around your timing and pace but price per group. The listing title and description state which is which, and both confirm instantly with an e-voucher.'],
            ['q' => 'Do tours include entry tickets to the sights they visit?',
             'a' => 'It varies by tour — some include entry to specific attractions, others are external-viewing only. The inclusion list on each listing spells this out before you book, so check it when comparing two similar itineraries.'],
            ['q' => 'What happens if it rains on the day of my tour?',
             'a' => 'Most operators run in light rain and reschedule or refund when weather genuinely cancels an outdoor tour. The exact policy is shown at checkout for each tour — many allow free cancellation up to 24 hours ahead, which covers most forecast surprises.'],
        ],
    ],

    'cruises' => [
        'h1' => 'Cruises & Boat Trips',
        'meta_title' => 'Cruise & Boat Trip Tickets — Dinner Cruises, Canal Boats & Sails',
        'meta_description' => 'Tickets for dinner cruises, canal boats, sunset sails and sightseeing cruises worldwide. Compare routes, durations and live prices before you book.',
        'intro' => [
            'See a city from its water: dinner cruises in Dubai Marina, canal boats in Amsterdam and Venice, Seine sightseeing in Paris, harbour sails in New York and sunset catamarans almost everywhere with a coastline. Listings show the route, duration and live price, and most boats confirm instantly with a mobile e-ticket.',
            'The same stretch of water is often sold three ways — a daytime sightseeing loop, a sunset sail and an evening dinner cruise — at very different prices. If you just want the skyline, the daytime crossing is usually the best value; the premium on the evening boats buys the meal and the light show.',
        ],
        'links' => [
            'Dubai cruises' => '/dubai/cruises',
            'All attractions' => '/attractions',
        ],
        'faqs' => [
            ['q' => 'What is included on a dinner cruise?',
             'a' => 'Typically a multi-course meal or buffet, the cruise itself and some form of entertainment, with drinks either included or sold on board — the listing\'s inclusion list is explicit so you can compare like for like. Vegetarian options are standard; note dietary needs at checkout.'],
            ['q' => 'When should I book a sunset cruise?',
             'a' => 'Sunset departures are the first to sell out, especially Friday to Sunday — two or three days ahead is safe in most cities, longer in peak season. Daytime sailings on the same route can usually be booked on the day.'],
            ['q' => 'Do boat trips run in bad weather?',
             'a' => 'Sheltered canal and river cruises run in almost anything; open-water sails cancel in high wind and you\'ll be offered a reschedule or refund per the operator\'s policy shown at checkout. Cancellations are confirmed by email before departure time.'],
        ],
    ],

    'food-and-wine-tours' => [
        'h1' => 'Food & Wine Tours',
        'meta_title' => 'Food Tours & Wine Tastings — Eat Like a Local',
        'meta_description' => 'Food tours, wine tastings and culinary walks worldwide. Small-group experiences with live prices, real reviews and instant booking confirmation.',
        'intro' => [
            'Eat your way through a neighbourhood with a local guide: tapas crawls in Madrid and Seville, pasta and gelato walks in Rome, wine tastings in real cellars, street-food safaris and market tours. Each listing states what you\'ll taste, how long it runs and the live per-person price.',
            'A good food tour replaces a meal — most include six to ten tastings, which is comfortably lunch or dinner. They also work best early in a trip: the guide\'s restaurant tips tend to shape where you eat for the rest of your stay.',
        ],
        'links' => [
            'Rome food tours' => '/italy/rome',
            'Madrid tapas' => '/spain/madrid',
            'Paris tastings' => '/france/paris',
        ],
        'faqs' => [
            ['q' => 'How much food is actually included on a food tour?',
             'a' => 'Enough to count as a full meal on most tours — typically six or more stops with a tasting at each. The listing description lists the number of tastings; if drinks (wine, coffee) are included, that\'s stated too.'],
            ['q' => 'Can food tours handle dietary restrictions?',
             'a' => 'Most operators handle vegetarian and many handle gluten-free with notice — there\'s a notes field at checkout for exactly this. Strict allergies are worth flagging in advance; the operator will confirm whether the route can adapt.'],
            ['q' => 'Are wine tastings guided or self-serve?',
             'a' => 'Listed tastings are hosted — a sommelier or producer walks you through each pour, usually three to six wines with local pairings. If transport between vineyards is included on rural tours, the listing says so explicitly.'],
        ],
    ],

    'nightlife' => [
        'h1' => 'Nightlife Tickets',
        'meta_title' => 'Nightlife Tickets — Club Entry, Rooftops & Night Shows',
        'meta_description' => 'Skip the guest list: club entry, rooftop lounges, cabaret and late-night shows with live prices and instant mobile tickets.',
        'intro' => [
            'Pre-book the night out: club entry, rooftop lounges, cabaret and variety shows, pub crawls and late-night river cruises. Buying ahead gets you a confirmed spot at the door price — no guest-list gambling — and your ticket lives on your phone.',
            'In nightlife capitals like Las Vegas, Dubai and Barcelona, the same venue can be a day club, a dinner show and a 2am dance floor on the same date. Listings here name the specific event and entry window, so check the time on the ticket, not just the venue name.',
        ],
        'links' => [
            'Las Vegas nights' => '/usa/las-vegas',
            'Dubai nightlife' => '/dubai/nightlife',
            'Barcelona nights' => '/spain/barcelona',
        ],
        'faqs' => [
            ['q' => 'Does a pre-booked club ticket guarantee entry?',
             'a' => 'It guarantees admission subject to the venue\'s door policy — dress codes and minimum age (commonly 21+ in the US, 18+ or 21+ elsewhere) still apply and are listed on each event. Arriving within your ticket\'s entry window matters at the busiest clubs.'],
            ['q' => 'Is buying nightlife tickets online cheaper than paying at the door?',
             'a' => 'Usually equal or cheaper, and it removes the sold-out risk on weekend nights. Some listings bundle a drink or fast-track entry that door tickets don\'t include — the inclusions line shows exactly what the price buys.'],
            ['q' => 'What ID do I need for a night out abroad?',
             'a' => 'A physical passport or national ID is the safe answer — many clubs in Europe and the US won\'t accept photos of documents. Your e-ticket plus the ID that matches the booking name is all you need at the door.'],
        ],
    ],

    'desert-experiences' => [
        'h1' => 'Desert Safari Tickets',
        'meta_title' => 'Desert Safari Tickets — Dune Bashing, Camel Rides & Bedouin Dinners',
        'meta_description' => 'Book desert safaris from Dubai and Abu Dhabi: dune bashing, camel rides, sandboarding and Bedouin-camp dinners with live prices and hotel pickup.',
        'intro' => [
            'The classic Arabian evening: 4x4 dune bashing, camel rides, sandboarding, falconry and a Bedouin-style camp dinner with live shows — most packages include hotel pickup and drop-off from Dubai or Abu Dhabi. Morning safaris cover the dunes and adrenaline; evening safaris add sunset photos and the camp dinner.',
            'Packages differ mainly in group size, camp quality and dinner spread, which is what the price gap reflects — a premium camp seats you at tables with table service rather than a shared buffet bench. Every listing details its inclusions so the comparison is straightforward.',
        ],
        'links' => [
            'Dubai desert safaris' => '/dubai/desert-safari',
            'All Dubai attractions' => '/dubai',
        ],
        'faqs' => [
            ['q' => 'Is dune bashing safe — and can children join?',
             'a' => 'Drivers are licensed for desert driving and vehicles run in convoy; it\'s a rollercoaster-grade ride, so it\'s not recommended for pregnant travellers, very young children or anyone with back problems. Most operators set a minimum age, listed on each safari.'],
            ['q' => 'What should I wear and bring on a desert safari?',
             'a' => 'Light, loose clothing with a layer for after sunset — the desert cools fast. Sunglasses, sunscreen and a charged phone cover the rest; closed shoes beat sandals for sandboarding. Cameras are welcome everywhere including the camp shows.'],
            ['q' => 'What\'s the difference between morning and evening safaris?',
             'a' => 'Morning trips focus on dune driving and sports with cooler air and emptier dunes; evening trips trade some drive time for sunset, the camp dinner and shows. If you only do one, the evening safari is the fuller experience — book a day or two ahead.'],
        ],
    ],

    'attraction-passes' => [
        'h1' => 'City Attraction Passes',
        'meta_title' => 'City Attraction Passes — One Ticket, Many Sights',
        'meta_description' => 'Compare city passes that bundle top attractions into one ticket. See what each pass includes and whether it beats single tickets for your itinerary.',
        'intro' => [
            'A city pass bundles entry to multiple attractions into one purchase — usually either a fixed list of big-name sights or a "choose any N" format over a set number of days. In cities where you\'d visit three or more paid attractions anyway, a pass typically undercuts the same tickets bought separately.',
            'The honest math: passes pay off for fast-paced itineraries and first visits, and don\'t for slow trips built around one or two sights. Add up the individual prices of what you\'d genuinely visit — the listings on this site show live single-ticket prices, which makes that comparison easy.',
        ],
        'links' => [
            'New York passes' => '/usa/new-york',
            'London passes' => '/uk/london',
            'Dubai passes' => '/dubai/attraction-passes',
        ],
        'faqs' => [
            ['q' => 'Do attraction passes really save money?',
             'a' => 'They save when you\'ll visit three or more included attractions within the pass\'s validity window — savings of 20-40% versus gate prices are typical at that pace. Below that, single tickets win. Compare against the live single-ticket prices listed here before deciding.'],
            ['q' => 'Do I still need to reserve time slots with a pass?',
             'a' => 'At timed-entry attractions, yes — the pass is your payment, but popular sights still want a reservation, made on the attraction\'s site or app after you activate the pass. Build the must-sees\' slots first and fit the rest around them.'],
            ['q' => 'When does a pass\'s clock start counting?',
             'a' => 'Almost all passes activate at first use, not at purchase — buying ahead of your trip is safe. Consecutive-day passes then run on calendar days, while "choose N attractions" passes usually allow 30-60 days to use the credits; each listing states its own rule.'],
        ],
    ],

    'landmarks-and-skyscrapers' => [
        'h1' => 'Landmark & Observation Deck Tickets',
        'meta_title' => 'Landmark Tickets — Observation Decks, Towers & Icons',
        'meta_description' => 'Tickets for the world\'s great landmarks and observation decks — Burj Khalifa, Empire State Building, Eiffel Tower and more, with timed entry and live prices.',
        'intro' => [
            'Tickets to the views: Burj Khalifa\'s At the Top in Dubai, the Empire State Building and Edge in New York, the Eiffel Tower summit, the London Eye and the other icons that define their skylines. Most use timed entry, and listings compare standard, fast-track and prime-time options with live prices.',
            'Timing is the whole game at observation decks. Sunset slots cost the most and sell out first; the golden combination is booking the slot 60-90 minutes before sunset so you get daylight, golden hour and the city lights on one ticket.',
        ],
        'links' => [
            'Burj Khalifa guide' => '/dubai/burj-khalifa',
            'New York decks' => '/usa/new-york',
            'Paris landmarks' => '/france/paris',
        ],
        'faqs' => [
            ['q' => 'Are sunset time slots worth the higher price?',
             'a' => 'If the view is the point of your visit, yes — but the slot just before the official "prime" window often catches the same light at the standard price. Daytime and late-night slots are the cheapest and shortest queues.'],
            ['q' => 'What happens if it\'s cloudy or hazy on the day?',
             'a' => 'Decks stay open in poor visibility and tickets generally aren\'t weather-refundable, though some attractions offer a courtesy return on zero-visibility days. If the forecast looks bad, book a flexible ticket — the cancellation terms are shown before checkout.'],
            ['q' => 'Can I skip the elevator queue at major towers?',
             'a' => 'Fast-track or skip-the-line tickets shortcut the boarding queue, which is the real wait at peak hours — at the busiest decks that can be an hour saved on weekends. Timed-entry standard tickets at off-peak hours achieve nearly the same thing for less.'],
        ],
    ],

    'disneyland' => [
        'h1' => 'Theme Park Tickets',
        'meta_title' => 'Theme Park Tickets — Disneyland, Waterparks & More',
        'meta_description' => 'Theme park and waterpark tickets — Disneyland Paris, Dubai parks and more. Compare 1-day and multi-day entry with live prices and instant e-tickets.',
        'intro' => [
            'Gate-ready e-tickets for theme parks and waterparks: Disneyland Paris, Dubai\'s waterparks and theme park resorts, and seasonal parks across our destinations. Listings compare one-day, multi-day and multi-park options, with live prices and the entry conditions stated up front.',
            'Multi-day and multi-park bundles almost always beat day tickets per visit — the second day typically costs a fraction of the first. Date-specific tickets are cheaper than anytime tickets, so committing to a date is the easiest saving there is.',
        ],
        'links' => [
            'Paris parks' => '/france/paris',
            'Dubai waterparks' => '/dubai/waterparks',
            'Orlando parks' => '/usa/orlando',
        ],
        'faqs' => [
            ['q' => 'Should I buy dated or flexible theme park tickets?',
             'a' => 'Dated tickets are cheaper and guarantee entry on busy days when parks cap capacity; flexible tickets cost more but survive itinerary changes. In school holidays, dated is the safer call — popular dates genuinely sell out.'],
            ['q' => 'Do theme park tickets include ride queues or fast passes?',
             'a' => 'Standard entry covers all rides via the regular queues. Express or priority access is a separate add-on at most parks, and where a listing bundles it the inclusion is named explicitly — worth it mainly in peak season.'],
            ['q' => 'Can I upgrade a one-day ticket to multi-day at the park?',
             'a' => 'Many parks allow upgrades at guest services for the price difference, but the online multi-day rate is usually better than the gate upgrade rate. If there\'s any chance you\'ll return, the multi-day bundle bought upfront wins.'],
        ],
    ],

    'festivals' => [
        'h1' => 'Festival Tickets',
        'meta_title' => 'Festival Tickets — Music, Cultural & Street Fairs',
        'meta_description' => 'Tickets for music festivals, cultural celebrations and street fairs worldwide. Compare lineups, dates and live prices with secure instant booking.',
        'intro' => [
            'From multi-day camping festivals to single-afternoon street fairs, the festival calendar runs year-round across every continent. Whether it is a headline music festival with stadium-grade production or a neighbourhood cultural celebration built around food, art and local tradition, listings here show dates, tiers and live pricing so you can lock in your spot before the early-bird window closes.',
            'Festival tickets almost always get more expensive as the event approaches — organisers price in waves, and the first wave can be half the final gate price. If you know the dates work, buying early is the single biggest saving; day passes versus full-weekend bundles are the next lever.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'This weekend' => '/events?date=weekend',
            'All events' => '/events',
        ],
        'faqs' => [
            ['q' => 'When is the cheapest time to buy festival tickets?',
             'a' => 'The first on-sale tier is almost always the cheapest — many festivals sell blind early-bird tickets before announcing the lineup. After that, prices step up with each release wave, and resale prices after sellout are typically well above face value.'],
            ['q' => 'What is included with a festival ticket?',
             'a' => 'A standard festival ticket covers general admission to all stages for the dates on the pass. Camping, parking, VIP areas and single-day access are usually separate add-ons — the listing here details exactly which tier you are buying.'],
            ['q' => 'Can I get a refund if a festival headliner drops out?',
             'a' => 'Lineup changes rarely trigger refunds because the ticket is for the event, not a specific act. If the entire event cancels, refunds are standard. Check the refund terms shown at checkout for the specific policy each organiser sets.'],
        ],
    ],

    'family' => [
        'h1' => 'Family Event Tickets',
        'meta_title' => 'Family Tickets — Kids Shows, Days Out & Attractions',
        'meta_description' => 'Family-friendly event tickets for kids shows, theme parks, zoos and interactive attractions. Compare live prices and book with instant confirmation.',
        'intro' => [
            'Plan the family day out: kids\' theatre, interactive science shows, zoo and aquarium entry, theme park passes, circus performances and seasonal events like holiday pantos and ice shows. Every listing states the recommended age range and what is included, so you know before you book whether a toddler goes free or needs a ticket.',
            'Family pricing varies wildly — some attractions offer a bundled family ticket that undercuts four individual admissions, while others price children at a flat discount. Comparing those options here, where every listing shows live per-person and bundle prices side by side, takes the guesswork out of budgeting a day with the kids.',
        ],
        'links' => [
            'Theme parks' => '/category/disneyland',
            'Top venues' => '/venues',
            'Dubai attractions' => '/dubai',
        ],
        'faqs' => [
            ['q' => 'Are there discounts for children on event tickets?',
             'a' => 'Most family-oriented events offer reduced child pricing, and many admit children under three or four for free. The age bands and any free-entry thresholds are stated on each listing so you can tally the real cost before checkout.'],
            ['q' => 'How do I know if an event is suitable for young children?',
             'a' => 'Listings include a recommended age range and any content advisories. Theatre and circus shows usually state a minimum age; outdoor attractions and interactive experiences tend to welcome all ages with supervised access for the smallest visitors.'],
            ['q' => 'Can I buy a family bundle ticket online?',
             'a' => 'Where an attraction offers a family bundle — typically two adults plus two or three children — it appears as a separate ticket type in the listing. Compare it against individual tickets for your actual group size; families of three sometimes do better buying singles.'],
        ],
    ],

    'classical-music' => [
        'h1' => 'Classical Music Tickets',
        'meta_title' => 'Classical Music Tickets — Orchestra, Symphony & Recitals',
        'meta_description' => 'Tickets for orchestras, symphonies and classical recitals at the world\'s finest concert halls. Compare programmes, seats and live prices.',
        'intro' => [
            'Hear the world\'s leading orchestras and soloists live: symphony cycles, chamber recitals, piano concerts and choral masterworks at concert halls from Carnegie and the Barbican to the Vienna Musikverein. Listings carry the programme, the performers and live seat-category prices so you can pick the right night and the right section.',
            'Classical concerts reward flexible date-picking more than most genres. The same orchestra often performs the same programme across two or three evenings, and the midweek date routinely has better seat availability at the same price. Matinee and late-morning performances, common with Sunday series, are another route to premium halls at friendlier prices.',
        ],
        'links' => [
            'New York concerts' => '/usa/new-york',
            'London concerts' => '/uk/london',
            'All artists' => '/artists',
        ],
        'faqs' => [
            ['q' => 'What is the dress code for a classical concert?',
             'a' => 'Smart casual is accepted at almost every concert hall today — jeans are fine, flip-flops are not. Opening nights and gala performances skew more formal, but there is no enforced code at standard subscription concerts.'],
            ['q' => 'How far in advance should I book orchestra tickets?',
             'a' => 'Subscription holders fill the best seats months ahead, but single tickets release later and good seats often remain for midweek performances. For star soloists and special programmes, two to four weeks ahead is a safe window.'],
            ['q' => 'Are there cheaper seats at symphony concerts?',
             'a' => 'Yes — most halls tier their pricing by section, and upper-tier or side seats are significantly cheaper than stalls centre. Acoustically, many regular concertgoers prefer the first balcony for orchestral works; the price difference makes experimenting easy.'],
        ],
    ],

    'dance' => [
        'h1' => 'Dance Show Tickets',
        'meta_title' => 'Dance Tickets — Ballet, Contemporary & Dance Shows',
        'meta_description' => 'Tickets for ballet, contemporary dance and spectacular dance shows worldwide. Compare performances, venues and live seat prices.',
        'intro' => [
            'From classical ballet at the Royal Opera House and Lincoln Center to contemporary dance, flamenco, Riverdance-style spectaculars and Bollywood stage shows, dance performances combine athleticism with storytelling in a way no other art form matches. Listings show the company, the programme, the venue and live seat prices across every price band.',
            'Ballet runs in repertory seasons where the same company performs different works on alternate nights — Swan Lake on Friday, a contemporary triple bill on Saturday. Checking the specific programme date matters more here than in most theatre; the listing title names the work so you book the right night.',
        ],
        'links' => [
            'London shows' => '/uk/london',
            'New York shows' => '/usa/new-york',
            'All events' => '/events',
        ],
        'faqs' => [
            ['q' => 'What is the best seat for watching ballet?',
             'a' => 'Centre stalls about ten rows back or the front of the first balcony give the best balance of proximity and sightlines for full-stage formations. Very front-row seats lose the overhead perspective that makes corps de ballet choreography spectacular.'],
            ['q' => 'Are dance shows suitable for children?',
             'a' => 'Story ballets like The Nutcracker and Sleeping Beauty are popular family outings, and most theatres admit children from around age five. Contemporary and evening-length abstract works suit older audiences; the listing notes age guidance where applicable.'],
            ['q' => 'How long does a typical ballet performance last?',
             'a' => 'Full-length story ballets run two and a half to three hours including intervals. Mixed bills and contemporary programmes are usually 90 minutes to two hours. The listing states the runtime so you can plan your evening.'],
        ],
    ],

    'opera' => [
        'h1' => 'Opera Tickets',
        'meta_title' => 'Opera Tickets — World-Class Opera Performances',
        'meta_description' => 'Opera tickets at the world\'s great opera houses. Compare cast, programme and live seat prices from La Scala to the Met and the Royal Opera House.',
        'intro' => [
            'Experience opera at the houses where it was born and where it thrives today: the Metropolitan Opera in New York, the Royal Opera House in London, La Scala in Milan, the Vienna State Opera and Sydney Opera House. Listings name the production, the principal cast and live seat prices from the gallery to the grand tier.',
            'Opera pricing spans one of the widest ranges in live performance — the same night can cost a modest amount in the upper slips or a premium in the stalls. For newcomers, mid-priced seats in the amphitheatre or dress circle deliver strong sound and full-stage views without the front-row price tag.',
        ],
        'links' => [
            'London opera' => '/uk/london',
            'New York opera' => '/usa/new-york',
            'Top venues' => '/venues',
        ],
        'faqs' => [
            ['q' => 'Do opera performances have subtitles?',
             'a' => 'Most major opera houses provide surtitles projected above the stage or individual seat-back screens with translations, even when the opera is sung in the local language. The listing or venue page notes the language of performance and surtitle availability.'],
            ['q' => 'How long is a typical opera performance?',
             'a' => 'Two and a half to four hours including intervals is the standard range — Wagner and some Verdi operas push past four hours. The runtime is stated on each listing so you can plan dinner before or after accordingly.'],
            ['q' => 'What should I wear to the opera?',
             'a' => 'Smart casual is the modern norm at most houses; opening nights and gala performances invite more formal attire but rarely enforce a dress code. Comfort matters — you will be seated for several hours — and there is no section-based dress expectation.'],
        ],
    ],

    'jazz' => [
        'h1' => 'Jazz Concert Tickets',
        'meta_title' => 'Jazz Tickets — Concerts, Clubs & Jazz Festivals',
        'meta_description' => 'Jazz concert tickets for club shows, festival stages and headline tours. Compare dates, venues and live prices with instant e-ticket delivery.',
        'intro' => [
            'Live jazz in every format: intimate club sets at legendary rooms like the Blue Note and Ronnie Scott\'s, outdoor festival stages, big-band theatre shows and touring headline acts. Listings show the artist, the venue capacity and live ticket prices, so you can weigh a 200-seat club experience against an arena date for the same artist.',
            'Jazz clubs often run two sets per evening — an early and a late show — and the late set is frequently cheaper or easier to get into. Festival passes, meanwhile, follow the same early-bird logic as music festivals: the first release is the best price, and single-day passes let you cherry-pick the headliner night.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'New York jazz' => '/usa/new-york',
            'London jazz' => '/uk/london',
        ],
        'faqs' => [
            ['q' => 'What is the difference between early and late jazz club sets?',
             'a' => 'Early sets typically start around 7-8pm and late sets around 10-11pm, with the same artist often playing both. Late sets tend to be looser and more improvisational; early sets are easier to plan around dinner. Pricing and availability differ, so both appear as separate listings.'],
            ['q' => 'Do jazz clubs have assigned seating?',
             'a' => 'Some do, some operate on a first-come basis within a ticket tier. The listing states whether seats are assigned or general admission, and clubs with table service usually note the minimum spend per seat as well.'],
            ['q' => 'Are jazz festival passes worth it over single-day tickets?',
             'a' => 'If you plan to attend two or more days, a full pass almost always saves money. Single-day passes make sense when you only want one headliner night — compare the per-day cost of each option using the prices shown here.'],
        ],
    ],

    'hip-hop' => [
        'h1' => 'Hip-Hop & R&B Tickets',
        'meta_title' => 'Hip-Hop & R&B Tickets — Concerts & Tours',
        'meta_description' => 'Hip-hop and R&B concert tickets for headline tours, arena shows and festivals. Compare tour dates, venues and live prices with secure checkout.',
        'intro' => [
            'Tickets for hip-hop and R&B tours — from arena headliners and stadium world tours to club nights and festival slots. The genre drives some of the fastest-selling tours in music, and listings here update live so new dates appear the moment tickets drop, complete with venue, city and starting price.',
            'Hip-hop tours often announce dates in waves, starting with major markets and adding second shows or new cities based on demand. Following an artist page here catches those additions automatically, which matters because added dates frequently price lower than the original on-sale that sold out in minutes.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'All events' => '/events',
            'Top venues' => '/venues',
        ],
        'faqs' => [
            ['q' => 'Why do hip-hop concerts sell out so fast?',
             'a' => 'High demand meets limited arena capacity, and presale codes absorb a large share of inventory before general on-sale. Setting up alerts on the artist page here is the best way to catch the exact on-sale moment, including any added second dates.'],
            ['q' => 'Are floor or standing tickets available for hip-hop shows?',
             'a' => 'Most arena hip-hop tours offer general-admission floor sections alongside seated tiers. The listing specifies whether floor is standing GA or reserved seating, and floor GA is usually the first section to sell out.'],
            ['q' => 'Do hip-hop tours have opening acts?',
             'a' => 'Nearly all headline tours carry support acts, often announced closer to the date. Door times are typically 90 minutes before the headliner, and the full running order appears on the event page once confirmed.'],
        ],
    ],

    'rock' => [
        'h1' => 'Rock & Alternative Tickets',
        'meta_title' => 'Rock Tickets — Rock & Alternative Concerts',
        'meta_description' => 'Rock and alternative concert tickets for arena tours, club shows and festivals. Compare dates, venues and live prices with instant e-tickets.',
        'intro' => [
            'From stadium rock and legacy reunion tours to indie club shows and punk all-dayers, rock and alternative music spans the widest venue range of any genre. Listings carry the tour name, support acts where announced, venue capacity and live ticket prices so you can decide between an intimate 500-cap room and a 20,000-seat arena show.',
            'Rock tours reward early buying — first-wave general-admission and early-bird pricing undercut what the same seats cost a month later. For festivals, single-day passes let you target the headliner night, while full-weekend camping passes are where the per-day savings stack up.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'This weekend' => '/events?date=weekend',
            'Top venues' => '/venues',
        ],
        'faqs' => [
            ['q' => 'Are standing or seated tickets better for a rock concert?',
             'a' => 'Standing (GA floor or pit) puts you closest to the stage and in the energy of the crowd, but it means being on your feet for hours. Seated lower-bowl sections offer a good view with more comfort — the choice depends on how you like to experience a show.'],
            ['q' => 'How early should I arrive for a general-admission rock show?',
             'a' => 'For barrier spots, fans often queue hours before doors. For a comfortable spot in the middle of the floor, arriving at door time is usually enough. Club shows with smaller capacities fill faster than arena GA floors.'],
            ['q' => 'Will the setlist be the same at every tour stop?',
             'a' => 'Most rock tours run a fixed setlist with one or two rotating slots. Legacy acts and greatest-hits tours are especially consistent. Setlist-tracking sites confirm what was played at the previous few shows if you want to know in advance.'],
        ],
    ],

    'country-music' => [
        'h1' => 'Country Music Tickets',
        'meta_title' => 'Country Music Tickets — Concerts & Festivals',
        'meta_description' => 'Country music concert and festival tickets for stadium tours, honky-tonk shows and outdoor festivals. Live prices and instant booking confirmation.',
        'intro' => [
            'Tickets for country music in every format: stadium headliners, amphitheatre summer tours, honky-tonk club nights and multi-day outdoor festivals. Country tours lean heavily into the summer amphitheatre circuit, and those outdoor shows with lawn and pavilion seating offer a pricing spread that suits every budget.',
            'Country festivals — CMA Fest, Stagecoach, Tortuga and their regional equivalents — pack dozens of acts across multiple days and stages. Weekend passes are the standard buy, but single-day passes target the headliner you care about most and often sell at less than half the full-weekend price.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'Nashville events' => '/usa/nashville',
            'All events' => '/events',
        ],
        'faqs' => [
            ['q' => 'What is the difference between pavilion and lawn seats at an amphitheatre?',
             'a' => 'Pavilion (or reserved) seats are covered, numbered and closer to the stage. Lawn is general admission on the grassy slope behind the pavilion — bring a blanket, the view is distant but the vibe is relaxed and the price is significantly lower.'],
            ['q' => 'When do country music summer tours go on sale?',
             'a' => 'Most summer amphitheatre tours announce in January through March with on-sale shortly after. Prices are lowest at first release; by summer, popular dates are sold out or resale-priced. Artist pages here flag the on-sale the moment tickets drop.'],
            ['q' => 'Are country music festivals family-friendly?',
             'a' => 'Many are — daytime stages, open-air settings and all-ages policies make large country festivals popular family outings. Check the event listing for age policies and any separate kids\' pricing or free-entry thresholds.'],
        ],
    ],

    'electronic-music' => [
        'h1' => 'Electronic Music Tickets',
        'meta_title' => 'Electronic Music Tickets — EDM, Techno & House',
        'meta_description' => 'EDM, techno and house music tickets for club nights, warehouse raves and mega-festivals. Compare lineups, live prices and book instantly.',
        'intro' => [
            'Tickets for electronic music across the spectrum: EDM mega-festivals, underground techno warehouse nights, Ibiza superclub residencies, house-music day parties and touring DJ arena shows. Listings name the headlining DJs, the venue type and live pricing tier by tier, from early-bird general admission to VIP table packages.',
            'Electronic events use tiered release pricing more aggressively than any other genre — a first-release ticket can be a third of the final-release price for the same wristband. Buying the moment tickets drop is the single biggest money saver, and following the event or artist page here is the fastest way to catch that window.',
        ],
        'links' => [
            'Artists on tour' => '/artists',
            'Las Vegas events' => '/usa/las-vegas',
            'Dubai nightlife' => '/dubai/nightlife',
        ],
        'faqs' => [
            ['q' => 'What does VIP include at an electronic music event?',
             'a' => 'VIP typically adds a dedicated viewing area, faster entry, premium bars and sometimes a raised deck or lounge. Table packages at superclubs include bottle service and reserved space. The exact inclusions vary by event and are listed on each ticket tier.'],
            ['q' => 'How do tiered ticket releases work for EDM festivals?',
             'a' => 'Organisers release tickets in numbered tiers — Tier 1 is cheapest, each subsequent tier costs more for the same access. Once a tier sells out, the next opens automatically. The current tier and its price are always shown live on the listing.'],
            ['q' => 'Are electronic music events only at night?',
             'a' => 'Not at all — day festivals, brunch parties and pool-party residencies in cities like Las Vegas and Dubai run from midday. Club events are typically late-night with doors opening at 10pm or later; the listing states the entry window clearly.'],
        ],
    ],

    'boxing-and-mma' => [
        'h1' => 'Boxing & MMA Tickets',
        'meta_title' => 'Boxing & MMA Tickets — UFC, Title Fights & Cards',
        'meta_description' => 'Boxing and MMA tickets for UFC numbered events, title fights and undercard shows. Compare seats, live prices and book with instant confirmation.',
        'intro' => [
            'Ringside to nosebleeds: tickets for UFC numbered events, Fight Night cards, world-title boxing bouts and regional MMA promotions. Combat sports pricing has one of the steepest gradients in live events — floor seats at a major card can run many times the upper-bowl price — so the seat map comparison on each listing is essential reading.',
            'Fight cards are announced in stages: the main event first, then co-main and undercard bouts closer to the date. Buying on the initial on-sale secures the best seat selection at face value, while waiting means fewer options and secondary-market markups for the headline bouts.',
        ],
        'links' => [
            'Las Vegas fights' => '/usa/las-vegas',
            'All events' => '/events',
            'Top venues' => '/venues',
        ],
        'faqs' => [
            ['q' => 'When do UFC tickets go on sale?',
             'a' => 'UFC tickets typically go on sale four to six weeks before the event, with a presale window for UFC newsletter subscribers a day or two earlier. Numbered PPV events sell fastest — setting an alert on the event page here catches the on-sale moment.'],
            ['q' => 'What is the best section for watching a boxing match live?',
             'a' => 'Lower-bowl sections directly facing the ring offer the best sightlines without the extreme cost of floor seats. Floor seats carry atmosphere and proximity but viewing angles can be obstructed by the ring apron from the first few rows.'],
            ['q' => 'Do fight cards change after tickets go on sale?',
             'a' => 'Yes — bout changes due to injury or weight-miss are common in combat sports and do not usually trigger refunds since the ticket is for the event card, not a single fight. The main event and co-main changes are announced on the event page as they happen.'],
        ],
    ],

    'soccer' => [
        'h1' => 'Soccer Tickets',
        'meta_title' => 'Soccer Tickets — Premier League, La Liga & UCL',
        'meta_description' => 'Soccer tickets for the Premier League, La Liga, Champions League and international matches. Compare fixtures, stadiums and live seat prices.',
        'intro' => [
            'Match tickets for the world\'s biggest football leagues and tournaments: the English Premier League, La Liga, Serie A, Bundesliga, MLS, Champions League nights and international friendlies. Each fixture shows the stadium, kick-off time and live seat-category prices so you can weigh a long-side lower tier against a corner upper for the same match.',
            'European club football follows a September-to-May season, and Champions League knockout rounds from February onwards carry the steepest prices. Domestic league matches against mid-table opponents are where value seats live — same stadium, same atmosphere, a fraction of the derby-day price.',
        ],
        'links' => [
            'London matches' => '/uk/london',
            'Madrid matches' => '/spain/madrid',
            'All events' => '/events',
        ],
        'faqs' => [
            ['q' => 'How far ahead can I buy Premier League tickets?',
             'a' => 'Fixtures are confirmed four to six weeks ahead, and tickets go on sale shortly after. High-demand derbies sell out within hours of release; mid-table fixtures remain available closer to match day. The fixture page here updates as soon as tickets are released.'],
            ['q' => 'Can I buy tickets for Champions League matches as a neutral?',
             'a' => 'Yes — neutral tickets for group-stage and knockout matches are available through our ticketing partners, separate from club member allocations. Availability is limited and drops fast for semi-finals and the final, so early booking is essential.'],
            ['q' => 'Are away-section tickets available for league matches?',
             'a' => 'Away allocations in most European leagues are reserved for club members and travelling supporters\' clubs. Neutral-admission tickets seat you in home sections; respect the home-fan etiquette and you will have no issues.'],
        ],
    ],

    'tennis' => [
        'h1' => 'Tennis Tickets',
        'meta_title' => 'Tennis Tickets — Grand Slams & ATP/WTA Tours',
        'meta_description' => 'Tennis tickets for Wimbledon, US Open, Roland Garros, Australian Open and ATP/WTA tour events. Compare sessions, courts and live prices.',
        'intro' => [
            'Tickets for the four Grand Slams — Wimbledon, the US Open, Roland Garros and the Australian Open — plus ATP Masters 1000 events, WTA finals and touring exhibition matches. Listings are split by session (day or night where applicable) and by court, with live pricing for each combination.',
            'Grand Slam pricing hinges on two things: which court and which round. Early-round ground passes are the best-value way into a Slam — you see dozens of matches across the outer courts for a fraction of the centre-court price. Quarter-final and semi-final sessions on the show courts are where prices peak.',
        ],
        'links' => [
            'London events' => '/uk/london',
            'New York events' => '/usa/new-york',
            'Paris events' => '/france/paris',
        ],
        'faqs' => [
            ['q' => 'What is the difference between a ground pass and a show-court ticket?',
             'a' => 'A ground pass admits you to all outer courts on unreserved seating — you can watch multiple matches by moving between courts. A show-court ticket gives you a reserved seat on Centre Court or the main stadium, usually for two or three scheduled matches.'],
            ['q' => 'When do Grand Slam tickets go on sale?',
             'a' => 'Timelines vary: the US Open sells from spring, Roland Garros from March, and Wimbledon runs a ballot months ahead with remaining tickets sold closer to the event. Our listings go live the moment tickets are available for each session.'],
            ['q' => 'Are night sessions worth the premium at the US Open?',
             'a' => 'Night sessions on Arthur Ashe feature the marquee match of the day under lights with a distinct atmosphere. They cost more than day sessions, but you are guaranteed a headline draw. Day sessions offer more total tennis across multiple courts for less.'],
        ],
    ],

    'golf' => [
        'h1' => 'Golf Tournament Tickets',
        'meta_title' => 'Golf Tickets — PGA Tour, Majors & Experiences',
        'meta_description' => 'Golf tickets for PGA Tour events, The Masters, The Open, US Open and Ryder Cup. Compare daily grounds passes and hospitality with live prices.',
        'intro' => [
            'Grounds passes and hospitality packages for golf\'s biggest tournaments: PGA Tour events, the four majors — The Masters, PGA Championship, US Open and The Open — plus the Ryder Cup, Presidents Cup and DP World Tour stops. Listings show the tournament day, ticket tier and live pricing from general grounds admission to clubhouse hospitality.',
            'Golf ticketing works differently from most sports: grounds passes give you roaming access to every hole rather than a fixed seat, and the experience depends on where you choose to stand. Practice-round and early-week passes are dramatically cheaper than weekend final-round tickets for the same course.',
        ],
        'links' => [
            'All events' => '/events',
            'Top venues' => '/venues',
            'Artists on tour' => '/artists',
        ],
        'faqs' => [
            ['q' => 'What does a golf grounds pass actually include?',
             'a' => 'General grounds admission lets you walk the course freely, follow any group and stand at any hole. You can camp behind the 18th green or roam all 18 — the pass covers the full course for the ticketed day. Food, drink and merchandise are purchased separately on-site.'],
            ['q' => 'Are practice-round tickets worth buying?',
             'a' => 'Practice rounds are the most relaxed way to experience a major venue — smaller crowds, players often interact with fans, and you can walk every hole easily. They cost a fraction of competition-round passes and are popular as a first-timer\'s introduction.'],
            ['q' => 'What is the difference between grounds passes and hospitality?',
             'a' => 'Grounds passes give course access on foot; hospitality adds a private pavilion or chalet with seating, food, drink and often an elevated viewing platform near a key hole. Hospitality is priced for corporate entertaining, while grounds passes are the standard fan ticket.'],
        ],
    ],

    'motorsport' => [
        'h1' => 'Motorsport Tickets',
        'meta_title' => 'Motorsport Tickets — F1, NASCAR, IndyCar & MotoGP',
        'meta_description' => 'Motorsport tickets for Formula 1, NASCAR, IndyCar and MotoGP. Compare grandstands, general admission and hospitality with live race-day prices.',
        'intro' => [
            'Race-day tickets for Formula 1 Grand Prix weekends, NASCAR Cup Series ovals, IndyCar street circuits and MotoGP rounds at tracks worldwide. Listings break out by day (practice, qualifying, race day), by grandstand or general admission zone and by hospitality tier, with live pricing for every combination.',
            'Grandstand choice is everything in motorsport — the same race looks completely different from a hairpin grandstand versus a main-straight seat. General admission gives you roaming access to open hillsides and viewing zones at a lower price, and is the traditional way to experience a race weekend on a budget, especially for multi-day passes.',
        ],
        'links' => [
            'All events' => '/events',
            'Top venues' => '/venues',
            'Dubai events' => '/dubai',
        ],
        'faqs' => [
            ['q' => 'What is the best grandstand at a Formula 1 race?',
             'a' => 'Grandstands at heavy braking zones and chicanes see the most overtaking — that is where the action concentrates. Main-straight stands offer start-line drama and pit-stop views. Each listing names the grandstand and its location on the circuit so you can match your priority to the seat.'],
            ['q' => 'Is a three-day pass worth it or should I buy race day only?',
             'a' => 'Race day carries the main event, but Friday practice and Saturday qualifying have their own appeal at much thinner crowds. A three-day pass typically costs less than double the race-day-only price, making the extra days good value if your schedule allows.'],
            ['q' => 'What should I bring to a motorsport event?',
             'a' => 'Ear protection is essential — race noise exceeds safe levels, especially at NASCAR ovals and MotoGP starts. Sun protection, comfortable shoes for the walk between grandstands and a portable seat cushion make a full race day far more comfortable.'],
        ],
    ],

];
