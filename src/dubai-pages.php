<?php
declare(strict_types=1);

/* =========================================================================
   Dubai & Abu Dhabi SEO landing pages
   =========================================================================
   Renders hub, category, and individual attraction pages with rich schema
   markup for Dubai tourism keywords.
   ========================================================================= */

/**
 * Rich editorial twin for a HelloTickets activity, when one exists.
 * The hand-written /dubai/{category}/{slug} attraction pages cover the same
 * products as some /activity/ pages; the thin twin 301s to the rich page so
 * the two never compete in search.
 */
function dubai_attraction_path_for_activity(int $activityId): ?string
{
    static $map = null;
    if ($map === null) {
        $map = [];
        $packFile = __DIR__ . '/dubai-content.php';
        if (is_file($packFile)) {
            $pack = require $packFile;
            foreach ($pack['attractions'] ?? [] as $attraction) {
                $aid = (int) ($attraction['activity_id'] ?? 0);
                if ($aid > 0 && !empty($attraction['slug'])) {
                    $map[$aid] = '/dubai/' . ($attraction['category_slug'] ?? 'attractions') . '/' . $attraction['slug'];
                }
            }
        }
    }
    return $map[$activityId] ?? null;
}

// ---------------------------------------------------------------------------
// Helper: Breadcrumb schema
// ---------------------------------------------------------------------------

function dubai_breadcrumb_schema(array $config, array $crumbs): array
{
    $items = [];
    foreach (array_values($crumbs) as $index => $crumb) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['name'],
            'item' => $crumb['url'],
        ];
    }

    return [
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

// ---------------------------------------------------------------------------
// Helper: FAQ schema
// ---------------------------------------------------------------------------

function dubai_faq_schema(array $faqs): array
{
    $items = [];
    foreach ($faqs as $faq) {
        $items[] = [
            '@type' => 'Question',
            'name' => $faq['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['a'],
            ],
        ];
    }

    return [
        '@type' => 'FAQPage',
        'mainEntity' => $items,
    ];
}

// ---------------------------------------------------------------------------
// Helper: TouristAttraction schema with AggregateRating
// ---------------------------------------------------------------------------

function dubai_tourist_attraction_schema(array $config, array $attraction, array $activity): array
{
    $attractionUrl = absolute_url($config, '/dubai/' . ($attraction['category_slug'] ?? 'attractions') . '/' . ($attraction['slug'] ?? ''));
    $schema = [
        '@type' => 'TouristAttraction',
        '@id' => $attractionUrl . '#attraction',
        // The attraction's NAME, not the page title ("Burj Khalifa", not
        // "Burj Khalifa Tickets & Observation Deck Experiences").
        'name' => $attraction['short_name'] ?? ($attraction['title'] ?? ($activity['title'] ?? '')),
        'description' => $attraction['meta_description'] ?? '',
        'url' => $attractionUrl,
        'touristType' => 'Leisure',
        'isAccessibleForFree' => false,
    ];

    if (!empty($attraction['image'])) {
        $schema['image'] = $attraction['image'];
    }

    if (!empty($attraction['location'])) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Dubai',
            'addressCountry' => 'AE',
            'streetAddress' => $attraction['location'],
        ];
    }

    if (!empty($activity['reviews']['avg_rating'])) {
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (float) $activity['reviews']['avg_rating'],
            'reviewCount' => (int) ($activity['reviews']['number_of_reviews'] ?? 0),
            'bestRating' => 5,
        ];
    }

    return $schema;
}

// ---------------------------------------------------------------------------
// Helper: Category icon SVGs
// ---------------------------------------------------------------------------

function dubai_category_icon(string $slug): string
{
    $icons = [
        'burj-khalifa' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M9 21V6l-3 3"/><path d="M15 21V6l3 3"/><path d="M12 21V3"/><path d="M6 12h12"/></svg>',
        'desert-safari' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20s3-6 8-6 6 4 10 4 4-4 4-4"/><circle cx="18" cy="5" r="3"/><path d="M7 8l2 4"/><path d="M5 10l4 2"/></svg>',
        'waterparks' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 15c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M2 19c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M9 7a3 3 0 1 0 6 0 3 3 0 0 0-6 0"/><path d="M12 10v2"/></svg>',
        'aquarium' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5 0 9-3 9-8 0-4-3-6-5-8-1 3-4 4-4 4s-3-1-4-4c-2 2-5 4-5 8 0 5 4 8 9 8z"/><circle cx="9" cy="15" r="1"/><circle cx="15" cy="15" r="1"/></svg>',
        'dubai-frame' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1"/><rect x="9" y="8" width="6" height="8"/></svg>',
        'cruises' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20c2-1 4-1 6 0s4 1 6 0 4-1 6 0"/><path d="M4 17l2-10h12l2 10"/><path d="M12 7V3"/><path d="M8 7l4-4 4 4"/></svg>',
        'museum-of-the-future' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="12" rx="9" ry="8"/><ellipse cx="12" cy="12" rx="3.5" ry="2.5"/></svg>',
        'theme-parks' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15 15 0 0 1 4 10 15 15 0 0 1-4 10"/><path d="M12 2a15 15 0 0 0-4 10 15 15 0 0 0 4 10"/><path d="M2 12h20"/></svg>',
        'helicopter-tours' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M12 6V3"/><path d="M14 12h4l2 5H4l2-5h4"/><circle cx="12" cy="12" r="2"/><path d="M12 17v3"/><path d="M8 20h8"/></svg>',
        'jet-ski' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M5 14l5-6 3 4h6l-2 3H5z"/></svg>',
        'skydiving' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9a8 8 0 0 1 16 0"/><path d="M4 9l8 9 8-9"/><circle cx="12" cy="21" r="1.5"/></svg>',
        'hot-air-balloon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 1 7 7c0 4-3 7-7 7s-7-3-7-7a7 7 0 0 1 7-7z"/><path d="M9 16l1 4h4l1-4"/></svg>',
        'city-tours' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="4" height="11"/><rect x="10" y="3" width="4" height="18"/><rect x="16" y="7" width="4" height="14"/><path d="M2 21h20"/></svg>',
        'night-tours' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>',
        'water-sports' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 19c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/><path d="M12 15V3"/><path d="M12 3c5 1 7 5 7 9h-7"/></svg>',
        'fountain-show' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21V11"/><path d="M12 11c0-4-3-5-3-8"/><path d="M12 11c0-4 3-5 3-8"/><path d="M5 21h14"/><path d="M5 16c0 1 1 2 2 2"/><path d="M19 16c0 1-1 2-2 2"/></svg>',
        'sky-views' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>',
        'food-tours' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
    ];

    return $icons[$slug] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l2 2"/></svg>';
}

// ---------------------------------------------------------------------------
// Helper: Render breadcrumb HTML
// ---------------------------------------------------------------------------

function dubai_render_breadcrumbs(array $crumbs): void
{
    ?>
    <nav class="dubai-breadcrumb" aria-label="Breadcrumb">
        <ol>
            <?php foreach ($crumbs as $i => $crumb): ?>
                <li>
                    <?php if ($i < count($crumbs) - 1): ?>
                        <a href="<?= e($crumb['url']) ?>"><?= e($crumb['name']) ?></a>
                        <span aria-hidden="true">/</span>
                    <?php else: ?>
                        <span aria-current="page"><?= e($crumb['name']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
}

// ---------------------------------------------------------------------------
// Helper: Render FAQ accordion
// ---------------------------------------------------------------------------

function dubai_render_faq(array $faqs, string $heading = 'Frequently Asked Questions'): void
{
    if ($faqs === []) {
        return;
    }
    ?>
    <section class="dubai-faq">
        <div class="container">
            <h2><?= e($heading) ?></h2>
            <div class="dubai-faq__list">
                <?php foreach ($faqs as $faq): ?>
                    <details class="dubai-faq__item">
                        <summary>
                            <h3><?= e($faq['q']) ?></h3>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </summary>
                        <p><?= e($faq['a']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

// ---------------------------------------------------------------------------
// Helper: Star rating SVG
// ---------------------------------------------------------------------------

function dubai_stars_svg(float $rating, int $count = 0): string
{
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.3;
    $star = '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width:14px;height:14px;fill:var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"/></svg>';
    $out = str_repeat($star, $full);
    if ($half) {
        $out .= $star;
    }
    $out .= ' <strong>' . e(number_format($rating, 1)) . '</strong>';
    if ($count > 0) {
        $out .= ' <span class="dubai-rating__count">(' . e(number_format($count)) . ' reviews)</span>';
    }
    return $out;
}

// ===========================================================================
// 1. Dubai Hub Page  /dubai
// ===========================================================================

function render_dubai_hub(HelloTicketsClient $client, array $config, array $dubaiContent): void
{
    $categories = $dubaiContent['categories'] ?? [];
    $faqs = $dubaiContent['hub_faqs'] ?? [];
    $heroImage = $dubaiContent['hub_hero_image'] ?? $config['fallback_images']['hero'];

    $activitiesData = api_result(static fn() => $client->activities([
        'city_id' => 132,
        'limit' => 8,
        'page' => 1,
    ]), ['activities' => []]);
    $topActivities = $activitiesData['activities'] ?? [];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Dubai', 'url' => absolute_url($config, '/dubai')],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                'name' => 'Things to Do in Dubai - Tickets, Tours & Attractions',
                'url' => absolute_url($config, '/dubai'),
                'description' => 'Discover the best things to do in Dubai. Book tickets for attractions, tours, desert safaris, theme parks and more with instant e-tickets and free cancellation on most experiences.',
                // Reference the home page's #website node instead of re-declaring an
                // anonymous copy — disconnected duplicates fragment the entity graph.
                'isPartOf' => ['@id' => $config['site_url'] . '/#website'],
            ],
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ];

    render_layout($config, [
        'title' => 'Things to Do in Dubai — Tickets, Tours & Attractions | ' . $config['site_name'],
        'description' => 'Discover 100+ Dubai attractions. Book tickets for Burj Khalifa, desert safaris, theme parks, cruises and more. Instant e-tickets and free cancellation on most experiences.',
        'canonical' => absolute_url($config, '/dubai'),
        'preload_image' => $heroImage,
        'body_class' => 'dubai-hub-page',
    ], function () use ($config, $categories, $topActivities, $faqs, $heroImage, $breadcrumbs, $dubaiContent): void {
        ?>

        <!-- Hero -->
        <section class="dubai-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1>Things to Do in Dubai &mdash; Tickets, Tours &amp; Attractions</h1>
                <p class="dubai-hub__hero-sub">Skip-the-line tickets and instant confirmation for Dubai's best experiences</p>
                <form class="dubai-hub__search" action="/search" method="get">
                    <input type="search" name="q" placeholder="Search attractions, tours, tickets..." aria-label="Search Dubai attractions">
                    <button type="submit">Search</button>
                </form>
            </div>
        </section>

        <!-- Stats row -->
        <section class="dubai-hub__stats">
            <div class="container">
                <div class="dubai-hub__stats-grid">
                    <div class="dubai-hub__stat">
                        <strong>100+</strong>
                        <span>Attractions &amp; Tours</span>
                    </div>
                    <div class="dubai-hub__stat">
                        <strong>Instant</strong>
                        <span>E-Ticket Delivery</span>
                    </div>
                    <div class="dubai-hub__stat">
                        <strong>Free</strong>
                        <span>Cancellation on many tickets</span>
                    </div>
                    <div class="dubai-hub__stat">
                        <strong>24/7</strong>
                        <span>Partner Support</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category cards grid -->
        <section class="dubai-hub__categories section-band">
            <div class="container">
                <div class="section-heading">
                    <h2>Explore Dubai by Category</h2>
                    <a href="/attractions">See All</a>
                </div>
                <div class="dubai-hub__category-grid">
                    <?php foreach ($categories as $cat): ?>
                        <a class="dubai-hub__category-card" href="/dubai/<?= e($cat['slug']) ?>">
                            <span class="dubai-hub__category-icon" aria-hidden="true">
                                <?= dubai_category_icon($cat['slug']) ?>
                            </span>
                            <strong class="dubai-hub__category-title"><?= e($cat['short_name'] ?? $cat['name']) ?></strong>
                            <span class="dubai-hub__category-sub"><?= e($cat['subtitle'] ?? '') ?></span>
                            <?php if (!empty($cat['activity_count'])): ?>
                                <span class="dubai-hub__category-count"><?= e((string) $cat['activity_count']) ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Featured attractions -->
        <?php if ($topActivities !== []): ?>
            <section class="dubai-hub__featured section-band muted">
                <div class="container">
                    <div class="section-heading">
                        <h2>Top Dubai Attractions</h2>
                        <a href="/attractions">See All</a>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($topActivities as $activity): ?>
                            <?= activity_card($activity, $config) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Why book with us -->
        <section class="dubai-hub__trust section-band">
            <div class="container">
                <h2>Why Book With <?= e($config['site_name']) ?></h2>
                <div class="dubai-hub__trust-grid">
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        <h3>Free Cancellation on Many Tickets</h3>
                        <p>The exact policy for each ticket is shown at partner checkout before you pay.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg>
                        <h3>Instant E-Tickets</h3>
                        <p>Get your tickets delivered straight to your phone. No printing required, just show and go.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 1v4"/><path d="M12 19v4"/><circle cx="12" cy="12" r="7"/><path d="M8.5 12h5l-2-3"/><path d="M13.5 12h-5l2 3"/></svg>
                        <h3>Live Prices</h3>
                        <p>Prices and availability come straight from our ticket partner, so what you see is what you pay.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                        <h3>24/7 Partner Support</h3>
                        <p>Our ticket partner's support team is available around the clock for bookings and changes.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Rich content / guide -->
        <section class="dubai-hub__guide section-band muted">
            <div class="container">
                <div class="dubai-hub__guide-content">
                    <h2>Your Complete Guide to Dubai Attractions</h2>

                    <p>Dubai has transformed from a quiet trading port into one of the world's most visited cities, welcoming over 16 million international tourists each year. The city's skyline, anchored by the 828-metre Burj Khalifa, is only part of the story. From vast desert landscapes just minutes from downtown to indoor ski slopes and record-breaking theme parks, Dubai packs an extraordinary range of experiences into a compact metropolitan area connected by metro, tram and water taxi.</p>

                    <p>First-time visitors will want to start with the iconic observation decks: the At the Top experience at Burj Khalifa and the Sky Views Observatory offer dramatically different perspectives of the city. From there, the historic Al Fahidi District and Dubai Creek provide a window into the emirate's pearl-diving and spice-trading heritage, while the Dubai Mall, Museum of the Future and Dubai Frame bridge old and new in memorable ways.</p>

                    <p>Adventure seekers should look beyond the city centre. Desert safaris combine dune bashing, sandboarding and traditional Bedouin dinners under star-filled skies. The coastline offers yacht charters, jet-ski tours and deep-sea fishing, while Hatta's mountain trails and kayaking routes provide a cooler escape in the winter months. For families, Atlantis Aquaventure, IMG Worlds of Adventure and LEGOLAND offer full days of entertainment with skip-the-line ticket options.</p>

                    <p>Timing matters in Dubai. The peak tourist season runs from November to March when temperatures hover around a pleasant 25 degrees Celsius, making it ideal for outdoor attractions and desert excursions. Summer months (June to September) bring intense heat but also significant discounts on indoor attractions, hotels and dining. Ramadan offers a unique cultural experience, with special iftar events and a slower, more reflective pace of life across the city.</p>
                </div>
            </div>
        </section>

        <!-- Internal links to categories -->
        <section class="dubai-hub__explore section-band">
            <div class="container">
                <h2>Popular Dubai Experiences</h2>
                <div class="dubai-hub__link-grid">
                    <?php foreach ($dubaiContent['attractions'] ?? [] as $att): if (empty($att['slug'])) { continue; } ?>
                        <a class="dubai-hub__link-card" href="/dubai/<?= e($att['category_slug'] ?? 'attractions') ?>/<?= e($att['slug']) ?>">
                            <strong><?= e($att['short_name'] ?? $att['title']) ?></strong>
                            <span><?= e($att['category_short_name'] ?? ($att['category_name'] ?? '')) ?></span>
                        </a>
                    <?php endforeach; ?>
                    <a class="dubai-hub__link-card" href="/abu-dhabi">
                        <strong>Abu Dhabi Day Trips</strong>
                        <span>Louvre, Grand Mosque &amp; more</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <?php dubai_render_faq($faqs, 'Frequently Asked Questions About Dubai'); ?>

        <?php
    }, $schema);
}

// ===========================================================================
// 2. Dubai Category Page  /dubai/{slug}
// ===========================================================================

function render_dubai_category(HelloTicketsClient $client, array $config, array $dubaiContent, string $categorySlug): void
{
    $categories = $dubaiContent['categories'] ?? [];
    $category = null;
    foreach ($categories as $cat) {
        if (($cat['slug'] ?? '') === $categorySlug) {
            $category = $cat;
            break;
        }
    }

    if ($category === null) {
        render_error_page($config, 404, 'Category not found', 'This Dubai category page does not exist.');
        return;
    }

    $heroImage = $category['hero_image'] ?? $config['fallback_images']['hero'];
    $faqs = $category['faqs'] ?? [];
    $highlights = $category['highlights'] ?? [];
    $tips = $category['tips'] ?? [];
    $relatedCategories = array_filter($categories, static fn(array $c): bool => ($c['slug'] ?? '') !== $categorySlug);

    // Fetch the curated activities for this category one ID at a time
    $activityIds = $category['activity_ids'] ?? [];
    $activities = [];
    foreach ($activityIds as $id) {
        $a = api_result(static fn() => $client->activity((int) $id), []);
        if (!empty($a['id'])) {
            $activities[] = $a;
        }
    }
    if ($activities === []) {
        $query = $category['api_query'] ?? $category['name'];
        $data = api_result(static fn() => $client->activities([
            'city_id' => 132,
            'limit' => 24,
            'page' => 1,
            'query' => $query,
        ]), ['activities' => []]);
        $activities = $data['activities'] ?? [];
    }

    $shortName = $category['short_name'] ?? $category['name'];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Dubai', 'url' => absolute_url($config, '/dubai')],
        ['name' => $shortName, 'url' => absolute_url($config, '/dubai/' . $categorySlug)],
    ];

    // ItemList schema for activities
    $itemListElements = [];
    foreach (array_values($activities) as $i => $act) {
        $itemListElements[] = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'url' => absolute_url($config, activity_path($act)),
            'name' => $act['title'] ?? '',
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'TouristAttraction',
                'name' => $category['title'] ?? $category['name'],
                'description' => $category['meta_description'] ?? '',
                'url' => absolute_url($config, '/dubai/' . $categorySlug),
                'touristType' => 'Leisure',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Dubai',
                    'addressCountry' => 'AE',
                ],
            ],
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, $breadcrumbs),
            [
                '@type' => 'ItemList',
                'itemListElement' => $itemListElements,
            ],
        ],
    ];

    $pageTitle = $category['title'] ?? ($category['name'] . ' in Dubai');

    render_layout($config, [
        'title' => $pageTitle . ' | ' . $config['site_name'],
        'description' => $category['meta_description'] ?? ('Find the best ' . strtolower($category['name']) . ' in Dubai. Compare prices, read reviews and book online with instant confirmation.'),
        'canonical' => absolute_url($config, '/dubai/' . $categorySlug),
        'preload_image' => $heroImage,
        'body_class' => 'dubai-category-page',
    ], function () use ($config, $category, $categorySlug, $activities, $faqs, $highlights, $tips, $relatedCategories, $heroImage, $breadcrumbs, $pageTitle, $shortName): void {
        ?>

        <!-- Hero -->
        <section class="dubai-category__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1><?= e($pageTitle) ?></h1>
                <?php if (!empty($category['subtitle'])): ?>
                    <p class="dubai-category__hero-sub"><?= e($category['subtitle']) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Activity listings (directly after the hero) -->
        <?php if ($activities !== []): ?>
            <section class="dubai-category__activities section-band">
                <div class="container">
                    <div class="section-heading">
                        <?php // "{X}: Tickets & Experiences" works for singular landmarks
                              // ("Burj Khalifa") and plural categories ("Waterparks") alike —
                              // "Best Burj Khalifa in Dubai" read as generated boilerplate. ?>
                        <h2><?= e($shortName) ?>: Tickets &amp; Experiences in Dubai</h2>
                        <span><?= e((string) count($activities)) ?> experiences</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($activities as $activity): ?>
                            <?= activity_card($activity, $config) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Highlights -->
        <?php if ($highlights !== []): ?>
            <section class="dubai-category__highlights section-band">
                <div class="container">
                    <h2>Highlights</h2>
                    <ul class="dubai-category__highlights-list">
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
            <section class="dubai-category__tips section-band muted">
                <div class="container">
                    <h2>Tips for Visitors</h2>
                    <div class="dubai-category__tips-grid">
                        <?php foreach ($tips as $tip): ?>
                            <div class="dubai-category__tip">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <p><?= e($tip) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Long-form SEO content (kept low on the page so products lead) -->
        <?php if (!empty($category['intro'])): ?>
            <section class="dubai-category__intro section-band">
                <div class="container">
                    <div class="dubai-category__intro-content">
                        <h2>About <?= e($shortName) ?> in Dubai</h2>
                        <?php
                        $introParts = is_array($category['intro']) ? $category['intro'] : [$category['intro']];
                        foreach ($introParts as $part):
                            if (is_array($part) && !empty($part['heading'])): ?>
                                <h3><?= e($part['heading']) ?></h3>
                                <p><?= e($part['text']) ?></p>
                            <?php elseif (is_string($part)): ?>
                                <p><?= e($part) ?></p>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- FAQ -->
        <?php dubai_render_faq($faqs); ?>

        <!-- Related categories -->
        <?php if ($relatedCategories !== []): ?>
            <section class="dubai-category__related section-band">
                <div class="container">
                    <h2>More Things to Do in Dubai</h2>
                    <?php
                    // Keep the grid balanced: show a multiple of 4 so rows are even (4+4),
                    // never an orphan row like 5+3.
                    $relatedList = array_values($relatedCategories);
                    $relatedList = array_slice($relatedList, 0, (intdiv(min(count($relatedList), 8), 4)) * 4 ?: count($relatedList));
                    ?>
                    <div class="dubai-related-grid">
                        <?php foreach ($relatedList as $related): ?>
                            <a class="dubai-related-card" href="/dubai/<?= e($related['slug']) ?>">
                                <span class="dubai-related-card__img">
                                    <img src="<?= e($related['hero_image'] ?? $config['fallback_images']['hero']) ?>" alt="<?= e($related['short_name'] ?? $related['name']) ?>" loading="lazy">
                                </span>
                                <span class="dubai-related-card__body">
                                    <strong><?= e($related['short_name'] ?? $related['name']) ?></strong>
                                    <span><?= e($related['subtitle'] ?? '') ?></span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- CTA -->
        <section class="dubai-category__cta section-band muted">
            <div class="container">
                <div class="dubai-category__cta-box">
                    <h2>Ready to Explore <?= e($shortName) ?> in Dubai?</h2>
                    <p>Book your tickets now with instant confirmation and secure checkout on our official ticket partner's site.</p>
                    <a class="button-link wide" href="/attractions">Browse All Attractions</a>
                </div>
            </div>
        </section>

        <?php
    }, $schema);
}

// ===========================================================================
// 3. Dubai Attraction Page  /dubai/{category}/{slug}
// ===========================================================================

function render_dubai_attraction(HelloTicketsClient $client, array $config, array $dubaiContent, string $attractionSlug): void
{
    $attractions = $dubaiContent['attractions'] ?? [];
    $attraction = null;
    foreach ($attractions as $att) {
        if (($att['slug'] ?? '') === $attractionSlug) {
            $attraction = $att;
            break;
        }
    }

    if ($attraction === null) {
        render_error_page($config, 404, 'Attraction not found', 'This Dubai attraction page does not exist.');
        return;
    }

    $categorySlug = $attraction['category_slug'] ?? 'attractions';
    $categoryName = $attraction['category_name'] ?? 'Attractions';
    $categoryShort = $attraction['category_short_name'] ?? $categoryName;
    $attractionShort = $attraction['short_name'] ?? ($attraction['title'] ?? '');

    // 301 to the canonical path when the category segment in the URL is wrong
    $requestPath = (string) (parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?: '');
    if (preg_match('#^/dubai/([a-z0-9-]+)/[a-z0-9-]+$#', $requestPath, $segMatch) && $segMatch[1] !== $categorySlug) {
        header('Location: /dubai/' . $categorySlug . '/' . $attractionSlug, true, 301);
        return;
    }

    // Fetch live activity data
    $primaryId = (int) ($attraction['activity_id'] ?? 0);
    $activity = [];
    if ($primaryId > 0) {
        $activity = api_result(static fn() => $client->activity($primaryId), []);
    }

    // Related activity IDs from same category, fetched one at a time
    $relatedIds = $attraction['related_activity_ids'] ?? [];
    $relatedActivities = [];
    if ($relatedIds !== []) {
        foreach ($relatedIds as $relatedId) {
            $a = api_result(static fn() => $client->activity((int) $relatedId), []);
            if (!empty($a['id'])) {
                $relatedActivities[] = $a;
            }
        }
    }
    if ($relatedActivities === [] && !empty($attraction['category_name'])) {
        $data = api_result(static fn() => $client->activities([
            'city_id' => 132,
            'limit' => 8,
            'page' => 1,
            'query' => $attraction['category_name'],
        ]), ['activities' => []]);
        $relatedActivities = $data['activities'] ?? [];
    }

    // Filter out the primary activity from related
    if ($primaryId > 0) {
        $relatedActivities = array_filter($relatedActivities, static fn(array $a): bool => (int) ($a['id'] ?? 0) !== $primaryId);
    }

    $heroImage = $attraction['image'] ?? $config['fallback_images']['hero'];
    $galleryImages = $attraction['gallery'] ?? [];
    $faqs = $attraction['faqs'] ?? [];
    $tips = $attraction['tips'] ?? [];
    $whatToExpect = $attraction['what_to_expect'] ?? [];
    $quickFacts = $attraction['quick_facts'] ?? [];

    // Rating info — only render numbers the API actually supplied
    $rating = !empty($activity['reviews']['avg_rating']) ? (float) $activity['reviews']['avg_rating'] : 0.0;
    $reviewCount = !empty($activity['reviews']['number_of_reviews']) ? (int) $activity['reviews']['number_of_reviews'] : ($attraction['review_count'] ?? 0);
    $price = !empty($activity['from_price']) ? (float) $activity['from_price'] : ($attraction['price_from'] ?? 0);
    $currency = !empty($activity['currency']) ? (string) $activity['currency'] : $config['currency'];

    // Related attractions from content
    $relatedAttractions = [];
    foreach ($attractions as $att) {
        if (($att['slug'] ?? '') !== $attractionSlug && ($att['category_slug'] ?? '') === $categorySlug) {
            $relatedAttractions[] = $att;
        }
    }

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Dubai', 'url' => absolute_url($config, '/dubai')],
        ['name' => $categoryShort, 'url' => absolute_url($config, '/dubai/' . $categorySlug)],
        ['name' => $attractionShort, 'url' => absolute_url($config, '/dubai/' . $categorySlug . '/' . $attractionSlug)],
    ];

    // Build schema
    $schemaGraph = [
        dubai_tourist_attraction_schema($config, $attraction, $activity),
    ];

    // Product (the bookable ticket) referencing the TouristAttraction entity.
    // The rating lives ONLY on the attraction node — duplicating it on both
    // invites review-snippet quality flags for the same entity declared twice.
    $attractionUrl = absolute_url($config, '/dubai/' . $categorySlug . '/' . $attractionSlug);
    $productSchema = [
        '@type' => 'Product',
        '@id' => $attractionUrl . '#tickets',
        'name' => $attraction['title'],
        'description' => $attraction['meta_description'] ?? '',
        'image' => $heroImage,
        'mainEntityOfPage' => $attractionUrl,
        'brand' => [
            '@type' => 'Brand',
            'name' => $config['site_name'],
        ],
    ];
    if ($price > 0) {
        // Offer emitted only while the live API returns a bookable price, so
        // InStock is availability-gated rather than asserted blindly.
        $productSchema['offers'] = [
            '@type' => 'Offer',
            'url' => $attractionUrl,
            'price' => $price,
            'priceCurrency' => $currency,
            'availability' => 'https://schema.org/InStock',
        ];
    }
    $schemaGraph[] = $productSchema;

    if ($faqs !== []) {
        $schemaGraph[] = dubai_faq_schema($faqs);
    }
    $schemaGraph[] = dubai_breadcrumb_schema($config, $breadcrumbs);

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => $schemaGraph,
    ];

    render_layout($config, [
        'title' => $attraction['title'] . ' — Tickets & Prices | ' . $config['site_name'],
        'description' => $attraction['meta_description'] ?? ('Book ' . $attraction['title'] . ' tickets online. Skip the line with instant e-tickets and free cancellation on most experiences.'),
        'canonical' => absolute_url($config, '/dubai/' . $categorySlug . '/' . $attractionSlug),
        'preload_image' => $heroImage,
        'body_class' => 'attraction-detail-page',
    ], function () use ($config, $attraction, $activity, $categorySlug, $categoryName, $categoryShort, $attractionShort, $attractionSlug, $heroImage, $galleryImages, $faqs, $tips, $whatToExpect, $quickFacts, $rating, $reviewCount, $price, $currency, $relatedActivities, $relatedAttractions, $breadcrumbs, $client): void {
        ?>

        <!-- Hero -->
        <section class="attraction-detail__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1><?= e($attraction['title']) ?></h1>
                <div class="attraction-detail__hero-meta">
                    <?php if ($rating > 0): ?>
                        <span class="attraction-detail__rating">
                            <?= dubai_stars_svg($rating, $reviewCount) ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($price > 0): ?>
                        <span class="attraction-detail__price-badge">From <?= e(money($price, $currency)) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Image gallery -->
        <?php if ($galleryImages !== []): ?>
            <section class="attraction-detail__gallery section-band">
                <div class="container">
                    <div class="attraction-detail__gallery-grid">
                        <div class="attraction-detail__gallery-main">
                            <img src="<?= e($heroImage) ?>" alt="<?= e($attraction['title']) ?>" loading="lazy">
                        </div>
                        <?php foreach (array_slice($galleryImages, 0, 4) as $gImg): ?>
                            <div class="attraction-detail__gallery-thumb">
                                <img src="<?= e($gImg) ?>" alt="<?= e($attraction['title']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Main content + sidebar -->
        <section class="attraction-detail__main section-band">
            <div class="container">
                <div class="attraction-detail__grid">
                    <!-- Content column -->
                    <div class="attraction-detail__content">

                        <!-- What to expect -->
                        <?php if ($whatToExpect !== []): ?>
                            <div class="attraction-detail__section">
                                <h2>What to Expect</h2>
                                <ul class="attraction-detail__expect-list">
                                    <?php foreach ($whatToExpect as $point): ?>
                                        <li>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                            <?= e($point) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Intro / description -->
                        <?php if (!empty($attraction['intro'])): ?>
                            <div class="attraction-detail__section">
                                <h2>About <?= e($attractionShort) ?></h2>
                                <?php
                                $introParts = is_array($attraction['intro']) ? $attraction['intro'] : [$attraction['intro']];
                                foreach ($introParts as $part):
                                    if (is_array($part) && !empty($part['heading'])): ?>
                                        <h3><?= e($part['heading']) ?></h3>
                                        <p><?= e($part['text']) ?></p>
                                    <?php elseif (is_string($part)): ?>
                                        <p><?= e($part) ?></p>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Ticket options from API -->
                        <?php if (!empty($activity['id'])): ?>
                            <div class="attraction-detail__section">
                                <h2>Ticket Options</h2>
                                <div class="attraction-detail__ticket-card">
                                    <div class="attraction-detail__ticket-info">
                                        <strong><?= e($activity['title'] ?? $attraction['title']) ?></strong>
                                        <p><?= e($activity['supplier_name'] ?? 'Official ticket partner') ?></p>
                                        <?php if (!empty($activity['cancellation_policy'])): ?>
                                            <span class="attraction-detail__cancel-badge">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:14px;height:14px"><polyline points="20 6 9 17 4 12"/></svg>
                                                <?= e(strip_tags((string) $activity['cancellation_policy'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="attraction-detail__ticket-action">
                                        <?php if ($price > 0): ?>
                                            <span class="attraction-detail__ticket-price">From <?= e(money($price, $currency)) ?></span>
                                        <?php endif; ?>
                                        <a class="button-link" href="<?= e(go_url($activity, 'activity')) ?>" rel="sponsored nofollow">Check Availability</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Related ticket variants -->
                        <?php
                        $variants = array_slice(array_values($relatedActivities), 0, 4);
                        if ($variants !== []): ?>
                            <div class="attraction-detail__section">
                                <h2>Related <?= e($categoryShort) ?> Tickets</h2>
                                <div class="attraction-detail__variants">
                                    <?php foreach ($variants as $variant): ?>
                                        <div class="attraction-detail__variant-card">
                                            <div>
                                                <strong><a href="<?= e(activity_path($variant)) ?>"><?= e($variant['title'] ?? '') ?></a></strong>
                                                <span><?= e($variant['supplier_name'] ?? '') ?></span>
                                            </div>
                                            <div class="attraction-detail__variant-action">
                                                <span><?= e(money($variant['from_price'] ?? 0, (string) ($variant['currency'] ?? $currency))) ?></span>
                                                <a class="button-link small" href="<?= e(go_url($variant, 'activity')) ?>" rel="sponsored nofollow">Book</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tips -->
                        <?php if ($tips !== []): ?>
                            <div class="attraction-detail__section">
                                <h2>Tips for Visiting <?= e($attractionShort) ?></h2>
                                <div class="dubai-category__tips-grid">
                                    <?php foreach ($tips as $tip): ?>
                                        <div class="dubai-category__tip">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                            <p><?= e($tip) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- FAQ -->
                        <?php dubai_render_faq($faqs); ?>
                    </div>

                    <!-- Sidebar -->
                    <aside class="attraction-detail__sidebar">
                        <!-- Quick facts -->
                        <div class="attraction-detail__quick-facts">
                            <h3>Quick Facts</h3>
                            <dl>
                                <?php if ($price > 0): ?>
                                    <div><dt>Price from</dt><dd><?= e(money($price, $currency)) ?></dd></div>
                                <?php endif; ?>
                                <?php if (!empty($quickFacts['duration'])): ?>
                                    <div><dt>Duration</dt><dd><?= e($quickFacts['duration']) ?></dd></div>
                                <?php endif; ?>
                                <?php if (!empty($quickFacts['best_time'])): ?>
                                    <div><dt>Best time</dt><dd><?= e($quickFacts['best_time']) ?></dd></div>
                                <?php endif; ?>
                                <?php if (!empty($quickFacts['location'])): ?>
                                    <div><dt>Location</dt><dd><?= e($quickFacts['location']) ?></dd></div>
                                <?php endif; ?>
                                <?php if (!empty($activity['cancellation_policy'])): ?>
                                    <div><dt>Cancellation</dt><dd><?= e(strip_tags((string) $activity['cancellation_policy'])) ?></dd></div>
                                <?php endif; ?>
                                <?php if ($reviewCount > 0): ?>
                                    <div><dt>Rating</dt><dd><?= e(number_format($rating, 1)) ?>/5 (<?= e(number_format($reviewCount)) ?> reviews)</dd></div>
                                <?php endif; ?>
                            </dl>
                        </div>

                        <!-- Booking CTA -->
                        <?php if (!empty($activity['id'])): ?>
                            <div class="attraction-detail__book-panel">
                                <span class="price-label">Tickets From</span>
                                <strong><?= e(money($price, $currency)) ?></strong>
                                <a class="button-link wide" href="<?= e(go_url($activity, 'activity')) ?>" rel="sponsored nofollow">Check Availability</a>
                                <p class="checkout-note">Secure checkout on our official ticket partner's site. Instant e-tickets delivered to your phone. We may earn a commission &mdash; at no extra cost to you.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Related attractions links -->
                        <?php if ($relatedAttractions !== []): ?>
                            <div class="attraction-detail__related-links">
                                <h3>Related Attractions</h3>
                                <ul>
                                    <?php foreach (array_slice($relatedAttractions, 0, 6) as $rel): ?>
                                        <li><a href="/dubai/<?= e($rel['category_slug'] ?? $categorySlug) ?>/<?= e($rel['slug']) ?>"><?= e($rel['short_name'] ?? $rel['title']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Category link -->
                        <div class="attraction-detail__category-link">
                            <a href="/dubai/<?= e($categorySlug) ?>">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
                                All <?= e($categoryShort) ?>
                            </a>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <!-- More attractions rail -->
        <?php if ($relatedActivities !== []): ?>
            <?php render_card_section('More ' . $categoryShort . ' in Dubai', '/dubai/' . $categorySlug, array_values($relatedActivities), 'activity', $config); ?>
        <?php endif; ?>

        <?php
    }, $schema);
}

// ===========================================================================
// 4. Abu Dhabi Hub Page  /abu-dhabi
// ===========================================================================

function render_abu_dhabi_hub(HelloTicketsClient $client, array $config, array $dubaiContent): void
{
    $heroImage = $dubaiContent['abu_dhabi_hero_image'] ?? 'https://images.unsplash.com/photo-1587302912306-cf1ed9c33146?auto=format&fit=crop&w=1800&q=80';
    $faqs = $dubaiContent['abu_dhabi_faqs'] ?? [
        ['q' => 'How far is Abu Dhabi from Dubai?', 'a' => 'Abu Dhabi is approximately 130 km from Dubai, about a 90-minute drive via the E11 highway. Many tour operators offer convenient hotel pick-up and drop-off services from Dubai.'],
        ['q' => 'Can I visit Abu Dhabi on a day trip from Dubai?', 'a' => 'Yes, a day trip is very popular. Most guided tours depart Dubai early morning and return by evening, covering top attractions like the Sheikh Zayed Grand Mosque, Louvre Abu Dhabi and Yas Island.'],
        ['q' => 'What is the best time to visit Abu Dhabi?', 'a' => 'November to March offers the most comfortable weather with temperatures around 20-25 degrees Celsius. This is ideal for outdoor sightseeing and desert activities.'],
        ['q' => 'Do I need a separate visa for Abu Dhabi?', 'a' => 'No, Abu Dhabi is in the same country as Dubai (UAE). Your Dubai visa or visa-free entry covers the entire UAE including Abu Dhabi.'],
    ];

    $activitiesData = api_result(static fn() => $client->activities([
        'city_id' => 256,
        'limit' => 21,
        'page' => 1,
    ]), ['activities' => []]);
    $activities = $activitiesData['activities'] ?? [];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Abu Dhabi', 'url' => absolute_url($config, '/abu-dhabi')],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebPage',
                'name' => 'Things to Do in Abu Dhabi - Tours, Tickets & Day Trips from Dubai',
                'url' => absolute_url($config, '/abu-dhabi'),
                'description' => 'Explore Abu Dhabi from Dubai. Book tickets for Sheikh Zayed Grand Mosque, Louvre Abu Dhabi, Ferrari World, Yas Waterworld and more.',
            ],
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ];

    render_layout($config, [
        'title' => 'Things to Do in Abu Dhabi — Tours & Day Trips | ' . $config['site_name'],
        'description' => 'Book Abu Dhabi tours and tickets from Dubai. Visit Sheikh Zayed Grand Mosque, Louvre Abu Dhabi, Ferrari World and more with instant e-tickets and free cancellation on most experiences.',
        'canonical' => absolute_url($config, '/abu-dhabi'),
        'preload_image' => $heroImage,
        'body_class' => 'abu-dhabi-hub-page',
    ], function () use ($config, $activities, $faqs, $heroImage, $breadcrumbs): void {
        ?>

        <!-- Hero -->
        <section class="dubai-hub__hero abu-dhabi-hub__hero" style="background-image: linear-gradient(160deg, rgba(0,0,0,.55) 0%, rgba(0,0,0,.2) 100%), url('<?= e($heroImage) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <h1>Things to Do in Abu Dhabi &mdash; Tours, Tickets &amp; Day Trips</h1>
                <p class="dubai-hub__hero-sub">Explore the UAE capital with skip-the-line tickets and guided tours from Dubai</p>
            </div>
        </section>

        <!-- Stats -->
        <section class="dubai-hub__stats">
            <div class="container">
                <div class="dubai-hub__stats-grid">
                    <?php if (count($activities) > 0): ?>
                        <div class="dubai-hub__stat">
                            <strong><?= e((string) count($activities)) ?>+</strong>
                            <span>Activities</span>
                        </div>
                    <?php endif; ?>
                    <div class="dubai-hub__stat">
                        <strong>90 min</strong>
                        <span>From Dubai</span>
                    </div>
                    <div class="dubai-hub__stat">
                        <strong>Instant</strong>
                        <span>E-Tickets</span>
                    </div>
                    <div class="dubai-hub__stat">
                        <strong>Free</strong>
                        <span>Cancellation on many tickets</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Activities -->
        <?php if ($activities !== []): ?>
            <section class="abu-dhabi-hub__activities section-band">
                <div class="container">
                    <div class="section-heading">
                        <h2>Top Abu Dhabi Attractions &amp; Tours</h2>
                        <span><?= e((string) count($activities)) ?> experiences</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($activities as $activity): ?>
                            <?= activity_card($activity, $config) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- About Abu Dhabi -->
        <section class="dubai-hub__guide section-band muted">
            <div class="container">
                <div class="dubai-hub__guide-content">
                    <h2>Visiting Abu Dhabi from Dubai</h2>

                    <p>Abu Dhabi, the capital of the United Arab Emirates, offers a striking contrast to Dubai's glittering modernity. Just 90 minutes down the coast, the city balances world-class cultural institutions with dramatic desert landscapes and a thriving food scene. For Dubai visitors, a day trip to Abu Dhabi is one of the most rewarding excursions available.</p>

                    <p>The Sheikh Zayed Grand Mosque is the undisputed highlight, with its 82 white domes, gold-plated chandeliers and the world's largest hand-knotted carpet. Nearby, the Louvre Abu Dhabi showcases centuries of art beneath Jean Nouvel's spectacular dome of light. For thrill-seekers, Yas Island delivers with Ferrari World (home to the world's fastest roller coaster), Yas Waterworld and Warner Bros. World.</p>

                    <p>Most Abu Dhabi day tours from Dubai include hotel pick-up, air-conditioned transport and a guide who covers history, architecture and local customs. Basic mosque visits sit at the budget end, while full-day packages that combine multiple attractions with lunch cost more — every tour card on this page shows its live starting price. Booking online with instant confirmation guarantees your spot.</p>
                </div>
            </div>
        </section>

        <!-- Why Book -->
        <section class="dubai-hub__trust section-band">
            <div class="container">
                <h2>Why Book Abu Dhabi Tours With Us</h2>
                <div class="dubai-hub__trust-grid">
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <h3>Dubai Hotel Pick-up</h3>
                        <p>Most tours include convenient pick-up and drop-off from your Dubai hotel, so you can relax on the drive.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        <h3>Free Cancellation on Many Tickets</h3>
                        <p>The exact policy for each ticket is shown at partner checkout before you pay.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><line x1="13" y1="5" x2="13" y2="7"/><line x1="13" y1="11" x2="13" y2="13"/><line x1="13" y1="17" x2="13" y2="19"/></svg>
                        <h3>Instant E-Tickets</h3>
                        <p>Your booking confirmation is sent immediately. Just show it on your phone at the meeting point.</p>
                    </div>
                    <div class="dubai-hub__trust-card">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <h3>Skip the Line</h3>
                        <p>Pre-booked tickets let you bypass queues at Abu Dhabi's busiest attractions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Link back to Dubai -->
        <section class="abu-dhabi-hub__crosslink section-band muted">
            <div class="container">
                <div class="dubai-category__cta-box">
                    <h2>Exploring Dubai Too?</h2>
                    <p>Browse 100+ attractions, theme parks, desert safaris and more across Dubai.</p>
                    <a class="button-link wide" href="/dubai">Things to Do in Dubai</a>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <?php dubai_render_faq($faqs, 'Abu Dhabi Travel FAQ'); ?>

        <?php
    }, $schema);
}
