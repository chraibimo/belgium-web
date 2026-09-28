<?php
/** About us. */
if (!defined('PRIMO_APP')) exit;
$c = content('about');
$d = site_data();
page_open(['key' => 'about', 'title' => $c['title'], 'description' => $c['description']]);
render_page_hero(['badge' => $c['hero']['badge'], 'title' => $c['hero']['title'], 'lede' => e($c['hero']['lede']), 'doodle' => 'reel']);
?>
<section class="section reveal">
  <div class="container grid-2" style="align-items:center">
    <div><h2 class="section-title"><?= $c['intro']['title'] ?></h2><div class="prose" style="margin-top:16px"><?php foreach ($c['intro']['text'] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?></div></div>
    <div class="who__screen"><?= poster('Mickey TV', 'about', 'wide', ['palette' => 'red', 'motif' => 'bars']) ?></div>
  </div>
</section>
<section class="section band--cream reveal">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= $c['offer']['title'] ?></h2></div>
    <div class="grid-3"><?php foreach ($c['offer']['items'] as $i => $it): ?><div class="feature"><span class="feature__icon"><svg width="26" height="26"><use href="/assets/img/icons.svg#<?= ['tv', 'film', 'bolt', 'check', 'phone', 'headset'][$i] ?>"/></svg></span><h3 class="feature__title"><?= e($it[0]) ?></h3><p><?= e($it[1]) ?></p></div><?php endforeach; ?></div>
  </div>
</section>
<section class="section reveal">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= $c['why']['title'] ?></h2></div>
    <ul class="stats"><?php foreach ($c['why']['items'] as $i => $it): ?><li class="stat<?= $i % 2 ? ' stat--cream' : '' ?>"><div class="stat__label" style="font-size:20px"><?= e($it[0]) ?></div><div class="stat__sub" style="margin-top:8px"><?= e($it[1]) ?></div></li><?php endforeach; ?></ul>
    <div class="ticket" style="margin:40px auto 0;max-width:760px"><h2 class="ticket__title" style="font-size:44px"><?= e($c['join']['title']) ?></h2><p><?= e($c['join']['text']) ?></p><p style="display:flex;flex-wrap:wrap;gap:10px"><a class="btn btn--red" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a><a class="btn btn--ghost btn--ghost-dark" href="mailto:<?= e($d['brand']['email']) ?>"><?= e($d['brand']['email']) ?></a></p></div>
  </div>
</section>
<?php render_cta_band(); page_close(); ?>
