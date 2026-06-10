<?php
declare(strict_types=1);

function dispatch(HelloTicketsClient $client, array $config): void
{
    $path = current_path();
    if ($path !== '/' && substr($path, -1) === '/') {
        header('Location: ' . rtrim($path, '/'), true, 301);
        return;
    }

    if ($path === '/') {
        render_home_page($client, $config);
        return;
    }

    if ($path === '/events') {
        render_events_page($client, $config, $config['default_city_id']);
        return;
    }

    if ($path === '/attractions') {
        render_activities_page($client, $config, $config['default_city_id']);
        return;
    }

    if ($path === '/search') {
        render_search_page($client, $config);
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
        render_sitemap($client, $config);
        return;
    }

    if (preg_match('#^/city/([^/]+)$#', $path, $match)) {
        render_city_page($client, $config, id_from_slug($match[1]));
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
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($config['fallback_images']['hero']) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/styles.css">
    <?php if ($schema !== null): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
    <header class="site-header">
        <a class="brand" href="/" aria-label="<?= e($config['site_name']) ?> home">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="display: block; width: 28px; height: 28px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path><line x1="9" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line><line x1="15" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"></line></svg>
            <span>Ticked<em>Bus</em></span>
        </a>
        <div class="header-search">
            <form action="/search" method="get">
                <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search for Events, Attractions, Concerts, Theatre and Tours">
                <button type="submit" aria-label="Search">Search</button>
            </form>
        </div>
        <div class="header-actions">
            <a class="header-city" href="<?= e(city_path($config['market_cities'][0])) ?>"><?= e($config['default_city_name']) ?></a>
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
        <div>
            <strong>
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 22px; height: 22px;"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"></path></svg>
                Ticked<em style="font-style: normal; color: var(--red);">Bus</em>
            </strong>
            <p>Your guide to Dubai events, attractions and experiences. Prices and availability are live from our ticket partner, and checkout is completed securely on their site. We may earn a commission on bookings at no extra cost to you. &copy; <?= e(date('Y')) ?> <?= e($config['site_name']) ?>. All events, images and trademarks belong to their respective owners.</p>
        </div>
        <div class="footer-links">
            <a href="/events">Events</a>
            <a href="/attractions">Attractions</a>
            <a href="<?= e(city_path($config['market_cities'][0])) ?>">Dubai</a>
            <a href="/search">Search</a>
            <a href="/sitemap.xml">Sitemap</a>
        </div>
    </footer>
    <script src="/assets/app.js" defer></script>
</body>
</html>
    <?php
}

function render_home_page(HelloTicketsClient $client, array $config): void
{
    $cityId = (int) $config['default_city_id'];
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

    $categoriesData = api_result(static fn() => $client->categories(), ['categories' => []]);
    $activities = $activitiesData['activities'] ?? [];
    $events = $eventsData['performances'] ?? [];
    $globalEvents = $globalEventsData['performances'] ?? [];

    render_layout($config, [
        'title' => 'Dubai Events, Attractions & Tickets | ' . $config['site_name'],
        'description' => 'Find Dubai attraction tickets, concerts, theatre, sports and experiences with live prices from HelloTickets.',
        'canonical' => absolute_url($config, '/'),
        'body_class' => 'home-page',
    ], function () use ($config, $activities, $events, $globalEvents, $categoriesData): void {
        ?>
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
                        <?php foreach ($slides as $index => $slide): ?>
                            <div class="carousel-slide" style="background-image: url('<?= e($slide['image']) ?>')">
                                <div class="carousel-caption">
                                    <span class="slide-tag"><?= e($slide['tag']) ?></span>
                                    <?php if ($index === 0): ?>
                                        <h1><?= e($slide['title']) ?></h1>
                                    <?php else: ?>
                                        <h2><?= e($slide['title']) ?></h2>
                                    <?php endif; ?>
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
                    <a href="/events?date=weekend">This weekend</a>
                    <a href="/events?date=month">This month</a>
                    <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                    <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                </div>
            </div>
        </section>

        <?php if ($activities !== []): ?>
            <?php render_card_section('Recommended Attractions', '/attractions', $activities, 'activity', $config); ?>
        <?php endif; ?>

        <?php if ($events !== []): ?>
            <?php render_card_section('Live Events in Dubai', '/events', $events, 'event', $config); ?>
        <?php endif; ?>

        <?php if ($globalEvents !== []): ?>
            <?php render_card_section('Popular Events Worldwide', '/events', $globalEvents, 'event', $config, 'dark'); ?>
        <?php endif; ?>

        <section class="section-band">
            <div class="container split-section">
                <div>
                    <p class="eyebrow">Browse by destination</p>
                    <h2>Popular ticket cities</h2>
                </div>
                <div class="city-grid">
                    <?php foreach ($config['market_cities'] as $city): ?>
                        <a href="<?= e(city_path($city)) ?>">
                            <strong><?= e($city['name']) ?></strong>
                            <span><?= e($city['country']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="section-band muted">
            <div class="container split-section">
                <div>
                    <p class="eyebrow">Browse by category</p>
                    <h2>Concerts, theatre, sports and experiences</h2>
                </div>
                <div class="tag-grid">
                    <?php foreach (array_slice($categoriesData['categories'] ?? [], 0, 18) as $category): ?>
                        <a href="<?= e(category_path($category)) ?>"><?= e($category['name']) ?></a>
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
    $categoryLabel = $category ? $category['name'] . ' ' : '';
    $title = trim($categoryLabel . 'events in ' . $city['name']);

    render_listing_layout($config, [
        'title' => ucwords($title) . ' | ' . $config['site_name'],
        'description' => 'Browse live ' . strtolower($categoryLabel) . 'event tickets in ' . $city['name'] . ' with dates, venues and prices.',
        'canonical' => absolute_url($config, current_path(), array_filter(['q' => $query, 'date' => $date !== 'upcoming' ? $date : null])),
    ], $title, $items, 'event', $config, $data, [
        'city_id' => $cityId,
        'date' => $date,
        'q' => $query,
        'category' => $category,
    ]);
}

function render_activities_page(HelloTicketsClient $client, array $config, int $cityId, ?string $categoryQuery = null): void
{
    $city = city_for_id($cityId, $config);
    $page = page_number();
    $query = $categoryQuery ?: search_query();
    $data = api_result(static fn() => $client->activities([
        'limit' => 24,
        'page' => $page,
        'city_id' => $cityId,
        'query' => $query,
    ]), ['activities' => [], 'total_count' => 0]);

    $items = $data['activities'] ?? [];
    $title = $query !== '' ? $query . ' tickets in ' . $city['name'] : 'Attractions and experiences in ' . $city['name'];

    render_listing_layout($config, [
        'title' => ucwords($title) . ' | ' . $config['site_name'],
        'description' => 'Compare ' . $city['name'] . ' attractions, tours and experiences with current prices and partner checkout.',
        'canonical' => absolute_url($config, current_path(), array_filter(['q' => search_query()])),
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
                    <div class="empty-state">
                        <h2>No tickets found</h2>
                        <p>Try a broader search or browse the main Dubai listings.</p>
                        <a class="button-link" href="<?= $type === 'event' ? '/events' : '/attractions' ?>">Show all</a>
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

    $cityId = (int) $config['default_city_id'];
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

    render_layout($config, [
        'title' => 'Search tickets for ' . ($query ?: 'Dubai') . ' | ' . $config['site_name'],
        'description' => 'Search Dubai events, attractions and experiences.',
        'canonical' => absolute_url($config, '/search', ['q' => $query]),
    ], function () use ($query, $events, $activities, $config): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Search</p>
                <h1><?= $query !== '' ? 'Tickets for "' . e($query) . '"' : 'Search tickets' ?></h1>
                <form class="listing-toolbar" action="/search" method="get">
                    <input type="search" name="q" value="<?= e($query) ?>" placeholder="Search Dubai tickets">
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
                        <p>Browse the main Dubai pages to see current inventory.</p>
                        <a class="button-link" href="/attractions">View attractions</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php
    });
}

function render_city_page(HelloTicketsClient $client, array $config, int $cityId): void
{
    $city = city_for_id($cityId, $config);
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
        'canonical' => absolute_url($config, city_path($city)),
    ], function () use ($city, $eventsData, $activitiesData, $config): void {
        ?>
        <section class="listing-hero city-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['country'] ?: 'Destination') ?></p>
                <h1><?= e($city['name']) ?> tickets, events and attractions</h1>
                <div class="filter-row inverse">
                    <a href="/events?date=today">Today</a>
                    <a href="/events?date=weekend">This weekend</a>
                    <a href="/attractions">Attractions</a>
                    <a href="/events">Events</a>
                </div>
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
        render_events_page($client, $config, (int) $config['default_city_id'], $category);
        return;
    }

    render_activities_page($client, $config, (int) $config['default_city_id'], (string) $category['name']);
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

    render_layout($config, [
        'title' => $performance['name'] . ' Tickets | ' . $config['site_name'],
        'description' => 'See dates, venue and ticket prices for ' . $performance['name'] . ' in ' . $cityName . '.',
        'canonical' => absolute_url($config, event_path($performance)),
    ], function () use ($performance, $related, $config): void {
        $image = image_from_item($performance, 'event', $config);
        $price = $performance['price_range']['min_price'] ?? 0;
        $currency = $performance['price_range']['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
                <div class="detail-header">
                    <p class="eyebrow"><?= e($performance['category']['name'] ?? 'Event') ?></p>
                    <h1><?= e($performance['name']) ?></h1>
                    <div class="detail-facts">
                        <span>
                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                            4.9 rating
                        </span>
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
        <?php render_card_section('More events', '/events', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $performance['id']), 'event', $config); ?>
        <?php
    }, event_schema($config, $performance));
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

    render_layout($config, [
        'title' => $activity['title'] . ' | ' . $config['site_name'],
        'description' => 'Book ' . $activity['title'] . ' with current prices, reviews and available dates.',
        'canonical' => absolute_url($config, activity_path($activity)),
    ], function () use ($activity, $dates, $related, $config): void {
        $image = image_from_item($activity, 'activity', $config);
        $price = $activity['from_price'] ?? 0;
        $currency = $activity['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
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
        <?php render_card_section('More Dubai attractions', '/attractions', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $activity['id']), 'activity', $config); ?>
        <?php
    }, activity_schema($config, $activity));
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
                <a href="<?= e($href) ?>">Show all</a>
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
    
    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e(event_path($performance)) ?>">
            <img src="<?= e($image) ?>" alt="<?= e($performance['name'] ?? 'Event') ?>" loading="lazy">
            <div class="card-date-badge">
                <span class="month"><?= e($monthAbbr) ?></span>
                <span class="day"><?= e($dayNum) ?></span>
            </div>
            <div class="card-rating-strip">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                4.9/5
                <span class="votes"><?= e($performance['category']['name'] ?? 'Event') ?></span>
            </div>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e(event_path($performance)) ?>"><?= e($performance['name'] ?? 'Event') ?></a>
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
    $rating = !empty($activity['reviews']['avg_rating']) ? number_format((float) $activity['reviews']['avg_rating'], 1) : '4.8';
    $reviewsCount = !empty($activity['reviews']['number_of_reviews']) ? (int) $activity['reviews']['number_of_reviews'] : null;
    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e(activity_path($activity)) ?>">
            <img src="<?= e($image) ?>" alt="<?= e($activity['title'] ?? 'Experience') ?>" loading="lazy">
            <span class="category"><?= e($activity['city']['name'] ?? 'Attraction') ?></span>
            <div class="card-rating-strip">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                <?= e($rating) ?>/5
                <?php if ($reviewsCount !== null): ?>
                    <span class="votes"><?= e(number_format($reviewsCount)) ?> votes</span>
                <?php endif; ?>
            </div>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e(activity_path($activity)) ?>"><?= e($activity['title'] ?? 'Experience') ?></a>
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
        mkdir($logDir, 0775, true);
    }
    file_put_contents($logDir . '/clicks.log', $logLine, FILE_APPEND | LOCK_EX);

    header('Location: ' . affiliate_url($config, $destination, $subId), true, 302);
}

function render_robots(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /go\n";
    echo 'Sitemap: ' . $config['site_url'] . "/sitemap.xml\n";
}

function render_sitemap(HelloTicketsClient $client, array $config): void
{
    $urls = [
        absolute_url($config, '/'),
        absolute_url($config, '/events'),
        absolute_url($config, '/attractions'),
    ];

    foreach ($config['market_cities'] as $city) {
        $urls[] = absolute_url($config, city_path($city));
    }

    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    foreach (array_slice($categories, 0, 30) as $category) {
        $urls[] = absolute_url($config, category_path($category));
    }

    $events = api_result(static fn() => $client->performances(array_merge([
        'limit' => 50,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => (int) $config['default_city_id'],
    ], date_params(null))), ['performances' => []])['performances'] ?? [];
    foreach ($events as $event) {
        $urls[] = absolute_url($config, event_path($event));
    }

    $activities = api_result(static fn() => $client->activities([
        'limit' => 50,
        'page' => 1,
        'city_id' => (int) $config['default_city_id'],
    ]), ['activities' => []])['activities'] ?? [];
    foreach ($activities as $activity) {
        $urls[] = absolute_url($config, activity_path($activity));
    }

    $urls = array_values(array_unique($urls));
    header('Content-Type: application/xml; charset=utf-8');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($urls as $url) {
        echo "  <url><loc>" . e($url) . "</loc><lastmod>" . gmdate('Y-m-d') . "</lastmod></url>\n";
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
    return [
        '@context' => 'https://schema.org',
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
            'availability' => 'https://schema.org/InStock',
        ],
    ];
}

function activity_schema(array $config, array $activity): array
{
    $schema = [
        '@context' => 'https://schema.org',
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
