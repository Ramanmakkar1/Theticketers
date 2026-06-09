<?php
declare(strict_types=1);

$config = require __DIR__ . '/src/config.php';

require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/HelloTicketsClient.php';
require __DIR__ . '/src/pages.php';

$client = new HelloTicketsClient(
    $config['api_base_url'],
    $config['api_key'],
    $config['currency'],
    $config['locale'],
    $config['cache_dir'],
    $config['cache_ttl']
);

try {
    dispatch($client, $config);
} catch (Throwable $exception) {
    error_log('[app] ' . $exception->getMessage());
    render_error_page($config, 500, 'Something went wrong', 'We could not load the ticket data right now. Please try again in a moment.');
}

