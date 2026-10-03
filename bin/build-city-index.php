<?php
declare(strict_types=1);

/**
 * build-city-index.php — Probe live event inventory for every geo-detectable city
 * and write storage/city-index.json so the runtime knows which city pages are worth
 * indexing WITHOUT making API calls per request.
 *
 * The /city/{slug} and /events/this-weekend-in-{slug} pages already 404 at render
 * time when a city's inventory is thin (so we never publish doorway pages). But the
 * SITEMAP and internal-linking surfaces must NOT call the APIs 75× on every build —
 * so this script pre-computes the gate. Output shape:
 *   { "generated_at": "2026-06-11",
 *     "cities": { "101": {"events": 220, "months": [10,11,12],
 *                         "today": 4, "week": 17,
 *                         "categories": {"sports": 61, "concerts": 40}}, … } }
 * city_has_inventory() / city_event_count() / city_has_date_inventory() /
 * city_has_month_inventory() / city_has_category_inventory() in helpers.php read it; if
 * the file is absent — or a city is missing one of the optional keys, which is what a
 * failed probe records — they report NO inventory (fail closed), so the file must exist for
 * geo-city links, date-intent links, month arrows, city×category links and sitemap
 * URLs. Run this before/with the deploy, and whenever a surface needs a fresher answer
 * than the file carries.
 *
 * The `months` / `today` / `week` keys are measured on the SAME windows the renderers
 * use, by calling the same helpers: month_numbers() + city_months_with_events() for the
 * month list, date_params() for the HelloTickets windows and tm_local_start_range() for
 * the Ticketmaster ones. The count probes are count-only (limit/size=1), so they cost
 * one small response each and never pull a catalogue the runtime would have to re-derive.
 *
 * Run on the host (cron, e.g. daily/weekly), AFTER the API key is configured:
 *   php bin/build-city-index.php
 *   php bin/build-city-index.php --min=5     # inventory threshold to list a city (default 5)
 */

// Same clock as index.php. date_params()/date_bounds() resolve "today" in the default
// timezone, so a builder running on UTC would measure a window that is a day behind the
// window the site renders for part of every evening.
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Asia/Dubai');

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';
require $root . '/src/HelloTicketsClient.php';
require $root . '/src/TicketmasterClient.php';

$opts = getopt('', ['min::']);
$minInventory = isset($opts['min']) ? max(1, (int) $opts['min']) : 5;

$client = new HelloTicketsClient(
    $config['api_base_url'],
    $config['api_key'],
    $config['currency'],
    $config['locale'],
    $config['cache_dir'],
    $config['cache_ttl']
);

// Probe every geo city plus the hardcoded market cities (Dubai/Abu Dhabi).
$targets = [];
foreach (geo_cities() as $id => $geo) {
    $targets[(int) $id] = [
        'name' => (string) ($geo['name'] ?? ''),
        'country_code' => (string) ($geo['country_code'] ?? ''),
    ];
}
foreach ($config['market_cities'] as $mc) {
    $targets[(int) $mc['id']] = [
        'name' => (string) $mc['name'],
        'country_code' => (string) ($mc['country_code'] ?? ''),
    ];
}

$cities = [];
$kept = 0;
$withDateInventory = 0;
$withMonthInventory = 0;
$withCategoryInventory = 0;

/**
 * Count-only HelloTickets probe: limit=1 returns the partner's own total for the window,
 * so the date-intent gate costs one small response instead of a catalogue.
 * null = the call failed (rate limit, upstream blip). A null must NOT be recorded as 0 —
 * 0 is a measured answer that fails the gate on purpose, null means "unknown", and
 * unknown has to leave the key out so the reader fails closed instead of trusting it.
 */
$countEventsInWindow = static function (int $cityId, string $dateKey) use ($client): ?int {
    try {
        $data = $client->performances(array_merge([
            'limit' => 1,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $cityId,
        ], date_params($dateKey)));
    } catch (Throwable $exception) {
        fwrite(STDERR, sprintf("  ! %s probe failed: %s\n", $dateKey, $exception->getMessage()));
        return null;
    }
    if (!is_numeric($data['total_count'] ?? null)) {
        return null;
    }
    return (int) $data['total_count'];
};

/** Ticketmaster equivalent of the probe above: page.totalElements over the same window. */
$countTmEventsInWindow = static function (string $name, string $countryCode3, string $dateKey) use ($config): ?int {
    $tm = tm_client($config);
    if ($tm === null) {
        return null;
    }
    $params = [
        'city' => $name,
        'size' => 1,
        'page' => 0,
        'localStartDateTime' => tm_local_start_range($dateKey),
    ];
    $alpha2 = tm_country_code($countryCode3);
    if ($alpha2 !== '') {
        $params['countryCode'] = $alpha2;
    }
    $raw = api_result(static fn() => $tm->events($params), []);
    return is_numeric($raw['page']['totalElements'] ?? null) ? (int) $raw['page']['totalElements'] : null;
};

/**
 * Count-only probe for ONE intent category, on the same window city_category_events()
 * renders (date_params(null), i.e. today..+1y) and over the same category ids /
 * Ticketmaster classification names that helper uses. The two partners are counted
 * separately and the HIGHER total is stored, which — exactly as for today/week — can only
 * under-report the renderer's merged pool, never over-report it. So a category link shown
 * from this number is a link that will render.
 *
 * Every category with no probeable source (the seven intent categories that resolve only
 * through Ticketmaster classifications, on a host with no TM key) returns null, and null
 * leaves the key out so city_has_category_inventory() fails closed.
 */
$countCategoryEvents = static function (int $cityId, string $cityName, string $countryCode3, string $categorySlug) use ($config, $client): ?int {
    $categories = city_intent_categories();
    if (!isset($categories[$categorySlug])) {
        return null;
    }
    $counts = [];
    foreach ($categories[$categorySlug]['ht_category_ids'] as $categoryId) {
        try {
            $data = $client->performances(array_merge([
                'limit' => 1,
                'page' => 1,
                'is_sellable' => 'true',
                'city_id' => $cityId,
                'category_id' => (int) $categoryId,
            ], date_params(null)));
        } catch (Throwable $exception) {
            fwrite(STDERR, sprintf("  ! %s probe failed: %s\n", $categorySlug, $exception->getMessage()));
            continue;
        }
        if (is_numeric($data['total_count'] ?? null)) {
            $counts[] = (int) $data['total_count'];
        }
    }
    $tm = tm_client($config);
    if ($tm !== null) {
        $alpha2 = tm_country_code($countryCode3);
        foreach ($categories[$categorySlug]['tm_classification_names'] as $classificationName) {
            $params = ['city' => $cityName, 'size' => 1, 'page' => 0, 'classificationName' => $classificationName];
            if ($alpha2 !== '') {
                $params['countryCode'] = $alpha2;
            }
            $raw = api_result(static fn() => $tm->events($params), []);
            if (is_numeric($raw['page']['totalElements'] ?? null)) {
                $counts[] = (int) $raw['page']['totalElements'];
            }
        }
    }
    return $counts === [] ? null : max($counts);
};

foreach ($targets as $id => $meta) {
    $name = $meta['name'];
    if ($name === '') {
        continue;
    }

    // HelloTickets — read the reported total, fall back to the returned page size.
    $ht = api_result(static fn() => $client->performances(array_merge([
        'limit' => 24,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $id,
    ], date_params(null))), ['performances' => [], 'total_count' => 0]);
    $htCount = max((int) ($ht['total_count'] ?? 0), count($ht['performances'] ?? []));

    // Ticketmaster — deep city pull, the same shape render_city_page() asks for (two
    // pages of 100). Its length is the Ticketmaster half of the inventory total, and the
    // events themselves are reused below for the month list, so month coverage here can
    // never be narrower than the city hub's own "Events by Month" grid.
    $tmEvents = tm_events_for_city_deep($config, $name, $meta['country_code'], [], 2, 100);
    $tmCount = count($tmEvents);

    $total = $htCount + $tmCount;
    $reportMonths = '-';
    $reportToday = '-';
    $reportWeek = '-';
    $reportCats = '-';
    if ($total >= $minInventory) {
        $entry = ['events' => $total];

        // Months with real inventory: /events/{month}-in-{city} 404s an empty month, so
        // neither the hub's month grid nor a month page's prev/next arrow may name one.
        // Same helper, same target-year rule, same pool the hub builds.
        $monthNumbers = month_numbers();
        $months = [];
        foreach (city_months_with_events(city_event_pool($ht['performances'] ?? [], $tmEvents, $config)) as $monthSlug) {
            $months[] = $monthNumbers[$monthSlug];
        }
        sort($months);
        $entry['months'] = $months;
        $withMonthInventory++;
        $reportMonths = $months === [] ? '-' : implode(',', $months);

        // Date-intent pages 404 below city_date_min_events(), and the hub used to link
        // them blind. Store the higher of the two partner totals for the window: the
        // renderer's pool is at least as large as either partner's own count, so this can
        // under-report (hide a link that would have rendered) but cannot over-report a
        // page that will 404.
        foreach (['today', 'week'] as $dateKey) {
            $counts = array_values(array_filter([
                $countEventsInWindow($id, $dateKey),
                $countTmEventsInWindow($name, $meta['country_code'], $dateKey),
            ], static fn(?int $count): bool => $count !== null));
            if ($counts !== []) {
                $entry[$dateKey] = max($counts);
            }
        }
        if (isset($entry['today']) || isset($entry['week'])) {
            $withDateInventory++;
        }
        $reportToday = isset($entry['today']) ? (string) $entry['today'] : '-';
        $reportWeek = isset($entry['week']) ? (string) $entry['week'] : '-';

        // City×category inventory. render_city_category_page() 404s below
        // city_category_min_events(), and every hub used to link all ten intent categories
        // unconditionally — so each city shipped a dead link per category it cannot fill.
        $categoryCounts = [];
        foreach (array_keys(city_intent_categories()) as $categorySlug) {
            $count = $countCategoryEvents($id, $name, $meta['country_code'], (string) $categorySlug);
            if ($count !== null) {
                $categoryCounts[(string) $categorySlug] = $count;
            }
        }
        $entry['categories'] = $categoryCounts;
        $withCategoryInventory++;
        $reportCats = $categoryCounts === []
            ? '-'
            : implode(' ', array_map(
                static fn(string $slug, int $count): string => $slug . '=' . $count,
                array_keys($categoryCounts),
                array_values($categoryCounts)
            ));

        $cities[(string) $id] = $entry;
        $kept++;
    }
    fwrite(STDERR, sprintf(
        "%-22s id=%-5d HT=%-4d TM=%-4d => %-4s months=%-20s today=%-4s week=%-4s cats=%s\n",
        $name,
        $id,
        $htCount,
        $tmCount,
        $total >= $minInventory ? 'KEEP' : 'skip',
        $reportMonths,
        $reportToday,
        $reportWeek,
        $reportCats
    ));
}

$payload = [
    'generated_at' => gmdate('Y-m-d'),
    'min_inventory' => $minInventory,
    'cities' => $cities,
];

$outFile = rtrim((string) $config['cache_dir'], '/') . '/../city-index.json';
$storageDir = dirname($outFile);
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0775, true);
}
$json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($json === false) {
    fwrite(STDERR, "json_encode failed for $outFile\n");
    exit(1);
}
// Atomic write: a partial read by city_index() would briefly report no cities at all
// (city_has_inventory() fails closed), so readers must never see a truncated file. Keep
// the temp file in the same dir as $outFile so rename() stays on one filesystem.
$tmp = dirname($outFile) . '/city-index.json.tmp.' . getmypid();
if (@file_put_contents($tmp, $json) === strlen($json)) {
    @rename($tmp, $outFile);
} else {
    @unlink($tmp);
    fwrite(STDERR, "short write to $outFile\n");
    exit(1);
}

fwrite(STDERR, sprintf("\nWrote %s — %d cities with >=%d inventory.\n", $outFile, $kept, $minInventory));
// A '-' in the per-city columns above means the probe failed (usually a partner rate
// limit), NOT that the city is empty. Those cities keep their events count but carry no
// months/date keys, so the runtime hides those links until the next clean run — say so
// loudly, because a throttled build silently unpublishes every date and month link.
fwrite(STDERR, sprintf(
    "  %d/%d cities carry month inventory, %d/%d carry date inventory, %d/%d carry category inventory (a '-' above is a failed probe, not an empty city).\n",
    $withMonthInventory,
    $kept,
    $withDateInventory,
    $kept,
    $withCategoryInventory,
    $kept
));
fwrite(STDERR, sprintf(
    "  Until this runs, no city\u00d7category link is emitted at all (city_has_category_inventory fails closed on a missing key) \u2014 that is the safe direction, not a regression.\n"
));
