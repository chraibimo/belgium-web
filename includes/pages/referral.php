<?php
/** Referral programme: perks, how it works, claim form (posts JSON to /api/hook.php?t=referral). */
if (!defined('PRIMO_APP')) exit;
$c = content('referral');
page_open(['key' => 'referral', 'title' => $c['title'], 'description' => $c['description']]);
render_page_hero(['badge' => $c['hero']['badge'], 'title' => $c['hero']['title'], 'lede' => e($c['hero']['lede']), 'doodle' => 'megaphone']);
?>
<section class="section">
  <div class="container grid-2" style="align-items:start">
    <div>
      <div class="perks"><?php foreach ($c['perks'] as $p): ?><div class="perk"><p class="eyebrow"><?= e($p[0]) ?></p><b><?= e($p[1]) ?></b></div><?php endforeach; ?></div>
      <h2 class="section-title" style="font-size:clamp(28px,3vw,40px);margin-top:40px"><?= e($c['how']['title']) ?></h2>
      <ol class="tut__steps"><?php foreach ($c['how']['steps'] as $st): ?><li class="tut__step"><div><h3><?= e($st[0]) ?></h3><p><?= e($st[1]) ?></p></div></li><?php endforeach; ?></ol>
      <p class="muted" style="margin-top:12px;font-size:14px"><?= e($c['how']['note']) ?></p>
    </div>
    <div style="display:grid;gap:20px">
      <form class="contact__form" id="referral" novalidate data-referral>
        <h2 class="section-title" style="font-size:clamp(28px,3vw,40px)"><?= e($c['form']['title']) ?></h2>
        <p class="eyebrow muted"><?= e($c['form']['you']) ?></p>
        <div class="field"><label for="r-name"><?= e($c['form']['name']) ?> *</label><input id="r-name" name="fullName" type="text" autocomplete="name" required><span class="field__error"></span></div>
        <div class="grid-2">
          <div class="field"><label for="r-email"><?= e($c['form']['email']) ?> *</label><input id="r-email" name="email" type="email" autocomplete="email" required><span class="field__error"></span></div>
          <div class="field"><label for="r-order"><?= e($c['form']['order']) ?> *</label><input id="r-order" name="orderNumber" type="text" placeholder="<?= e($c['form']['order_hint']) ?>" required><span class="field__error"></span></div>
        </div>
        <p class="eyebrow muted"><?= e($c['form']['friend']) ?></p>
        <div class="grid-2">
          <div class="field"><label for="r-rname"><?= e($c['form']['ref_name']) ?> *</label><input id="r-rname" name="referrerFullName" type="text" required><span class="field__error"></span></div>
          <div class="field"><label for="r-remail"><?= e($c['form']['ref_email']) ?> *</label><input id="r-remail" name="referrerEmail" type="email" required><span class="field__error"></span></div>
        </div>
        <div><button type="submit" class="btn btn--red"><?= e($c['form']['send']) ?></button></div>
        <p class="form__msg" role="status" aria-live="polite"></p>
      </form>
      <div class="vault"><h2 class="section-title" style="font-size:28px"><?= e($c['vault']['title']) ?></h2><?php foreach ($c['vault']['items'] as $it): ?><div><h3><?= e($it[0]) ?></h3><p><?= e($it[1]) ?></p></div><?php endforeach; ?></div>
    </div>
  </div>
</section>
<script>
(function(){var f=document.querySelector('[data-referral]');if(!f)return;var T=(window.MTV&&window.MTV.t)||{},msg=f.querySelector('.form__msg');f.addEventListener('submit',async function(e){e.preventDefault();var ok=true;f.querySelectorAll('.field').forEach(function(x){x.classList.remove('is-error')});f.querySelectorAll('[required]').forEach(function(i){var bad=!i.value.trim()||(i.type==='email'&&!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(i.value));if(bad){ok=false;i.closest('.field').classList.add('is-error')}});if(!ok){msg.textContent=T.ref_fix;msg.className='form__msg is-error';return}msg.textContent=T.sending;msg.className='form__msg';var data={};new FormData(f).forEach(function(v,k){data[k]=v});data.pageUrl=location.href;data.submittedAt=new Date().toISOString();data.lang=window.MTV.lang;try{var r=await fetch('/api/hook.php?t=referral',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)});var j=await r.json().catch(function(){return{}});if(r.ok&&j.ok!==false){msg.textContent=j.message||T.ref_ok;msg.className='form__msg is-ok';f.reset()}else{msg.textContent=T.ref_fail;msg.className='form__msg is-error'}}catch(_){msg.textContent=T.network;msg.className='form__msg is-error'}})})();
</script>
<?php render_cta_band(); page_close(); ?>
