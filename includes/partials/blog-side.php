<?php
/** Blog sidebar: lead form (form=lead), tag cloud, latest posts. */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
?>
<aside class="post-side">
  <form class="side-card side-card--red js-form" action="/api/forms.php" method="post" novalidate>
    <input type="hidden" name="form" value="lead"><input type="hidden" name="lang" value="<?= lang() ?>">
    <h2><?= e(t('blog.lead_title')) ?></h2>
    <p><?= e(t('blog.lead_lede')) ?></p>
    <div class="field"><label for="l-name"><?= e(t('blog.lead_name')) ?></label><input id="l-name" name="name" type="text" autocomplete="name" style="background:var(--cream);color:var(--ink);border-color:var(--ink)"></div>
    <div class="field"><label for="l-phone"><?= e(t('blog.lead_phone')) ?> *</label><input id="l-phone" name="phone" type="tel" autocomplete="tel" required style="background:var(--cream);color:var(--ink);border-color:var(--ink)"><span class="field__error"></span></div>
    <div class="field"><label for="l-email"><?= e(t('blog.lead_email')) ?> *</label><input id="l-email" name="email" type="email" autocomplete="email" required style="background:var(--cream);color:var(--ink);border-color:var(--ink)"><span class="field__error"></span></div>
    <p style="margin-top:14px"><button type="submit" class="btn"><?= e(t('blog.lead_button')) ?></button></p>
    <p class="form__msg" role="status" aria-live="polite"></p>
  </form>
  <div class="side-card">
    <h2><?= e(t('blog.tags')) ?></h2>
    <div class="tag-cloud" style="margin-top:12px"><?php foreach (tags() as $tag => $n): $cur = ($GLOBALS['tag'] ?? null) === $tag; ?><a href="<?= e(tag_url($tag)) ?>"<?= $cur ? ' class="is-active" aria-current="page"' : '' ?>><?= e($tag) ?> <span class="muted"><?= $n ?></span></a><?php endforeach; ?></div>
  </div>
  <div class="side-card">
    <h2><?= e(t('blog.keep_reading')) ?></h2>
    <div style="display:grid;gap:10px;margin-top:12px">
      <?php foreach (array_slice(posts(), 0, 5) as $rp): if (isset($post) && $post && $rp['key'] === $post['key']) continue; ?>
      <a href="<?= e(post_url($rp)) ?>" style="display:grid;grid-template-columns:72px 1fr;gap:10px;align-items:center"><span style="overflow:hidden;border:2px solid var(--ink);border-radius:4px"><?= poster('', $rp['key'], 'wide', array_merge($rp['poster'] ?? [], ['class' => 'poster--mini'])) ?></span><span style="font-weight:700;font-size:14px;line-height:1.3"><?= e($rp['title']) ?></span></a>
      <?php endforeach; ?>
    </div>
  </div>
</aside>
