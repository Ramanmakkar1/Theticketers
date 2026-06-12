<?php
declare(strict_types=1);

/**
 * build-seo-index.php — Phase 1 programmatic SEO inventory.
 *
 * Generates storage/seo-index.json from real HelloTickets + Ticketmaster inventory.
 * Runtime pages still gate themselves, but sitemaps should only advertise URLs that
 * have already passed basic inventory checks.
 *
 * Typical cron:
 *   php bin/build-seo-index.php
 *
 * Useful knobs:
 *   php bin/build-seo-index.php --events=3000 --artists=5000 --cities=75
 *   php bin/build-seo-index.php --venues=1500 --artist-cities=5000
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';
require $root . '/src/HelloTicketsClient.php';
require $root . '/src/TicketmasterClient.php';
require $root . '/src/pages.php';

$opts = getopt('', [
    'events::',
    'artists::',
    'cities::',
    'venues::',
    'artist-cities::',
    'city-categories::',
    'quiet',
]);

$eventLimit = max(0, (int) ($opts['events'] ?? 3000));
$artistLimit = max(0, (int) ($opts['artists'] ?? 5000));
$cityLimit = max(1, (int) ($opts['cities'] ?? 75));
$venueLimit = max(0, (int) ($opts['venues'] ?? 1500));
$artistCityLimit = max(0, (int) ($opts['artist-cities'] ?? 5000));
$cityCategoryLimit = max(0, (int) ($opts['city-categories'] ?? 500));
$quiet = isset($opts['quiet']);

$client = new HelloTicketsClient(
    $config['api_base_url'],
    $config['api_key'],
    $config['currency'],
    $config['locale'],
    $config['cache_dir'],
    $config['cache_ttl']
);

$urls = [
    'events' => [],
    'artists' => [],
    'artist_cities' => [],
    'venues' => [],
    'city_dates' => [],
    'city_categories' => [],
];
$maps = [
    'event' => [],
    'artist' => [],
    'venue' => [],
    'tm_artist' => [],
];

$seen = [];
$knownArtistSlugs = [];
$add = static function (string $bucket, string $path, int $limit = PHP_INT_MAX) use (&$urls, &$seen): void {
    if ($path === '' || count($urls[$bucket]) >= $limit) {
        return;
    }
    $key = $bucket . '|' . $path;
    if (isset($seen[$key])) {
        return;
    }
    $seen[$key] = true;
    $urls[$bucket][] = $path;
};

$say = static function (string $line) use ($quiet): void {
    if (!$quiet) {
        fwrite(STDERR, $line . "\n");
    }
};

$teamSlugs = [];
if (function_exists('team_seed_list')) {
    foreach (team_seed_list() as [$teamName]) {
        $teamSlugs[slugify((string) $teamName)] = true;
    }
}

$addEventEntities = static function (array $event) use (&$add, &$teamSlugs, &$knownArtistSlugs, &$maps, $config, $artistLimit, $artistCityLimit, $venueLimit): void {
    $cityName = trim((string) ($event['venue']['city'] ?? ''));
    $citySlug = slugify($cityName);

    if (!empty($event['venue']['tm_id'])) {
        $venueSlug = slugify((string) ($event['venue']['name'] ?? ''));
        if ($venueSlug !== 'tickets') {
            $maps['venue'][$venueSlug] = (string) $event['venue']['tm_id'];
        }
        $venuePath = tm_venue_path([
            'tm_id' => (string) $event['venue']['tm_id'],
            'name' => (string) ($event['venue']['name'] ?? ''),
        ]);
        $add('venues', $venuePath, $venueLimit);
    }

    foreach ($event['performers'] ?? [] as $performer) {
        $name = trim((string) ($performer['name'] ?? ''));
        if ($name === '') {
            continue;
        }
        $artistSlug = slugify($name);
        if ($artistSlug === 'tickets' || ctype_digit($artistSlug)) {
            continue;
        }
        $tmArtistId = (string) ($performer['tm_id'] ?? '');
        if ($tmArtistId !== '') {
            tm_artist_slug_remember($artistSlug, $tmArtistId);
            $maps['tm_artist'][$artistSlug] = $tmArtistId;
            $knownArtistSlugs[$artistSlug] = true;
        } elseif (!isset($knownArtistSlugs[$artistSlug])) {
            continue;
        }
        $artistPath = isset($performer['id']) && (int) $performer['id'] > 0
            ? artist_path(['id' => (int) $performer['id'], 'name' => $name])
            : '/artist/' . $artistSlug;
        if (isset($performer['id']) && (int) $performer['id'] > 0) {
            $maps['artist'][$artistSlug] = (int) $performer['id'];
        }

        if (!isset($teamSlugs[$artistSlug])) {
            $add('artists', $artistPath, $artistLimit);
        }
        if ($citySlug !== '' && resolve_city_id_by_slug($config, $citySlug) !== null) {
            $add('artist_cities', $artistPath . '/' . $citySlug, $artistCityLimit);
        }
    }
};

// Local event detail pages: HelloTickets only. TM events currently resolve to the
// partner URL, not a local /event/{slug} detail page.
$say('Collecting HelloTickets event pages...');
for ($page = 1; count($urls['events']) < $eventLimit && $page <= 150; $page++) {
    $data = api_result(static fn() => $client->performances(array_merge([
        'limit' => 48,
        'page' => $page,
        'is_sellable' => 'true',
    ], date_params(null))), ['performances' => []]);
    $events = $data['performances'] ?? [];
    if ($events === []) {
        break;
    }
    foreach ($events as $event) {
        if ((int) ($event['id'] ?? 0) <= 0) {
            continue;
        }
        $maps['event'][event_slug($event)] = (int) $event['id'];
        $add('events', event_path($event), $eventLimit);
        $addEventEntities($event);
        if (count($urls['events']) >= $eventLimit) {
            break;
        }
    }
}
$say('Events: ' . count($urls['events']));

// Artist index from HelloTickets performer pages.
$say('Collecting artist pages...');
for ($page = 1; count($urls['artists']) < $artistLimit && $page <= 120; $page++) {
    $data = api_result(static fn() => $client->performers([
        'limit' => 48,
        'page' => $page,
    ]), ['performers' => []]);
    $performers = $data['performers'] ?? [];
    if ($performers === []) {
        break;
    }
    foreach ($performers as $performer) {
        $name = trim((string) ($performer['name'] ?? ''));
        if ($name === '') {
            continue;
        }
        $slug = slugify($name);
        if ($slug === 'tickets' || ctype_digit($slug)) {
            continue;
        }
        if (isset($teamSlugs[$slug])) {
            continue;
        }
        $knownArtistSlugs[$slug] = true;
        if ((int) ($performer['id'] ?? 0) > 0) {
            $maps['artist'][$slug] = (int) $performer['id'];
        }
        $add('artists', artist_path($performer), $artistLimit);
    }
}
$say('Artists: ' . count($urls['artists']));

// City targets: highest-inventory cities first when storage/city-index.json exists.
$cityTargets = [];
$cityIndex = city_index();
if ($cityIndex !== null) {
    $ranked = $cityIndex['cities'] ?? [];
    uasort($ranked, static fn(array $a, array $b): int => ((int) ($b['events'] ?? 0)) <=> ((int) ($a['events'] ?? 0)));
    foreach ($ranked as $id => $row) {
        $city = city_for_id((int) $id, $config);
        if (!empty($city['name'])) {
            $cityTargets[(int) $id] = $city;
        }
        if (count($cityTargets) >= $cityLimit) {
            break;
        }
    }
}
foreach ($config['market_cities'] as $city) {
    if (count($cityTargets) >= $cityLimit) {
        break;
    }
    $cityTargets[(int) $city['id']] = $city;
}
foreach (geo_cities() as $id => $geo) {
    if (count($cityTargets) >= $cityLimit) {
        break;
    }
    $gid = (int) $id;
    if ($cityIndex !== null && !isset(($cityIndex['cities'] ?? [])[(string) $gid])) {
        continue;
    }
    $cityTargets[$gid] = city_for_id($gid, $config);
}

$say('Collecting city/date/category, venue, and artist-city pages from ' . count($cityTargets) . ' cities...');
foreach ($cityTargets as $cityId => $city) {
    $cityName = (string) ($city['name'] ?? '');
    if ($cityName === '') {
        continue;
    }

    // Broad city pool learns venues and artist-city combinations.
    $ht = api_result(static fn() => $client->performances(array_merge([
        'limit' => 48,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => (int) $cityId,
    ], date_params(null))), ['performances' => []])['performances'] ?? [];
    $tm = tm_events_for_city_deep($config, $cityName, (string) ($city['country_code'] ?? ''), [], 3, 100);
    foreach (array_slice(city_event_pool($ht, $tm, $config), 0, 300) as $event) {
        $addEventEntities($event);
    }

    foreach (['today' => 1, 'week' => 3] as $dateKey => $minEvents) {
        $events = city_date_events($client, $config, (int) $cityId, (string) $dateKey, 1);
        if (count($events) >= $minEvents) {
            $add('city_dates', city_date_path($city, (string) $dateKey));
            foreach (array_slice($events, 0, 120) as $event) {
                $addEventEntities($event);
            }
        }
    }

    foreach (array_keys(city_intent_categories()) as $categorySlug) {
        if (count($urls['city_categories']) >= $cityCategoryLimit) {
            break;
        }
        $events = city_category_events($client, $config, (int) $cityId, $categorySlug, 1);
        if (count($events) >= 3) {
            $add('city_categories', city_category_path($city, $categorySlug), $cityCategoryLimit);
            foreach (array_slice($events, 0, 120) as $event) {
                $addEventEntities($event);
            }
        }
    }

    $say(sprintf(
        '%-22s events=%4d artists=%4d artist-city=%4d venues=%4d city-date=%3d city-cat=%3d',
        $cityName,
        count($urls['events']),
        count($urls['artists']),
        count($urls['artist_cities']),
        count($urls['venues']),
        count($urls['city_dates']),
        count($urls['city_categories'])
    ));
}

foreach ($urls as $bucket => $bucketUrls) {
    sort($bucketUrls);
    $urls[$bucket] = array_values(array_unique($bucketUrls));
}
foreach ($maps as $type => $entries) {
    ksort($entries);
    $maps[$type] = $entries;
}

slug_map_flush();

$payload = [
    'generated_at' => gmdate('Y-m-d'),
    'limits' => [
        'events' => $eventLimit,
        'artists' => $artistLimit,
        'cities' => $cityLimit,
        'venues' => $venueLimit,
        'artist_cities' => $artistCityLimit,
        'city_categories' => $cityCategoryLimit,
    ],
    'counts' => array_map('count', $urls),
    'maps' => $maps,
    'urls' => $urls,
];

$outFile = seo_index_file();
if (!is_dir(dirname($outFile))) {
    @mkdir(dirname($outFile), 0775, true);
}
$tmp = $outFile . '.tmp';
file_put_contents($tmp, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
rename($tmp, $outFile);

$say('');
$say('Wrote ' . $outFile);
foreach ($payload['counts'] as $bucket => $count) {
    $say(sprintf('%-16s %5d', $bucket . ':', $count));
}
