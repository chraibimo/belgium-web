/* Channel list page: one search box, in the hero. Nothing is listed until the
   visitor types; every query goes to /api/channels.php (the same index the home
   box uses, feeds grouped under one clean channel name), 24 names at a time, and
   the results open right under the box. The Belgian name chips and ?q= feed it.
   While the box is empty the upcoming events show instead (includes/events.php),
   with a live countdown; the ones that are over drop out by themselves.
   Mickey the TV (tv.js, guide mode) follows along: he watches the box while the
   visitor types, tunes to the result under the pointer, loses his signal when
   nothing matches and surfs the upcoming events when nobody is searching. */
(function () {
  'use strict';
  const root = document.querySelector('[data-guide]');
  if (!root) return;
  const MTV = window.MTV;
  const T = (MTV && MTV.t) || {};
  const esc = MTV.esc, fmt = MTV.fmt;
  const nf = (n) => Number(n).toLocaleString((MTV.lang || 'nl') === 'fr' ? 'fr-BE' : 'nl-BE');
  const el = {
    form: root.querySelector('[data-guide-form]'), q: root.querySelector('[data-guide-q]'),
    out: root.querySelector('[data-guide-results]'), status: root.querySelector('[data-guide-status]'),
    more: root.querySelector('[data-guide-more]'), note: root.querySelector('[data-guide-note]'),
  };
  const tv = MTV.tv || null;
  const tvBox = document.querySelector('[data-tv-idle]');
  const tvIdle = tvBox ? JSON.parse(tvBox.dataset.tvIdle || '{}') : {};
  const PAGE = 24, MAX = 96;
  let timer, ctrl, want = PAGE, last = '', showing = false, current = null, tuned = 0;
  const cache = {};

  /* ---------- what the TV shows for a result ---------- */
  const SCENE = { SPORTS: 'sport', MOVIES: 'film', ENTERTAINMENT: 'series', KIDS: 'kids', NEWS: 'news', MUSIC: 'music', DOCUMENTARY: 'docu' };
  function sceneFor(r) {
    const n = r.name.toUpperCase();
    if (/\bF1\b|FORMULA|MOTOR|RACING|NASCAR|MOTOGP|INDYCAR/.test(n)) return 'racing';
    if (/CYCL|WIELER|\bVELO\b|TOUR DE|GIRO|VUELTA/.test(n)) return 'cycling';
    return SCENE[r.genre] || 'general';
  }
  function quality(r) {
    const all = r.variants.map((v) => v.n).join(' ').toUpperCase();
    return /\b8K\b/.test(all) ? '8K' : /\b(4K|UHD)\b/.test(all) ? '4K' : /\bFHD\b|1080/.test(all) ? 'FHD' : /\bHD\b/.test(all) ? 'HD' : '';
  }
  const resultItems = (list) => list.map((r, i) => ({
    id: sceneFor(r), name: r.name, osd: 'CH ' + String(i + 1).padStart(2, '0'),
    kicker: fmt(T.tv_found, i + 1, list.length), meta: [r.country, r.genre, quality(r)].filter(Boolean).join(' · '),
  }));
  const hits = () => Array.from(el.out.querySelectorAll('.cc__hit'));
  function markTuned(i, reveal) {
    const hs = hits();
    hs.forEach((h, j) => h.classList.toggle('is-tuned', j === i));
    if (reveal && hs[i]) hs[i].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
  }

  /* ---------- search ---------- */
  function highlight(name, q) {
    const i = name.toLowerCase().indexOf(q.toLowerCase());
    return i < 0 ? esc(name) : esc(name.slice(0, i)) + '<mark>' + esc(name.slice(i, i + q.length)) + '</mark>' + esc(name.slice(i + q.length));
  }
  function idle(msg) {
    el.out.innerHTML = ''; el.status.textContent = msg; el.more.hidden = true; el.note.hidden = true;
    showing = false; current = null;
    refreshEvents();
    tvHome();
  }
  function render(j) {
    const q = j.q;
    showing = true;
    refreshEvents();
    if (!j.results.length) {
      el.out.innerHTML = '<div class="cc__empty">' + fmt(T.cg_empty, esc(q)) + '</div>';
      el.status.textContent = ''; el.more.hidden = true; el.note.hidden = true;
      current = null;
      if (tv) tv.noSignal({ kicker: T.tv_nosignal, name: q, meta: fmt(T.tv_nosignal_meta, q), osd: String(T.tv_nosignal || '').toUpperCase() });
      return;
    }
    el.out.innerHTML = j.results.map((r) => {
      const feeds = r.variants.slice(0, 8).map((v) => '<span class="cc__feed"><b>' + esc(v.c) + (v.s ? ' · ' + esc(v.s) : '') + '</b>' + esc(v.n) + '</span>').join('')
        + (r.count > 8 ? '<span class="cc__feed">' + fmt(T.cc_feeds, r.count - 8) + '</span>' : '');
      return '<div class="cc__hit" role="button" tabindex="0"><div class="cc__hit-top"><span>' + highlight(r.name, q) + '</span><span class="badge badge--green">' + esc(T.cc_included) + '</span></div>'
        + '<div class="cc__hit-sub">' + esc(r.country) + ' · ' + esc(r.genre) + (r.count > 1 ? ' · ' + fmt(T.cc_more, r.count - 1) : '') + '</div><div class="cc__feeds">' + feeds + '</div></div>';
    }).join('');
    el.status.textContent = fmt(T.cg_count, nf(j.names), q, nf(j.total));
    el.note.hidden = false;
    const rest = j.names - j.shown;
    el.more.hidden = rest <= 0 || j.shown >= MAX;
    if (!el.more.hidden) el.more.textContent = T.cg_more + ' (' + nf(Math.min(rest, PAGE)) + ')';
    else if (rest > 0) el.status.textContent = fmt(T.cg_shown, nf(j.shown), nf(j.names));
    // "more" keeps the channel that was on; a new query starts at the top
    const keep = current === q && tuned < j.results.length ? tuned : 0;
    current = q; tuned = keep;
    if (tv) tv.play(resultItems(j.results), keep, { surf: false });
    markTuned(keep, false);
  }
  async function search(q, n) {
    q = q.trim();
    if (q.length < 2) { if (ctrl) ctrl.abort(); if (last || showing) idle(T.cg_hint); last = ''; return; }
    if (q !== last) want = PAGE;
    if (n) want = Math.min(MAX, n);
    last = q;
    const key = q + '|' + want;
    if (cache[key]) return render(cache[key]);
    if (ctrl) ctrl.abort();
    ctrl = new AbortController();
    el.status.textContent = T.cg_searching;
    if (tv) tv.searching();
    try {
      const r = await fetch('/api/channels.php?q=' + encodeURIComponent(q) + '&n=' + want, { signal: ctrl.signal });
      const j = await r.json();
      cache[key] = j; render(j);
    } catch (e) {
      if (e.name !== 'AbortError') idle(T.cg_error);
    }
  }
  const jump = () => { root.scrollIntoView({ behavior: 'smooth', block: 'start' }); };
  const pick = (q, scroll) => { el.q.value = q; search(q); if (scroll) jump(); };

  el.q.addEventListener('input', () => {
    clearTimeout(timer);
    if (tv && el.q.value.trim().length < 2) tv.watch(el.q);
    timer = setTimeout(() => search(el.q.value), 140);
  });
  el.q.addEventListener('focus', () => { if (tv && !showing) tv.watch(el.q); });
  el.q.addEventListener('blur', () => { if (tv) tv.watch(null); });
  el.form.addEventListener('submit', (e) => { e.preventDefault(); clearTimeout(timer); search(el.q.value); });
  el.more.addEventListener('click', () => search(last, want + PAGE));
  el.q.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { el.q.value = ''; search(''); }
    if (e.key === 'ArrowDown' && hits().length) { e.preventDefault(); hits()[0].focus(); }
  });
  el.out.addEventListener('keydown', (e) => {
    const hs = hits();
    const i = hs.indexOf(document.activeElement);
    if (e.key === 'ArrowDown' && i < hs.length - 1) { e.preventDefault(); hs[i + 1].focus(); }
    if (e.key === 'ArrowUp') { e.preventDefault(); if (i > 0) hs[i - 1].focus(); else el.q.focus(); }
    if ((e.key === 'Enter' || e.key === ' ') && i >= 0) { e.preventDefault(); hs[i].classList.toggle('is-open'); }
  });
  // the result under the pointer (or keyboard focus) goes on the TV
  const tuneHit = (e) => { const h = e.target.closest('.cc__hit'); if (h && tv) tv.tune(hits().indexOf(h)); };
  el.out.addEventListener('mouseover', tuneHit);
  el.out.addEventListener('focusin', tuneHit);
  el.out.addEventListener('click', (e) => { const h = e.target.closest('.cc__hit'); if (h) { h.classList.toggle('is-open'); tuneHit(e); } });

  /* ---------- upcoming events ---------- */
  const evBox = document.querySelector('[data-events]');
  const evRow = evBox && evBox.querySelector('[data-events-row]');
  const events = evBox ? JSON.parse(evBox.querySelector('[data-events-json]').textContent) : [];
  const countdown = (ms) => {
    const m = Math.max(1, Math.ceil(ms / 60000)), d = Math.floor(m / 1440), h = Math.floor((m % 1440) / 60);
    return d ? fmt(T.ev_in_d, d, h) : h ? fmt(T.ev_in_h, h, m % 60) : fmt(T.ev_in_m, m);
  };
  const upcoming = () => { const now = Date.now(); return events.map((e, i) => Object.assign({ i }, e)).filter((e) => e.end > now); };
  function refreshEvents() {
    if (!evBox) return;
    const now = Date.now();
    let left = 0;
    evBox.querySelectorAll('[data-ev]').forEach((card) => {
      const e = events[card.dataset.ev];
      const over = now >= e.end;
      card.hidden = over;
      if (over) return;
      left++;
      const live = now >= e.start;
      card.classList.toggle('is-live', live);
      card.querySelector('[data-ev-count]').textContent = live ? T.ev_live : countdown(e.start - now);
    });
    evBox.hidden = !left || showing;
  }
  const eventItems = (list) => {
    const now = Date.now();
    return list.map((e, n) => ({
      id: e.scene, name: e.title, label: e.short, ev: e.i, clean: true,   // no invented score or lap on an upcoming event
      osd: now >= e.start ? 'LIVE' : e.osd,
      kicker: fmt(T.tv_events, n + 1, list.length), meta: e.comp + ' · ' + e.when,
    }));
  };
  function markEvent(i, follow) {
    if (!evBox) return;
    evBox.querySelectorAll('[data-ev]').forEach((c) => c.classList.toggle('is-tuned', +c.dataset.ev === i));
    const card = evBox.querySelector('[data-ev="' + i + '"]');
    // while Mickey surfs, keep the card he is on in view inside the strip (never scrolls the page)
    if (follow && card && evRow) {
      const l = card.offsetLeft - evRow.offsetLeft, r = l + card.offsetWidth;
      if (l < evRow.scrollLeft || r > evRow.scrollLeft + evRow.clientWidth) evRow.scrollTo({ left: Math.max(0, l - 4), behavior: 'smooth' });
    }
  }
  // the TV at rest: surf the upcoming events, or just Mickey's face with a line of help
  function tvHome(start) {
    if (!tv) return;
    const list = upcoming();
    if (list.length) tv.play(eventItems(list), start == null ? -1 : start, { surf: true, idle: Object.assign({}, tvIdle, { meta: T.tv_events_meta }) });
    else tv.idle(tvIdle);
  }
  if (evBox) {
    evBox.addEventListener('click', (e) => {
      const b = e.target.closest('[data-ev-tune]');
      if (!b) return;
      const i = +b.closest('[data-ev]').dataset.ev;
      const n = upcoming().findIndex((x) => x.i === i);
      if (n < 0) return;
      tvHome(n);
      // on a phone the set sits above the box: bring it back if it scrolled away
      const set = document.getElementById('tv');
      if (set && set.getBoundingClientRect().bottom < 80) set.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
    setInterval(refreshEvents, 30000);
  }

  // the set reports every switch: light up the matching result or event card
  document.addEventListener('mtv:tv', (e) => {
    const { index, item, user } = e.detail;
    if (showing) { if (index >= 0) { tuned = index; markTuned(index, user); } }
    else markEvent(item ? item.ev : -1, !user);
  });

  // the Belgian name chips further down send the visitor back up to the box
  document.querySelectorAll('[data-guide-pick]').forEach((b) => b.addEventListener('click', () => pick(b.dataset.guidePick, true)));
  document.addEventListener('keydown', (e) => { if (e.key === '/' && !/input|textarea/i.test(document.activeElement.tagName)) { e.preventDefault(); el.q.focus(); jump(); } });

  // ?q= from the home box's "full list" link (read here: the page itself is cached without its query)
  const q0 = new URLSearchParams(location.search).get('q');
  if (q0 && q0.trim()) pick(q0, false);
  else if (el.q.value.trim()) search(el.q.value);   // restored by the browser on back navigation
  else idle(T.cg_hint);
})();
