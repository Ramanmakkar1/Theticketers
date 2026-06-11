<?php
declare(strict_types=1);

/**
 * Thin HTTP wrapper around the Ticketmaster Discovery API v2.
 *
 * Mirrors the shape of HelloTicketsClient (constructor signature, caching, get()) so the
 * unified catalog layer can treat both sources the same. Caches to the SAME storage/cache
 * dir but with a "tm:" key prefix so we never collide with HelloTickets entries.
 *
 * Rate limits: TM caps free keys at ~5 req/s and 5,000/day. The cache (default 1h) plus a
 * 230ms inter-request usleep keep us well under both. We back off ONCE on a 429.
 */
final class TicketmasterClient
{
    private string $apiKey;
    private string $cacheDir;
    private int $defaultTtl;
    private string $baseUrl = 'https://app.ticketmaster.com/discovery/v2';

    public function __construct(string $apiKey, string $cacheDir, int $defaultTtl = 3600)
    {
        $this->apiKey = $apiKey;
        $this->cacheDir = $cacheDir;
        $this->defaultTtl = $defaultTtl;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    /** True when a key is configured — caller can skip TM entirely if not. */
    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    /** Upcoming events, filtered to future-dated by startDateTime=now (UTC). */
    public function events(array $params = []): array
    {
        return $this->get('events.json', $this->withFutureOnly($params), 1800);
    }

    public function event(string $id): array
    {
        return $this->get('events/' . urlencode($id) . '.json', [], 1800);
    }

    /** Artists / teams / shows — TM calls these "attractions". */
    public function attractions(array $params = []): array
    {
        return $this->get('attractions.json', $params, 3600);
    }

    public function attraction(string $id): array
    {
        return $this->get('attractions/' . urlencode($id) . '.json', [], 3600);
    }

    public function venues(array $params = []): array
    {
        return $this->get('venues.json', $params, 86400);
    }

    public function venue(string $id): array
    {
        return $this->get('venues/' . urlencode($id) . '.json', [], 86400);
    }

    public function get(string $path, array $params = [], ?int $ttl = null): array
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $params = array_filter($params, static fn($v): bool => $v !== null && $v !== '');
        ksort($params);

        $cacheKey = 'tm-' . sha1($path . '?' . http_build_query($params));
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        if (is_file($cacheFile) && time() - filemtime($cacheFile) < $ttl) {
            $cached = json_decode((string) file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $params['apikey'] = $this->apiKey;
        $url = $this->baseUrl . '/' . ltrim($path, '/') . '?' . http_build_query($params);

        $body = $this->request($url);
        $decoded = $body !== null ? json_decode($body, true) : null;

        if (!is_array($decoded)) {
            // Stale-on-error: a TM outage serves yesterday's data instead of an
            // empty section. touch() spaces out retries so we don't stampede.
            if (is_file($cacheFile)) {
                $stale = json_decode((string) file_get_contents($cacheFile), true);
                if (is_array($stale)) {
                    @touch($cacheFile, time() - $ttl + 300);
                    return $stale;
                }
            }
            return [];
        }

        file_put_contents($cacheFile, json_encode($decoded, JSON_UNESCAPED_SLASHES));
        return $decoded;
    }

    private function request(string $url, int $retry = 0): ?string
    {
        $headers = ['Accept: application/json', 'User-Agent: TickedBus/1.0'];

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 12,
                CURLOPT_CONNECTTIMEOUT => 6,
            ]);
            $body = curl_exec($ch);
            $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            if ($status === 429 && $retry === 0) {
                usleep(900000);
                return $this->request($url, 1);
            }
            if ($status >= 400 || $body === false) {
                return null;
            }
            if (PHP_SAPI === 'cli') {
                usleep(230000); // ~4 req/s ceiling for batch scripts; never delay a visitor
            }
            return (string) $body;
        }

        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'header' => implode("\r\n", $headers),
            'timeout' => 12,
            'ignore_errors' => true,
        ]]);
        $body = @file_get_contents($url, false, $ctx);
        return $body === false ? null : (string) $body;
    }

    private function withFutureOnly(array $params): array
    {
        if (!isset($params['startDateTime'])) {
            // Truncated to MIDNIGHT (not now) so the cache key is stable all day —
            // a per-second timestamp made every page view a fresh live API call,
            // burning the 5k/day quota and writing a new cache file per hit.
            $params['startDateTime'] = gmdate('Y-m-d\T00:00:00\Z');
        }
        if (!isset($params['sort'])) {
            $params['sort'] = 'date,asc';
        }
        return $params;
    }
}
