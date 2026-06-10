<?php
declare(strict_types=1);

$config = [
    'site_name' => getenv('SITE_NAME') ?: 'TickedBus',
    'site_tagline' => 'Dubai events, attractions and experiences',
    // SITE_URL env wins; otherwise derive from the live host so canonicals/sitemap/og
    // never silently ship as "localhost" on a shared host that can't set env vars.
    'site_url' => rtrim(getenv('SITE_URL') ?: (
        ($_SERVER['HTTP_HOST'] ?? '') !== ''
            ? ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'])
            : 'http://localhost:8000'
    ), '/'),
    'api_base_url' => rtrim(getenv('HELLOTICKETS_API_URL') ?: 'https://api-live.hellotickets.com', '/'),
    'api_key' => getenv('HELLOTICKETS_PUBLIC_KEY') ?: 'pub-bcaaca28-c7df-4fc1-9274-61a0f1439d13',
    'impact_url' => getenv('IMPACT_BASE_URL') ?: 'https://hellotickets.sjv.io/MKNd7K',
    'currency' => getenv('HELLOTICKETS_CURRENCY') ?: 'AED',
    'locale' => getenv('HELLOTICKETS_LOCALE') ?: 'en-GB',
    'default_city_id' => 132,
    'default_city_name' => 'Dubai',
    'cache_dir' => __DIR__ . '/../storage/cache',
    'cache_ttl' => 3600,
    // UAE flagship cities are hardcoded; every other market city is derived below
    // from src/destinations-content.json — the single source of truth for city
    // coverage (PHP, the Node preview mirror and app.js geo all read from it).
    'market_cities' => [
        ['id' => 132, 'name' => 'Dubai', 'state' => 'Dubai', 'country' => 'United Arab Emirates', 'country_code' => 'ARE', 'featured' => true],
        ['id' => 256, 'name' => 'Abu Dhabi', 'state' => 'Abu Dhabi', 'country' => 'United Arab Emirates', 'country_code' => 'ARE', 'featured' => true],
    ],
    // Country markets — drives /{country} and /{country}/{city} SEO hubs, nav and
    // footer groupings. city_ids are filled from the content pack below.
    'markets' => [
        'usa'    => ['name' => 'United States',  'country_code' => 'USA', 'city_ids' => []],
        'canada' => ['name' => 'Canada',         'country_code' => 'CAN', 'city_ids' => []],
        'uk'     => ['name' => 'United Kingdom',  'country_code' => 'GBR', 'city_ids' => []],
        'italy'  => ['name' => 'Italy',           'country_code' => 'ITA', 'city_ids' => []],
        'spain'  => ['name' => 'Spain',           'country_code' => 'ESP', 'city_ids' => []],
        'france' => ['name' => 'France',          'country_code' => 'FRA', 'city_ids' => []],
    ],
    // The activities API has NO category filter — only free-text `query`. So activity
    // category pages search by a representative keyword. Empty/missing => list all city
    // activities (a valid "browse" experience), never an empty page.
    'activity_category_queries' => [
        20 => 'cruise',        // Canal Cruises
        13 => 'cruise',        // Cruises
        28 => 'sailing',       // Cruises and Sailing
        33 => 'boat',          // Speedboats and Cruises
        24 => 'gondola',       // Gondola Rides
        29 => 'desert',        // Desert Experiences
        15 => 'museum',        // Museums
        16 => 'tower',         // Landmarks and Skyscrapers
        21 => 'food',          // Food and Wine Tours
        8  => 'food',          // Food and Drink
        22 => 'cooking',       // Cooking Classes
        10 => 'tour',          // Tours
        9  => 'tour',          // City Escapes
        32 => 'night',         // Nightlife
        18 => 'disney',        // Disneyland
        36 => 'pass',          // Attraction Passes
        6  => 'transfer',      // Transfers
    ],
    'fallback_images' => [
        'hero' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1800&q=80',
        'activity' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=1000&q=80',
        'event' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1000&q=80',
        'Concerts' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1000&q=80',
        'Theatre' => 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=1000&q=80',
        'Sports' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=1000&q=80',
        'burj' => 'https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=1000&q=80',
        'waterpark' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
        'desert' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9?auto=format&fit=crop&w=1000&q=80',
        'aquarium' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=1000&q=80',
        'cruise' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1000&q=80',
    ],
];

// Derive market cities and per-country city lists from the destinations content
// pack. Order in the JSON cities map controls display order; entries flagged
// "featured" appear in the header city picker and the first-visit modal.
$destinationsJson = @file_get_contents(__DIR__ . '/destinations-content.json');
$destinationsPack = $destinationsJson !== false ? json_decode($destinationsJson, true) : null;
foreach (($destinationsPack['cities'] ?? []) as $packCity) {
    if (empty($packCity['city_id'])) {
        continue;
    }
    $config['market_cities'][] = [
        'id' => (int) $packCity['city_id'],
        'name' => (string) $packCity['name'],
        'state' => (string) ($packCity['state'] ?? ''),
        'country' => (string) ($packCity['country'] ?? ''),
        'country_code' => (string) ($packCity['country_code'] ?? ''),
        'featured' => !empty($packCity['featured']),
        'slug' => (string) ($packCity['slug'] ?? ''),
        'country_slug' => (string) ($packCity['country_slug'] ?? ''),
    ];
    $countrySlug = (string) ($packCity['country_slug'] ?? '');
    if ($countrySlug !== '' && isset($config['markets'][$countrySlug])) {
        $config['markets'][$countrySlug]['city_ids'][] = (int) $packCity['city_id'];
    }
}

return $config;
