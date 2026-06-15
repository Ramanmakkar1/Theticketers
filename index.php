<?php
declare(strict_types=1);

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Asia/Dubai');
ini_set('display_errors', '0'); // never leak warnings/stack traces to visitors in production

// Baseline security headers on every route (sent before any output).
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

$config = require __DIR__ . '/src/config.php';

require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/HelloTicketsClient.php';
require __DIR__ . '/src/TicketmasterClient.php';
require __DIR__ . '/src/pages.php';
require __DIR__ . '/src/dubai-pages.php';
require __DIR__ . '/src/destinations.php';

$dubaiContent = file_exists(__DIR__ . '/src/dubai-content.php')
    ? require __DIR__ . '/src/dubai-content.php'
    : ['categories' => [], 'attractions' => []];

$destinationsContent = file_exists(__DIR__ . '/src/destinations-content.php')
    ? require __DIR__ . '/src/destinations-content.php'
    : ['countries' => [], 'cities' => []];

// Resolve the display currency for this request (page market wins, then the
// visitor's saved city) so API prices, "from" prices and schema all agree.
$config['currency'] = request_currency($config);

$client = new HelloTicketsClient(
    $config['api_base_url'],
    $config['api_key'],
    $config['currency'],
    $config['locale'],
    $config['cache_dir'],
    $config['cache_ttl']
);

// Output caching: serve cached HTML, skipping all API calls. Crawlers and AI bots
// hit the 28K-page long tail mostly cold, so a generous TTL is what keeps TTFB (and
// Lighthouse / Core Web Vitals / crawl budget) healthy. The cache KEY includes the
// resolved currency — prices vary by market and the key is otherwise path-only, so
// without this a longer TTL would serve one visitor's currency to everyone.
$ocPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$ocQuery = (string) ($_SERVER['QUERY_STRING'] ?? '');
$ocIsDiscoveryFile = $ocPath === '/robots.txt'
    || $ocPath === '/llms.txt'
    || $ocPath === '/llms-full.txt'
    || $ocPath === '/ai-index.json'
    || $ocPath === '/sitemap.xml'
    || $ocPath === '/sitemap-index.xml'
    || preg_match('#^/sitemap-(static|events|artists|artist-cities|venues|cities|monthly|venue-categories|artist-tours)\.xml$#', $ocPath) === 1;
$ocSkip = isset($_GET['nocache']) || ($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET' || $ocIsDiscoveryFile;
$ocDir = $config['cache_dir'] . '/html';
$ocFile = $ocDir . '/' . md5($config['currency'] . '|' . $ocPath . '?' . $ocQuery) . '.html';
$ocTtl = (int) (getenv('HTML_CACHE_TTL') ?: 21600); // 6h default; tune via env

record_ai_visit($config);

if (!$ocSkip && is_file($ocFile) && filesize($ocFile) > 200 && (time() - filemtime($ocFile)) < $ocTtl) {
    header('X-Cache: HIT');
    header('Content-Type: text/html; charset=utf-8');
    header('Link: <' . absolute_url($config, '/llms.txt') . '>; rel="alternate"; type="text/plain"', false);
    header('Link: <' . absolute_url($config, '/ai-index.json') . '>; rel="alternate"; type="application/json"', false);
    readfile($ocFile);
    exit;
}
header('X-Cache: MISS');

ob_start();
try {
    dispatch($client, $config, $dubaiContent, $destinationsContent);
} catch (Throwable $exception) {
    error_log('[app] ' . $exception->getMessage());
    render_error_page($config, 500, 'Something went wrong', 'We could not load the ticket data right now. Please try again in a moment.');
}
$ocHtml = ob_get_flush();

if (!$ocSkip && $ocHtml !== false && http_response_code() === 200 && strlen($ocHtml) > 200) {
    if (!is_dir($ocDir)) { @mkdir($ocDir, 0775, true); }
    // Atomic publish: write to a per-process temp file then rename into place.
    // rename() on the same filesystem is atomic, so concurrent readfile() readers
    // never see a half-written (truncated) cache file.
    $tmp = $ocFile . '.tmp' . getmypid();
    if (@file_put_contents($tmp, $ocHtml) === strlen($ocHtml)) { @rename($tmp, $ocFile); } else { @unlink($tmp); }
}
