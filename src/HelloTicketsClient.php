<?php
declare(strict_types=1);

final class HelloTicketsClient
{
    private string $baseUrl;
    private string $publicKey;
    private string $currency;
    private string $locale;
    private string $cacheDir;
    private int $defaultTtl;

    public function __construct(string $baseUrl, string $publicKey, string $currency, string $locale, string $cacheDir, int $defaultTtl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->publicKey = $publicKey;
        $this->currency = $currency;
        $this->locale = $locale;
        $this->cacheDir = $cacheDir;
        $this->defaultTtl = $defaultTtl;

        if (!is_dir($this->cacheDir)) {
            @mkdir($this->cacheDir, 0775, true);
        }
    }

    public function performances(array $params = []): array
    {
        return $this->get('/v1/performances', $params);
    }

    public function performance(int $id): array
    {
        $response = $this->get('/v1/performances/' . $id, [], 900);
        return $response['performance'] ?? $response;
    }

    public function performers(array $params = []): array
    {
        return $this->get('/v1/performers', $params, 3600);
    }

    public function performer(int $id): array
    {
        $response = $this->get('/v1/performers/' . $id, [], 3600);
        return $response['performer'] ?? $response;
    }

    public function activities(array $params = []): array
    {
        return $this->get('/v1/activities', $params);
    }

    public function activity(int $id): array
    {
        return $this->get('/v1/activities/' . $id, [], 1800);
    }

    public function activityDates(int $id, array $params = []): array
    {
        return $this->get('/v1/activities/' . $id . '/dates', $params, 1800);
    }

    public function categories(): array
    {
        return $this->get('/v1/categories', [], 86400);
    }

    public function get(string $path, array $params = [], ?int $ttl = null): array
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $params = array_filter($params, static function ($value): bool {
            return $value !== null && $value !== '';
        });

        ksort($params);
        $cacheKey = sha1($path . '?' . http_build_query($params) . '|' . $this->currency . '|' . $this->locale);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.json';

        if (is_file($cacheFile) && time() - filemtime($cacheFile) < $ttl) {
            $cached = json_decode((string) file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');
        if ($params !== []) {
            $url .= '?' . http_build_query($params);
        }

        try {
            $body = $this->request($url);
            $decoded = json_decode($body, true);
            if (!is_array($decoded)) {
                throw new RuntimeException('HelloTickets returned invalid JSON.');
            }
        } catch (Throwable $e) {
            // Stale-on-error: an API blip serves slightly-old data instead of an
            // empty page. touch() gives a 5-minute grace before the next retry,
            // so concurrent visitors don't stampede a struggling upstream.
            if (is_file($cacheFile)) {
                $stale = json_decode((string) file_get_contents($cacheFile), true);
                if (is_array($stale)) {
                    @touch($cacheFile, time() - $ttl + 300);
                    return $stale;
                }
            }
            throw $e;
        }

        // Atomic write: a bare file_put_contents() can leave a truncated JSON file when
        // concurrent FPM workers write the same cache key. Write to a temp file then
        // rename() (atomic on the same filesystem) so readers never see a partial file.
        $json = json_encode($decoded, JSON_UNESCAPED_SLASHES);
        $tmp = $cacheFile . '.tmp' . getmypid();
        if ($json !== false && @file_put_contents($tmp, $json) === strlen($json)) {
            @rename($tmp, $cacheFile);
        } else {
            @unlink($tmp);
        }

        return $decoded;
    }

    private function request(string $url): string
    {
        // Retry transient failures (connect drop, 429, 5xx) up to 2 times with simple
        // backoff so one blip on a cold page doesn't render an empty section. The final
        // attempt still throws, preserving the stale-on-error path in get().
        $attempts = 3; // 1 initial + 2 retries
        $backoff = [300000, 700000]; // ~300ms, ~700ms between attempts

        for ($attempt = 0; $attempt < $attempts; $attempt++) {
            [$status, $body, $retryAfter, $error] = $this->fetch($url);

            $connectFailed = ($body === null);
            $retryable = $connectFailed || in_array($status, [429, 502, 503, 504], true);
            $isLastAttempt = ($attempt === $attempts - 1);

            // Non-retryable 4xx (e.g. 400/404) must fail immediately.
            if (!$retryable && $status >= 400) {
                throw new RuntimeException('HelloTickets API error ' . $status . ': ' . substr((string) $body, 0, 300));
            }

            if (!$retryable) {
                return (string) $body;
            }

            if ($isLastAttempt) {
                if ($connectFailed) {
                    throw new RuntimeException('Could not connect to HelloTickets: ' . $error);
                }
                throw new RuntimeException('HelloTickets API error ' . $status . ': ' . substr((string) $body, 0, 300));
            }

            // Back off before the next attempt. Honor Retry-After on 429 if present.
            $sleep = $backoff[$attempt] ?? end($backoff);
            if ($status === 429 && $retryAfter > 0) {
                $sleep = min($retryAfter * 1000000, 5000000); // cap at 5s
            }
            usleep($sleep);
        }

        // Unreachable — the loop always returns or throws — but keep the contract explicit.
        throw new RuntimeException('Could not connect to HelloTickets.');
    }

    /**
     * Single HTTP GET. Returns [statusCode, body|null, retryAfterSeconds, error].
     * body is null on a connect-level failure.
     */
    private function fetch(string $url): array
    {
        $headers = [
            'Accept: application/json',
            'X-Public-Key: ' . $this->publicKey,
            'X-Currency: ' . $this->currency,
            'Accept-Language: ' . $this->locale,
        ];

        if (function_exists('curl_init')) {
            $handle = curl_init($url);
            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 12,
                CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_FAILONERROR => false,
                CURLOPT_HEADER => true,
            ]);

            $raw = curl_exec($handle);
            $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
            $headerSize = (int) curl_getinfo($handle, CURLINFO_HEADER_SIZE);
            $error = curl_error($handle);
            curl_close($handle);

            if ($raw === false) {
                return [$status, null, 0, $error];
            }

            $rawHeaders = substr((string) $raw, 0, $headerSize);
            $body = substr((string) $raw, $headerSize);
            $retryAfter = $this->parseRetryAfter($rawHeaders);

            return [$status, (string) $body, $retryAfter, ''];
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
                'timeout' => 12,
                'ignore_errors' => true,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            return [0, null, 0, 'connection failed'];
        }

        // Parse the status line + Retry-After from $http_response_header so the stream
        // transport handles statuses identically to curl (mirrors TicketmasterClient::fetch).
        $status = 0;
        $retryAfter = 0;
        foreach ($http_response_header ?? [] as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $h, $m)) {
                $status = (int) $m[1];
            } elseif (preg_match('#^Retry-After:\s*(\d+)#i', $h, $m)) {
                $retryAfter = (int) $m[1];
            }
        }

        return [$status, (string) $body, $retryAfter, ''];
    }

    /** Pull an integer-seconds Retry-After value out of a raw header blob (0 if absent). */
    private function parseRetryAfter(string $rawHeaders): int
    {
        if (preg_match('#^Retry-After:\s*(\d+)#im', $rawHeaders, $m)) {
            return (int) $m[1];
        }
        return 0;
    }
}

