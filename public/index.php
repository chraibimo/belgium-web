<?php
/**
 * Front controller. Every request that is not an existing static file lands here
 * (php -S router mode, or Apache via .htaccess).
 *
 * Order: static files (dev) -> security headers -> API routes -> redirects ->
 * robots/sitemap -> language + page resolution -> page cache -> render -> 404.
 */

// Public site: never leak PHP internals (absolute paths, stack traces) into a
// response. Errors are logged, not displayed.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Marks legitimate entry through the front controller; app/ files refuse direct hits.
define('PRIMO_APP', true);

// Portable dir resolution: works whether app/ sits above the web root
// (repo / Git deploy: public/ + ../app) or inside it (flattened zip: public_html/app).
define('APP_DIR', is_dir(__DIR__ . '/../app') ? __DIR__ . '/../app' : __DIR__ . '/app');
define('INCLUDES_DIR', is_dir(__DIR__ . '/../includes') ? __DIR__ . '/../includes' : __DIR__ . '/includes');
define('DOCROOT', __DIR__);

require APP_DIR . '/config.php';
require INCLUDES_DIR . '/bootstrap.php';
// finished-page cache (optional - delete the file and every page is built per request)
if (is_file(INCLUDES_DIR . '/page-cache.php')) require INCLUDES_DIR . '/page-cache.php';
if (is_file(INCLUDES_DIR . '/maintenance.php')) { require INCLUDES_DIR . '/maintenance.php'; maintenance_gate(); }

$uri  = $_SERVER['REQUEST_URI'] ?? '/';
$path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?: '/');

/* php -S router mode: let the built-in server deliver real files itself.
   Resolve + contain the path so a ../ traversal can't point is_file() outside
   the web root (dev-only hardening; Apache serves static files directly). */
if (PHP_SAPI === 'cli-server') {
    $real = realpath(__DIR__ . $path);
    if ($path !== '/' && $real !== false && str_starts_with($real, __DIR__ . DIRECTORY_SEPARATOR) && is_file($real)) {
        return false;
    }
}

/* Security headers on every dynamic response (static assets get them via .htaccess). */
header_remove('X-Powered-By');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header("Content-Security-Policy: frame-ancestors 'self'");
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {
    header('Strict-Transport-Security: max-age=15768000');   // 6 months; no includeSubDomains/preload
}

function redirect301(string $to): never { header('Location: ' . $to, true, 301); exit; }
function redirect302(string $to): never { header('Location: ' . $to, true, 302); exit; }

/* ------------------------------------------------------------ api routes */

if ($path === '/api/ajax.php' || $path === '/api/forms.php') { require APP_DIR . '/handlers/ajax.php';    exit; }
if ($path === '/api/channels.php')                            { require APP_DIR . '/handlers/channels.php'; exit; }
if ($path === '/api/hook.php')                                { require APP_DIR . '/handlers/hook.php';     exit; }
if ($path === '/api/search.php') {
    require APP_DIR . '/handlers/search.php';
    set_lang(in_array($_GET['lang'] ?? '', SITE_LANGS, true) ? $_GET['lang'] : DEFAULT_LANG);
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: public, max-age=300');
    $q = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 100);
    echo json_encode(['results' => mb_strlen($q) >= 2 ? search_articles($q, 6) : []], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}
if (str_starts_with($path, '/api/')) { http_response_code(404); header('Content-Type: application/json'); echo '{"ok":false}'; exit; }

/* ------------------------------------------------------------ redirects */

$redirects = require APP_DIR . '/redirects.php';
$bare = rtrim($path, '/');
if ($bare !== '' && isset($redirects[$bare])) redirect301($redirects[$bare]);
if (preg_match('~^/(?:wp-admin|wp-login|wp-content|wp-includes|wp-json|xmlrpc)~', $path)) redirect301('/');

/* ------------------------------------------------------------ robots + sitemap */

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "User-agent: *\nAllow: /\nDisallow: /api/\n\nSitemap: " . current_origin() . "/sitemap.xml\n";
    exit;
}
if ($path === '/sitemap.xml' || $path === '/sitemap_index.xml') {
    require INCLUDES_DIR . '/sitemap.php';
    exit;
}

/* ------------------------------------------------------------ language */

$lang = DEFAULT_LANG;
$rest = $path;
foreach (SITE_LANGS as $l) {
    if ($l === DEFAULT_LANG) continue;
    if ($path === '/' . $l || str_starts_with($path, '/' . $l . '/')) { $lang = $l; $rest = substr($path, strlen($l) + 1) ?: '/'; break; }
}
set_lang($lang);

/* First visit on the root: send a French-speaking browser to /fr/ once. A
   language chosen by clicking the switcher (cookie) always wins. */
$cookieLang = $_COOKIE['mtv_lang'] ?? '';
if ($path === '/' && !in_array($cookieLang, SITE_LANGS, true)) {
    $accept = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '');
    if ($accept !== '' && preg_match('/^\s*([a-z]{2})/', $accept, $m) && $m[1] === 'fr') {
        setcookie('mtv_lang', 'fr', ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax', 'httponly' => false]);
        redirect302('/fr/');
    }
}
if ($cookieLang !== $lang) {
    setcookie('mtv_lang', $lang, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax', 'httponly' => false]);
}

/* ------------------------------------------------------------ page resolution */

$slug = trim($rest, '/');
$page = null;      // route key
$post = null;      // post array
$tag  = null;      // tag archive
$routes = routes();

if ($slug === '') {
    $page = 'home';
} else {
    foreach ($routes as $key => $r) {
        if (($r[$lang] ?? null) === $slug) { $page = $key; break; }
    }
    if ($page === null) {
        $blogSlug = $routes['blog'][$lang];
        if (preg_match('~^' . preg_quote($blogSlug, '~') . '/tag/([^/]+)$~', $slug, $m)) {
            $tag = rawurldecode($m[1]);
            if (isset(tags()[$tag])) $page = 'tag';
        } elseif (($post = post_by_slug($slug)) !== null) {
            $page = 'post';
        }
    }
}

if ($page === null) {
    // A post or page slug from the other language? Send it to its own prefix.
    foreach (SITE_LANGS as $l) {
        if ($l === $lang) continue;
        foreach ($routes as $key => $r) if (($r[$l] ?? '') === $slug && $slug !== '') redirect301(url($key, $l));
        if (post_by_slug($slug, $l)) redirect301(lang_prefix($l) . '/' . $slug . '/');
    }
    http_response_code(404);
    $page = '404';
}

/* canonical trailing slash (keeps fbclid/utm_* through the redirect) */
if ($page !== '404' && $slug !== '' && !str_ends_with($path, '/')) {
    $qs = $_SERVER['QUERY_STRING'] ?? '';
    redirect301($path . '/' . ($qs !== '' ? '?' . $qs : ''));
}
if ($page === 'home' && $lang !== DEFAULT_LANG && $path === '/' . $lang) redirect301('/' . $lang . '/');

/* ------------------------------------------------------------ render */

$template = match ($page) {
    'post'  => 'post',
    'tag'   => 'archive',
    '404'   => '404',
    default => $routes[$page]['tpl'],
};
$templateFile = INCLUDES_DIR . '/pages/' . $template . '.php';

// The search page varies by query and is never cached; everything else is
// fixed markup (currency, forms, the wheel all happen in the browser).
$cacheable = function_exists('primo_page_cache_get')
    && $page !== 'search' && $page !== '404'
    && in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD'], true);
$cacheKey  = $cacheable ? primo_page_cache_key(current_origin() . '|' . $lang . '|' . $path) : '';
$html      = $cacheable ? primo_page_cache_get($cacheKey) : null;

if ($html !== null) {
    header('X-Primo-Cache: HIT');
} else {
    $GLOBALS['page'] = $page;
    $GLOBALS['post'] = $post;
    $GLOBALS['tag']  = $tag;
    ob_start();
    include $templateFile;
    $html = ob_get_clean();
    if ($cacheable) { primo_page_cache_put($cacheKey, $html); header('X-Primo-Cache: MISS'); }
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: ' . ($page === 'search' || $page === '404' ? 'no-store' : 'public, max-age=300'));
header('Vary: Host, Accept-Language, Accept-Encoding');

/* gzip here rather than trust the host: mod_deflate/LiteSpeed skip a response that already carries an encoding. */
if (function_exists('gzencode') && !ini_get('zlib.output_compression')
    && str_contains(strtolower($_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''), 'gzip')) {
    $gz = gzencode($html, 6);
    if ($gz !== false && strlen($gz) < strlen($html)) { header('Content-Encoding: gzip'); $html = $gz; }
}
header('Content-Length: ' . strlen($html));
echo $html;
