<?php
/** Blog index: all posts (lead card + grid), topic filters, tag cloud, search, lead form. */
if (!defined('PRIMO_APP')) exit;
$all = posts();
$topics = [];
foreach ($all as $p) $topics[$p['topic']] = ($topics[$p['topic']] ?? 0) + 1;
page_open(['key' => 'blog', 'title' => t('blog.title') . ' — ' . t('blog.eyebrow'), 'description' => t('blog.lede')]);
render_page_hero(['badge' => t('blog.eyebrow'), 'title' => t('blog.title'), 'lede' => e(t('blog.lede')), 'doodle' => 'clapper',
    'after' => '<div class="live-search" data-live-search style="max-width:520px;margin-top:22px"><div class="cc__search"><label class="sr-only" for="blog-q">' . e(t('blog.search_placeholder')) . '</label><input id="blog-q" type="search" placeholder="' . e(t('blog.search_placeholder')) . '" autocomplete="off"><button type="button" tabindex="-1" aria-hidden="true"><svg width="20" height="20"><use href="/assets/img/icons.svg#search"/></svg></button></div></div>']);
?>
<section class="section">
  <div class="container post-layout">
    <div>
      <div class="chips chips--wrap" data-blog-filters style="margin-bottom:22px">
        <button type="button" class="chip" aria-pressed="true" data-topic=""><?= e(t('blog.filter_all')) ?> <span class="muted"><?= count($all) ?></span></button>
        <?php foreach ($topics as $tp => $n): ?><button type="button" class="chip" aria-pressed="false" data-topic="<?= e($tp) ?>"><?= e($tp) ?> <span class="muted"><?= $n ?></span></button><?php endforeach; ?>
      </div>
      <div class="title-grid title-grid--wide" data-blog-grid data-step="8" data-visible="9">
        <?php foreach ($all as $i => $p): ?><div class="blog-item" data-topic="<?= e($p['topic']) ?>"<?= $i >= 9 ? ' hidden' : '' ?>><?php render_post_card($p, 'wide', $i === 0, 2); ?></div><?php endforeach; ?>
      </div>
      <p style="margin-top:22px;display:flex;align-items:center;gap:16px"><button type="button" class="btn btn--ghost" data-blog-more><?= e(t('blog.more')) ?></button><span class="muted" data-blog-count></span></p>
    </div>
    <?php include INCLUDES_DIR . '/partials/blog-side.php'; ?>
  </div>
</section>
<?php render_cta_band(); page_close(); ?>
