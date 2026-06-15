<?php
declare(strict_types=1);

/**
 * build-cdn-image-map.php — Build a performer→CDN-URL map by matching
 * HelloTickets performers against Ticketmaster Discovery attractions.
 *
 * Outputs storage/tm-images.json with CDN URLs (not local files), so the
 * live site renders <img src="https://s1.ticketm.net/…"> directly — no
 * 107 MB media upload needed.
 *
 *   php bin/build-cdn-image-map.php
 *   php bin/build-cdn-image-map.php --pages=6   # more artist pages (default 4)
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';
require $root . '/src/HelloTicketsClient.php';
require $root . '/src/TicketmasterClient.php';

$opts  = getopt('', ['pages:']);
$pages = max(1, min(20, (int) ($opts['pages'] ?? 4)));

$htClient = new HelloTicketsClient(
    $config['api_base_url'], $config['api_key'], $config['currency'],
    $config['locale'], $config['cache_dir'], $config['cache_ttl']
);

$keys = $config['tm_api_keys'] ?: ($config['tm_api_key'] !== '' ? [$config['tm_api_key']] : []);
if ($keys === []) {
    fwrite(STDERR, "No Ticketmaster keys found. Set tm_api_keys in config.local.php.\n");
    exit(1);
}
$keyCount  = count($keys);
$keyCursor = 0;

$mapFile = $root . '/storage/tm-images.json';
$map = is_file($mapFile) ? (json_decode((string) file_get_contents($mapFile), true) ?: []) : [];
$startCount = count($map);

function tm_search(string $name, array &$keys, int &$cursor): ?string
{
    $keyCount = count($keys);
    $params = http_build_query([
        'apikey'   => $keys[$cursor % $keyCount],
        'keyword'  => $name,
        'size'     => 1,
        'locale'   => '*',
    ]);
    $cursor++;
    $url = 'https://app.ticketmaster.com/discovery/v2/attractions.json?' . $params;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_CONNECTTIMEOUT => 6,
        CURLOPT_USERAGENT      => 'TheTicketers-cdn-map/1.0',
    ]);
    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status === 429) {
        usleep(600000);
        return tm_search($name, $keys, $cursor);
    }
    if ($status !== 200 || !is_string($body)) {
        return null;
    }
    $json = json_decode($body, true);
    $attractions = $json['_embedded']['attractions'] ?? [];
    if (empty($attractions[0]['images'])) {
        return null;
    }
    return tm_pick_best_image($attractions[0]['images']);
}

function tm_pick_best_image(array $images): ?string
{
    $best = null;
    $bestScore = PHP_INT_MIN;
    foreach ($images as $img) {
        if (empty($img['url'])) continue;
        $url = (string) $img['url'];
        if (str_contains($url, '_SOURCE')) continue;
        $w = (int) ($img['width'] ?? 0);
        $score = $w
            + (empty($img['fallback']) ? 100000 : 0)
            + (($img['ratio'] ?? '') === '3_2' ? 8000 : 0)
            + (($img['ratio'] ?? '') === '16_9' ? 5000 : 0)
            + ($w >= 500 && $w <= 1200 ? 3000 : 0);
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $url;
        }
    }
    return $best;
}

echo "Fetching performers from HelloTickets ({$pages} pages)…\n";
$allPerformers = [];
for ($p = 1; $p <= $pages; $p++) {
    $data = $htClient->performers(['page' => $p, 'limit' => 48]);
    $batch = $data['performers'] ?? [];
    if ($batch === []) break;
    $allPerformers = array_merge($allPerformers, $batch);
    echo "  Page {$p}: " . count($batch) . " performers\n";
}
echo "Total HT performers: " . count($allPerformers) . "\n\n";

$found = 0;
$skipped = 0;
$missed = 0;

foreach ($allPerformers as $i => $perf) {
    $id   = (int) ($perf['id'] ?? 0);
    $name = (string) ($perf['name'] ?? '');
    $key  = 'performer-' . $id;
    if ($id <= 0 || $name === '') continue;

    if (!empty($map[$key]) && str_starts_with($map[$key], 'https://')) {
        $skipped++;
        continue;
    }

    $cdnUrl = tm_search($name, $keys, $keyCursor);
    if ($cdnUrl !== null) {
        $map[$key] = $cdnUrl;
        $found++;
        echo "  ✓ {$name} (#{$id})\n";
    } else {
        $missed++;
        echo "  ✗ {$name} (#{$id}) — no TM match\n";
    }

    if (($i + 1) % 5 === 0) {
        usleep(200000);
    }
}

foreach ($map as $k => $v) {
    if (str_starts_with($v, '/assets/media/')) {
        unset($map[$k]);
    }
}

ksort($map);
$json = json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if ($json === false) {
    fwrite(STDERR, "json_encode failed for $mapFile\n");
    exit(1);
}
// Atomic write: a concurrent image_map() read must never see a truncated file.
$json .= "\n";
$tmp = $mapFile . '.tmp.' . getmypid();
if (@file_put_contents($tmp, $json) === strlen($json)) {
    @rename($tmp, $mapFile);
} else {
    @unlink($tmp);
    fwrite(STDERR, "short write to $mapFile\n");
    exit(1);
}
$finalCount = count($map);

echo "\nDone. Found: {$found}, Skipped (already CDN): {$skipped}, No match: {$missed}\n";
echo "Map entries: {$finalCount} (was {$startCount})\n";
echo "Written to {$mapFile}\n";
