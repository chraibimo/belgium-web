<?php
/**
 * Sweep every URL in the sitemap (plus the API routes and the static assets a
 * page references) against a running server and report anything that is not
 * 200 or that shows a PHP error.
 *
 *   php -S 127.0.0.1:8130 -t public public/index.php      (in another window)
 *   php build/verify.php http://127.0.0.1:8130
 */
$base = rtrim($argv[1] ?? 'http://127.0.0.1:8130', '/');

function get(string $url): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false, CURLOPT_TIMEOUT => 20, CURLOPT_HEADER => false]);
    $body = (string)curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    return [$code, $body];
}

[$code, $xml] = get("$base/sitemap.xml");
if ($code !== 200 || !preg_match_all('~<loc>([^<]+)</loc>~', $xml, $m)) { fwrite(STDERR, "sitemap unreachable ($code)\n"); exit(1); }
$origin = parse_url($m[1][0], PHP_URL_SCHEME) . '://' . parse_url($m[1][0], PHP_URL_HOST) . (parse_url($m[1][0], PHP_URL_PORT) ? ':' . parse_url($m[1][0], PHP_URL_PORT) : '');
$urls = array_map(fn($u) => str_replace($origin, $base, html_entity_decode($u)), $m[1]);
$urls[] = "$base/robots.txt";
$urls[] = "$base/api/channels.php?q=vrt";
$urls[] = "$base/api/search.php?q=iptv&lang=nl";
$urls[] = "$base/api/search.php?q=iptv&lang=fr";

$bad = 0; $assets = []; $n = 0;
foreach ($urls as $u) {
    [$code, $body] = get($u);
    $n++;
    // /chat/ is a 302 to the tawk.to conversation whenever the chat is configured.
    $expect302 = (bool)preg_match('~/(chat)/$~', $u);
    $err = ($expect302 ? $code !== 302 && $code !== 200 : $code !== 200)
        || preg_match('~(Fatal error|Warning:|Notice:|Parse error)~', $body);
    if ($err) { $bad++; echo "  $code  $u\n"; }
    if (preg_match_all('~(?:href|src)="(/assets/[^"?]+|/static/[^"?]+)~', $body, $am)) foreach ($am[1] as $a) $assets[$a] = true;
}
echo "$n pages, " . ($bad ? "$bad problems" : 'all OK') . "\n";

$abad = 0;
foreach (array_keys($assets) as $a) {
    [$code] = get($base . $a);
    if ($code !== 200) { $abad++; echo "  $code  $a\n"; }
}
echo count($assets) . " assets, " . ($abad ? "$abad missing" : 'all OK') . "\n";
exit($bad + $abad ? 1 : 0);
