<?php
/** Tag archive. */
if (!defined('PRIMO_APP')) exit;
$tag = $GLOBALS['tag'];
$list = array_values(array_filter(posts(), fn($p) => in_array($tag, $p['tags'] ?? [], true)));
page_open(['key' => 'tag', 'title' => t('blog.tag_title', $tag), 'description' => t('blog.lede')]);
render_page_hero(['badge' => t('blog.tags'), 'title' => e($tag), 'lede' => e(t('blog.tag_count', count($list))), 'doodle' => 'ticket']);
?>
<section class="section">
  <div class="container post-layout<?= count($list) <= 6 ? ' post-layout--slim' : '' ?>">
    <div class="title-grid title-grid--wide"><?php foreach ($list as $p) render_post_card($p, 'wide', false, 2); ?></div>
    <?php include INCLUDES_DIR . '/partials/blog-side.php'; ?>
  </div>
</section>
<?php render_cta_band(); page_close(); ?>
