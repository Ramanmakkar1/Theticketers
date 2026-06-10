<?php
declare(strict_types=1);

function dispatch(HelloTicketsClient $client, array $config, array $dubaiContent = [], array $destinationsContent = []): void
{
    $rawPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($rawPath !== '/' && substr($rawPath, -1) === '/') {
        $qs = (string) ($_SERVER['QUERY_STRING'] ?? '');
        header('Location: ' . rtrim($rawPath, '/') . ($qs !== '' ? '?' . $qs : ''), true, 301);
        return;
    }

    $path = current_path();

    if ($path === '/') {
        render_home_page($client, $config, $destinationsContent);
        return;
    }

    if ($path === '/dubai') {
        render_dubai_hub($client, $config, $dubaiContent);
        return;
    }

    if ($path === '/abu-dhabi') {
        render_abu_dhabi_hub($client, $config, $dubaiContent);
        return;
    }

    if (preg_match('#^/dubai/([a-z0-9-]+)/([a-z0-9-]+)$#', $path, $match)) {
        render_dubai_attraction($client, $config, $dubaiContent, $match[2]);
        return;
    }

    if (preg_match('#^/dubai/([a-z0-9-]+)$#', $path, $match)) {
        render_dubai_category($client, $config, $dubaiContent, $match[1]);
        return;
    }

    if ($path === '/events') {
        render_events_page($client, $config, active_city_id($config));
        return;
    }

    if ($path === '/attractions') {
        render_activities_page($client, $config, active_city_id($config));
        return;
    }

    if ($path === '/artists') {
        render_artists_page($client, $config);
        return;
    }

    if (preg_match('#^/artist/([^/]+)$#', $path, $match)) {
        render_artist_detail_page($client, $config, id_from_slug($match[1]));
        return;
    }

    if ($path === '/search') {
        render_search_page($client, $config);
        return;
    }

    if ($path === '/about') {
        render_static_page($config, 'About ' . $config['site_name'], $config['site_name'] . ' is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations worldwide.', '/about', function () use ($config): void {
            ?>
            <p><?= e($config['site_name']) ?> is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations across the United States, Canada, the United Kingdom, Italy, Spain and France.</p>
            <p>We list concerts, theatre, sports, tours and attractions with live prices and availability supplied by our official ticketing partner, HelloTickets. When you choose a ticket, you complete your booking securely on our partner's site &mdash; they handle payment, ticket delivery and customer support.</p>
            <p><?= e($config['site_name']) ?> is operated by Town Media Labs. Questions? See our <a href="/contact">Contact</a> page.</p>
            <?php
        });
        return;
    }

    if ($path === '/contact') {
        render_static_page($config, 'Contact Us', 'How to reach the ' . $config['site_name'] . ' team for partnerships, listings, feedback and corrections.', '/contact', function () use ($config): void {
            ?>
            <p>The fastest way to reach us is email: <a href="mailto:townmedialabs@gmail.com"><strong>townmedialabs@gmail.com</strong></a></p>
            <ul>
                <li>Booking, payment or refund questions: these are handled by our ticketing partner &mdash; use the support links in your booking confirmation email.</li>
                <li>Partnerships and listings: email us with the subject "Partner with <?= e($config['site_name']) ?>".</li>
                <li>Site feedback or corrections: email us and include the page link.</li>
            </ul>
            <?php
        });
        return;
    }

    if ($path === '/how-we-make-money') {
        render_static_page($config, 'How We Make Money', $config['site_name'] . ' is free to use. Here is how affiliate commissions fund the site without changing the price you pay.', '/how-we-make-money', function () use ($config): void {
            ?>
            <p><?= e($config['site_name']) ?> is free to use. When you buy a ticket through a link on our site, our ticketing partner may pay us a commission. This never increases the price you pay &mdash; prices and availability come directly from the partner.</p>
            <p>We do not process payments, hold ticket inventory, or charge any fees. Commissions are how we fund the site.</p>
            <?php
        });
        return;
    }

    if ($path === '/go') {
        handle_outbound_redirect($config);
        return;
    }

    if ($path === '/robots.txt') {
        render_robots($config);
        return;
    }

    if ($path === '/sitemap.xml') {
        render_sitemap($client, $config, $destinationsContent);
        return;
    }

    if (preg_match('#^/city/([^/]+)$#', $path, $match)) {
        render_city_page($client, $config, id_from_slug($match[1]), $destinationsContent);
        return;
    }

    if (preg_match('#^/category/([^/]+)$#', $path, $match)) {
        render_category_page($client, $config, id_from_slug($match[1]));
        return;
    }

    if (preg_match('#^/event/([^/]+)$#', $path, $match)) {
        render_event_detail_page($client, $config, id_from_slug($match[1]));
        return;
    }

    if (preg_match('#^/activity/([^/]+)$#', $path, $match)) {
        render_activity_detail_page($client, $config, id_from_slug($match[1]));
        return;
    }

    // Destination SEO hubs — /{country}/{city} and /{country}, guarded by the
    // content pack so they only match known markets (never /events, /search, …).
    if (preg_match('#^/([a-z0-9-]+)/([a-z0-9-]+)$#', $path, $match)
        && destination_city_in_country($destinationsContent, $match[1], $match[2])) {
        render_city_hub($client, $config, $destinationsContent, $match[1], $match[2]);
        return;
    }

    if (preg_match('#^/([a-z0-9-]+)$#', $path, $match)
        && destination_country_exists($destinationsContent, $match[1])) {
        render_country_hub($client, $config, $destinationsContent, $match[1]);
        return;
    }

    render_error_page($config, 404, 'Page not found', 'The ticket page you are looking for may have moved.');
}

function render_layout(array $config, array $meta, callable $content, ?array $schema = null): void
{
    $title = $meta['title'] ?? $config['site_name'];
    $description = $meta['description'] ?? $config['site_tagline'];
    $canonical = $meta['canonical'] ?? absolute_url($config, current_path());
    $bodyClass = $meta['body_class'] ?? '';
    $q = search_query();

    header('Content-Type: text/html; charset=utf-8');
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <?php if (!empty($meta['robots'])): ?>
    <meta name="robots" content="<?= e($meta['robots']) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($meta['image'] ?? $config['fallback_images']['hero']) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/styles.css">
    <?php if ($schema !== null): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
    <header class="site-header">
        <a class="brand" href="/" aria-label="<?= e($config['site_name']) ?> home">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="display: block;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path><line x1="9" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><line x1="15" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line></svg>
            <span>Ticked<em>Bus</em></span>
        </a>
        <div class="header-search">
            <form action="/search" method="get">
                <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search for Events, Attractions, Concerts, Theatre and Tours">
                <button type="submit" aria-label="Search">Search</button>
            </form>
        </div>
        <div class="header-actions">
            <?php $activeCity = city_for_id(active_city_id($config), $config); ?>
            <div class="city-picker" data-city-picker>
                <button class="header-city" type="button" data-city-toggle aria-haspopup="true"><?= e($activeCity['name']) ?></button>
                <div class="city-menu" data-city-menu>
                    <button class="city-detect" type="button" data-city-detect>Detect my location</button>
                    <?php foreach ($config['market_cities'] as $menuCity): ?>
                        <?php if (empty($menuCity['featured'])) { continue; } ?>
                        <a href="<?= e(city_path($menuCity)) ?>" data-city-id="<?= e($menuCity['id']) ?>"><?= e($menuCity['name']) ?><span><?= e($menuCity['country_code']) ?></span></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a class="header-cta" href="/attractions">Get Tickets</a>
            <button class="nav-toggle" type="button" data-nav-toggle aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <div class="site-subnav">
        <div class="container">
            <nav class="site-nav" data-nav>
                <a href="/events">Events</a>
                <a href="/attractions">Attractions</a>
                <a href="/artists">Artists</a>
                <a href="/dubai">Dubai</a>
                <a href="/abu-dhabi">Abu Dhabi</a>
                <?php $navLabels = ['usa' => 'USA', 'canada' => 'Canada', 'uk' => 'UK', 'italy' => 'Italy', 'spain' => 'Spain', 'france' => 'France'];
                foreach (($config['markets'] ?? []) as $mSlug => $market): ?>
                    <a href="/<?= e($mSlug) ?>"><?= e($navLabels[$mSlug] ?? $market['name']) ?></a>
                <?php endforeach; ?>
                <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                <a href="<?= e(category_path(['id' => 1, 'name' => 'Sports'])) ?>">Sports</a>
            </nav>
            <div class="subnav-side">
                <?php foreach (array_slice($config['market_cities'], 0, 3) as $navCity): ?>
                    <a href="<?= e(city_path($navCity)) ?>"><?= e($navCity['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <main>
        <?php $content(); ?>
    </main>
    <footer class="site-footer">
        <div class="footer-partner">
            <div class="container">
                <p>Got an event, activity or experience? <strong>Partner with us &amp; get listed on <?= e($config['site_name']) ?></strong></p>
                <a class="footer-partner-btn" href="/contact">Contact Us</a>
            </div>
        </div>
        <div class="container">
            <div class="footer-care">
                <div>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                    24/7 Partner Support
                </div>
                <div>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path><line x1="13" y1="5" x2="13" y2="7"></line><line x1="13" y1="11" x2="13" y2="13"></line><line x1="13" y1="17" x2="13" y2="19"></line></svg>
                    Live Prices &amp; Availability
                </div>
                <div>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                    Secure Partner Checkout
                </div>
            </div>
            <div class="footer-cols">
                <div>
                    <h4>Destinations</h4>
                    <a href="/dubai">Dubai</a>
                    <a href="/abu-dhabi">Abu Dhabi</a>
                    <?php foreach (($config['markets'] ?? []) as $mSlug => $market): ?>
                        <a href="/<?= e($mSlug) ?>"><?= e($market['name']) ?></a>
                    <?php endforeach; ?>
                </div>
                <div>
                    <h4>Categories</h4>
                    <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                    <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                    <a href="<?= e(category_path(['id' => 1, 'name' => 'Sports'])) ?>">Sports</a>
                    <a href="/attractions">Attractions &amp; Tours</a>
                </div>
                <div>
                    <h4>Discover</h4>
                    <a href="/events">All Events</a>
                    <a href="/events?date=weekend">This Weekend</a>
                    <a href="/search">Search Tickets</a>
                    <a href="/about">About Us</a>
                    <a href="/contact">Contact</a>
                    <a href="/how-we-make-money">How We Make Money</a>
                    <a href="/sitemap.xml">Sitemap</a>
                </div>
            </div>
            <div class="footer-bottom">
                <strong>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 22px; height: 22px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></svg>
                    Ticked<em style="font-style: normal; color: var(--red);">Bus</em>
                </strong>
                <p>Your guide to events, attractions and experiences in Dubai and top destinations worldwide. Prices and availability are live from our ticket partner, and checkout is completed securely on their site. We may earn a commission on bookings at no extra cost to you. &copy; <?= e(date('Y')) ?> <?= e($config['site_name']) ?>. All events, images and trademarks belong to their respective owners.</p>
            </div>
        </div>
    </footer>
    <div class="city-modal" data-city-modal data-default-city="<?= e($config['default_city_id']) ?>" hidden>
        <div class="city-modal-box">
            <h3>Where do you want tickets?</h3>
            <p>Pick your city to see local events, attractions and live prices.</p>
            <button class="city-detect-big" type="button" data-city-detect>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Detect my location
            </button>
            <div class="city-modal-grid">
                <?php foreach ($config['market_cities'] as $modalCity): ?>
                    <?php if (empty($modalCity['featured'])) { continue; } ?>
                    <a href="<?= e(city_path($modalCity)) ?>" data-city-id="<?= e($modalCity['id']) ?>"><strong><?= e($modalCity['name']) ?></strong><span><?= e($modalCity['country']) ?></span></a>
                <?php endforeach; ?>
            </div>
            <button class="city-modal-close" type="button" data-city-close>Maybe later</button>
        </div>
    </div>
    <script src="/assets/app.js" defer></script>
</body>
</html>
    <?php
}

function render_static_page(array $config, string $title, string $desc, string $path, callable $body): void
{
    render_layout($config, [
        'title' => $title . ' | ' . $config['site_name'],
        'description' => $desc,
        'canonical' => absolute_url($config, $path),
    ], function () use ($title, $body): void {
        ?>
        <section class="section-band">
            <div class="container prose">
                <h1><?= e($title) ?></h1>
                <?php $body(); ?>
            </div>
        </section>
        <?php
    });
}

function render_home_page(HelloTicketsClient $client, array $config, array $destinationsContent = []): void
{
    $cityId = active_city_id($config);
    $homeCity = city_for_id($cityId, $config);
    $dateParams = date_params(null);

    $eventsData = api_result(static fn() => $client->performances(array_merge([
        'limit' => 12,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], $dateParams)), ['performances' => []]);

    $activitiesData = api_result(static fn() => $client->activities([
        'limit' => 12,
        'page' => 1,
        'city_id' => $cityId,
    ]), ['activities' => []]);

    $globalEventsData = count($eventsData['performances'] ?? []) < 6
        ? api_result(static fn() => $client->performances(array_merge([
            'limit' => 12,
            'page' => 1,
            'is_sellable' => 'true',
        ], date_params(null))), ['performances' => []])
        : ['performances' => []];

    $performers = api_result(static fn() => $client->performers([
        'limit' => 12,
        'page' => 1,
    ]), ['performers' => []])['performers'] ?? [];

    $activities = $activitiesData['activities'] ?? [];
    $events = $eventsData['performances'] ?? [];
    $globalEvents = $globalEventsData['performances'] ?? [];

    // Never repeat a performance across the local and worldwide rails.
    $seenIds = array_map(static fn($performance) => (int) ($performance['id'] ?? 0), $events);
    $globalEvents = array_values(array_filter(
        $globalEvents,
        static fn($performance): bool => !in_array((int) ($performance['id'] ?? 0), $seenIds, true)
    ));

    render_layout($config, [
        'title' => $homeCity['name'] . ' Events, Attractions & Tickets | ' . $config['site_name'],
        'description' => 'Find ' . $homeCity['name'] . ' attraction tickets, concerts, theatre, sports and experiences with live prices from HelloTickets.',
        'canonical' => absolute_url($config, '/'),
        'body_class' => 'home-page',
    ], function () use ($config, $activities, $events, $globalEvents, $performers, $homeCity, $destinationsContent): void {
        ?>
        <h1 class="visually-hidden"><?= e($homeCity['name']) ?> Events, Attractions &amp; Tickets</h1>
        <?php
        $slides = [
            ['image' => $config['fallback_images']['hero'], 'tag' => 'Featured', 'title' => 'Dubai events, attractions and experiences', 'text' => 'Live prices and availability, with secure partner checkout.', 'href' => '/attractions', 'cta' => 'Book Now'],
            ['image' => $config['fallback_images']['burj'], 'tag' => 'Top Attraction', 'title' => 'Burj Khalifa: At the Top', 'text' => 'Skip the queue with instant e-tickets to the world\'s tallest tower.', 'href' => '/attractions?q=Burj%20Khalifa', 'cta' => 'Get Tickets'],
            ['image' => $config['fallback_images']['desert'], 'tag' => 'Experiences', 'title' => 'Desert safaris and dune adventures', 'text' => 'Sunset drives, camel rides and Bedouin dinners under the stars.', 'href' => '/attractions?q=Desert%20Safari', 'cta' => 'Explore'],
            ['image' => $config['fallback_images']['Concerts'], 'tag' => 'Live Events', 'title' => 'Concerts, theatre and sport in Dubai', 'text' => 'See what\'s playing this week across the city\'s biggest venues.', 'href' => '/events', 'cta' => 'See Events'],
        ];
        ?>
        <section class="hero">
            <div class="container">
                <div class="carousel" data-carousel>
                    <div class="carousel-track" data-carousel-track>
                        <?php foreach ($slides as $slide): ?>
                            <div class="carousel-slide" style="background-image: url('<?= e($slide['image']) ?>')">
                                <div class="carousel-caption">
                                    <span class="slide-tag"><?= e($slide['tag']) ?></span>
                                    <h2><?= e($slide['title']) ?></h2>
                                    <p><?= e($slide['text']) ?></p>
                                    <a class="slide-btn" href="<?= e($slide['href']) ?>"><?= e($slide['cta']) ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn prev" type="button" data-carousel-prev aria-label="Previous banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button class="carousel-btn next" type="button" data-carousel-next aria-label="Next banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <div class="carousel-dots" data-carousel-dots></div>
                </div>
            </div>
        </section>

        <section class="section-band compact">
            <div class="container">
                <div class="filter-row">
                    <a href="/events?date=today">Today</a>
                    <a href="/events?date=tomorrow">Tomorrow</a>
                    <a href="/events?date=weekend">This Weekend</a>
                    <a href="/events?date=month">This Month</a>
                </div>
            </div>
        </section>

        <?php if ($activities !== []): ?>
            <?php render_card_section('Recommended in ' . $homeCity['name'], '/attractions', $activities, 'activity', $config); ?>
        <?php endif; ?>

        <?php if ($events !== []): ?>
            <?php render_card_section('Live Events in ' . $homeCity['name'], '/events', $events, 'event', $config); ?>
        <?php endif; ?>

        <?php render_artists_rail($performers); ?>

        <?php render_live_events_band(); ?>

        <?php if (count($globalEvents) >= 4): ?>
            <?php render_card_section('Popular Events Worldwide', '/events', $globalEvents, 'event', $config); ?>
        <?php endif; ?>

        <?php render_promo_banner($config); ?>

        <?php $homeCountries = $destinationsContent['countries'] ?? []; ?>
        <?php if ($homeCountries !== []): ?>
            <section class="section-band">
                <div class="container">
                    <div class="section-heading">
                        <h2>Explore by Destination</h2>
                        <a href="/attractions">See All</a>
                    </div>
                    <div class="home-destinations__grid">
                        <?php foreach ($homeCountries as $hcSlug => $hc): ?>
                            <a class="home-destinations__card" href="/<?= e($hcSlug) ?>" style="background-image: linear-gradient(180deg, rgba(0,0,0,.1) 0%, rgba(0,0,0,.7) 100%), url('<?= e($hc['hero_image'] ?? $config['fallback_images']['hero']) ?>')"><?= e($hc['name'] ?? $hcSlug) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="section-band">
            <div class="container split-section">
                <div>
                    <p class="eyebrow">Browse by destination</p>
                    <h2>Popular Ticket Cities</h2>
                </div>
                <div class="city-grid">
                    <?php foreach (array_slice($config['market_cities'], 0, 8) as $city): ?>
                        <a href="<?= e(city_path($city)) ?>">
                            <strong><?= e($city['name']) ?></strong>
                            <span><?= e($city['country']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }, website_schema($config));
}

function render_events_page(HelloTicketsClient $client, array $config, int $cityId, ?array $category = null): void
{
    $city = city_for_id($cityId, $config);
    $page = page_number();
    $date = (string) ($_GET['date'] ?? 'upcoming');
    $query = search_query();
    $params = array_merge([
        'limit' => 24,
        'page' => $page,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params($date));

    if ($query !== '') {
        $params['performance'] = $query;
    }

    if ($category !== null) {
        $params['category_id'] = (int) $category['id'];
    }

    $data = api_result(static fn() => $client->performances($params), ['performances' => [], 'total_count' => 0]);
    $items = $data['performances'] ?? [];

    // Most market cities have very little sellable event inventory. When a city is thin
    // (and the user isn't searching/paging), blend in global sellable events so the page
    // is useful rather than near-empty. Local events stay first.
    if (count($items) < 6 && $query === '' && $page === 1) {
        $globalParams = $params;
        unset($globalParams['city_id']);
        $global = api_result(static fn() => $client->performances($globalParams), ['performances' => []])['performances'] ?? [];
        $seen = [];
        foreach ($items as $existing) {
            $seen[(int) ($existing['id'] ?? 0)] = true;
        }
        foreach ($global as $candidate) {
            $cid = (int) ($candidate['id'] ?? 0);
            if ($cid > 0 && !isset($seen[$cid])) {
                $items[] = $candidate;
                $seen[$cid] = true;
            }
            if (count($items) >= 24) {
                break;
            }
        }
        $data['total_count'] = count($items);
    }

    $categoryLabel = $category ? $category['name'] . ' ' : '';
    $title = trim($categoryLabel . 'events in ' . $city['name']);

    render_listing_layout($config, [
        'title' => ucwords($title) . ' | ' . $config['site_name'],
        'description' => 'Browse live ' . strtolower($categoryLabel) . 'event tickets in ' . $city['name'] . ' with dates, venues and prices.',
        'canonical' => absolute_url($config, current_path(), array_filter([
            'date' => $date !== 'upcoming' ? $date : null,
            'page' => $page > 1 ? $page : null,
        ])),
    ], $title, $items, 'event', $config, $data, [
        'city_id' => $cityId,
        'date' => $date,
        'q' => $query,
        'category' => $category,
    ]);
}

function render_activities_page(HelloTicketsClient $client, array $config, int $cityId, ?string $categoryQuery = null, ?string $categoryLabel = null): void
{
    $city = city_for_id($cityId, $config);
    $page = page_number();
    $query = $categoryQuery !== null ? $categoryQuery : search_query();
    $data = api_result(static fn() => $client->activities(array_filter([
        'limit' => 24,
        'page' => $page,
        'city_id' => $cityId,
        'query' => $query,
    ], static fn($v) => $v !== '')), ['activities' => [], 'total_count' => 0]);

    $items = $data['activities'] ?? [];
    if ($categoryLabel !== null) {
        $title = $categoryLabel . ' in ' . $city['name'];
    } else {
        $title = $query !== '' ? $query . ' tickets in ' . $city['name'] : 'Attractions and experiences in ' . $city['name'];
    }

    render_listing_layout($config, [
        'title' => ucwords($title) . ' | ' . $config['site_name'],
        'description' => 'Compare ' . $city['name'] . ' attractions, tours and experiences with current prices and partner checkout.',
        'canonical' => absolute_url($config, current_path(), array_filter(['page' => $page > 1 ? $page : null])),
    ], $title, $items, 'activity', $config, $data, [
        'city_id' => $cityId,
        'q' => search_query(),
    ]);
}

function render_listing_layout(array $config, array $meta, string $heading, array $items, string $type, array $configAgain, array $data, array $filters): void
{
    render_layout($config, $meta, function () use ($heading, $items, $type, $configAgain, $data, $filters): void {
        $total = (int) ($data['total_count'] ?? count($items));
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= $type === 'event' ? 'Live inventory' : 'Experiences' ?></p>
                <h1><?= e($heading) ?></h1>
                <form class="listing-toolbar" action="<?= $type === 'event' ? '/events' : '/attractions' ?>" method="get">
                    <input type="search" name="q" value="<?= e((string) ($filters['q'] ?? '')) ?>" placeholder="<?= $type === 'event' ? 'Search performer or event' : 'Search attraction or tour' ?>">
                    <?php if ($type === 'event'): ?>
                        <select name="date" aria-label="Date">
                            <?php foreach (['upcoming' => 'Upcoming', 'month' => 'This month', 'today' => 'Today', 'tomorrow' => 'Tomorrow', 'weekend' => 'This weekend'] as $key => $label): ?>
                                <option value="<?= e($key) ?>" <?= ($filters['date'] ?? 'month') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <button type="submit">Search</button>
                </form>
                <div class="result-count"><?= e(number_format($total)) ?> results</div>
            </div>
        </section>

        <section class="section-band">
            <div class="container">
                <?php if ($items === []): ?>
                    <?php $emptyCity = city_for_id((int) ($filters['city_id'] ?? 0), $configAgain); ?>
                    <div class="empty-state">
                        <h2>No tickets found</h2>
                        <p>Try a broader search or browse all <?= e($emptyCity['name']) ?> listings.</p>
                        <a class="button-link" href="<?= $type === 'event' ? '/events' : '/attractions' ?>">See All</a>
                    </div>
                <?php else: ?>
                    <div class="card-grid">
                        <?php foreach ($items as $item): ?>
                            <?= $type === 'event' ? event_card($item, $configAgain) : activity_card($item, $configAgain) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php render_pagination($data); ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, item_list_schema($config, $items, $type));
}

function render_search_page(HelloTicketsClient $client, array $config): void
{
    $query = search_query();
    $type = (string) ($_GET['type'] ?? 'all');

    if ($type === 'events') {
        header('Location: ' . route_url('/events', ['q' => $query]), true, 302);
        return;
    }

    if ($type === 'attractions') {
        header('Location: ' . route_url('/attractions', ['q' => $query]), true, 302);
        return;
    }

    $cityId = active_city_id($config);
    $events = $query === '' ? [] : (api_result(static fn() => $client->performances(array_merge([
        'limit' => 8,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
        'performance' => $query,
    ], date_params(null))), ['performances' => []])['performances'] ?? []);

    $activities = $query === '' ? [] : (api_result(static fn() => $client->activities([
        'limit' => 8,
        'page' => 1,
        'city_id' => $cityId,
        'query' => $query,
    ]), ['activities' => []])['activities'] ?? []);

    $searchCity = city_for_id(active_city_id($config), $config);
    $cityName = (string) $searchCity['name'];

    render_layout($config, [
        'title' => ($query !== '' ? 'Search tickets for ' . $query : 'Search Tickets in ' . $cityName) . ' | ' . $config['site_name'],
        'description' => 'Search ' . $cityName . ' events, attractions and experiences.',
        'canonical' => absolute_url($config, '/search'),
        'robots' => 'noindex, follow',
    ], function () use ($query, $events, $activities, $config, $cityName): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Search</p>
                <h1><?= $query !== '' ? 'Tickets for "' . e($query) . '"' : 'Search Tickets in ' . e($cityName) ?></h1>
                <form class="listing-toolbar" action="/search" method="get">
                    <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search <?= e($cityName) ?> tickets">
                    <select name="type" aria-label="Search type">
                        <option value="all">All tickets</option>
                        <option value="events">Events</option>
                        <option value="attractions">Attractions</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div>
        </section>
        <?php if ($events !== []): ?>
            <?php render_card_section('Events', route_url('/events', ['q' => $query]), $events, 'event', $config); ?>
        <?php endif; ?>
        <?php if ($activities !== []): ?>
            <?php render_card_section('Attractions', route_url('/attractions', ['q' => $query]), $activities, 'activity', $config); ?>
        <?php endif; ?>
        <?php if ($query === '' || ($events === [] && $activities === [])): ?>
            <section class="section-band">
                <div class="container">
                    <div class="empty-state">
                        <h2>No matches yet</h2>
                        <p>Browse <?= e($cityName) ?> attractions to see current inventory.</p>
                        <a class="button-link" href="/attractions">View attractions</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php
    });
}

function render_city_page(HelloTicketsClient $client, array $config, int $cityId, array $destinationsContent = []): void
{
    $city = city_for_id($cityId, $config);
    $guidePath = destination_hub_path_for_city($destinationsContent, $cityId);

    // Only known market cities get a page — unknown ids/slugs 404 instead of
    // rendering a thin, indexable soft-404 of fallback content.
    $isKnownCity = false;
    foreach ($config['market_cities'] as $marketCity) {
        if ((int) $marketCity['id'] === $cityId) {
            $isKnownCity = true;
            break;
        }
    }
    if (!$isKnownCity) {
        render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
        return;
    }
    setcookie('tb_city', (string) $cityId, [
        'expires' => time() + 31536000,
        'path' => '/',
        'samesite' => 'Lax',
    ]);
    $eventsData = api_result(static fn() => $client->performances(array_merge([
        'limit' => 12,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params(null))), ['performances' => []]);
    $activitiesData = api_result(static fn() => $client->activities([
        'limit' => 12,
        'page' => 1,
        'city_id' => $cityId,
    ]), ['activities' => []]);

    render_layout($config, [
        'title' => $city['name'] . ' Tickets, Events & Attractions | ' . $config['site_name'],
        'description' => 'Browse current tickets for ' . $city['name'] . ', including attractions, tours, concerts, theatre and sports.',
        // When an editorial /{country}/{city} hub exists, point the canonical at it so
        // the thin listing page doesn't cannibalise the rich hub.
        'canonical' => absolute_url($config, $guidePath ?? city_path($city)),
    ], function () use ($city, $eventsData, $activitiesData, $config, $guidePath): void {
        ?>
        <section class="listing-hero city-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['country'] ?: 'Destination') ?></p>
                <h1><?= e($city['name']) ?> tickets, events and attractions</h1>
                <div class="filter-row inverse">
                    <a href="/events?date=today">Today</a>
                    <a href="/events?date=weekend">This Weekend</a>
                    <a href="/attractions">Attractions</a>
                    <a href="/events">Events</a>
                </div>
                <?php if ($guidePath !== null): ?>
                    <p class="city-guide-link"><a href="<?= e($guidePath) ?>">Read the full <?= e($city['name']) ?> guide &rarr;</a></p>
                <?php endif; ?>
            </div>
        </section>
        <?php render_card_section('Events in ' . $city['name'], '/events', $eventsData['performances'] ?? [], 'event', $config); ?>
        <?php render_card_section('Attractions in ' . $city['name'], '/attractions', $activitiesData['activities'] ?? [], 'activity', $config); ?>
        <?php
    });
}

function render_category_page(HelloTicketsClient $client, array $config, int $categoryId): void
{
    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    $category = null;
    foreach ($categories as $candidate) {
        if ((int) $candidate['id'] === $categoryId) {
            $category = $candidate;
            break;
        }
    }

    if ($category === null) {
        render_error_page($config, 404, 'Category not found', 'This ticket category is not available.');
        return;
    }

    if (in_array($categoryId, [1, 2, 3], true)) {
        render_events_page($client, $config, active_city_id($config), $category);
        return;
    }

    // The activities API has no category filter — search by a representative keyword
    // (config map), falling back to all city activities so the page is never empty.
    $keyword = $config['activity_category_queries'][$categoryId] ?? '';
    render_activities_page($client, $config, active_city_id($config), $keyword, (string) $category['name']);
}

function render_event_detail_page(HelloTicketsClient $client, array $config, int $performanceId): void
{
    $performance = api_result(static fn() => $client->performance($performanceId));
    if ($performance === [] || empty($performance['id'])) {
        render_error_page($config, 404, 'Event not found', 'This event is not available anymore.');
        return;
    }

    $cityName = $performance['venue']['city'] ?? $config['default_city_name'];
    $categoryId = (int) ($performance['category']['id'] ?? 0);
    $related = api_result(static fn() => $client->performances(array_merge([
        'limit' => 8,
        'page' => 1,
        'is_sellable' => 'true',
        'category_id' => $categoryId ?: null,
    ], date_params(null))), ['performances' => []])['performances'] ?? [];

    $localDate = (string) ($performance['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Events', 'url' => absolute_url($config, '/events')],
        ['name' => (string) ($performance['name'] ?? 'Event'), 'url' => absolute_url($config, event_path($performance))],
    ];

    render_layout($config, [
        'title' => $performance['name'] . ' Tickets | ' . $config['site_name'],
        'description' => 'See dates, venue and ticket prices for ' . $performance['name'] . ' in ' . $cityName . '.',
        'canonical' => absolute_url($config, event_path($performance)),
        'image' => image_from_item($performance, 'event', $config),
        'robots' => $isPast ? 'noindex, follow' : null,
    ], function () use ($performance, $related, $config, $breadcrumbs): void {
        $image = image_from_item($performance, 'event', $config);
        $price = $performance['price_range']['min_price'] ?? 0;
        $currency = $performance['price_range']['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <div class="detail-header">
                    <p class="eyebrow"><?= e($performance['category']['name'] ?? 'Event') ?></p>
                    <h1><?= e($performance['name']) ?></h1>
                    <div class="detail-facts">
                        <span><?= e($performance['venue']['city'] ?? 'Dubai') ?></span>
                        <span><?= e($performance['venue']['name'] ?? 'Venue TBA') ?></span>
                    </div>
                </div>

                <div class="detail-gallery" style="background-image: url('<?= e($image) ?>')"></div>

                <div class="detail-grid">
                    <div class="detail-content">
                        <h2>Event details</h2>
                        <dl class="detail-list">
                            <div><dt>Date</dt><dd><?= e(format_date_time($performance['start_date'] ?? [])) ?></dd></div>
                            <div><dt>Venue</dt><dd><?= e($performance['venue']['name'] ?? 'Venue TBA') ?></dd></div>
                            <div><dt>Address</dt><dd><?= e(trim(($performance['venue']['address'] ?? '') . ', ' . ($performance['venue']['city'] ?? ''))) ?></dd></div>
                            <div><dt>Category</dt><dd><?= e($performance['category']['name'] ?? 'Event') ?></dd></div>
                        </dl>

                        <?php if (!empty($performance['performers'])): ?>
                            <h2>Performers</h2>
                            <div class="tag-grid compact-tags">
                                <?php foreach ($performance['performers'] ?? [] as $performer): ?>
                                    <span><?= e($performer['name'] ?? '') ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <aside class="checkout-panel">
                        <span class="price-label">Tickets From</span>
                        <strong><?= e(money($price, (string) $currency)) ?></strong>
                        <a class="button-link wide" href="<?= e(go_url($performance, 'event')) ?>" rel="sponsored nofollow">Find Tickets</a>
                        <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
                    </aside>
                </div>
            </div>
        </section>
        <?php render_card_section('More Events', '/events', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $performance['id']), 'event', $config); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            event_schema($config, $performance),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ]);
}

function render_activity_detail_page(HelloTicketsClient $client, array $config, int $activityId): void
{
    $activity = api_result(static fn() => $client->activity($activityId));
    if ($activity === [] || empty($activity['id'])) {
        render_error_page($config, 404, 'Activity not found', 'This activity is not available anymore.');
        return;
    }

    $dateWindow = [
        'date_from' => (new DateTimeImmutable('today'))->format('Y-m-d'),
        'date_to' => (new DateTimeImmutable('today'))->modify('+90 days')->format('Y-m-d'),
    ];
    $dates = api_result(static fn() => $client->activityDates($activityId, $dateWindow), ['dates' => []])['dates'] ?? [];
    $related = api_result(static fn() => $client->activities([
        'limit' => 8,
        'page' => 1,
        'city_id' => (int) ($activity['city']['id'] ?? $config['default_city_id']),
    ]), ['activities' => []])['activities'] ?? [];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Attractions', 'url' => absolute_url($config, '/attractions')],
        ['name' => (string) ($activity['title'] ?? 'Experience'), 'url' => absolute_url($config, activity_path($activity))],
    ];

    render_layout($config, [
        'title' => $activity['title'] . ' | ' . $config['site_name'],
        'description' => 'Book ' . $activity['title'] . ' with current prices, reviews and available dates.',
        'canonical' => absolute_url($config, activity_path($activity)),
        'image' => image_from_item($activity, 'activity', $config),
    ], function () use ($activity, $dates, $related, $config, $breadcrumbs): void {
        $image = image_from_item($activity, 'activity', $config);
        $price = $activity['from_price'] ?? 0;
        $currency = $activity['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <div class="detail-header">
                    <p class="eyebrow"><?= e($activity['city']['name'] ?? 'Experience') ?></p>
                    <h1><?= e($activity['title']) ?></h1>
                    <div class="detail-facts">
                        <?php if (!empty($activity['reviews']['avg_rating'])): ?>
                            <span>
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                                <?= e(number_format((float) $activity['reviews']['avg_rating'], 1)) ?> rating
                            </span>
                        <?php endif; ?>
                        <span><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></span>
                        <?php if (!empty($activity['duration'])): ?>
                            <span><?= e($activity['duration']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-gallery" style="background-image: url('<?= e($image) ?>')"></div>

                <div class="detail-grid">
                    <div class="detail-content">
                        <h2>Experience details</h2>
                        <dl class="detail-list">
                            <div><dt>City</dt><dd><?= e($activity['city']['name'] ?? '') ?></dd></div>
                            <div><dt>Supplier</dt><dd><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></dd></div>
                            <div><dt>Cancellation</dt><dd><?= e(strip_tags((string) ($activity['cancellation_policy'] ?? 'Check partner checkout for policy.'))) ?></dd></div>
                        </dl>

                        <h2>Upcoming dates</h2>
                        <div class="date-grid">
                            <?php foreach (array_slice($dates, 0, 12) as $date): ?>
                                <span><?= e($date) ?></span>
                            <?php endforeach; ?>
                            <?php if ($dates === []): ?>
                                <p style="grid-column: 1 / -1; color: var(--muted); font-weight: 500;">Dates are confirmed during checkout.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <aside class="checkout-panel">
                        <span class="price-label">Tickets From</span>
                        <strong><?= e(money($price, (string) $currency)) ?></strong>
                        <a class="button-link wide" href="<?= e(go_url($activity, 'activity')) ?>" rel="sponsored nofollow">Check Availability</a>
                        <p class="checkout-note">Secure checkout on our official ticket partner's site.</p>
                    </aside>
                </div>
            </div>
        </section>
        <?php render_card_section('More Attractions in ' . ($activity['city']['name'] ?? 'Dubai'), '/attractions', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $activity['id']), 'activity', $config); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            activity_schema($config, $activity),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ]);
}

function render_card_section(string $heading, string $href, array $items, string $type, array $config, string $variant = ''): void
{
    if ($items === []) {
        return;
    }
    ?>
    <section class="section-band<?= $variant !== '' ? ' ' . e($variant) : '' ?>">
        <div class="container">
            <div class="section-heading">
                <h2><?= e($heading) ?></h2>
                <a href="<?= e($href) ?>">See All</a>
            </div>
            <div class="rail-wrapper">
                <button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="rail">
                    <?php foreach ($items as $item): ?>
                        <?= $type === 'event' ? event_card($item, $config) : activity_card($item, $config) ?>
                    <?php endforeach; ?>
                </div>
                <button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>
    <?php
}

function artist_card(array $performer): string
{
    $name = (string) ($performer['name'] ?? 'Artist');
    $category = (string) ($performer['category']['name'] ?? '');
    $total = (int) ($performer['total_performances'] ?? 0);
    ob_start();
    ?>
    <a class="artist-card" href="<?= e(artist_path($performer)) ?>">
        <span class="artist-avatar" aria-hidden="true"><?= e(artist_initials($name)) ?></span>
        <strong><?= e($name) ?></strong>
        <span><?= e($category !== '' ? $category : 'Live') ?><?= $total > 0 ? ' · ' . e((string) $total) . ' shows' : '' ?></span>
    </a>
    <?php
    return (string) ob_get_clean();
}

function render_artists_rail(array $performers): void
{
    if ($performers === []) {
        return;
    }
    ?>
    <section class="section-band">
        <div class="container">
            <div class="section-heading">
                <h2>Trending Artists</h2>
                <a href="/artists">See All</a>
            </div>
            <div class="rail-wrapper">
                <button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="rail artist-rail">
                    <?php foreach ($performers as $performer): ?>
                        <?= artist_card($performer) ?>
                    <?php endforeach; ?>
                </div>
                <button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>
    <?php
}

function render_artists_page(HelloTicketsClient $client, array $config): void
{
    $page = page_number();
    $data = api_result(static fn() => $client->performers([
        'limit' => 48,
        'page' => $page,
    ]), ['performers' => [], 'total_count' => 0]);
    $performers = $data['performers'] ?? [];

    render_layout($config, [
        'title' => 'Artists On Tour — Concert & Show Tickets | ' . $config['site_name'],
        'description' => 'Browse artists currently on tour. See upcoming dates, venues and live ticket prices for every show.',
        'canonical' => absolute_url($config, '/artists'),
    ], function () use ($performers, $data): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">On Tour</p>
                <h1>Trending Artists</h1>
                <p class="listing-sub">Artists with upcoming shows — pick one to see every date, venue and ticket price.</p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($performers === []): ?>
                    <div class="empty-state">
                        <h2>No artists found</h2>
                        <p>Check back soon — tours are added as soon as tickets go on sale.</p>
                        <a class="button-link" href="/events">Browse events</a>
                    </div>
                <?php else: ?>
                    <div class="artist-grid">
                        <?php foreach ($performers as $performer): ?>
                            <?= artist_card($performer) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php render_pagination($data); ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, item_list_schema_for_artists($config, $performers));
}

function render_artist_detail_page(HelloTicketsClient $client, array $config, int $performerId): void
{
    $performer = api_result(static fn() => $client->performer($performerId));
    if ($performer === [] || empty($performer['id'])) {
        render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
        return;
    }

    $name = (string) ($performer['name'] ?? 'Artist');
    $events = api_result(static fn() => $client->performances([
        'limit' => 48,
        'page' => 1,
        'is_sellable' => 'true',
        'performer_id' => $performerId,
    ]), ['performances' => []])['performances'] ?? [];

    $nextDate = '';
    if (!empty($performer['next_performance_local_date'])) {
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $performer['next_performance_local_date']);
        $nextDate = $parsed ? $parsed->format('D, M j') : '';
    }

    render_layout($config, [
        'title' => $name . ' Tickets, Tour Dates & Venues | ' . $config['site_name'],
        'description' => 'See all upcoming ' . $name . ' shows with dates, venues, cities and live ticket prices. Secure checkout on our official ticket partner.',
        'canonical' => absolute_url($config, artist_path($performer)),
    ], function () use ($config, $performer, $name, $events, $nextDate): void {
        ?>
        <section class="listing-hero artist-hero">
            <div class="container">
                <div class="artist-hero__row">
                    <span class="artist-avatar artist-avatar--lg" aria-hidden="true"><?= e(artist_initials($name)) ?></span>
                    <div>
                        <p class="eyebrow"><?= e($performer['category']['name'] ?? 'On Tour') ?></p>
                        <h1><?= e($name) ?></h1>
                        <div class="artist-hero__facts">
                            <?php if (count($events) > 0): ?>
                                <span><?= e((string) count($events)) ?> upcoming show<?= count($events) === 1 ? '' : 's' ?></span>
                            <?php endif; ?>
                            <?php if ($nextDate !== ''): ?>
                                <span>Next: <?= e($nextDate) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>No shows on sale right now</h2>
                        <p>New <?= e($name) ?> dates appear here as soon as tickets are released.</p>
                        <a class="button-link" href="/artists">Browse all artists</a>
                    </div>
                <?php else: ?>
                    <div class="section-heading">
                        <h2>Tour Dates</h2>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, artist_schema($config, $performer, $events));
}

function item_list_schema_for_artists(array $config, array $performers): array
{
    $elements = [];
    foreach (array_values($performers) as $index => $performer) {
        $elements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => absolute_url($config, artist_path($performer)),
            'name' => $performer['name'] ?? '',
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $elements,
    ];
}

function artist_schema(array $config, array $performer, array $events): array
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => ($performer['category']['name'] ?? '') === 'Sports' ? 'SportsTeam' : 'PerformingGroup',
        'name' => $performer['name'] ?? '',
        'url' => absolute_url($config, artist_path($performer)),
    ];

    $eventSchemas = [];
    foreach (array_slice($events, 0, 10) as $event) {
        $eventSchemas[] = [
            '@type' => 'Event',
            'name' => $event['name'] ?? '',
            'startDate' => $event['start_date']['date_time'] ?? ($event['start_date']['local_date'] ?? ''),
            'location' => [
                '@type' => 'Place',
                'name' => $event['venue']['name'] ?? '',
                'address' => trim(($event['venue']['address'] ?? '') . ', ' . ($event['venue']['city'] ?? ''), ', '),
            ],
            'url' => absolute_url($config, event_path($event)),
        ];
    }
    if ($eventSchemas !== []) {
        $schema['event'] = $eventSchemas;
    }

    return $schema;
}

function render_promo_banner(array $config): void
{
    ?>
    <section class="promo-band">
        <div class="container">
            <div class="promo-banner">
                <div class="promo-copy">
                    <span class="promo-kicker">Why <?= e($config['site_name']) ?></span>
                    <h2>Real tickets, instant delivery.</h2>
                    <p>Live prices, instant e-tickets and free cancellation on most experiences &mdash; booked securely through our official ticket partner.</p>
                </div>
                <a class="promo-btn" href="/attractions">Browse experiences</a>
            </div>
        </div>
    </section>
    <?php
}

function render_live_events_band(): void
{
    $cards = [
        ['title' => 'Concerts & Gigs', 'sub' => 'Live music nights', 'href' => category_path(['id' => 2, 'name' => 'Concerts']), 'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Theatre & Arts', 'sub' => 'Plays & musicals', 'href' => category_path(['id' => 3, 'name' => 'Theatre']), 'image' => 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Sports', 'sub' => 'Matchday action', 'href' => category_path(['id' => 1, 'name' => 'Sports']), 'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Desert Safari', 'sub' => 'Dunes & dinners', 'href' => '/attractions?q=Desert%20Safari', 'image' => 'https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Theme Parks', 'sub' => 'Rides & waterparks', 'href' => '/attractions?q=Theme%20Park', 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Cruises', 'sub' => 'Marina & dhow', 'href' => '/attractions?q=Cruise', 'image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80'],
    ];
    ?>
    <section class="live-band">
        <div class="container">
            <div class="section-heading">
                <h2>Browse by Category</h2>
                <a href="/attractions">See All</a>
            </div>
            <div class="cat-grid">
                <?php foreach ($cards as $card): ?>
                    <a class="cat-tile" href="<?= e($card['href']) ?>">
                        <img src="<?= e($card['image']) ?>" alt="<?= e($card['title']) ?>" loading="lazy">
                        <span class="cat-tile__body">
                            <span class="cat-tile__title"><?= e($card['title']) ?></span>
                            <span class="cat-tile__sub"><?= e($card['sub']) ?></span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function event_card(array $performance, array $config): string
{
    $image = image_from_item($performance, 'event', $config);
    $price = $performance['price_range']['min_price'] ?? 0;
    $currency = (string) ($performance['price_range']['currency'] ?? $config['currency']);
    
    $dateStr = $performance['start_date']['local_date'] ?? '';
    $monthAbbr = 'TBA';
    $dayNum = '';
    if ($dateStr !== '') {
        $d = DateTimeImmutable::createFromFormat('!Y-m-d', $dateStr);
        if ($d) {
            $monthAbbr = strtoupper($d->format('M'));
            $dayNum = $d->format('j');
        }
    }

    // Cards link straight to the partner checkout (via /go), falling back to the
    // on-site detail page only when the item has no bookable URL.
    $cardHref = !empty($performance['url']) ? go_url($performance, 'event') : event_path($performance);

    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e($cardHref) ?>" rel="sponsored nofollow">
            <img src="<?= e($image) ?>" alt="<?= e($performance['name'] ?? 'Event') ?>" loading="lazy">
            <div class="card-date-badge">
                <span class="month"><?= e($monthAbbr) ?></span>
                <span class="day"><?= e($dayNum) ?></span>
            </div>
            <div class="card-rating-strip">
                <span class="votes"><?= e($performance['category']['name'] ?? 'Event') ?></span>
            </div>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e($cardHref) ?>" rel="sponsored nofollow"><?= e($performance['name'] ?? 'Event') ?></a>
            <p><?= e(format_date_time($performance['start_date'] ?? [])) ?></p>
            <p><?= e(trim(($performance['venue']['name'] ?? '') . ', ' . ($performance['venue']['city'] ?? 'Dubai'), ', ')) ?></p>
            <p class="card-onwards"><?= e(money($price, $currency)) ?><?= ((float) $price) > 0 ? ' onwards' : '' ?></p>
        </div>
    </article>
    <?php
    return (string) ob_get_clean();
}

function activity_card(array $activity, array $config): string
{
    $image = image_from_item($activity, 'activity', $config);
    $price = $activity['from_price'] ?? 0;
    $currency = (string) ($activity['currency'] ?? $config['currency']);
    $rating = !empty($activity['reviews']['avg_rating']) ? number_format((float) $activity['reviews']['avg_rating'], 1) : null;
    $reviewsCount = !empty($activity['reviews']['number_of_reviews']) ? (int) $activity['reviews']['number_of_reviews'] : null;

    // Cards link straight to the partner checkout (via /go), falling back to the
    // on-site detail page only when the item has no bookable URL.
    $cardHref = !empty($activity['url']) ? go_url($activity, 'activity') : activity_path($activity);

    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e($cardHref) ?>" rel="sponsored nofollow">
            <img src="<?= e($image) ?>" alt="<?= e($activity['title'] ?? 'Experience') ?>" loading="lazy">
            <span class="category"><?= e($activity['city']['name'] ?? 'Attraction') ?></span>
            <?php if ($rating !== null): ?>
                <div class="card-rating-strip">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                    <?= e($rating) ?>/5
                    <?php if ($reviewsCount !== null): ?>
                        <span class="votes"><?= e(number_format($reviewsCount)) ?> votes</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e($cardHref) ?>" rel="sponsored nofollow"><?= e($activity['title'] ?? 'Experience') ?></a>
            <p><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></p>
            <p class="card-onwards"><?= e(money($price, $currency)) ?><?= ((float) $price) > 0 ? ' onwards' : '' ?></p>
        </div>
    </article>
    <?php
    return (string) ob_get_clean();
}

function render_pagination(array $data): void
{
    $page = (int) ($data['current_page'] ?? page_number());
    $perPage = max(1, (int) ($data['per_page'] ?? 24));
    $total = (int) ($data['total_count'] ?? 0);
    $hasNext = $total > $page * $perPage;
    if ($page <= 1 && !$hasNext) {
        return;
    }

    $query = $_GET;
    ?>
    <nav class="pagination" aria-label="Pagination">
        <?php if ($page > 1): ?>
            <?php $query['page'] = $page - 1; ?>
            <a href="<?= e(route_url(current_path(), $query)) ?>">Previous</a>
        <?php endif; ?>
        <span>Page <?= e($page) ?></span>
        <?php if ($hasNext): ?>
            <?php $query['page'] = $page + 1; ?>
            <a href="<?= e(route_url(current_path(), $query)) ?>">Next</a>
        <?php endif; ?>
    </nav>
    <?php
}

function handle_outbound_redirect(array $config): void
{
    header('X-Robots-Tag: noindex, nofollow');

    $destination = base64_url_decode((string) ($_GET['u'] ?? ''));
    $type = preg_replace('/[^a-z]/', '', (string) ($_GET['type'] ?? 'ticket')) ?: 'ticket';
    $id = (int) ($_GET['id'] ?? 0);

    if (!allowed_hellotickets_url($destination)) {
        render_error_page($config, 400, 'Invalid ticket link', 'This outbound ticket link is not valid.');
        return;
    }

    $subId = $type . '-' . $id;

    // Redirect FIRST so the affiliate click is never lost to a logging hiccup
    // (unwritable storage, display_errors, etc.). Logging is best-effort only.
    header('Location: ' . affiliate_url($config, $destination, $subId), true, 302);

    $logLine = json_encode([
        'time' => gmdate('c'),
        'type' => $type,
        'id' => $id,
        'destination' => $destination,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 180),
    ], JSON_UNESCAPED_SLASHES) . PHP_EOL;

    $logDir = __DIR__ . '/../storage';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    @file_put_contents($logDir . '/clicks.log', $logLine, FILE_APPEND | LOCK_EX);
}

function render_robots(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /go\n";
    echo "Disallow: /search\n";
    echo 'Sitemap: ' . $config['site_url'] . "/sitemap.xml\n";
}

function render_sitemap(HelloTicketsClient $client, array $config, array $destinationsContent = []): void
{
    // Guarded like index.php — a missing content file must not 500 Google's discovery surface.
    $dubaiContent = file_exists(__DIR__ . '/dubai-content.php')
        ? require __DIR__ . '/dubai-content.php'
        : ['categories' => [], 'attractions' => []];

    // Content-modified date for editorial/hub pages, as a real recrawl signal.
    $contentMtimes = array_filter([
        @filemtime(__DIR__ . '/destinations-content.json') ?: null,
        @filemtime(__DIR__ . '/dubai-content.php') ?: null,
    ]);
    $contentMod = $contentMtimes !== [] ? date('Y-m-d', max($contentMtimes)) : date('Y-m-d');
    $today = date('Y-m-d');

    // loc => lastmod ('' = omit), de-duped by loc, canonical URLs only.
    $entries = [];
    $add = static function (string $path, string $lastmod = '') use (&$entries, $config): void {
        $loc = absolute_url($config, $path);
        if (!array_key_exists($loc, $entries)) {
            $entries[$loc] = $lastmod;
        }
    };

    // Home + evergreen static pages.
    $add('/', $today);
    foreach (['/events', '/attractions', '/artists', '/about', '/contact', '/how-we-make-money'] as $staticPath) {
        $add($staticPath, $contentMod);
    }

    // Editorial hubs — the highest-value SEO landing pages.
    $add('/dubai', $contentMod);
    $add('/abu-dhabi', $contentMod);
    foreach ($dubaiContent['categories'] ?? [] as $cat) {
        $add('/dubai/' . $cat['slug'], $contentMod);
    }
    foreach ($dubaiContent['attractions'] ?? [] as $attr) {
        $add('/dubai/' . ($attr['category_slug'] ?? 'attractions') . '/' . $attr['slug'], $contentMod);
    }
    foreach ($destinationsContent['countries'] ?? [] as $cSlug => $country) {
        $add('/' . $cSlug, $contentMod);
        foreach ($country['cities'] ?? [] as $hubCity) {
            if (!empty($hubCity['slug'])) {
                $add('/' . $cSlug . '/' . $hubCity['slug'], $contentMod);
            }
        }
    }

    // /city/{slug} pages are deliberately omitted — they canonicalize to the editorial
    // /{country}/{city} hubs, so listing them here would be non-canonical noise.

    // Category listing pages.
    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    foreach (array_slice($categories, 0, 30) as $category) {
        $add(category_path($category));
    }

    // Performers / artists.
    $performers = api_result(static fn() => $client->performers([
        'limit' => 48,
        'page' => 1,
    ]), ['performers' => []])['performers'] ?? [];
    foreach ($performers as $performer) {
        $add(artist_path($performer));
    }

    // Live events — real <lastmod> from the API's last_updated_at.
    $events = api_result(static fn() => $client->performances(array_merge([
        'limit' => 50,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => (int) $config['default_city_id'],
    ], date_params(null))), ['performances' => []])['performances'] ?? [];
    foreach ($events as $event) {
        $lastmod = substr((string) ($event['last_updated_at'] ?? ''), 0, 10);
        $add(event_path($event), preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastmod) === 1 ? $lastmod : '');
    }

    // Deep activity detail pages (Dubai + Abu Dhabi inventory).
    foreach ([132, 256] as $cityId) {
        $activities = api_result(static fn() => $client->activities([
            'limit' => 100,
            'page' => 1,
            'city_id' => $cityId,
        ]), ['activities' => []])['activities'] ?? [];
        foreach ($activities as $activity) {
            $add(activity_path($activity));
        }
    }

    header('Content-Type: application/xml; charset=utf-8');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($entries as $loc => $lastmod) {
        echo "  <url><loc>" . e($loc) . "</loc>"
            . ($lastmod !== '' ? "<lastmod>" . e($lastmod) . "</lastmod>" : '')
            . "</url>\n";
    }
    echo "</urlset>\n";
}

function render_error_page(array $config, int $status, string $heading, string $message): void
{
    http_response_code($status);
    render_layout($config, [
        'title' => $heading . ' | ' . $config['site_name'],
        'description' => $message,
        'canonical' => absolute_url($config, current_path()),
    ], function () use ($heading, $message): void {
        ?>
        <section class="section-band">
            <div class="container">
                <div class="empty-state">
                    <h1><?= e($heading) ?></h1>
                    <p><?= e($message) ?></p>
                    <a class="button-link" href="/">Back home</a>
                </div>
            </div>
        </section>
        <?php
    });
}

function website_schema(array $config): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $config['site_name'],
        'url' => $config['site_url'],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => $config['site_url'] . '/search?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

function item_list_schema(array $config, array $items, string $type): array
{
    $elements = [];
    foreach (array_values($items) as $index => $item) {
        $path = $type === 'event' ? event_path($item) : activity_path($item);
        $elements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => absolute_url($config, $path),
            'name' => $type === 'event' ? ($item['name'] ?? '') : ($item['title'] ?? ''),
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $elements,
    ];
}

function event_schema(array $config, array $event): array
{
    $price = $event['price_range']['min_price'] ?? 0;
    $localDate = (string) ($event['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    return [
        '@type' => 'Event',
        'name' => $event['name'] ?? '',
        'startDate' => $event['start_date']['date_time'] ?? ($event['start_date']['local_date'] ?? ''),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => [
            '@type' => 'Place',
            'name' => $event['venue']['name'] ?? '',
            'address' => trim(($event['venue']['address'] ?? '') . ', ' . ($event['venue']['city'] ?? '')),
        ],
        'image' => [image_from_item($event, 'event', $config)],
        'offers' => [
            '@type' => 'Offer',
            'url' => absolute_url($config, event_path($event)),
            'price' => (float) $price,
            'priceCurrency' => $event['price_range']['currency'] ?? $config['currency'],
            'availability' => $isPast ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
        ],
    ];
}

function activity_schema(array $config, array $activity): array
{
    $schema = [
        '@type' => 'Product',
        'name' => $activity['title'] ?? '',
        'image' => [image_from_item($activity, 'activity', $config)],
        'brand' => [
            '@type' => 'Brand',
            'name' => $activity['supplier_name'] ?? 'HelloTickets',
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => absolute_url($config, activity_path($activity)),
            'price' => (float) ($activity['from_price'] ?? 0),
            'priceCurrency' => $activity['currency'] ?? $config['currency'],
            'availability' => 'https://schema.org/InStock',
        ],
    ];

    if (!empty($activity['reviews']['avg_rating'])) {
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (float) $activity['reviews']['avg_rating'],
            'reviewCount' => (int) ($activity['reviews']['number_of_reviews'] ?? 0),
        ];
    }

    return $schema;
}
