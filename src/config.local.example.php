<?php
declare(strict_types=1);

/**
 * config.local.example.php — TEMPLATE for src/config.local.php
 *
 * src/config.local.php holds secrets that must NEVER be committed (it's gitignored).
 * Because it's gitignored, `git pull` does NOT create it on the server — so after
 * every fresh deploy you must create it manually, or the Ticketmaster data layer is
 * disabled (tm_client() returns null) and ALL Ticketmaster-sourced content vanishes:
 * team pages, venue pages, the league hubs (/nba …), US events, and the deep city
 * event pulls all go missing or 404.
 *
 * TO FIX "missing Ticketmaster content on the live site":
 *   1. Copy this file to src/config.local.php on the server.
 *   2. Paste your real Ticketmaster Discovery API consumer key.
 *   3. (No restart needed — PHP picks it up on the next request.)
 *
 * Alternatively, set the TICKETMASTER_API_KEY environment variable in your hosting
 * control panel (cPanel → "Environment Variables"). Do NOT put the key in .htaccess —
 * that file IS committed to git, so the key would leak.
 *
 * config.php merges whatever this returns over the defaults (array_replace).
 */

return [
    // Recommended: a LIST of Ticketmaster Discovery consumer keys. They are rotated
    // per request (each key has its own 5,000/day + ~5 req/s quota, so N keys ≈ N×
    // the headroom) with automatic failover to the next key on a 429. Use the
    // Consumer KEY only — the Consumer Secret is for TM's Commerce/OAuth API, which
    // this site never calls, so it is not needed here.
    'tm_api_keys' => [
        'PASTE_TICKETMASTER_CONSUMER_KEY_1',
        'PASTE_TICKETMASTER_CONSUMER_KEY_2',
        'PASTE_TICKETMASTER_CONSUMER_KEY_3',
    ],

    // Or, if you only have one key, use this instead of the list above:
    // 'tm_api_key' => 'YOUR_SINGLE_TICKETMASTER_CONSUMER_KEY',

    // Optional overrides (uncomment only if you need them):
    // 'api_key'   => 'pub-...your-HelloTickets-public-key...',
    // 'site_url'  => 'https://your-live-domain.com',
];
