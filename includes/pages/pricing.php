<?php
/** Pricing page: full tickets (term + devices), comparison, how it works, refund, FAQ. */
if (!defined('PRIMO_APP')) exit;
$c = content('pricing');
$d = site_data();
page_open(['key' => 'pricing', 'title' => $c['title'], 'description' => $c['description'], 'jsonld' => [faq_jsonld($c['faq']['items'])]]);
?>
<div class="band--red" style="height:var(--header-h)"></div>
<div class="band--red"><?php $mode = 'full'; include INCLUDES_DIR . '/partials/pricing-block.php'; ?></div>

<section class="section reveal">
  <div class="container">
    <div class="section-head"><div><h2 class="section-title"><?= $c['compare']['title'] ?></h2><p class="section-lede"><?= e($c['compare']['lede']) ?></p></div></div>
    <div class="compare-wrap">
      <table class="compare">
        <thead><tr><?php foreach ($c['compare']['head'] as $th): ?><th><?= e($th) ?></th><?php endforeach; ?></tr></thead>
        <tbody><?php foreach ($c['compare']['rows'] as $r): ?><tr><td><?= e($r[0]) ?></td><td><?= $r[1] === '—' ? '<span class="no">—</span>' : e($r[1]) ?></td><td><b><?= e($r[2]) ?></b></td></tr><?php endforeach; ?></tbody>
      </table>
    </div>
  </div>
</section>

<section class="section band--cream reveal">
  <div class="container">
    <div class="section-head"><div><h2 class="section-title"><?= $c['how']['title'] ?></h2><p class="section-lede"><?= e($c['how']['lede']) ?></p></div></div>
    <div class="steps">
      <?php foreach ($c['how']['steps'] as $st): ?><div class="step"><h3 class="step__title"><?= e($st[0]) ?></h3><p><?= e($st[1]) ?></p></div><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section reveal">
  <div class="container">
    <div class="section-head"><div><h2 class="section-title"><?= $c['devices']['title'] ?></h2><p class="section-lede"><?= e($c['devices']['lede']) ?></p></div></div>
    <div class="grid-3">
      <?php foreach ($c['devices']['items'] as $i => $it): ?><div class="feature"><span class="feature__icon"><svg width="26" height="26"><use href="/assets/img/icons.svg#<?= ['tv', 'box', 'headset'][$i] ?>"/></svg></span><h3 class="feature__title"><?= e($it[0]) ?></h3><p><?= e($it[1]) ?></p></div><?php endforeach; ?>
    </div>
    <div class="ticket reveal" style="margin:40px auto 0;max-width:820px">
      <h2 class="ticket__title"><?= $c['refund']['title'] ?></h2>
      <p><?= e($c['refund']['text']) ?></p>
      <p><a class="btn btn--red" href="<?= e(url('refund')) ?>"><?= e(t('nav.refund')) ?></a></p>
    </div>
  </div>
</section>

<section class="section band--night2 reveal">
  <div class="container" style="max-width:960px">
    <div class="section-head"><h2 class="section-title"><?= $c['faq']['title'] ?></h2></div>
    <?php render_faq($c['faq']['items']); ?>
  </div>
</section>

<section class="section band--red reveal">
  <div class="container grid-2" style="align-items:center">
    <div><h2 class="section-title"><?= $c['deciding']['title'] ?></h2><p class="section-lede"><?= e($c['deciding']['lede']) ?></p></div>
    <div style="display:flex;flex-wrap:wrap;gap:12px">
      <a class="btn" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener"><?= e($c['deciding']['wa']) ?></a>
      <a class="btn btn--maroon" href="<?= e(url('channels')) ?>"><?= e($c['deciding']['list']) ?></a>
      <a class="btn btn--ghost" href="mailto:<?= e($d['brand']['email']) ?>"><?= e($d['brand']['email']) ?></a>
    </div>
  </div>
</section>
<?php page_close(['scripts' => ['/assets/js/pricing.js']]); ?>
