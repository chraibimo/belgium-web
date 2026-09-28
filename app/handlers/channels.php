<?php
/**
 * Channel search for the home page's "Is your channel on Mickey TV?" box and
 * the search on the channel list page.
 *
 *   GET /api/channels.php?q=bein         (n=1..96 names, default 8)
 *   -> {"q":"bein","total":263,"names":57,"shown":8,"results":[{name,country,genre,count,variants:[{c,s,n,g}]}]}
 *
 * Reads app/channel-index.txt (built by build/channel-index.php from the same
 * pack the Channel List page renders): one channel name per line, tab-separated
 * - search key | display name | country | genre | variant count | variants JSON.
 * Every term of the query has to appear in the key. Ranking: names that start
 * with the query, then names with a word that starts with it, then the rest;
 * shorter names and more variants first within a tier. Only the lines that
 * make the cut are parsed, so a query costs a few milliseconds.
 *
 * The index is static between builds, so responses are cacheable for an hour.
 */

if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
header('Vary: Accept-Encoding');

const CHANNELS_SHOWN     = 8;    // default; the channel list page asks for more
const CHANNELS_SHOWN_MAX = 96;
/** Within a tier, channels shown under the audience's countries come first. */
const CHANNELS_COUNTRY_RANK = ['BELGIUM' => 0, 'FRANCE' => 1, 'NETHERLANDS' => 2, 'UK' => 3, 'USA' => 4];

/** Same normalisation as build/channel-index.php: lowercase, accents folded, one space between words. */
function channels_key(string $s): string {
    $s = mb_strtolower($s, 'UTF-8');
    if (!preg_match('/[\x{0600}-\x{06ff}]/u', $s)) {          // Arabic names keep their letters
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if (is_string($t) && $t !== '' && preg_match('/[a-z0-9]/', $t)) $s = $t;
    }
    $s = preg_replace('/[^a-z0-9\x{0600}-\x{06ff}]+/u', ' ', $s);
    return trim($s);
}

$q = $_GET['q'] ?? '';
$q = is_string($q) ? mb_substr(trim($q), 0, 60) : '';
$key = channels_key($q);
$n   = max(1, min(CHANNELS_SHOWN_MAX, (int)($_GET['n'] ?? CHANNELS_SHOWN)));
$out = ['q' => $q, 'total' => 0, 'names' => 0, 'shown' => 0, 'results' => []];

if ($key === '' || !is_file(APP_DIR . '/channel-index.txt')) {
    echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$terms = array_values(array_unique(explode(' ', $key)));
$first = $terms[0];
$fh = fopen(APP_DIR . '/channel-index.txt', 'rb');
$hits = [];
while (($line = fgets($fh)) !== false) {
    $tab = strpos($line, "\t");
    if ($tab === false) continue;
    $k = substr($line, 0, $tab);
    foreach ($terms as $t) if (strpos($k, $t) === false) continue 2;
    // tier 0: the name starts with the whole query; 1: a word starts with the first term; 2: substring
    $tier = str_starts_with($k, $key) ? 0 : ((str_starts_with($k, $first) || strpos($k, ' ' . $first) !== false) ? 1 : 2);
    // country is the 3rd field, variant count the 5th
    $p2 = strpos($line, "\t", $tab + 1);
    $p3 = $p2 === false ? false : strpos($line, "\t", $p2 + 1);
    $p4 = $p3 === false ? false : strpos($line, "\t", $p3 + 1);
    $country = ($p2 !== false && $p3 !== false) ? substr($line, $p2 + 1, $p3 - $p2 - 1) : '';
    $count = $p4 === false ? 1 : (int)substr($line, $p4 + 1);
    $out['total'] += $count;
    $hits[] = [$tier, CHANNELS_COUNTRY_RANK[$country] ?? 9, strlen($k), -$count, $line];
}
fclose($fh);

$out['names'] = count($hits);
usort($hits, fn($a, $b) => $a[0] <=> $b[0] ?: $a[1] <=> $b[1] ?: $a[2] <=> $b[2] ?: $a[3] <=> $b[3] ?: strcmp($a[4], $b[4]));

foreach (array_slice($hits, 0, $n) as $h) {
    $f = explode("\t", rtrim($h[4], "\r\n"), 6);
    if (count($f) < 6) continue;
    $out['results'][] = [
        'name'     => $f[1],
        'country'  => $f[2],
        'genre'    => $f[3],
        'count'    => (int)$f[4],
        'variants' => json_decode($f[5], true) ?: [],
    ];
}
$out['shown'] = count($out['results']);

echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
