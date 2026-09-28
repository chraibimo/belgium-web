<?php
/** Legal pages: privacy / refund / terms. Content: content/<lang>/legal-<key>.php with sections [id, title, html]. */
if (!defined('PRIMO_APP')) exit;
$key = $GLOBALS['page'];
$c = content('legal-' . $key);
page_open(['key' => $key, 'title' => $c['title'], 'description' => $c['description']]);
?>
<section class="phero band--red" style="padding-bottom:28px">
  <div class="container phero__inner">
    <span class="badge phero__badge"><?= e($c['badge']) ?></span>
    <h1 class="phero__title display hard-shadow" style="font-size:clamp(44px,8vw,120px)"><?= e($c['h1']) ?></h1>
    <p class="phero__lede"><?= e($c['lede']) ?></p>
    <p class="phero__meta"><span class="pill"><?= e($c['meta']) ?></span></p>
  </div>
</section>
<section class="section">
  <div class="container legal">
    <nav class="legal__toc" aria-label="<?= e($c['toc']) ?>">
      <p class="eyebrow muted" style="padding:6px 8px"><?= e($c['toc']) ?></p>
      <?php foreach ($c['sections'] as $i => $s): ?><a href="#<?= e($s[0]) ?>"><?= ($i + 1) ?>. <?= e($s[1]) ?></a><?php endforeach; ?>
    </nav>
    <article class="prose">
      <?php if (!empty($c['intro'])): ?><p class="note"><?= $c['intro'] ?></p><?php endif; ?>
      <?php foreach ($c['sections'] as $i => $s): ?>
      <section id="<?= e($s[0]) ?>"><h2><?= ($i + 1) ?>. <?= e($s[1]) ?></h2><?= $s[2] ?></section>
      <?php endforeach; ?>
    </article>
  </div>
</section>
<?php page_close(); ?>
