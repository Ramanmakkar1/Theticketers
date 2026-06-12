<?php
declare(strict_types=1);

/**
 * indexnow-ping.php — Submit new/updated URLs to Bing and Yandex via IndexNow.
 * Run after seo-index rebuild or content updates.
 *   php bin/indexnow-ping.php
 *   php bin/indexnow-ping.php --limit=100
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';
require $root . '/src/helpers.php';

$opts = getopt('', ['limit:']);
$limit = max(1, min(10000, (int) ($opts['limit'] ?? 500)));

$key = '3b755c4bf17a4f638f12b503d0de9d44';

$host = parse_url($config['site_url'], PHP_URL_HOST);
$seoIndex = seo_index();
$urls = [];
foreach ($seoIndex['urls'] ?? [] as $bucket => $bucketUrls) {
    foreach ($bucketUrls as $path) {
        $urls[] = $config['site_url'] . $path;
        if (count($urls) >= $limit) break 2;
    }
}

if ($urls === []) { echo "No URLs to submit.\n"; exit(0); }

$payload = json_encode(['host' => $host, 'key' => $key, 'keyLocation' => $config['site_url'] . '/' . $key . '.txt', 'urlList' => $urls]);

$endpoints = ['https://api.indexnow.org/indexnow', 'https://www.bing.com/indexnow'];
foreach ($endpoints as $endpoint) {
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=utf-8'],
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30]);
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$endpoint: HTTP $status\n";
}

echo "Submitted " . count($urls) . " URLs.\n";
