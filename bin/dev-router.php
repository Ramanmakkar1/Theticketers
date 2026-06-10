<?php
// Dev-only router for `php -S` previews: serve real files (CSS/JS) directly,
// route everything else through index.php. Not used in production.
$path = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($path !== '/' && is_file(__DIR__ . '/..' . $path)) {
    return false;
}
require __DIR__ . '/../index.php';
