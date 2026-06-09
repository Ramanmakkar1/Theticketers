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
            mkdir($this->cacheDir, 0775, true);
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

    public function cities(array $params = []): array
    {
        return $this->get('/v1/cities', $params, 86400);
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

        $body = $this->request($url);
        $decoded = json_decode($body, true);

        if (!is_array($decoded)) {
            throw new RuntimeException('HelloTickets returned invalid JSON.');
        }

        file_put_contents($cacheFile, json_encode($decoded, JSON_UNESCAPED_SLASHES));

        return $decoded;
    }

    private function request(string $url): string
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
            ]);

            $body = curl_exec($handle);
            $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
            $error = curl_error($handle);
            curl_close($handle);

            if ($body === false) {
                throw new RuntimeException('Could not connect to HelloTickets: ' . $error);
            }

            if ($status >= 400) {
                throw new RuntimeException('HelloTickets API error ' . $status . ': ' . substr((string) $body, 0, 300));
            }

            return (string) $body;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\n", $headers),
                'timeout' => 12,
            ],
        ]);

        $body = file_get_contents($url, false, $context);
        if ($body === false) {
            throw new RuntimeException('Could not connect to HelloTickets.');
        }

        return (string) $body;
    }
}

