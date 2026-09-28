<?php
/**
 * Finished-page cache - the router serves a page it has already built.
 *
 * Safe because nothing a page contains depends on who is asking: everything
 * personal (the promo wheel, forms, cookie consent, checkout links) happens in
 * the browser. The key carries the origin, the language, the path and the
 * newest modification time of every file that shapes a page (templates,
 * content, layout, config, data JSON, the stylesheet and scripts), so an edit
 * or an upload is live on the very next request. The TTL is the backstop.
 *
 * Stored gzip-compressed in app/data/pagecache/ (outside the web root in the
 * repo layout, behind the app/ deny rule in the flattened one).
 *
 * Off switch: set PRIMO_PAGE_CACHE to false, or delete this file.
 */
if (!defined('PRIMO_APP')) exit;

const PRIMO_PAGE_CACHE     = true;
const PRIMO_PAGE_CACHE_TTL = 3600;   // seconds

function primo_page_cache_dir(): string { return APP_DIR . '/data/pagecache'; }

/** Newest mtime of everything that shapes a page. Computed once per request. */
function primo_site_stamp(): int {
    static $stamp = null;
    if ($stamp !== null) return $stamp;
    $stamp = 0;
    $files = array_merge(
        [DOCROOT . '/index.php', APP_DIR . '/config.php'],
        glob(INCLUDES_DIR . '/*.php') ?: [],
        glob(INCLUDES_DIR . '/{layout,pages,partials,content/*,content/*/posts}/*.php', GLOB_BRACE) ?: [],
        glob(APP_DIR . '/*.json') ?: [],
        glob(DOCROOT . '/assets/{css,js}/*.{css,js}', GLOB_BRACE) ?: []
    );
    foreach ($files as $f) {
        $t = (int) @filemtime($f);
        if ($t > $stamp) $stamp = $t;
    }
    return $stamp;
}

function primo_page_cache_key(string $id): string {
    return sha1($id . '|' . primo_site_stamp());
}

/** The stored page, or null when there is none or it is past its TTL. */
function primo_page_cache_get(string $key): ?string {
    if (!PRIMO_PAGE_CACHE || !function_exists('gzdecode')) return null;
    $file  = primo_page_cache_dir() . '/' . $key . '.html.gz';
    $mtime = @filemtime($file);
    if ($mtime === false || time() - $mtime > PRIMO_PAGE_CACHE_TTL) return null;
    $gz = @file_get_contents($file);
    if ($gz === false) return null;
    $html = @gzdecode($gz);
    return is_string($html) && $html !== '' ? $html : null;
}

/** Store a finished page. Any failure just means the next request builds it again. */
function primo_page_cache_put(string $key, string $html): void {
    if (!PRIMO_PAGE_CACHE || !function_exists('gzencode') || $html === '') return;
    $dir = primo_page_cache_dir();
    if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) return;
    $gz = gzencode($html, 6);
    if ($gz === false) return;
    $file = $dir . '/' . $key . '.html.gz';
    $tmp  = $file . '.' . getmypid() . '.tmp';
    if (@file_put_contents($tmp, $gz) === false) return;
    if (!@rename($tmp, $file)) { @unlink($tmp); return; }
    if (mt_rand(1, 50) === 1) {
        foreach (glob($dir . '/*.html.gz') ?: [] as $f) {
            if (time() - (int) @filemtime($f) > PRIMO_PAGE_CACHE_TTL) @unlink($f);
        }
    }
}
