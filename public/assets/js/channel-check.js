/* "Is your channel on Mickey TV?" — live search against /api/channels.php,
   cycling placeholder, logo tiles that type a name, keyboard navigation. */
(function () {
  'use strict';
  const box = document.querySelector('[data-cc]');
  if (!box) return;
  const T = (window.MTV && window.MTV.t) || {};
  const esc = window.MTV.esc;
  const fmt = window.MTV.fmt;
  const input = box.querySelector('[data-cc-input]');
  const out = box.querySelector('[data-cc-results]');
  const full = box.dataset.full;
  const placeholders = JSON.parse(box.dataset.placeholders || '[]');
  const nf = (n) => Number(n).toLocaleString((window.MTV.lang || 'nl') === 'fr' ? 'fr-BE' : 'nl-BE');
  let ph = 0, timer, ctrl, cache = {}, focusIdx = -1;

  if (placeholders.length > 1 && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    setInterval(() => { if (document.activeElement !== input && !input.value) { ph = (ph + 1) % placeholders.length; input.placeholder = placeholders[ph]; } }, 2600);
  }

  function highlight(name, q) {
    const i = name.toLowerCase().indexOf(q.toLowerCase());
    return i < 0 ? esc(name) : esc(name.slice(0, i)) + '<mark>' + esc(name.slice(i, i + q.length)) + '</mark>' + esc(name.slice(i + q.length));
  }
  function render(j) {
    const q = j.q;
    if (!j.results.length) {
      out.innerHTML = '<div class="cc__empty">' + fmt(T.cc_empty, esc(q)) + ' <a href="' + full + '?q=' + encodeURIComponent(q) + '">' + esc(T.cc_full) + '</a></div>';
      return;
    }
    out.innerHTML = j.results.map((r) => {
      const feeds = r.variants.slice(0, 6).map((v) => '<span class="cc__feed"><b>' + esc(v.c) + (v.s ? ' · ' + esc(v.s) : '') + '</b>' + esc(v.n) + '</span>').join('') + (r.count > 6 ? '<span class="cc__feed">' + fmt(T.cc_feeds, r.count - 6) + '</span>' : '');
      return '<div class="cc__hit" role="button" tabindex="0"><div class="cc__hit-top"><span>' + highlight(r.name, q) + '</span><span class="badge badge--green">' + esc(T.cc_included) + '</span></div><div class="cc__hit-sub">' + esc(r.country) + ' · ' + esc(r.genre) + (r.count > 1 ? ' · ' + fmt(T.cc_more, r.count - 1) : '') + '</div><div class="cc__feeds">' + feeds + '</div></div>';
    }).join('') + '<div class="cc__foot"><span>' + fmt(T.cc_showing, nf(j.shown), nf(j.names), nf(j.total)) + '</span><a href="' + full + '?q=' + encodeURIComponent(q) + '">' + esc(T.cc_full) + '</a></div>';
    focusIdx = -1;
  }
  async function search(q) {
    q = q.trim();
    if (q.length < 2) { out.innerHTML = ''; return; }
    if (cache[q]) return render(cache[q]);
    if (ctrl) ctrl.abort();
    ctrl = new AbortController();
    try {
      const r = await fetch('/api/channels.php?q=' + encodeURIComponent(q), { signal: ctrl.signal });
      const j = await r.json();
      cache[q] = j; render(j);
    } catch (e) {
      if (e.name !== 'AbortError') out.innerHTML = '<div class="cc__empty">' + esc(T.cc_error) + ' <a href="' + full + '">' + esc(T.cc_full) + '</a></div>';
    }
  }
  input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => search(input.value), 140); });
  input.addEventListener('keydown', (e) => {
    const hits = Array.from(out.querySelectorAll('.cc__hit'));
    if (e.key === 'Escape') { input.value = ''; out.innerHTML = ''; }
    if (e.key === 'ArrowDown' && hits.length) { e.preventDefault(); focusIdx = Math.min(hits.length - 1, focusIdx + 1); hits[focusIdx].focus(); }
  });
  out.addEventListener('keydown', (e) => {
    const hits = Array.from(out.querySelectorAll('.cc__hit'));
    const i = hits.indexOf(document.activeElement);
    if (e.key === 'ArrowDown' && i < hits.length - 1) { e.preventDefault(); hits[i + 1].focus(); }
    if (e.key === 'ArrowUp') { e.preventDefault(); if (i > 0) hits[i - 1].focus(); else input.focus(); }
    if ((e.key === 'Enter' || e.key === ' ') && i >= 0) { e.preventDefault(); hits[i].classList.toggle('is-open'); }
  });
  out.addEventListener('click', (e) => { const hit = e.target.closest('.cc__hit'); if (hit) hit.classList.toggle('is-open'); });
  // one listener for the whole logo river: binding each of the ~270 tiles cost a 200 ms task on mobile
  document.addEventListener('click', (e) => {
    const b = e.target.closest('[data-cc-pick]');
    if (!b) return;
    input.value = b.dataset.ccPick; input.focus(); search(input.value);
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });
  // ?q= from the full-list link back to the home box
  const q0 = new URLSearchParams(location.search).get('q');
  if (q0 && location.hash === '#channel-check') { input.value = q0; search(q0); }
})();
