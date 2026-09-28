<?php
/** A blog post / SEO landing page. */
if (!defined('PRIMO_APP')) exit;
$post = $GLOBALS['post'];
$d = site_data();
$origin = current_origin();
$related = array_values(array_filter(posts(), fn($p) => $p['key'] !== $post['key'] && ($p['topic'] === $post['topic'] || array_intersect($p['tags'] ?? [], $post['tags'] ?? []))));
if (count($related) < 4) foreach (posts() as $p) { if ($p['key'] !== $post['key'] && !in_array($p, $related, true)) $related[] = $p; if (count($related) >= 4) break; }
$related = array_slice($related, 0, 4);
page_open([
    'key' => 'post', 'post' => $post, 'title' => $post['title'], 'description' => $post['excerpt'], 'og_type' => 'article',
    'jsonld' => array_merge([[
        '@type' => 'Article', 'headline' => $post['title'], 'description' => $post['excerpt'], 'datePublished' => $post['date'], 'dateModified' => $post['updated'] ?? $post['date'],
        'inLanguage' => locale(), 'mainEntityOfPage' => $origin . post_url($post),
        'author' => ['@type' => 'Organization', 'name' => $d['brand']['name']], 'publisher' => ['@id' => $origin . '/#org'],
    ]], !empty($post['faq']) ? [faq_jsonld($post['faq'])] : []),
]);
?>
<section class="post-hero">
  <div class="container">
    <p class="eyebrow muted" style="margin-bottom:12px"><a href="<?= e(url('blog')) ?>" style="display:inline-block;padding:10px 0"><?= e(t('nav.blog')) ?></a> · <?= e($post['topic']) ?></p>
    <h1 class="post-hero__title"><?= e($post['title']) ?></h1>
    <div class="post-hero__meta"><span><?= e(date_human($post['date'])) ?></span><span>·</span><span><?= e(t('blog.read_time', $post['minutes'])) ?></span><span>·</span><span><?= e(t('blog.by')) ?></span></div>
    <div class="post-hero__art" style="max-width:960px"><?= poster($post['poster_title'] ?? $post['title'], $post['key'], 'wide', $post['poster'] ?? []) ?></div>
  </div>
</section>
<section class="section" style="padding-top:0">
  <div class="container post-layout">
    <article class="prose">
      <?= $post['body'] ?>
      <?php if (!empty($post['prices'])): ?>
      <div class="post-price">
        <?php foreach ([3, 6, 12] as $m): ?><a href="<?= e(url('pricing')) ?>"><span><?= e(t('pricing.months_n', $m)) ?></span><b><?= e(money($d['pricing']['prices']['vip'][$m][1])) ?></b><span>Elite VIP · 1 <?= lang() === 'fr' ? 'appareil' : 'toestel' ?></span></a><?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php if (!empty($post['faq'])): ?><h2>FAQ</h2><?php render_faq($post['faq']); endif; ?>
      <p style="margin-top:2em"><a class="btn btn--red" href="<?= e(url('pricing')) ?>"><?= e(t('cta.see_plans')) ?></a> <a class="btn btn--ghost" href="<?= e($d['brand']['whatsapp'] . '?text=' . rawurlencode(t('cta.trial_message'))) ?>" target="_blank" rel="noopener"><?= e(t('cta.free_trial')) ?></a></p>
      <?php if (!empty($post['tags'])): ?><div class="tag-cloud" style="margin-top:28px"><?php foreach ($post['tags'] as $tg): ?><a href="<?= e(tag_url($tg)) ?>">#<?= e($tg) ?></a><?php endforeach; ?></div><?php endif; ?>
      <?php $share = rawurlencode($origin . post_url($post)); $st = rawurlencode($post['title']); ?>
      <div class="share"><span><?= e(t('blog.share')) ?></span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share ?>" target="_blank" rel="noopener" aria-label="Facebook"><b>f</b></a>
        <a href="https://twitter.com/intent/tweet?url=<?= $share ?>&text=<?= $st ?>" target="_blank" rel="noopener" aria-label="X"><b>X</b></a>
        <a href="https://wa.me/?text=<?= $st ?>%20<?= $share ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><svg width="18" height="18"><use href="/assets/img/icons.svg#whatsapp"/></svg></a>
        <a href="mailto:?subject=<?= $st ?>&body=<?= $share ?>" aria-label="E-mail"><svg width="18" height="18"><use href="/assets/img/icons.svg#mail"/></svg></a>
      </div>
    </article>
    <?php include INCLUDES_DIR . '/partials/blog-side.php'; ?>
  </div>
</section>
<section class="section band--night2">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= e(t('blog.keep_reading')) ?></h2><a class="section-link" href="<?= e(url('blog')) ?>"><?= e(t('blog.all', count(posts()))) ?> →</a></div>
    <div class="title-grid title-grid--wide"><?php foreach ($related as $rp) render_post_card($rp, 'wide'); ?></div>
  </div>
</section>
<?php render_cta_band(); page_close(); ?>
