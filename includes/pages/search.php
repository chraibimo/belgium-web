<?php
/** Site search results. */
if (!defined('PRIMO_APP')) exit;
require_once APP_DIR . '/handlers/search.php';
$q = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 100);
$hits = $q !== '' ? search_articles($q, 30) : [];
page_open(['key' => 'search', 'title' => $q !== '' ? t('search.results_for', $q) : t('search.title'), 'noindex' => true]);
?>
<section class="phero band--red" style="padding-bottom:28px">
  <div class="container phero__inner">
    <span class="badge phero__badge"><?= e(t('search.title')) ?></span>
    <form action="<?= e(url('search')) ?>" method="get" role="search" class="search-box">
      <svg viewBox="0 0 24 24" width="48" height="48"><use href="/assets/img/icons.svg#search"/></svg>
      <label class="sr-only" for="s-q"><?= e(t('search.hint')) ?></label>
      <input id="s-q" type="search" name="q" value="<?= e($q) ?>" placeholder="<?= e(t('search.placeholder')) ?>" autocomplete="off" autofocus>
      <button type="submit" class="btn btn--sm"><?= e(t('search.button')) ?></button>
    </form>
    <p class="phero__lede"><?= e(t('search.hint')) ?></p>
  </div>
</section>
<section class="section">
  <div class="container" style="max-width:900px">
    <?php if ($q === ''): ?>
    <div class="title-grid title-grid--wide"><?php foreach (array_slice(posts(), 0, 6) as $p) render_post_card($p, 'wide', false, 2); ?></div>
    <?php elseif ($hits): ?>
    <p class="muted" style="margin-bottom:8px"><?= e(t('search.count', count($hits))) ?></p>
    <?php foreach ($hits as $h): ?><div class="search-hit"><h2><a href="<?= e($h['url']) ?>"><?= e($h['title']) ?></a></h2><p><?= e($h['excerpt']) ?></p></div><?php endforeach; ?>
    <?php else: ?>
    <div class="ticket"><h2 class="ticket__title" style="font-size:40px">404</h2><p><?= e(t('search.empty', $q)) ?></p><p><a class="btn btn--red" href="<?= e(url('blog')) ?>"><?= e(t('nav.blog')) ?></a></p></div>
    <?php endif; ?>
  </div>
</section>
<?php page_close(); ?>
