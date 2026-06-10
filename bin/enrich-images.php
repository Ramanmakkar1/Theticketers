<?php
declare(strict_types=1);

/**
 * enrich-images.php — Harvest real hero images for inventory.
 *
 * The HelloTickets API returns NO image fields. Each item's public page
 * (item.url) does carry a real photo on the Tiqets imgix CDN. This script
 * fetches those pages and stores a {type-id => image_url} map in
 * storage/images.json, which image_from_item() reads at render time.
 *
 * Run on the host via cron (e.g. weekly) or manually:
 *   php bin/enrich-images.php                 # Dubai activities + events
 *   php bin/enrich-images.php --all           # every market city + events
 *   php bin/enrich-images.php --city=256      # one city
 *   php bin/enrich-images.php --limit=80      # how many activities per city
 *   php bin/enrich-images.php --refresh       # re-harvest even known ids
 *
 * Never called during a web request — page render only reads images.json.
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';
require $root . '/src/HelloTicketsClient.php';

$opts = getopt('', ['all', 'city:', 'limit:', 'events', 'no-events', 'refresh', 'quiet']);
$all = isset($opts['all']);
$refresh = isset($opts['refresh']);
$quiet = isset($opts['quiet']);
$withEvents = $all || isset($opts['events']) || !isset($opts['no-events']);
$perCity = max(1, min(100, (int) ($opts['limit'] ?? 60)));

$client = new HelloTicketsClient(
    $config['api_base_url'],
    $config['api_key'],
    $config['currency'],
    $config['locale'],
    $config['cache_dir'],
    $config['cache_ttl']
);

$mapFile = $root . '/storage/images.json';
$map = is_file($mapFile) ? (json_decode((string) file_get_contents($mapFile), true) ?: []) : [];

function say(bool $quiet, string $line): void
{
    if (!$quiet) {
        fwrite(STDOUT, $line . "\n");
    }
}

/** Fetch a HelloTickets page and pull the first real Tiqets imgix photo. */
function harvest_image(string $pageUrl): ?string
{
    if ($pageUrl === '') {
        return null;
    }
    $ch = curl_init($pageUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    if (!is_string($html) || $html === '') {
        return null;
    }
    if (preg_match('#https://aws-tiqets-cdn\.imgix\.net/images/content/[a-f0-9]+\.(?:jpe?g|png|webp)#i', $html, $m)) {
        // Poster crop, optimised + compressed by imgix on the fly.
        return $m[0] . '?w=600&h=900&fit=crop&crop=edges&auto=format,compress';
    }
    return null;
}

// ---- Gather inventory to enrich ----
$cityIds = $all
    ? array_map(static fn($c) => (int) $c['id'], $config['market_cities'])
    : [(int) ($opts['city'] ?? $config['default_city_id'])];

$items = []; // ['type'=>..,'id'=>..,'url'=>..]
foreach ($cityIds as $cid) {
    try {
        $data = $client->activities(['limit' => min(50, $perCity), 'page' => 1, 'city_id' => $cid]);
        foreach ($data['activities'] ?? [] as $a) {
            $items[] = ['type' => 'activity', 'id' => (int) ($a['id'] ?? 0), 'url' => (string) ($a['url'] ?? '')];
        }
        // second page if a deeper harvest was requested
        if ($perCity > 50) {
            $data2 = $client->activities(['limit' => min(50, $perCity - 50), 'page' => 2, 'city_id' => $cid]);
            foreach ($data2['activities'] ?? [] as $a) {
                $items[] = ['type' => 'activity', 'id' => (int) ($a['id'] ?? 0), 'url' => (string) ($a['url'] ?? '')];
            }
        }
    } catch (Throwable $e) {
        say($quiet, "!! activities city $cid: " . $e->getMessage());
    }
}

if ($withEvents) {
    $eventQueries = [[], ['city_id' => (int) $config['default_city_id']]];
    foreach ($eventQueries as $extra) {
        try {
            $params = array_merge(['limit' => 50, 'page' => 1, 'is_sellable' => 'true'], date_params(null), $extra);
            $data = $client->performances($params);
            foreach ($data['performances'] ?? [] as $p) {
                $items[] = ['type' => 'event', 'id' => (int) ($p['id'] ?? 0), 'url' => (string) ($p['url'] ?? '')];
            }
        } catch (Throwable $e) {
            say($quiet, '!! performances: ' . $e->getMessage());
        }
    }
}

// ---- Harvest ----
$hits = 0;
$miss = 0;
$skip = 0;
$seen = [];
foreach ($items as $it) {
    $id = $it['id'];
    if ($id <= 0) {
        continue;
    }
    $key = $it['type'] . '-' . $id;
    if (isset($seen[$key])) {
        continue;
    }
    $seen[$key] = true;

    if (!$refresh && isset($map[$key])) {
        $skip++;
        continue;
    }
    $img = harvest_image($it['url']);
    if ($img !== null) {
        $map[$key] = $img;
        $hits++;
        say($quiet, "OK  $key");
    } else {
        $miss++;
        say($quiet, "--  $key (no image found)");
    }
    usleep(900000); // ~1 request/sec, polite to HelloTickets
}

// ---- Persist atomically ----
ksort($map);
$tmp = $mapFile . '.tmp';
file_put_contents($tmp, json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
rename($tmp, $mapFile);

say($quiet, "done: $hits new, $skip already had, $miss missed | total in map: " . count($map));
