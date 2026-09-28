/* "Mickey" — the living TV.
   Home page: knobs change channel (static in between), each channel is a
   code-drawn animated scene; between channels Mickey's face comes back and
   follows the cursor; every so often the picture glitches and a tap on top of
   the set fixes it (like any good old TV). Power button switches it off (CRT collapse).
   Channel list page (data-tv-mode="guide"): the page drives the set through
   window.MTV.tv — it tunes to the search results, loses its signal when nothing
   matches, watches the search box while the visitor types and, when idle, surfs
   through the upcoming events. Every picture is drawn here: no channel logos,
   broadcast images or real-world marks ever appear on the screen. */
(function () {
  'use strict';
  const tv = document.getElementById('tv');
  if (!tv) return;
  const MTV = window.MTV || (window.MTV = {});
  const T = MTV.t || {};
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const guided = tv.dataset.tvMode === 'guide';
  let items = JSON.parse(tv.dataset.channels || '[]');
  let opts = { surf: !guided };
  const screen = tv.querySelector('[data-tv-screen]');
  const canvas = tv.querySelector('[data-tv-static]');
  const osd = tv.querySelector('[data-tv-osd]');
  const label = tv.querySelector('[data-tv-label]');
  const numEl = document.querySelector('[data-tv-num]');
  const kickerEl = document.querySelector('[data-tv-kicker]');
  const nameEl = document.querySelector('[data-tv-name]');
  const metaEl = document.querySelector('[data-tv-meta]');
  const dots = Array.from(document.querySelectorAll('[data-tv-go]'));
  const knobs = Array.from(tv.querySelectorAll('[data-tv-knob]'));
  const NS = 'http://www.w3.org/2000/svg';
  const C = { red: '#c4252d', cream: '#fee2a8', ink: '#000', maroon: '#540f12', green: '#2f7d4a', navy: '#1e2b4a', teal: '#2d6a73', mustard: '#e6a23c', salmon: '#ee8b63', plum: '#4b2350' };
  const A = reduced ? '' : 'on';   // SMIL animations only when motion is welcome

  /* ---------- scenes (240 x 180) ---------- */
  const anim = (attr, values, dur, extra) => A ? `<animate attributeName="${attr}" values="${values}" dur="${dur}" repeatCount="indefinite" ${extra || ''}/>` : '';
  const animT = (type, values, dur, extra) => A ? `<animateTransform attributeName="transform" type="${type}" values="${values}" dur="${dur}" repeatCount="indefinite" ${extra || ''}/>` : '';
  const liveTag = (x, y) => `<rect x="${x}" y="${y}" width="46" height="16" fill="${C.red}"/><circle cx="${x + 10}" cy="${y + 8}" r="4" fill="#fff">${anim('opacity', '1;.2;1', '1.2s')}</circle><text x="${x + 18}" y="${y + 12}" font-family="Inter, sans-serif" font-weight="800" font-size="10" fill="#fff">LIVE</text>`;

  const scenes = {
    // mood: 'happy' (default) or 'sad' (no signal: frown, brows, a tear)
    face: (mood) => {
      const sad = mood === 'sad';
      return `
      <rect width="240" height="180" fill="${C.red}"/>
      <g data-eye-l><ellipse cx="88" cy="86" rx="24" ry="34" fill="${C.cream}"/><ellipse data-pupil cx="92" cy="92" rx="10" ry="15" fill="${C.ink}"/><ellipse cx="80" cy="74" rx="6" ry="8" fill="#fff" opacity=".9"/></g>
      <g data-eye-r><ellipse cx="152" cy="86" rx="24" ry="34" fill="${C.cream}"/><ellipse data-pupil cx="156" cy="92" rx="10" ry="15" fill="${C.ink}"/><ellipse cx="144" cy="74" rx="6" ry="8" fill="#fff" opacity=".9"/></g>
      <rect data-lid x="60" y="46" width="120" height="0" fill="${C.red}"/>
      ${sad ? `<path d="M66 46 L104 34 M174 46 L136 34" stroke="${C.ink}" stroke-width="7" stroke-linecap="round"/>` : ''}
      <path data-mouth d="${sad ? 'M92 150 Q120 128 148 150' : 'M92 136 Q120 158 148 136'}" fill="none" stroke="${C.cream}" stroke-width="7" stroke-linecap="round"/>
      ${sad ? `<path d="M168 116 q6 10 0 16 q-6 -6 0 -16" fill="#9fd3ff" stroke="${C.ink}" stroke-width="1.5">${animT('translate', '0 0;0 22;0 22', '2.4s')}${anim('opacity', '1;1;0', '2.4s')}</path>` : ''}
      <circle cx="58" cy="128" r="9" fill="${C.cream}" opacity=".35"/><circle cx="182" cy="128" r="9" fill="${C.cream}" opacity=".35"/>`;
    },

    sport: (it) => `
      <rect width="240" height="180" fill="${C.green}"/>
      <g fill="none" stroke="#fff" stroke-width="2.5" opacity=".9"><rect x="14" y="20" width="212" height="140"/><line x1="120" y1="20" x2="120" y2="160"/><circle cx="120" cy="90" r="26"/><rect x="14" y="56" width="34" height="68"/><rect x="192" y="56" width="34" height="68"/></g>
      <g><circle r="9" fill="#fff" stroke="${C.ink}" stroke-width="2"/><path d="M-4 -3 L4 -3 L6 3 L0 7 L-6 3 Z" fill="${C.ink}"/>
        ${A ? '<animateMotion dur="5s" repeatCount="indefinite" path="M40 120 C 80 40, 140 150, 200 70 S 120 160, 40 120"/>' : '<set attributeName="transform" to="translate(120 90)"/>'}</g>
      ${it.clean ? '' : `<g><rect x="70" y="4" width="100" height="18" fill="${C.ink}"/><text x="120" y="17" text-anchor="middle" font-family="Anton, Impact, sans-serif" font-size="13" fill="${C.cream}" letter-spacing="1">BEL 2 – 1  <tspan fill="${C.red}">LIVE</tspan></text></g>`}
      <g fill="${C.cream}"><circle cx="62" cy="88" r="5"><animateTransform attributeName="transform" type="translate" values="0 0;12 -14;0 0" dur="2.4s" repeatCount="indefinite"/></circle><circle cx="170" cy="112" r="5"><animateTransform attributeName="transform" type="translate" values="0 0;-16 8;0 0" dur="3s" repeatCount="indefinite"/></circle></g>`,

    film: () => `
      <rect width="240" height="180" fill="${C.ink}"/>
      <g fill="none" stroke="${C.cream}" stroke-width="2"><circle cx="120" cy="90" r="70"/><circle cx="120" cy="90" r="58"/><line x1="120" y1="20" x2="120" y2="160"/><line x1="50" y1="90" x2="190" y2="90"/></g>
      <path d="M120 90 L120 20 A70 70 0 0 1 190 90 Z" fill="${C.red}" opacity=".85">${animT('rotate', '0 120 90;360 120 90', '2s')}</path>
      <g font-family="Anton, Impact, sans-serif" font-size="72" fill="${C.cream}" text-anchor="middle">
        <text x="120" y="116">3${anim('opacity', '1;1;0;0;0;0', '3s')}</text>
        <text x="120" y="116">2${anim('opacity', '0;0;1;1;0;0', '3s')}</text>
        <text x="120" y="116">1${anim('opacity', '0;0;0;0;1;1', '3s')}</text>
      </g>
      <g fill="${C.cream}" opacity=".7"><rect x="0" y="0" width="240" height="8"/><rect x="0" y="172" width="240" height="8"/>${Array.from({ length: 12 }, (_, i) => `<rect x="${i * 20 + 4}" y="2" width="10" height="4" fill="${C.ink}"/><rect x="${i * 20 + 4}" y="174" width="10" height="4" fill="${C.ink}"/>`).join('')}</g>`,

    series: () => {
      const cols = ['#c4252d', '#2d6a73', '#e6a23c', '#4b2350', '#ee8b63', '#1e2b4a', '#23402f', '#540f12', '#fee2a8', '#2f7d4a', '#e4572e', '#f2c14e'];
      let s = `<rect width="240" height="180" fill="#120708"/>`;
      cols.forEach((c, i) => { const x = 14 + (i % 4) * 56, y = 34 + Math.floor(i / 4) * 46; const d = (i * 0.45) % 5.4;
        s += `<g><rect x="${x}" y="${y}" width="48" height="36" rx="3" fill="${c}"/><rect x="${x}" y="${y}" width="48" height="36" rx="3" fill="#000" opacity=".55">${anim('opacity', '.55;.55;0;0;.55;.55', '5.4s', `begin="${d}s"`)}</rect><rect x="${x + 4}" y="${y + 26}" width="${14 + (i * 7) % 26}" height="4" fill="#fff" opacity=".8"/></g>`; });
      return s + `<text x="14" y="22" font-family="Inter, sans-serif" font-weight="800" font-size="11" fill="${C.cream}" letter-spacing="2">SERIES · S1–S4</text><rect x="14" y="166" width="212" height="4" fill="#fff" opacity=".25"/><rect x="14" y="166" width="60" height="4" fill="${C.red}">${anim('width', '20;212;20', '9s')}</rect>`;
    },

    kids: () => `
      <rect width="240" height="180" fill="${C.mustard}"/>
      <circle cx="200" cy="40" r="22" fill="${C.cream}" stroke="${C.ink}" stroke-width="3">${animT('rotate', '0 200 40;360 200 40', '12s')}<animate attributeName="r" values="22;25;22" dur="2s" repeatCount="indefinite"/></circle>
      <g stroke="${C.ink}" stroke-width="3" opacity=".8">${Array.from({ length: 8 }, (_, i) => `<line x1="200" y1="40" x2="${200 + Math.cos(i * Math.PI / 4) * 34}" y2="${40 + Math.sin(i * Math.PI / 4) * 34}"/>`).join('')}</g>
      <circle cx="60" cy="110" r="26" fill="${C.red}" stroke="${C.ink}" stroke-width="3">${animT('translate', '0 0;0 -40;0 0', '1.6s', 'calcMode="spline" keySplines=".3 0 .7 1;.3 0 .7 1"')}</circle>
      <rect x="104" y="84" width="48" height="48" rx="6" fill="${C.teal}" stroke="${C.ink}" stroke-width="3">${animT('rotate', '0 128 108;90 128 108;90 128 108;180 128 108;180 128 108', '4s')}</rect>
      <polygon points="190,132 218,132 204,100" fill="${C.plum}" stroke="${C.ink}" stroke-width="3">${animT('translate', '0 0;0 -28;0 0', '2.2s')}</polygon>
      <path d="M0 150 Q60 130 120 150 T240 150 V180 H0 Z" fill="${C.green}"/>
      <g fill="${C.cream}"><circle cx="30" cy="30" r="3"/><circle cx="120" cy="20" r="2"/><circle cx="80" cy="50" r="2.5"/></g>`,

    news: () => `
      <rect width="240" height="180" fill="${C.navy}"/>
      <rect x="0" y="0" width="240" height="120" fill="#243560"/>
      <g fill="${C.cream}" opacity=".2">${Array.from({ length: 7 }, (_, i) => `<rect x="${i * 36}" y="0" width="20" height="120"/>`).join('')}</g>
      <circle cx="90" cy="64" r="20" fill="${C.cream}"/><path d="M50 128 Q90 90 130 128 Z" fill="${C.cream}"/><rect x="82" y="84" width="16" height="14" fill="${C.cream}"/>
      <rect x="150" y="34" width="70" height="50" rx="3" fill="${C.red}"/><text x="185" y="64" text-anchor="middle" font-family="Anton, Impact, sans-serif" font-size="22" fill="${C.cream}">NEWS</text>
      ${liveTag(14, 14)}
      <rect x="0" y="120" width="240" height="24" fill="${C.cream}"/><rect x="0" y="120" width="52" height="24" fill="${C.red}"/><text x="26" y="137" text-anchor="middle" font-family="Inter, sans-serif" font-weight="900" font-size="11" fill="${C.cream}">20:00</text>
      <g clip-path="inset(0 0 0 52px)"><text x="60" y="137" font-family="Inter, sans-serif" font-weight="800" font-size="11" fill="${C.ink}">HET JOURNAAL · LE JOURNAL · BREAKING · WEER · SPORT · CULTUUR · HET JOURNAAL · LE JOURNAL · ${animT('translate', '0 0;-320 0', '9s')}</text></g>
      <rect x="0" y="144" width="240" height="36" fill="${C.ink}"/><text x="14" y="166" font-family="Anton, Impact, sans-serif" font-size="16" fill="${C.cream}" letter-spacing="1">MICKEY TV NIEUWS</text>`,

    music: () => {
      let s = `<rect width="240" height="180" fill="${C.plum}"/>`;
      for (let i = 0; i < 16; i++) { const x = 14 + i * 14, h1 = 20 + ((i * 37) % 90), h2 = 30 + ((i * 53) % 110), h3 = 15 + ((i * 29) % 70); const col = [C.red, C.cream, C.mustard, C.salmon][i % 4];
        s += `<rect x="${x}" y="${150 - h1}" width="9" height="${h1}" fill="${col}">${anim('height', `${h1};${h2};${h3};${h1}`, `${.8 + (i % 5) * .15}s`)}${anim('y', `${150 - h1};${150 - h2};${150 - h3};${150 - h1}`, `${.8 + (i % 5) * .15}s`)}</rect>`; }
      return s + `<circle cx="196" cy="42" r="26" fill="${C.ink}"/><circle cx="196" cy="42" r="8" fill="${C.cream}"/><circle cx="196" cy="42" r="18" fill="none" stroke="${C.cream}" stroke-width="1" opacity=".5">${animT('rotate', '0 196 42;360 196 42', '3s')}</circle><rect x="0" y="152" width="240" height="28" fill="${C.ink}"/><text x="14" y="170" font-family="Anton, Impact, sans-serif" font-size="14" fill="${C.cream}" letter-spacing="2">♪ NOW PLAYING</text>`;
    },

    docu: () => `
      <rect width="240" height="180" fill="${C.teal}"/>
      <circle cx="80" cy="70" r="26" fill="${C.cream}">${animT('translate', '0 30;0 0;0 30', '8s')}</circle>
      <polygon points="0,180 0,120 40,80 90,130 130,70 180,120 220,90 240,120 240,180" fill="${C.navy}"/>
      <polygon points="0,180 0,150 60,110 120,150 170,120 240,160 240,180" fill="${C.ink}" opacity=".75"/>
      <g fill="${C.cream}"><circle cx="30" cy="30" r="2"/><circle cx="150" cy="24" r="1.5"/><circle cx="200" cy="40" r="2"/></g>
      <path d="M 40 60 q 8 -8 16 0 q 8 8 16 0" fill="none" stroke="${C.ink}" stroke-width="2">${animT('translate', '0 0;140 -20;0 0', '10s')}</path>
      <rect x="14" y="150" width="120" height="18" fill="${C.ink}"/><text x="20" y="163" font-family="Inter, sans-serif" font-weight="800" font-size="10" fill="${C.cream}" letter-spacing="2">NATUUR · 4K UHD</text>`,

    // a lone rider on a rolling road: spinning wheels, moving road markings
    cycling: (it) => {
      const wheel = (cx) => `<g><circle cx="${cx}" cy="138" r="13" fill="none" stroke="${C.ink}" stroke-width="3"/><g stroke="${C.ink}" stroke-width="1.2"><line x1="${cx - 12}" y1="138" x2="${cx + 12}" y2="138"/><line x1="${cx}" y1="126" x2="${cx}" y2="150"/><line x1="${cx - 8.5}" y1="129.5" x2="${cx + 8.5}" y2="146.5"/><line x1="${cx - 8.5}" y1="146.5" x2="${cx + 8.5}" y2="129.5"/>${animT('rotate', `0 ${cx} 138;360 ${cx} 138`, '.45s')}</g></g>`;
      return `
      <rect width="240" height="180" fill="${C.salmon}"/>
      <circle cx="188" cy="44" r="22" fill="${C.cream}"/>
      <path d="M0 104 Q50 66 104 96 T206 84 T240 90 V180 H0 Z" fill="${C.mustard}"/>
      <path d="M0 122 Q64 94 132 116 T240 108 V180 H0 Z" fill="${C.green}"/>
      <rect x="0" y="150" width="240" height="30" fill="#3a2c24"/>
      <g stroke="${C.cream}" stroke-width="3" stroke-dasharray="16 12"><line x1="0" y1="166" x2="540" y2="166">${animT('translate', '0 0;-56 0', '.5s')}</line></g>
      <g stroke="#fff" stroke-width="2" stroke-linecap="round" opacity=".7"><line x1="30" y1="112" x2="62" y2="112"/><line x1="18" y1="124" x2="56" y2="124"/><line x1="36" y1="136" x2="64" y2="136"/>${animT('translate', '8 0;-10 0;8 0', '.6s')}</g>
      <g transform="translate(64 0)"><g>${animT('translate', '0 0;0 -1.5;0 0', '.45s')}
        ${wheel(92)}${wheel(136)}
        <path d="M92 138 L108 120 L128 120 L136 138 M108 120 L114 138 L92 138 M128 120 L124 110 L132 110" fill="none" stroke="${C.ink}" stroke-width="3" stroke-linejoin="round"/>
        <path d="M110 116 L126 100" stroke="${C.red}" stroke-width="9" stroke-linecap="round"/>
        <path d="M124 102 L132 112" stroke="${C.cream}" stroke-width="4" stroke-linecap="round"/>
        <path d="M110 116 L116 130" stroke="${C.ink}" stroke-width="5" stroke-linecap="round"/>
        <circle cx="130" cy="94" r="7" fill="${C.cream}" stroke="${C.ink}" stroke-width="2"/><path d="M123 92 Q130 82 138 92 Z" fill="${C.red}" stroke="${C.ink}" stroke-width="1.5"/>
      </g></g>
      ${it.clean ? '' : `<rect x="14" y="14" width="64" height="16" fill="${C.ink}"/><text x="46" y="26" text-anchor="middle" font-family="Anton, Impact, sans-serif" font-size="11" fill="${C.cream}" letter-spacing="1">NOG 20 KM</text>${liveTag(84, 14)}`}`;
    },

    // a car lapping a curved track, red and white kerbs, a chequered flag
    racing: (it) => {
      const track = 'M-20 158 C 40 158, 70 78, 140 78 S 230 40, 270 40';
      let flag = '';
      for (let r = 0; r < 3; r++) for (let c = 0; c < 4; c++) flag += `<rect x="${22 + c * 8}" y="${16 + r * 8}" width="8" height="8" fill="${(r + c) % 2 ? C.ink : '#fff'}"/>`;
      return `
      <rect width="240" height="180" fill="#1b1411"/>
      <path d="${track}" fill="none" stroke="${C.red}" stroke-width="54"/>
      <path d="${track}" fill="none" stroke="#fff" stroke-width="54" stroke-dasharray="10 10"/>
      <path d="${track}" fill="none" stroke="#3b3b3b" stroke-width="44"/>
      <path d="${track}" fill="none" stroke="${C.cream}" stroke-width="2" stroke-dasharray="8 10" opacity=".6"/>
      <g>${A ? `<animateMotion dur="3s" repeatCount="indefinite" rotate="auto" path="${track}"/>` : '<set attributeName="transform" to="translate(118 84) rotate(-12)"/>'}
        <path d="M-16 -5 L10 -5 L19 0 L10 5 L-16 5 Z" fill="${C.red}" stroke="${C.ink}" stroke-width="1.5"/>
        <rect x="-18" y="-9" width="5" height="18" fill="${C.ink}"/><rect x="13" y="-8" width="3" height="16" fill="${C.ink}"/>
        <circle cx="-1" cy="0" r="3" fill="${C.cream}" stroke="${C.ink}" stroke-width="1"/>
      </g>
      <g stroke="${C.cream}" stroke-width="3" stroke-linecap="round"><line x1="20" y1="14" x2="20" y2="62"/></g>
      <g>${animT('skewY', '0;-6;0;6;0', '1.4s')}${flag}</g>
      <rect x="176" y="104" width="54" height="62" fill="${C.ink}" opacity=".85"/>
      ${it.clean ? '' : `<text x="182" y="118" font-family="Inter, sans-serif" font-weight="800" font-size="9" fill="${C.cream}" letter-spacing="1">LAP 12/51</text>`}
      <g font-family="Inter, sans-serif" font-weight="800" font-size="9" fill="#fff">
        <rect x="182" y="124" width="4" height="10" fill="${C.red}"/><text x="190" y="132">P1</text>
        <rect x="182" y="138" width="4" height="10" fill="${C.mustard}"/><text x="190" y="146">P2</text>
        <rect x="182" y="152" width="4" height="10" fill="${C.teal}"/><text x="190" y="160">P3</text>
      </g>`;
    },

    // anything else: a variety stage, curtains and sweeping spotlights
    general: () => {
      const folds = (x0, dir) => Array.from({ length: 4 }, (_, i) => `<path d="M${x0 + dir * i * 12} 18 q${dir * 6} 70 0 150" fill="none" stroke="#000" stroke-width="2" opacity=".25"/>`).join('');
      return `
      <rect width="240" height="180" fill="#1b0c0d"/>
      <g opacity=".22" fill="${C.cream}">
        <polygon points="40,0 70,0 150,150 90,150">${animT('rotate', '-8 55 0;10 55 0;-8 55 0', '6s')}</polygon>
        <polygon points="170,0 200,0 150,150 90,150">${animT('rotate', '8 185 0;-10 185 0;8 185 0', '7s')}</polygon>
      </g>
      <rect x="0" y="146" width="240" height="34" fill="#3a2418"/>
      <ellipse cx="120" cy="148" rx="72" ry="9" fill="${C.cream}" opacity=".3"/>
      <g stroke="${C.ink}" stroke-width="3" stroke-linecap="round"><line x1="120" y1="146" x2="120" y2="104"/><line x1="108" y1="146" x2="132" y2="146"/></g>
      <rect x="114" y="92" width="12" height="16" rx="6" fill="${C.cream}" stroke="${C.ink}" stroke-width="2"/>
      <g fill="${C.cream}">${Array.from({ length: 5 }, (_, i) => `<path d="M${40 + i * 40} 60 l3 7 7 1 -5 5 1 7 -6 -3 -6 3 1 -7 -5 -5 7 -1 Z">${anim('opacity', '.2;1;.2', `${1.4 + i * .3}s`)}</path>`).join('')}</g>
      <path d="M0 0 H62 Q56 90 70 170 H0 Z" fill="${C.red}"/>${folds(10, 1)}
      <path d="M240 0 H178 Q184 90 170 170 H240 Z" fill="${C.red}"/>${folds(230, -1)}
      <path d="M0 0 H240 V18 Q228 30 216 18 Q204 30 192 18 Q180 30 168 18 Q156 30 144 18 Q132 30 120 18 Q108 30 96 18 Q84 30 72 18 Q60 30 48 18 Q36 30 24 18 Q12 30 0 18 Z" fill="${C.maroon}"/>`;
    },
  };

  /* ---------- state ---------- */
  let idx = -1;              // -1 = face
  let off = false;
  let glitching = false;
  let busy = false;
  let pending = null;        // a switch asked for while the static was still on
  let gen = 0;               // bumps whenever the page hands over a new list
  let lastUser = 0;
  let faceMood = 'happy';
  let idleInfo = {};         // what the "now" panel says while the face is on (guide mode)
  let watching = null;       // element Mickey keeps his eyes on (the search box)
  let surfTimer, glitchTimer, glitchAuto, staticRaf, faceReturn = 0;
  let audioCtx;

  const svg = document.createElementNS(NS, 'svg');
  svg.setAttribute('class', 'tv__scene');
  svg.setAttribute('viewBox', '0 0 240 180');
  svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
  screen.insertBefore(svg, canvas);

  function showScene(id, it) {
    svg.innerHTML = id === 'face' ? scenes.face(faceMood) : (scenes[id] || scenes.general)(it || {});
  }

  /* static noise */
  const ctx = canvas.getContext('2d');
  const img = ctx.createImageData(canvas.width, canvas.height);
  const px = new Uint32Array(img.data.buffer);
  function drawStatic() {
    for (let i = 0; i < px.length; i++) { const v = (Math.random() * 255) | 0; px[i] = 0xff000000 | (v << 16) | (v << 8) | v; }
    ctx.putImageData(img, 0, 0);
    if (tv.classList.contains('is-static') || tv.classList.contains('is-glitch')) staticRaf = requestAnimationFrame(drawStatic);
  }
  function staticOn() { tv.classList.add('is-static'); cancelAnimationFrame(staticRaf); drawStatic(); }
  function staticOff() { tv.classList.remove('is-static'); }

  function click() {
    if (reduced) return;
    try {
      audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
      const o = audioCtx.createOscillator(), g = audioCtx.createGain();
      o.type = 'square'; o.frequency.value = 180; g.gain.value = 0.03;
      o.connect(g); g.connect(audioCtx.destination);
      o.start(); g.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.06); o.stop(audioCtx.currentTime + 0.07);
    } catch (_) {}
  }

  function setInfo(i) {
    const it = items[i];
    const n = i < 0 ? '00' : String(i + 1).padStart(2, '0');
    if (numEl) numEl.textContent = n;
    if (osd) osd.textContent = it ? (it.osd || 'CH ' + n) : (idleInfo.osd || 'CH 00');
    if (label) { label.hidden = !it; if (it) label.textContent = it.label || it.name; }
    // the home panel keeps the last channel while the face is on; the guide panel says what Mickey is doing
    const info = it || (guided ? idleInfo : null);
    if (info) {
      if (kickerEl) kickerEl.textContent = info.kicker || '';
      if (nameEl) { nameEl.textContent = info.name || ''; if (info.href && nameEl.tagName === 'A') nameEl.href = info.href; }
      if (metaEl) metaEl.textContent = info.meta || '';
    }
    dots.forEach((d, j) => d.setAttribute('aria-pressed', String(j === i)));
    knobs[0] && knobs[0].style.setProperty('--turn', (i + 1) * 45 + 'deg');
    knobs[1] && knobs[1].style.setProperty('--turn', (i + 1) * -30 + 'deg');
  }

  function go(i, user) {
    if (off) return;
    if (busy) { pending = [i, user]; return; }
    busy = true;
    const g = gen;
    if (user) { lastUser = Date.now(); click(); }
    fixGlitch(true);
    staticOn();
    setTimeout(() => {
      busy = false;
      if (g === gen) {   // a list handed over meanwhile makes this switch moot; the pending one follows
        idx = items[i] ? i : -1;
        showScene(idx < 0 ? 'face' : items[idx].id, items[idx]);
        setInfo(idx);
        staticOff();
        if (idx < 0 && watching) lookAt(watching);
        tv.dispatchEvent(new CustomEvent('mtv:tv', { bubbles: true, detail: { index: idx, item: items[idx] || null, user: !!user } }));
      }
      if (pending) { const [pi, pu] = pending; pending = null; if (pi !== idx || g !== gen) return go(pi, pu); }
      if (tv.classList.contains('is-static')) staticOff();
      scheduleGlitch();
    }, reduced ? 60 : 380);
  }

  const next = (dir, user) => {
    if (guided && !items.length) return;
    let i = idx + dir;
    if (i >= items.length) { i = user || guided ? 0 : -1; }
    if (i < -1 || (guided && i < 0)) i = items.length - 1;
    if (!user && idx >= 0 && ++faceReturn % 4 === 0) i = -1;   // Mickey peeks back every few channels
    go(i, user);
  };

  /* glitch: a random picture fault the visitor fixes by tapping the set */
  function scheduleGlitch() {
    clearTimeout(glitchTimer);
    if (reduced || idx < 0 || !opts.surf) return;
    glitchTimer = setTimeout(startGlitch, 14000 + Math.random() * 16000);
  }
  function startGlitch() {
    if (off || busy || idx < 0 || document.hidden) return scheduleGlitch();
    glitching = true;
    tv.classList.add('is-glitch');
    cancelAnimationFrame(staticRaf); drawStatic();
    glitchAuto = setTimeout(() => fixGlitch(false), 9000);
  }
  function fixGlitch(silent) {
    clearTimeout(glitchAuto);
    if (!glitching) return;
    glitching = false;
    tv.classList.remove('is-glitch');
    if (!silent) scheduleGlitch();
  }
  function bonk() {
    tv.classList.remove('is-bonk'); void tv.offsetWidth; tv.classList.add('is-bonk');
    setTimeout(() => tv.classList.remove('is-bonk'), 600);
  }

  /* auto-surf while nobody is touching it */
  function surf() {
    clearInterval(surfTimer);
    if (reduced || !opts.surf) return;
    surfTimer = setInterval(() => { if (!document.hidden && Date.now() - lastUser > 16000 && !glitching && !watching && items.length) next(1, false); }, guided ? 7000 : 9000);
  }

  /* ---------- wiring ---------- */
  knobs.forEach((k) => k.addEventListener('click', (e) => { e.stopPropagation(); next(parseInt(k.dataset.tvKnob, 10), true); }));
  dots.forEach((d) => d.addEventListener('click', () => go(parseInt(d.dataset.tvGo, 10), true)));
  const powerBtn = tv.querySelector('[data-tv-power]');
  if (powerBtn) powerBtn.addEventListener('click', (e) => {
    e.stopPropagation(); click();
    off = !off; tv.classList.toggle('is-off', off); lastUser = Date.now();
    if (!off) { off = false; go(idx, false); }
  });
  const top = tv.querySelector('[data-tv-top]');
  const tap = (e) => {
    e.stopPropagation();
    lastUser = Date.now();
    bonk(); click();
    if (glitching) { fixGlitch(false); staticOn(); setTimeout(staticOff, 220); return; }
    if (off) { powerBtn && powerBtn.click(); return; }
    next(1, true);
  };
  if (top) top.addEventListener('click', tap);
  tv.querySelector('.tv__body').addEventListener('click', (e) => { if (!e.target.closest('button')) tap(e); });

  /* eyes follow the pointer while Mickey's face is on — or the element he was asked to watch */
  const clamp = (v) => Math.max(-1, Math.min(1, v));
  function aim(x, y) {
    const r = screen.getBoundingClientRect();
    const dx = clamp((x - (r.left + r.width / 2)) / r.width), dy = clamp((y - (r.top + r.height / 2)) / r.height);
    svg.querySelectorAll('[data-pupil]').forEach((p) => { p.setAttribute('transform', `translate(${dx * 9} ${dy * 10})`); });
  }
  function lookAt(el) { const b = el.getBoundingClientRect(); aim(b.left + Math.min(b.width, 260) / 2, b.top + b.height / 2); }
  document.addEventListener('pointermove', (e) => {
    if (idx !== -1 || off || watching) return;
    aim(e.clientX, e.clientY);
  }, { passive: true });
  function blink() {
    if (idx === -1 && !off) { const lid = svg.querySelector('[data-lid]'); if (lid) { lid.setAttribute('height', '80'); setTimeout(() => lid.setAttribute('height', '0'), 140); } }
    setTimeout(blink, 2400 + Math.random() * 3200);
  }

  /* ---------- the channel list page drives the set ---------- */
  if (guided) {
    const handOver = (list, o, info, mood) => {
      gen++; items = list || []; opts = o || {}; idleInfo = info || {}; faceMood = mood || 'happy';
      faceReturn = 0; clearInterval(surfTimer); fixGlitch(true);
    };
    MTV.tv = {
      /* a new list: search results or upcoming events; start < 0 = face first */
      play(list, start, o) {
        handOver(list, o, (o && o.idle) || {});
        if (start >= 0) lastUser = Date.now();   // a picked item stays on a while before surfing resumes
        go(start >= 0 && items[start] ? start : -1, false);
        surf();
      },
      /* follow the visitor's pointer / focus through the list */
      tune(i) { lastUser = Date.now(); if (items[i] && (i !== idx || busy)) go(i, false); },
      /* nothing to show: Mickey's face with a line of help */
      idle(info) { handOver([], {}, info); go(-1, false); },
      /* the search found nothing */
      noSignal(info) { handOver([], {}, info, 'sad'); go(-1, false); },
      /* a request is on its way */
      searching() { if (!off && !busy) { fixGlitch(true); staticOn(); } },
      /* keep the eyes on the search box while the visitor types (null = let go) */
      watch(el) {
        watching = el || null;
        lastUser = Date.now();
        if (!watching) return;
        if (idx !== -1 && !busy) { faceMood = 'happy'; go(-1, false); }
        else if (idx === -1) lookAt(watching);
      },
    };
  }

  /* boot: Mickey wakes up, then channel 1 (the channel list page takes over from here) */
  showScene('face'); setInfo(-1);
  if (!reduced) blink();
  if (!guided) {
    // the first channel switch paints static and builds a scene: keep it out of the page load
    const wake = () => setTimeout(() => go(0, false), reduced ? 100 : 2200);
    if (document.readyState === 'complete') wake();
    else window.addEventListener('load', wake, { once: true });
    surf();
  }
  document.addEventListener('visibilitychange', () => { if (document.hidden) { fixGlitch(true); } });
})();
