<?php
/**
 * Page chrome: <head>, header, footer, cookie consent, promo wheel, scripts.
 * Templates call page_open([...]) then page_close([...]).
 */
if (!defined('PRIMO_APP')) exit;

/** Versioned asset URL so browsers refetch after an upload. */
function asset(string $path): string {
    $t = (int)@filemtime(DOCROOT . $path);
    return $path . ($t ? '?v=' . $t : '');
}

/** hreflang alternates for the current page: lang => absolute URL. */
function page_alternates(string $key, ?array $post): array {
    $origin = current_origin();
    $alts = [];
    if ($post) {
        foreach (SITE_LANGS as $l) {
            $twin = post_by_key($post['key'], $l);
            if ($twin) $alts[$l] = $origin . post_url($twin, $l);
        }
    } elseif (isset(routes()[$key])) {
        foreach (SITE_LANGS as $l) $alts[$l] = $origin . url($key, $l);
    }
    return $alts;
}

function nav_items(): array {
    return [
        ['key' => 'home',     'label' => t('nav.home')],
        ['key' => 'pricing',  'label' => t('nav.pricing')],
        ['key' => 'channels', 'label' => t('nav.channels')],
        ['key' => 'tutorial', 'label' => t('nav.tutorial'), 'children' => [
            ['href' => url('tutorial'), 'label' => t('nav.tutorial_all')],
            ['post' => 'iptv-smart-tv',   'label' => t('nav.tutorial_smarttv')],
            ['post' => 'iptv-firestick',  'label' => t('nav.tutorial_firestick')],
            ['post' => 'iptv-tv-box',     'label' => t('nav.tutorial_box')],
            ['post' => 'iptv-pc',         'label' => t('nav.tutorial_pc')],
        ]],
        ['key' => 'blog',     'label' => t('nav.blog')],
        ['key' => 'contact',  'label' => t('nav.contact')],
    ];
}

/**
 * $o: title, description, key (route key or 'post'/'tag'/'404'), post, body_class,
 *     noindex, jsonld (array of extra graphs), og_type, theme ('red' hero pages)
 */
function page_open(array $o): void {
    $d       = site_data();
    $brand   = $d['brand']['name'];
    $key     = $o['key'] ?? ($GLOBALS['page'] ?? 'home');
    $post    = $o['post'] ?? null;
    $title   = $o['title'] ?? $brand;
    $full    = ($o['full_title'] ?? false) ? $title : ($key === 'home' ? $title : $title . ' – ' . $brand);
    $desc    = $o['description'] ?? t('site.description');
    $origin  = current_origin();
    $path    = $_SERVER['REQUEST_URI'] ?? '/';
    $path    = parse_url($path, PHP_URL_PATH) ?: '/';
    $canon   = $origin . $path;
    $alts    = page_alternates($key, $post);
    $noindex = !empty($o['noindex']) || !empty(routes()[$key]['noindex']);
    $body    = trim('page-' . $key . ' ' . ($o['body_class'] ?? ''));
    $ogImage = $origin . '/assets/img/og-card.png';

    $graph = [
        ['@type' => 'Organization', '@id' => $origin . '/#org', 'name' => $brand, 'url' => $origin . '/',
         'logo' => $origin . '/assets/img/logo-mark.svg', 'email' => $d['brand']['email'], 'telephone' => $d['brand']['phone_tel'],
         'contactPoint' => ['@type' => 'ContactPoint', 'contactType' => 'customer support', 'availableLanguage' => ['nl', 'fr'], 'url' => $origin . url('contact')]],
        ['@type' => 'WebSite', '@id' => $origin . '/#site', 'name' => $brand, 'url' => $origin . '/', 'inLanguage' => locale(),
         'potentialAction' => ['@type' => 'SearchAction', 'target' => $origin . url('search') . '?q={search_term_string}', 'query-input' => 'required name=search_term_string']],
    ];
    if ($key !== 'home') {
        $graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => t('nav.home'), 'item' => $origin . url('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $title, 'item' => $canon],
        ]];
    }
    foreach ($o['jsonld'] ?? [] as $g) $graph[] = $g;
    ?>
<!doctype html>
<html lang="<?= locale() ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($full) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<?php if ($noindex): ?><meta name="robots" content="noindex, follow"><?php endif; ?>
<link rel="canonical" href="<?= e($canon) ?>">
<?php foreach ($alts as $l => $href): ?>
<link rel="alternate" hreflang="<?= locale($l) ?>" href="<?= e($href) ?>">
<?php endforeach; if (isset($alts[DEFAULT_LANG])): ?>
<link rel="alternate" hreflang="x-default" href="<?= e($alts[DEFAULT_LANG]) ?>">
<?php endif; ?>
<meta name="theme-color" content="#c4252d">
<meta property="og:type" content="<?= e($o['og_type'] ?? 'website') ?>">
<meta property="og:site_name" content="<?= e($brand) ?>">
<meta property="og:locale" content="<?= og_locale() ?>">
<meta property="og:title" content="<?= e($full) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:url" content="<?= e($canon) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="preload" href="/assets/fonts/anton-latin-400.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/inter-latin-wght.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('/assets/css/site.css')) ?>">
<script type="application/ld+json"><?= json_attr(['@context' => 'https://schema.org', '@graph' => $graph]) ?></script>
<script>document.documentElement.classList.add('js');</script>
</head>
<body class="<?= e($body) ?>" data-lang="<?= lang() ?>" data-brand="<?= e($brand) ?>">
<a class="skip-link btn btn--sm" href="#main"><?= e(t('a11y.skip')) ?></a>
<?php render_header($key); ?>
<main id="main">
<?php
}

function render_header(string $key): void {
    $d = site_data();
    $items = nav_items();
    $post  = $GLOBALS['post'] ?? null;
    ?>
<header class="header" id="header">
  <button type="button" class="header__burger" aria-expanded="false" aria-controls="menu" aria-label="<?= e(t('a11y.menu')) ?>">
    <span></span><span></span><span></span>
  </button>
  <nav class="header__nav" aria-label="<?= e(t('a11y.main_nav')) ?>">
    <?php foreach ($items as $it): $active = ($GLOBALS['page'] ?? '') === $it['key'] || (isset($it['children']) && ($GLOBALS['page'] ?? '') === 'post' && in_array($post['key'] ?? '', array_column($it['children'], 'post'), true)); ?>
      <?php if (isset($it['children'])): ?>
      <div class="header__item has-sub">
        <a href="<?= e(url($it['key'])) ?>" class="header__link<?= $active ? ' is-active' : '' ?>" aria-haspopup="true"><?= e($it['label']) ?> <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></a>
        <div class="header__sub">
          <?php foreach ($it['children'] as $c): $href = $c['href'] ?? (($p = post_by_key($c['post'])) ? post_url($p) : url('tutorial')); ?>
          <a href="<?= e($href) ?>"><?= e($c['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <a href="<?= e(url($it['key'])) ?>" class="header__link<?= $active ? ' is-active' : '' ?>"><?= e($it['label']) ?></a>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>
  <a href="<?= e(url('home')) ?>" class="header__brand">
    <svg class="header__mark" viewBox="0 0 64 64" aria-hidden="true"><use href="/assets/img/logo-mark.svg#mark"/></svg>
    <span>MICKEY<b>TV</b></span>
  </a>
  <div class="header__actions">
    <a href="<?= e(url('search')) ?>" class="header__icon" aria-label="<?= e(t('nav.search')) ?>">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    </a>
    <?php render_lang_switch(); ?>
    <a href="<?= e($d['brand']['whatsapp'] . '?text=' . rawurlencode(t('cta.trial_message'))) ?>" class="btn btn--sm header__cta" target="_blank" rel="noopener"><?= e(t('cta.free_trial')) ?></a>
  </div>
</header>
<div class="menu" id="menu" hidden>
  <div class="menu__inner">
    <nav class="menu__nav" aria-label="<?= e(t('a11y.main_nav')) ?>">
      <?php foreach ($items as $i => $it): ?>
      <a href="<?= e(url($it['key'])) ?>" class="menu__link display" style="--i:<?= $i ?>"><?= e($it['label']) ?></a>
      <?php endforeach; ?>
      <a href="<?= e(url('faqs')) ?>" class="menu__link display" style="--i:6"><?= e(t('nav.faqs')) ?></a>
      <a href="<?= e(url('about')) ?>" class="menu__link display" style="--i:7"><?= e(t('nav.about')) ?></a>
    </nav>
    <div class="menu__foot">
      <a href="<?= e($d['brand']['whatsapp'] . '?text=' . rawurlencode(t('cta.trial_message'))) ?>" class="btn" target="_blank" rel="noopener"><?= e(t('cta.free_trial')) ?></a>
      <?php render_lang_switch('menu'); ?>
    </div>
  </div>
</div>
<?php
}

function render_lang_switch(string $where = 'header'): void {
    $key  = $GLOBALS['page'] ?? 'home';
    $post = $GLOBALS['post'] ?? null;
    echo '<div class="lang lang--' . $where . '" role="group" aria-label="' . e(t('a11y.language')) . '">';   // a label needs a role to hang on
    foreach (SITE_LANGS as $l) {
        if ($post) { $twin = post_by_key($post['key'], $l); $href = $twin ? post_url($twin, $l) : url('blog', $l); }
        elseif ($key === 'tag' && ($GLOBALS['tag'] ?? null)) { $href = url('blog', $l); }
        elseif (isset(routes()[$key])) { $href = url($key, $l); }
        else { $href = url('home', $l); }
        $cur = $l === lang();
        echo '<a href="' . e($href) . '" hreflang="' . $l . '" lang="' . $l . '"' . ($cur ? ' aria-current="true"' : '') . ' data-lang-pick="' . $l . '">' . strtoupper($l) . '</a>';
    }
    echo '</div>';
}

/**
 * $o: scripts (array of /assets/js paths), before_footer (html), no_footer
 */
function page_close(array $o = []): void {
    $d = site_data();
    echo "\n</main>\n";
    if (empty($o['no_footer'])) render_footer();
    render_consent();
    render_chat_launcher();
    render_wheel();
    $cfg = [
        'lang'     => lang(),
        'brand'    => $d['brand']['name'],
        'whatsapp' => $d['brand']['whatsapp'],
        'urls'     => ['pricing' => url('pricing'), 'contact' => url('contact'), 'channels' => url('channels'), 'thankyou' => url('thankyou'), 'search' => url('search')],
        'pixel'    => FB_PIXEL_ID,
        'tawk'     => TAWK_PROPERTY !== '' && TAWK_WIDGET !== '' ? [TAWK_PROPERTY, TAWK_WIDGET] : null,
        'fomoFeed' => FOMO_FEED_URL,
        // Generated activity toasts: only shipped when no real feed is set and
        // FOMO_SYNTHETIC is on (see app/config.php for the legal note).
        'activity' => FOMO_SYNTHETIC && FOMO_FEED_URL === '' ? content('activity') : null,
        'wheel'    => ['codes' => $d['wheel']['codes'], 'hold' => $d['wheel']['hold_minutes']],
        't'        => content('js'),
    ];
    ?>
<script>window.MTV=<?= json_attr($cfg) ?>;</script>
<script src="<?= e(asset('/assets/js/site.js')) ?>" defer></script>
<script src="<?= e(asset('/assets/js/posters.js')) ?>" defer></script>
<script src="<?= e(asset('/assets/js/wheel.js')) ?>" defer></script>
<?php foreach ($o['scripts'] ?? [] as $s): ?>
<script src="<?= e(asset($s)) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
<?php
}

function render_footer(): void {
    $d = site_data();
    $guides = [
        ['href' => url('tutorial'),  'label' => t('nav.tutorial_all')],
        ['post' => 'iptv-firestick', 'label' => t('nav.tutorial_firestick')],
        ['post' => 'iptv-smart-tv',  'label' => t('nav.tutorial_smarttv')],
        ['post' => 'iptv-pc',        'label' => t('nav.tutorial_pc')],
        ['post' => 'iptv-smarters',  'label' => t('nav.tutorial_smarters')],
    ];
    ?>
<footer class="footer band--black">
  <div class="container footer__news">
    <div>
      <p class="eyebrow"><?= e(t('footer.news_eyebrow')) ?></p>
      <h2 class="section-title"><?= e(t('footer.news_title')) ?></h2>
      <p class="muted"><?= e(t('footer.news_lede')) ?></p>
    </div>
    <form class="newsletter js-form" action="/api/forms.php" method="post" novalidate>
      <input type="hidden" name="form" value="newsletter">
      <input type="hidden" name="lang" value="<?= lang() ?>">
      <label class="sr-only" for="nl-email"><?= e(t('footer.news_label')) ?></label>
      <input id="nl-email" type="email" name="email" placeholder="<?= e(t('footer.news_placeholder')) ?>" autocomplete="email" required>
      <button type="submit" class="btn"><?= e(t('footer.news_button')) ?></button>
      <p class="form__msg" role="status" aria-live="polite"></p>
    </form>
  </div>
  <div class="container footer__grid">
    <div class="footer__about">
      <a href="<?= e(url('home')) ?>" class="footer__brand">MICKEY<b>TV</b></a>
      <p><?= e(t('footer.blurb')) ?></p>
      <p class="footer__tally"><span class="footer__dot" aria-hidden="true"></span><?= e(t('footer.support_online')) ?></p>
      <p class="eyebrow footer__pay-label"><?= e(t('footer.we_accept')) ?></p>
      <div class="footer__pay">
        <img src="/assets/img/payment-visa.svg" alt="Visa" width="46" height="30" loading="lazy">
        <img src="/assets/img/payment-mastercard.svg" alt="Mastercard" width="46" height="30" loading="lazy">
        <img src="/assets/img/payment-amex.svg" alt="American Express" width="46" height="30" loading="lazy">
        <span class="footer__pay-text">Bancontact</span>
        <span class="footer__pay-text">Crypto</span>
      </div>
    </div>
    <nav class="footer__col" aria-label="<?= e(t('footer.quick')) ?>">
      <h2 class="eyebrow"><?= e(t('footer.quick')) ?></h2>
      <a href="<?= e(url('home')) ?>"><?= e(t('nav.home')) ?></a>
      <a href="<?= e(url('pricing')) ?>"><?= e(t('nav.pricing')) ?></a>
      <a href="<?= e(url('channels')) ?>"><?= e(t('nav.channels')) ?></a>
      <a href="<?= e(url('blog')) ?>"><?= e(t('nav.blog')) ?></a>
      <a href="<?= e(url('reviews')) ?>"><?= e(t('nav.reviews')) ?></a>
      <a href="<?= e(url('faqs')) ?>"><?= e(t('nav.faqs')) ?></a>
      <a href="<?= e(url('referral')) ?>"><?= e(t('nav.referral')) ?></a>
    </nav>
    <nav class="footer__col" aria-label="<?= e(t('footer.guides')) ?>">
      <h2 class="eyebrow"><?= e(t('footer.guides')) ?></h2>
      <?php foreach ($guides as $g): $href = $g['href'] ?? (($p = post_by_key($g['post'])) ? post_url($p) : url('tutorial')); ?>
      <a href="<?= e($href) ?>"><?= e($g['label']) ?></a>
      <?php endforeach; ?>
    </nav>
    <nav class="footer__col" aria-label="<?= e(t('footer.legal')) ?>">
      <h2 class="eyebrow"><?= e(t('footer.legal')) ?></h2>
      <a href="<?= e(url('terms')) ?>"><?= e(t('nav.terms')) ?></a>
      <a href="<?= e(url('privacy')) ?>"><?= e(t('nav.privacy')) ?></a>
      <a href="<?= e(url('refund')) ?>"><?= e(t('nav.refund')) ?></a>
      <a href="<?= e(url('about')) ?>"><?= e(t('nav.about')) ?></a>
      <button type="button" class="footer__linkbtn" data-consent-open><?= e(t('consent.settings')) ?></button>
    </nav>
    <div class="footer__col">
      <h2 class="eyebrow"><?= e(t('footer.contact')) ?></h2>
      <a href="mailto:<?= e($d['brand']['email']) ?>"><?= e($d['brand']['email']) ?></a>
      <a href="<?= e($d['brand']['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a>
      <a href="tel:<?= e($d['brand']['phone_tel']) ?>"><?= e($d['brand']['phone']) ?></a>
      <a href="<?= e(url('contact')) ?>"><?= e(t('footer.contact_form')) ?></a>
      <p class="footer__hours"><?= e(lang() === 'fr' ? $d['brand']['hours_fr'] : $d['brand']['hours_nl']) ?></p>
    </div>
  </div>
  <div class="footer__word" aria-hidden="true">MICKEY TV</div>
  <div class="container footer__bottom">
    <span>© <?= e($d['brand']['legal']) ?> <?= date('Y') ?>. <?= e(t('footer.rights')) ?></span>
    <span class="footer__disclaimer"><?= e(t('footer.disclaimer')) ?></span>
  </div>
</footer>
<?php
}

/**
 * Our own chat launcher. tawk.to draws its widget inside a cross-origin iframe,
 * so its bubble cannot be restyled from here - instead site.js hides it
 * (Tawk_API.hideWidget) and this button opens the chat (Tawk_API.maximize).
 * It stays hidden until tawk has actually loaded, so it never lies.
 */
function render_chat_launcher(): void {
    if (TAWK_PROPERTY === '' || TAWK_WIDGET === '') return;
    ?>
<button type="button" class="chatbtn" id="chatbtn" hidden aria-label="<?= e(t('chat.open')) ?>">
  <span class="chatbtn__label" aria-hidden="true"><?= e(t('chat.launcher')) ?></span>
  <span class="chatbtn__disc">
    <svg class="chatbtn__mark" viewBox="0 0 64 64" aria-hidden="true"><use href="/assets/img/logo-mark.svg#mark"/></svg>
    <span class="chatbtn__dot" data-chat-dot hidden title="<?= e(t('chat.online')) ?>"></span>
    <span class="chatbtn__badge" data-chat-unread hidden></span>
  </span>
</button>
<?php
}

function render_consent(): void {
    if (FB_PIXEL_ID === '' && TAWK_PROPERTY === '') return;   // only functional cookies: no banner needed
    ?>
<div class="consent" id="consent" hidden role="dialog" aria-labelledby="consent-title" aria-describedby="consent-text">
  <div class="consent__box">
    <p class="eyebrow" id="consent-title"><?= e(t('consent.title')) ?></p>
    <p id="consent-text"><?= e(t('consent.text')) ?> <a href="<?= e(url('privacy')) ?>"><?= e(t('consent.more')) ?></a></p>
    <div class="consent__actions">
      <button type="button" class="btn btn--sm" data-consent="all"><?= e(t('consent.accept')) ?></button>
      <button type="button" class="btn btn--sm btn--ghost" data-consent="essential"><?= e(t('consent.essential')) ?></button>
    </div>
  </div>
</div>
<?php
}

function render_wheel(): void {
    $w = content('wheel');
    if (!$w) return;
    ?>
<div class="wheel" id="wheel" hidden role="dialog" aria-modal="true" aria-labelledby="wheel-title">
  <div class="wheel__backdrop" data-wheel-close></div>
  <div class="wheel__box">
    <button type="button" class="wheel__close" data-wheel-close aria-label="<?= e(t('a11y.close')) ?>">×</button>
    <div class="wheel__left">
      <div class="wheel__disc-wrap">
        <div class="wheel__pointer" aria-hidden="true"></div>
        <svg class="wheel__disc" viewBox="0 0 400 400" aria-hidden="true"></svg>
        <div class="wheel__hub" aria-hidden="true">TV</div>
      </div>
    </div>
    <div class="wheel__right">
      <div class="wheel__step" data-step="gate">
        <p class="eyebrow"><?= e($w['kicker']) ?></p>
        <h2 class="wheel__title display" id="wheel-title"><?= e($w['title']) ?></h2>
        <p class="wheel__sub"><?= $w['sub'] ?></p>
        <label class="wheel__label"><?= e($w['email_label']) ?> <small><?= e($w['optional']) ?></small>
          <input type="email" class="wheel__input" placeholder="<?= e($w['email_placeholder']) ?>" autocomplete="email" data-wheel-email></label>
        <p class="wheel__fine"><?= e($w['consent']) ?></p>
        <button type="button" class="btn btn--red wheel__spin" data-wheel-spin><?= e($w['spin']) ?></button>
        <button type="button" class="wheel__decline" data-wheel-decline><?= e($w['decline']) ?></button>
        <p class="wheel__fine wheel__fine--rules"><?= e($w['rules']) ?></p>
      </div>
      <div class="wheel__step" data-step="prize" hidden>
        <p class="eyebrow"><?= e($w['won']) ?></p>
        <h2 class="wheel__title display" data-prize-title></h2>
        <p class="wheel__sub" data-prize-desc></p>
        <div class="wheel__code"><span data-prize-code></span><button type="button" class="btn btn--sm" data-copy-code><?= e($w['copy']) ?></button></div>
        <p class="wheel__hold"><?= e($w['holds']) ?> <b data-prize-timer>05:00</b></p>
        <a href="<?= e(url('pricing')) ?>" class="btn btn--red" data-prize-cta><?= e($w['claim']) ?></a>
        <p class="wheel__fine"><?= e($w['apply']) ?></p>
      </div>
      <div class="wheel__step" data-step="again" hidden>
        <p class="eyebrow"><?= e($w['again_kicker']) ?></p>
        <h2 class="wheel__title display"><?= e($w['again_title']) ?></h2>
        <p class="wheel__sub"><?= e($w['again_sub']) ?></p>
        <button type="button" class="btn btn--red" data-wheel-spin><?= e($w['again_button']) ?></button>
      </div>
    </div>
  </div>
</div>
<div class="wheel-dock" id="wheel-dock" hidden>
  <span class="wheel-dock__label"><?= e($w['won']) ?></span>
  <b data-dock-prize></b>
  <code data-dock-code></code>
  <button type="button" class="btn btn--sm" data-copy-code><?= e($w['copy']) ?></button>
  <span class="wheel-dock__timer"><?= e($w['expires']) ?> <b data-dock-timer>05:00</b></span>
  <a href="<?= e(url('pricing')) ?>" class="btn btn--sm btn--red"><?= e($w['claim_short']) ?></a>
</div>
<div class="wheel-reopen" id="wheel-reopen" hidden>
  <button type="button" class="wheel-reopen__open" data-wheel-reopen>
    <span class="wheel-reopen__gift" aria-hidden="true">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M3 9h18M5 9v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9"/><path d="M12 9S9.5 3 7 4.5 9.5 9 12 9zM12 9s2.5-6 5-4.5S14.5 9 12 9z"/></svg>
    </span>
    <span class="wheel-reopen__text"><b><?= e($w['reopen']) ?></b><small><?= e($w['reopen_sub']) ?></small></span>
    <span class="wheel-reopen__cta"><?= e($w['reopen_cta']) ?></span>
  </button>
  <button type="button" class="wheel-reopen__x" data-wheel-reopen-hide aria-label="<?= e($w['reopen_hide']) ?>">&times;</button>
</div>
<script type="application/json" id="wheel-data"><?= json_attr($w['slices']) ?></script>
<?php
}

/* ------------------------------------------------------------ shared blocks */

function render_faq(array $items, string $class = ''): void {
    echo '<div class="faq ' . e($class) . '">';
    foreach ($items as $i => $f) {
        echo '<details class="faq__item"' . ($i === 0 ? ' open' : '') . '><summary>' . e($f['q']) . '</summary><div class="faq__body">' . (str_starts_with(trim($f['a']), '<') ? $f['a'] : '<p>' . $f['a'] . '</p>') . '</div></details>';
    }
    echo '</div>';
}

function faq_jsonld(array $items): array {
    return ['@type' => 'FAQPage', 'mainEntity' => array_map(fn($f) => [
        '@type' => 'Question', 'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])],
    ], $items)];
}

function render_cta_band(?string $title = null, ?string $lede = null): void {
    ?>
<section class="band--cream promo">
  <div class="container promo__inner">
    <div>
      <p class="eyebrow"><?= e(t('cta.band_eyebrow')) ?></p>
      <h2 class="promo__title display"><?= $title ?? t('cta.band_title') ?></h2>
    </div>
    <div>
      <p class="promo__lede"><?= e($lede ?? t('cta.band_lede')) ?></p>
      <div class="promo__cta">
        <a href="<?= e(url('pricing')) ?>" class="btn btn--red"><?= e(t('cta.see_plans')) ?> <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <a href="<?= e(site_data()['brand']['whatsapp']) ?>" class="btn btn--ghost btn--ghost-dark" target="_blank" rel="noopener"><?= e(t('cta.whatsapp')) ?></a>
      </div>
    </div>
  </div>
</section>
<?php
}

function render_page_hero(array $o): void {
    ?>
<section class="phero band--red">
  <?php if (!empty($o['doodle'])): ?><svg class="phero__doodle" aria-hidden="true"><use href="/assets/img/doodles.svg#<?= e($o['doodle']) ?>"/></svg><?php endif; ?>
  <div class="container phero__inner">
    <?php if (!empty($o['badge'])): ?><span class="badge phero__badge"><?= e($o['badge']) ?></span><?php endif; ?>
    <h1 class="phero__title display hard-shadow"><?= $o['title'] ?></h1>
    <?php if (!empty($o['lede'])): ?><p class="phero__lede"><?= $o['lede'] ?></p><?php endif; ?>
    <?php if (!empty($o['after'])) echo $o['after']; ?>
  </div>
</section>
<?php
}

function render_post_card(array $p, string $variant = 'portrait', bool $lead = false, int $level = 3): void {
    $meta = date_human($p['date']) . ' · ' . $p['minutes'] . ' min';
    ?>
<article class="tcard tcard--<?= $variant ?><?= $lead ? ' tcard--lead' : '' ?>" data-topic="<?= e($p['topic']) ?>">
  <a href="<?= e(post_url($p)) ?>" class="tcard__link">
    <div class="tcard__frame"><?= poster($p['poster_title'] ?? $p['title'], $p['key'], $variant, $p['poster'] ?? []) ?><span class="tcard__tag"><?= e($p['topic']) ?></span></div>
    <h<?= $level ?> class="tcard__title"><?= e($p['title']) ?></h<?= $level ?>>
    <p class="tcard__meta"><?= e($meta) ?></p>
    <?php if ($lead): ?><p class="tcard__excerpt"><?= e($p['excerpt']) ?></p><?php endif; ?>
  </a>
</article>
<?php
}
