<?php
/** 404 - "no signal". */
if (!defined('PRIMO_APP')) exit;
page_open(['key' => '404', 'title' => t('notfound.badge'), 'noindex' => true]);
?>
<section class="nf band--red">
  <div class="container nf__inner">
    <div class="nf__screen" aria-hidden="true"><canvas width="160" height="120" data-noise></canvas><span class="nf__signal">NO SIGNAL</span></div>
    <div>
      <span class="badge"><?= e(t('notfound.badge')) ?></span>
      <h1 class="nf__title display hard-shadow"><?= t('notfound.title') ?></h1>
      <p class="nf__lede"><?= e(t('notfound.lede')) ?></p>
      <div class="nf__actions">
        <a href="<?= e(url('home')) ?>" class="btn"><?= e(t('cta.back_home')) ?></a>
        <a href="<?= e(url('search')) ?>" class="btn btn--maroon"><?= e(t('nav.search')) ?></a>
      </div>
    </div>
  </div>
</section>
<?php page_close(); ?>
