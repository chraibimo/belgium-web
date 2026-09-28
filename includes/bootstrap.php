<?php
/**
 * Shared helpers for templates and handlers: escaping, translation, URLs,
 * content loading, posts. Included once by the front controller.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

require_once __DIR__ . '/data.php';

/** HTML-escape. Every dynamic value printed into markup goes through this. */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* ------------------------------------------------------------ language */

function lang(): string {
    return $GLOBALS['__lang'] ?? DEFAULT_LANG;
}

function set_lang(string $l): void {
    $GLOBALS['__lang'] = in_array($l, SITE_LANGS, true) ? $l : DEFAULT_LANG;
    $GLOBALS['__strings'] = null;
}

function lang_prefix(?string $l = null): string {
    $l = $l ?? lang();
    return $l === DEFAULT_LANG ? '' : '/' . $l;
}

/** Locale code for <html lang> and Open Graph. */
function locale(?string $l = null): string {
    return match ($l ?? lang()) { 'fr' => 'fr-BE', default => 'nl-BE' };
}

function og_locale(?string $l = null): string {
    return match ($l ?? lang()) { 'fr' => 'fr_BE', default => 'nl_BE' };
}

/** UI string: t('nav.pricing'), t('x.y', $a, $b) with sprintf placeholders. */
function t(string $key, ...$args): string {
    if (!isset($GLOBALS['__strings'])) {
        $GLOBALS['__strings'] = require INCLUDES_DIR . '/content/' . lang() . '/strings.php';
    }
    $s = $GLOBALS['__strings'][$key] ?? null;
    if ($s === null) {
        $fallback = $GLOBALS['__strings_default'] ??= require INCLUDES_DIR . '/content/' . DEFAULT_LANG . '/strings.php';
        $s = $fallback[$key] ?? $key;
    }
    return $args ? vsprintf($s, $args) : $s;
}

/** Page content array: content('home') -> includes/content/<lang>/home.php */
function content(string $name, ?string $l = null): array {
    $l = $l ?? lang();
    $file = INCLUDES_DIR . '/content/' . $l . '/' . $name . '.php';
    if (!is_file($file)) return [];
    $data = require $file;
    return is_array($data) ? $data : [];
}

/* ------------------------------------------------------------ routes */

function routes(): array {
    static $r = null;
    return $r ??= require INCLUDES_DIR . '/routes.php';
}

/** URL of a registered page, in the current (or given) language. */
function url(string $key, ?string $l = null, string $suffix = ''): string {
    $l = $l ?? lang();
    $r = routes();
    if (!isset($r[$key])) return lang_prefix($l) . '/';
    $slug = $r[$key][$l] ?? $r[$key][DEFAULT_LANG];
    return lang_prefix($l) . '/' . ($slug === '' ? '' : $slug . '/') . $suffix;
}

function post_url(array $post, ?string $l = null): string {
    return lang_prefix($l ?? lang()) . '/' . $post['slug'] . '/';
}

function tag_url(string $tag, ?string $l = null): string {
    return url('blog', $l) . 'tag/' . rawurlencode($tag) . '/';
}

/* ------------------------------------------------------------ posts */

/** All posts of a language, newest first. Each is the array its file returns. */
function posts(?string $l = null): array {
    static $cache = [];
    $l = $l ?? lang();
    if (isset($cache[$l])) return $cache[$l];
    $list = [];
    foreach (glob(INCLUDES_DIR . '/content/' . $l . '/posts/*.php') ?: [] as $f) {
        $p = require $f;
        if (!is_array($p) || empty($p['slug'])) continue;
        $p['key'] = basename($f, '.php');
        $p['lang'] = $l;
        $list[] = $p;
    }
    usort($list, fn($a, $b) => strcmp($b['date'], $a['date']));
    return $cache[$l] = $list;
}

function post_by_slug(string $slug, ?string $l = null): ?array {
    foreach (posts($l) as $p) if ($p['slug'] === $slug) return $p;
    return null;
}

function post_by_key(string $key, ?string $l = null): ?array {
    foreach (posts($l) as $p) if ($p['key'] === $key) return $p;
    return null;
}

/** Tag => post count, for the current language. */
function tags(?string $l = null): array {
    $tags = [];
    foreach (posts($l) as $p) foreach ($p['tags'] ?? [] as $t) $tags[$t] = ($tags[$t] ?? 0) + 1;
    ksort($tags);
    return $tags;
}

/* ------------------------------------------------------------ misc */

/** 12345 -> "12.345" (nl) / "12 345" (fr). */
function nf(int|float $n, int $dec = 0): string {
    return lang() === 'fr'
        ? number_format($n, $dec, ',', " ")
        : number_format($n, $dec, ',', '.');
}

/** "43.000+" style rounded-down figure for marketing copy. */
function nf_plus(int $n, int $step = 1000): string {
    return nf(intdiv($n, $step) * $step) . '+';
}

/** € 24,99 formatted the Belgian way. */
function money(float $v): string {
    return '€ ' . number_format($v, 2, ',', '.');
}

function date_human(string $ymd): string {
    $ts = strtotime($ymd);
    $months = lang() === 'fr'
        ? ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre']
        : ['januari','februari','maart','april','mei','juni','juli','augustus','september','oktober','november','december'];
    return (int)date('j', $ts) . ' ' . $months[(int)date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

/** Read minutes from a body's word count (min 1). */
function reading_minutes(string $html): int {
    return max(1, (int)round(str_word_count(strip_tags($html)) / 200));
}

/** Deterministic palette/motif for generated poster art from a string. */
function poster_seed(string $s): array {
    $palettes = ['red', 'maroon', 'cream', 'navy', 'forest', 'mustard', 'ink', 'teal', 'salmon', 'plum'];
    $motifs   = ['sun', 'moon', 'grid', 'rings', 'waves', 'bars', 'rails', 'city', 'orbit', 'dots', 'mountains', 'split', 'stairs', 'eye'];
    $h = crc32($s);
    return ['palette' => $palettes[$h % count($palettes)], 'motif' => $motifs[($h >> 8) % count($motifs)]];
}

/**
 * Generated poster markup (rendered by public/assets/js/posters.js). The title
 * is in the DOM, so the card reads with JS off; the SVG art is drawn on load.
 */
function poster(string $title, string $seed, string $variant = 'portrait', array $opts = []): string {
    $p = poster_seed($seed);
    $palette = $opts['palette'] ?? $p['palette'];
    $motif   = $opts['motif'] ?? $p['motif'];
    $meta    = isset($opts['meta']) ? '<span class="poster__meta">' . e($opts['meta']) . '</span>' : '';
    $cls     = 'poster poster--' . $variant . (isset($opts['class']) ? ' ' . $opts['class'] : '');
    return '<div class="' . $cls . '" data-palette="' . e($palette) . '" data-motif="' . e($motif) . '" data-seed="' . e($seed) . '" aria-hidden="true">'
        . '<div class="poster__text">' . $meta . '<span class="poster__title">' . e($title) . '</span></div></div>';
}

/**
 * How many times to repeat a marquee lane's item set so the lane is at least
 * $minTotal px wide (two identical halves are needed for the -50% loop, so the
 * count is always even and at least 2). Keeps wide screens free of gaps.
 */
function marquee_copies(int $items, int $itemWidth, int $minTotal = 4400): int {
    if ($items < 1) return 2;
    $copies = (int)ceil($minTotal / max(1, $items * $itemWidth));
    $copies = max(2, $copies);
    return $copies % 2 ? $copies + 1 : $copies;
}

/** Absolute origin for canonical/og/sitemap (whitelisted host). */
function current_origin(): string {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    $raw   = $_SERVER['HTTP_HOST'] ?? DEFAULT_HOST;
    $parts = explode(':', $raw, 2);
    $h     = strtolower($parts[0]);
    $host  = (in_array($h, ALLOWED_HOSTS, true) && (!isset($parts[1]) || ctype_digit($parts[1]))) ? $raw : DEFAULT_HOST;
    return ($https ? 'https://' : 'http://') . $host;
}

function is_dev(): bool {
    $h = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0]);
    return in_array($h, ['localhost', '127.0.0.1'], true);
}

/** Current promo round: [ends_at (unix), starts_at]. The real recurring cycle;
 *  what the tickets actually count down is pricing.promo.session_seconds when set. */
function promo_round(): ?array {
    $p = site_data()['pricing']['promo'];
    if (empty($p['enabled'])) return null;
    $anchor = strtotime($p['anchor']);
    $len    = max(1, (int)$p['cycle_days']) * 86400;
    $now    = time();
    $start  = $anchor + intdiv(max(0, $now - $anchor), $len) * $len;
    return ['start' => $start, 'end' => $start + $len];
}

/** JSON for inline <script> data blocks (never closes the tag early). */
function json_attr(mixed $v): string {
    return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);
}

require_once __DIR__ . '/layout.php';
