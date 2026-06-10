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

/** Fetch a HelloTickets page and pull its real per-item hero photo. */
function harvest_image(string $pageUrl): ?string
{
    static $cache = [];
    if ($pageUrl === '') {
        return null;
    }
    if (array_key_exists($pageUrl, $cache)) {
        return $cache[$pageUrl]; // many performances share one event page
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
    $cache[$pageUrl] = (is_string($html) && $html !== '') ? extract_hero($html) : null;
    return $cache[$pageUrl];
}

/** Pull the best per-item hero from page HTML (activity Tiqets + event Cloudinary). */
function extract_hero(string $html): ?string
{
    // 1) JSON-LD structured-data image (authoritative on Tiqets activity pages).
    if (preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $html, $blocks)) {
        foreach ($blocks[1] as $block) {
            if (preg_match('#"image"\s*:\s*"([^"]+)"#', $block, $m)
                || preg_match('#"image"\s*:\s*\[\s*"([^"]+)"#', $block, $m)) {
                return normalize_hero(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5));
            }
        }
    }

    // 2) og:image — the event page's real Cloudinary cover lives here.
    if (preg_match('#<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']#i', $html, $m)
        || preg_match('#<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']#i', $html, $m)) {
        $url = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5);
        if (stripos($url, 'static.hellotickets.com') === false) { // skip the site logo
            return normalize_hero($url);
        }
    }

    // 3) Cloudinary hello-tickets content image.
    if (preg_match('#https://res\.cloudinary\.com/hello-tickets/image/upload/[^"\s>\\\\]+#i', $html, $m)) {
        return normalize_hero($m[0]);
    }

    // 4) Tiqets imgix content image.
    if (preg_match('#https://aws-tiqets-cdn\.imgix\.net/images/content/[a-f0-9]+\.(?:jpe?g|png|webp)#i', $html, $m)) {
        return normalize_hero($m[0]);
    }

    return null;
}

/** Consistent hero URL: imgix gets a poster crop; Cloudinary forced to full size. */
function normalize_hero(string $url): string
{
    if (stripos($url, 'imgix.net') !== false) {
        $base = explode('?', $url, 2)[0];
        return $base . '?w=600&h=900&fit=crop&crop=edges&auto=format,compress';
    }
    if (stripos($url, 'res.cloudinary.com') !== false) {
        // Force a large render of the same asset (avoid the page's h_140 thumbnail).
        return preg_replace('#/image/upload/[a-z][^/]*,[^/]+/#', '/image/upload/c_limit,f_auto,q_auto,w_1300/', $url) ?? $url;
    }
    return $url;
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
    // Events are harvested for every market city (+ a global pass) so concerts in
    // Toronto/NYC/etc. get real covers, not just Dubai.
    $eventQueries = [[]];
    foreach ($config['market_cities'] as $mc) {
        $eventQueries[] = ['city_id' => (int) $mc['id']];
    }
    foreach ($eventQueries as $extra) {
        try {
            $params = array_merge(['limit' => 50, 'page' => 1, 'is_sellable' => 'true'], date_params(null), $extra);
            $data = $client->performances($params);
            foreach ($data['performances'] ?? [] as $p) {
                // The API performance url is an imageless JS date-page; the EVENT page
                // (…/{slug}-tickets/{event_id}) carries the real og:image cover.
                $perfUrl = (string) ($p['url'] ?? '');
                $eventId = (int) ($p['event_id'] ?? 0);
                $eventPageUrl = ($eventId > 0 && str_contains($perfUrl, '-tickets/'))
                    ? preg_replace('#(-tickets)/.*$#', '$1/' . $eventId, $perfUrl)
                    : $perfUrl;
                $items[] = ['type' => 'event', 'id' => (int) ($p['id'] ?? 0), 'url' => (string) $eventPageUrl];
            }
        } catch (Throwable $e) {
            say($quiet, '!! performances: ' . $e->getMessage());
        }
    }
}

// ---- Harvest (incremental checkpoint + resume) ----
// Background jobs are capped (~10 min) and can be killed mid-run, so we persist
// every CHECKPOINT_EVERY items and track a per-pass "done" set. A re-run with the
// same flags resumes where it left off instead of starting over.
const CHECKPOINT_EVERY = 20;
$progressFile = $root . '/storage/.enrich-progress.json';

$writeMap = static function () use (&$map, $mapFile): void {
    ksort($map);
    $tmp = $mapFile . '.tmp';
    file_put_contents($tmp, json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    rename($tmp, $mapFile);
};

// On --refresh we re-fetch everything, so we can't tell "done" from the map alone;
// a progress file records which keys this pass has already handled.
$done = ($refresh && is_file($progressFile))
    ? array_flip(json_decode((string) file_get_contents($progressFile), true) ?: [])
    : [];

$hits = 0;
$miss = 0;
$skip = 0;
$processed = 0;
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

    // Skip already-handled work: known map entry (normal) or done-this-pass (refresh).
    if ((!$refresh && isset($map[$key])) || ($refresh && isset($done[$key]))) {
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
    $done[$key] = true;

    if (++$processed % CHECKPOINT_EVERY === 0) {
        $writeMap();
        file_put_contents($progressFile, json_encode(array_keys($done)));
        say($quiet, "   .. checkpoint ($processed processed, " . count($map) . ' in map)');
    }
    usleep(400000); // ~2.5 req/sec, polite to HelloTickets
}

$writeMap();
@unlink($progressFile); // pass finished cleanly — clear resume state
say($quiet, "done: $hits new, $skip skipped, $miss missed | total in map: " . count($map));
