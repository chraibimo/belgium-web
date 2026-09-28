<?php
/**
 * Site search over the blog posts and the main pages of the current language.
 *
 * The index is built from the content files on demand and cached in
 * app/data/search-<lang>.json, keyed on the newest content mtime, so editing a
 * post is picked up on the next search without a build step.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

function search_index(): array {
    $l     = lang();
    $files = array_merge(
        glob(INCLUDES_DIR . '/content/' . $l . '/posts/*.php') ?: [],
        glob(INCLUDES_DIR . '/content/' . $l . '/*.php') ?: []
    );
    $stamp = 0;
    foreach ($files as $f) $stamp = max($stamp, (int)@filemtime($f));
    $cacheFile = APP_DIR . '/data/search-' . $l . '.json';
    $cached = @json_decode((string)@file_get_contents($cacheFile), true);
    if (is_array($cached) && ($cached['stamp'] ?? 0) === $stamp) return $cached['docs'];

    $docs = [];
    foreach (posts($l) as $p) {
        $docs[] = [
            'title'   => $p['title'],
            'url'     => post_url($p, $l),
            'excerpt' => $p['excerpt'],
            'text'    => mb_strtolower($p['title'] . ' ' . $p['excerpt'] . ' ' . strip_tags($p['body'])),
        ];
    }
    // The main pages: title + lede from each content file's 'search' block.
    foreach (['pricing', 'channels', 'tutorial', 'contact', 'about', 'faqs', 'reviews', 'referral'] as $key) {
        $c = content($key, $l);
        if (empty($c['search'])) continue;
        $docs[] = [
            'title'   => $c['search']['title'],
            'url'     => url($key, $l),
            'excerpt' => $c['search']['excerpt'],
            'text'    => mb_strtolower($c['search']['title'] . ' ' . $c['search']['excerpt'] . ' ' . ($c['search']['text'] ?? '')),
        ];
    }
    @mkdir(APP_DIR . '/data', 0700, true);
    @file_put_contents($cacheFile, json_encode(['stamp' => $stamp, 'docs' => $docs], JSON_UNESCAPED_UNICODE));
    return $docs;
}

/** Ranked hits: +10 per term in the title, +1 per term in the text. */
function search_articles(string $q, int $limit = 10): array {
    $terms = array_values(array_filter(preg_split('/\s+/', mb_strtolower(trim($q))) ?: [], fn($t) => mb_strlen($t) >= 2));
    if (!$terms) return [];
    $hits = [];
    foreach (search_index() as $d) {
        $score = 0;
        $title = mb_strtolower($d['title']);
        foreach ($terms as $t) {
            if (str_contains($title, $t)) $score += 10;
            if (str_contains($d['text'], $t)) $score += 1;
        }
        if ($score > 0) $hits[] = ['score' => $score, 'title' => $d['title'], 'url' => $d['url'], 'excerpt' => mb_substr($d['excerpt'], 0, 180)];
    }
    usort($hits, fn($a, $b) => $b['score'] <=> $a['score']);
    return array_map(fn($h) => ['title' => $h['title'], 'url' => $h['url'], 'excerpt' => $h['excerpt']], array_slice($hits, 0, $limit));
}
