<?php
declare(strict_types=1);

/* =========================================================================
   Curated artist INTENT content — the only artists whose /artist/{slug}/
   {ticket-prices|tour-dates|setlist} pages we ask Google (and AI engines)
   to index. Every entry is hand-written/curated and unique to the artist;
   an artist not in this map 404s on the intent routes, so these can never
   become thin templated doorways (same rule as category-content.php).

   These pages are EVERGREEN: they render and rank even when no show is on
   sale. Live dates, live "from" prices and ticket cards are injected at
   render time from the API when available.

   This file is partly machine-assembled by bin/merge-intent-content.php.
   Shape per slug: name, genre, prices{range_low,range_high,currency,intro[],
   tiers[{name,desc}],why,faqs[{q,a}]}, tour{tour_name,intro[],faqs[{q,a}]},
   setlist{intro[],songs[],encore[],note,faqs[{q,a}]}.
   ========================================================================= */

return array (
  'billie-eilish' => 
  array (
    'name' => 'Billie Eilish',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 65,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Billie Eilish tickets typically run from around $65 for upper-tier seats to $350 or more for the floor and VIP packages, though the exact figure you pay depends on the city, the date and how early you buy. Arena shows on the "Hit Me Hard and Soft" tour use Ticketmaster\'s dynamic pricing, so the headline number moves with demand rather than sitting at a fixed face value.',
        1 => 'This guide breaks down what each seating tier actually costs, why the price you saw yesterday may be different today, and how to land the cheaper end of the range. Live "from" prices for every confirmed date appear in the cards below the moment a show goes on sale, so you can compare two nights of the same tour at a glance.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper bowl (300-level)',
          'desc' => 'The cheapest standard seats, usually $65–$110. You\'re high up and to the side, but on an arena production the screens and staging read clearly from here, and it\'s the tier that sells out last for mid-week dates.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl (100/200-level)',
          'desc' => 'Side and corner seats closer to the stage, roughly $120–$200. The sweet spot for sound and sightlines without floor pricing.',
        ),
        2 => 
        array (
          'name' => 'Floor / General Admission',
          'desc' => 'Standing room closest to the stage, typically $180–$300. Billie\'s floor is GA on most dates, so arriving early matters more than the exact ticket.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP runs $300 and up and bundles premium seating or early GA entry with merch and early load-in. It is not a meet-and-greet on this tour — read what each package includes before paying the premium.',
        ),
      ),
      'why' => 'Billie Eilish\'s on-sales use dynamic pricing, which means Ticketmaster adjusts the price in real time based on how fast a section is selling. A date that opens at $90 can climb past $150 within minutes for an in-demand city, then ease back as more dates are added or resale supply grows. Prices are generally lowest right at on-sale and for weekday shows, and highest for weekend dates in major markets.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much do Billie Eilish tickets cost in 2026?',
          'a' => 'Most Billie Eilish tickets fall between about $65 and $350. Upper-bowl seats start near $65, lower bowl runs $120–$200, and floor/GA plus VIP sit at the top of that band. Dynamic pricing means the live figure changes by city and date — the cards on this page show the current "from" price for each show.',
        ),
        1 => 
        array (
          'q' => 'Why are Billie Eilish ticket prices so high?',
          'a' => 'High-demand arena dates use dynamic pricing, so prices rise while a section is selling quickly. The face value isn\'t fixed — it reflects real-time demand. Weekday and newly added dates are usually cheaper than the first weekend shows that sell out fastest.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Billie Eilish?',
          'a' => 'Buy upper-bowl seats at on-sale for a weekday date, and compare cities on the same tour — a Tuesday in a smaller market is often half the price of a Saturday in a major one for the same show. Live prices for every date are listed on this page.',
        ),
        3 => 
        array (
          'q' => 'Do Billie Eilish ticket prices drop closer to the show?',
          'a' => 'Sometimes. Resale prices can soften in the final week if a show hasn\'t sold out, but in-demand dates hold firm or climb. There\'s no guaranteed last-minute discount, so it\'s a gamble against missing out.',
        ),
        4 => 
        array (
          'q' => 'How much are floor tickets for Billie Eilish?',
          'a' => 'Floor and GA tickets typically run $180–$300 depending on the city. Billie\'s floor is general admission on most 2026 dates, so the ticket gets you in — your spot depends on how early you line up.',
        ),
        5 => 
        array (
          'q' => 'Are VIP tickets worth it for Billie Eilish?',
          'a' => 'VIP packages ($300+) bundle premium seating or early GA entry with merch and early load-in, but they are not meet-and-greets on this tour. They\'re worth it mainly if early GA access or a guaranteed premium seat matters to you.',
        ),
        6 => 
        array (
          'q' => 'When do Billie Eilish tickets go on sale?',
          'a' => 'Tickets release a few months before each leg, with artist and Ticketmaster presales running a day or two ahead of the general on-sale. New dates appear on this page as soon as they\'re live.',
        ),
        7 => 
        array (
          'q' => 'Are these official Billie Eilish tickets?',
          'a' => 'Yes. The prices and dates here come from official ticketing partners, and checkout completes on the partner\'s secure site with instant e-ticket delivery. We never resell or mark up tickets.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Hit Me Hard and Soft: The Tour',
      'intro' => 
      array (
        0 => 'Billie Eilish is touring arenas worldwide on "Hit Me Hard and Soft: The Tour," supporting her third album. Every confirmed date with a live ticket price is listed below; new shows are added automatically the moment they go on sale, so this is the fastest place to catch a date in your city.',
        1 => 'The production is staged in the round on most arena dates, with Billie performing on a central stage — which is why even upper-bowl seats keep a clean view. If your dates are flexible, mid-week shows are routinely cheaper than the first weekend of each city\'s run.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Billie Eilish touring in 2026?',
          'a' => 'Yes — Billie Eilish is on the road with "Hit Me Hard and Soft: The Tour," playing arenas across North America, Europe and beyond. Every confirmed date on sale appears on this page with live prices.',
        ),
        1 => 
        array (
          'q' => 'What cities is Billie Eilish playing?',
          'a' => 'The current tour covers major arena markets across multiple countries. The date cards above show exactly which cities have tickets on sale right now, each linking to live seat availability.',
        ),
        2 => 
        array (
          'q' => 'How long is a Billie Eilish concert?',
          'a' => 'A Billie Eilish headline set runs roughly 1 hour 45 minutes to 2 hours, plus the opening act. Doors typically open about 90 minutes before the headline set.',
        ),
        3 => 
        array (
          'q' => 'Who is opening for Billie Eilish?',
          'a' => 'Support acts vary by leg and city. Check the individual event page for a given date to see the confirmed opener and set times.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Billie Eilish "Hit Me Hard and Soft" setlist leans on the new album front to back, woven together with the hits that built her — expect a two-act flow that moves from the whispered, intimate songs into the heavier, bass-driven moments. The list below reflects what she\'s been playing on recent arena dates; the exact running order shifts night to night.',
      ),
      'songs' => 
      array (
        0 => 'CHIHIRO',
        1 => 'LUNCH',
        2 => 'NDA',
        3 => 'Therefore I Am',
        4 => 'wish you were gay',
        5 => 'idontwannabeyouanymore',
        6 => 'Skinny',
        7 => 'TV',
        8 => 'The Greatest',
        9 => 'your power',
        10 => 'BIRDS OF A FEATHER',
        11 => 'Happier Than Ever',
        12 => 'bad guy',
        13 => 'bury a friend',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary every night, and Billie often swaps in older songs or stripped-back moments — treat this as a guide, not a guarantee.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Billie Eilish play live?',
          'a' => 'Recent setlists mix the full "Hit Me Hard and Soft" album — including BIRDS OF A FEATHER, CHIHIRO and LUNCH — with career hits like bad guy, Happier Than Ever and Therefore I Am. The exact order changes night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Billie Eilish play bad guy live?',
          'a' => 'Yes — bad guy is a fixture of the current set, usually landing in the high-energy back half of the show alongside the bass-heavy tracks.',
        ),
        2 => 
        array (
          'q' => 'How many songs is a Billie Eilish setlist?',
          'a' => 'A typical 2026 set runs around 22–26 songs across roughly 1 hour 45 minutes, structured in two acts that move from intimate to high-energy.',
        ),
        3 => 
        array (
          'q' => 'Does Billie Eilish do an encore?',
          'a' => 'The current production often runs as a continuous two-act show rather than a separate encore, closing on her biggest singalongs. It can vary by night.',
        ),
      ),
    ),
  ),
  'taylor-swift' => 
  array (
    'name' => 'Taylor Swift',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 49,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Taylor Swift ticket prices sit at the upper end of the live music market because she performs almost exclusively in stadiums, where demand routinely outstrips supply. Face-value seats generally open from the low double digits for distant upper sections and climb steadily toward premium floor positions and packages. Because primary allocations sell quickly, many buyers encounter resale listings well above original prices, so it helps to understand where a given seat sits before committing.',
        1 => 'What you pay for Taylor Swift tickets depends on the city, the venue\'s size and the seating tier rather than a single flat figure. Stadium shows mean a wide spread between the cheapest rear-corner seats and central lower-bowl or pitch-standing positions. Official VIP packages add merchandise, early entry or premium views at a higher cost. The bands below reflect typical primary pricing seen across her recent stadium runs.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear sections',
          'desc' => 'The most affordable Taylor Swift seats, high in the stands or in rear corners of the stadium, typically run about $49 to $130. Views are distant but the staging and screens are built for these sightlines.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and side lower bowl',
          'desc' => 'Side and elevated lower-bowl seats with clearer angles on the stage generally fall between $130 and $300. A common middle choice balancing price against proximity.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and pitch standing',
          'desc' => 'Central lower-level seats and general-admission pitch or floor standing usually sit around $300 to $600, offering close, front-facing views of the main stage.',
        ),
        3 => 
        array (
          'name' => 'Premium and front floor',
          'desc' => 'Front-of-stage floor positions and prime lower-bowl centre seats commonly range from $600 to about $900 at face value, with the highest demand of any standard category.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and hospitality packages, bundling premium seating with merchandise, early entry or lounge access, typically run from roughly $500 up to $1,200 depending on the inclusions and city.',
        ),
      ),
      'why' => 'Taylor Swift\'s stadium tours are sold through platforms that use dynamic pricing, meaning the cost of many seats moves with live demand rather than staying fixed. When interest is high, prices for popular sections can rise during an on-sale, and standard seats sit beside higher-priced official platinum and VIP categories. Resale prices are set by individual sellers and frequently exceed face value, sometimes by a wide margin. Reviewing the seat location, the category label and whether a listing is primary or resale gives the clearest sense of value before buying.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Taylor Swift tickets?',
          'a' => 'Taylor Swift tickets generally range from about $49 for upper-level stadium seats to $900 or more for premium front-floor positions, with official VIP packages reaching around $1,200. The exact figure depends on the city, the venue and how close the seat is to the stage.',
        ),
        1 => 
        array (
          'q' => 'Why are Taylor Swift tickets so expensive?',
          'a' => 'Taylor Swift plays stadiums where demand far exceeds available seats, and her tours use dynamic pricing that lifts costs for sought-after sections. Resale listings, set by individual sellers, often push prices well above the original face value, which is why secondary tickets can look especially high.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Taylor Swift live?',
          'a' => 'The cheapest seats are usually high in the upper levels or rear corners of the stadium, often starting near $49 at face value. Buying during the primary on-sale rather than on the resale market tends to give the lowest prices, as resale costs are typically higher.',
        ),
        3 => 
        array (
          'q' => 'Do Taylor Swift tickets use dynamic pricing?',
          'a' => 'Yes. Most of Taylor Swift\'s stadium shows are sold through systems that apply dynamic pricing, so the cost of many seats shifts with demand during the on-sale. Alongside standard tickets you will also see higher-priced platinum and official VIP categories.',
        ),
        4 => 
        array (
          'q' => 'How much do Taylor Swift VIP packages cost?',
          'a' => 'Official Taylor Swift VIP packages usually run from around $500 to $1,200, depending on the city and what is included. They typically bundle premium seating with extras such as merchandise, early venue entry or lounge access rather than just a ticket.',
        ),
        5 => 
        array (
          'q' => 'Are floor tickets worth it for Taylor Swift?',
          'a' => 'Floor and pitch-standing tickets put you close to the main stage and front-facing, which many fans value, and they generally cost from about $300 upward. Whether they are worth it depends on your budget and whether you prefer proximity over a seated, elevated view of the full staging.',
        ),
        6 => 
        array (
          'q' => 'Why do Taylor Swift ticket prices vary so much between cities?',
          'a' => 'Prices vary because stadium capacities, local demand and seating layouts differ from city to city, and dynamic pricing responds to each market separately. A central lower-bowl seat in one stadium may be priced differently from a comparable seat elsewhere on the same tour.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Taylor Swift tickets at face value or resale?',
          'a' => 'Face value through the primary on-sale is generally cheaper than resale. Resale prices are set by individual sellers and for Taylor Swift\'s shows have often run far above the original cost, so the official on-sale is usually the more economical option when seats are available.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Eras Tour',
      'intro' => 
      array (
        0 => 'Taylor Swift\'s most recent headline run was The Eras Tour, a stadium production structured as a journey through the distinct eras of her catalogue. Tour dates spanned multiple continents across an extended schedule, with shows built around a long main set divided by album chapter. Because any future routing is announced city by city, the most reliable approach is to check the official listings for each market rather than relying on advance rumour.',
        1 => 'The Eras Tour set the template for how Taylor Swift tour dates are typically organised: large stadium venues, multi-night stands in major cities, and staggered on-sales by region. When new shows are confirmed, they are usually released in batches with registration or presale steps ahead of the general on-sale. Anyone tracking dates should follow her official channels and the venue announcements, since scheduling and availability differ from one city to the next.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Taylor Swift touring in 2026?',
          'a' => 'Taylor Swift\'s confirmed most-recent tour, The Eras Tour, concluded in late 2024. Any further dates for 2026 are confirmed on a city-by-city basis, so the most reliable source is her official tour listings and the relevant venue announcements rather than unverified speculation.',
        ),
        1 => 
        array (
          'q' => 'What was Taylor Swift\'s last tour called?',
          'a' => 'Taylor Swift\'s most recent tour was The Eras Tour, a stadium show structured around the different eras of her discography. It ran across multiple continents and featured a long, chaptered main set covering material from across her albums.',
        ),
        2 => 
        array (
          'q' => 'How long is a Taylor Swift concert?',
          'a' => 'On The Eras Tour, a Taylor Swift show typically ran well over three hours, reflecting an unusually long main set spanning multiple album eras. Running times can vary by show, so it is worth allowing extra time around the listed start.',
        ),
        3 => 
        array (
          'q' => 'How can I find Taylor Swift tour dates?',
          'a' => 'Taylor Swift tour dates are released and updated city by city, usually in batches with presale and registration steps ahead of the general on-sale. Checking her official tour page and the individual venue listings gives the most accurate and current schedule for each market.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Taylor Swift setlist is built as a tour through her albums, with The Eras Tour pulling staples from across her catalogue into a single long show. Recent setlists have featured era-defining singles alongside fan favourites, arranged into themed segments rather than a straight greatest-hits order. The songs below are genuine live staples from her recent stadium shows, though the running order and selections shift between performances.',
      ),
      'songs' => 
      array (
        0 => 'Cruel Summer',
        1 => 'The Man',
        2 => 'You Belong with Me',
        3 => 'Love Story',
        4 => 'Blank Space',
        5 => 'Style',
        6 => 'Shake It Off',
        7 => 'Wildest Dreams',
        8 => 'Bad Blood',
        9 => 'We Are Never Ever Getting Back Together',
        10 => 'Delicate',
        11 => 'Don\'t Blame Me',
        12 => 'Look What You Made Me Do',
        13 => 'Enchanted',
        14 => '...Ready for It?',
        15 => 'August',
        16 => 'Cardigan',
        17 => 'The 1',
        18 => 'Anti-Hero',
        19 => 'Karma',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night. Taylor Swift performs different acoustic surprise songs at each show, and the running order and selections can change between performances, so any given night may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Taylor Swift play live?',
          'a' => 'Taylor Swift\'s recent shows feature staples such as Cruel Summer, Love Story, Blank Space, Style, Shake It Off, Anti-Hero and Karma, drawn from across her albums. The set is arranged by era, and she also includes acoustic surprise songs that change each night.',
        ),
        1 => 
        array (
          'q' => 'Does Taylor Swift play an encore?',
          'a' => 'The Eras Tour runs as one continuous, era-by-era show rather than a separate encore segment, closing on a high-energy run including Karma. Because the set flows without a traditional encore break, the final songs effectively serve that role.',
        ),
        2 => 
        array (
          'q' => 'What are the surprise songs at a Taylor Swift concert?',
          'a' => 'At each Eras Tour show Taylor Swift performs two acoustic surprise songs drawn from across her catalogue, and they differ from city to city. Because they are deliberately rotated to make every night distinct, there is no fixed list of which ones you will hear.',
        ),
        3 => 
        array (
          'q' => 'Is the Taylor Swift setlist the same every night?',
          'a' => 'No. While the main era-by-era structure stays largely consistent, Taylor Swift changes her acoustic surprise songs at every show and the running order can vary. Setlists differ from night to night, so each performance is somewhat unique.',
        ),
      ),
    ),
  ),
  'coldplay' => 
  array (
    'name' => 'Coldplay',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 900,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Coldplay are a stadium act, and that scale shapes what their ticket prices look like. Face value across recent runs has generally started around the mid double figures for upper-level and standing-adjacent seats and climbed into the several hundreds for lower-tier and pitch positions. The band also releases a small allocation of low-cost Infinity Tickets for most shows, which sit well below the standard range and are sold in pairs.',
        1 => 'Where a Coldplay seat lands within that band depends on the venue, the city and how close to the stage you sit. Front pitch, side-stage and premium lower-bowl seats carry the highest face values, while upper tiers and rear standing are the most affordable standard options. Hospitality and VIP packages add early entry, lounge access and merchandise, which is why those prices run well clear of a plain ticket.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Infinity Tickets',
          'desc' => 'A limited low-cost allocation released for most shows, typically around 20-45 USD per ticket and sold in pairs, with the seat location revealed close to the show date. Aimed at keeping the tour accessible rather than guaranteeing a particular view.',
        ),
        1 => 
        array (
          'name' => 'Upper tier and rear standing',
          'desc' => 'The most affordable standard seats, generally in the 70-160 USD range. Higher up or further from the stage, but inside a production built to be seen from across a stadium, including the wristband light show.',
        ),
        2 => 
        array (
          'name' => 'Lower tier and side seating',
          'desc' => 'Mid-band seats with a clearer sightline to the main stage, usually around 160-350 USD depending on city and position. A common middle choice for those who want a fixed seat closer to the action.',
        ),
        3 => 
        array (
          'name' => 'Pitch standing and premium lower bowl',
          'desc' => 'Floor standing and the best lower-level seats, often 300-550 USD. Closest to the main and B-stages, and the area where dynamic pricing tends to push face values highest on busy dates.',
        ),
        4 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages from roughly 500 USD up to around 900 USD or beyond, bundling premium seating or early pitch entry with lounge access, dedicated entrances, merchandise and other extras.',
        ),
      ),
      'why' => 'Coldplay tours are promoted by Live Nation and ticketed largely through Ticketmaster, which means most shows use dynamic pricing. Under that model, standard face values move with demand: high-demand seats and the busiest dates can rise above their starting price during a sale, while quieter sections may settle lower. Infinity Tickets and official VIP packages are priced separately from the dynamic standard tiers. Resale prices on the secondary market are set by sellers and routinely exceed face value, so they are not a reliable guide to what the band charges directly.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Coldplay tickets?',
          'a' => 'Coldplay ticket prices generally run from around 45 USD for upper-tier and limited Infinity Tickets up to roughly 900 USD for VIP and hospitality packages, with most standard seats falling between about 70 and 350 USD. The exact figure depends on the venue, the city and how close you sit to the stage.',
        ),
        1 => 
        array (
          'q' => 'Why are Coldplay tickets so expensive?',
          'a' => 'Coldplay play stadiums with a large-scale production, including the synchronised wristband light show, and demand consistently outstrips supply. Because most dates use dynamic pricing, the busiest shows and best seats can climb well above their starting face value, which pushes the upper end of the range higher than for smaller venue acts.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Coldplay?',
          'a' => 'The cheapest official route is usually an Infinity Ticket, a limited low-cost allocation released for most shows at around 20-45 USD and sold in pairs, with the seat assigned closer to the date. Failing that, upper-tier and rear standing tickets are the most affordable standard seats.',
        ),
        3 => 
        array (
          'q' => 'What are Coldplay Infinity Tickets?',
          'a' => 'Infinity Tickets are a small batch of deliberately low-priced tickets that Coldplay release for most shows to keep the tour accessible. They are sold in pairs at a flat low price, and the specific seat location is revealed shortly before the concert rather than at the point of purchase.',
        ),
        4 => 
        array (
          'q' => 'Do Coldplay tickets use dynamic pricing?',
          'a' => 'Most Coldplay dates ticketed through Ticketmaster use dynamic pricing, so standard face values rise and fall with demand during a sale. Infinity Tickets and official VIP packages sit outside that system and are priced separately, while resale listings are set by individual sellers.',
        ),
        5 => 
        array (
          'q' => 'How much are Coldplay VIP and hospitality packages?',
          'a' => 'Official Coldplay VIP and hospitality packages typically start around 500 USD and can reach 900 USD or more. They bundle premium seating or early pitch entry with extras such as lounge access, a dedicated entrance and merchandise, which is why they sit above standard ticket prices.',
        ),
        6 => 
        array (
          'q' => 'Are resale Coldplay tickets more expensive than face value?',
          'a' => 'Resale Coldplay tickets are almost always more expensive than face value, because secondary-market prices are set by individual sellers rather than the band. Listings can run several times the original price for popular dates, so they are not a reliable guide to what Coldplay charge directly.',
        ),
        7 => 
        array (
          'q' => 'How much is a pitch or floor ticket for Coldplay?',
          'a' => 'Pitch standing and premium lower-bowl seats are typically the priciest standard option, often around 300-550 USD depending on the city and date. These positions are closest to the main and B-stages, and dynamic pricing tends to push them toward the top of that band on high-demand nights.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Music of the Spheres World Tour',
      'intro' => 
      array (
        0 => 'Coldplay\'s most recent run of tour dates falls under the Music of the Spheres World Tour, named after their 2021 album and staged almost entirely in stadiums. The production is built around bright primary colours, a sustainability focus and the LED wristbands handed to every attendee, which light up in time with the show. The tour has run across multiple continents and stands among the highest-attended concert tours on record.',
        1 => 'After an extended stretch of stadium dates, the Music of the Spheres World Tour paused following a run at Wembley Stadium, with the band indicating further shows would follow in a later year rather than continuing without a break. Future Coldplay tour dates are confirmed and scheduled by the band and Live Nation, so the most reliable plan is to check official listings for your region rather than relying on rumour.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Coldplay touring in 2026?',
          'a' => 'Coldplay\'s Music of the Spheres World Tour paused after a run at Wembley Stadium, and the band have signalled the show will resume in a later year rather than running continuously. Any new dates are confirmed by Coldplay and Live Nation, so official listings are the place to verify whether shows are scheduled in your region.',
        ),
        1 => 
        array (
          'q' => 'What is Coldplay\'s current tour called?',
          'a' => 'Coldplay\'s current and most recent tour is the Music of the Spheres World Tour, named after their 2021 album. It is staged predominantly in stadiums and is known for its colour-led production, sustainability measures and the synchronised LED wristbands given to the audience.',
        ),
        2 => 
        array (
          'q' => 'How long is a Coldplay concert?',
          'a' => 'A Coldplay headline show on the Music of the Spheres World Tour generally runs around two hours to two and a quarter hours, spanning the main set and encore. Exact length varies night to night depending on the set and any guest appearances, and support acts play earlier in the evening.',
        ),
        3 => 
        array (
          'q' => 'Where does Coldplay usually play on tour?',
          'a' => 'On the Music of the Spheres World Tour, Coldplay play almost exclusively in large stadiums across Europe, the Americas, Asia, Oceania and the Middle East, often staging multiple nights in major cities. The scale suits the tour\'s wraparound staging, B-stage and audience-wide wristband effects.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Coldplay\'s Music of the Spheres World Tour setlist mixes singles spanning their whole catalogue with material from the album the tour is named after. A typical night opens with the spoken Higher Power intro and runs through career staples before moving to a smaller B-stage in the crowd for a stripped-back stretch, then building back to the full-production finale. Roughly 18 to 19 songs stay consistent from show to show, with a few rotating slots.',
      ),
      'songs' => 
      array (
        0 => 'Higher Power',
        1 => 'Adventure of a Lifetime',
        2 => 'Paradise',
        3 => 'The Scientist',
        4 => 'Viva la Vida',
        5 => 'Hymn for the Weekend',
        6 => 'Charlie Brown',
        7 => 'Yellow',
        8 => 'Human Heart',
        9 => 'People of the Pride',
        10 => 'Clocks',
        11 => 'Something Just Like This',
        12 => 'My Universe',
        13 => 'A Sky Full of Stars',
        14 => 'Sparks',
        15 => 'Fix You',
        16 => 'Every Teardrop Is a Waterfall',
        17 => 'We Pray',
        18 => 'feelslikeimfallinginlove',
      ),
      'encore' => 
      array (
        0 => 'Sparks',
        1 => 'Fix You',
        2 => 'Every Teardrop Is a Waterfall',
        3 => 'A Sky Full of Stars',
      ),
      'note' => 'Setlists vary night to night. The song list above reflects recurring staples from recent Music of the Spheres World Tour shows; the band rotate certain slots, swap in requests or acoustic numbers on the B-stage, and occasionally add a one-off cover, so any given concert may differ in choices and running order.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Coldplay play live?',
          'a' => 'On the Music of the Spheres World Tour, Coldplay reliably play career staples such as Yellow, Viva la Vida, Paradise, The Scientist, Clocks, Fix You and A Sky Full of Stars, alongside newer material like Higher Power, My Universe and We Pray. Around 18 to 19 songs stay consistent each night, with a handful of rotating slots.',
        ),
        1 => 
        array (
          'q' => 'Does Coldplay do an encore?',
          'a' => 'Yes. Coldplay shows on the Music of the Spheres World Tour close with an encore that typically features a B-stage stretch and a full-production finale, often built around songs such as Sparks, Fix You and A Sky Full of Stars. The exact encore choices vary from night to night.',
        ),
        2 => 
        array (
          'q' => 'Does Coldplay open with Higher Power?',
          'a' => 'Coldplay have regularly opened Music of the Spheres World Tour shows with a spoken Music of the Spheres intro leading into Higher Power, followed by Adventure of a Lifetime. The opening is not guaranteed, as the band adjust the running order between shows, but it has been the common pattern on this tour.',
        ),
        3 => 
        array (
          'q' => 'Do Coldplay play old songs live?',
          'a' => 'Yes. Coldplay\'s live set draws across their whole catalogue, so older songs such as Yellow, The Scientist, Clocks, Paradise and Fix You feature alongside more recent releases. The band also rotate occasional deeper cuts and acoustic requests into the B-stage section, so the back catalogue is well represented.',
        ),
      ),
    ),
  ),
  'shakira' => 
  array (
    'name' => 'Shakira',
    'genre' => 'Latin Pop',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Shakira ticket prices sit across a fairly wide band, shaped by whether a given show lands in an arena or a larger stadium and how close to the stage the seat falls. Her recent touring has mixed both formats, so the same night can offer upper-level seats at a modest figure alongside floor positions that climb considerably higher. The bands below describe how Shakira pricing tends to be structured rather than any single date, since released seats and final figures are confirmed at the point of sale.',
        1 => 'As a long-running Latin pop headliner, Shakira draws strong demand, and that demand feeds directly into where prices settle on each Shakira ticket. Entry-level seats remain the most accessible way in, while front-section and packaged options carry a clear premium. Treat the ranges here as a general guide to relative cost across the seating map, with exact amounts varying by city, venue size and how quickly a particular show fills.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most accessible Shakira seats, generally around 70 to 130 USD, covering upper tiers and positions toward the back of the floor. These give a full view of the staging and choreography from a distance and are the seats that tend to clear first on lower-demand dates.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and mid-floor',
          'desc' => 'Mid-tier seating, roughly 130 to 280 USD, taking in lower-bowl sides and the central floor. This band balances proximity against cost and is where a large share of general inventory usually sits for a Shakira show.',
        ),
        2 => 
        array (
          'name' => 'Front floor and prime lower bowl',
          'desc' => 'Closer positions, broadly 280 to 450 USD, covering front-floor blocks and prime lower-bowl seats near the stage. Expect the strongest sightlines of the standard map here, with prices rising on higher-demand nights.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages, typically 450 to 600 USD and occasionally beyond, bundling premium seating with extras such as early entry, dedicated check-in or branded merchandise. Contents differ by city and are set by the tour\'s hospitality partner rather than fixed nightly.',
        ),
      ),
      'why' => 'Shakira\'s recent shows run through Ticketmaster and Live Nation, both of which apply dynamic pricing on a portion of seats. Under that model the listed face value of certain sections moves up or down with live demand rather than staying fixed, so a section can read higher when a date sells quickly and ease back when it does not. The bands above reflect typical released face values; resale and platform listings can sit well above them. Final amounts, plus any service fees, are confirmed only at checkout for the specific date and seat.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Shakira tickets?',
          'a' => 'Shakira tickets generally range from about 70 USD for upper-level seats to around 600 USD for VIP and hospitality packages. Most standard lower-bowl and mid-floor seats fall somewhere between roughly 130 and 280 USD. The figure for any given night depends on the venue size, the seat location and live demand at the time of purchase.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Shakira live?',
          'a' => 'The cheapest Shakira seats are usually the upper tiers and rear-floor positions, often starting near 70 USD on lower-demand dates. These offer a full view of the staging from a distance. Booking early, before dynamic pricing lifts popular sections, is the most reliable way to secure the lower end of the range.',
        ),
        2 => 
        array (
          'q' => 'Why do Shakira ticket prices change so much?',
          'a' => 'Shakira tours through Ticketmaster and Live Nation, which apply dynamic pricing to part of the seating map. That means the face value of some sections rises and falls with real-time demand, so prices for the same seat type can differ between dates and even shift during a single sale window.',
        ),
        3 => 
        array (
          'q' => 'Are Shakira VIP packages worth it?',
          'a' => 'Shakira VIP packages, generally 450 to 600 USD, bundle premium seating with extras such as early entry, a dedicated check-in lane or exclusive merchandise. Whether they suit you depends on how much you value those additions over a standard front-section seat, since the underlying view can be similar. Package contents are set per city by the tour\'s hospitality partner.',
        ),
        4 => 
        array (
          'q' => 'Do Shakira ticket prices differ between arena and stadium shows?',
          'a' => 'Yes. Shakira\'s recent touring has mixed intimate arena dates with larger stadium shows, and that affects pricing. Arena nights tend to carry a higher floor on entry seats given limited capacity, while stadiums spread inventory across more tiers, which can widen the gap between the cheapest and most expensive Shakira seats.',
        ),
        5 => 
        array (
          'q' => 'How much are floor seats for Shakira?',
          'a' => 'Front-floor and prime lower-bowl seats for Shakira typically run from about 280 to 450 USD, with mid-floor positions sitting lower at roughly 130 to 280 USD. Floor pricing reflects proximity to the stage and rises on higher-demand nights under dynamic pricing, so exact figures vary by date and city.',
        ),
        6 => 
        array (
          'q' => 'Are resale Shakira tickets more expensive than face value?',
          'a' => 'Often, yes. Resale listings for Shakira can sit well above the original face value, particularly for in-demand dates, and average secondary-market prices have run several hundred dollars on some shows. Buying from the primary seller at the on-sale stage is generally the way to stay closest to the face-value bands described here.',
        ),
        7 => 
        array (
          'q' => 'Do Shakira tickets include service fees?',
          'a' => 'The headline Shakira price usually excludes service and processing fees, which are added at checkout. These fees vary by seller and venue, so the final amount can sit noticeably above the listed face value. The total for your specific seat and date is confirmed before payment on the checkout screen.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Las Mujeres Ya No Lloran World Tour',
      'intro' => 
      array (
        0 => 'Shakira tour dates fall under the Las Mujeres Ya No Lloran World Tour, the run supporting her twelfth studio album of the same name. The tour opened in early 2025 and has continued into 2026 across the Americas, Europe and beyond, taking in both large stadiums and a set of more intimate arena nights. New legs and individual Shakira tour dates are confirmed by the tour in stages rather than all at once.',
        1 => 'Because routing is announced city by city, the live schedule of Shakira tour dates is injected separately and changes as fresh shows are added. The tour has been among the most commercially successful Latin runs on record, and demand has stayed high across markets. For confirmed dates, venues and on-sale timing, refer to the official listings, which are kept current as each leg is released.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Shakira touring in 2026?',
          'a' => 'Yes. Shakira\'s Las Mujeres Ya No Lloran World Tour has continued into 2026, with shows announced across multiple regions including a run of arena dates and a stadium residency. The tour has expanded in stages, so individual cities are confirmed over time. Check the official live listings for the most current 2026 Shakira tour dates.',
        ),
        1 => 
        array (
          'q' => 'What is Shakira\'s current tour called?',
          'a' => 'Shakira\'s current tour is the Las Mujeres Ya No Lloran World Tour, named after her twelfth studio album. It launched in early 2025 and supports the record\'s themes, mixing newer material with her established catalogue across stadium and arena shows worldwide.',
        ),
        2 => 
        array (
          'q' => 'How long is a Shakira concert?',
          'a' => 'A Shakira concert on the Las Mujeres Ya No Lloran World Tour typically runs in the region of two hours, covering a setlist of around 20 or more songs plus interludes and costume changes. Exact running time varies by date, and set times for each venue are confirmed closer to the show.',
        ),
        3 => 
        array (
          'q' => 'Does Shakira play stadiums or arenas?',
          'a' => 'Both. The Las Mujeres Ya No Lloran World Tour has played large stadiums in many markets while also adding a set of more intimate arena dates. The format affects capacity and pricing, so it is worth checking the specific venue for any Shakira tour date you are considering.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Shakira setlist on the Las Mujeres Ya No Lloran World Tour pairs newer album tracks with the hits that span her career, moving between Spanish and English material across the night. Recent shows have run past 20 songs, built around medleys, an acoustic stretch and full-production staging. The Shakira setlist below reflects songs that have featured regularly on the current run.',
      ),
      'songs' => 
      array (
        0 => 'La Fuerte',
        1 => 'Girl Like Me',
        2 => 'Las de la Intuición',
        3 => 'Estoy Aquí',
        4 => 'Inevitable',
        5 => 'Empire',
        6 => 'Te Felicito',
        7 => 'TQG',
        8 => 'Don\'t Bother',
        9 => 'Copa Vacía',
        10 => 'La Tortura',
        11 => 'Hips Don\'t Lie',
        12 => 'Chantaje',
        13 => 'Monotonía',
        14 => 'Loca',
        15 => 'Última',
        16 => 'Ojos Así',
        17 => 'Antología',
        18 => 'Whenever, Wherever',
        19 => 'Waka Waka (This Time for Africa)',
      ),
      'encore' => 
      array (
        0 => 'Bzrp Music Sessions, Vol. 53',
        1 => 'Loba',
      ),
      'note' => 'Setlists vary night to night, and the order, medleys and song choices can change from show to show on the Las Mujeres Ya No Lloran World Tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Shakira play live?',
          'a' => 'Shakira\'s live setlist on the Las Mujeres Ya No Lloran World Tour features staples such as Hips Don\'t Lie, Whenever, Wherever, Waka Waka (This Time for Africa), Ojos Así and Te Felicito, alongside newer tracks like La Fuerte and Copa Vacía. The exact run of songs varies from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Shakira play Hips Don\'t Lie live?',
          'a' => 'Yes. Hips Don\'t Lie is a regular fixture of Shakira\'s current setlist and usually lands among the high-energy numbers in the main set. As with any song, its placement can shift from date to date, but it has featured consistently on the Las Mujeres Ya No Lloran World Tour.',
        ),
        2 => 
        array (
          'q' => 'Does Shakira do an encore?',
          'a' => 'Shakira\'s recent shows have closed with a final high-tempo stretch, with the Bizarrap Session 53 collaboration often serving as a late or closing moment. Whether it is staged as a formal encore varies by night, and the ending of the Shakira setlist can change from show to show.',
        ),
        3 => 
        array (
          'q' => 'Does Shakira sing in English and Spanish at her concerts?',
          'a' => 'Yes. A Shakira concert moves between both languages, pairing English-language hits such as Whenever, Wherever and Hips Don\'t Lie with Spanish material like Antología, Ojos Así and TQG. The bilingual mix is a consistent feature of the Las Mujeres Ya No Lloran World Tour setlist.',
        ),
      ),
    ),
  ),
  'diljit-dosanjh' => 
  array (
    'name' => 'Diljit Dosanjh',
    'genre' => 'Punjabi',
    'prices' => 
    array (
      'range_low' => 90,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Diljit Dosanjh ticket prices generally sit in the upper-mid band for a live music act, reflecting a touring operation that moves between large arenas and full stadiums. Entry-level seats typically open around the lower end of the range, while floor positions, premium blocks and hospitality packages climb considerably higher. Because Dosanjh plays both arena and stadium dates, the spread on any given night depends heavily on the size of the room and where a seat sits within it.',
        1 => 'Where a Diljit Dosanjh ticket lands within the band comes down to venue scale, seat location and how quickly a particular show sells. Stadium shows tend to carry a wider price ceiling than arena dates, and the closer a seat is to the stage, the steeper the price. Service and facility fees are added at checkout and are not included in the face values quoted here.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear bowl',
          'desc' => 'The most economical way in, usually running from around 90 to 160 USD. These seats sit in the higher levels or behind the stage and give a full view of the production rather than close proximity to Dosanjh himself.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side blocks',
          'desc' => 'Mid-range seating, broadly 160 to 280 USD, covering the lower seated levels and side sections. A balance of sightline and value, and often the first tier to sell through on arena dates.',
        ),
        2 => 
        array (
          'name' => 'Floor and front blocks',
          'desc' => 'Premium standing or seated floor positions close to the stage, typically 280 to 450 USD. Demand here is high, and on stadium nights the upper end of this tier can stretch further still.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages combining prime seating with extras such as early entry, merchandise or lounge access, generally from 450 USD upward to around 600 USD. Availability and inclusions vary by city and venue.',
        ),
      ),
      'why' => 'Diljit Dosanjh\'s recent dates run through Ticketmaster and Live Nation, which apply dynamic pricing to many shows. Under that model the listed price for in-demand seats moves with real-time demand rather than holding a single fixed figure, so the same seat can cost more during a surge and ease back when demand softens. This is why two buyers can pay different amounts for comparable seats on the same night, and why face values are best treated as a guide rather than a fixed figure. Resale listings sit on top of this and can run above or below face value depending on how a show is selling. Final checkout prices also include service and facility fees that are not reflected in the bands above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Diljit Dosanjh tickets?',
          'a' => 'Diljit Dosanjh tickets generally range from around 90 USD for upper-tier seats to roughly 600 USD for VIP and hospitality packages, before fees. Most standard seats fall between these points, with lower-bowl and floor positions commanding the higher prices. Exact figures depend on the venue, the seat and demand for that particular date.',
        ),
        1 => 
        array (
          'q' => 'Why do Diljit Dosanjh ticket prices change?',
          'a' => 'Prices change because his tours use dynamic pricing through Ticketmaster and Live Nation, where the cost of in-demand seats rises and falls with real-time demand. Stadium dates also carry a wider ceiling than arena shows, and resale listings add further variation. The same seat can therefore carry a different price from one moment to the next.',
        ),
        2 => 
        array (
          'q' => 'Are Diljit Dosanjh tickets expensive?',
          'a' => 'Diljit Dosanjh tickets sit in the upper-mid range for live music rather than at the very top end. Entry-level seats are relatively accessible, while floor, premium and VIP options carry a clear premium. Buyers wanting the lowest price tend to look at upper-tier and rear-bowl seating.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Diljit Dosanjh live?',
          'a' => 'The most economical seats are usually in the upper tier or rear bowl, often starting around 90 to 160 USD before fees. These positions give a full view of the stage and production at the lowest entry point, though they sit furthest from the performer.',
        ),
        4 => 
        array (
          'q' => 'Do Diljit Dosanjh tickets include fees?',
          'a' => 'The face values quoted here do not include service and facility fees, which Ticketmaster and Live Nation add at checkout. The final amount paid is therefore higher than the listed seat price, and fees vary by venue and ticket type.',
        ),
        5 => 
        array (
          'q' => 'Are there VIP packages for Diljit Dosanjh concerts?',
          'a' => 'Yes, official VIP and hospitality packages are usually offered on his tours, typically from around 450 USD upward. These combine premium seating with extras such as early entry, exclusive merchandise or lounge access, though the exact inclusions vary by city and venue.',
        ),
        6 => 
        array (
          'q' => 'How much are Diljit Dosanjh floor tickets?',
          'a' => 'Floor and front-block tickets for Diljit Dosanjh generally run from around 280 to 450 USD before fees, and can stretch higher on stadium dates. These are among the most sought-after positions, placing buyers close to the stage, so they tend to sell quickly and sit toward the top of the standard price range.',
        ),
        7 => 
        array (
          'q' => 'Do Diljit Dosanjh stadium shows cost more than arena shows?',
          'a' => 'Stadium dates tend to carry a wider price ceiling than arena shows, particularly for premium and floor positions. Arena shows are more contained, so their top prices are usually lower, while stadium nights such as larger international dates can push the upper end of the range further.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Aura World Tour',
      'intro' => 
      array (
        0 => 'Diljit Dosanjh\'s current run is the Aura World Tour, which has carried him across arenas and stadiums internationally. The tour follows the global momentum he built on earlier outings and moves between large indoor venues and full stadium shows depending on the market. For anyone tracking Diljit Dosanjh tour dates, the routing spans multiple countries, with the scale of each show shaped by local demand.',
        1 => 'The Aura World Tour leans on a high-energy production that blends bhangra, hip-hop and pop with a full live show. Diljit Dosanjh tour dates are released city by city through official channels, and the lineup of venues ranges from mid-sized arenas to landmark stadiums. As with most major tours, individual dates can be added or adjusted, so the schedule is best confirmed through official listings.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Diljit Dosanjh touring in 2026?',
          'a' => 'Yes, Diljit Dosanjh is touring on the Aura World Tour, which runs across arenas and stadiums in multiple countries. The routing covers North America and international markets, with dates released city by city. Specific shows and on-sale details are confirmed through official channels rather than fixed here.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Diljit Dosanjh\'s current tour?',
          'a' => 'Diljit Dosanjh\'s current tour is the Aura World Tour. It follows his earlier global runs and moves between large arenas and full stadiums, with a production blending bhangra, hip-hop and pop in a full live show.',
        ),
        2 => 
        array (
          'q' => 'What venues does the Diljit Dosanjh Aura World Tour play?',
          'a' => 'The Aura World Tour plays a mix of major arenas and stadiums depending on the city, ranging from large indoor venues to landmark outdoor grounds. The exact venue for each date is listed through official channels, as the scale varies by market and demand.',
        ),
        3 => 
        array (
          'q' => 'How long is a Diljit Dosanjh concert?',
          'a' => 'A Diljit Dosanjh show on the Aura World Tour typically runs across an extended set spanning his catalogue, often featuring more than two dozen songs. Exact running times vary by venue and night, and door and start times are confirmed by each venue.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Diljit Dosanjh setlist on the Aura World Tour draws across his Punjabi catalogue and crossover hits, opening at high energy and building through bhangra, hip-hop and pop. The set typically runs to more than two dozen songs and has included tributes to Punjabi legend Amar Singh Chamkila. The Diljit Dosanjh setlist below reflects songs that have featured as live staples on recent dates, though the exact order shifts from show to show.',
      ),
      'songs' => 
      array (
        0 => 'G.O.A.T.',
        1 => '5 Taara',
        2 => 'Do You Know',
        3 => 'Born To Shine',
        4 => 'Proper Patola',
        5 => 'Patiala Peg',
        6 => 'Peaches',
        7 => 'Lover',
        8 => 'Lemonade',
        9 => 'Kinni Kinni',
        10 => 'Ikk Kudi',
        11 => 'Naina',
        12 => 'Hass Hass',
        13 => 'Putt Jatt Da',
        14 => 'Laembadgini',
        15 => 'Vibe',
        16 => 'Clash',
        17 => 'Lalkaara',
        18 => 'Mitran Da Junction',
        19 => 'Ishq Mitaye',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, so the songs and running order above are a guide drawn from recent shows rather than a fixed list. Diljit Dosanjh adjusts his set by city and date, and individual songs may be added, dropped or reordered on any given night.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Diljit Dosanjh play live?',
          'a' => 'Diljit Dosanjh\'s live sets feature staples such as G.O.A.T., 5 Taara, Do You Know, Born To Shine, Patiala Peg, Peaches and Lover, alongside crossover tracks like Hass Hass and Lemonade. The set typically opens at high energy and runs across his Punjabi catalogue, though the exact lineup changes night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Diljit Dosanjh do an encore?',
          'a' => 'Diljit Dosanjh\'s shows tend to run as a continuous high-energy set rather than a formal encore break, closing on an anthem such as Ishq Mitaye. The exact ending varies by night, but the format leans toward an unbroken run through his catalogue rather than a separate encore segment.',
        ),
        2 => 
        array (
          'q' => 'What song does Diljit Dosanjh open with?',
          'a' => 'On recent Aura World Tour dates Diljit Dosanjh has opened with G.O.A.T., setting a high-energy tone from the start. Openers can change from night to night, so the first song is not fixed, but G.O.A.T. has been a recurring choice on this run.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Diljit Dosanjh play in concert?',
          'a' => 'A Diljit Dosanjh concert on the current tour typically spans more than two dozen songs, drawing across his Punjabi hits and crossover tracks. The exact count varies by night and venue, and has at times included tribute songs to Amar Singh Chamkila within the wider set.',
        ),
      ),
    ),
  ),
  'bad-bunny' => 
  array (
    'name' => 'Bad Bunny',
    'genre' => 'Reggaeton',
    'prices' => 
    array (
      'range_low' => 90,
      'range_high' => 750,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Bad Bunny ticket prices sit at the higher end of the live market because his current run is an all-stadium tour, where venue scale and demand both push face values up. As a rough guide, standard seats tend to open around the lower hundreds of dollars, while floor and front-section places cost considerably more. Hospitality and VIP packages sit above that again, and resale figures frequently exceed face value once a show sells through.',
        1 => 'Because his shows routinely sell out within hours, much of the available inventory moves to the resale market, where prices float with demand rather than a fixed tariff. The bands below reflect typical primary face values for a stadium date; treat resale as a separate, usually higher, layer rather than the baseline.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / general stadium seating',
          'desc' => 'Standard seated positions higher in the bowl, the most affordable way in. Face values here typically run from about $90 to $180 depending on the market and sightline.',
        ),
        1 => 
        array (
          'name' => 'Lower tier / side floor',
          'desc' => 'Closer seated sections and side-of-stage standing, with a clearer view of the staging. Expect roughly $180 to $350 at face value, more on resale for sold-out dates.',
        ),
        2 => 
        array (
          'name' => 'Front floor / premium seats',
          'desc' => 'Prime standing pens near the stage and premium seated blocks. These commonly land between $350 and $550 face value, with strong resale demand pushing higher.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages bundling early entry, premium placement, lounge access or merchandise. These typically start around $500 and reach $750 or beyond depending on inclusions.',
        ),
      ),
      'why' => 'Bad Bunny\'s tour is sold through Ticketmaster and promoted by Live Nation, both of which use dynamic pricing on high-demand shows. That means the listed face value can move up or down in real time according to how quickly a section is selling, so two buyers in the same block may pay different amounts depending on when they purchase. Genuinely fixed prices are rare on a tour of this scale; the figures above describe the usual range rather than a guaranteed rate.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Bad Bunny tickets?',
          'a' => 'Face values for Bad Bunny\'s stadium shows generally run from around $90 for upper-tier seats to $550 or more for front-floor and premium placements, with VIP packages reaching $750 and beyond. Because the tour sells out quickly, much of the remaining stock sits on resale, where prices are typically higher and move with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Bad Bunny tickets so expensive?',
          'a' => 'The main driver is that this is an all-stadium tour with demand far exceeding supply, so dynamic pricing and the resale market both push figures up. Stadium production costs and the scale of the staging also feed into face values, which sit above what you would pay for a comparable arena act.',
        ),
        2 => 
        array (
          'q' => 'Do Bad Bunny ticket prices use dynamic pricing?',
          'a' => 'Yes. Shows sold through Ticketmaster and promoted by Live Nation commonly use dynamic pricing, so the face value of a section can rise or fall in real time as it sells. This is why prices quoted for the same area can differ between buyers depending on timing.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Bad Bunny live?',
          'a' => 'The lowest face values are usually upper-tier seated positions, which tend to start around $90 to $180 depending on the city. Buying during the initial on-sale rather than later on resale also helps, since resold inventory on sold-out dates typically carries a premium.',
        ),
        4 => 
        array (
          'q' => 'How much do Bad Bunny VIP packages cost?',
          'a' => 'Official VIP and hospitality packages generally begin around $500 and can exceed $750, depending on what they include. Typical extras are early entry, premium standing or seating, lounge access and exclusive merchandise, which account for the step up over a standard ticket.',
        ),
        5 => 
        array (
          'q' => 'Are Bad Bunny resale tickets more expensive than face value?',
          'a' => 'Usually, yes. Because his dates sell out fast, a large share of available tickets ends up on the resale market, where prices float with demand and frequently sit above the original face value. The gap tends to widen as a show approaches and stock thins out.',
        ),
        6 => 
        array (
          'q' => 'How much are floor tickets for Bad Bunny?',
          'a' => 'Front-floor standing and premium seats commonly carry face values between about $350 and $550, with side-floor positions a little lower at roughly $180 to $350. On sold-out dates these are among the first to move to resale at higher prices.',
        ),
        7 => 
        array (
          'q' => 'Do Bad Bunny ticket prices vary by city?',
          'a' => 'Yes. Face values differ between markets according to local demand, stadium capacity and pricing strategy, so the same tier can cost more in one city than another. The bands above describe a typical spread rather than a single fixed rate across every date.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Debí Tirar Más Fotos World Tour',
      'intro' => 
      array (
        0 => 'Bad Bunny\'s current run is the Debí Tirar Más Fotos World Tour, an all-stadium production supporting his 2025 album of the same name. Tour dates span the Caribbean, Latin America, Australia, Asia and Europe, with shows staged in large outdoor venues rather than arenas. Because the routing reaches multiple regions across an extended schedule, exact dates are best checked against the live listings, which are kept current alongside this page.',
        1 => 'The tour is notable for its scale and its focus on markets outside the United States, with extended residencies in several cities. Demand has been high throughout, and individual dates have sold out rapidly, so availability for any given Bad Bunny show varies considerably from one market to the next.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Bad Bunny touring in 2026?',
          'a' => 'Yes. Bad Bunny is on the Debí Tirar Más Fotos World Tour, which runs into 2026 with dates across Latin America, Australia, Asia and Europe. The schedule is all-stadium, and current dates for each city are listed alongside this page as they are confirmed.',
        ),
        1 => 
        array (
          'q' => 'What is Bad Bunny\'s current tour called?',
          'a' => 'It is the Debí Tirar Más Fotos World Tour, named after his 2025 album. The run is built around large stadium shows and reaches the Caribbean, Latin America, Australia, Asia and Europe rather than focusing on the United States.',
        ),
        2 => 
        array (
          'q' => 'Is Bad Bunny touring the United States?',
          'a' => 'The Debí Tirar Más Fotos World Tour deliberately skips the United States mainland and is routed through Latin America, Australia, Asia and Europe instead. Fans based in the US have generally travelled to international dates or attended his Puerto Rico residency shows; check the live listings for the most accurate current routing.',
        ),
        3 => 
        array (
          'q' => 'How many shows is Bad Bunny playing on this tour?',
          'a' => 'The Debí Tirar Más Fotos World Tour is an extended run of dozens of stadium dates, including multi-night residencies in several cities. Because the schedule is large and spread across regions, the precise count and remaining dates are best confirmed from the current listings.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Bad Bunny setlist on the Debí Tirar Más Fotos World Tour blends songs from the 2025 album with established hits drawn from across his catalogue. Recent shows have been built in distinct sections, moving between a main stage and a smaller staging area, and the running order leans on a core group of tracks that recur night to night. The selection below reflects songs that have been consistent live staples on the current run.',
      ),
      'songs' => 
      array (
        0 => 'NUEVAYoL',
        1 => 'BAILE INoLVIDABLE',
        2 => 'Tití Me Preguntó',
        3 => 'Callaíta',
        4 => 'Me Porto Bonito',
        5 => 'Yo Perreo Sola',
        6 => 'Safaera',
        7 => 'Efecto',
        8 => 'Mónaco',
        9 => 'DÁKITI',
        10 => 'Yonaguni',
        11 => 'Ojitos Lindos',
        12 => 'El Apagón',
        13 => 'Neverita',
        14 => 'Si Veo a Tu Mamá',
        15 => 'VeLDÁ',
        16 => 'TURiSTA',
        17 => 'DtMF',
        18 => 'EoO',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order and selection of songs change from show to show; each date also features a one-off song that is not repeated elsewhere on the tour, so the list above is a guide rather than a fixed running order.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Bad Bunny play live?',
          'a' => 'On the current tour Bad Bunny mixes recent material such as NUEVAYoL, BAILE INoLVIDABLE and DtMF with established hits including Tití Me Preguntó, Me Porto Bonito, Safaera and DÁKITI. The exact selection shifts between shows, so the running order is best treated as a guide rather than a guarantee.',
        ),
        1 => 
        array (
          'q' => 'Does Bad Bunny play Tití Me Preguntó live?',
          'a' => 'Yes. Tití Me Preguntó has been a regular fixture on the Debí Tirar Más Fotos World Tour, sitting among the catalogue hits performed alongside newer album tracks. As with any song, its placement in the set can move from night to night.',
        ),
        2 => 
        array (
          'q' => 'Does Bad Bunny do an encore?',
          'a' => 'His current shows tend to run as a continuous, sectioned production rather than closing with a separate encore. The set moves through distinct stages and builds to a finale, so the format differs from the traditional walk-off-and-return encore.',
        ),
        3 => 
        array (
          'q' => 'How long is a Bad Bunny concert?',
          'a' => 'Recent Bad Bunny shows have run for roughly two and a half to three hours, covering around thirty songs across the different staging sections. Exact length varies by date, since the setlist and any one-off additions change from show to show.',
        ),
      ),
    ),
  ),
  'beyonce' => 
  array (
    'name' => 'Beyoncé',
    'genre' => 'Pop / R&B',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Beyoncé ticket prices sit at the higher end of the live music market because she performs almost exclusively in stadiums, where production scale and demand both push costs up. Across a typical run, face-value seats begin in the region of 70 USD for upper-level positions and climb past 1,000 USD for floor sections close to the stage. The exact figure depends on the city, the venue and how early a seat is bought.',
        1 => 'As with most large stadium tours, Beyoncé tickets are sold across several tiers rather than at a single price. Upper-bowl seats are the most affordable, lower-bowl and side-stage positions occupy the middle, and floor and premium hospitality packages reach the top of the range. Understanding which tier a seat falls into is the clearest way to judge whether a listed price is reasonable before purchase.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Seats in the upper tiers of the stadium, generally from around 70 to 150 USD at face value. These give a full view of the stage and the screens at the lowest entry cost, and are usually the first option for those prioritising budget over proximity.',
        ),
        1 => 
        array (
          'name' => 'Lower level',
          'desc' => 'Lower-bowl seating along the sides and behind the stage, broadly in the 150 to 350 USD band. These positions are closer to the action than the upper tiers while remaining seated, and tend to balance price against sightlines.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Floor seating and the closest blocks to the stage, typically from around 350 to 700 USD. Demand for these is high, so they are among the first to sell and the most likely to be resold at a premium.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official premium packages, branded on the Cowboy Carter run with names such as Real Live Boogie and Club Ho Down, that bundle prime seating with extras. These commonly run from roughly 480 USD to 1,200 USD depending on the inclusions and the city.',
        ),
      ),
      'why' => 'Beyoncé\'s tours are promoted through Live Nation and sold via Ticketmaster, which means most dates use dynamic pricing. Under that model the listed face value of a seat moves with demand in real time, so identical seats can cost different amounts at different moments and a popular night may price higher than a quieter one. Official VIP and platinum tiers are priced separately again. Buyers comparing options should note whether a price is a primary face value or a resale listing, as the two can diverge sharply for an artist at this level of demand.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Beyoncé tickets?',
          'a' => 'Beyoncé tickets generally start near 70 USD for upper-level seats and rise past 1,000 USD for floor and premium positions, because she plays stadiums where demand is consistently high. The precise price depends on the city, the seat tier and the timing of purchase, since most dates use dynamic pricing that shifts with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Beyoncé tickets so expensive?',
          'a' => 'Beyoncé tickets are priced at the upper end mainly because she performs in stadiums with large-scale staging and her shows draw very high demand. Dynamic pricing on Ticketmaster then lets face values rise as a date sells, and premium and VIP tiers add further cost above standard seating.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Beyoncé live?',
          'a' => 'The cheapest seats are in the upper tiers of the stadium, often around 70 to 150 USD at face value. Buying early in a primary sale, before dynamic pricing climbs, and choosing a less in-demand date typically secures the lowest available price.',
        ),
        3 => 
        array (
          'q' => 'Do Beyoncé tickets use dynamic pricing?',
          'a' => 'Yes. Beyoncé\'s tours run through Ticketmaster, and most dates use dynamic pricing, so the face value of a seat changes in line with demand rather than staying fixed. This is why two buyers can pay different amounts for comparable seats on the same night.',
        ),
        4 => 
        array (
          'q' => 'How much are VIP or front-row Beyoncé tickets?',
          'a' => 'Official VIP packages and front sections sit at the top of the range, commonly from around 480 USD into four figures depending on the city and inclusions. On the Cowboy Carter run these were branded with names such as Real Live Boogie and Club Ho Down, bundling prime seating with added extras.',
        ),
        5 => 
        array (
          'q' => 'Are resale Beyoncé tickets more expensive than face value?',
          'a' => 'They often are. Because demand for Beyoncé is high, resale listings for sought-after seats can sit well above the original face value, particularly for floor positions. Comparing a resale price against the primary tiers is the clearest way to judge whether a listing is fair.',
        ),
        6 => 
        array (
          'q' => 'How much did Beyoncé tickets cost on the Cowboy Carter Tour?',
          'a' => 'On the Cowboy Carter Tour, standard face-value seats began in the region of 70 USD for the upper levels and reached past 1,000 USD for floor sections, with VIP packages higher again. Prices varied by city and moved with dynamic pricing throughout the on-sale period.',
        ),
        7 => 
        array (
          'q' => 'Does the ticket price include fees?',
          'a' => 'Not always. The advertised face value of a Beyoncé ticket usually excludes service and processing fees, which are added at checkout, so the final total is higher than the headline figure. The exact fees depend on the seller and the market, so it is worth reviewing the full breakdown before paying.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Cowboy Carter Tour',
      'intro' => 
      array (
        0 => 'Beyoncé\'s most recent run of tour dates was the Cowboy Carter Tour, staged across major North American and European stadiums and built around her 2024 album Cowboy Carter alongside material from across her catalogue. The production runs close to three hours and is organised into distinct acts, drawing on country, R&B and pop. New tour dates beyond this run are announced through her official channels and her promoter rather than confirmed in advance here.',
        1 => 'Beyoncé typically tours in support of a new album, and her recent dates have concentrated on stadiums in the United States and the United Kingdom and Europe. Because the schedule of any future tour, including which cities and venues are included, is set by Beyoncé and her promoter, the most reliable approach is to follow official announcements and verified on-sale information rather than rely on speculation about routing.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Beyoncé touring in 2026?',
          'a' => 'Beyoncé\'s most recent confirmed run was the Cowboy Carter Tour in 2025; any 2026 dates would be announced separately through her official channels and her promoter. Tours are usually tied to album cycles, so confirmed schedules and venues should be checked against verified announcements rather than assumed.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Beyoncé\'s current tour?',
          'a' => 'Beyoncé\'s most recent tour was the Cowboy Carter Tour, supporting her 2024 album Cowboy Carter. It ran across stadiums in North America and Europe and was structured into themed acts spanning country, R&B and pop material from across her career.',
        ),
        2 => 
        array (
          'q' => 'Where does Beyoncé usually perform on tour?',
          'a' => 'Beyoncé performs almost entirely in large stadiums, with recent dates concentrated in major United States cities and across the United Kingdom and Europe. The scale of her staging means her shows are routed to the largest available venues rather than arenas or theatres.',
        ),
        3 => 
        array (
          'q' => 'How long is a Beyoncé concert?',
          'a' => 'A Beyoncé show on the Cowboy Carter Tour ran close to three hours, divided into distinct acts with multiple costume and staging changes. Exact running times vary by night, but her stadium shows are consistently among the longer headline sets in current live music.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Beyoncé\'s recent setlist on the Cowboy Carter Tour drew heavily on her 2024 album Cowboy Carter and combined it with established hits from Renaissance, Lemonade and earlier records. The show is built in acts, moving between country-leaning material, R&B and pop across a run of more than thirty songs. The list below reflects staples that recurred across the tour rather than the order of any single performance.',
      ),
      'songs' => 
      array (
        0 => 'AMERIICAN REQUIEM',
        1 => 'Blackbiird',
        2 => 'Freedom',
        3 => 'YA YA',
        4 => 'AMERICA HAS A PROBLEM',
        5 => 'SPAGHETTII',
        6 => 'Formation',
        7 => 'Diva',
        8 => 'TEXAS HOLD \'EM',
        9 => '16 CARRIAGES',
        10 => 'PROTECTOR',
        11 => 'DAUGHTER',
        12 => 'II MOST WANTED',
        13 => 'JOLENE',
        14 => 'CUFF IT',
        15 => 'ALIEN SUPERSTAR',
        16 => 'CRAZY IN LOVE',
        17 => 'TYRANT',
        18 => 'THIQUE',
        19 => 'AMEN',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Beyoncé regularly reorders, adds or drops songs between dates, so any given show may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Beyoncé play live?',
          'a' => 'On the Cowboy Carter Tour, Beyoncé\'s live set leaned on album tracks such as AMERIICAN REQUIEM, TEXAS HOLD \'EM and 16 CARRIAGES alongside catalogue favourites including CRAZY IN LOVE, CUFF IT and Formation. The full set ran past thirty songs and was arranged into themed acts.',
        ),
        1 => 
        array (
          'q' => 'Does Beyoncé do an encore?',
          'a' => 'Beyoncé\'s Cowboy Carter shows ran as a continuous, act-based production rather than closing with a separate encore, typically ending on AMEN. The arrangement varies, but the structure favours a single sustained set over the traditional encore format.',
        ),
        2 => 
        array (
          'q' => 'What song does Beyoncé open with?',
          'a' => 'On the Cowboy Carter Tour, Beyoncé generally opened with AMERIICAN REQUIEM, the first track from the album, before moving into Blackbiird and Freedom. Openers can change between nights, so the first song is not fixed across every date.',
        ),
        3 => 
        array (
          'q' => 'Does Beyoncé play her old hits on tour?',
          'a' => 'Yes. Alongside the Cowboy Carter material, Beyoncé\'s recent setlist included older hits such as CRAZY IN LOVE, Formation, Diva and CUFF IT. Her stadium sets typically balance new album tracks with established favourites drawn from across her career.',
        ),
      ),
    ),
  ),
  'the-weeknd' => 
  array (
    'name' => 'The Weeknd',
    'genre' => 'R&B / Pop',
    'prices' => 
    array (
      'range_low' => 65,
      'range_high' => 850,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'The Weeknd performs at stadium scale, so ticket prices tend to sit higher than for arena or theatre acts. Across a typical run, the cheapest upper-tier seats often begin in the region of 65 to 110 USD, while floor positions, lower-bowl seats and hospitality packages climb well beyond that. Because the shows are staged in the round with a large central runway, sightline quality varies considerably by section, which is the main factor shaping where prices land.',
        1 => 'Beyond the standard seated bands, The Weeknd dates usually offer general-admission standing, premium club access and VIP packages that bundle early entry, merchandise and lounge areas. These sit at the top of the range. The figures below describe how the tiers generally behave rather than any single confirmed price, since live face values are set per venue and per market and are injected separately.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level seated',
          'desc' => 'Higher-tier seats further from the stage, typically the most affordable option at roughly 65 to 130 USD. Good for the full-stadium production and screen visuals, with a more distant view of the runway.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl seated',
          'desc' => 'Mid-level seats closer to the action around the central stage, generally in the region of 130 to 280 USD. A balance of price and proximity, with clearer sightlines to the runway.',
        ),
        2 => 
        array (
          'name' => 'Floor and general admission',
          'desc' => 'Standing positions on the stadium floor near the in-the-round stage, usually about 180 to 400 USD. Closest non-premium access, though views can shift as the show moves along the runway.',
        ),
        3 => 
        array (
          'name' => 'Premium and front sections',
          'desc' => 'Prime floor and front-of-stage seating with the best vantage points, commonly between 350 and 600 USD depending on the venue and date.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Hospitality and VIP bundles with early entry, dedicated lounges, merchandise and premium placement, typically ranging from 500 to 850 USD and occasionally higher for the most exclusive tiers.',
        ),
      ),
      'why' => 'Most major stadium tours, including The Weeknd\'s Live Nation-produced dates, use dynamic pricing. This means face values move up or down with demand rather than staying fixed, so identical seats can cost different amounts depending on when you buy and how quickly a show sells. Platinum and official VIP tiers are priced separately and usually sit at the upper end. The bands shown here are general guidance; the system injects current live prices for each specific date and venue.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are The Weeknd tickets?',
          'a' => 'The Weeknd tickets generally range from around 65 USD for upper-level seats to 850 USD or more for VIP and hospitality packages. Because he plays stadiums in the round, lower-bowl seats and floor standing fall in the middle of that band, while premium front sections sit toward the top.',
        ),
        1 => 
        array (
          'q' => 'Why do The Weeknd ticket prices change so much?',
          'a' => 'Prices vary because his tours use dynamic pricing, where face values rise and fall with demand. The same seat can cost more during peak buying periods. Venue size, market and seat location all push prices across the wide stadium-scale band.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see The Weeknd live?',
          'a' => 'The cheapest seats are usually in the upper levels of the stadium, often starting in the 65 to 110 USD region. These offer a full view of the production and screens, though the central in-the-round stage is further away than from lower sections.',
        ),
        3 => 
        array (
          'q' => 'How much do The Weeknd VIP packages cost?',
          'a' => 'VIP packages for The Weeknd typically run from around 500 to 850 USD, and occasionally higher for the most exclusive tiers. They generally include early entry, lounge access, merchandise and premium placement, with exact contents differing by venue.',
        ),
        4 => 
        array (
          'q' => 'Are The Weeknd floor tickets worth it?',
          'a' => 'Floor and general-admission standing tickets, usually about 180 to 400 USD, put you closest to the in-the-round stage and runway. They suit fans who want proximity, though views can change as the performance moves along the central walkway.',
        ),
        5 => 
        array (
          'q' => 'Do The Weeknd ticket prices differ by city?',
          'a' => 'Yes. Face values are set per venue and market, so the same tier can cost more in larger or higher-demand cities. Stadium capacity, local taxes and fees, and demand for each date all influence the final price.',
        ),
        6 => 
        array (
          'q' => 'What does Platinum pricing mean for The Weeknd shows?',
          'a' => 'Platinum tickets are standard seats priced dynamically to reflect real-time demand, not a separate VIP experience. They often cost more than face value and sit toward the upper end of the range, with no added perks beyond the seat itself.',
        ),
        7 => 
        array (
          'q' => 'How early should I buy The Weeknd tickets for the best price?',
          'a' => 'With dynamic pricing, buying early often secures lower face values before demand pushes them up, particularly for popular dates. That said, prices can also soften closer to a show if inventory remains, so there is no single guaranteed cheapest moment.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'After Hours Til Dawn',
      'intro' => 
      array (
        0 => 'The Weeknd\'s current run of tour dates falls under the After Hours Til Dawn banner, a stadium production he has carried across multiple legs since its launch. The show ties together material from After Hours, Dawn FM and the Hurry Up Tomorrow album, staged in the round with a large central runway and elaborate visuals. It has become one of the highest-grossing tours by a solo male artist, reflecting the scale at which he now performs.',
        1 => 'The 2026 leg extends the tour through North America, South America, Europe, the UK and Asia, with Playboi Carti featuring as support on a number of dates. The production keeps the same in-the-round staging that defines the run. Specific dates, venues and on-sale details are handled separately by the system; this section describes the tour in general terms rather than confirming any single show.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is The Weeknd touring in 2026?',
          'a' => 'Yes, The Weeknd is touring in 2026 with further legs of the After Hours Til Dawn stadium tour, reaching North America, South America, Europe, the UK and Asia. The run has been billed as the tour\'s final stretch, with Playboi Carti supporting on several dates.',
        ),
        1 => 
        array (
          'q' => 'What is the name of The Weeknd\'s current tour?',
          'a' => 'The Weeknd\'s current tour is called After Hours Til Dawn. It is a stadium production staged in the round, drawing on his After Hours, Dawn FM and Hurry Up Tomorrow releases, and it ranks among the highest-grossing tours by a solo male artist.',
        ),
        2 => 
        array (
          'q' => 'Who is the support act on The Weeknd\'s tour?',
          'a' => 'Playboi Carti features as a support act on a number of After Hours Til Dawn dates. Supporting line-ups can vary by region and venue, so the opener for any particular show depends on the market and date.',
        ),
        3 => 
        array (
          'q' => 'Where is The Weeknd performing on the After Hours Til Dawn tour?',
          'a' => 'The After Hours Til Dawn tour plays major stadiums across North America, South America, Europe, the UK and Asia. Venues are large-capacity stadiums chosen to suit the in-the-round staging, with the exact list of cities and dates set per leg.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical The Weeknd setlist on the After Hours Til Dawn tour spans his full catalogue, moving from early House of Balloons-era material through the After Hours and Dawn FM hits to recent Hurry Up Tomorrow songs. The roughly two-hour show is built around the in-the-round stage, with the running order weaving older fan favourites between newer tracks. The selections below reflect songs that have featured regularly on recent dates.',
      ),
      'songs' => 
      array (
        0 => 'Baptized in Fear',
        1 => 'Open Hearts',
        2 => 'Wake Me Up',
        3 => 'After Hours',
        4 => 'Starboy',
        5 => 'Heartless',
        6 => 'Take My Breath',
        7 => 'Sacrifice',
        8 => 'Can\'t Feel My Face',
        9 => 'Often',
        10 => 'I Was Never There',
        11 => 'The Hills',
        12 => 'Timeless',
        13 => 'Out of Time',
        14 => 'I Feel It Coming',
        15 => 'Die for You',
        16 => 'Wicked Games',
        17 => 'Call Out My Name',
        18 => 'Save Your Tears',
        19 => 'Blinding Lights',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the running order, song choices and any tape-played interludes change between shows and across the tour\'s legs.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does The Weeknd play live?',
          'a' => 'The Weeknd live sets typically include Blinding Lights, Starboy, Save Your Tears, Can\'t Feel My Face, The Hills, Die for You and Call Out My Name, alongside newer Hurry Up Tomorrow material such as Timeless and Open Hearts. The two-hour show spans his full catalogue.',
        ),
        1 => 
        array (
          'q' => 'Does The Weeknd play Blinding Lights live?',
          'a' => 'Yes, Blinding Lights is a fixture of The Weeknd\'s live shows and usually appears late in the set, often near the closing stretch. As his biggest single, it remains one of the most anticipated moments of the night.',
        ),
        2 => 
        array (
          'q' => 'Does The Weeknd do an encore?',
          'a' => 'The Weeknd\'s After Hours Til Dawn shows generally run as a continuous set rather than breaking for a formal encore. The performance builds through around two hours and closes on its final tracks without the usual leave-and-return encore structure.',
        ),
        3 => 
        array (
          'q' => 'How long is a The Weeknd concert?',
          'a' => 'A The Weeknd concert on the After Hours Til Dawn tour typically lasts about two hours. The set moves through more than 20 songs across his catalogue, staged on an in-the-round design with a central runway, though exact length varies by night.',
        ),
      ),
    ),
  ),
  'ed-sheeran' => 
  array (
    'name' => 'Ed Sheeran',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Ed Sheeran ticket prices vary widely because he plays large stadiums and outdoor venues, where seat location does most of the work in setting the cost. As a general guide, entry-level upper-tier and rear-stage seats sit at the lower end of the band, mid-bowl and lower-tier seats fall in the middle, and front pitch, premium club and hospitality packages reach the top. The figures below describe how the pricing usually breaks down rather than any one show.',
        1 => 'Because Ed Sheeran performs in the round on a central rotating stage, fewer seats carry a true restricted view, which tends to lift the value of the cheaper tiers compared with a conventional end-stage layout. Most listings are sold through the primary seller at face value, while resale prices float with demand. When you compare Ed Sheeran ticket prices across cities, expect higher entry points in the largest markets and for weekend dates.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear-stage',
          'desc' => 'The most affordable seats, generally in the upper bowl or behind the central stage. Prices here usually land around 60 to 100 USD. Sightlines are full given the in-the-round set-up, though you are further from the performer.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and side seating',
          'desc' => 'Lower-bowl side seats and mid-level positions, typically around 100 to 175 USD. A common middle choice that balances proximity against cost without paying premium rates.',
        ),
        2 => 
        array (
          'name' => 'Lower tier and floor',
          'desc' => 'Closer floor and front-of-bowl seats nearer the stage, generally 175 to 280 USD. These bring you within clear view of the rotating stage and tend to sell quickly.',
        ),
        3 => 
        array (
          'name' => 'Front pitch and premium',
          'desc' => 'Standing front pitch and premium reserved seats ringing the central stage, usually 280 to 400 USD. The closest standard positions to Ed Sheeran during the performance.',
        ),
        4 => 
        array (
          'name' => 'Hospitality and VIP packages',
          'desc' => 'Official packages adding early entry, premium viewing, lounge access or merchandise. These often start near the top of the 400 USD band and can rise further depending on what is included.',
        ),
      ),
      'why' => 'Ed Sheeran tours through Ticketmaster and Live Nation, which apply dynamic pricing on many dates. This means face values are not fixed: the system adjusts certain seats up or down according to live demand, so a section listed at one figure early on can read higher once a show sells well. Platinum seats use the same mechanism to price in-demand positions at market rate. Service and facility fees are added at checkout on top of the listed price, and resale figures move independently of face value. The bands above reflect typical primary pricing; popular markets and weekend dates trend toward the upper end.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Ed Sheeran tickets?',
          'a' => 'Ed Sheeran tickets generally run from about 60 USD for upper-tier and rear-stage seats to around 400 USD for front pitch and premium positions, with hospitality packages costing more. Because he plays stadiums with an in-the-round stage, the range is broad and driven mainly by how close you sit. Exact figures depend on the city, the date and demand at the time of booking.',
        ),
        1 => 
        array (
          'q' => 'Why do Ed Sheeran ticket prices change?',
          'a' => 'Most Ed Sheeran dates use dynamic pricing through Ticketmaster, so the cost of certain seats shifts with demand rather than staying fixed. Platinum seats are priced at market rate for sought-after positions. On top of that, service and facility fees are added at checkout, and resale prices move independently. This is why two people in similar seats can pay different amounts.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Ed Sheeran?',
          'a' => 'The lowest Ed Sheeran prices are usually upper-tier or rear-stage seats, often starting near 60 USD at face value. Thanks to his rotating central stage, these positions still offer a full view of the show. Buying through the official primary seller when tickets first release tends to avoid the markups common on resale platforms.',
        ),
        3 => 
        array (
          'q' => 'Are Ed Sheeran VIP packages worth it?',
          'a' => 'Ed Sheeran VIP and hospitality packages typically add early entry, premium viewing areas, lounge access or exclusive merchandise, and usually start at the top of the standard price band. Whether they are worth it depends on how much you value those extras over simply buying a closer standard seat, which can sometimes cost less.',
        ),
        4 => 
        array (
          'q' => 'Do Ed Sheeran ticket prices differ by city?',
          'a' => 'Yes. Ed Sheeran prices tend to be higher in the largest markets and on weekend dates, where demand is strongest, and lower in smaller cities or on midweek shows. The venue also matters, as stadium configurations and local fees vary. Comparing several dates can reveal noticeable differences in entry-level pricing.',
        ),
        5 => 
        array (
          'q' => 'How much are floor or pitch tickets for Ed Sheeran?',
          'a' => 'Front pitch and floor positions near Ed Sheeran\'s central stage generally fall between about 175 and 400 USD, depending on exactly how close they are and the demand for that show. These are among the first sections to sell out, so they often appear at higher figures on resale once primary stock is gone.',
        ),
        6 => 
        array (
          'q' => 'Are there fees on top of Ed Sheeran tickets?',
          'a' => 'Yes. The face value shown is not the final amount, as service and facility fees are added at checkout, and some markets apply local taxes. These fees vary by venue and seller, so the total you pay for an Ed Sheeran ticket is typically higher than the headline price. It is worth checking the full breakdown before confirming.',
        ),
        7 => 
        array (
          'q' => 'Is resale cheaper than face value for Ed Sheeran?',
          'a' => 'Not usually. Because Ed Sheeran shows are in high demand, resale prices often sit above face value, particularly for popular dates and closer seats. Occasionally prices soften close to the show when sellers offload spare tickets, but this is not reliable. Buying at face value through the official seller is generally the more economical route.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Loop Tour',
      'intro' => 
      array (
        0 => 'Ed Sheeran\'s current run is the Loop Tour, a stadium and outdoor-venue tour supporting his 2025 album Play. It follows the long-running Mathematics Tour, which wrapped in 2025, and continues his in-the-round staging on a rotating central stage. Ed Sheeran tour dates for this cycle move through Oceania, Europe and North America across 2026, with multi-night stops in several major cities as the tour progresses.',
        1 => 'As with his previous tours, Ed Sheeran performs largely solo on the Loop Tour, building songs live with a loop pedal rather than a full backing band. The set design is new for this cycle and the running order leans on material from Play alongside catalogue favourites. Specific Ed Sheeran tour dates, venues and on-sale details for each city are listed separately and updated as shows are confirmed.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Ed Sheeran touring in 2026?',
          'a' => 'Yes. Ed Sheeran is on the road with the Loop Tour through 2026, playing stadiums and large outdoor venues across multiple regions including Oceania, Europe and North America. The tour supports his album Play. Individual dates and cities are confirmed on a rolling basis, so the live listings show the most current schedule.',
        ),
        1 => 
        array (
          'q' => 'What is Ed Sheeran\'s current tour called?',
          'a' => 'Ed Sheeran\'s current tour is the Loop Tour, named in reference to the loop-pedal approach that underpins his solo live performances. It succeeds the Mathematics Tour, which ran from 2022 to 2025, and is built around his 2025 album Play together with songs from across his career.',
        ),
        2 => 
        array (
          'q' => 'Where is Ed Sheeran playing on the Loop Tour?',
          'a' => 'The Loop Tour visits stadiums and outdoor venues across several regions, including Australia and New Zealand, Europe and North America, with multi-night residencies in some of the larger cities. The exact venues and routing vary by leg, so the live date listings are the best place to check which shows are scheduled near you.',
        ),
        3 => 
        array (
          'q' => 'Does Ed Sheeran have a support act?',
          'a' => 'Ed Sheeran has featured guest opening acts on past tours, and support slots on the Loop Tour are typically announced per leg or per city rather than as a single fixed line-up. Because supporting artists can differ between dates, the listing for each individual show is where any confirmed openers appear.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'An Ed Sheeran setlist on the Loop Tour mixes singles from his 2025 album Play with the catalogue songs that have anchored his shows for years. Performing largely solo with a loop pedal, he layers guitar, vocals and percussion live, which shapes how each track unfolds on stage. The Ed Sheeran setlist below reflects songs that have featured regularly on recent dates, though the running order is rearranged from night to night.',
      ),
      'songs' => 
      array (
        0 => 'You Need Me, I Don\'t Need You',
        1 => 'Sapphire',
        2 => 'Castle on the Hill',
        3 => 'The A Team',
        4 => 'Shivers',
        5 => 'Don\'t',
        6 => 'Eyes Closed',
        7 => 'Galway Girl',
        8 => 'Old Phone',
        9 => 'Photograph',
        10 => 'Thinking Out Loud',
        11 => 'Perfect',
        12 => 'Bloodstream',
        13 => 'Afterglow',
      ),
      'encore' => 
      array (
        0 => 'Shape of You',
        1 => 'Azizam',
        2 => 'Bad Habits',
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and their order change between shows. Ed Sheeran also takes live requests on the Loop Tour, meaning some tracks differ depending on the audience on any given evening.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Ed Sheeran play live?',
          'a' => 'Ed Sheeran\'s live shows typically feature staples such as Castle on the Hill, Shivers, The A Team, Galway Girl, Thinking Out Loud, Perfect, Bad Habits and Shape of You, alongside newer tracks like Sapphire and Old Phone from his 2025 album Play. He performs them solo using a loop pedal, building each arrangement on stage.',
        ),
        1 => 
        array (
          'q' => 'Does Ed Sheeran do an encore?',
          'a' => 'Yes. Ed Sheeran usually closes the night with an upbeat encore, often finishing on big singalongs such as Shape of You, Azizam and Bad Habits. As his set is built live with a loop pedal, the encore can shift from show to show, but it tends to send the crowd off on a high.',
        ),
        2 => 
        array (
          'q' => 'How long is an Ed Sheeran concert?',
          'a' => 'An Ed Sheeran headline show generally lasts around two hours, though the exact length varies by night. Because he performs solo and builds songs live with a loop pedal, the pacing differs between dates, and the number of tracks can shift depending on how the set is arranged that evening.',
        ),
        3 => 
        array (
          'q' => 'Does Ed Sheeran take requests at his concerts?',
          'a' => 'Yes. A feature of the Loop Tour is that Ed Sheeran often takes live song requests from the audience, so part of the setlist is decided on the night. This means the running order is never fixed, and two shows on the same tour can include different songs depending on what the crowd calls for.',
        ),
      ),
    ),
  ),
  'karol-g' => 
  array (
    'name' => 'Karol G',
    'genre' => 'Reggaeton',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Karol G ticket prices vary by city, seat location and demand, but most of her recent shows have been staged in stadiums, which keeps the overall band wide. Upper-tier seats typically open near the lower end of the range, while floor positions and packages close to the stage sit considerably higher. As a headline reggaeton act now playing venues such as Soldier Field and Hard Rock Stadium, her pricing tends to run above arena level.',
        1 => 'Because Karol G performs in large outdoor and stadium settings, the gap between the cheapest and most expensive Karol G tickets is substantial. Rear general seating offers the most affordable entry, mid-tier seating gives a clearer sightline, and floor or VIP packages carry the steepest prices. The figures below describe how the market generally behaves rather than any single confirmed sale.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most affordable Karol G tickets, usually around 75 to 140 USD, covering upper-tier and rear stadium sections with a full but distant view of the stage.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and lower bowl',
          'desc' => 'Lower-level and side seating roughly in the 140 to 280 USD band, offering closer sightlines than the upper sections without floor-level pricing.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Standing floor and front-block seating that commonly runs from about 280 to 450 USD, depending on city and proximity to the stage.',
        ),
        3 => 
        array (
          'name' => 'VIP and packages',
          'desc' => 'Official VIP packages and premium positions, often 450 to 600 USD or more, which may bundle early entry, dedicated areas or merchandise alongside the seat.',
        ),
      ),
      'why' => 'Karol G\'s stadium tours are promoted through Live Nation and sold on Ticketmaster, which apply dynamic pricing to many shows. This means face values for some sections move with demand rather than staying fixed, so identical seats can cost different amounts at different points in a sale. High-demand cities and on-sale surges push prices toward the upper end of each tier, while quieter dates or later releases can sit lower. Resale listings add a separate layer and are not capped by the original face value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Karol G tickets?',
          'a' => 'Karol G tickets generally range from about 75 USD for upper-tier stadium seats to 600 USD or more for VIP packages and floor positions. Most of her shows are stadium-scale, so prices sit above typical arena levels and vary by city and demand.',
        ),
        1 => 
        array (
          'q' => 'Why do Karol G ticket prices change so much?',
          'a' => 'Her tours use dynamic pricing through Ticketmaster, so face values for some sections rise and fall with demand during a sale. A seat can therefore cost more in a high-demand city or peak on-sale moment than the same seat elsewhere.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Karol G live?',
          'a' => 'The cheapest Karol G tickets are usually upper-level and rear stadium sections, often starting around 75 to 140 USD. These offer the full show at the greatest distance from the stage.',
        ),
        3 => 
        array (
          'q' => 'How much do Karol G floor tickets cost?',
          'a' => 'Floor and front-block tickets for Karol G commonly run from about 280 to 450 USD, depending on the venue and how close the position is to the stage. Demand in larger Latin markets can push these higher.',
        ),
        4 => 
        array (
          'q' => 'Are Karol G VIP packages worth it?',
          'a' => 'Karol G VIP packages typically cost between 450 and 600 USD and may include early entry, premium positioning, dedicated areas or merchandise. Whether they are worth it depends on how much the extras and proximity matter to you against the higher price.',
        ),
        5 => 
        array (
          'q' => 'Do Karol G ticket prices differ by city?',
          'a' => 'Yes. Karol G prices tend to be higher in large Latin-audience markets such as Miami, Houston and Chicago, where demand is strongest, and can be lower in cities with softer sales or for later-announced dates.',
        ),
        6 => 
        array (
          'q' => 'Is resale more expensive than face value for Karol G?',
          'a' => 'Resale Karol G tickets are often higher than original face value, particularly for sold-out stadium dates, because resale prices are not capped by the initial sale. Buying during the primary on-sale is usually the cheaper route.',
        ),
        7 => 
        array (
          'q' => 'What does the price of a Karol G ticket include?',
          'a' => 'A standard Karol G ticket covers entry and the seat or standing area shown. Service and facility fees are added at checkout, and only VIP packages include extras such as early entry or merchandise beyond admission.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Viajando Por El Mundo Tropitour',
      'intro' => 
      array (
        0 => 'Karol G tour dates for the Viajando Por El Mundo Tropitour cover stadiums across North America and beyond, following her run as a Coachella headliner. The routing favours large-capacity venues, so listings tend to appear well in advance and demand is consistently high in cities with sizeable Latin audiences. Exact dates and on-sale timing are confirmed by the promoter and reflected in current listings rather than fixed here.',
        1 => 'The Tropitour continues Karol G\'s move into stadium-scale production after the earlier Mañana Será Bonito and Bichota runs. Tour dates are organised in regional legs, and individual shows can sell quickly once general sale opens. Because schedules can shift, the most reliable view of upcoming Karol G dates is the live listings, which track additions, rescheduling and extra nights as they are announced.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Karol G touring in 2026?',
          'a' => 'Yes. Karol G is touring on the Viajando Por El Mundo Tropitour, a stadium run that follows her Coachella headline performances. Specific dates and cities are shown in the current live listings.',
        ),
        1 => 
        array (
          'q' => 'What is Karol G\'s current tour called?',
          'a' => 'Karol G\'s current tour is the Viajando Por El Mundo Tropitour, which leans into the tropical sound reflected in its name and continues her shift to stadium-scale shows.',
        ),
        2 => 
        array (
          'q' => 'What kind of venues does Karol G play on this tour?',
          'a' => 'The Tropitour is built around large stadiums such as Soldier Field, NRG Stadium and Hard Rock Stadium, rather than arenas, which is why capacity and demand both run high.',
        ),
        3 => 
        array (
          'q' => 'How can I find Karol G tour dates near me?',
          'a' => 'Karol G tour dates are listed by city and venue in the live event listings, which update as new shows, extra nights or rescheduled dates are confirmed. Checking those gives the current schedule for your area.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical Karol G setlist draws heavily on her 2025 album Tropicoqueta alongside Mañana Será Bonito and her older catalogue, mixing reggaeton, urbano and the tropical sound that names the Tropitour. Recent shows open on high-energy material and build toward the larger singles, with collaborations woven in. The list below reflects songs that have featured regularly rather than a fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'PROVENZA',
        1 => 'TQG',
        2 => 'Tusa',
        3 => 'MAMIII',
        4 => 'BICHOTA',
        5 => 'EL MAKINON',
        6 => 'AMARGURA',
        7 => 'Si Antes Te Hubiera Conocido',
        8 => 'GATÚBELA',
        9 => 'QLONA',
        10 => 'X SI VOLVEMOS',
        11 => 'OKI DOKI',
        12 => 'S91',
        13 => 'LATINA FOREVA',
        14 => 'Papasito',
        15 => 'Mi Cama',
        16 => '200 COPAS',
        17 => 'CAIRO',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, and Karol G regularly rotates songs, collaborations and the running order between shows, so any given concert may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Karol G play live?',
          'a' => 'Karol G\'s live shows centre on hits such as PROVENZA, TQG, Tusa, MAMIII and BICHOTA, with EL MAKINON, AMARGURA and QLONA also featuring regularly. Recent material from Tropicoqueta, including Si Antes Te Hubiera Conocido, LATINA FOREVA and Papasito, anchors the current set.',
        ),
        1 => 
        array (
          'q' => 'Does Karol G play TQG and Tusa live?',
          'a' => 'Yes. TQG and Tusa are both regular fixtures in Karol G\'s sets, two of her best-known collaborations and dependable singalong moments across recent tours.',
        ),
        2 => 
        array (
          'q' => 'How long is a Karol G concert?',
          'a' => 'A Karol G headline show typically runs in the region of two hours, with close to thirty songs across her catalogue, though the exact length and song count vary by night and venue.',
        ),
        3 => 
        array (
          'q' => 'Does Karol G do an encore?',
          'a' => 'Karol G\'s stadium shows tend to run as a continuous set built around her biggest singles rather than a formal encore, though the closing stretch and song choices change from night to night.',
        ),
      ),
    ),
  ),
  'drake' => 
  array (
    'name' => 'Drake',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Drake ticket prices tend to sit in the mid-to-upper arena band, reflecting the scale of the venues he fills rather than the higher tiers commanded by stadium headliners. Entry-level seats usually open around the cost of a standard arena ticket, while floor positions close to the stage and packaged seats carry a clear premium. Where a show lands within that band depends on the city, the night of the week and how the venue is configured.',
        1 => 'Because most of Drake\'s runs are sold through Ticketmaster and Live Nation, the headline figure quoted for a Drake ticket is a starting point rather than a fixed rate. The exact price you pay shifts with demand, seat location and timing, so two people in the same section can pay different amounts. Reading the ticket prices alongside the seat map is the most reliable way to judge value before committing.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and value seats',
          'desc' => 'The most affordable way in, typically running from around 55 to 110 USD. These sit in the upper bowl or behind the stage, trading sightline proximity for a lower outlay while keeping the full production in view.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side stage',
          'desc' => 'Mid-range seating in the lower tier and along the sides, generally from about 110 to 200 USD. A common balance between price and a clear, head-on view of the main stage.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Floor seats and the closest blocks to the stage, usually between 200 and 320 USD. Demand here is high, so these are among the first to move and the most exposed to dynamic pricing.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Premium packages with early entry, prime seating and merchandise or hospitality extras, commonly from 320 up to around 450 USD or more depending on the inclusions and the market.',
        ),
      ),
      'why' => 'Drake\'s tours are routed and sold by Live Nation and Ticketmaster, which apply dynamic pricing to in-demand shows. Under that model the published price is not fixed; it moves up or down with live demand, the section selected and how close the date is. High-profile dates and prime floor seats are the most likely to climb above their starting figures, while upper-level seats and quieter markets tend to stay closer to the lower end of the band. Knowing this in advance helps explain why the same seat can be quoted differently from one visit to the next.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Drake tickets?',
          'a' => 'Drake tickets generally range from about 55 USD for upper-level seats to roughly 450 USD for VIP and hospitality packages. Lower-bowl seating tends to fall between those figures, with floor positions sitting near the top of the standard band before packages are added.',
        ),
        1 => 
        array (
          'q' => 'Why do Drake ticket prices change so much?',
          'a' => 'Most Drake dates use dynamic pricing through Ticketmaster, so the figure quoted reflects live demand at the moment you look. Prices rise on busy sections and high-profile nights and ease on quieter dates, which is why the same seat can show different amounts.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Drake live?',
          'a' => 'The cheapest seats are usually in the upper bowl or behind the stage, often starting around 55 USD. These keep the full stage production in view while trading some proximity, and they are typically the lowest-priced option before any fees.',
        ),
        3 => 
        array (
          'q' => 'How much do Drake floor tickets cost?',
          'a' => 'Floor and front-section tickets for Drake commonly run from about 200 to 320 USD. They are the closest standard seats to the stage, sell quickly and are the most affected by dynamic pricing on popular dates.',
        ),
        4 => 
        array (
          'q' => 'Are Drake VIP packages worth the price?',
          'a' => 'Drake VIP packages, usually from around 320 USD upward, bundle premium seating with extras such as early entry, dedicated entrances and merchandise. Whether they are worth it depends on how much you value guaranteed prime seats and the hospitality elements over a standard ticket.',
        ),
        5 => 
        array (
          'q' => 'Do Drake ticket prices include fees?',
          'a' => 'The headline price for a Drake ticket usually excludes service and facility fees, which are added at checkout. Final cost therefore runs above the listed figure, so it is worth checking the full total before confirming a purchase.',
        ),
        6 => 
        array (
          'q' => 'Why are some Drake shows more expensive than others?',
          'a' => 'Pricing varies by city, venue configuration and demand, so a Drake show in a major market can cost noticeably more than one in a smaller city. Weekend dates and venues with limited capacity also tend to carry higher prices.',
        ),
        7 => 
        array (
          'q' => 'How far in advance should I buy Drake tickets?',
          'a' => 'Because Drake\'s better seats move quickly and dynamic pricing tends to firm up as dates approach, earlier purchases often secure both wider choice and steadier prices. Floor and lower-bowl seats in particular are among the first to sell on popular nights.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Some Special Shows 4 U',
      'intro' => 
      array (
        0 => 'Drake\'s most recent headlining run, the Some Special Shows 4 U tour with PARTYNEXTDOOR, played arena audiences across the UK and Europe with a long, catalogue-spanning show built around his collaborative album with PARTYNEXTDOOR. The routing has centred on major arena markets rather than stadiums, which shapes both the scale of the production and how Drake tour dates are spread across each region. New legs and additional cities are confirmed by his team ahead of each run.',
        1 => 'When considering Drake tour dates it is worth noting that the schedule has shifted before, with shows added, moved or rescheduled depending on routing and venue availability. The system here lists only confirmed dates as they are announced, so the most reliable approach is to check the current listings rather than rely on earlier-published plans, which can change between legs of a tour.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Drake touring in 2026?',
          'a' => 'Drake wrapped his Some Special Shows 4 U run with PARTYNEXTDOOR in late 2025, and further activity is published as his team announces it. Confirmed 2026 dates appear in the current date listings as they are added, so checking those listings is the most reliable way to see where and when he is playing.',
        ),
        1 => 
        array (
          'q' => 'What is Drake\'s current tour called?',
          'a' => 'Drake\'s most recent headlining tour is Some Special Shows 4 U, a co-headlining run with PARTYNEXTDOOR in support of their joint album. It played arena venues with a setlist drawn from across his catalogue rather than a single album cycle.',
        ),
        2 => 
        array (
          'q' => 'What kind of venues does Drake play on tour?',
          'a' => 'Drake primarily plays large indoor arenas, such as Co-op Live in Manchester and the Ziggo Dome in Amsterdam, rather than open-air stadiums. That arena scale influences both ticket pricing and the design of the stage production.',
        ),
        3 => 
        array (
          'q' => 'How long is a Drake concert?',
          'a' => 'A Drake show typically runs around 90 minutes to two hours, moving through a long sequence of singles and deep cuts. Exact running times vary by night and city, so the published start time is the most reliable guide.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Drake\'s setlist on the Some Special Shows 4 U tour draws from more than a decade of releases, opening with earlier material before moving through his run of chart singles and recent collaborations. The Drake setlist leans on the songs audiences expect, including "God\'s Plan", "One Dance" and "Hotline Bling", while leaving room for deeper cuts that rotate between shows.',
      ),
      'songs' => 
      array (
        0 => 'Over My Dead Body',
        1 => 'Passionfruit',
        2 => 'Marvins Room',
        3 => 'Teenage Fever',
        4 => 'Jungle',
        5 => 'What Did I Miss?',
        6 => 'Headlines',
        7 => 'Know Yourself',
        8 => 'Nonstop',
        9 => 'Energy',
        10 => 'God\'s Plan',
        11 => 'Started From the Bottom',
        12 => 'Laugh Now Cry Later',
        13 => 'Controlla',
        14 => 'In My Feelings',
        15 => 'Nice for What',
        16 => 'Hold On, We\'re Going Home',
        17 => 'One Dance',
        18 => 'Hotline Bling',
        19 => 'NOKIA',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order and song choices shift between cities, so any given show may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Drake play live?',
          'a' => 'Drake\'s live shows centre on hits such as "God\'s Plan", "One Dance", "Hotline Bling", "Started From the Bottom" and "Nice for What", alongside earlier tracks like "Marvins Room" and recent cuts including "What Did I Miss?" and "NOKIA". The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'What song does Drake open with?',
          'a' => 'On his recent tour Drake has frequently opened with earlier catalogue material such as "Over My Dead Body" or "Passionfruit" before building into his run of singles. Openers can change between shows, so the first song is not fixed across the tour.',
        ),
        2 => 
        array (
          'q' => 'Does Drake do an encore?',
          'a' => 'Drake\'s recent shows have generally run as a single continuous set rather than closing with a separate encore. He tends to play through a long sequence of songs without a formal encore break, though this can vary by night.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Drake play in concert?',
          'a' => 'Drake performs a long set, often working through dozens of songs and snippets across roughly 90 minutes to two hours. Because so much of the catalogue is in rotation, the precise number and choice of songs differ from one show to the next.',
        ),
      ),
    ),
  ),
  'olivia-rodrigo' => 
  array (
    'name' => 'Olivia Rodrigo',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 800,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Olivia Rodrigo ticket prices sit across a fairly wide band because her shows mix budget-conscious seating with premium floor and VIP options. In the United States, the cheapest upper-level and limited-allocation seats tend to land near the lower end of the scale, while general admission floor standing and early-entry pit experiences reach the upper end. Where you sit, the size of the arena and overall demand for a particular night all shape what you ultimately pay.',
        1 => 'Because Olivia Rodrigo plays arenas rather than stadiums, her pricing generally lands in the mid tier for a major pop act, below stadium headliners but above most theatre and club bookings. Service fees are usually added at checkout on top of the face value shown, so the final total runs higher than the headline figure. Buyers comparing options should weigh seated comfort against the standing floor before deciding which tier suits them.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Limited-allocation and upper level',
          'desc' => 'The most affordable way in. Olivia Rodrigo\'s tours have set aside a small number of heavily discounted seats, and these alongside standard upper-bowl positions typically run from around 60 to 130 USD before fees. Views are distant but the full production remains visible.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier seated',
          'desc' => 'Lower-bowl and mid-level seated tickets generally fall between roughly 130 and 280 USD before fees. These balance a clearer sightline of the stage and screens against a more moderate outlay, and are the most commonly chosen band for seated attendees.',
        ),
        2 => 
        array (
          'name' => 'Floor general admission',
          'desc' => 'Standing floor tickets put you closest to the main stage and tend to price from about 250 to 400 USD before fees. There are usually no reserved seats on the floor, so arrival time affects how near the front you end up.',
        ),
        3 => 
        array (
          'name' => 'VIP and early-entry pit',
          'desc' => 'Premium packages, including early-entry pit access and bundled extras, sit at the top of the range and have reached roughly 540 to 800 USD inclusive of fees. These prioritise proximity and added perks rather than the lowest cost.',
        ),
      ),
      'why' => 'Ticket prices vary by city, venue size, seat location and how busy a given date is, which is why two shows on the same tour can differ noticeably. One point worth noting for Olivia Rodrigo specifically: her recent touring has stepped away from algorithmic dynamic pricing, so face values are set in advance rather than rising in real time with demand. Even so, the final amount you pay still depends on the tier you choose and the service and handling fees applied at checkout, and primary allocations can sell through quickly, pushing remaining inventory to resale at higher prices.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Olivia Rodrigo tickets?',
          'a' => 'Olivia Rodrigo tickets generally range from around 60 USD for limited-allocation and upper-level seats to roughly 800 USD for VIP and early-entry pit packages including fees. Mid-tier seated tickets commonly fall between about 130 and 280 USD before fees.',
        ),
        1 => 
        array (
          'q' => 'Why are Olivia Rodrigo tickets so expensive?',
          'a' => 'The higher prices come from premium floor standing and VIP pit packages, which carry added perks and proximity to the stage. Demand for an arena run from a major pop act also tightens primary availability, and service fees added at checkout raise the final total above the listed face value.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Olivia Rodrigo live?',
          'a' => 'The cheapest route is usually an upper-level seat or one of the small number of heavily discounted tickets she has set aside on recent tours, both of which sit near the bottom of the range. Buying from the primary seller during the general onsale also avoids resale mark-ups.',
        ),
        3 => 
        array (
          'q' => 'Does Olivia Rodrigo use dynamic pricing?',
          'a' => 'Olivia Rodrigo\'s recent touring has moved away from algorithmic dynamic pricing, meaning face values are set ahead of the onsale rather than climbing in real time with demand. Service fees still apply at checkout, and resale listings can sit well above the original price.',
        ),
        4 => 
        array (
          'q' => 'How much do Olivia Rodrigo floor tickets cost?',
          'a' => 'General admission floor standing tickets typically price from about 250 to 400 USD before fees. The floor is usually unreserved, so how close you get to the stage depends on when you arrive rather than the exact ticket you bought.',
        ),
        5 => 
        array (
          'q' => 'Are there VIP packages for Olivia Rodrigo concerts?',
          'a' => 'Yes. VIP options have included early-entry pit access and bundled extras, sitting at the top of the price band at roughly 540 to 800 USD inclusive of fees. The exact contents and cost vary by tour and venue.',
        ),
        6 => 
        array (
          'q' => 'Do Olivia Rodrigo ticket prices include fees?',
          'a' => 'Listed face values usually exclude service and handling fees, which are added during checkout, so the final total runs higher than the headline figure. Some premium and VIP prices are quoted inclusive of fees, so it is worth checking before you confirm.',
        ),
        7 => 
        array (
          'q' => 'How much are Olivia Rodrigo tickets in the UK?',
          'a' => 'UK pricing is set separately in pounds and, excluding VIP packages, has spanned roughly 79 to 414 GBP depending on seat or standing position. Currency conversion and regional fees mean UK totals do not map directly onto US dollar figures.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Unraveled Tour',
      'intro' => 
      array (
        0 => 'Olivia Rodrigo\'s current touring centres on The Unraveled Tour, an arena run supporting her studio work that travels across North America, the United Kingdom and Europe. Tour dates are released in stages by city, with on-sale information handled by the official primary sellers for each market. Because routing and venues differ between legs, the schedule for any given country is best confirmed against the listings shown for that region.',
        1 => 'As an arena act, Olivia Rodrigo typically books multiple nights in larger markets and single dates elsewhere, with supporting acts varying by leg. Tour dates can be added or adjusted after the initial announcement as demand becomes clear, so the live listings reflect the most current routing. Anyone tracking a specific city should check the dedicated event page for that venue rather than relying on a fixed calendar.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Olivia Rodrigo touring in 2026?',
          'a' => 'Yes. Olivia Rodrigo is on The Unraveled Tour, an arena run that extends across North America, the UK and Europe into 2027. Specific dates are released by city and confirmed through the official primary sellers, so check the live listings for your market.',
        ),
        1 => 
        array (
          'q' => 'What is Olivia Rodrigo\'s current tour called?',
          'a' => 'Her current tour is The Unraveled Tour, which follows the earlier GUTS World Tour. It is an arena tour supporting her newer studio material, with legs scheduled across multiple countries.',
        ),
        2 => 
        array (
          'q' => 'Which cities is Olivia Rodrigo playing on tour?',
          'a' => 'The Unraveled Tour visits major arena markets across North America, the United Kingdom and Europe, with multiple nights in larger cities. Because routing is announced in stages, the most reliable list is the set of live event pages for each region.',
        ),
        3 => 
        array (
          'q' => 'How long is an Olivia Rodrigo concert?',
          'a' => 'An Olivia Rodrigo headline set generally runs in the region of an hour and three quarters to two hours, supporting acts aside. Exact running times shift between nights depending on the setlist and staging for that show.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Olivia Rodrigo\'s live setlist draws across her catalogue, pairing the confessional ballads that built her early following with the sharper rock-leaning material from her more recent work. Recent shows have opened on a high-energy note and moved through quieter, piano-led moments before building back up, giving the set a clear emotional arc. The running order below reflects songs that have featured as staples on her recent touring rather than a fixed list for any single night.',
      ),
      'songs' => 
      array (
        0 => 'bad idea right?',
        1 => 'ballad of a homeschooled girl',
        2 => 'vampire',
        3 => 'traitor',
        4 => 'drivers license',
        5 => 'pretty isn\'t pretty',
        6 => 'love is embarrassing',
        7 => 'making the bed',
        8 => 'logical',
        9 => 'enough for you',
        10 => 'lacy',
        11 => 'jealousy, jealousy',
        12 => 'happier',
        13 => 'favorite crime',
        14 => 'deja vu',
        15 => 'the grudge',
        16 => 'brutal',
        17 => 'all-american bitch',
      ),
      'encore' => 
      array (
        0 => 'good 4 u',
        1 => 'get him back!',
      ),
      'note' => 'Setlists vary from night to night, so the running order, song choices and any covers or one-off additions can change between shows on the same tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Olivia Rodrigo play live?',
          'a' => 'Olivia Rodrigo\'s live sets feature staples such as vampire, drivers license, brutal, good 4 u, deja vu and all-american bitch, drawing across both her albums. The exact selection and order vary from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Olivia Rodrigo do an encore?',
          'a' => 'Yes. Her recent shows have closed with an encore that typically includes good 4 u and get him back!, two of her most energetic numbers, sending the crowd out on a high. Encore choices can still differ between dates.',
        ),
        2 => 
        array (
          'q' => 'Does Olivia Rodrigo play drivers license live?',
          'a' => 'Yes. drivers license, her breakout single, has remained a fixture of her live sets and usually appears among the slower, piano-led ballads in the middle of the show, though its exact placement can shift between nights.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Olivia Rodrigo play in concert?',
          'a' => 'A typical Olivia Rodrigo set runs to roughly 18 to 22 songs including the encore, spanning material from across her catalogue. The precise count varies by night depending on the staging and running time for that show.',
        ),
      ),
    ),
  ),
  'dua-lipa' => 
  array (
    'name' => 'Dua Lipa',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Dua Lipa ticket prices sit across a broad band because her shows span both large arenas and full stadiums, and the same tour can move between the two within a single run. As a general guide, entry-level seats tend to start around 45 to 70 USD, mid-tier sections settle in the 90 to 180 USD range, and premium floor or front-block seats reach 250 USD and above before fees and any official VIP packages are added.',
        1 => 'Where a Dua Lipa date lands inside that range depends mainly on the venue, the city, and how early you book. Stadium nights and high-demand markets push the upper end, while midweek arena dates and upper-tier seats keep the lower figures realistic. The bands below describe how seating usually tiers on her current run rather than any single confirmed price, since live figures are listed separately.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / back of stadium',
          'desc' => 'The most affordable seated areas, usually toward the rear or upper levels with a side or distant view of the stage. On most Dua Lipa dates these run roughly 45 to 80 USD before fees and are the first sections regular buyers reach once a sale opens.',
        ),
        1 => 
        array (
          'name' => 'Lower tier / side seating',
          'desc' => 'Seated blocks closer to the floor or along the sides of the room, offering a clearer sightline without front-row pricing. Expect somewhere around 90 to 160 USD on a typical show, varying by city and how full the date is.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing on the floor or seated in the lower central blocks, generally the most sought-after standard tickets. These commonly fall between 150 and 250 USD, and stadium GA pits in busy markets can sit at the upper edge of that span.',
        ),
        3 => 
        array (
          'name' => 'Front block / premium',
          'desc' => 'Seats nearest the stage or in designated premium areas, the highest standard pricing before packages. On stadium nights and high-demand cities these can reach 250 to 400 USD, occasionally higher when resale takes over a sold-out date.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and hospitality packages bundle a premium seat with extras such as early entry, a lounge, or merchandise. Pricing is set per package rather than per seat and frequently lands above 400 USD, so it sits outside the standard ticket bands above.',
        ),
      ),
      'why' => 'Dua Lipa\'s tours are promoted by Live Nation and sold through Ticketmaster, which means most dates use dynamic pricing. Under that system the listed face value moves with real-time demand rather than staying fixed, so popular sections can climb during a busy sale and softer dates may ease back. This is why two seats in the same block can carry different prices, and why the figures here are bands rather than a single number. Booking fees, facility charges, and any VIP package costs are added on top, and the live prices shown alongside this guide reflect what is actually available at the time you look.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Dua Lipa tickets?',
          'a' => 'Dua Lipa tickets generally range from about 45 USD for upper-tier seats to 400 USD or more for premium front-block positions, with mid-tier seating around 90 to 180 USD. The exact figure depends on the venue, city, and demand on the day, since her current run uses dynamic pricing through Ticketmaster.',
        ),
        1 => 
        array (
          'q' => 'Why do Dua Lipa ticket prices change?',
          'a' => 'Most Dua Lipa dates use dynamic pricing, so the face value rises and falls with live demand rather than staying fixed. A high-demand stadium night in a major city will list higher than a midweek arena date, and prices within the same section can differ depending on when you buy.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Dua Lipa live?',
          'a' => 'The cheapest standard option is usually an upper-tier or rear-stadium seat, which tends to start around 45 to 70 USD before fees. Buying early in a sale and choosing a midweek date in a less busy market generally gives the best chance of the lower figures.',
        ),
        3 => 
        array (
          'q' => 'Are there VIP packages for Dua Lipa concerts?',
          'a' => 'Yes, official VIP and hospitality packages are offered on most Dua Lipa dates and combine a premium seat with extras such as early entry, a lounge, or merchandise. They are priced per package, often above 400 USD, and are listed separately from standard tickets.',
        ),
        4 => 
        array (
          'q' => 'Do Dua Lipa stadium shows cost more than arena shows?',
          'a' => 'Stadium dates often carry higher headline prices for the best positions, particularly floor and premium blocks, while their upper tiers can still be reasonable. Arena shows tend to have a tighter overall band. Demand in the specific city matters as much as the venue type.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Dua Lipa?',
          'a' => 'Floor or general-admission tickets for Dua Lipa typically fall between 150 and 250 USD before fees, and stadium GA pits in busy markets can sit at the top of that range. These are among the most sought-after standard tickets and tend to sell first.',
        ),
        6 => 
        array (
          'q' => 'Do Dua Lipa ticket prices include booking fees?',
          'a' => 'No, the headline price usually excludes service and facility fees, which are added at checkout. VIP package costs are also separate. The live prices shown with this guide reflect what is available at the time you look, so the final total may be higher than the base figure.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Dua Lipa tickets early or later?',
          'a' => 'Under dynamic pricing there is no fixed rule, but booking early in a sale usually secures the lower-tier figures before high-demand sections rise. Waiting can occasionally bring resale dips on softer dates, though popular shows more often climb rather than fall as the date nears.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Radical Optimism Tour',
      'intro' => 
      array (
        0 => 'Dua Lipa\'s current run is the Radical Optimism Tour, supporting her third studio album of the same name. The tour has moved through Asia, Australia, Europe, the UK, and North America across a long stretch of arena and stadium dates, and has extended into further 2026 shows in selected cities. When checking Dua Lipa tour dates, it is worth confirming the venue for each show, as the staging and capacity shift between rooms.',
        1 => 'Because the routing spans multiple continents and venue sizes, Dua Lipa tour dates are best treated city by city rather than as one fixed schedule. Some markets host a single arena night while others step up to a stadium, and additional dates have been added where demand was strongest. The live listings shown alongside this guide carry the confirmed cities and on-sale details rather than this evergreen overview.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Dua Lipa touring in 2026?',
          'a' => 'Yes, Dua Lipa has extended the Radical Optimism Tour into 2026 with additional shows in selected cities, including stadium dates in markets that sold out quickly during the earlier legs. Confirmed cities and venues appear in the live listings, as the routing has continued to grow beyond the original 2025 schedule.',
        ),
        1 => 
        array (
          'q' => 'What is Dua Lipa\'s current tour called?',
          'a' => 'Dua Lipa\'s current tour is the Radical Optimism Tour, named after her third studio album. It began in late 2024 and has run through Asia, Australia, Europe, the UK, and North America, mixing arena and stadium dates depending on the city.',
        ),
        2 => 
        array (
          'q' => 'What venues does Dua Lipa play on this tour?',
          'a' => 'The Radical Optimism Tour plays a mix of large arenas and full stadiums, with bigger markets stepping up to stadium capacity. Earlier legs included major arenas worldwide alongside stadium nights in cities such as London and Liverpool, with further stadium dates added for 2026.',
        ),
        3 => 
        array (
          'q' => 'How long is a Dua Lipa concert on this tour?',
          'a' => 'A Dua Lipa show on the Radical Optimism Tour generally runs around an hour and three quarters to two hours, covering roughly twenty songs plus an encore. Support acts and any local guest covers can extend the overall evening, so timings vary slightly by city.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Dua Lipa\'s Radical Optimism Tour setlist blends the new album with the singles that built her name, typically running to around twenty songs before the encore. Recent shows have opened with newer material such as Training Season and End of an Era, then worked through career staples including Levitating, Physical, and Hallucinate. The order below reflects how her current setlist has generally been arranged rather than any one fixed night.',
      ),
      'songs' => 
      array (
        0 => 'Training Season',
        1 => 'End of an Era',
        2 => 'Break My Heart',
        3 => 'One Kiss',
        4 => 'Whatcha Doing',
        5 => 'Levitating',
        6 => 'These Walls',
        7 => 'Maria',
        8 => 'Physical',
        9 => 'Electricity',
        10 => 'Hallucinate',
        11 => 'Illusion',
        12 => 'Falling Forever',
        13 => 'Happy for You',
        14 => 'Love Again',
        15 => 'Anything for Love',
        16 => 'Be the One',
      ),
      'encore' => 
      array (
        0 => 'New Rules',
        1 => 'Dance the Night',
        2 => 'Don\'t Start Now',
        3 => 'Houdini',
      ),
      'note' => 'Setlists vary from night to night. Dua Lipa has regularly added a one-off cover of a local artist\'s song in each city on this run, and the song order and inclusions change between shows, so the list above is a representative guide rather than a guaranteed running order for any specific date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Dua Lipa play live?',
          'a' => 'Dua Lipa\'s live set leans on hits such as Levitating, Don\'t Start Now, Physical, New Rules, and Houdini, mixed with Radical Optimism tracks like Training Season, Illusion, and End of an Era. A typical night runs about twenty songs plus an encore, though the exact selection shifts between shows.',
        ),
        1 => 
        array (
          'q' => 'Does Dua Lipa do an encore?',
          'a' => 'Yes, Dua Lipa\'s Radical Optimism Tour shows have closed with an encore, recently featuring New Rules, Dance the Night, and Don\'t Start Now before finishing on Houdini. As with the main set, the encore can change from night to night, so the closing songs are not guaranteed for every date.',
        ),
        2 => 
        array (
          'q' => 'Does Dua Lipa play her old songs live?',
          'a' => 'Yes, her current setlist keeps the early singles in rotation, with Be the One, New Rules, One Kiss, and Don\'t Start Now sitting alongside newer Radical Optimism material. The show is built to balance the latest album with the tracks that established her, rather than focusing only on recent releases.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Dua Lipa perform in concert?',
          'a' => 'Dua Lipa typically performs around twenty songs on the Radical Optimism Tour, including the encore. The number varies slightly by night, and she has often added a local cover in each city, which can push the total a little higher on some dates.',
        ),
      ),
    ),
  ),
  'bruno-mars' => 
  array (
    'name' => 'Bruno Mars',
    'genre' => 'Pop / R&B',
    'prices' => 
    array (
      'range_low' => 95,
      'range_high' => 950,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Bruno Mars ticket prices vary widely because his shows are now staged in large stadiums, where capacity and seat type create a broad spread between the cheapest upper-tier places and front-of-stage positions. As a general guide, value seats tend to open in the lower three figures, while premium floor and package options sit much higher. The figures below describe how the bands typically work rather than the cost of any single date.',
        1 => 'Because Bruno Mars sells at stadium scale, the gap between the entry price and the best house seats is large, and demand for the strongest dates pushes the upper bands well above the floor. Knowing roughly where each tier sits makes it easier to read a live listing and judge whether a given price is reasonable for the section on offer.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most affordable way in, usually around 95 to 180 USD. These are higher or further-back stadium seats with a full view of the stage and screens, suited to attendees who want the show and atmosphere at the lowest outlay.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side seating',
          'desc' => 'Mid-range seats, broadly 180 to 380 USD, set in the lower tiers or along the sides nearer the stage. A common choice balancing a closer view against cost, with sightlines that stay strong for the full set.',
        ),
        2 => 
        array (
          'name' => 'Floor and prime sections',
          'desc' => 'Higher-demand standing or seated places close to the stage, typically 380 to 650 USD. Pricing here moves most with demand, as these positions sell quickly for the busier dates.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Premium packages from roughly 650 to 950 USD and occasionally beyond, bundling prime positions with extras such as early entry, dedicated entrances or merchandise. Inclusions are set by the promoter and differ by venue.',
        ),
      ),
      'why' => 'Most Bruno Mars dates run through Ticketmaster and Live Nation, which apply dynamic pricing on many shows. This means face values can move up or down with demand rather than staying fixed, so an identical seat may cost more for a high-demand night than a quieter one. Posted prices also exclude service and facility fees added at checkout, and any VIP or package elements carry their own pricing. The bands above reflect typical primary-market levels; resale listings can sit higher still for sought-after sections.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Bruno Mars tickets?',
          'a' => 'Bruno Mars tickets generally start around 95 to 180 USD for upper-level stadium seats and rise to roughly 380 to 650 USD for floor and prime sections, with VIP packages reaching about 950 USD or more. Exact figures depend on the venue, the date and where demand sits at the time of booking.',
        ),
        1 => 
        array (
          'q' => 'Why do Bruno Mars ticket prices change so much?',
          'a' => 'Prices shift because most of his dates use dynamic pricing, which adjusts face value in line with demand. Stadium shows also span many seat types, from distant upper tiers to front-of-stage floor positions, so the spread between the cheapest and most expensive places is naturally wide.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Bruno Mars?',
          'a' => 'The lowest-cost option is usually an upper-level or rear stadium seat, often around 95 to 180 USD before fees. These still give a clear view of the stage and screens, and they are typically the first standard tier to appear on a listing.',
        ),
        3 => 
        array (
          'q' => 'Are Bruno Mars VIP packages worth it?',
          'a' => 'VIP packages suit those who want prime positions plus extras such as early entry or merchandise, and they generally run from about 650 to 950 USD. Whether they represent value depends on the specific inclusions, which are set by the promoter and vary from venue to venue.',
        ),
        4 => 
        array (
          'q' => 'Do Bruno Mars ticket prices include fees?',
          'a' => 'Advertised face values usually exclude service and facility charges, which are added during checkout. The final amount can therefore be noticeably higher than the headline price, so it is worth reviewing the full breakdown before completing a booking.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Bruno Mars?',
          'a' => 'Floor and prime sections close to the stage typically fall between 380 and 650 USD. Because these positions are in high demand and subject to dynamic pricing, they tend to be among the first to sell and the most variable in cost across different dates.',
        ),
        6 => 
        array (
          'q' => 'Are Bruno Mars tickets more expensive on resale sites?',
          'a' => 'Resale listings can sit above primary prices for popular dates and sought-after sections, though they sometimes settle closer to face value nearer the show. The primary market through Ticketmaster and Live Nation is generally the starting reference point for what a seat should cost.',
        ),
        7 => 
        array (
          'q' => 'Why are stadium seats for Bruno Mars priced so differently?',
          'a' => 'Stadiums hold a large range of seat types, from distant upper tiers to front-row floor places, and each carries a different value. That range, combined with demand-led dynamic pricing on many dates, produces the wide gap between the lowest and highest Bruno Mars ticket prices.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Romantic Tour',
      'intro' => 
      array (
        0 => 'Bruno Mars returned to large-scale touring with The Romantic Tour, his first full headline run in close to a decade. The trek moves through stadiums across North America and Europe, drawing on his catalogue alongside Silk Sonic material and newer releases. Tour dates are released in stages by venue and region, so the live schedule shifts as shows are added; current confirmed dates are listed separately on this page.',
        1 => 'The Romantic Tour pairs Bruno Mars with a full live band and a production built for stadium settings. Because routing is announced city by city, the run of tour dates expands over time rather than appearing all at once, and individual shows can sell quickly once they open.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Bruno Mars touring in 2026?',
          'a' => 'Yes, Bruno Mars is touring on The Romantic Tour, which takes in stadiums across North America and Europe. It marks his first full headline tour in nearly ten years. Confirmed dates are listed separately on this page, as the schedule is released city by city.',
        ),
        1 => 
        array (
          'q' => 'What is Bruno Mars\'s current tour called?',
          'a' => 'His current run is The Romantic Tour, a stadium-scale headline tour. It is his first extended solo touring cycle in close to a decade and features his own catalogue alongside Silk Sonic songs and more recent material.',
        ),
        2 => 
        array (
          'q' => 'What cities is Bruno Mars playing on The Romantic Tour?',
          'a' => 'The Romantic Tour visits major stadium markets across North America and Europe. Because dates are confirmed in stages by region, the full list of cities grows over time; the live dates shown separately on this page reflect what is currently confirmed.',
        ),
        3 => 
        array (
          'q' => 'How long is a Bruno Mars concert on The Romantic Tour?',
          'a' => 'Recent shows on The Romantic Tour have run roughly two hours, typically around 25 songs plus an encore. Exact running times vary by venue and date, and the set can shift slightly from night to night.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Bruno Mars setlist on The Romantic Tour pulls from across his career, opening with recent and Silk Sonic material before moving into the established hits that close the night. Recent shows have featured singles such as 24K Magic, That\'s What I Like, Locked Out of Heaven and Uptown Funk, alongside ballads and Silk Sonic numbers. The order below reflects songs that have appeared as regular fixtures.',
      ),
      'songs' => 
      array (
        0 => 'Risk It All',
        1 => 'Cha Cha Cha',
        2 => '24K Magic',
        3 => 'Treasure',
        4 => 'That\'s What I Like',
        5 => 'Smokin Out the Window',
        6 => 'Leave the Door Open',
        7 => 'Marry You',
        8 => 'Die With a Smile',
        9 => 'Versace on the Floor',
        10 => 'Locked Out of Heaven',
        11 => 'Just the Way You Are',
        12 => 'Uptown Funk',
        13 => 'When I Was Your Man',
        14 => 'Talking to the Moon',
        15 => 'It Will Rain',
      ),
      'encore' => 
      array (
        0 => 'Dance With Me',
      ),
      'note' => 'Setlists vary from night to night, and the running order, medleys and song choices can change between dates. The list above reflects recent shows and should be taken as a guide rather than a fixed plan for any single concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Bruno Mars play live?',
          'a' => 'On recent dates Bruno Mars has played 24K Magic, Treasure, That\'s What I Like, Locked Out of Heaven, Just the Way You Are and Uptown Funk, plus Silk Sonic songs such as Leave the Door Open and Smokin Out the Window. The exact set varies from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Bruno Mars play Uptown Funk live?',
          'a' => 'Yes, Uptown Funk is a regular fixture and has typically closed the main set on recent shows. Like the rest of the running order it can move or change between dates, but it remains one of the most consistent inclusions.',
        ),
        2 => 
        array (
          'q' => 'Does Bruno Mars do an encore?',
          'a' => 'Recent Romantic Tour shows have closed with a short encore, often Dance With Me, after a main set of around 25 songs. Encore choices and whether one is performed can vary by venue and date.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Bruno Mars play in concert?',
          'a' => 'Recent concerts have featured roughly 25 songs plus an encore, across about two hours. The count and selection shift between dates, with some songs appearing as medleys and others rotating in and out of the set.',
        ),
      ),
    ),
  ),
  'kendrick-lamar' => 
  array (
    'name' => 'Kendrick Lamar',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 1100,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Kendrick Lamar ticket prices sit at the higher end of live hip-hop, reflecting the stadium scale at which he now performs. Across his recent run, get-in seats have tended to open in the region of 70 to 130 USD, with floor and lower-bowl positions climbing into the mid hundreds. Where he co-headlines, demand often firms prices further, so the figures below describe how the market generally behaves rather than any single show.',
        1 => 'Because Kendrick Lamar plays arenas and stadiums depending on the market, the spread between the cheapest upper-tier seat and a front-of-stage package is wide. Premium and hospitality tickets can pass 1,000 USD, while patient buyers watching the upper tiers usually find the more modest end of the band. Prices listed here are indicative ranges; live availability and exact figures are shown separately at the point of sale.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear sections',
          'desc' => 'The most economical way in, typically around 70 to 140 USD. These seats sit high in the bowl or toward the back of a stadium, with a full view of the stage and production but at distance from the performance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side floor',
          'desc' => 'Mid-range seating, broadly 140 to 320 USD. Expect a closer, clearer sightline from the sides or the lower level, a common choice for those wanting proximity without committing to front-of-stage pricing.',
        ),
        2 => 
        array (
          'name' => 'Front floor and prime seats',
          'desc' => 'Sought-after positions near the stage, generally 320 to 600 USD. Pricing here moves most under dynamic models, particularly for marquee city dates and any co-headline appearances.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Premium packages that can run from roughly 600 to 1,100 USD or more. These usually bundle a prime seat with early entry, hospitality areas or exclusive merchandise, and sit at the top of the range.',
        ),
      ),
      'why' => 'Kendrick Lamar tickets are sold largely through dynamic pricing, used across most major Ticketmaster and Live Nation tours. Under this model the face value of a seat shifts with real-time demand rather than staying fixed, so a popular section can cost more as a date sells through and may ease if demand softens. High-profile markets and co-headline dates tend to see the sharpest movement. Added service and facility fees also affect the final total, which is why the same seat can carry different prices across cities and over time.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Kendrick Lamar tickets?',
          'a' => 'Kendrick Lamar tickets generally range from about 70 USD for upper-tier seats to 1,100 USD or more for VIP and hospitality packages. Lower-bowl and floor positions typically fall between 140 and 600 USD. As a stadium-scale act, his pricing runs higher than most touring rappers, and exact figures vary by city and seat.',
        ),
        1 => 
        array (
          'q' => 'Why do Kendrick Lamar ticket prices change so much?',
          'a' => 'Most of his dates use dynamic pricing, so seat costs rise and fall with live demand rather than holding a fixed value. A high-demand section in a major market can climb as a show sells through, while quieter dates may settle lower. Service and facility fees add to the headline price at checkout.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Kendrick Lamar live?',
          'a' => 'The cheapest seats are usually in the upper tier or rear stadium sections, often opening around 70 to 140 USD. These offer a full view of the stage and production at a distance. Watching the upper tiers as a date approaches can occasionally surface lower-priced availability.',
        ),
        3 => 
        array (
          'q' => 'How much do Kendrick Lamar VIP packages cost?',
          'a' => 'VIP and hospitality packages typically run from about 600 USD into four figures, often exceeding 1,000 USD for the most comprehensive options. They generally combine a prime seat with extras such as early entry, hospitality access or exclusive merchandise, and represent the upper end of his pricing.',
        ),
        4 => 
        array (
          'q' => 'Are Kendrick Lamar tickets more expensive than other rappers?',
          'a' => 'Yes, his tickets sit toward the top of the hip-hop market because he performs at stadium and large-arena scale, where production and capacity push prices up. Average resale figures on recent tours were among the highest of any act, particularly where he shared the bill with another headliner.',
        ),
        5 => 
        array (
          'q' => 'Do Kendrick Lamar ticket prices include fees?',
          'a' => 'The advertised price often excludes service and facility fees, which are added during checkout and can increase the total noticeably. Some sellers display an all-in price upfront. It is worth reviewing the final figure before purchase, as fees vary by venue and ticket type.',
        ),
        6 => 
        array (
          'q' => 'How much are floor tickets for Kendrick Lamar?',
          'a' => 'Floor tickets generally fall between 140 USD for side-floor positions and 600 USD for front-of-stage seats. Demand on the floor is high, so dynamic pricing tends to lift these the most, especially in larger cities and on any co-headline dates.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Kendrick Lamar tickets early or late?',
          'a' => 'There is no fixed rule. Early buying secures choice of seat before popular sections sell out, while prices can occasionally ease close to a date if demand cools. Because dynamic pricing governs most seats, the most reliable approach is to compare availability across tiers rather than time the market.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Grand National Tour',
      'intro' => 
      array (
        0 => 'Kendrick Lamar\'s most recent major outing was the Grand National Tour, a stadium run supporting his album GNX and staged in part as a co-headline with SZA. It moved through North America, Europe, Australia and South America, marking his largest production to date. Tour dates for any future run are published by the artist and primary sellers; the schedule and on-sale details for upcoming shows are shown separately by the system.',
        1 => 'The Grand National Tour paired a deep catalogue set with the GNX material and a stage production built around its four-act structure. When Kendrick Lamar confirms new tour dates, they typically go on sale through Ticketmaster and Live Nation with presales ahead of general release. The information here describes how his touring generally works rather than any specific announced date.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Kendrick Lamar touring in 2026?',
          'a' => 'Kendrick Lamar\'s most recent major tour was the Grand National Tour, which ran through 2025 in support of GNX. Any 2026 dates would be confirmed by the artist and primary ticket sellers; current scheduling and on-sale details are shown separately on this page when available.',
        ),
        1 => 
        array (
          'q' => 'What is Kendrick Lamar\'s current tour called?',
          'a' => 'His most recent named tour was the Grand National Tour, a stadium run supporting the album GNX, performed in part as a co-headline with SZA. It travelled across North America, Europe, Australia and South America before concluding, and stands as his largest production so far.',
        ),
        2 => 
        array (
          'q' => 'Where can I get Kendrick Lamar tour dates and tickets?',
          'a' => 'Official tour dates and tickets are released through Ticketmaster and Live Nation, usually with artist and cardholder presales before the general on-sale. Live availability for any confirmed Kendrick Lamar shows is displayed separately by the system rather than within this evergreen overview.',
        ),
        3 => 
        array (
          'q' => 'Did Kendrick Lamar tour with SZA?',
          'a' => 'Yes. The Grand National Tour was staged largely as a co-headline with SZA, with the two trading solo segments and joining for collaborations including All the Stars. The pairing was a defining feature of the run and contributed to the strong demand seen across its dates.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Kendrick Lamar setlist on recent tours leans heavily on GNX alongside long-standing catalogue staples, structured into distinct acts rather than a straight run of singles. Expect To Pimp a Butterfly and DAMN. material woven through the GNX tracks, with the set built to move between his harder cuts and more reflective moments. The running order is arranged for the production and shifts between shows.',
      ),
      'songs' => 
      array (
        0 => 'wacced out murals',
        1 => 'squabble up',
        2 => 'N95',
        3 => 'King Kunta',
        4 => 'ELEMENT.',
        5 => 'tv off',
        6 => 'euphoria',
        7 => 'reincarnated',
        8 => 'HUMBLE.',
        9 => 'Backseat Freestyle',
        10 => 'Swimming Pools (Drank)',
        11 => 'm.A.A.d city',
        12 => 'Alright',
        13 => 'man at the garden',
        14 => 'peekaboo',
        15 => 'DNA.',
        16 => 'Money Trees',
        17 => 'Poetic Justice',
        18 => 'Not Like Us',
        19 => 'gloria',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Kendrick Lamar regularly reworks the running order, swaps individual songs and adjusts arrangements across a tour, so any given show may differ from the list above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Kendrick Lamar play live?',
          'a' => 'Recent Kendrick Lamar setlists mix GNX tracks such as squabble up, tv off and reincarnated with established staples including HUMBLE., DNA., Alright, King Kunta and m.A.A.d city. Not Like Us has been a consistent late-set highlight. The exact selection changes between shows.',
        ),
        1 => 
        array (
          'q' => 'Does Kendrick Lamar play Not Like Us live?',
          'a' => 'Yes, Not Like Us has been a regular and prominent part of Kendrick Lamar\'s recent sets, typically placed near the end of the show as a high point. As with any song, its inclusion can vary, but it featured consistently across his most recent tour.',
        ),
        2 => 
        array (
          'q' => 'Does Kendrick Lamar do an encore?',
          'a' => 'His recent shows have generally run as a continuous, act-based performance rather than closing with a separate encore segment. The set is structured to build through to a final stretch, so there is usually no distinct encore in the traditional sense. Show formats can change from night to night.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Kendrick Lamar play in concert?',
          'a' => 'Recent Kendrick Lamar shows have featured close to 30 songs, including around ten from GNX, though many are performed in shortened or reworked forms within the act structure. The total and the specific tracks vary between dates, as he regularly adjusts the setlist across a tour.',
        ),
      ),
    ),
  ),
  'sabrina-carpenter' => 
  array (
    'name' => 'Sabrina Carpenter',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Sabrina Carpenter ticket prices sit in the mid band typical of a current arena headliner rather than a stadium act. Standard seated and general-admission tickets released by Ticketmaster have generally opened around the lower end, with premium lower-bowl seats and packages reaching higher. Because demand for her shows is consistently strong, the gap between the cheapest upper-tier seat and a front-section ticket can be wide on any given night.',
        1 => 'When weighing Sabrina Carpenter ticket prices it helps to separate face value from resale. Primary prices are set per market and per seating zone, while resale listings float with demand and often run well above the original price for popular dates. The bands below reflect realistic primary pricing for a pop arena tour and are intended as a general guide rather than a quote for any single show.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Seats in the upper tiers and rear sections are the entry point, generally in the USD 60 to 110 range. They give a full view of the stage and production from a distance and are the first to sell at most arenas.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and floor',
          'desc' => 'Lower-level seating and standard floor positions usually land between USD 110 and 200. These offer a closer sightline to the main stage and any runway, and are the tickets most often resold above face value.',
        ),
        2 => 
        array (
          'name' => 'Premium and front sections',
          'desc' => 'Prime lower-bowl and front-of-floor tickets typically run USD 180 to 280. These are positioned for the clearest view of the performance and tend to clear quickly during the initial on-sale.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and enhanced packages generally fall in the USD 250 to 400 range. They bundle a premium seat with extras such as early entry, a merchandise item or a dedicated check-in, and are sold in limited numbers.',
        ),
      ),
      'why' => 'Sabrina Carpenter\'s tours are promoted through Ticketmaster and Live Nation, which apply dynamic pricing on many dates. Under that model a portion of inventory is not fixed at a single face value; prices move up or down with real-time demand, so the same seat can cost more during a high-demand on-sale than a quieter one. This is separate from resale, where independent sellers set their own asking prices that can sit far above the original. The bands here describe primary pricing; actual figures vary by city, venue size, seating zone and how demand moves during the sale.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Sabrina Carpenter tickets?',
          'a' => 'Sabrina Carpenter tickets generally start around USD 60 for upper-level seats and rise to roughly USD 280 for premium lower-bowl positions, with official VIP packages reaching about USD 400. She plays arenas rather than stadiums, so pricing sits in the mid band for a major pop act. Exact figures depend on the city, the venue and where demand sits when you buy.',
        ),
        1 => 
        array (
          'q' => 'Why do Sabrina Carpenter ticket prices vary so much?',
          'a' => 'Prices vary because most of her dates use dynamic pricing, which lets a share of tickets move with real-time demand. A high-demand on-sale can push the same seat well above its starting price. Venue size, seating zone and city also shift the numbers, and resale listings add a further layer on top of the original face value.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Sabrina Carpenter live?',
          'a' => 'The cheapest route is usually an upper-level seat bought at the original on-sale, which for her arena shows tends to start near USD 60. Buying directly through the official seller at face value avoids resale mark-ups. Mid-week dates and venues outside the largest markets often carry lower starting prices than weekend shows in major cities.',
        ),
        3 => 
        array (
          'q' => 'Do Sabrina Carpenter tickets use dynamic pricing?',
          'a' => 'Yes. Many of Sabrina Carpenter\'s dates are sold through Ticketmaster and Live Nation using dynamic pricing, where part of the inventory adjusts with demand. That means the listed price for a section can change during the sale rather than staying at a single fixed figure, which is why two buyers can pay different amounts for similar seats.',
        ),
        4 => 
        array (
          'q' => 'How much are VIP tickets for Sabrina Carpenter?',
          'a' => 'Official VIP packages for Sabrina Carpenter generally run between USD 250 and 400. They pair a premium seat with extras such as early venue entry, an exclusive merchandise item or a separate check-in. The exact contents and price depend on the package tier and the market, and quantities are limited per show.',
        ),
        5 => 
        array (
          'q' => 'Are Sabrina Carpenter tickets expensive?',
          'a' => 'Compared with stadium headliners her tickets are mid-range, with arena seats often starting near USD 60 at face value. They can still feel expensive on resale, where strong demand has pushed listings several times above the original price. Buying at the initial on-sale through the official channel is the most cost-effective approach.',
        ),
        6 => 
        array (
          'q' => 'Why are Sabrina Carpenter resale tickets so much higher than face value?',
          'a' => 'Resale prices are set by independent sellers rather than the artist or promoter, so they reflect what the market will pay. Because her shows sell quickly, resale listings frequently sit well above the original price. The starting bands quoted here are primary face values; resale figures can be considerably higher, especially for major-city dates.',
        ),
        7 => 
        array (
          'q' => 'What factors affect the price of a Sabrina Carpenter ticket?',
          'a' => 'The main factors are seating zone, venue size, the city and the demand level when you buy, since dynamic pricing responds to that demand. Weekend shows in large markets typically cost more than mid-week dates in smaller ones. VIP inclusions and any resale mark-up further change the final amount you pay.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Short n\' Sweet Tour',
      'intro' => 
      array (
        0 => 'Sabrina Carpenter\'s most recent headline run was the Short n\' Sweet Tour, built around her album of the same name and later folded in songs from her follow-up record. The trek played arenas across North America and beyond, including multi-night stands in major cities. For Sabrina Carpenter tour dates, availability and on-sale details are released per market, and individual shows often go on sale in waves rather than all at once.',
        1 => 'Beyond that arena run, Sabrina Carpenter tour activity has continued through festival headline slots and one-off appearances. When a new headline tour is announced it is typically confirmed through her official channels and the promoter, with presales preceding the general on-sale. Checking the listed dates for each city is the most reliable way to see where she is currently scheduled to perform.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Sabrina Carpenter touring in 2026?',
          'a' => 'Sabrina Carpenter\'s headline Short n\' Sweet Tour wrapped its arena run, and her 2026 activity has centred on festival headline appearances rather than a full new arena tour. Any further headline dates would be confirmed through her official channels. Check the current listings for each city to see where she is scheduled, as plans are announced market by market.',
        ),
        1 => 
        array (
          'q' => 'What was Sabrina Carpenter\'s most recent tour called?',
          'a' => 'Her most recent headline tour was the Short n\' Sweet Tour, named after her album. It ran across arenas in North America and other regions, with several cities receiving multiple nights, and the setlist was later updated to include songs from her newer material before the run concluded.',
        ),
        2 => 
        array (
          'q' => 'How long is a Sabrina Carpenter concert?',
          'a' => 'A Sabrina Carpenter headline show generally runs around 90 minutes to just over an hour and a half, including the encore. The exact length varies night to night depending on the setlist and any added surprise song. Festival sets are typically shorter than her full headline arena performances.',
        ),
        3 => 
        array (
          'q' => 'How do I find Sabrina Carpenter tour dates near me?',
          'a' => 'The most reliable source is the official tour listing for each market, which shows the venues and cities currently scheduled. Dates are usually released in stages, with presales ahead of the general on-sale. Because availability is set per show, checking the listing for your nearest city gives the clearest picture of what is on sale.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Sabrina Carpenter setlist leans on her run of pop singles alongside album cuts, opening at a high tempo and moving through ballad moments before a brisk finish. On the Short n\' Sweet Tour the running order drew heavily from that album and was later updated with newer songs, while a rotating surprise song kept each night distinct. The list below reflects the staples that appeared most consistently across recent shows.',
      ),
      'songs' => 
      array (
        0 => 'Taste',
        1 => 'Good Graces',
        2 => 'Manchild',
        3 => 'Slim Pickins',
        4 => 'Tornado Warnings',
        5 => 'Lie to Girls',
        6 => 'Bed Chem',
        7 => 'Feather',
        8 => 'Fast Times',
        9 => 'Busy Woman',
        10 => 'Sharpest Tool',
        11 => 'Because I Liked a Boy',
        12 => 'Coincidence',
        13 => 'Nonsense',
        14 => 'Dumb & Poetic',
        15 => 'Juno',
        16 => 'Tears',
        17 => 'Don\'t Smile',
        18 => 'Please Please Please',
      ),
      'encore' => 
      array (
        0 => 'Espresso',
      ),
      'note' => 'Setlists vary from night to night. The order and song choices change between shows, and Sabrina Carpenter regularly performs a different surprise song each evening, so any given concert may differ from the list above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Sabrina Carpenter play live?',
          'a' => 'Sabrina Carpenter\'s live shows typically feature singles such as Espresso, Please Please Please, Taste and Feather, alongside album tracks like Bed Chem, Juno and Nonsense. On her recent tour she also worked in newer songs including Manchild and Tears. The exact selection shifts between shows, and she usually adds a rotating surprise song each night.',
        ),
        1 => 
        array (
          'q' => 'Does Sabrina Carpenter do an encore?',
          'a' => 'Yes. On her recent tour Sabrina Carpenter closed with an encore, most often ending the night on Espresso. The encore can vary, but a final high-energy single is a regular feature of her headline shows. Festival sets are more compressed and may not follow the same encore structure.',
        ),
        2 => 
        array (
          'q' => 'Does Sabrina Carpenter play a surprise song?',
          'a' => 'Yes. A rotating surprise song is a recurring part of Sabrina Carpenter\'s shows, with a different track chosen most nights, sometimes drawn from her back catalogue or other material. Because it changes from city to city, it is one of the main reasons her setlists differ between performances.',
        ),
        3 => 
        array (
          'q' => 'Does Sabrina Carpenter open with Taste?',
          'a' => 'On her recent tour Sabrina Carpenter frequently opened with Taste, using it as a high-tempo start before moving through the rest of the set. Opening choices can change across a run, so it is not guaranteed every night, but it was a common starting point on recent dates.',
        ),
      ),
    ),
  ),
  'post-malone' => 
  array (
    'name' => 'Post Malone',
    'genre' => 'Hip-Hop / Pop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 850,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Post Malone ticket prices sit at the higher end of the live market because he now plays stadiums rather than arenas, and most North American dates are co-headline shows that pair his catalogue with a second major name. Entry-level seats generally open in the region of 60 to 110 US dollars, while lower-bowl and pitch positions climb steeply. The spread on any given night reflects venue size, sightline and how close demand sits to the stage.',
        1 => 'Because Post Malone tours through Ticketmaster and Live Nation, his ticket prices are usually set by dynamic pricing rather than fixed face values. That means the same seat can carry a different cost depending on when you buy and how a particular stadium is selling. The bands below describe how the tiers tend to work across a typical date, not the exact figure for any one show.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and back terrace',
          'desc' => 'The most economical way in, usually around 60 to 120 USD. These are upper-tier stadium seats well back from the stage, suited to listeners who want the full production and screens rather than proximity. Sightlines are wide but distant.',
        ),
        1 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing access to the pitch or floor, commonly 130 to 220 USD. This puts you in the main standing body of the crowd with no fixed seat. Prices rise the closer a date sells to capacity, and front-of-floor positions depend on arrival time rather than ticket price.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl seated',
          'desc' => 'Reserved seats in the lower tier with clear stage views, broadly 200 to 380 USD. A balance between cost and a fixed, comfortable position closer to the action than the upper levels.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-section seats, early-entry and hospitality packages, typically 400 to 850 USD or above on the highest-demand nights. These cover the closest fixed positions and any official VIP extras bundled by the promoter.',
        ),
      ),
      'why' => 'Post Malone\'s tours run on Ticketmaster\'s dynamic, demand-based pricing, so a seat\'s cost moves with how strongly a given stadium is selling rather than sitting at one printed face value. A ticket bought early in a quieter market can cost noticeably less than the identical seat once a date heats up. Official VIP and premium packages are priced separately on top of the standard tiers, which is why the overall range is wide.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Post Malone tickets?',
          'a' => 'Post Malone tickets generally run from around 60 to 120 USD for upper-level stadium seats up to 400 to 850 USD or more for premium and VIP positions. Because he plays stadiums and most dates are co-headline shows, prices sit higher than a typical arena act, and the exact figure depends on the venue and how the date is selling.',
        ),
        1 => 
        array (
          'q' => 'Why do Post Malone ticket prices change so much?',
          'a' => 'Most Post Malone dates use Ticketmaster\'s dynamic pricing, which adjusts the cost of a seat according to live demand. The same lower-bowl seat can carry a different price from one day to the next, and from one stadium to another, which is why his ticket prices vary more than a fixed face value would.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Post Malone?',
          'a' => 'The lowest prices are usually upper-tier or back-terrace stadium seats, often starting near 60 to 110 USD before fees. These positions are furthest from the stage but still cover the full show and video screens. Buying early in a date\'s sales cycle tends to catch the softer end of dynamic pricing.',
        ),
        3 => 
        array (
          'q' => 'How much are Post Malone floor or pitch tickets?',
          'a' => 'General admission floor or pitch tickets commonly fall between roughly 130 and 220 USD. They give standing access to the main body of the crowd without a fixed seat, so how close you get depends on arrival time rather than the price you paid.',
        ),
        4 => 
        array (
          'q' => 'Are Post Malone VIP packages worth the price?',
          'a' => 'Post Malone VIP and premium packages typically cost from about 400 USD upward and bundle front-section seats, early entry or hospitality extras. Whether they are worth it depends on how much you value proximity and the add-ons, since the underlying seat can sometimes be bought on its own for less.',
        ),
        5 => 
        array (
          'q' => 'Do Post Malone ticket prices include fees?',
          'a' => 'The headline price rarely includes service and facility fees, which Ticketmaster adds at checkout. Expect the final total to sit above the listed figure, and note that some markets now show all-in pricing where the fees are folded into the displayed amount from the start.',
        ),
        6 => 
        array (
          'q' => 'Why are Post Malone tickets more expensive than other rappers?',
          'a' => 'Post Malone has moved up to stadium-scale venues and pairs many dates with a second headline act, which lifts demand and pricing above club or arena hip-hop shows. Stadium production costs and dynamic pricing both push his ticket prices toward the higher end of the live market.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Post Malone tickets early?',
          'a' => 'Often, yes. Because dynamic pricing tracks demand, seats bought soon after a date goes on sale, or in a less in-demand market, frequently cost less than the same seats closer to the show. Prices can also fall again late on if a date has unsold inventory, though that is not guaranteed.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The BIG ASS Stadium Tour Part 2',
      'intro' => 
      array (
        0 => 'Post Malone\'s current run is billed as The BIG ASS Stadium Tour Part 2, a continuation of the stadium project he launched in 2025. The bulk of the North American leg is a co-headline outing with Jelly Roll, moving through large football and baseball stadiums before further international dates. Post Malone tour dates are released by the promoter in waves, so the routing below describes how the run is structured rather than naming specific nights.',
        1 => 'Across this tour Post Malone leans on the stadium format, with a long single set rather than a short festival slot. The Part 2 branding signals that it follows directly on from the first stadium leg, keeping a similar production while adding new material from his recent releases. As with most major Live Nation runs, additional Post Malone tour dates can be added to busy markets after the initial announcement.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Post Malone touring in 2026?',
          'a' => 'Yes. Post Malone is on the road with The BIG ASS Stadium Tour Part 2, a stadium run that continues the project he started in 2025. Much of the North American schedule is a co-headline tour alongside Jelly Roll, with further international dates beyond the home leg.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Post Malone\'s current tour?',
          'a' => 'The current outing is called The BIG ASS Stadium Tour Part 2. It follows on from the first stadium leg of the same name and is staged largely in major stadiums rather than arenas.',
        ),
        2 => 
        array (
          'q' => 'Who is supporting Post Malone on tour?',
          'a' => 'On much of the North American leg Post Malone shares top billing with Jelly Roll in a co-headline format, with additional guests such as Carter Faith on selected dates. Line-ups can differ by market, so the supporting bill varies from one stadium to the next.',
        ),
        3 => 
        array (
          'q' => 'Does Post Malone tour internationally?',
          'a' => 'Yes. Beyond the North American stadium leg, Post Malone has taken recent tours to the UK, Europe, Asia and Australia. International routing on a given cycle is announced separately, so overseas dates may go on sale at a different time from the home shows.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Post Malone setlist on the stadium tour typically runs to around twenty songs across roughly an hour and three-quarters, opening with newer country-leaning material before moving through the hip-hop and pop hits that built his name. The order below reflects how his recent shows have been structured, drawing on staples that appear night after night. As ever, the exact running order shifts between cities, so treat this as a guide to the songs rather than a fixed list.',
      ),
      'songs' => 
      array (
        0 => 'Texas Tea',
        1 => 'Wow.',
        2 => 'Better Now',
        3 => 'Wrong Ones',
        4 => 'Go Flex',
        5 => 'Hollywood\'s Bleeding',
        6 => 'I Fall Apart',
        7 => 'Losers',
        8 => 'Goodbyes',
        9 => 'What Don\'t Belong to Me',
        10 => 'I Ain\'t Comin\' Back',
        11 => 'Feeling Whitney',
        12 => 'Circles',
        13 => 'White Iverson',
        14 => 'Psycho',
        15 => 'Pour Me a Drink',
        16 => 'Dead at the Honky Tonk',
        17 => 'rockstar',
        18 => 'I Had Some Help',
        19 => 'Sunflower',
      ),
      'encore' => 
      array (
        0 => 'Congratulations',
      ),
      'note' => 'Setlists vary night to night, and Post Malone regularly reorders songs, drops some and adds others depending on the city, the venue and any guests, so no two shows are identical.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Post Malone play live?',
          'a' => 'A typical Post Malone set blends his newer country-tinged tracks such as Texas Tea, Pour Me a Drink and I Had Some Help with the hits that made his name, including Wow., Better Now, Circles, rockstar, Sunflower and Congratulations. He generally plays a little over twenty songs a night.',
        ),
        1 => 
        array (
          'q' => 'Does Post Malone play Sunflower and Circles live?',
          'a' => 'Yes. Sunflower and Circles are both fixtures of Post Malone\'s recent setlists, with Sunflower usually arriving late in the main set. Other staples that appear most nights include Better Now, rockstar, White Iverson and Congratulations.',
        ),
        2 => 
        array (
          'q' => 'Does Post Malone do an encore?',
          'a' => 'Post Malone tends to run a continuous stadium set rather than a long break-and-return encore, often closing the night with Congratulations. Whether a formal encore is played can vary by city, so the ending is not always staged the same way.',
        ),
        3 => 
        array (
          'q' => 'How long is a Post Malone concert?',
          'a' => 'His headline stadium set generally lasts around an hour and three-quarters, covering a little over twenty songs. On co-headline dates the overall evening runs longer once the second act and any support are included, so the full bill can stretch across several hours.',
        ),
      ),
    ),
  ),
  'metallica' => 
  array (
    'name' => 'Metallica',
    'genre' => 'Metal',
    'prices' => 
    array (
      'currency' => 'USD',
      'range_low' => 95,
      'range_high' => 850,
      'intro' => 
      array (
        0 => 'Metallica ticket prices cover a wide span because the band plays large arenas, festivals and stadiums, and most shows run on the in-the-round M72 staging that puts seats on all four sides. Entry-level upper-tier seats generally sit at the lower end of the band, while floor positions close to the rotating stage and snake pit reach the upper end. Demand, venue size and the city all move the figure within that range.',
        1 => 'When you compare Metallica tour dates, the main drivers are the seat location and whether a date is a single show or part of a two-night No Repeat Weekend. Standard resale and primary listings tend to cluster in the mid hundreds for a good seated view, with premium and pit access higher. Reading the seating map before you buy is the surest way to match a price to the view you actually want.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper Level',
          'desc' => 'Seats in the higher tiers of the arena or stadium, typically around 95 to 180 USD. The in-the-round stage means even upper sections face the band rather than sitting behind it, so sightlines are usually reasonable for the price.',
        ),
        1 => 
        array (
          'name' => 'Lower Level',
          'desc' => 'Seated positions closer to the floor on the 100 or lower-bowl level, generally in the 180 to 350 USD range. These balance a clear, elevated view of the full rotating stage with a more moderate price than the floor.',
        ),
        2 => 
        array (
          'name' => 'Floor / General Admission',
          'desc' => 'Standing floor or reserved floor seats surrounding the central stage, commonly 300 to 550 USD. Demand for proximity to the band pushes these higher, and exact pricing shifts with the venue and the date.',
        ),
        3 => 
        array (
          'name' => 'Snake Pit & Premium',
          'desc' => 'The Snake Pit inside the stage ring, plus official premium and Enhanced Experience packages, which can run from roughly 450 up to 850 USD or beyond. These offer the closest vantage point and, in package form, may add early entry or merchandise.',
        ),
      ),
      'why' => 'Metallica tours through Live Nation and Ticketmaster, which apply dynamic pricing on many primary tickets. That means the listed price can move up or down with demand rather than staying at a fixed face value, so identical seats may cost different amounts depending on when you look. High-demand cities and the Sphere dates tend to sit at the firmer end. Checking prices across more than one date, and comparing primary against verified resale, gives the clearest read on what a given seat is actually worth.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Metallica tickets?',
          'a' => 'Metallica tickets generally range from about 95 USD for upper-tier seats to 850 USD or more for Snake Pit and premium packages. Lower-level seats usually land in the mid hundreds. The figure depends on the venue, the city and the seat location relative to the in-the-round stage.',
        ),
        1 => 
        array (
          'q' => 'Why do Metallica ticket prices change so much?',
          'a' => 'Prices change because Metallica\'s tours use dynamic pricing on many primary tickets, so the cost rises and falls with live demand. Stage layout matters too: floor and Snake Pit positions next to the central stage carry a premium over upper-tier seats.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Metallica?',
          'a' => 'The cheapest seats are normally in the upper tiers, often starting around 95 USD. Because the M72 staging is in-the-round, these higher sections still face the band, so a budget seat does not mean a view from behind the stage.',
        ),
        3 => 
        array (
          'q' => 'How much do Metallica floor tickets cost?',
          'a' => 'Floor and general admission tickets around the central stage typically cost between 300 and 550 USD, depending on the venue and date. They put you on the same level as the rotating stage, which is why they sit above seated lower-level prices.',
        ),
        4 => 
        array (
          'q' => 'What is the Metallica Snake Pit and how much is it?',
          'a' => 'The Snake Pit is a standing area inside the ring of Metallica\'s in-the-round stage, giving the closest view of the band. It usually falls at the top of the price band, from roughly 450 up to 850 USD or higher when sold as part of a premium package.',
        ),
        5 => 
        array (
          'q' => 'Are Metallica premium or VIP packages worth it?',
          'a' => 'Metallica\'s official Enhanced Experiences and premium packages bundle prime locations with extras such as early entry or merchandise, which is why they reach the upper end of the range. Whether they are worth it depends on how much the proximity and add-ons matter to you versus a standard seat.',
        ),
        6 => 
        array (
          'q' => 'Do Metallica ticket prices differ by city?',
          'a' => 'Yes. Metallica ticket prices vary by city according to venue size and local demand, with marquee markets and the Sphere residency typically commanding higher averages than smaller-demand stops. Comparing several dates often reveals a meaningful gap for similar seats.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Metallica tickets early?',
          'a' => 'Not always. Because dynamic pricing tracks demand, prices for high-demand Metallica dates can climb closer to the show as supply tightens, while quieter dates may soften. Checking early gives a baseline, but the trend depends on the specific date and city.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'M72 World Tour',
      'intro' => 
      array (
        0 => 'Metallica\'s current run is the M72 World Tour, built around the album 72 Seasons and staged in-the-round so the band performs from a central stage encircled by the crowd. Many cities host a two-night No Repeat Weekend, where the same venue gets two different setlists across the weekend and a single ticket type can cover both. To follow Metallica tour dates, watch for whether a city is a one-off show or part of one of these weekend pairings.',
        1 => 'The tour has moved through Europe, North America, Latin America and Australia across multiple legs, with support from rotating guests rather than a single fixed opener. Alongside the touring legs, Metallica have announced an extended residency at the Sphere in Las Vegas. Because routing and on-sale details are confirmed leg by leg, checking the official listings is the reliable way to see which Metallica tour dates are live for your region.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Metallica touring in 2026?',
          'a' => 'Yes. Metallica continue the M72 World Tour through 2026, including European stadium and arena dates and an extended residency at the Sphere in Las Vegas. Exact dates are confirmed leg by leg, so the official tour listings show which shows are currently on sale.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Metallica\'s current tour?',
          'a' => 'The current tour is the M72 World Tour, named after the 2023 album 72 Seasons. It is staged in-the-round on a central stage, with many stops running as two-night No Repeat Weekends featuring different setlists each night.',
        ),
        2 => 
        array (
          'q' => 'What is a Metallica No Repeat Weekend?',
          'a' => 'A No Repeat Weekend is when Metallica play two shows in the same city across a weekend with no song repeated between the two nights, and different support acts each evening. It rewards fans who attend both dates with a far wider span of the catalogue.',
        ),
        3 => 
        array (
          'q' => 'Who is opening for Metallica on the M72 tour?',
          'a' => 'Support on the M72 World Tour rotates by leg and by night rather than staying fixed, with acts such as Pantera, Mammoth WVH, Five Finger Death Punch, Ice Nine Kills, Gojira and Knocked Loose appearing across different dates. The official date listing confirms who plays each specific show.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Metallica\'s M72 setlist draws on four decades of material, pairing 72 Seasons tracks like Lux Aeterna with long-standing staples such as Master of Puppets, One and Enter Sandman. The in-the-round staging means the band rotate through the show, and on No Repeat Weekends the two nights share almost no songs, so the Metallica setlist you hear depends heavily on which date you attend.',
      ),
      'songs' => 
      array (
        0 => 'Creeping Death',
        1 => 'For Whom the Bell Tolls',
        2 => 'Lux Aeterna',
        3 => 'Ride the Lightning',
        4 => 'Fuel',
        5 => 'The Memory Remains',
        6 => 'King Nothing',
        7 => 'Welcome Home (Sanitarium)',
        8 => 'Moth Into Flame',
        9 => 'Sad but True',
        10 => 'Wherever I May Roam',
        11 => 'The Unforgiven',
        12 => 'Fade to Black',
        13 => 'Nothing Else Matters',
        14 => 'One',
        15 => 'Master of Puppets',
        16 => 'Whiplash',
        17 => 'Battery',
        18 => 'Seek & Destroy',
        19 => 'Enter Sandman',
      ),
      'encore' => 
      array (
        0 => 'One',
        1 => 'Master of Puppets',
        2 => 'Enter Sandman',
      ),
      'note' => 'Setlists vary night to night, especially on No Repeat Weekends where the two shows in a city share almost no songs, so the running order and song choices change from date to date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Metallica play live?',
          'a' => 'Metallica regularly play staples such as Master of Puppets, One, Enter Sandman, Creeping Death, For Whom the Bell Tolls and Seek & Destroy, mixed with 72 Seasons material like Lux Aeterna and Moth Into Flame. The exact selection changes from show to show across the M72 tour.',
        ),
        1 => 
        array (
          'q' => 'Does Metallica do an encore?',
          'a' => 'Yes. Metallica typically close with an encore that often includes One, Master of Puppets or Enter Sandman, sometimes preceded by Seek & Destroy. The specific encore songs change between dates, particularly across No Repeat Weekend pairings.',
        ),
        2 => 
        array (
          'q' => 'How long is a Metallica concert?',
          'a' => 'A Metallica headline set on the M72 tour generally runs around two hours, covering roughly sixteen to eighteen songs after the support acts. The length and song count vary by night, and No Repeat Weekend shows draw on a deeper pool of material.',
        ),
        3 => 
        array (
          'q' => 'Does Metallica play the same setlist every night?',
          'a' => 'No. Metallica change the setlist from night to night, and on No Repeat Weekends the two shows in a city share almost no songs. That means attending both dates of a weekend gives a much wider span of their catalogue than a single show.',
        ),
      ),
    ),
  ),
  'arijit-singh' => 
  array (
    'name' => 'Arijit Singh',
    'genre' => 'Bollywood',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Arijit Singh ticket prices in the United States generally sit at the upper end of the Bollywood live market, reflecting the size of the venues he fills and the strength of diaspora demand. Most seats land somewhere between the low hundreds and several hundred dollars, with entry-level upper-tier places opening the band and front-of-floor and premium hospitality closing it. The figures below describe how pricing tends to work rather than any single show on sale.',
        1 => 'Because Arijit Singh plays large arenas and, increasingly, stadium-scale rooms abroad, prices vary widely by city, seat location and how early you book. Tickets sold through Ticketmaster and other primary outlets usually carry per-ticket service fees on top of the face value, so the amount at checkout runs higher than the headline price. Resale listings can sit well above or, late in a slow on-sale, slightly below face.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear sections',
          'desc' => 'The most affordable way in, covering the higher seating bowl and seats furthest from the stage. These typically run from around $75 to $130 and still offer a clear view of the screens and full production in an arena setting.',
        ),
        1 => 
        array (
          'name' => 'Lower tier and side floor',
          'desc' => 'Mid-bowl and lower-level seating along the sides of the room, generally priced from roughly $130 to $250. A reliable balance of sightline and cost for those who want to be closer without paying front-row rates.',
        ),
        2 => 
        array (
          'name' => 'Front floor and prime seated',
          'desc' => 'Seats and standing positions close to the stage, usually in the $250 to $400 range. These sell quickly and put you within clear view of Arijit Singh and the band for the quieter, acoustic-led passages.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages bundling premium placement with extras such as early entry, dedicated entrances or merchandise. These commonly start near $400 and reach $600 or more depending on the city and what is included.',
        ),
      ),
      'why' => 'Arijit Singh tours through Ticketmaster and other major promoters, most of which apply dynamic pricing, so the cost of a given seat can move up or down with demand rather than staying fixed for the whole on-sale. High-demand cities and front sections tend to be priced higher from the outset and can climb further as a show approaches a sell-out, while slower markets may settle lower. Add per-ticket service and facility fees to any face value when budgeting, and treat resale prices as a separate market that reflects scarcity rather than the official rate.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Arijit Singh tickets?',
          'a' => 'Arijit Singh tickets in the United States typically range from about $75 for upper-tier seats to $400 or more for front-floor and prime positions, with VIP packages reaching $600 and above. Final checkout prices are higher once per-ticket service fees are added.',
        ),
        1 => 
        array (
          'q' => 'Why are Arijit Singh tickets so expensive?',
          'a' => 'Arijit Singh fills large arenas and stadium-scale venues with strong diaspora demand, and most on-sales use dynamic pricing that raises in-demand seats as a show approaches a sell-out. Limited premium inventory and service fees push the total cost higher still.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Arijit Singh live?',
          'a' => 'The cheapest seats are usually in the upper tier or rear sections, often starting around $75 to $130. Booking early in the on-sale, before dynamic pricing lifts the better-located seats, tends to secure the lowest face value.',
        ),
        3 => 
        array (
          'q' => 'Do Arijit Singh tickets use dynamic pricing?',
          'a' => 'Yes. Like most major tours sold through Ticketmaster and large promoters, Arijit Singh shows commonly use dynamic pricing, so seat prices can rise or fall with demand during the on-sale rather than staying fixed.',
        ),
        4 => 
        array (
          'q' => 'Are there VIP packages for Arijit Singh concerts?',
          'a' => 'Official VIP and hospitality packages are usually offered, bundling premium seating with extras such as early entry, a dedicated entrance or merchandise. These generally start near $400 and can exceed $600 depending on the city and inclusions.',
        ),
        5 => 
        array (
          'q' => 'How much are floor or front-row Arijit Singh tickets?',
          'a' => 'Front-floor and prime seated places typically cost between $250 and $400 at face value, before fees. These positions sell quickly and command the highest prices outside of dedicated VIP packages.',
        ),
        6 => 
        array (
          'q' => 'Are Arijit Singh tickets cheaper on resale?',
          'a' => 'It depends on demand. For popular Arijit Singh dates, resale prices usually sit above face value because of scarcity, while a slower-selling show can occasionally see resale listings dip near or slightly below the original price closer to the date.',
        ),
        7 => 
        array (
          'q' => 'Do Arijit Singh ticket prices include fees?',
          'a' => 'Usually not at the headline stage. The advertised face value is shown first, and per-ticket service and venue facility fees are added during checkout, so the final amount you pay is higher than the listed seat price.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'World Tour 2026-27',
      'intro' => 
      array (
        0 => 'Arijit Singh\'s current touring is built around his World Tour 2026-27, a large multi-city run that takes in arenas and, in several markets, stadium-scale venues. Tour dates are released city by city across multiple legs rather than all at once, so availability and routing differ by region. The live schedule and on-sale information are handled separately by the booking system; this page explains how his touring generally works rather than listing specific dates.',
        1 => 'As one of the most in-demand voices in Bollywood playback, Arijit Singh tends to announce tour dates with a short lead time before tickets go on sale, and high-demand cities can sell out quickly. Presales, fan registrations and venue or promoter access often open ahead of the general on-sale, so following official channels is the most reliable way to know when dates for a given city become available.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Arijit Singh touring in 2026?',
          'a' => 'Yes. Arijit Singh is touring under his World Tour 2026-27, a large multi-leg run across arenas and stadium-scale venues. Dates are announced city by city, so specific shows for your location depend on the leg covering that region.',
        ),
        1 => 
        array (
          'q' => 'What is Arijit Singh\'s current tour called?',
          'a' => 'His current run is the World Tour 2026-27, announced as a multi-city international tour that includes both arena and stadium-scale shows across several legs rather than a single short itinerary.',
        ),
        2 => 
        array (
          'q' => 'How can I find Arijit Singh tour dates near me?',
          'a' => 'Arijit Singh tour dates are listed city by city as each leg is confirmed. Checking the live listings for your region, and registering for presale or fan access, is the most reliable way to catch dates near you before they sell out.',
        ),
        3 => 
        array (
          'q' => 'Do Arijit Singh concerts sell out quickly?',
          'a' => 'High-demand cities often do. Given the size of his following across the Bollywood diaspora, popular Arijit Singh dates can sell out fast, which is why presales and early booking are worth tracking when a city is announced.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'An Arijit Singh setlist draws on more than a decade of Bollywood playback, weaving together heartbreak ballads, romantic anthems and a handful of upbeat numbers across a long evening. Recent shows have opened with Tum Hi Ho and moved through staples such as Kesariya, Channa Mereya and Apna Bana Le, with Arijit pausing between songs to speak to the audience and occasionally take requests. The list below reflects songs he has regularly performed on his most recent tours.',
      ),
      'songs' => 
      array (
        0 => 'Tum Hi Ho',
        1 => 'Kesariya',
        2 => 'Ae Dil Hai Mushkil',
        3 => 'Gerua',
        4 => 'Apna Bana Le',
        5 => 'Enna Sona',
        6 => 'Channa Mereya',
        7 => 'Chaleya',
        8 => 'Agar Tum Saath Ho',
        9 => 'Raabta',
        10 => 'Phir Mohabbat',
        11 => 'Hamari Adhuri Kahani',
        12 => 'Sajni',
        13 => 'Tujhe Kitna Chahne Lage Hum',
        14 => 'Bekhayali',
        15 => 'Kalank',
        16 => 'Duaa',
        17 => 'Illahi',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, and the order, song choices and any improvised or requested numbers change from city to city, so the running order at any given show may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Arijit Singh play live?',
          'a' => 'Arijit Singh\'s live shows centre on his best-known playback tracks, including Tum Hi Ho, Kesariya, Channa Mereya, Apna Bana Le, Ae Dil Hai Mushkil and Agar Tum Saath Ho, mixing slow ballads with a few more upbeat numbers across the night.',
        ),
        1 => 
        array (
          'q' => 'Does Arijit Singh perform Tum Hi Ho at concerts?',
          'a' => 'Yes. Tum Hi Ho is one of Arijit Singh\'s signature songs and a regular fixture in his sets, often used to open the show or as a major singalong moment during the evening.',
        ),
        2 => 
        array (
          'q' => 'How long is an Arijit Singh concert?',
          'a' => 'Arijit Singh\'s concerts typically run for around two to three hours, with the length varying by show. He often extends the evening with spoken interludes, audience interaction and occasional song requests between numbers.',
        ),
        3 => 
        array (
          'q' => 'Does Arijit Singh do an encore?',
          'a' => 'Arijit Singh\'s shows tend to flow as one continuous performance rather than a formal encore break, with the biggest singalong numbers saved for late in the set. The exact closing songs vary from night to night.',
        ),
      ),
    ),
  ),
  'travis-scott' => 
  array (
    'name' => 'Travis Scott',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 95,
      'range_high' => 525,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Travis Scott ticket prices sit at the higher end of the hip-hop market because his shows are built around large arenas and stadiums rather than smaller halls. As a general guide, upper-level seats tend to open around 95 to 150 USD, lower-tier and side-stage positions move into the middle band, and floor or premium positions reach the upper end. The exact figure for any given night depends on the venue and the demand for that market.',
        1 => 'Beyond the standard seated and floor categories, Travis Scott runs official packages that bundle better positions with merchandise or early entry, and these carry a clear premium. Because his production leans heavily on the floor pit and the stage design, general-admission standing is usually the most sought-after option. When you compare Travis Scott ticket prices across cities, the spread between the cheapest seat and a front-of-stage package is wide, so it is worth deciding early which experience you want.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper Level',
          'desc' => 'Seats in the higher tiers of the arena or stadium, generally from 95 to 160 USD. These give a full view of the stage and the production lighting from distance, and are the most economical way to attend a Travis Scott show.',
        ),
        1 => 
        array (
          'name' => 'Lower Level Seated',
          'desc' => 'Reserved seats closer to the stage in the lower bowl, typically 165 to 280 USD. A balance between price and proximity, with a clearer sightline to the performer and the moving stage elements.',
        ),
        2 => 
        array (
          'name' => 'General Admission Floor',
          'desc' => 'Standing access to the floor pit, usually 220 to 360 USD. This is the densest part of the crowd and the area the show is designed around, so it sells quickly in most markets.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP Packages',
          'desc' => 'Official packages combining prime floor or front-section positions with extras such as merchandise or early entry, commonly 360 to 525 USD or above. Allocations are limited and vary by city.',
        ),
      ),
      'why' => 'Travis Scott tours run on dynamic pricing through the primary ticketing systems used by major Live Nation and Ticketmaster productions. This means the listed price for a section can move up or down in response to live demand rather than staying fixed from the on-sale, so two buyers in the same category may pay different amounts depending on when they purchase. Premium and platinum-style seats are priced by the system to track market value, which is why the most in-demand floor positions can climb well above the standard face value. Knowing this helps explain why quoted figures shift between markets and over time.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Travis Scott tickets?',
          'a' => 'Travis Scott tickets generally run from around 95 USD for upper-level seats to roughly 525 USD or more for premium floor and VIP packages. Lower-bowl seats and general-admission floor positions fall between those figures, and the precise price depends on the venue size and how strong demand is in that particular city.',
        ),
        1 => 
        array (
          'q' => 'Why are Travis Scott tickets so expensive?',
          'a' => 'Travis Scott plays arenas and stadiums with a large stage production, which sets prices above the average hip-hop show. His tours also use dynamic pricing, so the busiest floor and front sections are priced to market value and can rise notably above the cheapest seats in the same building.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Travis Scott live?',
          'a' => 'The cheapest Travis Scott tickets are usually upper-tier seats, which tend to start in the region of 95 to 150 USD. These offer a complete view of the stage and lighting from height, and buying early in the on-sale generally gives access to the lower end of that band before dynamic pricing moves it.',
        ),
        3 => 
        array (
          'q' => 'How much is the Travis Scott floor or GA pit?',
          'a' => 'General-admission floor and pit access for Travis Scott typically costs between 220 and 360 USD, though it can rise higher in high-demand markets. The floor is the standing area the show is staged around, so it is one of the first categories to sell out.',
        ),
        4 => 
        array (
          'q' => 'Does Travis Scott use dynamic pricing?',
          'a' => 'Yes. Like most major Live Nation and Ticketmaster tours, Travis Scott shows use dynamic pricing, where the cost of a section shifts with live demand. This is why the same seat can carry different prices at different points in the sale and why premium positions often exceed the base face value.',
        ),
        5 => 
        array (
          'q' => 'Are there VIP packages for Travis Scott concerts?',
          'a' => 'Travis Scott tours offer official VIP and premium packages that pair prime floor or front-section positions with extras such as merchandise or early entry. These usually fall in the 360 to 525 USD range or above, with limited allocations that differ from city to city.',
        ),
        6 => 
        array (
          'q' => 'How much were Travis Scott Circus Maximus tickets?',
          'a' => 'On the Circus Maximus run, prices broadly spanned from around 100 USD in the upper sections to several hundred for floor and premium positions, with averages reported in the 300 to 500 USD region. Figures varied widely by country and venue across the tour\'s stadium and arena dates.',
        ),
        7 => 
        array (
          'q' => 'Do Travis Scott ticket prices vary by city?',
          'a' => 'Yes, Travis Scott ticket prices differ by market depending on venue capacity, local demand and whether the date is a stadium or arena show. Larger stadium markets and high-demand cities tend to push floor and premium prices toward the top of the range, while seated upper tiers stay closer to the entry level.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Utopia – Circus Maximus Tour',
      'intro' => 
      array (
        0 => 'Travis Scott\'s most recent major run was the Utopia – Circus Maximus Tour, built around his 2023 album Utopia and staged across arenas and stadiums worldwide. The trek ran from late 2023 through 2025 and became the highest-grossing solo rap tour on record, taking in more than two million tickets across over eighty dates. Anyone tracking Travis Scott tour dates will find this the defining cycle of his recent touring history.',
        1 => 'The Circus Maximus production was known for its scale, with a central stage, heavy lighting and a floor pit that anchored the live experience. The tour reached North America, Europe, Latin America, the Middle East, Asia and beyond, adapting its venues to each market. Future Travis Scott tour dates have not been confirmed in this content, since live scheduling is handled separately, but the Circus Maximus era remains the reference point for his current touring format.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Travis Scott touring in 2026?',
          'a' => 'Travis Scott\'s main touring cycle was the Utopia – Circus Maximus Tour, which ran through 2025. Any individual 2026 appearances would be confirmed separately, and this page does not list specific dates; check the live listings for the current schedule in your market.',
        ),
        1 => 
        array (
          'q' => 'What is Travis Scott\'s current tour called?',
          'a' => 'The most recent named Travis Scott tour is the Utopia – Circus Maximus Tour, supporting his Utopia album. It ran across arenas and stadiums worldwide and became the highest-grossing solo rap tour to date.',
        ),
        2 => 
        array (
          'q' => 'How long is a Travis Scott concert?',
          'a' => 'A Travis Scott headline set generally runs in the region of 75 to 105 minutes, depending on the venue and whether it is a festival or arena date. The Circus Maximus shows were built around a continuous, high-energy run rather than long breaks between songs.',
        ),
        3 => 
        array (
          'q' => 'Where did Travis Scott tour on Circus Maximus?',
          'a' => 'The Utopia – Circus Maximus Tour spanned North America, Europe, Latin America, the Middle East, Asia, Africa and Oceania across more than eighty dates. It covered a mix of large arenas and stadiums, making it one of the most widely travelled rap tours staged.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Travis Scott setlist leans heavily on his biggest commercial singles alongside deeper album cuts from Utopia, Astroworld and Rodeo. Recent shows on the Circus Maximus run and his 2025 festival sets have reliably featured FE!N, SICKO MODE, GOOSEBUMPS and HIGHEST IN THE ROOM, with the floor pit forming a core part of how those songs are performed live. The running order below reflects the songs that have appeared most consistently.',
      ),
      'songs' => 
      array (
        0 => 'HYAENA',
        1 => 'MODERN JAM',
        2 => 'THANK GOD',
        3 => 'MAMACITA',
        4 => 'SKYFALL',
        5 => 'BUTTERFLY EFFECT',
        6 => 'HIGHEST IN THE ROOM',
        7 => 'STARGAZING',
        8 => 'MY EYES',
        9 => 'SKELETONS',
        10 => '90210',
        11 => 'I KNOW ?',
        12 => 'NO BYSTANDERS',
        13 => 'TELEKINESIS',
        14 => 'FE!N',
        15 => 'SICKO MODE',
        16 => 'ANTIDOTE',
        17 => 'GOOSEBUMPS',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Travis Scott regularly reorders songs, drops tracks or adds extended FE!N reprises depending on the venue and the crowd, so the exact running order on any given date will differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Travis Scott play live?',
          'a' => 'Travis Scott\'s live shows centre on FE!N, SICKO MODE, GOOSEBUMPS, HIGHEST IN THE ROOM and ANTIDOTE, supported by Utopia tracks such as HYAENA, MODERN JAM and MY EYES. He mixes his biggest singles with album cuts from Astroworld and Rodeo, and the order changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Travis Scott play SICKO MODE live?',
          'a' => 'Yes, SICKO MODE is a fixture of Travis Scott\'s live sets and is usually saved for the latter part of the show. It is one of his most recognised tracks and a consistent crowd moment, alongside FE!N and GOOSEBUMPS in the closing stretch.',
        ),
        2 => 
        array (
          'q' => 'Does Travis Scott do an encore?',
          'a' => 'Travis Scott typically performs a continuous set rather than a formal encore with a stage exit and return. He often extends his finale, most notably repeating FE!N multiple times, so the closing section can feel like an encore even though the show runs straight through.',
        ),
        3 => 
        array (
          'q' => 'How many songs are in a Travis Scott set?',
          'a' => 'A Travis Scott headline set usually runs around eighteen to twenty-five songs, though several are performed as shortened segments or transitions rather than in full. The count shifts between festival and arena dates and from one night to the next.',
        ),
      ),
    ),
  ),
  'sza' => 
  array (
    'name' => 'SZA',
    'genre' => 'R&B',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'SZA ticket prices vary widely because her shows have moved into stadiums and large arenas, where seat location drives most of the cost. Upper-level and rear sections sit at the affordable end, while floor seats, front blocks and premium packages reach the top of the band. As an R&B headline act with consistent demand, her events tend to hold their value, so the gap between the cheapest and most expensive seats on a given night can be considerable.',
        1 => 'When budgeting for SZA, treat the figures below as a general guide rather than a fixed rate. Face value depends on the venue, the city and how a particular date is selling, and resale listings can sit above or below the original price. The most reliable approach is to compare the seating tiers for the specific show you want rather than assuming a single flat price applies across her run.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear sections',
          'desc' => 'The entry point for most SZA shows, generally around 60 to 110 USD. These seats sit higher in the bowl or toward the back of a stadium, with a full view of the stage and screens but more distance from the performance.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and side seating',
          'desc' => 'Lower bowl sides and mid-range blocks usually land between 110 and 220 USD. A balance of sightline and cost, these are often the first sections to sell once a date is announced.',
        ),
        2 => 
        array (
          'name' => 'Floor and lower front',
          'desc' => 'Floor standing or seated sections close to the stage typically run from 220 to 450 USD. Demand here is high on popular dates, so prices climb quickly as availability drops.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-block seats and official VIP packages range from roughly 450 to 1,200 USD. These can bundle early entry, premium viewing areas and merchandise, with exact inclusions varying by tour and city.',
        ),
      ),
      'why' => 'SZA\'s larger tours run through Ticketmaster and Live Nation, both of which use dynamic pricing on high-demand dates. This means the listed face value can rise or fall in real time according to how a particular show is selling, so two buyers purchasing the same tier on different days may pay different amounts. Premium and platinum seats are priced this way most often, while standard sections tend to move less. Resale listings add a further layer of variation, sitting above or below face value depending on the date and city.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are SZA tickets?',
          'a' => 'SZA tickets generally range from about 60 USD for upper-level and rear seats to around 1,200 USD for premium and VIP packages. Mid-tier seating tends to fall between 110 and 220 USD, with floor and lower-front sections higher again. The exact figure depends on the venue, the city and how the specific date is selling.',
        ),
        1 => 
        array (
          'q' => 'Why do SZA ticket prices change?',
          'a' => 'Most of SZA\'s major dates run through Ticketmaster and Live Nation, which apply dynamic pricing to in-demand shows. Face value can shift in real time based on demand, so the same seating tier may cost different amounts on different days. Premium and platinum seats are most affected, while standard sections move less.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see SZA live?',
          'a' => 'The lowest-priced SZA seats are usually in the upper level or rear sections, often starting around 60 to 110 USD. These offer a complete view of the stage and screens from a greater distance. Buying early, before higher tiers sell through and dynamic pricing lifts the remaining stock, tends to give the best value.',
        ),
        3 => 
        array (
          'q' => 'Are SZA VIP packages worth it?',
          'a' => 'SZA VIP packages, typically from around 450 to 1,200 USD, suit fans who want premium viewing areas, early entry or exclusive merchandise. Whether they represent good value depends on the inclusions, which vary by tour and city. Buyers wanting only a good seat may prefer a standard floor ticket at a lower cost.',
        ),
        4 => 
        array (
          'q' => 'How much are SZA floor tickets?',
          'a' => 'Floor and lower-front seats at SZA shows usually run from about 220 to 450 USD. These sections place you close to the stage and tend to sell quickly on popular dates, so prices rise as availability falls. Exact figures depend on the venue layout and the city.',
        ),
        5 => 
        array (
          'q' => 'Do SZA ticket prices go down closer to the show?',
          'a' => 'Sometimes. On dates with remaining inventory, resale listings can soften in the final days before a SZA show, but high-demand nights often hold firm or climb. Because dynamic pricing and resale both fluctuate, there is no guarantee that waiting will lower the cost, and popular seats may sell out entirely.',
        ),
        6 => 
        array (
          'q' => 'Are SZA tickets expensive?',
          'a' => 'SZA tickets cover a broad range rather than a single price. Upper-level seats are relatively accessible from around 60 USD, while floor sections and VIP packages can reach 1,200 USD. Because she now plays stadiums and large arenas, the spread between the cheapest and most expensive seats on a given night is wide.',
        ),
        7 => 
        array (
          'q' => 'What does SZA general admission cost?',
          'a' => 'General admission and standing-floor access at SZA shows commonly starts in the lower-to-mid hundreds, often from around 110 to 250 USD depending on the venue. Where GA places you in a standing pit near the stage, it can sit higher than equivalent seated sections owing to its proximity and demand.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Grand National Tour',
      'intro' => 
      array (
        0 => 'SZA\'s most recent large-scale outing was the Grand National Tour, a co-headline stadium run alongside Kendrick Lamar that played North America, Europe and Australia. For SZA fans tracking tour dates, this marked a step up from her earlier solo arena touring into full stadium venues, with an extended solo segment built into the wider show. Any new SZA dates announced will appear once confirmed by the artist and her promoters.',
        1 => 'Before the Grand National Tour, SZA headlined the SOS Tour in support of her second album, playing arenas across North America and Europe. Her live schedule tends to follow album cycles, so tour dates typically cluster around new releases and festival seasons. The most reliable way to confirm an upcoming SZA appearance is to check the official listing for the specific city and date you have in mind.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is SZA touring in 2026?',
          'a' => 'SZA\'s most recent major tour was the Grand National Tour with Kendrick Lamar, which ran through 2025. She has since announced a solo headline run, The LANA Tour, though dates depend on official confirmation from the artist and her promoters. Touring schedules generally follow album cycles, so confirmed dates are best checked against the official listing for your city.',
        ),
        1 => 
        array (
          'q' => 'What is SZA\'s current tour called?',
          'a' => 'SZA\'s most recent completed tour is the Grand National Tour, a co-headline stadium run with Kendrick Lamar that featured an extended solo segment from SZA. She has since announced a solo headline run, The LANA Tour, in support of the LANA reissue of her album SOS. New dates are confirmed by the artist when they are announced.',
        ),
        2 => 
        array (
          'q' => 'Did SZA tour with Kendrick Lamar?',
          'a' => 'Yes. SZA co-headlined the Grand National Tour with Kendrick Lamar, playing stadiums across North America, Europe and Australia. The show combined solo sets from each artist with their joint tracks, including collaborations the two have recorded together. It was SZA\'s largest-scale touring to date in terms of venue size.',
        ),
        3 => 
        array (
          'q' => 'Where does SZA usually perform?',
          'a' => 'SZA has moved from theatres and clubs early in her career to arenas on the SOS Tour and then stadiums on the Grand National Tour. Her venue scale now depends on the specific run, with co-headline stadium shows and solo arena dates both in recent history. Festival appearances also feature in her schedule between full tours.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'An SZA setlist draws heavily on her two studio albums, Ctrl and SOS, blending the slower R&B cuts that built her reputation with the bigger, more direct songs that now anchor her live shows. On recent tours she has paired material such as Kill Bill and Snooze with earlier favourites, and her stadium dates fold in collaborations recorded with other artists. The running order below reflects songs that have featured regularly rather than a fixed sequence.',
      ),
      'songs' => 
      array (
        0 => 'Kill Bill',
        1 => 'Snooze',
        2 => 'Good Days',
        3 => 'Love Galore',
        4 => 'The Weekend',
        5 => 'Broken Clocks',
        6 => 'Saturn',
        7 => 'Drew Barrymore',
        8 => 'Normal Girl',
        9 => 'Supermodel',
        10 => 'Prom',
        11 => 'Hit Different',
        12 => 'Nobody Gets Me',
        13 => 'Open Arms',
        14 => 'Ghost in the Machine',
        15 => 'Shirt',
        16 => 'Low',
        17 => 'Garden (Say It Like Dat)',
        18 => '30 for 30',
        19 => 'Doves in the Wind',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and SZA adjusts her song choices, segment length and any guest collaborations between dates, so the exact running order differs from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does SZA play live?',
          'a' => 'SZA\'s live shows lean on her albums Ctrl and SOS, with regulars including Kill Bill, Snooze, Good Days, Love Galore, The Weekend and Broken Clocks. Recent setlists also feature Saturn and Nobody Gets Me, alongside collaborations such as 30 for 30. The exact selection shifts between dates, so no two nights are identical.',
        ),
        1 => 
        array (
          'q' => 'Does SZA play Kill Bill at her concerts?',
          'a' => 'Yes. Kill Bill, one of SZA\'s best-known singles from the album SOS, has been a consistent fixture of her recent live shows and typically lands as a highlight of the set. As with any song, its placement in the running order can change from night to night, but it has featured regularly across her recent dates.',
        ),
        2 => 
        array (
          'q' => 'Does SZA do an encore?',
          'a' => 'SZA\'s recent stadium shows, including the co-headline Grand National Tour, have generally run as continuous sets rather than relying on a traditional encore break. Her solo arena dates have varied in format. Because she adjusts the structure between tours and venues, whether an encore appears depends on the specific show.',
        ),
        3 => 
        array (
          'q' => 'How long is an SZA concert?',
          'a' => 'A headline SZA set typically runs around 90 minutes to two hours, though her segment is shorter on co-headline dates where stage time is shared. The length depends on the type of show, the venue and whether collaborations or guest appearances feature. Set durations differ between tours, so timings vary from one date to the next.',
        ),
      ),
    ),
  ),
  'ariana-grande' => 
  array (
    'name' => 'Ariana Grande',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 65,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Ariana Grande ticket prices tend to sit in the upper arena bracket, reflecting her standing as one of pop\'s most in-demand live performers. Standard face value usually opens around the mid double figures for upper-tier seating and climbs toward the low hundreds for lower bowl and floor positions. As with most major arena runs, the headline figure you see first is the starting price for the cheapest available seats, not a flat rate across the building.',
        1 => 'Where you sit shapes the cost more than anything else with an Ariana Grande show. Production-heavy staging means sightlines vary, so closer floor and front-section seats carry a clear premium over the upper levels. VIP and packaged options sit well above standard admission, while verified resale can run higher still once primary allocation thins out. Reading each tier against the venue map before buying tends to be the most reliable guide.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / value seating',
          'desc' => 'Higher sections and seats further from the stage, generally the entry point for an Ariana Grande arena show. Expect roughly $65 to $130 at face value depending on the market and night. The trade-off is distance, though large video screens keep the staging visible from the upper bowl.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl',
          'desc' => 'Side and rear lower-level seating with stronger sightlines and easier access. These typically land around $135 to $250 at face value, balancing proximity against cost for buyers who want a closer view without paying floor prices.',
        ),
        2 => 
        array (
          'name' => 'Floor and premium',
          'desc' => 'Floor sections and prime lower-tier seats nearest the stage, the most sought-after standard tickets. Face value commonly runs from about $260 up to $450, and these clear quickest for popular dates.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP packages bundle a premium seat with extras such as lounge access, early entry and exclusive merchandise. Pricing sits above standard floor tickets, frequently in the $500 to $850 range, and the contents differ by package and city.',
        ),
      ),
      'why' => 'Ariana Grande\'s tours are routed through Ticketmaster and Live Nation, which apply dynamic pricing to many seats. That means the listed price for a given section can move up or down with demand rather than staying fixed from on-sale, so two buyers in similar seats may pay different amounts depending on timing. Verified resale adds a second layer: those prices are set by individual sellers and can exceed original face value, particularly for floors and sold-out nights. The bands here describe typical face-value ranges by section; the live figure shown at checkout reflects current demand for the specific date and venue.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Ariana Grande tickets?',
          'a' => 'Ariana Grande tickets generally start around $65 for upper-tier seats at face value and rise toward $450 for floor and premium positions, with VIP packages higher again. Exact prices depend on the venue, the night and dynamic pricing applied at the time of sale.',
        ),
        1 => 
        array (
          'q' => 'Why do Ariana Grande ticket prices change?',
          'a' => 'Most of her dates use Ticketmaster dynamic pricing, so seat costs shift with demand instead of holding a fixed rate. A section may be priced higher during peak interest and ease back later, which is why quoted figures vary between buyers and over time.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Ariana Grande live?',
          'a' => 'The lowest face-value option is usually upper-tier seating, often from around $65, bought through the official primary seller at on-sale. These sections sit furthest from the stage but rely on large video screens, and they tend to sell quickly for in-demand shows.',
        ),
        3 => 
        array (
          'q' => 'How much are Ariana Grande floor tickets?',
          'a' => 'Floor and premium lower-tier seats typically run from about $260 to $450 at face value, making them the most expensive standard tickets. On resale they can climb well beyond that for sold-out nights, since sellers set their own prices.',
        ),
        4 => 
        array (
          'q' => 'Are Ariana Grande VIP packages worth it?',
          'a' => 'VIP packages cost more than standard floor seats, often $500 to $850, and bundle a premium ticket with extras like lounge access, early entry and exclusive merchandise. Whether they suit you depends on how much you value those additions over a regular seat.',
        ),
        5 => 
        array (
          'q' => 'Do Ariana Grande tickets get cheaper closer to the show?',
          'a' => 'Not reliably. Prices can soften on resale if a date is underselling, but high-demand Ariana Grande shows often hold firm or rise as the night approaches. Dynamic pricing means there is no guaranteed late discount on the primary market.',
        ),
        6 => 
        array (
          'q' => 'Why are resale Ariana Grande tickets more expensive than face value?',
          'a' => 'Resale prices are set by individual sellers rather than the venue, so they reflect what the market will pay. For sold-out Ariana Grande dates that frequently means a markup over original face value, especially for floor seats and limited-availability sections.',
        ),
        7 => 
        array (
          'q' => 'What affects the price of an Ariana Grande ticket most?',
          'a' => 'Seat location is the biggest factor, with floor and lower-bowl seats commanding more than the upper tier. Beyond that, the specific city, the night of the week and dynamic pricing demand all influence the final figure at checkout.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Eternal Sunshine Tour',
      'intro' => 
      array (
        0 => 'Ariana Grande\'s current run is The Eternal Sunshine Tour, built around her album of the same name and its deluxe edition. It marks her return to arena stages after an extended break from touring, with multiple-night residencies in several cities rather than a single date in each market. Tour dates are added and routed through official channels, so checking the listed schedule for your nearest city is the most dependable way to plan.',
        1 => 'The Eternal Sunshine Tour leans on a theatrical, multi-act staging concept rather than a straightforward greatest-hits format. Because demand for Ariana Grande shows is high, many dates sell through quickly and remaining availability often shifts to verified resale. Where a city holds several consecutive nights, comparing dates can occasionally reveal better seating, though this varies by venue and how each individual night is selling.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Ariana Grande touring in 2026?',
          'a' => 'Yes. Ariana Grande is on The Eternal Sunshine Tour, her return to live arena shows supporting the Eternal Sunshine album. The run spans North America and includes a London residency, with dates routed through official sellers, so check the current schedule for confirmed cities.',
        ),
        1 => 
        array (
          'q' => 'What is Ariana Grande\'s current tour called?',
          'a' => 'Her current tour is The Eternal Sunshine Tour, named after her 2024 album and its 2025 deluxe edition. The show is structured around that material alongside selections from across her catalogue, presented in a multi-act staged format.',
        ),
        2 => 
        array (
          'q' => 'How many nights does Ariana Grande play in each city?',
          'a' => 'It varies by market. The Eternal Sunshine Tour favours multiple-night residencies in larger cities, with some venues hosting several consecutive shows, while smaller markets may receive a single date. The official schedule lists exactly how many nights each city has.',
        ),
        3 => 
        array (
          'q' => 'Does Ariana Grande tour often?',
          'a' => 'No, she tours relatively infrequently. The Eternal Sunshine Tour followed a multi-year gap since her previous major outing, which is part of why demand for dates runs high. When a run is announced, popular cities tend to sell out quickly.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Ariana Grande\'s setlist on The Eternal Sunshine Tour blends recent material from the Eternal Sunshine era with established hits drawn from across her catalogue. The show is staged in acts, opening with newer tracks before working through fan favourites from Positions, Thank U, Next, Dangerous Woman and her earlier records. The running order below reflects a recent performance and offers a reliable guide to what tends to feature live.',
      ),
      'songs' => 
      array (
        0 => 'yes, and?',
        1 => 'positions',
        2 => 'dandelion',
        3 => 'the boy is mine',
        4 => 'eternal sunshine',
        5 => 'just like magic',
        6 => 'thank u, next',
        7 => '7 rings',
        8 => 'imperfect for you',
        9 => 'warm',
        10 => 'safety net',
        11 => 'one last time',
        12 => 'rain on me',
        13 => 'break free',
        14 => 'past life',
        15 => 'dangerous woman',
        16 => 'honeymoon avenue',
        17 => 'into you',
        18 => 'we can\'t be friends (wait for your love)',
        19 => 'supernatural',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and running order on any given date may differ from this list. Ariana Grande has favoured a consistent set on this tour, but inclusions, transitions and surprises can change between shows.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Ariana Grande play live?',
          'a' => 'Ariana Grande\'s live set mixes Eternal Sunshine tracks such as yes, and? and we can\'t be friends with established hits like 7 rings, thank u, next, supernatural and into you. The show moves through her newer material before reaching catalogue favourites.',
        ),
        1 => 
        array (
          'q' => 'Does Ariana Grande do an encore?',
          'a' => 'On The Eternal Sunshine Tour her show runs as a continuous staged performance, typically closing into a video outro rather than a traditional encore break with the lights up. The set is built to flow through to its ending without an extended pause.',
        ),
        2 => 
        array (
          'q' => 'Does Ariana Grande play her old songs live?',
          'a' => 'Yes. Alongside Eternal Sunshine material, the set features older songs including dangerous woman, into you, break free, one last time and honeymoon avenue, reaching back across her earlier albums rather than focusing only on recent releases.',
        ),
        3 => 
        array (
          'q' => 'How long is an Ariana Grande concert?',
          'a' => 'A typical Ariana Grande headline show runs in the region of two hours, covering roughly twenty or more songs across a multi-act staging. Exact length varies by night depending on the running order and production for that date.',
        ),
      ),
    ),
  ),
  'harry-styles' => 
  array (
    'name' => 'Harry Styles',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 50,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Harry Styles ticket prices sit at the higher end of the pop market because his shows are staged in major stadiums and arenas rather than smaller venues. As a rough guide, entry-level seats tend to open around 50 to 90 USD, while general standing and lower-tier seating usually fall in the mid hundreds. Premium and front-of-stage positions can reach four figures, and the exact figure you see depends on the city, the night and how the seating map is configured.',
        1 => 'Because demand for Harry Styles consistently outstrips supply, the price you pay rarely matches a single fixed figure. Most tickets are sold through dynamic pricing, so the same section can read differently from one visit to the next. Treating the bands below as a realistic spread rather than a quote is the sensible approach, and it helps to compare a few seating areas before deciding where the balance of view and cost works best for you.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, typically 50 to 110 USD. These seats sit high or far from the stage but give a full view of the production, which for a Harry Styles show is a large part of the experience.',
        ),
        1 => 
        array (
          'name' => 'Mid-level seated',
          'desc' => 'Side and middle-bowl seating, usually 110 to 250 USD. A balanced choice with a clear sightline to the main stage and reduced reliance on the video screens.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and floor seats',
          'desc' => 'Closer seated positions near the stage, roughly 250 to 450 USD. These sell quickly and tend to be among the first to move into dynamic pricing during high demand.',
        ),
        3 => 
        array (
          'name' => 'General admission standing',
          'desc' => 'Floor standing nearest the stage and the B-stage, commonly 200 to 400 USD depending on the city. The closest atmosphere to the performance, though prices rise sharply when a date is in heavy demand.',
        ),
        4 => 
        array (
          'name' => 'VIP and premium packages',
          'desc' => 'Official hospitality, early entry and prime positions, generally 450 to 1,200 USD or more. These bundle perks such as merchandise and dedicated areas, so the headline figure covers more than the seat alone.',
        ),
      ),
      'why' => 'Harry Styles tours use dynamic pricing, the system Ticketmaster and Live Nation apply to most major stadium and arena runs. Under it, the cost of a section moves with live demand rather than staying fixed, so popular nights and the best-placed seats can climb well above the starting figure, sometimes within minutes of going on sale. Official resale and verified secondary listings add further variation, and packages carry a premium for their extras. This is why a single quoted price is unreliable and the bands above are framed as a range.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Harry Styles tickets?',
          'a' => 'Harry Styles tickets generally range from about 50 USD for upper-tier seats to 1,200 USD or more for VIP and premium packages, with most mid-level seating landing in the low-to-mid hundreds. Because his shows are staged in stadiums and arenas, the spread is wider than for theatre acts, and dynamic pricing means the figure shifts with demand on each night.',
        ),
        1 => 
        array (
          'q' => 'Why are Harry Styles tickets so expensive?',
          'a' => 'Prices are high largely because demand far exceeds the number of seats, and most tickets are sold through dynamic pricing that rises as a section fills. Harry Styles plays large stadiums and arena residencies with elaborate staging, which lifts production costs, and the best floor and lower-bowl positions are limited, so they reach the upper end of the range quickly.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Harry Styles live?',
          'a' => 'The cheapest reliable option is an upper-tier or rear seated ticket bought at the original on-sale, often around 50 to 110 USD. These give a complete view of the stage and production. Waiting for verified resale can occasionally surface lower prices closer to a date, but this carries no guarantee and is unpredictable.',
        ),
        3 => 
        array (
          'q' => 'Are Harry Styles VIP packages worth it?',
          'a' => 'VIP packages, usually 450 USD upward, suit fans who want early entry, prime positions and extras such as exclusive merchandise. They are not essential to enjoy the show, since the production reads well from most of the venue, but they remove some of the queuing and uncertainty around securing a good spot.',
        ),
        4 => 
        array (
          'q' => 'Does Harry Styles use dynamic pricing?',
          'a' => 'Yes. Like most major Ticketmaster and Live Nation tours, Harry Styles dates use dynamic pricing, so the cost of a given section changes with live demand. This is why the same seats can appear at different prices during a single on-sale, and why high-demand nights move toward the top of the range fastest.',
        ),
        5 => 
        array (
          'q' => 'How much do Harry Styles floor tickets cost?',
          'a' => 'Floor tickets, whether seated lower-bowl or general admission standing, typically run from around 200 to 450 USD depending on the city and the night. These positions are closest to the stage and the B-stage, so they are among the first to sell and the most affected by dynamic pricing during peak demand.',
        ),
        6 => 
        array (
          'q' => 'Do Harry Styles ticket prices vary by city?',
          'a' => 'Yes. Prices differ between cities because of venue size, local demand and how each seating map is arranged. A residency night in a major market can price differently from a single stadium date elsewhere, so it is worth comparing the same tier across cities rather than assuming a uniform figure.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Harry Styles tickets on resale?',
          'a' => 'Not usually. Resale prices for Harry Styles often sit above face value because of strong demand, and verified secondary listings can reach several times the original. Occasionally prices soften close to a date as sellers offload spare tickets, but buying at the original on-sale is generally the more economical route.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Together, Together Tour',
      'intro' => 
      array (
        0 => 'Harry Styles is on the road with the Together, Together Tour, his first major touring run since the multi-year Love On Tour. The trek is built around stadium dates and extended residencies in a handful of cities rather than a conventional date-per-city routing, headlined by a 30-night run at New York\'s Madison Square Garden, with further stops spanning Europe, the Americas and Australia. Tour dates and on-sale details are confirmed per city, so checking the city you intend to travel to is the most reliable way to plan.',
        1 => 'The Together, Together Tour supports Harry Styles\'s latest studio album, Kiss All The Time. Disco, Occasionally., alongside the catalogue that defined his earlier solo records. As with previous runs, the staging leans on a central stage and a secondary B-stage that brings him closer to the standing floor. Because the routing concentrates on multi-night residencies in major venues, ticket availability for a given city can move quickly once a run goes on sale.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Harry Styles touring in 2026?',
          'a' => 'Yes. Harry Styles is touring on the Together, Together Tour, which runs through 2026 across Europe, South America, North America and Australia. The schedule favours multi-night residencies in a small set of cities rather than a single date in each, including a 30-night stand at Madison Square Garden, so the number of stops is smaller but the run at each venue is longer.',
        ),
        1 => 
        array (
          'q' => 'What is Harry Styles\'s current tour called?',
          'a' => 'His current run is the Together, Together Tour, the successor to Love On Tour. It is structured around stadium shows and extended city residencies, and it accompanies his most recent studio album, Kiss All The Time. Disco, Occasionally., alongside earlier solo material.',
        ),
        2 => 
        array (
          'q' => 'Where is Harry Styles playing on the Together, Together Tour?',
          'a' => 'The Together, Together Tour visits a concentrated set of cities, including Amsterdam, London, Sao Paulo, Mexico City, New York, Melbourne and Sydney, with extended residencies rather than one-off dates in most markets. Because the routing is city-focused, it is best to check the specific city you plan to attend for confirmed dates and venues.',
        ),
        3 => 
        array (
          'q' => 'How long is a Harry Styles concert?',
          'a' => 'A Harry Styles show on the Together, Together Tour runs roughly two hours, built around five sections that move between the main stage and a secondary B-stage. The set blends recent album tracks with established solo singles, and exact running times vary slightly by night.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'The Harry Styles setlist on the Together, Together Tour draws heavily on his solo catalogue, mixing tracks from his latest album with the singles that have anchored his shows since the start of his solo career. The performance is split into five sections that move between the main stage and a B-stage, and the running order has stayed broadly consistent across early dates while leaving room for occasional changes. The list below reflects the songs that have featured as regular staples.',
      ),
      'songs' => 
      array (
        0 => 'Are You Listening Yet?',
        1 => 'Golden',
        2 => 'Adore You',
        3 => 'Watermelon Sugar',
        4 => 'Music for a Sushi Restaurant',
        5 => 'Taste Back',
        6 => 'Coming Up Roses',
        7 => 'Fine Line',
        8 => 'Italian Girls',
        9 => 'American Girls',
        10 => 'Keep Driving',
        11 => 'Ready Steady Go!',
        12 => 'Dance No More',
        13 => 'Treat People With Kindness',
        14 => 'Pop',
        15 => 'Season 2 Weight Loss',
        16 => 'Carla\'s Song',
        17 => 'Aperture',
        18 => 'Matilda',
        19 => 'Sign of the Times',
      ),
      'encore' => 
      array (
        0 => 'Matilda',
        1 => 'Sign of the Times',
        2 => 'As It Was',
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and running order can change between shows on the Together, Together Tour. The list above reflects the regular staples rather than a fixed running order for any single date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Harry Styles play live?',
          'a' => 'On the Together, Together Tour, Harry Styles regularly performs staples such as As It Was, Sign of the Times, Watermelon Sugar, Adore You, Golden and Music for a Sushi Restaurant, alongside new material like Aperture and other tracks from Kiss All The Time. Disco, Occasionally. The set runs to around twenty songs and mixes his newest album with singles from across his solo career.',
        ),
        1 => 
        array (
          'q' => 'Does Harry Styles do an encore?',
          'a' => 'Yes. Harry Styles closes his shows with an encore, and on the Together, Together Tour it has typically featured Matilda, Sign of the Times and As It Was. The exact encore can vary by night, but these have served as the regular closing run.',
        ),
        2 => 
        array (
          'q' => 'Does Harry Styles play new album songs live?',
          'a' => 'Yes. Roughly half the Together, Together Tour setlist is drawn from his latest album, Kiss All The Time. Disco, Occasionally., including the single Aperture and tracks such as Italian Girls, American Girls and Season 2 Weight Loss, performed alongside established solo singles. As with the rest of the show, the exact selection can vary from one night to another.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Harry Styles perform?',
          'a' => 'A Harry Styles show on the Together, Together Tour runs to around twenty songs across roughly two hours, divided into five sections that move between the main stage and a B-stage. The balance leans toward recent album material with established singles spread through the set and saved for the close.',
        ),
      ),
    ),
  ),
  'chappell-roan' => 
  array (
    'name' => 'Chappell Roan',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 320,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Chappell Roan ticket prices tend to sit in the upper-middle pop bracket, reflecting an act that has moved quickly from clubs to large arenas and festival headline slots. As a general guide, standard seated and general-admission tickets usually open in the lower part of the band, while premium floor positions and packages reach the higher end. Exact figures vary by city, venue size and how much demand a given night attracts.',
        1 => 'Because Chappell Roan shows often sell briskly, the gap between the cheapest upper-tier seat and a front-of-stage package can be wide. The ranges here describe how pricing typically behaves across a tour rather than any single announced date. Where a show uses general admission rather than reserved seating, standing tickets are usually priced as one flat tier, with seated and accessible options listed separately.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seated',
          'desc' => 'The most affordable way in, usually around 75 to 120 USD before fees. These are higher or further-back reserved seats with a full view of the stage, the tier that tends to clear first on popular nights.',
        ),
        1 => 
        array (
          'name' => 'General admission standing',
          'desc' => 'Floor or pit standing, commonly around 100 to 160 USD. Pricing is typically flat regardless of where you end up once inside, and early arrival is the main way to get close to the stage.',
        ),
        2 => 
        array (
          'name' => 'Lower tier and side seated',
          'desc' => 'Closer reserved seating with sightlines toward the front, generally in the 130 to 200 USD region depending on venue and proximity to the stage.',
        ),
        3 => 
        array (
          'name' => 'Premium and front block',
          'desc' => 'Prime floor seats or front standing access, often 180 to 260 USD. These positions are limited and tend to carry the steepest face values on high-demand dates.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Bundled experiences that may include early entry, a dedicated viewing area and merchandise, typically from around 250 up to 320 USD. Contents differ by promoter and city, so the inclusions are worth checking before buying.',
        ),
      ),
      'why' => 'Chappell Roan tours are routed through Ticketmaster and Live Nation, which generally means dynamic pricing on at least part of the inventory. Under that model the listed price of a ticket can move up or down with real-time demand rather than staying fixed, so two seats in the same section can carry different prices depending on when they are bought. Roan has publicly pushed to keep face values reasonable and to limit resale mark-ups, but high demand still pushes premium and limited-allocation tickets toward the top of the band. The figures here are typical face-value guidance; verified resale can sit well above them.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Chappell Roan tickets?',
          'a' => 'Chappell Roan tickets generally range from about 75 USD for upper-tier seats to roughly 320 USD for VIP packages, before fees. Most standard seated and general-admission tickets fall in the 75 to 160 USD area, with premium floor and front-block positions sitting higher. Prices vary by city, venue and demand on the night.',
        ),
        1 => 
        array (
          'q' => 'Why do Chappell Roan ticket prices change so much?',
          'a' => 'Her tours typically run on Ticketmaster and Live Nation systems that use dynamic pricing, so part of the inventory shifts with live demand rather than holding a fixed price. A popular date can see the same section priced higher than a quieter one, which is the main reason quoted figures move between shows.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Chappell Roan live?',
          'a' => 'The lowest face values are usually upper-tier or rear reserved seats, often starting around 75 to 120 USD. On general-admission shows, standing tickets are a single flat price, so arriving early is the way to get closer without paying more. These cheaper tiers tend to sell first on in-demand nights.',
        ),
        3 => 
        array (
          'q' => 'Are Chappell Roan VIP packages worth it?',
          'a' => 'VIP packages, generally from about 250 to 320 USD, bundle extras such as early entry, a dedicated viewing area or merchandise. Whether they are worthwhile depends on the specific inclusions, which differ by city and promoter, so it is worth reading the package details before deciding rather than assuming a standard offering.',
        ),
        4 => 
        array (
          'q' => 'Does Chappell Roan use dynamic pricing?',
          'a' => 'Yes, like most major Ticketmaster and Live Nation tours, Chappell Roan shows commonly apply dynamic pricing to some tickets. This means a portion of seats are priced in response to demand at the moment of sale, so face values can rise on busy dates and ease on slower ones.',
        ),
        5 => 
        array (
          'q' => 'How much do Chappell Roan floor tickets cost?',
          'a' => 'Floor access, whether premium seated or front standing, typically runs from around 130 to 260 USD depending on the venue and how close the position is to the stage. General-admission floor standing is usually a flat price, while reserved premium floor seats sit toward the upper end of the band.',
        ),
        6 => 
        array (
          'q' => 'Are Chappell Roan tickets cheaper than other pop headliners?',
          'a' => 'Roan plays arenas and festival headline slots rather than full stadium runs, so face values generally sit below the very top stadium acts while remaining a mainstream pop price. Standard tickets in the 75 to 160 USD range are broadly comparable to other arena-level pop tours rather than the highest stadium tiers.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Chappell Roan tickets on resale?',
          'a' => 'Not usually. Demand for Chappell Roan shows tends to push verified resale prices above face value rather than below it, especially for closer positions. The most reliable way to pay near face value is through the primary on-sale; resale is better seen as a fallback when official allocation has gone.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Visions of Damsels & Other Dangerous Things Tour',
      'intro' => 
      array (
        0 => 'Chappell Roan\'s most recent headline run is the Visions of Damsels & Other Dangerous Things Tour, a large-scale arena and festival production that followed the breakout success of her debut album. Tour dates for it have spanned international festival slots and headline arena shows across multiple continents. Because routing and on-sale timing differ by region, the way to confirm current dates is always the official listing for your country rather than older schedules.',
        1 => 'When Chappell Roan tour dates are announced, they typically move through standard presale and general on-sale stages via Ticketmaster and Live Nation. Festival appearances are booked separately from her own headline shows, so a city may see her on a festival bill in one year and a standalone arena date in another. Checking the official artist page is the most reliable way to see which format applies near you.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Chappell Roan touring in 2026?',
          'a' => 'Chappell Roan\'s Visions of Damsels & Other Dangerous Things Tour extended into early 2026 with festival and headline dates, including headlining Laneway Festival across Australia and New Zealand. Whether further shows are added beyond that depends on new announcements, so the official Live Nation and Ticketmaster listings are the place to confirm current and upcoming dates for your region.',
        ),
        1 => 
        array (
          'q' => 'What is Chappell Roan\'s current tour called?',
          'a' => 'Her most recent headline tour is the Visions of Damsels & Other Dangerous Things Tour, which followed her earlier Midwest Princess Tour. It combined headline arena shows with major festival appearances, and the name has been used consistently across the run\'s promotional materials and ticketing.',
        ),
        2 => 
        array (
          'q' => 'How do I get Chappell Roan tour dates and presale access?',
          'a' => 'Tour dates and presale codes are released through official channels, usually Ticketmaster, Live Nation and the artist\'s mailing list. Presales typically run ahead of the general on-sale, so registering interest in advance and following the official artist page is the most reliable way to access tickets early.',
        ),
        3 => 
        array (
          'q' => 'Does Chappell Roan play festivals as well as her own shows?',
          'a' => 'Yes. Alongside headline arena dates, Chappell Roan regularly appears as a festival headliner, and these slots are ticketed through each festival rather than as standalone concerts. A festival set is usually shorter than a full headline show, so the format affects both setlist length and how tickets are sold.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Chappell Roan setlist draws heavily on her debut album alongside her later standalone singles, balancing high-tempo pop with a few slower moments. Recent shows have opened with bold, up-tempo numbers before working through fan favourites and closing on her biggest singalongs. The running order shifts between headline shows and shorter festival sets, so the exact sequence on any given night is not fixed even when the core songs recur.',
      ),
      'songs' => 
      array (
        0 => 'Super Graphic Ultra Modern Girl',
        1 => 'Femininomenon',
        2 => 'After Midnight',
        3 => 'Naked in Manhattan',
        4 => 'Guilty Pleasure',
        5 => 'Casual',
        6 => 'The Subway',
        7 => 'Hot To Go!',
        8 => 'Picture You',
        9 => 'Love Me Anyway',
        10 => 'The Giver',
        11 => 'Red Wine Supernova',
        12 => 'Coffee',
        13 => 'Good Luck, Babe!',
        14 => 'My Kink Is Karma',
        15 => 'California',
        16 => 'Pink Pony Club',
      ),
      'encore' => 
      array (
        0 => 'Pink Pony Club',
      ),
      'note' => 'Setlists vary night to night, and festival sets are usually shorter than headline shows, so the order and inclusion of individual songs change from date to date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Chappell Roan play live?',
          'a' => 'Chappell Roan\'s live sets centre on debut-album tracks such as Femininomenon, Red Wine Supernova, Casual, Naked in Manhattan and Hot To Go!, alongside standalone singles including Good Luck, Babe!, The Giver and The Subway. Pink Pony Club typically anchors the end of the show as the biggest singalong of the night.',
        ),
        1 => 
        array (
          'q' => 'Does Chappell Roan do an encore?',
          'a' => 'Roan\'s headline shows have often closed with Pink Pony Club as the final, climactic moment, sometimes framed as an encore and sometimes as a continuous set finish. Festival appearances are shorter and may run straight through without a separate encore, so the format depends on the type of show.',
        ),
        2 => 
        array (
          'q' => 'Does Chappell Roan play any covers live?',
          'a' => 'On the Visions of Damsels & Other Dangerous Things Tour she has performed a cover of Heart\'s 1977 song Barracuda. Covers are not a fixed part of every set, so whether one appears can vary by date, and the bulk of any given show stays focused on her own material.',
        ),
        3 => 
        array (
          'q' => 'Does Chappell Roan play Pink Pony Club at every show?',
          'a' => 'Pink Pony Club is the most consistent fixture of her live shows and usually closes the main set as the central singalong. While setlists change night to night, this song is among the most reliable to expect, particularly at headline dates where the full production is in place.',
        ),
      ),
    ),
  ),
  'lana-del-rey' => 
  array (
    'name' => 'Lana Del Rey',
    'genre' => 'Alt-Pop',
    'prices' => 
    array (
      'currency' => 'USD',
      'range_low' => 75,
      'range_high' => 450,
      'intro' => 
      array (
        0 => 'Lana Del Rey ticket prices sit in a fairly wide band because her shows span large arenas and, more recently, full stadiums, where seating quality varies considerably from the back of the upper tier to the floor. Standard tickets generally start in the lower three figures, while closer seated sections and pit access run higher. As with most arena and stadium acts, the cleanest pricing tends to appear when a run first goes on general sale rather than later through resale.',
        1 => 'Because Lana Del Rey plays infrequently and demand consistently outstrips supply, her face-value ticket prices skew toward the upper end of comparable alt-pop and indie acts. The figures below describe how her pricing typically works across venue tiers rather than any single confirmed date, since live on-sale prices are set per show and per market and are listed separately by the system.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'Seats in the higher levels and behind-stage sections, generally around 75 to 130 USD. These offer the full production at the lowest cost but with the most distant sightlines, which matters at stadium scale.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and lower bowl',
          'desc' => 'Side and rear floor-level seating plus the better-placed lower bowl, usually in the 130 to 230 USD range. A common middle choice that balances proximity against price.',
        ),
        2 => 
        array (
          'name' => 'Front sections and standing pit',
          'desc' => 'Front blocks, premium seated areas and general-admission standing pits, typically 230 to 350 USD. Standing pit demand is high given the intimacy of her staging.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages bundling premium placement, early entry, merchandise or lounge access, often 350 to 450 USD or higher depending on the market and inclusions.',
        ),
      ),
      'why' => 'Lana Del Rey\'s tours are sold through Ticketmaster and Live Nation, which apply dynamic pricing on many in-demand sections. This means the listed price can move up or down with real-time demand rather than staying fixed, so identical seats may cost more during peak buying windows. Standard face value is set first, but Platinum and dynamically priced inventory can climb well above it on her most sought-after dates. Final prices also depend on the venue, city and any added service fees.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Lana Del Rey tickets?',
          'a' => 'Lana Del Rey tickets generally range from around 75 USD for upper-tier seats to roughly 450 USD for VIP and hospitality packages. Mid-tier seating tends to land between 130 and 230 USD, while front sections and standing pit access run higher. Exact prices vary by venue, city and demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Lana Del Rey tickets so expensive?',
          'a' => 'Lana Del Rey tours rarely and demand runs high, so prices skew upward, and Ticketmaster\'s dynamic pricing pushes sought-after sections above standard face value. Her recent move to stadiums has not eased this, since pit and premium inventory remains limited relative to the audience wanting it.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Lana Del Rey live?',
          'a' => 'The cheapest seats are typically in the upper tiers or behind-stage sections, often starting near 75 USD at general on-sale. Buying during the initial sale rather than through resale, and avoiding dynamically priced premium blocks, usually keeps costs lowest.',
        ),
        3 => 
        array (
          'q' => 'Does Lana Del Rey use dynamic pricing?',
          'a' => 'Yes. Her shows are sold via Ticketmaster and Live Nation, which apply dynamic pricing to many sections. Prices on those tickets shift with live demand, so the same seat can cost noticeably more during peak buying periods than at quieter moments.',
        ),
        4 => 
        array (
          'q' => 'How much do Lana Del Rey VIP packages cost?',
          'a' => 'Official VIP and hospitality packages for Lana Del Rey commonly run from about 350 USD upward, sometimes well beyond it. They bundle extras such as premium placement, early entry, lounge access or merchandise, with the exact price depending on the market and what each package includes.',
        ),
        5 => 
        array (
          'q' => 'Are Lana Del Rey pit or standing tickets worth it?',
          'a' => 'Standing pit and front sections, usually 230 to 350 USD, put fans closest to the stage, which suits her intimate staging. Whether they are worth it depends on budget and tolerance for standing, since the same production is visible, if more distant, from cheaper seated areas.',
        ),
        6 => 
        array (
          'q' => 'Do Lana Del Rey ticket prices change over time?',
          'a' => 'They can. Standard face value is set at on-sale, but dynamic and Platinum inventory moves with demand, so prices may rise as a date sells through. Resale prices fluctuate independently and often exceed face value for her higher-demand shows.',
        ),
        7 => 
        array (
          'q' => 'How much were Lana Del Rey tickets for her stadium shows?',
          'a' => 'For her recent stadium run, standard tickets generally started in the lower three figures, with premium seated areas, pit and packages rising into the hundreds. Stadium pricing tracks the same tiered, demand-led pattern as her arena dates, just across a larger seated footprint.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Lana Del Rey 2025 UK and Ireland Tour',
      'intro' => 
      array (
        0 => 'Lana Del Rey\'s most recent run was the 2025 UK and Ireland Tour, her first all-stadium outing, which played venues including Cardiff\'s Principality Stadium and London\'s Wembley Stadium across June and July. Tour dates for any new leg are announced per market and listed separately, so this page covers how her touring generally works rather than confirming specific upcoming shows.',
        1 => 'Lana Del Rey tours selectively, favouring concentrated runs over long global legs, which keeps each announcement closely watched. New tour dates typically surface alongside album activity and go on sale through Ticketmaster and Live Nation. Because she plays infrequently, demand for any fresh dates tends to be heavy, and shows in major cities often sell through quickly once general sale opens.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Lana Del Rey touring in 2026?',
          'a' => 'As of now, no official 2026 tour dates have been confirmed by Lana Del Rey\'s team, though rumours of new US and UK shows have circulated. Her last run was the 2025 UK and Ireland stadium tour. Any new dates are listed here as soon as they are officially announced.',
        ),
        1 => 
        array (
          'q' => 'What was Lana Del Rey\'s most recent tour?',
          'a' => 'Her most recent tour was the 2025 UK and Ireland Tour, her first to play stadiums throughout, including Cardiff\'s Principality Stadium and London\'s Wembley Stadium. It ran across late June and early July 2025 and previewed material from her forthcoming country-leaning album alongside catalogue staples.',
        ),
        2 => 
        array (
          'q' => 'How often does Lana Del Rey tour?',
          'a' => 'Lana Del Rey tours infrequently, typically in short concentrated runs rather than extended world legs, often timed around new album cycles. This selective approach means her announcements are limited and tend to draw heavy demand whenever fresh dates appear.',
        ),
        3 => 
        array (
          'q' => 'Where can I find Lana Del Rey tour dates?',
          'a' => 'Confirmed Lana Del Rey tour dates appear on this page once announced, drawn from official Ticketmaster and Live Nation listings. Because she tours selectively and dates are released per market, checking back around album announcements is the most reliable way to catch new shows early.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Lana Del Rey\'s setlists draw heavily on her catalogue staples while folding in newer, country-leaning material from her recent stadium shows. A typical set moves between sweeping ballads and her better-known singles, framed by cinematic staging. The running order below reflects songs she has regularly performed on recent dates rather than a fixed list, since her setlist changes between shows.',
      ),
      'songs' => 
      array (
        0 => 'Stars Fell on Alabama',
        1 => 'Henry, come on',
        2 => 'Chemtrails Over the Country Club',
        3 => 'Ultraviolence',
        4 => 'Ride',
        5 => 'Video Games',
        6 => 'Norman fucking Rockwell',
        7 => 'Arcadia',
        8 => 'Did you know that there\'s a tunnel under Ocean Blvd',
        9 => 'Young and Beautiful',
        10 => 'Summertime Sadness',
        11 => 'Born to Die',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, so the exact songs and running order differ from show to show; this reflects recent staples rather than a guaranteed list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Lana Del Rey play live?',
          'a' => 'Lana Del Rey\'s live sets centre on staples such as Video Games, Born to Die, Summertime Sadness, Young and Beautiful and Ride, mixed with newer country-leaning material like Henry, come on. The exact selection shifts between shows, but those signature songs appear regularly across recent dates.',
        ),
        1 => 
        array (
          'q' => 'Does Lana Del Rey play Video Games live?',
          'a' => 'Yes. Video Games, her breakthrough single, has remained a consistent fixture of Lana Del Rey\'s sets and frequently closes or anchors recent shows. As with any song, its inclusion is not guaranteed every night, since her setlist varies from date to date.',
        ),
        2 => 
        array (
          'q' => 'Does Lana Del Rey perform an encore?',
          'a' => 'Her recent stadium shows have generally run as a continuous set rather than a formal encore, often building to signature songs like Video Games and Summertime Sadness toward the end. Structure can differ between dates, so the closing sequence is not fixed.',
        ),
        3 => 
        array (
          'q' => 'How long is a Lana Del Rey concert?',
          'a' => 'A Lana Del Rey headline set typically runs around 90 minutes to two hours, depending on the show and any interludes, such as the hologram and spoken-word segments used on her recent stadium tour. Exact length varies by date and venue.',
        ),
      ),
    ),
  ),
  'katy-perry' => 
  array (
    'name' => 'Katy Perry',
    'genre' => 'Pop',
    'prices' => 
    array (
      'currency' => 'USD',
      'range_low' => 60,
      'range_high' => 400,
      'intro' => 
      array (
        0 => 'Katy Perry ticket prices generally sit in the mid-range bracket common to arena pop tours, reflecting the indoor venues she favours over larger stadium runs. Entry-level seats in the upper tiers tend to start lower, while floor positions and front-section seats carry a clear premium. Where a show is in high demand, the cheapest face-value tickets sell quickly, which tends to push the typical figure people pay above the headline starting price.',
        1 => 'The spread between the least and most expensive Katy Perry tickets is wide, driven by sightline, distance from the stage and any hospitality element attached to a seat. Standard general-admission or upper-level pricing anchors the lower end, and VIP packages with early entry or merchandise sit well above it. Knowing where a seat falls within the venue is the clearest guide to what you are likely to pay.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most affordable Katy Perry tickets, usually in the upper bowl or behind the stage, generally fall in the 60 to 110 USD band. Sightlines are more distant but the full production remains visible.',
        ),
        1 => 
        array (
          'name' => 'Lower level and side seating',
          'desc' => 'Mid-priced lower-bowl and side-section seats typically range from around 110 to 200 USD. These offer a closer, more central view without the floor premium.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Floor positions and front-of-house seats near the stage commonly run from roughly 200 to 300 USD, reflecting proximity to the performance and the catwalk staging she has used.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and hospitality packages, which may include premium seating, early entry, a merchandise item or lounge access, generally sit between 300 and 400 USD or higher depending on the inclusions.',
        ),
      ),
      'why' => 'Most of Katy Perry\'s arena dates are sold through Ticketmaster and Live Nation, which apply dynamic pricing on a large share of inventory. This means the listed price moves with real-time demand rather than staying fixed, so popular shows and prime seats can rise above their initial level and quieter dates may settle lower. Face value is also separate from booking, service and facility fees, which are added at checkout and vary by venue and market.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Katy Perry tickets?',
          'a' => 'Katy Perry tickets generally range from about 60 USD for upper-level seats to 400 USD or more for floor and VIP positions, placing her in the mid-range bracket typical of arena pop tours. The exact figure depends on the venue, the seat location and demand for the specific date, since dynamic pricing applies to much of the inventory.',
        ),
        1 => 
        array (
          'q' => 'Why do Katy Perry ticket prices change so much?',
          'a' => 'Prices change because most of her dates use dynamic pricing, where the cost of a seat moves with live demand rather than staying fixed. A high-demand date or a prime floor seat can climb well above the entry price, while quieter shows may settle nearer the lower end. Added booking and facility fees also widen the gap between face value and final cost.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Katy Perry live?',
          'a' => 'The cheapest Katy Perry tickets are normally the upper-level or rear-stage seats, which tend to start around 60 to 80 USD before fees. Buying early, when the broadest spread of inventory is still available, generally gives access to the lowest face-value seats before demand pushes prices upward.',
        ),
        3 => 
        array (
          'q' => 'Are Katy Perry VIP packages worth it?',
          'a' => 'Katy Perry VIP packages typically cost between 300 and 400 USD or more and bundle premium seating with extras such as early entry, a lounge or exclusive merchandise. Whether they represent value depends on how much the seating upgrade and inclusions matter to the individual buyer compared with a standard lower-level ticket.',
        ),
        4 => 
        array (
          'q' => 'Do Katy Perry tickets include fees?',
          'a' => 'The advertised face value usually does not include fees. Booking, service and facility charges are added at checkout and vary by venue and seller, so the final amount paid is higher than the headline price. Reviewing the full breakdown before purchase gives the clearest picture of the total cost.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Katy Perry?',
          'a' => 'Floor tickets for Katy Perry generally run from around 200 to 300 USD, with seats nearest the stage and any catwalk sitting at the upper end of that range. As floor inventory is limited and in demand, dynamic pricing can lift these figures further for sought-after shows.',
        ),
        6 => 
        array (
          'q' => 'Are Katy Perry tickets expensive compared to other pop artists?',
          'a' => 'Katy Perry sits in the mid-range for major pop acts. Her tours play arenas rather than stadiums, so her ticket band is generally below stadium headliners while remaining above smaller theatre and club acts. This places her pricing in line with comparable arena-scale pop performers.',
        ),
        7 => 
        array (
          'q' => 'Can Katy Perry ticket prices drop closer to the show?',
          'a' => 'Prices can move in either direction near a show date. Under dynamic pricing, unsold seats on quieter dates may ease, while in-demand shows tend to hold firm or rise. There is no guarantee of a late reduction, so buyers weighing a wait should balance potential savings against the risk of limited availability.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Lifetimes Tour',
      'intro' => 
      array (
        0 => 'Katy Perry\'s most recent major outing is The Lifetimes Tour, which supported her 2024 album 143 and ran across five continents through 2025. It was structured as an arena tour, with the staging divided into themed performance levels rather than the stadium scale of some of her peers. Anyone tracking Katy Perry tour dates should note that named tours run in defined cycles, and any further shows are typically announced as fresh legs or one-off appearances.',
        1 => 'The Lifetimes Tour grossed around 134 million USD across roughly 90 shows and stood as one of her highest-grossing tours to date. It blended material from 143 with the catalogue of singles that built her arena audience. When new Katy Perry tour dates appear, they are added by the system as they are confirmed, so this section covers how her touring generally works rather than any single fixed schedule.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Katy Perry touring in 2026?',
          'a' => 'Katy Perry\'s named run, The Lifetimes Tour, concluded its main schedule in late 2025, and any further 2026 appearances are confirmed individually rather than as a continuous tour. New shows and festival dates are listed by the system as they are announced, so the live schedule reflects whatever performances are currently on sale.',
        ),
        1 => 
        array (
          'q' => 'What is Katy Perry\'s current tour called?',
          'a' => 'Her most recent major tour is The Lifetimes Tour, named after a track from her 2024 album 143. It was an arena production that travelled across multiple continents through 2025. Any subsequent touring would carry its own name, announced separately from this run.',
        ),
        2 => 
        array (
          'q' => 'How long is a Katy Perry concert?',
          'a' => 'A Katy Perry headline show generally lasts around two hours, covering material from across her career alongside her latest album. The Lifetimes Tour used a multi-level stage concept with several costume and set changes, so the exact running time can vary slightly by production and venue.',
        ),
        3 => 
        array (
          'q' => 'Does Katy Perry play arenas or stadiums?',
          'a' => 'Katy Perry primarily tours arenas rather than stadiums, as seen on The Lifetimes Tour, which played indoor venues across its run. This keeps her audience capacities and ticket pricing in the arena bracket, with occasional larger outdoor or festival appearances scheduled separately.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Katy Perry setlist generally moves through her run of pop singles alongside newer album tracks, structured around the themed staging she used on The Lifetimes Tour. Recent shows have opened with material from 143 before working through the catalogue hits that fill her arena sets. The order and song choices shift between dates, and she has included a fan-voted segment, so the Katy Perry setlist on any given night is a guide rather than a fixed list.',
      ),
      'songs' => 
      array (
        0 => 'Artificial',
        1 => 'Chained to the Rhythm',
        2 => 'Teary Eyes',
        3 => 'Dark Horse',
        4 => 'Woman\'s World',
        5 => 'California Gurls',
        6 => 'Teenage Dream',
        7 => 'Hot n Cold',
        8 => 'Last Friday Night (T.G.I.F.)',
        9 => 'I Kissed a Girl',
        10 => 'Crush',
        11 => 'I\'m His, He\'s Mine',
        12 => 'Wide Awake',
        13 => 'The One That Got Away',
        14 => 'E.T.',
        15 => 'Part of Me',
        16 => 'Rise',
        17 => 'Roar',
        18 => 'Daisies',
        19 => 'Firework',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night. Katy Perry rotates her song selections, includes a fan-voted Choose Your Own Adventure segment, and adjusts the running order between shows, so any individual concert may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Katy Perry play live?',
          'a' => 'Katy Perry\'s live sets centre on her singles, including Roar, Firework, Dark Horse, California Gurls, Teenage Dream and Hot n Cold, mixed with newer tracks such as Woman\'s World and Lifetimes from her 2024 album. The exact selection changes between shows, with a fan-voted segment adding variety to the running order.',
        ),
        1 => 
        array (
          'q' => 'Does Katy Perry play Firework live?',
          'a' => 'Yes, Firework is a regular fixture in Katy Perry\'s live shows and has typically closed her recent sets, including on The Lifetimes Tour. As one of her best-known singles it remains a consistent part of the setlist, though its exact placement can vary by night.',
        ),
        2 => 
        array (
          'q' => 'Does Katy Perry do an encore?',
          'a' => 'Katy Perry\'s recent shows have largely run as a continuous performance rather than breaking for a separate encore, building to a finale with songs such as Firework. The structure can differ between tours and venues, so some dates may handle the closing section differently.',
        ),
        3 => 
        array (
          'q' => 'What is the Choose Your Own Adventure segment at Katy Perry shows?',
          'a' => 'On The Lifetimes Tour, Katy Perry included a Choose Your Own Adventure segment in which the audience voted to decide which older tracks she performed that night. This means deeper catalogue songs such as The One That Got Away or Not Like the Movies appear on some dates and not others, adding variation between shows.',
        ),
      ),
    ),
  ),
  'gracie-abrams' => 
  array (
    'name' => 'Gracie Abrams',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Gracie Abrams ticket prices tend to sit in the mid-range arena band rather than the stadium tier occupied by the largest pop acts. Across recent runs, standard seated and general admission tickets have generally opened in the lower part of the range, with premium floor positions and front sections priced higher. Because she plays arenas such as the Kia Forum and United Center rather than stadiums, face values stay more contained than they would for a stadium headliner.',
        1 => 'How much you pay for Gracie Abrams largely depends on where you sit and which market you buy in. Upper-level seats are the most affordable entry point, lower bowl and floor commands a premium, and official VIP packages add a further layer on top of the standard ticket. The figures here describe typical face-value bands; the live prices for any given show are pulled in separately and reflect that date\'s seating map and demand.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most economical way in, usually around 55 to 100 USD before fees. These are higher or further-back seats with a full view of the stage, suited to fans who want to be in the room without paying floor prices.',
        ),
        1 => 
        array (
          'name' => 'General admission standing',
          'desc' => 'Floor standing access on dates that offer it, broadly in the 80 to 130 USD area. Pricing reflects proximity to the stage and the appeal of a standing pit at an Abrams show, where the crowd singalong is part of the draw.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and premium seating',
          'desc' => 'Reserved seats closer to the action and in the lower tiers, typically around 120 to 220 USD. This band covers the better sightlines that sell quickly in arena configurations.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official packages such as the Daughter from Hell, Good Reason and Look At My Life Lounge tiers bundle a premium ticket with extras like early entry, merchandise or lounge access, generally landing in the 250 to 400 USD range depending on the inclusions and city.',
        ),
      ),
      'why' => 'Gracie Abrams\'s arena tours are promoted through Live Nation and sold via Ticketmaster, which means most North American dates use dynamic pricing. Under that model the face value of a ticket can move up or down with demand rather than staying fixed, so identical seats may cost different amounts at different points in a sale and popular shows can climb above the standard band. The ranges shown reflect typical face values across her arena run; the prices you see for a specific date are drawn in separately and account for that show\'s seating map, market and current demand, before any service fees are added at checkout.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Gracie Abrams tickets?',
          'a' => 'Gracie Abrams tickets typically run from about 55 USD for upper-level seats to around 400 USD for official VIP packages. As an arena act she sits in the mid-range pop band, below stadium headliners. General admission and lower-bowl seats fall in between, and exact prices depend on the city and seating section.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Gracie Abrams live?',
          'a' => 'The cheapest Gracie Abrams tickets are usually upper-level or rear seated sections, often starting near 55 to 100 USD before fees. Buying during a presale and choosing a less in-demand night in the run can also help, since dynamic pricing tends to push the most popular dates higher.',
        ),
        2 => 
        array (
          'q' => 'Does Gracie Abrams use dynamic pricing?',
          'a' => 'Yes. Most of Gracie Abrams\'s Ticketmaster and Live Nation arena dates use dynamic pricing, so face values can rise or fall with demand during a sale. This means the same seat may be listed at different prices at different times, and busier shows often cost more than the standard band suggests.',
        ),
        3 => 
        array (
          'q' => 'How much do Gracie Abrams VIP packages cost?',
          'a' => 'Gracie Abrams VIP packages generally fall in the 250 to 400 USD range. Tiers such as the Daughter from Hell, Good Reason and Look At My Life Lounge packages bundle a premium ticket with extras like early entry, exclusive merchandise or lounge access, with the exact price varying by package and city.',
        ),
        4 => 
        array (
          'q' => 'Are Gracie Abrams tickets expensive?',
          'a' => 'Gracie Abrams tickets are moderate by current pop standards. Because she headlines arenas rather than stadiums, her face values stay below acts like Taylor Swift or Coldplay. Upper-level seats are reasonably priced, while floor, lower-bowl and VIP options carry the higher end of the range.',
        ),
        5 => 
        array (
          'q' => 'Why do Gracie Abrams ticket prices vary so much?',
          'a' => 'Prices vary because of seating location, the market a show is in and dynamic pricing on her arena dates. Upper-level seats cost far less than the floor or lower bowl, and demand-based pricing can lift face values on the busiest nights, which is why the same tour can show a wide spread across cities.',
        ),
        6 => 
        array (
          'q' => 'Do Gracie Abrams ticket prices include fees?',
          'a' => 'The face values quoted here do not include service or processing fees, which Ticketmaster and other primary sellers add at checkout. Final costs can therefore run higher than the listed band, so it is worth viewing the full breakdown before completing a purchase.',
        ),
        7 => 
        array (
          'q' => 'Is general admission available for Gracie Abrams shows?',
          'a' => 'Many Gracie Abrams arena dates offer general admission standing on the floor, typically in the 80 to 130 USD area. GA puts you closer to the stage for the singalong moments her shows are known for, though it sells quickly and is not offered at every venue.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Look at My Life Tour',
      'intro' => 
      array (
        0 => 'Gracie Abrams\'s current run is The Look at My Life Tour, a large arena tour supporting her third studio album, Daughter from Hell. It marks a step up in scale from her earlier theatre and amphitheatre shows, with the schedule built around major arenas across North America, the United Kingdom and Europe. Tour dates are added and updated by venue, so the listings here reflect the confirmed arena routing for the run.',
        1 => 'The tour follows the lengthy Secret of Us touring cycle and pairs new material from Daughter from Hell with the songs that built her audience. For Gracie Abrams tour dates, the routing covers cities such as Denver, Los Angeles, Chicago, Brooklyn and London, with a rotating cast of support acts. Specific on-sale information and individual show dates are handled separately by the system rather than listed here.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Gracie Abrams touring in 2026?',
          'a' => 'Yes. Gracie Abrams is touring on The Look at My Life Tour, a 64-date arena run that opens in North America in late 2026 and continues through the United Kingdom and Europe into 2027. It supports her third album, Daughter from Hell, and visits major arenas across both continents.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Gracie Abrams\'s current tour?',
          'a' => 'Her current tour is The Look at My Life Tour. It is her fifth concert tour and the first staged primarily in arenas, released in support of the album Daughter from Hell, following the Secret of Us and Secret of Us Deluxe touring cycle.',
        ),
        2 => 
        array (
          'q' => 'What size venues does Gracie Abrams play?',
          'a' => 'Gracie Abrams plays arenas on The Look at My Life Tour, including venues such as Ball Arena in Denver, the Kia Forum in Los Angeles, the United Center in Chicago, Barclays Center in Brooklyn and The O2 in London. This is a larger scale than the theatres and amphitheatres of her earlier tours.',
        ),
        3 => 
        array (
          'q' => 'Who is opening for Gracie Abrams on tour?',
          'a' => 'Support on The Look at My Life Tour rotates by date and region, with artists such as Rachel Chinouriri, Holly Humberstone, Del Water Gap, Jensen McRae and The Japanese House among those announced for various shows. The opening act for any given night depends on the city, so it is worth checking the specific date.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Gracie Abrams setlist leans on The Secret of Us and her debut Good Riddance, threaded with material from Daughter from Hell as the new album moves into the live show. Recent shows have opened with Risk and built toward the singalong peaks of That\'s So True and Close to You, mixing full-band moments with quieter solo passages on guitar and piano.',
      ),
      'songs' => 
      array (
        0 => 'Risk',
        1 => 'Blowing Smoke',
        2 => '21',
        3 => 'I Love You, I\'m Sorry',
        4 => 'Where do we go now?',
        5 => 'Normal Thing',
        6 => 'Friend',
        7 => 'Mess It Up',
        8 => 'Packing It Up',
        9 => 'Full machine',
        10 => 'I Told You Things',
        11 => 'Let It Happen',
        12 => 'Tough Love',
        13 => 'Cool',
        14 => 'I miss you, I\'m sorry',
        15 => 'Right now',
        16 => 'Free Now',
        17 => 'us.',
      ),
      'encore' => 
      array (
        0 => 'That\'s So True',
        1 => 'Close to You',
      ),
      'note' => 'This reflects songs Gracie Abrams has regularly performed on recent tours. Setlists vary night to night, and she often includes a rotating surprise song along with new material, so the exact running order and song choices change from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Gracie Abrams play live?',
          'a' => 'Gracie Abrams\'s recent sets draw heavily on The Secret of Us and Good Riddance, with staples including Risk, I Love You I\'m Sorry, Where do we go now?, Tough Love, us. and That\'s So True. She also folds in newer Daughter from Hell material and a rotating surprise song, so the exact selection shifts between shows.',
        ),
        1 => 
        array (
          'q' => 'Does Gracie Abrams play an encore?',
          'a' => 'Yes. Gracie Abrams typically closes with an encore, which on recent tours has featured That\'s So True and Close to You, two of her biggest singalong moments. As with the main set, the encore can change from night to night.',
        ),
        2 => 
        array (
          'q' => 'Does Gracie Abrams play a surprise song each night?',
          'a' => 'She often includes a rotating surprise song, frequently performed solo on guitar or piano, which changes from city to city. Past examples have included Two People and Rockland, so part of the appeal of a given date is not knowing exactly which stripped-back song will appear.',
        ),
        3 => 
        array (
          'q' => 'How long is a Gracie Abrams concert?',
          'a' => 'A Gracie Abrams headline set generally runs around 90 minutes to two hours, depending on the venue and date, covering roughly 20 songs across the main set and encore. Support acts play beforehand, so the full evening is longer. The exact length and running order vary night to night.',
        ),
      ),
    ),
  ),
  'tate-mcrae' => 
  array (
    'name' => 'Tate McRae',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Tate McRae ticket prices tend to sit in the mid arena band rather than the stadium tier she has not yet reached. Across her recent run, face-value seats commonly opened in the region of 55 to 120 USD, with floor positions, premium blocks and VIP packages reaching higher. Because she plays arenas rather than open-air stadiums, the overall spread stays narrower than acts such as Taylor Swift or Coldplay, though demand for floor access still pushes the top of the band upward.',
        1 => 'What you pay for a Tate McRae ticket depends mainly on the venue, the seating level and how close to the stage you sit. Upper-tier and rear seats anchor the lower end, central lower-bowl and floor positions form the middle, and VIP or meet-and-greet bundles sit at the top. Resale figures vary widely by market and can run well above face value when a date sells quickly, so the figures here describe typical primary pricing rather than any single show.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, typically around 55 to 90 USD. These seats sit high in the bowl or behind the stage line, with a full view of the production but the longest sightline to the stage.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side floor',
          'desc' => 'Mid-range seating, commonly 90 to 160 USD. This covers lower-level sections and side floor positions that bring you closer to the stage while keeping a seated, fixed view.',
        ),
        2 => 
        array (
          'name' => 'Front floor and premium blocks',
          'desc' => 'Higher-demand positions near the stage or in designated premium sections, generally 160 to 250 USD. These sell quickly and account for much of the upper movement in pricing.',
        ),
        3 => 
        array (
          'name' => 'VIP and meet-and-greet packages',
          'desc' => 'Top of the range, usually 250 to 350 USD or more. Packages have included early entry, premium seating, exclusive merchandise and, on some dates, a photo opportunity, with exact contents varying by tour and city.',
        ),
      ),
      'why' => 'Tate McRae\'s tours are routed through Ticketmaster and Live Nation, which means most on-sales use dynamic pricing. Under that model the listed price moves with live demand rather than staying fixed, so popular dates and the best seats can climb above their starting figure during a busy on-sale and ease back when demand softens. Face value also varies by city, venue capacity and seating level, and resale prices sit separately again and can run higher still. Treat the bands here as a guide to typical primary pricing rather than a guaranteed rate for any given show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Tate McRae tickets?',
          'a' => 'Tate McRae tickets generally start around 55 USD for upper-tier seats and run to roughly 350 USD for VIP packages, with lower-bowl and floor seats falling in between. As an arena act her pricing stays below stadium headliners, though floor and premium blocks raise the top of the band. Exact figures depend on the venue, the city and how quickly the date sells.',
        ),
        1 => 
        array (
          'q' => 'Why do Tate McRae ticket prices change so much?',
          'a' => 'Most of her shows run on Ticketmaster and Live Nation dynamic pricing, so the listed cost rises and falls with live demand during an on-sale rather than holding a fixed figure. Add differences in venue size, seating level and city, plus separate resale listings, and the spread between the cheapest and dearest seats for the same tour can be wide.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Tate McRae live?',
          'a' => 'The lowest prices are usually upper-tier or behind-stage seats, often in the region of 55 to 90 USD at face value. Buying during the original on-sale rather than on resale, and choosing a less central position in the bowl, tends to keep the cost down. Prices still vary by market, so smaller-city dates can come in lower than major metros.',
        ),
        3 => 
        array (
          'q' => 'Do Tate McRae VIP packages cost more?',
          'a' => 'Yes. VIP and meet-and-greet bundles sit at the top of the range, commonly 250 to 350 USD or higher. They have included extras such as early entry, premium seating, exclusive merchandise and, on some dates, a photo opportunity. Contents and price differ by tour and venue, and these packages tend to sell out before standard seats.',
        ),
        4 => 
        array (
          'q' => 'Are Tate McRae floor tickets worth it?',
          'a' => 'Floor positions put you closest to the stage and the choreography-led production, which is why they carry higher prices, typically from around 160 USD upward for front sections. Side floor sits a little lower. Whether the cost is worthwhile depends on how much proximity matters to you versus the wider, often cheaper view from the lower bowl.',
        ),
        5 => 
        array (
          'q' => 'How much were Miss Possessive Tour tickets?',
          'a' => 'During her Miss Possessive Tour, her first arena run, face-value seats commonly opened in the 55 to 120 USD region, with floor and premium blocks higher and VIP packages reaching into the hundreds. Resale prices for in-demand dates ran well above that. Any future tour is likely to price in a similar arena band rather than at stadium level.',
        ),
        6 => 
        array (
          'q' => 'Is there a booking or service fee on Tate McRae tickets?',
          'a' => 'Most primary sellers add service and facility fees on top of the base ticket price, so the final checkout total is usually higher than the figure first shown. The exact amount depends on the venue and seller. It is worth checking whether the displayed price is all-in or pre-fee before completing a purchase.',
        ),
        7 => 
        array (
          'q' => 'Do Tate McRae ticket prices differ by city?',
          'a' => 'Yes. Larger metropolitan dates and higher-capacity arenas often price above smaller markets, and local demand affects how far dynamic pricing pushes the cost during the on-sale. The same seating level can therefore carry different figures from one stop to the next, which is why the bands here describe a typical range rather than a single fixed price.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Miss Possessive Tour',
      'intro' => 
      array (
        0 => 'Tate McRae\'s most recent headline run was the Miss Possessive Tour, the first arena tour of her career, staged in support of her third album, So Close to What. It opened in early 2025 and continued across North America and beyond before closing later that year, taking in dozens of dates with a rotating set of support acts. The production leaned heavily on choreography and a full live show built around the album\'s pop and dance material.',
        1 => 'For anyone tracking Tate McRae tour dates, her schedule shifts between full headline runs and festival appearances depending on the year. New routings are announced periodically through her official channels and the usual ticketing platforms, and on-sales typically follow a pre-sale and general-sale pattern. Because live dates change, the listings shown alongside this page reflect current availability rather than any fixed itinerary.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Tate McRae touring in 2026?',
          'a' => 'Tate McRae\'s last full headline run, the Miss Possessive Tour, wrapped in late 2025. Her confirmed 2026 appearances so far centre on festival headline slots rather than a dedicated arena tour, and any new headline routing would be announced through her official channels. Check the live listings on this page for the most current confirmed dates.',
        ),
        1 => 
        array (
          'q' => 'What was Tate McRae\'s most recent tour?',
          'a' => 'Her most recent headline tour was the Miss Possessive Tour, her first arena tour, which supported the album So Close to What and ran through much of 2025. It featured a choreography-led production and several rotating opening acts across its North American and international dates.',
        ),
        2 => 
        array (
          'q' => 'How do Tate McRae presale tickets work?',
          'a' => 'On-sales for her tours typically open with pre-sale windows ahead of the general sale, accessed through codes from artist, venue, fan-club or credit-card partners. The general public sale follows. Demand for arena dates can be high, so registering for a pre-sale in advance improves your chances before remaining seats reach general release.',
        ),
        3 => 
        array (
          'q' => 'Where can I find Tate McRae tour dates?',
          'a' => 'Confirmed dates appear on her official site and through the ticketing platforms handling each run, and the live listings on this page reflect what is currently on sale. Because routings and festival slots are added over time, it is worth checking back rather than relying on an older schedule.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Tate McRae setlist draws heavily on So Close to What alongside her earlier hits, built around a dance-forward show with full choreography. On the Miss Possessive Tour the running order leaned on recent singles such as Sports Car, 2 Hands and Revolving Door, while retaining breakthrough tracks from her catalogue. The list below reflects songs that have been genuine live staples on her recent dates, though the exact order and inclusions move around from show to show.',
      ),
      'songs' => 
      array (
        0 => 'Miss Possessive',
        1 => 'No I\'m Not in Love',
        2 => '2 Hands',
        3 => 'Guilty Conscience',
        4 => 'Purple Lace Bra',
        5 => 'Like I Do',
        6 => 'Uh Oh',
        7 => 'Dear God',
        8 => 'Siren Sounds',
        9 => 'Greenlight',
        10 => 'You Broke Me First',
        11 => 'Run for the Hills',
        12 => 'Exes',
        13 => 'She\'s All I Wanna Be',
        14 => 'Revolving Door',
        15 => 'It\'s OK I\'m OK',
        16 => 'Sports Car',
        17 => 'Greedy',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, so the songs and their order shift between shows and across different legs of a tour. Treat this as a representative guide drawn from recent dates rather than a fixed running order for any specific concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Tate McRae play live?',
          'a' => 'Recent Tate McRae shows have featured Sports Car, Greedy, 2 Hands, Revolving Door, It\'s OK I\'m OK, You Broke Me First and Miss Possessive among others, mixing So Close to What material with her earlier hits. The set is built around choreography-led pop, though the exact songs vary from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Tate McRae play Greedy live?',
          'a' => 'Yes. Greedy, her breakthrough single, has been a regular fixture in her recent setlists and typically lands as one of the show\'s high points. As with any track, its placement and inclusion can change between dates, but it has been among the most consistent songs on her recent tours.',
        ),
        2 => 
        array (
          'q' => 'Does Tate McRae do an encore?',
          'a' => 'Her recent shows have generally run as a continuous set rather than relying on a formal encore break, building toward a closing run of her biggest singles. Encore formats can change between tours and venues, so the structure of any given night may differ from the typical pattern.',
        ),
        3 => 
        array (
          'q' => 'How many songs are in a Tate McRae set?',
          'a' => 'A headline Tate McRae set has typically run to roughly 18 to 20 songs, combining So Close to What tracks with earlier hits across a show shaped around dance and choreography. The precise count varies by tour and venue, with festival appearances usually featuring a shorter selection than a full headline date.',
        ),
      ),
    ),
  ),
  'green-day' => 
  array (
    'name' => 'Green Day',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 50,
      'range_high' => 950,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Green Day ticket prices generally sit within a broad band that reflects the band\'s move between large arenas, festival headline slots and the occasional stadium. Entry-level seats tend to land at the lower end, while floor positions close to the stage, pit access and hospitality packages carry a clear premium. As with most major rock tours, the figures shift by city, venue size and how much demand a given show attracts.',
        1 => 'Because Green Day work with Ticketmaster and Live Nation, most on-sales for the band use dynamic pricing, so the cost of a given seat can move during a sale rather than holding a fixed face value. The bands below describe how Green Day pricing usually breaks down across upper tiers, lower bowl, floor or pit, and VIP, giving a realistic sense of what each part of the room tends to cost.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and lawn',
          'desc' => 'The most affordable Green Day seats, covering upper-level arena sections and outer lawn or grandstand areas at festivals and amphitheatres. These usually run from around $50 to $90 and trade proximity for a full view of the staging and crowd.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and reserved',
          'desc' => 'Mid-priced reserved seating in the lower bowl or closer side sections, typically between $90 and $180. This band suits those who want a clear sightline to Billie Joe Armstrong and the band without paying floor or pit rates.',
        ),
        2 => 
        array (
          'name' => 'Floor and general admission',
          'desc' => 'Standing floor or general-admission positions on the main level, generally $150 to $320 depending on the venue and demand. Prices climb as you move toward the front, and these areas often sell quickly for higher-profile dates.',
        ),
        3 => 
        array (
          'name' => 'Front pit',
          'desc' => 'Premium standing access nearest the stage, commonly $300 to $600. The pit places fans directly in front of the band for the full Dookie and American Idiot run, and supply is limited at every show.',
        ),
        4 => 
        array (
          'name' => 'VIP and hospitality',
          'desc' => 'Official VIP and hospitality packages, which can reach $600 to $950 or more. These bundle premium seating or early entry with extras such as merchandise, dedicated entrance or lounge access, and sit at the top of the Green Day price range.',
        ),
      ),
      'why' => 'Most Green Day shows are sold through Ticketmaster and Live Nation, which apply dynamic pricing to many sections. This means a seat\'s cost is tied to live demand and can rise or fall during a sale rather than holding one fixed face value. Popular dates, marquee cities and the best floor or pit positions are the most likely to move upward, while upper-tier and lawn seats stay closer to the lower end of the band. Resale listings add further variation and can sit above or below the original price depending on how a particular show is selling.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Green Day tickets?',
          'a' => 'Green Day tickets generally range from about $50 for upper-tier and lawn seats to $950 or more for VIP and hospitality packages. Lower-bowl reserved seats tend to fall between $90 and $180, while floor, general admission and front-pit positions sit higher. Exact prices depend on the venue, the city and how much demand a particular date draws.',
        ),
        1 => 
        array (
          'q' => 'Why do Green Day ticket prices change during a sale?',
          'a' => 'Green Day on-sales through Ticketmaster and Live Nation use dynamic pricing on many sections, so the cost of a seat is linked to live demand and can shift while a sale is running. Higher-profile dates and the best floor or pit spots are the most likely to move upward, while upper-tier seats usually stay nearer the lower end.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Green Day?',
          'a' => 'The lowest Green Day prices are normally upper-tier arena seats or outer lawn and grandstand areas at amphitheatres and festivals, often starting around $50 to $90. These positions trade closeness for a full view of the staging and remain the most budget-friendly part of most Green Day shows.',
        ),
        3 => 
        array (
          'q' => 'How much do Green Day VIP packages cost?',
          'a' => 'Official Green Day VIP and hospitality packages typically run from about $600 to $950 or more. They usually combine premium seating or early entry with extras such as merchandise, a dedicated entrance or lounge access, which is why they sit at the top of the band\'s price range.',
        ),
        4 => 
        array (
          'q' => 'Are Green Day floor tickets expensive?',
          'a' => 'Green Day floor and general-admission positions usually cost between $150 and $320, with front-pit access higher at roughly $300 to $600. Prices rise as you move toward the stage, and these standing areas tend to sell faster than reserved seating for in-demand dates.',
        ),
        5 => 
        array (
          'q' => 'Do Green Day ticket prices vary by city?',
          'a' => 'Yes. Green Day prices differ between cities and venues, since arena, amphitheatre, festival and stadium shows carry different capacities and demand. Larger markets and marquee dates generally sit toward the upper end of the band, while smaller or less in-demand shows can land closer to the entry-level figures.',
        ),
        6 => 
        array (
          'q' => 'Is resale cheaper than face value for Green Day?',
          'a' => 'It depends on the show. Resale listings for Green Day can sit above or below the original price depending on how well a date is selling. For lower-demand shows, resale prices sometimes fall below face value, while sold-out or high-profile dates often push resale above the original cost.',
        ),
        7 => 
        array (
          'q' => 'What is included in a Green Day general admission ticket?',
          'a' => 'A Green Day general-admission ticket provides standing access to the main floor rather than an assigned seat, so positions are claimed on a first-come basis. The closer you stand to the stage the better the view of the band, and front-pit upgrades offer the nearest standing access for an additional cost.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Saviors Tour',
      'intro' => 
      array (
        0 => 'Green Day tour dates have centred on The Saviors Tour, the band\'s run supporting their 2024 album Saviors alongside landmark anniversaries of Dookie and American Idiot. The trek has moved through arenas, stadiums and major festival headline slots across North America, Europe and beyond, pairing newer material with the catalogue that built the band\'s reputation. Routing and venues vary by leg, so the scale of a given Green Day show depends on the market.',
        1 => 'When planning around Green Day tour dates it helps to know how the band typically stages a run. Shows are usually announced city by city, with on-sales handled through Ticketmaster and Live Nation, and festival appearances slotting in alongside headline arena and stadium nights. The sections below cover the common questions fans raise about touring, where Green Day tend to play, and how their live schedule is generally structured.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Green Day touring in 2026?',
          'a' => 'Green Day have toured heavily in recent years behind The Saviors Tour, and the band continue to announce live dates and festival appearances city by city. Any new 2026 shows are confirmed through official channels and on-sales via Ticketmaster and Live Nation, so checking the band\'s listings is the reliable way to see the current schedule.',
        ),
        1 => 
        array (
          'q' => 'What is Green Day\'s current tour called?',
          'a' => 'Green Day\'s most recent named tour is The Saviors Tour, built around their 2024 album Saviors and the anniversaries of Dookie and American Idiot. The run has covered arenas, stadiums and festival headline slots, with the band performing both albums in full alongside selections from across their catalogue.',
        ),
        2 => 
        array (
          'q' => 'Where does Green Day usually play on tour?',
          'a' => 'Green Day typically headline large arenas and stadiums, and they frequently top the bill at major festivals. Venue size varies by market and leg, so a given Green Day date might be an indoor arena, an outdoor amphitheatre, a stadium or a festival stage, which in turn affects capacity and ticket availability.',
        ),
        3 => 
        array (
          'q' => 'How are Green Day tour dates announced?',
          'a' => 'Green Day tour dates are announced city by city through the band\'s official channels, with tickets sold via Ticketmaster and Live Nation. On-sales are usually scheduled per show, and festival appearances are confirmed separately by the individual events, so the full live schedule builds up across multiple announcements.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Green Day setlist on recent tours has leaned heavily on the band\'s two defining records, with Dookie and American Idiot played close to in full to mark their anniversaries. Around that spine the band add tracks from the 2024 album Saviors and a rotating selection of catalogue favourites, so a typical show blends 1990s punk energy with the larger rock-opera moments and a handful of newer songs.',
      ),
      'songs' => 
      array (
        0 => 'The American Dream Is Killing Me',
        1 => 'American Idiot',
        2 => 'Holiday',
        3 => 'Boulevard of Broken Dreams',
        4 => 'Jesus of Suburbia',
        5 => 'St. Jimmy',
        6 => 'Longview',
        7 => 'Welcome to Paradise',
        8 => 'Hitchin\' a Ride',
        9 => 'Brain Stew',
        10 => 'Basket Case',
        11 => 'When I Come Around',
        12 => 'Minority',
        13 => 'Know Your Enemy',
        14 => '21 Guns',
        15 => 'Dilemma',
        16 => 'Bobby Sox',
        17 => 'Wake Me Up When September Ends',
        18 => 'Good Riddance (Time of Your Life)',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and their order change between shows.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Green Day play live?',
          'a' => 'Green Day live shows are anchored by Dookie and American Idiot, played close to in full, so staples include American Idiot, Holiday, Boulevard of Broken Dreams, Jesus of Suburbia, Basket Case, When I Come Around and Welcome to Paradise. The band also add tracks from their 2024 album Saviors such as The American Dream Is Killing Me, Dilemma and Bobby Sox.',
        ),
        1 => 
        array (
          'q' => 'Does Green Day do an encore?',
          'a' => 'Green Day often close their shows with a stripped-back acoustic moment rather than a long separate encore block, frequently ending on Good Riddance (Time of Your Life) performed by Billie Joe Armstrong. The structure varies between dates, and the run through Dookie and American Idiot forms the bulk of the main set.',
        ),
        2 => 
        array (
          'q' => 'Does Green Day still play Basket Case live?',
          'a' => 'Yes. Basket Case remains a fixture of Green Day\'s live shows, performed as part of the Dookie portion of recent sets alongside Longview, Welcome to Paradise, When I Come Around and Brain Stew. It is one of the band\'s most consistent setlist inclusions across tours.',
        ),
        3 => 
        array (
          'q' => 'How long is a Green Day concert?',
          'a' => 'A Green Day headline show generally runs for roughly two to two and a half hours, since the band perform Dookie and American Idiot in full plus additional material. Exact running time varies by date and whether the show is a full headline set or a shorter festival appearance.',
        ),
      ),
    ),
  ),
  'linkin-park' => 
  array (
    'name' => 'Linkin Park',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Linkin Park ticket prices vary widely depending on the venue, the city and where you choose to sit, as the band\'s current run mixes arena headline shows with larger stadium and festival dates. Entry-level seats in the upper tiers tend to sit at the lower end of the range, while floor positions, lower-bowl seats and VIP packages carry a clear premium. As a general guide, expect face-value prices to span a broad band before fees are added.',
        1 => 'Because demand for Linkin Park is consistently strong, the better-located seats and hospitality packages move quickly and are usually the first to reach the higher end of the price range. Prices also differ between intimate arena nights and open-air stadium shows, so comparing several dates is the most reliable way to find a figure that suits your budget.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, generally around 70 to 130 USD before fees. These seats sit higher up or further back but still offer a full view of the stage and production for Linkin Park\'s full set.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side seating',
          'desc' => 'Mid-range seating with a closer, more central sightline, typically 130 to 250 USD. A common choice for those who want a clearer view of the band without paying floor or VIP prices.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Standing floor or premium front blocks, usually 250 to 450 USD depending on the venue. These positions put you nearest the stage and tend to sell out earliest for popular dates.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official enhanced packages combining prime seating or early entry with extras such as merchandise or lounge access, ranging from roughly 450 to 1,200 USD. Inclusions vary by city and venue.',
        ),
      ),
      'why' => 'Linkin Park\'s tour is sold largely through platforms that use dynamic pricing, meaning face values can rise and fall with demand rather than staying fixed. Popular dates and the best-located seats often climb toward the upper end of the range, while quieter mid-week shows or higher tiers can be found nearer the lower end. Booking fees and service charges are added on top of the face value at checkout, so the final total is typically higher than the listed seat price.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Linkin Park tickets?',
          'a' => 'Linkin Park tickets generally range from around 70 USD for upper-tier seats to about 1,200 USD for VIP and hospitality packages before fees. Lower-bowl seats commonly fall between 130 and 250 USD, with floor and front sections higher again. The exact figure depends on the venue, the city and how close to the stage you sit.',
        ),
        1 => 
        array (
          'q' => 'Why do Linkin Park ticket prices change?',
          'a' => 'Most Linkin Park dates use dynamic pricing, so face values shift with demand rather than remaining fixed. High-demand shows and well-located seats tend to rise toward the top of the range, while higher tiers and less busy dates sit lower. Service and booking fees are then added at checkout, raising the final total.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Linkin Park live?',
          'a' => 'The cheapest option is usually an upper-tier or rear seat, often around 70 to 130 USD before fees. Choosing a less in-demand date, such as a mid-week show, and booking early before dynamic pricing pushes popular seats higher can also help keep the cost down.',
        ),
        3 => 
        array (
          'q' => 'How much are Linkin Park VIP tickets?',
          'a' => 'Official Linkin Park VIP and hospitality packages typically cost between 450 and 1,200 USD. They combine premium seating or early entry with extras that may include merchandise, a dedicated entrance or lounge access. Exact inclusions and prices differ by venue and city.',
        ),
        4 => 
        array (
          'q' => 'Do Linkin Park ticket prices include fees?',
          'a' => 'The advertised face value usually does not include booking and service fees, which are added during checkout. These charges can add a noticeable amount to the listed seat price, so the final total you pay is generally higher than the headline figure shown for each seating tier.',
        ),
        5 => 
        array (
          'q' => 'Are stadium or arena Linkin Park tickets more expensive?',
          'a' => 'It varies. Stadium and festival dates can offer cheaper entry-level standing or upper seats due to larger capacity, while arena shows are more intimate and may price closely matched seats higher. Comparing several dates is the best way to judge value for a given budget.',
        ),
        6 => 
        array (
          'q' => 'How much do floor tickets for Linkin Park cost?',
          'a' => 'Floor and front-section tickets for Linkin Park usually range from about 250 to 450 USD before fees, depending on the venue. These positions place you nearest the stage and the band, and they are typically among the first to sell out for in-demand shows.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Linkin Park tickets early?',
          'a' => 'Often, yes. Because dynamic pricing responds to demand, the best-value seats are frequently available earlier in the on-sale period before popular sections climb. Waiting can mean lower tiers sell out, leaving only higher-priced floor, premium or VIP options for that date.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'From Zero World Tour',
      'intro' => 
      array (
        0 => 'Linkin Park\'s current run of tour dates falls under the From Zero World Tour, supporting the band\'s From Zero album and marking their return to the road with vocalist Emily Armstrong alongside Mike Shinoda. The tour has moved through North America, Europe, Asia, Latin America and Australia across multiple legs, taking in arenas, stadiums and major festival appearances along the way.',
        1 => 'The schedule blends headline indoor shows with larger open-air stadium and festival dates, so the scale of each performance varies by city. Tour dates are announced in stages and on-sale periods differ by region, which means checking the listing for a specific city is the most reliable way to confirm where and when Linkin Park are playing.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Linkin Park touring in 2026?',
          'a' => 'Yes. Linkin Park are continuing the From Zero World Tour through 2026, with a run of European stadium, arena and festival dates among the legs that have been confirmed. The tour follows earlier stops across North America, Asia, Latin America and Australia. Exact dates and cities are best checked on the individual event listings.',
        ),
        1 => 
        array (
          'q' => 'What is Linkin Park\'s current tour called?',
          'a' => 'The current tour is the From Zero World Tour, named after the band\'s From Zero album. It marks Linkin Park\'s return to extensive touring with Emily Armstrong sharing vocals alongside Mike Shinoda, and spans multiple continents across several legs of arena, stadium and festival shows.',
        ),
        2 => 
        array (
          'q' => 'Where is Linkin Park playing on the From Zero World Tour?',
          'a' => 'The From Zero World Tour has visited North America, Europe, Asia, Latin America and Australia, mixing arena headline shows with stadium and festival dates. Specific cities and venues vary by leg, so the most accurate way to see where Linkin Park are playing is to check the current listings for each date.',
        ),
        3 => 
        array (
          'q' => 'Who is singing with Linkin Park on this tour?',
          'a' => 'Linkin Park\'s From Zero World Tour features Emily Armstrong on lead vocals alongside co-founder Mike Shinoda, with the wider line-up performing material spanning the band\'s catalogue and the From Zero album. The shows draw on both the band\'s earlier hits and newer songs from the current era.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical Linkin Park setlist on the From Zero World Tour balances long-standing favourites with newer From Zero material, opening with high-energy numbers before moving through quieter, more melodic moments. Recent shows have featured early staples such as One Step Closer, Crawling and Papercut alongside The Emptiness Machine and Heavy Is the Crown, giving a spread across the band\'s history.',
      ),
      'songs' => 
      array (
        0 => 'The Emptiness Machine',
        1 => 'Lying From You',
        2 => 'Crawling',
        3 => 'Up From the Bottom',
        4 => 'New Divide',
        5 => 'Points of Authority',
        6 => 'Somewhere I Belong',
        7 => 'The Catalyst',
        8 => 'Burn It Down',
        9 => 'Where\'d You Go',
        10 => 'Waiting for the End',
        11 => 'Two Faced',
        12 => 'One Step Closer',
        13 => 'Breaking the Habit',
        14 => 'What I\'ve Done',
        15 => 'Heavy Is the Crown',
        16 => 'Numb',
        17 => 'Papercut',
        18 => 'In the End',
        19 => 'Faint',
      ),
      'encore' => 
      array (
        0 => 'Papercut',
        1 => 'In the End',
        2 => 'Faint',
        3 => 'Bleed It Out',
      ),
      'note' => 'Setlists vary from night to night, and the order, song choices and encore can change between shows on the From Zero World Tour, so the exact running order at any given date may differ from the selection listed here.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Linkin Park play live?',
          'a' => 'Linkin Park\'s recent setlists draw on the full catalogue, mixing early staples such as One Step Closer, Crawling, Numb, In the End and Faint with later tracks like What I\'ve Done and New Divide and newer material including The Emptiness Machine and Heavy Is the Crown. The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Linkin Park do an encore?',
          'a' => 'Yes, Linkin Park\'s shows on the From Zero World Tour typically close with an encore. Recent encores have featured songs such as Papercut, In the End, Faint and Bleed It Out, though the choice of encore tracks can change from one date to the next.',
        ),
        2 => 
        array (
          'q' => 'Does Linkin Park play Numb and In the End live?',
          'a' => 'Yes. Numb and In the End remain regular fixtures of Linkin Park\'s live shows and have featured consistently across recent From Zero World Tour dates. Both sit among the band\'s most-performed songs, usually appearing in the latter part of the main set or the encore, though placement can vary by night.',
        ),
        3 => 
        array (
          'q' => 'How long is a Linkin Park concert?',
          'a' => 'A headline Linkin Park show generally runs around two hours, covering roughly 20 or more songs across the main set and encore. The length can vary by venue and whether the date is a full headline show or a shorter festival appearance, where the set is usually trimmed.',
        ),
      ),
    ),
  ),
  'imagine-dragons' => 
  array (
    'name' => 'Imagine Dragons',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Imagine Dragons ticket prices vary with the band\'s stadium and large-arena routing, the city, the seat and how close a show is to selling out. As a broad guide, face-value tickets tend to open in the region of 55 USD for upper-level or lawn positions and climb toward 350 USD for the best floor and lower-bowl seats and VIP packages. The figures below describe how the tiers generally sit rather than any single dated event.',
        1 => 'Because Imagine Dragons play a mix of outdoor stadiums, amphitheatres and indoor arenas, the spread between the cheapest and most expensive seat can be wide at one venue and narrower at another. Reading the band\'s pricing by tier, rather than by a single headline number, gives the clearest sense of what a given seat is likely to cost before fees are added.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and lawn',
          'desc' => 'The entry tier, generally around 55 to 95 USD, covering upper-deck seating in arenas and lawn or general-admission grass at amphitheatre shows. These positions trade proximity for value and suit anyone prioritising the overall sound and production over a close view.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier and side seating',
          'desc' => 'Typically in the region of 95 to 165 USD, this band covers mid-level bowl seats and side sections with a clearer sightline to the stage. It is the most common choice for fans wanting a balance between cost and a reasonable view of the band.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and close seating',
          'desc' => 'Usually around 165 to 260 USD, this tier covers lower-level seats and closer floor positions near the main stage. Pricing within the band moves with demand and how central the seat is to the performance area.',
        ),
        3 => 
        array (
          'name' => 'Floor and premium VIP',
          'desc' => 'The top tier, broadly 260 to 350 USD, spans premium floor standing, front-section seats and official VIP packages that may add early entry, merchandise or hospitality. Exact contents and prices depend on the venue and the package offered for that run.',
        ),
      ),
      'why' => 'Most major Imagine Dragons dates are sold through Ticketmaster and Live Nation using dynamic pricing, so the cost of a given seat can rise or fall with real-time demand rather than staying fixed at a published face value. Popular shows and prime seats may sit well above the figures here, while quieter dates can fall below them. Platinum and official VIP inventory is priced separately and tends to sit at the upper end. Treat the bands above as a realistic starting reference and check the live seat map for the current price before booking.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Imagine Dragons tickets?',
          'a' => 'Imagine Dragons ticket prices generally run from about 55 USD for upper-level or lawn seats to around 350 USD for premium floor positions and VIP packages, before fees. The band play stadiums, amphitheatres and arenas, so the exact spread depends on the venue, the seat and current demand under dynamic pricing.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Imagine Dragons live?',
          'a' => 'The lowest-cost option is usually an upper-level arena seat or a lawn or general-admission position at an amphitheatre, typically starting around 55 to 95 USD. These places offer the full show and production while keeping closer to the entry price than lower-bowl or floor tickets.',
        ),
        2 => 
        array (
          'q' => 'Why do Imagine Dragons ticket prices change so much?',
          'a' => 'Most Imagine Dragons dates use dynamic pricing through Ticketmaster and Live Nation, so seat prices move with live demand instead of staying at a single fixed figure. A high-demand stadium night can sit well above the typical band, while a quieter date may fall below it.',
        ),
        3 => 
        array (
          'q' => 'How much are VIP or floor tickets for Imagine Dragons?',
          'a' => 'Premium floor and official VIP packages generally fall in the region of 260 to 350 USD, sometimes higher for the most sought-after shows. VIP packages can include early entry, dedicated merchandise or hospitality, with the exact contents varying by venue and tour leg.',
        ),
        4 => 
        array (
          'q' => 'Are Imagine Dragons tickets expensive compared with other rock acts?',
          'a' => 'Imagine Dragons sit in the upper-arena to stadium bracket, so prices are higher than a club or theatre act but generally below the very top stadium headliners. Entry seats stay accessible while premium and VIP positions carry the larger figures, much as with comparable large-scale touring bands.',
        ),
        5 => 
        array (
          'q' => 'Do Imagine Dragons ticket prices include fees?',
          'a' => 'The figures quoted here are face-value guides and do not include service, facility or order-processing fees, which are added at checkout. Final cost depends on the seller and venue, so the total paid is usually somewhat above the listed seat price.',
        ),
        6 => 
        array (
          'q' => 'Is general admission available for Imagine Dragons shows?',
          'a' => 'Many Imagine Dragons stadium and amphitheatre dates offer general-admission standing on the floor or lawn, typically among the lower-priced options. Availability depends on the venue, as some indoor arena dates are fully seated with no standing area.',
        ),
        7 => 
        array (
          'q' => 'How far in advance should I plan for the best Imagine Dragons prices?',
          'a' => 'Prices under dynamic pricing tend to be most favourable when a date first goes on sale and before a show sells out, as remaining inventory can rise with demand. Comparing seats across tiers and venues on a tour leg gives the clearest picture of value before booking.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Loom World Tour',
      'intro' => 
      array (
        0 => 'Imagine Dragons tour dates fall under the Loom World Tour, the band\'s large-scale run staged in support of their 2024 album Loom. The tour has taken in stadiums, arenas and festival sites across North America, Europe, Asia and South America, pairing Loom material with the band\'s established catalogue. Specific dates and on-sale details for each leg are listed separately, as routing and venues are confirmed city by city.',
        1 => 'As with most Imagine Dragons touring, the Loom World Tour mixes outdoor stadium and amphitheatre shows with indoor arena dates, so the scale of any given night varies by market. Where new legs or additional dates are announced, they are added to the tour\'s published schedule rather than described here, keeping this overview focused on how the band generally tour.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Are Imagine Dragons touring in 2026?',
          'a' => 'Imagine Dragons have continued the Loom World Tour into 2026, the run supporting their 2024 album Loom. The tour has spanned multiple continents, and individual 2026 dates and venues are published separately as each leg is confirmed, so checking the current schedule gives the most accurate picture.',
        ),
        1 => 
        array (
          'q' => 'What is the name of the current Imagine Dragons tour?',
          'a' => 'The current run is the Loom World Tour, named after the band\'s 2024 album Loom. It is Imagine Dragons\' fifth headline concert tour and has visited North America, Europe, Asia and South America across its various legs.',
        ),
        2 => 
        array (
          'q' => 'Which album is the Imagine Dragons tour supporting?',
          'a' => 'The Loom World Tour supports Loom, Imagine Dragons\' sixth studio album, released in 2024. Sets draw on that record alongside the band\'s earlier singles, so newer Loom tracks appear next to longer-standing catalogue songs.',
        ),
        3 => 
        array (
          'q' => 'Where do Imagine Dragons usually perform on tour?',
          'a' => 'Imagine Dragons typically play a mix of outdoor stadiums, amphitheatres and indoor arenas, alongside selected festival appearances. The Loom World Tour has followed this pattern across multiple continents, with the scale of each show depending on the city and venue.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical Imagine Dragons setlist on the Loom World Tour runs to around twenty songs, blending tracks from the 2024 album Loom with the singles that built the band\'s reputation. Recent shows have opened with newer material before working through familiar arena anthems, and the running order shifts between dates. The songs below reflect staples of the band\'s recent live sets rather than a fixed list for any single performance.',
      ),
      'songs' => 
      array (
        0 => 'Fire in These Hills',
        1 => 'Take Me to the Beach',
        2 => 'Eyes Closed',
        3 => 'Nice to Meet You',
        4 => 'Thunder',
        5 => 'Bones',
        6 => 'Sharks',
        7 => 'Shots',
        8 => 'Enemy',
        9 => 'It\'s Time',
        10 => 'I Bet My Life',
        11 => 'Next to Me',
        12 => 'Bad Liar',
        13 => 'Whatever It Takes',
        14 => 'Natural',
        15 => 'Demons',
        16 => 'Believer',
        17 => 'Radioactive',
      ),
      'encore' => 
      array (
        0 => 'Believer',
        1 => 'Radioactive',
      ),
      'note' => 'Setlists vary night to night, and Imagine Dragons regularly rotate songs, swap the running order and adjust the encore from show to show, so any given concert may differ from the list above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Imagine Dragons play live?',
          'a' => 'Imagine Dragons live sets centre on hits such as Radioactive, Believer, Thunder, Demons, Whatever It Takes and Natural, alongside Loom-era tracks including Eyes Closed, Take Me to the Beach and Fire in These Hills. The exact selection changes from night to night across the tour.',
        ),
        1 => 
        array (
          'q' => 'Does the Imagine Dragons setlist include older songs?',
          'a' => 'Yes. Alongside newer Loom material, Imagine Dragons regularly perform earlier singles such as It\'s Time, Demons, I Bet My Life, Bad Liar and Radioactive, so long-standing fans hear catalogue favourites mixed in with the recent album tracks.',
        ),
        2 => 
        array (
          'q' => 'Do Imagine Dragons do an encore?',
          'a' => 'Imagine Dragons frequently close their shows with an encore, often built around their biggest anthems such as Believer and Radioactive. As with the main set, the encore can change between dates, so the closing songs are not fixed across the tour.',
        ),
        3 => 
        array (
          'q' => 'How long is an Imagine Dragons concert?',
          'a' => 'A headline Imagine Dragons set on the Loom World Tour typically runs around 75 to 90 minutes across roughly twenty songs, not counting any support acts. The precise length depends on the night, the venue and how the running order and encore are arranged.',
        ),
      ),
    ),
  ),
  'twenty-one-pilots' => 
  array (
    'name' => 'Twenty One Pilots',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 325,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Twenty One Pilots ticket prices tend to sit in the mid band common to arena and amphitheatre acts, rather than the higher tiers reserved for stadium headliners. Across a typical run, most reserved seats land somewhere between the upper bowl and the lower sections close to the stage, with general admission floor and pit standing as the in-demand option. The figures below describe how the band\'s pricing usually breaks down, so you can judge where a given seat falls.',
        1 => 'How much Twenty One Pilots tickets cost depends mainly on the venue and where the seat sits within it. Their shows mix indoor arenas, open-air amphitheatres and the occasional stadium date, and each scale shifts the entry point. Upper-level seats remain the most affordable way in, while floor general admission and premium lower-tier seats carry a clear premium. VIP packages sit above the standard tiers and bundle extras rather than simply better sightlines.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and lawn',
          'desc' => 'The most affordable entry, covering upper-tier arena seats and amphitheatre lawn. Expect roughly $45 to $90 depending on the venue and the night\'s demand. Sightlines are distant but the staging and lighting design read well from height.',
        ),
        1 => 
        array (
          'name' => 'Mid and lower bowl reserved',
          'desc' => 'Reserved seats through the middle and lower sections, generally around $90 to $170. This band offers a balance of closer sightlines and seated comfort, and tends to be the broadest pool of inventory on a Twenty One Pilots date.',
        ),
        2 => 
        array (
          'name' => 'Floor general admission and pit',
          'desc' => 'Standing general admission on the floor, including the front pit closest to the stage, usually runs about $140 to $325. These are the most sought-after positions for the band\'s high-energy, movement-heavy show and sell quickly.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP bundles typically range from around $200 to $600 per person. They commonly add early entry, priority pit or premium reserved seating, exclusive merchandise and a commemorative item rather than guaranteeing the very front row.',
        ),
      ),
      'why' => 'Twenty One Pilots tickets are sold largely through Ticketmaster and Live Nation, which apply dynamic pricing to most major dates. This means the listed price can move with demand rather than staying fixed: high-demand sections and shows flagged as selling quickly may climb above the opening figure, while quieter dates can hold nearer the lower end. Service fees and taxes are added at checkout and commonly increase the headline price by roughly 15 to 30 percent. Quoting a single band rather than one number reflects how the same seat type can differ between an arena, an amphitheatre and a stadium date, and from one city to the next.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Twenty One Pilots tickets?',
          'a' => 'Twenty One Pilots tickets generally range from about $45 for upper-level and lawn seats to roughly $325 for floor general admission and pit positions. Mid and lower reserved seating tends to fall between those points, and prices vary by venue scale and demand on the night.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Twenty One Pilots live?',
          'a' => 'The cheapest standard option is usually an upper-tier arena seat or amphitheatre lawn ticket, often starting around $45 to $90. Buying when a date first goes on general sale, before dynamic pricing responds to demand, also tends to secure the lower end of the range.',
        ),
        2 => 
        array (
          'q' => 'Why do Twenty One Pilots ticket prices change?',
          'a' => 'Most Twenty One Pilots dates use dynamic pricing through Ticketmaster and Live Nation, so prices rise and fall with demand rather than staying fixed. A section flagged as selling fast can climb above its opening figure, while a quieter show may settle nearer the lower band.',
        ),
        3 => 
        array (
          'q' => 'Are Twenty One Pilots VIP packages worth it?',
          'a' => 'VIP packages, typically $200 to $600 per person, suit fans who value early entry, priority pit or premium seating, and exclusive merchandise. They bundle extras rather than guaranteeing front-row spots, so the value depends on how much those perks matter to you.',
        ),
        4 => 
        array (
          'q' => 'How much do floor or pit tickets for Twenty One Pilots cost?',
          'a' => 'Floor general admission and front-pit tickets usually run about $140 to $325. These standing positions are the most in-demand for the band\'s energetic, movement-heavy performances and tend to sell out faster than seated sections.',
        ),
        5 => 
        array (
          'q' => 'Do Twenty One Pilots tickets get cheaper closer to the show?',
          'a' => 'Sometimes, but it is not reliable. Under dynamic pricing, prices can drop on a softer-selling date as it approaches, yet popular shows often hold firm or rise. Waiting carries the risk of better sections selling out first.',
        ),
        6 => 
        array (
          'q' => 'Are there extra fees on Twenty One Pilots tickets?',
          'a' => 'Yes. Service fees and taxes are added at checkout on most Twenty One Pilots tickets and commonly raise the headline price by around 15 to 30 percent. The figure shown before checkout is rarely the final total.',
        ),
        7 => 
        array (
          'q' => 'Is there a difference in price between arena and stadium Twenty One Pilots shows?',
          'a' => 'Yes. Stadium and large festival dates can sit at the higher end of the range or above, while indoor arena and amphitheatre shows usually start lower. The same tier of seat tends to cost more at larger-capacity venues.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Clancy World Tour',
      'intro' => 
      array (
        0 => 'Twenty One Pilots tour dates have centred on The Clancy World Tour, the duo\'s run in support of the albums Clancy and the follow-up Breach. The trek moved through arenas, amphitheatres and select stadiums across North America, Europe and beyond, pairing the narrative threads of the recent records with longstanding catalogue favourites. Routing has typically clustered by region, so checking which leg covers your area is the practical first step.',
        1 => 'When following Twenty One Pilots tour plans, it helps to know the band tend to announce legs in regional blocks and have leaned toward larger venues and summer festival appearances as the cycle has progressed. Dates, on-sale timing and supporting acts are confirmed per leg, so live listings rather than general expectation are the reliable source for any specific city or night.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Are Twenty One Pilots touring in 2026?',
          'a' => 'Twenty One Pilots have continued performing into 2026 with festival appearances and select headline dates following The Clancy World Tour, including a large hometown stadium show in Ohio. Confirmed dates for any given region are best checked against current live listings.',
        ),
        1 => 
        array (
          'q' => 'What is the name of the current Twenty One Pilots tour?',
          'a' => 'The band\'s most recent major tour is The Clancy World Tour, supporting their albums Clancy and Breach. It ran across arenas, amphitheatres and stadiums, with later festival and one-off dates extending the live cycle.',
        ),
        2 => 
        array (
          'q' => 'Where are Twenty One Pilots playing on this tour?',
          'a' => 'The Clancy World Tour spanned North America, Europe and other regions across indoor arenas, open-air amphitheatres and a number of stadium dates. The band have also added summer festival slots, so coverage varies by leg and city.',
        ),
        3 => 
        array (
          'q' => 'Who is opening for Twenty One Pilots?',
          'a' => 'Support acts on Twenty One Pilots dates are confirmed per leg and have varied across the run, with Death Cab for Cutie among the announced supports for a major stadium show. The opener for a specific night is listed alongside that date.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Twenty One Pilots setlist on recent tours draws heavily on Clancy and Breach while keeping the catalogue staples that long-time fans expect, typically running close to two hours. Recent shows have opened with Overcompensate and woven together the band\'s blend of rap-rock, pop and electronics, with Tyler Joseph and Josh Dun moving between instruments and into the crowd. The songs below are the genuine live regulars rather than a fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'Overcompensate',
        1 => 'Holding On to You',
        2 => 'Vignette',
        3 => 'Heavydirtysoul',
        4 => 'The Line',
        5 => 'Backslide',
        6 => 'Shy Away',
        7 => 'Next Semester',
        8 => 'We Don\'t Believe What\'s on TV',
        9 => 'The Outside',
        10 => 'Heathens',
        11 => 'Routines in the Night',
        12 => 'Ride',
        13 => 'Stressed Out',
        14 => 'Tear in My Heart',
        15 => 'House of Gold',
        16 => 'Lane Boy',
        17 => 'Car Radio',
        18 => 'Trees',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Twenty One Pilots regularly rotate album cuts, mash-ups and fan favourites between shows, so the exact running order and song choices differ from one date to the next.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Twenty One Pilots play live?',
          'a' => 'Recent Twenty One Pilots sets mix current material such as Overcompensate, Vignette and Next Semester with longstanding favourites like Stressed Out, Ride, Heathens, Car Radio and Trees. The band typically play close to two hours, drawing across their catalogue.',
        ),
        1 => 
        array (
          'q' => 'Does the Twenty One Pilots setlist change each night?',
          'a' => 'Yes. While core songs such as Overcompensate and Stressed Out appear most nights, Twenty One Pilots rotate album tracks, mash-ups and deeper cuts between shows, so the running order and selection differ from date to date.',
        ),
        2 => 
        array (
          'q' => 'Do Twenty One Pilots play an encore?',
          'a' => 'Twenty One Pilots typically run their show as a continuous, theatrically paced set rather than a conventional encore break, often building to a major closer such as Trees. The exact ending can vary between shows.',
        ),
        3 => 
        array (
          'q' => 'What song do Twenty One Pilots usually open with?',
          'a' => 'On recent tours Twenty One Pilots have frequently opened with Overcompensate, the lead single from Clancy. As with the rest of the set, the opener can change between dates, but it has been the consistent starting point on this cycle.',
        ),
      ),
    ),
  ),
  'red-hot-chili-peppers' => 
  array (
    'name' => 'Red Hot Chili Peppers',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 65,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Red Hot Chili Peppers ticket prices generally sit within the range expected of a long-running arena, amphitheatre and stadium act rather than a club band. Across recent touring, entry-level seats have tended to open around the lower end, with the better-located floor and lower-bowl positions costing considerably more. The figures below describe how the band\'s pricing usually behaves so you can gauge a fair outlay before live listings load.',
        1 => 'Where a Red Hot Chili Peppers show falls within its price band depends heavily on the venue and the seat. An open-air amphitheatre lawn ticket behaves very differently from a front-block stadium seat, and demand for a given market shifts the picture further. The tiers here set out the typical structure of a Chili Peppers ticket so the numbers attached to live dates read as expected rather than surprising.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Lawn and upper level',
          'desc' => 'General-admission lawn at amphitheatres and the highest seated tiers in arenas and stadiums, usually around 65 to 110 USD. The most economical way in, with a full view of the stage and screens but the greatest distance from the band.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier seated',
          'desc' => 'Mid-bowl and side seating with a clearer sightline, typically about 110 to 200 USD. A common middle ground for those wanting a fixed seat without paying floor or front-block rates.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and floor',
          'desc' => 'Lower-level seating and standing or seated floor positions close to the stage, generally in the 200 to 320 USD range. Demand here is consistently high given the band\'s live reputation.',
        ),
        3 => 
        array (
          'name' => 'Premium and front block',
          'desc' => 'Front-of-floor, premium-located seats and the best house positions, often 320 to 450 USD or more in busier markets. The closest standard tickets short of a packaged VIP option.',
        ),
      ),
      'why' => 'Red Hot Chili Peppers tours through Ticketmaster and Live Nation, which means many shows use dynamic pricing. Under that model the listed price moves with demand rather than staying fixed, so identical seats can cost more for a high-demand market or date and less for a quieter one. Official Platinum seats are priced this way deliberately and are not VIP packages despite the higher figures. Service and facility fees are added at checkout on top of the face value, so the final total usually exceeds the headline price.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Red Hot Chili Peppers tickets?',
          'a' => 'Red Hot Chili Peppers tickets generally range from about 65 USD for lawn or upper-tier seats to around 450 USD for premium front-block positions, with most standard seated tickets landing somewhere in between. The exact figure depends on the venue type, the market and how the band\'s dynamic pricing reads demand for that date. Fees are added at checkout.',
        ),
        1 => 
        array (
          'q' => 'Why do Red Hot Chili Peppers ticket prices change?',
          'a' => 'Most Red Hot Chili Peppers dates run on Ticketmaster and Live Nation dynamic pricing, so the same seat can cost different amounts depending on how strong demand is for that particular show. Official Platinum tickets float in price for the same reason. This is standard for the band\'s tours and explains why a stadium date in one city may list higher than an amphitheatre date elsewhere.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Red Hot Chili Peppers live?',
          'a' => 'The cheapest Red Hot Chili Peppers tickets are usually amphitheatre lawn passes and the highest seated tiers in arenas and stadiums, often starting around 65 to 80 USD before fees. These give a full view of the stage and video screens at the cost of distance. Buying early in an on-sale, before dynamic pricing lifts the better sections, also tends to help.',
        ),
        3 => 
        array (
          'q' => 'Are there VIP packages for Red Hot Chili Peppers?',
          'a' => 'Red Hot Chili Peppers tours have offered premium and VIP packages on past runs, typically bundling a strong seat with extras such as early entry or merchandise. These are priced separately and well above standard tickets, sometimes several hundred dollars more. Availability varies by date and venue, so any package offered is shown alongside the live listing rather than guaranteed for every show.',
        ),
        4 => 
        array (
          'q' => 'Do Red Hot Chili Peppers ticket prices include fees?',
          'a' => 'No. The headline Red Hot Chili Peppers ticket price is the face value, and service plus facility fees are added at checkout. On primary platforms these can add a meaningful amount to the total, so the final cost is higher than the listed seat price. It is worth checking the full breakdown before completing a purchase.',
        ),
        5 => 
        array (
          'q' => 'Are stadium or amphitheatre Red Hot Chili Peppers tickets more expensive?',
          'a' => 'It depends on the seat rather than the venue type alone. Stadium shows can carry higher front-block prices because of scale and demand, while amphitheatres offer cheaper lawn entry but pricier reserved seating near the stage. A lawn ticket at an amphitheatre is typically the lowest-cost option, whereas a front-floor stadium seat sits at the top of the band\'s range.',
        ),
        6 => 
        array (
          'q' => 'How much are floor tickets for Red Hot Chili Peppers?',
          'a' => 'Floor tickets for Red Hot Chili Peppers generally fall in the 200 to 320 USD range, with the very front positions reaching higher under dynamic pricing. Floor access puts you closest to the band, which is why these consistently sit among the more expensive standard options. Exact pricing varies by venue layout and how the market is reading demand.',
        ),
        7 => 
        array (
          'q' => 'Do Red Hot Chili Peppers tickets get cheaper closer to the show?',
          'a' => 'There is no guarantee. With dynamic pricing, primary Red Hot Chili Peppers seats can move in either direction as a date approaches, and high-demand shows often hold firm or rise. Some seats may appear late if holds are released, but relying on a last-minute drop is unpredictable. Buying when a price looks fair for the seat is generally the safer approach.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Unlimited Love Tour',
      'intro' => 
      array (
        0 => 'Red Hot Chili Peppers\' most recent major touring run was the Unlimited Love Tour, built around the band\'s 2022 albums Unlimited Love and Return of the Dream Canteen, both of which featured returning guitarist John Frusciante. The run carried the band across stadiums and amphitheatres before winding down, and remains the reference point for what current Red Hot Chili Peppers tour dates and staging look like in practice.',
        1 => 'When Red Hot Chili Peppers schedule shows, dates typically land in stadiums, large arenas and open-air amphitheatres, with the band often supported by rotating opening acts across a run. New legs are announced through official channels and major ticketing platforms ahead of any on-sale. The notes below explain how the band\'s touring generally operates so you know what to expect from any forthcoming Red Hot Chili Peppers dates.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Red Hot Chili Peppers touring in 2026?',
          'a' => 'As of mid-2026, Red Hot Chili Peppers have not announced a confirmed new tour, having last toured heavily on the Unlimited Love Tour through 2024. The band remains active and has toured regularly across recent years, so further dates are plausible, but any 2026 shows would be confirmed through official channels first. Live listings here reflect whatever has been officially scheduled.',
        ),
        1 => 
        array (
          'q' => 'What was the Red Hot Chili Peppers\' most recent tour?',
          'a' => 'The Unlimited Love Tour was Red Hot Chili Peppers\' most recent major run, supporting their 2022 albums Unlimited Love and Return of the Dream Canteen with John Frusciante back on guitar. It travelled through stadiums and amphitheatres across multiple legs before concluding in 2024. The band also made one-off appearances afterward, including a short set at the FireAid benefit in early 2025.',
        ),
        2 => 
        array (
          'q' => 'What kind of venues do Red Hot Chili Peppers play?',
          'a' => 'Red Hot Chili Peppers typically play large venues: stadiums, major arenas and open-air amphitheatres, reflecting their long-standing draw. On the Unlimited Love Tour the band headlined stadiums in many markets alongside amphitheatre dates. The venue type affects both the experience and ticket pricing, with stadium shows carrying the largest staging and amphitheatres offering cheaper lawn entry.',
        ),
        3 => 
        array (
          'q' => 'Who opens for Red Hot Chili Peppers on tour?',
          'a' => 'Support acts vary by leg and market. Across the Unlimited Love Tour, Red Hot Chili Peppers were joined by a rotating cast of opening artists rather than a single fixed support, a pattern they have used on previous runs. Any confirmed opener for a given Red Hot Chili Peppers date is listed alongside that show rather than fixed across the whole tour.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical Red Hot Chili Peppers setlist leans on the band\'s catalogue of singles alongside funk-driven jams and material from recent albums, usually opening with an improvised intro before the first song proper. Recent shows have balanced enduring hits such as Californication and Can\'t Stop with newer cuts like Black Summer and Tippa My Tongue. The Red Hot Chili Peppers setlist below reflects songs that have been live staples across their most recent touring.',
      ),
      'songs' => 
      array (
        0 => 'Can\'t Stop',
        1 => 'Around the World',
        2 => 'Dani California',
        3 => 'Scar Tissue',
        4 => 'The Zephyr Song',
        5 => 'Aquatic Mouth Dance',
        6 => 'Snow ((Hey Oh))',
        7 => 'Universally Speaking',
        8 => 'Eddie',
        9 => 'Soul to Squeeze',
        10 => 'Tippa My Tongue',
        11 => 'Tell Me Baby',
        12 => 'Black Summer',
        13 => 'Suck My Kiss',
        14 => 'Californication',
        15 => 'By the Way',
        16 => 'Nobody Weird Like Me',
        17 => 'Don\'t Forget Me',
      ),
      'encore' => 
      array (
        0 => 'Under the Bridge',
        1 => 'Give It Away',
      ),
      'note' => 'Setlists vary night to night. Red Hot Chili Peppers regularly rotate songs and jams between shows, so the running order and song choices change from date to date and this list is a guide rather than a fixed set.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Red Hot Chili Peppers play live?',
          'a' => 'Red Hot Chili Peppers live shows usually feature staples such as Can\'t Stop, Dani California, Scar Tissue, Californication, By the Way and Snow ((Hey Oh)), mixed with funk workouts and newer tracks like Black Summer and Tippa My Tongue. The band also opens many shows with an improvised jam. The exact selection rotates from night to night across a tour.',
        ),
        1 => 
        array (
          'q' => 'Does Red Hot Chili Peppers play an encore?',
          'a' => 'Yes. Red Hot Chili Peppers typically close with a short encore, and on recent tours Give It Away has been the regular closer. The song before it varies night to night, ranging from Under the Bridge to deeper cuts such as I Could Have Lied, as the band rotates its set. Give It Away ending the show has been the most consistent feature of the encore on the Unlimited Love Tour.',
        ),
        2 => 
        array (
          'q' => 'Do Red Hot Chili Peppers play Under the Bridge and Californication live?',
          'a' => 'Both are regular fixtures. Californication usually features in the main set, while Under the Bridge appears regularly too, sometimes late in the main set and sometimes in the encore. Neither is guaranteed on any single night given how much the band rotates its setlist, but both rank among the songs most likely to be performed.',
        ),
        3 => 
        array (
          'q' => 'How long is a Red Hot Chili Peppers concert?',
          'a' => 'A Red Hot Chili Peppers headline set generally runs around 90 minutes to two hours, opening with an improvised intro jam before moving into the main set and a short encore. The length varies by show and whether the band is headlining or playing a festival or benefit slot. Set times for a specific date are set by the venue and promoter.',
        ),
      ),
    ),
  ),
  'foo-fighters' => 
  array (
    'name' => 'Foo Fighters',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Foo Fighters ticket prices sit across a broad band because the band plays stadiums and large arenas, where seat location drives most of the cost. Upper-level and rear seating tends to land toward the lower end, while floor positions and seats near the stage carry a clear premium. The figures here describe how primary pricing generally works for a Foo Fighters show rather than any single date, so treat them as a guide to the typical spread.',
        1 => 'What a Foo Fighters ticket costs depends heavily on the market and how a given show sells. Major-market dates and stadium configurations tend to push prices higher than smaller arena stops, and premium packages add a further layer above standard seating. Resale prices can run well above face value for in-demand shows, so the bands below reflect primary sale levels and the realistic range most attendees encounter when buying direct.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / value seating',
          'desc' => 'The most affordable standard seats, typically the upper tiers and rear sections of the stadium or arena. Face value usually falls in the 75 to 130 USD range. The view is distant but the sound and stage production carry well across large rooms.',
        ),
        1 => 
        array (
          'name' => 'Lower level reserved',
          'desc' => 'Side and lower-bowl seating with a closer, more central view of the stage. These commonly run from about 130 to 230 USD depending on the market and exact location within the section.',
        ),
        2 => 
        array (
          'name' => 'Floor / front sections',
          'desc' => 'General-admission floor or front-block reserved seats near the stage, where demand is strongest. Expect roughly 230 to 350 USD at face value, with the larger stadium dates sitting toward the upper part of that figure.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Official packages bundling prime seating with early entry, merchandise or hospitality. These typically start around 350 USD and can reach 450 USD or more, varying by the inclusions offered at each venue.',
        ),
      ),
      'why' => 'Foo Fighters tours through Ticketmaster and Live Nation, which commonly apply dynamic pricing on higher-demand shows. Under that model, face value for popular sections can rise above the standard published price as seats sell, so two buyers at the same show may pay different amounts depending on when they purchase. Prices also shift between stadium and arena dates and between major and secondary markets. The bands here describe typical primary levels; resale can sit well above them for sought-after dates.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Foo Fighters tickets?',
          'a' => 'Foo Fighters tickets generally run from around 75 USD for upper-level seats to roughly 350 USD for floor and front sections, with premium and VIP packages reaching 450 USD or more. The exact price depends on the venue, the market and how a given show is selling.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Foo Fighters?',
          'a' => 'The lowest-cost option is usually upper-level or rear seating, which often starts near 75 to 90 USD at face value. Buying during the initial on-sale and choosing smaller-market or arena dates rather than major-city stadium shows tends to keep the price down.',
        ),
        2 => 
        array (
          'q' => 'Why do Foo Fighters ticket prices vary so much?',
          'a' => 'Prices vary because the band plays both stadiums and arenas, and seat location ranges from distant upper tiers to floor positions near the stage. Dynamic pricing on busier shows and differences between major and secondary markets widen the spread further.',
        ),
        3 => 
        array (
          'q' => 'Does Foo Fighters use dynamic pricing?',
          'a' => 'Yes. Like most major Ticketmaster and Live Nation tours, Foo Fighters dates often use dynamic pricing, where the face value of in-demand sections can rise as seats sell. This means the same section may cost different amounts depending on when you buy.',
        ),
        4 => 
        array (
          'q' => 'How much are Foo Fighters floor tickets?',
          'a' => 'Floor and front-section tickets typically cost around 230 to 350 USD at face value, sitting toward the upper end for large stadium dates. These are the highest-demand standard seats, so they tend to sell first and can carry dynamic pricing increases.',
        ),
        5 => 
        array (
          'q' => 'Are Foo Fighters VIP packages worth it?',
          'a' => 'VIP and premium packages add prime seating plus extras such as early entry, merchandise or hospitality, typically from about 350 USD upward. Whether they suit you depends on how much value you place on those inclusions over a standard ticket in the same area.',
        ),
        6 => 
        array (
          'q' => 'Why are resale Foo Fighters tickets more expensive than face value?',
          'a' => 'Resale prices reflect open-market demand rather than the band\'s set face value, so they can sit well above primary levels for popular shows. Buying during the official on-sale through Ticketmaster or Live Nation is usually the most cost-effective route.',
        ),
        7 => 
        array (
          'q' => 'Do Foo Fighters ticket prices differ between cities?',
          'a' => 'Yes. Major-market and stadium dates generally price higher than smaller arena stops, driven by demand and venue capacity. Get-in prices can differ substantially between cities even within the same tour, so comparing dates is worthwhile if budget is a priority.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Take Cover Tour 2026',
      'intro' => 
      array (
        0 => 'Foo Fighters tour dates for the Take Cover Tour 2026 cover a run of stadium and large-arena shows across multiple markets. The band returns to large-format touring with a set built around its catalogue rather than a single new album cycle, and the schedule mixes major-city stadium stops with arena dates. Specific cities and on-sale details are confirmed through official channels, so the picture below explains how the touring run is structured rather than listing fixed dates.',
        1 => 'As with previous Foo Fighters runs, the Take Cover Tour 2026 routes through stadiums and arenas with support acts varying by region. Tour dates are announced in stages and released through Ticketmaster and Live Nation, with presales typically preceding the general on-sale. Because routing and additions can change, checking the official listings remains the reliable way to confirm whether the band is playing a particular city.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Foo Fighters touring in 2026?',
          'a' => 'Yes. Foo Fighters are touring in 2026 on the Take Cover Tour, a run of stadium and arena shows across several markets. Dates are released in stages through official channels, so the current schedule is confirmed via Ticketmaster and Live Nation listings.',
        ),
        1 => 
        array (
          'q' => 'What is the name of the Foo Fighters 2026 tour?',
          'a' => 'The current run is the Take Cover Tour 2026. It follows the band\'s recent touring and continues their large-format stadium and arena shows rather than supporting one specific new release.',
        ),
        2 => 
        array (
          'q' => 'How long is a Foo Fighters concert?',
          'a' => 'Foo Fighters shows typically run well over two hours, often around two and a half hours, as the band tends to play long sets spanning its catalogue. Exact length varies by night and venue, and support acts add time before the headline set.',
        ),
        3 => 
        array (
          'q' => 'Where can I buy Foo Fighters tour tickets?',
          'a' => 'Official Foo Fighters tickets are sold through Ticketmaster and Live Nation, usually with artist and venue presales ahead of the general on-sale. Buying through these primary channels is the most reliable way to secure tickets at face value.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Foo Fighters setlist leans heavily on the band\'s hits while rotating in deeper cuts, so recent shows have opened with high-energy material before moving through the catalogue. The selections below reflect songs that have featured as genuine staples on the current touring run. Foo Fighters are known for long sets and occasional extended jams or drum segments, so the running order and exact choices shift from one night to the next.',
      ),
      'songs' => 
      array (
        0 => 'All My Life',
        1 => 'Times Like These',
        2 => 'The Pretender',
        3 => 'La Dee Da',
        4 => 'These Days',
        5 => 'My Hero',
        6 => 'Learn to Fly',
        7 => 'Run',
        8 => 'This Is a Call',
        9 => 'No Son of Mine',
        10 => 'Aurora',
        11 => 'White Limo',
        12 => 'Arlandria',
        13 => 'Monkey Wrench',
        14 => 'Hey, Johnny Park!',
        15 => 'Best of You',
      ),
      'encore' => 
      array (
        0 => 'Exhausted',
        1 => 'Everlong',
      ),
      'note' => 'Setlists vary night to night. Foo Fighters rotate songs and adjust the running order between shows, so the selection above reflects recent staples rather than a fixed set for any given date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Foo Fighters play live?',
          'a' => 'Foo Fighters open recent shows with material such as All My Life and Times Like These, and regularly play The Pretender, My Hero, Learn to Fly, Monkey Wrench and Best of You. Deeper cuts like This Is a Call, Aurora and Arlandria also appear, with the exact list changing each night.',
        ),
        1 => 
        array (
          'q' => 'Does Foo Fighters do an encore?',
          'a' => 'Yes. Foo Fighters typically close with an encore, often building to Everlong as the final song. Recent shows have paired it with the acoustic Exhausted, though the encore choices can vary between dates.',
        ),
        2 => 
        array (
          'q' => 'Do Foo Fighters play Everlong live?',
          'a' => 'Yes. Everlong is one of the band\'s most consistent live closers and usually arrives at or near the end of the set, frequently shifting from its quieter opening into a full-band finish. It remains a fixture on the current tour.',
        ),
        3 => 
        array (
          'q' => 'How many songs do Foo Fighters play in concert?',
          'a' => 'Foo Fighters generally play around eighteen to twenty-plus songs across a set lasting well over two hours, including the encore. The total and the running order vary from night to night as the band rotates material in and out.',
        ),
      ),
    ),
  ),
  'arctic-monkeys' => 
  array (
    'name' => 'Arctic Monkeys',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 65,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Arctic Monkeys ticket prices tend to sit in the band\'s established arena and stadium bracket, with the cheapest seats opening around 65 USD and most fans paying somewhere in the low three figures. Where a date falls on the run, the size of the venue and how close to the stage a seat is all move the figure. The headline numbers below describe how pricing has generally worked across recent Arctic Monkeys touring rather than any single advertised show.',
        1 => 'Because Arctic Monkeys play rooms ranging from large arenas to outdoor stadiums, the spread on any given night is wide. Standing or general-admission floor tickets, lower-bowl seats, upper tiers and hospitality packages each carry their own price, and demand for the Sheffield band remains high enough that the better blocks tend to clear quickly. Treat the bands below as a guide to relative cost rather than a fixed tariff.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, typically 65 to 110 USD. These seats sit high in the arena or toward the back of a stadium, with a full view of the stage and production but the longest sightline to the band.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side seating',
          'desc' => 'Mid-range seated tickets, usually 110 to 180 USD. Closer to the floor with a clearer view of Alex Turner and the band, these are among the first standard blocks to sell.',
        ),
        2 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing on the floor near the stage, generally 150 to 240 USD depending on the market. Best for fans who want to be in the thick of the crowd for the louder, faster portions of the set.',
        ),
        3 => 
        array (
          'name' => 'Premium and front blocks',
          'desc' => 'Prime seated or front-standing positions, roughly 220 to 330 USD. These are the closest non-package options and tend to carry the steepest standard pricing.',
        ),
        4 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages from around 300 up to 450 USD and occasionally beyond, bundling premium placement with early entry, hospitality or merchandise. Allocation is limited and varies by venue.',
        ),
      ),
      'why' => 'Like most large Ticketmaster and Live Nation tours, Arctic Monkeys shows generally use dynamic pricing, so the cost of a given seat can move up or down with demand rather than holding a single fixed figure. The most sought-after blocks for popular dates can climb well above their starting price, while quieter markets or late-released seats sometimes settle lower. Final totals also include booking and facility fees added at checkout, which is why the price you pay can differ from the headline face value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Arctic Monkeys tickets?',
          'a' => 'Arctic Monkeys tickets generally start around 65 USD for upper-tier seats and run to roughly 450 USD for VIP and hospitality packages. Most standard seated and standing tickets fall in the 110 to 240 USD range, with the exact figure depending on the venue, the market and how close to the stage a seat is.',
        ),
        1 => 
        array (
          'q' => 'Why do Arctic Monkeys ticket prices change?',
          'a' => 'Prices change mainly because Arctic Monkeys tours typically use dynamic pricing, which lets the cost of a seat rise or fall with demand. High-demand floor and lower-bowl blocks often increase, while quieter dates or late-released inventory can come down. Booking and facility fees added at checkout also affect the final total.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Arctic Monkeys?',
          'a' => 'The cheapest tickets are usually upper-tier or rear-stadium seats, often around 65 to 110 USD. These give a full view of the stage and the band\'s production from a distance, and they are generally the lowest-priced standard option before fees are added.',
        ),
        3 => 
        array (
          'q' => 'Are Arctic Monkeys VIP packages worth it?',
          'a' => 'Arctic Monkeys VIP and hospitality packages, typically priced from about 300 USD upward, suit fans who want premium placement plus extras such as early entry, hospitality access or exclusive merchandise. Whether they are worth it depends on how much you value those additions over a standard seat at a lower price.',
        ),
        4 => 
        array (
          'q' => 'How much is general admission for Arctic Monkeys?',
          'a' => 'General admission floor tickets for Arctic Monkeys usually cost between 150 and 240 USD, depending on the city and venue. This standing area puts you near the stage for the band\'s louder material and tends to be more expensive than upper-tier seating.',
        ),
        5 => 
        array (
          'q' => 'Do Arctic Monkeys ticket prices differ between arenas and stadiums?',
          'a' => 'Yes. Arctic Monkeys play both arenas and outdoor stadiums, and pricing reflects the room. Stadiums offer a wider spread, with cheaper distant seats but also large premium and pitch-standing allocations, while arenas tend to have a tighter range across fewer tiers.',
        ),
        6 => 
        array (
          'q' => 'Are there booking fees on Arctic Monkeys tickets?',
          'a' => 'Most Arctic Monkeys tickets carry booking and facility fees added during checkout, so the final price is higher than the listed face value. The exact fee depends on the seller and venue, which is one reason the amount you pay can differ from the advertised starting figure.',
        ),
        7 => 
        array (
          'q' => 'When do Arctic Monkeys tickets sell out?',
          'a' => 'Demand for Arctic Monkeys remains strong, so general-admission floor and lower-bowl blocks for popular dates often clear first, sometimes within the initial on-sale window. Upper-tier seats and quieter markets usually stay available longer, and additional inventory occasionally appears closer to the show.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Car Tour',
      'intro' => 
      array (
        0 => 'Arctic Monkeys\' most recent named run was The Car Tour, which supported their 2022 album The Car and carried the band through arenas, festivals and stadiums before concluding in late 2023. When the Sheffield group return to the road, tour dates are typically rolled out by region with a primary on-sale following any presale window. The schedule and any new routing are injected separately, so the details here describe how an Arctic Monkeys tour generally takes shape.',
        1 => 'Across The Car Tour, Arctic Monkeys paired material from their later, more orchestral records with the guitar-driven songs that built their reputation. Future tour dates would be expected to follow a similar pattern of large-venue headline shows and festival appearances. For confirmed cities, venues and on-sale timing, refer to the live listings, which update as fresh dates are announced rather than being fixed in this overview.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Are Arctic Monkeys touring in 2026?',
          'a' => 'Arctic Monkeys do not have a confirmed full tour announced at the time of writing, with The Car Tour having been their most recent major run before it wrapped in late 2023. Any new dates, festival slots or one-off shows appear in the live listings as they are confirmed, so checking those is the reliable way to see current plans.',
        ),
        1 => 
        array (
          'q' => 'What was the last Arctic Monkeys tour?',
          'a' => 'The last major Arctic Monkeys tour was The Car Tour, which ran in support of their seventh album The Car and travelled through arenas, stadiums and festivals before finishing in Dublin in October 2023. It spanned the band\'s full catalogue, from early singles to the more cinematic later material.',
        ),
        2 => 
        array (
          'q' => 'How long is an Arctic Monkeys concert?',
          'a' => 'An Arctic Monkeys headline show generally runs around an hour and three quarters to two hours, including the encore. The exact length depends on the venue and whether the date is a festival set, which tends to be shorter than a full arena or stadium headline performance.',
        ),
        3 => 
        array (
          'q' => 'Do Arctic Monkeys play festivals?',
          'a' => 'Yes, Arctic Monkeys regularly headline major festivals alongside their own arena and stadium dates, including a Glastonbury headline slot during The Car Tour. Festival sets are usually tighter and lean more heavily on the band\'s best-known singles than a full headline show.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'An Arctic Monkeys setlist draws across all seven studio albums, balancing the orchestral songs of their later records with the faster guitar tracks that opened their career. On The Car Tour the band typically built toward a high-energy run of singles before a quieter, more reflective stretch. The list below reflects songs that have been genuine live staples on recent Arctic Monkeys tours rather than a fixed running order for any single night.',
      ),
      'songs' => 
      array (
        0 => 'Sculptures of Anything Goes',
        1 => 'Brianstorm',
        2 => 'Snap Out of It',
        3 => 'Crying Lightning',
        4 => 'Teddy Picker',
        5 => 'The View From the Afternoon',
        6 => 'Don\'t Sit Down \'Cause I\'ve Moved Your Chair',
        7 => 'Why\'d You Only Call Me When You\'re High?',
        8 => 'Arabella',
        9 => 'Pretty Visitors',
        10 => 'Cornerstone',
        11 => 'Do I Wanna Know?',
        12 => '505',
        13 => 'Four Out of Five',
        14 => 'Body Paint',
        15 => 'I Bet You Look Good on the Dancefloor',
        16 => 'Mardy Bum',
        17 => 'I Wanna Be Yours',
        18 => 'R U Mine?',
      ),
      'encore' => 
      array (
        0 => 'Sculptures of Anything Goes',
        1 => 'I Bet You Look Good on the Dancefloor',
        2 => 'R U Mine?',
      ),
      'note' => 'Setlists vary from night to night, and Arctic Monkeys regularly rotate songs in and out, so the running order and song choices differ between shows on a tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Arctic Monkeys play live?',
          'a' => 'Arctic Monkeys live sets pull from across their catalogue, with staples including Brianstorm, Crying Lightning, Do I Wanna Know?, Why\'d You Only Call Me When You\'re High?, 505, I Bet You Look Good on the Dancefloor and R U Mine?. Recent tours also featured later material such as Sculptures of Anything Goes and Body Paint, though the exact selection changes night to night.',
        ),
        1 => 
        array (
          'q' => 'Do Arctic Monkeys play an encore?',
          'a' => 'Yes, Arctic Monkeys typically close with an encore. On recent tours it has often built around Sculptures of Anything Goes followed by I Bet You Look Good on the Dancefloor and R U Mine?, though the specific songs vary between shows.',
        ),
        2 => 
        array (
          'q' => 'Do Arctic Monkeys play old songs live?',
          'a' => 'Arctic Monkeys regularly revisit their early material live, with The View From the Afternoon, I Bet You Look Good on the Dancefloor, Mardy Bum and Teddy Picker among the older songs that appear in recent sets alongside their newer, more orchestral work.',
        ),
        3 => 
        array (
          'q' => 'How many songs are in an Arctic Monkeys set?',
          'a' => 'An Arctic Monkeys headline set usually runs to roughly 20 to 22 songs, including the encore. Festival appearances tend to be shorter and focus more tightly on the band\'s best-known singles, so the count there is generally lower.',
        ),
      ),
    ),
  ),
  'the-killers' => 
  array (
    'name' => 'The Killers',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 50,
      'range_high' => 300,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'The Killers play arenas, large theatres and festival main stages, and ticket prices tend to reflect that scale rather than the premiums seen on full stadium runs. Across a typical show, face values usually open around the low end for upper-tier and general admission positions and climb toward the upper bands for lower-bowl seating and front-of-stage spots. Where the band stages a Las Vegas residency, pricing sits noticeably higher than a standard arena night.',
        1 => 'Because most of The Killers\' dates are sold through Ticketmaster and Live Nation, the figures shown at checkout are primary face values before fees, and they shift with demand. The bands below give a realistic sense of what The Killers ticket prices look like for a standard arena or festival show; resale listings on secondary sites can run well above these numbers, particularly for sold-out markets and marquee festival appearances.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'General admission and upper tier',
          'desc' => 'Standing general admission at festivals and seats high in the arena typically fall in the 50 to 90 USD band. This is the most economical way to see The Killers, with a clear view of the stage and full sound, though distance from the band is greatest here.',
        ),
        1 => 
        array (
          'name' => 'Lower tier and mid-level seating',
          'desc' => 'Reserved seats around the sides and middle levels of the arena generally run 95 to 160 USD. These positions balance proximity and cost, and they are often the first standard seats to sell once a show goes on sale.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl and floor',
          'desc' => 'Closer floor seats and lower-bowl positions usually sit in the 165 to 230 USD range. Sightlines are strong and the band is within clear view, which makes this band popular for established fans.',
        ),
        3 => 
        array (
          'name' => 'Premium and front-of-stage',
          'desc' => 'Front-of-stage standing, premium packages and the best floor rows typically reach 235 to 300 USD, and occasionally higher for Las Vegas residency nights. These often bundle early entry or dedicated entrances depending on the venue.',
        ),
      ),
      'why' => 'The Killers tour mainly through Ticketmaster and Live Nation, which apply dynamic pricing to many dates. That means face values are not fixed: they move up or down with demand, so the same seat can cost more for a high-demand market or a festival headline slot than for a midweek arena date. Official platinum seats are priced this way as well, which is why a small number of premium positions can exceed the standard top band. The figures here describe typical primary face values before service fees; resale prices are set by sellers and frequently sit above them.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are The Killers tickets?',
          'a' => 'The Killers tickets generally range from about 50 USD for general admission and upper-tier seats to around 300 USD for premium and front-of-stage positions at a standard arena or festival show. Mid-level seating tends to sit between roughly 95 and 160 USD. Las Vegas residency dates usually price higher across the board.',
        ),
        1 => 
        array (
          'q' => 'Why do The Killers ticket prices change so much?',
          'a' => 'Most of The Killers\' dates use dynamic pricing through Ticketmaster and Live Nation, so face values rise and fall with demand. A high-demand market, a festival headline slot or a Las Vegas residency night will typically carry higher prices than a standard midweek arena date, even for comparable seats.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see The Killers live?',
          'a' => 'The lowest face values are usually general admission standing at festivals and seats high in the arena, generally from about 50 to 90 USD. Buying through the official primary platform when a show first goes on sale, before resale markups appear, tends to give the best chance at these prices.',
        ),
        3 => 
        array (
          'q' => 'Are The Killers tickets worth the price?',
          'a' => 'The Killers build their sets around long-running singles such as Mr. Brightside, When You Were Young and Human, and recent shows run as a near-continuous greatest-hits performance. For listeners who know the catalogue, the mid and lower-bowl bands tend to offer the clearest balance of proximity and cost.',
        ),
        4 => 
        array (
          'q' => 'How much are floor tickets for The Killers?',
          'a' => 'Floor and lower-bowl positions for The Killers usually fall between about 165 and 230 USD at face value, with the closest front-of-stage rows reaching the 235 to 300 USD premium band. Exact pricing varies by venue layout and demand on the date you choose.',
        ),
        5 => 
        array (
          'q' => 'Do The Killers offer VIP or premium packages?',
          'a' => 'Many of The Killers\' dates include premium options such as front-of-stage standing, enhanced seating and packages with early entry or dedicated entrances. These typically sit at the top of the range, around 235 to 300 USD, and occasionally above it for residency nights and high-demand markets.',
        ),
        6 => 
        array (
          'q' => 'Are resale tickets for The Killers more expensive than face value?',
          'a' => 'Usually yes. The bands here reflect primary face values before fees, while resale platforms let sellers set their own prices, which often run above face value for sold-out arena dates and festival headline slots. Checking the official primary seller first generally gives the lower figure.',
        ),
        7 => 
        array (
          'q' => 'How much were The Killers Las Vegas residency tickets?',
          'a' => 'Las Vegas residency shows priced well above a standard arena night, with many seats sitting in the low hundreds and the best positions reaching several hundred dollars on the secondary market. As the band\'s home city, Vegas dates draw strong demand and tend to carry the highest face values of any leg.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => '',
      'intro' => 
      array (
        0 => 'The Killers\' most recent branded run was the Rebel Diamonds Tour, named after the 2023 greatest-hits collection and built as a live retrospective spanning the band\'s two decades of singles; it ran through 2024 alongside the 20 Years of Hot Fuss residency, which wrapped in early 2025. In 2026 the band has no single branded tour name, instead playing a mix of headline shows and festival main stages across the United States and into Latin America. Dates are added in waves, so the schedule continues to fill out by market.',
        1 => 'Because these shows are structured around the catalogue rather than a single new album, they lean heavily on The Killers\' best-known material. Set lengths and staging scale with the venue, from intimate theatre nights to large outdoor festival stages. Live dates, on-sale times and any newly announced legs are confirmed through official channels and updated as they are released.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is The Killers touring in 2026?',
          'a' => 'Yes. The Killers are playing live in 2026 with a mix of headline shows and festival appearances across the United States and Latin America, rather than a single branded tour. The schedule is announced in waves, so additional shows and legs are added over time. Check official listings for the markets confirmed nearest your area.',
        ),
        1 => 
        array (
          'q' => 'What is The Killers\' current tour called?',
          'a' => 'There is no single branded tour name for The Killers\' 2026 dates, which are a mix of headline and festival shows. Their most recent named run was the Rebel Diamonds Tour, after the band\'s 2023 greatest-hits album, which ran in 2024 alongside the 20 Years of Hot Fuss residency that wrapped in early 2025.',
        ),
        2 => 
        array (
          'q' => 'Where are The Killers playing in 2026?',
          'a' => 'The Killers\' 2026 dates span US arenas, theatres and major festival stages, with additional appearances in Latin America including festival slots. Venue scale varies from intimate theatres to large outdoor sites. The full list of cities is published and updated through official channels as new dates are confirmed.',
        ),
        3 => 
        array (
          'q' => 'How long is a typical Killers concert?',
          'a' => 'A headline Killers show generally runs around 90 minutes to two hours, with festival slots shorter and arena or residency nights longer. The set is paced as a near-continuous run of singles, and exact length depends on the venue and whether it is a headline or festival appearance.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Killers setlist functions as a greatest-hits run, drawing on singles from Hot Fuss through to the later albums. Recent shows reliably feature Mr. Brightside, When You Were Young, Human and Somebody Told Me, with the band closing on the long-running anthem All These Things That I\'ve Done. The exact running order and deeper cuts shift between dates, so any The Killers setlist should be read as a guide rather than a fixed list.',
      ),
      'songs' => 
      array (
        0 => 'Mr. Brightside',
        1 => 'Somebody Told Me',
        2 => 'Smile Like You Mean It',
        3 => 'When You Were Young',
        4 => 'Read My Mind',
        5 => 'Spaceman',
        6 => 'Human',
        7 => 'The Man',
        8 => 'Runaways',
        9 => 'Shot at the Night',
        10 => 'Caution',
        11 => 'All These Things That I\'ve Done',
        12 => 'For Reasons Unknown',
        13 => 'A Dustland Fairytale',
        14 => 'This Is Your Life',
        15 => 'Jenny Was a Friend of Mine',
        16 => 'Bones',
        17 => 'Believe Me Natalie',
      ),
      'encore' => 
      array (
        0 => 'When You Were Young',
        1 => 'Mr. Brightside',
      ),
      'note' => 'Setlists vary night to night, so the order and song choices above will differ from any individual show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do The Killers play live?',
          'a' => 'The Killers reliably play Mr. Brightside, When You Were Young, Human, Somebody Told Me, Smile Like You Mean It and Spaceman, usually closing on All These Things That I\'ve Done. Recent sets run as a greatest-hits selection, with deeper cuts such as A Dustland Fairytale and For Reasons Unknown rotating in and out.',
        ),
        1 => 
        array (
          'q' => 'Do The Killers do an encore?',
          'a' => 'Yes, headline Killers shows typically close with an encore, often built around When You Were Young and Mr. Brightside. Festival slots are sometimes performed as a continuous set without a formal encore break. The exact structure depends on the venue and whether it is a headline or festival date.',
        ),
        2 => 
        array (
          'q' => 'What song do The Killers open with?',
          'a' => 'Openers vary by date, but recent Killers shows have frequently begun with Mr. Brightside or an album-track introduction before moving into the singles run. Because the band rotates the running order across the tour, the opening song is one of the elements most likely to change between shows.',
        ),
        3 => 
        array (
          'q' => 'Does The Killers\' setlist change every night?',
          'a' => 'Yes. The core singles such as Mr. Brightside and When You Were Young appear at most shows, but the surrounding songs and the running order shift from night to night. Deeper album cuts rotate in and out, so two dates can differ noticeably even within the same week.',
        ),
      ),
    ),
  ),
  'oasis' => 
  array (
    'name' => 'Oasis',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 90,
      'range_high' => 2500,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Oasis ticket prices sit toward the higher end of the live rock market, reflecting the stadium scale of the reunion shows and the level of demand the band attracts. Across a typical run, the cheapest upper-tier seats tend to start in the lower hundreds of dollars, while standing pitch positions and premium hospitality reach considerably higher. Where you sit, the size of the stadium and how early you buy all shape what you pay.',
        1 => 'Because Oasis play stadiums rather than arenas, the spread between the lowest and highest Oasis ticket prices is wide. A back-of-the-bowl seat is a different proposition from a front-pitch standing place or a packaged premium experience, and the figures below reflect that range. Resale listings can push prices well above face value for the most sought-after shows, so the official primary sale is usually the better-value route.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier seated',
          'desc' => 'The most affordable way in, usually in the higher sections of the stadium bowl. Expect roughly 90 to 160 USD per ticket at face value, with views from distance but the full production and sound.',
        ),
        1 => 
        array (
          'name' => 'Lower tier seated',
          'desc' => 'Closer seated positions in the lower bowl, generally around 160 to 300 USD. A balanced option for those who want a guaranteed seat with a clearer sightline to the stage.',
        ),
        2 => 
        array (
          'name' => 'General admission standing',
          'desc' => 'Standing places on the stadium pitch, typically 300 to 600 USD. The closest you can get to the band on a standard ticket, with no allocated seat and an early arrival rewarded with a better spot.',
        ),
        3 => 
        array (
          'name' => 'Premium and hospitality',
          'desc' => 'Packaged experiences combining prime seating or pitch access with hospitality, early entry or merchandise. These commonly run from 600 USD up to around 2,500 USD for the most comprehensive packages.',
        ),
      ),
      'why' => 'Oasis tickets are sold across multiple price tiers because stadiums hold a wide spread of seating, from distant upper sections to front-pitch standing. Several markets on the reunion tour also used Ticketmaster\'s dynamic pricing, where the live price of in-demand tickets moves with demand during the sale rather than staying fixed at the advertised figure, which is what pushed some UK standing prices well above their initial level. The band opted out of dynamic pricing for its North American and several later international dates, so those went on sale at set face values instead. Resale platforms add a further layer, and listings there frequently sit above face value for the busiest shows.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Oasis tickets?',
          'a' => 'Oasis tickets generally range from around 90 USD for upper-tier seats to about 2,500 USD for premium hospitality packages. Standing pitch tickets typically fall between 300 and 600 USD. Exact prices depend on the venue, the city and how close to the stage you sit.',
        ),
        1 => 
        array (
          'q' => 'Why did Oasis tickets get more expensive during the sale?',
          'a' => 'Several Oasis dates used Ticketmaster\'s dynamic pricing, which lets the price of high-demand tickets rise during the sale in response to demand. This is why some UK standing tickets ended up costing significantly more than their advertised starting figure rather than staying fixed.',
        ),
        2 => 
        array (
          'q' => 'Do Oasis use dynamic pricing for all shows?',
          'a' => 'No. Oasis used dynamic pricing for the initial UK and Ireland shows but opted out of it for the North American leg and several other later international dates, which went on sale at fixed face values to avoid a repeat of the earlier pricing complaints.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Oasis live?',
          'a' => 'The cheapest Oasis tickets are usually upper-tier seated positions, which tend to start in the lower hundreds of dollars at face value. Buying through the official primary sale rather than resale, and choosing a less in-demand date, generally keeps the cost down.',
        ),
        4 => 
        array (
          'q' => 'How much are Oasis standing tickets?',
          'a' => 'Oasis general admission standing tickets, which give access to the stadium pitch, typically cost between 300 and 600 USD at face value. They place you closest to the stage on a standard ticket but come without an allocated seat.',
        ),
        5 => 
        array (
          'q' => 'Are Oasis tickets cheaper on resale sites?',
          'a' => 'Not usually. For high-demand Oasis shows, resale listings tend to sit above face value rather than below it, so the official primary sale is generally the better-value option. Prices on resale platforms vary with how sought-after a particular date is.',
        ),
        6 => 
        array (
          'q' => 'What do premium Oasis tickets include?',
          'a' => 'Premium and hospitality Oasis tickets bundle prime seating or pitch access with extras such as early entry, hospitality areas or merchandise. They sit at the top of the price range, commonly from around 600 USD up to roughly 2,500 USD depending on what is included.',
        ),
        7 => 
        array (
          'q' => 'Why do Oasis ticket prices vary so much between cities?',
          'a' => 'Oasis play stadiums of differing sizes, and prices reflect local demand, venue capacity and whether a date used dynamic or fixed pricing. A seat in one city may cost more than the equivalent in another simply because of how that particular show was priced and how sought-after it is.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Oasis Live \'25 Tour',
      'intro' => 
      array (
        0 => 'The Oasis Live \'25 Tour marked the band\'s return to the stage after reuniting, with Liam and Noel Gallagher performing together for the first time in many years. The run was built around large stadium shows rather than arenas, taking in dates across the UK and Ireland before extending to North America, Australia, South America and Asia. Oasis tour dates for the reunion sold rapidly, with demand far outstripping the available capacity.',
        1 => 'For anyone tracking Oasis tour dates, the reunion was structured as a single extended run rather than a string of separate legs spread over years. The band kept a consistent stadium-focused approach throughout, playing major venues in each market. Whether further dates follow depends on announcements made by the band and its promoters, and any new shows are confirmed officially rather than through speculation.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What is the name of the Oasis tour?',
          'a' => 'The reunion run is called the Oasis Live \'25 Tour. It brought Liam and Noel Gallagher back to the stage together for a stadium tour spanning the UK and Ireland followed by international dates in North America, Australia, South America and Asia.',
        ),
        1 => 
        array (
          'q' => 'Is Oasis touring in 2026?',
          'a' => 'Oasis completed the Live \'25 reunion run across 2025, and no official 2026 dates have been confirmed. Any future shows are announced directly by the band and its promoters, so it is worth checking official sources rather than relying on unconfirmed reports.',
        ),
        2 => 
        array (
          'q' => 'Where did the Oasis reunion tour play?',
          'a' => 'The Oasis Live \'25 Tour opened in the UK and Ireland with stadium shows, then travelled to North America, Australia, South America and Asia. It was a stadium tour throughout, with the band playing major venues in each country rather than smaller arenas.',
        ),
        3 => 
        array (
          'q' => 'Are Liam and Noel Gallagher both in the reunion tour?',
          'a' => 'Yes. The Oasis Live \'25 Tour reunites brothers Liam and Noel Gallagher on stage together, which is the core of the reunion after their long split. Both front the band across every date of the run.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'The Oasis live setlist on the reunion tour draws heavily on the band\'s first two albums, with the bulk of the show built around Definitely Maybe and (What\'s the Story) Morning Glory? A typical Oasis setlist opens with \'Hello\' and \'Acquiesce\' before working through a run of singles and album favourites, closing the main set with the songs that have defined the band\'s live reputation. The selections below reflect the staples that anchored the reunion shows.',
      ),
      'songs' => 
      array (
        0 => 'Hello',
        1 => 'Acquiesce',
        2 => 'Morning Glory',
        3 => 'Some Might Say',
        4 => 'Bring It On Down',
        5 => 'Cigarettes & Alcohol',
        6 => 'Fade Away',
        7 => 'Supersonic',
        8 => 'Roll With It',
        9 => 'Talk Tonight',
        10 => 'Half the World Away',
        11 => 'Little by Little',
        12 => 'D\'You Know What I Mean',
        13 => 'Stand by Me',
        14 => 'Cast No Shadow',
        15 => 'Slide Away',
        16 => 'Whatever',
        17 => 'Live Forever',
        18 => 'Rock \'n\' Roll Star',
      ),
      'encore' => 
      array (
        0 => 'The Masterplan',
        1 => 'Don\'t Look Back in Anger',
        2 => 'Wonderwall',
        3 => 'Champagne Supernova',
      ),
      'note' => 'Setlists vary night to night, so the running order and song choices can change from show to show. Treat this as a guide to the staples rather than a fixed list for any particular date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Oasis play live?',
          'a' => 'Oasis lean heavily on Definitely Maybe and (What\'s the Story) Morning Glory? in concert, opening with \'Hello\' and \'Acquiesce\' and working through singles such as \'Supersonic\', \'Cigarettes & Alcohol\', \'Some Might Say\' and \'Roll With It\' before the main closers.',
        ),
        1 => 
        array (
          'q' => 'Does Oasis do an encore?',
          'a' => 'Yes. Oasis typically close the night with an encore built around \'Don\'t Look Back in Anger\', \'Wonderwall\' and \'Champagne Supernova\', often with \'The Masterplan\' leading into it. These songs anchor the end of the reunion shows.',
        ),
        2 => 
        array (
          'q' => 'How long is an Oasis concert?',
          'a' => 'An Oasis reunion show runs to roughly 23 songs across the main set and encore, which generally amounts to a little over two hours including support. The exact length depends on the night and the running order chosen.',
        ),
        3 => 
        array (
          'q' => 'Does Oasis open with the same song every night?',
          'a' => 'On the reunion tour Oasis have consistently opened with \'Hello\' followed by \'Acquiesce\', though setlists vary night to night and the wider running order can shift, so the order is not guaranteed to be identical at every show.',
        ),
      ),
    ),
  ),
  'pearl-jam' => 
  array (
    'name' => 'Pearl Jam',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 300,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Pearl Jam ticket prices generally reflect the band\'s arena-scale touring, with most face-value seats falling between roughly 60 and 300 USD before fees. As with the majority of Live Nation and Ticketmaster runs, the band uses dynamic pricing, so the cost of a given seat can move with demand across an on-sale. Upper-tier seats sit at the lower end of that band, while floor positions and limited premium packages reach the upper end.',
        1 => 'Where you sit accounts for most of the variation in Pearl Jam ticket prices. The band typically plays multi-night arena stands rather than single-date stadium shows, which keeps the overall range narrower than that of larger stadium acts. Resale availability and premium VIP packages can push prices well above face value, particularly for closer floor sections, opening nights and the band\'s home-market dates.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper Bowl / Value',
          'desc' => 'The most affordable Pearl Jam seats, generally in the upper levels of the arena. Expect roughly 60 to 110 USD at face value, offering a full view of the stage at the lowest cost.',
        ),
        1 => 
        array (
          'name' => 'Lower Bowl / Mid-Tier',
          'desc' => 'Side and lower-level seating closer to the stage, typically around 110 to 190 USD. A common middle option that balances proximity with price for most attendees.',
        ),
        2 => 
        array (
          'name' => 'Floor / General Admission',
          'desc' => 'Standing or seated floor positions near the front, usually between 190 and 300 USD. These sell quickly given the band\'s standing-floor tradition at many shows.',
        ),
        3 => 
        array (
          'name' => 'VIP / Premium Package',
          'desc' => 'Premium experiences combining prime seating with extras such as early entry or merchandise. These are priced separately and commonly run several times the cost of a base seat.',
        ),
      ),
      'why' => 'Pearl Jam tours through Ticketmaster and Live Nation, which apply dynamic pricing on most dates. Under this model, face values are not fixed: they adjust to live demand as a show sells, so an identical seat can cost more during peak buying and ease back as interest settles. Service and facility fees are added at checkout and vary by venue. Resale prices on the secondary market are set by individual sellers and frequently exceed face value, especially for sought-after floor sections and the band\'s hometown dates.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Pearl Jam tickets?',
          'a' => 'Pearl Jam tickets generally range from about 60 to 300 USD at face value, depending on the seat. Upper-level seats are the most affordable, while floor positions and premium sections command the higher end. Dynamic pricing and added fees mean the final checkout figure can vary by date and demand.',
        ),
        1 => 
        array (
          'q' => 'Why do Pearl Jam ticket prices change?',
          'a' => 'Most Pearl Jam dates use dynamic pricing through Ticketmaster, so face values rise and fall with live demand during an on-sale. A seat that costs one figure early can shift as a show sells. Service fees added at checkout and seller-set resale prices on the secondary market add further variation.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Pearl Jam?',
          'a' => 'Upper-bowl seating is typically the lowest-cost way to see Pearl Jam, often starting near 60 USD at face value before fees. Buying during the initial on-sale rather than on the resale market usually keeps prices closest to face value, particularly for non-premium sections.',
        ),
        3 => 
        array (
          'q' => 'How much do Pearl Jam floor tickets cost?',
          'a' => 'Floor and general-admission positions for Pearl Jam usually run between roughly 190 and 300 USD at face value. As the band maintains a standing-floor tradition at many shows, these sections tend to sell quickly and often carry higher resale prices.',
        ),
        4 => 
        array (
          'q' => 'Are Pearl Jam VIP packages worth it?',
          'a' => 'Pearl Jam VIP and premium packages bundle prime seating with extras such as early entry or merchandise, and are priced separately from standard tickets. They commonly cost several times a base seat, so their value depends on how much the added benefits matter to you.',
        ),
        5 => 
        array (
          'q' => 'Do Pearl Jam ticket prices include fees?',
          'a' => 'It depends on the on-sale. Pearl Jam has used all-in pricing on recent runs, where the displayed price already includes service fees and only taxes are added at checkout. On standard Ticketmaster sales and on resale platforms, however, service, facility or buyer fees can be added on top of the listed price, raising the final total.',
        ),
        6 => 
        array (
          'q' => 'Are Pearl Jam tickets cheaper than stadium acts?',
          'a' => 'Pearl Jam generally plays multi-night arena stands rather than stadiums, so their face-value range tends to be narrower than that of the largest stadium tours. The band\'s pricing typically sits in a mid-tier band, though premium floor seats and resale listings can still reach high figures.',
        ),
        7 => 
        array (
          'q' => 'How much are Pearl Jam tickets on the resale market?',
          'a' => 'Resale prices for Pearl Jam are set by individual sellers and frequently exceed face value, sometimes substantially for floor sections, opening nights and home-market dates. Pearl Jam has also run a Fan-to-Fan Face Value Exchange on recent tours to cap resale at the original price, so buying through official channels is usually the closest you will get to face value.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Dark Matter World Tour',
      'intro' => 
      array (
        0 => 'Pearl Jam\'s most recent touring cycle was the Dark Matter World Tour, supporting their 2024 album of the same name. The run moved through arena dates across North America, Europe, Australia and other regions before wrapping in Pittsburgh in May 2025. The band has historically favoured multi-night stands in major cities rather than single appearances, and continues to add occasional festival and one-off dates between full tours.',
        1 => 'The Dark Matter World Tour paired new material with the deep back catalogue Pearl Jam has built since the early 1990s. Specific tour dates, on-sale times and venues are announced through official channels and vary by market. The band\'s touring footprint typically spans arenas and select festival appearances, with international legs scheduled alongside North American runs.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Pearl Jam touring in 2026?',
          'a' => 'As of mid-2026, Pearl Jam has not announced a full new tour, though the band has confirmed scattered 2026 appearances and has signalled future touring plans. Their last major run, the Dark Matter World Tour, concluded in May 2025. New dates are confirmed venue by venue through official announcements, so availability depends on the specific market.',
        ),
        1 => 
        array (
          'q' => 'What is Pearl Jam\'s most recent tour called?',
          'a' => 'Pearl Jam\'s most recent tour was the Dark Matter World Tour, named after their 2024 studio album and running from May 2024 through May 2025. It blended songs from that record with material drawn from across the band\'s catalogue, played mainly across arenas with some festival appearances.',
        ),
        2 => 
        array (
          'q' => 'Where did Pearl Jam play on tour?',
          'a' => 'The Dark Matter World Tour covered arena venues across North America, Europe, Australia and other regions. Pearl Jam tends to play multi-night stands in larger cities. Confirmed venues and routing are published through official channels and differ by leg and market.',
        ),
        3 => 
        array (
          'q' => 'How long is a Pearl Jam concert?',
          'a' => 'Pearl Jam shows are typically long by arena standards, often running well over two hours and sometimes approaching three with encores. The band is known for extended sets and improvised song choices, so the exact running time varies from night to night.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Pearl Jam setlist draws on more than three decades of material, mixing songs from the 2024 album Dark Matter with the band\'s catalogue staples. The group is well known for changing the setlist substantially each night, so while certain anthems recur frequently, no two shows are identical. The selections below reflect songs that were genuine live regulars on the Dark Matter World Tour.',
      ),
      'songs' => 
      array (
        0 => 'Even Flow',
        1 => 'Corduroy',
        2 => 'Given to Fly',
        3 => 'Dark Matter',
        4 => 'Wreckage',
        5 => 'Scared of Fear',
        6 => 'Running',
        7 => 'Mind Your Manners',
        8 => 'Why Go',
        9 => 'Animal',
        10 => 'Daughter',
        11 => 'Jeremy',
        12 => 'Do the Evolution',
        13 => 'Black',
        14 => 'Porch',
        15 => 'Rearviewmirror',
        16 => 'Elderly Woman Behind the Counter in a Small Town',
        17 => 'Nothingman',
        18 => 'State of Love and Trust',
        19 => 'Once',
      ),
      'encore' => 
      array (
        0 => 'Better Man',
        1 => 'Alive',
        2 => 'Yellow Ledbetter',
        3 => 'Rockin\' in the Free World',
      ),
      'note' => 'Pearl Jam are known for varying their setlist heavily from night to night, so the songs and running order change at every show. The list above reflects recent live staples rather than a fixed set, and the band frequently rotates in deep cuts and covers.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Pearl Jam play live?',
          'a' => 'Pearl Jam regularly play staples such as Even Flow, Corduroy, Black, Jeremy and Given to Fly, alongside newer material from Dark Matter like the title track, Wreckage and Scared of Fear. The band varies the set heavily each night, so the exact selection changes from show to show.',
        ),
        1 => 
        array (
          'q' => 'Does Pearl Jam do an encore?',
          'a' => 'Yes. Pearl Jam typically close with an extended encore that often features Better Man, Alive and Yellow Ledbetter, sometimes alongside a cover such as Neil Young\'s Rockin\' in the Free World. The specific encore songs vary from one night to the next.',
        ),
        2 => 
        array (
          'q' => 'Does Pearl Jam play the same setlist every night?',
          'a' => 'No. Pearl Jam are well known for changing their setlist substantially at every show, rotating deep cuts, album tracks and covers around a loose core of anthems. Fans attending multiple nights can expect markedly different sets each time.',
        ),
        3 => 
        array (
          'q' => 'What new songs does Pearl Jam play from Dark Matter?',
          'a' => 'On the Dark Matter World Tour Pearl Jam regularly performed Dark Matter tracks including the title song Dark Matter, Wreckage, Scared of Fear and Running. These newer numbers were woven through a set otherwise built from the band\'s wider catalogue, and the chosen songs varied by night.',
        ),
      ),
    ),
  ),
  'doja-cat' => 
  array (
    'name' => 'Doja Cat',
    'genre' => 'Pop / Hip-Hop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 300,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Doja Cat ticket prices generally reflect her arena-level production, with face-value seats commonly opening in the lower band and rising toward the front of the floor and premium sections. As a pop and hip-hop headliner playing rooms such as Little Caesars Arena and Madison Square Garden, she draws steady demand, so the cheaper upper-tier seats tend to move first and the better views carry a clear premium.',
        1 => 'Where you sit shapes the cost more than anything else with Doja Cat. Upper-level and behind-stage seats sit at the bottom of the range, lower-bowl and floor positions occupy the middle, and front-floor or VIP packages reach the top. Resale figures fluctuate with the strength of each individual market, so a primary purchase at face value is usually the steadier guide to what a seat is worth.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Higher seating in the back and sides of the arena, typically around 60 to 95 USD. The most economical way in, with a full view of the staging from a distance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl',
          'desc' => 'Side and end seating closer to the action, usually in the region of 95 to 160 USD. A balanced choice for sightlines and proximity without floor pricing.',
        ),
        2 => 
        array (
          'name' => 'Floor',
          'desc' => 'Standing or seated floor positions near the stage, generally about 160 to 250 USD depending on the row and the market.',
        ),
        3 => 
        array (
          'name' => 'VIP and premium',
          'desc' => 'Front-floor seats and VIP packages with early entry or merchandise, commonly between 250 and 300 USD and occasionally higher in busier cities.',
        ),
      ),
      'why' => 'Doja Cat tours through Ticketmaster and Live Nation, which apply dynamic pricing on most major runs. That means the listed price for a given seat can move up or down with real-time demand rather than staying fixed, so two buyers in the same section may pay different amounts depending on when they purchase. Service and facility fees are added at checkout on top of the seat price. Premium markets and the most sought-after dates tend to sit at the upper end of the band, while quieter dates and upper-tier seats stay nearer the floor of the range.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Doja Cat tickets?',
          'a' => 'Doja Cat tickets typically range from around 60 USD for upper-level seats to roughly 300 USD for front-floor and VIP positions at face value. Lower-bowl seats generally fall between those figures, and dynamic pricing means the exact cost shifts with demand for each date.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Doja Cat live?',
          'a' => 'The cheapest seats are usually in the upper level or behind-stage sections, often starting near 60 USD before fees. These move quickly, so buying early in the on-sale window tends to secure the lowest available face-value prices.',
        ),
        2 => 
        array (
          'q' => 'How much do Doja Cat floor tickets cost?',
          'a' => 'Floor tickets for Doja Cat generally run from about 160 to 250 USD at face value, depending on the row and the city. Positions nearest the stage sit at the higher end of that band.',
        ),
        3 => 
        array (
          'q' => 'Are there VIP packages for Doja Cat concerts?',
          'a' => 'Yes, Doja Cat tours usually offer VIP packages that bundle a premium seat with extras such as early entry, dedicated check-in or exclusive merchandise. These commonly start around 250 USD and rise from there depending on what the package includes.',
        ),
        4 => 
        array (
          'q' => 'Why do Doja Cat ticket prices change?',
          'a' => 'Prices change because her tours use dynamic pricing through Ticketmaster, which adjusts seat costs in line with live demand. A high-interest date or market can push prices up, while quieter shows may stay closer to the base level.',
        ),
        5 => 
        array (
          'q' => 'Do Doja Cat ticket prices include fees?',
          'a' => 'No, the advertised seat price does not include fees. Service and facility charges are added at checkout, so the final total is higher than the headline figure for the seat itself.',
        ),
        6 => 
        array (
          'q' => 'Are resale Doja Cat tickets more expensive than face value?',
          'a' => 'Resale prices vary widely by market and can sit above or below face value. Some dates have appeared on secondary sites for under 100 USD, while busier markets and premium seats trade well above the original price.',
        ),
        7 => 
        array (
          'q' => 'Which Doja Cat shows tend to be the most expensive?',
          'a' => 'Large-market dates such as New York and Los Angeles generally carry the highest prices, reflecting stronger demand. Smaller-market arena dates and upper-level seats usually stay nearer the lower end of the range.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Tour Ma Vie World Tour',
      'intro' => 
      array (
        0 => 'Doja Cat tour dates currently fall under the Tour Ma Vie World Tour, her third headline concert tour, supporting the album Vie. The run opened across Oceania and Asia before extending into a wider 2026 schedule taking in Latin America, Europe, the United Kingdom and North America, marking her most geographically broad touring effort to date and her first headline tour spanning multiple continents.',
        1 => 'The arena-scale production visits major venues including Little Caesars Arena in Detroit and Madison Square Garden in New York, alongside dates in cities such as London, Paris, Lisbon, Mexico City, Chicago and Los Angeles. Because individual dates are added, adjusted or sold out over time, the live schedule and on-sale details for Doja Cat are confirmed separately rather than fixed within this overview.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Doja Cat touring in 2026?',
          'a' => 'Yes, Doja Cat is touring in 2026 on the Tour Ma Vie World Tour, with dates across Latin America, Europe, the United Kingdom and North America. The North American leg runs into late 2026 and is set to close with a show at Madison Square Garden in New York.',
        ),
        1 => 
        array (
          'q' => 'What is Doja Cat\'s current tour called?',
          'a' => 'Doja Cat\'s current tour is the Tour Ma Vie World Tour, her third headline tour. It supports her studio album Vie and is her widest-reaching live run so far, covering multiple continents.',
        ),
        2 => 
        array (
          'q' => 'What venues does Doja Cat play on the Tour Ma Vie World Tour?',
          'a' => 'Doja Cat plays arena-scale venues on this tour, including Little Caesars Arena in Detroit and Madison Square Garden in New York, along with major rooms in cities such as London, Paris, Mexico City, Chicago and Los Angeles.',
        ),
        3 => 
        array (
          'q' => 'How long is a Doja Cat concert?',
          'a' => 'A Doja Cat headline show on this tour generally runs in the region of an hour and three quarters, built around a multi-act structure. Exact running times vary by night and by venue.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Doja Cat setlist on the Tour Ma Vie World Tour pulls together her best-known singles with newer material from the album Vie, arranged across a multi-act show. Recent dates have opened with Cards and moved through staples such as Kiss Me More, Get Into It (Yuh), Need to Know and Paint the Town Red before closing on Say So. The order and selections are adjusted from night to night.',
      ),
      'songs' => 
      array (
        0 => 'Cards',
        1 => 'Kiss Me More',
        2 => 'Get Into It (Yuh)',
        3 => 'Gorgeous',
        4 => 'Take Me Dancing',
        5 => 'Woman',
        6 => 'Acts of Service',
        7 => 'Agora Hills',
        8 => 'Make It Up',
        9 => 'All Mine',
        10 => 'Ain\'t Shit',
        11 => 'Paint the Town Red',
        12 => 'Juicy',
        13 => 'Need to Know',
        14 => 'Streets',
        15 => 'Demons',
        16 => 'Tia Tamera',
        17 => 'Boss Bitch',
        18 => 'Say So',
        19 => 'Jealous Type',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, so the songs and running order on any given date may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Doja Cat play live?',
          'a' => 'Doja Cat\'s live shows feature hits such as Say So, Kiss Me More, Need to Know, Paint the Town Red, Woman and Get Into It (Yuh), mixed with newer tracks from the album Vie. The exact selection changes from one date to the next.',
        ),
        1 => 
        array (
          'q' => 'Does Doja Cat play Say So live?',
          'a' => 'Yes, Say So is a regular feature of Doja Cat\'s recent setlists and has typically appeared near the end of the main show on the Tour Ma Vie World Tour, though placement can vary by night.',
        ),
        2 => 
        array (
          'q' => 'Does Doja Cat do an encore?',
          'a' => 'Doja Cat\'s recent shows have generally run as a continuous performance rather than a separate encore segment. The set is structured into distinct acts, with the closing numbers built into the main run.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Doja Cat perform?',
          'a' => 'Doja Cat usually performs in the region of 20 or more songs across a headline date, spanning her singles and album material. The precise count differs from show to show.',
        ),
      ),
    ),
  ),
  'tyler-the-creator' => 
  array (
    'name' => 'Tyler, The Creator',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Tyler, The Creator ticket prices sit in the range usual for a headline arena act, with the spread on any given night shaped by the venue, the city and where the seat falls. Lower-bowl and floor positions carry the steepest face values, while upper-tier seats stay closer to the entry point. As a Grammy-winning rapper and producer who fills indoor arenas rather than stadiums, his pricing tends to land in the mid band for a hip-hop headliner rather than at the top.',
        1 => 'Across a Tyler, The Creator run, the gap between the cheapest upper-level seat and a front-floor or VIP package is wide. Stage production for the Chromakopia shows places a premium on sightlines, so floor and front-section seats command more. The figures below reflect typical primary on-sale bands; resale prices move independently and can sit above or below face depending on demand for a particular date.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / rear sections',
          'desc' => 'Seats high in the upper bowl or behind the stage, typically around 60 to 110 USD. The most economical way in, with a full view of the production from distance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / side floor',
          'desc' => 'Lower-tier seating and side-floor positions, usually about 110 to 190 USD. A closer, more central angle on the stage without front-row pricing.',
        ),
        2 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing floor access near the stage, generally in the 150 to 230 USD band. Popular for the energy of the pit, though it is unreserved standing.',
        ),
        3 => 
        array (
          'name' => 'Premium / front floor',
          'desc' => 'Front-floor reserved seats and prime lower-bowl positions, commonly 200 to 300 USD depending on the market.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP bundles with early entry, premium placement and merchandise items, ranging roughly 280 to 350 USD and occasionally higher in major cities.',
        ),
      ),
      'why' => 'Most Tyler, The Creator dates are sold through Ticketmaster, which applies dynamic pricing on selected sections. This means the listed price can rise or fall with live demand rather than holding a fixed figure, so the same seat type may cost more for a high-demand city or a fast-selling night. Service and facility fees are added at checkout on top of the face value. Standard reserved sections away from the most sought-after seats are less likely to move sharply, while floor and front-block prices fluctuate most.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Tyler, The Creator tickets?',
          'a' => 'Tyler, The Creator tickets generally run from around 60 USD for upper-level seats to about 300 USD for premium front-floor positions, with official VIP packages reaching roughly 350 USD. As an arena headliner he sits in the mid band for a major hip-hop act rather than at stadium-level pricing. Exact figures depend on the city, the venue and how a particular date is selling.',
        ),
        1 => 
        array (
          'q' => 'Why do Tyler, The Creator ticket prices change?',
          'a' => 'Most Tyler, The Creator dates use Ticketmaster dynamic pricing, so prices on selected sections move with live demand rather than staying fixed. A fast-selling city or a single high-demand night can push the same seat above its starting figure. Service and facility fees are also added at checkout, which raises the total beyond the listed face value.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Tyler, The Creator?',
          'a' => 'The cheapest seats are typically in the upper bowl or rear sections behind the stage, often starting near 60 USD before fees. These offer a complete view of the Chromakopia production from a distance. Buying early in an on-sale and avoiding floor or premium blocks keeps the cost closest to the entry point.',
        ),
        3 => 
        array (
          'q' => 'Are Tyler, The Creator VIP tickets worth it?',
          'a' => 'Tyler, The Creator VIP packages, priced roughly 280 to 350 USD, bundle early entry, premium placement and merchandise items. Whether they suit you depends on how much you value proximity to the stage and the extras; the core show is the same from any seat. They sell out quickly in larger markets.',
        ),
        4 => 
        array (
          'q' => 'How much is general admission for Tyler, The Creator?',
          'a' => 'General admission floor access for Tyler, The Creator usually falls in the 150 to 230 USD band. It places you in the standing pit near the stage, which many prefer for the atmosphere, though it is unreserved and first-come for position once doors open.',
        ),
        5 => 
        array (
          'q' => 'Do Tyler, The Creator ticket prices vary by city?',
          'a' => 'Yes. Tyler, The Creator prices vary noticeably by market, with larger cities and higher-capacity arenas commanding more than smaller dates. UK and European shows are priced in local currency and convert to a broadly similar band, while demand in major hubs can lift floor and lower-bowl figures.',
        ),
        6 => 
        array (
          'q' => 'Are resale tickets more expensive than face value?',
          'a' => 'Resale Tyler, The Creator tickets can sit above or below face value depending on demand for the specific date. Sold-out or high-profile nights often carry a markup on the secondary market, while quieter dates sometimes drop below the original price. Always check the face value first before comparing resale listings.',
        ),
        7 => 
        array (
          'q' => 'What fees are added to Tyler, The Creator tickets?',
          'a' => 'Service and facility charges are added at checkout on top of the listed face value, which is standard for Ticketmaster and Live Nation arena sales. These can add a meaningful amount per ticket, so the final total is higher than the headline price. The exact fee depends on the venue and ticket type.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Chromakopia: The World Tour',
      'intro' => 
      array (
        0 => 'Tyler, The Creator toured behind his album Chromakopia on Chromakopia: The World Tour, an arena run spanning North America, the UK, Europe, Oceania and beyond alongside festival appearances. Tour dates were announced in batches by region, with arena shows forming the core of the schedule. The system lists live dates separately as they are confirmed, so this page focuses on how the tour is structured rather than fixed calendar listings.',
        1 => 'The Chromakopia shows leaned heavily on the album of the same name while folding in catalogue staples, with Lil Yachty and Paris Texas featuring among the supporting acts on selected legs. As an established arena headliner, Tyler, The Creator typically plays multiple nights in major markets, and demand for those dates tended to be high once they went on sale.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Tyler, The Creator touring in 2026?',
          'a' => 'Chromakopia: The World Tour ran into early 2026 and wrapped its final dates in March 2026, so the tour itself has now concluded. No further tour had been announced as of mid-2026. Any newly confirmed live dates are listed separately as they are announced.',
        ),
        1 => 
        array (
          'q' => 'What is Tyler, The Creator\'s current tour called?',
          'a' => 'Tyler, The Creator\'s most recent tour was Chromakopia: The World Tour, named after his Chromakopia album. It was an arena-scale run covering North America, the UK, Europe, Oceania and other regions, with festival appearances added on certain legs.',
        ),
        2 => 
        array (
          'q' => 'Who is opening for Tyler, The Creator?',
          'a' => 'Lil Yachty and Paris Texas featured as supporting acts on Chromakopia: The World Tour, though line-ups differed by region and date. Festival appearances did not carry the same support bill. Check the specific date listing for the confirmed openers.',
        ),
        3 => 
        array (
          'q' => 'Does Tyler, The Creator play arenas or stadiums?',
          'a' => 'Tyler, The Creator headlines indoor arenas rather than stadiums, often playing multiple nights in major cities, as he did on Chromakopia: The World Tour. He also appears at large outdoor festivals, where the format and set length differ from his own headline shows.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Tyler, The Creator setlist on Chromakopia: The World Tour drew first from the Chromakopia album, opening with tracks such as St. Chroma, Rah Tah Tah and Noid before reaching back through his catalogue. Recent shows ran around ninety minutes and balanced newer material with established staples like EARFQUAKE and NEW MAGIC WAND. The running order below reflects songs played on recent 2025 and 2026 dates.',
      ),
      'songs' => 
      array (
        0 => 'St. Chroma',
        1 => 'Rah Tah Tah',
        2 => 'Noid',
        3 => 'Darling, I',
        4 => 'Sugar on My Tongue',
        5 => 'Take Your Mask Off',
        6 => 'Sticky',
        7 => 'Who Dat Boy',
        8 => 'ARE WE STILL FRIENDS?',
        9 => 'DOGTOOTH',
        10 => 'WUSYANAME',
        11 => 'IFHY',
        12 => 'EARFQUAKE',
        13 => 'Tamale',
        14 => 'Thought I Was Dead',
        15 => 'Like Him',
        16 => 'NEW MAGIC WAND',
        17 => 'See You Again',
        18 => 'Big Poe',
        19 => 'I Hope You Find Your Way Home',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, and the exact songs, order and any swaps differ between dates and cities. The list above reflects recent performances and should be treated as a guide rather than a fixed running order.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Tyler, The Creator play live?',
          'a' => 'Tyler, The Creator opens recent shows with Chromakopia tracks such as St. Chroma, Rah Tah Tah and Noid, then works through staples including EARFQUAKE, NEW MAGIC WAND, See You Again and WUSYANAME. Sets run roughly ninety minutes and mix the newest album with older fan favourites. The exact list changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Tyler, The Creator play EARFQUAKE live?',
          'a' => 'Yes. EARFQUAKE remains a regular fixture in Tyler, The Creator\'s sets and is one of his most recognised live moments. It usually appears in the back half of the show alongside other catalogue staples such as NEW MAGIC WAND and See You Again, though placement can shift between dates.',
        ),
        2 => 
        array (
          'q' => 'Does Tyler, The Creator do an encore?',
          'a' => 'Tyler, The Creator\'s recent Chromakopia shows generally ran as a continuous set rather than breaking for a separate encore, closing on tracks like Balloon and I Hope You Find Your Way Home. Formats can change by date, so some nights may differ from this structure.',
        ),
        3 => 
        array (
          'q' => 'How long is a Tyler, The Creator concert?',
          'a' => 'Recent Tyler, The Creator headline shows have lasted around ninety minutes, covering roughly twenty songs across the Chromakopia album and his wider catalogue. Festival appearances are shorter and carry a trimmed set. Exact length varies by date and city.',
        ),
      ),
    ),
  ),
  'nicki-minaj' => 
  array (
    'name' => 'Nicki Minaj',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Nicki Minaj ticket prices tend to span a wide band because her shows are arena productions sold through dynamic pricing. Entry-level upper-tier seats usually sit at the lower end, while floor positions and front blocks rise sharply depending on the market and how quickly a date sells. As a rough guide, most standard seats fall somewhere in the middle of the band, with premium and VIP options reaching the upper figures.',
        1 => 'Where you sit accounts for most of the variation in Nicki Minaj ticket prices. Larger markets and high-demand dates push averages up, while added shows and weeknight dates often soften pricing. Resale figures can run well above face value when a date is in heavy demand, so comparing the primary allocation against verified resale before buying is generally the sensible approach.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, generally around 45 to 110 USD. These seats sit higher in the bowl or toward the back, with a full view of the stage and screens but more distance from the performance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side floor',
          'desc' => 'Mid-range seating, typically 110 to 250 USD. These positions offer a closer, more central sightline in the lower level or along the sides of the floor, and tend to be the first standard seats to sell in busy markets.',
        ),
        2 => 
        array (
          'name' => 'Front floor and premium blocks',
          'desc' => 'Higher-demand positions near the stage, usually 250 to 420 USD. Pricing here moves most under dynamic models, climbing as a date fills and easing on slower-selling shows.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and hospitality bundles, commonly 350 to 600 USD or more. These can include premium seating, early entry, merchandise or lounge access, and are priced above standard tickets for the added inclusions rather than the seat alone.',
        ),
      ),
      'why' => 'Most Nicki Minaj dates are sold through Ticketmaster using dynamic pricing, which means face value shifts with demand rather than staying fixed. A seat in the same section can cost more on a fast-selling date and less on a quieter one, and added shows or second legs sometimes see prices adjusted downward. Resale prices move independently and can sit above or below the primary allocation. The figures here describe typical bands rather than a set rate, so checking the live primary and verified resale options for a specific date gives the most accurate picture.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Nicki Minaj tickets?',
          'a' => 'Nicki Minaj tickets generally range from about 45 USD for upper-tier seats to 600 USD or more for VIP packages, with most standard seats falling in the middle of that band. Because her arena shows use dynamic pricing, the exact figure depends on the market, the seating section and how quickly the date is selling.',
        ),
        1 => 
        array (
          'q' => 'Why do Nicki Minaj ticket prices vary so much?',
          'a' => 'The variation comes mainly from seating location and dynamic pricing. A floor seat near the stage can cost several times more than an upper-tier seat, and prices on busy dates climb as the show fills. Larger markets and weekend dates tend to run higher, while added shows can be softer.',
        ),
        2 => 
        array (
          'q' => 'Are Nicki Minaj VIP tickets worth it?',
          'a' => 'VIP packages cost more because they bundle extras such as premium seating, early entry, merchandise or lounge access alongside the ticket. Whether they represent value depends on how much those inclusions matter to you, as the seat itself can sometimes be bought separately for less.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Nicki Minaj live?',
          'a' => 'The lowest prices are usually upper-tier and rear seating, often starting around 45 USD on the primary allocation. Buying when a tour leg is first announced, considering weeknight or added dates, and comparing verified resale against face value all help keep costs down.',
        ),
        4 => 
        array (
          'q' => 'Do Nicki Minaj ticket prices drop closer to the show?',
          'a' => 'They sometimes do on slower-selling dates, where resale listings can fall below face value in the final days. On high-demand shows the opposite is more common, with prices holding firm or rising. There is no guarantee either way, so it depends on how a specific date is selling.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Nicki Minaj?',
          'a' => 'Floor tickets typically run from around 250 to 420 USD for front and premium blocks, with side-floor positions sometimes lower. These are among the most demand-sensitive seats, so prices can climb noticeably on fast-selling dates under dynamic pricing.',
        ),
        6 => 
        array (
          'q' => 'Is there a booking fee on Nicki Minaj tickets?',
          'a' => 'Most primary tickets carry service and facility fees added at checkout, which is standard for arena shows. The advertised seat price is usually before fees, so the final total can be higher than the listed figure. Checking the full breakdown at checkout shows the complete cost.',
        ),
        7 => 
        array (
          'q' => 'Are resale Nicki Minaj tickets safe to buy?',
          'a' => 'Resale tickets are widely available and can be reliable when bought through verified platforms that guarantee entry. Prices there move independently of face value and can sit above or below it depending on demand, so comparing resale against the primary allocation before buying is generally worthwhile.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Pink Friday 2 World Tour',
      'intro' => 
      array (
        0 => 'Nicki Minaj\'s most recent headline run was the Pink Friday 2 World Tour, an arena production supporting her fifth studio album and drawing across her catalogue. Whether new Nicki Minaj tour dates are on sale at any given time depends on her current schedule, which is announced periodically rather than running continuously. When a fresh leg or run is confirmed, dates are typically released market by market through the primary ticketing partner.',
        1 => 'Tour dates for Nicki Minaj generally appear in waves, with markets and venues confirmed ahead of each leg. Demand tends to concentrate on larger cities, where shows can sell quickly, and additional dates are sometimes added in response. Checking her official channels and the primary ticketing listing for any current on-sale is the most reliable way to confirm what is scheduled.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Nicki Minaj touring in 2026?',
          'a' => 'Whether Nicki Minaj has confirmed 2026 dates depends on her current schedule, which is announced in waves rather than running year-round. Her most recent major run was the Pink Friday 2 World Tour. For any live on-sale, checking her official channels and the primary ticketing listing gives the current position.',
        ),
        1 => 
        array (
          'q' => 'What is Nicki Minaj\'s current tour called?',
          'a' => 'Her most recent headline tour was the Pink Friday 2 World Tour, an arena run supporting the album of the same name. Any future tour would carry its own name, typically announced alongside the first wave of dates.',
        ),
        2 => 
        array (
          'q' => 'How long is a Nicki Minaj concert?',
          'a' => 'A Nicki Minaj headline show typically runs a little over two hours, covering a broad selection from her catalogue plus newer material. Exact running time varies by date, and support acts can add to the overall evening.',
        ),
        3 => 
        array (
          'q' => 'How do I find Nicki Minaj tour dates near me?',
          'a' => 'The most reliable approach is to check her official site and the primary ticketing listing, which show confirmed markets and venues for any current leg. Dates are released in waves, so signing up for alerts helps you catch a new on-sale when it is announced.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Nicki Minaj setlist draws across more than a decade of material, mixing chart singles, mixtape favourites and recent album tracks into a single arena show. On the Pink Friday 2 World Tour her set leaned on hits such as Super Bass and Starships alongside newer cuts like FTCU, with the running order shifting from night to night. The songs below reflect staples that have featured regularly on her recent live dates.',
      ),
      'songs' => 
      array (
        0 => 'I\'m the Best',
        1 => 'Barbie Dangerous',
        2 => 'FTCU',
        3 => 'Big Difference',
        4 => 'Pink Birthday',
        5 => 'Feeling Myself',
        6 => 'High School',
        7 => 'Chun-Li',
        8 => 'Red Ruby Da Sleeze',
        9 => 'Barbie World',
        10 => 'Roman\'s Revenge',
        11 => 'Right Thru Me',
        12 => 'Save Me',
        13 => 'Truffle Butter',
        14 => 'Super Freaky Girl',
        15 => 'Anaconda',
        16 => 'Itty Bitty Piggy',
        17 => 'Super Bass',
        18 => 'Moment 4 Life',
        19 => 'Starships',
      ),
      'encore' => 
      array (
        0 => 'Everybody',
      ),
      'note' => 'Setlists vary from night to night, and the songs above reflect recent live staples rather than a fixed running order. The exact selection, length and arrangement can change between dates and tour legs.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Nicki Minaj play live?',
          'a' => 'Nicki Minaj\'s recent setlists feature staples such as Super Bass, Starships, Anaconda, Chun-Li and Moment 4 Life, alongside newer tracks like FTCU and Super Freaky Girl. The exact selection changes from night to night, so the running order on any given date can differ from the list here.',
        ),
        1 => 
        array (
          'q' => 'Does Nicki Minaj play Super Bass live?',
          'a' => 'Super Bass has been a regular feature of her recent shows and is one of her most reliable live inclusions. As with any song, its place in the set can vary by date, but it has been a consistent staple on recent tours.',
        ),
        2 => 
        array (
          'q' => 'Does Nicki Minaj perform an encore?',
          'a' => 'On the Pink Friday 2 World Tour she has typically closed with an encore, returning for Everybody after the main set. The structure can vary by date and tour, so some shows may differ.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Nicki Minaj play in concert?',
          'a' => 'A Nicki Minaj headline set typically includes a large number of songs, often blending full tracks with shorter segments and medleys across her catalogue. The total varies by date, but recent shows have run well past twenty selections over a little more than two hours.',
        ),
      ),
    ),
  ),
  'eminem' => 
  array (
    'name' => 'Eminem',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 90,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Eminem ticket prices sit at the higher end of hip-hop because he performs rarely and almost always at stadium or festival scale, where demand consistently outstrips supply. Face value typically opens around the lower three figures for upper-tier seats and climbs steeply for floor and lower-bowl positions. Because his appearances are infrequent headline slots rather than long touring runs, individual shows tend to draw unusually broad demand, which keeps the overall band wide.',
        1 => 'Where an Eminem appearance forms part of a wider event, such as a Grand Prix weekend or a festival, the concert access is often bundled into a larger ticket, so the figures below describe standalone concert pricing rather than packaged event admission. Resale markets for his shows usually run well above face value given how seldom he plays.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seated',
          'desc' => 'Higher-level seated positions, typically USD 90 to 180. The most economical way in, with a full stage view from distance and reliable availability when seats are released at face value.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side stage',
          'desc' => 'Lower seated sections nearer the stage, generally USD 180 to 350. A clearer sightline of the staging and video walls without standing on the floor.',
        ),
        2 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing access to the main floor, usually USD 300 to 550. Closest to the stage for those who prefer to stand, and often the first category to sell out.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-of-floor positions and hospitality packages, commonly USD 600 to 1,200 or more. May include early entry, dedicated entrances and merchandise, though contents vary by event and rarely include a meet-and-greet.',
        ),
      ),
      'why' => 'Most of the major promoters and ticketing platforms involved in Eminem\'s appearances use dynamic pricing, meaning face values move with demand rather than staying fixed. Popular sections can be released at higher prices as a show sells, so two buyers in the same category may pay different amounts depending on timing. Because his shows are infrequent and heavily subscribed, prices tend to rise rather than fall after the initial release.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Eminem tickets?',
          'a' => 'Eminem tickets generally run from around USD 90 for upper-tier seated positions to USD 1,200 or more for premium and VIP packages. Lower-bowl seats and general admission floor typically fall between USD 180 and 550. His shows price toward the upper end of hip-hop because he plays stadium-scale events only occasionally.',
        ),
        1 => 
        array (
          'q' => 'Why are Eminem tickets so expensive?',
          'a' => 'Eminem performs live very rarely and almost always at stadium or major-festival scale, so demand is concentrated into a small number of dates. That scarcity, combined with dynamic pricing that lets face values rise with demand, pushes his ticket prices above those of artists who tour regularly across smaller venues.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Eminem live?',
          'a' => 'The most economical option is usually an upper-tier or rear seated position, often starting near USD 90 at face value. Buying when seats are first released, rather than on resale, tends to give the lowest price, as resale markets for his rare appearances typically sit well above face value.',
        ),
        3 => 
        array (
          'q' => 'Does Eminem use dynamic pricing for tickets?',
          'a' => 'Yes. The promoters and platforms handling Eminem\'s appearances generally use dynamic pricing, so face values shift with demand rather than remaining fixed. Sought-after sections can be re-released at higher prices as a show sells, which is why two buyers in the same category sometimes pay different amounts.',
        ),
        4 => 
        array (
          'q' => 'How much do Eminem VIP or floor tickets cost?',
          'a' => 'General admission floor access typically costs between USD 300 and 550, while premium and VIP packages commonly range from USD 600 to 1,200 or higher. VIP packages may add early entry, a dedicated entrance and merchandise, though exact contents differ by event and seldom include a meet-and-greet.',
        ),
        5 => 
        array (
          'q' => 'Are Eminem tickets cheaper on resale?',
          'a' => 'Usually not. Because Eminem appears live so infrequently, resale prices for his shows tend to run above face value rather than below it. The lowest prices are normally found at the initial face-value release, so buying early through official channels is generally the better-value route.',
        ),
        6 => 
        array (
          'q' => 'Do Eminem tickets include festival or event access?',
          'a' => 'It depends on the event. When Eminem headlines as part of a wider occasion, such as a Grand Prix weekend or a festival, concert access is often bundled into a larger admission ticket. The price bands here describe standalone concert tickets rather than packaged multi-day or event entry.',
        ),
        7 => 
        array (
          'q' => 'How far in advance should I buy Eminem tickets?',
          'a' => 'Because Eminem plays so rarely, his shows tend to sell quickly and prices climb as availability tightens. Buying at the initial release generally secures the lowest face value, since both official dynamic pricing and resale markets push figures upward as a date approaches sell-out.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => '',
      'intro' => 
      array (
        0 => 'Eminem does not currently run a conventional named tour. For much of the past decade his live work has consisted of occasional headline appearances at major festivals and one-off stadium events rather than a routed concert series, so Eminem tour dates tend to surface as individual bookings rather than a continuous schedule. Any live dates are listed separately by the system as they are confirmed.',
        1 => 'When Eminem does appear, the shows are typically large-scale and infrequent, often tied to album anniversaries or marquee events. Because there is no announced touring run, prospective attendees are best served by watching official channels for individual date confirmations rather than expecting a multi-city itinerary.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Eminem touring in 2026?',
          'a' => 'Eminem has no confirmed conventional tour at present. His recent live activity has centred on occasional headline appearances at festivals and major one-off events rather than a routed multi-city tour. Any individual confirmed dates are listed separately as they are announced through official channels.',
        ),
        1 => 
        array (
          'q' => 'Does Eminem have a named tour right now?',
          'a' => 'No. There is no officially announced named Eminem tour currently running. Reports of a titled farewell tour have circulated online but are not confirmed by official sources, so we list only individually verified appearances rather than an unconfirmed tour name.',
        ),
        2 => 
        array (
          'q' => 'Why does Eminem play so few live shows?',
          'a' => 'Eminem has long preferred selective, high-profile appearances over extended touring. Rather than routing many consecutive dates, he tends to headline a small number of festivals or standalone stadium events, which is why his live calendar is sparse compared with artists who tour every album cycle.',
        ),
        3 => 
        array (
          'q' => 'Where can I find confirmed Eminem live dates?',
          'a' => 'Confirmed Eminem dates are listed separately by the system as they are announced and verified. Because his appearances are infrequent and often tied to specific events, it is worth checking official listings rather than relying on speculative tour schedules circulating on third-party sites.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Eminem\'s recent setlists draw heavily on his career-spanning catalogue, pairing early Slim Shady-era material with later singles and a handful of newer tracks. A typical Eminem setlist opens at pace and runs through roughly two hours of material, mixing chart hits with deeper cuts that reward longer-standing listeners. The selection below reflects songs that have featured consistently across his most recent appearances.',
      ),
      'songs' => 
      array (
        0 => 'Till I Collapse',
        1 => 'White America',
        2 => 'Square Dance',
        3 => 'Sing for the Moment',
        4 => 'Without Me',
        5 => 'Cinderella Man',
        6 => 'Love the Way You Lie',
        7 => 'Not Afraid',
        8 => 'Houdini',
        9 => 'Lucifer',
        10 => 'Berzerk',
        11 => 'Rap God',
        12 => 'The Monster',
        13 => 'Godzilla',
        14 => 'My Name Is',
        15 => 'The Real Slim Shady',
        16 => 'Like Toy Soldiers',
        17 => 'Venom',
        18 => 'Criminal',
        19 => 'Lose Yourself',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, and the running order, deep cuts and any guest appearances change between performances. Treat this as a representative guide rather than a fixed list for any specific show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Eminem play live?',
          'a' => 'Eminem\'s recent shows typically feature staples such as Lose Yourself, Without Me, Till I Collapse, Rap God, The Monster and Not Afraid, alongside early material like My Name Is and Criminal and newer tracks including Houdini and Godzilla. The exact selection shifts between performances.',
        ),
        1 => 
        array (
          'q' => 'Does Eminem perform Lose Yourself live?',
          'a' => 'Yes. Lose Yourself is a fixture of Eminem\'s live shows and almost always closes the main set. As his best-known song and an Academy Award winner, it is among the most reliable inclusions whenever he performs, typically arriving as the final or near-final number.',
        ),
        2 => 
        array (
          'q' => 'Does Eminem do an encore?',
          'a' => 'Eminem\'s headline appearances generally run as a single continuous set rather than breaking for a formal encore, often building to Lose Yourself as the closer. Because many of his recent shows form part of larger events with fixed running times, an extended encore is uncommon.',
        ),
        3 => 
        array (
          'q' => 'How long is an Eminem concert?',
          'a' => 'An Eminem headline set typically lasts around two hours, give or take depending on the event. Festival and event slots can run shorter than a standalone show, and the running order and song count vary from night to night, so duration is approximate rather than fixed.',
        ),
      ),
    ),
  ),
  'future' => 
  array (
    'name' => 'Future',
    'genre' => 'Hip-Hop',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Future ticket prices generally sit in the arena tier of live hip-hop, below the stadium bracket occupied by the genre\'s biggest crossover headliners. Across a typical run, entry-level seats tend to open in the lower band, with floor and lower-bowl positions costing more and hospitality packages sitting at the top. Demand is heaviest in his home market of Atlanta and in larger coastal cities, where availability tightens fastest and resale activity is most pronounced.',
        1 => 'What you pay for Future largely depends on where he plays and how close you sit rather than a fixed figure. Upper-level seats remain the most economical way in, mid-tier and lower-bowl seats carry a premium, and the floor commands the highest face value before VIP. Because most of his recent dates run through Ticketmaster and Live Nation, the prices below reflect realistic primary face values rather than resale, which can move higher when a show sells quickly.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / get-in',
          'desc' => 'The most affordable seats, usually in the upper bowl or further from the stage. Face value typically falls in the 75 to 130 USD range and is the standard entry point for most arena dates.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier / lower bowl',
          'desc' => 'Side and corner lower-bowl seating with a clearer sightline. These generally run from about 130 to 230 USD depending on the venue and how strong demand is for that market.',
        ),
        2 => 
        array (
          'name' => 'Floor / front sections',
          'desc' => 'Floor positions and the closest blocks to the stage, where energy is highest. Prices here tend to sit between roughly 230 and 350 USD before any hospitality is added.',
        ),
        3 => 
        array (
          'name' => 'VIP / packages',
          'desc' => 'Premium and VIP packages bundling prime seating with early entry or merchandise. These typically range from around 300 up to 450 USD, and limited premium inventory can reach the top of that band.',
        ),
      ),
      'why' => 'Future\'s tours are routed through Ticketmaster and Live Nation, which use dynamic pricing on a large share of seats. That means face values can rise and fall in real time with demand rather than staying fixed, so a section listed at one figure early in a sale may cost more once a show is moving quickly. Released-in-stages pricing, platinum seats and add-on fees also affect the final total, which is why the bands above describe general ranges rather than a single set price.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Future tickets?',
          'a' => 'Future tickets generally range from about 75 USD for upper-level seats to around 450 USD for VIP packages, placing him in the arena tier of live hip-hop. Floor and lower-bowl seats sit in between, and exact face value depends on the city, the venue and how quickly the show sells.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see Future live?',
          'a' => 'The cheapest seats are usually in the upper bowl, typically starting around 75 to 130 USD at face value. Buying early in a sale and choosing a less in-demand market rather than Atlanta or a major coastal city tends to give the lowest prices before fees.',
        ),
        2 => 
        array (
          'q' => 'Why do Future ticket prices change so much?',
          'a' => 'Most Future dates run through Ticketmaster and Live Nation, which apply dynamic pricing to many seats. Prices move with live demand, so the same section can cost more once a show starts selling quickly, and platinum or premium seats are priced separately from standard inventory.',
        ),
        3 => 
        array (
          'q' => 'How much are floor seats for a Future concert?',
          'a' => 'Floor and front-section seats for Future typically cost between roughly 230 and 350 USD at face value before VIP add-ons. These are the closest positions to the stage and usually the first standard seats to sell out in larger markets.',
        ),
        4 => 
        array (
          'q' => 'Are there VIP packages for Future shows?',
          'a' => 'Yes, Future tours commonly offer VIP and premium packages that bundle prime seating with perks such as early entry or merchandise. These generally run from about 300 to 450 USD, with the most limited premium inventory reaching the higher end of that range.',
        ),
        5 => 
        array (
          'q' => 'Do Future ticket prices include fees?',
          'a' => 'The face values quoted are before service and facility fees, which Ticketmaster adds at checkout. The final total is usually noticeably higher than the listed seat price, so it is worth viewing the all-in figure before completing a purchase.',
        ),
        6 => 
        array (
          'q' => 'Are Future tickets more expensive in Atlanta?',
          'a' => 'Hometown shows in Atlanta tend to see the strongest demand, which can push both primary and resale prices above other markets. Availability there often tightens fastest, so prices for comparable seats can sit higher than in smaller cities.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Future tickets on resale?',
          'a' => 'Not usually. Resale prices for Future often run above face value when a show is in demand, particularly for floor seats. Primary face value through the official seller is generally the lower option, though resale can soften closer to the date for slower-selling shows.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'We Trust You Tour',
      'intro' => 
      array (
        0 => 'Future\'s most recent headline run was the We Trust You Tour, staged jointly with producer Metro Boomin in support of their collaborative albums We Don\'t Trust You and We Still Don\'t Trust You. The North American run played arenas through the second half of 2024, and Future tour dates from that cycle leaned heavily on the two records alongside his deep back catalogue. Any future routing is announced by the artist\'s team and his promoters rather than fixed in advance.',
        1 => 'Beyond the joint headline tour, Future regularly appears at major festivals and one-off arena shows, so tour activity is not limited to a single named run. When new dates are confirmed they are typically routed through Ticketmaster and Live Nation, with on-sales staggered by market. Checking the official listings is the most reliable way to see where he is currently scheduled to perform.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Future touring in 2026?',
          'a' => 'Future\'s most recent major headline run was the 2024 We Trust You Tour with Metro Boomin. Any 2026 activity tends to centre on festival appearances and individual shows rather than a confirmed headline tour, so the official Ticketmaster and Live Nation listings are the place to confirm current dates.',
        ),
        1 => 
        array (
          'q' => 'What was Future\'s last tour?',
          'a' => 'Future\'s last major tour was the We Trust You Tour, a co-headline arena run with Metro Boomin across North America in 2024. It supported their collaborative albums We Don\'t Trust You and We Still Don\'t Trust You and ran through the late summer of that year.',
        ),
        2 => 
        array (
          'q' => 'Who did Future tour with on the We Trust You Tour?',
          'a' => 'Future co-headlined the We Trust You Tour with producer Metro Boomin. Metro opened each night with a solo DJ set before the pair performed material from their two collaborative albums and Future\'s wider catalogue together.',
        ),
        3 => 
        array (
          'q' => 'Where does Future usually perform?',
          'a' => 'Future typically headlines arenas in major North American markets and appears at large festivals worldwide. His hometown of Atlanta is a regular stop, and his routing tends to favour larger coastal and Southern cities where demand for his catalogue is strongest.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Future setlist draws on more than a decade of material, mixing chart singles with mixtape staples that long-time crowds know word for word. Recent shows have balanced collaborative tracks from his work with Metro Boomin against signature solo records, so the running order shifts depending on the run. The songs below reflect the live staples that have anchored his recent setlist rather than a fixed order for any single night.',
      ),
      'songs' => 
      array (
        0 => 'Stick Talk',
        1 => 'Wait for U',
        2 => 'Mask Off',
        3 => 'March Madness',
        4 => 'Type Shit',
        5 => 'Like That',
        6 => 'Same Damn Time',
        7 => 'Fuck Up Some Commas',
        8 => 'Thought It Was a Drought',
        9 => 'Where Ya At',
        10 => 'Move That Dope',
        11 => 'Low Life',
        12 => 'Superhero (Heroes & Villains)',
        13 => 'Karate Chop',
        14 => 'Turn On the Lights',
        15 => 'Honest',
        16 => 'We Don\'t Trust You',
        17 => 'Wicked',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Future rotates songs in and out across a run, so the exact selection and order differ from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Future play live?',
          'a' => 'Future\'s live shows typically feature staples such as Mask Off, March Madness, Stick Talk, Wait for U and Type Shit, mixed with mixtape favourites like Same Damn Time and Thought It Was a Drought. The exact selection changes from night to night across a tour.',
        ),
        1 => 
        array (
          'q' => 'Does Future play Mask Off in concert?',
          'a' => 'Yes, Mask Off is one of Future\'s most reliable live moments and a regular fixture in recent sets. As his biggest crossover single, it usually lands as a peak point in the show, though its placement in the running order can vary.',
        ),
        2 => 
        array (
          'q' => 'Does Future do an encore?',
          'a' => 'Future\'s recent arena shows have generally run as a continuous set rather than building to a separate encore. The pace stays high through the closing tracks, so the show tends to end on a run of his best-known records rather than a staged return to the stage.',
        ),
        3 => 
        array (
          'q' => 'How long is a Future concert?',
          'a' => 'A Future headline set typically runs in the region of 80 to 100 minutes, though on co-headline runs such as the tour with Metro Boomin the overall night is longer once the opening DJ set and shared segments are included. Exact length varies by venue and routing.',
        ),
      ),
    ),
  ),
  'peso-pluma' => 
  array (
    'name' => 'Peso Pluma',
    'genre' => 'Regional Mexican',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 800,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Peso Pluma ticket prices vary widely from night to night because his shows use dynamic pricing, where the cost of a seat moves with demand. As an arena and amphitheatre headliner rather than a stadium act, his face values tend to land in the mid range, with upper-tier seats among the most affordable and floor positions near the stage commanding the most. The figures below describe how the bands typically break down across a run.',
        1 => 'Where you sit and how busy the market is matter more than any single advertised number. Major-market dates around Los Angeles, Chicago and the New York area generally sit at the top of the scale, while smaller arenas and regional stops tend to open lower. Treating the ranges here as a guide to relative value, rather than a fixed price, is the most reliable way to read what a Peso Pluma seat is likely to cost.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Upper-bowl and rear seating, usually the lowest face values, often around 70 to 130 USD. The trade-off is distance from the stage, but the corridos-tumbados sound carries well and the full production is visible from height.',
        ),
        1 => 
        array (
          'name' => 'Lower level',
          'desc' => 'Sideview and end-stage seats in the lower bowl, commonly 140 to 260 USD. A reliable balance of price and a clear sightline without paying for floor access, and a frequent sell-out tier in busier markets.',
        ),
        2 => 
        array (
          'name' => 'Floor',
          'desc' => 'Seated and standing floor positions on the arena floor, typically 270 to 450 USD depending on row and market. The closest standard view of the band, popular with fans who want to be in the thick of the crowd.',
        ),
        3 => 
        array (
          'name' => 'Front floor and pit',
          'desc' => 'Front rows and any standing pit or barrier area, often 450 to 650 USD and higher in major cities. The nearest vantage point, with limited availability that pushes prices up quickly when demand is strong.',
        ),
        4 => 
        array (
          'name' => 'VIP and premium',
          'desc' => 'Packages bundling premium seating with early entry, lounge access or merchandise, generally 600 to 800 USD or more. Contents differ by venue, so the value depends on what each specific package includes.',
        ),
      ),
      'why' => 'Peso Pluma\'s tours are sold through Ticketmaster using dynamic pricing, so a seat\'s cost rises and falls with real-time demand rather than staying fixed. Popular markets and the days right after on-sale tend to show the highest prices, while quieter dates and last-minute releases can soften. Verified resale adds further variation, with listings sometimes above or below original face value. Because of this, two seats in the same section can carry different prices, and the bands above describe typical levels rather than guaranteed figures.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Peso Pluma tickets?',
          'a' => 'Peso Pluma tickets generally range from about 70 USD for upper-level seats to 800 USD or more for VIP and premium packages, reflecting his arena and amphitheatre scale. Lower-bowl seats commonly sit between 140 and 260 USD, with floor positions higher. Because dynamic pricing is used, the exact figure depends on the date, the city and how much demand a particular show is seeing.',
        ),
        1 => 
        array (
          'q' => 'Why do Peso Pluma ticket prices change so much?',
          'a' => 'Prices move because his shows use dynamic pricing, which adjusts seat costs in line with live demand. A high-demand date in a major market such as Los Angeles or Chicago can sit well above a quieter regional stop. Verified resale listings add more variation. As a result, the same section can show different prices at different moments, so the bands above are best read as a guide rather than fixed numbers.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Peso Pluma?',
          'a' => 'The lowest face values are usually upper-level seats, often around 70 to 130 USD, and smaller-market dates tend to open cheaper than headline cities. Buying early can help before dynamic pricing climbs, though occasional last-minute resale drops also appear. Setting alerts for additional dates in your region is a practical way to find the better-value options before a show sells through.',
        ),
        3 => 
        array (
          'q' => 'Are Peso Pluma VIP packages worth it?',
          'a' => 'That depends on what each package contains, since VIP offerings differ by venue and date. They typically pair premium seating with extras such as early entry, a lounge or merchandise, generally from around 600 to 800 USD or more. If the included seat and perks match what you would otherwise pay for separately, the package can represent fair value; if not, a standard floor or lower-bowl seat may serve you better.',
        ),
        4 => 
        array (
          'q' => 'How much are floor tickets for Peso Pluma?',
          'a' => 'Floor tickets typically run from about 270 to 450 USD for standard positions, with front rows and any pit or barrier area often reaching 450 to 650 USD and beyond in major cities. Floor carries the widest spread because proximity to the stage drives demand. In the largest markets these are usually the first sections to climb under dynamic pricing.',
        ),
        5 => 
        array (
          'q' => 'Do Peso Pluma ticket prices include fees?',
          'a' => 'Advertised face values usually exclude service and processing fees, which are added at checkout, so the final total runs above the listed seat price. Some markets now show all-in pricing that folds fees into the headline figure. It is worth checking the full breakdown before paying, as fees can add a noticeable amount on top of the base ticket cost.',
        ),
        6 => 
        array (
          'q' => 'Is it cheaper to buy Peso Pluma tickets on resale?',
          'a' => 'Sometimes. Verified resale can dip below original face value when sellers offload tickets close to a show, particularly for midweek or lower-demand dates. For sought-after major-market nights, however, resale often sits above face value. Comparing primary availability against resale for the specific date is the most reliable way to judge which is cheaper rather than assuming one is always better.',
        ),
        7 => 
        array (
          'q' => 'How far in advance should I buy Peso Pluma tickets?',
          'a' => 'Buying soon after a date goes on sale generally secures the lowest face values before dynamic pricing responds to demand, which matters most for headline cities that sell quickly. For quieter regional dates there is less urgency, and prices can ease nearer the show. Watching for newly added dates in your area is a useful way to access fresh inventory at opening prices.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'DINASTÍA by Peso Pluma & Friends Tour',
      'intro' => 
      array (
        0 => 'Peso Pluma\'s current run is the DINASTÍA by Peso Pluma & Friends Tour, built around the DINASTÍA project and his collaboration with Tito Double P. The trek plays arenas and amphitheatres across North America, pairing his own catalogue with a rotating cast of guest artists from the corridos-tumbados scene. Tour dates are released in stages, so the schedule grows over time and additional shows are commonly added in markets with strong demand.',
        1 => 'Because the billing changes from city to city, no two nights are quite alike, and the guest line-up is often kept under wraps until showtime. For the most reliable picture of where and when Peso Pluma is playing, it is best to check the live schedule, which lists confirmed dates as they are announced and updated.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Peso Pluma touring in 2026?',
          'a' => 'Yes, Peso Pluma has been touring on the DINASTÍA by Peso Pluma & Friends Tour, a multi-city run through arenas and amphitheatres across North America supporting the DINASTÍA project. Dates are announced in stages and further shows are sometimes added in busy markets, so the live schedule is the most reliable place to confirm current and upcoming dates in your area.',
        ),
        1 => 
        array (
          'q' => 'What is Peso Pluma\'s tour called?',
          'a' => 'The current tour is the DINASTÍA by Peso Pluma & Friends Tour, named after the DINASTÍA project and his work alongside Tito Double P. The \'and Friends\' billing reflects a rotating cast of guest artists who join across different cities, which is a defining feature of the run and means the supporting line-up varies from one date to the next.',
        ),
        2 => 
        array (
          'q' => 'Who are the special guests on the Peso Pluma tour?',
          'a' => 'The DINASTÍA tour features a rotating roster of guests drawn from the corridos-tumbados and regional Mexican scene, with figures such as Tito Double P central to the project. The specific guests differ by city and are often not disclosed in advance, so the line-up on any given night is part of the draw. Checking local listings closer to a date gives the best indication of who may appear.',
        ),
        3 => 
        array (
          'q' => 'Where is Peso Pluma touring?',
          'a' => 'The DINASTÍA tour concentrates on arenas and amphitheatres across the United States, taking in major markets such as the Los Angeles area, Chicago and the New York region alongside numerous regional stops. The full routing is published in stages, and new dates are added in high-demand cities, so the live schedule is the most accurate source for the current list of venues and locations.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Peso Pluma setlist leans on the corridos-tumbados hits that built his name, mixing chart crossovers such as Ella Baila Sola and Lady Gaga with deeper catalogue cuts and collaborations. On the DINASTÍA run the running order also makes room for guest spots, so the shape of a show shifts depending on who joins on a given night. The list below reflects songs that have featured regularly on recent dates.',
      ),
      'songs' => 
      array (
        0 => 'Ella Baila Sola',
        1 => 'Lady Gaga',
        2 => 'AMG',
        3 => 'La Bebe',
        4 => 'Qlona',
        5 => 'Rosa Pastel',
        6 => 'PRC',
        7 => 'Bye',
        8 => 'VVS',
        9 => 'Bzrp Music Sessions, Vol. 55',
        10 => 'El Belicon',
        11 => 'Por las Noches',
        12 => 'Tulum',
        13 => 'La People',
        14 => 'Carnal',
        15 => '7-3',
        16 => 'Lagunas',
        17 => 'Nueva Vida',
        18 => 'Las Morras',
        19 => 'Igualito a Mi Apa',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, and the rotating roster of guest artists on the DINASTÍA tour means the running order and any collaborations change from one show to the next, so the songs above are a guide rather than a fixed list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Peso Pluma play live?',
          'a' => 'Peso Pluma\'s live shows centre on his biggest corridos-tumbados tracks, including Ella Baila Sola, Lady Gaga, AMG, La Bebe and Qlona, alongside collaborations such as PRC and his Bzrp session. Deeper cuts and guest features round out the set. The exact running order changes from night to night, so the list above reflects recent staples rather than a fixed sequence.',
        ),
        1 => 
        array (
          'q' => 'Does Peso Pluma play Ella Baila Sola live?',
          'a' => 'Yes, Ella Baila Sola is a regular fixture of Peso Pluma\'s live shows and one of the most anticipated moments of the night, given it is the biggest hit of his career. As with the rest of the set it is not guaranteed on every date, since running orders vary, but it has featured consistently across recent performances.',
        ),
        2 => 
        array (
          'q' => 'How long is a Peso Pluma concert?',
          'a' => 'A Peso Pluma headline set typically runs in the region of an hour and a half to two hours, though the length varies with the venue and how many guest artists appear on the DINASTÍA dates. Because the show leans on a rotating cast of friends, some nights run longer with additional collaborations, so the exact duration differs from one city to the next.',
        ),
        3 => 
        array (
          'q' => 'Does Peso Pluma do an encore?',
          'a' => 'Peso Pluma\'s shows tend to run as a continuous set built around his hits and guest appearances rather than a formal encore break, so the closing songs flow on from the main performance. As with everything in the set this can vary by night, particularly given the rotating guest billing, so the ending of any given show is not fixed.',
        ),
      ),
    ),
  ),
  'feid' => 
  array (
    'name' => 'Feid',
    'genre' => 'Reggaeton',
    'prices' => 
    array (
      'range_low' => 50,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Feid ticket prices generally sit in the mid band for a touring reggaeton headliner, reflecting the theatre, House of Blues and Fillmore-scale rooms that make up much of his recent itinerary rather than full stadium pricing. Face value across his shows typically runs from around 50 USD for upper or general admission standing up to roughly 200 to 350 USD for front-of-stage and VIP positions, with the exact band shifting by city, venue size and how the room is configured.',
        1 => 'Because demand for the Medellin singer tends to outstrip the intimate venues he favours, the resale market often sits well above face value, with averages reported around the 200 USD mark. Booking through official primary channels at face value, including Ticketmaster\'s Face Value Exchange, is usually the more economical route, and Feid ticket prices on the secondary market should be treated as a separate, demand-driven figure.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'General admission / upper level',
          'desc' => 'The entry point for most Feid shows, covering general admission standing at the back of the floor or seats in the upper tiers. Expect roughly 50 to 90 USD at face value, with the lower end appearing at his larger Latin American arena and stadium dates.',
        ),
        1 => 
        array (
          'name' => 'Lower level and mid-floor',
          'desc' => 'Reserved seating in the lower bowl or a mid-floor standing position, the most common choice for a clear view without premium pricing. These typically land between 90 and 160 USD depending on venue and proximity to the stage.',
        ),
        2 => 
        array (
          'name' => 'Front floor and premium seats',
          'desc' => 'Close standing positions near the barrier and the best reserved seats in the house, generally in the 160 to 250 USD range. Pricing here moves most under dynamic pricing when a show sells quickly.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP bundles can run from around 200 to 350 USD and may add early entry, priority merchandise access, a collectible gift item and a dedicated check-in, though contents vary by date and are not a guarantee of the closest seats.',
        ),
      ),
      'why' => 'Most of Feid\'s tours are promoted by Live Nation and ticketed through Ticketmaster, which applies dynamic pricing to a portion of inventory. Under this model a section is not fixed at a single number; prices for those seats move up or down with real-time demand, so the same tier can cost more for a fast-selling night than a quieter one. Face value, resale and platform fees are also distinct figures, and the headline prices quoted on resale sites usually reflect demand rather than what the box office originally charged.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Feid tickets?',
          'a' => 'Feid tickets generally range from about 50 USD for general admission or upper-level seats to roughly 250 USD for premium floor positions, with official VIP packages reaching around 350 USD. The band depends on the city, venue size and whether dynamic pricing has pushed a fast-selling night higher.',
        ),
        1 => 
        array (
          'q' => 'Why do Feid ticket prices vary so much between shows?',
          'a' => 'Prices vary because Feid plays a mix of intimate theatres and larger Latin American arenas, and because Ticketmaster applies dynamic pricing to part of the inventory. A sold-out House of Blues date can carry very different numbers from an arena show, and demand shifts the figure within a single tier.',
        ),
        2 => 
        array (
          'q' => 'Are Feid tickets cheaper at face value than on resale?',
          'a' => 'Usually yes. Resale averages for Feid have been reported around 200 USD, often above the original box-office price, while official primary tickets and the Ticketmaster Face Value Exchange let you buy or resell at the set face value, which tends to be the more economical route.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Feid live?',
          'a' => 'The cheapest seats are typically general admission standing at the back of the floor or upper-tier positions, often starting near 50 USD at face value, with the lowest entry prices appearing at his larger arena and stadium dates in Latin America rather than smaller US rooms.',
        ),
        4 => 
        array (
          'q' => 'Do Feid VIP packages cost more?',
          'a' => 'Yes. Official VIP packages for Feid generally run from around 200 to 350 USD and can include early entry, priority merchandise access and a collectible item. They cost more than standard tickets and do not always include the closest positions to the stage.',
        ),
        5 => 
        array (
          'q' => 'Does Feid use dynamic pricing?',
          'a' => 'Yes. Like most major Live Nation and Ticketmaster tours, Feid\'s shows use dynamic pricing on a portion of seats, so prices rise and fall with demand. The same section can cost noticeably more on a high-demand night than on a slower one.',
        ),
        6 => 
        array (
          'q' => 'How much are front-row or premium Feid tickets?',
          'a' => 'Front-floor and premium reserved seats for Feid typically sit in the 160 to 250 USD range at face value, and this is where dynamic pricing has the most effect. Official VIP bundles can push the figure higher, toward 350 USD.',
        ),
        7 => 
        array (
          'q' => 'Are there extra fees on top of the Feid ticket price?',
          'a' => 'Yes. The advertised price is usually before service and facility fees, which Ticketmaster adds at checkout, so the final total is higher than the face value shown. Resale platforms add their own fees on top of the listed price as well.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'FEID vs FERXXO: Falxo Tour',
      'intro' => 
      array (
        0 => 'Feid\'s current run is the FEID vs FERXXO: Falxo Tour, a Live Nation-promoted outing built around the contrast between the artist\'s given name and his Ferxxo persona. The tour favours theatres and House of Blues and Fillmore-scale rooms across North America alongside larger arena and stadium dates in Latin America, so Feid tour dates tend to feel more intimate in the United States than at his hometown shows in Colombia and Mexico.',
        1 => 'When checking Feid tour dates it is worth noting how the itinerary splits by region. The North American leg runs through smaller, faster-selling venues, while Latin American stops have included rooms such as Estadio El Campin in Bogota and the Monumental Plaza de Toros in Mexico City. Routing, on-sale timing and venue capacity all differ between these legs, and specific dates are confirmed per city rather than fixed in advance.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Feid touring in 2026?',
          'a' => 'Yes. Feid is on the road with the FEID vs FERXXO: Falxo Tour, which includes a North American leg through theatre and House of Blues-scale venues alongside larger dates across Latin America. Exact cities and on-sale details are confirmed per show rather than all at once.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Feid\'s current tour?',
          'a' => 'Feid\'s current tour is the FEID vs FERXXO: Falxo Tour, also styled with the subtitle El Mano a Mano Del Ano. It frames the show as a contest between his real name, Feid, and his Ferxxo alter ego.',
        ),
        2 => 
        array (
          'q' => 'What venues does Feid play on tour?',
          'a' => 'On this tour Feid mixes intimate North American rooms such as House of Blues and Fillmore venues with larger Latin American settings, including arenas and stadiums like Estadio El Campin in Bogota. Capacity and atmosphere differ noticeably between the two legs.',
        ),
        3 => 
        array (
          'q' => 'How can I get Feid tickets at face value?',
          'a' => 'Feid tickets are sold through Ticketmaster and official presale channels, and the tour uses the Ticketmaster Face Value Exchange, which lets fans resell spare tickets only at the original price. Buying through these official routes is the most reliable way to secure face-value entry.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Feid setlist on the FEID vs FERXXO: Falxo Tour moves through his Ferxxo catalogue in a largely continuous run, opening with EL PADRINO and TE MATA before working through fan staples such as Doblexxo, X TI and Classy 101. The show breaks midway for a stripped-back acoustic segment before lifting again into its closing stretch, so the Feid setlist balances club-ready reggaeton with quieter moments rather than running at one pace throughout.',
      ),
      'songs' => 
      array (
        0 => 'EL PADRINO',
        1 => 'TE MATA',
        2 => 'OLD NAVY',
        3 => 'TRANKAITO',
        4 => 'Medellin Takai',
        5 => 'Doblexxo',
        6 => 'Mirame Baby',
        7 => 'X TI',
        8 => 'EL CUARTO DE FERXXO',
        9 => 'Chica 305',
        10 => 'PPCP',
        11 => 'ALAKRAN',
        12 => 'SI TU SUPIERAS',
        13 => 'LUNA',
        14 => 'Classy 101',
        15 => 'VACAXIONES',
        16 => 'CHIMBITA',
        17 => 'JORDAN IV',
        18 => 'Quiero',
        19 => 'La Mejor Musica',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order and song choices above are drawn from recent Falxo Tour shows. Feid rotates tracks, acoustic selections and guest moments by city, so any given concert may add, drop or reorder songs.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Feid play live?',
          'a' => 'Feid\'s recent shows lean on Ferxxo staples including EL PADRINO, TE MATA, Doblexxo, X TI, Classy 101 and VACAXIONES, with a mid-set acoustic segment of quieter cuts. The exact running order changes from night to night across the Falxo Tour.',
        ),
        1 => 
        array (
          'q' => 'Does Feid play Classy 101 live?',
          'a' => 'Yes. Classy 101, his collaboration that became one of his biggest hits, is a regular fixture in Feid\'s live sets and has featured across both his 2025 dates and the current Falxo Tour, usually landing in the main body of the show.',
        ),
        2 => 
        array (
          'q' => 'Does Feid do an encore?',
          'a' => 'Feid\'s recent shows generally run as a continuous set rather than ending with a separate encore block. The performance includes an acoustic mid-section before building to a closing run of tracks such as Quiero and La Mejor Musica.',
        ),
        3 => 
        array (
          'q' => 'How long is a Feid concert?',
          'a' => 'A Feid headline show typically runs around two hours, based on recent Falxo Tour timings of roughly 8:35 to 10:35 in the evening. Length can vary by venue, curfew and whether the show includes the full acoustic segment and guest appearances.',
        ),
      ),
    ),
  ),
  'rauw-alejandro' => 
  array (
    'name' => 'Rauw Alejandro',
    'genre' => 'Reggaeton',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Rauw Alejandro ticket prices generally reflect his standing as an arena-level reggaeton act, with most face-value seats falling within a broad band that shifts according to the venue, the city and how close a seat sits to the stage. Upper-tier seats tend to anchor the lower end of the range, while floor positions and front-of-house sections carry the highest face values. Resale listings can move well beyond face value for in-demand dates.',
        1 => 'Because Rauw Alejandro plays arenas rather than stadiums, his ticket prices typically sit in the mid bracket for a major Latin touring act. The figures below describe how seating tiers usually compare to one another rather than any single confirmed price, since live dates and current prices are listed separately and change from market to market.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper-level seats',
          'desc' => 'The most affordable standard option, usually in the region of 60 to 110 USD before fees. These sit in the upper bowl with a full view of the staging, suited to those prioritising value over proximity.',
        ),
        1 => 
        array (
          'name' => 'Lower-bowl seats',
          'desc' => 'Mid-range seating in the lower tier, commonly around 110 to 200 USD before fees. These offer closer sightlines to the stage and choreography without the floor premium.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Premium standing or seated positions near the stage, typically 200 to 320 USD before fees. Demand for these is highest, so face values vary considerably between dates.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Bundled experiences that can reach 320 to 450 USD or more, often combining a prime location with early entry, merchandise or lounge access. Contents differ by promoter and venue.',
        ),
      ),
      'why' => 'Rauw Alejandro\'s tours are promoted by Live Nation and ticketed through Ticketmaster, which apply dynamic pricing on many dates. Under this model the face value of a ticket can rise or fall in real time based on demand, so two buyers may pay different amounts for comparable seats. Fees are added at checkout and resale prices are set by sellers, both of which can push the final cost above the figures shown here.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Rauw Alejandro tickets?',
          'a' => 'Rauw Alejandro tickets generally range from around 60 USD for upper-level seats to 450 USD or more for VIP packages, before fees. As an arena act his prices sit in the mid bracket, with the exact figure depending on the venue, city and seat location.',
        ),
        1 => 
        array (
          'q' => 'Why do Rauw Alejandro ticket prices change?',
          'a' => 'Most Rauw Alejandro dates use dynamic pricing through Ticketmaster, so face values move up or down with demand. A high-demand market or a date near the stage can cost noticeably more than an off-peak show in the same tour.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Rauw Alejandro live?',
          'a' => 'The lowest face values are usually upper-level seats, often starting around 60 to 80 USD before fees. Buying during an official presale and avoiding peak-demand dates tends to give the best chance of securing them at face value.',
        ),
        3 => 
        array (
          'q' => 'Are Rauw Alejandro VIP packages worth it?',
          'a' => 'Rauw Alejandro VIP packages typically run from around 320 USD upward and bundle a prime location with extras such as early entry or merchandise. Their value depends on how much the add-ons matter to you, as the contents vary by venue and promoter.',
        ),
        4 => 
        array (
          'q' => 'Do Rauw Alejandro ticket prices include fees?',
          'a' => 'No. The face values quoted for Rauw Alejandro tickets are usually before fees, which Ticketmaster adds at checkout. The final amount paid is therefore higher than the listed price, and resale tickets carry their own seller-set pricing.',
        ),
        5 => 
        array (
          'q' => 'How much were Rauw Alejandro tickets on the Cosa Nuestra World Tour?',
          'a' => 'On the Cosa Nuestra World Tour, Rauw Alejandro seats were reported starting near the mid-70s USD before fees, with better positions and premium markets reaching several hundred dollars. Prices varied widely by venue and date.',
        ),
        6 => 
        array (
          'q' => 'Are Rauw Alejandro floor tickets more expensive?',
          'a' => 'Yes. Floor and front sections are the most sought-after Rauw Alejandro tickets and usually carry the highest standard face values, commonly in the 200 to 320 USD range before fees, given the proximity to his choreography and band.',
        ),
        7 => 
        array (
          'q' => 'Is resale a good option for Rauw Alejandro tickets?',
          'a' => 'Resale can secure Rauw Alejandro tickets for sold-out dates, but seller-set prices often exceed face value, especially for floor seats in major cities. Comparing resale against any remaining official inventory is sensible before committing.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Cosa Nuestra World Tour',
      'intro' => 
      array (
        0 => 'Rauw Alejandro\'s most recent run of tour dates was the Cosa Nuestra World Tour, staged in support of his 2024 album Cosa Nuestra and its follow-up material. The production marked his first outing with a full live band, pairing a Broadway-inspired staging concept with the choreography that defines his shows. It moved through arenas in North America before reaching select European cities.',
        1 => 'When Rauw Alejandro announces fresh tour dates, they are typically routed through arenas and promoted by Live Nation, with on-sale handled by Ticketmaster. Confirmed dates and venues for any current run are listed separately, as the schedule and routing change between tours and tend to favour major metropolitan markets.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Rauw Alejandro touring in 2026?',
          'a' => 'Rauw Alejandro\'s Cosa Nuestra World Tour ran through 2025 and concluded in San Juan. Any 2026 dates would be announced separately through Live Nation and Ticketmaster, so check the live listings for the latest confirmed schedule rather than assuming a continuation.',
        ),
        1 => 
        array (
          'q' => 'What is Rauw Alejandro\'s current tour called?',
          'a' => 'Rauw Alejandro\'s most recent tour was the Cosa Nuestra World Tour, supporting his Cosa Nuestra album. It was his first tour to feature a live band alongside the staging and choreography his concerts are known for.',
        ),
        2 => 
        array (
          'q' => 'What kind of venues does Rauw Alejandro play?',
          'a' => 'Rauw Alejandro performs primarily in arenas, such as Climate Pledge Arena, the United Center and London\'s The O2. He sits at arena scale rather than stadium scale, which shapes both capacity and ticket pricing across his dates.',
        ),
        3 => 
        array (
          'q' => 'Does Rauw Alejandro tour internationally?',
          'a' => 'Yes. The Cosa Nuestra World Tour covered North American arenas before reaching Europe, with shows in cities including London and Milan. His routing typically concentrates on large markets across the Americas and Europe.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Rauw Alejandro setlist draws across his catalogue of reggaeton, R&B and Latin pop, mixing chart singles with album cuts and the choreography-led sequences his live show is built around. On the Cosa Nuestra World Tour he performed with a live band for the first time, which reshaped how several songs were arranged on stage compared with their studio versions.',
      ),
      'songs' => 
      array (
        0 => 'Punto 40',
        1 => 'Todo de Ti',
        2 => 'Tattoo',
        3 => 'Fantasias',
        4 => 'Desesperados',
        5 => 'El Efecto',
        6 => 'Lokera',
        7 => '2/Catorce',
        8 => 'Khe?',
        9 => 'Diluvio',
        10 => 'Santa',
        11 => 'Mil Mujeres',
        12 => 'Dejame Entrar',
        13 => 'Cosa Nuestra',
        14 => 'Pasaporte',
        15 => 'Que Pasaria...',
        16 => 'Baja Pa Aca',
        17 => 'SEXXXMACHINE',
        18 => 'Panties y Brasieres',
        19 => 'Touching the Sky',
      ),
      'encore' => 
      array (
      ),
      'note' => 'This is a representative selection and not a fixed running order. Rauw Alejandro\'s setlists vary from night to night, and songs may be added, dropped or rearranged depending on the city, the staging and the leg of the tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Rauw Alejandro play live?',
          'a' => 'Rauw Alejandro\'s live shows lean on staples such as Punto 40, Todo de Ti, Tattoo, Fantasias and Lokera, mixed with cuts from Cosa Nuestra. The exact selection varies by night, but these singles are recurring fixtures across recent dates.',
        ),
        1 => 
        array (
          'q' => 'Does Rauw Alejandro perform Todo de Ti live?',
          'a' => 'Yes. Todo de Ti, one of Rauw Alejandro\'s biggest crossover singles, has been a regular part of his recent setlists and is among the songs his audiences most expect to hear during the show.',
        ),
        2 => 
        array (
          'q' => 'Does Rauw Alejandro do an encore?',
          'a' => 'Rauw Alejandro\'s recent shows have generally run as a continuous set built around his choreography and live band rather than a formal encore break. The closing stretch tends to feature his higher-energy singles instead.',
        ),
        3 => 
        array (
          'q' => 'How long is a Rauw Alejandro concert?',
          'a' => 'A Rauw Alejandro headline show typically runs in the region of two hours, depending on the staging and setlist for the night. The Cosa Nuestra World Tour featured a live band and theatrical sequences that shaped the pacing of the set.',
        ),
      ),
    ),
  ),
  'maluma' => 
  array (
    'name' => 'Maluma',
    'genre' => 'Reggaeton',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 320,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Maluma ticket prices generally reflect his standing as an arena headliner, with the Colombian reggaeton artist filling venues across the Americas and Europe. Most face-value seats sit in the mid band, though demand on a strong night and the marketplace a buyer uses both move the figure. As a rough guide, expect upper-level seats at the lower end and floor or premium positions to carry a clear surcharge, with package tiers reaching higher still.',
        1 => 'Where a Maluma show lands on the scale depends on the city, the size of the room, and how close to the stage a seat sits. Smaller markets and weeknight dates tend to soften prices, while major-city stops and front-of-house sections push them up. The bands below describe how seating usually breaks down rather than any single confirmed date, since live pricing shifts with availability.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and value seats',
          'desc' => 'Upper-tier and rear sections, typically around 55 to 95 USD. These give a full view of the staging and production while keeping cost down, and they are usually the first value seats to clear in busier markets.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side floor',
          'desc' => 'Lower-level sides and rear floor positions, broadly 95 to 165 USD. A middle option that brings buyers closer to the main stage and the runway sections Maluma tends to use, without the premium of front rows.',
        ),
        2 => 
        array (
          'name' => 'Front floor and premium',
          'desc' => 'Front-of-stage floor and prime lower-bowl seats, roughly 165 to 250 USD. These are the closest standard tickets and the first to sell in major cities, so pricing here moves most under demand.',
        ),
        3 => 
        array (
          'name' => 'VIP and packages',
          'desc' => 'Official VIP and hospitality packages, generally 250 to 320 USD and occasionally beyond. These bundle priority entry, premium positions, and merchandise; exact contents vary by venue and promoter, and meet-and-greet style extras sit at the top of this band.',
        ),
      ),
      'why' => 'Maluma\'s tours run on Ticketmaster and Live Nation, which use dynamic pricing on many dates. Under that model, face values rise and fall with real-time demand rather than staying fixed, so popular sections in major cities can climb well above the opening price while quieter dates settle lower. Resale marketplaces add a further layer of variation, with their own fees. The bands here describe typical ranges; the figure any buyer sees depends on the date, city, section, and how early they book.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Maluma tickets?',
          'a' => 'Maluma tickets generally run from about 55 USD for upper-level seats to around 320 USD for VIP packages, with most standard seats falling in the mid range. The exact price depends on the city, venue size, seat section, and how demand is tracking when you book.',
        ),
        1 => 
        array (
          'q' => 'Why do Maluma ticket prices change so much?',
          'a' => 'Maluma\'s shows are sold through Ticketmaster and Live Nation, which apply dynamic pricing to many dates. That means face values move with live demand, so the same seat can cost more in a major city or on a high-demand night than in a quieter market.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Maluma live?',
          'a' => 'The cheapest Maluma tickets are usually upper-level and rear sections, often starting near 55 USD. Booking early, choosing weeknight or smaller-market dates, and avoiding floor and VIP areas typically keeps the cost at the lower end of the range.',
        ),
        3 => 
        array (
          'q' => 'How much do Maluma VIP tickets cost?',
          'a' => 'Maluma VIP and hospitality packages generally start around 250 USD and can reach 320 USD or more. They usually include premium positions, priority entry, and merchandise, with exact contents and pricing varying by venue and promoter.',
        ),
        4 => 
        array (
          'q' => 'Are Maluma floor tickets worth the price?',
          'a' => 'Maluma floor tickets, typically 165 to 250 USD, put you closest to the main stage and the runway sections he often uses during a show. Whether they justify the premium depends on how much proximity matters to you versus a wider view from the lower bowl.',
        ),
        5 => 
        array (
          'q' => 'Do Maluma ticket prices go down closer to the show?',
          'a' => 'Maluma prices can soften close to a date if sections remain unsold, but high-demand shows often hold firm or rise under dynamic pricing. There is no guarantee either way, so waiting carries the risk of losing the better-value seats.',
        ),
        6 => 
        array (
          'q' => 'Are resale Maluma tickets more expensive than face value?',
          'a' => 'Resale Maluma tickets can run above or below face value depending on demand for that date. Sought-after shows tend to carry a markup plus marketplace fees, while quieter dates sometimes appear below the original price.',
        ),
        7 => 
        array (
          'q' => 'How much should I budget for a good Maluma seat?',
          'a' => 'For a solid lower-bowl or side-floor Maluma seat, budgeting around 95 to 165 USD is realistic in most markets. Major-city dates and front-floor positions can push that higher, so allow extra if you want to be near the stage.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => '+Pretty +Dirty World Tour',
      'intro' => 
      array (
        0 => 'Maluma\'s most recent run is the +Pretty +Dirty World Tour, which carried the Colombian artist across Europe, Latin America, and North America. The trek played arenas in cities including London, Madrid, Barcelona, Paris, and Munich, pairing his catalogue of reggaeton and Latin pop hits with the large-scale staging his shows are known for. Tour dates rotate by region and season rather than following a single fixed pattern.',
        1 => 'Beyond the headline arena stops, Maluma\'s touring schedule typically blends standalone shows with festival appearances, and dates are announced in regional batches over time. Anyone tracking Maluma tour dates should expect the routing to shift between continents across a given year, with on-sale timing and venues confirmed closer to each leg rather than all at once.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Maluma touring in 2026?',
          'a' => 'Maluma continues to tour following his +Pretty +Dirty World Tour, with dates added in regional batches across Europe, Latin America, and North America. Specific 2026 shows depend on the latest announcements, so checking current listings is the reliable way to confirm a date near you.',
        ),
        1 => 
        array (
          'q' => 'What is Maluma\'s current tour called?',
          'a' => 'Maluma\'s most recent named tour is the +Pretty +Dirty World Tour, which ran across multiple continents and supported his recent material alongside his established hits. It followed earlier runs including the Don Juan World Tour and the Papi Juancho World Tour.',
        ),
        2 => 
        array (
          'q' => 'Which cities has Maluma\'s tour visited?',
          'a' => 'Maluma\'s +Pretty +Dirty World Tour visited arenas across Europe and the Americas, including London, Madrid, Barcelona, Paris, Rotterdam, and Munich, alongside Latin American and North American stops. Routing varies by leg, so the cities served change from one stretch of the tour to the next.',
        ),
        3 => 
        array (
          'q' => 'How long is a Maluma concert?',
          'a' => 'A Maluma concert typically runs around 90 minutes to two hours, covering roughly 18 to 24 songs depending on the venue and any curfew. The set blends his biggest singles with deeper cuts and the high-production staging that defines his arena shows.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Maluma setlist draws across his career, mixing early breakthroughs with recent singles and the collaborations that have defined his run as one of reggaeton\'s leading names. Recent shows lean on staples such as Felices los 4, Hawái, and Corazón, threaded together with the dance-driven production his live show is built around. The running order and song count shift by night and venue.',
      ),
      'songs' => 
      array (
        0 => 'Borro Cassette',
        1 => 'El Perdedor',
        2 => 'Obsesión',
        3 => 'Carnaval',
        4 => 'Marinero',
        5 => 'Vente Pa\' Ca',
        6 => 'Corazón',
        7 => 'Chantaje',
        8 => 'Cuatro Babys',
        9 => '11 PM',
        10 => 'HP',
        11 => 'Felices los 4',
        12 => 'ADMV',
        13 => 'Parce',
        14 => 'Madrid',
        15 => 'Hawái',
        16 => 'Mala Mía',
        17 => 'Sobrio',
        18 => 'Según Quién',
        19 => 'Coco Loco',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order, song count, and rotational slots change from show to show depending on the venue, the city, and any curfew.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Maluma play live?',
          'a' => 'Maluma typically plays a mix of hits and recent singles live, including Felices los 4, Hawái, Corazón, Borro Cassette, and Sobrio, alongside collaborations such as Vente Pa\' Ca and Chantaje. The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Maluma do an encore?',
          'a' => 'Maluma\'s shows usually run as a continuous set rather than ending on a formal encore, building through his catalogue to a high-energy close. The final stretch tends to feature his most recognisable singles, though the exact ending can vary by date.',
        ),
        2 => 
        array (
          'q' => 'How many songs does Maluma play in a concert?',
          'a' => 'Maluma usually performs around 18 to 24 songs per concert across roughly 90 minutes to two hours. The precise count depends on the venue, the city, and any curfew, with some slots rotating between shows.',
        ),
        3 => 
        array (
          'q' => 'Does Maluma play his old songs live?',
          'a' => 'Maluma regularly works early hits into his sets, with tracks like Borro Cassette, Obsesión, and El Perdedor sitting alongside newer material. The balance between older songs and recent releases shifts from one tour leg to the next.',
        ),
      ),
    ),
  ),
  'morgan-wallen' => 
  array (
    'name' => 'Morgan Wallen',
    'genre' => 'Country',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 650,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Morgan Wallen ticket prices sit at the higher end of country touring because he now headlines stadiums rather than arenas, and demand routinely outstrips supply. Most fans encounter a wide band rather than a single figure, shaped by venue, seat location and how early they buy. The numbers below describe how his pricing generally works across a stadium run, so you can judge value before live figures load.',
        1 => 'What you pay for Morgan Wallen depends heavily on whether you want a seat high in the stands or floor access close to the stage. Because his shows are held in large football and baseball stadiums, capacity is high but premium positions are limited, which keeps the top of the range firm. Treat the figures here as typical guidance rather than fixed quotes, since real prices vary by date and city.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper deck and rear stadium',
          'desc' => 'The most affordable seats, usually in the upper tiers or behind the stage, generally run from about 75 to 150 USD. You are a long way from Wallen but the stadium production and screens keep the show readable from height.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side seating',
          'desc' => 'Lower-level and side seats with a clearer sightline typically land between roughly 150 and 300 USD. This is the middle ground many fans target, balancing distance to the stage against cost.',
        ),
        2 => 
        array (
          'name' => 'Floor and close seated',
          'desc' => 'Floor positions and the closest seated sections commonly sit around 300 to 500 USD. These put you near the main stage and, on tours with a B-stage, often within reach of the acoustic portion.',
        ),
        3 => 
        array (
          'name' => 'VIP and premium packages',
          'desc' => 'Official VIP and premium hospitality packages tend to range from about 500 to 650 USD or higher. These can bundle early entry, premium viewing areas and merchandise rather than just a better seat.',
        ),
      ),
      'why' => 'Morgan Wallen tours through Ticketmaster and Live Nation, which apply dynamic pricing on many of his dates. This means the listed price for a given seat can move up or down with demand rather than staying fixed, so an in-demand stadium night may show prices well above the base figure, while quieter dates settle lower. Wallen\'s recent on-sales drew criticism for how high primary prices climbed, which reflects dynamic pricing responding to heavy demand. Service and facility fees are added at checkout on top of the face value, and resale prices on the secondary market move independently of the primary range. The bands here describe typical primary pricing; always check the live figure for your specific date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Morgan Wallen tickets?',
          'a' => 'Morgan Wallen tickets generally range from around 75 USD for upper-deck stadium seats to 500 USD or more for floor and premium positions, with VIP packages higher still. Because he plays stadiums, the spread is wide and the most in-demand seats hold near the top of that band.',
        ),
        1 => 
        array (
          'q' => 'Why are Morgan Wallen tickets so expensive?',
          'a' => 'Morgan Wallen prices run high because he headlines large stadiums on dynamic pricing, so face values rise with demand. His recent on-sales saw primary prices climb sharply, and limited premium positions in a high-capacity venue keep the top of the range firm even when cheaper upper seats remain.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Morgan Wallen live?',
          'a' => 'The cheapest seats are typically in the upper deck or behind the stage, often starting near 75 USD before fees. Buying early in an on-sale and avoiding the highest-demand dates usually gives the best chance of catching the lower end of the range.',
        ),
        3 => 
        array (
          'q' => 'Does Morgan Wallen use dynamic pricing?',
          'a' => 'Yes. Many Morgan Wallen dates use Ticketmaster\'s dynamic pricing, so the price of a seat can shift with demand rather than staying fixed. This is why the same section can cost noticeably more on a sought-after stadium night than on a quieter date.',
        ),
        4 => 
        array (
          'q' => 'Are there VIP packages for Morgan Wallen concerts?',
          'a' => 'Yes. Official VIP and premium packages are usually offered and typically start around 500 USD, often more. They tend to bundle perks such as early entry, premium viewing and merchandise rather than simply a closer seat, so value depends on what the specific package includes.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Morgan Wallen?',
          'a' => 'Floor and the closest seated sections for Morgan Wallen commonly run from about 300 to 500 USD on the primary market. Prices vary by city and date, and dynamic pricing can push popular stadium nights above that on the day of sale.',
        ),
        6 => 
        array (
          'q' => 'Do Morgan Wallen ticket prices include fees?',
          'a' => 'No. Listed Morgan Wallen prices are usually before service and facility fees, which are added at checkout and can be a meaningful share of the total. The bands quoted here reflect face value, so budget extra for fees on top.',
        ),
        7 => 
        array (
          'q' => 'Are resale Morgan Wallen tickets cheaper than face value?',
          'a' => 'Sometimes, but not reliably. Resale prices move independently of the primary range and can sit below or well above face value depending on demand for that date. For high-demand Morgan Wallen stadium shows, resale often trends higher rather than lower.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Still The Problem Tour',
      'intro' => 
      array (
        0 => 'Morgan Wallen\'s current run is the Still The Problem Tour, a stadium trek that follows his 2025 I\'m The Problem Tour and album of the same name. The dates span major American stadiums, with multiple nights in some cities and a rotating cast of country support acts. Tour dates are added and adjusted over time, so the schedule below explains how the run is structured rather than listing fixed days.',
        1 => 'Because the Still The Problem Tour plays large football and baseball stadiums, on-sales tend to move quickly and some cities carry two-night stands. Wallen typically tours across spring and summer, and exact Morgan Wallen tour dates for any city are confirmed closer to the run. Use the live listings for the current schedule, and treat the notes here as general guidance on how his touring usually works.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Morgan Wallen touring in 2026?',
          'a' => 'Yes. Morgan Wallen is touring on the Still The Problem Tour, a stadium run across the United States that follows his 2025 I\'m The Problem Tour. The schedule covers major stadium markets, with some cities hosting more than one night.',
        ),
        1 => 
        array (
          'q' => 'What is Morgan Wallen\'s current tour called?',
          'a' => 'His current tour is the Still The Problem Tour. The name nods to his 2025 album I\'m The Problem, and the run continues the stadium-scale touring he established on the preceding I\'m The Problem Tour.',
        ),
        2 => 
        array (
          'q' => 'Who is opening for Morgan Wallen?',
          'a' => 'Support on the Still The Problem Tour rotates by date and has included country and Americana acts rather than a single fixed opener. Because the line-up changes from city to city, check the listing for your specific date to see who is appearing that night.',
        ),
        3 => 
        array (
          'q' => 'Does Morgan Wallen play stadiums or arenas?',
          'a' => 'Morgan Wallen now headlines stadiums on the Still The Problem Tour, including large football and baseball venues, rather than arenas. This scale is why capacities are high, premium positions are limited and some markets get two-night stands.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Morgan Wallen setlist on the Still The Problem Tour leans heavily on his 2025 album I\'m The Problem while threading in the hits that built his stadium following. The shows are long, often running close to thirty songs, and include a stripped-back B-stage segment alongside the full-band material. The list below reflects songs that have been genuine staples on recent nights rather than a fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'Don\'t We',
        1 => 'I\'m The Problem',
        2 => 'I Got Better',
        3 => '20 Cigarettes',
        4 => 'Love Somebody',
        5 => 'Dark Til Daylight',
        6 => 'I\'m a Little Crazy',
        7 => 'TN',
        8 => 'Just in Case',
        9 => 'Chasin\' You',
        10 => 'You Proof',
        11 => 'Heartless',
        12 => 'Wasted On You',
        13 => 'Cover Me Up',
        14 => '7 Summers',
        15 => 'I Had Some Help',
        16 => 'Last Night',
        17 => 'Whiskey Glasses',
        18 => 'Sand In My Boots',
        19 => 'The Way I Talk',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night. Morgan Wallen rotates album tracks, acoustic B-stage choices and fan-voted songs between cities, so the exact order and inclusions change from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Morgan Wallen play live?',
          'a' => 'Morgan Wallen\'s live set centres on I\'m The Problem tracks such as the title song, I Got Better and 20 Cigarettes, alongside hits like Last Night, You Proof, Whiskey Glasses and the Post Malone collaboration I Had Some Help. He usually closes with The Way I Talk.',
        ),
        1 => 
        array (
          'q' => 'Does Morgan Wallen do an encore?',
          'a' => 'Morgan Wallen typically runs a continuous show rather than a separate encore, building to a finale such as The Way I Talk instead of leaving and returning. The set is long, often near thirty songs, with a B-stage acoustic stretch in the middle.',
        ),
        2 => 
        array (
          'q' => 'Does Morgan Wallen play Last Night live?',
          'a' => 'Yes. Last Night, one of his biggest chart hits, has been a regular on the Still The Problem Tour. It sits among the marquee singles in the main set, though the exact placement shifts from night to night.',
        ),
        3 => 
        array (
          'q' => 'How long is a Morgan Wallen concert?',
          'a' => 'A Morgan Wallen headline show usually runs around two hours, covering close to thirty songs including a B-stage acoustic segment. Set length varies slightly by night depending on the running order and any fan-voted additions.',
        ),
      ),
    ),
  ),
  'zach-bryan' => 
  array (
    'name' => 'Zach Bryan',
    'genre' => 'Country',
    'prices' => 
    array (
      'range_low' => 75,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Zach Bryan ticket prices sit at the higher end of country touring because he plays stadiums and large outdoor venues rather than arenas or theatres. Get-in seats in the upper levels tend to be the most affordable option, while lower-bowl and pit positions carry a clear premium. Final figures depend on the city, the date and how demand moves through the on-sale, so the bands below describe how pricing generally works rather than any single fixed amount.',
        1 => 'Across a typical Zach Bryan run, the spread between the cheapest upper-tier seat and a front-of-stage or premium package can be several hundred dollars. Resale activity is strong for his shows, which pushes secondary prices above face value in busier markets. Reading the tiers below should give a realistic sense of where Zach Bryan ticket prices tend to land before fees are added at checkout.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and general admission',
          'desc' => 'The entry point for most Zach Bryan stadium shows, typically around 75 to 130 USD. This covers higher seated sections and standing general admission on the floor where available. Sightlines are distant but the sound and large-screen production carry well in an open stadium.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side stage',
          'desc' => 'Mid-range seating closer to the action, generally 130 to 250 USD. These positions in the lower tier or along the sides offer a clearer view of the stage and band without the steepest premium attached to front-row areas.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Prime standing or seated positions near the stage, usually 250 to 400 USD. Demand here is consistently high, so prices climb quickly once a date sells through its initial allocation.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-of-stage pit, official VIP packages and best-available premium seats, often 400 to 600 USD and occasionally higher in the busiest markets. These may bundle early entry or merchandise depending on the tour\'s package structure.',
        ),
      ),
      'why' => 'Most Zach Bryan dates run through Ticketmaster and use dynamic pricing, which means face values shift with demand rather than staying fixed. When a show sells quickly, prices for the remaining seats rise in real time, so two buyers in the same section can pay different amounts depending on when they check out. Resale supply also feeds into what you see, and popular stadium nights frequently trade above the original face value. Adding service and facility fees at checkout raises the final total beyond the listed ticket price.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Zach Bryan tickets?',
          'a' => 'Zach Bryan tickets generally start around 75 to 130 USD for upper-level and general admission spots at his stadium shows, with lower-bowl seats running roughly 130 to 250 USD. Floor and front sections sit around 250 to 400 USD, and premium or VIP packages can reach 400 to 600 USD or more in high-demand cities. Prices vary by market and move with dynamic pricing during the on-sale.',
        ),
        1 => 
        array (
          'q' => 'Why are Zach Bryan tickets so expensive?',
          'a' => 'Zach Bryan plays stadiums and large outdoor venues, so prices skew higher than a typical arena country act. Strong demand, dynamic pricing on Ticketmaster and active resale all push figures up, particularly for floor and premium positions where supply is limited relative to interest.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Zach Bryan live?',
          'a' => 'The cheapest Zach Bryan tickets are usually upper-level seats or general admission, often around 75 to 130 USD before fees. Buying early in the on-sale and choosing weeknight or less central markets tends to keep costs lower, since dynamic pricing raises figures as a show fills up.',
        ),
        3 => 
        array (
          'q' => 'Does Zach Bryan use dynamic pricing?',
          'a' => 'Yes. Like most major Ticketmaster and Live Nation tours, Zach Bryan shows use dynamic pricing, so face values rise and fall with demand during the sale. The same seat can cost different amounts depending on timing, and the fastest-selling dates tend to see the steepest increases.',
        ),
        4 => 
        array (
          'q' => 'How much is a Zach Bryan VIP or pit ticket?',
          'a' => 'Front-of-stage pit and official VIP packages for Zach Bryan typically run from around 400 to 600 USD, and sometimes higher in the busiest cities. These can include extras such as early entry or merchandise depending on how the tour structures its packages.',
        ),
        5 => 
        array (
          'q' => 'Are Zach Bryan resale tickets more expensive than face value?',
          'a' => 'Often yes. Because demand for Zach Bryan stadium dates is high and primary inventory sells through quickly, resale prices frequently sit above the original face value, especially for sought-after markets and floor sections. Quieter dates can occasionally trade closer to or below face value.',
        ),
        6 => 
        array (
          'q' => 'Do Zach Bryan ticket prices include fees?',
          'a' => 'No. The listed Zach Bryan ticket price is the base figure, and service plus facility fees are added at checkout. The final amount is generally higher than the headline price, so it is worth factoring in those charges when comparing seats.',
        ),
        7 => 
        array (
          'q' => 'Do Zach Bryan ticket prices vary by city?',
          'a' => 'Considerably. Larger markets and high-demand stadium dates carry higher average prices, while smaller or less central shows tend to be cheaper. Get-in costs for the same artist can differ by a wide margin from one city to another across a single tour.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'With Heaven On Tour',
      'intro' => 
      array (
        0 => 'Zach Bryan\'s current run is the With Heaven On Tour, a 2026 stadium trek across North America and Europe supporting his album With Heaven on Top. The tour visits major outdoor stadiums and pairs Bryan with a rotating cast of support acts that has included Kings of Leon, Alabama Shakes, MJ Lenderman and Gregory Alan Isakov. Tour dates and on-sale details are listed separately, since they are added and adjusted throughout the schedule.',
        1 => 'The With Heaven On Tour continues the large-scale staging Zach Bryan has favoured in recent years, with long sets that draw across his catalogue. European stops include shows in the UK, while the North American leg covers stadiums from the Midwest to the coasts. Because routing can expand mid-cycle, checking current tour dates for your region is the most reliable way to see where he is playing.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Zach Bryan touring in 2026?',
          'a' => 'Yes. Zach Bryan is on the With Heaven On Tour in 2026, a stadium run across North America and Europe supporting the album With Heaven on Top. The schedule spans dozens of dates, with UK and European stops alongside the North American leg.',
        ),
        1 => 
        array (
          'q' => 'What is Zach Bryan\'s current tour called?',
          'a' => 'Zach Bryan\'s current tour is the With Heaven On Tour, named in connection with his recent album cycle. It is a stadium-scale tour rather than an arena run, reflecting the size of his audience.',
        ),
        2 => 
        array (
          'q' => 'Who is opening for Zach Bryan on the With Heaven On Tour?',
          'a' => 'Support on the With Heaven On Tour rotates by date and has included acts such as Kings of Leon, Alabama Shakes, MJ Lenderman and Gregory Alan Isakov. The specific opener depends on the city, so it is worth checking the individual date you plan to attend.',
        ),
        3 => 
        array (
          'q' => 'Where is Zach Bryan playing on the With Heaven On Tour?',
          'a' => 'The With Heaven On Tour visits major stadiums across North America and Europe, including UK dates. Routing covers a wide range of markets and can be extended during the cycle, so the current date listings for your region are the best guide to nearby shows.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Zach Bryan setlist typically runs long, often well past twenty songs, and leans on the folk-tinged country that defines his catalogue. Recent shows on the With Heaven On Tour have mixed signature tracks such as Something in the Orange and I Remember Everything with newer material and deeper cuts. The running order shifts from night to night, so the list below reflects songs that have been regular fixtures rather than a fixed sequence.',
      ),
      'songs' => 
      array (
        0 => 'Overtime',
        1 => 'Open the Gate',
        2 => 'Something in the Orange',
        3 => 'Dawns',
        4 => 'Nine Ball',
        5 => 'Hey Driver',
        6 => 'If She Wants a Cowboy',
        7 => 'Oklahoma Smokeshow',
        8 => 'Pink Skies',
        9 => 'God Speed',
        10 => 'Burn, Burn, Burn',
        11 => 'East Side of Sorrow',
        12 => 'Heading South',
        13 => 'Sun to Me',
        14 => 'I Remember Everything',
        15 => 'Highway Boys',
        16 => 'Fifth of May',
        17 => 'Quittin\' Time',
      ),
      'encore' => 
      array (
        0 => 'Revival',
      ),
      'note' => 'Setlists vary night to night, so the songs and running order on any given Zach Bryan date may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Zach Bryan play live?',
          'a' => 'Zach Bryan\'s live sets feature staples such as Something in the Orange, I Remember Everything, Oklahoma Smokeshow, Pink Skies and Heading South, alongside deeper cuts and newer material. His shows run long, often beyond twenty songs, and the exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Zach Bryan play Something in the Orange live?',
          'a' => 'Yes. Something in the Orange is one of Zach Bryan\'s signature songs and a regular fixture in his sets, frequently appearing as a highlight of the main portion of the show.',
        ),
        2 => 
        array (
          'q' => 'Does Zach Bryan do an encore?',
          'a' => 'Yes. Zach Bryan typically closes his shows with an encore built around Revival, which has often been the finale on recent tours and has occasionally featured a guest. As with the rest of the set, the encore can vary by night.',
        ),
        3 => 
        array (
          'q' => 'How long is a Zach Bryan concert?',
          'a' => 'Zach Bryan concerts tend to be lengthy, commonly running around two hours or more with setlists that can stretch past thirty songs on his longest nights. The precise length depends on the date and the running order chosen for that show.',
        ),
      ),
    ),
  ),
  'luke-combs' => 
  array (
    'name' => 'Luke Combs',
    'genre' => 'Country',
    'prices' => 
    array (
      'range_low' => 95,
      'range_high' => 850,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Luke Combs ticket prices sit at the higher end of country touring because he now headlines stadiums rather than arenas. Entry-level seats in the upper bowl tend to start in the region of 95 to 150 USD before fees, while lower-tier and closer floor positions climb into the low hundreds. The exact figures depend on the city, the size of the venue and how quickly a given night sells, so two shows on the same tour can carry noticeably different price bands.',
        1 => 'Beyond the standard seating map, Luke Combs tickets also include premium and hospitality options that lift the top of the range considerably. Front-of-stage spots, club access and packaged experiences with merchandise or early entry typically run from the mid hundreds upward. Because his stadium shows place a portion of inventory through official platforms and a portion through verified resale, the spread between the cheapest available seat and a premium package on any single date is wide.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and back of bowl',
          'desc' => 'The most affordable Luke Combs seats, usually in the upper tiers or behind the stage, generally fall between roughly 95 and 160 USD before fees. Sightlines are distant in a stadium but the production and screens are scaled for it.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side floor',
          'desc' => 'Mid-priced seating in the lower bowl or along the sides of the floor typically lands around 160 to 320 USD. These offer a clearer view of the stage and are often the first standard seats to sell on popular dates.',
        ),
        2 => 
        array (
          'name' => 'Front floor and prime sections',
          'desc' => 'Closer floor positions and prime lower-bowl seats near the stage generally run from about 320 to 550 USD, reflecting proximity and demand for in-the-round configurations used on parts of the tour.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Official premium experiences, hospitality and front-row packages sit at the top of the range, commonly from roughly 550 to 850 USD and occasionally beyond. These may bundle early entry, dedicated entrances, merchandise or lounge access.',
        ),
      ),
      'why' => 'Luke Combs\'s stadium shows are sold largely through Ticketmaster, where most major Live Nation tours of this scale use dynamic pricing. That means face values are not fixed in advance and can move up or down with demand as a particular night sells, so the same section may cost more on a high-demand Saturday than on a slower weeknight date. Verified resale adds further variation, since listings can run above or below the original face value depending on how the market is moving close to showtime. As a result, the bands here describe the general shape of what seats tend to cost rather than a fixed tariff, and the cheapest get-in figure for any one show is best confirmed against current live listings.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Luke Combs tickets?',
          'a' => 'Luke Combs tickets generally start in the region of 95 to 150 USD for upper-level stadium seats, with lower-bowl and floor positions running into the low-to-mid hundreds. Premium and VIP packages reach the upper hundreds. Because he plays stadiums, prices sit higher than a typical arena country bill and vary by city and date.',
        ),
        1 => 
        array (
          'q' => 'Why do Luke Combs ticket prices vary so much between shows?',
          'a' => 'Prices vary because his shows use dynamic pricing on the primary platform, so face values shift with demand as each night sells. Stadium capacity, market size and whether a date falls on a weekend all push the band around. A higher-demand show can cost substantially more in the same section than a quieter weeknight on the same tour.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Luke Combs?',
          'a' => 'The cheapest seats are usually in the upper tiers or behind the stage, often starting around 95 to 130 USD before fees. Buying early in a sale, choosing a midweek date where available, and comparing the official platform against verified resale closer to the show are the most reliable ways to find a lower get-in price.',
        ),
        3 => 
        array (
          'q' => 'Do Luke Combs tickets include fees in the listed price?',
          'a' => 'Listed prices on most platforms do not include service and facility fees, which are added at checkout and can raise the total noticeably. Some sellers now show all-in pricing, but it is worth checking whether the figure shown is the face value or the final total before completing a Luke Combs purchase.',
        ),
        4 => 
        array (
          'q' => 'Are Luke Combs VIP packages worth the price?',
          'a' => 'VIP and premium packages, typically from the mid to upper hundreds, suit fans who want early entry, closer seating, dedicated entrances or merchandise bundled in. Whether they represent value depends on what is included for a given date; the contents and price of official packages differ from show to show across his stadium run.',
        ),
        5 => 
        array (
          'q' => 'How much do front-row Luke Combs tickets cost?',
          'a' => 'Front-row and prime front-of-stage positions are among the most expensive, generally from around 550 USD upward and often sold as premium packages rather than standard seats. With some 2026 dates using an in-the-round stage, demand for the closest positions can push these well into the upper part of the range.',
        ),
        6 => 
        array (
          'q' => 'Can you find Luke Combs tickets below face value?',
          'a' => 'Sometimes, yes. On verified resale, listings can appear below the original face value when a show is not sold out or as the event nears and sellers adjust. It is less common for his most popular stadium dates, where resale tends to sit at or above face, so availability below face varies sharply by city.',
        ),
        7 => 
        array (
          'q' => 'Do Luke Combs ticket prices change closer to the show?',
          'a' => 'They can. Primary dynamic pricing may ease on slower-selling dates, while resale prices often move in the final days as supply and demand settle. For in-demand stadium nights prices tend to hold firm or rise, so there is no single rule; comparing live listings near the date gives the clearest picture.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'My Kinda Saturday Night Tour',
      'intro' => 
      array (
        0 => 'Luke Combs is currently on the My Kinda Saturday Night Tour, a stadium run that follows the release of his album The Way I Am. The schedule reaches major venues across North America before crossing to Europe, taking in football stadiums and large open-air sites rather than the arenas of his earlier touring. Luke Combs tour dates are routed city by city, with a rotating cast of support acts changing from market to market.',
        1 => 'Because the My Kinda Saturday Night Tour is built around stadium capacities, on-sales tend to open in stages, often with artist or platform presales ahead of the general release. Individual Luke Combs tour dates can sell quickly in larger markets, and second nights are sometimes added where demand supports them. Confirmed cities, openers and any newly added shows are listed alongside live availability rather than fixed here.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Luke Combs touring in 2026?',
          'a' => 'Yes. Luke Combs is touring on the My Kinda Saturday Night Tour, a stadium run spanning North American and European dates in support of The Way I Am. The routing moves through major stadiums across multiple countries; specific cities and any added nights are shown with current listings rather than fixed in this guide.',
        ),
        1 => 
        array (
          'q' => 'What is Luke Combs\'s current tour called?',
          'a' => 'His current tour is the My Kinda Saturday Night Tour, named after the song that also opens the show. It is a stadium-scale production that pulls heavily from his recent album while keeping the established hits in the set, and it features a rotating line-up of support artists across the run.',
        ),
        2 => 
        array (
          'q' => 'Who is opening for Luke Combs on tour?',
          'a' => 'Support changes by market on the My Kinda Saturday Night Tour, with a rotating group of country and roots acts joining for different legs and cities. Because openers are assigned per date rather than fixed across the whole tour, the line-up for a specific show is best confirmed against that date\'s listing.',
        ),
        3 => 
        array (
          'q' => 'Does Luke Combs tour internationally?',
          'a' => 'Yes. Alongside an extensive North American stadium schedule, the My Kinda Saturday Night Tour includes European dates, reflecting the wider reach of his recent records. International routing tends to be more compact than the home run, so European fans typically have fewer dates and cities to choose from.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Luke Combs setlist on the My Kinda Saturday Night Tour runs to roughly 25 or more songs and opens with the title track that names the tour. The core mixes the radio singles that built his audience with newer material from The Way I Am, so a night moves between barroom anthems, mid-tempo ballads and a closing stretch of his biggest hits. The list below reflects songs that have been recurring staples on the current run.',
      ),
      'songs' => 
      array (
        0 => 'My Kinda Saturday Night',
        1 => 'Lovin\' on You',
        2 => 'Hurricane',
        3 => 'She Got the Best of Me',
        4 => 'One Number Away',
        5 => 'Going, Going, Gone',
        6 => 'Cold as You',
        7 => 'The Kind of Love We Make',
        8 => 'Must\'ve Never Met You',
        9 => 'Tell \'Em About Tonight',
        10 => 'Rethink Some Things',
        11 => 'Even Though I\'m Leaving',
        12 => 'When It Rains It Pours',
        13 => 'Beautiful Crazy',
        14 => 'Better Together',
        15 => 'Doin\' This',
        16 => '1, 2 Many',
        17 => 'Beer Never Broke My Heart',
        18 => 'Ain\'t No Love in Oklahoma',
      ),
      'encore' => 
      array (
        0 => 'Fast Car',
        1 => 'Where the Wild Things Are',
      ),
      'note' => 'Setlists vary night to night. Luke Combs rotates several slots across the tour, swapping in album tracks and occasional covers, and the running order and encore can change from one stadium to the next, so any single show may differ from the list above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Luke Combs play live?',
          'a' => 'Luke Combs opens with My Kinda Saturday Night and works through staples such as Hurricane, When It Rains It Pours, Beautiful Crazy, One Number Away and The Kind of Love We Make, alongside newer cuts from The Way I Am. The set typically closes with his biggest singles. Exact songs vary from night to night across the tour.',
        ),
        1 => 
        array (
          'q' => 'Does Luke Combs do an encore?',
          'a' => 'Yes. He generally returns for an encore that has featured his cover of Tracy Chapman\'s Fast Car as the opener, followed by another hit such as Where the Wild Things Are. As with the main set, the encore can change between dates, so the closing songs are not fixed across every stop on the tour.',
        ),
        2 => 
        array (
          'q' => 'Does Luke Combs play Fast Car at concerts?',
          'a' => 'Yes. His version of Tracy Chapman\'s Fast Car has become a regular fixture, frequently appearing as the encore opener on the current tour. It is one of the few covers he performs routinely, and audiences tend to treat it as a singalong moment near the end of the night.',
        ),
        3 => 
        array (
          'q' => 'How long is a Luke Combs concert?',
          'a' => 'A headline Luke Combs stadium show generally runs in the region of two hours, covering around 25 or more songs plus an encore. Support acts perform earlier in the evening, so the full bill can run longer; the exact length depends on the set on a given night and the number of openers on that date.',
        ),
      ),
    ),
  ),
  'chris-stapleton' => 
  array (
    'name' => 'Chris Stapleton',
    'genre' => 'Country',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Chris Stapleton ticket prices generally sit in the mid-range for a country act, reflecting the mix of amphitheatres, arenas and the occasional stadium he plays. Lawn and upper-tier seats tend to land at the lower end of the band, while reserved seating closer to the stage and premium packages climb higher. Where a show is staged matters: a large outdoor amphitheatre usually carries gentler pricing than a sought-after arena or stadium night.',
        1 => 'Because Stapleton tours steadily and plays sizeable rooms, supply is reasonable in most markets, which keeps entry-level prices accessible compared with stadium-only headliners. Demand still concentrates on weekend dates and shows near his Kentucky and Nashville base, so those can sit toward the upper part of the range. The figures here describe how pricing tends to behave across a typical leg rather than any single advertised date.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Lawn and upper level',
          'desc' => 'General-admission lawn at amphitheatres and the highest seated tiers in arenas, usually around $60 to $110. The most economical way in, with the trade-off of distance from the stage.',
        ),
        1 => 
        array (
          'name' => 'Mid-tier reserved',
          'desc' => 'Lower-bowl arena seating and mid-section reserved amphitheatre seats, broadly $110 to $200. A balance of sightlines and cost that suits most attendees.',
        ),
        2 => 
        array (
          'name' => 'Floor and lower reserved',
          'desc' => 'Floor seats and front lower-bowl positions, typically $200 to $300, putting you within clear view of the stage for the band-driven set.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-of-stage seats and official VIP packages that may bundle early entry or merchandise, generally $300 to $400 and occasionally beyond for marquee stadium dates.',
        ),
      ),
      'why' => 'Most Chris Stapleton dates run through Ticketmaster and Live Nation, which apply dynamic pricing on a portion of inventory. That means face values can move up or down with demand rather than staying fixed, so a high-demand section may cost more than the same seat on a quieter night. Standard tickets hold their listed price, while seats flagged as Platinum or Official Platinum are the ones that fluctuate. Checking a specific date gives the accurate current figure.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Chris Stapleton tickets?',
          'a' => 'Chris Stapleton tickets generally range from about $60 for lawn and upper-level seats to roughly $400 for floor and premium positions. Mid-tier reserved seating commonly falls between $110 and $200. The exact figure depends on the venue, the city and how close to the stage you sit, with stadium dates carrying the steeper end.',
        ),
        1 => 
        array (
          'q' => 'Why do Chris Stapleton ticket prices change?',
          'a' => 'Prices change because most of his shows use dynamic pricing through Ticketmaster, where demand adjusts the cost of certain Platinum-flagged seats. A popular weekend date can therefore be priced higher than a midweek show in the same venue. Standard tickets stay at their set face value, so the variation is limited to the demand-priced sections.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Chris Stapleton?',
          'a' => 'The cheapest option is usually a general-admission lawn ticket at an outdoor amphitheatre, often around $60 to $90. Upper-level seated tiers in arenas are similarly economical. Midweek dates and shows away from his Nashville and Kentucky strongholds also tend to carry softer pricing than weekend or hometown nights.',
        ),
        3 => 
        array (
          'q' => 'Are Chris Stapleton VIP tickets worth it?',
          'a' => 'VIP and premium packages, generally $300 to $400, place you close to the stage and can include extras such as early entry or merchandise. They suit attendees who want the best sightlines for a show built around live musicianship. For most people, mid-tier reserved seats deliver a strong experience at a noticeably lower cost.',
        ),
        4 => 
        array (
          'q' => 'Do Chris Stapleton ticket prices differ by city?',
          'a' => 'Yes. Shows in or near Nashville and his native Kentucky, along with major-market arena dates, tend to price higher because of concentrated local demand. Larger amphitheatres and dates in smaller markets usually offer gentler pricing. Venue type is the biggest single factor, with stadiums sitting above arenas and amphitheatres.',
        ),
        5 => 
        array (
          'q' => 'How much are floor seats for Chris Stapleton?',
          'a' => 'Floor and lower-reserved seats typically run from about $200 to $300, putting you close to the stage for the full band performance. On high-demand or stadium dates, dynamic pricing can push these higher, while quieter midweek shows may bring them nearer the lower figure.',
        ),
        6 => 
        array (
          'q' => 'Are Chris Stapleton tickets expensive compared with other country artists?',
          'a' => 'His pricing sits in the mid-range for a country headliner. Entry-level lawn seats near $60 keep him more accessible than stadium-only acts, while floor and premium seats reach the $300 to $400 band. Overall he is comparable to peers playing similar amphitheatre and arena rooms rather than the highest-priced stadium tours.',
        ),
        7 => 
        array (
          'q' => 'What does a Chris Stapleton ticket include?',
          'a' => 'A standard ticket covers entry and your seat or lawn space for the performance, including the support acts that rotate across the tour. VIP packages add extras such as early access or merchandise. Tickets do not include parking or fees, which are added at checkout, so the final total runs above the listed face value.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'All-American Road Show',
      'intro' => 
      array (
        0 => 'Chris Stapleton tours under the long-running All-American Road Show banner, the name he has used for his headline dates across recent years. The format pairs his core band with a rotating cast of guest performers, so the supporting line-up shifts from city to city. For tour dates, the practical approach is to follow his official schedule and set alerts for your nearest market, since legs are announced in waves and additional nights are added where demand is strong.',
        1 => 'The All-American Road Show moves through amphitheatres, arenas and a number of stadiums, which shapes both capacity and pricing in each city. Stapleton routes steadily through North America rather than concentrating on a handful of stops, giving most regions a reachable date within a touring year. Exact venues and on-sale timing are confirmed per leg, so checking the current itinerary is the reliable way to see where and when he is playing.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Chris Stapleton touring in 2026?',
          'a' => 'Yes. Chris Stapleton continues his All-American Road Show through 2026, with dates across North America spanning amphitheatres, arenas and select stadiums. The tour features rotating guest acts and has seen second nights added in several cities. Consult his official schedule for the venues and on-sale details applicable to your area.',
        ),
        1 => 
        array (
          'q' => 'What is Chris Stapleton\'s tour called?',
          'a' => 'His headline tour is the All-American Road Show, the name Stapleton has carried across multiple touring years. It is built around his band and a changing roster of opening performers, so the support line-up varies by city while the headline set stays the constant draw.',
        ),
        2 => 
        array (
          'q' => 'What venues does Chris Stapleton play?',
          'a' => 'The All-American Road Show plays a mix of outdoor amphitheatres, indoor arenas and a number of stadiums, depending on the market. Larger amphitheatres feature lawn and reserved seating, while stadium dates in major cities offer the biggest capacities. This range is why ticket pricing and availability differ noticeably between stops.',
        ),
        3 => 
        array (
          'q' => 'Who opens for Chris Stapleton?',
          'a' => 'Support acts rotate across the tour rather than staying fixed, and recent legs have drawn from artists such as Molly Tuttle, Maggie Rose, Grace Potter, Ashley McBryde and Nikki Lane. Because the line-up changes by city, the specific openers depend on the date, so check the individual show for who is billed.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Chris Stapleton setlist leans on his Traveller-era material and the band-driven blues and country that define his live show, typically opening with up-tempo numbers before settling into the ballads. The setlist usually closes on his best-known songs, with extended guitar work stretching several tracks well beyond their studio length. The running order shifts between shows, so the selections below reflect songs that have been consistent staples on recent All-American Road Show dates.',
      ),
      'songs' => 
      array (
        0 => 'Tennessee Whiskey',
        1 => 'Starting Over',
        2 => 'Broken Halos',
        3 => 'Parachute',
        4 => 'Nobody to Blame',
        5 => 'Fire Away',
        6 => 'Cold',
        7 => 'You Should Probably Leave',
        8 => 'Whiskey and You',
        9 => 'Outlaw State of Mind',
        10 => 'Midnight Train to Memphis',
        11 => 'Millionaire',
        12 => 'Hard Livin\'',
        13 => 'I Was Wrong',
        14 => 'Worry B Gone',
        15 => 'Arkansas',
        16 => 'Was It 26',
        17 => 'White Horse',
        18 => 'Devil Always Made Me Think Twice',
        19 => 'Either Way',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order, song choices and any covers differ from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Chris Stapleton play live?',
          'a' => 'Chris Stapleton\'s live sets are built around staples such as Tennessee Whiskey, Starting Over, Broken Halos, Parachute and Fire Away, mixed with deeper cuts from across his albums. He often extends songs with long guitar passages, and the exact selection changes from night to night, so no two shows are identical.',
        ),
        1 => 
        array (
          'q' => 'Does Chris Stapleton play Tennessee Whiskey live?',
          'a' => 'Yes. Tennessee Whiskey is a fixture of his live show and usually arrives late in the set as one of the evening\'s high points. The arrangement typically runs longer than the studio version, with extended guitar and vocal sections that lean into its soul influence.',
        ),
        2 => 
        array (
          'q' => 'Does Chris Stapleton perform an encore?',
          'a' => 'Stapleton\'s shows tend to run as a continuous set that builds to his biggest songs near the close rather than following a formal encore break every night. When he does return, it is often for a marquee number such as Tennessee Whiskey. The structure varies, so the ending differs from show to show.',
        ),
        3 => 
        array (
          'q' => 'How long is a Chris Stapleton concert?',
          'a' => 'A headline Chris Stapleton set generally runs around 90 minutes to two hours, depending on the night and the number of extended jams. Support acts play beforehand, so the full evening is longer. Because he stretches several songs live, the exact running time varies between dates.',
        ),
      ),
    ),
  ),
  'ap-dhillon' => 
  array (
    'name' => 'AP Dhillon',
    'genre' => 'Punjabi',
    'prices' => 
    array (
      'range_low' => 50,
      'range_high' => 300,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'AP Dhillon ticket prices generally sit in the mid-tier band, reflecting the arenas and large ballrooms he tends to play across North America, the UK and India. Entry-level seats in the upper bowl usually open at the lower end, while floor general admission and front blocks command a clear premium. Because demand for his shows is strong and inventory moves quickly, the spread between the cheapest and most expensive face-value tickets can be wide.',
        1 => 'Where a date sells through official channels, AP Dhillon ticket prices are set by tier rather than a single flat figure, so the seat map matters more than the headline number. Standing floor areas, side and rear sections, and premium packages each carry their own pricing. The figures below describe how the bands typically work rather than any one show, since the venue scale ranges from a few thousand to arena capacity.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear sections',
          'desc' => 'The most affordable seated tickets, in the upper tiers or behind and to the side of the stage. Expect roughly USD 50 to 90 depending on the venue. The view is distant but the full production and sound carry across the room.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and mid-tier seated',
          'desc' => 'Side and rear lower-level seats with a clearer sightline to the stage, generally around USD 90 to 150. A common middle choice for those who want a seat without paying floor or premium rates.',
        ),
        2 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing access to the floor, closest to the energy of the show. Typically USD 130 to 220 depending on the market and how quickly the date sells. The busiest tier for his audience and often the first to clear.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-block seats, early entry or hospitality bundles where offered, usually from USD 200 up to around USD 300 or more. Contents vary by promoter and are not available for every date.',
        ),
      ),
      'why' => 'AP Dhillon\'s larger dates are routed through Ticketmaster and Live Nation, which means most major shows use dynamic pricing. Under that model the face value of a seat shifts with live demand rather than staying fixed, so identical seats can cost different amounts at different moments and popular sections rise as they sell. This is why a single quoted price is misleading: the band reflects where seats have generally landed, not a guaranteed figure. Smaller theatre and ballroom dates are more often fixed-price, and resale listings sit outside the artist\'s control and can run above or below face value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are AP Dhillon tickets?',
          'a' => 'AP Dhillon tickets typically range from about USD 50 for upper-level seats to around USD 300 for premium and VIP options, with general admission floor commonly between USD 130 and 220. The exact figure depends on the venue size, the city and how a particular date is selling.',
        ),
        1 => 
        array (
          'q' => 'What is the cheapest way to see AP Dhillon live?',
          'a' => 'The cheapest seats are usually in the upper tiers or rear and side sections, often starting near USD 50. Booking early through the official seller tends to secure the lowest band before the more affordable inventory clears.',
        ),
        2 => 
        array (
          'q' => 'Why do AP Dhillon ticket prices change?',
          'a' => 'Most of his arena dates use dynamic pricing through Ticketmaster and Live Nation, so face value moves with live demand. Popular sections rise as they sell, which means the same seat can cost different amounts at different times.',
        ),
        3 => 
        array (
          'q' => 'How much is general admission floor for AP Dhillon?',
          'a' => 'General admission floor usually runs from about USD 130 to 220. As the standing area closest to the stage it is among the most sought-after tiers and often sells through before seated sections.',
        ),
        4 => 
        array (
          'q' => 'Are AP Dhillon VIP packages worth it?',
          'a' => 'VIP and premium packages, generally from USD 200 to around USD 300 or more, can include front-block seating, early entry or hospitality. Whether they are worthwhile depends on what each promoter bundles in, as contents differ by date and are not offered everywhere.',
        ),
        5 => 
        array (
          'q' => 'Do AP Dhillon ticket prices vary by city?',
          'a' => 'Yes. Prices reflect venue scale, so a few-thousand-capacity ballroom date prices differently from a full arena show. Larger markets and faster-selling dates tend to sit higher within the bands described above.',
        ),
        6 => 
        array (
          'q' => 'Is resale cheaper than face value for AP Dhillon?',
          'a' => 'Sometimes, but not reliably. Resale sits outside the artist\'s pricing and can run above or below face value depending on demand for a given date. For high-demand shows resale often exceeds the original price.',
        ),
        7 => 
        array (
          'q' => 'Are there booking or service fees on AP Dhillon tickets?',
          'a' => 'Official sellers usually add service and facility fees on top of the face value shown, so the final checkout total runs higher than the listed band. The exact fees depend on the venue and seller.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'One of One India Tour',
      'intro' => 
      array (
        0 => 'AP Dhillon tour dates have most recently run under the One of One India Tour, an eight-city run across India that followed his earlier arena touring. He previously headlined The Brownprint Tour, built around the EP of the same name, as well as the Out of This World Tour, his first major North American arena run. Routing and venue scale vary by region, so each leg is confirmed independently rather than as one fixed global itinerary.',
        1 => 'Because AP Dhillon tour dates are announced leg by leg, the cities and venues differ from one run to the next, spanning intimate ballrooms through to full arenas. New dates are typically revealed by the promoter ahead of an on-sale, with presale access often tied to fan or partner registrations. The notes below explain how his touring generally works rather than committing to any single schedule.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is AP Dhillon touring in 2026?',
          'a' => 'AP Dhillon most recently toured on the One of One India Tour, an eight-city Indian run, after earlier legs across North America and other markets. New legs are confirmed by his promoter individually, so check the official listings for the most current routing in any given year.',
        ),
        1 => 
        array (
          'q' => 'What is AP Dhillon\'s current tour called?',
          'a' => 'His most recent named run is the One of One India Tour. Earlier headline tours included The Brownprint Tour, tied to the EP of the same name, and the Out of This World Tour.',
        ),
        2 => 
        array (
          'q' => 'How do AP Dhillon presale tickets work?',
          'a' => 'Presales for his shows are usually run through the promoter or partner platforms ahead of the general on-sale, often requiring registration in advance. Signing up for artist or venue alerts is the most reliable way to get presale access codes.',
        ),
        3 => 
        array (
          'q' => 'Which cities does AP Dhillon usually play?',
          'a' => 'His tours regularly take in major Indian metros such as Mumbai, Delhi and Ahmedabad alongside North American markets including Toronto, Vancouver, New York and Los Angeles, plus UK and international dates. Exact cities are confirmed per leg.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'An AP Dhillon setlist leans on the Brown Town catalogue that built his audience, mixing the breakout singles with newer EP material. Recent shows have paired anthems such as Brown Munde and Excuses with later tracks, giving the set a blend of the songs that broke him and the more recent releases driving his current touring. The running order shifts between dates, but the core staples below recur across his live shows.',
      ),
      'songs' => 
      array (
        0 => 'Brown Munde',
        1 => 'Excuses',
        2 => 'Insane',
        3 => 'With You',
        4 => 'Summer High',
        5 => 'Takeover',
        6 => 'Drip',
        7 => 'True Stories',
        8 => 'Toxic',
        9 => 'Majhail',
        10 => 'Sleepless',
        11 => 'Afsos',
        12 => 'STFU',
        13 => 'Thodi Si Daaru',
        14 => 'Bora Bora',
        15 => 'Old Money',
        16 => 'Wo Noor',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and the order and song choices change from show to show depending on the city, the length of the set and the run he is touring. Treat this as a representative selection of his live staples rather than a fixed running order for any single date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does AP Dhillon play live?',
          'a' => 'AP Dhillon\'s live shows centre on staples such as Brown Munde, Excuses, Insane, With You and Summer High, alongside tracks like Takeover, Drip and Toxic and newer EP material. The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does AP Dhillon perform Brown Munde at concerts?',
          'a' => 'Yes. Brown Munde is the song that broke him internationally and remains a fixture of his live set, usually placed as a peak moment that the audience knows word for word.',
        ),
        2 => 
        array (
          'q' => 'How long is an AP Dhillon concert?',
          'a' => 'His headline sets generally run somewhere around 75 to 90 minutes, though this varies with the venue, the city and whether the date is a full headline show or a festival appearance. Support acts can extend the overall evening.',
        ),
        3 => 
        array (
          'q' => 'Does AP Dhillon do an encore?',
          'a' => 'It varies by date. Many of his shows run as a continuous set rather than a formal encore, so whether he returns for additional songs depends on the venue and the night rather than a fixed routine.',
        ),
      ),
    ),
  ),
  'karan-aujla' => 
  array (
    'name' => 'Karan Aujla',
    'genre' => 'Punjabi',
    'prices' => 
    array (
      'range_low' => 70,
      'range_high' => 360,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Karan Aujla ticket prices generally sit in the mid-tier arena band, reflecting the venues he fills across North America and Europe. Entry-level upper-tier seats tend to open near the lower end of the range, while lower-bowl and floor positions cost more. Because his shows are routed through Live Nation and Ticketmaster, most face-value prices are set by tier and then adjusted by demand rather than fixed across a whole tour.',
        1 => 'Beyond the standard tiers, Karan Aujla dates often carry VIP and early-entry packages that bundle premium positions with merchandise or other extras, which pushes the top of the range higher. Resale can sit above or below face value depending on how quickly a given city sells. The figures here describe how pricing usually works for his arena shows rather than any single announced date.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / value seating',
          'desc' => 'Upper-bowl and rear-section seats, typically the most affordable way in. For Karan Aujla arena dates these commonly fall in the 70 to 110 USD band at face value, with sightlines toward the stage from the higher rows.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl',
          'desc' => 'Side and lower-level seating closer to the floor, usually around 110 to 190 USD. This tier balances a clear view with a price below the premium floor sections and tends to sell steadily for his shows.',
        ),
        2 => 
        array (
          'name' => 'Floor / front sections',
          'desc' => 'Standing or seated floor positions near the stage, generally 180 to 300 USD depending on the city and proximity. These are the highest-demand standard tickets and move quickly in larger Punjabi-diaspora markets.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Premium bundles that pair prime floor or front-section access with early entry, merchandise or other extras. For Karan Aujla these commonly run from roughly 280 up to about 360 USD, with the exact contents varying by date.',
        ),
      ),
      'why' => 'Most Karan Aujla dates run through Live Nation and Ticketmaster, which use demand-based dynamic pricing on many shows. That means a tier\'s price can rise as a section sells and ease back if demand softens, so two buyers in the same area may pay different amounts depending on timing. Face value is set per seating tier first; dynamic adjustments and any VIP package contents then move the final figure. Resale prices are set independently by sellers and are not capped to face value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Karan Aujla tickets?',
          'a' => 'Karan Aujla tickets typically range from about 70 USD for upper-tier seats to roughly 360 USD for VIP packages at his arena shows. Lower-bowl seats usually fall between 110 and 190 USD, while floor and front sections sit higher. Exact prices vary by city and by how a particular date is selling.',
        ),
        1 => 
        array (
          'q' => 'Why do Karan Aujla ticket prices change?',
          'a' => 'Many Karan Aujla dates use demand-based dynamic pricing through Ticketmaster, so a section\'s price can rise as it sells and ease if demand is lower. Each seating tier has a base face value that is then adjusted by demand, which is why the same area can cost different amounts at different times.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Karan Aujla live?',
          'a' => 'The cheapest standard option is usually an upper-tier or rear-section seat, often opening near the lower end of the range for his arena shows. Buying early, before high-demand sections climb under dynamic pricing, also tends to help. Prices differ by city and venue size.',
        ),
        3 => 
        array (
          'q' => 'Are there VIP tickets for Karan Aujla concerts?',
          'a' => 'Yes, Karan Aujla dates commonly offer VIP packages that combine premium floor or front-section positions with extras such as early entry or merchandise. These sit at the top of the price range, generally from around 280 USD upward, with the precise contents varying from one date to the next.',
        ),
        4 => 
        array (
          'q' => 'How much are floor tickets for Karan Aujla?',
          'a' => 'Floor and front-section tickets for Karan Aujla usually cost between about 180 and 300 USD at face value, depending on the city and how close the position is to the stage. They are the highest-demand standard tickets and tend to sell faster than upper-tier seats.',
        ),
        5 => 
        array (
          'q' => 'Do Karan Aujla ticket prices vary by city?',
          'a' => 'Yes. Karan Aujla prices tend to run higher in large Punjabi-diaspora markets where demand is strongest, and the venue size also matters, since arena and stadium dates are priced differently. The tiers stay broadly similar, but the exact figures shift from one city to another.',
        ),
        6 => 
        array (
          'q' => 'Is resale cheaper than face value for Karan Aujla?',
          'a' => 'Sometimes. Resale prices for Karan Aujla are set by individual sellers and can sit above or below face value depending on how quickly a date sells. In high-demand cities resale often runs higher, while slower-selling dates can occasionally fall below the original price.',
        ),
        7 => 
        array (
          'q' => 'What do Karan Aujla VIP packages include?',
          'a' => 'Karan Aujla VIP packages usually bundle a premium floor or front-section ticket with extras such as early venue entry, a merchandise item or a commemorative laminate. The exact inclusions differ by date and city, so the listed contents should be checked for each specific show before buying.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'P-Pop Culture Tour',
      'intro' => 
      array (
        0 => 'Karan Aujla\'s current touring centres on the P-Pop Culture Tour, the run built around his album of the same name, which extended his live reach across Canadian arenas, European dates and large-scale stadium shows in India. Earlier, his It Was All A Dream World Tour took in arenas across North America and Europe. Tour dates are confirmed city by city, so the routing and venues vary between legs rather than following a single fixed pattern.',
        1 => 'When checking Karan Aujla tour dates, it helps to note that he plays a mix of arena and stadium venues depending on the market, with the largest crowds in India and strong demand in Punjabi-diaspora cities. Presales and general on-sales are typically handled through Ticketmaster and Live Nation, and individual legs are announced separately, so the full schedule builds out over time rather than appearing all at once.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Karan Aujla touring in 2026?',
          'a' => 'Yes, Karan Aujla has been touring on the P-Pop Culture Tour, which includes Canadian arena dates and large stadium shows in India. Routing is confirmed city by city and legs are announced separately, so the live schedule expands over time. Check current listings for the dates relevant to your region.',
        ),
        1 => 
        array (
          'q' => 'What is Karan Aujla\'s current tour called?',
          'a' => 'Karan Aujla\'s current tour is the P-Pop Culture Tour, named after his P-Pop Culture album. It follows his earlier It Was All A Dream World Tour and spans arenas and stadiums across Canada, Europe and India, with venues differing by market and demand.',
        ),
        2 => 
        array (
          'q' => 'Where does Karan Aujla usually perform?',
          'a' => 'Karan Aujla performs in arenas across North America and Europe and in large stadiums in India, where his crowds are biggest. Cities with large Punjabi-diaspora populations see strong demand. The specific venues vary by tour leg, so the routing changes from one run to the next.',
        ),
        3 => 
        array (
          'q' => 'How are Karan Aujla tour tickets sold?',
          'a' => 'Karan Aujla tour tickets are generally sold through Ticketmaster and Live Nation, typically starting with artist or venue presales followed by a general on-sale. Dates are released city by city across separate legs, so on-sale timing varies by show and region rather than following one fixed date.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Karan Aujla setlist generally moves between his hard-edged rap tracks and his melodic crossover hits, drawing heavily on the It Was All A Dream and P-Pop Culture material alongside earlier staples. Recent shows have opened with high-energy numbers before working through the radio singles and collaborations that fill his catalogue. The running order shifts from night to night, but a core group of songs appears across most dates on his current run.',
      ),
      'songs' => 
      array (
        0 => 'MF Gabhru',
        1 => 'Winning Speech',
        2 => 'Bachke Bachke',
        3 => 'Admirin\' You',
        4 => 'Chitta Kurta',
        5 => 'Players',
        6 => 'On Top',
        7 => 'Gangsta',
        8 => 'Softly',
        9 => 'Tauba Tauba',
        10 => 'Antidote',
        11 => '52 Bars',
        12 => 'Wavy',
        13 => 'Jee Ni Laggda',
        14 => 'Here & There',
        15 => 'Don\'t Look',
        16 => 'Try Me',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, so the exact songs and running order differ between Karan Aujla shows and from one tour leg to the next.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Karan Aujla play live?',
          'a' => 'Karan Aujla\'s live shows usually feature tracks such as MF Gabhru, Winning Speech, Bachke Bachke, Admirin\' You, Chitta Kurta, Players and Softly, alongside the rap-leaning Gangsta and 52 Bars. The exact selection changes from night to night, but these songs appear across most recent dates.',
        ),
        1 => 
        array (
          'q' => 'Does Karan Aujla play Tauba Tauba live?',
          'a' => 'Yes, Tauba Tauba, his widely streamed crossover hit, has featured in Karan Aujla\'s recent setlists. As with the rest of his catalogue, its inclusion can vary by date, since he adjusts the running order between shows and tour legs rather than playing a fixed set every night.',
        ),
        2 => 
        array (
          'q' => 'Does Karan Aujla do an encore?',
          'a' => 'Karan Aujla\'s shows tend to run as a continuous set rather than building around a formal encore break, so the closing songs flow on from the main set. The exact ending varies by night, with one of his bigger singles typically reserved for the final stretch of the show.',
        ),
        3 => 
        array (
          'q' => 'How long is a Karan Aujla concert?',
          'a' => 'A Karan Aujla headline set generally runs in the region of 90 minutes to two hours, depending on the venue and the number of guests, with stadium shows in India often at the longer end. The precise length and song count vary from one date to the next.',
        ),
      ),
    ),
  ),
  'john-mayer' => 
  array (
    'name' => 'John Mayer',
    'genre' => 'Rock',
    'prices' => 
    array (
      'range_low' => 60,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'John Mayer ticket prices tend to sit in the mid arena band rather than the stadium tier, reflecting the rooms he plays on his solo run, from TD Garden to the Kia Forum. Upper-level seats are the entry point, while floor rows and front sections carry the steepest face values. Because Mayer headlines without an opener on these shows, demand concentrates on a single act, which firms up pricing across the bowl.',
        1 => 'What you pay for John Mayer also depends on the night and the market, since most of these dates run through Ticketmaster with dynamic pricing enabled. Prices shift with how a given show is selling, so two seats in the same section can list differently on different days. The bands below describe how seating generally maps to cost, with live figures injected separately rather than fixed here.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / value',
          'desc' => 'The cheapest way in, covering the upper bowl and rear corners. For a Mayer solo arena show these seats generally land in the 60 to 110 USD range at face value, with the view trading off against price.',
        ),
        1 => 
        array (
          'name' => 'Lower level / mid-tier',
          'desc' => 'Side and rear lower-bowl seating with a clear sightline to the stage. These typically run around 120 to 200 USD, the bracket most general buyers end up in.',
        ),
        2 => 
        array (
          'name' => 'Floor / premium',
          'desc' => 'Floor rows and prime lower sections close to the stage, usually in the 210 to 350 USD band. On Mayer\'s stripped-back solo shows the proximity is a real draw, which keeps these in demand.',
        ),
        3 => 
        array (
          'name' => 'VIP / packages',
          'desc' => 'Official VIP and hospitality packages, where offered, bundle a premium seat with extras such as early entry or merchandise. These sit at or above the top of the standard range and vary by venue and promoter.',
        ),
      ),
      'why' => 'Most John Mayer dates are sold through Ticketmaster, which applies dynamic pricing on the majority of major Live Nation tours. That means face values are not fixed; they move with demand, so a fast-selling night can climb well above the opening price while a softer show may ease back. Resale listings sit separately again and often run higher than face, particularly for floor seats. The figures shown on a live page reflect current availability at the moment you view them, which is why we describe how the pricing works rather than quoting a single number.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are John Mayer tickets?',
          'a' => 'John Mayer tickets generally start around 60 USD for upper-level seats and reach roughly 350 USD for floor and premium sections at his solo arena shows. Lower-bowl seating typically falls between those points. Exact prices depend on the venue, the date and live demand.',
        ),
        1 => 
        array (
          'q' => 'Why do John Mayer ticket prices change so much?',
          'a' => 'Most John Mayer dates run on Ticketmaster\'s dynamic pricing, so face values rise and fall with demand in real time. A high-demand night can push prices above the opening level, while a quieter show may settle lower, which is why two similar seats can list at different amounts.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see John Mayer?',
          'a' => 'The lowest face values are usually upper-level and rear-corner seats, often in the 60 to 110 USD range for his solo arena shows. Buying early, before dynamic pricing reacts to demand, also tends to help on the busier dates.',
        ),
        3 => 
        array (
          'q' => 'How much are John Mayer floor tickets?',
          'a' => 'Floor and prime lower-section seats for John Mayer typically run in the 210 to 350 USD band at face value. On his solo acoustic shows the closeness to the stage is part of the appeal, which keeps these among the first to sell.',
        ),
        4 => 
        array (
          'q' => 'Are there VIP packages for John Mayer concerts?',
          'a' => 'Official VIP and hospitality packages are offered on many John Mayer dates, bundling a premium seat with extras such as early entry or merchandise. Pricing sits at or above the top of the standard range and varies by venue and promoter.',
        ),
        5 => 
        array (
          'q' => 'Are John Mayer tickets more expensive than stadium acts?',
          'a' => 'No. John Mayer plays arenas rather than stadiums on his solo run, so prices generally sit in the mid band, below the levels seen for stadium headliners. Floor seats are the dearest part of the house but still typically peak around 350 USD at face.',
        ),
        6 => 
        array (
          'q' => 'Do John Mayer ticket prices include fees?',
          'a' => 'Listed face values usually exclude service and processing fees, which Ticketmaster and other sellers add at checkout. The final total is therefore higher than the seat price shown, so it is worth checking the full breakdown before completing a purchase.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy John Mayer tickets on resale?',
          'a' => 'Not usually. Resale listings for John Mayer often run above face value, especially for floor seats on in-demand nights. Primary sale through the official seller is generally the cheaper route when standard inventory is still available.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'John Mayer Solo Tour',
      'intro' => 
      array (
        0 => 'John Mayer\'s current run is his solo tour, a stripped-back arena format in which he performs alone across acoustic guitar, electric guitar and piano without a backing band. The staging leans on his catalogue and playing rather than production, and the tour dates have covered major arenas including Madison Square Garden, TD Garden and the Kia Forum, with additional legs added in response to demand.',
        1 => 'Beyond the solo shows, John Mayer also performs as part of Dead and Company, so his touring schedule moves between the two. For the solo tour specifically, dates are released by market and routed through arenas rather than stadiums. Live tour dates and on-sale details are injected separately, so the picture below explains how the run is generally structured rather than listing fixed dates.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is John Mayer touring in 2026?',
          'a' => 'Yes. John Mayer has continued his solo arena tour into 2026, with dates added across major US arenas. He also performs separately with Dead and Company, so his schedule moves between solo shows and the band. Specific dates and on-sale timings are confirmed market by market.',
        ),
        1 => 
        array (
          'q' => 'What is John Mayer\'s current tour called?',
          'a' => 'John Mayer\'s current run is his Solo Tour, an acoustic-led arena format in which he plays alone on guitar and piano without a backing band. It has been staged at venues such as Madison Square Garden and the Kia Forum, with extra legs added as demand has grown.',
        ),
        2 => 
        array (
          'q' => 'What kind of show is the John Mayer Solo Tour?',
          'a' => 'The Solo Tour is built around John Mayer performing on his own, moving between acoustic guitar, electric guitar and piano. There is no opening act or backing band, so the focus stays on his catalogue and playing, which gives the arena shows a more intimate feel than a full production.',
        ),
        3 => 
        array (
          'q' => 'Does John Mayer have an opening act on the Solo Tour?',
          'a' => 'No. The John Mayer Solo Tour is a single-artist show with no support act; Mayer performs the full evening alone. That format concentrates the running time on his own material and the different instruments he moves between across the set.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A John Mayer setlist on the solo tour typically pulls from across his catalogue, mixing early hits with later album tracks and a few blues-leaning moments. Recent shows have opened frequently with Last Train Home and built toward Gravity as the closer, with songs such as Slow Dancing in a Burning Room, Who Says and Wild Blue recurring through the run. The solo format lets him reshape arrangements night to night.',
      ),
      'songs' => 
      array (
        0 => 'Last Train Home',
        1 => 'Belief',
        2 => 'Queen of California',
        3 => 'Who Says',
        4 => 'Love on the Weekend',
        5 => 'Something Like Olivia',
        6 => 'I Guess I Just Feel Like',
        7 => 'In the Blood',
        8 => 'Wild Blue',
        9 => 'New Light',
        10 => 'Why Georgia',
        11 => 'Stop This Train',
        12 => 'Edge of Desire',
        13 => 'Waiting on the World to Change',
        14 => 'Your Body Is a Wonderland',
        15 => 'Daughters',
        16 => 'Slow Dancing in a Burning Room',
        17 => 'Gravity',
      ),
      'encore' => 
      array (
        0 => 'Free Fallin\'',
      ),
      'note' => 'Setlists vary from night to night, and the order, song choices and arrangements change across dates on the solo tour. Treat the list above as a guide to recent staples rather than a fixed running order for any particular show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does John Mayer play live?',
          'a' => 'John Mayer\'s solo shows draw on staples such as Gravity, Slow Dancing in a Burning Room, Last Train Home, Who Says and Waiting on the World to Change, alongside later tracks like New Light and Wild Blue. The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does John Mayer do an encore?',
          'a' => 'It varies. Many John Mayer solo shows run as a continuous set closing on Gravity, while others add a short encore, sometimes featuring his cover of Tom Petty\'s Free Fallin\'. Whether there is an encore depends on the particular night.',
        ),
        2 => 
        array (
          'q' => 'What song does John Mayer usually open with?',
          'a' => 'On recent solo dates John Mayer has often opened with Last Train Home, though he also starts shows with songs such as Belief or Queen of California. As with the rest of the set, the opener is not fixed and changes between dates.',
        ),
        3 => 
        array (
          'q' => 'Does John Mayer play his old hits live?',
          'a' => 'Yes. John Mayer regularly performs earlier hits including Your Body Is a Wonderland, Daughters, Why Georgia and Gravity, mixing them with later material. The solo format lets him rework these arrangements, so familiar songs can sound different from the recorded versions.',
        ),
      ),
    ),
  ),
  'hozier' => 
  array (
    'name' => 'Hozier',
    'genre' => 'Alternative',
    'prices' => 
    array (
      'range_low' => 55,
      'range_high' => 320,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Hozier ticket prices tend to reflect the mid-scale rooms he favours, which means arenas and outdoor amphitheatres rather than full stadiums. Across a typical run, face-value seats generally open around the mid double figures for upper tiers and climb towards the lower hundreds for the best reserved blocks and pit access. Where a show sells quickly, dynamic pricing can push the most sought-after positions higher, so the spread on any given night is usually wide.',
        1 => 'The range you encounter for Hozier depends heavily on venue type, position and how a particular date is performing. Amphitheatre lawn and upper-bowl seats sit at the affordable end, lower reserved and floor seating occupy the middle, and front-of-stage or premium packages reach the top of the band. Prices below are indicative of standard primary sale levels and exclude fees, which are added at checkout.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and lawn',
          'desc' => 'Upper-bowl arena seating and outdoor lawn or general admission grass, typically around 55 to 90 USD. The most economical way in, with sound usually strong even where the stage feels distant.',
        ),
        1 => 
        array (
          'name' => 'Mid-level reserved',
          'desc' => 'Mid-bowl and rear lower-tier reserved seats, generally 90 to 150 USD. A balanced choice that keeps the stage in clear view without paying premium-block rates.',
        ),
        2 => 
        array (
          'name' => 'Lower tier and floor',
          'desc' => 'Front lower-bowl and reserved floor seating, usually 150 to 230 USD. Closer sightlines and, in amphitheatres, the better covered positions near the stage.',
        ),
        3 => 
        array (
          'name' => 'Pit and front rows',
          'desc' => 'Standing pit and the foremost reserved rows, commonly 200 to 280 USD, with dynamic pricing on high-demand nights occasionally reaching towards 320 USD.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and enhanced experiences combining a premium seat or early entry with extras such as merchandise or a dedicated entrance, typically 220 to 320 USD depending on the inclusions offered.',
        ),
      ),
      'why' => 'Most major Hozier dates run through Ticketmaster and Live Nation, which apply dynamic pricing. This means face values can move with demand rather than staying fixed, so a section listed at one figure when sales open may rise if the show is selling quickly, and certain seats are released as higher-priced Platinum positions. The base tiers above reflect standard primary levels; the exact figure shown at checkout depends on the specific date, venue and remaining availability, with booking and service fees added on top.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Hozier tickets?',
          'a' => 'Hozier tickets generally start around 55 to 90 USD for upper-tier and lawn positions and reach roughly 200 to 320 USD for pit, front rows and VIP packages, before fees. Because he plays arenas and amphitheatres rather than stadiums, the overall band sits in the mid range, with mid-level reserved seats commonly landing between 90 and 150 USD.',
        ),
        1 => 
        array (
          'q' => 'Why do Hozier ticket prices change?',
          'a' => 'Hozier\'s major dates use dynamic pricing through Ticketmaster and Live Nation, so face values shift with demand. A seat can cost more if a show is selling fast, and some premium positions are released as higher-priced Platinum tickets. This is why two people buying for the same concert can pay different amounts for similar seats.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Hozier live?',
          'a' => 'The cheapest Hozier tickets are usually upper-tier arena seats or outdoor lawn and general admission grass, often around 55 to 90 USD before fees. These positions trade proximity for price, though his band and vocal-led arrangements tend to carry well even from the back of a venue.',
        ),
        3 => 
        array (
          'q' => 'Are Hozier VIP packages worth it?',
          'a' => 'Hozier VIP packages, typically 220 to 320 USD, bundle a premium seat or early entry with extras such as exclusive merchandise or a separate entrance. Whether they suit you depends on how much you value those inclusions over a standard ticket, since the experience on stage is the same regardless of package.',
        ),
        4 => 
        array (
          'q' => 'Do Hozier tickets include booking fees?',
          'a' => 'No, the prices quoted for Hozier are generally face values, and booking or service fees are added at checkout. These vary by seller and venue, so the final total is usually higher than the listed seat price. It is worth reviewing the full amount before confirming a purchase.',
        ),
        5 => 
        array (
          'q' => 'How much are Hozier pit tickets?',
          'a' => 'Hozier pit and standing tickets typically run from about 200 to 280 USD, occasionally rising towards 320 USD on the busiest dates under dynamic pricing. The pit places you nearest the stage, which suits those who prefer a standing, close-up view over a reserved seat further back.',
        ),
        6 => 
        array (
          'q' => 'Are Hozier tickets more expensive at festivals?',
          'a' => 'Festival access to Hozier is priced as a day or weekend pass rather than a single-artist ticket, so the cost covers the whole bill. A standalone Hozier headline show is generally the more direct option if he is the main act you want to see, with seats spanning the tiers above.',
        ),
        7 => 
        array (
          'q' => 'Do Hozier ticket prices vary by city?',
          'a' => 'Yes, Hozier ticket prices vary by market and venue size. Larger arenas in higher-demand cities tend to sit at the upper end of each tier, while amphitheatre and smaller-market dates can open lower. Position within the venue remains the biggest single factor in what you pay.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Unreal Unearth Tour',
      'intro' => 
      array (
        0 => 'Hozier tour dates have centred on the Unreal Unearth Tour, the extended run supporting his third album, which has carried him across North America, Europe, South America and the festival circuit. The staging leans on his full band and a setlist that draws heavily from Unreal Unearth alongside earlier material. Routing typically favours arenas and outdoor amphitheatres, with festival headline slots filling out the calendar.',
        1 => 'How Hozier tour dates are released tends to follow a familiar pattern: an announcement, an artist or fan presale, then a general on-sale through the venue\'s primary ticketing partner. Demand for the Unreal Unearth Tour has been consistent, so popular dates can move quickly. Checking the official artist channels and the venue\'s ticketing page is the most reliable way to track when a city goes on sale.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Hozier touring in 2026?',
          'a' => 'Hozier has toured extensively on the Unreal Unearth Tour across recent years, taking in arenas, amphitheatres and festivals. Touring plans are confirmed on a rolling basis, so the most reliable way to check whether a given city or date is scheduled is the official artist channels and the venue\'s primary ticketing page, where new on-sales are listed as they are announced.',
        ),
        1 => 
        array (
          'q' => 'What is Hozier\'s current tour called?',
          'a' => 'Hozier\'s recent touring has run under the Unreal Unearth Tour, supporting his third studio album of the same name. The run has spanned multiple continents and legs, mixing headline arena and amphitheatre shows with festival appearances, and the setlist reflects that album while retaining his established live staples.',
        ),
        2 => 
        array (
          'q' => 'How do I get Hozier presale tickets?',
          'a' => 'Hozier presale access typically comes through an artist or fan club presale ahead of the general on-sale, often requiring a code distributed via his mailing list or official channels. Venue and credit-card presales can also apply. Signing up to the official list before an announcement is the usual route to early access for a given date.',
        ),
        3 => 
        array (
          'q' => 'Does Hozier play festivals?',
          'a' => 'Yes, Hozier regularly appears at festivals as well as headline shows, having taken slots at major events alongside the Unreal Unearth Tour. Festival sets tend to be shorter than a full headline performance and are accessed through a day or weekend pass rather than a single-artist ticket.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Hozier setlist on the Unreal Unearth Tour typically pairs a substantial block of that album with the songs that built his reputation, so a night moves between newer narrative pieces and the established singles. Expect material such as De Selby, Eat Your Young and Francesca sitting alongside Take Me to Church and Work Song. The exact running order shifts between shows, but the broad shape of the set has stayed fairly steady across the run.',
      ),
      'songs' => 
      array (
        0 => 'De Selby (Part 1)',
        1 => 'De Selby (Part 2)',
        2 => 'Jackie and Wilson',
        3 => 'Eat Your Young',
        4 => 'Too Sweet',
        5 => 'Francesca',
        6 => 'Like Real People Do',
        7 => 'From Eden',
        8 => 'I, Carrion (Icarian)',
        9 => 'Someone New',
        10 => 'Angel of Small Death & the Codeine Scene',
        11 => 'Dinner & Diatribes',
        12 => 'Abstract (Psychopomp)',
        13 => 'Would That I',
        14 => 'Almost (Sweet Music)',
        15 => 'Cherry Wine',
        16 => 'Movement',
        17 => 'Nina Cried Power',
        18 => 'Work Song',
        19 => 'Take Me to Church',
      ),
      'encore' => 
      array (
        0 => 'Nina Cried Power',
        1 => 'Work Song',
        2 => 'Take Me to Church',
      ),
      'note' => 'Setlists vary night to night, so the exact songs and running order change between shows; this reflects a typical recent Hozier performance rather than a fixed list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Hozier play live?',
          'a' => 'A Hozier show usually features Take Me to Church, Work Song, Too Sweet and Cherry Wine alongside a strong block from Unreal Unearth such as De Selby, Eat Your Young and Francesca. The set blends his best-known singles with deeper album cuts, though the precise selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Hozier do an encore?',
          'a' => 'Hozier typically closes with an encore, often built around Nina Cried Power, Work Song and Take Me to Church, the last of which usually ends the night. As with the main set, the encore can vary between shows, so the closing songs are not guaranteed to be identical at every date.',
        ),
        2 => 
        array (
          'q' => 'Does Hozier play Take Me to Church live?',
          'a' => 'Yes, Take Me to Church remains a fixture of Hozier\'s live shows and is usually saved for the end of the night. As his breakthrough single it tends to draw the strongest crowd response, and it has stayed in the set across the Unreal Unearth Tour despite the focus on newer material.',
        ),
        3 => 
        array (
          'q' => 'How long is a Hozier concert?',
          'a' => 'A Hozier headline performance generally runs in the region of ninety minutes to two hours, depending on the venue and how many songs are included that night. Festival sets are shorter. The pacing moves between full-band moments and quieter, more stripped passages across the evening.',
        ),
      ),
    ),
  ),
  'benson-boone' => 
  array (
    'name' => 'Benson Boone',
    'genre' => 'Pop',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 320,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Benson Boone ticket prices sit in the mid arena band, reflecting his move to larger rooms and amphitheatres. Across a typical run, face-value seats tend to open from around 45 dollars in the upper levels, while lower-tier and floor positions usually land in the 90 to 180 dollar range. Premium and front-section seats can climb toward 320 dollars at the busiest shows. Final figures depend on the venue, the city and how quickly a date sells.',
        1 => 'Because Boone trades on big vocal moments and a physical stage show, demand clusters around the floor and the seats closest to the runway. Where you sit, the night of the week and overall appetite for a given market all feed into what you pay. The bands below are a guide to how Benson Boone ticket prices generally break down rather than a fixed tariff for any single performance.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level and rear seating',
          'desc' => 'The most affordable way in, covering the upper tiers and seats furthest from the stage. Expect roughly 45 to 80 dollars at face value. The view is wide rather than close, but the sound across modern arenas is even and the full production reads clearly from height.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and side sections',
          'desc' => 'Mid-priced seating in the lower bowl and along the sides, generally around 80 to 140 dollars. These positions trade a little distance for a more direct sightline to the stage and are a common middle ground for buyers who want a closer view without paying floor rates.',
        ),
        2 => 
        array (
          'name' => 'Floor and front blocks',
          'desc' => 'Standing or seated floor positions and the front-facing lower sections, usually 140 to 230 dollars. This is where the runway moments and crowd interaction land closest, and these blocks tend to be among the first to sell in stronger markets.',
        ),
        3 => 
        array (
          'name' => 'Premium and VIP packages',
          'desc' => 'Front-row blocks and official VIP packages, which can run from about 230 to 320 dollars or more. Packages may bundle early entry, a dedicated merchandise item or a separate check-in, though contents differ by tour and are confirmed at the point of sale.',
        ),
      ),
      'why' => 'Most Benson Boone dates are sold through Ticketmaster and Live Nation, which apply dynamic pricing on many shows. That means the listed price for a seat can move up or down with demand rather than staying fixed, so two buyers in the same section may pay different amounts depending on when they purchase. Service and facility fees are added at checkout and vary by venue. Resale prices sit separately from face value and can run well above the original figure for sought-after markets, so the bands here describe primary face value rather than the secondary market.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Benson Boone tickets?',
          'a' => 'Benson Boone tickets generally start around 45 dollars at face value for upper-level seats and run to roughly 320 dollars for premium and VIP positions. Most lower-bowl and floor seats fall between 90 and 200 dollars. The exact figure depends on the venue, the city and how much demand a particular date attracts.',
        ),
        1 => 
        array (
          'q' => 'Why do Benson Boone ticket prices change so much?',
          'a' => 'Many Benson Boone shows use dynamic pricing through Ticketmaster, so the cost of a seat can rise or fall with live demand rather than staying fixed. Fees added at checkout and the gap between face value and resale listings widen the spread further, which is why two seats in the same area can carry different prices.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Benson Boone live?',
          'a' => 'The cheapest seats are usually in the upper tiers, often from around 45 to 80 dollars at face value. Buying early in a sale, choosing a midweek date or looking at a less in-demand city can also keep costs down, since arena rooms tend to price upper-level and rear sections lowest.',
        ),
        3 => 
        array (
          'q' => 'Are Benson Boone VIP packages worth it?',
          'a' => 'Benson Boone VIP packages typically cost from about 230 dollars upward and can include early entry, premium seating or a merchandise item. Whether they are worth it depends on how much value you place on those extras and on being close to the stage, as the exact contents vary by tour and are listed at purchase.',
        ),
        4 => 
        array (
          'q' => 'Do Benson Boone ticket prices include fees?',
          'a' => 'The advertised price usually does not include service and facility fees, which are added during checkout and differ by venue. Because of this, the final total can sit noticeably above the headline figure, so it is worth checking the full price before completing a purchase.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Benson Boone?',
          'a' => 'Floor and front-block tickets for Benson Boone generally range from about 140 to 230 dollars at face value. These positions place you closest to the runway and the main staging, and they tend to sell quickly in busier markets, which can push resale figures higher.',
        ),
        6 => 
        array (
          'q' => 'Are Benson Boone tickets expensive compared with other pop acts?',
          'a' => 'Benson Boone sits in the mid arena range, lower than stadium headliners but above small-club acts. His shift to arenas and amphitheatres places typical face values in the 45 to 320 dollar span, which is broadly in line with comparable rising pop headliners playing rooms of similar size.',
        ),
        7 => 
        array (
          'q' => 'Can I still get Benson Boone tickets after a show sells out?',
          'a' => 'Once a date sells out at face value, the remaining route is usually the resale market, where prices are set by sellers and often run above the original cost. Some shows also release a limited number of additional seats closer to the date, so checking the official outlet again can occasionally turn up returns.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Wanted Man Tour',
      'intro' => 
      array (
        0 => 'Benson Boone\'s current run is the Wanted Man Tour, a North American outing that follows his first all-arena tour and carries the show into amphitheatres and arenas across the United States. Tour dates typically move through major and mid-sized markets over a summer-into-autumn window, building on the larger production he developed on the road. Specific Benson Boone tour dates, cities and on-sale details are confirmed by the promoter and listed separately, as schedules shift between legs.',
        1 => 'The Wanted Man Tour leans on the material from his recent album cycle alongside the songs that first carried him to wider audiences. As with most touring at this scale, routing covers a spread of regions rather than every city, so the closest date for a given listener can vary. Checking the official schedule for current Benson Boone tour dates remains the reliable way to confirm where and when he is playing.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Benson Boone touring in 2026?',
          'a' => 'Yes. Benson Boone is on the road with the Wanted Man Tour, a North American run that follows his earlier all-arena touring. It carries his stage show through arenas and amphitheatres across the United States. Exact dates and cities are confirmed by the promoter and listed separately, as routing changes between legs.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Benson Boone\'s current tour?',
          'a' => 'Benson Boone\'s current tour is the Wanted Man Tour, which followed the American Heart World Tour that wrapped earlier in 2026. It focuses on North American dates and builds on the larger arena and amphitheatre production he developed on his previous run.',
        ),
        2 => 
        array (
          'q' => 'Which cities is Benson Boone playing on tour?',
          'a' => 'The Wanted Man Tour covers a spread of major and mid-sized markets across the United States rather than every city. Because routing varies between legs, the closest date for any listener depends on the schedule, so checking the official tour listing is the reliable way to confirm cities.',
        ),
        3 => 
        array (
          'q' => 'How long is a Benson Boone concert?',
          'a' => 'A Benson Boone headline set typically runs around an hour and three-quarters to two hours, depending on the night and the setlist. The show pairs his bigger vocal numbers with the up-tempo material from his recent album, and exact running times can shift between dates.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Benson Boone setlist usually pairs the radio singles that broke him with deeper cuts from his recent album cycle, building toward his bigger vocal moments late in the night. Recent shows have drawn heavily on the American Heart material alongside earlier favourites, and the running order tends to balance up-tempo numbers with the slower, voice-led songs. The Benson Boone setlist below reflects songs that have featured regularly on his most recent tours.',
      ),
      'songs' => 
      array (
        0 => 'I Wanna Be the One You Call',
        1 => 'Wanted Man',
        2 => 'Sorry I\'m Here for Someone Else',
        3 => 'Man in Me',
        4 => 'Drunk in My Mind',
        5 => 'Slow It Down',
        6 => 'Be Someone',
        7 => 'Mystical Magical',
        8 => 'Pretty Slowly',
        9 => 'In the Stars',
        10 => 'Take Me Home',
        11 => 'Young American Heart',
        12 => 'Mr Electric Blue',
        13 => 'Love of Mine',
        14 => 'Reminds Me of You',
        15 => 'Beautiful Things',
      ),
      'encore' => 
      array (
        0 => 'Cry',
      ),
      'note' => 'Setlists vary night to night, so the order and song choices above are a guide drawn from recent shows rather than a fixed list for any single date. Boone often adjusts the running order, swaps in medley segments and occasionally adds a one-off cover, so individual performances differ.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Benson Boone play live?',
          'a' => 'Benson Boone\'s live shows usually feature singles such as Beautiful Things, In the Stars and Mystical Magical alongside recent album tracks like Sorry I\'m Here for Someone Else, Wanted Man and Young American Heart. The exact running order shifts between dates, but those songs have been regular fixtures on his recent tours.',
        ),
        1 => 
        array (
          'q' => 'Does Benson Boone play Beautiful Things live?',
          'a' => 'Yes. Beautiful Things, his breakthrough single, is a regular part of Benson Boone\'s live set and typically lands late in the night as one of the show\'s bigger moments. As with any song, its placement can move between dates, but it has been a consistent feature on recent tours.',
        ),
        2 => 
        array (
          'q' => 'Does Benson Boone do an encore?',
          'a' => 'Benson Boone often closes with an encore, with Cry featuring as a recent choice to end the night. Encore content can change between shows, and on some dates the set runs continuously, so the closing section is not fixed across every performance.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Benson Boone play in concert?',
          'a' => 'A Benson Boone headline set usually runs to roughly 17 to 19 songs, including any encore and the occasional medley segment. The precise count varies by night and venue, as he adjusts the running order and sometimes adds or drops a track between dates.',
        ),
      ),
    ),
  ),
  'teddy-swims' => 
  array (
    'name' => 'Teddy Swims',
    'genre' => 'Soul',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Teddy Swims ticket prices reflect his move from clubs and theatres into full arenas, so the band is broader than it once was. Entry-level seats in the upper tiers remain among the more affordable in mainstream live soul, while floor and front-section seats carry a clear premium. Costs shift by city, day of week and how close the seat sits to the stage, and high-demand markets push the upper end well above the median.',
        1 => 'Because most of his arena shows run through Ticketmaster and Live Nation, prices on the primary market are set in tiers rather than a single flat figure. Resale listings sit on top of that and can move in either direction as a date approaches. Treating the numbers below as a guide, rather than a fixed rate, is the sensible way to read a Teddy Swims ticket price.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Seats in the upper bowl and rear sections, generally the cheapest way in. Expect roughly $45 to $90 depending on the arena and the demand for that particular night.',
        ),
        1 => 
        array (
          'name' => 'Lower level',
          'desc' => 'Side and rear lower-bowl seats with a clearer view of the stage, usually around $90 to $170. Sightlines are stronger and the sound is more even than the upper tiers.',
        ),
        2 => 
        array (
          'name' => 'Floor and close sections',
          'desc' => 'Floor seats and the closest lower-bowl blocks, typically $170 to $280. These sell quickest and tend to lead any dynamic price rises.',
        ),
        3 => 
        array (
          'name' => 'VIP and premium',
          'desc' => 'Front-row blocks and official VIP packages, which can include early entry or merchandise, commonly run $280 to $350 and higher in the busiest cities.',
        ),
      ),
      'why' => 'Most Teddy Swims arena dates are sold through Ticketmaster and Live Nation, which apply dynamic pricing. In practice this means the system adjusts face value up or down in response to real-time demand, so the best seats can climb above their starting tier when a show sells strongly and soften if it does not. There is no single fixed Teddy Swims ticket price; the figure you see depends on the section, the city and the moment you check, which is why two seats a row apart can carry different prices.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Teddy Swims tickets?',
          'a' => 'Teddy Swims tickets generally run from around $45 for upper-level arena seats to roughly $350 for floor and VIP positions. Lower-bowl seats tend to sit in the $90 to $170 band. The exact figure depends on the venue, the city and how close to the stage you sit.',
        ),
        1 => 
        array (
          'q' => 'Why do Teddy Swims ticket prices change?',
          'a' => 'Prices change because his arena shows use dynamic pricing through Ticketmaster and Live Nation, which moves face value with live demand. A strong-selling night pushes the better seats higher, while quieter dates can ease back. Resale listings add a further layer on top of the primary price.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Teddy Swims live?',
          'a' => 'The cheapest seats are in the upper tiers of the arena, usually starting near $45. Buying when a date first opens, choosing a midweek show or picking a less in-demand city all tend to keep the cost down compared with weekend dates in major markets.',
        ),
        3 => 
        array (
          'q' => 'Are Teddy Swims VIP packages worth it?',
          'a' => 'VIP packages for Teddy Swims typically bundle premium seats with extras such as early entry or merchandise, and run from about $280 upward. Whether they are worthwhile depends on how much the closer view and add-ons matter to you, as the seat-only premium alone is steep.',
        ),
        4 => 
        array (
          'q' => 'Do Teddy Swims ticket prices vary by city?',
          'a' => 'Yes. Larger markets and arenas in cities with strong demand carry higher prices, while smaller venues and quieter dates tend to be cheaper. The same seat category can differ by a wide margin between a major coastal arena and a mid-sized inland one.',
        ),
        5 => 
        array (
          'q' => 'How much are floor seats for Teddy Swims?',
          'a' => 'Floor seats and the closest lower-bowl sections for Teddy Swims usually fall in the $170 to $280 range on the primary market. They sell fastest and are the first to move under dynamic pricing, so they often climb above that band in busy cities.',
        ),
        6 => 
        array (
          'q' => 'Is resale cheaper than face value for Teddy Swims?',
          'a' => 'Sometimes. Resale prices float with demand, so they can dip below face value for slower-selling dates or sit well above it for popular shows. Checking both the primary tiers and resale listings before buying gives the clearest picture of what a seat is worth.',
        ),
        7 => 
        array (
          'q' => 'Are Teddy Swims tickets expensive compared with other soul acts?',
          'a' => 'Teddy Swims sits in the mid arena band rather than the premium stadium tier occupied by the largest pop acts. Entry-level seats are relatively accessible for a headline soul show, while floor and VIP positions reach the higher end common to any current arena-scale tour.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Ugly Tour',
      'intro' => 
      array (
        0 => 'Teddy Swims tour dates centre on The Ugly Tour, his arena run across North America that follows the wider success of his recent records. The trek moves through major indoor arenas, with festival appearances and international shows filling out the calendar around it. Routing typically threads coast to coast over several weeks, so the schedule shifts each year as new legs are confirmed.',
        1 => 'Because demand for Teddy Swims tour dates has grown alongside his profile, individual shows can sell quickly once they open. Presales, venue holds and general on-sales each release seats at different points, and some markets add second nights when the first sells out. Checking the official listings for a given city is the reliable way to see which dates are currently scheduled.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Teddy Swims touring in 2026?',
          'a' => 'Yes. Teddy Swims is touring on The Ugly Tour, an arena run across North America, alongside festival sets and international dates. The schedule moves city to city over several weeks, so the exact stops depend on which legs are currently confirmed for the year you are checking.',
        ),
        1 => 
        array (
          'q' => 'What is Teddy Swims\'s current tour called?',
          'a' => 'His current run is The Ugly Tour, a headline arena tour through North American cities. It follows his earlier touring behind the I\'ve Tried Everything But Therapy material and reflects his step up to larger indoor venues.',
        ),
        2 => 
        array (
          'q' => 'Where can I see Teddy Swims live?',
          'a' => 'Teddy Swims plays major indoor arenas on The Ugly Tour, with the route covering cities across North America from coast to coast. He also appears at selected festivals and adds international dates, so the venues vary widely by region and leg.',
        ),
        3 => 
        array (
          'q' => 'Does Teddy Swims add extra tour dates?',
          'a' => 'Yes. When a show sells out quickly, second nights or additional cities are sometimes added to a leg. New dates tend to surface as a tour is announced and extended, so the listing for any given city can change over time.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Teddy Swims setlist leans on the soul-pop singles that built his audience, balanced against deeper album cuts and the slower, vocal-led moments his shows are known for. Recent arena dates have opened with up-tempo material before easing into the ballads, with the biggest hits weighted toward the closing stretch. The running order changes between nights, but the core of the set stays broadly consistent across a tour.',
      ),
      'songs' => 
      array (
        0 => 'Some Things I\'ll Never Know',
        1 => 'Bad Dreams',
        2 => 'Guilty',
        3 => 'Devil in a Dress',
        4 => 'Are You Even Real',
        5 => 'She Loves the Rain',
        6 => 'What More Can I Say',
        7 => 'Northern Lights',
        8 => 'Hammer to the Heart',
        9 => 'All That Really Matters',
        10 => 'The Door',
        11 => 'Not Your Man',
        12 => 'Apple Juice',
        13 => 'Need You More',
        14 => 'Your Kind of Crazy',
        15 => 'Lose Control',
      ),
      'encore' => 
      array (
        0 => 'Bed on Fire',
        1 => 'Goodbye\'s Been Good to You',
      ),
      'note' => 'Setlists vary night to night, so the exact songs and running order differ between shows on the tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Teddy Swims play live?',
          'a' => 'Teddy Swims usually plays the singles that defined his rise, including Lose Control, Bad Dreams, The Door and Devil in a Dress, alongside slower cuts such as Some Things I\'ll Never Know. The order shifts night to night, but those soul-pop staples anchor most recent shows.',
        ),
        1 => 
        array (
          'q' => 'Does Teddy Swims do an encore?',
          'a' => 'Yes. Recent Teddy Swims shows have closed with a short encore, typically built around Bed on Fire and Goodbye\'s Been Good to You after the main set ends on Lose Control. The encore songs can change, but he has regularly returned to the stage.',
        ),
        2 => 
        array (
          'q' => 'Does Teddy Swims play Lose Control at every show?',
          'a' => 'Lose Control has been a fixture of recent Teddy Swims setlists, usually placed near the end of the main set as one of the night\'s high points. While no song is guaranteed, it has appeared consistently across his recent arena and festival dates.',
        ),
        3 => 
        array (
          'q' => 'How long is a Teddy Swims concert?',
          'a' => 'A headline Teddy Swims set generally runs in the region of 90 minutes to two hours, covering his singles, album tracks and a short encore. The exact length varies by venue and whether the date is a festival slot or a full headline arena show.',
        ),
      ),
    ),
  ),
  'noah-kahan' => 
  array (
    'name' => 'Noah Kahan',
    'genre' => 'Folk-Pop',
    'prices' => 
    array (
      'range_low' => 100,
      'range_high' => 550,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Noah Kahan ticket prices vary by venue, seat location and demand, and his current run plays large ballparks and stadiums alongside a handful of arena dates, so the spread between the cheapest and most expensive seats can be wide. On most dates the upper-level, outfield and rear sections sit at the affordable end, while field, floor, pit access and front-of-stage positions command a premium. The figures here describe how pricing generally behaves rather than any single fixed rate.',
        1 => 'Because Noah Kahan plays a mix of open-air ballparks, stadiums and a few seated arena rooms, the same nominal tier can look different from city to city. Upper-deck seats, outfield areas and obstructed-view positions tend to be the most economical, and prices climb steadily as you move closer to the stage or into hospitality packages. Treat any band below as a guide to the structure of a typical on-sale rather than a guaranteed price for a particular night.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper deck and rear sections',
          'desc' => 'The most affordable way in, with standard tickets at many shows starting in the region of $100 to $180. Covers upper-tier seating, outfield and rear ballpark sections and the cheaper arena levels where availability is widest. Kahan also released a limited run of $100 \'Front Porch\' general-admission tickets ahead of the on-sale, which sold out quickly.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and mid-tier',
          'desc' => 'Generally between $180 and $300. Lower-level seated sections and mid-distance ballpark positions with clearer sightlines than the upper tiers, popular with fans who want a balance of view and cost.',
        ),
        2 => 
        array (
          'name' => 'Field, floor and front sections',
          'desc' => 'Usually $300 to $450. Field seating, floor positions, front-of-stage standing and closer ballpark sections near the main stage and runway, where demand is consistently strong.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Premium options that often run from around $450 to $550 or higher. These bundle prime seating or early-entry pit access with extras such as merchandise, dedicated entry and on-site hosting, and sell in limited numbers.',
        ),
      ),
      'why' => 'Most Noah Kahan dates are sold through Ticketmaster, which applies dynamic pricing on a large share of inventory. Under that model the listed price moves with real-time demand rather than staying fixed, so popular sections can rise above their opening figure and quieter ones may settle lower. Service and facility fees are added at checkout on top of the face value. Kahan has also used initiatives such as lower-priced \'Front Porch\' front-of-stage tickets and Ticketmaster\'s Face Value Exchange for resale, which can keep some genuine fan options closer to original prices.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Noah Kahan tickets?',
          'a' => 'Standard Noah Kahan tickets on his current tour generally start in the region of $100 to $180 for upper-deck, outfield and rear sections and rise toward $300 to $450 for field, floor and front-of-stage seats, with VIP and hospitality packages reaching roughly $450 to $550 or more. The exact figure depends on the venue, the section and how demand is tracking when you buy.',
        ),
        1 => 
        array (
          'q' => 'Why do Noah Kahan ticket prices change so much?',
          'a' => 'Most of his dates use Ticketmaster\'s dynamic pricing, which adjusts the cost of a section in line with live demand. A high-interest show can push field and lower-bowl prices well above their opening level, while less busy sections may ease back. Prices also differ between his arena rooms and the larger ballpark and stadium dates that make up most of the tour.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Noah Kahan live?',
          'a' => 'The lowest standard prices are usually found in upper-deck seats, outfield and rear ballpark sections and the cheaper arena levels, which at many shows begin around $100 to $180 before fees. Kahan also offered a limited run of $100 \'Front Porch\' general-admission tickets ahead of the on-sale, though those sold out fast. Buying early and staying flexible on seat location gives the best chance of securing the lower-priced options.',
        ),
        3 => 
        array (
          'q' => 'Do Noah Kahan tickets include extra fees?',
          'a' => 'Yes. The advertised price is the face value, and service plus facility charges are added during checkout, so the final total is higher than the headline figure. The amount varies by venue and ticket type, and it is shown before you complete the purchase.',
        ),
        4 => 
        array (
          'q' => 'Are VIP packages available for Noah Kahan shows?',
          'a' => 'Yes. Noah Kahan tours typically offer VIP and hospitality packages that combine premium seating or early pit entry with extras such as exclusive merchandise and dedicated entry. These sit at the top of the price range, often from around $450 to $550 or above, and are limited in number.',
        ),
        5 => 
        array (
          'q' => 'How much are front-row or pit tickets for Noah Kahan?',
          'a' => 'Field, floor and front-of-stage standing are among the priciest standard options, commonly in the $300 to $450 band and higher once VIP elements are attached. Ahead of his current tour Kahan offered a limited run of $100 \'Front Porch\' general-admission pit tickets aimed at fans, though those sold out quickly.',
        ),
        6 => 
        array (
          'q' => 'Is resale a good option for Noah Kahan tickets?',
          'a' => 'Resale can help when a date is sold out, but prices on the open market move with demand and can sit above or below face value. Kahan has supported Ticketmaster\'s Face Value Exchange, which lets fans resell at the original price and is generally the more predictable route for genuine seats.',
        ),
        7 => 
        array (
          'q' => 'Do Noah Kahan ticket prices differ between arena and ballpark shows?',
          'a' => 'Yes. His larger open-air ballpark and stadium dates have different seating maps from his indoor arena shows, so equivalent sections can be priced differently. Ballparks add outfield and upper-deck areas at the lower end, while premium field and front positions still command the highest standard prices.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Great Divide Tour',
      'intro' => 
      array (
        0 => 'Noah Kahan\'s current run is The Great Divide Tour, supporting his album of the same name and standing as his largest North American outing to date. The schedule moves through major ballparks and stadiums alongside a handful of arena dates, with international dates extending the run. Tour dates and on-sale details are confirmed venue by venue, so the routing and timing are best checked against each individual show rather than treated as fixed.',
        1 => 'The Great Divide Tour follows the breakthrough of Kahan\'s earlier Stick Season material and pairs older fan favourites with songs from the new record. Support acts vary across the run, and the production is built around a multi-stage layout that lets him move out into the crowd. Because tour dates are added and adjusted over time, the full list of cities is confirmed progressively rather than all at once.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Noah Kahan touring in 2026?',
          'a' => 'Yes. Noah Kahan is on The Great Divide Tour, his biggest North American run to date, taking in ballparks and stadiums such as Boston\'s Fenway Park, Chicago\'s Wrigley Field and Pasadena\'s Rose Bowl alongside arena dates and international shows. Specific cities and on-sale timings are confirmed per venue, so it is worth checking the individual date you are interested in.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Noah Kahan\'s current tour?',
          'a' => 'His current tour is The Great Divide Tour, named after the album it supports. It builds on the success of his Stick Season era and represents a step up in venue scale, with shows staged in large ballparks and stadiums as well as some arena rooms.',
        ),
        2 => 
        array (
          'q' => 'Who is opening for Noah Kahan on The Great Divide Tour?',
          'a' => 'Support acts on The Great Divide Tour vary by date, with different artists joining across the run rather than a single opener for every show. The confirmed support for any given night is listed with that specific date, so check the show you plan to attend.',
        ),
        3 => 
        array (
          'q' => 'Where is Noah Kahan playing on The Great Divide Tour?',
          'a' => 'The Great Divide Tour spans major North American ballparks and stadiums, including a record four-night stand at Boston\'s Fenway Park, plus arena dates and additional international shows. The full routing is confirmed venue by venue over time, so the complete city list is best taken from the up-to-date date listings.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A typical Noah Kahan setlist on The Great Divide Tour balances the Stick Season songs that built his audience with material from the newer record, anchored by singalong staples such as Stick Season, Dial Drunk and Northern Attitude. The running order moves between a main stage and smaller satellite stages, and the show generally closes with an encore. The selections below reflect songs that have featured regularly rather than a fixed list for any single night.',
      ),
      'songs' => 
      array (
        0 => 'American Cars',
        1 => 'Doors',
        2 => 'All My Love',
        3 => 'Everywhere, Everything',
        4 => 'She Calls Me Back',
        5 => 'Dial Drunk',
        6 => 'Porch Light',
        7 => 'Orbiter',
        8 => 'Paid Time Off',
        9 => 'The View Between Villages',
        10 => 'Northern Attitude',
        11 => 'The Great Divide',
        12 => 'Orange Juice',
        13 => 'New Perspective',
        14 => 'Call Your Mom',
        15 => 'Your Needs, My Needs',
        16 => 'Growing Sideways',
      ),
      'encore' => 
      array (
        0 => 'End of August',
        1 => 'Homesick',
        2 => 'Stick Season',
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and running order change between shows on the tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Noah Kahan play live?',
          'a' => 'Noah Kahan\'s live shows lean on his best-known tracks, including Stick Season, Dial Drunk, Northern Attitude, Orange Juice and The View Between Villages, mixed with newer material from The Great Divide such as American Cars, Doors and Paid Time Off. He also reaches back to earlier favourites like Hurt Somebody and Call Your Mom on selected nights.',
        ),
        1 => 
        array (
          'q' => 'Does Noah Kahan perform Stick Season at his concerts?',
          'a' => 'Yes. Stick Season is the song most associated with Noah Kahan and is a regular fixture of his shows, frequently held back as the closing number in the encore where it draws the loudest crowd response of the night.',
        ),
        2 => 
        array (
          'q' => 'Does Noah Kahan play an encore?',
          'a' => 'Yes. His Great Divide Tour shows typically include an encore, often featuring songs such as Homesick and Stick Season to finish. The exact encore choices can change from night to night, so the closing run is not identical at every show.',
        ),
        3 => 
        array (
          'q' => 'How long is a Noah Kahan concert?',
          'a' => 'A headline Noah Kahan set generally runs in the region of two hours, including the encore, though the precise length varies by show. The set is built from roughly twenty songs spread across his main stage and the smaller satellite stages used during the performance.',
        ),
      ),
    ),
  ),
  'laufey' => 
  array (
    'name' => 'Laufey',
    'genre' => 'Jazz-Pop',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Laufey ticket prices sit in the mid band typical of an arena and large-theatre jazz-pop act rather than a stadium headliner. Most face-value tickets land somewhere between roughly 45 and 350 US dollars, with the figure shaped by the city, the venue size and how close to the stage a seat is. Because her shows are arranged in acts with a seated, recital-style feel, sightlines matter, and the better-placed seats carry a clear premium over the upper tiers.',
        1 => 'What you pay for Laufey depends less on a single sticker price and more on the tier and the moment you buy. Primary face values from the box office tend to be the lower end of the band, while resale listings for sold-out arena dates often sit higher. Treating the range as a guide rather than a fixed cost is the sensible approach, and the breakdown below sets out roughly what each kind of seat usually involves.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, usually around 45 to 90 USD. These are the upper-level or rear-floor positions, further from the stage but with full views of the staged, multi-act production. The natural starting point for casual listeners and the seats that tend to clear first at the lower price points.',
        ),
        1 => 
        array (
          'name' => 'Mid-level reserved',
          'desc' => 'Lower-bowl and mid-floor reserved seats, generally in the 90 to 160 USD range. A balance of price and proximity, close enough to follow the quieter, jazz-leaning passages clearly. The bracket many regular concertgoers settle on for an arena date.',
        ),
        2 => 
        array (
          'name' => 'Premium and front sections',
          'desc' => 'Front-of-floor and prime lower-bowl seats, typically 160 to 260 USD. Among the closest standard tickets to the stage and band, suited to those who want the detail of the live arrangements. Demand here is strong, so availability at face value can be limited.',
        ),
        3 => 
        array (
          'name' => 'VIP and experience packages',
          'desc' => 'Official VIP packages, broadly 220 to 350 USD and sometimes beyond depending on the market. These bundle a premium seat with extras such as early entry, a merchandise item or a dedicated check-in, and are capped in number. Contents vary by date and city.',
        ),
      ),
      'why' => 'Laufey\'s tour runs through major promoters and primary ticketing platforms that use dynamic pricing, so face values can move with demand. In practice, popular dates and the best-located seats are released at or adjusted to higher prices than the cheapest tiers, and figures can shift between the on-sale and the show. The quieter, seated nature of her concerts puts a premium on proximity, which widens the gap between the lowest and highest seats. Resale prices for sold-out arena nights are set by individual sellers and may sit above or below the original face value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Laufey tickets?',
          'a' => 'Laufey tickets generally range from about 45 to 350 USD at face value, depending on the venue and seat. Upper-tier seats sit at the lower end, mid-level reserved seats fall in the middle, and premium positions or VIP packages reach the top of the band. Resale prices for sold-out arena dates can run higher.',
        ),
        1 => 
        array (
          'q' => 'Why do Laufey ticket prices vary so much?',
          'a' => 'The spread comes from venue size, seat location and dynamic pricing. Laufey plays arenas and large theatres where a front lower-bowl seat is worth far more than an upper-tier one, and because her shows reward being close to the quieter arrangements, demand for the best seats pushes those prices up while cheaper tiers stay lower.',
        ),
        2 => 
        array (
          'q' => 'Are Laufey tickets expensive?',
          'a' => 'Compared with stadium headliners, Laufey tickets are moderate, with entry-level seats often around 45 to 90 USD. She performs at the arena and theatre scale rather than stadiums, so the ceiling is lower than acts like Coldplay or Taylor Swift, though premium and VIP options still reach a few hundred dollars.',
        ),
        3 => 
        array (
          'q' => 'What is the cheapest way to see Laufey live?',
          'a' => 'The cheapest standard tickets are usually the upper-tier or rear seats, often around 45 to 90 USD at face value. Buying when a date first goes on sale, before higher-demand seats and resale markups take hold, tends to give the best chance of securing the lower price points.',
        ),
        4 => 
        array (
          'q' => 'Does Laufey use dynamic pricing?',
          'a' => 'Yes. Like most major tours on the main ticketing platforms, Laufey\'s dates use dynamic pricing, meaning face values can rise and fall with demand rather than staying fixed. The most sought-after seats and dates are typically priced higher, so checking early and comparing tiers is worthwhile.',
        ),
        5 => 
        array (
          'q' => 'How much do Laufey VIP tickets cost?',
          'a' => 'Official Laufey VIP packages generally fall in the region of 220 to 350 USD, and sometimes higher in certain markets. They pair a premium seat with extras such as early entry, a merchandise item or dedicated check-in. The exact inclusions and price vary by city and date and are limited in number.',
        ),
        6 => 
        array (
          'q' => 'Are Laufey concerts seated or standing?',
          'a' => 'Most Laufey arena and theatre shows are fully seated, which suits the staged, multi-act format of the A Matter of Time Tour. Some markets may offer a general-admission floor, but the reserved-seating model is the norm, and it is part of why proximity affects the price so noticeably.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Laufey tickets on resale or primary?',
          'a' => 'Primary face value is usually the lower option, especially when a date first goes on sale. Resale prices are set by individual sellers and, for sold-out arena nights, often sit above face value, though they can occasionally dip below it closer to the show. Comparing both before buying is sensible.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'A Matter of Time Tour',
      'intro' => 
      array (
        0 => 'Laufey\'s current run is the A Matter of Time Tour, named after her third studio album and marking her step up to major arena and large-theatre venues. The dates span North America, Europe, Asia and Oceania, presented as a staged, multi-act show rather than a standard concert format. Live tour dates and on-sale details are listed separately, as schedules and venues differ by region and are confirmed market by market.',
        1 => 'Built around the A Matter of Time material alongside earlier favourites, the tour pairs Laufey with a live band and, in some markets, fuller orchestral arrangements that suit her jazz-pop writing. It is her largest production to date and her first to reach arena scale across several continents. For specific tour dates, cities and venues, check the live listings, which are kept current as new shows are announced.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Laufey touring in 2026?',
          'a' => 'Yes. Laufey is on the A Matter of Time Tour, which extends through 2026 with dates across multiple continents including Europe, Asia and Oceania. The run supports her third album and reaches arena-scale venues. Specific cities and dates are listed in the live schedule, which updates as shows are confirmed.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Laufey\'s current tour?',
          'a' => 'Laufey\'s current tour is the A Matter of Time Tour, named after her third studio album. It is her largest production so far, moving up to arena and large-theatre venues and structured as a staged show across several acts rather than a conventional set.',
        ),
        2 => 
        array (
          'q' => 'What venues does Laufey play on tour?',
          'a' => 'On the A Matter of Time Tour, Laufey plays arenas and large theatres, a step up from the smaller theatres and halls of her earlier runs. The seated, recital-style staging suits these rooms, and exact venues differ by city, so the live listings are the place to confirm where each date is held.',
        ),
        3 => 
        array (
          'q' => 'How long is a Laufey concert?',
          'a' => 'A Laufey show on this tour typically runs in the region of an hour and a half to two hours, including a support act and an encore on most nights. The set is arranged in distinct acts, so the exact running time can vary by date and market.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Laufey setlist on the A Matter of Time Tour draws heavily on her third album while keeping room for the earlier songs that built her following. The show is arranged in acts, moving between fuller band moments and quieter, jazz-leaning passages, and tends to open with newer material before reaching the better-known singles later on. The list below reflects songs that have been regular fixtures on recent dates.',
      ),
      'songs' => 
      array (
        0 => 'Clockwork',
        1 => 'Lover Girl',
        2 => 'Dreamer',
        3 => 'Falling Behind',
        4 => 'Silver Lining',
        5 => 'Bored',
        6 => 'Too Little, Too Late',
        7 => 'Valentine',
        8 => 'Fragile',
        9 => 'Second Best',
        10 => 'Let You Break My Heart Again',
        11 => 'Carousel',
        12 => 'Forget-Me-Not',
        13 => 'Mr. Eclectic',
        14 => 'Castle in Hollywood',
        15 => 'Promise',
        16 => 'Goddess',
        17 => 'Tough Luck',
        18 => 'Snow White',
        19 => 'From the Start',
      ),
      'encore' => 
      array (
        0 => 'How I Get',
        1 => 'Letter to My 13 Year Old Self',
      ),
      'note' => 'Setlists vary from night to night, and the order, song choices and encore can change between dates and markets, so any individual show may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Laufey play live?',
          'a' => 'Laufey\'s recent shows lean on her A Matter of Time album alongside established favourites such as From the Start, Falling Behind, Valentine, Bored and Let You Break My Heart Again. Newer tracks like Clockwork, Lover Girl, Tough Luck and Goddess also feature, though the exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Laufey play From the Start live?',
          'a' => 'Yes. From the Start, one of her best-known singles, has been a regular part of recent Laufey setlists and usually appears in the later stretch of the main set. As with any song, its inclusion is not guaranteed at every show, since the running order varies by date.',
        ),
        2 => 
        array (
          'q' => 'Does Laufey do an encore?',
          'a' => 'Yes, most Laufey dates on this tour close with an encore, often featuring songs such as How I Get and Letter to My 13 Year Old Self. The encore can change between shows, and on some nights the format or song choices may differ slightly from one date to the next.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Laufey play in concert?',
          'a' => 'A typical Laufey set on the A Matter of Time Tour runs to roughly twenty songs across its acts, including the encore. The number can shift between dates depending on the market and the staging, and instrumental interludes are sometimes woven in between the sung material.',
        ),
      ),
    ),
  ),
  'mumford-and-sons' => 
  array (
    'name' => 'Mumford & Sons',
    'genre' => 'Folk-Rock',
    'prices' => 
    array (
      'range_low' => 45,
      'range_high' => 350,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Mumford & Sons ticket prices tend to sit in the mid-range typical of arena and amphitheatre folk-rock tours rather than the higher bands seen at full stadium runs. Most shows open with affordable upper-level and lawn seating before climbing through the lower bowl to premium front-of-stage positions. Where a date lands at a larger outdoor field or festival, the top end stretches further, but the band has generally kept a broad spread of price points across each venue.',
        1 => 'What you pay for a Mumford & Sons show depends mainly on the venue, the seat location and how briskly a given night sells. The band routinely plays a mix of arenas, amphitheatres and the occasional larger field date, and each format carries its own pricing logic. The figures here describe the usual shape of the market so you can judge whether a listing looks fair before any live, date-specific numbers are applied.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Lawn and upper level',
          'desc' => 'The entry point for most Mumford & Sons dates, usually around $45 to $80. Covers general-admission lawn at amphitheatres and upper-tier seating in arenas. The view is distant but the open, communal sound of a folk-rock crowd carries well from these sections.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and reserved seating',
          'desc' => 'Mid-range reserved seats, generally $80 to $150, in the lower tiers of arenas or the covered pavilion at outdoor venues. A reliable balance of sightline and cost, and the band of price points most show-goers settle on.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Floor seating or standing pit positions near the stage, typically $150 to $260. Closest to the band\'s acoustic-to-full-band dynamics, where the build of songs like The Cave registers most directly. Demand here tends to firm up first.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official VIP packages run roughly $250 to $350 and above, bundling premium seating with early entry, dedicated club or lounge access and collectible merchandise. Priced per the package contents rather than the seat alone, so value varies by what is included.',
        ),
      ),
      'why' => 'Mumford & Sons tours are handled through Ticketmaster and Live Nation, which means most on-sales use dynamic pricing. Face values move up or down with real-time demand, so a popular night can climb well above the original list price while a softer date may ease below it. Platform and Verified Resale listings add their own service fees on top. Because of this, the same seat can carry different prices at different moments, and the ranges here describe the broad market rather than a fixed tariff. Checking a few sections and timing a purchase away from peak demand usually gives the clearest read on fair value.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Mumford & Sons tickets?',
          'a' => 'Mumford & Sons tickets generally run from about $45 for lawn and upper-level seats to $260 or more for floor and front sections, with official VIP packages reaching $350 and above. As an arena and amphitheatre act, their pricing sits in the mid-range rather than the high stadium band, though larger outdoor dates can push the top end further.',
        ),
        1 => 
        array (
          'q' => 'Why do Mumford & Sons ticket prices change so much?',
          'a' => 'Most Mumford & Sons dates use Ticketmaster dynamic pricing, so face values rise and fall with live demand. A high-demand night can climb above the initial list price while a quieter date may settle lower. Service fees and resale markups add further variation, which is why the same seat can show different prices at different times.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Mumford & Sons live?',
          'a' => 'The lowest-cost option is usually general-admission lawn at an amphitheatre or upper-tier arena seating, often starting around $45 to $80. Buying away from peak demand windows and comparing several sections tends to surface the best value, since dynamic pricing can move the entry point during busy on-sale periods.',
        ),
        3 => 
        array (
          'q' => 'Are Mumford & Sons VIP packages worth it?',
          'a' => 'Official VIP packages, generally $250 to $350 and up, bundle premium seats with perks such as early entry, club or lounge access and exclusive merchandise. Whether they justify the cost depends on how much you value those extras, since you are paying for the package contents rather than the seat location alone.',
        ),
        4 => 
        array (
          'q' => 'How much are floor tickets for Mumford & Sons?',
          'a' => 'Floor seating or standing positions near the stage typically cost between $150 and $260, depending on the venue and demand. These sections put you closest to the band\'s shift between acoustic passages and full-volume choruses, and they generally sell faster than the upper levels.',
        ),
        5 => 
        array (
          'q' => 'Do Mumford & Sons tickets get cheaper closer to the show?',
          'a' => 'Sometimes. Under dynamic pricing and on resale markets, prices for softer-selling dates can ease in the final days, but in-demand shows often hold firm or rise. There is no guarantee either way, so waiting is a gamble that pays off mainly for dates that have not sold strongly.',
        ),
        6 => 
        array (
          'q' => 'Are resale Mumford & Sons tickets safe to buy?',
          'a' => 'Resale tickets bought through Ticketmaster Verified Resale or other reputable platforms are generally safe, as transfers are validated. Expect added service fees on top of the listed price. Prices on the secondary market can run above or below face value depending on how a particular night is selling.',
        ),
        7 => 
        array (
          'q' => 'Do Mumford & Sons offer any discounted tickets?',
          'a' => 'Some dates have featured limited box-office initiatives such as student-priced tickets, though availability varies by show and is not guaranteed across a tour. Outside those, the most dependable savings come from lawn and upper-level seating and from buying away from the busiest on-sale moments.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Prizefighter Tour',
      'intro' => 
      array (
        0 => 'Mumford & Sons are touring behind Prizefighter, their sixth studio album, released in early 2026 and produced with Aaron Dessner. The Prizefighter Tour follows the 2025 record RUSHMERE and threads new material through the catalogue staples that built the band\'s reputation. Tour dates span arenas, amphitheatres and a handful of larger outdoor venues, with the routing weighted toward North America before moving to selected international markets.',
        1 => 'The Prizefighter Tour continues the band\'s pattern of pairing a fresh album cycle with the long-standing songs audiences expect. Because schedules and on-sale windows are confirmed in stages, the most reliable way to track Mumford & Sons tour dates is through the venue and the band\'s official ticketing partners. The notes here cover how the tour is structured rather than any single fixed itinerary.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Mumford & Sons touring in 2026?',
          'a' => 'Yes. Mumford & Sons are on the road with the Prizefighter Tour, supporting their 2026 album of the same name. The run takes in arenas, amphitheatres and some larger outdoor dates, led by a North American leg with further international shows. Specific dates and cities are confirmed in stages through their official ticketing partners.',
        ),
        1 => 
        array (
          'q' => 'What is the name of Mumford & Sons\' current tour?',
          'a' => 'The current run is the Prizefighter Tour, named after the band\'s sixth studio album released in early 2026. It follows the touring cycle around their 2025 record RUSHMERE and combines new songs with the catalogue staples that have anchored their live shows for years.',
        ),
        2 => 
        array (
          'q' => 'Where are Mumford & Sons playing on the Prizefighter Tour?',
          'a' => 'The Prizefighter Tour is weighted toward North American arenas and amphitheatres, with a number of larger outdoor and festival-scale dates and selected international shows. The exact city list is rolled out in phases, so the band\'s official site and ticketing partners hold the current, confirmed schedule.',
        ),
        3 => 
        array (
          'q' => 'How long are Mumford & Sons concerts?',
          'a' => 'A Mumford & Sons headline show typically runs around two hours, give or take, depending on the setlist and venue. That allows room for both the newer Prizefighter material and the long-standing singalongs, along with the band\'s habit of stretching certain songs into extended live arrangements.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Mumford & Sons setlist on recent tours blends the early singles that broke the band with selections from their newer records, RUSHMERE and Prizefighter. Expect the familiar acoustic-led builds alongside the heavier, fuller-band arrangements developed over later albums. The setlist below reflects songs the band has leaned on across recent dates, though the exact running order and song choices shift from one night to the next.',
      ),
      'songs' => 
      array (
        0 => 'Little Lion Man',
        1 => 'The Cave',
        2 => 'I Will Wait',
        3 => 'Awake My Soul',
        4 => 'Roll Away Your Stone',
        5 => 'Lover of the Light',
        6 => 'Babel',
        7 => 'Ghosts That We Knew',
        8 => 'White Blank Page',
        9 => 'Timshel',
        10 => 'Believe',
        11 => 'Guiding Light',
        12 => 'Delta',
        13 => 'The Wolf',
        14 => 'Rushmere',
        15 => 'Truth',
        16 => 'Prizefighter',
        17 => 'Whispers in the Dark',
        18 => 'Holland Road',
      ),
      'encore' => 
      array (
        0 => 'Cold Arms',
        1 => 'After the Storm',
      ),
      'note' => 'Setlists vary night to night, so the running order and individual song choices change from show to show across the tour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs do Mumford & Sons play live?',
          'a' => 'Mumford & Sons typically open out their set with staples such as Little Lion Man, The Cave, I Will Wait and Awake My Soul, alongside newer material from RUSHMERE and Prizefighter. Deeper cuts like Lover of the Light, Ghosts That We Knew and Roll Away Your Stone also feature regularly, though the exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Do Mumford & Sons play Little Lion Man live?',
          'a' => 'Yes. Little Lion Man, the band\'s breakthrough single, remains a near-constant fixture of their live shows and one of the surest singalong moments of the night. As with any song it can be dropped or moved on a given date, but it has been a long-standing staple across recent tours.',
        ),
        2 => 
        array (
          'q' => 'Does Mumford & Sons do an encore?',
          'a' => 'Yes. Mumford & Sons shows usually close with an encore, often featuring a quieter, acoustic-leaning number before a fuller final song such as After the Storm. The encore contents vary by night, and on some occasions the band has woven in a cover during this stretch of the set.',
        ),
        3 => 
        array (
          'q' => 'How many songs are in a Mumford & Sons setlist?',
          'a' => 'A typical Mumford & Sons headline set runs to roughly eighteen to twenty-two songs, including the encore. The balance between older singles and newer Prizefighter and RUSHMERE material shifts from show to show, so the precise count and running order are not fixed across the tour.',
        ),
      ),
    ),
  ),
);
