/* MICKEY TV — shared behaviours: menu, sticky header, reveals, counters,
   forms, cookie consent + third-party loader, blog filters, live search,
   copy buttons, language cookie. Everything degrades: no JS = static page. */
(function () {
  'use strict';
  const MTV = window.MTV || {};
  const T = MTV.t || {};
  const $ = (s, r) => (r || document).querySelector(s);
  const $$ = (s, r) => Array.from((r || document).querySelectorAll(s));
  const fmt = (s, ...a) => String(s).replace(/%[sd]/g, () => a.shift());
  const store = {
    get(k) { try { return localStorage.getItem(k); } catch (_) { return null; } },
    set(k, v) { try { localStorage.setItem(k, v); } catch (_) {} },
    del(k) { try { localStorage.removeItem(k); } catch (_) {} },
  };
  window.MTV.store = store;
  window.MTV.fmt = fmt;

  /* ---------- toasts (used by copy buttons, wheel, activity feed) ---------- */
  let toastBox;
  function toast(html, opts) {
    opts = opts || {};
    if (!toastBox) { toastBox = document.createElement('div'); toastBox.className = 'toasts'; toastBox.setAttribute('aria-live', 'polite'); document.body.appendChild(toastBox); }
    const el = document.createElement('div');
    el.className = 'toast' + (opts.simple ? ' toast--simple' : '') + (opts.cls ? ' ' + opts.cls : '');
    el.innerHTML = html;
    toastBox.appendChild(el);
    while (toastBox.children.length > 3) toastBox.firstChild.remove();
    // The timer hangs off the element so a caller can pause it (hover) or reset it.
    el._t = setTimeout(() => el.remove(), opts.ms || 2800);
    return el;
  }
  window.MTV.toast = toast;

  /* ---------- header ---------- */
  const header = $('#header');
  const burger = $('.header__burger');
  const menu = $('#menu');
  /* A sentinel at the top says when the page has scrolled: reading window.scrollY in a scroll
     handler forces a layout of the whole page on every event. */
  if (header) {
    const spy = document.createElement('div');
    spy.className = 'scroll-spy';
    spy.setAttribute('aria-hidden', 'true');
    document.body.prepend(spy);
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(([e]) => header.classList.toggle('is-scrolled', !e.isIntersecting)).observe(spy);
    } else {
      const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 24);
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }
  }
  if (burger && menu) {
    burger.addEventListener('click', () => {
      const open = burger.getAttribute('aria-expanded') !== 'true';
      burger.setAttribute('aria-expanded', String(open));
      menu.hidden = !open;
      document.body.classList.toggle('menu-open', open);
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !menu.hidden) burger.click(); });
  }
  // The visible language pick wins over the browser-language guess on "/".
  $$('[data-lang-pick]').forEach((a) => a.addEventListener('click', () => {
    document.cookie = 'mtv_lang=' + a.dataset.langPick + ';path=/;max-age=31536000;samesite=lax';
  }));

  /* ---------- reveal on scroll ---------- */
  const reveals = $$('.reveal');
  if (reveals.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px' });
    reveals.forEach((el) => io.observe(el));
  } else reveals.forEach((el) => el.classList.add('is-in'));

  /* ---------- counters ---------- */
  const lang = MTV.lang || 'nl';
  const nf = (n, dec) => n.toLocaleString(lang === 'fr' ? 'fr-BE' : 'nl-BE', { minimumFractionDigits: dec, maximumFractionDigits: dec });
  const counters = $$('[data-count]');
  if (counters.length && 'IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (!en.isIntersecting) return;
        io.unobserve(en.target);
        const el = en.target, to = parseFloat(el.dataset.count), dec = String(to).includes('.') ? 1 : 0, suf = el.dataset.suffix || '';
        const t0 = performance.now(), dur = 1800;
        const step = (now) => {
          const k = Math.min(1, (now - t0) / dur), ease = 1 - Math.pow(1 - k, 3);
          el.textContent = nf(to * ease, dec) + suf;
          if (k < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      });
    }, { threshold: 0.4 });
    counters.forEach((el) => io.observe(el));
  }
  // Compare-table bars grow in
  $$('.compare .bar > span').forEach((b) => { b.style.transform = 'scaleX(0)'; });
  const bars = $('.compare');
  if (bars && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => { entries.forEach((en) => { if (en.isIntersecting) { $$('.bar > span', en.target).forEach((b) => { b.style.transform = ''; }); io.disconnect(); } }); }, { threshold: 0.3 });
    io.observe(bars);
  }

  /* ---------- forms (contact, newsletter, lead) ---------- */
  $$('form.js-form').forEach((form) => {
    const msg = $('.form__msg', form);
    const submit = $('[type="submit"]', form);
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      $$('.field', form).forEach((f) => { f.classList.remove('is-error'); const er = $('.field__error', f); if (er) er.textContent = ''; });
      if (msg) { msg.textContent = T.sending || '…'; msg.className = 'form__msg'; }
      if (submit) submit.disabled = true;
      const fd = new FormData(form);
      fd.set('page', location.href);
      try {
        const ctrl = new AbortController();
        const timer = setTimeout(() => ctrl.abort(), 25000);
        const r = await fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'fetch' }, signal: ctrl.signal });
        clearTimeout(timer);
        const j = await r.json();
        if (j.ok) {
          form.dispatchEvent(new CustomEvent('mtv:sent', { detail: j }));
          if (msg) { msg.textContent = j.message; msg.classList.add('is-ok'); }
          form.reset();
          if (j.redirect) setTimeout(() => location.assign(j.redirect), 900);
        } else {
          if (msg) { msg.textContent = j.message; msg.classList.add('is-error'); }
          Object.entries(j.errors || {}).forEach(([name, text]) => {
            const input = form.elements[name];
            const field = input && input.closest('.field');
            if (field) { field.classList.add('is-error'); const er = $('.field__error', field); if (er) er.textContent = text; }
          });
        }
      } catch (_) {
        if (msg) { msg.textContent = T.network || 'Error'; msg.classList.add('is-error'); }
      } finally { if (submit) submit.disabled = false; }
    });
  });
  // A "started" stamp lets the server drop instant (bot) submissions.
  $$('input[name="started"]').forEach((i) => { i.value = String(Date.now()); });

  /* ---------- copy buttons ---------- */
  document.addEventListener('click', async (e) => {
    const b = e.target.closest('[data-copy]');
    if (!b) return;
    const text = b.dataset.copy || (b.previousElementSibling && b.previousElementSibling.textContent) || '';
    try { await navigator.clipboard.writeText(text.trim()); toast(T.copied || 'OK', { simple: true, ms: 1600 }); } catch (_) {}
  });

  /* ---------- cookie consent + third-party loader ---------- */
  const consent = $('#consent');
  const consentKey = 'mtv:consent';
  /* Live chat is our own support channel, so it loads on either consent answer;
     the marketing tags need an explicit "accept all". */
  function loadChat() {
    if (!store.get(consentKey)) return;
    if (MTV.tawk && !window.Tawk_API) {
      window.Tawk_API = window.Tawk_API || {}; window.Tawk_LoadStart = new Date();
      /* Pin the panel bottom-right, clear of the wheel dock and the toasts in
         the other corner, whatever the tawk dashboard is set to. */
      window.Tawk_API.customStyle = {
        visibility: {
          desktop: { position: 'br', xOffset: 20, yOffset: 20 },
          mobile:  { position: 'br', xOffset: 14, yOffset: 14 },
        },
      };
      /* tawk's bubble lives in a cross-origin iframe and cannot be restyled, so
         it is hidden and #chatbtn (ours, in the site's own style) drives it.
         onBeforeLoad runs BEFORE tawk draws anything, so its own bubble never
         flashes on screen; .tawk-min-container in site.css is the safety net. */
      window.Tawk_API.onBeforeLoad = function () { try { window.Tawk_API.hideWidget(); } catch (_) {} };
      const btn = $('#chatbtn');
      if (btn) {
        const dot = $('[data-chat-dot]', btn), badge = $('[data-chat-unread]', btn);
        const api = () => window.Tawk_API || {};
        const hideBubble = () => { try { api().hideWidget(); } catch (_) {} };
        const status = () => {
          try { if (dot) dot.hidden = api().getStatus && api().getStatus() !== 'online'; } catch (_) {}
        };
        window.Tawk_API.onLoad = function () { hideBubble(); btn.hidden = false; status(); };
        window.Tawk_API.onStatusChange = status;
        window.Tawk_API.onChatMaximized = function () { btn.hidden = true; };
        window.Tawk_API.onChatMinimized = function () { hideBubble(); btn.hidden = false; };
        window.Tawk_API.onChatHidden = function () { btn.hidden = false; };
        window.Tawk_API.onUnreadCountChanged = function (n) {
          if (!badge) return;
          badge.textContent = n > 9 ? '9+' : String(n);
          badge.hidden = !n;
        };
        btn.addEventListener('click', () => {
          try { api().maximize(); } catch (_) {}
          // Some tawk builds refuse to maximize while the widget is hidden:
          // show it and try once more (the panel covers the bubble anyway).
          setTimeout(() => {
            try {
              if (api().isChatMaximized && !api().isChatMaximized()) { api().showWidget(); api().maximize(); }
            } catch (_) {}
          }, 400);
        });
      }
      const s = document.createElement('script'); s.async = true; s.src = 'https://embed.tawk.to/' + MTV.tawk[0] + '/' + MTV.tawk[1]; s.charset = 'UTF-8'; s.setAttribute('crossorigin', '*');
      document.body.appendChild(s);
    }
  }
  function loadThirdParty() {
    loadChat();
    if (store.get(consentKey) !== 'all') return;
    if (MTV.pixel && !window.fbq) {
      /* Meta pixel base code */
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
      window.fbq('init', MTV.pixel);
      window.fbq('track', 'PageView');
    }
  }
  if (consent) {
    if (!store.get(consentKey)) consent.hidden = false;
    $$('[data-consent]', consent).forEach((b) => b.addEventListener('click', () => {
      store.set(consentKey, b.dataset.consent);
      consent.hidden = true;
      loadThirdParty();
      // The activity toasts share the bottom of the screen with this banner.
      document.dispatchEvent(new CustomEvent('mtv:consent', { detail: b.dataset.consent }));
    }));
    $$('[data-consent-open]').forEach((b) => b.addEventListener('click', () => { consent.hidden = false; }));
    // Marketing tags load on the first real gesture, never inside a speed test.
    const gesture = () => { loadThirdParty(); ['pointerdown', 'keydown', 'touchstart', 'wheel'].forEach((ev) => window.removeEventListener(ev, gesture)); };
    ['pointerdown', 'keydown', 'touchstart', 'wheel'].forEach((ev) => window.addEventListener(ev, gesture, { passive: true, once: true }));
    // ...but the chat button has to be there for someone who only reads, so it
    // also comes up on its own a few seconds after the page has loaded.
    addEventListener('load', () => setTimeout(loadChat, 6000));
  } else {
    $$('[data-consent-open]').forEach((b) => { b.hidden = true; });
  }

  /* ---------- blog filters / show more ---------- */
  const grid = $('[data-blog-grid]');
  if (grid) {
    const items = $$('.blog-item', grid);
    const filters = $$('[data-blog-filters] .chip');
    const more = $('[data-blog-more]');
    const count = $('[data-blog-count]');
    const step = parseInt(grid.dataset.step || '6', 10);
    let topic = '', visible = parseInt(grid.dataset.visible || '7', 10);
    function render() {
      const pool = items.filter((it) => !topic || it.dataset.topic === topic);
      items.forEach((it) => { it.hidden = true; });
      pool.slice(0, visible).forEach((it) => { it.hidden = false; });
      if (more) more.hidden = pool.length <= visible;
      if (count) count.textContent = pool.length ? fmt(T.showing || '%d / %d', Math.min(visible, pool.length), pool.length) : (T.blog_empty || '');
    }
    filters.forEach((b) => b.addEventListener('click', () => {
      filters.forEach((x) => x.setAttribute('aria-pressed', 'false'));
      b.setAttribute('aria-pressed', 'true');
      topic = b.dataset.topic || ''; visible = parseInt(grid.dataset.visible || '7', 10); render();
    }));
    if (more) more.addEventListener('click', () => { visible += step; render(); });
    render();
  }

  /* ---------- live search (blog sidebar, search page) ---------- */
  $$('[data-live-search]').forEach((wrap) => {
    const input = $('input', wrap);
    const list = document.createElement('div');
    list.className = 'live-search__list';
    wrap.appendChild(list);
    let timer, ctrl;
    input.addEventListener('input', () => {
      clearTimeout(timer);
      const q = input.value.trim();
      if (q.length < 2) { list.innerHTML = ''; return; }
      timer = setTimeout(async () => {
        if (ctrl) ctrl.abort();
        ctrl = new AbortController();
        try {
          const r = await fetch('/api/search.php?lang=' + encodeURIComponent(lang) + '&q=' + encodeURIComponent(q), { signal: ctrl.signal });
          const j = await r.json();
          list.innerHTML = (j.results || []).map((h) => '<a href="' + h.url + '"><b>' + esc(h.title) + '</b><span>' + esc(h.excerpt) + '</span></a>').join('');
        } catch (_) {}
      }, 300);
    });
    input.addEventListener('blur', () => setTimeout(() => { list.innerHTML = ''; }, 200));
  });
  function esc(s) { return String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c])); }
  window.MTV.esc = esc;

  /* ---------- tabs ---------- */
  $$('[data-tabs]').forEach((box) => {
    const tabs = $$('[role="tab"]', box), panels = $$('[role="tabpanel"]', box);
    tabs.forEach((tab, i) => tab.addEventListener('click', () => {
      tabs.forEach((x) => x.setAttribute('aria-selected', 'false'));
      tab.setAttribute('aria-selected', 'true');
      panels.forEach((p, j) => { p.hidden = i !== j; });
    }));
  });

  /* ---------- legal TOC scroll-spy ---------- */
  const toc = $('.legal__toc');
  if (toc && 'IntersectionObserver' in window) {
    const links = $$('a', toc);
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) links.forEach((a) => a.classList.toggle('is-active', a.getAttribute('href') === '#' + en.target.id)); });
    }, { rootMargin: '-20% 0px -70% 0px' });
    links.forEach((a) => { const sec = $(a.getAttribute('href')); if (sec) io.observe(sec); });
  }

  /* ---------- static noise canvases (404) ---------- */
  $$('[data-noise]').forEach((cv) => {
    const ctx = cv.getContext('2d'), img = ctx.createImageData(cv.width, cv.height), px = new Uint32Array(img.data.buffer);
    const still = matchMedia('(prefers-reduced-motion: reduce)').matches;
    (function draw() { for (let i = 0; i < px.length; i++) { const v = (Math.random() * 255) | 0; px[i] = 0xff000000 | (v << 16) | (v << 8) | v; } ctx.putImageData(img, 0, 0); if (!still) requestAnimationFrame(draw); })();
  });
})();
