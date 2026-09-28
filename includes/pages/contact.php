<?php
/** Contact: three ways in, the form (posts to /api/forms.php form=contact), hours, FAQ. */
if (!defined('PRIMO_APP')) exit;
$c = content('contact');
$d = site_data();
page_open(['key' => 'contact', 'title' => $c['title'], 'description' => $c['description'], 'jsonld' => [faq_jsonld($c['faq'])]]);
?>
<section class="phero band--red">
  <svg class="phero__doodle" aria-hidden="true"><use href="/assets/img/doodles.svg#chat"/></svg>
  <div class="container phero__inner">
    <span class="badge phero__badge"><?= e($c['hero']['badge']) ?></span>
    <h1 class="phero__title display hard-shadow"><?= $c['hero']['title'] ?></h1>
    <p class="phero__lede"><?= e($c['hero']['lede']) ?></p>
    <div class="phero__meta"><span class="pill"><svg width="16" height="16"><use href="/assets/img/icons.svg#clock"/></svg><?= e($c['hero']['reply']) ?></span></div>
    <div class="phero__actions"><a class="btn" href="#form"><?= e($c['form']['title']) ?></a><a class="btn btn--maroon" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener"><?= e(t('cta.whatsapp')) ?></a></div>
  </div>
</section>

<section class="section">
  <div class="container contact">
    <div>
      <form class="contact__form js-form" id="form" action="/api/forms.php" method="post" novalidate>
        <input type="hidden" name="form" value="contact"><input type="hidden" name="lang" value="<?= lang() ?>"><input type="hidden" name="started" value="">
        <div class="form__hp" aria-hidden="true"><label>Company <input type="text" name="company" tabindex="-1" autocomplete="off"></label></div>
        <h2 class="section-title" style="font-size:clamp(28px,3vw,40px)"><?= e($c['form']['title']) ?></h2>
        <div class="radio-row">
          <?php foreach ($c['form']['topics'] as $i => $tp): ?><input type="radio" name="topic" id="topic<?= $i ?>" value="<?= e($tp) ?>"<?= $i === 0 ? ' checked' : '' ?>><label for="topic<?= $i ?>"><?= e($tp) ?></label><?php endforeach; ?>
        </div>
        <div class="grid-2">
          <div class="field"><label for="c-name"><?= e($c['form']['name']) ?> *</label><input id="c-name" name="name" type="text" autocomplete="name" required><span class="field__error"></span></div>
          <div class="field"><label for="c-email"><?= e($c['form']['email']) ?> *</label><input id="c-email" name="email" type="email" autocomplete="email" required><span class="field__error"></span></div>
        </div>
        <div class="grid-2">
          <div class="field"><label for="c-phone"><?= e($c['form']['phone']) ?> <small><?= e($c['form']['phone_hint']) ?></small></label><input id="c-phone" name="phone" type="tel" autocomplete="tel"><span class="field__error"></span></div>
          <div class="field"><label for="c-subject"><?= e($c['form']['subject']) ?> *</label><input id="c-subject" name="subject" type="text" maxlength="190" required><span class="field__error"></span></div>
        </div>
        <div class="field"><label for="c-message"><?= e($c['form']['message']) ?> *</label><textarea id="c-message" name="message" maxlength="5000" required></textarea><span class="field__error"></span></div>
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:14px"><button type="submit" class="btn btn--red"><?= e($c['form']['send']) ?></button><small class="muted"><?= e($c['form']['privacy']) ?></small></div>
        <p class="form__msg" role="status" aria-live="polite"></p>
      </form>
    </div>
    <aside class="contact__ways">
      <h2 class="section-title" style="font-size:clamp(28px,3vw,40px)"><?= $c['ways']['title'] ?></h2>
      <?php foreach ($c['ways']['items'] as $w): ?>
      <div class="way"><span class="way__icon"><svg width="24" height="24"><use href="/assets/img/icons.svg#<?= e($w['icon']) ?>"/></svg></span><div><h3><?= e($w['title']) ?></h3><p><?= e($w['text']) ?></p><a href="<?= e($w['link']) ?>"<?= str_starts_with($w['link'], 'http') ? ' target="_blank" rel="noopener"' : '' ?>><?= e($w['label']) ?></a></div></div>
      <?php endforeach; ?>
      <div class="side-card"><h2><?= e($c['after']['title']) ?></h2><ol style="display:grid;gap:8px;margin-top:10px;padding-left:1.2em;list-style:decimal;font-size:14px;color:var(--muted)"><?php foreach ($c['after']['items'] as $it): ?><li><?= e($it) ?></li><?php endforeach; ?></ol></div>
    </aside>
  </div>
</section>

<section class="section band--night2 reveal">
  <div class="container">
    <div class="section-head"><h2 class="section-title"><?= $c['hours']['title'] ?></h2></div>
    <div class="hours"><?php foreach ($c['hours']['items'] as $h): ?><div><b><?= e($h[0]) ?></b><strong><?= e($h[1]) ?></strong><span><?= e($h[2]) ?></span></div><?php endforeach; ?></div>
    <div style="max-width:860px;margin-top:40px"><?php render_faq($c['faq']); ?></div>
  </div>
</section>
<?php render_cta_band(); ?>
<?php page_close(); ?>
