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

try {
    dispatch($client, $config, $dubaiContent, $destinationsContent);
} catch (Throwable $exception) {
    error_log('[app] ' . $exception->getMessage());
    render_error_page($config, 500, 'Something went wrong', 'We could not load the ticket data right now. Please try again in a moment.');
}

