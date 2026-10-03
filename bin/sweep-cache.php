<?php
declare(strict_types=1);

/**
 * sweep-cache.php — delete cache files in storage/cache/ that are older than a TTL.
 *
 * Nothing in the app evicts by age. index.php (:82-88) republishes an HTML entry only when
 * that exact URL is requested again, and HelloTicketsClient::get() only overwrites an API
 * entry when that exact key is re-requested — so an expired file is harmless to a reader
 * but stays on disk forever. Over a full crawl of the ~28K-page long tail that is unbounded
 * growth (1,093 files / 22 MB here from a couple of days of local browsing).
 *
 * This is the other half: anything older than the TTL would have been refetched on its next
 * hit, so deleting it costs one slow request per evicted entry and nothing else. Run it by
 * hand or from cron — deliberately NOT wired into any deploy step:
 *
 *   php bin/sweep-cache.php                    # 7 days (default)
 *   php bin/sweep-cache.php --ttl=86400        # 24 hours
 *   php bin/sweep-cache.php --ttl=2592000      # 30 days
 *   php bin/sweep-cache.php --dry-run          # report only, delete nothing
 *   CACHE_SWEEP_TTL=86400 php bin/sweep-cache.php
 *
 * Scope guarantees, asserted before anything is deleted:
 *   - only files UNDER this project's own storage/cache are considered (the walk root is
 *     realpath-compared against $root/storage/cache, so a moved or mistyped cache_dir
 *     aborts the run rather than sweeping somewhere else);
 *   - symlinks are never followed and never deleted;
 *   - dotfiles (.gitkeep) are never deleted;
 *   - directories are never deleted, so storage/cache/html/ survives an empty sweep;
 *   - the TTL must be > 0 — there is no "delete everything" default.
 */

$root = dirname(__DIR__);
$config = require $root . '/src/config.php';

/** 1.5 MB etc. */
function human_bytes(int $bytes): string
{
    if ($bytes < 1024) {
        return $bytes . ' B';
    }
    $units = ['KB', 'MB', 'GB', 'TB'];
    $value = $bytes / 1024;
    $unit = 0;
    while ($value >= 1024 && $unit < count($units) - 1) {
        $value /= 1024;
        $unit++;
    }
    return sprintf('%.1f %s', $value, $units[$unit]);
}

$opts = getopt('', ['ttl::', 'dry-run']);
$rawTtl = (string) ($opts['ttl'] ?? getenv('CACHE_SWEEP_TTL') ?: '');
$ttl = $rawTtl === '' ? 604800 : (int) $rawTtl; // 7 days
$dryRun = isset($opts['dry-run']);
if ($ttl <= 0) {
    fwrite(STDERR, "--ttl must be a positive number of seconds (got '" . $rawTtl . "')\n");
    exit(1);
}

// --- Containment check: refuse to sweep anything but this project's storage/cache. ------
$cacheDir = realpath((string) $config['cache_dir']);
$expected = realpath($root . '/storage/cache');
if ($cacheDir === false || $expected === false || $cacheDir !== $expected) {
    fwrite(STDERR, sprintf(
        "refusing to sweep: cache_dir resolves to %s, expected %s\n",
        var_export($cacheDir, true),
        var_export($expected, true)
    ));
    exit(1);
}
if (basename($cacheDir) !== 'cache' || basename(dirname($cacheDir)) !== 'storage') {
    fwrite(STDERR, "refusing to sweep: $cacheDir is not storage/cache\n");
    exit(1);
}
if (!is_dir($cacheDir)) {
    fwrite(STDERR, "$cacheDir does not exist — nothing to sweep\n");
    exit(0);
}

$cutoff = time() - $ttl;
$removed = [];    // relative path => bytes
$keptFresh = 0;
$protected = 0;   // dotfiles (.gitkeep) and symlinks — never touched
$failed = 0;

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($cacheDir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::LEAVES_ONLY
);

try {
    foreach ($iterator as $file) {
        /** @var SplFileInfo $file */
        $relative = ltrim(substr($file->getPathname(), strlen($cacheDir)), '/');

        // Never follow or remove a symlink: it can point anywhere, including outside the
        // cache dir. RecursiveDirectoryIterator does not descend into linked directories
        // by default; this covers a link sitting directly in the tree too.
        if ($file->isLink()) {
            $protected++;
            continue;
        }
        if (!$file->isFile()) {
            continue;
        }
        // .gitkeep (and any other dotfile) is tracked/managed, not cache content.
        if (str_starts_with($file->getFilename(), '.')) {
            $protected++;
            continue;
        }
        if ($file->getMTime() >= $cutoff) {
            $keptFresh++;
            continue;
        }

        $bytes = (int) $file->getSize();
        if (!$dryRun && !@unlink($file->getPathname())) {
            $failed++;
            fwrite(STDERR, "  ! could not remove $relative\n");
            continue;
        }
        $removed[$relative] = $bytes;
    }
} catch (UnexpectedValueException $exception) {
    // An unreadable subdirectory must not abort the sweep of everything else.
    fwrite(STDERR, '  ! skipped a subdirectory: ' . $exception->getMessage() . "\n");
}

fwrite(STDERR, sprintf(
    "%s storage/cache entries older than %ds (before %sZ)\n",
    $dryRun ? 'would remove' : 'removed',
    $ttl,
    gmdate('Y-m-d H:i', $cutoff)
));

// Per-directory counts: ./ holds the API cache (HelloTicketsClient), html/ the output
// cache (index.php).
$byDir = [];
foreach ($removed as $relative => $bytes) {
    $dir = str_contains($relative, '/') ? substr($relative, 0, (int) strpos($relative, '/')) . '/' : './';
    $byDir[$dir] = ($byDir[$dir] ?? 0) + 1;
}
ksort($byDir);
foreach ($byDir as $dir => $count) {
    printf("  %-6s %d file%s\n", $dir, $count, $count === 1 ? '' : 's');
}

// Then a sample, so the report shows WHAT went rather than only a total.
$sample = array_slice($removed, 0, 10, true);
foreach ($sample as $relative => $bytes) {
    printf("  - %s (%s)\n", $relative, human_bytes($bytes));
}
if (count($removed) > count($sample)) {
    printf("  … and %d more\n", count($removed) - count($sample));
}

printf(
    "%s %d file%s, %s freed; kept %d fresher than the TTL and %d protected entr%s.\n",
    $dryRun ? 'would remove' : 'removed',
    count($removed),
    count($removed) === 1 ? '' : 's',
    human_bytes(array_sum($removed)),
    $keptFresh,
    $protected,
    $protected === 1 ? 'y' : 'ies'
);
if ($failed > 0) {
    fwrite(STDERR, "$failed file(s) could not be removed — check permissions on $cacheDir\n");
    exit(1);
}
