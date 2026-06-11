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
];
