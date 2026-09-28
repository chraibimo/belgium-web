/* Generated poster art: every .poster[data-palette][data-motif] gets an inline
   SVG scene drawn from a deterministic seed, so no photo or film artwork is
   ever needed. Flat retro shapes in the site palette. */
(function () {
  'use strict';
  const PAL = {
    red:     { bg: '#c4252d', fg: '#fee2a8', ac: '#000000', sh: '#000000' },
    maroon:  { bg: '#540f12', fg: '#fee2a8', ac: '#c4252d', sh: '#000000' },
    cream:   { bg: '#fee2a8', fg: '#1a0a0b', ac: '#c4252d', sh: '#c4252d' },
    navy:    { bg: '#1e2b4a', fg: '#fee2a8', ac: '#e4572e', sh: '#000000' },
    forest:  { bg: '#23402f', fg: '#fee2a8', ac: '#e6a23c', sh: '#000000' },
    mustard: { bg: '#e6a23c', fg: '#1a0a0b', ac: '#c4252d', sh: '#fee2a8' },
    ink:     { bg: '#0f0a0a', fg: '#fee2a8', ac: '#c4252d', sh: '#c4252d' },
    teal:    { bg: '#2d6a73', fg: '#fee2a8', ac: '#f2c14e', sh: '#000000' },
    salmon:  { bg: '#ee8b63', fg: '#1a0a0b', ac: '#540f12', sh: '#fee2a8' },
    plum:    { bg: '#4b2350', fg: '#fee2a8', ac: '#f28c6a', sh: '#000000' },
  };
  const BARS = ['#fee2a8', '#e6a23c', '#2d6a73', '#ee8b63', '#c4252d', '#4b2350', '#1e2b4a'];
  const SIZES = { portrait: [200, 300], wide: [320, 180], tv: [240, 180], square: [240, 240] };

  function hash(s) { let h = 2166136261; for (let i = 0; i < s.length; i++) { h ^= s.charCodeAt(i); h = Math.imul(h, 16777619); } return h >>> 0; }
  function rng(seed) { let a = seed >>> 0; return () => { a = (a + 0x6d2b79f5) | 0; let t = Math.imul(a ^ (a >>> 15), 1 | a); t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t; return ((t ^ (t >>> 14)) >>> 0) / 4294967296; }; }
  const n = (v) => Math.round(v * 10) / 10;

  const M = {
    sun(w, h, p) {
      const cx = w / 2, cy = h * .4, r = Math.min(w, h) * .3, hz = cy + r * .5;
      let s = `<circle cx="${cx}" cy="${cy}" r="${r}" fill="${p.fg}"/>`;
      for (let i = 0; i < 5; i++) s += `<rect x="0" y="${n(cy + i * r * .11)}" width="${w}" height="${n(1 + i * 1.4)}" fill="${p.bg}"/>`;
      return s + `<rect x="0" y="${n(hz)}" width="${w}" height="${n(h - hz)}" fill="${p.ac}"/><rect x="0" y="${n(hz)}" width="${w}" height="2" fill="${p.fg}" opacity=".5"/>`;
    },
    moon(w, h, p, seed) {
      const r = rng(seed), R = Math.min(w, h) * .22, cx = w * .66, cy = h * .28; let s = '';
      for (let i = 0; i < 18; i++) s += `<circle cx="${n(r() * w)}" cy="${n(r() * h * .62)}" r="${n(.5 + r() * 1.3)}" fill="${p.fg}" opacity=".8"/>`;
      s += `<circle cx="${cx}" cy="${cy}" r="${R}" fill="${p.fg}"/><circle cx="${n(cx + R * .45)}" cy="${n(cy - R * .2)}" r="${n(R * .88)}" fill="${p.bg}"/>`;
      return s + `<path d="M0 ${n(h * .74)} Q ${n(w * .28)} ${n(h * .6)} ${n(w * .56)} ${n(h * .7)} T ${w} ${n(h * .64)} V ${h} H 0 Z" fill="${p.ac}" opacity=".9"/>`;
    },
    grid(w, h, p) {
      const hy = h * .52, vx = w / 2, R = Math.min(w, h) * .27; let s = `<circle cx="${vx}" cy="${hy}" r="${R}" fill="${p.fg}"/>`;
      for (let i = 0; i < 4; i++) s += `<rect x="0" y="${n(hy - R * .5 + i * R * .14)}" width="${w}" height="${1 + i}" fill="${p.bg}"/>`;
      s += `<rect x="0" y="${hy}" width="${w}" height="${n(h - hy)}" fill="${p.bg}"/><g stroke="${p.ac}" stroke-width="1.3"><line x1="0" y1="${hy}" x2="${w}" y2="${hy}"/>`;
      for (let i = 0; i < 13; i++) s += `<line x1="${vx}" y1="${hy}" x2="${n(-w * 1.2 + (i * w * 3.4) / 12)}" y2="${h}"/>`;
      for (let i = 0; i < 8; i++) { const y = hy + (h - hy) * Math.pow((i + 1) / 8, 2); s += `<line x1="0" y1="${n(y)}" x2="${w}" y2="${n(y)}"/>`; }
      return s + '</g>';
    },
    rings(w, h, p) {
      const cx = w / 2, cy = h * .36, max = Math.min(w, h) * .62, k = 8; let s = '';
      for (let i = 0; i < k; i++) s += `<circle cx="${cx}" cy="${cy}" r="${n(max - (i * max) / k)}" fill="${i % 2 ? p.bg : p.ac}"/>`;
      return s + `<circle cx="${cx}" cy="${cy}" r="${n((max / k) * .7)}" fill="${p.fg}"/>`;
    },
    waves(w, h, p) {
      let s = `<circle cx="${n(w * .72)}" cy="${n(h * .2)}" r="${n(Math.min(w, h) * .13)}" fill="${p.fg}"/>`;
      for (let i = 0; i < 6; i++) { const y = h * .34 + i * h * .09, a = h * .035 + i * 1.5, k = w / 2;
        s += `<path d="M0 ${n(y)} C ${n(k * .5)} ${n(y - a * 2)} ${n(k * .9)} ${n(y + a * 2)} ${k} ${n(y)} S ${n(w * .95)} ${n(y - a * 2)} ${w} ${n(y)} V ${h} H 0 Z" fill="${i % 2 ? p.ac : p.fg}" opacity="${n(.2 + i * .12)}"/>`; }
      return s;
    },
    bars(w, h, p, seed, uid) {
      const k = BARS.length, bw = w / k, top = h * .58;
      let s = `<defs><pattern id="${uid}s" width="4" height="4" patternUnits="userSpaceOnUse"><rect width="4" height="1.3" fill="#000" opacity=".22"/></pattern></defs>`;
      BARS.forEach((c, i) => { s += `<rect x="${n(i * bw)}" y="0" width="${n(bw + .5)}" height="${n(top)}" fill="${c}"/>`; });
      BARS.forEach((c, i) => { s += `<rect x="${n(i * bw)}" y="${n(top)}" width="${n(bw + .5)}" height="${n(h * .05)}" fill="${BARS[k - 1 - i]}"/>`; });
      return s + `<rect x="0" y="${n(top + h * .05)}" width="${w}" height="${h}" fill="${p.bg}"/><rect width="${w}" height="${h}" fill="url(#${uid}s)"/>`;
    },
    rails(w, h, p) {
      const vx = w * .5, vy = h * .42, sp = w * .46, at = (y, side) => vx + (side * (y - vy) * sp) / (h - vy);
      let s = `<rect x="0" y="${n(vy)}" width="${w}" height="${n(h - vy)}" fill="${p.ac}" opacity=".35"/><circle cx="${vx}" cy="${n(vy)}" r="${n(Math.min(w, h) * .22)}" fill="${p.fg}" opacity=".14"/><circle cx="${vx}" cy="${n(vy)}" r="${n(Math.min(w, h) * .07)}" fill="${p.fg}"/><g fill="${p.fg}" opacity=".55">`;
      for (let i = 0; i < 9; i++) { const y = vy + (h - vy) * Math.pow((i + 1) / 9, 1.8), t = 1 + ((y - vy) / (h - vy)) * 5; s += `<rect x="${n(at(y, -1.3))}" y="${n(y)}" width="${n(at(y, 1.3) - at(y, -1.3))}" height="${n(t)}"/>`; }
      return s + `</g><g stroke="${p.fg}" stroke-width="2"><line x1="${vx}" y1="${n(vy)}" x2="${n(at(h, -1))}" y2="${h}"/><line x1="${vx}" y1="${n(vy)}" x2="${n(at(h, 1))}" y2="${h}"/></g><g stroke="${p.fg}" stroke-width="1" opacity=".6"><line x1="0" y1="${n(h * .1)}" x2="${vx}" y2="${n(vy - h * .1)}"/><line x1="${w}" y1="${n(h * .1)}" x2="${vx}" y2="${n(vy - h * .1)}"/></g>`;
    },
    city(w, h, p, seed) {
      const r = rng(seed), base = h * .8, b = []; for (let x = -4; x < w;) { const bw = w * (.08 + r() * .1); b.push({ x, bw, bh: h * (.18 + r() * .36) }); x += bw + 1.5; }
      let s = `<circle cx="${n(w * .24)}" cy="${n(h * .2)}" r="${n(Math.min(w, h) * .12)}" fill="${p.fg}"/>`, win = '';
      b.forEach((bl) => { s += `<rect x="${n(bl.x)}" y="${n(base - bl.bh)}" width="${n(bl.bw)}" height="${h}" fill="${p.ac}"/>`;
        for (let y = base - bl.bh + 6; y < base - 6; y += 8) for (let x = bl.x + 4; x < bl.x + bl.bw - 5; x += 6) if (r() < .32) win += `<rect x="${n(x)}" y="${n(y)}" width="2.6" height="3.6"/>`; });
      return s + `<g fill="${p.fg}" opacity=".85">${win}</g><rect x="0" y="${n(base)}" width="${w}" height="${n(h - base)}" fill="#000" opacity=".35"/>`;
    },
    orbit(w, h, p, seed) {
      const r = rng(seed), cx = w * .5, cy = h * .4, R = Math.min(w, h) * .2; let s = '';
      for (let i = 0; i < 22; i++) s += `<circle cx="${n(r() * w)}" cy="${n(r() * h)}" r="${n(.4 + r() * 1.1)}" fill="${p.fg}" opacity=".7"/>`;
      s += `<g transform="rotate(-18 ${cx} ${cy})" fill="none" stroke="${p.fg}"><ellipse cx="${cx}" cy="${cy}" rx="${n(R * 2.6)}" ry="${n(R * .9)}" opacity=".3" stroke-width="1"/><ellipse cx="${cx}" cy="${cy}" rx="${n(R * 1.9)}" ry="${n(R * .5)}" stroke-width="2.5"/></g>`;
      s += `<circle cx="${cx}" cy="${cy}" r="${R}" fill="${p.ac}"/><circle cx="${n(cx - R * .35)}" cy="${n(cy - R * .35)}" r="${n(R * .35)}" fill="${p.fg}" opacity=".18"/>`;
      return s + `<path d="M ${n(cx - R * 1.83)} ${n(cy + R * .12)} A ${n(R * 1.9)} ${n(R * .5)} 0 0 0 ${n(cx + R * 1.83)} ${n(cy - R * .12)}" transform="rotate(-18 ${cx} ${cy})" fill="none" stroke="${p.fg}" stroke-width="2.5"/><circle cx="${n(cx + R * 2.2)}" cy="${n(cy - R * 1.2)}" r="${n(R * .12)}" fill="${p.fg}"/>`;
    },
    dots(w, h, p) {
      const st = Math.min(w, h) / 10, fx = w * .68, fy = h * .3, md = Math.hypot(w, h) * .7; let s = `<g fill="${p.ac}">`;
      for (let y = st / 2; y < h; y += st) for (let x = st / 2; x < w; x += st) { const k = Math.pow(Math.max(0, 1 - Math.hypot(x - fx, y - fy) / md), 1.5); if (k > .04) s += `<circle cx="${n(x)}" cy="${n(y)}" r="${n(st * .48 * k)}"/>`; }
      return s + `</g><circle cx="${n(fx)}" cy="${n(fy)}" r="${n(st * 1.4)}" fill="${p.fg}"/>`;
    },
    mountains(w, h, p) {
      const pk = (ys) => `0,${h} ` + ys.map((y, i) => `${n((i * w) / (ys.length - 1))},${n(h * y)}`).join(' ') + ` ${w},${h}`;
      return `<circle cx="${n(w * .3)}" cy="${n(h * .28)}" r="${n(Math.min(w, h) * .14)}" fill="${p.fg}"/><polygon points="${pk([.6, .42, .55, .36, .52, .4, .5])}" fill="${p.fg}" opacity=".3"/><polygon points="${pk([.66, .52, .62, .48, .6, .5, .58])}" fill="${p.ac}" opacity=".85"/><polygon points="${pk([.8, .66, .74, .62, .72, .68, .76])}" fill="#000" opacity=".5"/>`;
    },
    split(w, h, p, seed, uid) {
      const cx = w * .5, cy = h * .46, R = Math.min(w, h) * .27, cut = `0,${n(h * .72)} ${w},${n(h * .24)} ${w},${h} 0,${h}`;
      let s = `<defs><clipPath id="${uid}c"><polygon points="${cut}"/></clipPath></defs><circle cx="${cx}" cy="${cy}" r="${R}" fill="${p.fg}"/><polygon points="${cut}" fill="${p.ac}"/><circle cx="${cx}" cy="${cy}" r="${R}" fill="${p.bg}" clip-path="url(#${uid}c)"/><g stroke="${p.fg}" stroke-width="1" opacity=".5">`;
      for (let i = 0; i < 5; i++) s += `<line x1="0" y1="${n(h * .78 + i * 7)}" x2="${w}" y2="${n(h * .3 + i * 7)}"/>`;
      return s + '</g>';
    },
    stairs(w, h, p) {
      const k = 7, rise = (h * .58) / k; let d = `M0 ${h}`; for (let i = 0; i < k; i++) d += ` V ${n(h - (i + 1) * rise)} H ${n(((i + 1) * w) / k)}`; d += ` V ${h} Z`; const top = h - k * rise;
      return `<circle cx="${n(w * .22)}" cy="${n(h * .18)}" r="${n(Math.min(w, h) * .1)}" fill="${p.fg}"/><path d="${d}" fill="${p.ac}" stroke="${p.fg}" stroke-width="1.5"/><rect x="${n(w * .8)}" y="${n(top - h * .16)}" width="${n(w * .1)}" height="${n(h * .16)}" fill="${p.fg}"/><rect x="${n(w * .8)}" y="${n(top - h * .16)}" width="${n(w * .1)}" height="${n(h * .16)}" fill="#000" opacity=".25"/>`;
    },
    eye(w, h, p) {
      const cx = w / 2, cy = h * .4, ew = Math.min(w * .4, h * .5), eh = Math.min(w, h) * .16; let s = `<g stroke="${p.ac}" stroke-width="2" opacity=".5">`;
      for (let i = 0; i < 16; i++) { const a = (i / 16) * Math.PI * 2; s += `<line x1="${cx}" y1="${cy}" x2="${n(cx + Math.cos(a) * w)}" y2="${n(cy + Math.sin(a) * w)}"/>`; }
      return s + `</g><path d="M ${n(cx - ew)} ${cy} Q ${cx} ${n(cy - eh * 2.2)} ${n(cx + ew)} ${cy} Q ${cx} ${n(cy + eh * 2.2)} ${n(cx - ew)} ${cy} Z" fill="${p.fg}" stroke="${p.ac}" stroke-width="2"/><circle cx="${cx}" cy="${cy}" r="${n(eh * .95)}" fill="${p.ac}"/><circle cx="${cx}" cy="${cy}" r="${n(eh * .42)}" fill="#000"/><circle cx="${n(cx - eh * .3)}" cy="${n(cy - eh * .3)}" r="${n(eh * .16)}" fill="${p.fg}"/>`;
    },
  };

  let uidN = 0;
  function draw(el) {
    if (el.dataset.drawn) return;
    el.dataset.drawn = '1';
    const variant = ['wide', 'tv', 'square'].find((v) => el.classList.contains('poster--' + v)) || 'portrait';
    const [w, h] = SIZES[variant];
    const p = PAL[el.dataset.palette] || PAL.red;
    const motif = M[el.dataset.motif] || M.sun;
    const uid = 'p' + (++uidN);
    el.style.setProperty('--p-bg', p.bg); el.style.setProperty('--p-fg', p.fg); el.style.setProperty('--p-sh', p.sh);
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'poster__art'); svg.setAttribute('viewBox', `0 0 ${w} ${h}`); svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
    svg.innerHTML = `<rect width="${w}" height="${h}" fill="${p.bg}"/>` + motif(w, h, p, hash(el.dataset.seed || ''), uid);
    el.insertBefore(svg, el.firstChild);
    const title = el.querySelector('.poster__title');
    if (title) { const len = title.textContent.length; title.classList.add(len <= 8 ? 'poster__title--sm' : len > 22 ? 'poster__title--lg' : 'poster__title--md'); }
  }
  /* Drawing all of them at once cost ~200 ms of layout on a page with 46 posters, so each one is
     drawn shortly before it is scrolled to (600 px early - it is always ready by the time it shows). */
  const near = 'IntersectionObserver' in window
    ? new IntersectionObserver((els, obs) => els.forEach((e) => { if (e.isIntersecting) { obs.unobserve(e.target); draw(e.target); } }), { rootMargin: '600px 0px' })
    : null;
  /* .poster--idle is blurred background art: it waits for a quiet moment after load. */
  const idle = (fn) => (window.requestIdleCallback ? requestIdleCallback(fn, { timeout: 2000 }) : setTimeout(fn, 200));
  const afterLoad = (fn) => (document.readyState === 'complete' ? idle(fn) : window.addEventListener('load', () => idle(fn), { once: true }));
  function drawAll(root) {
    (root || document).querySelectorAll('.poster[data-motif]').forEach((el) => {
      if (el.classList.contains('poster--idle')) afterLoad(() => draw(el));
      else if (near) near.observe(el);
      else draw(el);
    });
  }
  drawAll();
  window.MTV = window.MTV || {};
  window.MTV.drawPosters = drawAll;
})();
