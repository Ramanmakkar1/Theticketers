<?php
declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function slugify(string $value): string
{
    $converted = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) : $value;
    $converted = strtolower((string) $converted);
    $converted = preg_replace('/[^a-z0-9]+/', '-', $converted) ?: '';
    return trim($converted, '-') ?: 'tickets';
}

function id_slug(string $name, int $id): string
{
    return slugify($name) . '-' . $id;
}

function id_from_slug(string $slug): int
{
    if (preg_match('/-(\d+)$/', $slug, $match)) {
        return (int) $match[1];
    }

    return (int) $slug;
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
 * Real-image map harvested from HelloTickets pages by bin/enrich-images.php.
 * Loaded once per request and keyed by "type-id" (e.g. "activity-2459").
 */
function image_map(): array
{
    static $map = null;
    if ($map === null) {
        $file = __DIR__ . '/../storage/images.json';
        $map = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }
    return $map;
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

function active_city_id(array $config): int
{
    $cookie = (int) ($_COOKIE['tb_city'] ?? 0);
    if ($cookie > 0) {
        foreach ($config['market_cities'] as $city) {
            if ((int) $city['id'] === $cookie) {
                return $cookie;
            }
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

function event_path(array $performance): string
{
    return '/event/' . id_slug((string) ($performance['name'] ?? 'event'), (int) ($performance['id'] ?? 0));
}

function activity_path(array $activity): string
{
    return '/activity/' . id_slug((string) ($activity['title'] ?? 'activity'), (int) ($activity['id'] ?? 0));
}

function city_path(array $city): string
{
    return '/city/' . id_slug((string) $city['name'], (int) $city['id']);
}

function category_path(array $category): string
{
    return '/category/' . id_slug((string) $category['name'], (int) $category['id']);
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
    return route_url('/go', [
        'type' => $type,
        'id' => (int) ($item['id'] ?? 0),
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

function allowed_hellotickets_url(string $url): bool
{
    // Exact registrable-domain match so e.g. "hellotickets.attacker.com" is rejected
    // (the old "contains hellotickets." regex was an open-redirect / affiliate-fraud vector).
    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));
    return in_array($scheme, ['http', 'https'], true)
        && ($host === 'hellotickets.com' || str_ends_with($host, '.hellotickets.com'));
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
