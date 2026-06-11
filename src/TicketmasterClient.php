<?php
declare(strict_types=1);

/**
 * Thin HTTP wrapper around the Ticketmaster Discovery API v2.
 *
 * Mirrors the shape of HelloTicketsClient (constructor signature, caching, get()) so the
 * unified catalog layer can treat both sources the same. Caches to the SAME storage/cache
 * dir but with a "tm:" key prefix so we never collide with HelloTickets entries.
 *
 * Rate limits: TM caps each key at ~5 req/s and 5,000/day. The cache (default 1h) plus a
 * 230ms inter-request usleep keep us well under both. We ALSO rotate across multiple keys
 * — each key has its own independent quota, so N keys ≈ N× the daily headroom — and a 429
 * on one key fails over to the next before we ever back off.
 */
final class TicketmasterClient
{
    /** @var string[] one or more Discovery consumer keys, rotated per request */
    private array $apiKeys;
    private int $keyCursor = 0;
    private string $cacheDir;
    private int $defaultTtl;
    private string $baseUrl = 'https://app.ticketmaster.com/discovery/v2';

    /**
     * @param string|string[] $apiKey A single key, a comma/space-separated list, or an
     *                                array of keys. Multiple keys are load-balanced.
     */
    public function __construct($apiKey, string $cacheDir, int $defaultTtl = 3600)
    {
        $keys = is_array($apiKey) ? $apiKey : preg_split('/[,\s]+/', (string) $apiKey);
        $this->apiKeys = array_values(array_unique(array_filter(
            array_map('trim', $keys ?: []),
            static fn($k): bool => $k !== ''
        )));
        $this->cacheDir = $cacheDir;
        $this->defaultTtl = $defaultTtl;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    /** True when at least one key is configured — caller can skip TM entirely if not. */
    public function isConfigured(): bool
    {
        return $this->apiKeys !== [];
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

        // apikey is appended per-attempt inside request() so 429 failover can swap keys.
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
        $n = count($this->apiKeys);
        if ($n === 0) {
            return null;
        }
        // Rotate the starting key every request so load spreads evenly across keys.
        $this->keyCursor = ($this->keyCursor + 1) % $n;

        // Try each key once. A 429 on one key (its quota is spent) fails over to the
        // next key, which has its own independent quota — no waiting needed.
        for ($i = 0; $i < $n; $i++) {
            $key = $this->apiKeys[($this->keyCursor + $i) % $n];
            [$status, $body] = $this->fetch($url . '&apikey=' . urlencode($key));

            if ($status === 429) {
                continue; // this key is rate-limited — try the next one
            }
            if ($status >= 400 || $body === null) {
                return null;
            }
            if (PHP_SAPI === 'cli') {
                usleep(230000); // ~4 req/s ceiling for batch scripts; never delay a visitor
            }
            return $body;
        }

        // Every key was rate-limited at once (rare). Back off once, then retry the rotation.
        if ($retry === 0) {
            usleep(900000);
            return $this->request($url, 1);
        }
        return null;
    }

    /** Single HTTP GET. Returns [statusCode, body|null]. */
    private function fetch(string $url): array
    {
        $headers = ['Accept: application/json', 'User-Agent: TheTicketers/1.0'];

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
            return [$status, $body === false ? null : (string) $body];
        }

        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'header' => implode("\r\n", $headers),
            'timeout' => 12,
            'ignore_errors' => true,
        ]]);
        $body = @file_get_contents($url, false, $ctx);
        // Parse the status line from $http_response_header so 429 failover works without curl.
        $status = 0;
        foreach ($http_response_header ?? [] as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $h, $m)) {
                $status = (int) $m[1];
            }
        }
        return [$status, $body === false ? null : (string) $body];
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
