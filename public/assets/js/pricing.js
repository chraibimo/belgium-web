/* Plan tickets: term / device selection, live EUR prices, promo countdown
   (promo.session_seconds in data.php: a fixed start number per browser session,
   otherwise the real recurring round), checkout links from the Google
   Sheet (key "plan-months-devices" -> https URL on an allowed host) with a
   WhatsApp order fallback when no link exists, Meta InitiateCheckout via
   pixel + server CAPI when the pixel is configured and consent was given. */
(function () {
  'use strict';
  const root = document.querySelector('[data-pricing]');
  if (!root) return;
  const MTV = window.MTV || {};
  const T = MTV.t || {};
  const store = MTV.store;
  const cfg = JSON.parse(root.querySelector('[data-pricing-config]').textContent);
  const lang = MTV.lang || 'nl';
  const money = (v) => '€ ' + Number(v).toLocaleString(lang === 'fr' ? 'fr-BE' : 'nl-BE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  const fmt = (s, ...a) => String(s).replace(/%[sd]/g, () => a.shift());

  /* ---------- countdown ---------- */
  const timerBox = root.querySelector('[data-timer]');
  if (timerBox) {
    const end = parseInt(timerBox.dataset.timer, 10) * 1000;
    const span = (parseInt(timerBox.dataset.timerSession || '0', 10) || 0) * 1000;
    const cells = { d: timerBox.querySelector('[data-t="d"]'), h: timerBox.querySelector('[data-t="h"]'), m: timerBox.querySelector('[data-t="m"]'), s: timerBox.querySelector('[data-t="s"]') };
    /* Session countdown: it starts at the full number the first time this tab
       sees it and keeps running across pages; a new session starts it over. */
    const K = 'mtv:promo:t0';
    let t0 = 0;
    if (span > 0) {
      let saved = 0;
      try { saved = parseInt(sessionStorage.getItem(K) || '0', 10); } catch (_) {}
      t0 = (saved > 0 && saved <= Date.now()) ? saved : Date.now();
      try { sessionStorage.setItem(K, String(t0)); } catch (_) {}
    }
    const tick = () => {
      // The session timer wraps instead of sitting at zero; the real round ends.
      let left = span > 0 ? Math.ceil((span - ((Date.now() - t0) % span)) / 1000)
                          : Math.max(0, Math.floor((end - Date.now()) / 1000));
      cells.d.textContent = Math.floor(left / 86400); left %= 86400;
      cells.h.textContent = String(Math.floor(left / 3600)).padStart(2, '0'); left %= 3600;
      cells.m.textContent = String(Math.floor(left / 60)).padStart(2, '0');
      cells.s.textContent = String(left % 60).padStart(2, '0');
      if (span <= 0 && end - Date.now() <= 0) setTimeout(() => location.reload(), 4000);   // next round starts server-side
    };
    tick(); setInterval(tick, 1000);
  }

  /* ---------- checkout links ---------- */
  const ALLOWED = ['myshopify.com', 'shopify.com', 'shop.app', 'mollie.com', 'stripe.com', 'paypal.com', location.hostname];
  const isAllowed = (raw) => { try { const u = new URL(raw); return u.protocol === 'https:' && !u.username && ALLOWED.some((d) => u.hostname === d || u.hostname.endsWith('.' + d)); } catch (_) { return false; } };
  let LINKS = {};
  const CK = 'mtv:links:v1';
  async function loadLinks() {
    if (!cfg.sheet) return;
    try {
      const r = await fetch(cfg.sheet, { cache: 'no-store', referrerPolicy: 'no-referrer' });
      if (!r.ok) throw new Error('sheet ' + r.status);
      const text = await r.text();
      const json = JSON.parse(text.substring(text.indexOf('{'), text.lastIndexOf('}') + 1));
      const map = {};
      (json.table.rows || []).forEach((row) => {
        const k = String((row.c && row.c[0] && row.c[0].v) || '').trim().toLowerCase();
        const u = String((row.c && row.c[1] && row.c[1].v) || '').trim();
        if (k && k !== 'key' && isAllowed(u)) map[k] = u;
      });
      LINKS = map;
      if (Object.keys(map).length) store.set(CK, JSON.stringify(map)); else store.del(CK);
      return;
    } catch (_) {}
    try { LINKS = JSON.parse(store.get(CK) || '{}'); } catch (_) { LINKS = {}; }
  }
  if (document.readyState === 'complete') setTimeout(loadLinks, 0); else window.addEventListener('load', () => setTimeout(loadLinks, 0), { once: true });

  /* ---------- tracking ---------- */
  function visitorId() { let v = store.get('mtv:eid'); if (!v) { v = 'mtv_' + Date.now().toString(36) + Math.random().toString(36).slice(2, 10); store.set('mtv:eid', v); } return v; }
  function cookie(name) { const m = document.cookie.match('(?:^|; )' + name + '=([^;]*)'); return m ? decodeURIComponent(m[1]) : ''; }
  function track(eventName, custom) {
    if (!MTV.pixel || store.get('mtv:consent') !== 'all') return '';
    const eventId = 'ev_' + Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
    try { if (window.fbq) window.fbq('track', eventName, custom, { eventID: eventId }); } catch (_) {}
    try {
      const body = JSON.stringify({ pixel_id: MTV.pixel, event_name: eventName, event_id: eventId, event_time: Math.floor(Date.now() / 1000), event_source_url: location.href, action_source: 'website',
        user_data: { external_id: visitorId(), fbp: cookie('_fbp'), fbc: cookie('_fbc'), client_user_agent: navigator.userAgent }, custom_data: custom });
      fetch('/api/hook.php?t=capi', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body, keepalive: true }).catch(() => {});
    } catch (_) {}
    return eventId;
  }
  if (cfg.mode === 'full') setTimeout(() => track('ViewContent', { content_name: 'Pricing Page', content_type: 'product_group' }), 600);

  /* ---------- tickets ---------- */
  const price = (plan, m, d) => (cfg.prices[plan] && cfg.prices[plan][m] && cfg.prices[plan][m][d]) || null;

  root.querySelectorAll('.plan').forEach((card) => {
    const plan = card.dataset.plan;
    const seg = card.querySelectorAll('.seg [data-months]');
    const selM = card.querySelector('[data-select="months"]');
    const selD = card.querySelector('[data-select="devices"]');
    const cta = card.querySelector('[data-cta]');
    const el = { price: card.querySelector('[data-price]'), per: card.querySelector('[data-per]'), was: card.querySelector('[data-was]'), save: card.querySelector('[data-save]'), term: card.querySelector('[data-line="term"]'), dev: card.querySelector('[data-line="devices"]') };
    const state = { months: seg.length ? parseInt(Array.from(seg).find((b) => b.getAttribute('aria-pressed') === 'true').dataset.months, 10) : parseInt(selM.value, 10), devices: selD ? parseInt(selD.value, 10) || 1 : 1 };

    function render() {
      const total = price(plan, state.months, state.devices);
      const termLabel = state.months === 1 ? T.month_1 : fmt(T.months_n, state.months);
      const devLabel = state.devices === 1 ? T.device_1 : fmt(T.devices_n, state.devices);
      if (el.term) el.term.textContent = state.months === 1 ? T.month_sub : fmt(T.months_sub, state.months);
      if (el.dev) el.dev.textContent = fmt(T.devices_line, devLabel);
      if (total === null) {
        el.price.textContent = '—'; el.per.textContent = ''; el.save.textContent = ''; el.was.hidden = true;
        cta.dataset.available = '0'; cta.textContent = T.contact_us;
        return;
      }
      el.price.textContent = money(total);
      el.per.textContent = fmt(T.per_month, money(Math.ceil((total / state.months / state.devices) * 100) / 100));
      const monthly = price(plan, 1, state.devices);
      const saving = monthly && state.months > 1 ? Math.round((1 - total / (monthly * state.months)) * 100) : 0;
      el.save.textContent = saving > 0 ? fmt(T.save, saving) : '';
      if (cfg.reference && cfg.reference > 0 && cfg.reference < 1) { el.was.hidden = false; el.was.innerHTML = '<s>' + money(total / cfg.reference) + '</s>'; } else el.was.hidden = true;
      cta.dataset.available = '1';
      cta.textContent = cfg.sheet ? T.order : T.order_wa;
      cta.dataset.term = termLabel; cta.dataset.devices = devLabel; cta.dataset.total = money(total); cta.dataset.value = total;
    }
    seg.forEach((b) => b.addEventListener('click', () => { seg.forEach((x) => x.setAttribute('aria-pressed', 'false')); b.setAttribute('aria-pressed', 'true'); state.months = parseInt(b.dataset.months, 10); render(); }));
    if (selM) selM.addEventListener('change', () => { state.months = parseInt(selM.value, 10); render(); });
    if (selD) selD.addEventListener('change', () => { state.devices = parseInt(selD.value, 10) || 1; render(); });
    render();

    /* the order button */
    ['auxclick', 'contextmenu'].forEach((ev) => cta.addEventListener(ev, (e) => e.preventDefault()));
    cta.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); cta.click(); } });
    cta.addEventListener('click', async (e) => {
      e.preventDefault();
      if (cta.dataset.available === '0' || cta.dataset.busy === '1') return;
      const key = plan + '-' + state.months + '-' + state.devices;
      const value = parseFloat(cta.dataset.value) || 0;
      const custom = { content_name: cfg.names[plan] || plan, content_ids: [key], content_type: 'product', num_items: 1, value, currency: cfg.currency, content_category: 'Digital Subscription' };
      let url = LINKS[key];
      if (!url && cfg.sheet) {
        cta.dataset.busy = '1'; const prev = cta.textContent; cta.textContent = T.loading;
        await loadLinks(); url = LINKS[key];
        cta.textContent = prev; cta.dataset.busy = '0';
      }
      track('InitiateCheckout', custom);
      if (url && isAllowed(url)) {
        try { const u = new URL(url); if (/\/cart\//.test(u.pathname)) { u.searchParams.set('attributes[external_id]', visitorId()); url = u.toString(); } } catch (_) {}
        setTimeout(() => {
          const a = document.createElement('a'); a.href = url; a.rel = 'noreferrer noopener nofollow'; a.referrerPolicy = 'no-referrer'; a.style.display = 'none';
          document.body.appendChild(a); a.click(); setTimeout(() => a.remove(), 2000);
        }, 300);
        return;
      }
      // No checkout link for this combination yet: order by WhatsApp with the selection pre-filled.
      const msg = String(T.order_msg).replace('%plan', cfg.names[plan] || plan).replace('%term', cta.dataset.term).replace('%devices', cta.dataset.devices).replace('%total', cta.dataset.total);
      window.open(MTV.whatsapp + '?text=' + encodeURIComponent(msg), '_blank', 'noopener');
    });
  });
})();
