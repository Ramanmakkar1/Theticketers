<?php
declare(strict_types=1);

/**
 * resize-media.php — Shrink downloaded artwork in assets/media to web size.
 *
 * Ticketmaster ships 2426x1365 originals (~500KB each); cards render them at
 * ~300-600px. This caps every image at MAX_W wide, re-encodes as JPEG q80
 * (PNG/WebP converted), updates storage/tm-images.json when an extension
 * changes, and is idempotent — already-small files are skipped.
 *
 * Run standalone (after a fetch pass, or once to fix an existing library):
 *   php bin/resize-media.php
 * Also required by bin/fetch-tm-images.php, which resizes on download.
 */

const TB_MEDIA_MAX_W = 800;
const TB_MEDIA_QUALITY = 80;

/**
 * Resize one file in place (JPEG q80, max-width cap). Returns the file's web
 * filename after processing (extension may change PNG→JPG), or null on failure.
 * Uses GD when built with JPEG support (typical shared host); falls back to
 * macOS `sips` for local dev builds whose GD lacks JPEG.
 */
function tb_resize_image(string $file, int $maxW = TB_MEDIA_MAX_W): ?string
{
    if (!is_file($file)) {
        return null;
    }
    $info = @getimagesize($file);
    if ($info === false) {
        return null;
    }
    [$w, $h] = $info;
    $isJpeg = ($info[2] ?? 0) === IMAGETYPE_JPEG;
    if ($w <= $maxW && $isJpeg && filesize($file) < 220000) {
        return basename($file); // already web-sized
    }

    $target = preg_replace('/\.(png|webp)$/i', '.jpg', $file) ?? $file;

    $gdJpeg = function_exists('imagecreatefromstring') && function_exists('imagejpeg');
    if ($gdJpeg) {
        $src = @imagecreatefromstring((string) file_get_contents($file));
        if ($src === false) {
            return null;
        }
        if ($w > $maxW) {
            $newW = $maxW;
            $newH = (int) round($h * $maxW / $w);
            $dst = imagecreatetruecolor($newW, $newH);
            // PNG transparency flattens to white (cards sit on light backgrounds).
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($src);
            $src = $dst;
        }
        $ok = imagejpeg($src, $target, TB_MEDIA_QUALITY);
        imagedestroy($src);
        if (!$ok) {
            return null;
        }
    } elseif (PHP_OS_FAMILY === 'Darwin' && is_executable('/usr/bin/sips')) {
        $cmd = sprintf(
            '/usr/bin/sips -s format jpeg -s formatOptions %d --resampleWidth %d %s --out %s 2>/dev/null',
            TB_MEDIA_QUALITY,
            min($maxW, $w),
            escapeshellarg($file),
            escapeshellarg($target)
        );
        exec($cmd, $out, $code);
        if ($code !== 0 || !is_file($target)) {
            return null;
        }
    } else {
        return null; // no resize capability — keep the original
    }

    if ($target !== $file) {
        @unlink($file);
    }
    return basename($target);
}

// ---- Standalone mode: process the whole library + sync the map ----
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === realpath(__FILE__)) {
    $root = dirname(__DIR__);
    $mediaDir = $root . '/assets/media';
    $mapFile = $root . '/storage/tm-images.json';
    $map = is_file($mapFile) ? (json_decode((string) file_get_contents($mapFile), true) ?: []) : [];
    $byFile = [];
    foreach ($map as $key => $path) {
        $byFile[basename((string) $path)] = $key;
    }

    $files = glob($mediaDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
    $done = 0;
    $skipped = 0;
    $bytesBefore = 0;
    $bytesAfter = 0;
    foreach ($files as $file) {
        $before = filesize($file) ?: 0;
        $oldName = basename($file);
        $newName = tb_resize_image($file);
        if ($newName === null) {
            $skipped++;
            continue;
        }
        $newFile = $mediaDir . '/' . $newName;
        $after = is_file($newFile) ? (filesize($newFile) ?: 0) : $before;
        $bytesBefore += $before;
        $bytesAfter += $after;
        if ($newName !== $oldName && isset($byFile[$oldName])) {
            $map[$byFile[$oldName]] = '/assets/media/' . $newName;
        }
        $done++;
    }

    ksort($map);
    $json = json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        fwrite(STDERR, "json_encode failed for $mapFile\n");
        exit(1);
    }
    // Atomic write: a concurrent image_map() read must never see a truncated file.
    $tmp = $mapFile . '.tmp.' . getmypid();
    if (@file_put_contents($tmp, $json) === strlen($json)) {
        @rename($tmp, $mapFile);
    } else {
        @unlink($tmp);
        fwrite(STDERR, "short write to $mapFile\n");
        exit(1);
    }
    fwrite(STDOUT, sprintf(
        "resized %d (skipped %d) | %.1f MB -> %.1f MB\n",
        $done,
        $skipped,
        $bytesBefore / 1048576,
        $bytesAfter / 1048576
    ));
}
