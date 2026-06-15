<?php
declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function anonymize_ip(string $rawIp): string
{
    if ($rawIp === '') {
        return '';
    }

    return str_contains($rawIp, ':')
        ? implode(':', array_slice(explode(':', $rawIp), 0, 3)) . '::'
        : (string) preg_replace('/\.\d+$/', '.0', $rawIp);
}

function ai_visit_source(string $userAgent, string $referrer): ?array
{
    $ua = strtolower($userAgent);
    $bots = function_exists('ai_crawler_user_agents') ? ai_crawler_user_agents() : [
        'OAI-SearchBot', 'ChatGPT-User', 'GPTBot', 'PerplexityBot', 'Perplexity-User',
        'ClaudeBot', 'Claude-SearchBot', 'Claude-User', 'Google-Extended', 'Applebot',
    ];

    foreach ($bots as $bot) {
        if ($bot !== '' && str_contains($ua, strtolower((string) $bot))) {
            return ['kind' => 'crawler', 'source' => (string) $bot];
        }
    }

    $host = strtolower((string) parse_url($referrer, PHP_URL_HOST));
    $host = preg_replace('/^www\./', '', $host) ?: '';
    if ($host === '') {
        return null;
    }

    $referrers = [
        'chatgpt.com' => 'ChatGPT',
        'chat.openai.com' => 'ChatGPT',
        'openai.com' => 'OpenAI',
        'perplexity.ai' => 'Perplexity',
        'claude.ai' => 'Claude',
        'gemini.google.com' => 'Google Gemini',
        'copilot.microsoft.com' => 'Microsoft Copilot',
        'bing.com' => 'Microsoft Copilot/Bing',
        'you.com' => 'You.com',
        'phind.com' => 'Phind',
        'poe.com' => 'Poe',
    ];

    foreach ($referrers as $domain => $label) {
        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
            return ['kind' => 'referral', 'source' => $label, 'referrer_host' => $host];
        }
    }

    return null;
}

function record_ai_visit(array $config): void
{
    $source = ai_visit_source(
        substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 240),
        substr((string) ($_SERVER['HTTP_REFERER'] ?? ''), 0, 300)
    );
    if ($source === null) {
        return;
    }

    $logDir = dirname(__DIR__) . '/storage';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }

    $path = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $line = json_encode([
        'time' => gmdate('c'),
        'kind' => $source['kind'],
        'source' => $source['source'],
        'path' => $path !== '' ? $path : '/',
        'query' => substr((string) ($_SERVER['QUERY_STRING'] ?? ''), 0, 300),
        'referrer_host' => $source['referrer_host'] ?? '',
        'ip' => anonymize_ip((string) ($_SERVER['REMOTE_ADDR'] ?? '')),
        'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 240),
    ], JSON_UNESCAPED_SLASHES) . PHP_EOL;

    @file_put_contents($logDir . '/ai-traffic.log', $line, FILE_APPEND | LOCK_EX);
}

function slugify(string $value): string
{
    // Transliterate accents ourselves first — macOS iconv turns "é" into "'e",
    // which used to produce slugs like "c-eline-dion" instead of "celine-dion".
    static $translit = [
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
        'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i',
        'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o',
        'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
        'ý' => 'y', 'ÿ' => 'y', 'þ' => 'th', 'ß' => 'ss', 'œ' => 'oe', 'š' => 's', 'ž' => 'z',
        'ł' => 'l', 'ć' => 'c', 'č' => 'c', 'đ' => 'd', 'ř' => 'r', 'ş' => 's', 'ţ' => 't',
        'ğ' => 'g', 'ı' => 'i', 'ą' => 'a', 'ę' => 'e', 'ė' => 'e', 'ń' => 'n', 'ś' => 's',
        'ź' => 'z', 'ż' => 'z', 'ū' => 'u', 'ī' => 'i', 'ā' => 'a', 'ō' => 'o', 'ē' => 'e',
    ];
    $value = strtr(mb_strtolower($value, 'UTF-8'), $translit);
    $converted = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) : $value;
    $converted = strtolower((string) $converted);
    $converted = preg_replace('/[^a-z0-9]+/', '-', $converted) ?: '';
    return trim($converted, '-') ?: 'tickets';
}

/** Trim an over-long slug at a word boundary so URLs stay readable. */
function slug_clip(string $slug, int $max = 70): string
{
    if (strlen($slug) <= $max) {
        return $slug;
    }
    $cut = strrpos(substr($slug, 0, $max + 1), '-');
    return $cut === false ? substr($slug, 0, $max) : substr($slug, 0, $cut);
}

/** True when $word appears in $slug as a whole hyphen-delimited word (not a substring). */
function slug_contains_word(string $slug, string $word): bool
{
    return $word !== '' && preg_match('/(^|-)' . preg_quote($word, '/') . '($|-)/', $slug) === 1;
}

/**
 * Numeric id at the end of a LEGACY slug ("sports-1", "dubai-132", "2348783"),
 * or null when the slug is a clean one. Date-suffixed slugs ("...-2026-07-18")
 * are clean event URLs, never legacy ids.
 */
function legacy_id_from_slug(string $slug): ?int
{
    if (preg_match('/\d{4}-\d{2}-\d{2}$/', $slug) === 1) {
        return null;
    }
    if (preg_match('/-(\d+)$/', $slug, $match) === 1) {
        return (int) $match[1];
    }
    return ctype_digit($slug) ? (int) $slug : null;
}

/* ---------- Slug → id map ----------
 * Clean URLs carry no numeric id, but the HelloTickets API only loads entities by id.
 * Every time a link is built we learn "this slug is this id" and persist it to
 * storage/slugs.json (flushed once per request). The sitemap render seeds the map for
 * exactly the URLs search engines discover; cold misses fall back to API name search.
 */

function slug_map_file(): string
{
    return __DIR__ . '/../storage/slugs.json';
}

function slug_map(): array
{
    static $map = null;
    if ($map === null) {
        $file = slug_map_file();
        $map = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    return $map;
}

function slug_lookup(string $type, string $slug): ?int
{
    $id = slug_map()[$type][$slug] ?? null;
    if (is_numeric($id) && (int) $id > 0) {
        return (int) $id;
    }
    $fallback = seo_index()['maps'][$type][$slug] ?? null;
    return is_numeric($fallback) && (int) $fallback > 0 ? (int) $fallback : null;
}

function &slug_pending(): array
{
    static $pending = [];
    return $pending;
}

function slug_remember(string $type, string $slug, int $id): void
{
    if ($id <= 0 || $slug === '' || $slug === 'tickets' || slug_lookup($type, $slug) === $id) {
        return;
    }
    $pending = &slug_pending();
    if (($pending[$type][$slug] ?? 0) === $id) {
        return;
    }
    $pending[$type][$slug] = $id;

    static $registered = false;
    if (!$registered) {
        $registered = true;
        register_shutdown_function('slug_map_flush');
    }
}

function slug_map_flush(): void
{
    $pending = &slug_pending();
    if ($pending === []) {
        return;
    }
    $file = slug_map_file();
    if (!is_dir(dirname($file))) {
        @mkdir(dirname($file), 0775, true);
    }
    $handle = @fopen($file, 'c+');
    if ($handle === false) {
        return;
    }
    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        return;
    }
    $raw = stream_get_contents($handle);
    $disk = [];
    if (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            // Never "repair" a corrupt map by wiping it — losing learned slugs turns
            // indexed clean URLs into 404s. Skip this flush; a later one will retry.
            flock($handle, LOCK_UN);
            fclose($handle);
            return;
        }
        $disk = $decoded;
    }
    foreach ($pending as $type => $entries) {
        if (!is_array($disk[$type] ?? null)) {
            $disk[$type] = [];
        }
        foreach ($entries as $slug => $id) {
            unset($disk[$type][$slug]); // re-insert at the end = most recently seen
            $disk[$type][$slug] = $id;
        }
        if (count($disk[$type]) > 10000) {
            $disk[$type] = array_slice($disk[$type], -10000, null, true);
        }
    }
    // Atomic replace (temp + rename) so readers never see a partial write and a
    // disk-full fwrite can't truncate the map.
    $json = json_encode($disk, JSON_UNESCAPED_SLASHES);
    $tmp = $file . '.tmp' . getmypid();
    if ($json !== false && @file_put_contents($tmp, $json) === strlen($json)) {
        @rename($tmp, $file);
    } else {
        @unlink($tmp);
    }
    flock($handle, LOCK_UN);
    fclose($handle);
    $pending = [];
}

/**
 * Earliest slug learned for an id — pins one canonical slug per entity even when
 * the API returns different titles for the same id (activity list vs detail).
 */
function slug_for_id(string $type, int $id): ?string
{
    static $reverse = [];
    if (!isset($reverse[$type])) {
        $reverse[$type] = [];
        foreach (slug_map()[$type] ?? [] as $slug => $mappedId) {
            if (is_numeric($mappedId) && !isset($reverse[$type][(int) $mappedId])) {
                $reverse[$type][(int) $mappedId] = (string) $slug;
            }
        }
    }
    return $reverse[$type][$id] ?? null;
}

/** Venue slugs map to string Ticketmaster ids, so they get their own accessors. */
function venue_slug_lookup(string $slug): ?string
{
    $id = slug_map()['venue'][$slug] ?? null;
    if (is_string($id) && $id !== '') {
        return $id;
    }
    $fallback = seo_index()['maps']['venue'][$slug] ?? null;
    return is_string($fallback) && $fallback !== '' ? $fallback : null;
}

function venue_slug_remember(string $slug, string $tmId): void
{
    if ($tmId === '' || $slug === '' || $slug === 'tickets' || venue_slug_lookup($slug) === $tmId) {
        return;
    }
    $pending = &slug_pending();
    if (($pending['venue'][$slug] ?? '') === $tmId) {
        return;
    }
    $pending['venue'][$slug] = $tmId;
    register_shutdown_function('slug_map_flush');
}

function tm_artist_slug_lookup(string $slug): ?string
{
    $id = slug_map()['tm_artist'][$slug] ?? null;
    if (is_string($id) && $id !== '') {
        return $id;
    }
    $fallback = seo_index()['maps']['tm_artist'][$slug] ?? null;
    return is_string($fallback) && $fallback !== '' ? $fallback : null;
}

function tm_artist_slug_remember(string $slug, string $tmId): void
{
    if ($tmId === '' || $slug === '' || $slug === 'tickets' || tm_artist_slug_lookup($slug) === $tmId) {
        return;
    }
    $pending = &slug_pending();
    if (($pending['tm_artist'][$slug] ?? '') === $tmId) {
        return;
    }
    $pending['tm_artist'][$slug] = $tmId;
    register_shutdown_function('slug_map_flush');
}

/**
 * Guard for LEGACY "{name}-{id}" URLs only: the name part has to plausibly belong to
 * the entity the id loads, otherwise /artist/anything-5 would 301 to whatever artist
 * owns id 5 — minting infinite duplicate URLs and redirecting typos to wrong pages.
 * Old slugs may differ from today's slugify (accents) or use an alternate API title,
 * so accept either an alphanumeric prefix match or >=50% word overlap.
 */
function legacy_slug_corresponds(string $requestedSlug, string $cleanSlug): bool
{
    $name = (string) preg_replace('/-\d+$/', '', strtolower($requestedSlug));
    $a = (string) preg_replace('/[^a-z0-9]/', '', $name);
    $b = (string) preg_replace('/[^a-z0-9]/', '', strtolower($cleanSlug));
    if ($a !== '' && strncmp($b, $a, strlen($a)) === 0) {
        return true;
    }
    $reqWords = array_filter(explode('-', $name), static fn(string $w): bool => strlen($w) >= 3);
    if ($reqWords === []) {
        return false;
    }
    $cleanWords = array_flip(array_filter(explode('-', strtolower($cleanSlug)), static fn(string $w): bool => $w !== ''));
    $hits = count(array_filter($reqWords, static fn(string $w): bool => isset($cleanWords[$w])));
    return $hits / count($reqWords) >= 0.5;
}

function route_url(string $path, array $query = []): string
{
    $path = '/' . ltrim($path, '/');
    $query = array_filter($query, static function ($value): bool {
        return $value !== null && $value !== '';
    });

    if ($query !== []) {
        $path .= '?' . http_build_query($query);
    }

    return $path;
}

function absolute_url(array $config, string $path, array $query = []): string
{
    return $config['site_url'] . route_url($path, $query);
}

// Content-fingerprinted asset URL (?v=mtime) so far-future Cache-Control on
// /assets/* can never serve a stale stylesheet after a deploy.
function asset_url(string $path): string
{
    static $versions = [];
    if (!isset($versions[$path])) {
        $file = dirname(__DIR__) . '/' . ltrim($path, '/');
        $versions[$path] = is_file($file) ? (string) filemtime($file) : '1';
    }
    return $path . '?v=' . $versions[$path];
}

function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    return '/' . trim((string) $path, '/');
}

function page_number(): int
{
    return max(1, min(100, (int) ($_GET['page'] ?? 1)));
}

function search_query(): string
{
    return trim((string) ($_GET['q'] ?? ''));
}

function money($amount, string $currency): string
{
    $amount = (float) $amount;
    if ($amount <= 0) {
        return 'Check price';
    }

    $symbols = [
        'AED' => 'AED ',
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
        'CAD' => 'C$',
        'AUD' => 'A$',
    ];

    $prefix = $symbols[$currency] ?? '';
    $suffix = $prefix === '' ? ' ' . $currency : '';
    $decimals = fmod($amount, 1.0) === 0.0 ? 0 : 2;

    return $prefix . number_format($amount, $decimals) . $suffix;
}

function format_date_time(array $startDate): string
{
    if (!empty($startDate['date_tba'])) {
        return 'Date to be announced';
    }

    $date = (string) ($startDate['local_date'] ?? '');
    $time = (string) ($startDate['local_time'] ?? '');
    if ($date === '') {
        return 'Upcoming';
    }

    $display = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
    $formatted = $display ? $display->format('D, M j') : $date;

    if ($time !== '' && empty($startDate['time_tba'])) {
        $timeDisplay = DateTimeImmutable::createFromFormat('!H:i', substr($time, 0, 5));
        $formatted .= $timeDisplay ? ' at ' . $timeDisplay->format('g:i A') : ' at ' . $time;
    }

    return $formatted;
}

/** Loading + intrinsic-size attributes for card-grid images. The first grid row
 *  is often above the fold — lazy-loading it actively deprioritizes the page's
 *  LCP candidate, so the first few images load eagerly. width/height give the
 *  browser an aspect ratio before CSS arrives (the .card-image wrapper's
 *  aspect-ratio reserves the space once styles load). */
function card_img_attrs(): string
{
    static $rendered = 0;
    $rendered++;
    return ($rendered <= 4 ? 'loading="eager"' : 'loading="lazy"') . ' width="600" height="750"';
}

/** "A", "A and B", "A, B and C" — for city lists in generated prose. A bare
 *  implode produced nonsense geography like "in San Antonio, New York". */
function natural_join(array $items): string
{
    $items = array_values(array_filter(array_map('strval', $items), static fn($v) => $v !== ''));
    if (count($items) <= 1) {
        return $items[0] ?? '';
    }
    $last = array_pop($items);
    return implode(', ', $items) . ' and ' . $last;
}

/** "Aug 2, 2026" from a Y-m-d string — for prose and schema descriptions. */
function format_date_label(string $localDate): string
{
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $localDate);
    return $d ? $d->format('M j, Y') : $localDate;
}

function date_bounds(?string $dateKey): array
{
    $today = new DateTimeImmutable('today');

    if ($dateKey === 'today') {
        return [$today, $today];
    }

    if ($dateKey === 'tomorrow') {
        $tomorrow = $today->modify('+1 day');
        return [$tomorrow, $tomorrow];
    }

    if ($dateKey === 'weekend') {
        $start = $today->modify('saturday this week');
        if ($start < $today) {
            $start = $today->modify('saturday next week');
        }
        return [$start, $start->modify('+1 day')];
    }

    if ($dateKey === 'week') {
        return [$today, $today->modify('+7 days')];
    }

    if ($dateKey === 'month') {
        return [$today, $today->modify('+30 days')];
    }

    return [$today, $today->modify('+1 year')];
}

function date_params(?string $dateKey): array
{
    [$from, $to] = date_bounds($dateKey);
    return [
        'local_date_from' => $from->format('Y-m-d'),
        'local_date_to' => $to->format('Y-m-d'),
    ];
}

/**
 * Real per-item image map, keyed "type-id" (e.g. "activity-2459", "performer-129").
 * Merges two sources, loaded once per request:
 *   - storage/images.json     : HelloTickets activity/event covers (URLs), committed.
 *   - storage/tm-images.json  : Ticketmaster artist/event images downloaded to
 *                               assets/media by bin/fetch-tm-images.php (server-local).
 * HelloTickets covers win over Ticketmaster on the same key (a real event poster
 * beats generic artist art); Ticketmaster fills artists and uncovered events.
 */
function image_map(): array
{
    static $map = null;
    if ($map === null) {
        $load = static function (string $file): array {
            return is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
        };
        $ht = $load(__DIR__ . '/../storage/images.json');
        $tm = $load(__DIR__ . '/../storage/tm-images.json');
        $map = array_merge($tm, $ht); // $ht overwrites $tm on conflict
    }
    return $map;
}

/** Real mapped image for an item, or null when we have none (no generic fallback). */
function mapped_image(string $type, int $id): ?string
{
    if ($id <= 0) {
        return null;
    }
    $map = image_map();
    $key = $type . '-' . $id;
    return (!empty($map[$key]) && is_string($map[$key])) ? $map[$key] : null;
}

/** Local image paths (/assets/media/…) become absolute; full URLs pass through.
 *  og:image and JSON-LD require absolute URLs, so every image that can reach a
 *  meta tag or schema block flows through this. */
function absolute_image_url(array $config, string $url): string
{
    return str_starts_with($url, '/') ? $config['site_url'] . $url : $url;
}

function image_from_item(array $item, string $type, array $config): string
{
    if (!empty($item['image']) && is_string($item['image'])) {
        return absolute_image_url($config, $item['image']);
    }

    if (!empty($item['images']) && is_array($item['images'])) {
        $first = $item['images'][0] ?? null;
        if (is_string($first)) {
            return absolute_image_url($config, $first);
        }
        if (is_array($first) && !empty($first['url'])) {
            return absolute_image_url($config, (string) $first['url']);
        }
    }

    // Real harvested photo for this exact item, if we have one.
    $id = (int) ($item['id'] ?? 0);
    if ($id > 0) {
        $map = image_map();
        $key = $type . '-' . $id;
        if (!empty($map[$key]) && is_string($map[$key])) {
            return absolute_image_url($config, $map[$key]);
        }
    }

    // An event with no cover of its own reuses its headline performer's photo
    // (Ticketmaster artist image) — content-matched and works in ANY city.
    if ($type === 'event' && !empty($item['performers']) && is_array($item['performers'])) {
        $performerIds = [];
        foreach ($item['performers'] as $performer) {
            $pid = (int) ($performer['id'] ?? 0);
            if (!empty($performer['is_main'])) {
                array_unshift($performerIds, $pid);
            } else {
                $performerIds[] = $pid;
            }
        }
        foreach ($performerIds as $pid) {
            $performerImage = mapped_image('performer', $pid);
            if ($performerImage !== null) {
                return absolute_image_url($config, $performerImage);
            }
        }
    }

    $name = strtolower((string) ($item['title'] ?? $item['name'] ?? ''));
    $keywordImages = [
        'burj' => ['burj', 'khalifa', 'dubai frame', 'skyscraper'],
        'waterpark' => ['waterpark', 'aquaventure', 'aqua', 'water park'],
        'desert' => ['desert', 'safari', 'dune', 'camel'],
        'aquarium' => ['aquarium', 'underwater', 'dolphin', 'seal'],
        'cruise' => ['cruise', 'boat', 'yacht', 'marina', 'dhow'],
    ];
    foreach ($keywordImages as $imageKey => $needles) {
        foreach ($needles as $needle) {
            if (str_contains($name, $needle) && isset($config['fallback_images'][$imageKey])) {
                return $config['fallback_images'][$imageKey];
            }
        }
    }

    $categoryName = $item['category']['name'] ?? '';
    if ($categoryName !== '' && isset($config['fallback_images'][$categoryName])) {
        return $config['fallback_images'][$categoryName];
    }

    // Location-neutral rotation for image-less activities (the listing API omits
    // images), so non-Dubai city/country pages never show a Dubai landmark.
    if ($type === 'activity') {
        $neutral = [
            'https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1546412414-e1885259563a?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1597659840241-37e2b7c6e922?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1526495124232-a04e1849168c?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
        ];
        return $neutral[$id % count($neutral)];
    }

    return $config['fallback_images'][$type] ?? $config['fallback_images']['hero'];
}

/**
 * Canonical id => {name, country_code} for every city the browser geo-detect can
 * resolve (mirrors the app.js list). Lets the server recognise a detected city
 * (e.g. Edmonton) even when it isn't one of the curated "featured" market cities.
 */
function geo_cities(): array
{
    static $cities = null;
    if ($cities === null) {
        $file = __DIR__ . '/geo-cities.json';
        $cities = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    return $cities;
}

/** Pre-computed inventory gate (storage/city-index.json, built by bin/build-city-index.php).
 *  Returns ['cities' => ['101' => ['events'=>220], …]] or null when no index exists yet. */
function city_index(): ?array
{
    static $index = false;
    if ($index === false) {
        $file = __DIR__ . '/../storage/city-index.json';
        $index = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: null) : null;
    }
    return $index ?: null;
}

/** Is a geo city worth indexing/linking? Reads the pre-built index so the SITEMAP
 *  and internal links never call the APIs per city. When no index exists yet, assume
 *  yes — the render-time inventory gate on /city/ and weekend pages still 404s thin
 *  cities, so the worst case is a few sitemap entries that resolve to 404 until the
 *  cron runs. */
function city_has_inventory(int $cityId): bool
{
    $index = city_index();
    if ($index === null) {
        return true;
    }
    return isset($index['cities'][(string) $cityId]);
}

/** Approximate live event count for a city from the pre-built index (0 if unknown). */
function city_event_count(int $cityId): int
{
    $index = city_index();
    return (int) ($index['cities'][(string) $cityId]['events'] ?? 0);
}

function currency_for_country_code(array $config, string $countryCode): ?string
{
    return $config['market_currencies'][$countryCode] ?? null;
}

function currency_for_city_id(array $config, int $cityId): ?string
{
    foreach ($config['market_cities'] as $city) {
        if ((int) $city['id'] === $cityId) {
            return currency_for_country_code($config, (string) ($city['country_code'] ?? ''));
        }
    }

    // A geo-detected city outside the flagship set (e.g. Edmonton) still has a
    // known country in geo-cities.json — price it in that country's currency
    // rather than silently dropping back to the AED default.
    $geo = geo_cities();
    if (isset($geo[(string) $cityId]['country_code'])) {
        return currency_for_country_code($config, (string) $geo[(string) $cityId]['country_code']);
    }

    return null;
}

/**
 * Display currency for this request. URL context wins (a French city page shows
 * EUR no matter where the visitor is), then the visitor's saved city cookie,
 * then the configured default. The API converts prices to whatever we request.
 */
function request_currency(array $config): string
{
    $path = current_path();

    if (preg_match('#^/(dubai|abu-dhabi)(/|$)#', $path) === 1) {
        return 'AED';
    }

    if (preg_match('#^/events/(?:today|this-week|this-weekend)-in-([^/]+)$#', $path, $match) === 1
        || preg_match('#^/city/([^/]+)$#', $path, $match) === 1
        || preg_match('#^/city/([^/]+)/(?:concerts|sports|theatre|comedy)$#', $path, $match) === 1
        || preg_match('#^/artist/[^/]+/([^/]+)$#', $path, $match) === 1) {
        // The captured segment is the city for date, city/category, city listing,
        // and "{artist} in {city}". Price in that city's market currency.
        $cityId = resolve_city_id_by_slug($config, $match[1]) ?? legacy_id_from_slug($match[1]) ?? 0;
        $currency = $cityId > 0 ? currency_for_city_id($config, $cityId) : null;
        if ($currency !== null) {
            return $currency;
        }
    }

    if (preg_match('#^/([a-z0-9-]+)(/|$)#', $path, $match) === 1) {
        $market = $config['markets'][$match[1]] ?? null;
        if ($market !== null) {
            $currency = currency_for_country_code($config, (string) ($market['country_code'] ?? ''));
            if ($currency !== null) {
                return $currency;
            }
        }
    }

    $currency = currency_for_city_id($config, active_city_id($config));
    if ($currency !== null) {
        return $currency;
    }

    return (string) $config['currency'];
}

function active_city_id(array $config): int
{
    $cookie = (int) ($_COOKIE['tb_city'] ?? 0);
    if ($cookie > 0) {
        foreach ($config['market_cities'] as $city) {
            if ((int) $city['id'] === $cookie) {
                return $cookie;
            }
        }
        // Any geo-detectable city is a valid choice too (the API accepts its id).
        if (isset(geo_cities()[(string) $cookie])) {
            return $cookie;
        }
    }

    return (int) $config['default_city_id'];
}

function city_for_id(int $cityId, array $config): array
{
    foreach ($config['market_cities'] as $city) {
        if ((int) $city['id'] === $cityId) {
            return $city;
        }
    }

    // A geo-detected (non-featured) city: resolve its real name from the canonical list.
    $geo = geo_cities();
    if (isset($geo[(string) $cityId])) {
        return [
            'id' => $cityId,
            'name' => (string) $geo[(string) $cityId]['name'],
            'state' => '',
            'country' => '',
            'country_code' => (string) ($geo[(string) $cityId]['country_code'] ?? ''),
        ];
    }

    // Never source the display name from user input (was $_GET['city'] — a content/
    // title-injection vector). Unknown ids fall back to the default city name.
    return [
        'id' => $cityId,
        'name' => $config['default_city_name'],
        'state' => '',
        'country' => '',
        'country_code' => '',
    ];
}

/**
 * Path of the editorial /{country}/{city} guide hub for a market city,
 * or null when the destinations pack has no guide for it.
 */
function destination_hub_path_for_city(array $destinationsContent, int $cityId): ?string
{
    foreach (($destinationsContent['cities'] ?? []) as $hubCity) {
        if ((int) ($hubCity['city_id'] ?? 0) === $cityId
            && !empty($hubCity['slug'])
            && !empty($hubCity['country_slug'])) {
            return '/' . $hubCity['country_slug'] . '/' . $hubCity['slug'];
        }
    }

    return null;
}

/** Other market cities in the same country (for nearby-city fallback). */
function nearby_city_ids(int $cityId, array $config): array
{
    $countryCode = null;
    foreach ($config['market_cities'] as $city) {
        if ((int) $city['id'] === $cityId) {
            $countryCode = $city['country_code'] ?? null;
            break;
        }
    }
    if (!$countryCode) {
        $countryCode = geo_cities()[(string) $cityId]['country_code'] ?? null;
    }
    if (!$countryCode) {
        return [];
    }
    $ids = [];
    foreach ($config['market_cities'] as $city) {
        if (($city['country_code'] ?? null) === $countryCode && (int) $city['id'] !== $cityId) {
            $ids[] = (int) $city['id'];
        }
    }
    return $ids;
}

/**
 * Date-prioritised events for the home page, with nearby-city fallback.
 * Returns 0–2 rails: ['label'=>.., 'items'=>[..], 'href'=>..]. Leads with the
 * soonest window that has events in the city (today → this week → this month),
 * then a wider "more upcoming" rail; if the city itself has none, falls back to
 * nearby cities in the same country. Empty array => let the page show worldwide.
 */
function home_event_rails(HelloTicketsClient $client, array $config, int $cityId, string $cityName): array
{
    $fetch = static function (string $window, int $cid, int $limit = 12) use ($client): array {
        return api_result(static fn() => $client->performances(array_merge([
            'limit' => $limit,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $cid,
        ], date_params($window)), ), ['performances' => []])['performances'] ?? [];
    };

    // One date-sorted upcoming pool for the city. Gate on how many HelloTickets events
    // carry a REAL cover, not the raw count: cities like Edmonton return plenty of HT
    // rows but with no images (HT serves none), so we must still page deep through
    // Ticketmaster — which has the posters — and let real-image events lead.
    $htUpcoming = $fetch('upcoming', $cityId, 24);
    $htWithImages = 0;
    foreach ($htUpcoming as $htEvent) {
        if (strpos(image_from_item($htEvent, 'event', $config), 'images.unsplash.com') === false) {
            $htWithImages++;
        }
    }
    if ($htWithImages >= 12) {
        $pool = $htUpcoming;
        usort($pool, static fn($a, $b): int => strcmp(
            (string) ($a['start_date']['local_date'] ?? '9999'),
            (string) ($b['start_date']['local_date'] ?? '9999')
        ));
    } else {
        $city = city_for_id($cityId, $config);
        $pool = city_event_pool(
            $htUpcoming,
            tm_events_for_city_deep($config, $cityName, (string) ($city['country_code'] ?? '')),
            $config
        );
    }

    // Nothing of its own even after TM — fall back to nearby cities (unchanged).
    if (count($pool) < 3) {
        $nearby = [];
        foreach (nearby_city_ids($cityId, $config) as $nearbyId) {
            $nearby = array_merge($nearby, $fetch('upcoming', $nearbyId, 8));
            if (count($nearby) >= 12) {
                break;
            }
        }
        return $nearby === [] ? [] : [[
            'label' => 'Events near ' . $cityName,
            'items' => array_slice($nearby, 0, 12),
            'href' => '/events',
        ]];
    }

    // Smart, honest label: name the narrowest timeframe that genuinely has enough on
    // (so "today" only when today is busy), but always fill the rail from the soonest
    // events so it never looks empty — today rolls into tomorrow rolls into this week.
    $today = (new DateTimeImmutable('today'))->format('Y-m-d');
    $weekEnd = (new DateTimeImmutable('today'))->modify('+7 days')->format('Y-m-d');
    $todayCount = 0;
    $weekCount = 0;
    foreach ($pool as $event) {
        $d = (string) ($event['start_date']['local_date'] ?? '');
        if ($d === '') {
            continue;
        }
        if ($d === $today) {
            $todayCount++;
        }
        if ($d >= $today && $d <= $weekEnd) {
            $weekCount++;
        }
    }
    if ($todayCount >= 5) {
        $leadLabel = 'Happening today in ' . $cityName;
        $leadHref = route_url('/events', ['date' => 'today']);
    } elseif ($weekCount >= 5) {
        $leadLabel = 'Happening this week in ' . $cityName;
        $leadHref = route_url('/events', ['date' => 'week']);
    } else {
        $leadLabel = 'Upcoming events in ' . $cityName;
        $leadHref = '/events';
    }

    $rails = [['label' => $leadLabel, 'items' => array_slice($pool, 0, 12), 'href' => $leadHref]];
    $more = array_slice($pool, 12, 12);
    if (count($more) >= 4) {
        $rails[] = ['label' => 'More events in ' . $cityName, 'items' => $more, 'href' => '/events'];
    }
    return $rails;
}

function city_intent_categories(): array
{
    return [
        'concerts' => [
            'label' => 'Concerts',
            'singular' => 'concert',
            'ht_category_ids' => [2],
            'tm_classification_names' => ['Music'],
        ],
        'sports' => [
            'label' => 'Sports',
            'singular' => 'sports event',
            'ht_category_ids' => [1],
            'tm_classification_names' => ['Sports'],
        ],
        'theatre' => [
            'label' => 'Theatre',
            'singular' => 'theatre show',
            'ht_category_ids' => [3],
            'tm_classification_names' => ['Arts & Theatre'],
        ],
        'comedy' => [
            'label' => 'Comedy',
            'singular' => 'comedy show',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Comedy'],
        ],
        'festivals' => [
            'label' => 'Festivals',
            'singular' => 'festival',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Festival'],
        ],
        'family' => [
            'label' => 'Family',
            'singular' => 'family event',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Family'],
        ],
        'classical' => [
            'label' => 'Classical',
            'singular' => 'classical performance',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Classical'],
        ],
        'hip-hop' => [
            'label' => 'Hip-Hop & R&B',
            'singular' => 'hip-hop show',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Hip-Hop/Rap', 'R&B'],
        ],
        'rock' => [
            'label' => 'Rock',
            'singular' => 'rock concert',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Rock', 'Alternative'],
        ],
        'country-music' => [
            'label' => 'Country',
            'singular' => 'country show',
            'ht_category_ids' => [],
            'tm_classification_names' => ['Country'],
        ],
    ];
}

function city_date_label(string $dateKey): string
{
    [$from, $to] = date_bounds($dateKey);
    return match ($dateKey) {
        'today' => 'today, ' . $from->format('M j'),
        'week' => 'this week, ' . $from->format('M j') . '-' . $to->format($from->format('M') === $to->format('M') ? 'j' : 'M j'),
        'weekend' => 'this weekend, ' . $from->format('M j') . '-' . $to->format($from->format('M') === $to->format('M') ? 'j' : 'M j'),
        default => 'upcoming',
    };
}

function tm_local_start_range(string $dateKey): string
{
    [$from, $to] = date_bounds($dateKey);
    return $from->format('Y-m-d\T00:00:00') . ',' . $to->format('Y-m-d\T23:59:59');
}

function city_date_events(HelloTicketsClient $client, array $config, int $cityId, string $dateKey, int $tmMaxPages = 2): array
{
    $city = city_for_id($cityId, $config);
    $ht = api_result(static fn() => $client->performances(array_merge([
        'limit' => 48,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params($dateKey))), ['performances' => []])['performances'] ?? [];

    $tm = tm_events_for_city_deep($config, (string) $city['name'], (string) ($city['country_code'] ?? ''), [
        'localStartDateTime' => tm_local_start_range($dateKey),
    ], $tmMaxPages, 100);

    return city_event_pool($ht, $tm, $config);
}

function city_category_events(HelloTicketsClient $client, array $config, int $cityId, string $categorySlug, int $tmMaxPages = 2): array
{
    $categories = city_intent_categories();
    if (!isset($categories[$categorySlug])) {
        return [];
    }

    $city = city_for_id($cityId, $config);
    $ht = [];
    foreach ($categories[$categorySlug]['ht_category_ids'] as $categoryId) {
        $data = api_result(static fn() => $client->performances(array_merge([
            'limit' => 48,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $cityId,
            'category_id' => (int) $categoryId,
        ], date_params(null))), ['performances' => []]);
        $ht = array_merge($ht, $data['performances'] ?? []);
    }

    $tm = [];
    foreach ($categories[$categorySlug]['tm_classification_names'] as $classificationName) {
        $tm = array_merge($tm, tm_events_for_city_deep($config, (string) $city['name'], (string) ($city['country_code'] ?? ''), [
            'classificationName' => $classificationName,
        ], $tmMaxPages, 100));
    }

    return city_event_pool($ht, $tm, $config);
}

function seo_index_file(): string
{
    return __DIR__ . '/../storage/seo-index.json';
}

function seo_index(): array
{
    static $index = null;
    if ($index === null) {
        $file = seo_index_file();
        $index = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    return $index;
}

function seo_index_urls(string $bucket): array
{
    $index = seo_index();
    $urls = $index['urls'][$bucket] ?? [];
    return is_array($urls) ? array_values(array_filter(array_map('strval', $urls))) : [];
}

/**
 * Clean, EVERGREEN event slug: name + city ("bad-bunny-san-juan"). Deliberately
 * carries NO date — the URL must outlive any single show date so the page keeps
 * accruing ranking signal across repeat tours instead of going stale the day after
 * the event. City disambiguates same-name shows across different cities without
 * exposing database ids, and reads like a real search query. Same-name shows in the
 * SAME city (residencies, repeat tour nights) intentionally collapse onto one
 * evergreen page rather than splitting signal across thin per-date duplicates.
 *
 * Legacy dated slugs ("...-2026-07-18") still resolve (see resolve_event_id) and
 * 301 to this clean form via the self-canonical redirect in render_event_detail_page.
 */
function event_slug(array $performance): string
{
    $slug = slug_clip(slugify((string) ($performance['name'] ?? 'event')));
    $cityRaw = trim((string) ($performance['venue']['city'] ?? ''));
    if ($cityRaw !== '') {
        $city = slugify($cityRaw);
        if ($city !== 'tickets' && !slug_contains_word($slug, $city)) {
            $slug .= '-' . $city;
        }
    }
    return $slug;
}

function event_path(array $performance): string
{
    $slug = event_slug($performance);
    $tmId = (string) ($performance['tm_id'] ?? '');
    if ($tmId !== '') {
        // Ticketmaster ids are already URL-safe ([A-Za-z0-9_]); emit the raw id so
        // the URL stays short and keyword-led. bin2hex() doubled the id length for
        // no SEO gain. Anything outside that set (or implausibly short) falls back to
        // hex so the decoder's hyphen-free tail extraction can't misread it.
        $token = preg_match('/^[A-Za-z0-9_]{10,}$/', $tmId) === 1 ? $tmId : bin2hex($tmId);
        return '/event/' . $slug . '-tm-' . $token;
    }

    slug_remember('event', $slug, (int) ($performance['id'] ?? 0));
    return '/event/' . $slug;
}

function tm_event_id_from_slug(string $slug): ?string
{
    // The id is the final hyphen-free segment after the last "-tm-". slugify() only
    // ever emits lowercase [a-z0-9-], so a real TM id (mixed case / underscores, 10+
    // chars) can't collide with a keyword slug word.
    $pos = strrpos(strtolower($slug), '-tm-');
    if ($pos === false) {
        return null;
    }
    $token = substr($slug, $pos + 4); // original case preserved (lengths match)

    // Legacy form: lowercase hex of even length that decodes to a valid id. These
    // 301 to the new short form via the self-canonical redirect on the detail page.
    if (preg_match('/^[a-f0-9]+$/', $token) === 1 && strlen($token) % 2 === 0) {
        $decoded = @hex2bin($token);
        if (is_string($decoded) && preg_match('/^[A-Za-z0-9_]{6,}$/', $decoded) === 1) {
            return $decoded;
        }
    }

    // New form: the raw, URL-safe Ticketmaster id.
    if (preg_match('/^[A-Za-z0-9_]{10,}$/', $token) === 1) {
        return $token;
    }

    return null;
}

/** ISO 3166-1 alpha-2 code for the country names our two APIs return; Google
 *  recommends the 2-letter code for addressCountry. Unknown names pass through
 *  (plain text is still valid). */
function iso_country_code(string $country): string
{
    static $map = [
        'united arab emirates' => 'AE',
        'united states' => 'US',
        'united states of america' => 'US',
        'usa' => 'US',
        'united kingdom' => 'GB',
        'great britain' => 'GB',
        'canada' => 'CA',
        'italy' => 'IT',
        'spain' => 'ES',
        'france' => 'FR',
        'netherlands' => 'NL',
        'germany' => 'DE',
        'portugal' => 'PT',
        'australia' => 'AU',
        'ireland' => 'IE',
        'singapore' => 'SG',
        'switzerland' => 'CH',
        'austria' => 'AT',
        'belgium' => 'BE',
        'saudi arabia' => 'SA',
        'qatar' => 'QA',
    ];
    return $map[strtolower(trim($country))] ?? $country;
}

/** Structured PostalAddress for schema location nodes — flat concatenated strings
 *  ("13 5 Street , Dubai") read as machine-mangled and lower location-match
 *  confidence. Falls back to the city string when nothing more is known. */
function schema_postal_address(array $venue)
{
    $street = trim((string) ($venue['street'] ?? $venue['address'] ?? ''), " \t,");
    $city = trim((string) ($venue['city'] ?? ''));
    // TM-normalized 'address' embeds ", City ST" — don't repeat the locality.
    if ($city !== '' && $street !== '' && stripos($street, $city) !== false) {
        $street = trim((string) ($venue['street'] ?? ''));
    }
    $address = array_filter([
        '@type' => 'PostalAddress',
        'streetAddress' => $street,
        'addressLocality' => $city,
        'addressRegion' => trim((string) ($venue['state'] ?? '')),
        'postalCode' => trim((string) ($venue['zip_code'] ?? '')),
        'addressCountry' => iso_country_code((string) ($venue['country_code'] ?? $venue['country'] ?? '')),
    ], static fn($v) => $v !== '');
    if (count($address) <= 1) {
        return $city;
    }
    return $address;
}

/** Schema.org startDate in VENUE-LOCAL time with its UTC offset, per Google's Event
 *  guidance. Emitting the raw UTC "…Z" timestamp made rich results show 4:00 PM for
 *  an 8 PM Dubai show — and contradicted the times printed on the page itself.
 *  The offset is derived from the API's paired local + UTC datetimes; with no UTC
 *  to compare against, plain local time (no zone) is still treated as venue-local. */
function schema_start_date(array $event): string
{
    $sd = $event['start_date'] ?? [];
    $localDate = (string) ($sd['local_date'] ?? '');
    $localTime = (string) ($sd['local_time'] ?? '');
    $utc = (string) ($sd['date_time'] ?? '');
    if ($localDate === '') {
        return $utc;
    }
    if ($localTime === '' || !empty($sd['time_tba'])) {
        return $localDate;
    }
    if (strlen($localTime) === 5) {
        $localTime .= ':00';
    }
    $local = $localDate . 'T' . $localTime;
    if ($utc !== '') {
        try {
            $utcTs = (new DateTimeImmutable($utc))->getTimestamp();
            $localTs = (new DateTimeImmutable($local, new DateTimeZone('UTC')))->getTimestamp();
            $offsetMin = (int) round(($localTs - $utcTs) / 60 / 15) * 15;
            if (abs($offsetMin) <= 14 * 60) {
                return $local . sprintf('%s%02d:%02d', $offsetMin < 0 ? '-' : '+', intdiv(abs($offsetMin), 60), abs($offsetMin) % 60);
            }
        } catch (Exception $e) {
            // fall through to plain local time
        }
    }
    return $local;
}

/** Canonical on-site event URL. Partner checkout URLs stay behind /go on detail pages. */
function event_canonical_url(array $config, array $event): string
{
    return absolute_url($config, event_path($event));
}

/** Clean activity slug: title (clipped) + city when the title doesn't already name it. */
function activity_slug(array $activity): string
{
    $slug = slug_clip(slugify((string) ($activity['title'] ?? 'activity')));
    $cityRaw = trim((string) ($activity['city']['name'] ?? ''));
    if ($cityRaw !== '') {
        $city = slugify($cityRaw);
        if ($city !== 'tickets' && !slug_contains_word($slug, $city)) {
            $slug .= '-' . $city;
        }
    }
    return $slug;
}

function activity_path(array $activity): string
{
    // The API returns different titles for the same activity in list vs detail
    // responses; pin the first slug we ever learned so the canonical URL never
    // flip-flops between variants (older variants 301 to the pinned one).
    $id = (int) ($activity['id'] ?? 0);
    $slug = ($id > 0 ? slug_for_id('activity', $id) : null) ?? activity_slug($activity);
    slug_remember('activity', $slug, $id);
    return '/activity/' . $slug;
}

function artist_path(array $performer): string
{
    $slug = slugify((string) ($performer['name'] ?? 'artist'));
    slug_remember('artist', $slug, (int) ($performer['id'] ?? 0));
    return '/artist/' . $slug;
}

function artist_initials(string $name): string
{
    $words = preg_split('/\s+/', trim($name)) ?: [];
    $initials = '';
    foreach (array_slice($words, 0, 2) as $word) {
        $first = mb_substr($word, 0, 1);
        if (preg_match('/[\p{L}\p{N}]/u', $first)) {
            $initials .= mb_strtoupper($first);
        }
    }
    return $initials !== '' ? $initials : '♪';
}

function weekend_path(array $city): string
{
    return '/events/this-weekend-in-' . slugify((string) ($city['name'] ?? 'city'));
}

function city_date_path(array $city, string $dateKey): string
{
    $slug = slugify((string) ($city['name'] ?? 'city'));
    return match ($dateKey) {
        'today' => '/events/today-in-' . $slug,
        'week' => '/events/this-week-in-' . $slug,
        'weekend' => '/events/this-weekend-in-' . $slug,
        default => city_path($city),
    };
}

function city_path(array $city): string
{
    return '/city/' . slugify((string) $city['name']);
}

function city_category_path(array $city, string $categorySlug): string
{
    return city_path($city) . '/' . slugify($categorySlug);
}

function monthly_events_path(array $city, string $month): string
{
    return '/events/' . strtolower($month) . '-in-' . slugify((string) ($city['name'] ?? ''));
}

function category_path(array $category): string
{
    return '/category/' . slugify((string) $category['name']);
}

/* ---------- Clean-slug resolvers (slug → id) ---------- */

/** City slug → HelloTickets city id, via the canonical geo list + market cities. */
function resolve_city_id_by_slug(array $config, string $slug): ?int
{
    static $map = null;
    if ($map === null) {
        $map = [];
        foreach (geo_cities() as $id => $geo) {
            $map[slugify((string) ($geo['name'] ?? ''))] = (int) $id;
        }
        foreach ($config['market_cities'] as $city) {
            $map[slugify((string) ($city['name'] ?? ''))] = (int) ($city['id'] ?? 0);
        }
        unset($map['tickets']);
    }
    $id = $map[$slug] ?? 0;
    return $id > 0 ? $id : null;
}

/** Category slug → category array, from the (cached daily) categories list. */
function resolve_category_by_slug(HelloTicketsClient $client, string $slug): ?array
{
    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    foreach ($categories as $category) {
        if (slugify((string) ($category['name'] ?? '')) === $slug) {
            return $category;
        }
    }
    return null;
}

/**
 * Artist slug → performer id. Map first; then the performers name filter — it is
 * case-insensitive but accent-sensitive, so "celine-dion" also retries per word
 * ("dion" matches "Céline Dion") and verifies candidates by re-slugifying their name.
 */
function resolve_artist_id(HelloTicketsClient $client, string $slug): ?int
{
    $hit = slug_lookup('artist', $slug);
    if ($hit !== null) {
        return $hit;
    }
    if (strlen($slug) > 90) {
        return null; // generated slugs are clipped at 70 — longer can't be real
    }

    $needles = [str_replace('-', ' ', $slug)];
    $words = array_filter(explode('-', $slug), static fn(string $w): bool => strlen($w) >= 3);
    usort($words, static fn(string $a, string $b): int => strlen($b) <=> strlen($a));
    foreach (array_slice($words, 0, 3) as $word) {
        $needles[] = $word;
    }

    foreach (array_unique($needles) as $needle) {
        $performers = api_result(static fn() => $client->performers([
            'name' => $needle,
            'page' => 1,
            'limit' => 48,
        ]), ['performers' => []])['performers'] ?? [];
        foreach ($performers as $performer) {
            if (slugify((string) ($performer['name'] ?? '')) === $slug) {
                $id = (int) ($performer['id'] ?? 0);
                slug_remember('artist', $slug, $id);
                return $id > 0 ? $id : null;
            }
        }
    }

    return null;
}

/**
 * Event slug → performance id. Map first; cold misses search by name (the slug minus
 * its date/city tail, shortened word by word) narrowed to the slug's date when present,
 * and verify candidates by rebuilding their clean slug.
 */
function resolve_event_id(HelloTicketsClient $client, string $slug): ?int
{
    $hit = slug_lookup('event', $slug);
    if ($hit !== null) {
        return $hit;
    }

    if (strlen($slug) > 90) {
        return null; // generated slugs are clipped at 70 — longer can't be real
    }

    // Clean slugs are dateless; legacy indexed URLs may still carry a "-YYYY-MM-DD"
    // tail. Strip it to recover the evergreen base and use the date to narrow the
    // API search — but match against the dateless base, since event_slug() no longer
    // emits the date. The matched event then 301s to the clean URL upstream.
    $base = $slug;
    $dateParams = [];
    if (preg_match('/-(\d{4}-\d{2}-\d{2})$/', $slug, $match) === 1) {
        $base = substr($slug, 0, -11);
        $dateParams = ['local_date_from' => $match[1], 'local_date_to' => $match[1]];
    }

    // Shorten the needle word by word (the tail carries city words the API name
    // doesn't contain), down to a single word, capped at 6 probes. Every candidate
    // is verified by rebuilding its clean slug, so short needles can't mismatch.
    $words = explode('-', $base);
    $probes = 0;
    for ($take = count($words); $take >= 1 && $probes < 6; $take--, $probes++) {
        $needle = implode(' ', array_slice($words, 0, $take));
        if ($needle === '') {
            break;
        }
        $performances = api_result(static fn() => $client->performances(array_merge([
            'name' => $needle,
            'page' => 1,
            'limit' => 48,
        ], $dateParams)), ['performances' => []])['performances'] ?? [];
        foreach ($performances as $performance) {
            if (event_slug($performance) === $base) {
                $id = (int) ($performance['id'] ?? 0);
                slug_remember('event', $slug, $id);
                return $id > 0 ? $id : null;
            }
        }
    }
    return null;
}

/** Activity slug → activity id. Map first, then the activities free-text search. */
function resolve_activity_id(HelloTicketsClient $client, string $slug): ?int
{
    $hit = slug_lookup('activity', $slug);
    if ($hit !== null) {
        return $hit;
    }
    if (strlen($slug) > 90) {
        return null; // generated slugs are clipped at 70 — longer can't be real
    }

    $candidates = [];
    $words = explode('-', $slug);
    for ($take = count($words); $take >= max(1, count($words) - 3); $take--) {
        $needle = implode(' ', array_slice($words, 0, $take));
        if ($needle === '') {
            break;
        }
        $activities = api_result(static fn() => $client->activities([
            'query' => $needle,
            'page' => 1,
            'limit' => 48,
        ]), ['activities' => []])['activities'] ?? [];
        foreach ($activities as $activity) {
            if (activity_slug($activity) === $slug) {
                $id = (int) ($activity['id'] ?? 0);
                slug_remember('activity', $slug, $id);
                return $id > 0 ? $id : null;
            }
        }
        foreach (array_slice($activities, 0, 3) as $activity) {
            $candidateId = (int) ($activity['id'] ?? 0);
            if ($candidateId > 0) {
                $candidates[$candidateId] = true;
            }
        }
    }

    // List and detail responses can title the same activity differently; a slug
    // built from the detail title only matches when we compare against details.
    foreach (array_slice(array_keys($candidates), 0, 5) as $candidateId) {
        $detail = api_result(static fn() => $client->activity($candidateId));
        if (!empty($detail['id']) && activity_slug($detail) === $slug) {
            slug_remember('activity', $slug, (int) $detail['id']);
            return (int) $detail['id'];
        }
    }
    return null;
}

/** 301 to the canonical clean path, preserving any query string. */
function redirect_permanent(string $path): void
{
    $qs = (string) ($_SERVER['QUERY_STRING'] ?? '');
    header('Location: ' . $path . ($qs !== '' ? '?' . $qs : ''), true, 301);
}

function base64_url_encode(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function base64_url_decode(string $value): string
{
    $base64 = strtr($value, '-_', '+/');
    $padding = strlen($base64) % 4;
    if ($padding > 0) {
        $base64 .= str_repeat('=', 4 - $padding);
    }

    return (string) base64_decode($base64, true);
}

function go_url(array $item, string $type): string
{
    $target = (string) ($item['url'] ?? '');
    // TM items don't have integer ids; their tm_id (e.g. "K8vZ9174qlV") becomes the tracking id
    // so subId1 reads e.g. "event-tm-K8vZ9174qlV" in the affiliate dashboard.
    $id = !empty($item['tm_id']) ? 'tm-' . $item['tm_id'] : (string) ((int) ($item['id'] ?? 0));
    return route_url('/go', [
        'type' => $type,
        'id' => $id,
        'u' => base64_url_encode($target),
    ]);
}

function affiliate_url(array $config, string $destination, string $subId): string
{
    $separator = strpos($config['impact_url'], '?') === false ? '?' : '&';
    return $config['impact_url'] . $separator . http_build_query([
        'u' => $destination,
        'subId1' => $subId,
    ]);
}

/* ---------- Ticketmaster Discovery API: normalisers + helpers ----------
 * TM is the fallback source — its objects come back through TicketmasterClient and we
 * convert them here into the SAME shape HelloTickets returns (start_date.local_date,
 * venue.name/city, price_range.min_price, url, image, category.name). Once normalised,
 * event_card / artist_card / item_list_schema render TM rows with ZERO template changes.
 */

/** Unwrap a TM affiliate-wrapped URL (ticketmaster.evyy.net/...?u=ENCODED) to the
 *  underlying canonical ticketmaster.com URL. /go re-wraps it so our subId tracking
 *  always reflects the visitor's actual click on OUR site. */
function tm_canonical_url(string $maybeWrapped): string
{
    $host = strtolower((string) parse_url($maybeWrapped, PHP_URL_HOST));
    if ($host !== 'ticketmaster.evyy.net') {
        return $maybeWrapped;
    }
    $query = (string) parse_url($maybeWrapped, PHP_URL_QUERY);
    if ($query === '') {
        return $maybeWrapped;
    }
    parse_str($query, $parts);
    return is_string($parts['u'] ?? null) ? (string) $parts['u'] : $maybeWrapped;
}

/** Pick the best content image from a TM images[] (widest, non-fallback, 16:9 preferred). */
function tm_best_image(array $images): ?string
{
    $best = null;
    $bestScore = PHP_INT_MIN;
    foreach ($images as $img) {
        if (empty($img['url'])) {
            continue;
        }
        // Prefer the CDN variant closest to ~1024px. "Widest wins" used to pick
        // _SOURCE — TM's unresized original upload, often 1-5 MB — for 300px cards.
        $width = (int) ($img['width'] ?? 0);
        $score = -abs($width - 1024)
            + (empty($img['fallback']) ? 100000 : 0)
            + (($img['ratio'] ?? '') === '16_9' ? 5000 : 0)
            + (strpos((string) $img['url'], '_SOURCE') !== false ? -50000 : 0);
        if ($score > $bestScore) {
            $bestScore = $score;
            $best = (string) $img['url'];
        }
    }
    return $best;
}

/** Convert a TM event into the same shape HelloTickets returns, so the same templates
 *  render it without any branching. Always future-dated (TM client uses startDateTime=now). */
function tm_normalize_event(array $tmEvent): array
{
    $dates = $tmEvent['dates']['start'] ?? [];
    $venue = ($tmEvent['_embedded']['venues'] ?? [])[0] ?? [];
    $priceRanges = $tmEvent['priceRanges'] ?? [];
    $first = $priceRanges[0] ?? [];

    return [
        'id' => 0, // TM ids are strings; we don't expose a TM-only event detail route yet
        'tm_id' => (string) ($tmEvent['id'] ?? ''),
        'source' => 'ticketmaster',
        // TM returns HTML-encoded names ("Girls&#39; Semi-Finals") — decode once
        // here so JSON-LD carries clean text and templates don't double-escape.
        'name' => html_entity_decode((string) ($tmEvent['name'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        'url' => tm_canonical_url((string) ($tmEvent['url'] ?? '')),
        'image' => tm_best_image($tmEvent['images'] ?? []) ?? '',
        'category' => [
            'name' => (string) ($tmEvent['classifications'][0]['segment']['name'] ?? 'Event'),
        ],
        'start_date' => [
            'local_date' => (string) ($dates['localDate'] ?? ''),
            'local_time' => (string) ($dates['localTime'] ?? ''),
            'date_time' => (string) ($dates['dateTime'] ?? ''),
            'date_tba' => !empty($dates['dateTBA']),
            'time_tba' => !empty($dates['timeTBA']),
        ],
        'venue' => [
            'name' => (string) ($venue['name'] ?? ''),
            'city' => (string) ($venue['city']['name'] ?? ''),
            'address' => trim(
                (string) ($venue['address']['line1'] ?? '') . ', ' .
                (string) ($venue['city']['name'] ?? '') . ' ' .
                (string) ($venue['state']['stateCode'] ?? ''),
                ', '
            ),
            'street' => (string) ($venue['address']['line1'] ?? ''),
            'state' => (string) ($venue['state']['stateCode'] ?? ''),
            'zip_code' => (string) ($venue['postalCode'] ?? ''),
            'country_code' => (string) ($venue['country']['countryCode'] ?? ''),
            'tm_id' => (string) ($venue['id'] ?? ''),
        ],
        'price_range' => [
            'min_price' => (float) ($first['min'] ?? 0),
            'max_price' => (float) ($first['max'] ?? 0),
            'currency' => (string) ($first['currency'] ?? 'USD'),
        ],
        'performers' => array_values(array_map(static fn($a): array => [
            'tm_id' => (string) ($a['id'] ?? ''),
            'name' => (string) ($a['name'] ?? ''),
            'is_main' => true,
        ], $tmEvent['_embedded']['attractions'] ?? [])),
    ];
}

function tm_normalize_attraction(array $tm): array
{
    return [
        'id' => 0,
        'tm_id' => (string) ($tm['id'] ?? ''),
        'source' => 'ticketmaster',
        'name' => (string) ($tm['name'] ?? ''),
        'url' => tm_canonical_url((string) ($tm['url'] ?? '')),
        'image' => tm_best_image($tm['images'] ?? []) ?? '',
        'category' => [
            'name' => (string) ($tm['classifications'][0]['segment']['name'] ?? 'On Tour'),
        ],
        'total_performances' => (int) ($tm['upcomingEvents']['_total'] ?? 0),
    ];
}

function tm_normalize_venue(array $tm): array
{
    return [
        'tm_id' => (string) ($tm['id'] ?? ''),
        'name' => (string) ($tm['name'] ?? ''),
        'city' => (string) ($tm['city']['name'] ?? ''),
        'state' => (string) ($tm['state']['stateCode'] ?? ''),
        'country' => (string) ($tm['country']['name'] ?? ''),
        'address' => (string) ($tm['address']['line1'] ?? ''),
        'url' => tm_canonical_url((string) ($tm['url'] ?? '')),
        'image' => tm_best_image($tm['images'] ?? []) ?? '',
        'upcoming_total' => (int) ($tm['upcomingEvents']['_total'] ?? 0),
    ];
}

function tm_venue_path(array $venue): string
{
    $slug = slugify((string) ($venue['name'] ?? 'venue'));
    venue_slug_remember($slug, (string) ($venue['tm_id'] ?? ''));
    return '/venue/' . $slug;
}

/** TM id tail of a LEGACY /venue/{name}-{tmId} URL (TM ids are long mixed-case tokens). */
function tm_legacy_id_from_slug(string $slug): ?string
{
    $pos = strrpos($slug, '-');
    $tail = $pos === false ? $slug : substr($slug, $pos + 1);
    return preg_match('/^[A-Za-z0-9]{8,}$/', $tail) === 1 && preg_match('/[A-Z]/', $tail) === 1
        ? $tail
        : null;
}

function tm_client(array $config): ?TicketmasterClient
{
    static $client = null;
    if ($client !== null) {
        return $client;
    }
    // Prefer the multi-key list (tm_api_keys) for rotation; fall back to the single
    // tm_api_key. Both live in the gitignored config.local.php / env, never in git.
    $keys = $config['tm_api_keys'] ?? [];
    if (!is_array($keys) || $keys === []) {
        $keys = (string) ($config['tm_api_key'] ?? '');
    }
    $candidate = new TicketmasterClient($keys, $config['cache_dir'], (int) $config['cache_ttl']);
    if (!$candidate->isConfigured()) {
        return null;
    }
    $client = $candidate;
    return $client;
}

/** De-dupe a merged HT+TM event list by (date, venue-name). HT wins (higher commission);
 *  TM fills gaps. Re-sorted ascending by date for the "Tour Dates" rendering. */
function merge_events_dedupe(array $primary, array $secondary): array
{
    $key = static function (array $e): string {
        return (string) ($e['start_date']['local_date'] ?? '') . '|' .
               strtolower(trim((string) ($e['venue']['name'] ?? '')));
    };
    $seen = [];
    foreach ($primary as $e) {
        $seen[$key($e)] = true;
    }
    foreach ($secondary as $e) {
        $k = $key($e);
        if ($k === '|' || isset($seen[$k])) {
            continue;
        }
        $seen[$k] = true;
        $primary[] = $e;
    }
    usort($primary, static fn($a, $b): int => strcmp(
        (string) ($a['start_date']['local_date'] ?? '9999'),
        (string) ($b['start_date']['local_date'] ?? '9999')
    ));
    return $primary;
}

/** ISO-3166 alpha-3 (our geo data) → alpha-2 (Ticketmaster's countryCode). */
function tm_country_code(string $alpha3): string
{
    $trimmed = strtoupper(trim($alpha3));
    if (preg_match('/^[A-Z]{2}$/', $trimmed) === 1) {
        return $trimmed;
    }
    $map = [
        'USA' => 'US',
        'CAN' => 'CA',
        'GBR' => 'GB',
        'ARE' => 'AE',
        'ITA' => 'IT',
        'ESP' => 'ES',
        'FRA' => 'FR',
        'NLD' => 'NL',
        'DEU' => 'DE',
        'PRT' => 'PT',
        'AUS' => 'AU',
    ];
    return $map[strtoupper($alpha3)] ?? '';
}

/**
 * Ticketmaster events for one city, normalized to the HelloTickets shape.
 * This is the gap filler behind "HelloTickets first, Ticketmaster fills the rest":
 * HT stays primary (higher commission, our detail pages); TM covers the local
 * long tail HT misses, especially in North America.
 */
function tm_events_for_city(array $config, string $cityName, string $countryCode3, array $extra = [], int $size = 24): array
{
    $tm = tm_client($config);
    if ($tm === null || trim($cityName) === '') {
        return [];
    }
    $params = array_merge(['city' => $cityName, 'size' => $size], $extra);
    $alpha2 = tm_country_code($countryCode3);
    if ($alpha2 !== '') {
        $params['countryCode'] = $alpha2;
    }
    $raw = api_result(static fn() => $tm->events($params), []);
    return array_map('tm_normalize_event', $raw['_embedded']['events'] ?? []);
}

/**
 * Deep city pull: page through Ticketmaster so a city page can show its FULL
 * catalogue (hundreds of events) instead of a thin 24-event fill. Each TM page
 * is cached by the client, so repeat loads (and deeper pagination) are free.
 * De-duplicated by TM event id, so distinct shows at the same venue/date survive
 * (unlike the date|venue collapse used when blending HT + TM).
 */
function tm_events_for_city_deep(array $config, string $cityName, string $countryCode3, array $extra = [], int $maxPages = 2, int $perPage = 100): array
{
    $tm = tm_client($config);
    if ($tm === null || trim($cityName) === '') {
        return [];
    }
    $alpha2 = tm_country_code($countryCode3);
    $out = [];
    $seen = [];
    for ($page = 0; $page < $maxPages; $page++) {
        $params = array_merge(['city' => $cityName, 'size' => $perPage, 'page' => $page], $extra);
        if ($alpha2 !== '') {
            $params['countryCode'] = $alpha2;
        }
        $raw = api_result(static fn() => $tm->events($params), []);
        foreach ($raw['_embedded']['events'] ?? [] as $tmEvent) {
            $event = tm_normalize_event($tmEvent);
            $id = (string) ($event['tm_id'] ?? '');
            if ($id !== '' && isset($seen[$id])) {
                continue;
            }
            if ($id !== '') {
                $seen[$id] = true;
            }
            $out[] = $event;
        }
        $totalPages = (int) ($raw['page']['totalPages'] ?? 1);
        if ($page + 1 >= $totalPages) {
            break;
        }
    }
    return $out;
}

/**
 * Combine HelloTickets (primary, higher commission + own detail pages) with a deep
 * Ticketmaster pull into one date-sorted listing. HT wins any HT/TM duplicate
 * (matched loosely on date + venue); TM events are otherwise kept whole so the city
 * page is as deep as the catalogue allows.
 */
function city_event_pool(array $htEvents, array $tmEvents, array $config): array
{
    // Loose same-show key: date + first words of the name. Catches the common case
    // where HelloTickets and Ticketmaster both list a show (HT carries no image, TM
    // does) so we don't print the same gig twice.
    $nameKey = static function (array $e): string {
        $date = (string) ($e['start_date']['local_date'] ?? '');
        $name = strtolower((string) preg_replace('/[^a-z0-9 ]/i', '', (string) ($e['name'] ?? '')));
        $words = array_slice(array_values(array_filter(explode(' ', $name))), 0, 3);
        return $date . '|' . implode(' ', $words);
    };
    // Does this event resolve to a real cover, or just a generic Unsplash fallback?
    // (HelloTickets returns no images; un-harvested cities fall back, which looks empty.)
    $hasRealImage = static fn(array $e): int =>
        strpos(image_from_item($e, 'event', $config), 'images.unsplash.com') === false ? 1 : 0;

    // Merge, keeping ONE row per show — prefer the copy that actually has a picture.
    $best = [];
    $loose = [];
    foreach (array_merge($htEvents, $tmEvents) as $event) {
        $k = $nameKey($event);
        if ($k === '|' || trim($k, '|') === '') {
            $loose[] = $event;
            continue;
        }
        if (!isset($best[$k]) || $hasRealImage($event) > $hasRealImage($best[$k])) {
            $best[$k] = $event;
        }
    }
    $pool = array_merge(array_values($best), $loose);

    // Order: real-cover events first (so a city looks full of real listings, not a
    // wall of fallbacks), then soonest first. Decorate-sort so image_from_item runs
    // once per event, not on every comparison.
    $decorated = array_map(static fn(array $e): array => [
        'e' => $e,
        'img' => $hasRealImage($e),
        'date' => (string) ($e['start_date']['local_date'] ?? '9999'),
    ], $pool);
    usort($decorated, static function (array $a, array $b): int {
        if ($a['img'] !== $b['img']) {
            return $b['img'] - $a['img'];
        }
        return strcmp($a['date'], $b['date']);
    });
    return array_map(static fn(array $x): array => $x['e'], $decorated);
}

/** Clean /artist/{slug} → TM attraction, for artists HelloTickets doesn't know at all. */
function tm_artist_by_slug(array $config, string $slug): ?array
{
    $tm = tm_client($config);
    if ($tm === null || $slug === '' || strlen($slug) > 90) {
        return null;
    }
    $rememberedId = tm_artist_slug_lookup($slug);
    if ($rememberedId !== null) {
        $remembered = api_result(static fn() => $tm->attraction($rememberedId), []);
        if (!empty($remembered['id'])) {
            return $remembered;
        }
    }
    $raw = api_result(static fn() => $tm->attractions([
        'keyword' => str_replace('-', ' ', $slug),
        'size' => 5,
    ]), []);
    foreach ($raw['_embedded']['attractions'] ?? [] as $attraction) {
        if (slugify((string) ($attraction['name'] ?? '')) === $slug) {
            tm_artist_slug_remember($slug, (string) ($attraction['id'] ?? ''));
            return $attraction;
        }
    }
    return null;
}

function allowed_hellotickets_url(string $url): bool
{
    // Exact registrable-domain match so e.g. "hellotickets.attacker.com" is rejected
    // (the old "contains hellotickets." regex was an open-redirect / affiliate-fraud vector).
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));
    return in_array($scheme, ['http', 'https'], true)
        && ($host === 'hellotickets.com' || str_ends_with($host, '.hellotickets.com'));
}

function allowed_ticketmaster_url(string $url): bool
{
    // Same exact-domain guard as HelloTickets. Ticketmaster's public ticket pages live on
    // ticketmaster.com (+ cc.) and the regional ticketmaster.{co.uk,ca,…} domains.
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));
    if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
        return false;
    }
    foreach (['ticketmaster.com', 'ticketmaster.co.uk', 'ticketmaster.ca', 'ticketmaster.es',
              'ticketmaster.it', 'ticketmaster.fr', 'ticketmaster.de', 'ticketmaster.ie',
              'ticketmaster.com.au', 'livenation.com'] as $domain) {
        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
            return true;
        }
    }
    return false;
}

function ticketmaster_affiliate_url(array $config, string $destination, string $subId): string
{
    $base = (string) ($config['tm_impact_url'] ?? '');
    $separator = strpos($base, '?') === false ? '?' : '&';
    return $base . $separator . http_build_query([
        'u' => $destination,
        'subId1' => $subId,
    ]);
}

/**
 * Source-aware outbound wrapper: returns the correctly-monetised affiliate link for a
 * destination, picked by its DOMAIN (not a spoofable param) — HelloTickets URLs go through
 * the HelloTickets Impact link, Ticketmaster URLs through the Ticketmaster Impact link.
 * Returns null if the destination is neither partner (so /go can 400 it).
 */
function outbound_affiliate_url(array $config, string $destination, string $subId): ?string
{
    if (allowed_hellotickets_url($destination)) {
        return affiliate_url($config, $destination, $subId);
    }
    if (allowed_ticketmaster_url($destination)) {
        return ticketmaster_affiliate_url($config, $destination, $subId);
    }
    return null;
}

function api_result(callable $callback, array $fallback = []): array
{
    try {
        $result = $callback();
        return is_array($result) ? $result : $fallback;
    } catch (Throwable $exception) {
        error_log('[api] ' . $exception->getMessage());
        return $fallback;
    }
}

/**
 * Pick a deterministic-unique slice of FAQs for an entity page from the
 * shared pool in faq-pool.php.
 *
 * Same slug → same FAQs every time (cache-safe). Across 25-30 templates and
 * a count of 6-8, two arbitrary slugs almost never line up — so every
 * artist/city/venue page renders a different mix.
 *
 * Entries whose answers still contain unfilled {placeholders} after strtr()
 * are skipped, so an artist with no live price won't ship a half-rendered
 * "starting from {min_price}" answer.
 *
 * @param string $type One of the buckets in faq-pool.php (artist, city, ...).
 * @param string $slug Stable per-entity identifier — drives the shuffle seed.
 * @param array  $data Map of "{placeholder}" => "live value".
 * @param int    $count Target number of FAQs to return.
 * @return array<int, array{q:string,a:string}>
 */
function unique_faqs(string $type, string $slug, array $data, int $count = 8): array
{
    static $pool = null;
    if ($pool === null) {
        $file = __DIR__ . '/faq-pool.php';
        $pool = is_file($file) ? require $file : [];
    }
    $bucket = $pool[$type] ?? [];
    if ($bucket === []) {
        return [];
    }

    // Deterministic Fisher–Yates shuffle seeded by the slug hash. Same slug
    // → same order on every render → identical FAQs every visit (cache-safe).
    $seed = crc32($slug);
    $indexed = $bucket;
    $n = count($indexed);
    for ($i = $n - 1; $i > 0; $i--) {
        $seed = ($seed * 1103515245 + 12345) & 0x7FFFFFFF;
        $j = $seed % ($i + 1);
        [$indexed[$i], $indexed[$j]] = [$indexed[$j], $indexed[$i]];
    }

    $picked = array_slice($indexed, 0, min($count, $n));
    $result = [];
    foreach ($picked as $entry) {
        $q = strtr($entry['q'], $data);
        $a = strtr($entry['a'], $data);
        // Skip entries with placeholders we couldn't fill — better to ship
        // fewer clean FAQs than a question with a literal "{min_price}" in it.
        if (preg_match('/\{[a-z_]+\}/', $a) || preg_match('/\{[a-z_]+\}/', $q)) {
            continue;
        }
        $result[] = ['q' => $q, 'a' => $a];
    }
    return $result;
}
