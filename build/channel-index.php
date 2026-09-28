<?php
/**
 * Channel search index for the home page's "Is your channel on Primo?" box
 * (includes/home-channel-check.php, answered by app/handlers/channels.php).
 *
 *   php build/channel-index.php          rebuild app/channel-index.txt + app/channel-stats.json
 *   php build/channel-index.php --dry    parse and report, write nothing
 *
 * Source of truth is the packed channel list already shipped inside
 * public/assets/data/channels-pack.js (window.PTV_PACK / PTV_CATS / PTV_META) -
 * the same data the Channel List page renders - so the two can never disagree.
 * Re-run this whenever that pack is regenerated.
 *
 * What it does: drops the adult block and the dated event feeds ("NFL | 01 -",
 * "... @ Jan 12 2:00 PM"), strips the source prefix ("US: ", "SLING: "), the
 * ᴴᴰ/ᴿᴬᵂ superscripts and the trailing quality tokens (HD, FHD, 4K, RAW ...)
 * so that "US: BEIN SPORTS 2 HD" and "SLING: BEIN SPORTS 2 ᴿᴬᵂ" group under one
 * channel, "BEIN SPORTS 2"; keeps every distinct variant under it; and maps
 * the 700-odd provider categories onto a dozen plain genres.
 *
 * Output: one channel per line, tab-separated -
 *   search key | display name | country label | genre | variant count | variants JSON
 * The handler scans the first field with strpos, so a query costs a few
 * milliseconds with no database and no opcache. Both files are UTF-8 and are
 * written byte-for-byte (no PowerShell round-trip - see CLAUDE.md).
 */

$root = dirname(__DIR__);
$dry  = in_array('--dry', $argv ?? [], true);
$src  = file_get_contents("$root/public/assets/data/channels-pack.js");
if ($src === false) { fwrite(STDERR, "cannot read public/assets/data/channels-pack.js\n"); exit(1); }

if (!preg_match('/^window\.PTV_PACK="(.*)";\r?$/m', $src, $m)) { fwrite(STDERR, "PTV_PACK not found\n"); exit(1); }
$pack = json_decode('"' . $m[1] . '"');
if (!preg_match('/^window\.PTV_CATS=(\[.*\]);\r?$/m', $src, $m)) { fwrite(STDERR, "PTV_CATS not found\n"); exit(1); }
$cats = json_decode($m[1], true);
if (!preg_match('/^window\.PTV_META=(\{.*\});\r?$/m', $src, $m)) { fwrite(STDERR, "PTV_META not found\n"); exit(1); }
$meta = json_decode($m[1], true);
if (!is_string($pack) || !is_array($cats) || !is_array($meta)) { fwrite(STDERR, "pack did not decode\n"); exit(1); }

/* ------------------------------------------------------------ helpers */

/** Region/quality blocks are labelled by what they are; countries keep their name. */
const COUNTRY_LABEL = [
    'United States' => 'USA', 'United Kingdom' => 'UK', 'Asia / India' => 'ASIA',
    'Switzerland / China' => 'SWITZERLAND', 'Malaysia / Malta' => 'MALAYSIA',
    'Ex-Yugoslavia' => 'EX-YU', '8K Ultra' => '8K', '4K Ultra HD' => '4K',
    'Latin America' => 'LATAM', 'Czech Republic' => 'CZECHIA', 'North Macedonia' => 'MACEDONIA',
    'World Cricket' => 'CRICKET',
];
/** Which country a channel is shown under when it exists in several - the site's audience first. */
const COUNTRY_PRIORITY = ['BE' => 0, 'FR' => 1, 'NL' => 2, 'UK' => 3, 'US' => 4];

/** Superscript letters (ᴴᴰ, ᴿᴬᵂ, ᶠᴴᴰ ...) back to plain text. */
const SUP = [
    'ᴬ' => 'A', 'ᴮ' => 'B', 'ᶜ' => 'C', 'ᴰ' => 'D', 'ᴱ' => 'E', 'ᶠ' => 'F', 'ᴳ' => 'G', 'ᴴ' => 'H', 'ᴵ' => 'I',
    'ᴶ' => 'J', 'ᴷ' => 'K', 'ᴸ' => 'L', 'ᴹ' => 'M', 'ᴺ' => 'N', 'ᴼ' => 'O', 'ᴾ' => 'P', 'ᵠ' => 'Q', 'ᴿ' => 'R',
    'ˢ' => 'S', 'ᵀ' => 'T', 'ᵁ' => 'U', 'ᵛ' => 'V', 'ᵂ' => 'W', 'ˣ' => 'X', 'ʸ' => 'Y', 'ᶻ' => 'Z',
    'ᵃ' => 'A', 'ᵇ' => 'B', 'ᵈ' => 'D', 'ᵉ' => 'E', 'ᵍ' => 'G', 'ʰ' => 'H', 'ⁱ' => 'I', 'ʲ' => 'J', 'ᵏ' => 'K',
    'ˡ' => 'L', 'ᵐ' => 'M', 'ⁿ' => 'N', 'ᵒ' => 'O', 'ᵖ' => 'P', 'ʳ' => 'R', 'ᵗ' => 'T', 'ᵘ' => 'U',
    '⁰' => '0', '¹' => '1', '²' => '2', '³' => '3', '⁴' => '4', '⁵' => '5', '⁶' => '6', '⁷' => '7', '⁸' => '8', '⁹' => '9',
];

/** Genre rules, tried in this order against the category name, then the channel name. */
const GENRES = [
    'KIDS'          => '/\b(kids?|children|cartoon|nick|disney|junior|jr\b|baby|toon|boomerang|cbeebies|femij|enfants|infantil|bambini|kinder|barn|dzieci|cocuk|детск)/iu',
    'NEWS'          => '/\b(news|cnn|msnbc|cnbc|bloomberg|c-?span|weather|newsmax|newsnation|hln|al jazeera|france 24|euronews|lajme|noticias|nachrichten|nieuws|nyheter|haber|actualit|info\b|lci|bfm|cnews)/iu',
    'MOVIES'        => '/\b(movie|movies|cinema|cinéma|cine\b|film|films|filma|hbo|starz|showtime|cinemax|mgm|tcm|box office|24\/7|vod|epix|encore|crave|sky cinema|pelicul|kino|elokuv|series)/iu',
    'SPORTS'        => '/\b(sport|sports|espn|bein sport|dazn|nfl|nba|nhl|mlb|mls|ufc|wwe|golf|tennis|cricket|rugby|f1|formula|motor|racing|nascar|boxing|fight|wrestling|soccer|football|hockey|basketball|baseball|ppv|premier league|la liga|bundesliga|serie a|ligue 1|champions|olympic|tsn|sportsnet|rds|eurosport|arena|ncaa|sec network|accn|big ten|nesn|masn|yes network|bally|marquee|fanduel|tudn|supersport|calcio)/iu',
    'MUSIC'         => '/\b(music|musique|musik|muzik|muzikë|mtv|vh1|cmt|hits|radio|mezzo|4music|trace|stingray)/iu',
    'DOCUMENTARY'   => '/\b(document|dokument|discovery|history|histoire|nat ?geo|national geographic|animal planet|science|planet|curiosity|smithsonian|crime|investigation|wild)/iu',
    'FAITH'         => '/\b(religio|christian|islam|gospel|church|catholic|faith|fetare|ewtn|god tv|daystar|tbn|quran|coran|مسيحية|إسلامية)/iu',
    'ENTERTAINMENT' => '/\b(entertain|serial|drama|comedy|lifestyle|reality|food|hgtv|bravo|tlc|tbs|tnt|usa network|fx\b|amc|syfy|paramount network|hallmark|lifetime|cooking|travel|home|diy|humor|general|gjenerale|divertissement|unterhaltung|variet|ترفيه)/iu',
];

function genre_of(string $cat, string $name): string {
    foreach (GENRES as $g => $re) if (preg_match($re, $cat)) return $g;
    foreach (GENRES as $g => $re) if (preg_match($re, $name)) return $g;
    return 'GENERAL';
}

/** Undo the one double-encoding the list carries ("ESPAÃ±OL" -> "ESPAÑOL"). */
function fix_mojibake(string $s): string {
    if (!str_contains($s, 'Ã')) return $s;
    $t = mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
    return ($t !== false && mb_check_encoding($t, 'UTF-8')) ? $t : $s;
}

function superscripts_to_text(string $s): string {
    return preg_replace_callback('/[\x{02B0}-\x{02FF}\x{1D2C}-\x{1D6B}\x{2070}-\x{209F}]+/u', function ($m) {
        return ' ' . strtr($m[0], SUP) . ' ';
    }, $s);
}

/** "bein sports 2" - lowercase, accents folded, one space between words. */
function search_key(string $s): string {
    $s = mb_strtolower($s, 'UTF-8');
    if (!preg_match('/[\x{0600}-\x{06ff}]/u', $s)) {          // Arabic names keep their letters
        $t = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
        if (is_string($t) && $t !== '' && preg_match('/[a-z0-9]/', $t)) $s = $t;
    }
    $s = preg_replace('/[^a-z0-9\x{0600}-\x{06ff}]+/u', ' ', $s);
    return trim($s);
}

/* ---------------------------------------------------------- first pass: which prefixes are source tags */

$blocks = explode("\u{00B6}", $pack);
$prefixCount = [];
foreach ($blocks as $b) {
    $rows = explode('~', $b);
    for ($i = 1, $n = count($rows); $i < $n; $i++) {
        if (preg_match('/^([A-Z0-9][A-Z0-9 +&.\-]{0,9}):\s/u', $rows[$i], $p)) {
            $prefixCount[$p[1]] = ($prefixCount[$p[1]] ?? 0) + 1;
        }
    }
}
// A prefix is a source tag (country code, provider, tier) when the list uses it
// on 25+ rows; "NCIS: LOS ANGELES" style titles never get near that.
$tags = [];
foreach ($prefixCount as $p => $c) if ($c >= 25) $tags[$p] = true;

/* ---------------------------------------------------------- second pass: rows -> channels */

$isEvent = '/\|\s*\d{1,2}\s*-|\s@\s|\d{1,2}(:\d{2})?\s*(am|pm)\b|\bvs\.?\s.*\d{1,2}:\d{2}/iu';
$tail    = '/(?:\s+(?:\(?(?:HD|FHD|UHD|SD|LQ|HQ|4K|8K|RAW|HEVC|H\.?265|3840P|2160P|1080P|720P|50FPS|60FPS|BACKUP|BACK-?UP|VIP|OPT\s?\d|ALT\s?\d?|\(H\)|\(V\)|\([^)]{1,10}\))\)?))+$/iu';

$groups = [];          // key => [name, variants[], countries[], genres[]]
$stats  = ['channels' => 0, 'countries' => 0, 'genres' => []];
$countryKeys = [];

foreach ($blocks as $b) {
    $rows = explode('~', $b);
    $head = explode('^', $rows[0]);
    $key  = $head[0];
    $blockName = $head[1] ?? $key;
    if (($meta['adult'][$key] ?? 0) === 1) continue;                       // never on the home page
    $label = COUNTRY_LABEL[$blockName] ?? mb_strtoupper($blockName, 'UTF-8');
    if (isset($meta['flag'][$key])) $countryKeys[$key] = true;

    for ($i = 1, $n = count($rows); $i < $n; $i++) {
        $us = strrpos($rows[$i], '^');
        if ($us === false) continue;
        $raw = substr($rows[$i], 0, $us);
        $cat = $cats[intval(substr($rows[$i], $us + 1), 36)] ?? '';
        if (preg_match($isEvent, $raw)) continue;                          // dated event feeds, not channels

        $name = fix_mojibake($raw);
        $source = '';
        if (preg_match('/^([A-Z0-9][A-Z0-9 +&.\-]{0,9}):\s+/u', $name, $p) && isset($tags[$p[1]])) {
            $name = substr($name, strlen($p[0]));
            // a plain country/language code is where it is, not who carries it
            if (!preg_match('/^[A-Z]{2}( [A-Z]{2})?$/', $p[1]) || in_array($p[1], ['GO', 'RK', 'OD', 'VO', 'NM', 'AF', 'YP', 'MG', 'TK'], true)) {
                $source = $p[1];
            }
        }
        $name = superscripts_to_text($name);
        $name = preg_replace('/\s+/u', ' ', trim($name, " \t-|*:."));
        if ($name === '' || !preg_match('/[\p{L}\p{N}]/u', $name)) continue;

        $base = preg_replace($tail, '', $name);
        $base = trim(preg_replace('/\s+/u', ' ', $base), " -|*:.");
        if ($base === '') $base = $name;
        $k = search_key($base);
        if ($k === '' || strlen($k) < 2) continue;

        $genre = genre_of($cat, $base);
        if (!isset($groups[$k])) $groups[$k] = ['name' => mb_strtoupper($base, 'UTF-8'), 'v' => [], 'c' => [], 'g' => [], 'ck' => []];
        $g = &$groups[$k];
        $vid = $key . '|' . $source . '|' . mb_strtoupper($name, 'UTF-8');
        if (!isset($g['v'][$vid])) {
            $g['v'][$vid] = ['c' => $label, 's' => $source, 'n' => mb_strtoupper($name, 'UTF-8'), 'g' => $genre];
            $g['c'][$label] = ($g['c'][$label] ?? 0) + 1;
            $g['ck'][$label] = $key;
            $g['g'][$genre] = ($g['g'][$genre] ?? 0) + 1;
        }
        unset($g);
    }
}

/* ---------------------------------------------------------- write */

$lines = [];
$totalVariants = 0;
$genreTotals = [];
foreach ($groups as $k => $g) {
    // display country: the audience's countries first, then wherever it has most variants
    uksort($g['c'], function ($a, $b) use ($g) {
        $pa = COUNTRY_PRIORITY[$g['ck'][$a]] ?? 9; $pb = COUNTRY_PRIORITY[$g['ck'][$b]] ?? 9;
        return $pa <=> $pb ?: $g['c'][$b] <=> $g['c'][$a] ?: strcmp($a, $b);
    });
    $country = array_key_first($g['c']);
    arsort($g['g']);
    $genre = array_key_first($g['g']);
    $variants = array_values($g['v']);
    // the chosen country's variants first, so the expanded row reads top-down
    usort($variants, function ($a, $b) use ($country) {
        return ($a['c'] === $country ? 0 : 1) <=> ($b['c'] === $country ? 0 : 1) ?: strcmp($a['c'], $b['c']) ?: strcmp($a['n'], $b['n']);
    });
    $count = count($variants);
    $totalVariants += $count;
    $genreTotals[$genre] = ($genreTotals[$genre] ?? 0) + $count;
    $lines[] = implode("\t", [
        $k, $g['name'], $country, $genre, $count,
        json_encode(array_slice($variants, 0, 24), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ]);
}
// shortest names first: for a given query the plain channel ("TSN") ranks above its
// spin-offs ("TSN 4") before any other ordering is applied
usort($lines, fn($a, $b) => strlen(strtok($a, "\t")) <=> strlen(strtok($b, "\t")) ?: strcmp($a, $b));

arsort($genreTotals);
$statsOut = [
    'generated' => date('c'),
    'channels'  => $totalVariants,
    'names'     => count($lines),
    'countries' => count($countryKeys),
    'genres'    => $genreTotals,
];

printf("%d rows -> %d channels under %d names, %d countries\n", 46758, $totalVariants, count($lines), count($countryKeys));
foreach ($genreTotals as $g => $n) printf("  %-14s %6d\n", $g, $n);
printf("tags: %s\n", implode(' ', array_keys($tags)));

if ($dry) exit(0);
file_put_contents("$root/app/channel-index.txt", implode("\n", $lines) . "\n");
file_put_contents("$root/app/channel-stats.json", json_encode($statsOut, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
printf("wrote app/channel-index.txt (%s bytes) and app/channel-stats.json\n", number_format(filesize("$root/app/channel-index.txt")));
