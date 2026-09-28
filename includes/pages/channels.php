<?php
/** Channels list: the hero holds the channel search (nothing is listed until the visitor types; results open right
 *  under it), the upcoming events while the box is empty, and Mickey the TV, who tunes to whatever is picked.
 *  Then the Belgian highlights and what's included.
 *  ?q= is read in the browser (channels.js), never here: the page is cached without its query string.
 *  Events come from includes/events.php; the browser hides the ones that are over (the page can be an hour old). */
if (!defined('PRIMO_APP')) exit;
$c = content('channels');
$d = site_data();
$ec = $c['events'];

$events = [];
$tz  = new DateTimeZone('Europe/Brussels');
$now = time();
$pick = fn($v) => is_array($v) ? (string)($v[lang()] ?? reset($v)) : (string)$v;
foreach ((array)(include INCLUDES_DIR . '/events.php') as $ev) {
    try { $at = new DateTimeImmutable((string)$ev['start'], $tz); } catch (Exception $x) { continue; }
    $start = $at->getTimestamp();
    $end   = $start + 60 * max(1, (int)($ev['minutes'] ?? 120));
    if ($end <= $now || $start > $now + 60 * 86400) continue;
    $sport = in_array($ev['sport'] ?? '', ['football', 'cycling', 'racing', 'tennis'], true) ? $ev['sport'] : 'other';
    $events[] = [
        'start'  => $start * 1000,
        'end'    => $end * 1000,
        'sport'  => $sport,
        'scene'  => ['football' => 'sport', 'cycling' => 'cycling', 'racing' => 'racing', 'tennis' => 'sport', 'other' => 'general'][$sport],
        'title'  => $pick($ev['title']),
        'short'  => $pick($ev['short'] ?? $ev['title']),
        'comp'   => $pick($ev['competition'] ?? ''),
        'when'   => $ec['days'][(int)$at->format('w')] . ' ' . $at->format('j') . ' ' . $ec['months'][(int)$at->format('n') - 1] . ' · ' . $at->format('H:i'),
        'osd'    => $at->format('d/m H:i'),
        'search' => $pick($ev['search'] ?? ''),
    ];
}
usort($events, fn($a, $b) => $a['start'] <=> $b['start']);
$events = array_slice($events, 0, 8);

// original line icons, one per sport
$icon = fn(string $s) => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . match ($s) {
    'football' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5l3.8 2.8-1.4 4.4H9.6L8.2 10.3z"/><path d="M12 7.5V3.5M15.8 10.3l3.9-1.3M14.4 14.7l2.4 3.4M9.6 14.7l-2.4 3.4M8.2 10.3L4.3 9"/>',
    'cycling'  => '<circle cx="6" cy="16" r="4"/><circle cx="18" cy="16" r="4"/><path d="M6 16l4-7h5l3 7M10 9l3 7H6M15 9l-1-3h3"/>',
    'racing'   => '<path d="M5 21V4h13l-2.5 4 2.5 4H5"/><path d="M9 4v4h4V4M9 8v4M13 8v4M5 8h4M13 8h3.5" stroke-width="1.6"/>',
    'tennis'   => '<circle cx="12" cy="12" r="9"/><path d="M5.5 6c3.5 3 3.5 9 0 12M18.5 6c-3.5 3-3.5 9 0 12"/>',
    default    => '<path d="M8 4h8v5a4 4 0 0 1-8 0zM12 13v4M8 20h8M8 6H5a3 3 0 0 0 3 4M16 6h3a3 3 0 0 1-3 4"/>',
} . '</svg>';

page_open(['key' => 'channels', 'title' => $c['title'], 'description' => $c['description']]);
?>
<section class="phero phero--tv band--red">
  <div class="container phero__inner">
    <div class="chero">
      <div class="chero__main">
        <span class="badge phero__badge"><?= e($c['hero']['badge']) ?></span>
        <h1 class="phero__title display hard-shadow"><?= $c['hero']['title'] ?></h1>
        <p class="phero__lede"><?= e($c['hero']['lede']) ?></p>
        <div class="guide" id="search" data-guide>
          <form class="cc__search" action="#search" role="search" data-guide-form>
            <label class="sr-only" for="guide-q"><?= e($c['hero']['search_placeholder']) ?></label>
            <input id="guide-q" type="search" name="q" placeholder="<?= e($c['hero']['search_placeholder']) ?>" autocomplete="off" spellcheck="false" data-guide-q>
            <button type="submit" aria-label="<?= e(t('nav.search')) ?>"><svg width="20" height="20"><use href="/assets/img/icons.svg#search"/></svg></button>
          </form>
          <p class="guide__status" data-guide-status><?= e($c['guide']['hint']) ?></p>
          <div class="cc__results" data-guide-results aria-live="polite"></div>
          <button type="button" class="btn btn--sm guide__more" data-guide-more hidden></button>
          <p class="guide__note" data-guide-note hidden><?= e($c['guide']['note']) ?></p>
        </div>
        <?php if ($events): ?>
        <div class="evs" data-events>
          <script type="application/json" data-events-json><?= json_attr($events) ?></script>
          <div class="evs__head"><h2 class="evs__title"><i class="evs__dot" aria-hidden="true"></i><?= e($ec['title']) ?></h2><p class="evs__lede"><?= e($ec['lede']) ?></p></div>
          <div class="evs__row" data-events-row>
            <?php foreach ($events as $i => $ev): ?>
            <div class="ev" data-ev="<?= $i ?>" data-sport="<?= e($ev['sport']) ?>">
              <button type="button" class="ev__tune" data-ev-tune aria-label="<?= e($ev['title'] . ' · ' . $ev['when']) ?>"></button>
              <span class="ev__top"><span class="ev__icon" aria-hidden="true"><?= $icon($ev['sport']) ?></span><span class="ev__when"><?= e($ev['when']) ?></span></span>
              <span class="ev__title"><?= e($ev['title']) ?></span>
              <span class="ev__comp"><?= e($ev['comp']) ?></span>
              <span class="ev__count" data-ev-count></span>
              <?php if ($ev['search'] !== ''): ?><button type="button" class="ev__find" data-guide-pick="<?= e($ev['search']) ?>"><?= e($ec['find']) ?></button><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <div class="phero__meta"><?php foreach ($c['hero']['chips'] as $ch): ?><span class="pill"><?= e($ch) ?></span><?php endforeach; ?></div>
        <div class="phero__actions"><a class="btn" href="<?= e(url('pricing')) ?>"><?= e($c['hero']['cta1']) ?></a></div>
      </div>
      <aside class="chero__tv" data-tv-idle="<?= e(json_attr($c['tv'])) ?>">
        <?php $tvChannels = []; $tvMode = 'guide'; include INCLUDES_DIR . '/partials/tv.php'; ?>
        <div class="hero__now chero__now" aria-live="polite">
          <span class="eyebrow" data-tv-kicker><?= e($c['tv']['kicker']) ?></span>
          <p class="hero__now-title" data-tv-name><?= e($c['tv']['name']) ?></p>
          <span class="hero__now-meta" data-tv-meta><?= e($c['tv']['meta']) ?></span>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="section reveal">
  <div class="container">
    <div class="section-head"><div><p class="eyebrow"><?= e($c['belgium']['eyebrow']) ?></p><h2 class="section-title"><?= $c['belgium']['title'] ?></h2><p class="section-lede"><?= e($c['belgium']['lede']) ?></p></div></div>
    <div class="grid-4">
      <?php foreach ($c['belgium']['groups'] as $g): ?>
      <div class="feature"><h3 class="feature__title"><?= e($g[0]) ?></h3><div class="chips chips--wrap"><?php foreach ($g[1] as $n): ?><button type="button" class="chip" data-guide-pick="<?= e($n) ?>"><?= e($n) ?></button><?php endforeach; ?></div></div>
      <?php endforeach; ?>
    </div>
    <p class="muted" style="margin-top:16px;font-size:14px"><?= e($c['belgium']['note']) ?></p>
  </div>
</section>

<section class="section band--cream reveal">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= $c['included']['title'] ?></h2></div>
    <div class="grid-3">
      <?php foreach ($c['included']['items'] as $it): ?><div class="feature"><h3 class="feature__title" style="font-size:38px"><?= e($it[0]) ?></h3><p class="eyebrow" style="color:rgba(0,0,0,.6)"><?= e($it[1]) ?></p><p><?= e($it[2]) ?></p></div><?php endforeach; ?>
    </div>
  </div>
</section>

<?php render_cta_band(); ?>
<?php page_close(['scripts' => ['/assets/js/tv.js', '/assets/js/channels.js']]); ?>
