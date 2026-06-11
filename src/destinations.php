<?php
declare(strict_types=1);

/* =========================================================================
   Destination engine — generic country & city SEO hubs
   -------------------------------------------------------------------------
   Powers /{country} and /{country}/{city} for the markets defined in the
   content pack (src/destinations-content.php). Reuses the shared chrome
   (render_layout), card rails (render_card_section), and the generic
   schema/UI helpers from dubai-pages.php (dubai_render_breadcrumbs,
   dubai_render_faq, dubai_breadcrumb_schema, dubai_faq_schema).
   Dubai/Abu Dhabi keep their own bespoke renderers.
   ========================================================================= */

function destination_country_exists(array $pack, string $slug): bool
{
    return isset($pack['countries'][$slug]);
}

function destination_city_in_country(array $pack, string $countrySlug, string $citySlug): bool
{
    return isset($pack['cities'][$citySlug])
        && ($pack['cities'][$citySlug]['country_slug'] ?? '') === $countrySlug;
}

/** Split a multi-paragraph intro string ("para\n\npara") into paragraphs. */
function destination_paragraphs($intro): array
{
    if (is_array($intro)) {
        return $intro;
    }
    $parts = preg_split('/\n\n+/', trim((string) $intro)) ?: [];
    return array_values(array_filter(array_map('trim', $parts), static fn($p) => $p !== ''));
}

/** Country names that read with the definite article ("the United States"). */
function destination_display_name(string $name): string
{
    static $theCountries = ['United States', 'United Kingdom', 'Netherlands', 'Czech Republic', 'United Arab Emirates', 'Philippines'];
    return in_array($name, $theCountries, true) ? 'the ' . $name : $name;
}

// ===========================================================================
// 1. Country hub  /{country}
// ===========================================================================

function render_country_hub(HelloTicketsClient $client, array $config, array $pack, string $countrySlug): void
{
    $country = $pack['countries'][$countrySlug];
    $cities = $country['cities'] ?? [];
    $faqs = $country['faqs'] ?? [];
    $stats = $country['stats'] ?? [];
    $heroImage = $country['hero_image'] ?? $config['fallback_images']['hero'];
    $name = $country['name'] ?? ucfirst($countrySlug);
    $displayName = destination_display_name($name);

    // One live rail from the primary flagship city keeps the hub fast.
    $primary = $cities[0] ?? null;
    $topActivities = $primary
        ? (api_result(static fn() => $client->activities([
            'city_id' => (int) ($primary['city_id'] ?? 0),
            'limit' => 8,
            'page' => 1,
        ]), ['activities' => []])['activities'] ?? [])
        : [];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => $name, 'url' => absolute_url($config, '/' . $countrySlug)],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                'name' => $country['meta_title'] ?? ($name . ' Tickets & Attractions'),
                'url' => absolute_url($config, '/' . $countrySlug),
                'description' => $country['meta_description'] ?? '',
                // Reference the home page's #website node instead of re-declaring an
                // anonymous copy — disconnected duplicates fragment the entity graph.
                'isPartOf' => ['@id' => $config['site_url'] . '/#website'],
            ],
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ];

    $statLabels = [
        'attractions' => 'Attractions & Tours',
        'price_from' => 'Prices From',
        'support' => 'Partner Support',
    ];

    // Long-tail internal linking: every inventory-having geo city in this country
    // that isn't already a flagship editorial hub gets a /city/{slug} link here, so
    // "events in {city}" pages (Edmonton, Quebec, Glasgow…) are discoverable and pass
    // PageRank from the country hub instead of sitting orphaned.
    $countryCode3 = (string) ($config['markets'][$countrySlug]['country_code'] ?? '');
    $flagshipSlugs = [];
    foreach ($cities as $fc) {
        $flagshipSlugs[slugify((string) ($fc['name'] ?? ''))] = true;
    }
    $moreCities = [];
    if ($countryCode3 !== '') {
        foreach (geo_cities() as $gid => $geo) {
            $gidInt = (int) $gid;
            if ($gidInt === 132 || $gidInt === 256) {
                continue;
            }
            if ((string) ($geo['country_code'] ?? '') !== $countryCode3 || !city_has_inventory($gidInt)) {
                continue;
            }
            $gName = (string) ($geo['name'] ?? '');
            $gSlug = slugify($gName);
            if ($gName === '' || isset($flagshipSlugs[$gSlug])) {
                continue;
            }
            $moreCities[$gSlug] = $gName;
        }
        ksort($moreCities);
    }

    render_layout($config, [
        'title' => $country['meta_title'] ?? ($name . ' Tickets, Tours & Attractions | ' . $config['site_name']),
        'description' => $country['meta_description'] ?? ('Book tickets and tours across ' . $name . ' with instant e-tickets and free cancellation on most experiences.'),
        'canonical' => absolute_url($config, '/' . $countrySlug),
        'image' => $heroImage,
        'preload_image' => $heroImage,
        'body_class' => 'destination-hub-page',
    ], function () use ($config, $country, $countrySlug, $cities, $faqs, $stats, $statLabels, $heroImage, $name, $displayName, $breadcrumbs, $topActivities, $primary, $moreCities): void {
        ?>

        <!-- Hero -->
        <section class="destination-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.6) 0%, rgba(0,0,0,.25) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1>Things to Do in <?= e($displayName) ?> &mdash; Tickets, Tours &amp; Attractions</h1>
                <p class="destination-hub__hero-sub">Skip-the-line tickets and instant confirmation for the best experiences in <?= e($displayName) ?></p>
                <form class="destination-hub__search" action="/search" method="get">
                    <input type="search" name="q" placeholder="Search attractions, tours, tickets..." aria-label="Search <?= e($name) ?> attractions">
                    <button type="submit">Search</button>
                </form>
            </div>
        </section>

        <!-- Stats row -->
        <?php if ($stats !== []): ?>
            <section class="destination-hub__stats">
                <div class="container">
                    <div class="destination-hub__stats-grid">
                        <?php foreach ($statLabels as $key => $label): if (empty($stats[$key])) { continue; } ?>
                            <div class="destination-hub__stat">
                                <strong><?= e((string) $stats[$key]) ?></strong>
                                <span><?= e($label) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- City chooser -->
        <?php if ($cities !== []): ?>
            <section class="destination-hub__cities section-band">
                <div class="container">
                    <div class="section-heading">
                        <h2>Top Destinations in <?= e($displayName) ?></h2>
                        <a href="/attractions">See All</a>
                    </div>
                    <div class="destination-hub__city-grid">
                        <?php foreach ($cities as $city): ?>
                            <a class="destination-hub__city-card" href="/<?= e($countrySlug) ?>/<?= e($city['slug']) ?>" style="background-image: linear-gradient(180deg, rgba(0,0,0,.15) 0%, rgba(0,0,0,.78) 100%), url('<?= e($city['hero_image'] ?? $config['fallback_images']['hero']) ?>')">
                                <span class="destination-hub__city-name"><?= e($city['name']) ?></span>
                                <span class="destination-hub__city-sub"><?= e($city['highlights'][0] ?? 'Tickets, tours & attractions') ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Live attractions rail (primary city) -->
        <?php if ($topActivities !== [] && $primary !== null): ?>
            <?php render_card_section('Top Things to Do in ' . $primary['name'], '/' . $countrySlug . '/' . $primary['slug'], $topActivities, 'activity', $config, 'muted'); ?>
        <?php endif; ?>

        <!-- More cities (long-tail "events in {city}" pages) -->
        <?php if ($moreCities !== []): ?>
            <section class="destination-hub__more-cities section-band">
                <div class="container">
                    <div class="section-heading">
                        <h2>More Cities in <?= e($displayName) ?></h2>
                    </div>
                    <p class="more-cities-intro">Live events, concerts and sports with tickets on sale across <?= e($name) ?>.</p>
                    <ul class="more-cities-list">
                        <?php foreach ($moreCities as $mcSlug => $mcName): ?>
                            <li><a href="/city/<?= e($mcSlug) ?>"><?= e($mcName) ?> events</a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>

        <!-- Guide / SEO content -->
        <?php $paras = destination_paragraphs($country['intro'] ?? ''); ?>
        <?php if ($paras !== []): ?>
            <section class="destination-hub__guide section-band">
                <div class="container">
                    <div class="destination-hub__guide-content">
                        <h2>Your Guide to <?= e($displayName) ?></h2>
                        <?php foreach ($paras as $p): ?>
                            <p><?= e($p) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Trust band -->
        <section class="destination-hub__trust section-band muted">
            <div class="container">
                <h2>Why Book With <?= e($config['site_name']) ?></h2>
                <div class="destination-hub__trust-grid">
                    <div class="destination-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        <h3>Free Cancellation on Many Tickets</h3>
                        <p>The exact policy for each ticket is shown at partner checkout before you pay.</p>
                    </div>
                    <div class="destination-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg>
                        <h3>Instant E-Tickets</h3>
                        <p>Get your tickets delivered straight to your phone. No printing required, just show and go.</p>
                    </div>
                    <div class="destination-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                        <h3>24/7 Partner Support</h3>
                        <p>Our ticket partner's support team is available around the clock for bookings and changes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <?php dubai_render_faq($faqs, 'Frequently Asked Questions About ' . $displayName); ?>

        <?php
    }, $schema);
}

// ===========================================================================
// 2. City hub  /{country}/{city}
// ===========================================================================

function render_city_hub(HelloTicketsClient $client, array $config, array $pack, string $countrySlug, string $citySlug): void
{
    $city = $pack['cities'][$citySlug];
    $country = $pack['countries'][$countrySlug] ?? [];
    $countryName = $country['name'] ?? ucfirst($countrySlug);
    $countryDisplay = destination_display_name($countryName);
    $cityId = (int) ($city['city_id'] ?? 0);
    $cityName = $city['name'] ?? ucfirst($citySlug);
    $heroImage = $city['hero_image'] ?? $config['fallback_images']['hero'];
    $faqs = $city['faqs'] ?? [];
    $highlights = $city['highlights'] ?? [];
    $tips = $city['tips'] ?? [];

    $page = page_number();
    $perPage = 24;

    // Attractions stay a teaser rail (HelloTickets; only page 1 carries them).
    $activities = [];
    if ($page === 1) {
        $activities = api_result(static fn() => $client->activities([
            'city_id' => $cityId,
            'limit' => 12,
            'page' => 1,
        ]), ['activities' => []])['activities'] ?? [];
    }

    // Events are the deep catalogue: HelloTickets first, then page through
    // Ticketmaster, then paginate on-page (24 per page) like the standalone city page.
    $htEvents = api_result(static fn() => $client->performances(array_merge([
        'city_id' => $cityId,
        'limit' => 24,
        'page' => 1,
        'is_sellable' => 'true',
    ], date_params(null))), ['performances' => []])['performances'] ?? [];
    $tmEvents = tm_events_for_city_deep($config, $cityName, (string) ($city['country_code'] ?? ''));
    $eventPool = city_event_pool($htEvents, $tmEvents, $config);
    $totalEvents = count($eventPool);
    $events = array_slice($eventPool, ($page - 1) * $perPage, $perPage);
    $eventsPageData = ['current_page' => $page, 'per_page' => $perPage, 'total_count' => $totalEvents];

    // Sibling cities for internal links.
    $siblings = [];
    foreach ($country['cities'] ?? [] as $sibling) {
        if (($sibling['slug'] ?? '') !== $citySlug) {
            $siblings[] = $sibling;
        }
    }

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => $countryName, 'url' => absolute_url($config, '/' . $countrySlug)],
        ['name' => $cityName, 'url' => absolute_url($config, '/' . $countrySlug . '/' . $citySlug)],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => ['WebPage', 'TouristDestination'],
                'name' => $cityName,
                'url' => absolute_url($config, '/' . $countrySlug . '/' . $citySlug),
                'description' => $city['meta_description'] ?? '',
                // Reference the home page's #website node instead of re-declaring an
                // anonymous copy — disconnected duplicates fragment the entity graph.
                'isPartOf' => ['@id' => $config['site_url'] . '/#website'],
            ],
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ];

    render_layout($config, [
        'title' => $city['meta_title'] ?? ($cityName . ' Tickets, Tours & Attractions | ' . $config['site_name']),
        'description' => $city['meta_description'] ?? ('Book the best things to do in ' . $cityName . ' with instant e-tickets and free cancellation on most experiences.'),
        'canonical' => absolute_url($config, '/' . $countrySlug . '/' . $citySlug, array_filter(['page' => $page > 1 ? $page : null])),
        'image' => $heroImage,
        'preload_image' => $heroImage,
        'body_class' => 'destination-city-page',
    ], function () use ($config, $city, $countrySlug, $countryName, $countryDisplay, $cityName, $cityId, $heroImage, $faqs, $highlights, $tips, $activities, $events, $eventsPageData, $siblings, $breadcrumbs): void {
        ?>

        <!-- Hero -->
        <section class="destination-city__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.58) 0%, rgba(0,0,0,.25) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1>Things to Do in <?= e($cityName) ?></h1>
                <p class="destination-city__hero-sub">Tickets, tours and experiences in <?= e($cityName) ?>, <?= e($countryName) ?></p>
            </div>
        </section>

        <!-- Live attractions + events FIRST (visitors came for tickets; the city
             write-up sits below). events_first markets (DE/AU/CA) lead with events. -->
        <?php $cityHref = city_path(['name' => $cityName, 'id' => $cityId]); ?>
        <?php if (!empty($city['events_first'])): ?>
            <?php render_events_grid_section('Events in ' . $cityName, $cityName, $events, $eventsPageData, $config); ?>
            <?php if ($activities !== []): ?>
                <?php render_card_section('Top Attractions in ' . $cityName, $cityHref, $activities, 'activity', $config, 'muted'); ?>
            <?php endif; ?>
        <?php else: ?>
            <?php if ($activities !== []): ?>
                <?php render_card_section('Top Attractions in ' . $cityName, $cityHref, $activities, 'activity', $config); ?>
            <?php endif; ?>
            <?php render_events_grid_section('Events in ' . $cityName, $cityName, $events, $eventsPageData, $config, 'muted'); ?>
        <?php endif; ?>

        <!-- Intro / city write-up — below the listings -->
        <?php $paras = destination_paragraphs($city['intro'] ?? ''); ?>
        <?php if ($paras !== []): ?>
            <section class="destination-city__intro section-band">
                <div class="container">
                    <h2>About <?= e($cityName) ?></h2>
                    <div class="destination-city__intro-content">
                        <?php foreach ($paras as $p): ?>
                            <p><?= e($p) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Highlights -->
        <?php if ($highlights !== []): ?>
            <section class="destination-city__highlights section-band">
                <div class="container">
                    <h2>Highlights</h2>
                    <ul class="destination-city__highlights-list">
                        <?php foreach ($highlights as $highlight): ?>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <?= e($highlight) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>

        <!-- Tips -->
        <?php if ($tips !== []): ?>
            <section class="destination-city__tips section-band muted">
                <div class="container">
                    <h2>Tips for Visiting <?= e($cityName) ?></h2>
                    <div class="destination-city__tips-grid">
                        <?php foreach ($tips as $tip): ?>
                            <div class="destination-city__tip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p><?= e($tip) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- FAQ -->
        <?php dubai_render_faq($faqs, 'Frequently Asked Questions About ' . $cityName); ?>

        <!-- Sibling cities -->
        <?php if ($siblings !== []): ?>
            <section class="destination-city__related section-band">
                <div class="container">
                    <h2>More Destinations in <?= e($countryDisplay) ?></h2>
                    <div class="dubai-hub__link-grid">
                        <?php foreach ($siblings as $sibling): ?>
                            <a class="dubai-hub__link-card" href="/<?= e($countrySlug) ?>/<?= e($sibling['slug']) ?>">
                                <strong><?= e($sibling['name']) ?></strong>
                                <span><?= e($sibling['highlights'][0] ?? 'Tickets & tours') ?></span>
                            </a>
                        <?php endforeach; ?>
                        <a class="dubai-hub__link-card" href="/<?= e($countrySlug) ?>">
                            <strong>All of <?= e($countryName) ?></strong>
                            <span>Browse every destination</span>
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php
    }, $schema);
}
