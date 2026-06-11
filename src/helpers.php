<?php
declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
    return is_numeric($id) && (int) $id > 0 ? (int) $id : null;
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
    $disk = is_string($raw) && $raw !== '' ? (json_decode($raw, true) ?: []) : [];
    foreach ($pending as $type => $entries) {
        foreach ($entries as $slug => $id) {
            unset($disk[$type][$slug]); // re-insert at the end = most recently seen
            $disk[$type][$slug] = $id;
        }
        if (count($disk[$type]) > 4000) {
            $disk[$type] = array_slice($disk[$type], -4000, null, true);
        }
    }
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($disk, JSON_UNESCAPED_SLASHES));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    $pending = [];
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

function image_from_item(array $item, string $type, array $config): string
{
    if (!empty($item['image']) && is_string($item['image'])) {
        return $item['image'];
    }

    if (!empty($item['images']) && is_array($item['images'])) {
        $first = $item['images'][0] ?? null;
        if (is_string($first)) {
            return $first;
        }
        if (is_array($first) && !empty($first['url'])) {
            return (string) $first['url'];
        }
    }

    // Real harvested photo for this exact item, if we have one.
    $id = (int) ($item['id'] ?? 0);
    if ($id > 0) {
        $map = image_map();
        $key = $type . '-' . $id;
        if (!empty($map[$key]) && is_string($map[$key])) {
            return $map[$key];
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
                return $performerImage;
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

    if (preg_match('#^/events/this-weekend-in-([^/]+)$#', $path, $match) === 1
        || preg_match('#^/city/([^/]+)$#', $path, $match) === 1) {
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

    $lead = null;
    $leadLabel = '';
    $leadWindow = '';
    foreach ([
        ['today', 'Happening today in ' . $cityName],
        ['week', 'This week in ' . $cityName],
        ['month', 'This month in ' . $cityName],
    ] as [$window, $label]) {
        $items = $fetch($window, $cityId);
        if (count($items) >= 3) {
            $lead = $items;
            $leadLabel = $label;
            $leadWindow = $window;
            break;
        }
    }

    $rails = [];
    $upcoming = $fetch('upcoming', $cityId);

    if ($lead !== null) {
        $rails[] = ['label' => $leadLabel, 'items' => $lead, 'href' => route_url('/events', ['date' => $leadWindow])];
        $leadIds = array_map(static fn($e) => (int) ($e['id'] ?? 0), $lead);
        $more = array_values(array_filter($upcoming, static fn($e) => !in_array((int) ($e['id'] ?? 0), $leadIds, true)));
        if (count($more) >= 4) {
            $rails[] = ['label' => 'More upcoming events in ' . $cityName, 'items' => $more, 'href' => '/events'];
        }
        return $rails;
    }

    if (count($upcoming) >= 3) {
        $rails[] = ['label' => 'Upcoming events in ' . $cityName, 'items' => $upcoming, 'href' => '/events'];
        return $rails;
    }

    // City has no events of its own — pull from nearby cities in the same country.
    $nearby = [];
    foreach (nearby_city_ids($cityId, $config) as $nearbyId) {
        $nearby = array_merge($nearby, $fetch('upcoming', $nearbyId, 8));
        if (count($nearby) >= 12) {
            break;
        }
    }
    if ($nearby !== []) {
        $rails[] = ['label' => 'Events near ' . $cityName, 'items' => array_slice($nearby, 0, 12), 'href' => '/events'];
    }
    return $rails;
}

/**
 * Clean descriptive event slug: name + city + date ("bad-bunny-san-juan-2026-07-18").
 * City and date make same-name shows (tours, repeat nights) unique without exposing
 * database ids, and read like a real search query.
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
    $date = (string) ($performance['start_date']['local_date'] ?? '');
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1) {
        $slug .= '-' . $date;
    }
    return $slug;
}

function event_path(array $performance): string
{
    $slug = event_slug($performance);
    slug_remember('event', $slug, (int) ($performance['id'] ?? 0));
    return '/event/' . $slug;
}

/** Canonical "where this event lives" URL — TM-sourced events point straight at the partner page
 *  (we don't own a detail page for them); HT events point at our /event/{slug}. */
function event_canonical_url(array $config, array $event): string
{
    if (!empty($event['tm_id']) && !empty($event['url'])) {
        return (string) $event['url'];
    }
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
    $slug = activity_slug($activity);
    slug_remember('activity', $slug, (int) ($activity['id'] ?? 0));
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

function city_path(array $city): string
{
    return '/city/' . slugify((string) $city['name']);
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

    $base = $slug;
    $dateParams = [];
    if (preg_match('/-(\d{4}-\d{2}-\d{2})$/', $slug, $match) === 1) {
        $base = substr($slug, 0, -11);
        $dateParams = ['local_date_from' => $match[1], 'local_date_to' => $match[1]];
    }

    $words = explode('-', $base);
    for ($take = count($words); $take >= max(1, count($words) - 3); $take--) {
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
            if (event_slug($performance) === $slug) {
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
        $score = (int) ($img['width'] ?? 0)
            + (empty($img['fallback']) ? 100000 : 0)
            + (($img['ratio'] ?? '') === '16_9' ? 5000 : 0);
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
        'name' => (string) ($tmEvent['name'] ?? ''),
        'url' => tm_canonical_url((string) ($tmEvent['url'] ?? '')),
        'image' => tm_best_image($tmEvent['images'] ?? []) ?? '',
        'category' => [
            'name' => (string) ($tmEvent['classifications'][0]['segment']['name'] ?? 'Event'),
        ],
        'start_date' => [
            'local_date' => (string) ($dates['localDate'] ?? ''),
            'local_time' => (string) ($dates['localTime'] ?? ''),
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
    return '/venue/' . slugify((string) ($venue['name'] ?? 'venue'));
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
    $key = (string) ($config['tm_api_key'] ?? '');
    if ($key === '') {
        return null;
    }
    $client = new TicketmasterClient($key, $config['cache_dir'], (int) $config['cache_ttl']);
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
