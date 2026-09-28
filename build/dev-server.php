<?php
/**
 * Development front door that behaves like the production host.
 *
 *   php -S 127.0.0.1:8130 -t build/devroot build/dev-server.php
 *
 * The built-in server hands static files out itself - raw, uncached - which makes
 * a local Lighthouse run look far worse than the real site. Pointing the docroot
 * at the empty build/devroot sends every request through here instead, so assets
 * get the gzip and the immutable caching that public/.htaccess sets on Apache /
 * LiteSpeed. Pages fall through to public/index.php exactly as they do live.
 *
 * Nothing here ships: the deploy zip contains public/, app/ and includes/ only.
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$root = dirname(__DIR__) . '/public';
$file = realpath($root . $path);

if ($file !== false && is_file($file) && str_starts_with($file, realpath($root)) && $path !== '/') {
    $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $type = [
        'css' => 'text/css', 'js' => 'application/javascript', 'json' => 'application/json',
        'svg' => 'image/svg+xml', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp', 'gif' => 'image/gif', 'ico' => 'image/x-icon', 'woff2' => 'font/woff2',
        'mp4' => 'video/mp4', 'txt' => 'text/plain', 'xml' => 'application/xml', 'webmanifest' => 'application/manifest+json',
    ][$ext] ?? 'application/octet-stream';

    $body = (string)file_get_contents($file);
    header('Content-Type: ' . $type);
    header('Cache-Control: public, max-age=31536000, immutable');   // public/.htaccess, FilesMatch block
    header('Vary: Accept-Encoding');
    // mod_deflate compresses text; images, fonts and video are already compressed
    $text = preg_match('~^(text/|application/(javascript|json|xml|manifest)|image/svg)~', $type);
    if ($text && str_contains(strtolower($_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''), 'gzip')) {
        $gz = gzencode($body, 6);
        if ($gz !== false && strlen($gz) < strlen($body)) { header('Content-Encoding: gzip'); $body = $gz; }
    }
    header('Content-Length: ' . strlen($body));
    echo $body;
    exit;
}

require dirname(__DIR__) . '/public/index.php';
