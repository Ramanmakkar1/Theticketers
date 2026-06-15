<?php
declare(strict_types=1);

/**
 * fetch-tm-images.php — Download content-matched ARTIST and EVENT images from the
 * Ticketmaster Discovery API and store them ON OUR SERVER under assets/media/.
 *
 * The HelloTickets API has no images. Activities are covered by enrich-images.php;
 * this script covers artists (which HelloTickets can't provide at all) and events
 * that have no HelloTickets cover, by matching each item's NAME against Ticketmaster
 * (which has rich, content-matched artwork). The downloaded file paths are written
 * to storage/tm-images.json as {type-id => /assets/media/file}; mapped_image() /
 * image_from_item() read that map at render time, so NO API call happens per request.
 *
 * Run on the host (cron, e.g. weekly). Key via --key or the TICKETMASTER_API_KEY env:
 *   php bin/fetch-tm-images.php --key=XXXX                 # artists + Dubai events
 *   php bin/fetch-tm-images.php --key=XXXX --all           # + every market city's events
 *   php bin/fetch-tm-images.php --key=XXXX --artists       # artists only
 *   php bin/fetch-tm-images.php --key=XXXX --events --all  # events only, all cities
 *   php bin/fetch-tm-images.php --key=XXXX --pages=6       # artist pages to scan (48/page)
 *   php bin/fetch-tm-images.php --key=XXXX --refresh       # re-download known items
 *
 * Ticketmaster ToS note: their images stay theirs. Re-run periodically to refresh,
 * and delete assets/media on request — keep this as a cache, not a permanent archive.
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';
require $root . '/src/HelloTicketsClient.php';
require $root . '/bin/resize-media.php'; // tb_resize_image() — shrink on download

$opts = getopt('', ['key:', 'all', 'artists', 'events', 'pages:', 'city:', 'refresh', 'quiet']);
$TM_KEY = (string) ($opts['key'] ?? (getenv('TICKETMASTER_API_KEY') ?: ($config['tm_api_key'] ?? '')));
if ($TM_KEY === '') {
    fwrite(STDERR, "Missing Ticketmaster key. Pass --key=XXXX, set TICKETMASTER_API_KEY, or create src/config.local.php.\n");
    exit(1);
}
$refresh = isset($opts['refresh']);
$quiet = isset($opts['quiet']);
$doArtists = isset($opts['artists']) || !isset($opts['events']);
$doEvents = isset($opts['events']) || !isset($opts['artists']);
$artistPages = max(1, min(40, (int) ($opts['pages'] ?? 3)));
$all = isset($opts['all']);

$client = new HelloTicketsClient(
    $config['api_base_url'], $config['api_key'], $config['currency'],
    $config['locale'], $config['cache_dir'], $config['cache_ttl']
);

$mediaDir = $root . '/assets/media';
if (!is_dir($mediaDir)) {
    mkdir($mediaDir, 0775, true);
}
$mapFile = $root . '/storage/tm-images.json';
$map = is_file($mapFile) ? (json_decode((string) file_get_contents($mapFile), true) ?: []) : [];
// HelloTickets covers (real event posters) win over TM artist art — skip those events.
$htMap = is_file($root . '/storage/images.json')
    ? (json_decode((string) file_get_contents($root . '/storage/images.json'), true) ?: [])
    : [];

function say(bool $quiet, string $line): void
{
    if (!$quiet) {
        fwrite(STDOUT, $line . "\n");
    }
}

/** GET a Ticketmaster Discovery endpoint; backs off on 429 with bounded exponential retry. */
function tm_get(string $path, array $params, int $attempt = 0): ?array
{
    global $TM_KEY;
    $params['apikey'] = $TM_KEY;
    $url = 'https://app.ticketmaster.com/discovery/v2/' . $path . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_USERAGENT => 'TheTicketers-image-cache/1.0',
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code === 429) {
        if ($attempt >= 4) {
            return null;
        }
        sleep(1 << $attempt);
        return tm_get($path, $params, $attempt + 1);
    }
    if ($code !== 200 || !is_string($body)) {
        return null;
    }
    $json = json_decode($body, true);
    return is_array($json) ? $json : null;
}

/** Search TM attractions by name; cache by normalized name (events reuse artist art). */
$attractionCache = [];
function tm_attraction_image(string $name): ?string
{
    global $attractionCache;
    $norm = mb_strtolower(trim($name));
    if ($norm === '') {
        return null;
    }
    if (array_key_exists($norm, $attractionCache)) {
        return $attractionCache[$norm];
    }
    $json = tm_get('attractions.json', ['keyword' => $name, 'size' => 5, 'sort' => 'relevance,desc']);
    usleep(230000); // ~4.3 req/s, under TM's 5/s cap
    $best = null;
    $bestScore = -1;
    foreach ($json['_embedded']['attractions'] ?? [] as $a) {
        $img = tm_best_image($a['images'] ?? []);
        if ($img === null) {
            continue;
        }
        $segment = (string) ($a['classifications'][0]['segment']['name'] ?? '');
        $upcoming = (int) ($a['upcomingEvents']['_total'] ?? 0);
        $hasReal = false;
        foreach ($a['images'] ?? [] as $im) {
            if (empty($im['fallback'])) { $hasReal = true; break; }
        }
        $score = ($segment === 'Music' ? 1000 : 0) + min($upcoming, 500) + ($hasReal ? 3000 : 0);
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = $img;
        }
    }
    return $attractionCache[$norm] = $best;
}

/** Download an image to assets/media/{key}.{ext}; returns the web path or null. */
function tm_download(string $url, string $key, string $mediaDir): ?string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_USERAGENT => 'TheTicketers-image-cache/1.0',
    ]);
    $data = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ctype = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    if ($code !== 200 || !is_string($data) || strlen($data) < 2000 || stripos($ctype, 'image/') !== 0) {
        return null;
    }
    $ext = stripos($ctype, 'png') !== false ? 'png' : (stripos($ctype, 'webp') !== false ? 'webp' : 'jpg');
    $file = $key . '.' . $ext;
    if (file_put_contents($mediaDir . '/' . $file, $data) === false) {
        return null;
    }
    // Shrink to web size on the way in (TM originals are ~2400px / 500KB).
    $resized = tb_resize_image($mediaDir . '/' . $file);
    if ($resized !== null) {
        $file = $resized;
    }
    return '/assets/media/' . $file;
}

$writeMap = static function () use (&$map, $mapFile): void {
    ksort($map);
    $tmp = $mapFile . '.tmp' . getmypid();
    if (file_put_contents($tmp, json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) === false) {
        @unlink($tmp);
        return;
    }
    rename($tmp, $mapFile);
};

$hits = 0;
$miss = 0;
$processed = 0;

$alreadyHave = static function (string $key) use (&$map, $refresh, $root): bool {
    if ($refresh || !isset($map[$key])) {
        return false;
    }
    // Trust the map only if the file is actually present.
    return is_file($root . $map[$key]);
};

// ---- Artists ----
if ($doArtists) {
    for ($p = 1; $p <= $artistPages; $p++) {
        $performers = api_result(static fn() => $client->performers(['limit' => 48, 'page' => $p]), ['performers' => []])['performers'] ?? [];
        if ($performers === []) {
            break;
        }
        foreach ($performers as $perf) {
            $id = (int) ($perf['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $key = 'performer-' . $id;
            if ($alreadyHave($key)) {
                continue;
            }
            $name = (string) ($perf['name'] ?? '');
            $img = tm_attraction_image($name);
            $local = $img !== null ? tm_download($img, $key, $mediaDir) : null;
            if ($local !== null) {
                $map[$key] = $local;
                $hits++;
                say($quiet, "OK  $key  $name");
            } else {
                $miss++;
                say($quiet, "--  $key  $name (no TM image)");
            }
            if (++$processed % 20 === 0) {
                $writeMap();
                say($quiet, "   .. checkpoint ($processed processed, " . count($map) . ' in map)');
            }
        }
    }
}

// ---- Events (use the headline performer's TM artwork; skip events with a HelloTickets cover) ----
if ($doEvents) {
    $cityIds = $all
        ? array_map(static fn($c) => (int) $c['id'], $config['market_cities'])
        : [(int) ($opts['city'] ?? $config['default_city_id'])];
    foreach ($cityIds as $cid) {
        $events = api_result(static fn() => $client->performances(array_merge([
            'limit' => 50, 'page' => 1, 'is_sellable' => 'true', 'city_id' => $cid,
        ], date_params(null))), ['performances' => []])['performances'] ?? [];
        foreach ($events as $ev) {
            $id = (int) ($ev['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $key = 'event-' . $id;
            if (isset($htMap[$key]) || $alreadyHave($key)) {
                continue; // real HelloTickets poster already covers it
            }
            $mainName = '';
            foreach ($ev['performers'] ?? [] as $pf) {
                if (!empty($pf['is_main'])) { $mainName = (string) ($pf['name'] ?? ''); break; }
            }
            if ($mainName === '') {
                $mainName = (string) ($ev['performers'][0]['name'] ?? $ev['name'] ?? '');
            }
            $img = $mainName !== '' ? tm_attraction_image($mainName) : null;
            if ($img === null) {
                $json = tm_get('events.json', ['keyword' => (string) ($ev['name'] ?? ''), 'size' => 3, 'sort' => 'relevance,desc']);
                usleep(230000);
                foreach ($json['_embedded']['events'] ?? [] as $e) {
                    $img = tm_best_image($e['images'] ?? []);
                    if ($img !== null) { break; }
                }
            }
            $local = $img !== null ? tm_download($img, $key, $mediaDir) : null;
            if ($local !== null) {
                $map[$key] = $local;
                $hits++;
                say($quiet, "OK  $key  " . ($ev['name'] ?? ''));
            } else {
                $miss++;
                say($quiet, "--  $key  " . ($ev['name'] ?? '') . ' (no TM image)');
            }
            if (++$processed % 20 === 0) {
                $writeMap();
                say($quiet, "   .. checkpoint ($processed processed, " . count($map) . ' in map)');
            }
        }
    }
}

$writeMap();
say($quiet, "done: $hits downloaded, $miss missed | total in map: " . count($map));
