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
 *   { "generated_at": "2026-06-11", "cities": { "101": {"events": 220} , ... } }
 * city_has_inventory() / city_event_count() in helpers.php read it; if the file is
 * absent they assume every geo city qualifies (the render-time gate still protects us).
 *
 * Run on the host (cron, e.g. daily/weekly), AFTER the API key is configured:
 *   php bin/build-city-index.php
 *   php bin/build-city-index.php --min=5     # inventory threshold to list a city (default 5)
 */

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

    // Ticketmaster — one page is enough to confirm real inventory exists.
    $tmCount = count(tm_events_for_city($config, $name, $meta['country_code'], [], 100));

    $total = $htCount + $tmCount;
    if ($total >= $minInventory) {
        $cities[(string) $id] = ['events' => $total];
        $kept++;
    }
    fwrite(STDERR, sprintf("%-22s id=%-5d HT=%-4d TM=%-4d => %s\n", $name, $id, $htCount, $tmCount, $total >= $minInventory ? 'KEEP' : 'skip'));
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
// Atomic write: a partial read by city_index() fails OPEN and would briefly
// expose doorway city pages, so readers must never see a truncated file. Keep
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
