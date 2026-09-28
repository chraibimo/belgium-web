# MICKEY TV — project notes for Claude

Plain-PHP (8.1+) bilingual site, no framework, no build step for pages. Read README.md first.

- **Languages**: `SITE_LANGS` in `app/config.php`; `nl` is default at `/`, `fr` at `/fr/`. Every page has
  a slug per language in `includes/routes.php`; posts carry their own slug and share a file *key* across
  languages (`content/nl/posts/x.php` ↔ `content/fr/posts/x.php`). Never hardcode a URL in templates —
  use `url('key')`, `post_url($post)`, `tag_url($tag)`. In post bodies (plain HTML) root-relative links
  are fine but must be per language (`/prijzen/` vs `/fr/tarifs/`).
- **Copy lives in `includes/content/<lang>/`**, templates in `includes/pages/`. `t('key')` for UI
  strings (`strings.php`), `content('name')` for page arrays, `content('js')` is exposed to scripts as
  `window.MTV.t`. Add a string to BOTH languages.
- **Speed & access** (100/100/100/100 in Lighthouse, mobile and desktop, 2026-09-28): keep it that way.
  The palette carries **WCAG AA**: `--red` is `#c4252d` so cream text on it clears 4.5:1, `--green` is
  `#237a3b` for white text, `--faint` is at .55, and on red surfaces `--muted`/`--faint` fall back to
  full cream (dimmed cream on red never clears AA - see the `.band--red, .hero, .phero…` rule and the
  `.plan--vip … { opacity: 1 }` one). Don't lighten the red or dim text on it without re-checking.
  Page HTML is gzipped in `public/index.php`; below-fold sections get `content-visibility` under 900 px
  (`#pricing`/`#form` are excluded - they are the only in-page anchor targets); poster art draws 600 px
  before it scrolls into view and the hero's blurred art waits for idle (`posters.js`); the logo river
  is `loading="lazy"`; the fonts are subset (`build/subset-fonts.mjs`, originals in `build/fonts-full/`).
  Measure with `build/dev-server.php` (below), never against `php -S … -t public`, which serves assets
  raw and uncached and costs ~8 points on mobile.
- **Design**: one stylesheet `public/assets/css/site.css`. Tokens at the top; the KINOROY look is
  Anton display type with `.hard-shadow`, red/cream/black bands, `.btn` with offset shadow, generated
  poster art (`poster()` in bootstrap + `posters.js`). Don't add a CSS framework.
- **The TV** (`tv.js`, `.tv` in CSS, markup in `partials/tv.php`, shared by two pages): on the home page
  channels come from `home.php` → `hero.channels`; scenes are SVG strings in `tv.js` keyed by id. Face,
  knobs, bonk-to-fix glitch, power, auto-surf. Keep it dependency-free, and keep every picture drawn in
  code: no channel logos, crests or broadcast images on the screen (the owner's copyright rule).
  On the channel list (`data-tv-mode="guide"`) `channels.js` drives it through `window.MTV.tv`
  (`play`, `tune`, `idle`, `noSignal`, `searching`, `watch`): it tunes to the result under the pointer,
  shows a sad face on no results, watches the box while typing and surfs the upcoming events when idle;
  every switch fires `mtv:tv` so the page can light up the matching card. Items marked `clean`
  (events) get scenes without the invented score / distance / lap.
- **Upcoming events** (`includes/events.php`, strip under the channel search while the box is empty):
  real fixtures only, each with its `source`, times in Belgian time. Over events drop out in the browser
  (the page can be cached for an hour); the strip hides when none are left. Never add unverified ones.
- **Forms** post to `/api/forms.php` (`app/handlers/ajax.php`): `form=contact|newsletter|lead`, JSON
  `{ok,message,errors,redirect}`. Honeypot `company`, `started` timestamp, per-IP throttle, everything
  logged to `app/data/submissions.jsonl` before mailing. Referral + wheel go to `/api/hook.php?t=`,
  which logs them too (and mails referrals) before forwarding to n8n if configured.
- **Page cache** (`includes/page-cache.php`) keys on the newest mtime of includes/, content, assets
  and config, so edits are live immediately; `app/data/pagecache/` can always be deleted.
- **Third-party**: pixel, Trustpilot and the checkout sheet stay off until set in
  `includes/services-config.local.php`; the **tawk chat** (bottom-right) and the **activity toasts**
  (bottom-left) ship on. `app/config.php` defaults `TAWK_PROPERTY/TAWK_WIDGET` to the owner's
  primotv.ca property until a Mickey TV one exists. The chat loads once the cookie banner is answered
  either way (own support channel) plus a 6 s post-load fallback, in `loadChat()` in `site.js`; the
  pixel still needs "accept all". The cookie banner appears whenever one of them is on.
- **Chat launcher is ours** (`#chatbtn`, `render_chat_launcher()` + `.chatbtn` in CSS): tawk's own
  bubble never appears - `Tawk_API.onBeforeLoad` calls `hideWidget()` before tawk draws anything (an
  `onLoad`-only hide let it flash first), with `.tawk-min-container` & co. hidden in CSS as the safety
  net; never hide `.tawk-max-container`, that is the panel itself. Our cream disc with the TV mark,
  hard shadow, unread badge and online dot calls `maximize()`.
  The button is `hidden` until `Tawk_API.onLoad` fires, so it never shows a chat that cannot open. The
  chat **panel** is a cross-origin iframe - its colours can only be changed in the tawk dashboard
  (Administration - Chat Widget), never from `site.css`.
- **The bottom-right corner belongs to the chat button.** The disc is 62 px on desktop and 68 px below
  900 px (the owner asked for a bigger tap target). Below 640 px the toast column is a narrow card
  (`width: min(62vw, 228px)`, ~219x53 px) and the wheel dock stops 96 px short of the right edge; the
  activity toast is compacted there (26 px avatar, 10-11 px type, no CTA text - the whole toast is
  tappable). While a won code is in the dock the activity toasts are hidden and the copy confirmation
  lifts to `bottom: 126px`. Keep that corner free.
- **Wheel reopen pill** (`#wheel-reopen`, markup in `render_wheel()`, logic in `wheel.js`): closing or
  declining the wheel without a prize raises a pill under the header that reopens it; it follows the
  visitor across pages for the session (`mtv:wheel:skip`) until they spin or dismiss it
  (`mtv:wheel:pilloff`). It replaced the old one-shot "nudge" toast — the bottom-left corner belongs
  to the activity toasts now.
- **Channel data**: `public/assets/data/channels-pack.js` is the source; `php build/channel-index.php`
  regenerates `app/channel-index.txt` + `channel-stats.json` (Belgium ranks first). The pack is not
  served: the home box and the channels page (`channels.js`) both search `/api/channels.php?q=&n=`,
  and the channels page lists nothing until the visitor types (the user's choice - keep it that way).
- **Checks**: `php build/verify.php http://127.0.0.1:8130` after content changes; screenshots via
  headless Chrome at 1440 and 390 px for design changes. Run the site with
  `php -S 127.0.0.1:8130 -t build/devroot build/dev-server.php` (gzip + immutable caching, like the
  live host). Lighthouse: `npx lighthouse <url> --preset=desktop` and without it for mobile; the
  mobile score only matches production over HTTP/2, so measure through an HTTP/2 front end or the
  live domain. `/bedankt/`, `/chat/` and `/zoeken/` are `noindex` on purpose - Lighthouse scores their
  SEO ~69 for it, which is correct, and they are out of the sitemap.
- **Honesty rules** (README "Notes on honesty") are deliberate: no fake reviews, no struck prices
  without a real reference. Don't reintroduce them. Two exceptions the owner asked for (2026-09-16):
  the **per-session promo countdown** (`pricing.promo.session_seconds` in `data.php`, `0` = the real
  cycle) and the **generated activity toasts** — pools in
  `includes/content/{nl,fr}/activity.php`, switch `FOMO_SYNTHETIC` in `app/config.php`, real feed
  (`FOMO_FEED_URL`) wins when set. Keep the switch and the legal note in place; if asked to make
  other invented claims, say once that it is a misleading practice under Book VI of the Belgian Code
  of Economic Law, then do what the owner decides.
- Secrets never go in git: `includes/*.local.php` and `app/data/` are ignored.
