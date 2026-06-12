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
      'range_low' => 90,
      'range_high' => 1200,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Taylor Swift tickets generally run from around $90 for upper-bowl seats to $1,200 or more for premium floor positions and VIP packages, though the figure you actually pay depends on the stadium, the night and how soon you buy. As a stadium headliner, her shows sit at the higher end of the live-music market, and the cheapest seats tend to disappear first.',
        1 => 'Because most of her tours are sold through Ticketmaster\'s Verified Fan and dynamic pricing systems, Taylor Swift ticket prices move with demand rather than holding a single fixed face value. The headline number you see at on-sale is a starting point, and resale prices on the secondary market frequently sit well above the original face value for the best-located seats.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / back bowl',
          'desc' => 'The most affordable seats, high in the stands and behind or to the side of the stage. Typically around $90 to $200 at face value, these go quickly and are the first tier to sell out at major stadium dates.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / sideline',
          'desc' => 'Seated views along the sides of the field with a clear sightline to the main stage and runway. Usually in the $200 to $450 range, depending on the city and how close to centre the block sits.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing or seated positions on the field, often closest to the catwalk and B-stage. Commonly $400 to $750 at face, with prices climbing sharply for spots near the front.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Bundled tickets that add early entry, merchandise, a commemorative item or premium seating. These typically run from roughly $500 to $1,200 or more, with the exact contents varying by tour and city.',
        ),
      ),
      'why' => 'Taylor Swift\'s tours run on Ticketmaster\'s dynamic pricing alongside the Verified Fan registration system, so face value is not fixed. Prices rise and fall with real-time demand, which means the same seat can cost noticeably more during peak buying windows than at quieter moments. With demand routinely outstripping supply, a large share of tickets also changes hands on the resale market, where prices commonly exceed the original face value, particularly for floor and lower-bowl seats.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Taylor Swift tickets?',
          'a' => 'Taylor Swift tickets typically range from about $90 for upper-level seats to $1,200 or more for premium floor spots and VIP packages. The final price depends on the stadium, the date and demand, and resale tickets often cost significantly more than face value.',
        ),
        1 => 
        array (
          'q' => 'Why are Taylor Swift tickets so expensive?',
          'a' => 'Demand vastly outstrips supply, and her tours use Ticketmaster\'s dynamic pricing, so prices climb as interest rises. Most shows are stadium-scale events, which command higher face values, and the resale market pushes the best seats well above their original price.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Taylor Swift live?',
          'a' => 'The cheapest route is buying upper-level seats at face value during the original on-sale, which start around $90. Registering for Verified Fan in advance improves your chance of access before tickets reach the higher-priced resale market.',
        ),
        3 => 
        array (
          'q' => 'How much do Taylor Swift VIP tickets cost?',
          'a' => 'Taylor Swift VIP packages generally run from around $500 to $1,200 or more, depending on the tour and city. They usually bundle premium seating with extras such as early entry, exclusive merchandise or a commemorative gift.',
        ),
        4 => 
        array (
          'q' => 'Does Taylor Swift use dynamic pricing?',
          'a' => 'Yes. Her major tours sell through Ticketmaster\'s dynamic pricing, so face value shifts with demand rather than staying fixed. This is why the same seat can show different prices depending on when you check during the on-sale.',
        ),
        5 => 
        array (
          'q' => 'How much are floor tickets for Taylor Swift?',
          'a' => 'Floor and general-admission tickets usually start around $400 and can reach $750 or more at face value, with positions nearest the catwalk costing the most. On resale, prime floor spots frequently sell for several times the original price.',
        ),
        6 => 
        array (
          'q' => 'Are Taylor Swift tickets cheaper on resale?',
          'a' => 'Rarely. Because demand is so high, Taylor Swift resale tickets almost always cost more than face value, especially for floor and lower-bowl seats. Prices can occasionally soften close to the show date, but bargains are uncommon.',
        ),
        7 => 
        array (
          'q' => 'Do Taylor Swift ticket prices vary by city?',
          'a' => 'Yes. Prices vary by stadium size, market and local demand, so a floor seat in one city can cost more than the equivalent elsewhere. Larger, higher-demand markets tend to carry the steepest face values and resale markups.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Taylor Swift Live',
      'intro' => 
      array (
        0 => 'Taylor Swift tours on a stadium scale, typically mounting long multi-night runs in major cities rather than brief club or arena visits. Her tour dates are released in waves through Ticketmaster\'s Verified Fan system, with registration opening well ahead of the public on-sale, and the biggest markets often add extra nights to meet demand. International legs are common, spanning North America, Europe, Asia and Oceania.',
        1 => 'Because her productions are elaborate and span much of her catalogue, runs are planned far in advance and announced city by city. When new Taylor Swift tour dates go live, demand routinely overwhelms availability, so fans are encouraged to register early, set up account alerts and be ready the moment an on-sale begins.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Taylor Swift touring in 2026?',
          'a' => 'Taylor Swift\'s touring plans are announced city by city through official channels and Ticketmaster\'s Verified Fan system. Check her official site and this page for confirmed dates, as live schedules are updated separately rather than fixed here in advance.',
        ),
        1 => 
        array (
          'q' => 'How do I get Taylor Swift presale tickets?',
          'a' => 'Presale access usually comes through Ticketmaster\'s Verified Fan registration, which opens before the general on-sale. You sign up in advance, and selected fans receive a code by email or text to buy during a dedicated early window.',
        ),
        2 => 
        array (
          'q' => 'Where does Taylor Swift usually perform?',
          'a' => 'Taylor Swift performs almost exclusively in large stadiums, often with multiple consecutive nights in the same city. Her tours span North America, Europe, Asia and Oceania, with the biggest markets frequently adding extra shows to meet demand.',
        ),
        3 => 
        array (
          'q' => 'How long is a Taylor Swift concert?',
          'a' => 'A Taylor Swift headline show typically runs well over three hours, making it one of the longest sets among major touring artists. The length reflects a deep catalogue and an elaborate, multi-act stage production.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Taylor Swift\'s recent live shows draw from across her catalogue, blending early hits with material from her newer records and a rotating section of surprise songs. The setlist below reflects staples that have anchored her recent stadium performances, though the running order and song choices shift from night to night.',
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
        7 => 'We Are Never Ever Getting Back Together',
        8 => 'Enchanted',
        9 => 'Look What You Made Me Do',
        10 => 'Don\'t Blame Me',
        11 => 'All Too Well',
        12 => 'Anti-Hero',
        13 => 'Lavender Haze',
        14 => 'Bad Blood',
        15 => 'willow',
        16 => 'cardigan',
        17 => 'Fearless',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night. Taylor Swift rotates a section of surprise acoustic songs at each show, so the exact lineup and order change from city to city and date to date.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Taylor Swift play live?',
          'a' => 'Taylor Swift\'s recent shows feature staples such as Cruel Summer, Love Story, Blank Space, Style, Shake It Off, All Too Well and Anti-Hero, drawn from across her catalogue. The exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Taylor Swift do an encore?',
          'a' => 'Taylor Swift\'s recent shows tend to run as one continuous, multi-act performance rather than ending with a separate encore. The set is long and structured into eras, so the closing songs flow directly from the main show.',
        ),
        2 => 
        array (
          'q' => 'Does Taylor Swift play surprise songs?',
          'a' => 'Yes. A signature feature of her recent tours is a section of surprise acoustic songs, usually one on guitar and one on piano, that she changes at every show. These selections rarely repeat across dates.',
        ),
        3 => 
        array (
          'q' => 'Does Taylor Swift play old songs live?',
          'a' => 'Yes. Her recent setlists span her whole career, mixing early hits like Love Story, Fearless and You Belong with Me with newer material. The show is structured around her successive eras, so older songs feature heavily.',
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
      'range_low' => 70,
      'range_high' => 500,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Coldplay ticket prices generally start around $70 for upper-tier and rear-stadium seats and climb past $400 for lower-bowl, pitch-standing and premium hospitality, with the figure shifting by city, date and how early you book. Because the band tours stadiums on their biggest runs, the spread is wider than a typical arena act, and the best-value seats tend to be the upper tiers that still face the main stage.',
        1 => 'What you actually pay depends heavily on demand. Coldplay\'s major tours run through Ticketmaster and Live Nation, both of which use dynamic pricing, so the headline number moves with how fast a given night is selling rather than sitting at a fixed face value. Wristband and production costs are built into the show, not charged separately, though official VIP and hospitality packages sit well above standard seating.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / rear stadium',
          'desc' => 'The most affordable way in, usually around $70-$130. You are high up or toward the back, but Coldplay\'s shows are built around big screens, lasers and the LED wristbands handed to every seat, so the production reads well from distance.',
        ),
        1 => 
        array (
          'name' => 'Mid tier / side stands',
          'desc' => 'Typically $130-$220. A balance of price and sightline, with a clearer view of the main stage and the B-stage walkways the band often uses to play in the round during the set.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl / front stands',
          'desc' => 'Generally $220-$330. Closer, level-ish views of the stage with strong sound, popular with fans who want detail without committing to standing for the whole night.',
        ),
        3 => 
        array (
          'name' => 'Pitch standing / floor',
          'desc' => 'Usually $250-$400 depending on the market. General-admission standing on the field, closest to the stage and the runways, and the first tier to sell out for in-demand nights.',
        ),
        4 => 
        array (
          'name' => 'Official VIP & hospitality',
          'desc' => 'Roughly $400-$500 and up. Packages bundle premium seating or early pitch entry with lounge access, hosts and merchandise. Sold through Ticketmaster or the band\'s official VIP partner rather than resale.',
        ),
      ),
      'why' => 'Coldplay\'s major tours are sold through Ticketmaster and Live Nation, which apply dynamic pricing on most dates. That means the price you see is set by live demand: high-demand nights and the best sections rise above face value, while slower mid-week or later dates can sit lower. There is no single fixed price for a seat type across the tour, so two people in the same row can pay different amounts depending on when they bought. Checking a few dates and booking earlier generally beats waiting for a section to climb.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Coldplay tickets?',
          'a' => 'Coldplay tickets typically range from around $70 for upper-tier stadium seats to $400 or more for pitch standing and premium areas, with official VIP packages running higher. Exact prices depend on the city, the date and demand, since the band\'s stadium tours use dynamic pricing rather than fixed face value.',
        ),
        1 => 
        array (
          'q' => 'Why are Coldplay tickets so expensive?',
          'a' => 'The higher prices reflect Coldplay\'s stadium-scale production and dynamic pricing, which lets the cost of the best seats rise with demand. Their shows include full laser rigs, pyrotechnics and the LED wristband system across the whole crowd, and on busy nights lower-bowl and pitch tickets can climb well above the entry-level price.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Coldplay live?',
          'a' => 'The cheapest seats are usually the upper tiers and rear stadium sections, often around $70-$130. Because the show is designed around large screens and synchronised wristbands, those seats still get the full visual effect. Booking early and comparing several tour dates is the most reliable way to find lower prices.',
        ),
        3 => 
        array (
          'q' => 'Are Coldplay floor or pitch tickets worth it?',
          'a' => 'Pitch standing puts you closest to the main stage and the runways the band uses to perform in the round, which suits fans who want the most immersive experience. It is general admission, so you stand for the night and arrive early for a good spot. Expect roughly $250-$400 depending on the market.',
        ),
        4 => 
        array (
          'q' => 'Does Coldplay use dynamic pricing?',
          'a' => 'Yes. Coldplay\'s major tours run through Ticketmaster and Live Nation, both of which apply dynamic pricing on most dates. The displayed price moves with real-time demand, so popular nights and prime sections sit higher, and the same seat type can cost different amounts across different dates.',
        ),
        5 => 
        array (
          'q' => 'How much are Coldplay VIP tickets?',
          'a' => 'Official Coldplay VIP and hospitality packages generally start around $400 and rise depending on the inclusions. They typically bundle premium seating or early pitch entry with lounge access, a host and exclusive merchandise, and are sold through Ticketmaster or the band\'s official VIP partner rather than the resale market.',
        ),
        6 => 
        array (
          'q' => 'Do Coldplay ticket prices go down closer to the show?',
          'a' => 'Not reliably. Because of dynamic pricing, prices track demand rather than the calendar, so high-demand nights tend to hold or rise as they sell. Slower mid-week or later dates can occasionally soften, but for popular shows waiting usually means fewer choices rather than a lower price.',
        ),
        7 => 
        array (
          'q' => 'Are LED wristbands included in the ticket price?',
          'a' => 'Yes. The Xyloband LED wristbands are handed out free to every ticket holder at the venue and are part of the production, not an extra charge. They light up in sync with the music across the whole stadium, which is why even the cheaper upper-tier seats still get the full visual effect.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Coldplay World Tour',
      'intro' => 
      array (
        0 => 'Coldplay tour dates tend to favour large stadiums and open-air venues, with the band routing in long runs that take in Europe, the Americas, Asia and Oceania across a single touring cycle. They are known for multi-night residencies in major cities rather than a single show per market, which spreads demand and gives fans more than one date to aim for when a city goes on sale.',
        1 => 'Routing usually follows album cycles, and the band has leaned into longer, environmentally focused tours that play fewer cities but more nights in each. When checking Coldplay tour dates, it helps to look at nearby cities and the full residency in a market, since a sold-out opening night is often followed by additional dates that are easier to book.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Coldplay touring in 2026?',
          'a' => 'Coldplay tour in cycles tied to their album releases and have maintained an active stadium touring schedule in recent years. Specific 2026 dates depend on the current routing, so check the live listings on this page for confirmed cities and on-sale information rather than relying on past schedules.',
        ),
        1 => 
        array (
          'q' => 'What venues does Coldplay play on tour?',
          'a' => 'Coldplay primarily play large stadiums and open-air venues on their major tours, often booking multi-night residencies in a single city. This stadium-scale routing accommodates their full laser and wristband production, which is why a market may see several consecutive Coldplay dates rather than one show.',
        ),
        2 => 
        array (
          'q' => 'How many shows does Coldplay play in each city?',
          'a' => 'On their biggest tours Coldplay frequently play multiple consecutive nights in major cities, sometimes a run of several dates in one stadium. If an opening night sells out quickly, later dates in the same residency are often the better target, so it is worth checking the full set of dates for a city.',
        ),
        3 => 
        array (
          'q' => 'How do I get presale access for Coldplay tour dates?',
          'a' => 'Presale access for Coldplay usually comes through the band\'s mailing list, official fan registration and partner presales such as those run by Ticketmaster or a tour sponsor. Registering ahead of an on-sale and signing up for alerts on the city you want is the most reliable way to reach tickets before the general sale.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Coldplay setlists in recent shows blend the era-defining singles with newer material and a handful of older fan favourites, structured around the band\'s stadium production of lasers, confetti and synchronised wristbands. The order changes between nights, but the broad shape of the show, from a high-energy opening to a quieter acoustic mid-section on the B-stage and a singalong finish, stays fairly consistent across a tour.',
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
        8 => 'Clocks',
        9 => 'In My Place',
        10 => 'Speed of Sound',
        11 => 'Something Just Like This',
        12 => 'A Sky Full of Stars',
        13 => 'Fix You',
        14 => 'My Universe',
        15 => 'Sparks',
        16 => 'People of the Pride',
      ),
      'encore' => 
      array (
        0 => 'Sunrise',
        1 => 'Coloratura',
        2 => 'Biutyful',
      ),
      'note' => 'Setlists vary night to night. Coldplay rotate songs, take requests and sometimes add acoustic covers or surprises on the B-stage, so the exact running order and song choices differ from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Coldplay play live?',
          'a' => 'Coldplay typically play their biggest singles live, including Yellow, Viva la Vida, The Scientist, Fix You, Paradise, A Sky Full of Stars and Adventure of a Lifetime, alongside newer tracks. The exact selection rotates from night to night, but those signature songs are near-fixtures in the set.',
        ),
        1 => 
        array (
          'q' => 'Does Coldplay do an encore?',
          'a' => 'Yes. Coldplay usually close with an encore that often moves to a smaller B-stage for quieter or acoustic material before a big final singalong such as A Sky Full of Stars or Fix You. The specific encore songs vary between shows, and they sometimes include deeper cuts like Coloratura.',
        ),
        2 => 
        array (
          'q' => 'How long is a Coldplay concert?',
          'a' => 'A Coldplay headline show generally runs around two hours, covering roughly 20 or more songs across the main set and encore. The length can vary slightly by tour and venue curfew, with the band\'s stadium shows tending toward the longer end of that range.',
        ),
        3 => 
        array (
          'q' => 'Does Coldplay play old songs like Yellow and Clocks?',
          'a' => 'Yes. Coldplay keep early favourites such as Yellow, Clocks, The Scientist and In My Place in regular rotation, often using the quieter ones in the acoustic B-stage section. Not every older song appears every night, but the most-loved early tracks are reliably part of the live set.',
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
      'range_low' => 60,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Shakira tickets generally run from around $60 for upper-tier seats to $400 or more for floor positions and VIP packages, though the figure you actually pay depends on the city, the date and how far in advance you book. Her tours typically play arenas and stadiums, and the larger the room, the wider the spread between the cheapest seats and the front of the floor.',
        1 => 'Because Shakira\'s recent dates sit on Ticketmaster and Live Nation systems, most shows use dynamic pricing, which means the headline number moves with demand rather than holding at a fixed face value. Stadium nights in major Latin American and European markets tend to sit at the higher end of that band, while smaller arena stops can open lower.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / back of bowl',
          'desc' => 'The most affordable seats, high up or behind the stage, usually in the $60-110 range. You trade proximity for price, but sightlines in most arenas and stadiums remain workable and the production reads well from distance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / side seating',
          'desc' => 'Seated positions along the sides and lower levels, typically $110-200. A solid middle ground with a clear angle on the stage and the catwalk that Shakira often uses to move out into the crowd.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing or seated floor sections close to the action, generally $180-300. Demand here is strong because of the energy near the stage, and dynamic pricing tends to push these the hardest.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official packages bundling premium positions with early entry, merchandise or hospitality, commonly $300-400 and occasionally beyond. Inclusions vary by tour and market, so check exactly what each package covers before buying.',
        ),
      ),
      'why' => 'Most of Shakira\'s major dates run through Ticketmaster and Live Nation, which means dynamic pricing is in play on a large share of her shows. Under that model the price of a given section shifts in real time with how many people are trying to buy, so two identical seats can carry different numbers depending on when you look. This is why a stadium night in a high-demand market can cost noticeably more than an arena stop elsewhere, and why booking early, before a section heats up, often works in your favour.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Shakira tickets?',
          'a' => 'Shakira tickets usually range from about $60 for upper-tier seats to $400 or more for floor spots and VIP packages. The exact price depends on whether the show is in an arena or a stadium, the city, the date and how early you buy, since most dates use dynamic pricing.',
        ),
        1 => 
        array (
          'q' => 'Why are some Shakira tickets so expensive?',
          'a' => 'The higher prices come from premium floor positions and official VIP packages, combined with dynamic pricing that lifts in-demand sections as a show sells. Stadium nights in major Latin American and European markets tend to carry the steepest numbers because demand there is heaviest.',
        ),
        2 => 
        array (
          'q' => 'Are Shakira tickets cheaper if you buy early?',
          'a' => 'Often, yes. Because dynamic pricing rises with demand, sections frequently sit at their lowest soon after a show goes on sale and climb as seats disappear. Booking early gives you the best chance at face-level pricing before popular sections heat up.',
        ),
        3 => 
        array (
          'q' => 'How much do Shakira VIP packages cost?',
          'a' => 'Shakira VIP packages typically run from around $300 to $400 and sometimes higher, depending on the tour and market. They usually bundle premium seating with extras such as early entry, exclusive merchandise or hospitality, so confirm what each package actually includes before purchasing.',
        ),
        4 => 
        array (
          'q' => 'What is the cheapest way to see Shakira live?',
          'a' => 'The cheapest seats are the upper tiers and sections behind the stage, generally starting around $60. Buying these soon after on-sale, before dynamic pricing pushes them up, is the most reliable way to keep the cost down while still seeing the full production.',
        ),
        5 => 
        array (
          'q' => 'Do Shakira ticket prices change?',
          'a' => 'Yes. Most of Shakira\'s dates use Ticketmaster and Live Nation dynamic pricing, so the cost of a section moves with demand rather than staying fixed. The same seat can show a different price at different times, which is why checking more than once can pay off.',
        ),
        6 => 
        array (
          'q' => 'Are floor tickets worth it for a Shakira show?',
          'a' => 'Floor tickets, usually $180-300, put you closest to the stage and the catwalk Shakira often uses to move into the crowd, which suits her high-energy, dance-driven sets. They cost more and tend to sell fastest, so they are worth it mainly if proximity matters most to you.',
        ),
        7 => 
        array (
          'q' => 'How much are Shakira stadium tickets compared to arena tickets?',
          'a' => 'Stadium tickets generally sit at the higher end of the band because demand and capacity are larger, with floor and premium areas pushing toward $400. Arena stops can open lower, sometimes from around $60, since the rooms are smaller and the section structure is more compact.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Shakira World Tour',
      'intro' => 
      array (
        0 => 'Shakira\'s tour dates tend to cluster around new album cycles, and when she goes out she plays a global itinerary that leans heavily on Latin America, North America and Europe, mixing arenas with stadiums in her biggest markets. Routing usually opens in one region before crossing continents, so the schedule for any given city is best confirmed against the official listings rather than assumed from past runs.',
        1 => 'Live dates and venues are added to this page as they are announced. Because Shakira draws strongly across both Spanish and English-speaking markets, her stadium nights in cities like Mexico City, Bogotá, Barcelona and Miami often sell quickly, and additional shows are sometimes added when initial dates clear fast.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Shakira touring?',
          'a' => 'Shakira tours around her album cycles, typically covering Latin America, North America and Europe across arenas and stadiums. Whether she is on the road in a given period changes by year, so check the live dates on this page for the current confirmed schedule and venues.',
        ),
        1 => 
        array (
          'q' => 'Where is Shakira touring?',
          'a' => 'Shakira\'s tours usually focus on Latin America, the United States, Canada and Europe, with the largest crowds in markets like Mexico, Colombia, Spain and the US. Exact cities vary by tour, and confirmed stops appear in the live listings above as they are announced.',
        ),
        2 => 
        array (
          'q' => 'How long is a Shakira concert?',
          'a' => 'A Shakira headline show generally lasts around an hour and three quarters to two hours, built around her hits and choreography-heavy staging. Run times shift depending on the setlist and production, and any support act will add to the overall length of the evening.',
        ),
        3 => 
        array (
          'q' => 'Does Shakira play stadiums or arenas?',
          'a' => 'Shakira plays both. In her biggest markets across Latin America and Europe she often books stadiums, while elsewhere she tends toward arenas. The venue type affects both capacity and pricing, so check each individual date to see whether it is a stadium or arena show.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Shakira\'s recent shows draw on a long catalogue that spans her early Spanish-language hits, her global crossover singles and her more recent collaborations, sung across both Spanish and English. A typical set leans on the songs below, weaving the dance-pop anthems together with a few slower moments and the reggaeton and Latin urban tracks from her later releases.',
      ),
      'songs' => 
      array (
        0 => 'She Wolf',
        1 => 'Whenever, Wherever',
        2 => 'Hips Don\'t Lie',
        3 => 'Ojos Así',
        4 => 'Inevitable',
        5 => 'Underneath Your Clothes',
        6 => 'Loca',
        7 => 'La Tortura',
        8 => 'Te Felicito',
        9 => 'TQG',
        10 => 'BZRP Music Sessions #53',
        11 => 'Chantaje',
        12 => 'Waka Waka (This Time for Africa)',
        13 => 'Antología',
        14 => 'Don\'t Bother',
        15 => 'Empire',
        16 => 'Ciega, Sordomuda',
        17 => 'Monotonía',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Shakira rotates songs in and out depending on the show, the market and the tour. Treat the list above as a guide to the staples she has been playing in recent shows rather than a fixed running order for any single concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Shakira play live?',
          'a' => 'Shakira\'s live shows typically feature staples such as Hips Don\'t Lie, Whenever, Wherever, She Wolf, Waka Waka and La Tortura, mixed with recent tracks like Te Felicito, TQG and her BZRP session. She sings across Spanish and English, and the exact selection shifts from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Shakira play Hips Don\'t Lie live?',
          'a' => 'Yes. Hips Don\'t Lie is one of Shakira\'s most reliable live moments and a regular fixture in recent sets, usually positioned as a high-energy peak. As always the running order can change between shows, but it is among the songs fans most expect to hear.',
        ),
        2 => 
        array (
          'q' => 'Does Shakira do an encore?',
          'a' => 'Shakira\'s recent shows tend to run as a continuous, choreography-led set rather than a formal stop-and-return encore, often closing on a major hit like Waka Waka. Structures differ by tour, so the ending can vary depending on the production and the night you attend.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Shakira play in concert?',
          'a' => 'A Shakira headline set usually contains somewhere around 20 songs, blending her older Spanish-language material with crossover hits and recent singles. The precise number changes by tour and night, since she rotates tracks in and out depending on the show and the market.',
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
      'range_low' => 75,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Diljit Dosanjh ticket prices typically run from around $75 for upper-tier seats to $400 or more for the floor and VIP packages, though the figure you actually pay depends on the city, the date and how early you book. His larger shows fill arenas and, increasingly, stadiums, so the gap between the cheapest seat and the front is wide and tends to widen further as the date approaches.',
        1 => 'Because most of his international dates run through Ticketmaster and Live Nation, the headline price moves with demand rather than sitting at a fixed face value. Punjabi music\'s global pull means popular markets such as Toronto, London and parts of the US can sell quickly, which pushes resale and dynamic prices above the original band, particularly for floor general admission.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / rear seating',
          'desc' => 'The most affordable way in, usually $75 to $130 in arenas. You are higher up and further back, but sightlines to the main stage are generally clear and the production carries across the room.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / side seating',
          'desc' => 'Mid-range seated tickets, roughly $130 to $220. These sit closer to the stage along the sides and lower levels, a common choice for fans who want a seat rather than standing on the floor.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing on the arena or stadium floor, typically $180 to $300 depending on demand. This is the closest standing access and the area that moves most under dynamic pricing for high-demand markets.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages that bundle premium seating or early entry with extras, commonly $300 to $400 or more. Inclusions vary by city and may add merchandise, dedicated entry or premium viewing areas.',
        ),
      ),
      'why' => 'Diljit Dosanjh\'s major tours are routed through Ticketmaster and Live Nation, which use dynamic pricing on most dates. That means a ticket\'s price is tied to live demand rather than a printed face value, so the same seat can cost noticeably more for a fast-selling market or weekend show than it did at on-sale. Strong demand across the Punjabi diaspora, especially in Canada, the UK and the US, can also push official allocations into resale, where prices sit above the original band. Booking early and comparing the official primary allocation against verified resale is usually the best way to gauge a fair number.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Diljit Dosanjh tickets?',
          'a' => 'Diljit Dosanjh tickets generally start around $75 for upper-tier seats and rise to $400 or more for floor and VIP packages. Lower-bowl seats tend to land in the $130 to $220 range. The exact price depends on the city, the date and how early you buy, since most of his shows use dynamic pricing.',
        ),
        1 => 
        array (
          'q' => 'Why are Diljit Dosanjh tickets so expensive?',
          'a' => 'High demand across the Punjabi diaspora is the main driver, particularly in Canada, the UK and the US, where shows can sell quickly. Most of his dates also use dynamic pricing, so prices climb with demand, and sold-out allocations push fans onto resale platforms where seats often list above face value.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Diljit Dosanjh live?',
          'a' => 'The cheapest seats are usually in the upper tiers, often around $75 to $130 in arenas. Buying as soon as tickets go on sale, before dynamic pricing responds to demand, and choosing a weekday date where available, tends to give you the lowest price.',
        ),
        3 => 
        array (
          'q' => 'Does Diljit Dosanjh use dynamic pricing?',
          'a' => 'Yes. Most of his international dates run through Ticketmaster and Live Nation, which apply dynamic pricing on the majority of shows. The listed price moves with demand rather than staying at a fixed face value, so popular markets and weekend dates often cost more than the on-sale starting band.',
        ),
        4 => 
        array (
          'q' => 'How much do Diljit Dosanjh VIP tickets cost?',
          'a' => 'Official VIP and hospitality packages typically run from about $300 to $400 or more, depending on the city and what is included. Packages usually bundle premium or early-entry seating with extras such as merchandise or dedicated entry, so they sit above standard seated tickets.',
        ),
        5 => 
        array (
          'q' => 'Are Diljit Dosanjh floor tickets worth it?',
          'a' => 'Floor or general-admission tickets, usually $180 to $300, put you closest to the stage and in the most energetic part of the crowd, which suits fans who want to stand and dance. If you prefer a guaranteed seat and a wider view of the production, a lower-bowl seat may offer better value.',
        ),
        6 => 
        array (
          'q' => 'Do Diljit Dosanjh ticket prices go down closer to the show?',
          'a' => 'Not usually for his in-demand markets. Because his shows often sell well, dynamic and resale prices tend to hold or rise as the date nears rather than fall. Occasional last-minute resale drops can happen for slower dates, but waiting is a gamble for popular cities.',
        ),
        7 => 
        array (
          'q' => 'Where can I buy Diljit Dosanjh tickets?',
          'a' => 'Diljit Dosanjh tickets are sold mainly through Ticketmaster and Live Nation for his international tours, plus verified resale marketplaces once primary allocations sell out. Buying from official primary sellers first gives you face-value pricing before resale markups apply.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Dil-Luminati Tour',
      'intro' => 
      array (
        0 => 'Diljit Dosanjh tour dates have expanded sharply in recent years, taking him from theatre and club shows to arenas and stadiums across North America, the UK, Australia and India. His Dil-Luminati run was a notable step up in scale, and his routing now regularly targets major diaspora markets such as Toronto, Vancouver, London, Birmingham and several US cities, alongside large home dates in India.',
        1 => 'As a touring act he tends to announce runs city by city rather than as one fixed annual cycle, so the live schedule changes between legs. New dates are added through Ticketmaster and Live Nation, and high-demand markets often go on sale first. The calendar on this page reflects his currently listed shows wherever they are confirmed.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Diljit Dosanjh touring in 2026?',
          'a' => 'Diljit Dosanjh tours regularly and continues to add international and Indian dates, so live shows in any given year are likely once a run is announced. Tour legs are confirmed city by city through Ticketmaster and Live Nation, so check the dates listed on this page for the latest confirmed schedule.',
        ),
        1 => 
        array (
          'q' => 'Where is Diljit Dosanjh performing on tour?',
          'a' => 'His tours focus on major Punjabi diaspora markets, including Canadian cities such as Toronto and Vancouver, UK dates in London and Birmingham, and several US cities, alongside large stadium and arena shows in India. The exact cities vary by tour leg, so the live list above shows currently confirmed stops.',
        ),
        2 => 
        array (
          'q' => 'How long is a Diljit Dosanjh concert?',
          'a' => 'A Diljit Dosanjh headline set typically runs roughly two hours, though length varies by venue and city. Larger arena and stadium shows tend to include a fuller production and a longer run of hits, while support acts and DJ segments can extend the overall evening.',
        ),
        3 => 
        array (
          'q' => 'Does Diljit Dosanjh play stadiums or arenas?',
          'a' => 'Both. His international shows are most often staged in arenas, while his biggest dates, particularly in India and selected diaspora markets, have moved into stadiums. The venue size shapes ticket pricing and availability, with stadium dates offering more capacity but strong demand for floor access.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Diljit Dosanjh\'s recent shows lean on his biggest Punjabi pop and bhangra singles, blending dancefloor anthems with a few film tracks and slower moments. The list below reflects songs that have featured regularly across his recent arena and stadium dates rather than the exact order of any single night.',
      ),
      'songs' => 
      array (
        0 => 'G.O.A.T.',
        1 => 'Lover',
        2 => 'Born to Shine',
        3 => '5 Taara',
        4 => 'Do You Know',
        5 => 'Proper Patola',
        6 => 'Patiala Peg',
        7 => 'Laembadgini',
        8 => 'Kylie + Kareena (KIKI)',
        9 => 'El Sueno',
        10 => 'Vibe',
        11 => 'Naina',
        12 => 'Khutti',
        13 => 'Peed',
        14 => 'Case',
        15 => 'Putt Jatt Da',
        16 => 'Ikk Kudi',
        17 => 'High End',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Diljit Dosanjh rotates songs, film tracks and crowd requests between cities, so the running order and exact selection differ from show to show.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Diljit Dosanjh play live?',
          'a' => 'Diljit Dosanjh\'s live shows feature his biggest singles such as G.O.A.T., Lover, Born to Shine, 5 Taara, Do You Know and Proper Patola, alongside dancefloor staples like Patiala Peg and Laembadgini. He mixes upbeat bhangra-pop anthems with the occasional film track and slower number.',
        ),
        1 => 
        array (
          'q' => 'Does Diljit Dosanjh do an encore?',
          'a' => 'Diljit Dosanjh tends to run a continuous high-energy set rather than a formal encore break, often closing on one of his biggest crowd anthems. The structure can vary by city and venue, so some nights feel like a single sustained run while others build to a clear finale.',
        ),
        2 => 
        array (
          'q' => 'What is the typical Diljit Dosanjh setlist order?',
          'a' => 'He usually opens with an up-tempo crowd favourite, works through a run of hit singles and dance tracks, and saves several of his biggest anthems like G.O.A.T. and Lover for the closing stretch. The exact order changes from night to night, so no two shows match precisely.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Diljit Dosanjh perform?',
          'a' => 'A Diljit Dosanjh headline show typically includes around 15 to 20 songs across roughly two hours, though the count varies by city and venue size. Larger arena and stadium dates often feature a longer set with more of his hits and extended dance segments.',
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
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Bad Bunny tickets typically run from around $90 for upper-level seats to $600 or more for floor positions and VIP packages, though the figure you actually pay depends on the city, the date and how early you book. Most of his shows are stadium and large-arena dates sold through Ticketmaster, which uses dynamic pricing, so the headline number tracks demand rather than holding at a fixed face value.',
        1 => 'Because Benito plays some of the biggest venues in Latin music, the gap between the cheapest seat and a floor ticket is wide. Upper decks anchor the lower end of the band, while floor and pit access near the stage sit at the top, with VIP bundles adding premium positioning and merchandise on top of that.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper Level / Grada',
          'desc' => 'Higher tiers of the stadium or arena, usually $90-$170. The most affordable way in, and on the larger productions the staging is built to carry sightlines to the back rows.',
        ),
        1 => 
        array (
          'name' => 'Lower Bowl / Reserved Seating',
          'desc' => 'Mid and lower seated sections with a closer angle on the stage, typically $180-$320. A solid balance of view and price for stadium dates.',
        ),
        2 => 
        array (
          'name' => 'Floor / Pista',
          'desc' => 'Standing-room floor closest to the stage, generally $300-$550. On many dates this is general admission, so arriving early matters as much as the ticket itself.',
        ),
        3 => 
        array (
          'name' => 'VIP Packages',
          'desc' => 'Premium seating or early floor entry bundled with merchandise and dedicated entry, often $500-$600 and up. Positioning and perks rather than meet-and-greets on most legs.',
        ),
      ),
      'why' => 'Bad Bunny\'s on-sales use dynamic pricing, which means Ticketmaster moves the price in real time based on how quickly a section is selling. A stadium date that opens around $120 can climb well past $250 within minutes in a high-demand market, then settle as resale supply builds. Prices tend to be lowest right at on-sale and for weeknight shows, and highest for weekend dates and his hometown Puerto Rico residency runs, which sell out fastest.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Bad Bunny tickets?',
          'a' => 'Most Bad Bunny tickets fall between about $90 and $600. Upper-level seats start near $90, the lower bowl runs $180-$320, and floor plus VIP sit at the top of the band. Because his shows use dynamic pricing, the live figure shifts by city and date, so the cards on this page show the current \'from\' price for each show.',
        ),
        1 => 
        array (
          'q' => 'Why are Bad Bunny tickets so expensive?',
          'a' => 'His stadium and residency dates use dynamic pricing, so the price rises while a section sells quickly, and Benito is one of the highest-demand acts in Latin music. The face value is not fixed; it reflects real-time demand. Weeknight and newly added dates usually come in cheaper than the first weekend shows.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Bad Bunny?',
          'a' => 'Buy upper-level or grada seats at on-sale for a weeknight date, and compare cities on the same tour, since a midweek show in a smaller market often costs far less than a weekend date in a major one. Live prices for every date are listed on this page.',
        ),
        3 => 
        array (
          'q' => 'How much are floor tickets for Bad Bunny?',
          'a' => 'Floor or pista tickets generally run $300-$550 depending on the city and venue size. On many dates the floor is general admission, so the ticket gets you onto the floor and your spot depends on how early you line up.',
        ),
        4 => 
        array (
          'q' => 'Do Bad Bunny ticket prices drop closer to the show?',
          'a' => 'Sometimes. Resale prices can soften in the final week for a date that has not sold out, but his high-demand stadium and Puerto Rico shows tend to hold firm or climb. There is no guaranteed last-minute discount, so waiting is a gamble against missing out.',
        ),
        5 => 
        array (
          'q' => 'Are VIP tickets worth it for Bad Bunny?',
          'a' => 'VIP packages, often $500 and up, bundle premium seating or early floor entry with merchandise and dedicated entry rather than meet-and-greets on most legs. They are worth it mainly if guaranteed premium positioning or early access onto the floor matters to you.',
        ),
        6 => 
        array (
          'q' => 'When do Bad Bunny tickets go on sale?',
          'a' => 'Tickets typically release a few months ahead of each leg, with artist and Ticketmaster presales running a day or two before the general on-sale. New dates appear on this page as soon as they go live.',
        ),
        7 => 
        array (
          'q' => 'Are these official Bad Bunny tickets?',
          'a' => 'Yes. The prices and dates here come from official ticketing partners, and checkout completes on the partner\'s secure site with instant e-ticket delivery. We never resell or mark up tickets.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Bad Bunny World Tour',
      'intro' => 
      array (
        0 => 'Bad Bunny tours at stadium and large-arena scale, moving between major Latin and global markets and, when announced, an extended residency run in his native Puerto Rico. Every confirmed date with a live ticket price is listed below, and new shows are added automatically the moment they go on sale, so this is the fastest place to catch a date near you.',
        1 => 'His productions are built around a large central stage with extensive video and lighting design, which is why even upper-level seats keep a usable view across a stadium. If your dates are flexible, midweek shows are routinely cheaper than the opening weekend of each city\'s run.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Bad Bunny touring in 2026?',
          'a' => 'Yes, Bad Bunny continues to tour at stadium and arena scale across the Americas, Europe and beyond, with residency runs in Puerto Rico when announced. Every confirmed date on sale appears on this page with live prices.',
        ),
        1 => 
        array (
          'q' => 'What cities is Bad Bunny playing?',
          'a' => 'His tours cover major stadium and arena markets across multiple countries, alongside his Puerto Rico residency dates when scheduled. The cards above show exactly which cities have tickets on sale right now, each linking to live seat availability.',
        ),
        2 => 
        array (
          'q' => 'How long is a Bad Bunny concert?',
          'a' => 'A Bad Bunny headline set typically runs around 2 to 2.5 hours, reflecting his deep catalogue across multiple albums. Doors usually open roughly 90 minutes to two hours before the main set on stadium dates.',
        ),
        3 => 
        array (
          'q' => 'Who is opening for Bad Bunny?',
          'a' => 'Support acts and guest appearances vary by leg and city, and Benito is known for bringing out surprise collaborators. Check the individual event page for a given date to see any confirmed opener and set times.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'A Bad Bunny setlist pulls across his full run of albums, from the trap and reggaeton that built him to the salsa, dembow and Puerto Rican rhythms of his recent work, so a typical night swings between hard club bangers and slower, melodic moments. The list below reflects what he has been playing on recent dates; the exact running order shifts night to night.',
      ),
      'songs' => 
      array (
        0 => 'Tití Me Preguntó',
        1 => 'Yonaguni',
        2 => 'Moscow Mule',
        3 => 'Me Porto Bonito',
        4 => 'Efecto',
        5 => 'Un Verano Sin Ti',
        6 => 'Dakiti',
        7 => 'La Cancion',
        8 => 'Callaita',
        9 => 'Neverita',
        10 => 'Ojitos Lindos',
        11 => 'Vete',
        12 => 'Si Veo a Tu Mamá',
        13 => 'Después de la Playa',
        14 => 'Un Preview',
        15 => 'Yo Perreo Sola',
        16 => 'Monaco',
        17 => 'Where She Goes',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary every night, and Bad Bunny often rotates album cuts, salsa and dembow sections and surprise guests, so treat this as a guide rather than a guarantee.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Bad Bunny play live?',
          'a' => 'Recent setlists mix big hits like Titi Me Pregunto, Me Porto Bonito and Yonaguni with album tracks from Un Verano Sin Ti and his newer Puerto Rico-rooted material, plus collaborations such as Dakiti. The exact order changes night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Bad Bunny play Titi Me Pregunto live?',
          'a' => 'Yes, Titi Me Pregunto is one of the most reliable fixtures of his set, usually landing in a high-energy stretch and drawing one of the loudest singalongs of the night.',
        ),
        2 => 
        array (
          'q' => 'How many songs does Bad Bunny play?',
          'a' => 'A typical set runs around 30 or more songs across roughly two to two-and-a-half hours, though many are medley-style or shortened to move through his large catalogue. The count varies by tour and venue.',
        ),
        3 => 
        array (
          'q' => 'Does Bad Bunny do an encore?',
          'a' => 'His shows often run as a continuous, high-tempo production that closes on his biggest singles rather than a separate encore, though this can vary by night and by tour.',
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
      'range_low' => 90,
      'range_high' => 900,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Beyoncé tickets generally run from around $90 for upper-bowl seats to $900 or more for floor positions and VIP packages, though the figure you actually pay shifts with the city, the night and how soon you book. Most of her recent runs have been stadium tours, and stadium scale pushes both the floor in cheap seats and the ceiling on premium ones higher than a typical arena show.',
        1 => 'Because Beyoncé works at stadium size, pricing spreads across a wide range rather than settling at a single face value. The headline number you see quoted is usually a starting point for the least expensive seats, while front-of-stage and packaged tickets sit well above it. Resale can move either direction depending on the market and how a given date is selling.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level',
          'desc' => 'Seats high in the stands or behind the stage, the most affordable way in. Expect roughly $90 to $180 depending on the city and date.',
        ),
        1 => 
        array (
          'name' => 'Lower level / side stage',
          'desc' => 'Side and corner views closer to the action with a clear sightline. These typically land around $180 to $350.',
        ),
        2 => 
        array (
          'name' => 'Floor and lower bowl center',
          'desc' => 'Prime central positions near the stage where demand is heaviest. Usually in the $350 to $650 band, more for the best rows.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Premium seating bundled with early entry, lounge access or merchandise. These commonly run $650 to $900 or beyond.',
        ),
      ),
      'why' => 'Beyoncé\'s stadium tours are promoted through Ticketmaster and Live Nation, which means dynamic pricing applies to a large share of seats. Rather than holding a fixed face value, prices move with real-time demand, so a high-demand night or a sought-after section can climb above the opening figure. The widest swings tend to hit floor and premium inventory, while upper-level seats stay closer to their starting price. Buying earlier in a sale generally gives you a better shot at the lower end of each band.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Beyoncé tickets?',
          'a' => 'Beyoncé tickets typically range from about $90 for upper-level stadium seats to $900 or more for floor positions and VIP packages. The price you pay depends on the venue, the city and how early you buy, since her stadium shows use dynamic pricing that moves with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Beyoncé tickets so expensive?',
          'a' => 'Beyoncé plays stadiums rather than arenas, so even modest seats start higher than a smaller show, and her tours run on dynamic pricing that lifts in-demand sections above face value. Production scale, limited dates per city and strong resale demand all push the premium and floor seats upward.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Beyoncé live?',
          'a' => 'The cheapest seats are usually upper-level or behind-stage positions, often starting around $90 to $180. Buying during the initial on-sale rather than waiting, and considering weeknight dates over weekends, generally gives you the best chance at the lower end of the range.',
        ),
        3 => 
        array (
          'q' => 'How much are Beyoncé floor tickets?',
          'a' => 'Floor and lower-bowl center tickets for Beyoncé generally run from about $350 to $650, with the front rows and most central positions reaching higher. Because these are the most in-demand seats, dynamic pricing tends to push them well above the starting figure on popular nights.',
        ),
        4 => 
        array (
          'q' => 'Do Beyoncé ticket prices change?',
          'a' => 'Yes. Most of Beyoncé\'s stadium seats are sold under Ticketmaster\'s dynamic pricing, so the listed price rises and falls with demand rather than staying fixed. Upper-level seats move least, while floor and premium sections can shift noticeably as a date sells through.',
        ),
        5 => 
        array (
          'q' => 'Are Beyoncé VIP packages worth it?',
          'a' => 'Beyoncé VIP packages, usually $650 to $900 or more, bundle premium seating with extras such as early entry, lounge access or exclusive merchandise. They are worth it if you value the location and add-ons, but standard floor or lower-level seats are the cheaper route if you only want a strong view.',
        ),
        6 => 
        array (
          'q' => 'How much do Beyoncé tickets cost on resale?',
          'a' => 'Resale prices for Beyoncé vary widely by market and date, sometimes sitting below the original price for slower nights and well above it for sold-out shows. Because demand drives the figure, there is no fixed resale rate, so comparing listings close to the event often gives the clearest picture.',
        ),
        7 => 
        array (
          'q' => 'When is the best time to buy Beyoncé tickets for a lower price?',
          'a' => 'Buying during the initial on-sale generally secures the lower end of each price band, before dynamic pricing responds to demand. For high-demand dates prices tend to hold or rise, while quieter nights can occasionally soften closer to the show, though waiting always carries the risk of selling out.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Beyoncé Live',
      'intro' => 
      array (
        0 => 'Beyoncé\'s tour dates tend to arrive in concentrated runs built around a new album rather than as constant year-round touring. When a tour is announced it usually spans major stadiums across North America and Europe, with select dates added by demand. Because she plays large venues, each city often gets only one or two nights, which is part of why on-sales move quickly.',
        1 => 'Her live shows are known for their scale, with elaborate staging, choreography and visual production designed for stadium audiences. Between tours she frequently appears at one-off events and festival headline slots, so checking for confirmed dates is the surest way to know where and when she is performing rather than relying on rumoured schedules.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Beyoncé touring in 2026?',
          'a' => 'Beyoncé tours in concentrated runs tied to her album cycles rather than every year, so whether she is on the road in any given year depends on her current release. Check the live dates listed on this page for confirmed shows, as those reflect officially announced performances rather than speculation.',
        ),
        1 => 
        array (
          'q' => 'Where does Beyoncé usually perform on tour?',
          'a' => 'Beyoncé typically performs in major stadiums across North America and Europe, hitting large markets such as London, New York, Los Angeles and Paris. Her venue choice reflects the scale of her production, and most cities receive only one or two dates per tour.',
        ),
        2 => 
        array (
          'q' => 'How long is a Beyoncé concert?',
          'a' => 'A Beyoncé headline show generally runs around two to two and a half hours, reflecting a long setlist and elaborate staging. There is usually no separate support act of note, as the production is built around her full performance from start to finish.',
        ),
        3 => 
        array (
          'q' => 'How often does Beyoncé go on tour?',
          'a' => 'Beyoncé tours roughly every few years, generally aligning a run with the release of a new album rather than touring continuously. Between major tours she takes on selective one-off shows and festival headline appearances, so gaps between full tours are normal.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Beyoncé\'s recent shows draw across her full catalogue, blending solo signatures with deeper album cuts and reworked arrangements built for stadium production. The selection below reflects songs that have featured regularly in her live sets, giving a sense of what tends to appear rather than a fixed running order. Expect medleys, interpolations and extended sequences that stitch several tracks together.',
      ),
      'songs' => 
      array (
        0 => 'Crazy in Love',
        1 => 'Single Ladies (Put a Ring on It)',
        2 => 'Halo',
        3 => 'Love on Top',
        4 => 'Run the World (Girls)',
        5 => 'Formation',
        6 => 'Drunk in Love',
        7 => 'Partition',
        8 => 'Flawless',
        9 => 'Cuff It',
        10 => 'Break My Soul',
        11 => 'Alien Superstar',
        12 => 'Texas Hold \'Em',
        13 => 'Daddy Lessons',
        14 => 'If I Were a Boy',
        15 => 'Irreplaceable',
        16 => 'Naughty Girl',
        17 => 'Freedom',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Beyoncé frequently reshapes her shows with medleys, interpolations and song swaps, so the exact selection and order differ from one date to the next.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Beyoncé play live?',
          'a' => 'Beyoncé\'s live sets pull from across her career, mixing signatures like Crazy in Love, Single Ladies and Halo with newer material such as Cuff It, Break My Soul and Texas Hold \'Em. The exact lineup changes by night, and many songs are performed as medleys or extended sequences.',
        ),
        1 => 
        array (
          'q' => 'Does Beyoncé do an encore?',
          'a' => 'Beyoncé\'s shows often run as a continuous, tightly choreographed production rather than a traditional stop-and-return encore, closing on a major number instead. The final stretch typically builds to one of her biggest songs, so the show ends on a high without a separate encore break.',
        ),
        2 => 
        array (
          'q' => 'How many songs does Beyoncé perform in concert?',
          'a' => 'A Beyoncé concert usually features upwards of 30 songs, though many are delivered as medleys, interludes or partial interpolations rather than full standalone performances. Combined with staging and choreography, this fills a set of roughly two to two and a half hours.',
        ),
        3 => 
        array (
          'q' => 'Does Beyoncé play her old songs live?',
          'a' => 'Yes. Beyoncé regularly revisits early hits such as Crazy in Love, Naughty Girl and Irreplaceable alongside her newer work, often in reworked arrangements. Which classics appear varies by tour and night, as she balances catalogue favourites against material from her latest album.',
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
      'range_low' => 70,
      'range_high' => 500,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'The Weeknd tickets typically run from around $70 for upper-bowl seats to $500 or more for floor positions and VIP packages, though what you actually pay depends on the city, the date and how far in advance you book. His recent runs lean toward stadiums and large arenas, where the cheapest seats sit high above the stage and the priciest place you close to the in-the-round or runway staging.',
        1 => 'Because most of these shows are routed through Ticketmaster and Live Nation, the face value you see is rarely fixed. Dynamic pricing moves the headline number with demand, so a Friday night in a major market lists higher than a midweek date elsewhere. The bands above reflect primary sale prices rather than resale, which can climb well beyond them for sold-out nights.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper bowl / rear stadium',
          'desc' => 'The most affordable seats, usually $70-130, set high in the upper tiers or behind the stage. You trade proximity for price, but Abel\'s stadium production leans on a large stage set and screens that read clearly from distance.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / side floor',
          'desc' => 'Mid-range seating around $130-250, covering the lower bowl and side-floor sections. These give a balanced view of the staging and lighting without the premium attached to the front sections.',
        ),
        2 => 
        array (
          'name' => 'Front floor / GA pit',
          'desc' => 'Prime standing or front-floor positions, generally $250-400, putting you near the runway and main stage. Demand here is heaviest, so dynamic pricing pushes these highest on popular dates.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP and hospitality bundles, often $350-500 or more, combining premium seating with early entry, merchandise and lounge access. Contents vary by tour and venue, so check what each package actually includes before buying.',
        ),
      ),
      'why' => 'Most of The Weeknd\'s major tours sell through Ticketmaster and Live Nation, which means dynamic pricing is the norm rather than the exception. Instead of a single fixed face value, prices shift in real time with demand, so a high-demand date in a big market can list noticeably higher than a quieter midweek show. This is why two seats in the same section can carry different prices, and why booking early, before demand builds, usually secures the lower end of each band.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are The Weeknd tickets?',
          'a' => 'The Weeknd tickets generally start around $70 for upper-tier stadium seats and rise to $500 or more for front-floor spots and VIP packages. The exact price depends on the venue size, the city and how early you book, since most dates use dynamic pricing that moves with demand.',
        ),
        1 => 
        array (
          'q' => 'Why do The Weeknd ticket prices change so much?',
          'a' => 'Prices change because his shows use Ticketmaster\'s dynamic pricing, which adjusts the face value in real time based on demand. A sold-out night in a major city will list higher than a midweek date in a smaller market, and the same section can carry different prices from one day to the next.',
        ),
        2 => 
        array (
          'q' => 'What are the cheapest The Weeknd tickets?',
          'a' => 'The cheapest tickets are usually upper-bowl or rear-stage seats, typically from around $70-130 at the primary sale. These sit high above the stage, but The Weeknd\'s stadium productions use large screens and staging that remain easy to follow from the upper tiers.',
        ),
        3 => 
        array (
          'q' => 'How much do The Weeknd VIP tickets cost?',
          'a' => 'Official VIP packages for The Weeknd commonly run from about $350 to $500 or more, depending on the tour and market. They typically bundle premium seating with perks such as early entry, exclusive merchandise or lounge access, so the price reflects both the seat and the extras included.',
        ),
        4 => 
        array (
          'q' => 'Are The Weeknd tickets worth it?',
          'a' => 'That depends on your budget and how close you want to be. The Weeknd\'s stadium shows are known for elaborate staging, strong lighting and a deep catalogue of hits, which many fans feel justifies the cost. Upper-tier seats offer the best value, while floor and VIP positions carry a clear premium.',
        ),
        5 => 
        array (
          'q' => 'Do The Weeknd ticket prices drop closer to the show?',
          'a' => 'Sometimes, but it is not guaranteed. For shows that do not sell out, primary prices can ease in the final days, and resale can soften. For high-demand dates, prices more often hold or climb. Booking early usually secures the lower end of the band rather than waiting for a drop.',
        ),
        6 => 
        array (
          'q' => 'Why are resale The Weeknd tickets more expensive?',
          'a' => 'Resale prices can exceed face value because sellers set their own asking prices on the secondary market, and popular dates sell out quickly. When a show is in heavy demand, resale listings for The Weeknd can run well above the original primary price, especially for floor and front sections.',
        ),
        7 => 
        array (
          'q' => 'Is there a booking fee on The Weeknd tickets?',
          'a' => 'Yes, most The Weeknd tickets carry service and booking fees added at checkout on top of the face value. These vary by seller and venue, so the final total is usually higher than the listed price. Always check the full breakdown before paying so there are no surprises at the last step.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'The Weeknd World Tour',
      'intro' => 
      array (
        0 => 'The Weeknd has become a reliable stadium and arena headliner, with tours that span North America, Europe, Latin America and beyond. His live runs tend to support a major album cycle, opening in large markets before extending into additional legs as demand grows. Routing usually favours major metropolitan areas first, so smaller cities often see fewer dates and tickets that move faster once a tour is announced.',
        1 => 'Tour dates are typically confirmed alongside an album release or its anniversary, then revealed market by market with on-sale dates staggered over a few weeks. Pre-sales through fan registration, the artist\'s mailing list and partner cards usually open before the general sale. Because his shows lean toward stadiums, capacities are large, but high-profile dates still sell quickly.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is The Weeknd touring in 2026?',
          'a' => 'Touring activity depends on his current album and release cycle. The Weeknd typically tours in support of a major project, with dates announced market by market. Check his official site and verified ticket sellers for the latest confirmed schedule, since live dates are added and updated throughout a tour cycle.',
        ),
        1 => 
        array (
          'q' => 'How do I get presale access for The Weeknd?',
          'a' => 'Presale access for The Weeknd usually comes through fan registration, his official mailing list, or partner promotions such as card-holder presales. Signing up in advance and registering early gives you a presale code before the general on-sale, which matters most for high-demand stadium dates that sell quickly.',
        ),
        2 => 
        array (
          'q' => 'Does The Weeknd play stadiums or arenas?',
          'a' => 'In recent years The Weeknd has mainly headlined stadiums on his largest tours, with arena dates appearing on certain legs or in smaller markets. The venue type affects both capacity and pricing, so stadium shows tend to have more seats but a wider price spread from upper tiers to the floor.',
        ),
        3 => 
        array (
          'q' => 'How long is a The Weeknd concert?',
          'a' => 'A The Weeknd headline show typically runs around 90 minutes to two hours, depending on the tour and setlist. His stadium productions feature extended staging and visuals, and any support act will add to the overall evening, so plan to arrive well before the headline set begins.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'The Weeknd\'s recent shows draw heavily from across his catalogue, mixing the synth-pop singles that dominated the charts with the darker, atmospheric tracks that built his early reputation. A typical set moves through career-spanning highlights, often grouped into themed segments backed by the large-scale staging his stadium tours are known for. The list below reflects songs that have featured regularly in recent setlists rather than any single fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'Blinding Lights',
        1 => 'Starboy',
        2 => 'Save Your Tears',
        3 => 'Can\'t Feel My Face',
        4 => 'The Hills',
        5 => 'Often',
        6 => 'Heartless',
        7 => 'After Hours',
        8 => 'I Feel It Coming',
        9 => 'Die For You',
        10 => 'Earned It',
        11 => 'In Your Eyes',
        12 => 'Take My Breath',
        13 => 'Sacrifice',
        14 => 'Out of Time',
        15 => 'Less Than Zero',
        16 => 'Pray for Me',
        17 => 'Call Out My Name',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and The Weeknd frequently rotates songs, intros and segment order between cities, so the exact selection and sequence on any given date will differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does The Weeknd play live?',
          'a' => 'The Weeknd\'s live sets center on his biggest hits, including Blinding Lights, Starboy, Save Your Tears, Can\'t Feel My Face and The Hills, alongside deeper cuts such as Often and Call Out My Name. The exact selection changes between shows, but the major singles tend to appear on most nights of a tour.',
        ),
        1 => 
        array (
          'q' => 'Does The Weeknd do an encore?',
          'a' => 'The Weeknd often runs his set as a continuous, theatrical performance rather than a traditional stop-and-return encore, frequently closing on a marquee song like Blinding Lights. Formats vary by tour, so some runs build to a clear finale while others keep the show moving without a separate encore section.',
        ),
        2 => 
        array (
          'q' => 'How many songs does The Weeknd perform?',
          'a' => 'A The Weeknd headline set usually features somewhere around 20 or more songs, often delivered in themed segments across a 90-minute to two-hour show. The count shifts by tour and venue, and some hits appear as shortened versions or medleys within the staging rather than full-length performances.',
        ),
        3 => 
        array (
          'q' => 'Does The Weeknd open with Blinding Lights?',
          'a' => 'Blinding Lights, his biggest single, is more often used as a high point or closer than an opener, though placement varies by tour. The Weeknd rotates his opening number and overall running order between dates, so the song\'s position in the set can differ from one city to the next.',
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
        0 => 'Ed Sheeran tickets typically run from around $60 for upper-tier and back-of-stadium seats to roughly $400 for premium pitch standing and VIP packages, though what you actually pay depends on the city, the night and how early you book. Sheeran plays large stadiums on his bigger tours, often with an in-the-round stage, so layout and sightlines matter as much as tier when you choose where to sit.',
        1 => 'Most of Sheeran\'s tours sell through Ticketmaster or Live Nation using dynamic pricing, which means the headline figure moves with demand rather than holding a fixed face value. General admission pitch tickets tend to clear quickly and can carry a premium, while upper-level seats around the bowl usually sit at the lower end of the band and offer the best value for the music.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / back stadium',
          'desc' => 'Seated places high in the bowl or furthest from the stage, generally the cheapest option at roughly $60 to $110. Fine for the in-the-round setup, where Sheeran rotates to face every side of the stadium.',
        ),
        1 => 
        array (
          'name' => 'Lower tier seated',
          'desc' => 'Seated areas closer to pitch level around the sides and ends, usually about $110 to $190. A comfortable middle choice with a clear, steady view of the central stage.',
        ),
        2 => 
        array (
          'name' => 'Pitch / general admission standing',
          'desc' => 'Standing on the floor near the stage, typically $160 to $260. The most atmospheric spot, with standing-only access and prices that rise as the show approaches.',
        ),
        3 => 
        array (
          'name' => 'Early entry / front pitch',
          'desc' => 'Priority or front-standing options where offered, around $220 to $320, giving earlier access to the floor and a closer position to the loop pedal rig.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official hospitality bundles roughly $300 to $400 or more, usually combining a premium location with early entry, a lounge or merchandise, depending on the city and promoter.',
        ),
      ),
      'why' => 'Ed Sheeran\'s stadium tours run on dynamic pricing through Ticketmaster and Live Nation, so prices shift with demand rather than staying fixed at face value. Popular nights and pitch standing can climb well above the opening figures, while upper-tier seats often stay near the bottom of the band. Booking early, before demand pushes the algorithm upward, is generally the most reliable way to pay less, and prices vary city to city.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Ed Sheeran tickets?',
          'a' => 'Ed Sheeran tickets generally range from about $60 for upper-tier stadium seats to around $400 for premium pitch and VIP packages. Because he plays large stadiums, the lower-priced seats are plentiful, while floor standing close to his in-the-round stage carries the highest demand and price.',
        ),
        1 => 
        array (
          'q' => 'Why are Ed Sheeran tickets so expensive?',
          'a' => 'Ed Sheeran tickets reach high prices mainly because his stadium tours use dynamic pricing, which lets face values rise with demand. Pitch standing and prime seats near the central stage attract the most buyers, pushing those tiers up, though upper-bowl seats remain comparatively affordable.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Ed Sheeran live?',
          'a' => 'The cheapest way to see Ed Sheeran is usually an upper-tier seat in the stadium bowl, often around $60 to $110. Because his stage sits in the round and rotates, even higher seats get a full view of the performance, making them strong value.',
        ),
        3 => 
        array (
          'q' => 'Are Ed Sheeran VIP packages worth it?',
          'a' => 'Ed Sheeran VIP packages, usually $300 to $400 or more, bundle a premium location with perks such as early entry, lounge access or merchandise. They suit fans who want a guaranteed good spot and extras, but the music itself reads clearly from far cheaper seats.',
        ),
        4 => 
        array (
          'q' => 'Does Ed Sheeran use dynamic pricing?',
          'a' => 'Yes, most of Ed Sheeran\'s tours sell through Ticketmaster or Live Nation with dynamic pricing enabled. This means ticket prices move up or down with demand, so the same seat can cost different amounts depending on when you buy and how brisk sales are.',
        ),
        5 => 
        array (
          'q' => 'How much is pitch standing for Ed Sheeran?',
          'a' => 'Pitch or general admission standing for Ed Sheeran typically costs around $160 to $260, sometimes more for early-entry options. These floor tickets put you nearest his in-the-round stage and tend to be the first to sell, which keeps their prices toward the top of the band.',
        ),
        6 => 
        array (
          'q' => 'Do Ed Sheeran ticket prices go down closer to the show?',
          'a' => 'Ed Sheeran ticket prices can occasionally soften close to a show if seats remain unsold, but with dynamic pricing they more often rise as demand builds. Booking early is generally the safer way to secure a lower price rather than waiting for a last-minute drop.',
        ),
        7 => 
        array (
          'q' => 'Are there resale tickets for Ed Sheeran?',
          'a' => 'Resale tickets for Ed Sheeran are common and often appear above face value, especially for pitch standing. Where official resale platforms operate, they cap prices and reduce fraud risk, so verified resale is generally safer than informal listings for sold-out dates.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Ed Sheeran Live',
      'intro' => 
      array (
        0 => 'Ed Sheeran tour dates usually centre on large stadiums, with long runs that group multiple nights in major cities and stretch across continents over a year or more. He has built a reputation for high-volume touring, often playing to some of the biggest crowds in live music, performing solo with a guitar and loop pedal rather than a full band on stage.',
        1 => 'When a new Ed Sheeran tour is announced, dates tend to be released in batches by region, frequently with presales for fan-club members and cardholders ahead of general on-sale. His shows commonly use an in-the-round stage that rotates through the night, so most seats in a stadium get a fair view at some point in the set.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Ed Sheeran touring in 2026?',
          'a' => 'Ed Sheeran tours regularly and tends to keep a busy live schedule, so new 2026 dates are likely whenever an album cycle supports them. Confirmed dates, cities and on-sale times are announced through his official channels and Ticketmaster, so check those for the current schedule.',
        ),
        1 => 
        array (
          'q' => 'Where does Ed Sheeran usually perform?',
          'a' => 'Ed Sheeran usually performs in large stadiums, often the biggest venues a city offers, and frequently books several consecutive nights in a single location. His in-the-round stage sits centrally rather than at one end, which shapes how tickets and sightlines are arranged across the bowl.',
        ),
        2 => 
        array (
          'q' => 'How long is an Ed Sheeran concert?',
          'a' => 'An Ed Sheeran concert typically lasts around two hours, performed solo with a guitar and loop pedal building layered live arrangements. Support acts beforehand can add another hour or so, so plan for a full evening depending on the venue and that night\'s lineup.',
        ),
        3 => 
        array (
          'q' => 'How do I get presale tickets for Ed Sheeran?',
          'a' => 'Presale access for Ed Sheeran usually comes through his official fan club, newsletter sign-ups or partnered cardholder presales ahead of the general on-sale. Registering early and being ready when the presale window opens gives the best chance at pitch standing and prime seats before wider demand hits.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Ed Sheeran\'s recent live shows draw on a deep run of hits stretched across his albums, performed largely solo with a guitar and loop pedal that lets him build full arrangements in real time. A typical setlist mixes early singalongs with later chart staples, and he often weaves short covers or mash-ups into songs, so the exact running order shifts from night to night.',
      ),
      'songs' => 
      array (
        0 => 'Castle on the Hill',
        1 => 'Shivers',
        2 => 'The A Team',
        3 => 'Don\'t',
        4 => 'Shape of You',
        5 => 'Galway Girl',
        6 => 'Photograph',
        7 => 'Perfect',
        8 => 'Thinking Out Loud',
        9 => 'Bloodstream',
        10 => 'Eyes Closed',
        11 => 'Bad Habits',
        12 => 'Sing',
        13 => 'Lego House',
        14 => 'I\'m a Mess',
        15 => 'Give Me Love',
        16 => 'Overpass Graffiti',
        17 => 'You Need Me, I Don\'t Need You',
      ),
      'encore' => 
      array (
        0 => 'Perfect',
        1 => 'Bad Habits',
        2 => 'Shape of You',
      ),
      'note' => 'Setlists vary night to night, and Ed Sheeran often adjusts songs, covers and mash-ups between shows, so the order above reflects recent staples rather than a fixed list for any single concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Ed Sheeran play live?',
          'a' => 'Ed Sheeran\'s live shows usually feature hits such as Shape of You, Perfect, Castle on the Hill, Shivers, Galway Girl and Thinking Out Loud, alongside earlier tracks like The A Team. He performs solo with a loop pedal, so arrangements are built live and vary across the tour.',
        ),
        1 => 
        array (
          'q' => 'Does Ed Sheeran do an encore?',
          'a' => 'Yes, Ed Sheeran usually closes with an encore, often saving his biggest singalongs such as Perfect, Bad Habits or Shape of You for the final stretch. The exact encore changes between shows, so the closing songs are not fixed from one night to the next.',
        ),
        2 => 
        array (
          'q' => 'How does Ed Sheeran perform without a band?',
          'a' => 'Ed Sheeran performs alone using a guitar and a loop pedal, recording short phrases live and layering them to create the sound of a full arrangement. This setup means each song is built in front of the audience, and small variations naturally appear from show to show.',
        ),
        3 => 
        array (
          'q' => 'Does Ed Sheeran play old songs like The A Team?',
          'a' => 'Yes, Ed Sheeran regularly includes early material such as The A Team, Lego House and Give Me Love alongside his recent hits. He balances debut-era favourites with newer singles, though which older songs appear can change between dates as he rotates his setlist.',
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
      'range_low' => 60,
      'range_high' => 500,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Karol G tickets generally run from around $60 for upper-level seats to $400 or more for the floor, lower-bowl and VIP packages, though what you actually pay depends on the city, the date and how early you book. Her recent run has moved from arenas into stadiums in many markets, and the larger the venue the wider that price band tends to stretch, with premium positions near the stage sitting well above the entry-level figure.',
        1 => 'On the resale market, prices for the most in-demand nights, particularly opening weekends and Latin-strong cities such as Miami or Los Angeles, can climb above face value. Buying early in the on-sale, before the cheaper inventory clears, is usually the most reliable way to stay near the lower end of the range rather than chasing seats once a show is close to selling out.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / general admission',
          'desc' => 'The cheapest way in, usually around $60 to $130. Upper-bowl seats in stadiums and the higher arena tiers fall here, along with standing general admission in smaller venues. The view is distant but the production and sound carry well across a Karol G show.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / mid-tier seating',
          'desc' => 'Reserved seats closer to the stage, roughly $130 to $250. These give a clear sightline without the floor premium and are the sweet spot for many fans wanting a balance of price and proximity.',
        ),
        2 => 
        array (
          'name' => 'Floor / field',
          'desc' => 'Standing or seated floor positions on the arena floor or stadium field, typically $200 to $400. Demand here is high because it puts you closest to the runway and main stage, so these clear quickly.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official VIP runs from about $350 to $500 and beyond. Packages usually bundle a premium seat or early entry with merchandise, dedicated check-in and occasionally lounge access. Contents vary by tour and city, so check what each tier includes before buying.',
        ),
      ),
      'why' => 'Karol G\'s stadium and arena shows are routed through Live Nation and Ticketmaster, which means most on-sales use dynamic pricing. Rather than a fixed face value, the headline number moves with demand, so the same seat can cost more on a high-demand night or in a Latin-strong market and less on a slower date. This is why two fans on the same tour often report different prices, and why booking early, before the cheaper allocations sell through, usually beats waiting.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Karol G tickets?',
          'a' => 'Karol G tickets typically range from around $60 for upper-level seats to $400 or more for the floor and VIP packages. The exact price depends on whether she is playing an arena or a stadium in your city, the specific date, and how early in the on-sale you buy.',
        ),
        1 => 
        array (
          'q' => 'Why are Karol G tickets so expensive?',
          'a' => 'Most Karol G shows use Ticketmaster\'s dynamic pricing, so prices rise with demand rather than staying at a fixed face value. Her move into stadiums and her strong following in Latin markets mean popular nights sell quickly, pushing both primary and resale prices higher for the best seats.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Karol G live?',
          'a' => 'The cheapest seats are in the upper levels, often starting near $60. To stay at the lower end, book early in the on-sale before the budget inventory clears, consider a weekday or second-night show if one is offered, and compare prices across nearby cities on the same tour.',
        ),
        3 => 
        array (
          'q' => 'How much do Karol G floor tickets cost?',
          'a' => 'Floor or field tickets for Karol G usually run from about $200 to $400, depending on the venue and demand. They place you closest to the runway and main stage, which is why they carry a premium and tend to sell out faster than seated sections.',
        ),
        4 => 
        array (
          'q' => 'Are Karol G VIP packages worth it?',
          'a' => 'Karol G VIP packages, generally $350 to $500 and up, bundle a premium seat or early entry with merchandise and dedicated check-in. They are worth it if you value being close to the stage and the included extras; if you mainly want to attend, a standard lower-bowl seat costs less.',
        ),
        5 => 
        array (
          'q' => 'Does Karol G use dynamic pricing?',
          'a' => 'Yes. Karol G\'s major tours sell through Ticketmaster, which applies dynamic pricing to most inventory. That means the listed price shifts with demand in real time, so seats can cost more on high-demand dates and the number you see may differ from another buyer\'s on the same tour.',
        ),
        6 => 
        array (
          'q' => 'When do Karol G tickets go on sale?',
          'a' => 'Karol G tickets are usually released through Ticketmaster, with verified-fan or artist presales running a day or two ahead of the general on-sale. Signing up for her mailing list or a presale code in advance gives you the best shot at the lower-priced seats before they clear.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Karol G tickets on resale?',
          'a' => 'Not usually for in-demand nights. Resale prices for Karol G\'s popular shows, especially opening weekends and Latin-strong cities, often sit above face value. Buying early in the primary on-sale is generally cheaper, though resale can occasionally drop close to the show date for less popular dates.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Karol G World Tour',
      'intro' => 
      array (
        0 => 'Karol G has become one of the biggest names in Latin music, and her live shows now fill arenas and stadiums across the Americas and Europe. Her tour dates typically cluster around a new album cycle, with the Colombian singer routing through major US cities with large Latin audiences, parts of Latin America, and a growing number of European stops. Routing and venue size vary from one tour to the next.',
        1 => 'Her stadium runs in particular sell quickly, so demand on opening weekends and in cities such as Miami, Los Angeles, New York, Madrid and across Mexico and Colombia is consistently strong. Tour dates are announced through her official channels and Ticketmaster, usually with a presale ahead of the general on-sale, and a full production that carries her reggaeton and pop catalogue across both seated and standing crowds.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Karol G touring in 2026?',
          'a' => 'Karol G tours regularly, typically around each album cycle, and continues to play arenas and stadiums across the US, Latin America and Europe. For confirmed 2026 dates, check her official site or Ticketmaster, as live routing and on-sale information are updated there rather than fixed far in advance.',
        ),
        1 => 
        array (
          'q' => 'Where is Karol G playing on tour?',
          'a' => 'Karol G\'s tours usually take in major US cities with strong Latin audiences such as Miami, Los Angeles, New York and Houston, alongside stops across Mexico, Colombia and other parts of Latin America, plus European cities including Madrid. The exact cities depend on the specific tour being announced.',
        ),
        2 => 
        array (
          'q' => 'How long is a Karol G concert?',
          'a' => 'A Karol G headline show generally runs around two hours, depending on the tour and setlist. She blends her biggest reggaeton and pop hits with newer album tracks, and stadium productions tend to run slightly longer than arena dates thanks to a larger stage and extended sections.',
        ),
        3 => 
        array (
          'q' => 'Does Karol G play stadiums or arenas?',
          'a' => 'Both. Karol G has scaled up from arenas to stadiums in many markets as her audience has grown, particularly in Latin America and Latin-strong US cities, while still playing arenas elsewhere. The venue type affects ticket availability and price, with stadiums offering more seats but wider price bands.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Karol G\'s recent shows lean on her run of reggaeton and Latin pop hits, mixing tracks from Mañana Será Bonito and Tropicoqueta with the singles that built her name. The setlist below reflects songs she has regularly performed on recent tours, giving a sense of what tends to feature rather than a fixed running order, since she rotates material and crowd favourites from night to night.',
      ),
      'songs' => 
      array (
        0 => 'TQG',
        1 => 'Bichota',
        2 => 'Provenza',
        3 => 'MAMIII',
        4 => 'Tusa',
        5 => 'Mi Cama',
        6 => 'Ahora Me Llama',
        7 => 'China',
        8 => 'Gatúbela',
        9 => 'Amargura',
        10 => 'Ojos Ferrari',
        11 => 'Si Antes Te Hubiera Conocido',
        12 => 'Carolina',
        13 => 'Mientras Me Curo de la Cura',
        14 => 'Cairo',
        15 => '200 Copas',
        16 => 'El Barco',
        17 => 'Don\'t Be Shy',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night. Karol G rotates songs, collaborations and album cuts between shows, so the exact selection and order on any given date will differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Karol G play live?',
          'a' => 'Karol G\'s live shows usually feature hits like Bichota, Provenza, TQG, Tusa and MAMIII, alongside tracks from Mañana Será Bonito and her newer releases. She mixes high-energy reggaeton with slower Latin pop moments, though the exact songs change from show to show.',
        ),
        1 => 
        array (
          'q' => 'Does Karol G play Tusa live?',
          'a' => 'Yes, Tusa, her chart-topping collaboration with Nicki Minaj, is one of Karol G\'s most recognisable songs and frequently appears in her live sets. As with any track, its inclusion can vary by night, but it remains a regular crowd highlight on her tours.',
        ),
        2 => 
        array (
          'q' => 'Does Karol G do an encore?',
          'a' => 'Karol G\'s shows often run as a continuous, choreographed set rather than a traditional stop-and-return encore, building to a high-energy finish with her biggest hits. Whether a distinct encore appears depends on the specific tour and venue, so the structure can differ between dates.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Karol G play in concert?',
          'a' => 'Karol G typically performs somewhere between 20 and 30 songs across a roughly two-hour set, often weaving shorter hits into medleys. The exact count varies by tour and venue, with stadium shows sometimes featuring extended sections and additional album tracks.',
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
      'range_low' => 70,
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Drake tickets generally run from around $70 for upper-bowl seats to $600 or more for floor positions and VIP packages, though the figure you actually pay depends on the city, the night and how close to the stage you sit. Drake plays large arenas and, on bigger runs, stadiums, and those venues use Ticketmaster\'s dynamic pricing, so the headline number shifts with demand rather than holding to a fixed face value.',
        1 => 'Because Drake is one of the highest-grossing touring acts in hip-hop, his shows tend to sit at the upper end of arena pricing, and co-headline runs can push the band higher still. The widest spread usually appears between the cheapest corner seats high in the bowl and the premium floor or pit areas near the runway, with VIP and early-entry packages forming a separate tier of their own.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper bowl / corners',
          'desc' => 'The most affordable seats, high in the arena or behind the stage, typically around $70-130. You are far from the runway but get a full view of the production and lighting design.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / sides',
          'desc' => 'Side and lower-tier seating with a clearer sightline to the main stage, usually in the $130-260 range depending on the city and night.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Floor or GA standing close to the action, commonly $260-450. On stadium dates this band widens as proximity to the central runway becomes the premium.',
        ),
        3 => 
        array (
          'name' => 'Pit / front floor',
          'desc' => 'The closest standing positions near the runway or B-stage, often $400-600 when sold separately, where dynamic pricing tends to bite hardest.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official packages bundling premium seating, early entry, merchandise and lounge access, frequently $450-600 and up depending on the inclusions offered on a given tour.',
        ),
      ),
      'why' => 'Most of Drake\'s arena and stadium shows are sold through Ticketmaster using dynamic, demand-based pricing. That means prices are not fixed at face value; they move up and down as seats sell, so an identical seat can cost noticeably more for a high-demand night in a major market than for a midweek date elsewhere. Buying early, before the strongest demand sets in, is usually the most reliable way to stay near the lower end of a section\'s band.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Drake tickets?',
          'a' => 'Drake tickets typically range from about $70 for upper-bowl seats to $600 or more for floor, pit and VIP packages. Most of his shows use Ticketmaster dynamic pricing, so the exact cost depends on the market, the night and how early you buy rather than a fixed face value.',
        ),
        1 => 
        array (
          'q' => 'Why are Drake tickets so expensive?',
          'a' => 'Drake is one of the biggest-grossing touring artists in hip-hop, and high demand combined with Ticketmaster\'s dynamic pricing pushes prices up as seats sell. Large-scale productions, runway stages and co-headline billings also add to the cost, with floor and pit positions commanding the steepest premiums.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Drake live?',
          'a' => 'The cheapest seats are usually the upper bowl or corners, often around $70-130. Buying early in an on-sale, before dynamic pricing climbs, and considering midweek dates in less-crowded markets are the most dependable ways to keep the price down.',
        ),
        3 => 
        array (
          'q' => 'Does Drake use dynamic pricing?',
          'a' => 'Yes. Drake\'s arena and stadium tours are generally sold through Ticketmaster with dynamic, demand-based pricing, so the listed price moves with how fast seats are selling. The same seat can cost more on a high-demand night than on a quieter date.',
        ),
        4 => 
        array (
          'q' => 'How much are Drake VIP and floor tickets?',
          'a' => 'Drake floor seats commonly run $260-450, with the pit nearest the runway reaching $400-600. Official VIP packages, which add early entry, premium seating and lounge or merchandise perks, often start around $450 and climb depending on the inclusions.',
        ),
        5 => 
        array (
          'q' => 'Are Drake tickets cheaper on the day of the show?',
          'a' => 'Sometimes, but it is not reliable. Prices on resale platforms can soften close to showtime if a date has not sold out, yet popular Drake nights often hold firm or rise. For the best chance at lower prices, buying early in the primary on-sale is usually safer.',
        ),
        6 => 
        array (
          'q' => 'Do Drake ticket prices vary by city?',
          'a' => 'Yes. Drake\'s prices reflect local demand, venue size and whether the show is an arena or stadium date, so major markets generally cost more than smaller ones. Dynamic pricing means even the same tour can show different bands from city to city.',
        ),
        7 => 
        array (
          'q' => 'Are Drake resale tickets safe to buy?',
          'a' => 'They can be when purchased through verified platforms like Ticketmaster resale or other reputable marketplaces with buyer guarantees. Resale prices for Drake often sit above face value, so compare against the primary on-sale and avoid unofficial sellers without protection.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Drake Live',
      'intro' => 
      array (
        0 => 'Drake tours in cycles tied to his album releases, typically headlining large arenas across North America and Europe and stepping up to stadiums for his biggest runs. He has a long history of co-headline tours with other major rap and R&B figures, which shape the routing and the scale of each outing. Between full tours he also appears at festivals and one-off shows, so his live schedule tends to come in concentrated bursts.',
        1 => 'When a Drake tour is announced, dates are usually confirmed market by market with Ticketmaster handling the primary on-sale, often preceded by artist and credit-card presales. Because demand is consistently high, popular dates can sell quickly, and additional nights are sometimes added in the largest cities once initial allocations clear.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Drake touring in 2026?',
          'a' => 'Drake tours in cycles around his releases, and any 2026 dates are confirmed market by market rather than as a single fixed schedule. Check the live listings on this page for the most current confirmed dates, as those are injected directly from the ticketing system and updated as shows are announced.',
        ),
        1 => 
        array (
          'q' => 'How do I get presale tickets for Drake?',
          'a' => 'Drake presales are typically run through Ticketmaster along with artist fan presales and card-partner presales that require a code. Registering interest ahead of an announced tour and signing up for his mailing list are the usual routes to a presale code before the general on-sale.',
        ),
        2 => 
        array (
          'q' => 'Does Drake play stadiums or arenas?',
          'a' => 'Both. Drake headlines large arenas for most tours and scales up to stadiums for his biggest runs, particularly on co-headline outings. The venue type affects capacity, stage design and pricing, with stadium shows usually built around a larger central runway production.',
        ),
        3 => 
        array (
          'q' => 'How long is a Drake concert?',
          'a' => 'A Drake headline set generally runs around 90 minutes to two hours, longer on co-headline tours where the running time is shared. The exact length varies by tour and whether there is a support act or a second headliner, so allow extra time for openers.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Drake\'s recent shows draw on a deep catalogue that spans his early mixtape material through his chart-topping albums, mixing rap sets with the melodic, R&B-leaning tracks he is known for. The exact selection changes from night to night and tour to tour, but a core of signature singles and crowd favourites tends to anchor every show, often woven into medleys across a runway or B-stage.',
      ),
      'songs' => 
      array (
        0 => 'Started From the Bottom',
        1 => 'Headlines',
        2 => 'God\'s Plan',
        3 => 'Hotline Bling',
        4 => 'Nice for What',
        5 => 'One Dance',
        6 => 'Passionfruit',
        7 => 'Marvins Room',
        8 => 'Hold On, We\'re Going Home',
        9 => 'Find Your Love',
        10 => 'In My Feelings',
        11 => 'Energy',
        12 => 'Nonstop',
        13 => 'Jumpman',
        14 => 'Forever',
        15 => 'Laugh Now Cry Later',
        16 => 'Rich Flex',
        17 => 'Knife Talk',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Drake frequently reworks the running order, swaps songs in and out and adds guest appearances or local surprises, so no two shows are identical. Treat this as a guide to his typical live staples rather than a fixed set order for any single concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Drake play live?',
          'a' => 'Drake\'s live sets typically feature signatures like God\'s Plan, Started From the Bottom, Hotline Bling, One Dance, Nice for What and Energy, alongside newer tracks such as Rich Flex. He blends rap and melodic songs across the show, and the exact selection shifts from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Drake do an encore?',
          'a' => 'Drake often runs his shows as a continuous set rather than a formal encore, threading his biggest hits through medleys and runway segments. Some nights end on a marquee song that functions like an encore, but the structure varies by tour and isn\'t fixed.',
        ),
        2 => 
        array (
          'q' => 'How long is Drake\'s setlist?',
          'a' => 'Drake usually performs a long set of signature singles and album cuts, frequently in medley form, filling roughly 90 minutes to two hours on a headline night. The count varies because he reworks the order and swaps songs in and out across a tour.',
        ),
        3 => 
        array (
          'q' => 'Does Drake bring out guests at his concerts?',
          'a' => 'Frequently. Drake has a long record of surprise guests, often local artists or collaborators, and co-headline tours naturally feature shared sets. These appearances differ by city and night, so they are part of why no two Drake shows play out the same way.',
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
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Olivia Rodrigo tickets typically run from around $60 for upper-tier seats to $400 or more for floor positions and VIP packages, though the exact figure depends on the city, the night and how early you book. Her tours play arenas, so the resale market can climb quickly once a city sells out, and the cheapest face-value seats tend to disappear first.',
        1 => 'Because Rodrigo\'s audience skews young and loyal, demand for her shows is consistently high. Standard tickets are sold through Ticketmaster using dynamic pricing, which means the headline figure shifts with how many people are buying rather than staying fixed. VIP and merchandise bundles sit at the top of the range and are limited in number per show.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / 300 level',
          'desc' => 'The most affordable seats, high in the arena bowl, usually $60 to $110. The view is distant but the production and screens carry well, and these go fastest at face value.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / 100 level',
          'desc' => 'Side and end seats closer to the stage, generally $120 to $200. A good balance of price and sightline, with a clear view of the stage and the runway on most arena setups.',
        ),
        2 => 
        array (
          'name' => 'Floor / GA standing',
          'desc' => 'Floor seating or general-admission standing near the stage, typically $180 to $300. Closest to the action; GA pit areas can mean queueing early to hold a spot at the barrier.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official bundles with premium seating, early entry and exclusive merchandise, usually $250 to $400 or more. These do not include a meet-and-greet on most legs but offer the best combination of position and extras.',
        ),
      ),
      'why' => 'Olivia Rodrigo\'s arena tours sell through Ticketmaster, which applies dynamic pricing to most major dates. That means the price you see is set by live demand rather than a fixed face value, so identical seats can cost more on a popular night and less on a quieter one. Standard tickets do not use the more aggressive Platinum surge that some stadium acts see, but sold-out cities push buyers onto resale, where prices are set by sellers and run well above face. Booking early, before a city sells through its allocation, is the reliable way to stay near the lower end of the band.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Olivia Rodrigo tickets?',
          'a' => 'Olivia Rodrigo tickets generally range from about $60 for upper-tier arena seats to $400 or more for floor positions and VIP packages. The price you pay depends on the city, the date and how early you buy, since her shows use dynamic pricing that moves with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Olivia Rodrigo tickets so expensive?',
          'a' => 'Prices climb because her arena shows sell out quickly and Ticketmaster uses dynamic pricing, raising face value on high-demand nights. Once a city is sold out, remaining tickets move to resale, where sellers set the price and the cheapest seats often vanish first.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Olivia Rodrigo live?',
          'a' => 'The cheapest route is buying an upper-tier or 300-level seat at face value as soon as a city goes on sale, where prices typically start near $60. Avoiding resale and skipping VIP bundles keeps the cost down, and weeknight shows can be slightly softer than weekends.',
        ),
        3 => 
        array (
          'q' => 'How much do Olivia Rodrigo VIP tickets cost?',
          'a' => 'Olivia Rodrigo VIP packages usually run from about $250 to $400 or more, depending on the city and what the bundle includes. They typically combine premium seating, early entry and exclusive merchandise rather than a meet-and-greet, and the number available per show is limited.',
        ),
        4 => 
        array (
          'q' => 'Are Olivia Rodrigo floor tickets worth it?',
          'a' => 'Floor tickets, generally $180 to $300, put you closest to the stage and runway, which suits fans who want the full energy of the show. General-admission floor sections are standing only and reward arriving early, so they are worth it mainly if proximity matters more than a guaranteed seat.',
        ),
        5 => 
        array (
          'q' => 'Does Olivia Rodrigo use dynamic pricing?',
          'a' => 'Yes. Most of Olivia Rodrigo\'s arena dates sell through Ticketmaster with dynamic pricing, so the headline price rises and falls with live demand rather than sitting at a fixed face value. This is why two people can pay different amounts for similar seats on the same night.',
        ),
        6 => 
        array (
          'q' => 'How much were Olivia Rodrigo tickets on the GUTS World Tour?',
          'a' => 'On the GUTS World Tour, face-value arena seats commonly started around $60 to $90 in the upper tiers and rose to roughly $150 to $250 for the lower bowl and floor, with VIP packages higher. Sold-out cities pushed resale prices well above those figures.',
        ),
        7 => 
        array (
          'q' => 'Are Olivia Rodrigo tickets cheaper on resale?',
          'a' => 'Resale tickets are usually more expensive than face value, not cheaper, because high demand drives sellers to mark them up. Prices can occasionally dip close to a show date if sellers are offloading spares, but for a sold-out night resale almost always costs more than the original ticket.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Olivia Rodrigo Live',
      'intro' => 
      array (
        0 => 'Olivia Rodrigo tours in support of her studio albums, building each run around a new record before adding international legs. Her live schedule has centred on arenas across North America, the UK, Europe and, increasingly, Asia and Australia, with multiple nights booked in major markets where a single show sells out. Tour dates are announced album by album rather than as a constant touring cycle.',
        1 => 'When a run is confirmed, dates and on-sale details are published through Ticketmaster and Rodrigo\'s official site, usually with a registration or presale step to manage demand. Because her shows fill quickly, cities are often added in clusters, and the most reliable way to follow the schedule is to check official listings rather than rely on rumoured dates.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Olivia Rodrigo touring in 2026?',
          'a' => 'Olivia Rodrigo\'s touring depends on her album cycle, and any new dates are confirmed through Ticketmaster and her official channels. Check the live listings on this page for the current schedule, since announced cities and on-sale dates are added as they are released rather than fixed far in advance.',
        ),
        1 => 
        array (
          'q' => 'Where is Olivia Rodrigo touring?',
          'a' => 'Olivia Rodrigo\'s tours typically cover North America, the UK and Europe, with additional dates in Asia and Australia on her wider world legs. She plays arenas in major cities, often booking multiple nights where demand is highest. The live listings here show the confirmed cities for the current run.',
        ),
        2 => 
        array (
          'q' => 'How long is an Olivia Rodrigo concert?',
          'a' => 'An Olivia Rodrigo headline show generally runs around 90 minutes to two hours, including a support act beforehand. The exact length varies by tour and city, but her main set covers material from her albums alongside the singles that fans expect.',
        ),
        3 => 
        array (
          'q' => 'How do I get presale tickets for Olivia Rodrigo?',
          'a' => 'Presale access for Olivia Rodrigo usually comes through a Ticketmaster registration or a fan presale tied to her official site, announced ahead of the general on-sale. Signing up early and being ready when the presale opens gives the best chance at face-value seats before a city sells out.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Olivia Rodrigo\'s recent shows lean heavily on her two studio albums, SOUR and GUTS, mixing the pop-punk energy of tracks like good 4 u and brutal with the slower piano ballads that built her reputation. The setlist below reflects songs she has regularly performed live, though the running order changes from night to night and she often rotates in covers or fan favourites.',
      ),
      'songs' => 
      array (
        0 => 'bad idea right?',
        1 => 'ballad of a homeschooled girl',
        2 => 'vampire',
        3 => 'drivers license',
        4 => 'traitor',
        5 => 'jealousy, jealousy',
        6 => 'love is embarrassing',
        7 => 'making the bed',
        8 => 'logical',
        9 => 'enough for you',
        10 => 'happier',
        11 => 'favorite crime',
        12 => 'deja vu',
        13 => 'brutal',
        14 => 'all-american bitch',
        15 => 'good 4 u',
        16 => 'get him back!',
      ),
      'encore' => 
      array (
        0 => '1 step forward, 3 steps back',
        1 => 'obsessed',
      ),
      'note' => 'Setlists vary night to night, so the exact songs and their order change from show to show. Olivia Rodrigo frequently swaps in different album tracks and occasional covers, and the list above reflects staples from recent tours rather than a single fixed concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Olivia Rodrigo play live?',
          'a' => 'Olivia Rodrigo\'s live shows feature hits like drivers license, good 4 u, vampire, brutal and bad idea right?, drawn mainly from her albums SOUR and GUTS. She balances the upbeat pop-punk tracks with piano ballads such as traitor and favorite crime, though the exact selection changes from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Olivia Rodrigo play drivers license live?',
          'a' => 'Yes, drivers license is a regular fixture of Olivia Rodrigo\'s live set and one of the songs audiences most expect to hear. She typically performs it as a centrepiece ballad, often with the crowd singing along, though as with any song it is not guaranteed on every single night.',
        ),
        2 => 
        array (
          'q' => 'Does Olivia Rodrigo do an encore?',
          'a' => 'Olivia Rodrigo often closes her shows with an encore, frequently bringing back a track like good 4 u or 1 step forward, 3 steps back to finish on high energy. The encore songs and whether one is played can vary by tour and city, so it is not fixed every night.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Olivia Rodrigo play in concert?',
          'a' => 'Olivia Rodrigo typically performs around 18 to 22 songs across a headline set lasting roughly 90 minutes to two hours. The total covers most of her album material plus her best-known singles, with the exact count shifting between tours and individual shows.',
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
      'range_low' => 60,
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Dua Lipa tickets typically run from around $60 for upper-tier and rear seats to $400 or more for floor positions, front-of-stage sections and VIP packages, though what you actually pay depends on the city, the night and how far in advance you book. Her arena and stadium shows are sold through Ticketmaster\'s dynamic pricing, so the headline figure shifts with demand rather than sitting at a fixed face value.',
        1 => 'Because she plays a mix of large arenas and, increasingly, stadiums on her bigger runs, the spread between the cheapest and most expensive Dua Lipa tickets can be wide. A simple upper-bowl seat sits at the lower end, while pit access, hospitality and early-entry packages push toward the top. Resale listings for sold-out dates often exceed face value, so the official on-sale is usually the better place to start.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / rear seating',
          'desc' => 'The most affordable way in, set high in the bowl or toward the back of the floor plan. Expect roughly $60 to $110 depending on the market and how full the room is.',
        ),
        1 => 
        array (
          'name' => 'Lower tier / side seating',
          'desc' => 'Seated views closer to stage level along the sides and lower bowl, a good balance of price and sightline. These generally land around $110 to $200.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission standing',
          'desc' => 'Standing on the floor, often closest to the production and the runway. Pricing here typically runs from about $180 to $300, moving with demand on popular nights.',
        ),
        3 => 
        array (
          'name' => 'Front pit / early entry',
          'desc' => 'Premium standing nearest the stage, sometimes with earlier admission to the venue. These usually sit in the $250 to $350 range.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official bundles combining a prime seat or pit spot with extras such as early entry, merchandise and lounge access, commonly from around $300 to $400 or above.',
        ),
      ),
      'why' => 'Most Dua Lipa dates run on Ticketmaster\'s dynamic pricing, which means face value is not fixed. Prices are set to move with real-time demand, so a high-interest city or a Saturday night can carry a noticeably higher headline number than a midweek show in a smaller market. The cheapest seats can sell quickly, after which the visible entry price climbs. Booking early, staying flexible on the date and comparing official allocation against resale all help you avoid paying inflated figures on sold-out nights.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Dua Lipa tickets?',
          'a' => 'Dua Lipa tickets generally range from about $60 for upper-tier seats to $400 or more for floor positions and VIP packages. The exact price depends on the city, the night and how early you buy, since her shows use dynamic pricing that moves with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are some Dua Lipa tickets so expensive?',
          'a' => 'The most expensive Dua Lipa tickets are usually front-pit, early-entry and VIP packages, plus dynamic-priced seats on high-demand nights. Because pricing adjusts in real time, a popular city or weekend date can carry a much higher headline figure than a quieter midweek show.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Dua Lipa live?',
          'a' => 'The cheapest way is to target upper-tier or rear seating, which typically starts around $60 to $110. Booking on the official on-sale, choosing a midweek date and staying flexible on the city all help, since these lower-priced seats tend to sell out first.',
        ),
        3 => 
        array (
          'q' => 'Does Dua Lipa use dynamic pricing?',
          'a' => 'Yes. Most Dua Lipa arena and stadium dates are sold through Ticketmaster\'s dynamic pricing, so face value is not fixed. The displayed price rises and falls with demand, which is why the same section can cost different amounts across cities and dates.',
        ),
        4 => 
        array (
          'q' => 'How much do Dua Lipa VIP tickets cost?',
          'a' => 'Dua Lipa VIP packages usually run from about $300 to $400 or higher. They bundle a premium seat or pit spot with extras such as early entry, exclusive merchandise and lounge access, so the price sits above standard tickets in the same area.',
        ),
        5 => 
        array (
          'q' => 'Are Dua Lipa floor tickets worth it?',
          'a' => 'Floor or general-admission standing puts you closest to the stage and runway, usually for around $180 to $300. They suit fans who want the most immersive view and do not mind standing, though seated lower-tier tickets offer a calmer experience for less.',
        ),
        6 => 
        array (
          'q' => 'Why do Dua Lipa ticket prices change so much?',
          'a' => 'Prices change because her tours use demand-based dynamic pricing. As interest in a given date rises, the visible price climbs, and once cheaper seats sell, only higher tiers remain. City, day of the week and venue size all feed into the final figure you see.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Dua Lipa tickets on resale?',
          'a' => 'Not usually. For in-demand Dua Lipa shows, resale listings often sit above face value, sometimes well above. The official on-sale is generally the cheaper option, with resale worth checking mainly for sold-out dates or when you need specific seats together.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Dua Lipa Live',
      'intro' => 
      array (
        0 => 'Dua Lipa tour dates tend to arrive in clearly defined campaigns built around an album cycle, with international legs covering Europe, North America, Latin America, Asia and Oceania. She has grown from theatres and clubs into arenas and, on her larger runs, stadiums, so the scale of any given tour reflects where she is in a release. Routing is usually announced city by city, with on-sales staggered across markets.',
        1 => 'When she is between album campaigns, Dua Lipa often appears at major festivals and one-off events rather than a full headline run. Checking the current schedule is the only reliable way to know whether a tour is active, since live dates change and new legs are added over time. This page reflects how her touring generally works; specific dates and venues are listed separately when confirmed.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Dua Lipa touring in 2026?',
          'a' => 'Dua Lipa tours in cycles tied to her album releases, so whether she is on the road in a given year depends on where she is in that schedule. Check the live dates listed on this page for the most current routing, as new legs and festival appearances are added over time.',
        ),
        1 => 
        array (
          'q' => 'Where is Dua Lipa touring?',
          'a' => 'Dua Lipa\'s tours typically span Europe, North America, Latin America, Asia and Oceania, mixing arena headline shows with stadium dates and festival slots. The exact countries and cities vary by campaign, so the confirmed schedule on this page is the best guide to where she is currently playing.',
        ),
        2 => 
        array (
          'q' => 'What venues does Dua Lipa play?',
          'a' => 'Dua Lipa now plays large arenas and, on her bigger tours, stadiums, having moved up from theatres and clubs earlier in her career. The venue size affects pricing and layout, with stadium dates offering more capacity and arena nights a more contained production.',
        ),
        3 => 
        array (
          'q' => 'How do I get Dua Lipa presale tickets?',
          'a' => 'Presale access for Dua Lipa usually comes through artist mailing-list sign-ups, fan-club registrations and partner promotions such as card or carrier presales, each with its own code and window. Registering early and watching the official announcement improves your chances before the general on-sale opens.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Dua Lipa\'s recent shows lean heavily on her dance-pop catalogue, pairing the disco-leaning singles from Future Nostalgia with newer material and a few early breakthrough hits. A typical set builds through her best-known club tracks toward a high-energy finish, with choreography and a full band shaping how each song lands live. The selection below reflects staples she has been playing rather than a fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'Training Season',
        1 => 'Break My Heart',
        2 => 'One Kiss',
        3 => 'Levitating',
        4 => 'Cold Heart',
        5 => 'Physical',
        6 => 'Hallucinate',
        7 => 'Love Again',
        8 => 'Illusion',
        9 => 'New Rules',
        10 => 'Don\'t Start Now',
        11 => 'IDGAF',
        12 => 'Be the One',
        13 => 'Electricity',
        14 => 'Houdini',
        15 => 'Dance the Night',
      ),
      'encore' => 
      array (
        0 => 'Houdini',
        1 => 'Dance the Night',
      ),
      'note' => 'Setlists vary night to night, and Dua Lipa often rotates album cuts, covers and recent singles in and out, so the exact songs and order on any given date will differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Dua Lipa play live?',
          'a' => 'Dua Lipa\'s live shows feature hits such as Don\'t Start Now, Levitating, New Rules, Physical and One Kiss, alongside newer tracks like Houdini, Training Season and Illusion. The precise selection changes between tours and individual nights.',
        ),
        1 => 
        array (
          'q' => 'Does Dua Lipa do an encore?',
          'a' => 'Dua Lipa often closes her shows with a high-energy encore built around her biggest singles, frequently including Houdini and Dance the Night. Whether there is a separate encore segment varies by tour, as some nights run as a more continuous set.',
        ),
        2 => 
        array (
          'q' => 'How long is a Dua Lipa concert?',
          'a' => 'A Dua Lipa headline show typically lasts around 90 minutes to two hours, depending on the tour and any support acts. The main set is built around her dance-pop hits, with the length adjusted for arena versus stadium or festival appearances.',
        ),
        3 => 
        array (
          'q' => 'Does Dua Lipa play New Rules live?',
          'a' => 'Yes, New Rules remains one of Dua Lipa\'s most reliable live staples and regularly features in her sets as one of her early breakthrough hits. As with any song, its inclusion can vary night to night, since she rotates her setlist across a tour.',
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
      'range_low' => 75,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Bruno Mars tickets typically run from around $75 for upper-tier seats to $450 or more for floor positions and VIP packages, though what you actually pay depends on the city, the night and how early you book. His arena and stadium shows sit at the higher end of the pop range, partly because the live band and production make these full-scale concerts rather than stripped-back sets, and partly because demand for his runs tends to outstrip supply.',
        1 => 'Most of Bruno Mars\' major dates are sold through Ticketmaster using dynamic pricing, which means the headline number moves with demand instead of resting at a fixed face value. Las Vegas residency shows and one-off festival appearances follow their own pricing logic, so it helps to compare the venue type before judging whether a price looks high or fair for a given seat.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / general admission',
          'desc' => 'The cheapest way in, usually the upper bowl in an arena or the back of a stadium. Expect roughly $75 to $140 depending on the market and how far ahead you buy.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / mid-level',
          'desc' => 'Seated positions closer to the stage with a clearer view of the band, typically running about $140 to $260. A common sweet spot for sound and sightlines without floor pricing.',
        ),
        2 => 
        array (
          'name' => 'Floor / front sections',
          'desc' => 'Standing or premium seated floor near the stage, where the brass section and dancing land hardest. Prices here generally sit between $260 and $400 and climb fastest under dynamic pricing.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Official packages bundling premium seats with early entry, merchandise or lounge access. These usually start around $350 and can pass $450 or more for the top tiers in larger markets.',
        ),
      ),
      'why' => 'Bruno Mars\' shows lean almost entirely on Ticketmaster\'s dynamic pricing for arena and stadium dates, so the price you see reflects live demand rather than a set face value. Seats released early or in slower-selling markets often carry lower numbers, while opening nights, weekend dates and high-demand cities push the same sections higher. Las Vegas residency performances and resale listings operate separately, which is why two seats in the same row can show very different prices.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Bruno Mars tickets?',
          'a' => 'Bruno Mars tickets generally range from about $75 for upper-tier seats to $450 or more for floor positions and VIP packages. The figure depends on the venue size, the city and how early you buy, since most dates use dynamic pricing that shifts with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are Bruno Mars tickets so expensive?',
          'a' => 'The higher prices come from a combination of strong demand and Ticketmaster\'s dynamic pricing, which lifts popular sections as they sell. His shows are also full-production concerts with a live band and brass section, and limited runs in each city mean supply stays tight relative to interest.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Bruno Mars live?',
          'a' => 'The cheapest seats are usually the upper tier or general admission, often starting around $75 to $140. Buying when tickets first go on sale, before dynamic pricing climbs, and choosing a midweek date rather than a weekend tends to keep the cost down.',
        ),
        3 => 
        array (
          'q' => 'Are Bruno Mars VIP packages worth it?',
          'a' => 'It depends on what you value. Bruno Mars VIP packages, typically from around $350 upward, bundle premium seats with extras like early entry, merchandise or lounge access. They suit fans who want a guaranteed good view and a smoother experience rather than the lowest possible price.',
        ),
        4 => 
        array (
          'q' => 'Does Bruno Mars use dynamic pricing?',
          'a' => 'Yes. Most of Bruno Mars\' arena and stadium dates are sold through Ticketmaster with dynamic pricing, so the listed price rises and falls with demand. This is why the same section can cost noticeably more on a high-demand night than on a quieter one.',
        ),
        5 => 
        array (
          'q' => 'How much are Bruno Mars Las Vegas residency tickets?',
          'a' => 'Bruno Mars\' Las Vegas residency shows are priced separately from his touring dates and tend to start higher, often from around $150 and rising well past $400 for premium seats. The intimate venues and limited capacity keep prices firm compared with larger arena shows.',
        ),
        6 => 
        array (
          'q' => 'When is the best time to buy Bruno Mars tickets?',
          'a' => 'The best time is usually the initial on-sale, before dynamic pricing responds to demand. Prices for popular dates tend to climb after launch, so booking early generally secures a better number, though occasional resale dips can appear closer to the show in quieter markets.',
        ),
        7 => 
        array (
          'q' => 'Are Bruno Mars floor tickets worth the extra cost?',
          'a' => 'Floor tickets, usually $260 to $400, put you closest to the band and the choreography, where the energy is strongest. They are worth it for fans who want proximity, but the lower bowl often gives better overall sound and sightlines for less money.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Bruno Mars Live',
      'intro' => 
      array (
        0 => 'Bruno Mars tour dates tend to cluster around major arenas and stadiums, with stretches of Las Vegas residency shows filling the gaps between full international runs. He does not tour constantly, so when dates are announced they often sell quickly, particularly in cities he visits less frequently. Alongside his solo shows, he also performs as part of Silk Sonic, the project with Anderson .Paak, which has its own separate run of appearances.',
        1 => 'Because his touring follows album cycles and residency commitments rather than a fixed annual schedule, the best approach is to watch official announcements and on-sale windows for your region. Festival headline slots and one-off international dates appear periodically too, so a year without a full tour does not always mean there is no chance to see him live somewhere nearby.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Bruno Mars touring in 2026?',
          'a' => 'Bruno Mars\' touring schedule shifts year to year around album cycles, residency runs and festival slots, so any 2026 dates depend on official announcements. He tends to confirm runs in waves, and checking his official tour listings is the most reliable way to see current dates for your region.',
        ),
        1 => 
        array (
          'q' => 'Does Bruno Mars have a Las Vegas residency?',
          'a' => 'Yes. Bruno Mars performs regular residency shows in Las Vegas, which run separately from his touring dates and recur across the year. These intimate-venue performances are often the most consistent way to see him live between full tours, though they sell quickly and are priced on their own terms.',
        ),
        2 => 
        array (
          'q' => 'How often does Bruno Mars tour?',
          'a' => 'Bruno Mars tours in bursts rather than continuously, usually tied to a new album or a Silk Sonic project, with Las Vegas residency dates between runs. This means there can be quieter stretches, so fans tend to act quickly when a full tour is announced.',
        ),
        3 => 
        array (
          'q' => 'Will Bruno Mars perform as Silk Sonic?',
          'a' => 'Bruno Mars performs as Silk Sonic, his project with Anderson .Paak, on a separate schedule from his solo shows. Those appearances are announced independently, so a Silk Sonic date is distinct from a standard Bruno Mars tour stop and may be ticketed under that name.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Bruno Mars\' recent shows pull from across his catalogue, mixing the funk-driven singles that fill his stadium sets with the slower ballads that give the band room to stretch out. The setlist below reflects songs that have featured regularly at his live performances, including Silk Sonic material when that project is on the bill. It is a guide to what tends to be played rather than a fixed running order for any single night.',
      ),
      'songs' => 
      array (
        0 => '24K Magic',
        1 => 'Finesse',
        2 => 'Treasure',
        3 => 'Calling All My Lovelies',
        4 => 'Marry You',
        5 => 'Runaway Baby',
        6 => 'That\'s What I Like',
        7 => 'Versace on the Floor',
        8 => 'Leave the Door Open',
        9 => 'Locked Out of Heaven',
        10 => 'When I Was Your Man',
        11 => 'Grenade',
        12 => 'Just the Way You Are',
        13 => 'The Lazy Song',
        14 => 'Uptown Funk',
        15 => 'Talking to the Moon',
        16 => 'Skate',
        17 => 'Perm',
      ),
      'encore' => 
      array (
        0 => 'Uptown Funk',
        1 => 'Just the Way You Are',
      ),
      'note' => 'Setlists vary night to night, and Bruno Mars often adjusts the running order, swaps in covers or extends songs depending on the venue and the crowd. Treat this as a guide to his live staples rather than a guaranteed order for any specific concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Bruno Mars play live?',
          'a' => 'Bruno Mars\' live sets usually feature hits like 24K Magic, That\'s What I Like, Treasure, Locked Out of Heaven and Uptown Funk, alongside ballads such as When I Was Your Man and Just the Way You Are. When Silk Sonic material is included, tracks like Leave the Door Open also appear.',
        ),
        1 => 
        array (
          'q' => 'Does Bruno Mars do an encore?',
          'a' => 'Yes, Bruno Mars typically closes with an encore, often built around his biggest singalongs such as Uptown Funk and Just the Way You Are. The exact choices shift from night to night, so the encore is not fixed even within a single tour.',
        ),
        2 => 
        array (
          'q' => 'How long is a Bruno Mars concert?',
          'a' => 'A Bruno Mars headline show generally runs around 90 minutes to two hours, depending on the venue and whether it is a solo set or a Silk Sonic billing. The live band and brass section keep the pace high, with extended grooves and audience interaction stretching some songs out.',
        ),
        3 => 
        array (
          'q' => 'Does Bruno Mars play Uptown Funk live?',
          'a' => 'Yes, Uptown Funk is a regular highlight of Bruno Mars\' live shows and often features near the end of the set or in the encore. As one of his most recognisable hits, it tends to draw the strongest crowd response, though its exact placement varies by night.',
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
      'range_high' => 600,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Kendrick Lamar ticket prices generally run from around $70 for upper-level seats to $400 or more for floor positions and VIP packages, though what you actually pay depends on the city, the night and how close to the stage you sit. His recent run has leaned toward stadiums and large arenas, where the gap between the cheapest and most expensive seats is wide.',
        1 => 'Because most of his shows are sold through Ticketmaster using dynamic pricing, the headline figure shifts with demand rather than holding at a fixed face value. Co-headline or festival-anchored dates, and any stadium leg, tend to sit at the higher end of that band, while standard arena nights and resale drops can land lower.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / 300-level',
          'desc' => 'The most affordable way in, usually $70-130. These are the upper bowl and back-corner seats; the view is distant but the production carries, and prices here move least with demand.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / 100-level',
          'desc' => 'Side and end seats closer to stage level, typically $140-250. A good balance of sightline and cost, and the first tier where dynamic pricing tends to bite on busier nights.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing pit or floor seats near the stage, often $250-400. Demand is heaviest here, so prices climb fastest and sell first, especially for stadium dates.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Premium seating bundled with early entry, merchandise or hospitality, generally $350-600+. Sold through official VIP partners rather than as plain tickets, so the price reflects the extras as much as the seat.',
        ),
      ),
      'why' => 'Kendrick Lamar\'s tours run almost entirely on Ticketmaster\'s dynamic pricing, which means face value is not fixed: the system adjusts prices up and down based on live demand for each seat and each night. A high-anticipation stadium date in a major market will often price well above a midweek arena show, even within the same tier. The realistic band here reflects typical sellable prices across his arena and stadium configurations rather than a single set face value, and resale can push popular dates higher still.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Kendrick Lamar tickets?',
          'a' => 'Kendrick Lamar tickets generally range from about $70 for upper-tier seats to $400 or more for floor and VIP, with most lower-bowl seats landing between $140 and $250. The exact price depends on the venue, the city and how demand is running when you buy, since his shows use dynamic pricing.',
        ),
        1 => 
        array (
          'q' => 'Why are Kendrick Lamar tickets so expensive?',
          'a' => 'Prices climb mainly because his tours use Ticketmaster dynamic pricing, which raises face value on high-demand seats and dates. Stadium configurations, his standing as one of the biggest names in hip-hop, and co-headline appearances all push the better seats higher, while upper-tier tickets stay comparatively affordable.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Kendrick Lamar live?',
          'a' => 'The cheapest seats are usually in the upper tier or 300-level, often around $70-130 face value. Buying early in the on-sale, choosing a midweek date, or watching for resale price drops closer to the show are the most reliable ways to keep the cost down.',
        ),
        3 => 
        array (
          'q' => 'How much are floor tickets for Kendrick Lamar?',
          'a' => 'Floor or pit tickets typically run from about $250 to $400, and more on the busiest stadium nights. This is the highest-demand area, so dynamic pricing tends to push these seats up first and they often sell out earliest.',
        ),
        4 => 
        array (
          'q' => 'Are Kendrick Lamar VIP packages worth it?',
          'a' => 'VIP packages, usually $350-600 or above, bundle premium seating with extras such as early entry, exclusive merchandise or hospitality. They are worth it if you value those add-ons and a guaranteed good seat; if you only want entry, a standard lower-bowl ticket is more economical.',
        ),
        5 => 
        array (
          'q' => 'Do Kendrick Lamar ticket prices change over time?',
          'a' => 'Yes. Because his shows use dynamic pricing, the cost of a given seat can rise or fall between the on-sale and the show date as demand shifts. Popular dates tend to get more expensive, while slower nights sometimes soften, particularly on resale.',
        ),
        6 => 
        array (
          'q' => 'How much were Kendrick Lamar tickets on his last tour?',
          'a' => 'On recent arena and stadium runs, upper-tier seats commonly started near $70-100, lower bowl sat around $150-250, and floor and VIP reached $350 and beyond. Co-headline stadium dates trended higher across every tier because of the added demand.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy Kendrick Lamar tickets on resale?',
          'a' => 'Sometimes. Resale can beat face value once initial demand cools, especially in the final days before a show, but for sought-after dates it often runs higher than the original price. Comparing the official on-sale against verified resale before buying is the safest approach.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Kendrick Lamar Live',
      'intro' => 
      array (
        0 => 'Kendrick Lamar tours in waves rather than constantly, typically building a run around a new album or a major collaborative project. When he does go out, the dates usually centre on large arenas and stadiums across North America and Europe, with festival headline slots filling the gaps between full tours.',
        1 => 'His live schedule is announced in blocks, with the on-sale handled through Ticketmaster and official presales. Because he often pairs with another major artist for co-headline stadium runs, the routing and the scale of each leg can vary considerably from one cycle to the next.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Kendrick Lamar touring?',
          'a' => 'Kendrick Lamar tours in cycles tied to his album and collaborative projects rather than year-round. When a run is active it usually covers arenas and stadiums across North America and Europe, with festival headline dates in between. Check his official site or Ticketmaster for the current confirmed schedule.',
        ),
        1 => 
        array (
          'q' => 'How long is a Kendrick Lamar concert?',
          'a' => 'A headline Kendrick Lamar set typically runs around 90 minutes to two hours. On co-headline stadium bills the timing is shared with the other artist, so his individual portion can be shorter, while a solo arena show tends to run longer with a fuller production.',
        ),
        2 => 
        array (
          'q' => 'Where does Kendrick Lamar usually tour?',
          'a' => 'His tours focus on major markets in North America and Europe, with shows in large arenas and stadiums in cities that can support the demand. He also appears as a festival headliner worldwide, which sometimes substitutes for a standalone tour stop in a given region.',
        ),
        3 => 
        array (
          'q' => 'Does Kendrick Lamar have an opening act?',
          'a' => 'It varies by run. Some tours feature dedicated support acts or guests from his pgLang and Top Dawg circles, while co-headline stadium dates effectively pair him with another major artist instead of a traditional opener. The lineup is confirmed when each leg is announced.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Kendrick Lamar\'s recent shows draw heavily on his most acclaimed albums, threading career-defining tracks together with newer material from his latest cycle. The set below reflects songs that have featured regularly across recent live dates rather than a fixed running order, and his shows are known for theatrical staging that reshapes how familiar tracks are presented.',
      ),
      'songs' => 
      array (
        0 => 'HUMBLE.',
        1 => 'DNA.',
        2 => 'King Kunta',
        3 => 'Alright',
        4 => 'm.A.A.d city',
        5 => 'Swimming Pools (Drank)',
        6 => 'Money Trees',
        7 => 'Bitch, Don\'t Kill My Vibe',
        8 => 'ELEMENT.',
        9 => 'LOYALTY.',
        10 => 'Backseat Freestyle',
        11 => 'N95',
        12 => 'Count Me Out',
        13 => 'Family Ties',
        14 => 'squabble up',
        15 => 'Not Like Us',
        16 => 'tv off',
        17 => 'luther',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, and Kendrick Lamar frequently reworks the running order, swaps deep cuts and adjusts the production from show to show, so any single concert may differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Kendrick Lamar play live?',
          'a' => 'Recent Kendrick Lamar sets lean on signatures like HUMBLE., DNA., Alright, King Kunta and m.A.A.d city, mixed with newer tracks such as N95, Not Like Us and squabble up. The exact selection changes from night to night, but those staples appear most often.',
        ),
        1 => 
        array (
          'q' => 'Does Kendrick Lamar play HUMBLE. live?',
          'a' => 'Yes. HUMBLE. is one of his most reliable live moments and a frequent set highlight, often used as a peak point in the show. Like everything in his set it is not guaranteed every night, but it features regularly across recent dates.',
        ),
        2 => 
        array (
          'q' => 'Does Kendrick Lamar do an encore?',
          'a' => 'Not in a traditional sense. His shows are usually built as a continuous, theatrically staged performance rather than a main set followed by a separate encore, so the biggest tracks are often placed as a climactic finish within the main run.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Kendrick Lamar play in concert?',
          'a' => 'A headline Kendrick Lamar set usually covers roughly 18 to 24 songs across about 90 minutes to two hours, though some are performed as shortened or medley segments. On co-headline stadium bills his shared portion is typically shorter.',
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
        0 => 'Sabrina Carpenter ticket prices typically run from around $60 for upper-level seats to $400 or more for floor positions and VIP packages, though the figure you actually pay depends on the city, the night and how early you commit. Her shows are arena-scale events, and most of them sell through Ticketmaster\'s dynamic pricing, so the headline number tracks demand rather than holding to a single fixed face value.',
        1 => 'The spread is wide because demand for Carpenter has climbed sharply since Short n\' Sweet, and her biggest markets clear fast. Resale can push prices well above face once a date sells out, but waiting for a verified resale drop or an added second show is often the more sensible route than paying the first resale figure you see, particularly for marquee city dates that move quickest.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper-level / behind stage',
          'desc' => 'The most affordable seats, high up or to the side of the stage, usually $60-110. You trade proximity for a full view of the production, which is part of the appeal at her shows.',
        ),
        1 => 
        array (
          'name' => 'Lower-bowl sides',
          'desc' => 'Side-of-floor and lower-tier seating with a closer, angled view, generally $110-200. A common middle-ground choice for fans who want detail without floor pricing.',
        ),
        2 => 
        array (
          'name' => 'Lower bowl centre / front blocks',
          'desc' => 'Centre lower-bowl and the better-positioned blocks facing the stage, roughly $200-300. Strong sightlines and a reliable balance of price and view.',
        ),
        3 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing or seated floor positions near the stage, typically $250-380. The closest standard tickets and the first to disappear in major cities.',
        ),
        4 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Official packages bundling premium seating with early entry, merchandise or lounge access, usually $300-400+. Sold by Ticketmaster rather than offered as a separate ticket type.',
        ),
      ),
      'why' => 'Most of Sabrina Carpenter\'s arena dates use Ticketmaster\'s dynamic pricing, which means face value shifts up or down with live demand rather than sitting at a set number. In practice the strongest cities and Friday or Saturday nights carry the highest prices, while midweek dates and newly added shows can land lower. The bands above reflect typical face value at her arena shows; verified resale can sit above those figures once a date is sold out, so buying early through the primary sale is usually the cheaper path.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Sabrina Carpenter tickets?',
          'a' => 'Sabrina Carpenter tickets generally range from about $60 for upper-level seats to $400 or more for floor and VIP packages. Because her arena shows use dynamic pricing, the exact figure depends on the city, the night of the week and how soon you buy after tickets go on sale.',
        ),
        1 => 
        array (
          'q' => 'Why are Sabrina Carpenter tickets so expensive?',
          'a' => 'Prices climb because demand has outpaced supply since Short n\' Sweet, and Ticketmaster\'s dynamic pricing lifts face value when a show is in high demand. Floor seats and major cities such as New York or Los Angeles command the steepest prices, while upper-level and midweek dates are noticeably cheaper.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Sabrina Carpenter live?',
          'a' => 'The cheapest seats are usually upper-level or behind-stage positions, often starting near $60. Buying during the initial on-sale rather than waiting for resale tends to save the most, and midweek dates or added second shows frequently carry lower prices than weekend nights.',
        ),
        3 => 
        array (
          'q' => 'Are Sabrina Carpenter floor tickets worth it?',
          'a' => 'Floor tickets put you closest to the stage and typically run $250-380, and they sell out first in big cities. They are worth it if proximity matters most to you, but her productions are designed to read well from the lower bowl too, so centre seated tickets can offer better value.',
        ),
        4 => 
        array (
          'q' => 'Do Sabrina Carpenter tickets use dynamic pricing?',
          'a' => 'Yes, most of her Ticketmaster arena dates use dynamic pricing, so the displayed price moves with demand rather than staying fixed. Checking prices at different moments, and across more than one date in your region, can occasionally surface a lower number.',
        ),
        5 => 
        array (
          'q' => 'How much are Sabrina Carpenter VIP packages?',
          'a' => 'Official VIP packages usually start around $300 and rise to $400 or more, depending on what they include. They typically bundle premium seating with extras such as early entry, exclusive merchandise or lounge access, and are sold directly through Ticketmaster as a package rather than a plain ticket.',
        ),
        6 => 
        array (
          'q' => 'Is it cheaper to buy Sabrina Carpenter tickets on resale?',
          'a' => 'Resale is rarely cheaper for in-demand dates, where verified resale often sits above original face value. It can occasionally help if a date is undersold or as the show date nears and sellers lower prices, but for popular cities the primary on-sale is usually the better deal.',
        ),
        7 => 
        array (
          'q' => 'Do Sabrina Carpenter ticket prices drop closer to the show?',
          'a' => 'Sometimes. For dates that are not sold out, resale prices can soften in the final week as sellers offload inventory. For high-demand cities, though, prices tend to hold or rise, so waiting carries real risk and the safer approach is buying early during the official sale.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Sabrina Carpenter World Tour',
      'intro' => 
      array (
        0 => 'Sabrina Carpenter tours primarily as an arena headliner, having graduated from theatres and support slots to her own large-scale shows on the back of Short n\' Sweet. A typical run covers North America and Europe across multiple legs, with major cities often receiving more than one night when demand warrants, and festival appearances slotted in around the headline dates.',
        1 => 'Her touring cadence follows her album cycles, so new legs and added shows tend to arrive when fresh material lands. On-sale dates usually open with artist or fan presales before a general sale, and high-demand markets clear quickly. Live dates for your city are listed separately on this page, as they change throughout each cycle and across the different legs of a tour.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Sabrina Carpenter touring in 2026?',
          'a' => 'Sabrina Carpenter continues to tour as an arena headliner, with dates tied to her current album cycle. Confirmed shows for your region are listed separately on this page, since routing and added dates change across the year as new legs and second shows are announced.',
        ),
        1 => 
        array (
          'q' => 'Where is Sabrina Carpenter performing?',
          'a' => 'Her tours typically take in arenas across North America and Europe, with the largest cities often getting multiple nights. Specific venues and cities for the current run appear in the live listings on this page, which update as new dates are confirmed.',
        ),
        2 => 
        array (
          'q' => 'How long is a Sabrina Carpenter concert?',
          'a' => 'A Sabrina Carpenter headline show generally runs around 90 minutes to just under two hours, including the encore, after a support act. The exact length varies by night and by the production for each tour, so timings should be taken as a guide rather than a fixed schedule.',
        ),
        3 => 
        array (
          'q' => 'How do I get presale tickets for Sabrina Carpenter?',
          'a' => 'Presale access usually comes through artist or fan registrations and partner promotions ahead of the general on-sale. Registering early and signing up for her mailing list improves your chances, and the general sale through Ticketmaster follows once any presales have run their course.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Recent Sabrina Carpenter shows lean heavily on Short n\' Sweet alongside breakout singles and a handful of older favourites, built around a polished, theatrical pop production. The setlist below reflects songs she has regularly performed on recent tours, rather than the exact order of any single concert, and it gives a fair sense of what a typical night covers.',
      ),
      'songs' => 
      array (
        0 => 'Taste',
        1 => 'Please Please Please',
        2 => 'Espresso',
        3 => 'Bed Chem',
        4 => 'Good Graces',
        5 => 'Sharpest Tool',
        6 => 'Slim Pickins',
        7 => 'Dumb & Poetic',
        8 => 'Feather',
        9 => 'Because I Liked a Boy',
        10 => 'Nonsense',
        11 => 'Bad Chem',
        12 => 'Coincidence',
        13 => 'Juno',
        14 => 'Lie to Girls',
        15 => 'Don\'t Smile',
      ),
      'encore' => 
      array (
        0 => 'Espresso',
      ),
      'note' => 'Setlists vary from night to night, and Sabrina Carpenter rotates songs, adds covers and adjusts the running order across a tour, so any single show may differ from the list above.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Sabrina Carpenter play live?',
          'a' => 'Recent shows centre on Short n\' Sweet tracks such as Taste, Please Please Please, Bed Chem and Espresso, mixed with earlier favourites like Nonsense and Feather. The exact selection shifts from night to night, so the running order at any one concert may differ from the typical set.',
        ),
        1 => 
        array (
          'q' => 'Does Sabrina Carpenter do an encore?',
          'a' => 'Yes, her headline shows typically close with an encore, often built around Espresso as the final moment of the night. As with the rest of the set, the encore can change across a tour, so it is best treated as a likely closer rather than a guaranteed one.',
        ),
        2 => 
        array (
          'q' => 'Does Sabrina Carpenter play Espresso live?',
          'a' => 'Yes, Espresso is a fixture of her recent shows and usually lands as a highlight late in the set or in the encore. Given how central the song is to her current popularity, it is among the most reliable inclusions on any given night.',
        ),
        3 => 
        array (
          'q' => 'How many songs does Sabrina Carpenter perform?',
          'a' => 'A typical Sabrina Carpenter headline set runs to roughly 16 to 20 songs, including the encore. The precise count varies by venue and tour leg, as she rotates album tracks, singles and the occasional cover across different nights.',
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
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Post Malone tickets generally run from around $60 for upper-level and rear seats to $400 or more for floor positions, pit access and VIP packages, though what you actually pay shifts with the city, the night of the week and how far ahead you book. Because his bigger runs play arenas and stadiums on Ticketmaster and Live Nation, the listed price is rarely a fixed face value and tends to climb as a date fills out.',
        1 => 'Where a show lands on that band depends largely on venue size. An arena date often sits lower at the back and mid-tier, while a stadium night pushes the floor and front sections higher. Add-ons such as early entry, premium viewing decks and merchandise bundles raise the top end further, so the figures below are a guide to typical Post Malone pricing rather than a quote for any single concert.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear seating',
          'desc' => 'The most affordable way in, usually $60 to $120. These are upper-bowl arena seats or rear stadium sections with a full-stage view from distance. Prices here move least with demand, so they are where early buyers tend to find the steadier numbers.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl and mid-level',
          'desc' => 'Typically $120 to $220. Sided and lower-tier seating closer to the stage, with a clearer sightline of Malone and the production. This band is where dynamic pricing is most visible, climbing as a date nears capacity.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Generally $200 to $320. Standing or seated floor positions near the front, the closest standard option to the stage. On busy stadium nights these sell quickly and the upper end of this range is common.',
        ),
        3 => 
        array (
          'name' => 'Pit and VIP packages',
          'desc' => 'Usually $300 to $400 or more. Front-pit access, premium decks and official VIP bundles that may include early entry, dedicated entrances or merchandise. The ceiling depends on what each package includes and the prestige of the venue.',
        ),
      ),
      'why' => 'Most of Post Malone\'s major arena and stadium dates use Ticketmaster\'s dynamic pricing, which means the price you see adjusts with live demand rather than holding at a single printed face value. Popular sections and high-demand nights drift upward as a show sells, while quieter dates and rear seating can stay closer to the lower band. Buying earlier generally gives you a calmer number, since the steepest movement tends to happen as a date approaches sell-out. VIP and pit tiers are priced separately by the promoter and sit above the standard scale.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Post Malone tickets?',
          'a' => 'Post Malone tickets typically range from about $60 for upper-tier seats to $400 or more for floor, pit and VIP packages. The exact price depends on the venue size, the city, the date and how early you buy, since his arena and stadium shows use dynamic pricing.',
        ),
        1 => 
        array (
          'q' => 'Why do Post Malone ticket prices change?',
          'a' => 'Prices change because most of his tours use Ticketmaster\'s dynamic pricing, which adjusts the cost in real time based on demand. As a date fills up, popular sections rise above their starting figure, so the same seat can cost different amounts on different days.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Post Malone live?',
          'a' => 'The cheapest seats are usually the upper-tier and rear sections, often starting around $60 to $120. These move least with demand, so booking early when a date first goes on sale tends to secure the lowest prices.',
        ),
        3 => 
        array (
          'q' => 'How much are Post Malone floor tickets?',
          'a' => 'Floor tickets generally run from about $200 to $320, and front-pit positions can reach $400 or more. Floor demand is high at stadium shows in particular, so these sections often sit near the top of the range as a date sells.',
        ),
        4 => 
        array (
          'q' => 'Are Post Malone VIP packages worth it?',
          'a' => 'VIP packages, usually $300 to $400 or more, suit fans who want early entry, premium viewing areas or merchandise bundled in. Whether they are worth it depends on the inclusions, which vary by tour and venue, so check exactly what each package offers before buying.',
        ),
        5 => 
        array (
          'q' => 'Do Post Malone ticket prices vary by city?',
          'a' => 'Yes. Larger markets and stadium dates tend to price higher than smaller arena stops, and weekend nights often cost more than midweek shows. Local demand and venue capacity are the main reasons the same tour can vary noticeably between cities.',
        ),
        6 => 
        array (
          'q' => 'Is it cheaper to buy Post Malone tickets early?',
          'a' => 'Usually, yes. Because dynamic pricing pushes popular sections up as a date approaches sell-out, the calmest numbers are often available when tickets first go on sale. Waiting can mean paying more, though quieter dates occasionally soften closer to the show.',
        ),
        7 => 
        array (
          'q' => 'How much do Post Malone tickets cost at a stadium versus an arena?',
          'a' => 'Stadium shows generally price higher, especially on the floor and in front sections, because demand and capacity are larger. Arena dates tend to offer more seats in the lower $60 to $150 bands, while stadium floors more often reach the $300-plus end.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Post Malone Live',
      'intro' => 
      array (
        0 => 'Post Malone tour dates tend to arrive in clearly defined runs rather than as a constant stream, with the artist announcing a leg of shows tied to a recent album and then routing through major arenas and stadiums. North American dates usually anchor a cycle, with European, UK and festival appearances added around them. Announcements typically come a few months ahead, followed by presales and a general on-sale through Ticketmaster and Live Nation.',
        1 => 'His live setup has grown with his catalogue, moving from clubs and theatres early on to full arena and stadium production. Because Malone draws across hip-hop, pop and, more recently, country audiences, his routing often mixes headline concerts with large festival slots. For the latest confirmed Post Malone tour dates, cities and on-sale times, check the live listings on this page, which are updated as shows are announced.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Post Malone touring in 2026?',
          'a' => 'Post Malone tours in defined runs tied to his album cycles, so whether a given year has dates depends on his current release schedule. Check the live listings on this page for confirmed cities and on-sale times, which update as new Post Malone shows are announced.',
        ),
        1 => 
        array (
          'q' => 'How do I get presale access to Post Malone tickets?',
          'a' => 'Presale access usually comes through artist mailing lists, Ticketmaster\'s Verified Fan registration and partner card or carrier promotions. Signing up before a tour is announced gives the best chance at a code, since presales run a day or two ahead of the general on-sale.',
        ),
        2 => 
        array (
          'q' => 'What venues does Post Malone usually play?',
          'a' => 'Post Malone typically plays large arenas and, on bigger tours, stadiums, alongside major festival headline slots. The venue size shapes both capacity and pricing, with stadium dates carrying higher floor prices than the arena stops on the same cycle.',
        ),
        3 => 
        array (
          'q' => 'How far in advance are Post Malone tour dates announced?',
          'a' => 'Tour dates are usually announced a few months before the first show, often alongside or shortly after a new album. Presales follow within days of the announcement, so registering interest early is the most reliable way to be ready when a date drops.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Post Malone\'s recent shows tend to draw across his whole run of hits, opening with high-energy singles before settling into the slower, melodic tracks that built his name. A typical night moves between hip-hop, pop and the country-leaning material from his later work, with Malone often handling long stretches solo on a central stage. The selection below reflects songs that have featured regularly in recent live sets rather than a fixed running order.',
      ),
      'songs' => 
      array (
        0 => 'Wow.',
        1 => 'Better Now',
        2 => 'I Fall Apart',
        3 => 'Goodbyes',
        4 => 'Psycho',
        5 => 'Rockstar',
        6 => 'Circles',
        7 => 'Congratulations',
        8 => 'White Iverson',
        9 => 'Sunflower',
        10 => 'Chemical',
        11 => 'Wrapped Around Your Finger',
        12 => 'Stay',
        13 => 'Take What You Want',
        14 => 'I Like You (A Happier Song)',
        15 => 'Cooped Up',
        16 => 'Overdrive',
        17 => 'Pour Me a Drink',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary from night to night, so the exact songs and their order change between shows. Post Malone often adjusts the list for festival slots, co-headline dates and his newer country-leaning material, and tends to run his set as a continuous performance rather than a separate encore.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Post Malone play live?',
          'a' => 'Post Malone usually plays his signature hits live, including Sunflower, Circles, Congratulations, Rockstar, Better Now and White Iverson, mixed with newer material. The exact selection changes night to night, so no two shows feature an identical list.',
        ),
        1 => 
        array (
          'q' => 'Does Post Malone do an encore?',
          'a' => 'Post Malone often runs his set as one continuous performance rather than a formal encore, closing on a major hit such as Congratulations or Sunflower. Whether a given night includes an encore depends on the venue and the show\'s running time.',
        ),
        2 => 
        array (
          'q' => 'How long is a Post Malone concert?',
          'a' => 'A headline Post Malone concert generally runs around 90 minutes to two hours, depending on the venue and whether there are support acts. Festival sets are shorter, usually closer to an hour, and feature a tighter selection of his best-known songs.',
        ),
        3 => 
        array (
          'q' => 'Does Post Malone play his country songs live?',
          'a' => 'Yes. Since moving into country music, Post Malone has worked tracks like Pour Me a Drink and I Had Some Help into his sets alongside his hip-hop and pop catalogue. How much country material appears varies by tour and by the audience at each show.',
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
      'range_low' => 80,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Metallica tickets generally run from around $80 for upper-bowl seats to $450 or more for floor positions and enhanced packages, though what you actually pay depends on the city, the venue and how early you book. Most of the band\'s recent touring has been in stadiums with an in-the-round stage, which spreads seating around all sides and creates a wide spread between the cheapest and most expensive tickets.',
        1 => 'Because Metallica works largely with Live Nation and Ticketmaster, the headline price you see is rarely a fixed face value. Stadium dates use dynamic pricing, so the same seat can cost noticeably more in a high-demand market than in a smaller one. The band has historically released a portion of lower-priced tickets to keep entry-level seats accessible, but those tend to clear quickly once a date goes on general sale.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper bowl / general seating',
          'desc' => 'The furthest seats from the stage, usually in the $80-$140 range. Fine for the full stadium spectacle, the pyro and the sound, but a long way from the band.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / mid-tier',
          'desc' => 'Side and lower-level seating, typically $150-$260. A clearer sightline to the in-the-round stage and a stronger sense of the production without floor pricing.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission pit',
          'desc' => 'Standing positions on the stadium floor, usually $250-$400. Closest to the band and the snake-pit area on relevant tours, with the most intense atmosphere.',
        ),
        3 => 
        array (
          'name' => 'Enhanced / VIP packages',
          'desc' => 'Premium seating bundled with early entry, merchandise or hospitality, commonly $400-$700+ depending on the market and inclusions.',
        ),
      ),
      'why' => 'Metallica\'s stadium tours are routed through Live Nation and ticketed by Ticketmaster, which means most dates use dynamic pricing. Rather than a single fixed face value, prices move with demand: a sold-out market or a high-profile opening night will push the same section higher than a midweek date elsewhere. The band typically holds back a block of lower-priced seats so entry-level tickets stay reachable, but the gap between those and floor or VIP pricing is wide, and the cheapest tiers usually sell first.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Metallica tickets?',
          'a' => 'Metallica tickets usually range from about $80 for upper-level stadium seats to $450 or more for the floor and VIP packages. Because their shows are large stadium dates with an in-the-round stage, the spread between the cheapest and most expensive seats is unusually wide.',
        ),
        1 => 
        array (
          'q' => 'Why do Metallica ticket prices change so much?',
          'a' => 'Most Metallica dates use Ticketmaster\'s dynamic pricing, so the cost rises and falls with demand rather than staying at a fixed face value. A high-demand city or opening night can sit well above the same seat in a quieter market.',
        ),
        2 => 
        array (
          'q' => 'Are there cheaper Metallica tickets available?',
          'a' => 'Yes. Metallica has historically released a block of lower-priced seats, often starting around $80 in the upper bowl, to keep entry-level tickets accessible. These tend to sell quickly once a date opens for general sale.',
        ),
        3 => 
        array (
          'q' => 'How much are floor tickets for Metallica?',
          'a' => 'Floor and pit tickets for Metallica typically run between $250 and $400, depending on the venue and demand. These put you closest to the in-the-round stage and the snake-pit area used on recent tours.',
        ),
        4 => 
        array (
          'q' => 'What do Metallica VIP packages cost?',
          'a' => 'Metallica VIP and enhanced packages generally start around $400 and can exceed $700, depending on the market. They usually bundle premium seating with extras such as early entry, exclusive merchandise or hospitality access.',
        ),
        5 => 
        array (
          'q' => 'Do Metallica\'s two-night runs affect ticket prices?',
          'a' => 'On No Repeat Weekend runs, where the band plays two different sets in one city, demand for a single-city pass can lift prices for both nights. Buying the two dates together is often marketed as a package, and dynamic pricing still applies to each.',
        ),
        6 => 
        array (
          'q' => 'Are Metallica tickets more expensive than other metal acts?',
          'a' => 'Generally yes. As one of the biggest acts in the genre playing stadiums rather than arenas, Metallica\'s top-end floor and VIP prices sit above most metal and rock tours, though their held-back lower tiers keep the entry price competitive.',
        ),
        7 => 
        array (
          'q' => 'When is the cheapest time to buy Metallica tickets?',
          'a' => 'The lowest face-value seats are usually available at the initial general sale, before dynamic pricing responds to demand. Waiting often means the cheapest tiers have sold and remaining inventory has drifted upward, so early buying tends to cost less.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Metallica Live',
      'intro' => 
      array (
        0 => 'Metallica tour dates centre on large stadium runs, often built around an in-the-round stage and, on recent outings, a No Repeat Weekend format where the band plays two different setlists in the same city across a weekend. They tend to tour in multi-year cycles tied to an album, hitting Europe, North America and select international markets before circling back.',
        1 => 'The band typically books well in advance, with on-sales months ahead of the shows themselves. Support line-ups vary by date and region, and Metallica has favoured rotating opening acts rather than a single fixed bill across a run. Routing is announced in blocks, so a market not listed in one leg can appear in a later one.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Metallica touring?',
          'a' => 'Metallica tours in multi-year cycles, usually built around a new album, and has spent recent years on large stadium runs. Specific dates and cities are announced in legs, so availability depends on the current routing for your region.',
        ),
        1 => 
        array (
          'q' => 'What is Metallica\'s No Repeat Weekend?',
          'a' => 'No Repeat Weekend is a format where Metallica plays a city twice over one weekend with two completely different setlists and, often, different opening acts. Fans attending both nights see a broad cross-section of the catalogue with minimal song overlap.',
        ),
        2 => 
        array (
          'q' => 'Does Metallica play stadiums or arenas?',
          'a' => 'Recent Metallica tours have been predominantly stadium shows, frequently using a 360-degree in-the-round stage so the audience surrounds the band. Earlier in their career and on smaller runs they have also played arenas and festivals.',
        ),
        3 => 
        array (
          'q' => 'How far in advance do Metallica tickets go on sale?',
          'a' => 'Metallica typically announces tour legs and opens sales several months before the shows, sometimes with fan-club or presale windows ahead of the general on-sale. Routing is revealed in blocks, so new cities can be added in later announcements.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Metallica\'s recent shows draw from across four decades, mixing the thrash staples that built their name with cuts from later albums and the occasional deep track. On No Repeat Weekend dates the band splits the catalogue across two nights, so any single show is only part of the picture. The list below reflects songs that have been regular features of their recent live sets.',
      ),
      'songs' => 
      array (
        0 => 'Enter Sandman',
        1 => 'Master of Puppets',
        2 => 'For Whom the Bell Tolls',
        3 => 'One',
        4 => 'Sad but True',
        5 => 'Creeping Death',
        6 => 'Seek & Destroy',
        7 => 'Fuel',
        8 => 'The Memory Remains',
        9 => 'Nothing Else Matters',
        10 => 'Wherever I May Roam',
        11 => 'Battery',
        12 => 'Fade to Black',
        13 => 'Welcome Home (Sanitarium)',
        14 => 'Whiplash',
        15 => 'The Unforgiven',
        16 => 'Lux Æterna',
        17 => 'Moth Into Flame',
      ),
      'encore' => 
      array (
        0 => 'Nothing Else Matters',
        1 => 'Enter Sandman',
        2 => 'Master of Puppets',
      ),
      'note' => 'Setlists vary from night to night, and on No Repeat Weekend dates the band deliberately changes the set between the two shows. Treat this as a guide to recent live staples rather than a fixed running order for any single concert.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Metallica play live?',
          'a' => 'Metallica\'s live sets are anchored by staples such as Enter Sandman, Master of Puppets, For Whom the Bell Tolls, One and Seek & Destroy, mixed with later material and occasional deep cuts. The exact selection changes from show to show.',
        ),
        1 => 
        array (
          'q' => 'Does Metallica do an encore?',
          'a' => 'Yes. Metallica typically closes with an encore that leans on their biggest songs, often including Enter Sandman and a track like Master of Puppets or Nothing Else Matters. The specific encore choices vary by night.',
        ),
        2 => 
        array (
          'q' => 'How long is a Metallica concert?',
          'a' => 'A Metallica headline show generally runs around two to two and a half hours, often spanning roughly 16 to 18 songs. Stadium production, extended intros and crowd interaction can stretch the running time further.',
        ),
        3 => 
        array (
          'q' => 'Do Metallica play the same setlist every night?',
          'a' => 'No. Metallica regularly rotate their setlist between shows, and on No Repeat Weekend dates they perform two entirely different sets in the same city. Core anthems recur often, but the surrounding songs change night to night.',
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
      'currency' => 'USD',
      'range_low' => 60,
      'range_high' => 400,
      'intro' => 
      array (
        0 => 'Arijit Singh ticket prices generally run from around $60 for upper-level and rear seating to $400 or more for premium front blocks and VIP packages, though what you actually pay depends on the city, the venue and how early you book. His international shows tend to fill large arenas, so prices sit higher than a typical Bollywood theatre night and move with demand rather than a single fixed face value.',
        1 => 'Because Arijit Singh tours arenas and the occasional stadium abroad, pricing follows the same pattern as other large concert tours: a wide band between budget upper tiers and premium floor seating. Demand for his shows is consistently strong across the Gulf, the UK, North America and India, so cheaper seats clear quickly and the headline price you see early tends to be the lowest you will find.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier and rear sections',
          'desc' => 'The most affordable seats, usually in the upper bowl or behind the stage, typically around $60 to $110. You are further from the stage but screens and the venue\'s acoustics keep the experience strong for a vocal-led show like his.',
        ),
        1 => 
        array (
          'name' => 'Lower tier and side blocks',
          'desc' => 'Mid-priced seating in the lower bowl or along the sides, generally $110 to $200. A good balance of sightline and value, with a clear view of the stage and band.',
        ),
        2 => 
        array (
          'name' => 'Floor and front sections',
          'desc' => 'Premium seated or standing areas close to the stage, usually $200 to $320. Best for fans who want to be near Arijit during the quieter, piano-led numbers.',
        ),
        3 => 
        array (
          'name' => 'VIP and hospitality packages',
          'desc' => 'Top-tier packages with the best seats and added extras such as early entry or hospitality, commonly $320 to $400 or above depending on the market and promoter.',
        ),
      ),
      'why' => 'Most of Arijit Singh\'s larger international arena shows are sold through promoters and ticketing platforms that use dynamic pricing, which means the listed price shifts with demand rather than staying fixed at face value. Popular dates and seat blocks can climb well above the opening price, while quieter midweek shows or less central sections may stay nearer the lower band. Booking early generally secures the closest thing to face value, since the cheapest tiers sell out first and prices tend to rise as a date approaches.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Arijit Singh tickets?',
          'a' => 'Arijit Singh tickets typically range from about $60 for upper-tier seats to $400 or more for premium floor and VIP packages. The exact price depends on the city, the venue size and how far in advance you buy, with arena shows priced higher than smaller theatre dates.',
        ),
        1 => 
        array (
          'q' => 'Why are Arijit Singh tickets so expensive?',
          'a' => 'Arijit Singh fills large arenas internationally and demand consistently outstrips supply, so promoters use dynamic pricing that rises with demand. Premium floor seats and VIP packages carry the steepest prices, while upper-tier seats remain comparatively affordable if you book early.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see Arijit Singh live?',
          'a' => 'The cheapest way to see Arijit Singh is to book upper-tier or rear seating early, where prices usually start around $60. These sections sell out first, so securing them well ahead of the show date gives you the best chance of the lowest price.',
        ),
        3 => 
        array (
          'q' => 'Do Arijit Singh ticket prices use dynamic pricing?',
          'a' => 'Yes, most of Arijit Singh\'s larger international arena shows use dynamic pricing, so the headline figure moves with demand rather than sitting at a fixed face value. Prices for in-demand dates and seat blocks tend to climb as the show approaches.',
        ),
        4 => 
        array (
          'q' => 'How much do front-row Arijit Singh tickets cost?',
          'a' => 'Front-row and premium floor seats for Arijit Singh generally cost between $200 and $400 or more, depending on the city and whether the seat is part of a VIP package. These are the first premium sections to sell out for popular dates.',
        ),
        5 => 
        array (
          'q' => 'Are Arijit Singh VIP packages worth it?',
          'a' => 'Arijit Singh VIP packages typically cost between $320 and $400 or above and bundle the best seats with extras such as early entry or hospitality. They suit fans who want to be close to the stage for his intimate, vocal-led performances and value the added convenience.',
        ),
        6 => 
        array (
          'q' => 'How far in advance should I buy Arijit Singh tickets?',
          'a' => 'Buy Arijit Singh tickets as early as possible, ideally when a date first goes on sale. His shows draw strong demand across the Gulf, UK, North America and India, so the cheapest seats clear quickly and dynamic pricing pushes remaining prices higher closer to the date.',
        ),
        7 => 
        array (
          'q' => 'Do Arijit Singh ticket prices vary by city?',
          'a' => 'Yes, Arijit Singh ticket prices vary by city, driven by venue size, local demand and the promoter. Major-market arena dates in places like London, Dubai or New York tend to price higher than shows in smaller venues, and each market sets its own band within the overall range.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Arijit Singh Live in Concert',
      'intro' => 
      array (
        0 => 'Arijit Singh tours regularly on the international circuit, taking his live show to arenas across the Gulf, the United Kingdom, North America, Europe, Australia and major Indian cities. His concerts are built around his playback catalogue, performed with a full live band and sweeping production, and dates are usually announced city by city through local promoters rather than as a single fixed global run.',
        1 => 'A typical Arijit Singh tour leans on arena-sized venues, reflecting the scale of his following beyond India. New legs and dates surface throughout the year, so the best approach is to follow announcements for your region. Because demand is high, on-sale dates tend to move quickly, and shows in the larger markets are often added or upgraded to bigger rooms as interest builds.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Arijit Singh touring in 2026?',
          'a' => 'Arijit Singh tours internationally on a regular basis, with new arena dates announced city by city through the year. Check the latest confirmed dates for your region, as his schedule typically spans the Gulf, the UK, North America, Europe and India rather than a single fixed run.',
        ),
        1 => 
        array (
          'q' => 'Where does Arijit Singh perform on tour?',
          'a' => 'Arijit Singh performs mainly in large arenas across the Gulf, the United Kingdom, North America, Europe, Australia and major Indian cities. His international dates target arena-sized venues, reflecting the scale of his audience outside India.',
        ),
        2 => 
        array (
          'q' => 'How long is an Arijit Singh concert?',
          'a' => 'An Arijit Singh concert usually runs around two to three hours, performed with a full live band. He moves between his romantic ballads, upbeat film numbers and quieter piano-led moments, often extending songs with improvisation depending on the night.',
        ),
        3 => 
        array (
          'q' => 'How do I find Arijit Singh tour dates near me?',
          'a' => 'Arijit Singh tour dates are announced city by city through regional promoters, so the most reliable way to find a show near you is to watch announcements for your market. Major-market dates often sell quickly, so signing up for on-sale alerts helps you book early.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Arijit Singh\'s recent shows draw from across his playback career, mixing his best-known romantic ballads with upbeat film numbers and stripped-back piano interludes. He often reworks familiar songs live, stretching the slower numbers and weaving in improvisation, so the set leans heavily on his signature melodies rather than a fixed sequence. The selection below reflects the staples that have featured regularly across his arena performances.',
      ),
      'songs' => 
      array (
        0 => 'Tum Hi Ho',
        1 => 'Channa Mereya',
        2 => 'Ae Dil Hai Mushkil',
        3 => 'Kesariya',
        4 => 'Agar Tum Saath Ho',
        5 => 'Raabta',
        6 => 'Gerua',
        7 => 'Janam Janam',
        8 => 'Pal Pal Dil Ke Paas',
        9 => 'Phir Le Aya Dil',
        10 => 'Hamari Adhuri Kahani',
        11 => 'Sajde',
        12 => 'Muskurane',
        13 => 'Shayad',
        14 => 'Tujhe Kitna Chahne Lage',
        15 => 'Kabira',
        16 => 'Soch Na Sake',
        17 => 'Apna Bana Le',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night. Arijit Singh changes his selection across shows and frequently extends or reworks songs live, so the running order on any given date will differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Arijit Singh play live?',
          'a' => 'Arijit Singh\'s live shows centre on his signature ballads and film hits such as Tum Hi Ho, Channa Mereya, Ae Dil Hai Mushkil, Kesariya and Agar Tum Saath Ho, alongside upbeat numbers like Gerua and Kabira. His exact selection varies from night to night.',
        ),
        1 => 
        array (
          'q' => 'Does Arijit Singh do an encore?',
          'a' => 'Arijit Singh\'s concerts tend to run as a continuous set rather than a formal encore, with the closing stretch built around his biggest singalong numbers. He often extends popular songs and adds improvised moments instead of a separate encore segment.',
        ),
        2 => 
        array (
          'q' => 'Does Arijit Singh play Tum Hi Ho live?',
          'a' => 'Yes, Tum Hi Ho is one of Arijit Singh\'s most reliable live staples and a frequent highlight of his shows. He often performs it as an extended, audience-led moment, though as with all his songs the placement in the set varies by night.',
        ),
        3 => 
        array (
          'q' => 'How long does Arijit Singh play for?',
          'a' => 'Arijit Singh usually performs for around two to three hours, working through a deep catalogue of romantic ballads, upbeat film tracks and piano-led interludes with his live band. The length and song choices vary depending on the show.',
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
      'range_low' => 70,
      'range_high' => 450,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'Travis Scott ticket prices generally run from around $70 for upper-level seats to $450 or more for the floor, pit and VIP packages, though what you actually pay depends on the city, the venue and how close to the stage you want to be. His shows lean heavily on a standing floor experience, so the rage pit and front general-admission sections tend to carry the steepest premiums.',
        1 => 'Because Travis Scott headlines arenas and, on bigger runs, stadiums, the spread between the cheapest seat and the most expensive package is wide. Stadium dates and festival-style productions usually push the top end higher than a standard arena night. Most of his major tours sell through Ticketmaster with dynamic pricing, so the figure you see shifts with demand rather than holding at a single fixed face value.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper level / 300s',
          'desc' => 'Seated views from the upper tiers, the most affordable way in. Expect roughly $70 to $130 depending on the market and whether it is an arena or a stadium.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl seated',
          'desc' => 'Side and end seating in the lower bowl with a clear view of the stage and production. Typically lands around $140 to $240.',
        ),
        2 => 
        array (
          'name' => 'General admission floor',
          'desc' => 'Standing room on the floor, the core of a Travis Scott show. Usually $200 to $320, with the rage pit area near the stage at the top of that band.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Premium packages bundling early entry, prime floor or pit access and merchandise. Commonly $350 to $450 or higher on stadium dates.',
        ),
      ),
      'why' => 'Travis Scott\'s arena and stadium tours run on Ticketmaster\'s dynamic pricing, which means prices move with live demand rather than sitting at a fixed face value. When a show sells quickly, the cost of remaining inventory climbs, and high-demand floor and pit sections in particular can rise well above their opening levels. The bands above are realistic guides for planning; the exact number depends on the date, the city and how early you buy.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are Travis Scott tickets?',
          'a' => 'Travis Scott tickets typically range from about $70 for upper-level seats to $450 or more for floor, pit and VIP packages. Arena nights tend to sit lower in that band, while stadium dates and premium general-admission floor sections push toward the top.',
        ),
        1 => 
        array (
          'q' => 'Why do Travis Scott ticket prices change so much?',
          'a' => 'His tours use Ticketmaster\'s dynamic pricing, so prices rise and fall with demand instead of holding at a fixed face value. When a date sells fast, the remaining tickets, especially floor and pit, get more expensive, which is why two seats in the same section can cost very different amounts.',
        ),
        2 => 
        array (
          'q' => 'How much is the rage pit at a Travis Scott concert?',
          'a' => 'The rage pit, the standing area closest to the stage, is usually among the priciest options, often $250 to $350 or more on arena dates and higher on stadium shows. It is general admission, so position within the pit comes down to how early you arrive.',
        ),
        3 => 
        array (
          'q' => 'Are Travis Scott VIP packages worth it?',
          'a' => 'Travis Scott VIP packages generally run $350 to $450 or higher and bundle perks such as early entry, premium floor or pit access and exclusive merchandise. They are worth it mainly if guaranteed proximity to the stage and a faster way in matter to you, since the show is built around the floor.',
        ),
        4 => 
        array (
          'q' => 'What is the cheapest way to see Travis Scott?',
          'a' => 'The cheapest seats are usually in the upper tiers, often starting around $70 to $90 on arena dates. Buying early, before dynamic pricing responds to demand, and staying flexible on which night you attend are the most reliable ways to keep the cost down.',
        ),
        5 => 
        array (
          'q' => 'Do Travis Scott tickets get cheaper closer to the show?',
          'a' => 'Sometimes, but not reliably. Prices on a high-demand Travis Scott date can stay firm or even climb as it approaches, because dynamic pricing tracks demand. Resale inventory occasionally softens for slower-selling shows, but for sold-out arena and stadium nights waiting is a gamble.',
        ),
        6 => 
        array (
          'q' => 'Is there a difference in price between arena and stadium shows?',
          'a' => 'Yes. Stadium dates generally carry a higher top end than arena nights because the production scale and demand are larger, so floor and VIP packages tend to cost more. Upper-level stadium seats, however, can sometimes start at a similar entry price to arena upper tiers.',
        ),
        7 => 
        array (
          'q' => 'Are Travis Scott floor tickets seated or standing?',
          'a' => 'Floor tickets at a Travis Scott show are almost always standing general admission, not seated. The floor is the heart of the concert, so spots are first come, first served, and the area nearest the stage is the rage pit.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'Travis Scott Tour',
      'intro' => 
      array (
        0 => 'Travis Scott tour dates usually centre on large arenas and, on his biggest runs, stadiums, with shows built around an elaborate stage production rather than a conventional support-and-headliner format. His touring tends to come in concentrated bursts tied to an album cycle, so active legs often cluster across North America, Europe and other major markets within a defined window.',
        1 => 'Specific cities and on-sale times vary from tour to tour, and Travis Scott has a track record of adding dates when demand is high. Tickets are sold primarily through Ticketmaster, frequently with registration-based presales for higher-demand markets. The live calendar on this page reflects the dates currently confirmed.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is Travis Scott touring in 2026?',
          'a' => 'Travis Scott\'s touring schedule depends on his current album and project cycle, and dates are added in concentrated legs rather than running year-round. Check the live listing on this page for the most up-to-date confirmed Travis Scott dates, since any newly announced shows appear there as they go on sale.',
        ),
        1 => 
        array (
          'q' => 'Does Travis Scott play stadiums or arenas?',
          'a' => 'Both. Travis Scott headlines large arenas on most tours and has scaled up to stadiums for his biggest runs, such as the Circus Maximus production. The venue type affects capacity, pricing and the scale of the stage, with stadium dates typically carrying a larger production.',
        ),
        2 => 
        array (
          'q' => 'How do I get presale tickets for Travis Scott?',
          'a' => 'Travis Scott presales typically run through Ticketmaster and often require advance registration for the show or market you want, sometimes via a Verified Fan-style process. Signing up early and being ready at the exact presale start time gives the best chance, as high-demand dates move quickly.',
        ),
        3 => 
        array (
          'q' => 'Does Travis Scott have a support act?',
          'a' => 'It varies by tour. Travis Scott sometimes brings guests or affiliated artists on the road, but his shows are built primarily around his own extended set and stage production rather than a traditional opener. Any confirmed support for a given date is listed with that show.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'Travis Scott\'s recent shows pull heavily from Utopia and Astroworld alongside earlier staples, delivered as a high-energy, near-continuous set rather than a song-by-song singalong. The setlist below reflects the kind of material that has anchored his recent live performances, giving a sense of what tends to feature rather than a fixed running order for any one night.',
      ),
      'songs' => 
      array (
        0 => 'HYAENA',
        1 => 'THANK GOD',
        2 => 'MODERN JAM',
        3 => 'FE!N',
        4 => 'I KNOW ?',
        5 => 'MELTDOWN',
        6 => 'Highest in the Room',
        7 => 'SICKO MODE',
        8 => 'goosebumps',
        9 => 'STARGAZING',
        10 => 'BUTTERFLY EFFECT',
        11 => 'Antidote',
        12 => '3500',
        13 => 'Mafia',
        14 => 'TELEKINESIS',
        15 => 'MY EYES',
        16 => 'No Bystanders',
        17 => '5% TINT',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Travis Scott runs his shows as a largely continuous set rather than a structured main-set-plus-encore, and the lineup changes from night to night. Songs are added, dropped or reordered depending on the city, the venue and the moment, so treat this as a guide to recurring staples rather than an exact running order.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does Travis Scott play live?',
          'a' => 'Travis Scott\'s live sets centre on hits like SICKO MODE, goosebumps, Highest in the Room and FE!N, mixed with Utopia tracks such as HYAENA, MELTDOWN and MODERN JAM. He typically runs them as a high-energy, near-continuous set rather than spacing them out with long breaks.',
        ),
        1 => 
        array (
          'q' => 'Does Travis Scott do an encore?',
          'a' => 'Not in the traditional sense. Travis Scott tends to perform a continuous, high-intensity set rather than leaving the stage and returning for a separate encore. Big closers like SICKO MODE or goosebumps often land late in the show as the climax instead.',
        ),
        2 => 
        array (
          'q' => 'Does Travis Scott play SICKO MODE at every show?',
          'a' => 'SICKO MODE is one of his most reliable inclusions and features in most recent Travis Scott shows, frequently as a peak moment. Setlists do change night to night, however, so no single song is guaranteed on any given date.',
        ),
        3 => 
        array (
          'q' => 'How long is a Travis Scott concert?',
          'a' => 'A Travis Scott headline set typically runs in the region of 75 to 100 minutes, though it varies by tour and venue. The pace is fast and largely continuous, packing a long run of songs into the set rather than stretching it with extended pauses.',
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
      'range_high' => 400,
      'currency' => 'USD',
      'intro' => 
      array (
        0 => 'SZA tickets generally run from around $60 for upper-level seats to $400 or more for floor positions and VIP packages, though what you actually pay turns on the city, the night of the week and how early you commit. Her headline tours play arenas and, increasingly, stadiums, both of which sit on Ticketmaster\'s dynamic pricing, so the listed figure shifts with demand rather than holding to a fixed face value.',
        1 => 'As a guide, the cheaper seats at an SZA show tend to land in the $60 to $110 range, mid-tier and lower-bowl seats sit somewhere in the middle, and the premium floor or VIP tiers carry the steepest prices. Resale can push numbers well above face on the busiest dates, so comparing the primary on-sale against the resale market is usually the sensible move before you buy.',
      ),
      'tiers' => 
      array (
        0 => 
        array (
          'name' => 'Upper tier / nosebleeds',
          'desc' => 'The highest sections of the arena or stadium, furthest from the stage but with a full view of the production. Typically $60 to $110, and the first tier to sell out for value-minded buyers.',
        ),
        1 => 
        array (
          'name' => 'Lower bowl / side seats',
          'desc' => 'Reserved seating around the lower and middle levels with closer sightlines than the upper tier. Usually $110 to $200 depending on the city and how square-on you are to the stage.',
        ),
        2 => 
        array (
          'name' => 'Floor / general admission',
          'desc' => 'Standing or seated floor positions near the stage, where SZA\'s set feels most immediate. Commonly $180 to $320, with the front of the pit at the top of that band.',
        ),
        3 => 
        array (
          'name' => 'VIP packages',
          'desc' => 'Bundled tickets that may include premium placement, early entry, merchandise or a lounge. These run from roughly $300 to $400 and up, varying by tour and what each package contains.',
        ),
      ),
      'why' => 'SZA\'s arena and stadium tours run on Ticketmaster\'s dynamic pricing, which means prices move with live demand instead of sitting at a single fixed face value. Popular nights and high-demand markets can see the same seat type listed higher than a quieter date, and prices can shift between the on-sale and the show. VIP and platinum-labelled seats are priced separately and tend to be the most expensive on the map. Because of this, the band quoted here is a realistic guide rather than a guaranteed figure, and the final total only settles once fees are added at checkout.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'How much are SZA tickets?',
          'a' => 'SZA tickets typically range from about $60 for upper-tier seats to $400 or more for floor spots and VIP packages. The exact price depends on the venue size, the city and how early you buy, since her tours use dynamic pricing that moves with demand.',
        ),
        1 => 
        array (
          'q' => 'Why are SZA tickets so expensive?',
          'a' => 'The higher prices come mostly from dynamic pricing and strong demand on her arena and stadium tours, where popular dates push floor and lower-bowl seats up. VIP and platinum seats are priced separately at the top of the range, and resale can add a further premium on sold-out nights.',
        ),
        2 => 
        array (
          'q' => 'What is the cheapest way to see SZA live?',
          'a' => 'The cheapest route is usually an upper-tier seat bought during the primary on-sale, which often starts around $60 before fees. Signing up for artist or venue presales and avoiding peak weekend dates can also help you land a lower price than the resale market.',
        ),
        3 => 
        array (
          'q' => 'Do SZA ticket prices change over time?',
          'a' => 'Yes. Because her tours use Ticketmaster\'s dynamic pricing, the same seat can cost more or less depending on how demand moves between the on-sale and the show. High-demand dates tend to climb, while quieter midweek shows sometimes hold lower prices.',
        ),
        4 => 
        array (
          'q' => 'How much are SZA VIP tickets?',
          'a' => 'SZA VIP packages generally start around $300 and can rise to $400 or more, depending on the tour and what each package bundles in. Inclusions vary but can cover premium seating, early entry, exclusive merchandise or lounge access.',
        ),
        5 => 
        array (
          'q' => 'Are SZA floor tickets worth it?',
          'a' => 'Floor tickets put you closest to the stage and give the most immersive view of SZA\'s production, which many fans feel justifies the higher cost. They usually fall in the $180 to $320 band, with the front rows at the top end, so it comes down to your budget and how close you want to be.',
        ),
        6 => 
        array (
          'q' => 'Do SZA tickets include fees?',
          'a' => 'The advertised price rarely includes everything; service and facility fees are usually added at checkout, so the final total runs higher than the headline number. Always check the full breakdown before paying so the band you budgeted for matches what you actually spend.',
        ),
        7 => 
        array (
          'q' => 'Is it cheaper to buy SZA tickets on resale?',
          'a' => 'Not usually for in-demand shows, where resale often sits above face value. Resale can occasionally beat the primary price for quieter dates or last-minute drops, so it is worth comparing the official on-sale against the resale market before deciding.',
        ),
      ),
    ),
    'tour' => 
    array (
      'tour_name' => 'SZA Live',
      'intro' => 
      array (
        0 => 'SZA tour dates tend to arrive in clusters around an album cycle, with North American legs leading and European, UK and occasionally Australian and Asian shows following. She has moved from theatres early in her career to full arena and stadium runs, and her live schedule generally blends headline tours with festival headline slots across the summer season.',
        1 => 'When a new SZA run is announced, tickets usually go through a mix of artist presales, venue or card-partner presales and a general on-sale, often via Ticketmaster in North America. Demand for the bigger dates is high, so registering for presales ahead of time and being ready at the on-sale is the most reliable way to secure seats near face value.',
      ),
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'Is SZA touring in 2026?',
          'a' => 'SZA tours in cycles tied to her album releases, and live dates are confirmed and updated by the system rather than fixed here. Check the live listings on this page for the current schedule, since her runs typically mix headline arena and stadium shows with festival appearances.',
        ),
        1 => 
        array (
          'q' => 'How do I get SZA presale tickets?',
          'a' => 'Presale access for SZA usually comes through artist sign-ups, fan-club or app registrations and card-partner or venue presales. Registering ahead of the announced on-sale and having your presale code ready gives you the best shot at seats before the general public sale opens.',
        ),
        2 => 
        array (
          'q' => 'Where does SZA usually tour?',
          'a' => 'SZA\'s headline tours focus on North America, with regular legs across Europe and the UK and occasional dates in Australia and Asia. She also takes major festival headline slots, so summer often brings festival sets alongside or instead of standalone shows in some regions.',
        ),
        3 => 
        array (
          'q' => 'What venues does SZA play?',
          'a' => 'On recent tours SZA has played large arenas and, on her biggest runs, stadiums, having grown from the theatre and club shows of her early career. The venue size affects pricing and layout, so floor, bowl and VIP options differ between an arena date and a stadium night.',
        ),
      ),
    ),
    'setlist' => 
    array (
      'intro' => 
      array (
        0 => 'SZA\'s recent shows draw heavily on her two albums, Ctrl and SOS, weaving the slow-burning R&B of her breakout with the genre-crossing range of her later work. A typical setlist moves between intimate, vocal-led moments and bigger, beat-driven songs, and she tends to give the fan favourites room to breathe rather than rushing through a checklist.',
      ),
      'songs' => 
      array (
        0 => 'Love Galore',
        1 => 'Broken Clocks',
        2 => 'The Weekend',
        3 => 'Drew Barrymore',
        4 => 'Supermodel',
        5 => 'Prom',
        6 => 'Garden (Say It Like Dat)',
        7 => 'Doves in the Wind',
        8 => 'Kill Bill',
        9 => 'Snooze',
        10 => 'Good Days',
        11 => 'Nobody Gets Me',
        12 => 'Low',
        13 => 'Shirt',
        14 => 'I Hate U',
        15 => 'Smoking on My Ex Pack',
        16 => 'Saturn',
        17 => 'Open Arms',
      ),
      'encore' => 
      array (
      ),
      'note' => 'Setlists vary night to night, so the songs above reflect staples from recent SZA shows rather than a fixed running order. She rotates album cuts and occasional surprises between dates, and the exact selection on any given night can differ from this list.',
      'faqs' => 
      array (
        0 => 
        array (
          'q' => 'What songs does SZA play live?',
          'a' => 'SZA\'s live sets lean on her Ctrl and SOS albums, with staples such as Kill Bill, Snooze, Good Days, Love Galore, The Weekend and Broken Clocks appearing regularly. The exact selection changes from night to night, so the running order is never identical between shows.',
        ),
        1 => 
        array (
          'q' => 'Does SZA do an encore?',
          'a' => 'SZA\'s shows often run as a continuous, flowing set rather than a fixed encore break, with the biggest songs frequently saved for the closing stretch. Whether a formal encore appears can vary by tour and venue, so it is not guaranteed on every date.',
        ),
        2 => 
        array (
          'q' => 'How long is an SZA concert?',
          'a' => 'An SZA headline set generally runs somewhere around 90 minutes to two hours, depending on the tour and the number of songs. Support acts add to the overall evening, so plan for a longer night out than the headline set alone.',
        ),
        3 => 
        array (
          'q' => 'Does SZA play Kill Bill live?',
          'a' => 'Yes. Kill Bill, one of her biggest hits from SOS, has been a regular fixture in recent SZA setlists and often lands as a high point of the show. As with any song, its inclusion can vary by date, but it has been a consistent staple.',
        ),
      ),
    ),
  ),
);
