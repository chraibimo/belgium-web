<?php
/** Community reviews. Sections that need real reviews hide themselves while the data is empty. */
if (!defined('PRIMO_APP')) exit;
$c = content('reviews');
$d = site_data();
$featured = $c['featured'];
$stars = fn(int $n) => '<span class="stars" role="img" aria-label="' . $n . '/5">' . str_repeat('<svg><use href="/assets/img/icons.svg#star"/></svg>', $n) . '</span>';
page_open(['key' => 'reviews', 'title' => $c['title'], 'description' => $c['description']]);
render_page_hero(['badge' => $c['hero']['badge'], 'title' => $c['hero']['title'], 'lede' => e($c['hero']['lede']), 'doodle' => 'star']);
?>
<section class="section">
  <div class="container">
    <?php if ($featured): ?>
    <div class="grid-3">
      <?php foreach ($featured as $r): ?>
      <div class="review"><?php if (!empty($r['sample'])): ?><span class="badge badge--ink review__sample"><?= e($c['sample']) ?></span><?php endif; ?><?= $stars((int)$r['rating']) ?><p class="review__quote">“<?= e($r['quote']) ?>”</p><div class="review__who"><span class="review__avatar"><?= e(mb_strtoupper(mb_substr($r['name'], 0, 1))) ?></span><div><b><?= e($r['name']) ?></b><small><?= e($r['date']) ?></small></div></div></div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="ticket" style="max-width:760px"><h2 class="ticket__title" style="font-size:40px"><?= e($c['empty']['title']) ?></h2><p><?= e($c['empty']['text']) ?></p><p><a class="btn btn--red" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a></p></div>
    <?php endif; ?>
  </div>
</section>

<section class="section band--night2 reveal">
  <div class="container" data-tabs>
    <div class="tabs" role="tablist"><?php foreach ($c['tabs'] as $i => $tb): ?><button type="button" class="chip" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"><?= e($tb['label']) ?></button><?php endforeach; ?></div>
    <?php foreach ($c['tabs'] as $i => $tb): ?>
    <div class="tabpanel" role="tabpanel"<?= $i === 0 ? '' : ' hidden' ?>>
      <div class="grid-2" style="align-items:center">
        <div><h2 class="section-title" style="font-size:clamp(30px,3.6vw,52px)"><?= e($tb['label']) ?></h2><p class="section-lede"><?= e($tb['text']) ?></p><div class="chips chips--wrap" style="margin-top:16px"><?php foreach ($tb['chips'] as $ch): ?><span class="chip"><?= e($ch) ?></span><?php endforeach; ?></div></div>
        <div class="who__screen"><?= poster($tb['label'], 'tab-' . $tb['id'], 'wide') ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section reveal">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= $c['story']['title'] ?></h2></div>
    <div class="story"><?php foreach ($c['story']['chapters'] as $i => $ch): ?><div class="story__chapter"><span class="story__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span><div><h3><?= e($ch[0]) ?></h3><p><?= e($ch[1]) ?></p></div></div><?php endforeach; ?></div>
  </div>
</section>

<section class="section band--cream reveal">
  <div class="container">
    <div class="tp-band" style="background:var(--night)">
      <div><h2 class="section-title" style="font-size:clamp(26px,3vw,40px)"><?= e($c['tp']['title']) ?></h2><p class="section-lede"><?= e($c['tp']['text']) ?></p></div>
      <?php if (TRUSTPILOT_URL !== ''): ?><a class="btn" href="<?= e(TRUSTPILOT_URL) ?>" target="_blank" rel="noopener nofollow"><?= e($c['tp']['button']) ?> ↗</a><?php endif; ?>
    </div>
  </div>
</section>
<?php render_cta_band(); page_close(); ?>
