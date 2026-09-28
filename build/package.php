<?php
/**
 * Build the Hostinger-ready deploy zip (same FLATTENED schema as primotv.ca):
 *
 *   php build/package.php [output.zip]      -> build/mickeytv-deploy.zip
 *
 * Inside the zip, public/ IS the docroot and app/ + includes/ sit next to
 * index.php, exactly as they live in public_html on the host:
 *
 *   public_html/
 *     .htaccess  index.php  assets/  static/
 *     app/        (config, handlers, channel index; app/.htaccess denies web access)
 *     includes/   (bootstrap, layout, pages, content; includes/.htaccess denies web access)
 *
 * public/index.php finds app/ and includes/ either above the docroot (repo layout)
 * or inside it (this zip), so nothing needs editing after extraction.
 * Excluded: app/data (runtime + PII, recreated on demand), *.local.php (secrets),
 * build/, dotfiles at the root, .git.
 *
 * Upload with hPanel -> Files -> File Manager -> public_html -> Upload -> the zip,
 * then right-click -> Extract (into public_html). Finally create
 * includes/mail-config.local.php (+ services-config.local.php, n8n-config.local.php).
 */
ini_set('memory_limit', '512M');
$root = dirname(__DIR__);
$out  = $argv[1] ?? __DIR__ . '/mickeytv-deploy.zip';

// Refuse to package a partial tree (checked BEFORE touching an existing zip so a
// failed run never destroys a good one).
foreach (['public/assets/css', 'public/assets/js', 'public/assets/data', 'includes/pages', 'includes/content/nl', 'includes/content/fr', 'app/handlers'] as $d) {
    if (!is_dir("$root/$d") || count(scandir("$root/$d")) <= 2) {
        fwrite(STDERR, "missing/empty $d - refusing to package (partial checkout?)\n");
        exit(1);
    }
}
foreach (['public/index.php', 'public/.htaccess', 'app/.htaccess', 'includes/.htaccess', 'app/config.php', 'app/channel-index.txt'] as $f) {
    if (!is_file("$root/$f")) { fwrite(STDERR, "missing $f - refusing to package\n"); exit(1); }
}

@unlink($out);
$zip = new ZipArchive();
if ($zip->open($out, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) { fwrite(STDERR, "cannot create $out\n"); exit(1); }

$textExt = ['html', 'htm', 'css', 'js', 'json', 'xml', 'svg', 'txt', 'map', 'htaccess', 'php', 'pem', 'jsonl'];

$add = function (string $dir, string $prefix, array $exclude) use ($zip, $root, $textExt): array {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root/$dir", FilesystemIterator::SKIP_DOTS));
    $n = 0; $bytes = 0;
    foreach ($it as $f) {
        if (!$f->isFile()) continue;
        $rel = str_replace(DIRECTORY_SEPARATOR, '/', substr($f->getPathname(), strlen("$root/$dir") + 1));
        foreach ($exclude as $ex) if (fnmatch($ex, $rel)) continue 2;
        $local = $prefix . $rel;
        $zip->addFile($f->getPathname(), $local);
        // Deflate compressible text; store already-compressed media (fast, no bloat).
        $ext = strtolower(pathinfo($f->getFilename(), PATHINFO_EXTENSION));
        $zip->setCompressionName($local, in_array($ext, $textExt, true) ? ZipArchive::CM_DEFLATE : ZipArchive::CM_STORE);
        $n++; $bytes += $f->getSize();
    }
    return [$n, $bytes];
};

$n = 0; $bytes = 0;
foreach ([
    ['public',   '',          ['*.tmp', '*.log']],
    ['app',      'app/',      ['data/*', '*.tmp', '*.log']],
    ['includes', 'includes/', ['*.local.php', '*.tmp', '*.log']],
] as [$dir, $prefix, $exclude]) {
    [$a, $b] = $add($dir, $prefix, $exclude);
    $n += $a; $bytes += $b;
}
// Runtime folder: submissions.jsonl, throttle counters, page cache, search cache.
// Created empty so it exists with the right owner; everything inside is written by PHP.
$zip->addEmptyDir('app/data');
$zip->addEmptyDir('app/data/pagecache');

echo "staged $n files (" . round($bytes / 1048576, 1) . " MB uncompressed); compressing...\n";
if ($zip->close() !== true) { fwrite(STDERR, "zip close failed\n"); exit(1); }
printf("wrote %s (%.1f MB)\n", $out, filesize($out) / 1048576);
echo "Extract inside public_html (the zip has no top-level folder), then create includes/mail-config.local.php.\n";
