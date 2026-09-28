<?php
/** Home page. */
if (!defined('PRIMO_APP')) exit;

$c  = content('home');
$d  = site_data();
$h  = $c['hero'];
$logoRows = json_decode((string)@file_get_contents(APP_DIR . '/channel-logos.json'), true)['rows'] ?? [];
$logos = $logoRows ? array_merge(...$logoRows) : [];   // the manifest groups tiles per river row
$allPosts = posts();
$topics = [];
foreach ($allPosts as $p) $topics[$p['topic']] = ($topics[$p['topic']] ?? 0) + 1;

page_open([
    'key' => 'home', 'title' => $c['title'], 'full_title' => true, 'description' => $c['description'],
    'jsonld' => [faq_jsonld($c['faq']['items'])],
]);
?>

<section class="hero band--red">
  <svg class="hero__doodle hero__doodle--1" aria-hidden="true"><use href="/assets/img/doodles.svg#camera"/></svg>
  <svg class="hero__doodle hero__doodle--2" aria-hidden="true"><use href="/assets/img/doodles.svg#reel"/></svg>
  <div class="hero__blur hero__blur--1" aria-hidden="true"><?= poster('', 'blur-1', 'portrait', ['palette' => 'ink', 'motif' => 'orbit', 'class' => 'poster--idle']) ?></div>
  <div class="hero__blur hero__blur--2" aria-hidden="true"><?= poster('', 'blur-2', 'wide', ['palette' => 'mustard', 'motif' => 'city', 'class' => 'poster--idle']) ?></div>

  <div class="container hero__inner">
    <div class="hero__copy">
      <span class="hero__badge"><?= e($h['badge']) ?></span>
      <h1 class="hero__title display hard-shadow"><span><?= e($h['line1']) ?></span><span><?= e($h['line2']) ?></span></h1>
      <p class="hero__lead"><?= e($h['lead']) ?></p>
      <div class="hero__actions">
        <a class="btn" href="<?= e(url('pricing')) ?>"><svg width="18" height="18"><use href="/assets/img/icons.svg#play"/></svg><?= e($h['cta1']) ?></a>
        <a class="btn btn--maroon" href="<?= e($d['brand']['whatsapp'] . '?text=' . rawurlencode(t('cta.trial_message'))) ?>" target="_blank" rel="noopener"><?= e($h['cta2']) ?></a>
      </div>
      <div class="hero__pills">
        <?php foreach ($h['pills'] as $pill): ?><span class="pill"><?= e($pill) ?></span><?php endforeach; ?>
      </div>
    </div>

    <div class="hero__stage">
<?php $tvChannels = array_map(fn($ch) => ['id' => $ch['id'], 'name' => $ch['name'], 'meta' => $ch['meta'], 'href' => url($ch['link'])], $h['channels']); $tvMode = ''; include INCLUDES_DIR . '/partials/tv.php'; ?>
      <div class="hero__now" aria-live="polite">
        <span class="eyebrow"><?= e($h['now']) ?> <b data-tv-num>01</b></span>
        <a href="<?= e(url('channels')) ?>" class="hero__now-title" data-tv-name><?= e($h['channels'][0]['name']) ?></a>
        <span class="hero__now-meta" data-tv-meta><?= e($h['channels'][0]['meta']) ?></span>
        <div class="hero__dots" data-tv-dots>
          <?php foreach ($h['channels'] as $i => $ch): ?><button type="button" aria-label="<?= e($ch['name']) ?>" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>" data-tv-go="<?= $i ?>"></button><?php endforeach; ?>
        </div>
        <span class="hero__now-meta muted"><?= e($h['hint']) ?></span>
      </div>
    </div>
  </div>
  <p class="container hero__foot"><?= e($h['foot']) ?></p>
</section>

<section class="section reveal">
  <div class="container grid-2" style="align-items:center">
    <div>
      <p class="eyebrow muted"><?= e($c['ready']['eyebrow']) ?></p>
      <h2 class="section-title"><?= $c['ready']['title'] ?></h2>
      <p class="section-lede"><?= e($c['ready']['lede']) ?></p>
      <ul class="check-list" style="margin-top:22px">
        <?php foreach ($c['ready']['checks'] as $ck): ?><li><?= e($ck) ?></li><?php endforeach; ?>
      </ul>
      <p style="margin-top:26px"><a class="btn" href="<?= e(url('channels')) ?>"><?= e($c['ready']['button']) ?></a></p>
    </div>
    <div class="wall-wrap">
      <div class="wall" data-wall aria-hidden="true">
        <?php
        $wallTitles = ['Pro League', 'Champions League', 'Ronde van Vlaanderen', 'Le Grand Film', 'Nieuwe serie', 'Kids', 'Formule 1', 'Docu', 'Late night', 'Concert', 'Roland Garros', 'Tour de France', 'Thriller', 'Comedy', 'Animatie', 'Nieuws', 'Klassieker', 'Premium boxing'];
        foreach ($wallTitles as $i => $wt) echo poster($wt, 'wall-' . $i);
        ?>
      </div>
    </div>
  </div>
</section>

<section class="section band--maroon reveal">
  <div class="container">
    <div class="section-head"><div><p class="eyebrow"><?= e($c['stats']['eyebrow']) ?></p><h2 class="section-title"><?= e($c['stats']['title']) ?></h2></div></div>
    <ul class="stats">
      <?php foreach ($c['stats']['items'] as $i => $st): ?>
      <li class="stat<?= $i % 2 ? ' stat--cream' : ' stat--red' ?>">
        <div class="stat__num" data-count="<?= e($st['num']) ?>" data-suffix="<?= e($st['suffix']) ?>"><?= e(is_float($st['num']) ? nf($st['num'], 1) : nf($st['num'])) . e($st['suffix']) ?></div>
        <div class="stat__label"><?= e($st['label']) ?></div>
        <div class="stat__sub"><?= e($st['sub']) ?></div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section reveal">
  <div class="container grid-2" style="align-items:center">
    <div class="who__art" aria-hidden="true">
      <div class="who__screen"><?= poster($c['who']['osd'], 'who', 'wide', ['palette' => 'navy', 'motif' => 'grid']) ?></div>
    </div>
    <div>
      <p class="eyebrow muted"><?= e($c['who']['eyebrow']) ?></p>
      <h2 class="section-title"><?= $c['who']['title'] ?></h2>
      <p class="section-lede"><?= e($c['who']['lede']) ?></p>
      <ul class="check-list" style="margin-top:22px">
        <?php foreach ($c['who']['claims'] as $cl): ?><li><?= e($cl) ?></li><?php endforeach; ?>
      </ul>
      <p style="margin-top:26px;display:flex;flex-wrap:wrap;gap:12px"><a class="btn" href="<?= e(url('pricing')) ?>"><?= e($c['who']['button1']) ?></a><a class="btn btn--ghost" href="<?= e(url('tutorial')) ?>"><?= e($c['who']['button2']) ?></a></p>
    </div>
  </div>
</section>

<?php $cc = $c['cc']; $lanes = array_chunk($logos, (int)ceil(count($logos) / 3)); ?>
<section class="cc section band--red reveal" id="channel-check">
  <div class="cc__river" aria-hidden="true">
    <?php foreach (array_slice($lanes, 0, 2) as $li => $lane): $copies = marquee_copies(count($lane), 140); ?>
    <div class="cc__lane<?= $li % 2 ? ' cc__lane--rev' : '' ?>" style="--dur:<?= (int)(1.7 * count($lane) * $copies / 2) + $li * 10 ?>s">
      <?php for ($rep = 0; $rep < $copies; $rep++) foreach ($lane as $lg): ?>
      <button type="button" class="cc__logo<?= ($lg['tone'] ?? '') === 'light' ? ' cc__logo--dark' : '' ?>" data-cc-pick="<?= e($lg['q'] ?? $lg['name']) ?>" tabindex="-1"><img src="/static/media/channels/<?= e($lg['id']) ?>.png" alt="<?= e($lg['name']) ?>" width="100" height="40" loading="lazy" decoding="async"></button>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="container">
    <div class="cc__card">
      <div>
        <p class="eyebrow"><?= e($cc['eyebrow']) ?></p>
        <h2 class="section-title"><?= $cc['title'] ?></h2>
        <p class="section-lede" style="color:rgba(0,0,0,.75)"><?= e($cc['lede']) ?></p>
        <p style="margin-top:22px;display:flex;flex-wrap:wrap;align-items:center;gap:12px"><a class="btn btn--red" href="#pricing"><?= e($cc['button']) ?></a><span class="badge badge--ink"><?= e($cc['pill']) ?></span></p>
      </div>
      <div class="cc__box" data-cc data-placeholders="<?= e(json_attr($cc['placeholders'])) ?>" data-full="<?= e(url('channels')) ?>">
        <p class="eyebrow"><?= e($cc['kicker']) ?></p>
        <h3 class="section-title" style="font-size:clamp(28px,3vw,40px);margin:6px 0 14px"><?= e($cc['h3']) ?></h3>
        <form class="cc__search" action="<?= e(url('channels')) ?>" method="get" role="search">
          <label class="sr-only" for="cc-q"><?= e($cc['h3']) ?></label>
          <input id="cc-q" type="search" name="q" autocomplete="off" spellcheck="false" placeholder="<?= e($cc['placeholders'][0]) ?>" data-cc-input>
          <button type="submit" aria-label="<?= e(t('nav.search')) ?>"><svg width="20" height="20"><use href="/assets/img/icons.svg#search"/></svg></button>
        </form>
        <div class="cc__results" data-cc-results aria-live="polite"></div>
        <p class="cc__note"><?= e($cc['note']) ?></p>
      </div>
    </div>
  </div>
  <div class="cc__river cc__river--stats" aria-hidden="true">
    <?php $lane3 = $lanes[2] ?? []; $copies = marquee_copies(count($lane3) + count($cc['stats']), 140); ?>
    <div class="cc__lane cc__lane--3" style="--dur:<?= (int)(1.7 * (count($lane3) + count($cc['stats'])) * $copies / 2) ?>s">
      <?php for ($rep = 0; $rep < $copies; $rep++): foreach ($lane3 as $lg): ?>
      <button type="button" class="cc__logo<?= ($lg['tone'] ?? '') === 'light' ? ' cc__logo--dark' : '' ?>" data-cc-pick="<?= e($lg['q'] ?? $lg['name']) ?>" tabindex="-1"><img src="/static/media/channels/<?= e($lg['id']) ?>.png" alt="<?= e($lg['name']) ?>" width="100" height="40" loading="lazy" decoding="async"></button>
      <?php endforeach; foreach ($cc['stats'] as $st): ?>
      <span class="cc__logo cc__logo--stat">+<?= e(nf(intdiv((int)$st['num'], $st['step'] ?? 100) * ($st['step'] ?? 100))) ?><small><?= e($st['label']) ?></small></span>
      <?php endforeach; endfor; ?>
    </div>
  </div>
</section>

<?php $mode = 'home'; include INCLUDES_DIR . '/partials/pricing-block.php'; ?>

<?php $rv = content('reviews'); $wall = $rv['wall'] ?? []; if ($wall): ?>
<section class="section band--night2 reveal">
  <div class="container">
    <div class="section-head"><div><p class="eyebrow"><?= e($rv['home_eyebrow']) ?></p><h2 class="section-title"><?= $rv['home_title'] ?></h2><p class="section-lede"><?= e($rv['home_lede']) ?></p></div>
      <a class="section-link" href="<?= e(url('reviews')) ?>"><?= e(t('common.see_all')) ?> →</a></div>
  </div>
  <div class="review-wall" aria-hidden="true">
    <?php foreach (array_chunk($wall, (int)ceil(count($wall) / 2)) as $li => $lane): $copies = marquee_copies(count($lane), 312); ?>
    <div class="review-lane<?= $li % 2 ? ' cc__lane--rev' : '' ?>" style="--dur:<?= (int)(20 * count($lane) * $copies / 2) ?>s">
      <?php for ($rep = 0; $rep < $copies; $rep++) foreach ($lane as $r): ?>
      <div class="review-mini">“<?= e($r['quote']) ?>”<b><?= e($r['name']) ?> · <?= e($r['date']) ?><?= !empty($r['sample']) ? ' · ' . e($rv['sample']) : '' ?></b></div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="section reveal">
  <div class="container">
    <div class="section-head"><div><p class="eyebrow"><?= e($c['features']['eyebrow']) ?></p><h2 class="section-title"><?= $c['features']['title'] ?></h2></div></div>
    <div class="grid-3">
      <?php foreach ($c['features']['items'] as $f): ?>
      <div class="feature">
        <span class="feature__icon"><svg width="26" height="26"><use href="/assets/img/icons.svg#<?= e($f['icon']) ?>"/></svg></span>
        <h3 class="feature__title"><?= e($f['title']) ?></h3>
        <p><?= e($f['text']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="section-head" style="margin-top:56px"><h2 class="section-title"><?= $c['features']['compare_title'] ?></h2></div>
    <div class="compare-wrap">
      <table class="compare">
        <thead><tr><?php foreach ($c['features']['compare_head'] as $th): ?><th><?= e($th) ?></th><?php endforeach; ?></tr></thead>
        <tbody>
          <?php foreach ($c['features']['compare'] as $row): ?>
          <tr>
            <td><?= e($row[0]) ?></td>
            <td><?= $row[1] === '✖' ? '<span class="no">✖</span>' : e($row[1]) ?><?php if ($row[3] > 0): ?><span class="bar"><span style="--w:<?= (int)$row[3] ?>%"></span></span><?php endif; ?></td>
            <td><?= $row[2] === '✔' ? '<span class="yes">✔</span>' : '<b>' . e($row[2]) . '</b>' ?><?php if ($row[3] > 0): ?><span class="bar"><span style="--w:<?= (int)$row[4] ?>%"></span></span><?php endif; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="section band--night2 reveal" id="faq">
  <div class="container grid-2 grid-2--faq">
    <div>
      <p class="eyebrow muted"><?= e($c['faq']['eyebrow']) ?></p>
      <h2 class="section-title"><?= $c['faq']['title'] ?></h2>
      <p class="section-lede" style="margin-bottom:24px"><?= e($c['faq']['lede']) ?></p>
      <?php render_faq($c['faq']['items'], 'faq--numbered'); ?>
    </div>
    <div style="display:grid;gap:18px">
      <h2 class="section-title" style="font-size:clamp(30px,3.4vw,48px)"><?= e($c['faq']['side_title']) ?></h2>
      <div class="who__screen"><?= poster('7/7', 'faq-side', 'square', ['palette' => 'teal', 'motif' => 'rings', 'meta' => 'Mickey TV · NL & FR']) ?></div>
      <div class="ticket" style="box-shadow:8px 8px 0 var(--red)">
        <h3 class="ticket__title" style="font-size:36px"><?= e($c['faq']['cta_title']) ?></h3>
        <p><?= e($c['faq']['cta_text']) ?></p>
        <p><a class="btn btn--red" href="<?= e(url('contact')) ?>"><?= e($c['faq']['cta_button']) ?></a></p>
      </div>
    </div>
  </div>
</section>

<section class="section reveal" id="blog">
  <div class="container">
    <div class="section-head">
      <div><p class="eyebrow"><?= e(t('blog.eyebrow')) ?></p><h2 class="section-title"><?= e(t('blog.title')) ?></h2><p class="section-lede"><?= e(t('blog.lede')) ?></p></div>
      <a class="section-link" href="<?= e(url('blog')) ?>"><?= e(t('blog.all', count($allPosts))) ?> →</a>
    </div>
    <div class="chips chips--wrap" data-blog-filters style="margin-bottom:22px">
      <button type="button" class="chip" aria-pressed="true" data-topic=""><?= e(t('blog.filter_all')) ?> <span class="muted"><?= count($allPosts) ?></span></button>
      <?php foreach ($topics as $tp => $n): ?><button type="button" class="chip" aria-pressed="false" data-topic="<?= e($tp) ?>"><?= e($tp) ?> <span class="muted"><?= $n ?></span></button><?php endforeach; ?>
    </div>
    <div class="title-grid title-grid--wide" data-blog-grid data-step="6" data-visible="7">
      <?php foreach ($allPosts as $i => $p): ?>
      <div class="blog-item" data-topic="<?= e($p['topic']) ?>"<?= $i >= 7 ? ' hidden' : '' ?>><?php render_post_card($p, 'wide', $i === 0); ?></div>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:22px;display:flex;align-items:center;gap:16px"><button type="button" class="btn btn--ghost" data-blog-more><?= e(t('blog.more')) ?></button><span class="muted" data-blog-count></span></p>
  </div>
</section>

<section class="section band--maroon reveal">
  <div class="container grid-2" style="align-items:center">
    <div>
      <h2 class="section-title"><?= $c['guarantee']['title'] ?></h2>
      <p class="section-lede" style="color:var(--cream)"><?= e($c['guarantee']['text']) ?></p>
      <p style="margin-top:22px"><a class="btn" href="<?= e(url('refund')) ?>"><?= e($c['guarantee']['button']) ?></a></p>
    </div>
    <div class="guarantee-badge" aria-hidden="true">
      <svg viewBox="0 0 200 200" width="240" height="240"><circle cx="100" cy="100" r="92" fill="#fee2a8" stroke="#000" stroke-width="5"/><circle cx="100" cy="100" r="74" fill="none" stroke="#c4252d" stroke-width="4" stroke-dasharray="6 6"/><text x="100" y="92" text-anchor="middle" font-family="Anton, Impact, sans-serif" font-size="46" fill="#c4252d">7/15</text><text x="100" y="124" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="14" letter-spacing="2" fill="#000"><?= e(mb_strtoupper(lang() === 'fr' ? 'JOURS' : 'DAGEN')) ?></text><text x="100" y="146" text-anchor="middle" font-family="Inter, sans-serif" font-weight="800" font-size="11" letter-spacing="1" fill="#000" textLength="<?= lang() === 'fr' ? 140 : 96 ?>" lengthAdjust="spacingAndGlyphs"><?= e(mb_strtoupper(lang() === 'fr' ? 'SATISFAIT OU REMBOURSÉ' : 'GELD TERUG')) ?></text></svg>
    </div>
  </div>
</section>

<?php render_cta_band(); ?>
<div class="grain" aria-hidden="true"></div>

<?php page_close(['scripts' => ['/assets/js/tv.js', '/assets/js/pricing.js', '/assets/js/channel-check.js']]); ?>
