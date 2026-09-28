<?php
/** Installation assistant: pick a device, get its steps (no JS: all lists are in the page, first one open). */
if (!defined('PRIMO_APP')) exit;
$c = content('tutorial');
$d = site_data();
$pick = preg_replace('/[^a-z]/', '', (string)($_GET['device'] ?? ''));
$active = 0;
foreach ($c['devices'] as $i => $dev) if ($dev['id'] === $pick) $active = $i;
page_open(['key' => 'tutorial', 'title' => $c['title'], 'description' => $c['description'], 'jsonld' => [faq_jsonld($c['faq'])]]);
?>
<section class="phero band--red">
  <svg class="phero__doodle" aria-hidden="true"><use href="/assets/img/doodles.svg#remote"/></svg>
  <div class="container phero__inner">
    <span class="badge phero__badge"><?= e($c['hero']['badge']) ?></span>
    <h1 class="phero__title display hard-shadow"><?= $c['hero']['title'] ?></h1>
    <p class="phero__lede"><?= $c['hero']['lede'] ?></p>
    <div class="phero__meta">
      <span class="pill"><svg width="16" height="16"><use href="/assets/img/icons.svg#clock"/></svg><?= e($c['hero']['time']) ?></span>
      <span class="pill"><?= e($c['code_label']) ?> <code style="font-weight:900;letter-spacing:.1em"><?= e($c['code']) ?></code> <button type="button" class="btn btn--sm" data-copy="<?= e($c['code']) ?>" style="height:28px;padding:0 8px;margin-left:6px"><?= e(t('common.copy')) ?></button></span>
      <a class="pill" href="<?= e(url('chat')) ?>"><svg width="16" height="16"><use href="/assets/img/icons.svg#chat"/></svg><?= e($c['hero']['help']) ?></a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container tut">
    <div>
      <p class="eyebrow muted"><?= e($c['pick']) ?></p>
      <div class="tut__devices" role="tablist" data-tut style="margin-top:12px">
        <?php foreach ($c['devices'] as $i => $dev): ?>
        <button type="button" class="tut__device" role="tab" aria-selected="<?= $i === $active ? 'true' : 'false' ?>" data-device="<?= e($dev['id']) ?>"><svg><use href="/assets/img/icons.svg#<?= e($dev['icon']) ?>"/></svg><span><?= e($dev['name']) ?></span></button>
        <?php endforeach; ?>
      </div>
      <?php foreach ($c['devices'] as $i => $dev): ?>
      <div class="tut__flow" role="tabpanel" data-flow="<?= e($dev['id']) ?>"<?= $i === $active ? '' : ' hidden' ?>>
        <h2 class="section-title" style="font-size:clamp(28px,3.4vw,44px);margin-top:28px"><?= e(sprintf($c['steps_title'], $dev['name'])) ?></h2>
        <ol class="tut__steps">
          <?php foreach ($dev['steps'] as $st): ?><li class="tut__step"><div><h3><?= $st[0] ?></h3><p><?= $st[1] ?></p></div></li><?php endforeach; ?>
        </ol>
      </div>
      <?php endforeach; ?>
      <details class="faq__item tut__trouble"><summary><?= e($c['trouble']['title']) ?></summary><div class="faq__body"><ul><li><?= e($c['trouble']['a']) ?></li><li><?= e($c['trouble']['b']) ?></li><li><?= e($c['trouble']['c']) ?></li></ul></div></details>
      <div class="section-head" style="margin-top:40px"><h2 class="section-title" style="font-size:clamp(28px,3vw,40px)">FAQ</h2></div>
      <?php render_faq($c['faq']); ?>
    </div>
    <aside class="tut__side">
      <div class="tut__card">
        <h2><?= e($c['side']['title']) ?></h2>
        <p style="margin-top:8px"><?= $c['side']['text'] ?></p>
        <ul><?php foreach ($c['side']['items'] as $it): ?><li><?= e($it) ?></li><?php endforeach; ?></ul>
        <div class="codebox"><div><small><?= e($c['code_label']) ?></small><code><?= e($c['code']) ?></code></div><button type="button" class="btn btn--sm btn--red" data-copy="<?= e($c['code']) ?>"><?= e(t('common.copy')) ?></button></div>
        <p style="margin-top:12px;font-size:13px"><?= e($c['side']['tip']) ?></p>
      </div>
      <div class="ticket" style="box-shadow:8px 8px 0 var(--red)">
        <h2 class="ticket__title" style="font-size:34px"><?= e($c['stuck']['title']) ?></h2>
        <p><?= e($c['stuck']['text']) ?></p>
        <p style="display:grid;gap:10px"><a class="btn btn--red" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener"><?= e($c['stuck']['wa']) ?></a><a class="btn btn--ghost btn--ghost-dark" href="mailto:<?= e($d['brand']['email']) ?>"><?= e($c['stuck']['mail']) ?></a></p>
      </div>
    </aside>
  </div>
</section>
<?php render_cta_band(); ?>
<script>
(function(){var box=document.querySelector('[data-tut]');if(!box)return;var tabs=box.querySelectorAll('[data-device]'),flows=document.querySelectorAll('[data-flow]');tabs.forEach(function(t){t.addEventListener('click',function(){tabs.forEach(function(x){x.setAttribute('aria-selected','false')});t.setAttribute('aria-selected','true');flows.forEach(function(f){f.hidden=f.dataset.flow!==t.dataset.device});try{history.replaceState(null,'','?device='+t.dataset.device)}catch(e){}var f=document.querySelector('[data-flow="'+t.dataset.device+'"]');if(f&&window.innerWidth<900)f.scrollIntoView({behavior:'smooth',block:'start'})})})})();
</script>
<?php page_close(); ?>
