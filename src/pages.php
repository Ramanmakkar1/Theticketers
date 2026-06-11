<?php
declare(strict_types=1);

function dispatch(HelloTicketsClient $client, array $config, array $dubaiContent = [], array $destinationsContent = []): void
{
    $rawPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($rawPath !== '/' && substr($rawPath, -1) === '/') {
        $qs = (string) ($_SERVER['QUERY_STRING'] ?? '');
        header('Location: ' . rtrim($rawPath, '/') . ($qs !== '' ? '?' . $qs : ''), true, 301);
        return;
    }

    $path = current_path();

    // All our slugs are lowercase; fold case variants into one URL before resolving
    // (e.g. /artist/MAROON-5 → /artist/maroon-5). Skip /venue — legacy Ticketmaster
    // ids in old venue URLs are case-sensitive.
    $lowerPath = strtolower($path);
    if ($lowerPath !== $path && strpos($path, '/venue/') !== 0 && strpos($path, '/go') !== 0) {
        redirect_permanent($lowerPath);
        return;
    }

    if ($path === '/') {
        render_home_page($client, $config, $destinationsContent);
        return;
    }

    if ($path === '/dubai') {
        render_dubai_hub($client, $config, $dubaiContent);
        return;
    }

    if ($path === '/abu-dhabi') {
        render_abu_dhabi_hub($client, $config, $dubaiContent);
        return;
    }

    if (preg_match('#^/dubai/([a-z0-9-]+)/([a-z0-9-]+)$#', $path, $match)) {
        render_dubai_attraction($client, $config, $dubaiContent, $match[2]);
        return;
    }

    if (preg_match('#^/dubai/([a-z0-9-]+)$#', $path, $match)) {
        render_dubai_category($client, $config, $dubaiContent, $match[1]);
        return;
    }

    if (preg_match('#^/events/this-weekend-in-([^/]+)$#', $path, $match)) {
        $weekendCityId = resolve_city_id_by_slug($config, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($weekendCityId === null) {
            render_error_page($config, 404, 'City not found', 'We do not cover weekend events for this city yet.');
            return;
        }
        render_weekend_page($client, $config, $weekendCityId);
        return;
    }

    if ($path === '/events') {
        render_events_page($client, $config, active_city_id($config));
        return;
    }

    if ($path === '/attractions') {
        render_activities_page($client, $config, active_city_id($config));
        return;
    }

    if ($path === '/artists') {
        render_artists_page($client, $config);
        return;
    }

    if (preg_match('#^/artist/([^/]+)$#', $path, $match)) {
        // Clean-name resolution first so artists like "maroon-5" never get mistaken
        // for a legacy "{slug}-{id}" URL; numeric tails are only tried after it fails.
        $performerId = resolve_artist_id($client, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($performerId === null) {
            // HelloTickets doesn't know this artist — Ticketmaster often does (NHL
            // teams, US tours). TM-only artists get the same page, fed by TM data.
            $tmOnly = tm_artist_by_slug($config, $match[1]);
            if ($tmOnly !== null) {
                render_artist_detail_page($client, $config, 0, $tmOnly);
                return;
            }
            render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
            return;
        }
        render_artist_detail_page($client, $config, $performerId);
        return;
    }

    if ($path === '/search') {
        render_search_page($client, $config);
        return;
    }

    if ($path === '/about') {
        render_static_page($config, 'About ' . $config['site_name'], $config['site_name'] . ' is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations worldwide.', '/about', function () use ($config): void {
            ?>
            <p><?= e($config['site_name']) ?> is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations across the United States, Canada, the United Kingdom, Italy, Spain and France.</p>
            <p>We list concerts, theatre, sports, tours and attractions with live prices and availability supplied by official ticketing partners &mdash; HelloTickets for attractions, tours and international events, and Ticketmaster for North American sports, venues and arena tours. When you choose a ticket, you complete your booking securely on the partner's own site: they handle payment, ticket delivery and customer support, and the price you pay is the partner's price.</p>
            <h2>What you'll find here</h2>
            <ul>
                <li><strong>Destination guides</strong> for <a href="/dubai">Dubai</a>, <a href="/abu-dhabi">Abu Dhabi</a> and flagship cities across six countries, each hand-written with practical tips, highlights and FAQs.</li>
                <li><strong>Live schedules</strong> for <a href="/artists">artists on tour</a>, <a href="/venues">major venues</a> like Madison Square Garden and Wembley, and <a href="/teams">NBA, NFL, MLB and NHL teams</a> &mdash; refreshed from partner data every time a page loads.</li>
                <li><strong>Honest answers</strong>: every count, date and starting price on the site comes from live partner inventory, never from a static copy that can go stale.</li>
            </ul>
            <p>Nothing on this site is pay-to-rank: listings are ordered by the partner's live data, and we earn the same way regardless of which ticket you pick &mdash; the details are on our <a href="/how-we-make-money">How We Make Money</a> page.</p>
            <p><?= e($config['site_name']) ?> is operated by Town Media Labs. Questions, corrections or partnership ideas? See our <a href="/contact">Contact</a> page &mdash; we read everything.</p>
            <?php
        });
        return;
    }

    if ($path === '/contact') {
        render_static_page($config, 'Contact Us', 'How to reach the ' . $config['site_name'] . ' team for partnerships, listings, feedback and corrections.', '/contact', function () use ($config): void {
            ?>
            <p>The fastest way to reach us is email: <a href="mailto:townmedialabs@gmail.com"><strong>townmedialabs@gmail.com</strong></a>. We typically reply within one to two business days.</p>
            <ul>
                <li><strong>Booking, payment or refund questions:</strong> these are handled by the ticketing partner that processed your order &mdash; use the support links in your booking confirmation email. We don't have access to partner booking systems, so the partner's support team will always be faster.</li>
                <li><strong>Partnerships and listings:</strong> if you run an event, venue, tour or experience and want it listed, email us with the subject "Partner with <?= e($config['site_name']) ?>" and a link to what you do.</li>
                <li><strong>Site feedback or corrections:</strong> spotted a wrong date, a broken page or an outdated price? Email us the page link and what's wrong &mdash; corrections go straight to the top of the queue.</li>
                <li><strong>Press and media:</strong> email with the subject "Press" for facts, data or comments about the site.</li>
            </ul>
            <?php
        });
        return;
    }

    if ($path === '/how-we-make-money') {
        render_static_page($config, 'How We Make Money', $config['site_name'] . ' is free to use. Here is how affiliate commissions fund the site without changing the price you pay.', '/how-we-make-money', function () use ($config): void {
            ?>
            <p><?= e($config['site_name']) ?> is free to use. When you buy a ticket through a link on our site, the ticketing partner that completes your booking &mdash; HelloTickets or Ticketmaster &mdash; may pay us a commission. This never increases the price you pay: prices and availability come directly from the partner, and you'd pay exactly the same buying from them directly.</p>
            <p>We do not process payments, hold ticket inventory, or charge any fees. Commissions are how we fund the site.</p>
            <h2>What this means in practice</h2>
            <ul>
                <li>Every "Find Tickets" and "Check Availability" button leads to a partner checkout page. Those links are marked as sponsored for search engines, which is the standard for affiliate links.</li>
                <li>We earn the same commission rate regardless of which event or attraction you choose, so we have no incentive to push one listing over another &mdash; ordering comes from the partner's live data.</li>
                <li>If a page is wrong or a price looks off, <a href="/contact">tell us</a> &mdash; accuracy is the only product we have.</li>
            </ul>
            <?php
        });
        return;
    }

    if ($path === '/privacy') {
        render_static_page($config, 'Privacy Policy', 'What data ' . $config['site_name'] . ' collects (a city-preference cookie, anonymised click logs), how optional location detection works, and your rights.', '/privacy', function () use ($config): void {
            ?>
            <p>Last updated: <?= e(date('F j, Y')) ?>. <?= e($config['site_name']) ?> ("we") is a ticket discovery site operated by Town Media Labs. We collect as little personal data as a working website allows. This page lists everything we do collect, why, and your choices.</p>
            <h2>Cookies we set</h2>
            <ul>
                <li><strong>tb_city</strong> &mdash; remembers which city you chose so pages open with your city's tickets. Stored for 1 year. Set when you pick a city (or use "Detect my location"). It contains only a city number &mdash; no personal data. Delete it any time in your browser settings.</li>
            </ul>
            <p>We set no advertising or analytics cookies.</p>
            <h2>Optional location detection</h2>
            <p>If you press <strong>"Detect my location"</strong> in the city chooser, your browser asks ipapi.co (a third-party geolocation service) which city your internet connection is in, so we can preselect it. This sends your IP address to ipapi.co (<a href="https://ipapi.co/privacy/" rel="noopener">their privacy policy</a>). It happens only when you press the button &mdash; never automatically.</p>
            <h2>Click logs</h2>
            <p>When you click out to a ticket partner, we record the ticket clicked, the time, your browser type and an <strong>anonymised</strong> IP address (last digits removed, so it no longer identifies you). We use this to count clicks and detect abuse. Logs are routinely deleted after 90 days.</p>
            <h2>Buying tickets</h2>
            <p>Purchases happen on our partners' sites (HelloTickets, Ticketmaster) via Impact affiliate links, which set their own tracking for commission attribution. Their privacy policies govern checkout: we never see your name, payment details or order contents.</p>
            <h2>Your rights</h2>
            <p>Under GDPR/UK GDPR you can request access to, correction of, or deletion of any data we hold. Since we store no accounts and anonymise click logs, there is usually nothing identifying to return &mdash; but email <a href="mailto:townmedialabs@gmail.com">townmedialabs@gmail.com</a> and we will check and respond within 30 days. EU/UK visitors may also complain to their local data-protection authority.</p>
            <?php
        });
        return;
    }

    if ($path === '/terms') {
        render_static_page($config, 'Terms of Service', 'The terms for using ' . $config['site_name'] . ': we list tickets and link to official partners; purchases are completed on the partner\'s site under their terms.', '/terms', function () use ($config): void {
            ?>
            <p>Last updated: <?= e(date('F j, Y')) ?>. By using <?= e($config['site_name']) ?> you agree to these terms.</p>
            <h2>What we are (and aren't)</h2>
            <p><?= e($config['site_name']) ?> is a free ticket discovery and comparison site. We do not sell tickets, process payments or hold inventory. Every purchase is completed on a partner's website (HelloTickets or Ticketmaster) under <em>their</em> terms of sale &mdash; including pricing, delivery, cancellations and refunds. For booking issues, contact the partner using the details in your confirmation email.</p>
            <h2>Accuracy</h2>
            <p>Prices, dates and availability are supplied live by our partners and can change between your viewing a page and completing checkout. The partner's checkout price is always the binding one. We work to keep listings accurate but provide the site "as is", without warranties, and are not liable for losses arising from listing errors or partner availability changes &mdash; your statutory consumer rights are unaffected.</p>
            <h2>Affiliate disclosure</h2>
            <p>We may earn a commission when you buy through our links, at no extra cost to you. Details: <a href="/how-we-make-money">How We Make Money</a>.</p>
            <h2>Content and images</h2>
            <p>Event names, artist names, venue names and images belong to their respective owners and are used to identify the events listed. If you own content shown here and want it corrected or removed, email <a href="mailto:townmedialabs@gmail.com">townmedialabs@gmail.com</a> &mdash; takedown requests are honoured within 24 hours.</p>
            <h2>Acceptable use</h2>
            <p>Don't scrape the site at abusive rates, attempt to break it, or misrepresent affiliation with us. We may block traffic that does.</p>
            <h2>Contact</h2>
            <p>Town Media Labs &mdash; <a href="mailto:townmedialabs@gmail.com">townmedialabs@gmail.com</a>. We may update these terms; the date above reflects the latest revision.</p>
            <?php
        });
        return;
    }

    if ($path === '/go') {
        handle_outbound_redirect($config);
        return;
    }

    if ($path === '/robots.txt') {
        render_robots($config);
        return;
    }

    if ($path === '/llms.txt') {
        render_llms_txt($client, $config, $destinationsContent);
        return;
    }

    if ($path === '/sitemap.xml') {
        render_sitemap($client, $config, $destinationsContent);
        return;
    }

    if (preg_match('#^/city/([^/]+)$#', $path, $match)) {
        $cityId = resolve_city_id_by_slug($config, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($cityId === null) {
            render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
            return;
        }
        render_city_page($client, $config, $cityId, $destinationsContent);
        return;
    }

    if (preg_match('#^/category/([^/]+)$#', $path, $match)) {
        $category = resolve_category_by_slug($client, $match[1]);
        $categoryId = $category !== null ? (int) $category['id'] : legacy_id_from_slug($match[1]);
        if ($categoryId === null) {
            render_error_page($config, 404, 'Category not found', 'This ticket category is not available.');
            return;
        }
        render_category_page($client, $config, $categoryId);
        return;
    }

    if (preg_match('#^/event/([^/]+)$#', $path, $match)) {
        $performanceId = resolve_event_id($client, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($performanceId === null) {
            render_error_page($config, 404, 'Event not found', 'This event is not available anymore.');
            return;
        }
        render_event_detail_page($client, $config, $performanceId);
        return;
    }

    if (preg_match('#^/activity/([^/]+)$#', $path, $match)) {
        $activityId = resolve_activity_id($client, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($activityId === null) {
            render_error_page($config, 404, 'Activity not found', 'This activity is not available anymore.');
            return;
        }
        render_activity_detail_page($client, $config, $activityId);
        return;
    }

    if (preg_match('#^/venue/([^/]+)$#', $path, $match)) {
        $tmVenueId = venue_slug_lookup($match[1]);
        if ($tmVenueId === null) {
            $seedVenue = resolve_seed_venue($config, $match[1]);
            $tmVenueId = $seedVenue !== null ? (string) $seedVenue['tm_id'] : tm_legacy_id_from_slug($match[1]);
        }
        if ($tmVenueId === null || $tmVenueId === '') {
            render_error_page($config, 404, 'Venue not found', 'This venue page is not available.');
            return;
        }
        render_venue_page($config, $tmVenueId);
        return;
    }

    if ($path === '/venues') {
        render_venues_index($config);
        return;
    }

    // League hubs — /nba, /nfl, /mlb, /nhl, /mls. Guarded by league_from_slug() so unknown
    // slugs fall through to /{country} → 404. Matched BEFORE the country catch-all below.
    if (preg_match('#^/([a-z]+)$#', $path, $match) && league_from_slug($match[1]) !== null) {
        render_league_page($config, $match[1]);
        return;
    }

    if (preg_match('#^/team/([^/]+)$#', $path, $match)) {
        $team = resolve_seed_team($config, $match[1]);
        if ($team === null) {
            render_error_page($config, 404, 'Team not found', 'This team page is not available.');
            return;
        }
        render_team_page($config, $team);
        return;
    }

    if ($path === '/teams') {
        render_teams_index($config);
        return;
    }

    // Destination SEO hubs — /{country}/{city} and /{country}, guarded by the
    // content pack so they only match known markets (never /events, /search, …).
    if (preg_match('#^/([a-z0-9-]+)/([a-z0-9-]+)$#', $path, $match)
        && destination_city_in_country($destinationsContent, $match[1], $match[2])) {
        render_city_hub($client, $config, $destinationsContent, $match[1], $match[2]);
        return;
    }

    if (preg_match('#^/([a-z0-9-]+)$#', $path, $match)
        && destination_country_exists($destinationsContent, $match[1])) {
        render_country_hub($client, $config, $destinationsContent, $match[1]);
        return;
    }

    render_error_page($config, 404, 'Page not found', 'The ticket page you are looking for may have moved.');
}

function render_layout(array $config, array $meta, callable $content, ?array $schema = null): void
{
    $title = $meta['title'] ?? $config['site_name'];
    $description = $meta['description'] ?? $config['site_tagline'];
    $canonical = $meta['canonical'] ?? absolute_url($config, current_path());
    $bodyClass = $meta['body_class'] ?? '';
    $q = search_query();

    header('Content-Type: text/html; charset=utf-8');
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <?php if (!empty($meta['robots'])): ?>
    <meta name="robots" content="<?= e($meta['robots']) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= e($canonical) ?>">
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="apple-touch-icon" href="/assets/favicon.svg">
    <meta name="theme-color" content="#e50914">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e(absolute_image_url($config, $meta['image'] ?? $config['fallback_images']['hero'])) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/styles.css">
    <?php if ($schema !== null): ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
    <header class="site-header">
        <a class="brand" href="/" aria-label="<?= e($config['site_name']) ?> home">
            <img class="brand-mark" src="/assets/logo.svg" alt="" width="36" height="36">
            <span class="brand-text"><span class="brand-the">The</span> <em>Ticketers</em></span>
        </a>
        <div class="header-search">
            <form action="/search" method="get">
                <input type="search" name="q" value="<?= e($q) ?>" aria-label="Search events and attractions" placeholder="Search for Events, Attractions, Concerts, Theatre and Tours">
                <button type="submit" aria-label="Search">Search</button>
            </form>
        </div>
        <div class="header-actions">
            <?php $activeCity = city_for_id(active_city_id($config), $config); ?>
            <div class="city-picker" data-city-picker>
                <button class="header-city" type="button" data-city-toggle aria-haspopup="true"><?= e($activeCity['name']) ?></button>
                <div class="city-menu" data-city-menu>
                    <button class="city-detect" type="button" data-city-detect>Detect my location</button>
                    <?php foreach ($config['market_cities'] as $menuCity): ?>
                        <?php if (empty($menuCity['featured'])) { continue; } ?>
                        <a href="<?= e(city_path($menuCity)) ?>" data-city-id="<?= e($menuCity['id']) ?>"><?= e($menuCity['name']) ?><span><?= e($menuCity['country_code']) ?></span></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="nav-toggle" type="button" data-nav-toggle aria-label="Open search">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <div class="site-subnav">
        <div class="container">
            <nav class="site-nav" data-nav>
                <a href="/events">Events</a>
                <a href="/attractions">Attractions</a>
                <a href="/artists">Artists</a>
                <a href="/dubai">Dubai</a>
                <a href="/abu-dhabi">Abu Dhabi</a>
                <?php $navLabels = ['usa' => 'USA', 'canada' => 'Canada', 'uk' => 'UK', 'italy' => 'Italy', 'spain' => 'Spain', 'france' => 'France', 'netherlands' => 'Netherlands', 'germany' => 'Germany', 'portugal' => 'Portugal', 'australia' => 'Australia'];
                foreach (($config['markets'] ?? []) as $mSlug => $market): ?>
                    <a href="/<?= e($mSlug) ?>"><?= e($navLabels[$mSlug] ?? $market['name']) ?></a>
                <?php endforeach; ?>
                <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                <a href="<?= e(category_path(['id' => 1, 'name' => 'Sports'])) ?>">Sports</a>
            </nav>
            <div class="subnav-side">
                <?php foreach (array_slice($config['market_cities'], 0, 3) as $navCity): ?>
                    <a href="<?= e(city_path($navCity)) ?>"><?= e($navCity['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <main>
        <?php $content(); ?>
    </main>
    <footer class="site-footer">
        <div class="footer-partner">
            <div class="container">
                <p>Got an event, activity or experience? <strong>Partner with us &amp; get listed on <?= e($config['site_name']) ?></strong></p>
                <a class="footer-partner-btn" href="/contact">Contact Us</a>
            </div>
        </div>
        <div class="container">
            <div class="footer-main">
                <div class="footer-brand">
                    <a class="footer-logo" href="/">
                        <img class="footer-logo-mark" src="/assets/logo.svg" alt="" width="32" height="32">
                        <span class="brand-the">The</span> <em>Ticketers</em>
                    </a>
                    <p>Your guide to events, attractions and experiences in Dubai and top destinations worldwide — with live prices and secure partner checkout.</p>
                    <ul class="footer-trust">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path><line x1="13" y1="5" x2="13" y2="7"></line><line x1="13" y1="11" x2="13" y2="13"></line><line x1="13" y1="17" x2="13" y2="19"></line></svg>
                            Live prices &amp; availability
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline></svg>
                            Secure partner checkout
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
                            24/7 partner support
                        </li>
                    </ul>
                </div>
                <nav class="footer-col" aria-label="Destinations">
                    <h4>Destinations</h4>
                    <a href="/dubai">Dubai</a>
                    <a href="/abu-dhabi">Abu Dhabi</a>
                    <?php foreach (($config['markets'] ?? []) as $mSlug => $market): ?>
                        <a href="/<?= e($mSlug) ?>"><?= e($market['name']) ?></a>
                    <?php endforeach; ?>
                </nav>
                <nav class="footer-col" aria-label="Categories">
                    <h4>Categories</h4>
                    <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                    <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                    <a href="<?= e(category_path(['id' => 1, 'name' => 'Sports'])) ?>">Sports</a>
                    <a href="/attractions">Attractions &amp; Tours</a>
                    <a href="/events">All Events</a>
                </nav>
                <nav class="footer-col" aria-label="Company">
                    <h4>Company</h4>
                    <a href="/about">About Us</a>
                    <a href="/contact">Contact</a>
                    <a href="/how-we-make-money">How We Make Money</a>
                    <a href="/privacy">Privacy Policy</a>
                    <a href="/terms">Terms of Service</a>
                    <a href="/search">Search Tickets</a>
                    <a href="/sitemap.xml">Sitemap</a>
                </nav>
            </div>
            <div class="footer-bar">
                <p>&copy; <?= e(date('Y')) ?> <?= e($config['site_name']) ?>. All events, images and trademarks belong to their respective owners.</p>
                <p class="footer-bar__note">Prices and availability are live from our ticket partner; checkout completes securely on their site. We may earn a commission on bookings at no extra cost to you.</p>
            </div>
        </div>
    </footer>
    <div class="city-modal" data-city-modal data-default-city="<?= e($config['default_city_id']) ?>" hidden>
        <div class="city-modal-box">
            <h3>Where do you want tickets?</h3>
            <p>Pick your city to see local events, attractions and live prices.</p>
            <button class="city-detect-big" type="button" data-city-detect>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Detect my location
            </button>
            <div class="city-modal-grid">
                <?php foreach ($config['market_cities'] as $modalCity): ?>
                    <?php if (empty($modalCity['featured'])) { continue; } ?>
                    <a href="<?= e(city_path($modalCity)) ?>" data-city-id="<?= e($modalCity['id']) ?>"><strong><?= e($modalCity['name']) ?></strong><span><?= e($modalCity['country']) ?></span></a>
                <?php endforeach; ?>
            </div>
            <button class="city-modal-close" type="button" data-city-close>Maybe later</button>
        </div>
    </div>
    <script src="/assets/app.js" defer></script>
</body>
</html>
    <?php
}

function render_static_page(array $config, string $title, string $desc, string $path, callable $body): void
{
    render_layout($config, [
        'title' => $title . ' | ' . $config['site_name'],
        'description' => $desc,
        'canonical' => absolute_url($config, $path),
    ], function () use ($title, $body): void {
        ?>
        <section class="section-band">
            <div class="container prose">
                <h1><?= e($title) ?></h1>
                <?php $body(); ?>
            </div>
        </section>
        <?php
    });
}

function render_home_page(HelloTicketsClient $client, array $config, array $destinationsContent = []): void
{
    $cityId = active_city_id($config);
    $homeCity = city_for_id($cityId, $config);

    // Date-prioritised local events (today -> this week -> upcoming), with
    // nearby-city fallback when the detected city has no inventory of its own.
    $eventRails = home_event_rails($client, $config, $cityId, $homeCity['name']);
    $events = $eventRails[0]['items'] ?? [];

    $activitiesData = api_result(static fn() => $client->activities([
        'limit' => 12,
        'page' => 1,
        'city_id' => $cityId,
    ]), ['activities' => []]);

    // Collect every event id already shown locally so worldwide never repeats one.
    $seenIds = [];
    foreach ($eventRails as $rail) {
        foreach ($rail['items'] as $performance) {
            $seenIds[] = (int) ($performance['id'] ?? 0);
        }
    }

    $globalEventsData = count($seenIds) < 6
        ? api_result(static fn() => $client->performances(array_merge([
            'limit' => 12,
            'page' => 1,
            'is_sellable' => 'true',
        ], date_params(null))), ['performances' => []])
        : ['performances' => []];

    $performers = api_result(static fn() => $client->performers([
        'limit' => 12,
        'page' => 1,
    ]), ['performers' => []])['performers'] ?? [];

    $activities = $activitiesData['activities'] ?? [];
    $globalEvents = array_values(array_filter(
        $globalEventsData['performances'] ?? [],
        static fn($performance): bool => !in_array((int) ($performance['id'] ?? 0), $seenIds, true)
    ));

    render_layout($config, [
        'title' => $homeCity['name'] . ' Events, Attractions & Tickets | ' . $config['site_name'],
        'description' => 'Find ' . $homeCity['name'] . ' attraction tickets, concerts, theatre, sports and experiences with live prices from HelloTickets.',
        'canonical' => absolute_url($config, '/'),
        'body_class' => 'home-page',
    ], function () use ($config, $cityId, $activities, $events, $eventRails, $globalEvents, $performers, $homeCity, $destinationsContent): void {
        ?>
        <h1 class="visually-hidden"><?= e($homeCity['name']) ?> Events, Attractions &amp; Tickets</h1>
        <?php
        if ($cityId === (int) $config['default_city_id']) {
            // Dubai flagship: the curated attraction-led banners.
            $slides = [
                ['image' => $config['fallback_images']['hero'], 'tag' => 'Featured', 'title' => 'Dubai events, attractions and experiences', 'text' => 'Live prices and availability, with secure partner checkout.', 'href' => '/attractions', 'cta' => 'Book Now'],
                ['image' => $config['fallback_images']['burj'], 'tag' => 'Top Attraction', 'title' => 'Burj Khalifa: At the Top', 'text' => 'Skip the queue with instant e-tickets to the world\'s tallest tower.', 'href' => '/attractions?q=Burj%20Khalifa', 'cta' => 'Get Tickets'],
                ['image' => $config['fallback_images']['desert'], 'tag' => 'Experiences', 'title' => 'Desert safaris and dune adventures', 'text' => 'Sunset drives, camel rides and Bedouin dinners under the stars.', 'href' => '/attractions?q=Desert%20Safari', 'cta' => 'Explore'],
                ['image' => $config['fallback_images']['Concerts'], 'tag' => 'Live Events', 'title' => 'Concerts, theatre and sport in Dubai', 'text' => 'See what\'s playing this week across the city\'s biggest venues.', 'href' => '/events', 'cta' => 'See Events'],
            ];
        } else {
            // Detected city: banner the visitor's own upcoming events.
            $slides = [];
            foreach (array_slice($events, 0, 4) as $heroEvent) {
                $venue = $heroEvent['venue']['name'] ?? $homeCity['name'];
                $slides[] = [
                    'image' => image_from_item($heroEvent, 'event', $config),
                    'tag' => $heroEvent['category']['name'] ?? 'Live Event',
                    'title' => $heroEvent['name'] ?? ('Live in ' . $homeCity['name']),
                    'text' => trim(format_date_time($heroEvent['start_date'] ?? []) . ' · ' . $venue, ' ·'),
                    'href' => event_path($heroEvent),
                    'cta' => 'Get Tickets',
                ];
            }
            if ($slides === []) {
                $slides[] = ['image' => $config['fallback_images']['Concerts'], 'tag' => 'Live Events', 'title' => 'What\'s on in ' . $homeCity['name'], 'text' => 'Concerts, theatre, sport and experiences with live prices.', 'href' => '/events', 'cta' => 'See Events'];
            }
        }
        ?>
        <section class="hero">
            <div class="container">
                <div class="carousel" data-carousel>
                    <div class="carousel-track" data-carousel-track>
                        <?php foreach ($slides as $slide): ?>
                            <div class="carousel-slide" style="background-image: url('<?= e($slide['image']) ?>')">
                                <div class="carousel-caption">
                                    <span class="slide-tag"><?= e($slide['tag']) ?></span>
                                    <h2><?= e($slide['title']) ?></h2>
                                    <p><?= e($slide['text']) ?></p>
                                    <a class="slide-btn" href="<?= e($slide['href']) ?>"><?= e($slide['cta']) ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn prev" type="button" data-carousel-prev aria-label="Previous banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button class="carousel-btn next" type="button" data-carousel-next aria-label="Next banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                    <div class="carousel-dots" data-carousel-dots></div>
                </div>
            </div>
        </section>

        <section class="section-band compact">
            <div class="container">
                <div class="filter-row">
                    <a href="/events?date=today">Today</a>
                    <a href="/events?date=tomorrow">Tomorrow</a>
                    <a href="/events?date=weekend">This Weekend</a>
                    <a href="/events?date=month">This Month</a>
                </div>
            </div>
        </section>

        <?php if ($activities !== []): ?>
            <?php render_card_section('Recommended in ' . $homeCity['name'], '/attractions', $activities, 'activity', $config); ?>
        <?php endif; ?>

        <?php foreach ($eventRails as $rail): ?>
            <?php render_card_section($rail['label'], $rail['href'], $rail['items'], 'event', $config); ?>
        <?php endforeach; ?>

        <?php render_artists_rail($performers); ?>

        <?php render_live_events_band(); ?>

        <?php if (count($globalEvents) >= 4): ?>
            <?php render_card_section('Popular Events Worldwide', '/events', $globalEvents, 'event', $config); ?>
        <?php endif; ?>

        <?php render_promo_banner($config); ?>

        <?php $homeCountries = $destinationsContent['countries'] ?? []; ?>
        <?php if ($homeCountries !== []): ?>
            <section class="section-band">
                <div class="container">
                    <div class="section-heading">
                        <h2>Explore by Destination</h2>
                        <a href="/attractions">See All</a>
                    </div>
                    <div class="home-destinations__grid">
                        <?php foreach ($homeCountries as $hcSlug => $hc): ?>
                            <a class="home-destinations__card" href="/<?= e($hcSlug) ?>" style="background-image: linear-gradient(180deg, rgba(0,0,0,.1) 0%, rgba(0,0,0,.7) 100%), url('<?= e($hc['hero_image'] ?? $config['fallback_images']['hero']) ?>')"><?= e($hc['name'] ?? $hcSlug) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="section-band">
            <div class="container split-section">
                <div>
                    <p class="eyebrow">Browse by destination</p>
                    <h2>Popular Ticket Cities</h2>
                </div>
                <div class="city-grid">
                    <?php foreach (array_slice($config['market_cities'], 0, 8) as $city): ?>
                        <a href="<?= e(city_path($city)) ?>">
                            <strong><?= e($city['name']) ?></strong>
                            <span><?= e($city['country']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }, website_schema($config));
}

function render_events_page(HelloTicketsClient $client, array $config, int $cityId, ?array $category = null, ?array $seo = null): void
{
    $city = city_for_id($cityId, $config);
    $page = page_number();
    $date = (string) ($_GET['date'] ?? 'upcoming');
    $query = search_query();
    $params = array_merge([
        'limit' => 24,
        'page' => $page,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params($date));

    if ($query !== '') {
        // 'name' is the filter the API actually honors; 'performance' is silently
        // ignored and used to return the whole unfiltered catalog.
        $params['name'] = $query;
    }

    if ($category !== null) {
        $params['category_id'] = (int) $category['id'];
    }

    $data = api_result(static fn() => $client->performances($params), ['performances' => [], 'total_count' => 0]);
    $items = $data['performances'] ?? [];

    // HelloTickets-first, Ticketmaster-fill: when local HT inventory is thin, top up
    // with TM events for the SAME city before reaching for global events. HT stays
    // first and wins dedupe (higher commission, our own detail pages); TM covers the
    // local long tail HT misses.
    if (count($items) < 18 && $query === '' && $page === 1) {
        $tmExtra = [];
        if ($date !== 'upcoming') {
            [$tmFrom, $tmTo] = date_bounds($date);
            $tmExtra['localStartDateTime'] = $tmFrom->format('Y-m-d\T00:00:00') . ',' . $tmTo->format('Y-m-d\T23:59:59');
        }
        if ($category !== null) {
            // HT category ids 1/2/3 map onto TM's segment taxonomy.
            $segments = [1 => 'Sports', 2 => 'Music', 3 => 'Arts & Theatre'];
            $segment = $segments[(int) $category['id']] ?? null;
            if ($segment !== null) {
                $tmExtra['segmentName'] = $segment;
            } else {
                $tmExtra = null; // unmapped category: skip TM rather than blend wrong genres
            }
        }
        if ($tmExtra !== null) {
            $tmEvents = tm_events_for_city($config, (string) $city['name'], (string) ($city['country_code'] ?? ''), $tmExtra, 24);
            if ($tmEvents !== []) {
                $items = array_slice(merge_events_dedupe($items, $tmEvents), 0, 24);
                $data['total_count'] = max((int) ($data['total_count'] ?? 0), count($items));
            }
        }
    }

    // Most market cities have very little sellable event inventory. When a city is thin
    // (and the user isn't searching/paging), blend in global sellable events so the page
    // is useful rather than near-empty. Local events stay first.
    if (count($items) < 6 && $query === '' && $page === 1) {
        $globalParams = $params;
        unset($globalParams['city_id']);
        $global = api_result(static fn() => $client->performances($globalParams), ['performances' => []])['performances'] ?? [];
        $seen = [];
        foreach ($items as $existing) {
            $seen[(int) ($existing['id'] ?? 0)] = true;
        }
        foreach ($global as $candidate) {
            $cid = (int) ($candidate['id'] ?? 0);
            if ($cid > 0 && !isset($seen[$cid])) {
                $items[] = $candidate;
                $seen[$cid] = true;
            }
            if (count($items) >= 24) {
                break;
            }
        }
        $data['total_count'] = count($items);
    }

    $categoryLabel = $category ? $category['name'] . ' ' : '';
    $title = trim($categoryLabel . 'events in ' . $city['name']);

    render_listing_layout($config, [
        // Curated categories use their global keyword form (inventory is global);
        // un-curated category listings stay out of the index entirely.
        'title' => ($seo !== null ? $seo['meta_title'] : ucwords($title)) . ' | ' . $config['site_name'],
        'description' => $seo['meta_description'] ?? ('Browse live ' . strtolower($categoryLabel) . 'event tickets in ' . $city['name'] . ' with dates, venues and prices.'),
        'robots' => ($category !== null && $seo === null) ? 'noindex, follow' : null,
        // Date filters (?date=today/weekend/...) are thin variants of the same
        // listing — canonicalize them to the unfiltered page so they don't compete
        // with /events and the dedicated /events/this-weekend-in-{city} pages.
        'canonical' => absolute_url($config, current_path(), array_filter([
            'page' => $page > 1 ? $page : null,
        ])),
    ], $seo['h1'] ?? $title, $items, 'event', $config, $data, [
        'city_id' => $cityId,
        'date' => $date,
        'q' => $query,
        'category' => $category,
    ], $seo ?? []);
}

function render_activities_page(HelloTicketsClient $client, array $config, int $cityId, ?string $categoryQuery = null, ?string $categoryLabel = null, ?array $seo = null): void
{
    $city = city_for_id($cityId, $config);
    $page = page_number();
    $query = $categoryQuery !== null ? $categoryQuery : search_query();
    $data = api_result(static fn() => $client->activities(array_filter([
        'limit' => 24,
        'page' => $page,
        'city_id' => $cityId,
        'query' => $query,
    ], static fn($v) => $v !== '')), ['activities' => [], 'total_count' => 0]);

    $items = $data['activities'] ?? [];

    // Category pages are global keyword pages — when the visitor's city has little
    // matching inventory (e.g. "museum" in Dubai), top up with the best worldwide
    // matches so the page always carries real, on-topic inventory. City items first.
    if ($categoryQuery !== null && $categoryQuery !== '' && count($items) < 6 && $page === 1) {
        $global = api_result(static fn() => $client->activities([
            'limit' => 24,
            'page' => 1,
            'query' => $categoryQuery,
        ]), ['activities' => []])['activities'] ?? [];
        $seen = [];
        foreach ($items as $existing) {
            $seen[(int) ($existing['id'] ?? 0)] = true;
        }
        foreach ($global as $candidate) {
            $cid = (int) ($candidate['id'] ?? 0);
            if ($cid > 0 && !isset($seen[$cid])) {
                $items[] = $candidate;
                $seen[$cid] = true;
            }
            if (count($items) >= 24) {
                break;
            }
        }
        $data['total_count'] = max((int) ($data['total_count'] ?? 0), count($items));
    }

    if ($categoryLabel !== null) {
        $title = $categoryLabel . ' in ' . $city['name'];
    } else {
        $title = $query !== '' ? $query . ' tickets in ' . $city['name'] : 'Attractions and experiences in ' . $city['name'];
    }

    render_listing_layout($config, [
        'title' => ($seo !== null ? $seo['meta_title'] : ucwords($title)) . ' | ' . $config['site_name'],
        'description' => $seo['meta_description'] ?? ('Compare ' . $city['name'] . ' attractions, tours and experiences with current prices and partner checkout.'),
        'robots' => ($categoryLabel !== null && $seo === null) ? 'noindex, follow' : null,
        'canonical' => absolute_url($config, current_path(), array_filter(['page' => $page > 1 ? $page : null])),
    ], $seo['h1'] ?? $title, $items, 'activity', $config, $data, [
        'city_id' => $cityId,
        'q' => search_query(),
    ], $seo ?? []);
}

function render_listing_layout(array $config, array $meta, string $heading, array $items, string $type, array $configAgain, array $data, array $filters, array $extras = []): void
{
    // Curated category pages append FAQPage to the ItemList schema.
    $schema = item_list_schema($config, $items, $type);
    if (!empty($extras['faqs'])) {
        unset($schema['@context']);
        $faqSchema = dubai_faq_schema($extras['faqs']);
        unset($faqSchema['@context']);
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [$schema, $faqSchema],
        ];
    }

    render_layout($config, $meta, function () use ($heading, $items, $type, $configAgain, $data, $filters, $extras): void {
        $total = (int) ($data['total_count'] ?? count($items));
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= $type === 'event' ? 'Live inventory' : 'Experiences' ?></p>
                <h1><?= e($heading) ?></h1>
                <form class="listing-toolbar" action="<?= $type === 'event' ? '/events' : '/attractions' ?>" method="get">
                    <input type="search" name="q" value="<?= e((string) ($filters['q'] ?? '')) ?>" placeholder="<?= $type === 'event' ? 'Search performer or event' : 'Search attraction or tour' ?>">
                    <?php if ($type === 'event'): ?>
                        <select name="date" aria-label="Date">
                            <?php foreach (['upcoming' => 'Upcoming', 'month' => 'This month', 'today' => 'Today', 'tomorrow' => 'Tomorrow', 'weekend' => 'This weekend'] as $key => $label): ?>
                                <option value="<?= e($key) ?>" <?= ($filters['date'] ?? 'month') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                    <button type="submit">Search</button>
                </form>
                <div class="result-count"><?= e(number_format($total)) ?> results</div>
            </div>
        </section>

        <section class="section-band">
            <div class="container">
                <?php if ($items === []): ?>
                    <?php $emptyCity = city_for_id((int) ($filters['city_id'] ?? 0), $configAgain); ?>
                    <div class="empty-state">
                        <h2>No tickets found</h2>
                        <p>Try a broader search or browse all <?= e($emptyCity['name']) ?> listings.</p>
                        <a class="button-link" href="<?= $type === 'event' ? '/events' : '/attractions' ?>">See All</a>
                    </div>
                <?php else: ?>
                    <div class="card-grid">
                        <?php foreach ($items as $item): ?>
                            <?= $type === 'event' ? event_card($item, $configAgain) : activity_card($item, $configAgain) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php render_pagination($data); ?>
                <?php endif; ?>
            </div>
        </section>

        <?php // Hand-written category guide + internal links, listings-first per house style. ?>
        <?php if (!empty($extras['intro'])): ?>
            <section class="section-band muted">
                <div class="container">
                    <div class="prose listing-guide">
                        <h2>About <?= e($heading) ?></h2>
                        <?php foreach ((array) $extras['intro'] as $paragraph): ?>
                            <p><?= e($paragraph) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($extras['links'])): ?>
                        <div class="filter-row" style="margin-top: 18px;">
                            <?php foreach ($extras['links'] as $linkLabel => $linkHref): ?>
                                <a href="<?= e($linkHref) ?>"><?= e($linkLabel) ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
        <?php if (!empty($extras['faqs'])): ?>
            <?php dubai_render_faq($extras['faqs'], $heading . ' — FAQs'); ?>
        <?php endif; ?>
        <?php
    }, $schema);
}

function render_search_page(HelloTicketsClient $client, array $config): void
{
    $query = search_query();
    $type = (string) ($_GET['type'] ?? 'all');

    if ($type === 'events') {
        header('Location: ' . route_url('/events', ['q' => $query]), true, 302);
        return;
    }

    if ($type === 'attractions') {
        header('Location: ' . route_url('/attractions', ['q' => $query]), true, 302);
        return;
    }

    $cityId = active_city_id($config);
    $events = $query === '' ? [] : (api_result(static fn() => $client->performances(array_merge([
        'limit' => 8,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
        'name' => $query,
    ], date_params(null))), ['performances' => []])['performances'] ?? []);

    $activities = $query === '' ? [] : (api_result(static fn() => $client->activities([
        'limit' => 8,
        'page' => 1,
        'city_id' => $cityId,
        'query' => $query,
    ]), ['activities' => []])['activities'] ?? []);

    $searchCity = city_for_id(active_city_id($config), $config);
    $cityName = (string) $searchCity['name'];

    render_layout($config, [
        'title' => ($query !== '' ? 'Search tickets for ' . $query : 'Search Tickets in ' . $cityName) . ' | ' . $config['site_name'],
        'description' => 'Search ' . $cityName . ' events, attractions and experiences.',
        'canonical' => absolute_url($config, '/search'),
        'robots' => 'noindex, follow',
    ], function () use ($query, $events, $activities, $config, $cityName): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Search</p>
                <h1><?= $query !== '' ? 'Tickets for "' . e($query) . '"' : 'Search Tickets in ' . e($cityName) ?></h1>
                <form class="listing-toolbar" action="/search" method="get">
                    <input type="search" name="q" value="<?= e($query) ?>" aria-label="Search tickets" placeholder="Search <?= e($cityName) ?> tickets">
                    <select name="type" aria-label="Search type">
                        <option value="all">All tickets</option>
                        <option value="events">Events</option>
                        <option value="attractions">Attractions</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div>
        </section>
        <?php if ($events !== []): ?>
            <?php render_card_section('Events', route_url('/events', ['q' => $query]), $events, 'event', $config); ?>
        <?php endif; ?>
        <?php if ($activities !== []): ?>
            <?php render_card_section('Attractions', route_url('/attractions', ['q' => $query]), $activities, 'activity', $config); ?>
        <?php endif; ?>
        <?php if ($query === '' || ($events === [] && $activities === [])): ?>
            <section class="section-band">
                <div class="container">
                    <div class="empty-state">
                        <h2>No matches yet</h2>
                        <p>Browse <?= e($cityName) ?> attractions to see current inventory.</p>
                        <a class="button-link" href="/attractions">View attractions</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php
    });
}

function render_city_page(HelloTicketsClient $client, array $config, int $cityId, array $destinationsContent = []): void
{
    $city = city_for_id($cityId, $config);
    // Dubai/Abu Dhabi have bespoke editorial hubs outside the destinations pack;
    // canonicalize their thin /city listings there too, like every other hub city.
    $guidePath = destination_hub_path_for_city($destinationsContent, $cityId)
        ?? ($cityId === 132 ? '/dubai' : null)
        ?? ($cityId === 256 ? '/abu-dhabi' : null);

    // Market cities always get a page. Any other geo-detectable city qualifies too,
    // but only if its combined HelloTickets + Ticketmaster inventory is real —
    // thin ones still 404 so we never publish doorway pages.
    $isMarketCity = false;
    foreach ($config['market_cities'] as $marketCity) {
        if ((int) $marketCity['id'] === $cityId) {
            $isMarketCity = true;
            break;
        }
    }
    if (!$isMarketCity && !isset(geo_cities()[(string) $cityId])) {
        render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
        return;
    }
    if (current_path() !== city_path($city)) {
        $requestedSlug = (string) substr(current_path(), strlen('/city/'));
        if (legacy_id_from_slug($requestedSlug) === $cityId
            && !legacy_slug_corresponds($requestedSlug, slugify((string) $city['name']))) {
            render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
            return;
        }
        redirect_permanent(city_path($city));
        return;
    }
    $eventsData = api_result(static fn() => $client->performances(array_merge([
        'limit' => 12,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params(null))), ['performances' => []]);
    $events = $eventsData['performances'] ?? [];
    if (count($events) < 12) {
        $events = array_slice(merge_events_dedupe(
            $events,
            tm_events_for_city($config, (string) $city['name'], (string) ($city['country_code'] ?? ''), [], 24)
        ), 0, 24);
    }
    $activitiesData = api_result(static fn() => $client->activities([
        'limit' => 12,
        'page' => 1,
        'city_id' => $cityId,
    ]), ['activities' => []]);
    $activities = $activitiesData['activities'] ?? [];

    if (!$isMarketCity && count($events) + count($activities) < 5) {
        render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
        return;
    }
    setcookie('tb_city', (string) $cityId, [
        'expires' => time() + 31536000,
        'path' => '/',
        'samesite' => 'Lax',
    ]);

    render_layout($config, [
        'title' => $city['name'] . ' Tickets, Events & Attractions | ' . $config['site_name'],
        'description' => 'Browse current tickets for ' . $city['name'] . ', including attractions, tours, concerts, theatre and sports.',
        // When an editorial /{country}/{city} hub exists, point the canonical at it so
        // the thin listing page doesn't cannibalise the rich hub.
        'canonical' => absolute_url($config, $guidePath ?? city_path($city)),
    ], function () use ($city, $events, $activities, $config, $guidePath): void {
        ?>
        <section class="listing-hero city-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['country'] ?: 'Destination') ?></p>
                <h1><?= e($city['name']) ?> tickets, events and attractions</h1>
                <div class="filter-row inverse">
                    <a href="/events?date=today">Today</a>
                    <a href="<?= e(weekend_path($city)) ?>">This Weekend</a>
                    <a href="/attractions">Attractions</a>
                    <a href="/events">Events</a>
                </div>
                <?php if ($guidePath !== null): ?>
                    <p class="city-guide-link"><a href="<?= e($guidePath) ?>">Read the full <?= e($city['name']) ?> guide &rarr;</a></p>
                <?php endif; ?>
            </div>
        </section>
        <?php render_card_section('Events in ' . $city['name'], '/events', $events, 'event', $config); ?>
        <?php render_card_section('Attractions in ' . $city['name'], '/attractions', $activities, 'activity', $config); ?>
        <?php
    });
}

function render_category_page(HelloTicketsClient $client, array $config, int $categoryId): void
{
    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    $category = null;
    foreach ($categories as $candidate) {
        if ((int) $candidate['id'] === $categoryId) {
            $category = $candidate;
            break;
        }
    }

    if ($category === null) {
        render_error_page($config, 404, 'Category not found', 'This ticket category is not available.');
        return;
    }

    if (current_path() !== category_path($category)) {
        $requestedSlug = (string) substr(current_path(), strlen('/category/'));
        if (legacy_id_from_slug($requestedSlug) === $categoryId
            && !legacy_slug_corresponds($requestedSlug, slugify((string) $category['name']))) {
            render_error_page($config, 404, 'Category not found', 'This ticket category is not available.');
            return;
        }
        redirect_permanent(category_path($category));
        return;
    }

    // Curated categories carry hand-written intros + FAQs (src/category-content.php)
    // and are the ONLY /category/ pages allowed into the index. Raw API categories
    // ("vatican-city", "sintra-and-cascais", …) stay browsable but noindex, so they
    // can never become thin doorway pages.
    $seo = category_content()[slugify((string) $category['name'])] ?? null;

    if (in_array($categoryId, [1, 2, 3], true)) {
        render_events_page($client, $config, active_city_id($config), $category, $seo);
        return;
    }

    // The activities API has no category filter — search by a representative keyword
    // (config map), falling back to all city activities so the page is never empty.
    $keyword = $config['activity_category_queries'][$categoryId] ?? '';
    render_activities_page($client, $config, active_city_id($config), $keyword, (string) $category['name'], $seo);
}

/** Hand-written content for indexable category pages, keyed by category slug. */
function category_content(): array
{
    static $map = null;
    if ($map === null) {
        $file = __DIR__ . '/category-content.php';
        $map = is_file($file) ? require $file : [];
    }
    return $map;
}

function render_event_detail_page(HelloTicketsClient $client, array $config, int $performanceId): void
{
    $performance = api_result(static fn() => $client->performance($performanceId));
    if ($performance === [] || empty($performance['id'])) {
        render_error_page($config, 404, 'Event not found', 'This event is not available anymore.');
        return;
    }

    // Legacy "{slug}-{id}" URLs and stale slugs (date/title changes) 301 to the one
    // canonical clean URL. event_path() registers the new slug before we redirect,
    // so the target always resolves.
    if (current_path() !== event_path($performance)) {
        $requestedSlug = (string) substr(current_path(), strlen('/event/'));
        if (legacy_id_from_slug($requestedSlug) === (int) $performance['id']
            && slug_lookup('event', $requestedSlug) !== (int) $performance['id']
            && !legacy_slug_corresponds($requestedSlug, event_slug($performance))) {
            render_error_page($config, 404, 'Event not found', 'This event is not available anymore.');
            return;
        }
        redirect_permanent(event_path($performance));
        return;
    }

    $cityName = $performance['venue']['city'] ?? $config['default_city_name'];
    $categoryId = (int) ($performance['category']['id'] ?? 0);
    $related = api_result(static fn() => $client->performances(array_merge([
        'limit' => 8,
        'page' => 1,
        'is_sellable' => 'true',
        'category_id' => $categoryId ?: null,
    ], date_params(null))), ['performances' => []])['performances'] ?? [];

    $localDate = (string) ($performance['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Events', 'url' => absolute_url($config, '/events')],
        ['name' => (string) ($performance['name'] ?? 'Event'), 'url' => absolute_url($config, event_path($performance))],
    ];

    render_layout($config, [
        'title' => $performance['name'] . ' Tickets | ' . $config['site_name'],
        'description' => 'See dates, venue and ticket prices for ' . $performance['name'] . ' in ' . $cityName . '.',
        'canonical' => absolute_url($config, event_path($performance)),
        'image' => image_from_item($performance, 'event', $config),
        'robots' => $isPast ? 'noindex, follow' : null,
    ], function () use ($performance, $related, $config, $breadcrumbs): void {
        $image = image_from_item($performance, 'event', $config);
        $price = $performance['price_range']['min_price'] ?? 0;
        $currency = $performance['price_range']['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <div class="detail-header">
                    <p class="eyebrow"><?= e($performance['category']['name'] ?? 'Event') ?></p>
                    <h1><?= e($performance['name']) ?></h1>
                    <div class="detail-facts">
                        <span><?= e($performance['venue']['city'] ?? 'Dubai') ?></span>
                        <span><?= e($performance['venue']['name'] ?? 'Venue TBA') ?></span>
                    </div>
                </div>

                <div class="detail-gallery" style="background-image: url('<?= e($image) ?>')"></div>

                <div class="detail-grid">
                    <div class="detail-content">
                        <h2>Event details</h2>
                        <dl class="detail-list">
                            <div><dt>Date</dt><dd><?= e(format_date_time($performance['start_date'] ?? [])) ?></dd></div>
                            <div><dt>Venue</dt><dd><?= e($performance['venue']['name'] ?? 'Venue TBA') ?></dd></div>
                            <div><dt>Address</dt><dd><?= e(trim(($performance['venue']['address'] ?? '') . ', ' . ($performance['venue']['city'] ?? ''))) ?></dd></div>
                            <div><dt>Category</dt><dd><?= e($performance['category']['name'] ?? 'Event') ?></dd></div>
                        </dl>

                        <?php if (!empty($performance['performers'])): ?>
                            <h2>Performers</h2>
                            <div class="tag-grid compact-tags">
                                <?php foreach ($performance['performers'] ?? [] as $performer): ?>
                                    <span><?= e($performer['name'] ?? '') ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <aside class="checkout-panel">
                        <span class="price-label">Tickets From</span>
                        <strong><?= e(money($price, (string) $currency)) ?></strong>
                        <a class="button-link wide" href="<?= e(go_url($performance, 'event')) ?>" rel="sponsored nofollow">Find Tickets</a>
                        <p class="checkout-note">Secure checkout on our official ticket partner's site. We may earn a commission &mdash; at no extra cost to you.</p>
                    </aside>
                </div>
            </div>
        </section>
        <?php render_card_section('More Events', '/events', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $performance['id']), 'event', $config); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            event_schema($config, $performance),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ]);
}

function render_activity_detail_page(HelloTicketsClient $client, array $config, int $activityId): void
{
    // The hand-written /dubai attraction pages are the rich twin for some
    // activities — 301 the thin twin there so the two never compete in search.
    $richTwin = dubai_attraction_path_for_activity($activityId);
    if ($richTwin !== null) {
        redirect_permanent($richTwin);
        return;
    }

    $activity = api_result(static fn() => $client->activity($activityId));
    if ($activity === [] || empty($activity['id'])) {
        render_error_page($config, 404, 'Activity not found', 'This activity is not available anymore.');
        return;
    }

    if (current_path() !== activity_path($activity)) {
        $requestedSlug = (string) substr(current_path(), strlen('/activity/'));
        if (legacy_id_from_slug($requestedSlug) === (int) $activity['id']
            && slug_lookup('activity', $requestedSlug) !== (int) $activity['id']
            && !legacy_slug_corresponds($requestedSlug, activity_slug($activity))) {
            render_error_page($config, 404, 'Activity not found', 'This activity is not available anymore.');
            return;
        }
        redirect_permanent(activity_path($activity));
        return;
    }

    $dateWindow = [
        'date_from' => (new DateTimeImmutable('today'))->format('Y-m-d'),
        'date_to' => (new DateTimeImmutable('today'))->modify('+90 days')->format('Y-m-d'),
    ];
    $dates = api_result(static fn() => $client->activityDates($activityId, $dateWindow), ['dates' => []])['dates'] ?? [];
    $related = api_result(static fn() => $client->activities([
        'limit' => 8,
        'page' => 1,
        'city_id' => (int) ($activity['city']['id'] ?? $config['default_city_id']),
    ]), ['activities' => []])['activities'] ?? [];

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Attractions', 'url' => absolute_url($config, '/attractions')],
        ['name' => (string) ($activity['title'] ?? 'Experience'), 'url' => absolute_url($config, activity_path($activity))],
    ];

    render_layout($config, [
        'title' => $activity['title'] . ' | ' . $config['site_name'],
        'description' => 'Book ' . $activity['title'] . ' with current prices, reviews and available dates.',
        'canonical' => absolute_url($config, activity_path($activity)),
        'image' => image_from_item($activity, 'activity', $config),
    ], function () use ($activity, $dates, $related, $config, $breadcrumbs): void {
        $image = image_from_item($activity, 'activity', $config);
        $price = $activity['from_price'] ?? 0;
        $currency = $activity['currency'] ?? $config['currency'];
        ?>
        <section class="detail-hero" style="--detail-image: url('<?= e($image) ?>')">
            <div class="container">
                <?php dubai_render_breadcrumbs($breadcrumbs); ?>
                <div class="detail-header">
                    <p class="eyebrow"><?= e($activity['city']['name'] ?? 'Experience') ?></p>
                    <h1><?= e($activity['title']) ?></h1>
                    <div class="detail-facts">
                        <?php if (!empty($activity['reviews']['avg_rating'])): ?>
                            <span>
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" style="width: 12px; height: 12px; fill: var(--amber);"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                                <?= e(number_format((float) $activity['reviews']['avg_rating'], 1)) ?> rating
                            </span>
                        <?php endif; ?>
                        <span><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></span>
                        <?php if (!empty($activity['duration'])): ?>
                            <span><?= e($activity['duration']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-gallery" style="background-image: url('<?= e($image) ?>')"></div>

                <div class="detail-grid">
                    <div class="detail-content">
                        <h2>Experience details</h2>
                        <dl class="detail-list">
                            <div><dt>City</dt><dd><?= e($activity['city']['name'] ?? '') ?></dd></div>
                            <div><dt>Supplier</dt><dd><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></dd></div>
                            <div><dt>Cancellation</dt><dd><?= e(strip_tags((string) ($activity['cancellation_policy'] ?? 'Check partner checkout for policy.'))) ?></dd></div>
                        </dl>

                        <h2>Upcoming dates</h2>
                        <div class="date-grid">
                            <?php foreach (array_slice($dates, 0, 12) as $date): ?>
                                <span><?= e($date) ?></span>
                            <?php endforeach; ?>
                            <?php if ($dates === []): ?>
                                <p style="grid-column: 1 / -1; color: var(--muted); font-weight: 500;">Dates are confirmed during checkout.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <aside class="checkout-panel">
                        <span class="price-label">Tickets From</span>
                        <strong><?= e(money($price, (string) $currency)) ?></strong>
                        <a class="button-link wide" href="<?= e(go_url($activity, 'activity')) ?>" rel="sponsored nofollow">Check Availability</a>
                        <p class="checkout-note">Secure checkout on our official ticket partner's site. We may earn a commission &mdash; at no extra cost to you.</p>
                    </aside>
                </div>
            </div>
        </section>
        <?php render_card_section('More Attractions in ' . ($activity['city']['name'] ?? 'Dubai'), '/attractions', array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $activity['id']), 'activity', $config); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            activity_schema($config, $activity),
            dubai_breadcrumb_schema($config, $breadcrumbs),
        ],
    ]);
}

function render_card_section(string $heading, string $href, array $items, string $type, array $config, string $variant = ''): void
{
    if ($items === []) {
        return;
    }
    ?>
    <section class="section-band<?= $variant !== '' ? ' ' . e($variant) : '' ?>">
        <div class="container">
            <div class="section-heading">
                <h2><?= e($heading) ?></h2>
                <a href="<?= e($href) ?>">See All</a>
            </div>
            <div class="rail-wrapper">
                <button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="rail">
                    <?php foreach ($items as $item): ?>
                        <?= $type === 'event' ? event_card($item, $config) : activity_card($item, $config) ?>
                    <?php endforeach; ?>
                </div>
                <button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>
    <?php
}

function artist_card(array $performer): string
{
    $name = (string) ($performer['name'] ?? 'Artist');
    $category = (string) ($performer['category']['name'] ?? '');
    $total = (int) ($performer['total_performances'] ?? 0);
    $img = mapped_image('performer', (int) ($performer['id'] ?? 0));
    ob_start();
    ?>
    <a class="artist-card" href="<?= e(artist_path($performer)) ?>">
        <?php if ($img !== null): ?>
            <span class="artist-avatar artist-avatar--img"><img src="<?= e($img) ?>" alt="<?= e($name) ?>" loading="lazy"></span>
        <?php else: ?>
            <span class="artist-avatar" aria-hidden="true"><?= e(artist_initials($name)) ?></span>
        <?php endif; ?>
        <strong><?= e($name) ?></strong>
        <span><?= e($category !== '' ? $category : 'Live') ?><?= $total > 0 ? ' · ' . e((string) $total) . ' shows' : '' ?></span>
    </a>
    <?php
    return (string) ob_get_clean();
}

function render_artists_rail(array $performers): void
{
    if ($performers === []) {
        return;
    }
    ?>
    <section class="section-band">
        <div class="container">
            <div class="section-heading">
                <h2>Trending Artists</h2>
                <a href="/artists">See All</a>
            </div>
            <div class="rail-wrapper">
                <button class="rail-btn prev" aria-label="Scroll left" data-scroll-dir="-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="rail artist-rail">
                    <?php foreach ($performers as $performer): ?>
                        <?= artist_card($performer) ?>
                    <?php endforeach; ?>
                </div>
                <button class="rail-btn next" aria-label="Scroll right" data-scroll-dir="1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>
    <?php
}

function render_weekend_page(HelloTicketsClient $client, array $config, int $cityId): void
{
    $city = null;
    foreach ($config['market_cities'] as $marketCity) {
        if ((int) $marketCity['id'] === $cityId) {
            $city = $marketCity;
            break;
        }
    }
    if ($city === null) {
        render_error_page($config, 404, 'City not found', 'We do not cover weekend events for this city yet.');
        return;
    }

    if (current_path() !== weekend_path($city)) {
        $requestedSlug = (string) substr(current_path(), strlen('/events/this-weekend-in-'));
        if (legacy_id_from_slug($requestedSlug) === $cityId
            && !legacy_slug_corresponds($requestedSlug, slugify((string) $city['name']))) {
            render_error_page($config, 404, 'City not found', 'We do not cover weekend events for this city yet.');
            return;
        }
        redirect_permanent(weekend_path($city));
        return;
    }

    [$saturday, $sunday] = date_bounds('weekend');
    $rangeLabel = $saturday->format('M j') . '–' . $sunday->format($saturday->format('M') === $sunday->format('M') ? 'j' : 'M j');

    $data = api_result(static fn() => $client->performances(array_merge([
        'limit' => 24,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params('weekend'))), ['performances' => [], 'total_count' => 0]);
    $events = $data['performances'] ?? [];
    $total = max((int) ($data['total_count'] ?? 0), count($events));

    // Ticketmaster fills the same weekend window when HelloTickets is thin.
    if (count($events) < 24) {
        $tmWeekend = tm_events_for_city($config, (string) $city['name'], (string) ($city['country_code'] ?? ''), [
            'localStartDateTime' => $saturday->format('Y-m-d\T00:00:00') . ',' . $sunday->format('Y-m-d\T23:59:59'),
        ], 24);
        if ($tmWeekend !== []) {
            $events = array_slice(merge_events_dedupe($events, $tmWeekend), 0, 24);
            $total = max($total, count($events));
        }
    }

    $fallback = false;
    if ($events === []) {
        $fallback = true;
        $events = api_result(static fn() => $client->performances(array_merge([
            'limit' => 12,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $cityId,
        ], date_params(null))), ['performances' => []])['performances'] ?? [];
    }

    // Attraction-led markets (Dubai has ~1 sellable event) would otherwise render a
    // near-empty page — attractions are open on weekends, so they ARE the answer there.
    $weekendActivities = count($events) < 12
        ? (api_result(static fn() => $client->activities([
            'limit' => 12,
            'page' => 1,
            'city_id' => $cityId,
        ]), ['activities' => []])['activities'] ?? [])
        : [];

    $minPrice = null;
    $currency = (string) $config['currency'];
    $venues = [];
    $topNames = [];
    foreach ($events as $event) {
        $price = (float) ($event['price_range']['min_price'] ?? 0);
        if ($price > 0 && ($minPrice === null || $price < $minPrice)) {
            $minPrice = $price;
            $currency = (string) ($event['price_range']['currency'] ?? $currency);
        }
        $venue = trim((string) ($event['venue']['name'] ?? ''));
        if ($venue !== '' && !in_array($venue, $venues, true)) {
            $venues[] = $venue;
        }
        if (count($topNames) < 3) {
            $eventName = trim((string) ($event['name'] ?? ''));
            if ($eventName !== '' && !in_array($eventName, $topNames, true)) {
                $topNames[] = $eventName;
            }
        }
    }

    if (!$fallback && $events !== []) {
        $directAnswer = 'There ' . ($total === 1 ? 'is 1 live event' : 'are ' . number_format($total) . ' live events') . ' in ' . $city['name'] . ' this weekend (' . $rangeLabel . ')'
            . ($topNames !== [] ? ', including ' . implode(', ', array_slice($topNames, 0, 2)) : '')
            . ($minPrice !== null ? ', with tickets from ' . money($minPrice, $currency) : '') . '.';
    } elseif ($events !== []) {
        $directAnswer = 'No events are on sale for this weekend (' . $rangeLabel . ') in ' . $city['name'] . ' yet — here is what is coming up next instead. New weekend dates appear as soon as they go on sale.';
    } elseif ($weekendActivities !== []) {
        $directAnswer = 'No live events are on sale in ' . $city['name'] . ' this weekend (' . $rangeLabel . '), but ' . $city['name'] . '\'s top attractions, tours and experiences below are all open and bookable for weekend visits.';
    } else {
        $directAnswer = 'There are no live events on sale in ' . $city['name'] . ' right now. Check the city page for attractions and tours, or browse nearby cities.';
    }

    $faqs = [];
    if (!$fallback && $events !== []) {
        $faqs[] = ['q' => 'What\'s happening in ' . $city['name'] . ' this weekend?', 'a' => ($total === 1 ? '1 live event is' : number_format($total) . ' live events are') . ' on sale for ' . $rangeLabel . ($topNames !== [] ? ', including ' . implode(', ', $topNames) : '') . '. The full list with dates and prices is on this page.'];
        if ($minPrice !== null) {
            $faqs[] = ['q' => 'How much are tickets for ' . $city['name'] . ' events this weekend?', 'a' => 'Tickets start from ' . money($minPrice, $currency) . '. Every price on this page is live from our official ticketing partner and includes instant e-ticket delivery.'];
        }
        if ($venues !== []) {
            $faqs[] = ['q' => 'Which venues have events in ' . $city['name'] . ' this weekend?', 'a' => 'This weekend\'s events take place at ' . implode(', ', array_slice($venues, 0, 5)) . (count($venues) > 5 ? ' and more venues' : '') . '.'];
        }
    }
    if ($weekendActivities !== []) {
        $activityNames = [];
        foreach ($weekendActivities as $weekendActivity) {
            $activityTitle = trim((string) ($weekendActivity['title'] ?? ''));
            if ($activityTitle !== '' && count($activityNames) < 3) {
                $activityNames[] = $activityTitle;
            }
        }
        if ($activityNames !== []) {
            $faqs[] = ['q' => 'What can I do in ' . $city['name'] . ' this weekend besides live events?', 'a' => 'Top weekend-bookable experiences in ' . $city['name'] . ' include ' . implode(', ', $activityNames) . ' and more — all with live prices and instant e-tickets on this page.'];
        }
    }
    $faqs[] = ['q' => 'How often is this page updated?', 'a' => 'Listings, prices and availability are pulled live from our official ticketing partner, so this page always reflects what is currently on sale for the upcoming weekend in ' . $city['name'] . '.'];

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            array_merge(item_list_schema($config, $events, 'event'), ['@context' => null]),
            dubai_faq_schema($faqs),
        ])),
    ];
    // item_list_schema sets @context — strip it inside the graph
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    render_layout($config, [
        'title' => 'Events This Weekend in ' . $city['name'] . ' (' . $rangeLabel . ') | ' . $config['site_name'],
        'description' => 'What\'s on in ' . $city['name'] . ' this weekend: live events for ' . $rangeLabel . ' with venues and ticket prices' . ($minPrice !== null ? ' from ' . money($minPrice, $currency) : '') . '. Updated daily.',
        'canonical' => absolute_url($config, weekend_path($city)),
    ], function () use ($config, $city, $events, $fallback, $rangeLabel, $directAnswer, $faqs, $weekendActivities): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= e($rangeLabel) ?></p>
                <h1>Events This Weekend in <?= e($city['name']) ?></h1>
                <p class="listing-sub"><?= e($directAnswer) ?></p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>Nothing on sale right now</h2>
                        <p>Browse <?= e($city['name']) ?> attractions and tours instead.</p>
                        <a class="button-link" href="<?= e(city_path($city)) ?>">Explore <?= e($city['name']) ?></a>
                    </div>
                <?php else: ?>
                    <?php if ($fallback): ?>
                        <div class="section-heading"><h2>Coming Up in <?= e($city['name']) ?></h2><a href="<?= e(city_path($city)) ?>">City guide</a></div>
                    <?php else: ?>
                        <div class="section-heading"><h2>This Weekend's Lineup</h2><a href="<?= e(city_path($city)) ?>">City guide</a></div>
                    <?php endif; ?>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php render_card_section('Things to Do in ' . $city['name'] . ' This Weekend', city_path($city), $weekendActivities, 'activity', $config, 'muted'); ?>
        <?php dubai_render_faq($faqs, 'This Weekend in ' . $city['name'] . ' — FAQs'); ?>
        <?php
    }, $schemaGraph);
}

function render_artists_page(HelloTicketsClient $client, array $config): void
{
    $page = page_number();
    $data = api_result(static fn() => $client->performers([
        'limit' => 48,
        'page' => $page,
    ]), ['performers' => [], 'total_count' => 0]);
    $performers = $data['performers'] ?? [];

    render_layout($config, [
        'title' => 'Artists On Tour — Concert & Show Tickets | ' . $config['site_name'],
        'description' => 'Browse artists currently on tour. See upcoming dates, venues and live ticket prices for every show.',
        'canonical' => absolute_url($config, '/artists'),
    ], function () use ($performers, $data): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">On Tour</p>
                <h1>Trending Artists</h1>
                <p class="listing-sub">Every artist on this page has confirmed upcoming shows — concerts, sports and stage acts, roughly ordered by how much they're touring right now. Open an artist to see their full tour in one place: every date, city, venue and the live starting price, with new shows added automatically the moment tickets go on sale.</p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($performers === []): ?>
                    <div class="empty-state">
                        <h2>No artists found</h2>
                        <p>Check back soon — tours are added as soon as tickets go on sale.</p>
                        <a class="button-link" href="/events">Browse events</a>
                    </div>
                <?php else: ?>
                    <div class="artist-grid">
                        <?php foreach ($performers as $performer): ?>
                            <?= artist_card($performer) ?>
                        <?php endforeach; ?>
                    </div>
                    <?php render_pagination($data); ?>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, item_list_schema_for_artists($config, $performers));
}

function render_artist_detail_page(HelloTicketsClient $client, array $config, int $performerId, ?array $tmOnly = null): void
{
    if ($tmOnly !== null) {
        // Artist unknown to HelloTickets, sourced entirely from Ticketmaster.
        $performer = tm_normalize_attraction($tmOnly);
    } else {
        $performer = api_result(static fn() => $client->performer($performerId));
        if ($performer === [] || empty($performer['id'])) {
            render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
            return;
        }

        if (current_path() !== artist_path($performer)) {
            $requestedSlug = (string) substr(current_path(), strlen('/artist/'));
            if (legacy_id_from_slug($requestedSlug) === (int) $performer['id']
                && slug_lookup('artist', $requestedSlug) !== (int) $performer['id']
                && !legacy_slug_corresponds($requestedSlug, slugify((string) $performer['name']))) {
                render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
                return;
            }
            redirect_permanent(artist_path($performer));
            return;
        }
    }

    $name = (string) ($performer['name'] ?? 'Artist');
    $events = $tmOnly !== null ? [] : (api_result(static fn() => $client->performances([
        'limit' => 48,
        'page' => 1,
        'is_sellable' => 'true',
        'performer_id' => $performerId,
    ]), ['performances' => []])['performances'] ?? []);

    // ---- Ticketmaster fallback ----
    // HelloTickets is concert-heavy and skews EU. For US-touring acts and US sports teams,
    // HT often returns just a couple of shows (or none). Pull Ticketmaster for the SAME name,
    // normalise to the HT shape, and merge by (date, venue) — HT wins on conflict so we keep
    // the higher-commission link. The threshold is generous (<10) so even artists with some
    // HT inventory still get full tour coverage on a single page that ranks for "X tour dates".
    $tmAttraction = $tmOnly;
    if (count($events) < 10 && ($tm = tm_client($config)) !== null) {
        if ($tmAttraction === null) {
            $tmRaw = api_result(static fn() => $tm->attractions(['keyword' => $name, 'size' => 3]), []);
            $tmAttractions = $tmRaw['_embedded']['attractions'] ?? [];
            // Best name match: prefer exact case-insensitive, else first result with upcoming events
            foreach ($tmAttractions as $a) {
                if (strcasecmp((string) ($a['name'] ?? ''), $name) === 0) {
                    $tmAttraction = $a;
                    break;
                }
            }
            if ($tmAttraction === null && $tmAttractions !== [] && !empty($tmAttractions[0]['upcomingEvents']['_total'])) {
                $tmAttraction = $tmAttractions[0];
            }
        }
        if ($tmAttraction !== null) {
            $tmEventsRaw = api_result(static fn() => $tm->events([
                'attractionId' => (string) $tmAttraction['id'],
                'size' => 50,
            ]), []);
            $tmEvents = array_map('tm_normalize_event', $tmEventsRaw['_embedded']['events'] ?? []);
            $events = merge_events_dedupe($events, $tmEvents);
        }
    }

    // TM-only artists with zero upcoming events would be empty doorway pages — 404.
    if ($tmOnly !== null && $events === []) {
        render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
        return;
    }

    $nextDate = '';
    if (!empty($performer['next_performance_local_date'])) {
        $parsed = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (string) $performer['next_performance_local_date']);
        $nextDate = $parsed ? $parsed->format('D, M j') : '';
    }

    $tourCities = [];
    foreach ($events as $tourEvent) {
        $tourCity = trim((string) ($tourEvent['venue']['city'] ?? ''));
        if ($tourCity !== '' && !in_array($tourCity, $tourCities, true)) {
            $tourCities[] = $tourCity;
        }
    }
    $faqs = artist_faqs($performer, $events, $config);

    $description = $tourCities !== []
        ? 'See ' . count($events) . ' upcoming ' . $name . ' shows in ' . implode(', ', array_slice($tourCities, 0, 4)) . (count($tourCities) > 4 ? ' and more cities' : '') . ' with dates, venues and live ticket prices.'
        : 'See all upcoming ' . $name . ' shows with dates, venues, cities and live ticket prices. Secure checkout on our official ticket partner.';

    $artistSchema = artist_schema($config, $performer, $events);
    unset($artistSchema['@context']);
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $artistSchema,
            $faqs !== [] ? dubai_faq_schema($faqs) : null,
        ])),
    ];

    $performerPhoto = mapped_image('performer', (int) ($performer['id'] ?? 0));

    render_layout($config, [
        'title' => $name . ' Tickets, Tour Dates & Venues | ' . $config['site_name'],
        'description' => $description,
        'canonical' => absolute_url($config, artist_path($performer)),
        'image' => $performerPhoto !== null ? absolute_image_url($config, $performerPhoto) : null,
    ], function () use ($config, $performer, $name, $events, $nextDate, $tourCities, $faqs): void {
        ?>
        <section class="listing-hero artist-hero">
            <div class="container">
                <?php $heroImg = mapped_image('performer', (int) ($performer['id'] ?? 0)); ?>
                <div class="artist-hero__row">
                    <?php if ($heroImg !== null): ?>
                        <span class="artist-avatar artist-avatar--lg artist-avatar--img"><img src="<?= e($heroImg) ?>" alt="<?= e($name) ?>" loading="lazy"></span>
                    <?php else: ?>
                        <span class="artist-avatar artist-avatar--lg" aria-hidden="true"><?= e(artist_initials($name)) ?></span>
                    <?php endif; ?>
                    <div>
                        <p class="eyebrow"><?= e($performer['category']['name'] ?? 'On Tour') ?></p>
                        <h1><?= e($name) ?></h1>
                        <div class="artist-hero__facts">
                            <?php if (count($events) > 0): ?>
                                <span><?= e((string) count($events)) ?> upcoming show<?= count($events) === 1 ? '' : 's' ?></span>
                            <?php endif; ?>
                            <?php if ($nextDate !== ''): ?>
                                <span>Next: <?= e($nextDate) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($tourCities !== []): ?>
                            <p class="listing-sub">On tour in <?= e(implode(', ', array_slice($tourCities, 0, 8))) ?><?= count($tourCities) > 8 ? ' and ' . e((string) (count($tourCities) - 8)) . ' more cities' : '' ?>.</p>
                        <?php endif; ?>
                        <?php if (count($events) === 1 && !empty($events[0]['url'])): ?>
                            <a class="button-link artist-hero__cta" href="<?= e(go_url($events[0], 'event')) ?>" rel="sponsored nofollow">Get Tickets</a>
                        <?php elseif (count($events) > 1): ?>
                            <a class="button-link artist-hero__cta" href="#tour-dates">See Tour Dates</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-band" id="tour-dates">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>No shows on sale right now</h2>
                        <p>New <?= e($name) ?> dates appear here as soon as tickets are released.</p>
                        <a class="button-link" href="/artists">Browse all artists</a>
                    </div>
                <?php else: ?>
                    <div class="section-heading">
                        <h2>Tour Dates</h2>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $name . ' Ticket FAQs'); ?>
        <?php
    }, $schemaGraph);
}

function artist_faqs(array $performer, array $events, array $config): array
{
    $name = (string) ($performer['name'] ?? 'this artist');
    $faqs = [];

    if ($events !== []) {
        $first = $events[0];
        $firstWhen = format_date_time($first['start_date'] ?? []);
        $firstVenue = trim((string) ($first['venue']['name'] ?? ''));
        $firstCity = trim((string) ($first['venue']['city'] ?? ''));
        $answer = 'The next ' . $name . ' show is ' . $firstWhen
            . ($firstVenue !== '' ? ' at ' . $firstVenue : '')
            . ($firstCity !== '' ? ' in ' . $firstCity : '') . '.';
        $faqs[] = ['q' => 'Where is ' . $name . ' performing next?', 'a' => $answer];

        $minPrice = null;
        $currency = (string) $config['currency'];
        foreach ($events as $event) {
            $price = (float) ($event['price_range']['min_price'] ?? 0);
            if ($price > 0 && ($minPrice === null || $price < $minPrice)) {
                $minPrice = $price;
                $currency = (string) ($event['price_range']['currency'] ?? $currency);
            }
        }
        if ($minPrice !== null) {
            $faqs[] = ['q' => 'How much are ' . $name . ' tickets?', 'a' => 'Tickets currently start from ' . money($minPrice, $currency) . ' depending on the city and seat. Prices come live from our official ticketing partner and can change as the show date approaches.'];
        }

        $cities = [];
        foreach ($events as $event) {
            $city = trim((string) ($event['venue']['city'] ?? ''));
            if ($city !== '' && !in_array($city, $cities, true)) {
                $cities[] = $city;
            }
        }
        if ($cities !== []) {
            $faqs[] = ['q' => 'Which cities is ' . $name . ' playing on this tour?', 'a' => count($events) . ' shows are currently on sale across ' . implode(', ', array_slice($cities, 0, 8)) . (count($cities) > 8 ? ' and more' : '') . '. Each date links to live seat availability.'];
        }
    }

    $faqs[] = ['q' => 'How do I buy official ' . $name . ' tickets?', 'a' => 'Pick a date on this page and complete checkout on our official ticketing partner\'s secure site. Tickets are delivered instantly by email, and prices shown are live.'];

    return $faqs;
}

function item_list_schema_for_artists(array $config, array $performers): array
{
    $elements = [];
    foreach (array_values($performers) as $index => $performer) {
        $elements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => absolute_url($config, artist_path($performer)),
            'name' => $performer['name'] ?? '',
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $elements,
    ];
}

function artist_schema(array $config, array $performer, array $events): array
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => ($performer['category']['name'] ?? '') === 'Sports' ? 'SportsTeam' : 'PerformingGroup',
        'name' => $performer['name'] ?? '',
        'url' => absolute_url($config, artist_path($performer)),
    ];

    $eventSchemas = [];
    foreach (array_slice($events, 0, 10) as $event) {
        $eventSchemas[] = [
            '@type' => 'Event',
            'name' => $event['name'] ?? '',
            'startDate' => $event['start_date']['date_time'] ?? ($event['start_date']['local_date'] ?? ''),
            'location' => [
                '@type' => 'Place',
                'name' => $event['venue']['name'] ?? '',
                'address' => trim(($event['venue']['address'] ?? '') . ', ' . ($event['venue']['city'] ?? ''), ', '),
            ],
            'url' => event_canonical_url($config, $event),
        ];
    }
    if ($eventSchemas !== []) {
        $schema['event'] = $eventSchemas;
    }

    return $schema;
}

function render_promo_banner(array $config): void
{
    ?>
    <section class="promo-band">
        <div class="container">
            <div class="promo-banner">
                <div class="promo-copy">
                    <span class="promo-kicker">Why <?= e($config['site_name']) ?></span>
                    <h2>Real tickets, instant delivery.</h2>
                    <p>Live prices, instant e-tickets and free cancellation on most experiences &mdash; booked securely through our official ticket partner.</p>
                </div>
                <a class="promo-btn" href="/attractions">Browse experiences</a>
            </div>
        </div>
    </section>
    <?php
}

function render_live_events_band(): void
{
    $cards = [
        ['title' => 'Concerts & Gigs', 'sub' => 'Live music nights', 'href' => category_path(['id' => 2, 'name' => 'Concerts']), 'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Theatre & Arts', 'sub' => 'Plays & musicals', 'href' => category_path(['id' => 3, 'name' => 'Theatre']), 'image' => 'https://images.unsplash.com/photo-1503095396549-807759245b35?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Sports', 'sub' => 'Matchday action', 'href' => category_path(['id' => 1, 'name' => 'Sports']), 'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Desert Safari', 'sub' => 'Dunes & dinners', 'href' => '/attractions?q=Desert%20Safari', 'image' => 'https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Theme Parks', 'sub' => 'Rides & waterparks', 'href' => '/attractions?q=Theme%20Park', 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'],
        ['title' => 'Cruises', 'sub' => 'Marina & dhow', 'href' => '/attractions?q=Cruise', 'image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=800&q=80'],
    ];
    ?>
    <section class="live-band">
        <div class="container">
            <div class="section-heading">
                <h2>Browse by Category</h2>
                <a href="/attractions">See All</a>
            </div>
            <div class="cat-grid">
                <?php foreach ($cards as $card): ?>
                    <a class="cat-tile" href="<?= e($card['href']) ?>">
                        <img src="<?= e($card['image']) ?>" alt="<?= e($card['title']) ?>" loading="lazy">
                        <span class="cat-tile__body">
                            <span class="cat-tile__title"><?= e($card['title']) ?></span>
                            <span class="cat-tile__sub"><?= e($card['sub']) ?></span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function event_card(array $performance, array $config): string
{
    $image = image_from_item($performance, 'event', $config);
    $price = $performance['price_range']['min_price'] ?? 0;
    $currency = (string) ($performance['price_range']['currency'] ?? $config['currency']);
    
    $dateStr = $performance['start_date']['local_date'] ?? '';
    $monthAbbr = 'TBA';
    $dayNum = '';
    if ($dateStr !== '') {
        $d = DateTimeImmutable::createFromFormat('!Y-m-d', $dateStr);
        if ($d) {
            $monthAbbr = strtoupper($d->format('M'));
            $dayNum = $d->format('j');
        }
    }

    // Cards link straight to the partner checkout (via /go), falling back to the
    // on-site detail page only when the item has no bookable URL. Only outbound
    // links carry sponsored/nofollow — internal detail links must pass PageRank.
    $isOutbound = !empty($performance['url']);
    $cardHref = $isOutbound ? go_url($performance, 'event') : event_path($performance);
    $rel = $isOutbound ? ' rel="sponsored nofollow"' : '';

    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e($cardHref) ?>"<?= $rel ?>>
            <img src="<?= e($image) ?>" alt="<?= e($performance['name'] ?? 'Event') ?>" loading="lazy">
            <div class="card-date-badge">
                <span class="month"><?= e($monthAbbr) ?></span>
                <span class="day"><?= e($dayNum) ?></span>
            </div>
            <div class="card-rating-strip">
                <span class="votes"><?= e($performance['category']['name'] ?? 'Event') ?></span>
            </div>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e($cardHref) ?>"<?= $rel ?>><?= e($performance['name'] ?? 'Event') ?></a>
            <p><?= e(format_date_time($performance['start_date'] ?? [])) ?></p>
            <p><?= e(trim(($performance['venue']['name'] ?? '') . ', ' . ($performance['venue']['city'] ?? 'Dubai'), ', ')) ?></p>
            <p class="card-onwards"><?= e(money($price, $currency)) ?><?= ((float) $price) > 0 ? ' onwards' : '' ?></p>
        </div>
    </article>
    <?php
    return (string) ob_get_clean();
}

function activity_card(array $activity, array $config): string
{
    $image = image_from_item($activity, 'activity', $config);
    $price = $activity['from_price'] ?? 0;
    $currency = (string) ($activity['currency'] ?? $config['currency']);
    $rating = !empty($activity['reviews']['avg_rating']) ? number_format((float) $activity['reviews']['avg_rating'], 1) : null;
    $reviewsCount = !empty($activity['reviews']['number_of_reviews']) ? (int) $activity['reviews']['number_of_reviews'] : null;

    // Cards link straight to the partner checkout (via /go), falling back to the
    // on-site detail page only when the item has no bookable URL. Only outbound
    // links carry sponsored/nofollow — internal detail links must pass PageRank.
    $isOutbound = !empty($activity['url']);
    $cardHref = $isOutbound ? go_url($activity, 'activity') : activity_path($activity);
    $rel = $isOutbound ? ' rel="sponsored nofollow"' : '';

    ob_start();
    ?>
    <article class="ticket-card">
        <a class="card-image" href="<?= e($cardHref) ?>"<?= $rel ?>>
            <img src="<?= e($image) ?>" alt="<?= e($activity['title'] ?? 'Experience') ?>" loading="lazy">
            <span class="category"><?= e($activity['city']['name'] ?? 'Attraction') ?></span>
            <?php if ($rating !== null): ?>
                <div class="card-rating-strip">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16 1.895l4.814 9.755 10.764 1.564-7.79 7.593 1.838 10.72L16 26.467l-9.626 5.06 1.838-10.72-7.79-7.593 10.764-1.564z"></path></svg>
                    <?= e($rating) ?>/5
                    <?php if ($reviewsCount !== null): ?>
                        <span class="votes"><?= e(number_format($reviewsCount)) ?> votes</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </a>
        <div class="card-body">
            <a class="card-title" href="<?= e($cardHref) ?>"<?= $rel ?>><?= e($activity['title'] ?? 'Experience') ?></a>
            <p><?= e($activity['supplier_name'] ?? 'Ticket partner') ?></p>
            <p class="card-onwards"><?= e(money($price, $currency)) ?><?= ((float) $price) > 0 ? ' onwards' : '' ?></p>
        </div>
    </article>
    <?php
    return (string) ob_get_clean();
}

function render_pagination(array $data): void
{
    $page = (int) ($data['current_page'] ?? page_number());
    $perPage = max(1, (int) ($data['per_page'] ?? 24));
    $total = (int) ($data['total_count'] ?? 0);
    $hasNext = $total > $page * $perPage;
    if ($page <= 1 && !$hasNext) {
        return;
    }

    $query = $_GET;
    ?>
    <nav class="pagination" aria-label="Pagination">
        <?php if ($page > 1): ?>
            <?php $query['page'] = $page - 1; ?>
            <a href="<?= e(route_url(current_path(), $query)) ?>">Previous</a>
        <?php endif; ?>
        <span>Page <?= e($page) ?></span>
        <?php if ($hasNext): ?>
            <?php $query['page'] = $page + 1; ?>
            <a href="<?= e(route_url(current_path(), $query)) ?>">Next</a>
        <?php endif; ?>
    </nav>
    <?php
}

function handle_outbound_redirect(array $config): void
{
    header('X-Robots-Tag: noindex, nofollow');

    $destination = base64_url_decode((string) ($_GET['u'] ?? ''));
    $type = preg_replace('/[^a-z]/', '', (string) ($_GET['type'] ?? 'ticket')) ?: 'ticket';
    // id is alphanumeric: integer HT ids OR "tm-{tm-id}" for Ticketmaster items.
    $id = preg_replace('/[^a-zA-Z0-9\-]/', '', (string) ($_GET['id'] ?? '0')) ?: '0';

    $subId = $type . '-' . $id;
    // Domain decides the affiliate program: HelloTickets URLs earn via the HelloTickets
    // Impact link, Ticketmaster URLs (the fallback source) via the Ticketmaster Impact link.
    $affiliate = outbound_affiliate_url($config, $destination, $subId);
    if ($affiliate === null) {
        render_error_page($config, 400, 'Invalid ticket link', 'This outbound ticket link is not valid.');
        return;
    }

    $source = allowed_ticketmaster_url($destination) ? 'ticketmaster' : 'hellotickets';

    // Redirect FIRST so the affiliate click is never lost to a logging hiccup
    // (unwritable storage, display_errors, etc.). Logging is best-effort only.
    header('Location: ' . $affiliate, true, 302);

    // IP is anonymised before logging (GDPR data-minimisation; /privacy documents
    // this). IPv4 drops the last octet, IPv6 keeps only the first 3 groups.
    $rawIp = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    $anonIp = str_contains($rawIp, ':')
        ? implode(':', array_slice(explode(':', $rawIp), 0, 3)) . '::'
        : preg_replace('/\.\d+$/', '.0', $rawIp);

    $logLine = json_encode([
        'time' => gmdate('c'),
        'type' => $type,
        'id' => $id,
        'source' => $source,
        'destination' => $destination,
        'ip' => $anonIp,
        'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 180),
    ], JSON_UNESCAPED_SLASHES) . PHP_EOL;

    $logDir = __DIR__ . '/../storage';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    @file_put_contents($logDir . '/clicks.log', $logLine, FILE_APPEND | LOCK_EX);
}

function render_robots(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /go\n";
    echo "Disallow: /search\n";
    echo "\n";
    // AI search & assistant crawlers are explicitly welcome — AI citations are a
    // traffic channel for this site (see /llms.txt for a machine-readable summary).
    foreach (['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-User', 'Claude-SearchBot', 'PerplexityBot', 'Perplexity-User', 'Google-Extended', 'GoogleOther', 'Applebot-Extended', 'meta-externalagent', 'CCBot'] as $bot) {
        echo 'User-agent: ' . $bot . "\n";
        echo "Allow: /\n";
        echo "Disallow: /go\n\n";
    }
    echo 'Sitemap: ' . $config['site_url'] . "/sitemap.xml\n";
}

function render_llms_txt(HelloTicketsClient $client, array $config, array $destinationsContent): void
{
    header('Content-Type: text/plain; charset=utf-8');
    $site = $config['site_url'];
    $name = $config['site_name'];

    echo '# ' . $name . "\n\n";
    echo '> ' . $name . " is a ticket discovery site for live events, concerts, sports, theatre, attractions and tours in Dubai, Abu Dhabi and 100+ cities across the United States, Canada, the United Kingdom, Italy, Spain and France. Prices and availability are live from an official ticketing partner; checkout happens on the partner's secure site. Pages include live counts, starting prices, venues and FAQs that are safe to cite.\n\n";

    echo "## Key pages\n\n";
    echo '- [Dubai tickets & attractions](' . $site . "/dubai): editorial hub with 25+ attraction guides, prices and FAQs\n";
    echo '- [Abu Dhabi tickets & attractions](' . $site . "/abu-dhabi)\n";
    echo '- [Artists on tour](' . $site . "/artists): every artist with upcoming shows, dates, venues and starting prices\n";
    echo '- [All live events](' . $site . "/events)\n";
    echo '- [All attractions & tours](' . $site . "/attractions)\n\n";

    echo "## Countries\n\n";
    foreach (($destinationsContent['countries'] ?? []) as $slug => $country) {
        echo '- [' . ($country['name'] ?? $slug) . ' tickets](' . $site . '/' . $slug . ")\n";
    }
    echo "\n## Sports schedules\n\n";
    foreach (league_seed_list() as $league) {
        echo '- [' . $league['title'] . '](' . $site . '/' . $league['slug'] . '): ' . $league['lead'] . "\n";
    }
    echo '- [Top sports teams](' . $site . "/teams): schedules and tickets for top NBA, NFL, MLB and NHL teams\n";

    echo "\n## Top venues\n\n";
    echo '- [All venues](' . $site . "/venues)\n";
    foreach (venue_seed_list() as [$venueName, $venueCity]) {
        echo '- [' . $venueName . ' events](' . $site . '/venue/' . slugify($venueName) . '): upcoming events at ' . $venueName . ', ' . $venueCity . "\n";
    }

    echo "\n## Browse by category\n\n";
    foreach (category_content() as $catSlug => $cat) {
        echo '- [' . $cat['h1'] . '](' . $site . '/category/' . $catSlug . ")\n";
    }

    echo "\n## What's on this weekend\n\n";
    foreach ($config['market_cities'] as $city) {
        if (empty($city['featured'])) {
            continue;
        }
        echo '- [Events this weekend in ' . $city['name'] . '](' . $site . weekend_path($city) . ")\n";
    }
    echo "\n## About\n\n";
    echo '- [About ' . $name . '](' . $site . "/about)\n";
    echo '- [How we make money](' . $site . "/how-we-make-money): affiliate model disclosure\n";
}

function render_sitemap(HelloTicketsClient $client, array $config, array $destinationsContent = []): void
{
    // Guarded like index.php — a missing content file must not 500 Google's discovery surface.
    $dubaiContent = file_exists(__DIR__ . '/dubai-content.php')
        ? require __DIR__ . '/dubai-content.php'
        : ['categories' => [], 'attractions' => []];

    // Content-modified date for editorial/hub pages, as a real recrawl signal.
    $contentMtimes = array_filter([
        @filemtime(__DIR__ . '/destinations-content.json') ?: null,
        @filemtime(__DIR__ . '/dubai-content.php') ?: null,
    ]);
    $contentMod = $contentMtimes !== [] ? date('Y-m-d', max($contentMtimes)) : date('Y-m-d');
    $today = date('Y-m-d');

    // loc => lastmod ('' = omit), de-duped by loc, canonical URLs only.
    $entries = [];
    $add = static function (string $path, string $lastmod = '') use (&$entries, $config): void {
        $loc = absolute_url($config, $path);
        if (!array_key_exists($loc, $entries)) {
            $entries[$loc] = $lastmod;
        }
    };

    // Home + evergreen static pages.
    $add('/', $today);
    foreach (['/events', '/attractions', '/artists', '/venues', '/teams', '/about', '/contact', '/how-we-make-money', '/privacy', '/terms'] as $staticPath) {
        $add($staticPath, $contentMod);
    }

    // Ticketmaster-sourced hubs — daily lastmod since these are live schedules.
    foreach (league_seed_list() as $league) {
        $add('/' . $league['slug'], $today);
    }
    foreach (venue_seed_list() as [$venueName]) {
        $add('/venue/' . slugify($venueName), $today);
    }
    foreach (team_seed_list() as [$teamName]) {
        $add('/team/' . slugify($teamName), $today);
    }

    // Editorial hubs — the highest-value SEO landing pages.
    $add('/dubai', $contentMod);
    $add('/abu-dhabi', $contentMod);
    foreach ($dubaiContent['categories'] ?? [] as $cat) {
        $add('/dubai/' . $cat['slug'], $contentMod);
    }
    foreach ($dubaiContent['attractions'] ?? [] as $attr) {
        $add('/dubai/' . ($attr['category_slug'] ?? 'attractions') . '/' . $attr['slug'], $contentMod);
    }
    foreach ($destinationsContent['countries'] ?? [] as $cSlug => $country) {
        $add('/' . $cSlug, $contentMod);
        foreach ($country['cities'] ?? [] as $hubCity) {
            if (!empty($hubCity['slug'])) {
                $add('/' . $cSlug . '/' . $hubCity['slug'], $contentMod);
            }
        }
    }

    // /city/{slug} pages are deliberately omitted — they canonicalize to the editorial
    // /{country}/{city} hubs, so listing them here would be non-canonical noise.

    // Category listing pages — ONLY the curated ones with hand-written content.
    // Raw API categories render noindex and must stay out of the sitemap.
    $curated = category_content();
    $categories = api_result(static fn() => $client->categories(), ['categories' => []])['categories'] ?? [];
    foreach ($categories as $category) {
        if (isset($curated[slugify((string) ($category['name'] ?? ''))])) {
            $add(category_path($category), $contentMod);
        }
    }

    // Performers / artists — top 96 by popularity (two cached pages).
    foreach ([1, 2] as $performerPage) {
        $performers = api_result(static fn() => $client->performers([
            'limit' => 48,
            'page' => $performerPage,
        ]), ['performers' => []])['performers'] ?? [];
        foreach ($performers as $performer) {
            $add(artist_path($performer));
        }
    }

    // "This weekend in {city}" intent pages — featured cities only (always have inventory).
    foreach ($config['market_cities'] as $weekendCity) {
        if (!empty($weekendCity['featured'])) {
            $add(weekend_path($weekendCity), $today);
        }
    }

    // Live events — real <lastmod> from the API's last_updated_at. Dubai alone has
    // almost no sellable events, so crawl the featured cities' inventory (capped).
    $eventCityIds = [(int) $config['default_city_id']];
    foreach ($config['market_cities'] as $eventCity) {
        if (!empty($eventCity['featured'])) {
            $eventCityIds[] = (int) $eventCity['id'];
        }
    }
    $eventEntries = 0;
    foreach (array_unique($eventCityIds) as $eventCityId) {
        if ($eventEntries >= 200) {
            break;
        }
        $events = api_result(static fn() => $client->performances(array_merge([
            'limit' => 25,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $eventCityId,
        ], date_params(null))), ['performances' => []])['performances'] ?? [];
        foreach ($events as $event) {
            $lastmod = substr((string) ($event['last_updated_at'] ?? ''), 0, 10);
            $add(event_path($event), preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastmod) === 1 ? $lastmod : '');
            $eventEntries++;
        }
    }

    // /activity/ detail pages are deliberately NOT in the sitemap: list-API titles
    // produce different slugs than the detail canonical (every entry 301s), and the
    // pages are thin. Their rich SEO twins — the /dubai/{category}/{slug} attraction
    // pages and the city/country hubs — are the indexed surface for activities.

    header('Content-Type: application/xml; charset=utf-8');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($entries as $loc => $lastmod) {
        echo "  <url><loc>" . e($loc) . "</loc>"
            . ($lastmod !== '' ? "<lastmod>" . e($lastmod) . "</lastmod>" : '')
            . "</url>\n";
    }
    echo "</urlset>\n";
}

function render_error_page(array $config, int $status, string $heading, string $message): void
{
    http_response_code($status);
    render_layout($config, [
        'title' => $heading . ' | ' . $config['site_name'],
        'description' => $message,
        'canonical' => absolute_url($config, current_path()),
    ], function () use ($heading, $message): void {
        ?>
        <section class="section-band">
            <div class="container">
                <div class="empty-state">
                    <h1><?= e($heading) ?></h1>
                    <p><?= e($message) ?></p>
                    <a class="button-link" href="/">Back home</a>
                </div>
            </div>
        </section>
        <?php
    });
}

function website_schema(array $config): array
{
    // WebSite + Organization in one graph — the Organization node is the entity
    // signal Google's knowledge graph and AI answer engines key brand facts off.
    return [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => $config['site_url'] . '/#website',
                'name' => $config['site_name'],
                'url' => $config['site_url'],
                'publisher' => ['@id' => $config['site_url'] . '/#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $config['site_url'] . '/search?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@type' => 'Organization',
                '@id' => $config['site_url'] . '/#organization',
                'name' => $config['site_name'],
                'url' => $config['site_url'],
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $config['site_url'] . '/assets/logo.svg',
                ],
                'email' => 'townmedialabs@gmail.com',
                'parentOrganization' => [
                    '@type' => 'Organization',
                    'name' => 'Town Media Labs',
                ],
                'description' => $config['site_name'] . ' is a ticket discovery site for live events, concerts, sports, theatre, attractions and tours worldwide. Prices and availability come live from official ticketing partners; checkout completes on the partner\'s secure site.',
            ],
        ],
    ];
}

function item_list_schema(array $config, array $items, string $type): array
{
    $elements = [];
    foreach (array_values($items) as $index => $item) {
        // Ticketmaster events have no on-site detail page (id=0) — their canonical
        // is the partner page, so never emit a dead /event/… URL in schema.
        $url = $type === 'event'
            ? event_canonical_url($config, $item)
            : absolute_url($config, activity_path($item));
        $elements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => $url,
            'name' => $type === 'event' ? ($item['name'] ?? '') : ($item['title'] ?? ''),
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => $elements,
    ];
}

function event_schema(array $config, array $event): array
{
    $price = $event['price_range']['min_price'] ?? 0;
    $localDate = (string) ($event['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    return [
        '@type' => 'Event',
        'name' => $event['name'] ?? '',
        'startDate' => $event['start_date']['date_time'] ?? ($event['start_date']['local_date'] ?? ''),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => [
            '@type' => 'Place',
            'name' => $event['venue']['name'] ?? '',
            'address' => trim(($event['venue']['address'] ?? '') . ', ' . ($event['venue']['city'] ?? '')),
        ],
        'image' => [image_from_item($event, 'event', $config)],
        'offers' => [
            '@type' => 'Offer',
            'url' => absolute_url($config, event_path($event)),
            'price' => (float) $price,
            'priceCurrency' => $event['price_range']['currency'] ?? $config['currency'],
            'availability' => $isPast ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
        ],
    ];
}

function activity_schema(array $config, array $activity): array
{
    $schema = [
        '@type' => 'Product',
        'name' => $activity['title'] ?? '',
        'image' => [image_from_item($activity, 'activity', $config)],
        'brand' => [
            '@type' => 'Brand',
            'name' => $activity['supplier_name'] ?? 'HelloTickets',
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => absolute_url($config, activity_path($activity)),
            'price' => (float) ($activity['from_price'] ?? 0),
            'priceCurrency' => $activity['currency'] ?? $config['currency'],
            'availability' => 'https://schema.org/InStock',
        ],
    ];

    if (!empty($activity['reviews']['avg_rating'])) {
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => (float) $activity['reviews']['avg_rating'],
            'reviewCount' => (int) ($activity['reviews']['number_of_reviews'] ?? 0),
        ];
    }

    return $schema;
}

/* ============================================================================
 * VENUE pages (Ticketmaster-sourced) — targets "{venue} tickets / events /
 * upcoming shows / what's on" keyword clusters HelloTickets doesn't cover:
 * Madison Square Garden, Red Rocks, Sphere Las Vegas, Allegiant Stadium, MetLife,
 * Wembley, Anfield, Camp Nou, etc.
 * ========================================================================== */

/** Hand-curated, high-volume venues for the /venues hub. Names are matched against TM by
 *  keyword, so we don't need to memorise IDs — they're resolved on render and cached. */
function venue_seed_list(): array
{
    return [
        ['Madison Square Garden', 'New York'],
        ['Sphere', 'Las Vegas'],
        ['Red Rocks Amphitheatre', 'Morrison'],
        ['Allegiant Stadium', 'Las Vegas'],
        ['MetLife Stadium', 'East Rutherford'],
        ['SoFi Stadium', 'Inglewood'],
        ['Fenway Park', 'Boston'],
        ['Yankee Stadium', 'Bronx'],
        ['Wrigley Field', 'Chicago'],
        ['Soldier Field', 'Chicago'],
        ['Barclays Center', 'Brooklyn'],
        ['Radio City Music Hall', 'New York'],
        ['T-Mobile Arena', 'Las Vegas'],
        ['Dodger Stadium', 'Los Angeles'],
        ['Petco Park', 'San Diego'],
        ['Citizens Bank Park', 'Philadelphia'],
        ['Wembley Stadium', 'London'],
        ['The O2', 'London'],
        ['Camp Nou', 'Barcelona'],
        ['Santiago Bernabéu', 'Madrid'],
    ];
}

/** Resolve one seed-list entry to a normalized TM venue (keyword search, exact name+city preferred). */
function venue_from_seed(array $config, string $name, string $city): ?array
{
    $tm = tm_client($config);
    if ($tm === null) {
        return null;
    }
    $raw = api_result(static fn() => $tm->venues([
        'keyword' => $name,
        'size' => 5,
        'sort' => 'relevance,desc',
    ]), []);
    $best = null;
    foreach ($raw['_embedded']['venues'] ?? [] as $candidate) {
        if (strcasecmp((string) ($candidate['name'] ?? ''), $name) === 0
            && strcasecmp((string) ($candidate['city']['name'] ?? ''), $city) === 0) {
            $best = $candidate;
            break;
        }
    }
    if ($best === null) {
        $best = ($raw['_embedded']['venues'] ?? [])[0] ?? null;
    }
    return $best !== null && !empty($best['id']) ? tm_normalize_venue($best) : null;
}

/** Clean /venue/{slug} → normalized TM venue, via the seed list (the only venues we link). */
function resolve_seed_venue(array $config, string $slug): ?array
{
    foreach (venue_seed_list() as [$name, $city]) {
        if (slugify($name) === $slug) {
            return venue_from_seed($config, $name, $city);
        }
    }
    // Ticketmaster's canonical venue name can differ from our seed name (e.g. seed
    // "Santiago Bernabéu" resolves to "Santiago Bernabéu Stadium") — links and
    // canonicals use the TM name, so match those too. All calls are cached.
    foreach (venue_seed_list() as [$name, $city]) {
        $venue = venue_from_seed($config, $name, $city);
        if ($venue !== null && slugify((string) $venue['name']) === $slug) {
            return $venue;
        }
    }
    return null;
}

function render_venue_page(array $config, string $tmVenueId): void
{
    $tm = tm_client($config);
    if ($tm === null || $tmVenueId === '') {
        render_error_page($config, 404, 'Venue not found', 'This venue page is not available.');
        return;
    }

    $rawVenue = api_result(static fn() => $tm->venue($tmVenueId), []);
    if (empty($rawVenue['id'])) {
        render_error_page($config, 404, 'Venue not found', 'This venue page is not available.');
        return;
    }
    $venue = tm_normalize_venue($rawVenue);

    // Legacy /venue/{name}-{tmId} URLs 301 to the clean name slug. tm_venue_path()
    // registers that slug → TM id in the map first, so the target always resolves —
    // seeded or not. Junk name parts on a valid id 404 instead of redirecting.
    $venuePath = tm_venue_path($venue);
    if (current_path() !== $venuePath) {
        $requestedSlug = (string) substr(current_path(), strlen('/venue/'));
        $namePart = (string) preg_replace('/-[A-Za-z0-9]{8,}$/', '', $requestedSlug);
        if (tm_legacy_id_from_slug($requestedSlug) === (string) $venue['tm_id']
            && venue_slug_lookup($requestedSlug) !== (string) $venue['tm_id']
            && !legacy_slug_corresponds($namePart, slugify((string) $venue['name']))) {
            render_error_page($config, 404, 'Venue not found', 'This venue page is not available.');
            return;
        }
        redirect_permanent($venuePath);
        return;
    }

    $rawEvents = api_result(static fn() => $tm->events([
        'venueId' => $tmVenueId,
        'size' => 50,
    ]), []);
    $events = array_map('tm_normalize_event', $rawEvents['_embedded']['events'] ?? []);
    $totalUpcoming = (int) ($rawEvents['page']['totalElements'] ?? count($events));

    // Page meta — direct-answer first paragraph (Google AI Overviews quotes these).
    $title = $venue['name'] . ' Tickets & Upcoming Events | ' . $config['site_name'];
    $direct = $events !== []
        ? 'There ' . ($totalUpcoming === 1 ? 'is 1 upcoming event' : 'are ' . number_format($totalUpcoming) . ' upcoming events')
            . ' at ' . $venue['name']
            . ($venue['city'] !== '' ? ' in ' . $venue['city'] : '')
            . '. The full schedule with dates and ticket prices is below.'
        : 'No on-sale events at ' . $venue['name'] . ' right now. New dates appear here as soon as tickets are released.';

    $description = $direct;

    $faqs = [
        ['q' => 'What events are coming up at ' . $venue['name'] . '?',
         'a' => $direct],
        ['q' => 'Where is ' . $venue['name'] . ' located?',
         'a' => trim($venue['address'] . ', ' . $venue['city'] . ' ' . $venue['state'] . ', ' . $venue['country'], ', ')],
        ['q' => 'How often is this schedule updated?',
         'a' => 'Listings, dates and ticket prices are pulled live from our ticketing partner, so this page always reflects what is currently on sale at ' . $venue['name'] . '.'],
    ];

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            [
                '@type' => 'MusicVenue',
                'name' => $venue['name'],
                'url' => absolute_url($config, tm_venue_path($venue)),
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $venue['address'],
                    'addressLocality' => $venue['city'],
                    'addressRegion' => $venue['state'],
                    'addressCountry' => $venue['country'],
                ],
            ],
            $events !== [] ? array_merge(item_list_schema($config, $events, 'event'), ['@context' => null]) : null,
            dubai_faq_schema($faqs),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    render_layout($config, [
        'title' => $title,
        'description' => $description,
        'canonical' => absolute_url($config, tm_venue_path($venue)),
    ], function () use ($venue, $events, $totalUpcoming, $direct, $faqs, $config): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Venue<?= $venue['city'] !== '' ? ' · ' . e($venue['city']) : '' ?></p>
                <h1><?= e($venue['name']) ?> Tickets</h1>
                <p class="listing-sub"><?= e($direct) ?></p>
            </div>
        </section>
        <section class="section-band" id="upcoming">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>No events on sale right now</h2>
                        <p>New dates at <?= e($venue['name']) ?> appear here as soon as tickets are released.</p>
                        <a class="button-link" href="/venues">Browse all venues</a>
                    </div>
                <?php else: ?>
                    <div class="section-heading">
                        <h2>Upcoming Events at <?= e($venue['name']) ?></h2>
                        <span class="muted"><?= e((string) $totalUpcoming) ?> upcoming</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $venue['name'] . ' — Visitor FAQs'); ?>
        <?php
    }, $schemaGraph);
}

function render_venues_index(array $config): void
{
    $venues = [];
    foreach (venue_seed_list() as [$name, $city]) {
        $venue = venue_from_seed($config, $name, $city);
        if ($venue !== null) {
            $venues[] = $venue;
        }
    }

    render_layout($config, [
        'title' => 'Top Venues — Tickets & Upcoming Events | ' . $config['site_name'],
        'description' => 'Browse upcoming events at top music, sports and theatre venues — Madison Square Garden, Sphere, Red Rocks, Wembley and more. Live prices, on-sale dates and seat maps.',
        'canonical' => absolute_url($config, '/venues'),
    ], function () use ($venues): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Venues</p>
                <h1>Top Venues</h1>
                <p class="listing-sub">Concert, sports and theatre arenas — pick one to see every upcoming show and ticket price.</p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($venues === []): ?>
                    <div class="empty-state">
                        <h2>Venues loading</h2>
                        <p>Check back in a moment.</p>
                    </div>
                <?php else: ?>
                    <div class="card-grid">
                        <?php foreach ($venues as $v): ?>
                            <article class="ticket-card">
                                <a class="card-image" href="<?= e(tm_venue_path($v)) ?>">
                                    <?php if ($v['image'] !== ''): ?>
                                        <img src="<?= e($v['image']) ?>" alt="<?= e($v['name']) ?>" loading="lazy">
                                    <?php endif; ?>
                                    <div class="card-rating-strip">
                                        <span class="votes"><?= e($v['city']) ?><?= $v['state'] !== '' ? ', ' . e($v['state']) : '' ?></span>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <a class="card-title" href="<?= e(tm_venue_path($v)) ?>"><?= e($v['name']) ?></a>
                                    <p><?= e($v['upcoming_total']) ?> upcoming events</p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    });
}

/* ============================================================================
 * LEAGUE hubs + TEAM pages (Ticketmaster-sourced) — targets US sports keyword
 * clusters that dominate Ticketmaster's organic traffic (and that HelloTickets
 * doesn't cover): "nba schedule", "nfl games today", "mlb tickets",
 * "yankees tickets", "lakers schedule", etc.
 * ========================================================================== */

/** Hand-curated leagues we expose as /{slug}. Mapped 1:1 to a TM Discovery genre name. */
function league_seed_list(): array
{
    return [
        ['slug' => 'nba',  'name' => 'NBA',  'sport' => 'Basketball', 'classification' => 'NBA',
         'title' => 'NBA Schedule, Games & Tickets',
         'lead' => 'Every upcoming NBA game — regular season, playoffs and Finals — with date, arena and live ticket prices.'],
        ['slug' => 'nfl',  'name' => 'NFL',  'sport' => 'Football',   'classification' => 'NFL',
         'title' => 'NFL Schedule, Games & Tickets',
         'lead' => 'Every upcoming NFL game with date, stadium and live ticket prices.'],
        ['slug' => 'mlb',  'name' => 'MLB',  'sport' => 'Baseball',   'classification' => 'MLB',
         'title' => 'MLB Schedule, Games & Tickets',
         'lead' => 'Every upcoming Major League Baseball game with date, ballpark and live ticket prices.'],
        ['slug' => 'nhl',  'name' => 'NHL',  'sport' => 'Hockey',     'classification' => 'NHL',
         'title' => 'NHL Schedule, Games & Tickets',
         'lead' => 'Every upcoming NHL game — regular season and Stanley Cup playoffs — with date, arena and live prices.'],
        ['slug' => 'mls',  'name' => 'MLS',  'sport' => 'Soccer',     'classification' => 'MLS',
         'title' => 'MLS Schedule, Matches & Tickets',
         'lead' => 'Every upcoming MLS match with date, stadium and live ticket prices.'],
    ];
}

function league_from_slug(string $slug): ?array
{
    foreach (league_seed_list() as $league) {
        if ($league['slug'] === $slug) {
            return $league;
        }
    }
    return null;
}

function render_league_page(array $config, string $slug): void
{
    $tm = tm_client($config);
    $league = league_from_slug($slug);
    if ($tm === null || $league === null) {
        render_error_page($config, 404, 'League not found', 'This league hub is not available.');
        return;
    }

    $raw = api_result(static fn() => $tm->events([
        'classificationName' => $league['classification'],
        'size' => 50,
    ]), []);
    $events = array_map('tm_normalize_event', $raw['_embedded']['events'] ?? []);
    $total = (int) ($raw['page']['totalElements'] ?? count($events));

    $direct = $events !== []
        ? 'There ' . ($total === 1 ? 'is 1 upcoming ' : 'are ' . number_format($total) . ' upcoming ')
            . $league['name'] . ' game' . ($total === 1 ? '' : 's')
            . ' on sale right now. The full schedule with dates, arenas and ticket prices is below.'
        : 'No on-sale ' . $league['name'] . ' games right now. New dates appear here as soon as tickets are released.';

    $faqs = [
        ['q' => 'What ' . $league['name'] . ' games are coming up?', 'a' => $direct],
        ['q' => 'How do I buy ' . $league['name'] . ' tickets?',
         'a' => 'Pick any game on this page — checkout completes securely on our official ticketing partner. Prices and seat availability are live.'],
        ['q' => 'How often is this ' . $league['name'] . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live from our partner so this page always reflects what is currently on sale.'],
    ];

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $events !== [] ? array_merge(item_list_schema($config, $events, 'event'), ['@context' => null]) : null,
            dubai_faq_schema($faqs),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    render_layout($config, [
        'title' => $league['title'] . ' | ' . $config['site_name'],
        'description' => $league['lead'] . ' Updated daily.',
        'canonical' => absolute_url($config, '/' . $league['slug']),
    ], function () use ($league, $events, $total, $direct, $faqs, $config): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= e($league['sport']) ?></p>
                <h1><?= e($league['title']) ?></h1>
                <p class="listing-sub"><?= e($direct) ?></p>
            </div>
        </section>
        <section class="section-band" id="schedule">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>No games on sale right now</h2>
                        <p>New <?= e($league['name']) ?> dates appear here as soon as tickets go on sale.</p>
                        <a class="button-link" href="/teams">Browse teams</a>
                    </div>
                <?php else: ?>
                    <div class="section-heading">
                        <h2>Upcoming <?= e($league['name']) ?> Games</h2>
                        <span class="muted"><?= e((string) $total) ?> on sale</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $league['name'] . ' — Ticket FAQs'); ?>
        <?php
    }, $schemaGraph);
}

/** Hand-curated teams we expose as /team/{slug}. Resolved against TM by exact name. */
function team_seed_list(): array
{
    return [
        // NBA
        ['New York Knicks', 'NBA'], ['Los Angeles Lakers', 'NBA'], ['Boston Celtics', 'NBA'],
        ['Golden State Warriors', 'NBA'], ['Brooklyn Nets', 'NBA'], ['Chicago Bulls', 'NBA'],
        ['Miami Heat', 'NBA'], ['Dallas Mavericks', 'NBA'], ['Denver Nuggets', 'NBA'],
        ['Philadelphia 76ers', 'NBA'], ['Phoenix Suns', 'NBA'], ['Milwaukee Bucks', 'NBA'],
        // MLB
        ['New York Yankees', 'MLB'], ['New York Mets', 'MLB'], ['Boston Red Sox', 'MLB'],
        ['Los Angeles Dodgers', 'MLB'], ['Chicago Cubs', 'MLB'], ['Philadelphia Phillies', 'MLB'],
        ['Atlanta Braves', 'MLB'], ['Houston Astros', 'MLB'], ['San Francisco Giants', 'MLB'],
        ['St. Louis Cardinals', 'MLB'], ['Texas Rangers', 'MLB'], ['Detroit Tigers', 'MLB'],
        // NFL
        ['Dallas Cowboys', 'NFL'], ['Kansas City Chiefs', 'NFL'], ['Philadelphia Eagles', 'NFL'],
        ['San Francisco 49ers', 'NFL'], ['Buffalo Bills', 'NFL'], ['Green Bay Packers', 'NFL'],
        ['New England Patriots', 'NFL'], ['Pittsburgh Steelers', 'NFL'],
        // NHL
        ['New York Rangers', 'NHL'], ['Boston Bruins', 'NHL'], ['Chicago Blackhawks', 'NHL'],
        ['Vegas Golden Knights', 'NHL'], ['Detroit Red Wings', 'NHL'],
    ];
}

/** Map league name → classification id used for /{league} link from a team page. */
function league_slug_for_team(string $sport): ?string
{
    $map = ['NBA' => 'nba', 'NFL' => 'nfl', 'MLB' => 'mlb', 'NHL' => 'nhl', 'MLS' => 'mls'];
    return $map[$sport] ?? null;
}

function tm_team_path(array $team): string
{
    return '/team/' . slugify((string) ($team['name'] ?? 'team'));
}

function team_from_seed(array $config, string $name, string $sport): ?array
{
    $tm = tm_client($config);
    if ($tm === null) {
        return null;
    }
    $raw = api_result(static fn() => $tm->attractions([
        'keyword' => $name,
        'classificationName' => $sport,
        'size' => 5,
        'sort' => 'relevance,desc',
    ]), []);
    foreach ($raw['_embedded']['attractions'] ?? [] as $a) {
        if (strcasecmp((string) ($a['name'] ?? ''), $name) === 0) {
            $team = tm_normalize_attraction($a);
            $team['sport'] = $sport;
            return $team;
        }
    }
    return null;
}

function resolve_seed_team(array $config, string $slug): ?array
{
    foreach (team_seed_list() as [$name, $sport]) {
        if (slugify($name) === $slug) {
            return team_from_seed($config, $name, $sport);
        }
    }
    return null;
}

function render_team_page(array $config, array $team): void
{
    $tm = tm_client($config);
    if ($tm === null || empty($team['tm_id'])) {
        render_error_page($config, 404, 'Team not found', 'This team page is not available.');
        return;
    }

    $raw = api_result(static fn() => $tm->events([
        'attractionId' => (string) $team['tm_id'],
        'size' => 50,
    ]), []);
    $events = array_map('tm_normalize_event', $raw['_embedded']['events'] ?? []);
    $total = (int) ($raw['page']['totalElements'] ?? count($events));

    $name = $team['name'];
    $sport = (string) ($team['sport'] ?? '');
    $leagueSlug = league_slug_for_team($sport);

    $cities = [];
    foreach ($events as $e) {
        $c = trim((string) ($e['venue']['city'] ?? ''));
        if ($c !== '' && !in_array($c, $cities, true)) {
            $cities[] = $c;
        }
    }

    $direct = $events !== []
        ? 'There ' . ($total === 1 ? 'is 1 upcoming ' : 'are ' . number_format($total) . ' upcoming ')
            . $name . ' game' . ($total === 1 ? '' : 's')
            . ($cities !== [] ? ' in ' . implode(', ', array_slice($cities, 0, 4))
                . (count($cities) > 4 ? ' and more cities' : '') : '')
            . '. The full schedule with dates and ticket prices is below.'
        : 'No on-sale ' . $name . ' games right now. New dates appear here as soon as tickets are released.';

    $faqs = [
        ['q' => 'What ' . $name . ' games are coming up?', 'a' => $direct],
        ['q' => 'How much are ' . $name . ' tickets?',
         'a' => 'Prices vary by date, opponent and seat — pick any game on this page to see live prices and seat availability on our partner.'],
        ['q' => 'How often is this ' . $name . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live so this page always reflects what is currently on sale.'],
    ];

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            [
                '@type' => 'SportsTeam',
                'name' => $name,
                'sport' => $sport,
                'url' => absolute_url($config, tm_team_path($team)),
            ],
            $events !== [] ? array_merge(item_list_schema($config, $events, 'event'), ['@context' => null]) : null,
            dubai_faq_schema($faqs),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    render_layout($config, [
        'title' => $name . ' Tickets, Schedule & Upcoming Games | ' . $config['site_name'],
        'description' => $direct,
        'canonical' => absolute_url($config, tm_team_path($team)),
    ], function () use ($name, $sport, $leagueSlug, $events, $total, $direct, $faqs, $config): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= e($sport) ?><?php if ($leagueSlug !== null): ?> · <a href="/<?= e($leagueSlug) ?>" class="muted-link"><?= e(strtoupper($leagueSlug)) ?> schedule</a><?php endif; ?></p>
                <h1><?= e($name) ?> Tickets &amp; Schedule</h1>
                <p class="listing-sub"><?= e($direct) ?></p>
            </div>
        </section>
        <section class="section-band" id="schedule">
            <div class="container">
                <?php if ($events === []): ?>
                    <div class="empty-state">
                        <h2>No games on sale right now</h2>
                        <p>New <?= e($name) ?> dates appear here as soon as tickets are released.</p>
                        <?php if ($leagueSlug !== null): ?>
                            <a class="button-link" href="/<?= e($leagueSlug) ?>">See all <?= e(strtoupper($leagueSlug)) ?> games</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="section-heading">
                        <h2>Upcoming Games</h2>
                        <span class="muted"><?= e((string) $total) ?> on sale</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $name . ' — Ticket FAQs'); ?>
        <?php
    }, $schemaGraph);
}

function render_teams_index(array $config): void
{
    $teams = [];
    foreach (team_seed_list() as [$name, $sport]) {
        $t = team_from_seed($config, $name, $sport);
        if ($t !== null) {
            $teams[] = $t;
        }
    }

    render_layout($config, [
        'title' => 'Top Sports Teams — Tickets & Schedules | ' . $config['site_name'],
        'description' => 'Browse upcoming games for the top NBA, NFL, MLB, NHL and MLS teams. Schedules, opponents, venues and live ticket prices.',
        'canonical' => absolute_url($config, '/teams'),
    ], function () use ($teams): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow">Sports</p>
                <h1>Top Sports Teams</h1>
                <p class="listing-sub">NBA, NFL, MLB, NHL and MLS — pick a team to see every upcoming game and live ticket prices.</p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <?php if ($teams === []): ?>
                    <div class="empty-state"><h2>Loading</h2></div>
                <?php else: ?>
                    <div class="card-grid">
                        <?php foreach ($teams as $t): ?>
                            <article class="ticket-card">
                                <a class="card-image" href="<?= e(tm_team_path($t)) ?>">
                                    <?php if (!empty($t['image'])): ?>
                                        <img src="<?= e($t['image']) ?>" alt="<?= e($t['name']) ?>" loading="lazy">
                                    <?php endif; ?>
                                    <div class="card-rating-strip">
                                        <span class="votes"><?= e($t['sport'] ?? '') ?></span>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <a class="card-title" href="<?= e(tm_team_path($t)) ?>"><?= e($t['name']) ?></a>
                                    <p><?= e((string) ($t['total_performances'] ?? 0)) ?> upcoming games</p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
    });
}
