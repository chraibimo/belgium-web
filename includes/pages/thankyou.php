<?php
/** Thank-you page: after a contact form send, and the target for the checkout's order confirmation. */
if (!defined('PRIMO_APP')) exit;
$c = content('thankyou');
$d = site_data();
page_open(['key' => 'thankyou', 'title' => $c['title'], 'description' => $c['description'], 'noindex' => true]);
?>
<section class="phero band--red">
  <svg class="phero__doodle" aria-hidden="true"><use href="/assets/img/doodles.svg#ticket"/></svg>
  <div class="container phero__inner">
    <span class="badge phero__badge"><?= e($c['badge']) ?></span>
    <h1 class="phero__title display hard-shadow"><?= $c['title_html'] ?></h1>
    <p class="phero__lede"><?= e($c['lede']) ?></p>
    <div class="phero__meta"><?php foreach ($c['chips'] as $ch): ?><span class="pill"><svg width="16" height="16"><use href="/assets/img/icons.svg#check"/></svg><?= e($ch) ?></span><?php endforeach; ?></div>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= e($c['next_title']) ?></h2></div>
    <div class="steps"><?php foreach ($c['next'] as $st): ?><div class="step"><h3 class="step__title" style="font-size:26px"><?= e($st[0]) ?></h3><p><?= e($st[1]) ?></p></div><?php endforeach; ?></div>
    <p style="margin-top:40px;display:flex;flex-wrap:wrap;gap:12px"><a class="btn" href="<?= e(url('home')) ?>"><?= e($c['back']) ?></a><a class="btn btn--maroon" href="<?= e(url('tutorial')) ?>"><?= e($c['tutorial']) ?></a><a class="btn btn--green" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a></p>
  </div>
</section>
<?php page_close(); ?>
