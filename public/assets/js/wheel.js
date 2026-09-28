/* Promo wheel + activity toasts.
   Wheel: opens once per session after the first real gesture (never inside a
   speed test), honest weighted odds from the wedge weights, "again" wedges
   give a second spin, the code holds for N minutes, the winner is posted to
   /api/hook.php?t=wheel (n8n) when an email is given, and a dock keeps the
   code on screen while it is valid. Declining hides it for 7 days.
   Closing it unspun raises the small pill at the top of the page (#wheel-reopen)
   which reopens the wheel; it follows the visitor from page to page for the
   rest of the session until they spin or dismiss the pill itself.
   Toasts (bottom-left): a REAL feed (window.MTV.fomoFeed) wins; otherwise they
   are GENERATED from window.MTV.activity - see the FOMO_SYNTHETIC note in
   app/config.php. */
(function () {
  'use strict';
  const MTV = window.MTV || {};
  const T = MTV.t || {};
  const store = MTV.store;
  const fmt = MTV.fmt;
  const esc = MTV.esc;
  const wheel = document.getElementById('wheel');
  const dataEl = document.getElementById('wheel-data');
  const pill = document.getElementById('wheel-reopen');
  const K = { seen: 'mtv:wheel:seen', decline: 'mtv:wheel:decline', prize: 'mtv:wheel:prize', spins: 'mtv:wheel:spins',
              skip: 'mtv:wheel:skip', pill: 'mtv:wheel:pilloff' };
  const HOLD = ((MTV.wheel && MTV.wheel.hold) || 5) * 60 * 1000;
  const COOLDOWN = 7 * 86400000;
  const MAX_SPINS = 3;

  if (wheel && dataEl && store) {
    const slices = JSON.parse(dataEl.textContent);
    const disc = wheel.querySelector('.wheel__disc');
    const steps = { gate: wheel.querySelector('[data-step="gate"]'), prize: wheel.querySelector('[data-step="prize"]'), again: wheel.querySelector('[data-step="again"]') };
    const dock = document.getElementById('wheel-dock');
    const REPEAT = 2;
    const wedges = [];
    for (let r = 0; r < REPEAT; r++) slices.forEach((s, i) => wedges.push(Object.assign({ idx: i }, s)));
    const N = wedges.length, step = 360 / N;
    const colors = ['#fee2a8', '#c4252d', '#f9e9c4', '#540f12', '#fee2a8', '#c4252d', '#f9e9c4', '#540f12', '#fee2a8', '#c4252d'];

    /* draw the disc */
    let svg = '';
    wedges.forEach((w, i) => {
      const a0 = (i * step - 90) * Math.PI / 180, a1 = ((i + 1) * step - 90) * Math.PI / 180;
      const x0 = 200 + 196 * Math.cos(a0), y0 = 200 + 196 * Math.sin(a0), x1 = 200 + 196 * Math.cos(a1), y1 = 200 + 196 * Math.sin(a1);
      const fill = colors[i % colors.length], dark = fill === '#c4252d' || fill === '#540f12';
      const mid = (i + .5) * step - 90;
      svg += `<path d="M200 200 L${x0} ${y0} A196 196 0 0 1 ${x1} ${y1} Z" fill="${fill}" stroke="#000" stroke-width="3"/>`;
      const ink = dark ? '#fee2a8' : '#000';
      svg += `<g transform="rotate(${mid} 200 200)"><text x="372" y="197" text-anchor="end" fill="${ink}" style="font-family:Anton,Impact,sans-serif;font-size:${w.label.length > 9 ? 17 : 21}px">${esc(w.label)}</text><text class="sub" x="372" y="212" text-anchor="end" fill="${ink}" style="font-family:Inter,sans-serif;font-weight:800;font-size:8px;letter-spacing:.08em">${esc(w.sub.toUpperCase())}</text></g>`;
    });
    disc.innerHTML = svg;

    let rot = 0, spinning = false, holdTimer;
    const show = (name) => Object.entries(steps).forEach(([k, el]) => { el.hidden = k !== name; });
    const open = () => { wheel.hidden = false; document.body.style.overflow = 'hidden'; hidePill(); };
    const close = () => { wheel.hidden = true; document.body.style.overflow = ''; };

    /* ---- the reopen pill at the top ---- */
    function hidePill() { if (pill) pill.hidden = true; }
    function showPill() {
      if (!pill || sessionStorage.getItem(K.pill) || store.get(K.prize)) return;
      pill.hidden = false;
    }
    /* Closing the wheel without a prize = "skipped": the pill offers the way back. */
    function skipped() {
      if (store.get(K.prize)) return;
      sessionStorage.setItem(K.skip, '1');
      setTimeout(showPill, 500);
    }
    if (pill) {
      pill.querySelector('[data-wheel-reopen]').addEventListener('click', () => {
        hidePill();
        const p = (() => { try { return JSON.parse(store.get(K.prize) || 'null'); } catch (_) { return null; } })();
        show(p && p.until > Date.now() ? 'prize' : 'gate');
        open();
      });
      pill.querySelector('[data-wheel-reopen-hide]').addEventListener('click', () => {
        sessionStorage.setItem(K.pill, '1');
        hidePill();
      });
    }
    const pickWedge = () => {
      const pool = wedges.filter((w) => w.weight > 0);
      const total = pool.reduce((s, w) => s + w.weight, 0);
      let r = Math.random() * total;
      for (const w of pool) { r -= w.weight; if (r <= 0) return w; }
      return pool[pool.length - 1];
    };
    function spin() {
      if (spinning) return;
      const spins = parseInt(sessionStorage.getItem(K.spins) || '0', 10);
      if (spins >= MAX_SPINS) return;
      sessionStorage.setItem(K.spins, String(spins + 1));
      spinning = true;
      const w = pickWedge();
      const i = wedges.indexOf(w);
      const target = 360 - (i * step + step / 2) + (Math.random() * step * .6 - step * .3);
      rot = rot - (rot % 360) + 360 * 6 + target;
      disc.style.setProperty('--rot', rot + 'deg');
      setTimeout(() => {
        spinning = false;
        if (!w.code) { show('again'); return; }
        win(w);
      }, 5600);
    }
    function win(w) {
      const prize = { code: w.code, title: w.title, desc: w.desc, until: Date.now() + HOLD };
      store.set(K.prize, JSON.stringify(prize));
      sessionStorage.removeItem(K.skip);
      hidePill();
      renderPrize(prize);
      show('prize');
      const email = (wheel.querySelector('[data-wheel-email]') || {}).value || '';
      fetch('/api/hook.php?t=wheel', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ email, prize: w.title, code: w.code, page: location.href, ts: new Date().toISOString(), lang: MTV.lang }), keepalive: true }).catch(() => {});
      try { window.dataLayer = window.dataLayer || []; window.dataLayer.push({ event: 'mtv_wheel', prize: w.code }); } catch (_) {}
    }
    function renderPrize(prize) {
      wheel.querySelector('[data-prize-title]').textContent = prize.title;
      wheel.querySelector('[data-prize-desc]').textContent = prize.desc;
      wheel.querySelector('[data-prize-code]').textContent = prize.code;
      wheel.querySelectorAll('[data-copy-code]').forEach((b) => { b.dataset.copy = prize.code; });
      if (dock) { dock.querySelector('[data-dock-prize]').textContent = prize.title; dock.querySelector('[data-dock-code]').textContent = prize.code; dock.querySelector('[data-copy-code]').dataset.copy = prize.code; dock.hidden = false; }
      clearInterval(holdTimer);
      const tick = () => {
        const left = Math.max(0, prize.until - Date.now());
        const mm = String(Math.floor(left / 60000)).padStart(2, '0'), ss = String(Math.floor((left % 60000) / 1000)).padStart(2, '0');
        wheel.querySelectorAll('[data-prize-timer],[data-dock-timer]').forEach((el) => { el.textContent = mm + ':' + ss; });
        if (dock) { const d = dock.querySelector('[data-dock-timer]'); if (d) d.textContent = mm + ':' + ss; }
        if (left <= 0) { clearInterval(holdTimer); store.del(K.prize); if (dock) dock.hidden = true; MTV.toast(esc(T.wheel_expired), { simple: true }); }
      };
      tick(); holdTimer = setInterval(tick, 1000);
    }
    wheel.querySelectorAll('[data-wheel-spin]').forEach((b) => b.addEventListener('click', spin));
    wheel.querySelectorAll('[data-wheel-close]').forEach((b) => b.addEventListener('click', () => { close(); skipped(); }));
    const decl = wheel.querySelector('[data-wheel-decline]');
    // "No thanks" stops the wheel from opening by itself for 7 days, but the
    // pill still offers a way back in - it is the visitor's own decision then.
    if (decl) decl.addEventListener('click', () => { store.set(K.decline, String(Date.now())); close(); skipped(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !wheel.hidden) { close(); skipped(); } });

    /* opening rules */
    const existing = (() => { try { return JSON.parse(store.get(K.prize) || 'null'); } catch (_) { return null; } })();
    if (existing && existing.until > Date.now()) { renderPrize(existing); show('prize'); }
    else store.del(K.prize);
    const declined = parseInt(store.get(K.decline) || '0', 10);
    const canOpen = !existing && !sessionStorage.getItem(K.seen) && Date.now() - declined > COOLDOWN && !/\/(bedankt|merci|chat)\//.test(location.pathname);
    if (canOpen) {
      const t0 = Date.now();
      const arm = () => {
        ['pointerdown', 'keydown', 'touchstart', 'wheel'].forEach((ev) => window.removeEventListener(ev, arm));
        const wait = Math.max(3500, 6000 - (Date.now() - t0));
        setTimeout(() => { if (document.hidden) return; sessionStorage.setItem(K.seen, '1'); show('gate'); open(); }, wait);
      };
      ['pointerdown', 'keydown', 'touchstart', 'wheel'].forEach((ev) => window.addEventListener(ev, arm, { passive: true, once: true }));
    }
    // Skipped earlier in this session (possibly on another page): offer it back.
    if (!existing && sessionStorage.getItem(K.skip)) showPill();
  }

  /* ---------- activity toasts, bottom-left ----------
     Source order: a real order feed (MTV.fomoFeed) first, else the generated
     pool (MTV.activity, from includes/content/<lang>/activity.php). Neither
     configured = nothing runs. */
  const pool = MTV.activity;
  if (MTV.toast && (MTV.fomoFeed || pool) && !/\/(bedankt|merci|chat)\//.test(location.pathname)) {
    const small = matchMedia('(max-width: 640px)').matches;
    const MAX = small ? 4 : 12;               // per session, per page view
    const GAP = [10000, 15000];               // 10-15 s between toasts, the first one included
    const AGO = [2, 9];                       // a generated order happened "x s ago"
    const VISIBLE = 6000;
    const rint = (a, b) => a + Math.floor(Math.random() * (b - a + 1));
    const pick = (a) => a[Math.floor(Math.random() * a.length)];
    const weighted = (list) => {
      const total = list.reduce((s, e) => s + (e.w || 1), 0);
      let r = Math.random() * total;
      for (const e of list) { r -= (e.w || 1); if (r <= 0) return e; }
      return list[list.length - 1];
    };
    const agoText = (secs) => (secs < 60 ? fmt(T.toast_ago_s, secs)
      : secs < 3600 ? fmt(T.toast_ago, Math.round(secs / 60))
      : fmt(T.toast_ago_h, Math.round(secs / 3600)));

    /* Generated entry: a name + town pair is never repeated in a session. */
    const usedNames = [];
    function fromPool() {
      let name, city, tries = 0;
      do {
        name = pick(pool.names) + ' ' + pick(pool.initials);
        city = pick(pool.cities);
        tries++;
      } while (tries < 30 && usedNames.indexOf(name) !== -1);
      usedNames.push(name);
      if (usedNames.length > 20) usedNames.shift();
      const ev = weighted(pool.events);
      return { name: name, city: city, action: ev.action, plan: ev.plan, ago: agoText(rint(AGO[0], AGO[1])) };
    }
    function fromFeed(ev) {
      const secs = Math.max(1, Math.round((Date.now() - new Date(ev.time).getTime()) / 1000));
      return { name: ev.name || '?', city: ev.city || '', action: '', plan: ev.plan || '', ago: agoText(secs) };
    }

    function render(d) {
      const line = d.action ? esc(d.action) + ' <b>' + esc(d.plan) + '</b>' : esc(fmt(T.toast_bought, d.plan));
      const el = MTV.toast(
        '<span class="toast__avatar">' + esc(d.name.charAt(0).toUpperCase()) + '</span>' +
        '<span class="toast__body">' +
          '<span class="toast__l1"><b>' + esc(d.name) + '</b>' + (d.city ? '<span class="toast__city">' + esc(d.city) + '</span>' : '') + '</span>' +
          '<span class="toast__l2">' + line + '</span>' +
          '<span class="toast__meta"><i class="toast__live" aria-hidden="true"></i>' + esc(d.ago) +
            '<a href="' + MTV.urls.pricing + '">' + esc(T.toast_cta) + '</a></span>' +
        '</span>' +
        '<button type="button" class="toast__x" aria-label="' + esc(T.toast_dismiss || '') + '">&times;</button>',
        { ms: VISIBLE, cls: 'toast--act' });
      el.querySelector('.toast__x').addEventListener('click', (e) => { e.stopPropagation(); el.remove(); });
      // On phones the "see this plan" link is hidden for space, so the whole
      // toast opens the pricing page instead.
      el.style.cursor = 'pointer';
      el.addEventListener('click', (e) => {
        if (e.target.closest('a, .toast__x')) return;
        location.href = MTV.urls.pricing;
      });
      // Reading one shouldn't make it vanish mid-sentence.
      el.addEventListener('mouseenter', () => clearTimeout(el._t));
      el.addEventListener('mouseleave', () => { el._t = setTimeout(() => el.remove(), 1800); });
    }

    let shown = 0, feed = null, fi = 0;
    function next() {
      if (shown >= MAX) return;
      if (!document.hidden) { shown++; render(feed && feed.length ? fromFeed(feed[fi++ % feed.length]) : fromPool()); }
      setTimeout(next, rint(GAP[0], GAP[1]));
    }
    /* The cookie banner owns the bottom of the screen until it is answered. */
    const banner = document.getElementById('consent');
    const start = () => {
      if (!banner || banner.hidden) { setTimeout(next, rint(GAP[0], GAP[1])); return; }
      document.addEventListener('mtv:consent', () => setTimeout(next, rint(GAP[0], GAP[1])), { once: true });
    };
    if (MTV.fomoFeed) {
      fetch(MTV.fomoFeed, { cache: 'no-store' }).then((r) => r.json()).then((list) => {
        if (Array.isArray(list) && list.length) { feed = list; start(); }
        else if (pool) start();
      }).catch(() => { if (pool) start(); });
    } else start();
  }
})();
