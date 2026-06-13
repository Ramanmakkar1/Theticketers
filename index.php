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

// Output caching: serve cached HTML for 10 minutes, skipping all API calls.
$ocPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$ocQuery = (string) ($_SERVER['QUERY_STRING'] ?? '');
$ocSkip = isset($_GET['nocache']) || $_SERVER['REQUEST_METHOD'] !== 'GET';
$ocDir = $config['cache_dir'] . '/html';
$ocFile = $ocDir . '/' . md5($ocPath . '?' . $ocQuery) . '.html';
$ocTtl = 600;

if (!$ocSkip && is_file($ocFile) && (time() - filemtime($ocFile)) < $ocTtl) {
    readfile($ocFile);
    exit;
}

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
    @file_put_contents($ocFile, $ocHtml);
}

