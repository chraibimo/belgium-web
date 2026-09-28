<?php
/** FAQ page with grouped questions and a client-side filter. */
if (!defined('PRIMO_APP')) exit;
$c = content('faqs');
$all = [];
foreach ($c['groups'] as $g) foreach ($g[1] as $f) $all[] = $f;
page_open(['key' => 'faqs', 'title' => $c['title'], 'description' => $c['description'], 'jsonld' => [faq_jsonld($all)]]);
render_page_hero(['badge' => $c['hero']['badge'], 'title' => $c['hero']['title'], 'lede' => e($c['hero']['lede']), 'doodle' => 'glasses',
    'after' => '<div class="cc__search" style="max-width:560px;margin-top:22px"><label class="sr-only" for="faq-q">' . e($c['filter_placeholder']) . '</label><input id="faq-q" type="search" placeholder="' . e($c['filter_placeholder']) . '" data-faq-filter autocomplete="off"><button type="button" tabindex="-1" aria-hidden="true"><svg width="20" height="20"><use href="/assets/img/icons.svg#search"/></svg></button></div>']);
?>
<section class="section">
  <div class="container" style="max-width:960px">
    <?php foreach ($c['groups'] as $g): ?>
    <div class="faq-group" style="margin-bottom:40px">
      <h2 class="section-title" style="font-size:clamp(28px,3vw,40px);margin-bottom:16px"><?= e($g[0]) ?></h2>
      <?php render_faq($g[1]); ?>
    </div>
    <?php endforeach; ?>
    <p class="muted" data-faq-empty hidden><?= e(content('js')['faq_search']) ?></p>
    <div class="ticket" style="margin-top:40px"><h2 class="ticket__title" style="font-size:40px"><?= e($c['cta']['title']) ?></h2><p><?= e($c['cta']['text']) ?></p><p><a class="btn btn--red" href="<?= e(url('contact')) ?>"><?= e($c['cta']['button']) ?></a></p></div>
  </div>
</section>
<script>
(function(){var q=document.querySelector('[data-faq-filter]');if(!q)return;var items=document.querySelectorAll('.faq__item'),groups=document.querySelectorAll('.faq-group'),empty=document.querySelector('[data-faq-empty]');q.addEventListener('input',function(){var v=q.value.trim().toLowerCase(),n=0;items.forEach(function(it){var hit=!v||it.textContent.toLowerCase().indexOf(v)>-1;it.hidden=!hit;if(hit)n++;if(v&&hit)it.open=true});groups.forEach(function(g){g.hidden=!Array.prototype.some.call(g.querySelectorAll('.faq__item'),function(i){return !i.hidden})});empty.hidden=n>0})})();
</script>
<?php render_cta_band(); page_close(); ?>
