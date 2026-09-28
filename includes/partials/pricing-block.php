<?php
/**
 * The two plan tickets + promo timer. Included by the home page and the
 * pricing page. Expects $mode = 'home' (term pills, 1 device) or 'full'
 * (term + devices selects). Prices are rendered server-side for the default
 * selection so the tickets read without JS; pricing.js does the rest.
 */
if (!defined('PRIMO_APP')) exit;

$pc    = content('pricing');
$blk   = $pc['block'];
$pr    = site_data()['pricing'];
$round = promo_round();
$sessionSecs = (int)($pr['promo']['session_seconds'] ?? 0);   // > 0: restart at this number every session
$defMonths = $mode === 'home' ? 3 : 1;
$cfg = [
    'currency' => $pr['currency'],
    'prices'   => $pr['prices'],
    'names'    => $pr['plans'],
    'refund'   => $pr['refund_days'],
    'reference'=> $pr['promo']['reference_multiplier'],
    'roundEnd' => $round['end'] ?? null,
    'timerSession' => $sessionSecs ?: null,
    'sheet'    => CHECKOUT_SHEET !== '' ? 'https://docs.google.com/spreadsheets/d/' . CHECKOUT_SHEET . '/gviz/tq?tqx=out:json&gid=' . CHECKOUT_SHEET_GID : '',
    'mode'     => $mode,
];
?>
<section class="pricing section" id="pricing" data-pricing>
  <script type="application/json" data-pricing-config><?= json_attr($cfg) ?></script>
  <div class="container">
    <div class="pricing__head">
      <p class="eyebrow"><?= e($blk['eyebrow']) ?></p>
      <h2 class="section-title"><?= $blk['title'] ?></h2>
      <p class="section-lede"><?= e($blk['lede']) ?></p>
      <?php if ($round): $t0 = $sessionSecs; ?>
      <div class="pricing__timer" data-timer="<?= (int)$round['end'] ?>"<?= $sessionSecs > 0 ? ' data-timer-session="' . $sessionSecs . '"' : '' ?>>
        <span><?= e($blk['timer']) ?></span>
        <span class="timer" aria-live="off">
          <span><b data-t="d"><?= $t0 ? intdiv($t0, 86400) : 0 ?></b><small><?= e(t('time.d')) ?></small></span>
          <span><b data-t="h"><?= $t0 ? sprintf('%02d', intdiv($t0 % 86400, 3600)) : '00' ?></b><small><?= e(t('time.h')) ?></small></span>
          <span><b data-t="m"><?= $t0 ? sprintf('%02d', intdiv($t0 % 3600, 60)) : '00' ?></b><small><?= e(t('time.m')) ?></small></span>
          <span><b data-t="s"><?= $t0 ? sprintf('%02d', $t0 % 60) : '00' ?></b><small><?= e(t('time.s')) ?></small></span>
        </span>
      </div>
      <?php endif; ?>
      <ul class="pricing__trust">
        <?php foreach ($blk['trust'] as $tr): ?><li><?= e($tr) ?></li><?php endforeach; ?>
      </ul>
    </div>

    <div class="plans">
      <?php foreach (['vip', 'essential'] as $plan): $p = $blk['plans'][$plan]; $price = $pr['prices'][$plan][$defMonths][1]; ?>
      <article class="plan plan--<?= $plan ?>" data-plan="<?= $plan ?>">
        <?php if ($p['flag']): ?><span class="plan__flag"><?= e($p['flag']) ?></span><?php endif; ?>
        <div class="plan__band"><?= e($p['band']) ?></div>
        <div class="plan__body">
          <h3 class="plan__name"><?= e($p['name']) ?></h3>
          <p class="plan__tag"><?= e($p['tag']) ?></p>

          <div class="plan__controls">
            <div class="plan__control">
              <span><?= e(t('pricing.months')) ?></span>
              <?php if ($mode === 'home'): ?>
              <div class="seg" role="group" aria-label="<?= e(t('pricing.months')) ?>">
                <?php foreach ([3, 6, 12] as $m): ?>
                <button type="button" data-months="<?= $m ?>" aria-pressed="<?= $m === $defMonths ? 'true' : 'false' ?>"><?= e(t('pricing.months_n', $m)) ?></button>
                <?php endforeach; ?>
              </div>
              <?php else: ?>
              <label class="select"><span class="sr-only"><?= e(t('pricing.months')) ?></span>
                <select data-select="months">
                  <?php foreach ($pr['terms'] as $m): ?><option value="<?= $m ?>"<?= $m === $defMonths ? ' selected' : '' ?>><?= e($m === 1 ? t('pricing.month_1') : t('pricing.months_n', $m)) ?></option><?php endforeach; ?>
                </select></label>
              <?php endif; ?>
            </div>
            <div class="plan__control"<?= $mode === 'home' ? ' hidden' : '' ?>>
              <span><?= e(t('pricing.devices')) ?></span>
              <label class="select"><span class="sr-only"><?= e(t('pricing.devices')) ?></span>
                <select data-select="devices"<?= $mode === 'home' ? ' disabled' : '' ?>>
                  <?php foreach ($pr['devices'] as $n): ?><option value="<?= $n ?>"><?= e($n === 1 ? t('pricing.device_1') : t('pricing.devices_n', $n)) ?></option><?php endforeach; ?>
                </select></label>
            </div>
          </div>

          <div class="plan__price">
            <p class="plan__price-label"><?= e(t('pricing.you_pay')) ?></p>
            <p class="plan__amount"><span data-price><?= e(money($price)) ?></span></p>
            <p class="plan__per" data-per><?= e(t('pricing.per_month', money($price / $defMonths))) ?></p>
            <p class="plan__was" data-was hidden></p>
            <span class="plan__save" data-save></span>
          </div>

          <div class="plan__features">
            <ul class="check-list<?= $plan === 'vip' ? '' : ' check-list--cream' ?>">
              <li data-line="term"><?= e(t('pricing.term_line', t('pricing.months_n', $defMonths))) ?></li>
              <li data-line="devices"><?= e(t('pricing.devices_line', t('pricing.device_1'))) ?></li>
              <?php foreach ($p['features'] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="plan__stub">
          <a class="btn plan__cta" role="button" tabindex="0" data-cta data-available="1"><?= e(t('cta.order')) ?></a>
          <div class="plan__stub-meta">
            <span><?= e(t('pricing.no_contract')) ?></span>
            <span><?= e(t('pricing.login_by')) ?></span>
          </div>
          <div class="plan__barcode" aria-hidden="true"></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <p class="pricing__fine"><?= e(t('pricing.fine')) ?></p>
  </div>
</section>
