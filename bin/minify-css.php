<?php
declare(strict_types=1);

/**
 * minify-css.php — generate assets/styles.min.css from assets/styles.css.
 *
 * styles.css stays the editable source of truth; the layout links the .min file
 * when present (and falls back to styles.css if it's ever missing). Conservative:
 * strips comments and collapses whitespace, leaving calc()/url()/strings intact.
 *
 * Run after any CSS edit:  php bin/minify-css.php
 */

$src = dirname(__DIR__) . '/assets/styles.css';
$dst = dirname(__DIR__) . '/assets/styles.min.css';

$css = (string) file_get_contents($src);
if ($css === '') {
    fwrite(STDERR, "empty or missing $src\n");
    exit(1);
}
$before = strlen($css);

// Strip comments (keep /*! important banners), collapse whitespace, tidy symbols.
$css = (string) preg_replace('#/\*(?!\!).*?\*/#s', '', $css);
$css = (string) preg_replace('/\s+/', ' ', $css);
$css = (string) preg_replace('/\s*([{}:;,>~])\s*/', '$1', $css);
$css = str_replace(';}', '}', $css);
$css = trim($css);

// Atomic write: the live layout links this stylesheet, so a concurrent read
// must never see a truncated file. rename() is atomic on the same filesystem.
$tmp = $dst . '.tmp' . getmypid();
if (@file_put_contents($tmp, $css) === strlen($css)) {
    @rename($tmp, $dst);
} else {
    @unlink($tmp);
    fwrite(STDERR, "short write to $dst\n");
    exit(1);
}
$after = strlen($css);
printf("styles.min.css: %d -> %d bytes (-%d%%)\n", $before, $after, (int) round(100 * ($before - $after) / max(1, $before)));
