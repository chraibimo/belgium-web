<?php
/**
 * sitemap.xml: every page, post and tag archive in every language, each with
 * its hreflang alternates so search engines pair the Dutch and French copies.
 */
if (!defined('PRIMO_APP')) exit;

$origin = current_origin();
$urls   = [];   // loc => [lang => loc]

foreach (routes() as $key => $r) {
    if (!empty($r['noindex'])) continue;
    $alts = [];
    foreach (SITE_LANGS as $l) $alts[$l] = $origin . url($key, $l);
    foreach ($alts as $loc) $urls[$loc] = $alts;
}
foreach (SITE_LANGS as $l) {
    foreach (posts($l) as $p) {
        $alts = [];
        foreach (SITE_LANGS as $l2) {
            $twin = post_by_key($p['key'], $l2);
            if ($twin) $alts[$l2] = $origin . post_url($twin, $l2);
        }
        $urls[$origin . post_url($p, $l)] = $alts;
    }
    foreach (array_keys(tags($l)) as $tag) {
        $urls[$origin . tag_url($tag, $l)] = [$l => $origin . tag_url($tag, $l)];
    }
}

header('Content-Type: application/xml; charset=UTF-8');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xhtml=\"http://www.w3.org/1999/xhtml\">\n";
foreach ($urls as $loc => $alts) {
    echo "  <url><loc>" . htmlspecialchars($loc, ENT_XML1) . "</loc>";
    if (count($alts) > 1) {
        foreach ($alts as $l => $a) {
            echo "<xhtml:link rel=\"alternate\" hreflang=\"" . locale($l) . "\" href=\"" . htmlspecialchars($a, ENT_XML1) . "\"/>";
        }
        echo "<xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($alts[DEFAULT_LANG] ?? reset($alts), ENT_XML1) . "\"/>";
    }
    echo "</url>\n";
}
echo "</urlset>\n";
