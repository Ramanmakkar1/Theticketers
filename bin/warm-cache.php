<?php
declare(strict_types=1);

/**
 * warm-cache.php — pre-render pages into the HTML output cache so crawlers, AI bots
 * and PageSpeed hit a warm (fast, API-free) page instead of the cold API-bound path.
 *
 * It simply requests URLs over HTTP against the canonical site; each 200 response is
 * written to storage/cache/html by index.php. Run on a cron a little more often than
 * HTML_CACHE_TTL so popular pages never go cold:
 *
 *   php bin/warm-cache.php                 # priority pages (home, cities, categories, top artists/events)
 *   php bin/warm-cache.php --all           # every URL in the SEO index (~28K — slow, off-peak only)
 *   php bin/warm-cache.php --limit=2000    # cap the number of URLs
 *   php bin/warm-cache.php --base=https://www.theticketers.com
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';

$opts = getopt('', ['all', 'limit::', 'base::', 'delay-ms::']);
$all = isset($opts['all']);
$limit = isset($opts['limit']) ? max(0, (int) $opts['limit']) : ($all ? 0 : 1200);
$base = rtrim((string) ($opts['base'] ?? $config['site_url'] ?? ''), '/');
$delayMs = max(0, (int) ($opts['delay-ms'] ?? 60));
if ($base === '' || !preg_match('#^https?://#', $base)) {
    fwrite(STDERR, "no valid base URL (set site_url in config or pass --base=)\n");
    exit(1);
}

$index = seo_index();
$buckets = $all
    ? ['events', 'artists', 'artist_cities', 'venues', 'city_dates', 'city_categories', 'monthly_events', 'venue_categories', 'artist_tours']
    : ['city_dates', 'city_categories', 'monthly_events']; // small, high-value listings

$paths = ['/', '/events', '/attractions', '/artists', '/venues', '/dubai', '/abu-dhabi'];
foreach ($buckets as $bucket) {
    foreach (($index['urls'][$bucket] ?? []) as $p) {
        $paths[] = (string) $p;
    }
}
// Priority mode: also take the first slice of the big buckets so top entities stay warm.
if (!$all) {
    foreach (['events', 'artists', 'venue_categories'] as $bucket) {
        foreach (array_slice($index['urls'][$bucket] ?? [], 0, 400) as $p) {
            $paths[] = (string) $p;
        }
    }
}
$paths = array_values(array_unique($paths));
if ($limit > 0) {
    $paths = array_slice($paths, 0, $limit);
}

$total = count($paths);
$ok = 0; $fail = 0; $i = 0;
fwrite(STDERR, "warming $total URLs against $base ...\n");
foreach ($paths as $path) {
    $i++;
    $url = $base . $path;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_NOBODY => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'TheTicketers-CacheWarmer/1.0',
        CURLOPT_HTTPHEADER => ['Accept: text/html'],
    ]);
    curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code >= 200 && $code < 400) { $ok++; } else { $fail++; }
    if ($i % 200 === 0) {
        fwrite(STDERR, sprintf("  %d/%d (ok %d, fail %d)\n", $i, $total, $ok, $fail));
    }
    if ($delayMs > 0) { usleep($delayMs * 1000); }
}
printf("done: %d warmed, %d failed, of %d\n", $ok, $fail, $total);
