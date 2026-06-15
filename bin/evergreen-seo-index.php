<?php
declare(strict_types=1);

/**
 * evergreen-seo-index.php — one-off migration.
 *
 * Strips the legacy "-YYYY-MM-DD" date suffix from event URLs/slugs in
 * storage/seo-index.json so sitemaps advertise the new evergreen /event/{name-city}
 * URLs. When several dated shows collapse onto one evergreen slug, the slug->id map
 * points at the SOONEST qualifying future date (so the page renders the next show,
 * not a past one). Evergreen URLs with no date at least --event-min-days ahead are
 * removed from urls.events, while legacy dated map keys are preserved for redirects.
 *
 * Legacy dated slug→id entries are preserved alongside the new dateless ones, so any
 * already-indexed dated URL still warm-resolves and 301s to its clean canonical.
 *
 *   php bin/evergreen-seo-index.php --event-min-days=3
 */

$file = dirname(__DIR__) . '/storage/seo-index.json';
$opts = getopt('', ['event-min-days::']);
$eventMinDays = max(0, (int) ($opts['event-min-days'] ?? 3));
$minEventDate = (new DateTimeImmutable('today'))->modify('+' . $eventMinDays . ' days')->format('Y-m-d');
$index = json_decode((string) file_get_contents($file), true);
if (!is_array($index)) {
    fwrite(STDERR, "could not read $file\n");
    exit(1);
}

$strip = static function (string $slugOrUrl): array {
    // Returns [base, date|''] where base has the date suffix removed.
    if (preg_match('/-(\d{4}-\d{2}-\d{2})$/', $slugOrUrl, $m) === 1) {
        return [substr($slugOrUrl, 0, -11), $m[1]];
    }
    return [$slugOrUrl, ''];
};

// --- maps.event: pick soonest qualifying future id per evergreen slug ---
$legacy = $index['maps']['event'] ?? [];
$best = []; // base slug => ['id'=>int,'date'=>string]
foreach ($legacy as $slug => $id) {
    [$base, $date] = $strip((string) $slug);
    if ($date === '' || $date < $minEventDate) {
        continue;
    }
    $cur = $best[$base] ?? null;
    if ($cur === null || $date < $cur['date']) {
        $best[$base] = ['id' => (int) $id, 'date' => $date];
    }
}

// --- urls.events: dedupe to evergreen form, preserve first-seen order, but only
// list URLs backed by a qualifying future date. ---
$before = count($index['urls']['events'] ?? []);
$seen = [];
$evergreen = [];
foreach ($index['urls']['events'] ?? [] as $url) {
    [$base] = $strip((string) $url);
    $slug = preg_replace('#^/event/#', '', $base);
    if (isset($best[$slug]) && !isset($seen[$base])) {
        $seen[$base] = true;
        $evergreen[] = '/event/' . $slug;
    }
}
foreach (array_keys($best) as $slug) {
    $url = '/event/' . $slug;
    if (!isset($seen[$url])) {
        $seen[$url] = true;
        $evergreen[] = $url;
    }
}
$index['urls']['events'] = $evergreen;

$newMap = [];
foreach ($best as $base => $pick) {
    $newMap[$base] = $pick['id'];      // evergreen slug -> soonest qualifying id
}
// Preserve legacy and prior evergreen keys so already-indexed URLs still resolve.
// Qualifying evergreen keys already inserted above keep their fresher id.
foreach ($legacy as $slug => $id) {
    if (!isset($newMap[$slug])) {
        $newMap[$slug] = (int) $id;
    }
}
$index['maps']['event'] = $newMap;

if (isset($index['counts']['events'])) {
    $index['counts']['events'] = count($evergreen);
}
$index['generated_at'] = gmdate('Y-m-d');
if (!isset($index['limits']) || !is_array($index['limits'])) {
    $index['limits'] = [];
}
$index['limits']['event_min_days'] = $eventMinDays;
$index['limits']['event_min_date'] = $minEventDate;

$json = json_encode($index, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
if ($json === false) {
    fwrite(STDERR, "json_encode failed\n");
    exit(1);
}
// Atomic write: a concurrent seo_index() read must never see a truncated file.
$tmp = $file . '.tmp.' . getmypid();
if (@file_put_contents($tmp, $json) === strlen($json)) {
    @rename($tmp, $file);
} else {
    @unlink($tmp);
    fwrite(STDERR, "short write to $file\n");
    exit(1);
}

$delta = $before - count($evergreen);
$deltaLabel = $delta >= 0 ? $delta . ' removed/collapsed' : abs($delta) . ' added';
printf(
    "events: %d previous URLs -> %d evergreen URLs (%s, min date %s)\n",
    $before,
    count($evergreen),
    $deltaLabel,
    $minEventDate
);
printf("maps.event: %d entries (%d evergreen + %d legacy dated)\n", count($newMap), count($best), count($newMap) - count($best));
