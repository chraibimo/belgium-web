<?php
/** /chat/: with tawk.to configured it redirects to the direct chat link; otherwise the WhatsApp/e-mail options. */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
$c = content('chat');
if (TAWK_PROPERTY !== '' && TAWK_WIDGET !== '') {
    $to = 'https://tawk.to/chat/' . rawurlencode(TAWK_PROPERTY) . '/' . rawurlencode(TAWK_WIDGET);
    header('Location: ' . $to, true, 302);
    exit;
}
page_open(['key' => 'chat', 'title' => $c['title'], 'description' => $c['description'], 'noindex' => true]);
?>
<section class="section" style="padding-top:calc(var(--header-h) + 48px)">
  <div class="container" style="max-width:820px">
    <div class="ticket">
      <span class="badge badge--red" style="justify-self:start"><?= e($c['badge']) ?></span>
      <h1 class="ticket__title"><?= $c['title_html'] ?></h1>
      <p><?= e($c['lede']) ?></p>
      <p style="display:flex;flex-wrap:wrap;gap:12px"><a class="btn btn--green" href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener"><svg width="18" height="18"><use href="/assets/img/icons.svg#whatsapp"/></svg> WhatsApp</a><a class="btn btn--red" href="mailto:<?= e($d['brand']['email']) ?>"><?= e($d['brand']['email']) ?></a><a class="btn btn--ghost btn--ghost-dark" href="<?= e(url('contact')) ?>"><?= e(t('footer.contact_form')) ?></a></p>
      <p style="font-size:14px"><?= e(lang() === 'fr' ? $d['brand']['hours_fr'] : $d['brand']['hours_nl']) ?></p>
    </div>
  </div>
</section>
<?php page_close(); ?>
