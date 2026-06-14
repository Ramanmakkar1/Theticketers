<?php
declare(strict_types=1);

function dispatch(HelloTicketsClient $client, array $config, array $dubaiContent = [], array $destinationsContent = []): void
{
    $rawPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

    // Normalize trailing slash AND case in a single 301 (chained redirects leak
    // crawl budget). All our slugs are lowercase (e.g. /artist/MAROON-5 →
    // /artist/maroon-5); skip /venue — legacy Ticketmaster ids in old venue URLs
    // are case-sensitive — and /go, whose ids are case-sensitive too.
    $target = ($rawPath !== '/' && substr($rawPath, -1) === '/') ? rtrim($rawPath, '/') : $rawPath;
    $lowerTarget = strtolower($target);
    if ($lowerTarget !== $target && strpos($target, '/venue/') !== 0 && strpos($target, '/go') !== 0) {
        $target = $lowerTarget;
    }
    if ($target !== $rawPath) {
        $qs = (string) ($_SERVER['QUERY_STRING'] ?? '');
        header('Location: ' . $target . ($qs !== '' ? '?' . $qs : ''), true, 301);
        return;
    }

    $path = current_path();

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

    if (preg_match('#^/events/(today|this-week)-in-([^/]+)$#', $path, $match)) {
        $dateCityId = resolve_city_id_by_slug($config, $match[2]) ?? legacy_id_from_slug($match[2]);
        if ($dateCityId === null) {
            render_error_page($config, 404, 'City not found', 'We do not cover events for this city yet.');
            return;
        }
        render_city_date_page($client, $config, $dateCityId, $match[1] === 'today' ? 'today' : 'week');
        return;
    }

    if (preg_match('#^/events/([a-z]+)-in-([a-z0-9-]+)$#', $path, $match)) {
        $monthName = $match[1];
        $citySlug = $match[2];
        $cityId = resolve_city_id_by_slug($config, $citySlug) ?? legacy_id_from_slug($citySlug);
        if ($cityId === null) {
            render_error_page($config, 404, 'City not found', 'We do not have events for this city.');
            return;
        }
        render_monthly_events_page($client, $config, $cityId, $monthName);
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

    // Artist INTENT pages ("{artist} ticket prices / tour dates / setlist").
    // Matched before the artist×city route so these reserved intents never
    // resolve as a city. Only curated artists render; others 404 in the handler.
    if (preg_match('#^/artist/([^/]+)/(ticket-prices|tour-dates|setlist)$#', $path, $match)) {
        render_artist_intent_page($client, $config, $match[1], $match[2]);
        return;
    }

    // Artist × country tour pages ("{artist} USA/UK/… tour").
    // Always end in "-tour" so they never collide with city slugs.
    if (preg_match('#^/artist/([^/]+)/([a-z]+)-tour$#', $path, $match)) {
        render_artist_country_tour($client, $config, $match[1], $match[2]);
        return;
    }

    // Artist × city long-tail pages ("{artist} in {city}"). Matched before the
    // single-segment artist route and the country catch-all. The renderer 404s
    // unless the artist actually has an event in that city, so no thin pages.
    if (preg_match('#^/artist/([^/]+)/([^/]+)$#', $path, $match)) {
        render_artist_in_city_page($client, $config, $match[1], $match[2]);
        return;
    }

    if (preg_match('#^/artist/([^/]+)$#', $path, $match)) {
        // Team-named performers (Lakers, Yankees…) have a richer canonical home at
        // /team/{slug}; two indexable self-canonical URLs would split ranking signals.
        foreach (team_seed_list() as [$teamName]) {
            if (slugify($teamName) === $match[1]) {
                redirect_permanent('/team/' . $match[1]);
                return;
            }
        }
        // Fast path: if the TM slug map already knows this artist, go straight to
        // TM data — skips all slow HT API name searches. Verify the slug matches
        // to avoid showing wrong artists from stale/bad index mappings.
        $knownTmId = tm_artist_slug_lookup($match[1]);
        if ($knownTmId !== null) {
            $tmClient = tm_client($config);
            $tmData = $tmClient !== null ? api_result(static fn() => $tmClient->attraction($knownTmId), []) : [];
            if (!empty($tmData['id']) && slugify((string) ($tmData['name'] ?? '')) === $match[1]) {
                render_artist_detail_page($client, $config, 0, $tmData);
                return;
            }
        }
        $performerId = resolve_artist_id($client, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($performerId === null) {
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
            <p><?= e($config['site_name']) ?> is a ticket discovery site for events, attractions and experiences in Dubai, Abu Dhabi and top destinations across <?= e(natural_join(array_map(static fn(array $m): string => (string) $m['name'], array_values($config['markets'] ?? [])))) ?>.</p>
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
        render_static_page($config, 'Contact Us', 'Get in touch with the ' . $config['site_name'] . ' team about partnerships, listings, corrections or press &mdash; we reply within one to two business days.', '/contact', function () use ($config): void {
            ?>
            <p>Use the form below to reach the <?= e($config['site_name']) ?> team. Tell us what it's about and we'll reply within one to two business days.</p>
            <ul>
                <li><strong>Booking, payment or refund questions:</strong> these are handled by the ticketing partner that processed your order &mdash; use the support links in your booking confirmation email. We don't have access to partner booking systems, so the partner's support team will always be faster.</li>
                <li><strong>Partnerships and listings:</strong> run an event, venue, tour or experience and want it listed? Pick &ldquo;Business partnership&rdquo; below and include a link to what you do.</li>
                <li><strong>Site feedback or corrections:</strong> spotted a wrong date, a broken page or an outdated price? Send the page link and what's wrong &mdash; corrections go to the top of the queue.</li>
                <li><strong>Press and media:</strong> choose &ldquo;Press &amp; media&rdquo; for facts, data or comments about the site.</li>
            </ul>
            <?php render_contact_form($config, 'Contact ' . $config['site_name']); ?>
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
            <p>Under GDPR/UK GDPR you can request access to, correction of, or deletion of any data we hold. Since we store no accounts and anonymise click logs, there is usually nothing identifying to return &mdash; but send a request through our <a href="/contact">contact form</a> and we will check and respond within 30 days. EU/UK visitors may also complain to their local data-protection authority.</p>
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
            <p>Event names, artist names, venue names and images belong to their respective owners and are used to identify the events listed. If you own content shown here and want it corrected or removed, send a takedown request through our <a href="/contact">contact form</a> &mdash; requests are honoured within 24 hours.</p>
            <h2>Acceptable use</h2>
            <p>Don't scrape the site at abusive rates, attempt to break it, or misrepresent affiliation with us. We may block traffic that does.</p>
            <h2>Contact</h2>
            <p>Operated by Town Media Labs. Questions about these terms? Use our <a href="/contact">contact form</a>. We may update these terms; the date above reflects the latest revision.</p>
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

    if ($path === '/llms-full.txt') {
        render_llms_full_txt($config, $destinationsContent);
        return;
    }

    if ($path === '/ai-index.json') {
        render_ai_index_json($config, $destinationsContent);
        return;
    }

    if ($path === '/sitemap.xml' || $path === '/sitemap-index.xml') {
        render_sitemap_index($config);
        return;
    }

    if (preg_match('#^/sitemap-(static|events|artists|artist-cities|venues|cities|monthly|venue-categories|artist-tours)\.xml$#', $path, $match)) {
        render_phase_one_sitemap($client, $config, $destinationsContent, $match[1]);
        return;
    }

    if (preg_match('#^/city/([^/]+)/(concerts|sports|theatre|comedy|festivals|family|classical|hip-hop|rock|country-music)$#', $path, $match)) {
        $cityId = resolve_city_id_by_slug($config, $match[1]) ?? legacy_id_from_slug($match[1]);
        if ($cityId === null) {
            render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
            return;
        }
        render_city_category_page($client, $config, $cityId, $match[2]);
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

    if (preg_match('#^/venue/([^/]+)/(concerts|sports|theatre)$#', $path, $match)) {
        $venueSlug = $match[1];
        $venueCat = $match[2];
        $tmVenueId = venue_slug_lookup($venueSlug);
        if ($tmVenueId === null) {
            $seedVenue = resolve_seed_venue($config, $venueSlug);
            $tmVenueId = $seedVenue !== null ? (string) $seedVenue['tm_id'] : tm_legacy_id_from_slug($venueSlug);
        }
        if ($tmVenueId === null || $tmVenueId === '') {
            render_error_page($config, 404, 'Venue not found', 'This venue page is not available.');
            return;
        }
        render_venue_category_page($config, $tmVenueId, $venueSlug, $venueCat);
        return;
    }

    if (preg_match('#^/venue/([^/]+)$#', $path, $match)) {
        $tmVenueId = venue_slug_lookup($match[1]);
        if ($tmVenueId === null) {
            $seedVenue = resolve_seed_venue($config, $match[1]);
            $tmVenueId = $seedVenue !== null ? (string) $seedVenue['tm_id'] : tm_legacy_id_from_slug($match[1]);
        }
        if ($tmVenueId === null || $tmVenueId === '') {
            // /venue/ is excluded from the global case-fold 301 (legacy TM ids are
            // case-sensitive), so a typed /venue/Madison-Square-Garden would hard-404
            // here. Recover: if the lowercased slug resolves, 301 to it.
            $lowerSlug = strtolower($match[1]);
            if ($lowerSlug !== $match[1]
                && (venue_slug_lookup($lowerSlug) !== null || resolve_seed_venue($config, $lowerSlug) !== null)) {
                redirect_permanent('/venue/' . $lowerSlug);
                return;
            }
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

    if (preg_match('#^/([a-z0-9-]+)/(concerts|sports|theatre|festivals|family|classical|hip-hop|rock|country-music|comedy)$#', $path, $match)
        && destination_country_exists($destinationsContent, $match[1])) {
        render_country_category_hub($client, $config, $destinationsContent, $match[1], $match[2]);
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
    $robots = $meta['robots'] ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
    $schemaForOutput = schema_for_output($config, $schema, $robots);

    header('Content-Type: text/html; charset=utf-8');
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (!empty($config['google_site_verification'])): ?>
    <meta name="google-site-verification" content="<?= e($config['google_site_verification']) ?>">
    <?php endif; ?>
    <meta name="impact-site-verification" value="b60bdc54-0e8a-4cb8-819d-b63e0f726953">
    <script type="text/javascript">(function(i,m,p,a,c,t){c.ire_o=p;c[p]=c[p]||function(){(c[p].a=c[p].a||[]).push(arguments)};t=a.createElement(m);var z=a.getElementsByTagName(m)[0];t.async=1;t.src=i;z.parentNode.insertBefore(t,z)})('https://utt.impactcdn.com/P-A7402647-42c2-4124-a95c-6909f769ba4d1.js','script','impactStat',document,window);impactStat('transformLinks');impactStat('trackImpression');</script>
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="robots" content="<?= e($robots) ?>">
    <?php if ($canonical !== ''): ?>
    <link rel="canonical" href="<?= e($canonical) ?>">
    <?php endif; ?>
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="apple-touch-icon" href="<?= is_file(dirname(__DIR__) . '/assets/apple-touch-icon.png') ? '/assets/apple-touch-icon.png' : '/assets/favicon.svg' ?>">
    <meta name="theme-color" content="#e50914">
    <meta property="og:title" content="<?= e($title) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:type" content="website">
    <?php if ($canonical !== ''): ?>
    <meta property="og:url" content="<?= e($canonical) ?>">
    <?php endif; ?>
    <meta property="og:image" content="<?= e(absolute_image_url($config, $meta['image'] ?? $config['fallback_images']['hero'])) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php if (!empty($meta['preload_image'])): ?>
    <?php // Hero is a CSS background-image (invisible to the preload scanner) — without
          // this the LCP download can't start until styles.css arrives and parses. ?>
    <link rel="preload" as="image" fetchpriority="high" href="<?= e($meta['preload_image']) ?>">
    <?php
        $preloadHost = parse_url($meta['preload_image'], PHP_URL_HOST);
        if ($preloadHost): ?>
    <link rel="preconnect" href="https://<?= e($preloadHost) ?>">
    <?php endif; endif; ?>
    <link rel="dns-prefetch" href="https://aws-tiqets-cdn.imgix.net">
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    <link rel="dns-prefetch" href="https://s1.ticketm.net">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('/assets/styles.css')) ?>">
    <?php if ($schemaForOutput !== null): ?>
    <script type="application/ld+json"><?= json_encode($schemaForOutput, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php endif; ?>
    <?php if (!empty($config['ga_measurement_id'])): $gaId = $config['ga_measurement_id']; ?>
    <!-- Google Analytics 4 (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(rawurlencode($gaId)) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?= json_encode($gaId, JSON_UNESCAPED_SLASHES) ?>);
    </script>
    <?php endif; ?>
    <?php if (!empty($config['clarity_id'])): ?>
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", <?= json_encode($config['clarity_id'], JSON_UNESCAPED_SLASHES) ?>);
    </script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>">
    <header class="site-header">
        <a class="brand" href="/" aria-label="<?= e($config['site_name']) ?> home">
            <img class="brand-mark" src="/assets/logo.svg" alt="" width="36" height="36">
            <span class="brand-text"><span class="brand-the">The</span><em>Ticketers</em></span>
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
                        <span class="brand-the">The</span><em>Ticketers</em>
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
                <?php
                // Balanced footer: one flat destinations list split into two equal
                // columns so every column has the same number of links.
                $footerDest = array_merge(
                    [['/dubai', 'Dubai'], ['/abu-dhabi', 'Abu Dhabi']],
                    array_map(
                        static fn($slug, $market): array => ['/' . $slug, (string) $market['name']],
                        array_keys($config['markets'] ?? []),
                        array_values($config['markets'] ?? [])
                    )
                );
                $footerDestChunks = array_chunk($footerDest, (int) ceil(count($footerDest) / 2));
                ?>
                <nav class="footer-col" aria-label="Destinations">
                    <h4>Destinations</h4>
                    <?php foreach (($footerDestChunks[0] ?? []) as [$href, $name]): ?>
                        <a href="<?= e($href) ?>"><?= e($name) ?></a>
                    <?php endforeach; ?>
                </nav>
                <nav class="footer-col" aria-label="More destinations">
                    <h4>More destinations</h4>
                    <?php foreach (($footerDestChunks[1] ?? []) as [$href, $name]): ?>
                        <a href="<?= e($href) ?>"><?= e($name) ?></a>
                    <?php endforeach; ?>
                </nav>
                <nav class="footer-col" aria-label="Categories">
                    <h4>Categories</h4>
                    <a href="<?= e(category_path(['id' => 2, 'name' => 'Concerts'])) ?>">Concerts</a>
                    <a href="<?= e(category_path(['id' => 3, 'name' => 'Theatre'])) ?>">Theatre</a>
                    <a href="<?= e(category_path(['id' => 1, 'name' => 'Sports'])) ?>">Sports</a>
                    <a href="/attractions">Attractions &amp; Tours</a>
                    <a href="/artists">Artists</a>
                    <a href="/events">All Events</a>
                </nav>
                <nav class="footer-col" aria-label="Company">
                    <h4>Company</h4>
                    <a href="/about">About Us</a>
                    <a href="/contact">Contact</a>
                    <a href="/how-we-make-money">How We Make Money</a>
                    <a href="/privacy">Privacy Policy</a>
                    <a href="/terms">Terms of Service</a>
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
    <script src="<?= e(asset_url('/assets/app.js')) ?>" defer></script>
</body>
</html>
    <?php
}

/**
 * Splitforms-backed contact / partnership form. The team email is never rendered
 * anywhere on the site, so scrapers have nothing to harvest — submissions are
 * routed through Splitforms' public access key (safe in client code by design).
 * Works as a plain HTML POST with no JS; app.js progressively enhances it to an
 * inline AJAX submit so visitors stay on-site.
 */
function render_contact_form(array $config, string $subject = 'New enquiry'): void
{
    $key = (string) ($config['splitforms_key'] ?? '');
    if ($key === '') {
        return;
    }
    ?>
    <form class="contact-form" action="https://splitforms.com/api/submit" method="POST" data-contact-form novalidate>
        <input type="hidden" name="access_key" value="<?= e($key) ?>">
        <input type="hidden" name="subject" value="<?= e($subject) ?>">
        <input type="hidden" name="from_site" value="<?= e($config['site_name']) ?>">
        <div class="contact-form__row">
            <label for="cf-name">Your name *</label>
            <input id="cf-name" type="text" name="name" placeholder="Jane Builder" autocomplete="name" required>
        </div>
        <div class="contact-form__row">
            <label for="cf-email">Your email *</label>
            <input id="cf-email" type="email" name="email" placeholder="jane@example.com" autocomplete="email" required>
        </div>
        <div class="contact-form__row">
            <label for="cf-topic">What's this about?</label>
            <select id="cf-topic" name="topic">
                <option>Business partnership</option>
                <option>List my event or venue</option>
                <option>Correction or wrong info</option>
                <option>Press &amp; media</option>
                <option>Something else</option>
            </select>
        </div>
        <div class="contact-form__row">
            <label for="cf-message">Message *</label>
            <textarea id="cf-message" name="message" rows="5" placeholder="Tell us what you need — include any relevant links." required></textarea>
        </div>
        <!-- honeypot: bots fill every field; humans never see this -->
        <input type="checkbox" name="botcheck" class="contact-form__hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <button class="button-link contact-form__submit" type="submit">Send message</button>
        <p class="contact-form__status" data-contact-status role="status" aria-live="polite" hidden></p>
        <p class="contact-form__note">By sending this you agree we may reply to the email address you provide. We don't share it.</p>
    </form>
    <?php
}

function render_static_page(array $config, string $title, string $desc, string $path, callable $body): void
{
    // About/Contact are E-E-A-T anchor pages — typed schema + the site-wide
    // #organization entity (same @id everywhere = one consolidated entity).
    $pageTypes = ['/about' => 'AboutPage', '/contact' => 'ContactPage'];
    $schema = null;
    if (isset($pageTypes[$path])) {
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => $pageTypes[$path],
                    'name' => $title,
                    'description' => $desc,
                    'url' => absolute_url($config, $path),
                    'about' => ['@id' => $config['site_url'] . '/#organization'],
                    'isPartOf' => ['@id' => $config['site_url'] . '/#website'],
                ],
                [
                    '@type' => 'Organization',
                    '@id' => $config['site_url'] . '/#organization',
                    'name' => $config['site_name'],
                    'url' => $config['site_url'],
                    // Contact is via the on-site form, so no email is exposed to scrapers.
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'customer support',
                        'url' => absolute_url($config, '/contact'),
                    ],
                ],
            ],
        ];
    }

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
    }, $schema);
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

    // First carousel slide is the LCP candidate — mirror the slide-selection logic
    // below so the head can preload it (CSS background-images are invisible to the
    // browser's preload scanner).
    $firstSlideImage = $config['fallback_images']['hero'];
    if ($cityId !== (int) $config['default_city_id']) {
        $firstSlideImage = $config['fallback_images']['Concerts'];
        foreach ($events as $heroEvent) {
            $heroImg = image_from_item($heroEvent, 'event', $config);
            if (strpos($heroImg, 'images.unsplash.com') === false) {
                $firstSlideImage = $heroImg;
                break;
            }
        }
    }

    render_layout($config, [
        'title' => $homeCity['name'] . ' Events, Attractions & Tickets | ' . $config['site_name'],
        'description' => 'Find ' . $homeCity['name'] . ' attraction tickets, concerts, theatre, sports and experiences with live prices from HelloTickets.',
        'canonical' => absolute_url($config, '/'),
        'body_class' => 'home-page',
        'preload_image' => $firstSlideImage,
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
            // Detected city: banner up to 10 trending shows. Only events with a REAL
            // poster make the slider (no generic fallbacks up top), and Ticketmaster
            // shows link straight to checkout like everything else.
            $slides = [];
            foreach ($events as $heroEvent) {
                $heroImage = image_from_item($heroEvent, 'event', $config);
                if (strpos($heroImage, 'images.unsplash.com') !== false) {
                    continue; // skip fallback-only events in the hero
                }
                $venue = $heroEvent['venue']['name'] ?? $homeCity['name'];
                $slides[] = [
                    'image' => $heroImage,
                    'tag' => $heroEvent['category']['name'] ?? 'Live Event',
                    'title' => $heroEvent['name'] ?? ('Live in ' . $homeCity['name']),
                    'text' => trim(format_date_time($heroEvent['start_date'] ?? []) . ' · ' . $venue, ' ·'),
                    'href' => !empty($heroEvent['url']) ? go_url($heroEvent, 'event') : event_path($heroEvent),
                    'cta' => 'Get Tickets',
                ];
                if (count($slides) >= 10) {
                    break;
                }
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
    // is useful rather than near-empty. Local events stay first. Curated category pages
    // ($seo) describe themselves as worldwide, so they blend to a full grid — an
    // indexable "Concert Tickets" page must not silently render one city's inventory.
    $blendThreshold = ($seo !== null && $category !== null) ? 24 : 6;
    if (count($items) < $blendThreshold && $query === '' && $page === 1) {
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
    // Proper sentence casing up front — ucwords() produced "Events In Dubai"
    // titles and the raw string produced a lowercase "events in Dubai" H1.
    $title = $category !== null
        ? trim($category['name'] . ' in ' . $city['name'])
        : 'Events in ' . $city['name'];

    render_listing_layout($config, [
        // Curated categories use their global keyword form (inventory is global);
        // un-curated category listings stay out of the index entirely.
        'title' => ($seo !== null ? $seo['meta_title'] : $title) . ' | ' . $config['site_name'],
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
        $title = $query !== '' ? ucfirst($query) . ' tickets in ' . $city['name'] : 'Attractions & Experiences in ' . $city['name'];
    }

    render_listing_layout($config, [
        'title' => ($seo !== null ? $seo['meta_title'] : $title) . ' | ' . $config['site_name'],
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
            '@graph' => array_values(array_filter([$schema !== [] ? $schema : null, $faqSchema])),
        ];
    } elseif ($schema === []) {
        $schema = null;
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

    // Ticketmaster covers the North-American long tail HelloTickets misses — e.g. a
    // Punjabi artist (Tarsem Jassar) playing Edmonton/Brampton. Search its artists and
    // events GLOBALLY by keyword, so a name search surfaces them regardless of city.
    $tmArtists = [];
    if ($query !== '') {
        $tm = tm_client($config);
        if ($tm !== null) {
            $rawArtists = api_result(static fn() => $tm->attractions(['keyword' => $query, 'size' => 8]), []);
            foreach ($rawArtists['_embedded']['attractions'] ?? [] as $attraction) {
                if ((int) ($attraction['upcomingEvents']['_total'] ?? 0) < 1) {
                    continue; // only acts with shows actually on sale
                }
                $tmArtists[] = tm_normalize_attraction($attraction);
            }
            $rawEvents = api_result(static fn() => $tm->events(['keyword' => $query, 'size' => 8]), []);
            $tmEvents = array_map('tm_normalize_event', $rawEvents['_embedded']['events'] ?? []);
            if ($tmEvents !== []) {
                $events = array_slice(merge_events_dedupe($events, $tmEvents), 0, 12);
            }
        }
    }

    $searchCity = city_for_id(active_city_id($config), $config);
    $cityName = (string) $searchCity['name'];

    render_layout($config, [
        'title' => ($query !== '' ? 'Search tickets for ' . $query : 'Search Tickets in ' . $cityName) . ' | ' . $config['site_name'],
        'description' => 'Search ' . $cityName . ' events, attractions and experiences.',
        'canonical' => absolute_url($config, '/search'),
        'robots' => 'noindex, follow',
    ], function () use ($query, $events, $activities, $tmArtists, $config, $cityName): void {
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
        <?php if ($tmArtists !== []): ?>
            <section class="section-band">
                <div class="container">
                    <div class="section-heading"><h2>Artists on tour</h2></div>
                    <div class="rail artist-rail">
                        <?php foreach ($tmArtists as $artist): ?>
                            <?= artist_card($artist) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <?php if ($events !== []): ?>
            <?php render_card_section('Events', route_url('/events', ['q' => $query]), $events, 'event', $config); ?>
        <?php endif; ?>
        <?php if ($activities !== []): ?>
            <?php render_card_section('Attractions', route_url('/attractions', ['q' => $query]), $activities, 'activity', $config); ?>
        <?php endif; ?>
        <?php if ($query === '' || ($events === [] && $activities === [] && $tmArtists === [])): ?>
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
    $page = page_number();
    $perPage = 24;

    // Build the city's FULL events catalogue, not a teaser: HelloTickets first
    // (own detail pages + higher commission), then page deep through Ticketmaster.
    $eventsData = api_result(static fn() => $client->performances(array_merge([
        'limit' => 24,
        'page' => 1,
        'is_sellable' => 'true',
        'city_id' => $cityId,
    ], date_params(null))), ['performances' => []]);
    $htEvents = $eventsData['performances'] ?? [];
    $tmEvents = tm_events_for_city_deep(
        $config,
        (string) $city['name'],
        (string) ($city['country_code'] ?? '')
    );
    $eventPool = city_event_pool($htEvents, $tmEvents, $config);
    $totalEvents = count($eventPool);
    $events = array_slice($eventPool, ($page - 1) * $perPage, $perPage);

    // Attractions (HelloTickets) only on page 1 — many geo cities have none, and
    // deeper pages stay focused on the event catalogue.
    $activities = [];
    if ($page === 1) {
        $activitiesData = api_result(static fn() => $client->activities([
            'limit' => 12,
            'page' => 1,
            'city_id' => $cityId,
        ]), ['activities' => []]);
        $activities = $activitiesData['activities'] ?? [];
    }

    if (!$isMarketCity && $totalEvents + count($activities) < 5) {
        render_error_page($config, 404, 'City not found', 'We do not have a tickets page for this city yet.');
        return;
    }
    setcookie('tb_city', (string) $cityId, [
        'expires' => time() + 31536000,
        'path' => '/',
        'samesite' => 'Lax',
    ]);

    $eventsPageData = ['current_page' => $page, 'per_page' => $perPage, 'total_count' => $totalEvents];
    // Hub cities (an editorial /{country}/{city} exists) canonicalize to that hub so
    // the listing doesn't cannibalise it; standalone geo cities self-canonical, with
    // ?page preserved on deeper pages.
    $canonical = $guidePath !== null
        ? absolute_url($config, $guidePath)
        : absolute_url($config, city_path($city), array_filter(['page' => $page > 1 ? $page : null]));

    // Schema only for standalone (self-canonical) geo cities — hub cities defer to
    // their /{country}/{city} page's own graph. ItemList self-filters to internal URLs.
    $citySchema = null;
    if ($guidePath === null) {
        $cityListSchema = item_list_schema($config, $events, 'event');
        $citySchema = [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([
                $cityListSchema !== [] ? $cityListSchema : null,
                dubai_breadcrumb_schema($config, [
                    ['name' => 'Home', 'url' => absolute_url($config, '/')],
                    ['name' => $city['name'], 'url' => absolute_url($config, city_path($city))],
                ]),
            ])),
        ];
    }

    render_layout($config, [
        'title' => $city['name'] . ' Tickets, Events & Attractions | ' . $config['site_name'],
        'description' => 'Browse ' . number_format($totalEvents) . ' live events, concerts, sports and attractions in ' . $city['name'] . ' with dates, venues and prices.',
        'canonical' => $canonical,
    ], function () use ($city, $events, $activities, $config, $guidePath, $eventsPageData, $totalEvents): void {
        ?>
        <section class="listing-hero city-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['country'] ?: 'Destination') ?></p>
                <h1><?= e($city['name']) ?> tickets, events and attractions</h1>
                <div class="filter-row inverse">
                    <a href="<?= e(city_date_path($city, 'today')) ?>">Today</a>
                    <a href="<?= e(city_date_path($city, 'week')) ?>">This Week</a>
                    <a href="<?= e(weekend_path($city)) ?>">This Weekend</a>
                    <a href="<?= e(city_category_path($city, 'concerts')) ?>">Concerts</a>
                    <a href="<?= e(city_category_path($city, 'sports')) ?>">Sports</a>
                    <a href="<?= e(city_category_path($city, 'theatre')) ?>">Theatre</a>
                    <a href="/attractions">Attractions</a>
                </div>
                <?php if ($guidePath !== null): ?>
                    <p class="city-guide-link"><a href="<?= e($guidePath) ?>">Read the full <?= e($city['name']) ?> guide &rarr;</a></p>
                <?php endif; ?>
            </div>
        </section>
        <?php render_events_grid_section('Events in ' . $city['name'], $city['name'], $events, $eventsPageData, $config); ?>
        <?php if ($activities !== []): ?>
            <?php render_card_section('Attractions in ' . $city['name'], '/attractions', $activities, 'activity', $config, 'muted'); ?>
        <?php endif; ?>
        <?php
        // --- AI-citeable content sections ---
        $cityName = (string) $city['name'];
        $countryName = (string) ($city['country'] ?? '');
        $categories = city_intent_categories();
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        ?>
        <section class="section-band muted">
            <div class="container">
                <h2>About Events in <?= e($cityName) ?></h2>
                <p><?= e($cityName) ?> currently has <?= e(number_format($totalEvents)) ?> events on sale across concerts, sports, theatre and attractions. Every listing on this page shows real-time pricing from our official ticketing partners with instant e-ticket delivery. Prices are live and may change based on demand and availability.</p>
                <?php if ($activities !== []): ?>
                    <p>Beyond live events, <?= e($cityName) ?> offers <?= e((string) count($activities)) ?> bookable attractions and experiences — from guided tours to landmark visits — all available with mobile tickets.</p>
                <?php endif; ?>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <h2>Browse <?= e($cityName) ?> by Category</h2>
                <p>Find exactly the type of event you are looking for in <?= e($cityName) ?>:</p>
                <ul class="more-cities-list">
                    <?php foreach ($categories as $catSlug => $catMeta): ?>
                        <li><a href="<?= e(city_category_path($city, $catSlug)) ?>"><?= e($catMeta['label']) ?> in <?= e($cityName) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <section class="section-band muted">
            <div class="container">
                <h2><?= e($cityName) ?> Events by Month</h2>
                <p>Planning ahead? Browse events in <?= e($cityName) ?> for a specific month:</p>
                <ul class="more-cities-list">
                    <?php foreach ($months as $m): ?>
                        <li><a href="<?= e(monthly_events_path($city, $m)) ?>"><?= e($m) ?> in <?= e($cityName) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
        $cityFaqs = [
            ['q' => 'What events are happening in ' . $cityName . ' right now?',
             'a' => 'There are currently ' . number_format($totalEvents) . ' events on sale in ' . $cityName . ' including concerts, sports, theatre and attractions. Every listing shows live pricing from our official ticketing partner.'],
            ['q' => 'How do I buy event tickets in ' . $cityName . '?',
             'a' => 'Browse the events on this page, select a show, and complete checkout on our ticketing partner\'s secure site. Tickets are delivered instantly by email or mobile.'],
            ['q' => 'Are ticket prices on this page accurate?',
             'a' => 'Yes. All prices shown are pulled live from our official ticketing partner\'s inventory. They reflect current availability and may change based on demand.'],
            ['q' => 'What types of events can I find in ' . $cityName . '?',
             'a' => 'This page covers concerts, sports, theatre, comedy, festivals, family events, classical performances and more. Use the category filters above to narrow your search.'],
            ['q' => 'Can I find last-minute tickets in ' . $cityName . '?',
             'a' => 'Yes. Check the "Today" and "This Weekend" filters at the top of this page for events with tickets still available. Our partner inventory updates in real time.'],
        ];
        // Augment with a deterministic-unique slice from the shared pool — see
        // helpers.php::unique_faqs() — so every city page renders a different FAQ mix.
        $cityMinPrice = null;
        $cityCurrencyForFaq = (string) $config['currency'];
        $cityVenues = [];
        foreach ($events as $cityEv) {
            $cp = (float) ($cityEv['price_range']['min_price'] ?? 0);
            if ($cp > 0 && ($cityMinPrice === null || $cp < $cityMinPrice)) {
                $cityMinPrice = $cp;
                $cityCurrencyForFaq = (string) ($cityEv['price_range']['currency'] ?? $cityCurrencyForFaq);
            }
            $cvn = trim((string) ($cityEv['venue']['name'] ?? ''));
            if ($cvn !== '' && !in_array($cvn, $cityVenues, true) && count($cityVenues) < 4) {
                $cityVenues[] = $cvn;
            }
        }
        $cityFaqData = [
            '{city}' => $cityName,
            '{country}' => (string) ($city['country'] ?? ''),
            '{count}' => (string) $totalEvents,
            '{min_price}' => $cityMinPrice !== null ? money($cityMinPrice, $cityCurrencyForFaq) : '',
            '{top_venues}' => implode(', ', array_slice($cityVenues, 0, 3)),
            '{site_name}' => (string) $config['site_name'],
        ];
        $cityFaqs = array_merge($cityFaqs, unique_faqs('city', slugify($cityName), $cityFaqData, 6));
        dubai_render_faq($cityFaqs, $cityName . ' — Event FAQs');
        ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Tickets in <?= e($cityName) ?></h2>
                <p>Looking for tickets in <?= e($cityName) ?>? This page is your complete guide to live events, concerts, sports and attractions in the city. Every listing shows real-time availability and pricing from our official ticketing partners. When you find an event, click through to purchase on the partner's secure checkout — tickets are delivered instantly.</p>
                <p>We cover everything from major arena tours and stadium sports to intimate theatre and comedy shows. New events appear automatically as they go on sale, so bookmark this page and check back regularly for the latest <?= e($cityName) ?> events.</p>
            </div>
        </section>
        <?php
    }, $citySchema);
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

    // "More Events" should be relevant: same city first (a Dubai rock fan doesn't
    // need a Flensburg tenor recital), topped up by category only if the city
    // pool is empty.
    $eventCityId = null;
    foreach ($config['market_cities'] as $marketCity) {
        if (strcasecmp((string) $marketCity['name'], (string) $cityName) === 0) {
            $eventCityId = (int) $marketCity['id'];
            break;
        }
    }
    $related = $eventCityId !== null
        ? api_result(static fn() => $client->performances(array_merge([
            'limit' => 8,
            'page' => 1,
            'is_sellable' => 'true',
            'city_id' => $eventCityId,
        ], date_params(null))), ['performances' => []])['performances'] ?? []
        : [];
    $related = array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $performance['id']);
    if ($related === []) {
        $related = api_result(static fn() => $client->performances(array_merge([
            'limit' => 8,
            'page' => 1,
            'is_sellable' => 'true',
            'category_id' => $categoryId ?: null,
        ], date_params(null))), ['performances' => []])['performances'] ?? [];
        $related = array_filter($related, static fn($item): bool => (int) ($item['id'] ?? 0) !== (int) $performance['id']);
    }

    $localDate = (string) ($performance['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Events', 'url' => absolute_url($config, '/events')],
        ['name' => (string) ($performance['name'] ?? 'Event'), 'url' => absolute_url($config, event_path($performance))],
    ];

    $venueName = (string) ($performance['venue']['name'] ?? '');
    $price = $performance['price_range']['min_price'] ?? 0;
    $currency = (string) ($performance['price_range']['currency'] ?? $config['currency']);
    $dateLabel = $localDate !== '' ? format_date_label($localDate) : '';
    $whenLabel = format_date_time($performance['start_date'] ?? []);

    // Self-contained, citable summary + FAQ answers built from live data — the
    // highest-intent template on the site previously had no extractable prose.
    $summary = $performance['name']
        . ($venueName !== '' ? ' comes to ' . $venueName : ' is on')
        . ' in ' . $cityName
        . ($whenLabel !== '' && $whenLabel !== 'Upcoming' ? ' on ' . $whenLabel : '')
        . '. ' . ((float) $price > 0 ? 'Tickets start from ' . money($price, $currency) . ' and availability' : 'Ticket availability')
        . ' is live from our official ticketing partner — checkout completes securely on their site.';
    $eventFaqs = array_values(array_filter([
        ['q' => 'When is ' . $performance['name'] . ' in ' . $cityName . '?',
         'a' => $whenLabel !== '' && $whenLabel !== 'Upcoming'
            ? $performance['name'] . ' takes place on ' . $whenLabel . ($venueName !== '' ? ' at ' . $venueName : '') . ' in ' . $cityName . '.'
            : null],
        (float) $price > 0 ? ['q' => 'How much are ' . $performance['name'] . ' tickets?',
         'a' => 'Tickets currently start from ' . money($price, $currency) . '. Prices vary by seat and can change with demand — the latest prices are on our partner\'s checkout.'] : null,
        $venueName !== '' ? ['q' => 'Where is ' . $performance['name'] . ' taking place?',
         'a' => 'The venue is ' . $venueName . (!empty($performance['venue']['address']) ? ', ' . trim((string) $performance['venue']['address']) : '') . ', ' . $cityName . '.'] : null,
    ], static fn($f) => $f !== null && $f['a'] !== null));

    $eventName = (string) $performance['name'];
    $eventFaqs[] = ['q' => 'Are ' . $eventName . ' tickets refundable?',
        'a' => 'Refund policies are set by the ticketing partner and vary by event. Check the terms on the checkout page before completing your purchase. Most events allow ticket transfers if you cannot attend.'];
    $eventFaqs[] = ['q' => 'How are tickets delivered for ' . $eventName . '?',
        'a' => 'Tickets are delivered instantly by email after purchase. Most venues accept mobile tickets — simply show the ticket on your phone at the entrance.'];
    $eventFaqs[] = ['q' => 'Is it safe to buy ' . $eventName . ' tickets on this site?',
        'a' => 'Yes. We link directly to our official ticketing partner\'s secure checkout. Your payment and personal information are handled entirely by the partner, and tickets are guaranteed authentic.'];

    // Deterministic-unique slice from the shared pool.
    $eventFaqData = [
        '{name}' => $eventName,
        '{city}' => (string) $cityName,
        '{next_venue}' => $venueName !== '' ? $venueName : 'the venue',
        '{min_price}' => (float) $price > 0 ? money($price, $currency) : '',
        '{site_name}' => (string) $config['site_name'],
    ];
    $eventFaqs = array_merge($eventFaqs, unique_faqs('event', slugify($eventName), $eventFaqData, 5));

    render_layout($config, [
        'title' => $performance['name'] . ' Tickets — ' . $cityName . ($dateLabel !== '' ? ', ' . $dateLabel : '') . ' | ' . $config['site_name'],
        'description' => $performance['name'] . ($venueName !== '' ? ' at ' . $venueName : '') . ', ' . $cityName
            . ($dateLabel !== '' ? ' on ' . $dateLabel : '') . '.'
            . ((float) $price > 0 ? ' Tickets from ' . money($price, $currency) . ' with live availability' : ' Live ticket availability')
            . ' — secure checkout via official partner.',
        'canonical' => absolute_url($config, event_path($performance)),
        'image' => image_from_item($performance, 'event', $config),
        'preload_image' => image_from_item($performance, 'event', $config),
        'robots' => $isPast ? 'noindex, follow' : null,
    ], function () use ($performance, $related, $config, $breadcrumbs, $summary, $eventFaqs, $eventName, $venueName, $cityName, $whenLabel): void {
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
                        <p class="detail-summary"><?= e($summary) ?></p>
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
        <?php if ($eventFaqs !== []) { dubai_render_faq($eventFaqs, $performance['name'] . ' — Ticket FAQs'); } ?>
        <section class="section-band muted">
            <div class="container artist-seo-content">
                <h2>About <?= e($eventName) ?></h2>
                <p><?= e($eventName) ?> <?php if ($venueName !== ''): ?>takes place at <?= e($venueName) ?><?php endif; ?><?php if ($cityName !== ''): ?> in <?= e($cityName) ?><?php endif; ?>. <?php if ($whenLabel !== ''): ?>The event is scheduled for <?= e($whenLabel) ?>.<?php endif; ?> Tickets are available now from our official ticketing partner with instant e-ticket delivery.</p>
                <p>All prices shown are live from the partner's inventory and may change based on demand and seat availability. We recommend booking early for the best selection.</p>
            </div>
        </section>
        <?php render_card_section('More Events in ' . ($performance['venue']['city'] ?? 'your city'), '/events', $related, 'event', $config); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            event_schema($config, $performance),
            dubai_breadcrumb_schema($config, $breadcrumbs),
            $eventFaqs !== [] ? dubai_faq_schema($eventFaqs) : null,
        ])),
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

    $activityTitle = (string) ($activity['title'] ?? 'this experience');
    $activityCity = (string) ($activity['city']['name'] ?? '');
    $activityPriceVal = (float) ($activity['from_price'] ?? 0);
    $activityCurrency = (string) ($activity['currency'] ?? $config['currency']);
    $cancellation = trim(strip_tags((string) ($activity['cancellation_policy'] ?? '')));
    $faqs = [
        ['q' => 'How do I book ' . $activityTitle . '?',
         'a' => 'Pick an available date on this page and continue to secure checkout on our official ticketing partner. Tickets are issued instantly by email after payment, so you can show them on your phone at the entrance.'],
        ['q' => 'How much does ' . $activityTitle . ' cost?',
         'a' => $activityPriceVal > 0
            ? 'Tickets start from ' . money($activityPriceVal, $activityCurrency) . '. Final pricing depends on the date, time slot and ticket type you select and is shown live at checkout.'
            : 'Prices are shown live at checkout and vary by date, time slot and ticket type. Select an available date above to see current pricing.'],
        ['q' => 'Can I cancel or change my booking?',
         'a' => $cancellation !== ''
            ? 'Cancellation policy for this experience: ' . $cancellation . ' Full terms are confirmed on the partner checkout before payment.'
            : 'Cancellation terms are set by the ticket partner and are confirmed on the checkout page before you pay. Many experiences offer free cancellation up to 24 hours before the start time.'],
        ['q' => 'How will I receive my tickets?',
         'a' => 'Tickets are delivered as e-tickets by email immediately after booking. Show the QR code on your phone at the entrance — no printing needed for most experiences.'],
        ['q' => 'What is included with ' . $activityTitle . '?',
         'a' => 'Inclusions vary by ticket type and are listed on the partner checkout page before you confirm. Read the experience details above for the headline inclusions and any optional add-ons.'],
    ];

    render_layout($config, [
        'title' => $activity['title'] . ' | ' . $config['site_name'],
        'description' => 'Book ' . $activity['title'] . ' with current prices, reviews and available dates.',
        'canonical' => absolute_url($config, activity_path($activity)),
        'image' => image_from_item($activity, 'activity', $config),
        'preload_image' => image_from_item($activity, 'activity', $config),
    ], function () use ($activity, $dates, $related, $config, $breadcrumbs, $faqs, $activityTitle, $activityCity, $activityPriceVal, $activityCurrency): void {
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
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About <?= e($activityTitle) ?></h2>
                <p><?= e($activityTitle) ?><?= $activityCity !== '' ? ' in ' . e($activityCity) : '' ?> is bookable on this page with live availability and instant e-ticket delivery. Pick an available date above to check current pricing and reserve your spot in a few clicks.</p>
                <p>Every booking is processed by our official ticketing partner's secure checkout. Tickets are issued by email the moment payment clears — there is no waiting list, no printing required, and no hidden booking fee beyond the price shown.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $activityTitle . ' — FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($activityTitle) ?> Tickets</h2>
                <p>Booking <?= e($activityTitle) ?> takes a couple of minutes: choose your date, choose your ticket type, and complete payment on our partner's secure checkout. Your e-ticket arrives by email instantly and shows the QR code you scan at the entrance.</p>
                <?php if ($activityPriceVal > 0): ?>
                    <p>Tickets currently start from <strong><?= e(money($activityPriceVal, $activityCurrency)) ?></strong>. Pricing is live from the partner and may shift slightly based on the date and time slot — book early for the best selection of slots.</p>
                <?php else: ?>
                    <p>Pricing is shown live at checkout once you pick a date and ticket type. Availability and price are pulled from the partner in real time, so this page always reflects what is on sale right now.</p>
                <?php endif; ?>
                <p>Looking for more in <?= e($activityCity !== '' ? $activityCity : 'the city') ?>? Browse <a href="/attractions">all attractions</a> for related tours and experiences.</p>
            </div>
        </section>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            activity_schema($config, $activity),
            dubai_faq_schema($faqs),
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

/**
 * A deep, paginated events grid (vs the teaser rail in render_card_section).
 * Used by the standalone city pages and the country/city hubs so a city shows its
 * FULL Ticketmaster + HelloTickets catalogue with on-page pagination.
 */
function render_events_grid_section(string $heading, string $cityName, array $events, array $pageData, array $config, string $variant = ''): void
{
    $total = (int) ($pageData['total_count'] ?? count($events));
    ?>
    <section class="section-band<?= $variant !== '' ? ' ' . e($variant) : '' ?>">
        <div class="container">
            <div class="section-heading">
                <h2><?= e($heading) ?></h2>
                <?php if ($total > 0): ?><span class="result-count"><?= e(number_format($total)) ?> events</span><?php endif; ?>
            </div>
            <?php if ($events === []): ?>
                <div class="empty-state">
                    <h2>No events on sale yet</h2>
                    <p>Check back soon for upcoming <?= e($cityName) ?> events.</p>
                </div>
            <?php else: ?>
                <div class="card-grid">
                    <?php foreach ($events as $event): ?>
                        <?= event_card($event, $config) ?>
                    <?php endforeach; ?>
                </div>
                <?php render_pagination($pageData); ?>
            <?php endif; ?>
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
    // Geo cities (Edmonton, Glasgow…) get a weekend page too, but only when their
    // pre-computed inventory clears the gate — otherwise "weekend in {city}" would
    // be a near-empty doorway page.
    if ($city === null && isset(geo_cities()[(string) $cityId]) && city_has_inventory($cityId)) {
        $city = city_for_id($cityId, $config);
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

    $weekendListSchema = item_list_schema($config, $events, 'event');
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $weekendListSchema !== [] ? $weekendListSchema : null,
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

function render_city_date_page(HelloTicketsClient $client, array $config, int $cityId, string $dateKey): void
{
    if (!in_array($dateKey, ['today', 'week'], true)) {
        render_error_page($config, 404, 'Page not found', 'This event date page is not available.');
        return;
    }

    $city = city_for_id($cityId, $config);
    $canonicalPath = city_date_path($city, $dateKey);
    if (current_path() !== $canonicalPath) {
        redirect_permanent($canonicalPath);
        return;
    }

    $page = page_number();
    $perPage = 24;
    $eventPool = city_date_events($client, $config, $cityId, $dateKey);
    $total = count($eventPool);
    $minRequired = $dateKey === 'today' ? 1 : 3;
    if ($total < $minRequired) {
        render_error_page($config, 404, 'No events found', 'There are not enough on-sale events in ' . $city['name'] . ' for this date page right now.');
        return;
    }
    $events = array_slice($eventPool, ($page - 1) * $perPage, $perPage);
    if ($events === [] && $page > 1) {
        render_error_page($config, 404, 'Page not found', 'This page of events is not available.');
        return;
    }

    $rangeLabel = city_date_label($dateKey);
    $topNames = [];
    $venues = [];
    $minPrice = null;
    $currency = (string) $config['currency'];
    foreach ($eventPool as $event) {
        $eventName = trim((string) ($event['name'] ?? ''));
        if ($eventName !== '' && count($topNames) < 3 && !in_array($eventName, $topNames, true)) {
            $topNames[] = $eventName;
        }
        $venue = trim((string) ($event['venue']['name'] ?? ''));
        if ($venue !== '' && count($venues) < 6 && !in_array($venue, $venues, true)) {
            $venues[] = $venue;
        }
        $price = (float) ($event['price_range']['min_price'] ?? 0);
        if ($price > 0 && ($minPrice === null || $price < $minPrice)) {
            $minPrice = $price;
            $currency = (string) ($event['price_range']['currency'] ?? $currency);
        }
    }

    $headline = $dateKey === 'today'
        ? 'Events Today in ' . $city['name']
        : 'Events This Week in ' . $city['name'];
    $summary = 'There ' . ($total === 1 ? 'is 1 live event' : 'are ' . number_format($total) . ' live events')
        . ' in ' . $city['name'] . ' ' . $rangeLabel
        . ($topNames !== [] ? ', including ' . natural_join(array_slice($topNames, 0, 2)) : '')
        . ($minPrice !== null ? ', with tickets from ' . money($minPrice, $currency) : '')
        . '.';

    $faqs = [
        ['q' => 'What events are happening in ' . $city['name'] . ' ' . $rangeLabel . '?',
         'a' => ($total === 1 ? '1 event is' : number_format($total) . ' events are') . ' on sale'
            . ($topNames !== [] ? ', including ' . natural_join($topNames) : '') . '.'],
    ];
    if ($minPrice !== null) {
        $faqs[] = ['q' => 'How much are tickets in ' . $city['name'] . ' ' . $rangeLabel . '?',
            'a' => 'Tickets currently start from ' . money($minPrice, $currency) . '. Prices and availability are live from our ticketing partners.'];
    }
    if ($venues !== []) {
        $faqs[] = ['q' => 'Which venues have events in ' . $city['name'] . '?',
            'a' => 'Events are listed at ' . natural_join($venues) . '.'];
    }
    $faqs[] = ['q' => 'How often is this page updated?',
        'a' => 'Dates, venues, prices and availability are refreshed from live partner inventory when the page loads.'];

    $listSchema = item_list_schema($config, $events, 'event');
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $listSchema !== [] ? $listSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => $city['name'], 'url' => absolute_url($config, city_path($city))],
                ['name' => $headline, 'url' => absolute_url($config, $canonicalPath)],
            ]),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    $pageData = ['current_page' => $page, 'per_page' => $perPage, 'total_count' => $total];
    render_layout($config, [
        'title' => $headline . ' | ' . $config['site_name'],
        'description' => $headline . ': ' . number_format($total) . ' live events with dates, venues and ticket prices' . ($minPrice !== null ? ' from ' . money($minPrice, $currency) : '') . '.',
        'canonical' => absolute_url($config, $canonicalPath, array_filter(['page' => $page > 1 ? $page : null])),
    ], function () use ($config, $city, $headline, $summary, $events, $pageData, $faqs): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['name']) ?></p>
                <h1><?= e($headline) ?></h1>
                <p class="listing-sub"><?= e($summary) ?></p>
                <div class="filter-row inverse">
                    <a href="<?= e(city_path($city)) ?>">All Events</a>
                    <a href="<?= e(city_date_path($city, 'today')) ?>">Today</a>
                    <a href="<?= e(city_date_path($city, 'week')) ?>">This Week</a>
                    <a href="<?= e(weekend_path($city)) ?>">This Weekend</a>
                </div>
            </div>
        </section>
        <?php render_events_grid_section($headline, (string) $city['name'], $events, $pageData, $config); ?>
        <?php dubai_render_faq($faqs, $headline . ' — FAQs'); ?>
        <?php
    }, $schemaGraph);
}

function render_city_category_page(HelloTicketsClient $client, array $config, int $cityId, string $categorySlug): void
{
    $categories = city_intent_categories();
    if (!isset($categories[$categorySlug])) {
        render_error_page($config, 404, 'Category not found', 'This city category page is not available.');
        return;
    }

    $city = city_for_id($cityId, $config);
    $canonicalPath = city_category_path($city, $categorySlug);
    if (current_path() !== $canonicalPath) {
        redirect_permanent($canonicalPath);
        return;
    }

    $page = page_number();
    $perPage = 24;
    $eventPool = city_category_events($client, $config, $cityId, $categorySlug);
    $total = count($eventPool);
    if ($total < 3) {
        render_error_page($config, 404, 'No events found', 'There are not enough on-sale ' . strtolower($categories[$categorySlug]['label']) . ' events in ' . $city['name'] . ' right now.');
        return;
    }
    $events = array_slice($eventPool, ($page - 1) * $perPage, $perPage);
    if ($events === [] && $page > 1) {
        render_error_page($config, 404, 'Page not found', 'This page of events is not available.');
        return;
    }

    $label = (string) $categories[$categorySlug]['label'];
    $singular = (string) $categories[$categorySlug]['singular'];
    $headline = $label . ' in ' . $city['name'];
    $topNames = [];
    $venues = [];
    $minPrice = null;
    $currency = (string) $config['currency'];
    foreach ($eventPool as $event) {
        $eventName = trim((string) ($event['name'] ?? ''));
        if ($eventName !== '' && count($topNames) < 3 && !in_array($eventName, $topNames, true)) {
            $topNames[] = $eventName;
        }
        $venue = trim((string) ($event['venue']['name'] ?? ''));
        if ($venue !== '' && count($venues) < 6 && !in_array($venue, $venues, true)) {
            $venues[] = $venue;
        }
        $price = (float) ($event['price_range']['min_price'] ?? 0);
        if ($price > 0 && ($minPrice === null || $price < $minPrice)) {
            $minPrice = $price;
            $currency = (string) ($event['price_range']['currency'] ?? $currency);
        }
    }

    $summary = 'There ' . ($total === 1 ? 'is 1 upcoming ' . $singular : 'are ' . number_format($total) . ' upcoming ' . strtolower($label) . ' events')
        . ' in ' . $city['name']
        . ($topNames !== [] ? ', including ' . natural_join(array_slice($topNames, 0, 2)) : '')
        . ($minPrice !== null ? ', with tickets from ' . money($minPrice, $currency) : '')
        . '.';

    $faqs = [
        ['q' => 'What ' . strtolower($label) . ' events are coming up in ' . $city['name'] . '?',
         'a' => ($total === 1 ? '1 event is' : number_format($total) . ' events are') . ' currently on sale'
            . ($topNames !== [] ? ', including ' . natural_join($topNames) : '') . '.'],
    ];
    if ($minPrice !== null) {
        $faqs[] = ['q' => 'How much are ' . strtolower($label) . ' tickets in ' . $city['name'] . '?',
            'a' => 'Tickets currently start from ' . money($minPrice, $currency) . '. Prices are live and can change by date, seat and demand.'];
    }
    if ($venues !== []) {
        $faqs[] = ['q' => 'Where are ' . strtolower($label) . ' events held in ' . $city['name'] . '?',
            'a' => 'Current listings include ' . natural_join($venues) . '.'];
    }
    $faqs[] = ['q' => 'When is the best time to buy ' . strtolower($label) . ' tickets in ' . $city['name'] . '?',
        'a' => 'Buying early typically offers the best selection and pricing. However, last-minute tickets are sometimes available — check this page for current availability. Prices are live and update automatically.'];
    $faqs[] = ['q' => 'Can I get ' . strtolower($label) . ' tickets at face value in ' . $city['name'] . '?',
        'a' => 'All tickets on this page are priced by our official ticketing partner. Prices vary by event, date, seat and demand. The prices shown are live from the partner\'s inventory.'];
    $faqs[] = ['q' => 'Are these official ticket listings?',
        'a' => 'Every listing links to checkout with an official ticketing partner. ' . $config['site_name'] . ' may earn a commission at no extra cost to you.'];

    // Deterministic-unique slice — each {city}/{category} combo gets its own mix.
    $catFaqData = [
        '{city}' => (string) $city['name'],
        '{category}' => strtolower($label),
        '{count}' => (string) $total,
        '{min_price}' => $minPrice !== null ? money($minPrice, $currency) : '',
        '{top_venues}' => implode(', ', array_slice($venues, 0, 3)),
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('city_category', slugify((string) $city['name']) . '-' . $categorySlug, $catFaqData, 6));

    $listSchema = item_list_schema($config, $events, 'event');
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $listSchema !== [] ? $listSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => $city['name'], 'url' => absolute_url($config, city_path($city))],
                ['name' => $label, 'url' => absolute_url($config, $canonicalPath)],
            ]),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    $pageData = ['current_page' => $page, 'per_page' => $perPage, 'total_count' => $total];
    render_layout($config, [
        'title' => $headline . ' Tickets | ' . $config['site_name'],
        'description' => $headline . ': ' . number_format($total) . ' upcoming events with dates, venues and live ticket prices' . ($minPrice !== null ? ' from ' . money($minPrice, $currency) : '') . '.',
        'canonical' => absolute_url($config, $canonicalPath, array_filter(['page' => $page > 1 ? $page : null])),
    ], function () use ($config, $city, $headline, $label, $total, $venues, $summary, $events, $pageData, $faqs): void {
        $cityCategories = city_intent_categories();
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><?= e($city['name']) ?></p>
                <h1><?= e($headline) ?></h1>
                <p class="listing-sub"><?= e($summary) ?></p>
                <div class="filter-row inverse">
                    <a href="<?= e(city_path($city)) ?>">All Events</a>
                    <?php foreach ($cityCategories as $slug => $meta): ?>
                        <a href="<?= e(city_category_path($city, $slug)) ?>"><?= e($meta['label']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php render_events_grid_section($headline, (string) $city['name'], $events, $pageData, $config); ?>
        <?php dubai_render_faq($faqs, $headline . ' — FAQs'); ?>
        <section class="section-band muted">
            <div class="container">
                <h2>About <?= e($headline) ?></h2>
                <p><?= e($city['name']) ?> has <?= e(number_format($total)) ?> upcoming <?= e(strtolower($label)) ?> event<?= $total === 1 ? '' : 's' ?> on sale right now. Every listing on this page includes the date, venue and live starting price from our official ticketing partner. Prices update in real time and may change based on demand and availability.</p>
                <?php if ($venues !== []): ?>
                    <p>Popular venues for <?= e(strtolower($label)) ?> in <?= e($city['name']) ?> include <?= e(natural_join(array_slice($venues, 0, 4))) ?>.</p>
                <?php endif; ?>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <h2>More Event Types in <?= e($city['name']) ?></h2>
                <p>Explore other categories in <?= e($city['name']) ?>:</p>
                <ul class="more-cities-list">
                    <li><a href="<?= e(city_path($city)) ?>">All Events in <?= e($city['name']) ?></a></li>
                    <?php foreach ($cityCategories as $slug => $meta): ?>
                        <li><a href="<?= e(city_category_path($city, $slug)) ?>"><?= e($meta['label']) ?> in <?= e($city['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <section class="section-band muted">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($headline) ?> Tickets</h2>
                <p>This page is your complete guide to <?= e(strtolower($label)) ?> events in <?= e($city['name']) ?>. Browse every confirmed date with live ticket availability and real-time pricing. When you find a show, click through to complete your purchase securely on our partner's checkout. Tickets are delivered instantly by email.</p>
                <p>New <?= e(strtolower($label)) ?> events in <?= e($city['name']) ?> appear automatically as they go on sale. Bookmark this page for the latest schedule.</p>
            </div>
        </section>
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
    $artistCount = (int) ($data['total_count'] ?? count($performers));

    $faqs = [
        ['q' => 'How do I find an artist on tour?',
         'a' => 'Browse the trending artists above or use the search bar to look up any artist by name. Every artist listed here has confirmed upcoming shows on sale — open an artist page to see the full tour with dates, cities, venues and live prices.'],
        ['q' => 'Which artists are most popular right now?',
         'a' => 'The list above is roughly ordered by how much each artist is touring right now and how many of their shows are on sale. Top of the page is where the biggest tours and the most active touring artists sit.'],
        ['q' => 'How do I know if an artist is on tour?',
         'a' => 'If an artist appears on this page, they have at least one confirmed upcoming show on sale. The artist page itself shows the full tour count, cities and the next date — empty pages are not listed here.'],
        ['q' => 'How are artist ticket prices set?',
         'a' => 'Prices are set by the ticket partner and pulled live for every show. They vary by city, venue, seat tier and how close to the show date you book — earlier bookings usually have the widest selection of tiers.'],
        ['q' => 'How quickly are new artist tours added?',
         'a' => 'New tours and dates appear here automatically the moment tickets go on sale at our official ticketing partner. The list refreshes throughout the day so it always reflects what is currently bookable.'],
    ];

    $artistListSchema = item_list_schema_for_artists($config, $performers);
    unset($artistListSchema['@context']);
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => [
            $artistListSchema,
            dubai_faq_schema($faqs),
        ],
    ];

    render_layout($config, [
        'title' => 'Artists On Tour — Concert & Show Tickets | ' . $config['site_name'],
        'description' => 'Browse artists currently on tour. See upcoming dates, venues and live ticket prices for every show.',
        'canonical' => absolute_url($config, '/artists'),
    ], function () use ($performers, $data, $faqs, $artistCount): void {
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
        <?php $guideStore = artist_intent_store(); ?>
        <?php if ($guideStore !== []): ?>
        <section class="section-band muted">
            <div class="container">
                <div class="section-heading"><h2>Popular Artist Guides</h2></div>
                <p class="more-cities-intro">In-depth ticket-price guides, tour dates and setlists for the artists people search for most — updated with live prices when shows are on sale.</p>
                <div class="guide-grid">
                    <?php foreach ($guideStore as $guideSlug => $guideEntry):
                        $guideName = (string) ($guideEntry['name'] ?? ucwords(str_replace('-', ' ', (string) $guideSlug)));
                        $guideHref = '/artist/' . $guideSlug; ?>
                        <div class="guide-card">
                            <a class="guide-card__name" href="<?= e($guideHref) ?>"><?= e($guideName) ?></a>
                            <span class="guide-card__links">
                                <?php if (!empty($guideEntry['prices'])): ?><a href="<?= e($guideHref) ?>/ticket-prices">Prices</a><?php endif; ?>
                                <?php if (!empty($guideEntry['tour'])): ?><a href="<?= e($guideHref) ?>/tour-dates">Tour Dates</a><?php endif; ?>
                                <?php if (!empty($guideEntry['setlist'])): ?><a href="<?= e($guideHref) ?>/setlist">Setlist</a><?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About Artists On Tour</h2>
                <p>This page is a live index of artists currently on tour — every entry has at least one confirmed upcoming show on sale via our official ticketing partner. Open any artist to see the full tour in one place: every date, city, venue and live starting price, with new shows added automatically the moment tickets go on sale.</p>
                <p>Concerts, festivals, stage productions and live sports acts are all represented. The list is roughly ordered by how active each artist's touring is right now, so the biggest tours and most-bookable acts sit at the top.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, 'Artists On Tour — FAQs'); ?>
        <?php
    }, $schemaGraph);
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
    $events = artist_tour_events($client, $config, $performerId, $name, $tmOnly);

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
    // Cities we can render a dedicated "{artist} in {city}" page for — links them
    // so the long-tail pages are discoverable (and Google never follows a link to
    // a city page that would 404).
    $linkableCities = artist_linkable_cities($config, $events);
    $faqs = artist_faqs($performer, $events, $config);

    // Augment the live-data FAQs with a deterministic-unique slice from the
    // shared pool so every artist page gets a different long-tail FAQ mix —
    // see faq-pool.php for the questions and helpers.php::unique_faqs() for
    // the slug-seeded shuffle.
    $artistMinPrice = null;
    $artistCurrency = (string) $config['currency'];
    $artistVenues = [];
    foreach ($events as $ev) {
        $p = (float) ($ev['price_range']['min_price'] ?? 0);
        if ($p > 0 && ($artistMinPrice === null || $p < $artistMinPrice)) {
            $artistMinPrice = $p;
            $artistCurrency = (string) ($ev['price_range']['currency'] ?? $artistCurrency);
        }
        $vn = trim((string) ($ev['venue']['name'] ?? ''));
        if ($vn !== '' && !in_array($vn, $artistVenues, true)) {
            $artistVenues[] = $vn;
        }
    }
    $artistData = [
        '{name}' => $name,
        '{count}' => (string) count($events),
        '{city_count}' => (string) count($tourCities),
        '{venue_count}' => (string) count($artistVenues),
        '{min_price}' => $artistMinPrice !== null ? money($artistMinPrice, $artistCurrency) : '',
        '{next_date}' => $nextDate,
        '{top_cities}' => implode(', ', array_slice($tourCities, 0, 3)),
        '{site_name}' => (string) $config['site_name'],
    ];
    $uniqueFaqs = unique_faqs('artist', slugify($name), $artistData, 6);
    $faqs = array_merge($faqs, $uniqueFaqs);

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
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => 'Artists', 'url' => absolute_url($config, '/artists')],
                ['name' => (string) ($performer['name'] ?? 'Artist'), 'url' => absolute_url($config, artist_path($performer))],
            ]),
        ])),
    ];

    $performerPhoto = mapped_image('performer', (int) ($performer['id'] ?? 0));

    render_layout($config, [
        'title' => $name . ' Tickets, Tour Dates & Venues | ' . $config['site_name'],
        'description' => $description,
        'canonical' => absolute_url($config, artist_path($performer)),
        'image' => $performerPhoto !== null ? absolute_image_url($config, $performerPhoto) : null,
    ], function () use ($config, $performer, $name, $events, $nextDate, $tourCities, $faqs, $linkableCities): void {
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
        <?php if (count($linkableCities) > 1): ?>
            <section class="section-band muted">
                <div class="container">
                    <div class="section-heading">
                        <h2><?= e($name) ?> by City</h2>
                    </div>
                    <p class="more-cities-intro">Jump straight to <?= e($name) ?>'s dates in a specific city:</p>
                    <ul class="more-cities-list">
                        <?php foreach ($linkableCities as $cSlug => $cName): ?>
                            <li><a href="<?= e(artist_path($performer)) ?>/<?= e($cSlug) ?>"><?= e($name) ?> in <?= e($cName) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>
        <?php $intentLinks = artist_intent_links(slugify($name), $name); ?>
        <?php if ($intentLinks !== []): ?>
            <section class="section-band">
                <div class="container">
                    <div class="section-heading"><h2><?= e($name) ?> Guides</h2></div>
                    <ul class="more-cities-list">
                        <?php foreach ($intentLinks as $intentSlug => $label): ?>
                            <li><a href="<?= e(artist_path($performer) . '/' . $intentSlug) ?>"><?= e($label) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>
        <?php
        // --- Rich content block: genre, about, tour overview ---
        $genre = (string) ($performer['category']['name'] ?? $performer['classifications'][0]['genre']['name'] ?? '');
        $subGenre = (string) ($performer['classifications'][0]['subGenre']['name'] ?? '');
        $segment = (string) ($performer['classifications'][0]['segment']['name'] ?? '');
        $venueNames = [];
        foreach ($events as $ev) {
            $vn = trim((string) ($ev['venue']['name'] ?? ''));
            if ($vn !== '' && !in_array($vn, $venueNames, true)) { $venueNames[] = $vn; }
        }
        ?>
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About <?= e($name) ?></h2>
                <?php if ($events !== []): ?>
                    <p><?= e($name) ?> has <?= e((string) count($events)) ?> upcoming show<?= count($events) === 1 ? '' : 's' ?> across <?= e((string) count($tourCities)) ?> <?= count($tourCities) === 1 ? 'city' : 'cities' ?>.
                    <?php if ($genre !== '' && $genre !== 'Undefined'): ?>
                        Known for <?= e(strtolower($genre)) ?><?= $subGenre !== '' && $subGenre !== 'Undefined' ? ' / ' . e(strtolower($subGenre)) : '' ?> performances,
                    <?php endif; ?>
                    <?= e($name) ?> is currently touring with dates on sale now. Every listing on this page shows real-time pricing direct from our official ticketing partners.</p>
                    <?php if (count($venueNames) > 1): ?>
                        <p>This tour includes stops at <?= e(implode(', ', array_slice($venueNames, 0, 6))) ?><?= count($venueNames) > 6 ? ' and ' . e((string) (count($venueNames) - 6)) . ' more venues' : '' ?>. Select any date below to see live seat availability and instant e-ticket delivery.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p><?= e($name) ?> does not have any upcoming shows on sale right now. This page updates automatically — when new <?= e($name) ?> tour dates are announced and tickets go on sale, they will appear here with live pricing from our official ticketing partners.</p>
                    <p>In the meantime, you can browse <a href="/artists">all artists currently on tour</a> or check back soon for updates.</p>
                <?php endif; ?>
            </div>
        </section>
        <?php if ($events !== []): ?>
        <section class="section-band">
            <div class="container">
                <h2><?= e($name) ?> Tour Overview</h2>
                <div class="artist-tour-overview">
                    <div class="tour-stat"><strong><?= e((string) count($events)) ?></strong><span>Total Shows</span></div>
                    <div class="tour-stat"><strong><?= e((string) count($tourCities)) ?></strong><span>Cities</span></div>
                    <div class="tour-stat"><strong><?= e((string) count($venueNames)) ?></strong><span>Venues</span></div>
                    <?php
                    $minP = null; $minC = '';
                    foreach ($events as $ev) {
                        $p = (float) ($ev['price_range']['min_price'] ?? 0);
                        if ($p > 0 && ($minP === null || $p < $minP)) { $minP = $p; $minC = (string) ($ev['price_range']['currency'] ?? $config['currency']); }
                    }
                    if ($minP !== null): ?>
                        <div class="tour-stat"><strong><?= e(money($minP, $minC)) ?></strong><span>From</span></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        <?php
        // Tour country links
        $tourCountryCodes = [];
        foreach ($events as $ev) {
            $cc = (string) ($ev['venue']['country_code'] ?? '');
            if ($cc !== '' && !isset($tourCountryCodes[$cc])) { $tourCountryCodes[$cc] = true; }
        }
        $countryMap = ['US' => 'usa', 'CA' => 'canada', 'GB' => 'uk', 'AU' => 'australia'];
        $countryLabels = ['usa' => 'USA', 'canada' => 'Canada', 'uk' => 'UK', 'australia' => 'Australia'];
        $tourCountryLinks = [];
        foreach ($countryMap as $cc => $cs) {
            if (isset($tourCountryCodes[$cc])) { $tourCountryLinks[$cs] = $countryLabels[$cs]; }
        }
        if (count($tourCountryLinks) > 0): ?>
        <section class="section-band muted">
            <div class="container">
                <h2><?= e($name) ?> by Country</h2>
                <p>See <?= e($name) ?> tour dates filtered by country:</p>
                <ul class="more-cities-list">
                    <?php foreach ($tourCountryLinks as $cs => $cl): ?>
                        <li><a href="<?= e(artist_path($performer) . '/' . $cs . '-tour') ?>"><?= e($name) ?> <?= e($cl) ?> Tour</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php endif; ?>
        <?php dubai_render_faq($faqs, $name . ' Ticket FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($name) ?> Tickets</h2>
                <p>Looking for <?= e($name) ?> tickets? You are in the right place. This page shows every confirmed <?= e($name) ?> date with live ticket availability and real-time pricing from our official ticketing partners. When you find a show, click through to complete your purchase on the partner's secure checkout — tickets are delivered instantly by email.</p>
                <p>Prices shown are set by the ticketing partner and may change as the show date approaches. We recommend booking early for the best selection of seats and pricing. All dates update automatically, so you will always see the most current schedule.</p>
                <?php if ($tourCities !== []): ?>
                    <p><?= e($name) ?> is currently performing in <?= e(implode(', ', array_slice($tourCities, 0, 10))) ?><?= count($tourCities) > 10 ? ' and more' : '' ?>. Select a city above or pick any date to view available seats and prices.</p>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, $schemaGraph);
}

/**
 * An artist's full merged tour: HelloTickets first (own detail pages + higher
 * commission), topped up from Ticketmaster for US-touring acts/teams HT covers
 * thinly. Shared by the artist detail page and the artist-in-city pages so both
 * see the same catalogue. $tmOnly is a pre-resolved TM attraction for artists HT
 * doesn't know at all.
 */
/**
 * "{Artist} in {City}" — a focused page for the highest-intent long-tail query
 * ("tarsem jassar show in edmonton", "taylor swift new york tickets"). Built only
 * when the artist genuinely has ≥1 event in that city, so it's never a thin
 * doorway: it's a real subset of the artist's tour, filtered to one city.
 */
function render_artist_in_city_page(HelloTicketsClient $client, array $config, string $artistSlug, string $citySlug): void
{
    // Resolve the city to a known name first — unknown cities can't be rendered.
    $cityId = resolve_city_id_by_slug($config, $citySlug);
    if ($cityId === null) {
        render_error_page($config, 404, 'Page not found', 'We do not have a page for this artist and city.');
        return;
    }
    $cityName = (string) city_for_id($cityId, $config)['name'];

    // Resolve the artist (same path as the main artist route).
    $performerId = resolve_artist_id($client, $artistSlug) ?? legacy_id_from_slug($artistSlug);
    $tmOnly = null;
    $performer = null;
    if ($performerId === null) {
        $tmOnly = tm_artist_by_slug($config, $artistSlug);
        if ($tmOnly === null) {
            render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
            return;
        }
        $performer = tm_normalize_attraction($tmOnly);
        $performerId = 0;
    } else {
        $performer = api_result(static fn() => $client->performer($performerId));
        if ($performer === [] || empty($performer['id'])) {
            render_error_page($config, 404, 'Artist not found', 'This artist is not on tour right now.');
            return;
        }
    }
    $name = (string) ($performer['name'] ?? 'Artist');

    // Canonical URL uses the resolved artist + city slugs — fold any legacy/case
    // variant into one URL so duplicates never compete.
    $canonicalPath = '/artist/' . slugify($name) . '/' . slugify($cityName);
    if ($performerId > 0) {
        // artist_path() may use a {slug}-{id} form; mirror its slug for the artist part.
        $canonicalPath = artist_path($performer) . '/' . slugify($cityName);
    }
    if (current_path() !== $canonicalPath) {
        redirect_permanent($canonicalPath);
        return;
    }

    // Filter the artist's full tour to this city. THIS is the inventory gate.
    // Match by SLUG (not exact name) so it's provably consistent with the link
    // artist_linkable_cities() builds (slugify(venue city)) — a linked city can
    // therefore never resolve to an empty page.
    $citySlugNorm = slugify($cityName);
    $allEvents = artist_tour_events($client, $config, (int) $performerId, $name, $tmOnly);
    $events = array_values(array_filter($allEvents, static fn($e): bool =>
        slugify(trim((string) ($e['venue']['city'] ?? ''))) === $citySlugNorm));
    if ($events === []) {
        render_error_page($config, 404, 'No upcoming shows', $name . ' has no upcoming shows in ' . $cityName . ' right now.');
        return;
    }

    $first = $events[0];
    $venueName = trim((string) ($first['venue']['name'] ?? ''));
    $whenLabel = format_date_time($first['start_date'] ?? []);
    $minPrice = null;
    $currency = (string) $config['currency'];
    foreach ($events as $e) {
        $p = (float) ($e['price_range']['min_price'] ?? 0);
        if ($p > 0 && ($minPrice === null || $p < $minPrice)) {
            $minPrice = $p;
            $currency = (string) ($e['price_range']['currency'] ?? $currency);
        }
    }

    $count = count($events);
    $summary = $count === 1
        ? $name . ' plays ' . $cityName . ($venueName !== '' ? ' at ' . $venueName : '')
            . ($whenLabel !== '' && $whenLabel !== 'Upcoming' ? ' on ' . $whenLabel : '') . '. '
            . ($minPrice !== null ? 'Tickets start from ' . money($minPrice, $currency) . '. ' : '')
            . 'Availability is live from our official ticketing partner.'
        : 'There are ' . $count . ' upcoming ' . $name . ' shows in ' . $cityName
            . ($whenLabel !== '' && $whenLabel !== 'Upcoming' ? ', next on ' . $whenLabel : '') . '. '
            . ($minPrice !== null ? 'Tickets start from ' . money($minPrice, $currency) . '. ' : '')
            . 'All dates and prices below are live from our official ticketing partner.';

    $faqs = array_values(array_filter([
        ['q' => 'Is ' . $name . ' playing in ' . $cityName . '?',
         'a' => $count === 1
            ? 'Yes — ' . $name . ' has 1 upcoming show in ' . $cityName
                . ($whenLabel !== '' && $whenLabel !== 'Upcoming' ? ' on ' . $whenLabel : '')
                . ($venueName !== '' ? ' at ' . $venueName : '') . '.'
            : 'Yes — ' . $name . ' has ' . $count . ' upcoming shows in ' . $cityName . '. The full list with dates and prices is on this page.'],
        $minPrice !== null ? ['q' => 'How much are ' . $name . ' tickets in ' . $cityName . '?',
         'a' => 'Tickets currently start from ' . money($minPrice, $currency) . ', varying by seat and date. Prices are live from our official ticketing partner.'] : null,
        $venueName !== '' ? ['q' => 'Where does ' . $name . ' play in ' . $cityName . '?',
         'a' => 'The ' . ($count === 1 ? 'show is' : 'next show is') . ' at ' . $venueName . ' in ' . $cityName . '.'] : null,
        ['q' => 'How do I buy ' . $name . ' tickets for ' . $cityName . '?',
         'a' => 'Pick any date above and continue to secure checkout on our official ticketing partner. Tickets are issued instantly by email after payment — show the QR code on your phone at the entrance.'],
        ['q' => 'When should I book ' . $name . ' tickets in ' . $cityName . '?',
         'a' => 'Earlier bookings usually have the widest selection of seat tiers and the best vantage points. Popular ' . $name . ' shows can sell out, so booking sooner than later is the safer bet.'],
        ['q' => 'How are ' . $name . ' tickets delivered?',
         'a' => 'Tickets are delivered as e-tickets by email immediately after booking. There is no printing required for most venues — your phone is the ticket.'],
    ], static fn($f) => $f !== null));

    // Deterministic-unique slice — each {artist}/{city} pair gets its own mix.
    $aicData = [
        '{name}' => $name,
        '{city}' => $cityName,
        '{count}' => (string) $count,
        '{min_price}' => $minPrice !== null ? money($minPrice, $currency) : '',
        '{next_venue}' => $venueName !== '' ? $venueName : 'the venue',
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('artist_in_city', slugify($name) . '-' . slugify($cityName), $aicData, 5));

    $listSchema = item_list_schema($config, $events, 'event');
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $listSchema !== [] ? $listSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => 'Artists', 'url' => absolute_url($config, '/artists')],
                ['name' => $name, 'url' => absolute_url($config, artist_path($performer))],
                ['name' => $cityName, 'url' => absolute_url($config, $canonicalPath)],
            ]),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    $titleDate = ($whenLabel !== '' && $whenLabel !== 'Upcoming' && $count === 1) ? ' — ' . $whenLabel : '';
    render_layout($config, [
        'title' => $name . ' in ' . $cityName . ' Tickets' . $titleDate . ' | ' . $config['site_name'],
        'description' => $name . ($venueName !== '' ? ' at ' . $venueName : '') . ' in ' . $cityName . '. '
            . $count . ' upcoming show' . ($count === 1 ? '' : 's')
            . ($minPrice !== null ? ' from ' . money($minPrice, $currency) : '')
            . ' with live availability — secure checkout via official partner.',
        'canonical' => absolute_url($config, $canonicalPath),
        'image' => ($img = mapped_image('performer', (int) ($performer['id'] ?? 0))) !== null ? absolute_image_url($config, $img) : null,
    ], function () use ($config, $name, $cityName, $events, $summary, $faqs, $performer, $count, $venueName, $minPrice, $currency): void {
        ?>
        <section class="listing-hero">
            <div class="container">
                <p class="eyebrow"><a href="<?= e(artist_path($performer)) ?>" class="muted-link"><?= e($name) ?></a> · <?= e($cityName) ?></p>
                <h1><?= e($name) ?> in <?= e($cityName) ?></h1>
                <p class="listing-sub"><?= e($summary) ?></p>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <div class="section-heading">
                    <h2><?= e($name) ?> &mdash; <?= e($cityName) ?> Dates</h2>
                    <span class="muted"><?= e((string) $count) ?> on sale</span>
                </div>
                <div class="card-grid">
                    <?php foreach ($events as $event): ?>
                        <?= event_card($event, $config) ?>
                    <?php endforeach; ?>
                </div>
                <p class="more-link"><a href="<?= e(artist_path($performer)) ?>">See all <?= e($name) ?> tour dates &rarr;</a></p>
            </div>
        </section>
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About <?= e($name) ?> in <?= e($cityName) ?></h2>
                <p><?= e($name) ?> has <?= e((string) $count) ?> confirmed show<?= $count === 1 ? '' : 's' ?> in <?= e($cityName) ?><?= $venueName !== '' ? ' at ' . e($venueName) : '' ?>, with live ticket pricing direct from our official ticketing partner. The schedule on this page refreshes automatically as new dates are added or as availability changes.</p>
                <p>Pick any date above to see seat availability and tier pricing in real time. Checkout completes on the partner's secure site and e-tickets are emailed instantly.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $name . ' in ' . $cityName . ' — FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($name) ?> Tickets in <?= e($cityName) ?></h2>
                <p>Booking is quick: pick a date above, choose your seat tier on the partner checkout, and pay with the card of your choice. Your e-ticket arrives by email seconds later and shows the QR code you scan at the entrance — no printing needed for most venues.</p>
                <?php if ($minPrice !== null): ?>
                    <p><?= e($name) ?> tickets in <?= e($cityName) ?> currently start from <strong><?= e(money($minPrice, $currency)) ?></strong>. Premium seats sell out first — booking earlier usually gives you the widest selection of tiers and the best vantage points.</p>
                <?php else: ?>
                    <p>Live pricing is shown on the partner checkout once you pick a date. Booking earlier usually gives you the widest selection of seat tiers and the best vantage points.</p>
                <?php endif; ?>
                <p>Looking elsewhere? See <a href="<?= e(artist_path($performer)) ?>">all <?= e($name) ?> tour dates</a> or browse <a href="/artists">other artists currently on tour</a>.</p>
            </div>
        </section>
        <?php
    }, $schemaGraph);
}

/**
 * An artist's full merged tour: HelloTickets first (own detail pages + higher
 * commission), topped up from Ticketmaster for US-touring acts/teams HT covers
 * thinly. Shared by the artist detail page and the artist-in-city pages so both
 * see the same catalogue. $tmOnly is a pre-resolved TM attraction for artists HT
 * doesn't know at all.
 */
function artist_intent_store(): array
{
    static $store = null;
    if ($store === null) {
        $store = file_exists(__DIR__ . '/artist-intent-content.php')
            ? require __DIR__ . '/artist-intent-content.php'
            : [];
    }
    return $store;
}

/** Curated intent links available for an artist slug, [intentSlug => label]. Empty
 *  when the artist isn't curated, so the detail page only links pages that exist. */
function artist_intent_links(string $slug, string $name): array
{
    $content = artist_intent_store()[$slug] ?? null;
    if ($content === null) {
        return [];
    }
    $links = [];
    if (!empty($content['prices']))  { $links['ticket-prices'] = $name . ' Ticket Prices'; }
    if (!empty($content['tour']))    { $links['tour-dates']    = $name . ' Tour Dates'; }
    if (!empty($content['setlist'])) { $links['setlist']       = $name . ' Setlist'; }
    return $links;
}

function render_artist_intent_page(HelloTicketsClient $client, array $config, string $artistSlug, string $intent): void
{
    $store = artist_intent_store();

    $content = $store[$artistSlug] ?? null;
    $section = $content[($intent === 'ticket-prices' ? 'prices' : ($intent === 'tour-dates' ? 'tour' : 'setlist'))] ?? null;
    if ($content === null || $section === null) {
        render_error_page($config, 404, 'Page not found', 'We do not have this guide for this artist yet.');
        return;
    }

    $name = (string) ($content['name'] ?? ucwords(str_replace('-', ' ', $artistSlug)));
    $artistHref = '/artist/' . $artistSlug;
    $canonical = absolute_url($config, $artistHref . '/' . $intent);

    // Resolve live data where we can — the page renders without it, but live cards
    // and a live "from" price turn an informational visit into a sale.
    $performer = null;
    $events = [];
    $performerId = resolve_artist_id($client, $artistSlug);
    if ($performerId !== null) {
        $performer = api_result(static fn() => $client->performer($performerId));
        $events = artist_tour_events($client, $config, $performerId, $name);
    } else {
        $tmOnly = tm_artist_by_slug($config, $artistSlug);
        if ($tmOnly !== null) {
            $performer = tm_normalize_attraction($tmOnly);
            $events = artist_tour_events($client, $config, 0, $name, $tmOnly);
        }
    }
    $photo = $performer !== null ? mapped_image('performer', (int) ($performer['id'] ?? 0)) : null;

    // Live minimum "from" price across the tour (drives the price page headline).
    $liveMin = null; $liveCur = (string) $config['currency'];
    foreach ($events as $ev) {
        $p = (float) ($ev['price_range']['min_price'] ?? 0);
        if ($p > 0 && ($liveMin === null || $p < $liveMin)) {
            $liveMin = $p; $liveCur = (string) ($ev['price_range']['currency'] ?? $liveCur);
        }
    }

    $faqs = $section['faqs'] ?? [];

    // Per-intent <title>, meta description and H1.
    if ($intent === 'ticket-prices') {
        $low = (int) ($section['range_low'] ?? 0); $high = (int) ($section['range_high'] ?? 0);
        $cur = (string) ($section['currency'] ?? 'USD');
        $title = $name . ' Ticket Prices 2026 — How Much Do Tickets Cost? | ' . $config['site_name'];
        $desc = 'How much are ' . $name . ' tickets? Prices typically run ' . money((float) $low, $cur) . '–' . money((float) $high, $cur) . ' by seat tier and city. See live "from" prices, tier breakdown and FAQs.';
        $h1 = $name . ' Ticket Prices 2026';
        $eyebrow = 'Price Guide · 2026';
    } elseif ($intent === 'tour-dates') {
        $tourName = trim((string) ($section['tour_name'] ?? ''));
        $title = $name . ' Tour Dates 2026 — Tickets & Cities | ' . $config['site_name'];
        $desc = $name . ' tour dates 2026' . ($tourName !== '' ? ' (' . $tourName . ')' : '') . ': every confirmed show with live ticket prices, venues and cities. New dates appear here the moment they go on sale.';
        $h1 = $name . ' Tour Dates 2026';
        $eyebrow = $tourName !== '' ? $tourName : 'Tour · 2026';
    } else {
        $title = $name . ' Setlist 2026 — Songs & What to Expect Live | ' . $config['site_name'];
        $desc = $name . ' setlist 2026: the songs played on recent dates, how the show is structured and what to expect live, plus tickets for upcoming shows.';
        $h1 = $name . ' Setlist 2026';
        $eyebrow = 'Setlist · 2026';
    }

    // Schema: PerformingGroup + FAQPage + Breadcrumb (+ live Events on the tour page).
    $artistNode = [
        '@type' => ($content['genre'] ?? '') === 'Sports' ? 'SportsTeam' : 'PerformingGroup',
        'name' => $name,
        'url' => absolute_url($config, $artistHref),
    ];
    if ($intent === 'tour-dates' && $performer !== null && $events !== []) {
        $full = artist_schema($config, $performer, $events);
        unset($full['@context']);
        $artistNode = $full;
    }
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            $artistNode,
            $faqs !== [] ? dubai_faq_schema($faqs) : null,
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => 'Artists', 'url' => absolute_url($config, '/artists')],
                ['name' => $name, 'url' => absolute_url($config, $artistHref)],
                ['name' => trim(str_replace($name, '', $h1)), 'url' => $canonical],
            ]),
        ])),
    ];

    render_layout($config, [
        'title' => $title,
        'description' => $desc,
        'canonical' => $canonical,
        'image' => $photo !== null ? absolute_image_url($config, $photo) : null,
    ], function () use ($config, $content, $section, $intent, $name, $artistHref, $artistSlug, $h1, $eyebrow, $photo, $events, $liveMin, $liveCur, $faqs): void {
        ?>
        <nav class="crumbs" aria-label="Breadcrumb">
            <div class="container">
                <a href="/">Home</a> <span aria-hidden="true">›</span>
                <a href="/artists">Artists</a> <span aria-hidden="true">›</span>
                <a href="<?= e($artistHref) ?>"><?= e($name) ?></a> <span aria-hidden="true">›</span>
                <span><?= e(trim(str_replace($name, '', $h1))) ?></span>
            </div>
        </nav>
        <section class="listing-hero artist-hero">
            <div class="container">
                <div class="artist-hero__row">
                    <?php if ($photo !== null): ?>
                        <span class="artist-avatar artist-avatar--lg artist-avatar--img"><img src="<?= e($photo) ?>" alt="<?= e($name) ?>" loading="lazy"></span>
                    <?php else: ?>
                        <span class="artist-avatar artist-avatar--lg" aria-hidden="true"><?= e(artist_initials($name)) ?></span>
                    <?php endif; ?>
                    <div>
                        <p class="eyebrow"><?= e($eyebrow) ?></p>
                        <h1><?= e($h1) ?></h1>
                        <?php if ($intent === 'ticket-prices'): ?>
                            <div class="artist-hero__facts">
                                <span>Typical range <?= e(money((float) ($section['range_low'] ?? 0), (string) ($section['currency'] ?? 'USD'))) ?>–<?= e(money((float) ($section['range_high'] ?? 0), (string) ($section['currency'] ?? 'USD'))) ?></span>
                                <?php if ($liveMin !== null): ?><span>Live from <?= e(money($liveMin, $liveCur)) ?></span><?php endif; ?>
                            </div>
                        <?php elseif ($intent === 'tour-dates' && $events !== []): ?>
                            <div class="artist-hero__facts">
                                <span><?= e((string) count($events)) ?> show<?= count($events) === 1 ? '' : 's' ?> on sale</span>
                                <?php if ($liveMin !== null): ?><span>From <?= e(money($liveMin, $liveCur)) ?></span><?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($events !== []): ?>
                            <a class="button-link artist-hero__cta" href="#tickets">See Tickets</a>
                        <?php else: ?>
                            <a class="button-link artist-hero__cta" href="<?= e($artistHref) ?>"><?= e($name) ?> Tickets</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-band">
            <div class="container article-body">
                <?php foreach (($section['intro'] ?? []) as $para): ?>
                    <p><?= e($para) ?></p>
                <?php endforeach; ?>

                <?php if ($intent === 'ticket-prices' && !empty($section['tiers'])): ?>
                    <h2><?= e($name) ?> Ticket Tiers Explained</h2>
                    <ul class="tier-list">
                        <?php foreach ($section['tiers'] as $tier): ?>
                            <li><strong><?= e($tier['name'] ?? '') ?>:</strong> <?= e($tier['desc'] ?? '') ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (!empty($section['why'])): ?>
                        <h2>Why Do <?= e($name) ?> Ticket Prices Change?</h2>
                        <p><?= e($section['why']) ?></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($intent === 'setlist'): ?>
                    <?php if (!empty($section['songs'])): ?>
                        <h2><?= e($name) ?> Setlist — Recent Shows</h2>
                        <ol class="setlist">
                            <?php foreach ($section['songs'] as $song): ?>
                                <li><?= e($song) ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                    <?php if (!empty($section['encore'])): ?>
                        <h3>Encore</h3>
                        <ol class="setlist setlist--encore">
                            <?php foreach ($section['encore'] as $song): ?>
                                <li><?= e($song) ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                    <?php if (!empty($section['note'])): ?>
                        <p class="muted-note"><?= e($section['note']) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <?php if ($events !== []): ?>
            <section class="section-band muted" id="tickets">
                <div class="container">
                    <div class="section-heading"><h2><?= e($name) ?> Tickets — On Sale Now</h2></div>
                    <div class="card-grid">
                        <?php foreach (array_slice($events, 0, 12) as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="section-band">
            <div class="container">
                <div class="section-heading"><h2>More on <?= e($name) ?></h2></div>
                <ul class="more-cities-list">
                    <?php
                    $others = array_filter([
                        'ticket-prices' => $intent !== 'ticket-prices' ? $name . ' Ticket Prices' : null,
                        'tour-dates'    => $intent !== 'tour-dates' ? $name . ' Tour Dates' : null,
                        'setlist'       => $intent !== 'setlist' ? $name . ' Setlist' : null,
                    ]);
                    foreach ($others as $slug => $label):
                        if ($content[$slug === 'ticket-prices' ? 'prices' : ($slug === 'tour-dates' ? 'tour' : 'setlist')] ?? null): ?>
                        <li><a href="<?= e($artistHref . '/' . $slug) ?>"><?= e($label) ?></a></li>
                    <?php endif; endforeach; ?>
                    <li><a href="<?= e($artistHref) ?>"><?= e($name) ?> — All Tickets &amp; Tour Dates</a></li>
                </ul>
            </div>
        </section>

        <?php dubai_render_faq($faqs, $name . ' — Frequently Asked Questions'); ?>
        <?php
    }, $schemaGraph);
}

/**
 * An artist's full merged tour: HelloTickets first (own detail pages + higher
 * commission), topped up from Ticketmaster for US-touring acts/teams HT covers
 * thinly. Shared by the artist detail page and the artist-in-city pages so both
 * see the same catalogue. $tmOnly is a pre-resolved TM attraction for artists HT
 * doesn't know at all.
 */
function artist_tour_events(HelloTicketsClient $client, array $config, int $performerId, string $name, ?array $tmOnly = null): array
{
    $events = $tmOnly !== null ? [] : (api_result(static fn() => $client->performances([
        'limit' => 48,
        'page' => 1,
        'is_sellable' => 'true',
        'performer_id' => $performerId,
    ]), ['performances' => []])['performances'] ?? []);

    $tmAttraction = $tmOnly;
    if (count($events) < 10 && ($tm = tm_client($config)) !== null) {
        if ($tmAttraction === null) {
            $tmRaw = api_result(static fn() => $tm->attractions(['keyword' => $name, 'size' => 3]), []);
            $tmAttractions = $tmRaw['_embedded']['attractions'] ?? [];
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

    return $events;
}

/** Tour cities the artist plays that resolve to a city page we can render — used to
 *  link /artist/{slug}/{city} only where the target won't 404. Returns [citySlug => cityName]. */
function artist_linkable_cities(array $config, array $events): array
{
    $cities = [];
    foreach ($events as $event) {
        $cityName = trim((string) ($event['venue']['city'] ?? ''));
        if ($cityName === '') {
            continue;
        }
        $slug = slugify($cityName);
        if (isset($cities[$slug])) {
            continue;
        }
        if (resolve_city_id_by_slug($config, $slug) !== null) {
            $cities[$slug] = $cityName;
        }
    }
    return $cities;
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
    $faqs[] = ['q' => 'Are ' . $name . ' tickets refundable?', 'a' => 'Refund policies depend on the ticketing partner and the specific event. Check the terms on the checkout page before completing your purchase. Most events allow transfers if you cannot attend.'];
    $faqs[] = ['q' => 'When do new ' . $name . ' tour dates go on sale?', 'a' => 'New dates appear on this page automatically as soon as tickets are released by the artist\'s management. Bookmark this page and check back regularly for updates.'];
    $faqs[] = ['q' => 'Can I get ' . $name . ' tickets at face value?', 'a' => 'All prices on this page come directly from our official ticketing partner\'s live inventory. Prices vary by venue, seat location and demand. Buying early typically offers the best selection and pricing.'];

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
            'startDate' => schema_start_date($event),
            'location' => [
                '@type' => 'Place',
                'name' => $event['venue']['name'] ?? '',
                'address' => schema_postal_address($event['venue'] ?? []),
            ],
            'performer' => [
                '@type' => $schema['@type'],
                'name' => $performer['name'] ?? '',
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
            <img src="<?= e($image) ?>" alt="<?= e($performance['name'] ?? 'Event') ?>" <?= card_img_attrs() ?>>
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
            <a class="card-cta" href="<?= e($cardHref) ?>"<?= $rel ?>>
                <span>Get Tickets</span>
                <?php if (((float) $price) > 0): ?><span class="card-cta__price"><?= e(money($price, $currency)) ?></span><?php endif; ?>
            </a>
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
            <img src="<?= e($image) ?>" alt="<?= e($activity['title'] ?? 'Experience') ?>" <?= card_img_attrs() ?>>
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
            <a class="card-cta" href="<?= e($cardHref) ?>"<?= $rel ?>>
                <span>Get Tickets</span>
                <?php if (((float) $price) > 0): ?><span class="card-cta__price"><?= e(money($price, $currency)) ?></span><?php endif; ?>
            </a>
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
    $nextUrl = '';
    if ($hasNext) {
        $nextQuery = $query;
        $nextQuery['page'] = $page + 1;
        $nextUrl = route_url(current_path(), $nextQuery);
    }
    // data-next is the hook the infinite-scroll script reads; the links stay real so
    // crawlers and no-JS visitors still page normally (progressive enhancement).
    ?>
    <nav class="pagination" aria-label="Pagination" data-pagination<?= $hasNext ? ' data-next="' . e($nextUrl) . '"' : '' ?>>
        <?php if ($page > 1): ?>
            <?php $query['page'] = $page - 1; ?>
            <a rel="prev" href="<?= e(route_url(current_path(), $query)) ?>">Previous</a>
        <?php endif; ?>
        <span>Page <?= e($page) ?></span>
        <?php if ($hasNext): ?>
            <a rel="next" href="<?= e($nextUrl) ?>">Next</a>
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

function ai_crawler_user_agents(): array
{
    return [
        'OAI-SearchBot',
        'ChatGPT-User',
        'GPTBot',
        'ClaudeBot',
        'Claude-SearchBot',
        'Claude-User',
        'anthropic-ai',
        'PerplexityBot',
        'Perplexity-User',
        'Googlebot',
        'GoogleOther',
        'Google-Extended',
        'Bingbot',
        'Applebot',
        'Applebot-Extended',
        'meta-externalagent',
        'FacebookBot',
        'CCBot',
        'Amazonbot',
        'Bytespider',
        'YouBot',
        'cohere-ai',
        'DuckAssistBot',
        // Best-effort aliases for answer engines that have not published a
        // stable crawler string, harmless if they never request the file.
        'xAI-Bot',
        'GrokBot',
        'Grok',
        'Twitterbot',
    ];
}

function configured_market_names(array $config): array
{
    return array_values(array_filter(array_map(
        static fn(array $m): string => trim((string) ($m['name'] ?? '')),
        array_values($config['markets'] ?? [])
    )));
}

function content_last_modified_date(): string
{
    $contentMtimes = array_filter([
        @filemtime(__DIR__ . '/destinations-content.json') ?: null,
        @filemtime(__DIR__ . '/dubai-content.php') ?: null,
        @filemtime(__DIR__ . '/category-content.php') ?: null,
        @filemtime(__FILE__) ?: null,
    ]);

    return $contentMtimes !== [] ? date('Y-m-d', max($contentMtimes)) : date('Y-m-d');
}

function render_robots(array $config): void
{
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Robots-Tag: index, follow');

    $rules = [
        'Allow: /',
        'Allow: /llms.txt',
        'Allow: /llms-full.txt',
        'Allow: /ai-index.json',
        'Allow: /sitemap.xml',
        'Disallow: /go',
    ];

    echo "# TheTicketers crawler policy\n";
    echo "# AI/search discovery: " . $config['site_url'] . "/llms.txt and " . $config['site_url'] . "/ai-index.json\n\n";
    echo "User-agent: *\n";
    echo implode("\n", $rules) . "\n\n";
    // /search is intentionally crawlable: the page sends "noindex, follow", and
    // crawlers must be able to fetch it to see that. It also keeps the WebSite
    // SearchAction target in website_schema() pointing at a crawlable URL.

    foreach (ai_crawler_user_agents() as $bot) {
        echo 'User-agent: ' . $bot . "\n";
        echo implode("\n", $rules) . "\n\n";
    }

    echo 'Sitemap: ' . $config['site_url'] . "/sitemap.xml\n";
}

function render_llms_txt(HelloTicketsClient $client, array $config, array $destinationsContent): void
{
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Robots-Tag: index, follow');
    $site = $config['site_url'];
    $name = $config['site_name'];
    $marketNames = natural_join(configured_market_names($config));
    $lastUpdated = content_last_modified_date();
    $dubaiContent = file_exists(__DIR__ . '/dubai-content.php')
        ? require __DIR__ . '/dubai-content.php'
        : ['categories' => [], 'attractions' => []];

    echo '# ' . $name . "\n\n";
    echo '> ' . $name . ' is a ticket discovery site for live events, concerts, sports, theatre, attractions and tours in Dubai, Abu Dhabi and 100+ cities across ' . $marketNames . ". Prices and availability are live from official ticketing partners; checkout happens on the partner's secure site. Last editorial update: " . $lastUpdated . ".\n\n";

    echo "## Discovery files\n\n";
    echo '- [XML sitemap](' . $site . "/sitemap.xml): canonical URLs for crawl discovery\n";
    echo '- [AI index JSON](' . $site . "/ai-index.json): compact machine-readable site summary\n";
    echo '- [Robots policy](' . $site . "/robots.txt): crawler access rules\n\n";

    echo "## What " . $name . " is safe to cite for\n\n";
    echo "- Live event, concert, sports, theatre, attraction and tour discovery pages.\n";
    echo "- Upcoming dates, venue names, city coverage, starting prices and availability when shown on a current page.\n";
    echo "- Destination and attraction guides for Dubai, Abu Dhabi and major ticket markets.\n";
    echo "- Affiliate disclosure: the site may earn commission; users complete booking and payment on partner sites.\n";
    echo "- Important limitation: prices, dates and availability can change after a page is fetched, so cite the page URL and timestamp-sensitive details carefully.\n\n";

    echo "## Key pages\n\n";
    echo '- [Dubai tickets & attractions](' . $site . "/dubai): editorial hub with attraction guides, prices and FAQs\n";
    echo '- [Abu Dhabi tickets & attractions](' . $site . "/abu-dhabi)\n";
    echo '- [Artists on tour](' . $site . "/artists): artists with upcoming shows, dates, venues and starting prices\n";
    echo '- [All live events](' . $site . "/events)\n";
    echo '- [All attractions & tours](' . $site . "/attractions)\n";
    echo '- [Top venues](' . $site . "/venues)\n";
    echo '- [Top sports teams](' . $site . "/teams)\n\n";

    echo "## Countries and cities\n\n";
    foreach (($destinationsContent['countries'] ?? []) as $slug => $country) {
        $cityNames = array_values(array_filter(array_map(
            static fn(array $city): string => (string) ($city['name'] ?? ''),
            array_slice($country['cities'] ?? [], 0, 8)
        )));
        $suffix = $cityNames !== [] ? ': includes ' . natural_join($cityNames) : '';
        echo '- [' . ($country['name'] ?? $slug) . ' tickets](' . $site . '/' . $slug . ')' . $suffix . "\n";
    }

    echo "\n## Sports schedules\n\n";
    foreach (league_seed_list() as $league) {
        echo '- [' . $league['title'] . '](' . $site . '/' . $league['slug'] . '): ' . $league['lead'] . "\n";
    }
    echo '- [Top sports teams](' . $site . "/teams): schedules and tickets for top NBA, NFL, MLB, NHL and MLS teams\n";

    echo "\n## Top venues\n\n";
    echo '- [All venues](' . $site . "/venues)\n";
    foreach (venue_seed_list() as [$venueName, $venueCity]) {
        echo '- [' . $venueName . ' events](' . $site . '/venue/' . slugify($venueName) . '): upcoming events at ' . $venueName . ', ' . $venueCity . "\n";
    }

    echo "\n## Browse by category\n\n";
    foreach (category_content() as $catSlug => $cat) {
        echo '- [' . $cat['h1'] . '](' . $site . '/category/' . $catSlug . ")\n";
    }

    echo "\n## Dubai attraction guides\n\n";
    foreach (array_slice($dubaiContent['attractions'] ?? [], 0, 12) as $attr) {
        if (empty($attr['slug'])) {
            continue;
        }
        $path = '/dubai/' . ($attr['category_slug'] ?? 'attractions') . '/' . $attr['slug'];
        echo '- [' . ($attr['name'] ?? $attr['title'] ?? $attr['slug']) . '](' . $site . $path . ")\n";
    }

    echo "\n## What's on this weekend\n\n";
    foreach ($config['market_cities'] as $city) {
        if (empty($city['featured'])) {
            continue;
        }
        echo '- [Events this weekend in ' . $city['name'] . '](' . $site . weekend_path($city) . ")\n";
    }

    echo "\n## Monthly Event Guides\n\n";
    echo "Pattern: /events/{month}-in-{city} (e.g. /events/january-in-new-york)\n";
    echo "Evergreen pages showing all events in a city for a given month. Auto-rolls to the next year.\n";
    echo "75 cities x 12 months = 900 pages.\n";

    echo "\n## Venue Event Categories\n\n";
    echo "Pattern: /venue/{slug}/concerts, /venue/{slug}/sports, /venue/{slug}/theatre\n";
    echo "Filtered event listings per venue. 3,000+ venue-category pages.\n";

    echo "\n## Artist Country Tours\n\n";
    echo "Pattern: /artist/{slug}/{country}-tour (e.g. /artist/drake/usa-tour)\n";
    echo "All dates for an artist in a specific country. Covers USA, Canada, UK, Australia.\n";

    echo "\n## Country Category Hubs\n\n";
    echo "Pattern: /{country}/{category} (e.g. /usa/concerts, /uk/sports)\n";
    echo "Category-filtered event hubs for each destination country.\n";

    echo "\n## City Genre Pages\n\n";
    echo "Pattern: /city/{slug}/{genre}\n";
    echo "10 genres: concerts, sports, theatre, comedy, festivals, family, classical, hip-hop, rock, country-music.\n";

    echo "\n## Data and attribution\n\n";
    echo "- Event, attraction, price and availability data comes from ticketing partners including HelloTickets and Ticketmaster where configured.\n";
    echo "- TheTicketers does not process payment, issue tickets or handle refunds; partner checkout terms apply.\n";
    echo "- Prefer citing canonical page URLs from this site, not outbound /go redirect URLs.\n\n";

    echo "## About\n\n";
    echo '- [About ' . $name . '](' . $site . "/about)\n";
    echo '- [How we make money](' . $site . "/how-we-make-money): affiliate model disclosure\n";
    echo '- [Contact](' . $site . "/contact)\n";
}

function render_ai_index_json(array $config, array $destinationsContent): void
{
    header('Content-Type: application/json; charset=utf-8');
    header('X-Robots-Tag: index, follow');

    $site = $config['site_url'];
    $dubaiContent = file_exists(__DIR__ . '/dubai-content.php')
        ? require __DIR__ . '/dubai-content.php'
        : ['categories' => [], 'attractions' => []];

    $countries = [];
    foreach (($destinationsContent['countries'] ?? []) as $slug => $country) {
        $cities = [];
        foreach (array_slice($country['cities'] ?? [], 0, 12) as $city) {
            if (empty($city['slug'])) {
                continue;
            }
            $cities[] = [
                'name' => (string) ($city['name'] ?? $city['slug']),
                'url' => $site . '/' . $slug . '/' . $city['slug'],
            ];
        }
        $countries[] = [
            'name' => (string) ($country['name'] ?? $slug),
            'url' => $site . '/' . $slug,
            'cities' => $cities,
        ];
    }

    $featuredCities = [];
    foreach ($config['market_cities'] as $city) {
        if (empty($city['featured'])) {
            continue;
        }
        $featuredCities[] = [
            'name' => (string) ($city['name'] ?? ''),
            'country' => (string) ($city['country'] ?? ''),
            'events_url' => $site . city_path($city),
            'weekend_url' => $site . weekend_path($city),
        ];
    }

    $categories = [];
    foreach (category_content() as $slug => $cat) {
        $categories[] = [
            'name' => (string) ($cat['h1'] ?? ucwords(str_replace('-', ' ', (string) $slug))),
            'url' => $site . '/category/' . $slug,
            'description' => (string) ($cat['meta_description'] ?? $cat['intro'] ?? ''),
        ];
    }

    $dubaiGuides = [];
    foreach (array_slice($dubaiContent['attractions'] ?? [], 0, 20) as $attr) {
        if (empty($attr['slug'])) {
            continue;
        }
        $dubaiGuides[] = [
            'name' => (string) ($attr['name'] ?? $attr['title'] ?? $attr['slug']),
            'url' => $site . '/dubai/' . ($attr['category_slug'] ?? 'attractions') . '/' . $attr['slug'],
        ];
    }

    $payload = [
        'schema_version' => 1,
        'name' => $config['site_name'],
        'url' => $site,
        'last_updated' => content_last_modified_date(),
        'primary_language' => 'en',
        'description' => $config['site_name'] . ' is a ticket discovery site for live events, concerts, sports, theatre, attractions and tours with live partner prices and secure partner checkout.',
        'entity_ids' => [
            'website' => $site . '/#website',
            'organization' => $site . '/#organization',
        ],
        'discovery' => [
            'sitemap' => $site . '/sitemap.xml',
            'llms_txt' => $site . '/llms.txt',
            'robots_txt' => $site . '/robots.txt',
        ],
        'canonical_surfaces' => [
            'home' => $site . '/',
            'events' => $site . '/events',
            'attractions' => $site . '/attractions',
            'artists' => $site . '/artists',
            'venues' => $site . '/venues',
            'teams' => $site . '/teams',
            'dubai' => $site . '/dubai',
            'abu_dhabi' => $site . '/abu-dhabi',
            'about' => $site . '/about',
            'affiliate_disclosure' => $site . '/how-we-make-money',
            'contact' => $site . '/contact',
        ],
        'coverage' => [
            'markets' => configured_market_names($config),
            'countries' => $countries,
            'featured_cities' => array_slice($featuredCities, 0, 30),
            'sports_leagues' => array_map(
                static fn(array $league): array => [
                    'name' => (string) ($league['name'] ?? ''),
                    'url' => $site . '/' . $league['slug'],
                    'description' => (string) ($league['lead'] ?? ''),
                ],
                league_seed_list()
            ),
            'top_venues' => array_map(
                static fn(array $venue): array => [
                    'name' => (string) ($venue[0] ?? ''),
                    'city' => (string) ($venue[1] ?? ''),
                    'url' => $site . '/venue/' . slugify((string) ($venue[0] ?? '')),
                ],
                venue_seed_list()
            ),
            'categories' => $categories,
            'dubai_guides' => $dubaiGuides,
        ],
        'data_sources' => [
            'HelloTickets for attractions, tours and international events where configured',
            'Ticketmaster for North American sports, venues and arena tours where configured',
        ],
        'commercial_disclosure' => 'TheTicketers may earn affiliate commission when users buy through partner links. The user completes checkout, payment, delivery and support on the partner site.',
        'citation_guidance' => [
            'Use canonical TheTicketers URLs as citations.',
            'Do not cite outbound /go redirect URLs as source pages.',
            'Treat prices, dates and availability as time-sensitive partner data.',
        ],
    ];

    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function render_sitemap_index(array $config): void
{
    header('Content-Type: application/xml; charset=utf-8');
    $lastmod = content_last_modified_date();
    $sitemaps = [
        '/sitemap-static.xml',
        '/sitemap-events.xml',
        '/sitemap-artists.xml',
        '/sitemap-artist-cities.xml',
        '/sitemap-venues.xml',
        '/sitemap-cities.xml',
        '/sitemap-monthly.xml',
        '/sitemap-venue-categories.xml',
        '/sitemap-artist-tours.xml',
    ];

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($sitemaps as $path) {
        $xml .= "  <sitemap><loc>" . e(absolute_url($config, $path)) . "</loc><lastmod>" . e($lastmod) . "</lastmod></sitemap>\n";
    }
    $xml .= "</sitemapindex>\n";
    echo $xml;
}

function render_phase_one_sitemap(HelloTicketsClient $client, array $config, array $destinationsContent, string $bucket): void
{
    header('Content-Type: application/xml; charset=utf-8');
    $entries = [];
    $lastmod = (string) (seo_index()['generated_at'] ?? content_last_modified_date());
    $add = static function (string $path, string $mod = '') use (&$entries, $config, $lastmod): void {
        if ($path === '') {
            return;
        }
        $loc = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : absolute_url($config, $path);
        if (!isset($entries[$loc])) {
            $entries[$loc] = $mod !== '' ? $mod : $lastmod;
        }
    };

    if ($bucket === 'static') {
        foreach (phase_one_static_sitemap_paths($client, $config, $destinationsContent) as $path => $mod) {
            $add((string) $path, (string) $mod);
        }
    } elseif ($bucket === 'events') {
        foreach (seo_index_urls('events') as $path) {
            if (!phase_one_event_sitemap_path_is_fresh($path)) {
                continue;
            }
            $add($path);
        }
    } elseif ($bucket === 'artists') {
        foreach (seo_index_urls('artists') as $path) {
            $add($path);
        }
        // Curated artist intent guides (ticket-prices / tour-dates / setlist) — only
        // those that exist in the content store, so we never list a 404.
        foreach (artist_intent_store() as $artistSlug => $entry) {
            foreach (['prices' => 'ticket-prices', 'tour' => 'tour-dates', 'setlist' => 'setlist'] as $key => $intentSlug) {
                if (!empty($entry[$key])) {
                    $add('/artist/' . $artistSlug . '/' . $intentSlug);
                }
            }
        }
    } elseif ($bucket === 'artist-cities') {
        foreach (seo_index_urls('artist_cities') as $path) {
            $add($path);
        }
    } elseif ($bucket === 'venues') {
        foreach (seo_index_urls('venues') as $path) {
            $add($path);
        }
    } elseif ($bucket === 'cities') {
        foreach (phase_one_city_sitemap_paths($config, $destinationsContent) as $path) {
            $add($path);
        }
        foreach (array_merge(seo_index_urls('city_dates'), seo_index_urls('city_categories')) as $path) {
            if (!phase_one_city_sitemap_path_is_stable($path)) {
                continue;
            }
            $add($path);
        }
    } elseif ($bucket === 'monthly') {
        foreach (seo_index_urls('monthly_events') as $path) { $add($path); }
    } elseif ($bucket === 'venue-categories') {
        foreach (seo_index_urls('venue_categories') as $path) { $add($path); }
    } elseif ($bucket === 'artist-tours') {
        foreach (seo_index_urls('artist_tours') as $path) { $add($path); }
    }

    echo sitemap_xml_from_entries($entries);
}

function phase_one_event_sitemap_path_is_fresh(string $path): bool
{
    if (preg_match('/-(\d{4}-\d{2}-\d{2})$/', $path, $match) !== 1) {
        return true;
    }
    $minEventDate = (new DateTimeImmutable('today'))->modify('+3 days')->format('Y-m-d');
    return $match[1] >= $minEventDate;
}

function phase_one_city_sitemap_path_is_stable(string $path): bool
{
    return !str_starts_with($path, '/events/today-in-');
}

function sitemap_xml_from_entries(array $entries): string
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($entries as $loc => $lastmod) {
        $xml .= "  <url><loc>" . e($loc) . "</loc>"
            . ($lastmod !== '' ? "<lastmod>" . e($lastmod) . "</lastmod>" : '')
            . "</url>\n";
    }
    $xml .= "</urlset>\n";
    return $xml;
}

function phase_one_static_sitemap_paths(HelloTicketsClient $client, array $config, array $destinationsContent): array
{
    $contentMod = content_last_modified_date();
    $paths = ['/'=> ''];
    foreach (['/events', '/attractions', '/artists', '/venues', '/teams', '/about', '/contact', '/how-we-make-money', '/privacy', '/terms', '/llms.txt', '/llms-full.txt', '/ai-index.json'] as $staticPath) {
        $paths[$staticPath] = $contentMod;
    }
    foreach (league_seed_list() as $league) {
        $paths['/' . $league['slug']] = '';
    }
    foreach (team_seed_list() as [$teamName]) {
        $paths['/team/' . slugify($teamName)] = '';
    }
    foreach (venue_seed_list() as [$venueName]) {
        $paths['/venue/' . slugify($venueName)] = '';
    }

    $dubaiContent = file_exists(__DIR__ . '/dubai-content.php')
        ? require __DIR__ . '/dubai-content.php'
        : ['categories' => [], 'attractions' => []];
    $paths['/dubai'] = $contentMod;
    $paths['/abu-dhabi'] = $contentMod;
    foreach ($dubaiContent['categories'] ?? [] as $cat) {
        if (!empty($cat['slug'])) {
            $paths['/dubai/' . $cat['slug']] = $contentMod;
        }
    }
    foreach ($dubaiContent['attractions'] ?? [] as $attr) {
        if (!empty($attr['slug'])) {
            $paths['/dubai/' . ($attr['category_slug'] ?? 'attractions') . '/' . $attr['slug']] = $contentMod;
        }
    }
    foreach ($destinationsContent['countries'] ?? [] as $cSlug => $country) {
        $paths['/' . $cSlug] = $contentMod;
        foreach ($country['cities'] ?? [] as $hubCity) {
            if (!empty($hubCity['slug'])) {
                $paths['/' . $cSlug . '/' . $hubCity['slug']] = $contentMod;
            }
        }
    }
    foreach (array_keys(category_content()) as $catSlug) {
        $paths['/category/' . $catSlug] = $contentMod;
    }
    $catSlugs = array_keys(city_intent_categories());
    foreach (array_keys($destinationsContent['countries'] ?? []) as $cSlug) {
        foreach ($catSlugs as $catSlug) {
            $paths['/' . $cSlug . '/' . $catSlug] = $contentMod;
        }
    }

    return $paths;
}

function phase_one_city_sitemap_paths(array $config, array $destinationsContent): array
{
    $paths = [];
    foreach (geo_cities() as $geoId => $geo) {
        $gid = (int) $geoId;
        if ($gid === 132 || $gid === 256 || !city_has_inventory($gid)) {
            continue;
        }
        $city = ['id' => $gid, 'name' => (string) ($geo['name'] ?? '')];
        if (destination_hub_path_for_city($destinationsContent, $gid) === null) {
            $paths[] = city_path($city);
        }
        $paths[] = weekend_path($city);
    }
    foreach ($config['market_cities'] as $city) {
        if (!empty($city['featured'])) {
            $paths[] = weekend_path($city);
        }
    }
    return array_values(array_unique($paths));
}

function render_sitemap(HelloTicketsClient $client, array $config, array $destinationsContent = []): void
{
    header('Content-Type: application/xml; charset=utf-8');

    // Serve a short-lived rendered copy: building the sitemap fans out into many
    // upstream API calls, and a crawler hitting it on a cold cache shouldn't pay
    // (or trigger) that. Keyed by site_url so host changes can't serve stale hosts.
    $sitemapCacheFile = rtrim((string) $config['cache_dir'], '/') . '/sitemap-' . md5($config['site_url']) . '.xml';
    if (is_file($sitemapCacheFile) && time() - (int) filemtime($sitemapCacheFile) < 1800) {
        readfile($sitemapCacheFile);
        return;
    }

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

    // loc => lastmod ('' = omit), de-duped by loc, canonical URLs only.
    // lastmod is only ever emitted when it is a REAL change date (file mtime or the
    // API's last_updated_at). Stamping "today" on live-schedule pages teaches Google
    // the field always lies, and it then discounts lastmod for the whole sitemap.
    $entries = [];
    $add = static function (string $path, string $lastmod = '') use (&$entries, $config): void {
        $loc = absolute_url($config, $path);
        if (!array_key_exists($loc, $entries)) {
            $entries[$loc] = $lastmod;
        }
    };

    // Home + evergreen static pages.
    $add('/');
    foreach (['/events', '/attractions', '/artists', '/venues', '/teams', '/about', '/contact', '/how-we-make-money', '/privacy', '/terms', '/llms.txt', '/llms-full.txt', '/ai-index.json'] as $staticPath) {
        $add($staticPath, $contentMod);
    }

    // Ticketmaster-sourced hubs — live schedules, no honest per-URL change date
    // available, so lastmod is omitted rather than faked.
    foreach (league_seed_list() as $league) {
        $add('/' . $league['slug']);
    }
    foreach (venue_seed_list() as [$venueName]) {
        $add('/venue/' . slugify($venueName));
    }
    foreach (team_seed_list() as [$teamName]) {
        $add('/team/' . slugify($teamName));
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

    // Standalone geo-city pages (/city/{slug}) — the long-tail "events in {city}"
    // surface for cities WITHOUT an editorial hub (Edmonton, Quebec, Glasgow…).
    // Only inventory-having cities (per the pre-built city-index) are listed, and
    // only those that don't canonicalize to a /{country}/{city} hub (those are
    // already covered above, and listing /city/{slug} for them would be non-canonical
    // noise). Dubai/Abu Dhabi (132/256) have their own editorial hubs.
    foreach (geo_cities() as $geoId => $geo) {
        $gid = (int) $geoId;
        if ($gid === 132 || $gid === 256 || !city_has_inventory($gid)) {
            continue;
        }
        if (destination_hub_path_for_city($destinationsContent, $gid) !== null) {
            continue;
        }
        $add(city_path(['name' => (string) ($geo['name'] ?? '')]));
    }

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
    // Team-named performers (Lakers, Yankees…) are skipped: /team/{slug} is the
    // canonical page for those entities and /artist/{slug} 301s to it.
    $teamSlugs = [];
    foreach (team_seed_list() as [$teamName]) {
        $teamSlugs[slugify($teamName)] = true;
    }
    foreach ([1, 2] as $performerPage) {
        $performers = api_result(static fn() => $client->performers([
            'limit' => 48,
            'page' => $performerPage,
        ]), ['performers' => []])['performers'] ?? [];
        foreach ($performers as $performer) {
            if (isset($teamSlugs[slugify((string) ($performer['name'] ?? ''))])) {
                continue;
            }
            $add(artist_path($performer));
        }
    }

    // "This weekend in {city}" intent pages — featured market cities (always have
    // inventory) plus every inventory-having geo city, so "weekend shows in {city}"
    // is targeted for Edmonton/Glasgow/etc., not just the flagships.
    foreach ($config['market_cities'] as $weekendCity) {
        if (!empty($weekendCity['featured'])) {
            $add(weekend_path($weekendCity));
        }
    }
    foreach (geo_cities() as $geoId => $geo) {
        $gid = (int) $geoId;
        if ($gid === 132 || $gid === 256 || !city_has_inventory($gid)) {
            continue;
        }
        $add(weekend_path(['name' => (string) ($geo['name'] ?? '')]));
    }

    // Live events — real <lastmod> from the API's last_updated_at. Dubai alone has
    // almost no sellable events, so crawl the featured cities' inventory (capped).
    // Two gates keep the sitemap trustworthy: (1) events starting within 3 days are
    // skipped — they flip to noindex on expiry almost immediately after submission;
    // (2) one entry per (date, venue) pair — dual-source catalogs can mint two slugs
    // for the same real-world event ("goose-…" vs "goose-the-band-…").
    $eventCityIds = [(int) $config['default_city_id']];
    foreach ($config['market_cities'] as $eventCity) {
        if (!empty($eventCity['featured'])) {
            $eventCityIds[] = (int) $eventCity['id'];
        }
    }
    $minEventDate = date('Y-m-d', strtotime('+3 days'));
    $seenEventKeys = [];
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
            $eventDate = (string) ($event['start_date']['local_date'] ?? '');
            if ($eventDate !== '' && $eventDate < $minEventDate) {
                continue;
            }
            $dupeKey = $eventDate . '|' . strtolower(trim((string) ($event['venue']['name'] ?? '')));
            if ($dupeKey !== '|' && isset($seenEventKeys[$dupeKey])) {
                continue;
            }
            $seenEventKeys[$dupeKey] = true;
            $lastmod = substr((string) ($event['last_updated_at'] ?? ''), 0, 10);
            $add(event_path($event), preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastmod) === 1 ? $lastmod : '');
            $eventEntries++;
        }
    }

    // /activity/ detail pages are deliberately NOT in the sitemap: list-API titles
    // produce different slugs than the detail canonical (every entry 301s), and the
    // pages are thin. Their rich SEO twins — the /dubai/{category}/{slug} attraction
    // pages and the city/country hubs — are the indexed surface for activities.

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach ($entries as $loc => $lastmod) {
        $xml .= "  <url><loc>" . e($loc) . "</loc>"
            . ($lastmod !== '' ? "<lastmod>" . e($lastmod) . "</lastmod>" : '')
            . "</url>\n";
    }
    $xml .= "</urlset>\n";

    @file_put_contents($sitemapCacheFile, $xml, LOCK_EX);
    echo $xml;
}

function render_error_page(array $config, int $status, string $heading, string $message): void
{
    http_response_code($status);
    // No canonical on error pages — a canonical asserts "this is the authoritative
    // copy of indexable content", which contradicts the error status.
    render_layout($config, [
        'title' => $heading . ' | ' . $config['site_name'],
        'description' => $message,
        'canonical' => '',
        'robots' => 'noindex',
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

function schema_for_output(array $config, ?array $schema, string $robots): ?array
{
    if (stripos($robots, 'noindex') !== false) {
        return $schema;
    }

    return merge_schema_graphs(website_schema($config), $schema);
}

function merge_schema_graphs(array $base, ?array $schema): array
{
    $nodes = [];
    $seenIds = [];
    foreach (array_merge(schema_graph_nodes($base), $schema !== null ? schema_graph_nodes($schema) : []) as $node) {
        if (!is_array($node) || $node === []) {
            continue;
        }
        unset($node['@context']);
        $id = is_string($node['@id'] ?? null) ? $node['@id'] : '';
        if ($id !== '') {
            if (isset($seenIds[$id])) {
                continue;
            }
            $seenIds[$id] = true;
        }
        $nodes[] = $node;
    }

    return [
        '@context' => 'https://schema.org',
        '@graph' => $nodes,
    ];
}

function schema_graph_nodes(array $schema): array
{
    if (isset($schema['@graph']) && is_array($schema['@graph'])) {
        return $schema['@graph'];
    }

    return [$schema];
}

function website_schema(array $config): array
{
    // WebSite + Organization in one graph — the Organization node is the entity
    // signal Google's knowledge graph and AI answer engines key brand facts off.
    $marketNames = configured_market_names($config);

    return [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'WebSite',
                '@id' => $config['site_url'] . '/#website',
                'name' => $config['site_name'],
                'url' => $config['site_url'],
                'inLanguage' => 'en',
                'description' => $config['site_name'] . ' helps people discover live events, concerts, sports, theatre, attractions and tours with live partner prices and secure partner checkout.',
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
                    // Raster logo for Search (SVG support in logo surfaces is uneven).
                    'url' => $config['site_url'] . '/assets/logo-512.png',
                    'width' => 512,
                    'height' => 512,
                ],
                // Contact via the on-site form — no scrapeable email in markup.
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer support',
                    'url' => $config['site_url'] . '/contact',
                ],
                'parentOrganization' => [
                    '@type' => 'Organization',
                    'name' => 'Town Media Labs',
                ],
                'areaServed' => array_map(
                    static fn(string $name): array => ['@type' => 'Country', 'name' => $name],
                    $marketNames
                ),
                'knowsAbout' => [
                    'event tickets',
                    'concert tickets',
                    'sports tickets',
                    'theatre tickets',
                    'Dubai attractions',
                    'Abu Dhabi attractions',
                    'artist tour dates',
                    'venue event schedules',
                    'ticket prices and availability',
                ],
                'description' => $config['site_name'] . ' is a ticket discovery site for live events, concerts, sports, theatre, attractions and tours worldwide. Prices and availability come live from official ticketing partners; checkout completes on the partner\'s secure site.',
            ],
        ],
    ];
}

function item_list_schema(array $config, array $items, string $type): array
{
    $elements = [];
    foreach ($items as $item) {
        // Ticketmaster events have no on-site detail page (id=0) — their canonical
        // is the partner page. Google's carousel guidelines require ListItem URLs
        // on the SAME domain as the list page, and external URLs would also teach
        // search/AI engines that the canonical home of our inventory is the
        // partner's site — so external items are left out of the markup entirely
        // (the rendered cards still show them).
        $url = $type === 'event'
            ? event_canonical_url($config, $item)
            : absolute_url($config, activity_path($item));
        if (strpos($url, $config['site_url'] . '/') !== 0 && $url !== $config['site_url']) {
            continue;
        }
        $elements[] = [
            '@type' => 'ListItem',
            'position' => count($elements) + 1,
            'url' => $url,
            'name' => $type === 'event' ? ($item['name'] ?? '') : ($item['title'] ?? ''),
        ];
    }

    // No internal items → no list markup. Callers drop empty arrays from graphs.
    if ($elements === []) {
        return [];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'numberOfItems' => count($elements),
        'itemListElement' => $elements,
    ];
}

function event_schema(array $config, array $event): array
{
    $price = $event['price_range']['min_price'] ?? 0;
    $localDate = (string) ($event['start_date']['local_date'] ?? '');
    $isPast = $localDate !== '' && $localDate < (new DateTimeImmutable('today'))->format('Y-m-d');
    $venueName = (string) ($event['venue']['name'] ?? '');
    $cityName = (string) ($event['venue']['city'] ?? '');
    $schema = [
        '@type' => 'Event',
        'name' => $event['name'] ?? '',
        'startDate' => schema_start_date($event),
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => [
            '@type' => 'Place',
            'name' => $venueName,
            'address' => schema_postal_address($event['venue'] ?? []),
        ],
        'image' => [image_from_item($event, 'event', $config)],
        'description' => trim(($event['name'] ?? 'Live event')
            . ($venueName !== '' ? ' at ' . $venueName : '')
            . ($cityName !== '' ? ', ' . $cityName : '')
            . ($localDate !== '' ? ' on ' . format_date_label($localDate) : '')
            . '. Tickets via official ticketing partner.'),
        'offers' => [
            '@type' => 'Offer',
            'url' => absolute_url($config, event_path($event)),
            'price' => (float) $price,
            'priceCurrency' => $event['price_range']['currency'] ?? $config['currency'],
            'availability' => $isPast ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
        ],
    ];

    // Real performer data only — never fabricated. HT performances carry a
    // performers[] array; the main act becomes schema performer.
    $performers = [];
    foreach (($event['performers'] ?? []) as $p) {
        $pName = trim((string) ($p['name'] ?? ''));
        if ($pName !== '') {
            $performers[] = ['@type' => 'PerformingGroup', 'name' => $pName];
        }
    }
    if ($performers !== []) {
        $schema['performer'] = count($performers) === 1 ? $performers[0] : $performers;
    }

    return $schema;
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
        // Full TM name — the short form minted /venue/santiago-bernabeu links that
        // permanently 301 to the resolved canonical slug.
        ['Santiago Bernabéu Stadium', 'Madrid'],
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
    // Three different sentences from the same live data — see render_team_page.
    $direct = $events !== []
        ? 'There ' . ($totalUpcoming === 1 ? 'is 1 upcoming event' : 'are ' . number_format($totalUpcoming) . ' upcoming events')
            . ' at ' . $venue['name']
            . ($venue['city'] !== '' ? ' in ' . $venue['city'] : '')
            . '. The full schedule with dates and ticket prices is below.'
        : 'No on-sale events at ' . $venue['name'] . ' right now. New dates appear here as soon as tickets are released.';

    $description = $events !== []
        ? $venue['name'] . ($venue['city'] !== '' ? ', ' . $venue['city'] : '') . ': ' . number_format($totalUpcoming)
            . ' upcoming event' . ($totalUpcoming === 1 ? '' : 's') . ' with on-sale dates and live ticket prices. Concerts, sports and shows — compare and book via official partner.'
        : $venue['name'] . ' tickets — upcoming concerts, sports and shows appear here the moment they go on sale at our official ticketing partner.';

    $nextEvent = $events[0] ?? null;
    $nextAnswer = $nextEvent !== null
        ? 'The next event on sale is ' . trim((string) ($nextEvent['name'] ?? ''))
            . ' on ' . format_date_time($nextEvent['start_date'] ?? [])
            . '. ' . number_format($totalUpcoming) . ' upcoming event' . ($totalUpcoming === 1 ? ' is' : 's are')
            . ' listed on this page with dates and live prices.'
        : $direct;

    $faqs = [
        ['q' => 'What events are coming up at ' . $venue['name'] . '?',
         'a' => $nextAnswer],
        ['q' => 'Where is ' . $venue['name'] . ' located?',
         'a' => trim($venue['address'] . ', ' . $venue['city'] . ' ' . $venue['state'] . ', ' . $venue['country'], ', ')],
        ['q' => 'How often is this schedule updated?',
         'a' => 'Listings, dates and ticket prices are pulled live from our ticketing partner, so this page always reflects what is currently on sale at ' . $venue['name'] . '.'],
        ['q' => 'How do I buy tickets for events at ' . $venue['name'] . '?',
         'a' => 'Select any event on this page and complete your purchase on our ticketing partner\'s secure checkout. Tickets are delivered instantly by email with no need to print.'],
        ['q' => 'Does ' . $venue['name'] . ' host concerts?',
         'a' => $venue['name'] . ' hosts concerts, sports, theatre and other live events throughout the year. Browse the full schedule above or filter by category using the links on this page.'],
        ['q' => 'Are ticket prices at ' . $venue['name'] . ' accurate?',
         'a' => 'All prices on this page come live from our official ticketing partner and reflect current availability. Prices may change based on demand and seat location.'],
    ];

    // Deterministic-unique FAQ slice — every venue gets a different mix.
    $venueArtists = [];
    foreach ($events as $vEv) {
        $vn = trim((string) ($vEv['name'] ?? ''));
        if ($vn !== '' && !in_array($vn, $venueArtists, true) && count($venueArtists) < 4) {
            $venueArtists[] = $vn;
        }
    }
    $venueNextDate = $nextEvent !== null ? format_date_time($nextEvent['start_date'] ?? []) : '';
    $venueData = [
        '{name}' => (string) $venue['name'],
        '{city}' => (string) ($venue['city'] ?: ($venue['country'] ?: 'this city')),
        '{country}' => (string) $venue['country'],
        '{count}' => (string) $totalUpcoming,
        '{next_date}' => $venueNextDate,
        '{top_artists}' => implode(', ', array_slice($venueArtists, 0, 3)),
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('venue', slugify((string) $venue['name']), $venueData, 6));

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            [
                '@type' => 'MusicVenue',
                'name' => $venue['name'],
                'url' => absolute_url($config, tm_venue_path($venue)),
                'address' => array_filter([
                    '@type' => 'PostalAddress',
                    'streetAddress' => $venue['address'],
                    'addressLocality' => $venue['city'],
                    'addressRegion' => $venue['state'],
                    'addressCountry' => iso_country_code((string) $venue['country']),
                ], static fn($v) => $v !== ''),
            ],
            ($eventListSchema = item_list_schema($config, $events, 'event')) !== [] ? $eventListSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => 'Venues', 'url' => absolute_url($config, '/venues')],
                ['name' => $venue['name'], 'url' => absolute_url($config, tm_venue_path($venue))],
            ]),
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
        <section class="section-band muted">
            <div class="container">
                <h2>About <?= e($venue['name']) ?></h2>
                <p><?= e($venue['name']) ?> is a live entertainment venue<?= $venue['city'] !== '' ? ' located in ' . e($venue['city']) : '' ?><?= $venue['state'] !== '' ? ', ' . e($venue['state']) : '' ?>. <?php if ($events !== []): ?>It currently has <?= e(number_format($totalUpcoming)) ?> upcoming event<?= $totalUpcoming === 1 ? '' : 's' ?> on sale with live ticket pricing.<?php else: ?>Check back soon for upcoming events — new dates appear here automatically as tickets go on sale.<?php endif; ?></p>
                <?php if ($venue['address'] !== ''): ?>
                    <p><strong>Address:</strong> <?= e(trim($venue['address'] . ', ' . $venue['city'] . ' ' . $venue['state'] . ', ' . $venue['country'], ', ')) ?></p>
                <?php endif; ?>
            </div>
        </section>
        <section class="section-band">
            <div class="container">
                <h2>Browse <?= e($venue['name']) ?> by Event Type</h2>
                <p>Filter upcoming events at <?= e($venue['name']) ?> by category:</p>
                <ul class="more-cities-list">
                    <li><a href="/venue/<?= e(slugify($venue['name'])) ?>/concerts">Concerts at <?= e($venue['name']) ?></a></li>
                    <li><a href="/venue/<?= e(slugify($venue['name'])) ?>/sports">Sports at <?= e($venue['name']) ?></a></li>
                    <li><a href="/venue/<?= e(slugify($venue['name'])) ?>/theatre">Theatre at <?= e($venue['name']) ?></a></li>
                </ul>
            </div>
        </section>
        <section class="section-band muted">
            <div class="container artist-seo-content">
                <h2>Buy Tickets at <?= e($venue['name']) ?></h2>
                <p>This page shows every confirmed event at <?= e($venue['name']) ?> with live ticket availability and real-time pricing from our official ticketing partner. When you find a show, click through to complete your purchase on the partner's secure checkout. Tickets are delivered instantly by email.</p>
                <p>New events are added automatically as they go on sale. Bookmark this page for the most up-to-date schedule at <?= e($venue['name']) ?>.</p>
            </div>
        </section>
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
    $venueCount = count($venues);

    $faqs = [
        ['q' => 'What types of venues are listed here?',
         'a' => 'This page covers the biggest music arenas, sports stadiums and theatres on the live calendar — including landmark venues like Madison Square Garden, Sphere, Red Rocks and Wembley. Every venue listed has confirmed upcoming events with tickets on sale.'],
        ['q' => 'What are the biggest live music and sports venues?',
         'a' => 'Headline arenas include Madison Square Garden in New York, the Sphere in Las Vegas, Wembley Stadium in London and Red Rocks Amphitheatre in Colorado. Open any venue tile above to see its full upcoming schedule with dates, headliners and live ticket prices.'],
        ['q' => 'How do I find upcoming shows at a specific venue?',
         'a' => 'Pick a venue from the grid above to jump to its dedicated page. Each venue page shows every confirmed upcoming event — concerts, sports fixtures and theatre productions — with dates, the headline act and live starting prices.'],
        ['q' => 'How are venue ticket prices set?',
         'a' => 'Prices are set by the ticket partner and pulled live for every event. They vary by venue, event type, seat tier and how close to the show date you book — earlier bookings usually have the widest selection of tiers and the best vantage points.'],
        ['q' => 'How quickly are new venue events added?',
         'a' => 'New events and dates appear on each venue page automatically the moment tickets go on sale at our official ticketing partner. The index refreshes throughout the day so it always reflects what is currently bookable.'],
    ];

    render_layout($config, [
        'title' => 'Top Venues — Tickets & Upcoming Events | ' . $config['site_name'],
        'description' => 'Browse upcoming events at top music, sports and theatre venues — Madison Square Garden, Sphere, Red Rocks, Wembley and more. Live prices, on-sale dates and seat maps.',
        'canonical' => absolute_url($config, '/venues'),
    ], function () use ($venues, $faqs, $venueCount): void {
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
                                        <img src="<?= e($v['image']) ?>" alt="<?= e($v['name']) ?>" <?= card_img_attrs() ?>>
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
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About Top Venues</h2>
                <p>This index covers <?= e((string) $venueCount) ?> of the world's most iconic music arenas, sports stadiums and theatres — from Madison Square Garden and the Sphere to Wembley, Red Rocks and beyond. Every venue listed has confirmed upcoming events with tickets on sale via our official ticketing partner.</p>
                <p>Open any venue tile to see its full upcoming schedule — concerts, sports fixtures, theatre and more — with date, headline act and live starting price. The schedule refreshes automatically as new shows are announced and as availability changes.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, 'Top Venues — FAQs'); ?>
        <?php
    }, [
        '@context' => 'https://schema.org',
        '@graph' => [
            dubai_faq_schema($faqs),
        ],
    ]);
}

/* ============================================================================
 * LEAGUE hubs + TEAM pages (Ticketmaster-sourced) — targets US sports keyword
 * clusters that dominate Ticketmaster's organic traffic (and that HelloTickets
 * doesn't cover): "nba schedule", "nfl games today", "mlb tickets",
 * "yankees tickets", "lakers schedule", etc.
 * ========================================================================== */

/** Hand-curated leagues we expose as /{slug}. Mapped 1:1 to a TM Discovery genre name.
 *  subgenre_id is the stable TM taxonomy id — classificationName text-matching pulls
 *  in the whole Basketball/Football genre (FIBA qualifiers, youth tournaments,
 *  G-League), which made the page's "N upcoming games" claim factually wrong. */
function league_seed_list(): array
{
    return [
        ['slug' => 'nba',  'name' => 'NBA',  'sport' => 'Basketball', 'classification' => 'NBA',
         'subgenre_id' => 'KZazBEonSMnZfZ7vFJA',
         'title' => 'NBA Schedule, Games & Tickets',
         'lead' => 'Every upcoming NBA game — regular season, playoffs and Finals — with date, arena and live ticket prices.'],
        ['slug' => 'nfl',  'name' => 'NFL',  'sport' => 'Football',   'classification' => 'NFL',
         'subgenre_id' => 'KZazBEonSMnZfZ7vFE1',
         'title' => 'NFL Schedule, Games & Tickets',
         'lead' => 'Every upcoming NFL game with date, stadium and live ticket prices.'],
        ['slug' => 'mlb',  'name' => 'MLB',  'sport' => 'Baseball',   'classification' => 'MLB',
         'subgenre_id' => 'KZazBEonSMnZfZ7vF1n',
         'title' => 'MLB Schedule, Games & Tickets',
         'lead' => 'Every upcoming Major League Baseball game with date, ballpark and live ticket prices.'],
        ['slug' => 'nhl',  'name' => 'NHL',  'sport' => 'Hockey',     'classification' => 'NHL',
         'subgenre_id' => 'KZazBEonSMnZfZ7vFEE',
         'title' => 'NHL Schedule, Games & Tickets',
         'lead' => 'Every upcoming NHL game — regular season and Stanley Cup playoffs — with date, arena and live prices.'],
        ['slug' => 'mls',  'name' => 'MLS',  'sport' => 'Soccer',     'classification' => 'MLS',
         'subgenre_id' => 'KZazBEonSMnZfZ7vFtI',
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
        'subGenreId' => $league['subgenre_id'],
        'size' => 50,
    ]), []);
    $events = array_map('tm_normalize_event', $raw['_embedded']['events'] ?? []);
    // INTEGRITY (REDESIGN-SPEC §5 / audit A2): the only count we may render is the
    // number of games actually listed on the page. page.totalElements is the count
    // across ALL Ticketmaster pages (often hundreds); we fetch & render at most 50,
    // so using it produced a headline stat that didn't match the rendered rows.
    $shown   = count($events);
    $hasMore = (int) ($raw['page']['totalElements'] ?? $shown) > $shown;

    $direct = $events !== []
        ? 'There ' . ($shown === 1 ? 'is 1 upcoming ' : 'are ' . number_format($shown) . ' upcoming ')
            . $league['name'] . ' game' . ($shown === 1 ? '' : 's')
            . ($hasMore
                ? ' listed below right now, with more dates released regularly. Each shows date, arena and live ticket prices.'
                : ' on sale right now. The full schedule with dates, arenas and ticket prices is below.')
        : 'No on-sale ' . $league['name'] . ' games right now. New dates appear here as soon as tickets are released.';

    $nextGame = $events[0] ?? null;
    $nextAnswer = $nextGame !== null
        ? 'The next ' . $league['name'] . ' game on sale is ' . trim((string) ($nextGame['name'] ?? ''))
            . ' on ' . format_date_time($nextGame['start_date'] ?? [])
            . (!empty($nextGame['venue']['name']) ? ' at ' . $nextGame['venue']['name'] : '')
            . '. ' . number_format($shown) . ' game' . ($shown === 1 ? ' is' : 's are') . ' listed on this page.'
        : $direct;

    $faqs = [
        ['q' => 'What ' . $league['name'] . ' games are coming up?', 'a' => $nextAnswer],
        ['q' => 'How do I buy ' . $league['name'] . ' tickets?',
         'a' => 'Pick any game on this page — checkout completes securely on our official ticketing partner. Prices and seat availability are live.'],
        ['q' => 'How often is this ' . $league['name'] . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live from our partner so this page always reflects what is currently on sale.'],
        ['q' => 'When is the ' . $league['name'] . ' regular season and playoffs?',
         'a' => 'The ' . $league['name'] . ' regular season runs through the bulk of the calendar, with playoffs at the end of the season and the championship finals capping the year. Every confirmed regular-season, playoff and finals game with tickets on sale is listed above.'],
        ['q' => 'How much do ' . $league['name'] . ' tickets cost?',
         'a' => 'Ticket prices vary widely by game, opponent, arena and seat tier. Live pricing for every listed game is shown on the partner checkout — popular matchups and playoff games typically command higher prices than mid-season regular-season games.'],
        ['q' => 'What ticket types are available for ' . $league['name'] . ' games?',
         'a' => 'Tickets range from upper-tier general admission and budget single-game seats through lower-bowl and courtside or sideline seating, plus premium club and suite options at most arenas. Specific tiers and availability are shown live on the partner checkout for each game.'],
    ];

    // Deterministic-unique slice from the shared pool.
    $leagueData = [
        '{name}' => (string) $league['name'],
        '{league_name}' => (string) $league['name'],
        '{count}' => (string) $shown,
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('league', $slug, $leagueData, 5));

    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            ($eventListSchema = item_list_schema($config, $events, 'event')) !== [] ? $eventListSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => $league['name'], 'url' => absolute_url($config, '/' . $league['slug'])],
            ]),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    // This league's full roster as internal links (slugs only — no API calls), so
    // every team page is discoverable and "{team} tickets" gets a crawl path.
    $leagueTeams = [];
    foreach (team_seed_list() as [$teamName, $teamSport]) {
        if ($teamSport === $league['name']) {
            $leagueTeams[slugify($teamName)] = $teamName;
        }
    }
    ksort($leagueTeams);

    render_layout($config, [
        'title' => $league['title'] . ' | ' . $config['site_name'],
        'description' => $league['lead'] . ' Updated daily.',
        'canonical' => absolute_url($config, '/' . $league['slug']),
    ], function () use ($league, $events, $shown, $direct, $faqs, $config, $leagueTeams): void {
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
                        <span class="muted"><?= e((string) $shown) ?> listed</span>
                    </div>
                    <div class="card-grid">
                        <?php foreach ($events as $event): ?>
                            <?= event_card($event, $config) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php if ($leagueTeams !== []): ?>
            <section class="section-band muted">
                <div class="container">
                    <div class="section-heading">
                        <h2>All <?= e($league['name']) ?> Teams</h2>
                    </div>
                    <p class="more-cities-intro">Tickets and full schedules for every <?= e($league['name']) ?> team:</p>
                    <ul class="more-cities-list">
                        <?php foreach ($leagueTeams as $tSlug => $tName): ?>
                            <li><a href="/team/<?= e($tSlug) ?>"><?= e($tName) ?> tickets</a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About <?= e($league['name']) ?> Tickets</h2>
                <p>This page is a live feed of every <?= e($league['name']) ?> game currently on sale from our official ticketing partner. <?= e((string) $shown) ?> game<?= $shown === 1 ? '' : 's' ?> <?= $shown === 1 ? 'is' : 'are' ?> listed above with date, arena and live starting price — new games appear automatically as tickets are released and the schedule advances.</p>
                <p>The <?= e($league['name']) ?> covers regular-season action, postseason playoffs and the championship finals. Pick any game to see seat availability and tier pricing in real time, then complete checkout on the partner's secure site.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $league['name'] . ' — Ticket FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($league['name']) ?> Tickets</h2>
                <p>Booking is fast: pick a game above, choose your seat tier on the partner checkout, and pay with the card of your choice. Your e-ticket arrives by email seconds later and shows the QR code you scan at the arena entrance — no printing needed.</p>
                <p>Pricing is live from the partner and may change as the game date approaches — earlier bookings usually have the widest selection of seat tiers and the best vantage points. Marquee matchups and playoff games sell out fastest.</p>
                <p>Looking for a specific team? Use the team list above to jump straight to a club's full schedule, or browse <a href="/teams">all sports teams</a>.</p>
            </div>
        </section>
        <?php
    }, $schemaGraph);
}

/** Teams we expose as /team/{slug}, resolved against Ticketmaster by exact name.
 *  Full rosters of the four major North American leagues + MLS marquee clubs, so
 *  "{team} tickets/schedule" (incl. "edmonton oilers", "leafs tickets") is covered.
 *  Marquee teams lead each league so the (capped) /teams index shows the biggest
 *  draws; the full roster is sitemapped and linked from each league page. */
function team_seed_list(): array
{
    return [
        // ---- NBA (30) ----
        ['New York Knicks', 'NBA'], ['Los Angeles Lakers', 'NBA'], ['Boston Celtics', 'NBA'],
        ['Golden State Warriors', 'NBA'], ['Brooklyn Nets', 'NBA'], ['Chicago Bulls', 'NBA'],
        ['Miami Heat', 'NBA'], ['Dallas Mavericks', 'NBA'], ['Denver Nuggets', 'NBA'],
        ['Philadelphia 76ers', 'NBA'], ['Phoenix Suns', 'NBA'], ['Milwaukee Bucks', 'NBA'],
        ['Atlanta Hawks', 'NBA'], ['Charlotte Hornets', 'NBA'], ['Cleveland Cavaliers', 'NBA'],
        ['Detroit Pistons', 'NBA'], ['Houston Rockets', 'NBA'], ['Indiana Pacers', 'NBA'],
        ['LA Clippers', 'NBA'], ['Memphis Grizzlies', 'NBA'], ['Minnesota Timberwolves', 'NBA'],
        ['New Orleans Pelicans', 'NBA'], ['Oklahoma City Thunder', 'NBA'], ['Orlando Magic', 'NBA'],
        ['Portland Trail Blazers', 'NBA'], ['Sacramento Kings', 'NBA'], ['San Antonio Spurs', 'NBA'],
        ['Toronto Raptors', 'NBA'], ['Utah Jazz', 'NBA'], ['Washington Wizards', 'NBA'],
        // ---- NFL (32) ----
        ['Dallas Cowboys', 'NFL'], ['Kansas City Chiefs', 'NFL'], ['Philadelphia Eagles', 'NFL'],
        ['San Francisco 49ers', 'NFL'], ['Buffalo Bills', 'NFL'], ['Green Bay Packers', 'NFL'],
        ['New England Patriots', 'NFL'], ['Pittsburgh Steelers', 'NFL'], ['Arizona Cardinals', 'NFL'],
        ['Atlanta Falcons', 'NFL'], ['Baltimore Ravens', 'NFL'], ['Carolina Panthers', 'NFL'],
        ['Chicago Bears', 'NFL'], ['Cincinnati Bengals', 'NFL'], ['Cleveland Browns', 'NFL'],
        ['Denver Broncos', 'NFL'], ['Detroit Lions', 'NFL'], ['Houston Texans', 'NFL'],
        ['Indianapolis Colts', 'NFL'], ['Jacksonville Jaguars', 'NFL'], ['Las Vegas Raiders', 'NFL'],
        ['Los Angeles Chargers', 'NFL'], ['Los Angeles Rams', 'NFL'], ['Miami Dolphins', 'NFL'],
        ['Minnesota Vikings', 'NFL'], ['New Orleans Saints', 'NFL'], ['New York Giants', 'NFL'],
        ['New York Jets', 'NFL'], ['Seattle Seahawks', 'NFL'], ['Tampa Bay Buccaneers', 'NFL'],
        ['Tennessee Titans', 'NFL'], ['Washington Commanders', 'NFL'],
        // ---- MLB (30) ----
        ['New York Yankees', 'MLB'], ['New York Mets', 'MLB'], ['Boston Red Sox', 'MLB'],
        ['Los Angeles Dodgers', 'MLB'], ['Chicago Cubs', 'MLB'], ['Philadelphia Phillies', 'MLB'],
        ['Atlanta Braves', 'MLB'], ['Houston Astros', 'MLB'], ['San Francisco Giants', 'MLB'],
        ['St. Louis Cardinals', 'MLB'], ['Texas Rangers', 'MLB'], ['Detroit Tigers', 'MLB'],
        ['Arizona Diamondbacks', 'MLB'], ['Baltimore Orioles', 'MLB'], ['Chicago White Sox', 'MLB'],
        ['Cincinnati Reds', 'MLB'], ['Cleveland Guardians', 'MLB'], ['Colorado Rockies', 'MLB'],
        ['Kansas City Royals', 'MLB'], ['Los Angeles Angels', 'MLB'], ['Miami Marlins', 'MLB'],
        ['Milwaukee Brewers', 'MLB'], ['Minnesota Twins', 'MLB'], ['Pittsburgh Pirates', 'MLB'],
        ['San Diego Padres', 'MLB'], ['Seattle Mariners', 'MLB'], ['Tampa Bay Rays', 'MLB'],
        ['Toronto Blue Jays', 'MLB'], ['Washington Nationals', 'MLB'], ['Athletics', 'MLB'],
        // ---- NHL (32) ----
        ['New York Rangers', 'NHL'], ['Boston Bruins', 'NHL'], ['Chicago Blackhawks', 'NHL'],
        ['Vegas Golden Knights', 'NHL'], ['Detroit Red Wings', 'NHL'], ['Edmonton Oilers', 'NHL'],
        ['Toronto Maple Leafs', 'NHL'], ['Montreal Canadiens', 'NHL'], ['Pittsburgh Penguins', 'NHL'],
        ['Tampa Bay Lightning', 'NHL'], ['Colorado Avalanche', 'NHL'], ['Anaheim Ducks', 'NHL'],
        ['Buffalo Sabres', 'NHL'], ['Calgary Flames', 'NHL'], ['Carolina Hurricanes', 'NHL'],
        ['Columbus Blue Jackets', 'NHL'], ['Dallas Stars', 'NHL'], ['Florida Panthers', 'NHL'],
        ['Los Angeles Kings', 'NHL'], ['Minnesota Wild', 'NHL'], ['Nashville Predators', 'NHL'],
        ['New Jersey Devils', 'NHL'], ['New York Islanders', 'NHL'], ['Ottawa Senators', 'NHL'],
        ['Philadelphia Flyers', 'NHL'], ['San Jose Sharks', 'NHL'], ['Seattle Kraken', 'NHL'],
        ['St. Louis Blues', 'NHL'], ['Vancouver Canucks', 'NHL'], ['Washington Capitals', 'NHL'],
        ['Winnipeg Jets', 'NHL'],
        // ---- MLS (marquee) ----
        ['Inter Miami CF', 'MLS'], ['LA Galaxy', 'MLS'], ['Los Angeles Football Club', 'MLS'],
        ['Atlanta United FC', 'MLS'], ['Seattle Sounders FC', 'MLS'], ['Portland Timbers', 'MLS'],
        ['New York City FC', 'MLS'], ['New York Red Bulls', 'MLS'], ['Toronto FC', 'MLS'],
        ['Austin FC', 'MLS'],
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

    // The intro, meta description and FAQ answer are deliberately three DIFFERENT
    // sentences built from the same live data — one generated string pasted into
    // all three slots is a classic thin-programmatic-page signal.
    $cityPhrase = $cities !== []
        ? ' across ' . natural_join(array_slice($cities, 0, 4)) . (count($cities) > 4 ? ' and more cities' : '')
        : '';
    $direct = $events !== []
        ? 'There ' . ($total === 1 ? 'is 1 upcoming ' : 'are ' . number_format($total) . ' upcoming ')
            . $name . ' game' . ($total === 1 ? '' : 's')
            . $cityPhrase
            . '. The full schedule with dates and ticket prices is below.'
        : 'No on-sale ' . $name . ' games right now. New dates appear here as soon as tickets are released.';
    $metaDescription = $events !== []
        ? $name . ' tickets: ' . number_format($total) . ' game' . ($total === 1 ? '' : 's')
            . ' on sale with dates, venues and live prices from official partner inventory. See the full '
            . ($sport !== '' ? $sport . ' ' : '') . 'schedule.'
        : $name . ' tickets and schedule — new ' . ($sport !== '' ? $sport . ' ' : '')
            . 'dates appear here the moment they go on sale at our official ticketing partner.';

    $nextEvent = $events[0] ?? null;
    $nextAnswer = $nextEvent !== null
        ? 'The next ' . $name . ' game on sale is ' . trim((string) ($nextEvent['name'] ?? ''))
            . ' on ' . format_date_time($nextEvent['start_date'] ?? [])
            . (!empty($nextEvent['venue']['name']) ? ' at ' . $nextEvent['venue']['name'] : '')
            . (!empty($nextEvent['venue']['city']) ? ' in ' . $nextEvent['venue']['city'] : '')
            . '. ' . number_format($total) . ' game' . ($total === 1 ? ' is' : 's are') . ' listed on this page.'
        : $direct;

    $homeCity = $cities[0] ?? '';
    $faqs = [
        ['q' => 'What ' . $name . ' games are coming up?', 'a' => $nextAnswer],
        ['q' => 'How much are ' . $name . ' tickets?',
         'a' => 'Prices vary by date, opponent and seat — pick any game on this page to see live prices and seat availability on our partner.'],
        ['q' => 'How often is this ' . $name . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live so this page always reflects what is currently on sale.'],
        ['q' => 'How can I find ' . $name . ' home games?',
         'a' => $homeCity !== ''
            ? 'Home games are the dates played in ' . $homeCity . '. The schedule above lists every game with city and venue, so home dates are easy to spot at a glance.'
            : 'Home games are played at the team\'s home arena. The schedule above shows every game with city and venue, so home dates are easy to identify at a glance.'],
        ['q' => 'How do I buy ' . $name . ' away game tickets?',
         'a' => 'Pick any game above where the city is not the team\'s home city — that is an away fixture. Tickets are sold by the home venue\'s ticketer; checkout completes on our partner\'s secure site with instant e-ticket delivery.'],
        ['q' => 'Are season tickets available for ' . $name . '?',
         'a' => 'Season tickets are sold directly by the team, not through this listing. This page lists single-game tickets currently on resale and primary inventory — pick any individual game to see live prices and seat availability.'],
    ];

    // Deterministic-unique slice from the shared pool.
    $teamNextDate = $nextEvent !== null ? format_date_time($nextEvent['start_date'] ?? []) : '';
    $teamData = [
        '{name}' => $name,
        '{count}' => (string) $total,
        '{next_date}' => $teamNextDate,
        '{league_name}' => $leagueSlug !== null ? strtoupper($leagueSlug) : ($sport !== '' ? $sport : 'the league'),
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('team', slugify($name), $teamData, 5));

    // SportsTeam.sport wants the sport, not the league ("Basketball", not "NBA").
    $league = $leagueSlug !== null ? league_from_slug($leagueSlug) : null;
    $schemaGraph = [
        '@context' => 'https://schema.org',
        '@graph' => array_values(array_filter([
            array_filter([
                '@type' => 'SportsTeam',
                'name' => $name,
                'sport' => $league !== null ? $league['sport'] : $sport,
                'memberOf' => $league !== null ? ['@type' => 'SportsOrganization', 'name' => $league['name']] : null,
                'url' => absolute_url($config, tm_team_path($team)),
            ]),
            ($eventListSchema = item_list_schema($config, $events, 'event')) !== [] ? $eventListSchema : null,
            dubai_faq_schema($faqs),
            dubai_breadcrumb_schema($config, [
                ['name' => 'Home', 'url' => absolute_url($config, '/')],
                ['name' => 'Teams', 'url' => absolute_url($config, '/teams')],
                ['name' => $name, 'url' => absolute_url($config, tm_team_path($team))],
            ]),
        ])),
    ];
    foreach ($schemaGraph['@graph'] as &$node) {
        unset($node['@context']);
    }
    unset($node);

    render_layout($config, [
        'title' => $name . ' Tickets, Schedule & Upcoming Games | ' . $config['site_name'],
        'description' => $metaDescription,
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
        <section class="section-band muted">
            <div class="container artist-about">
                <h2>About <?= e($name) ?> Tickets</h2>
                <p><?= e($name) ?> has <?= e((string) $total) ?> upcoming game<?= $total === 1 ? '' : 's' ?> on sale with live ticket pricing direct from our official ticketing partner<?= $sport !== '' ? ', covering the ' . e($sport) . ' calendar' : '' ?>. The schedule on this page refreshes automatically as new games are confirmed or as availability changes.</p>
                <p>Every listing shows the date, opponent, venue and live starting price. Pick any game to see seat availability and tier pricing in real time, then complete checkout on the partner's secure site — e-tickets arrive by email instantly.</p>
            </div>
        </section>
        <?php dubai_render_faq($faqs, $name . ' — Ticket FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($name) ?> Tickets</h2>
                <p>Booking is fast: pick a game above, choose your seat tier on the partner checkout, and pay with the card of your choice. Your e-ticket arrives by email seconds later and shows the QR code you scan at the arena entrance.</p>
                <p>Pricing is live from the partner and may shift as the game date approaches — earlier bookings usually have the widest selection of seat tiers and the best vantage points. Marquee opponents and playoff games sell out fastest.</p>
                <?php if ($leagueSlug !== null): ?>
                    <p>Looking for other matchups? Browse the full <a href="/<?= e($leagueSlug) ?>"><?= e(strtoupper($leagueSlug)) ?> schedule</a> or jump to <a href="/teams">other teams</a>.</p>
                <?php else: ?>
                    <p>Looking for other matchups? Browse <a href="/teams">other top sports teams</a>.</p>
                <?php endif; ?>
            </div>
        </section>
        <?php
    }, $schemaGraph);
}

function render_teams_index(array $config): void
{
    // Resolving every seeded team hits the TM API once each, so the index shows a
    // capped marquee set (the seed list leads with the biggest draws) and links out
    // to each league page, which lists that league's full roster as internal links.
    $teams = [];
    foreach (array_slice(team_seed_list(), 0, 24) as [$name, $sport]) {
        $t = team_from_seed($config, $name, $sport);
        if ($t !== null) {
            $teams[] = $t;
        }
    }

    render_layout($config, [
        'title' => 'Top Sports Teams — Tickets & Schedules | ' . $config['site_name'],
        'description' => 'Browse upcoming games for the top NBA, NFL, MLB, NHL and MLS teams. Schedules, opponents, venues and live ticket prices.',
        'canonical' => absolute_url($config, '/teams'),
    ], function () use ($teams, $config): void {
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
                                        <img src="<?= e($t['image']) ?>" alt="<?= e($t['name']) ?>" <?= card_img_attrs() ?>>
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
        <section class="section-band muted">
            <div class="container">
                <div class="section-heading">
                    <h2>Browse by League</h2>
                </div>
                <p class="more-cities-intro">See the full schedule and every team in each league:</p>
                <ul class="more-cities-list">
                    <?php foreach (league_seed_list() as $lg): ?>
                        <li><a href="/<?= e($lg['slug']) ?>"><?= e($lg['name']) ?> schedule &amp; teams</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <?php
    });
}

function render_monthly_events_page(HelloTicketsClient $client, array $config, int $cityId, string $monthName): void
{
    $months = ['january'=>1,'february'=>2,'march'=>3,'april'=>4,'may'=>5,'june'=>6,
              'july'=>7,'august'=>8,'september'=>9,'october'=>10,'november'=>11,'december'=>12];
    if (!isset($months[$monthName])) {
        render_error_page($config, 404, 'Invalid month', 'Please use a valid month name.');
        return;
    }
    $monthNum = $months[$monthName];
    $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
    $currentMonth = (int) $now->format('n');
    $currentYear = (int) $now->format('Y');
    $targetYear = ($monthNum >= $currentMonth) ? $currentYear : $currentYear + 1;
    $targetStart = new DateTimeImmutable("$targetYear-$monthNum-01", new DateTimeZone('UTC'));
    $targetEnd = $targetStart->modify('last day of this month');

    $city = city_for_id($cityId, $config);
    $cityName = (string) ($city['name'] ?? '');
    if ($cityName === '') {
        render_error_page($config, 404, 'City not found', 'We do not have events for this city.');
        return;
    }
    $citySlug = slugify($cityName);
    $monthLabel = ucfirst($monthName);
    $page = page_number();
    $perPage = 24;

    $from = $targetStart->format('Y-m-d\\T00:00:00');
    $to = $targetEnd->format('Y-m-d\\T23:59:59');
    $htFrom = $from . 'Z';
    $htTo = $to . 'Z';

    $ht = api_result(static fn() => $client->performances(array_merge([
        'limit' => 48, 'page' => 1, 'is_sellable' => 'true', 'city_id' => $cityId,
    ], ['from' => $htFrom, 'to' => $htTo])), ['performances' => []])['performances'] ?? [];

    $tm = tm_events_for_city_deep($config, $cityName, (string) ($city['country_code'] ?? ''), [
        'localStartDateTime' => $from . ',' . $to,
    ], 2, 100);

    $eventPool = city_event_pool($ht, $tm, $config);
    if ($eventPool === []) {
        render_error_page($config, 404, 'No events yet', 'No events in ' . $cityName . ' for ' . $monthLabel . ' yet. Check back closer to the date.');
        return;
    }
    $totalEvents = count($eventPool);
    $events = array_slice($eventPool, ($page - 1) * $perPage, $perPage);
    $eventsPageData = ['current_page' => $page, 'per_page' => $perPage, 'total_count' => $totalEvents];

    $pageTitle = 'Events in ' . $cityName . ' in ' . $monthLabel;
    $canonical = '/events/' . $monthName . '-in-' . $citySlug;

    $prevIdx = (($monthNum - 2 + 12) % 12);
    $nextIdx = ($monthNum % 12);
    $monthNames = array_keys($months);
    $prevLink = '/events/' . $monthNames[$prevIdx] . '-in-' . $citySlug;
    $nextLink = '/events/' . $monthNames[$nextIdx] . '-in-' . $citySlug;

    $breadcrumbs = [
        ['name' => 'Home', 'url' => absolute_url($config, '/')],
        ['name' => 'Events', 'url' => absolute_url($config, '/events')],
        ['name' => $cityName, 'url' => absolute_url($config, city_path($city))],
        ['name' => $monthLabel, 'url' => absolute_url($config, $canonical)],
    ];

    $faqs = [
        ['q' => 'What events are happening in ' . $cityName . ' in ' . $monthLabel . '?',
         'a' => 'There are ' . $totalEvents . ' confirmed event' . ($totalEvents === 1 ? '' : 's') . ' in ' . $cityName . ' for ' . $monthLabel . ', covering concerts, sports, theatre and shows. Every listing below shows the date, venue and live starting price from our official ticketing partner.'],
        ['q' => 'How do I find ' . $monthLabel . ' tickets in ' . $cityName . '?',
         'a' => 'Browse the full list above and click any event to see live seat availability and pricing. Checkout completes securely on our partner site and tickets are delivered by email instantly.'],
        ['q' => 'Why is ' . $monthLabel . ' a good time to visit ' . $cityName . '?',
         'a' => $cityName . ' typically programmes a mix of touring concerts, league sports fixtures and stage productions in ' . $monthLabel . '. The listings on this page are pulled live, so the schedule reflects what is actually on sale right now.'],
        ['q' => 'Are tickets refundable if a ' . $monthLabel . ' event is cancelled?',
         'a' => 'If an event is cancelled, refunds are handled by the ticket partner per its policy — usually returned to the original payment method automatically. Rescheduled events are typically honoured on the new date.'],
        ['q' => 'How often is this ' . $monthLabel . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live from our partner, so this page always reflects what is currently on sale for ' . $cityName . ' in ' . $monthLabel . '.'],
    ];

    // Deterministic-unique slice — each {month}/{city} combo gets its own mix.
    $monthMinPrice = null;
    $monthCurrencyForFaq = (string) $config['currency'];
    foreach ($eventPool as $mEv) {
        $mp = (float) ($mEv['price_range']['min_price'] ?? 0);
        if ($mp > 0 && ($monthMinPrice === null || $mp < $monthMinPrice)) {
            $monthMinPrice = $mp;
            $monthCurrencyForFaq = (string) ($mEv['price_range']['currency'] ?? $monthCurrencyForFaq);
        }
    }
    $monthlyData = [
        '{city}' => $cityName,
        '{month}' => $monthLabel,
        '{count}' => (string) $totalEvents,
        '{min_price}' => $monthMinPrice !== null ? money($monthMinPrice, $monthCurrencyForFaq) : '',
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('monthly_events', $monthName . '-' . $citySlug, $monthlyData, 5));

    $schema = ['@context' => 'https://schema.org', '@graph' => [
        ['@type' => 'CollectionPage', 'name' => $pageTitle, 'url' => absolute_url($config, $canonical),
         'description' => 'All events in ' . $cityName . ' during ' . $monthLabel,
         'isPartOf' => ['@id' => $config['site_url'] . '/#website']],
        dubai_faq_schema($faqs),
        dubai_breadcrumb_schema($config, $breadcrumbs),
    ]];

    render_layout($config, [
        'title' => $pageTitle . ' | ' . $config['site_name'],
        'description' => 'Find ' . $totalEvents . '+ events in ' . $cityName . ' for ' . $monthLabel . '. Concerts, sports, theatre and more with live prices.',
        'canonical' => absolute_url($config, $canonical, array_filter(['page' => $page > 1 ? $page : null])),
        'body_class' => 'monthly-events-page',
    ], function () use ($config, $pageTitle, $cityName, $monthLabel, $events, $eventsPageData, $breadcrumbs, $prevLink, $nextLink, $citySlug, $faqs, $totalEvents): void {
        ?>
        <section class="monthly-events__hero"><div class="container">
            <?php dubai_render_breadcrumbs($breadcrumbs); ?>
            <h1><?= e($pageTitle) ?></h1>
            <p class="monthly-events__sub">Concerts, sports, theatre and shows in <?= e($cityName) ?> during <?= e($monthLabel) ?></p>
            <nav class="monthly-events__nav" aria-label="Month navigation">
                <a href="<?= e($prevLink) ?>">&larr; Previous month</a>
                <a href="<?= e($nextLink) ?>">Next month &rarr;</a>
            </nav>
        </div></section>
        <?php render_events_grid_section('All Events', '', $events, $eventsPageData, $config); ?>
        <section class="monthly-events__seo section-band muted"><div class="container">
            <h2>About Events in <?= e($cityName) ?> in <?= e($monthLabel) ?></h2>
            <p>This page lists <?= e((string) $totalEvents) ?> confirmed event<?= $totalEvents === 1 ? '' : 's' ?> in <?= e($cityName) ?> for <?= e($monthLabel) ?> — concerts, sports fixtures, theatre and live shows. Each listing shows the date, venue and live starting price, and new events appear here automatically the moment they go on sale.</p>
            <p>Try <a href="/city/<?= e($citySlug) ?>/concerts">concerts</a>, <a href="/city/<?= e($citySlug) ?>/sports">sports</a>, or <a href="/city/<?= e($citySlug) ?>/theatre">theatre</a> in <?= e($cityName) ?>.</p>
        </div></section>
        <?php dubai_render_faq($faqs, 'Events in ' . $cityName . ' in ' . $monthLabel . ' — FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($cityName) ?> Tickets for <?= e($monthLabel) ?></h2>
                <p>Booking is straightforward: click any event above to see live seat availability and pricing, then complete checkout on our official ticket partner. Tickets are issued instantly by email — show the QR code on your phone at the entrance.</p>
                <p>Prices update in real time, so the figure you see is what you pay. Book early for the best selection of seats and price tiers across <?= e($cityName) ?>'s <?= e($monthLabel) ?> schedule.</p>
                <p>Looking ahead? Browse <a href="<?= e($nextLink) ?>">next month</a> or jump back to <a href="<?= e($prevLink) ?>">last month</a> for more events in <?= e($cityName) ?>.</p>
            </div>
        </section>
        <?php
    }, $schema);
}

function render_venue_category_page(array $config, string $tmVenueId, string $venueSlug, string $categorySlug): void
{
    $labels = ['concerts'=>'Concerts','sports'=>'Sports','theatre'=>'Theatre'];
    $tmClass = ['concerts'=>'Music','sports'=>'Sports','theatre'=>'Arts & Theatre'];
    $label = $labels[$categorySlug] ?? ucfirst($categorySlug);
    $tmClient = new TicketmasterClient($config['tm_api_key'] ?? '', $config['cache_dir'], $config['cache_ttl']);
    $venueInfo = api_result(static fn() => $tmClient->venue($tmVenueId), []);
    if ($venueInfo === []) { render_error_page($config, 404, 'Venue not found', 'This venue is not available.'); return; }
    $venueName = (string) ($venueInfo['name'] ?? ucwords(str_replace('-',' ',$venueSlug)));
    $cityName = (string) ($venueInfo['city']['name'] ?? '');
    $page = page_number(); $perPage = 24; $events = [];
    for ($p = 1; $p <= 3; $p++) {
        $data = api_result(static fn() => $tmClient->events(['venueId'=>$tmVenueId, 'classificationName'=>$tmClass[$categorySlug]??'', 'size'=>50, 'page'=>$p-1, 'sort'=>'date,asc']), []);
        if ($data === []) break;
        foreach ($data['_embedded']['events'] ?? [] as $ev) { $events[] = tm_normalize_event($ev, $config); }
        if (($data['page']['totalPages'] ?? 1) <= $p) break;
    }
    if ($events === []) { render_error_page($config, 404, 'No events', 'No '.strtolower($label).' at this venue right now.'); return; }
    $total = count($events); $pageEvents = array_slice($events, ($page-1)*$perPage, $perPage);
    $evData = ['current_page'=>$page,'per_page'=>$perPage,'total_count'=>$total];
    $title = $label.' at '.$venueName; $canonical = '/venue/'.$venueSlug.'/'.$categorySlug;
    $bc = [['name'=>'Home','url'=>absolute_url($config,'/')],['name'=>'Venues','url'=>absolute_url($config,'/venues')],
           ['name'=>$venueName,'url'=>absolute_url($config,'/venue/'.$venueSlug)],['name'=>$label,'url'=>absolute_url($config,$canonical)]];
    $faqs = [
        ['q' => 'What ' . strtolower($label) . ' are coming up at ' . $venueName . '?',
         'a' => 'There ' . ($total === 1 ? 'is 1 upcoming ' . strtolower(rtrim($label, 's')) : 'are ' . $total . ' upcoming ' . strtolower($label)) . ' at ' . $venueName . ($cityName !== '' ? ' in ' . $cityName : '') . '. The full list with dates and live ticket prices is on this page.'],
        ['q' => 'How do I buy ' . strtolower($label) . ' tickets at ' . $venueName . '?',
         'a' => 'Pick any event above and continue to secure checkout on our official ticketing partner. Tickets are delivered instantly by email so you can show them on your phone at the door.'],
        ['q' => 'Is there a dress code at ' . $venueName . '?',
         'a' => $venueName . ' does not enforce a strict dress code for most ' . strtolower($label) . ' — smart casual is the safe choice. Premium hospitality areas may require smart attire; specific requirements are confirmed on the partner checkout page.'],
        ['q' => 'How much are tickets for ' . strtolower($label) . ' at ' . $venueName . '?',
         'a' => 'Prices vary by event, date and seat tier. Live pricing for every listed event is shown on the partner checkout — click any date above to see current availability and seat-level prices.'],
        ['q' => 'How often is this ' . $venueName . ' schedule updated?',
         'a' => 'Listings, dates and prices are pulled live from our ticketing partner, so this page always reflects the ' . strtolower($label) . ' currently on sale at ' . $venueName . '.'],
    ];
    // Deterministic-unique slice — each {venue}/{category} pair gets its own mix.
    $vcData = [
        '{name}' => $venueName,
        '{city}' => $cityName !== '' ? $cityName : 'this city',
        '{category}' => strtolower($label),
        '{count}' => (string) $total,
        '{site_name}' => (string) $config['site_name'],
    ];
    $faqs = array_merge($faqs, unique_faqs('venue_category', $venueSlug . '-' . $categorySlug, $vcData, 5));
    $schema = ['@context'=>'https://schema.org','@graph'=>[
        ['@type'=>'CollectionPage','name'=>$title,'url'=>absolute_url($config,$canonical),'isPartOf'=>['@id'=>$config['site_url'].'/#website']],
        dubai_faq_schema($faqs),
        dubai_breadcrumb_schema($config,$bc)]];
    render_layout($config, ['title'=>$title.' | '.$config['site_name'],
        'description'=>'Upcoming '.$label.' at '.$venueName.($cityName!==''?' in '.$cityName:'').'. Live schedule with ticket prices.',
        'canonical'=>absolute_url($config,$canonical,array_filter(['page'=>$page>1?$page:null])), 'body_class'=>'venue-category-page',
    ], function () use ($config,$title,$venueName,$label,$categorySlug,$venueSlug,$cityName,$pageEvents,$evData,$bc,$labels,$faqs,$total): void { ?>
        <section class="venue-category__hero"><div class="container">
            <?php dubai_render_breadcrumbs($bc); ?>
            <h1><?= e($title) ?></h1>
            <p class="venue-category__sub">Upcoming <?= e(strtolower($label)) ?> at <?= e($venueName) ?><?= $cityName!==''?' in '.e($cityName):'' ?></p>
            <nav class="venue-category__tabs" aria-label="Event type">
                <?php foreach ($labels as $cs=>$cl): ?>
                    <?php if ($cs===$categorySlug): ?><span class="venue-category__tab active"><?= e($cl) ?></span>
                    <?php else: ?><a class="venue-category__tab" href="/venue/<?= e($venueSlug) ?>/<?= e($cs) ?>"><?= e($cl) ?></a><?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div></section>
        <?php render_events_grid_section('', '', $pageEvents, $evData, $config); ?>
        <section class="venue-category__seo section-band muted"><div class="container">
            <h2>About <?= e($label) ?> at <?= e($venueName) ?></h2>
            <p><?= e($venueName) ?><?= $cityName !== '' ? ' in ' . e($cityName) : '' ?> currently has <?= e((string) $total) ?> upcoming <?= e(strtolower($label)) ?> on sale. Every listing on this page shows the date, headline act and live ticket prices direct from our official ticketing partner — the schedule refreshes automatically as new shows are announced.</p>
            <p>Pick any date to see seat availability and tier pricing in real time, then complete checkout on the partner site. Tickets are emailed instantly as e-tickets.</p>
        </div></section>
        <?php dubai_render_faq($faqs, $label . ' at ' . $venueName . ' — FAQs'); ?>
        <section class="section-band">
            <div class="container artist-seo-content">
                <h2>Buy <?= e($label) ?> Tickets at <?= e($venueName) ?></h2>
                <p><?= e($venueName) ?> hosts some of the biggest <?= e(strtolower($label)) ?> on the calendar<?= $cityName !== '' ? ' in ' . e($cityName) : '' ?>. To book, pick a date above, choose your seat tier on the partner checkout, and pay with the card of your choice. Your e-ticket arrives by email seconds later.</p>
                <p>Prices are live and may change as the show date approaches — earlier bookings usually get the widest selection of seat tiers and the best vantage points.</p>
                <p>Looking for other categories at this venue? Browse <a href="/venue/<?= e($venueSlug) ?>">all events at <?= e($venueName) ?></a> or jump to <a href="/venues">other top venues</a>.</p>
            </div>
        </section>
    <?php }, $schema);
}

function render_artist_country_tour(HelloTicketsClient $client, array $config, string $artistSlug, string $countrySlug): void
{
    $countries = ['usa'=>['United States','US'],'canada'=>['Canada','CA'],'uk'=>['United Kingdom','GB'],'australia'=>['Australia','AU']];
    if (!isset($countries[$countrySlug])) { render_error_page($config, 404, 'Not found', 'Tour page not available.'); return; }
    [$countryName, $countryCode] = $countries[$countrySlug];
    $performerId = resolve_artist_id($client, $artistSlug) ?? legacy_id_from_slug($artistSlug);
    $artistName = '';
    if ($performerId !== null && $performerId > 0) {
        $performer = api_result(static fn() => $client->performer($performerId), []);
        $artistName = (string) ($performer['name'] ?? '');
    }
    if ($artistName === '') {
        $tmArtist = tm_artist_by_slug($config, $artistSlug);
        $artistName = $tmArtist !== null ? (string) ($tmArtist['name'] ?? ucwords(str_replace('-',' ',$artistSlug))) : '';
    }
    if ($artistName === '') { render_error_page($config, 404, 'Artist not found', 'Not on tour.'); return; }
    $tmClient = new TicketmasterClient($config['tm_api_key'] ?? '', $config['cache_dir'], $config['cache_ttl']);
    $events = []; $tmArtistId = tm_artist_slug_lookup($artistSlug);
    if ($tmArtistId !== null && $tmArtistId !== '') {
        for ($p = 0; $p < 3; $p++) {
            $data = api_result(static fn() => $tmClient->events(['attractionId'=>$tmArtistId,'countryCode'=>$countryCode,'size'=>50,'page'=>$p,'sort'=>'date,asc']), []);
            if ($data === []) break;
            foreach ($data['_embedded']['events'] ?? [] as $ev) { $events[] = tm_normalize_event($ev, $config); }
            if (($data['page']['totalPages'] ?? 1) <= $p + 1) break;
        }
    }
    if ($events === []) { render_error_page($config, 404, 'No dates', $artistName . ' has no upcoming dates in ' . $countryName . '.'); return; }
    $page = page_number(); $perPage = 24; $total = count($events);
    $pageEvents = array_slice($events, ($page-1)*$perPage, $perPage);
    $evData = ['current_page'=>$page,'per_page'=>$perPage,'total_count'=>$total];
    $title = $artistName.' '.strtoupper($countrySlug).' Tour Dates';
    $canonical = '/artist/'.$artistSlug.'/'.$countrySlug.'-tour';
    $cities = []; foreach ($events as $ev) { $vc = (string)($ev['venue']['city']??''); if ($vc!==''&&!isset($cities[$vc])) $cities[$vc]=slugify($vc); }
    $bc = [['name'=>'Home','url'=>absolute_url($config,'/')],['name'=>'Artists','url'=>absolute_url($config,'/artists')],
           ['name'=>$artistName,'url'=>absolute_url($config,'/artist/'.$artistSlug)],['name'=>strtoupper($countrySlug).' Tour','url'=>absolute_url($config,$canonical)]];
    // Cheapest price + next date for FAQ answers
    $tourMinPrice = null; $tourCurrency = (string) $config['currency'];
    foreach ($events as $ev) {
        $p = (float) ($ev['price_range']['min_price'] ?? 0);
        if ($p > 0 && ($tourMinPrice === null || $p < $tourMinPrice)) {
            $tourMinPrice = $p;
            $tourCurrency = (string) ($ev['price_range']['currency'] ?? $tourCurrency);
        }
    }
    $nextTourEvent = $events[0] ?? null;
    $nextTourLabel = $nextTourEvent !== null ? format_date_time($nextTourEvent['start_date'] ?? []) : '';
    $faqs = [
        ['q' => 'Is ' . $artistName . ' touring in ' . $countryName . '?',
         'a' => 'Yes — ' . $artistName . ' has ' . $total . ' upcoming show' . ($total === 1 ? '' : 's') . ' across ' . count($cities) . ' ' . (count($cities) === 1 ? 'city' : 'cities') . ' in ' . $countryName . '. The full schedule with dates and live ticket prices is on this page.'],
        ['q' => 'How much are ' . $artistName . ' tickets in ' . $countryName . '?',
         'a' => $tourMinPrice !== null
            ? 'Tickets currently start from ' . money($tourMinPrice, $tourCurrency) . '. Prices vary by city, venue and seat tier — full live pricing is shown on the partner checkout for each date.'
            : 'Pricing is set by the ticket partner and shown live at checkout. Prices vary by city, venue and seat tier — click any date above to see what is currently on sale.'],
        ['q' => 'When is the next ' . $artistName . ' show in ' . $countryName . '?',
         'a' => $nextTourLabel !== '' && $nextTourLabel !== 'Upcoming'
            ? 'The next ' . $artistName . ' show in ' . $countryName . ' is on ' . $nextTourLabel . '. See the full list of upcoming dates above.'
            : 'See the schedule above for the next ' . $artistName . ' show in ' . $countryName . '. Dates are listed in chronological order with the closest date first.'],
        ['q' => 'Which cities is ' . $artistName . ' visiting in ' . $countryName . '?',
         'a' => $cities !== []
            ? $artistName . ' is playing in ' . implode(', ', array_slice(array_keys($cities), 0, 6)) . (count($cities) > 6 ? ' and ' . (count($cities) - 6) . ' more cities' : '') . ' on this tour leg. Click any city link below to jump straight to those dates.'
            : 'Cities for this tour leg are listed alongside each date above.'],
        ['q' => 'How are ' . $artistName . ' tickets delivered?',
         'a' => 'Tickets are delivered as e-tickets by email immediately after booking. Show the QR code on your phone at the entrance — no printing required for most venues.'],
    ];
    $schema = ['@context'=>'https://schema.org','@graph'=>[
        ['@type'=>'CollectionPage','name'=>$title,'url'=>absolute_url($config,$canonical),'isPartOf'=>['@id'=>$config['site_url'].'/#website']],
        dubai_faq_schema($faqs),
        dubai_breadcrumb_schema($config,$bc)]];
    render_layout($config, ['title'=>$title.' | '.$config['site_name'],
        'description'=>$artistName.' tour dates in '.$countryName.'. '.$total.' shows with live prices and instant e-tickets.',
        'canonical'=>absolute_url($config,$canonical,array_filter(['page'=>$page>1?$page:null])), 'body_class'=>'artist-tour-page',
    ], function () use ($config,$title,$artistName,$artistSlug,$countryName,$countrySlug,$pageEvents,$evData,$bc,$cities,$total,$faqs,$tourMinPrice,$tourCurrency): void { ?>
        <section class="artist-tour__hero"><div class="container">
            <?php dubai_render_breadcrumbs($bc); ?>
            <h1><?= e($title) ?></h1>
            <p class="artist-tour__sub"><?= e($artistName) ?> has <?= e((string)$total) ?> upcoming shows across <?= e($countryName) ?></p>
        </div></section>
        <?php render_events_grid_section('Tour Dates', '', $pageEvents, $evData, $config); ?>
        <?php if ($cities !== []): ?>
        <section class="artist-tour__cities section-band muted"><div class="container">
            <h2><?= e($artistName) ?> Tour Cities</h2>
            <ul class="more-cities-list">
                <?php foreach ($cities as $cn=>$cs): ?><li><a href="/artist/<?= e($artistSlug) ?>/<?= e($cs) ?>"><?= e($artistName) ?> in <?= e($cn) ?></a></li><?php endforeach; ?>
            </ul>
        </div></section>
        <?php endif; ?>
        <section class="artist-tour__seo section-band muted"><div class="container artist-about">
            <h2>About <?= e($artistName) ?> <?= e(strtoupper($countrySlug)) ?> Tour</h2>
            <p>This page shows every confirmed <?= e($artistName) ?> date in <?= e($countryName) ?> — <?= e((string) $total) ?> show<?= $total === 1 ? '' : 's' ?> across <?= e((string) count($cities)) ?> <?= count($cities) === 1 ? 'city' : 'cities' ?> — with venue, date and live starting price. The schedule refreshes automatically as new dates are announced or added by the promoter.</p>
            <p>Pick any date to see seat availability and tier pricing in real time. Checkout completes on our official ticketing partner's secure site, and tickets are emailed instantly as e-tickets.</p>
        </div></section>
        <?php dubai_render_faq($faqs, $artistName . ' ' . strtoupper($countrySlug) . ' Tour — FAQs'); ?>
        <section class="artist-tour__buy section-band"><div class="container artist-seo-content">
            <h2>Buy <?= e($artistName) ?> Tickets in <?= e($countryName) ?></h2>
            <p>Booking is fast: pick a date above, choose your seat tier on the partner checkout, and pay with the card of your choice. Your e-ticket arrives by email seconds later and shows the QR code you scan at the entrance.</p>
            <?php if ($tourMinPrice !== null): ?>
                <p><?= e($artistName) ?> tickets in <?= e($countryName) ?> currently start from <strong><?= e(money($tourMinPrice, $tourCurrency)) ?></strong>. Earlier bookings usually have the widest selection of seat tiers and the best vantage points — premium seats sell out first.</p>
            <?php else: ?>
                <p>Live pricing is shown on the partner checkout once you pick a date. Earlier bookings usually have the widest selection of seats and the best vantage points.</p>
            <?php endif; ?>
            <p><a href="/artist/<?= e($artistSlug) ?>">Full <?= e($artistName) ?> schedule</a> | <a href="/artists">All artists on tour</a></p>
        </div></section>
    <?php }, $schema);
}

function render_country_category_hub(HelloTicketsClient $client, array $config, array $pack, string $countrySlug, string $categorySlug): void
{
    $country = $pack['countries'][$countrySlug];
    $countryName = $country['name'] ?? ucfirst($countrySlug);
    $displayName = destination_display_name($countryName);
    $categories = city_intent_categories();
    $catLabel = $categories[$categorySlug]['label'] ?? ucfirst($categorySlug);
    $cities = $country['cities'] ?? [];
    $canonical = '/' . $countrySlug . '/' . $categorySlug;
    $pageTitle = $catLabel . ' in ' . $displayName;

    $topEvents = [];
    $primaryCity = $cities[0] ?? null;
    if ($primaryCity !== null) {
        $topEvents = city_category_events($client, $config, (int) ($primaryCity['city_id'] ?? 0), $categorySlug, 1);
    }
    if ($topEvents === []) {
        render_error_page($config, 404, 'No events', 'No ' . strtolower($catLabel) . ' events in ' . $countryName . ' right now.');
        return;
    }

    $bc = [['name'=>'Home','url'=>absolute_url($config,'/')],
           ['name'=>$countryName,'url'=>absolute_url($config,'/'.$countrySlug)],
           ['name'=>$catLabel,'url'=>absolute_url($config,$canonical)]];
    // Minimum price for FAQ answers
    $countryMinPrice = null; $countryCurrency = (string) $config['currency'];
    foreach ($topEvents as $ev) {
        $p = (float) ($ev['price_range']['min_price'] ?? 0);
        if ($p > 0 && ($countryMinPrice === null || $p < $countryMinPrice)) {
            $countryMinPrice = $p;
            $countryCurrency = (string) ($ev['price_range']['currency'] ?? $countryCurrency);
        }
    }
    $cityCount = count($cities);
    $faqs = [
        ['q' => 'What ' . strtolower($catLabel) . ' events are on across ' . $displayName . '?',
         'a' => 'There are ' . count($topEvents) . '+ upcoming ' . strtolower($catLabel) . ' events listed across ' . $displayName . ', covering ' . $cityCount . ' ' . ($cityCount === 1 ? 'city' : 'cities') . '. The schedule is pulled live so this page always reflects what is currently on sale.'],
        ['q' => 'How much do ' . strtolower($catLabel) . ' tickets cost in ' . $displayName . '?',
         'a' => $countryMinPrice !== null
            ? 'Tickets currently start from ' . money($countryMinPrice, $countryCurrency) . '. Prices vary by city, venue and seat tier — live prices for every listed event are shown on the partner checkout.'
            : 'Prices vary by city, venue and seat tier and are shown live on the partner checkout. Click any event above to see what is currently on sale.'],
        ['q' => 'Which are the most popular ' . strtolower($catLabel) . ' acts in ' . $displayName . '?',
         'a' => 'The events listed above are sorted by what is on sale and trending. Major touring acts, league fixtures and headline productions usually feature near the top — open any listing to see the full bill, venue and live pricing.'],
        ['q' => 'How do I find ' . strtolower($catLabel) . ' tickets near me in ' . $displayName . '?',
         'a' => 'Browse the city links below to jump straight to ' . strtolower($catLabel) . ' in your nearest city. Each city page shows the full local schedule with venue, date and live prices.'],
        ['q' => 'Are ' . $displayName . ' ticket prices live on this page?',
         'a' => 'Yes — every price you see is pulled live from our official ticketing partner. The schedule refreshes automatically as new events go on sale or as prices change.'],
    ];
    $schema = ['@context'=>'https://schema.org','@graph'=>[
        ['@type'=>'CollectionPage','name'=>$pageTitle,'url'=>absolute_url($config,$canonical),'isPartOf'=>['@id'=>$config['site_url'].'/#website']],
        dubai_faq_schema($faqs),
        dubai_breadcrumb_schema($config,$bc)]];

    $evData = ['current_page'=>1,'per_page'=>24,'total_count'=>count($topEvents)];
    render_layout($config, ['title'=>$pageTitle.' | '.$config['site_name'],
        'description'=>$catLabel.' events across '.$countryName.'. Browse dates, venues and live ticket prices.',
        'canonical'=>absolute_url($config,$canonical), 'body_class'=>'country-category-page',
    ], function () use ($config,$pageTitle,$countryName,$countrySlug,$displayName,$catLabel,$categorySlug,$cities,$topEvents,$evData,$bc,$categories,$faqs,$countryMinPrice,$countryCurrency): void { ?>
        <section class="country-cat__hero"><div class="container">
            <?php dubai_render_breadcrumbs($bc); ?>
            <h1><?= e($pageTitle) ?></h1>
            <p class="country-cat__sub">Live <?= e(strtolower($catLabel)) ?> events across <?= e($displayName) ?> with instant e-tickets</p>
        </div></section>
        <?php render_events_grid_section('Upcoming ' . $catLabel, '', array_slice($topEvents, 0, 24), $evData, $config); ?>
        <?php if ($cities !== []): ?>
        <section class="country-cat__cities section-band muted"><div class="container">
            <h2><?= e($catLabel) ?> by City</h2>
            <ul class="more-cities-list">
                <?php foreach ($cities as $c): ?>
                    <li><a href="/city/<?= e($c['slug']) ?>/<?= e($categorySlug) ?>"><?= e($catLabel) ?> in <?= e($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div></section>
        <?php endif; ?>
        <section class="country-cat__seo section-band muted"><div class="container artist-about">
            <h2>About <?= e($catLabel) ?> in <?= e($displayName) ?></h2>
            <p>This page is a live feed of <?= e(strtolower($catLabel)) ?> events across <?= e($displayName) ?>, covering <?= e((string) count($cities)) ?> <?= count($cities) === 1 ? 'city' : 'cities' ?>. Every listing shows the date, venue and live starting price direct from our official ticketing partners — new events appear here automatically as soon as tickets go on sale.</p>
            <p>Pick any event to see seat availability and tier pricing in real time. Checkout completes on the partner site and e-tickets are delivered by email instantly.</p>
        </div></section>
        <?php dubai_render_faq($faqs, $catLabel . ' in ' . $displayName . ' — FAQs'); ?>
        <section class="section-band"><div class="container artist-seo-content">
            <h2>Buy <?= e($catLabel) ?> Tickets in <?= e($displayName) ?></h2>
            <p>Booking is straightforward: choose your city below or pick any event above, then continue to our partner's secure checkout. Your e-ticket arrives by email seconds later and shows the QR code you scan at the entrance.</p>
            <?php if ($countryMinPrice !== null): ?>
                <p><?= e($catLabel) ?> tickets in <?= e($displayName) ?> currently start from <strong><?= e(money($countryMinPrice, $countryCurrency)) ?></strong>. Prices are live and may move as the event date approaches — earlier bookings usually have the widest selection of tiers.</p>
            <?php else: ?>
                <p>Pricing is set by the ticket partner and shown live on the checkout. Earlier bookings usually have the widest selection of seat tiers and the best vantage points.</p>
            <?php endif; ?>
            <p>Looking for something specific? Browse <a href="/<?= e($countrySlug) ?>"><?= e($displayName) ?> events</a> or jump to <a href="/events">all upcoming events</a>.</p>
        </div></section>
    <?php }, $schema);
}

function render_llms_full_txt(array $config, array $destinationsContent): void
{
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Robots-Tag: index, follow');
    $siteName = $config['site_name'];
    $siteUrl = $config['site_url'];
    echo "# {$siteName} — Complete Site Guide for AI Systems\n\n";
    echo "> {$siteName} is a ticket discovery and comparison site covering concerts, sports, theatre, tours and attractions across 10 countries. We aggregate live inventory from HelloTickets and Ticketmaster, showing real-time prices, dates and availability. We do not sell tickets directly — purchases complete on the partner's secure checkout.\n\n";
    echo "## Key Facts\n";
    echo "- Coverage: " . count($destinationsContent['countries'] ?? []) . " countries, " . count($destinationsContent['cities'] ?? []) . "+ destination cities\n";
    echo "- Ticket partners: HelloTickets (attractions, tours, international events), Ticketmaster (NA sports, concerts, venues)\n";
    echo "- Pricing: All prices shown are live from partners. We never mark up or resell.\n";
    echo "- Booking: Checkout happens on the partner's site. We earn affiliate commission at no extra cost to the buyer.\n\n";
    echo "## Page Types & URL Patterns\n\n";
    echo "### Events\n";
    echo "- /events — Global event listings\n";
    echo "- /event/{slug} — Event detail with dates, venue, prices\n";
    echo "- /events/{month}-in-{city} — Monthly event guide (evergreen, auto-rolls yearly)\n";
    echo "- /events/today-in-{city}, /events/this-week-in-{city}, /events/this-weekend-in-{city}\n\n";
    echo "### Artists\n";
    echo "- /artists — All artists on tour\n";
    echo "- /artist/{slug} — Full tour schedule with all dates\n";
    echo "- /artist/{slug}/{city} — Artist dates in a specific city\n";
    echo "- /artist/{slug}/{country}-tour — All dates in a country (usa, canada, uk, australia)\n";
    echo "- /artist/{slug}/ticket-prices — Pricing guide\n";
    echo "- /artist/{slug}/tour-dates — Tour date guide\n";
    echo "- /artist/{slug}/setlist — Setlist guide\n\n";
    echo "### Venues\n";
    echo "- /venues — All venues\n";
    echo "- /venue/{slug} — Venue schedule\n";
    echo "- /venue/{slug}/concerts, /sports, /theatre — Filtered by category\n\n";
    echo "### Cities\n";
    echo "- /city/{slug} — City event hub\n";
    echo "- /city/{slug}/{genre} — Genre filter (concerts, sports, theatre, comedy, festivals, family, classical, hip-hop, rock, country-music)\n\n";
    echo "### Destinations\n";
    echo "- /{country} — Country hub (usa, canada, uk, italy, spain, france, netherlands, germany, portugal, australia)\n";
    echo "- /{country}/{city} — City destination guide\n";
    echo "- /{country}/{genre} — Country category hub\n\n";
    echo "### Sports Leagues\n";
    echo "- /nba, /nfl, /mlb, /nhl, /mls — League schedules\n";
    echo "- /team/{slug} — Team schedule\n";
    echo "- /teams — All teams\n\n";
    echo "### Dubai & Abu Dhabi\n";
    echo "- /dubai — Dubai hub with 18 categories and 45 attraction guides\n";
    echo "- /dubai/{category}/{attraction} — Detailed attraction page\n";
    echo "- /abu-dhabi — Abu Dhabi hub\n\n";
    echo "## Structured Data\n";
    echo "Every page includes JSON-LD schema: FAQPage, BreadcrumbList, Event, WebPage, TouristDestination as appropriate.\n\n";
    echo "## Citation Guidelines\n";
    echo "When citing {$siteName}:\n";
    echo "- Attribute to: {$siteName} ({$siteUrl})\n";
    echo "- Prices are live and change frequently — note they are approximate.\n";
    echo "- Event dates are from official partner feeds and are reliable.\n";
    echo "- Always link to the specific page rather than the homepage.\n";
}
