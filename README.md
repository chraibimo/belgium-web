# MICKEY TV — Belgian IPTV store (NL + FR)

A plain-PHP website for **Mickey TV**: the KINOROY visual concept (red / cream / black, giant
condensed type with hard shadows, flat retro poster art, a living vintage TV as mascot) carrying
every feature of the Primo store — pricing tickets with checkout, channel check + channel-list search,
installation assistant, contact/newsletter/lead forms, promo wheel, referral programme, blog with
tag archives, legal pages, site search, sitemap, page cache, security headers.

Dutch is the default language at `/`, French lives under `/fr/`.

## Run it locally

```bash
php -S 127.0.0.1:8130 -t public public/index.php
```

Open http://127.0.0.1:8130/ (Dutch) or http://127.0.0.1:8130/fr/ (French).
`php build/verify.php http://127.0.0.1:8130` sweeps every URL in the sitemap.

## Before going live — fill these in

| What | Where |
|---|---|
| Domain(s) | `ALLOWED_HOSTS` / `DEFAULT_HOST` in `app/config.php` |
| Brand phone, WhatsApp, e-mail, legal entity name | `includes/data.php` (`brand`) |
| Where forms are e-mailed + SMTP | copy `includes/mail-config.example.php` → `mail-config.local.php` |
| Meta pixel, tawk.to chat, Trustpilot URL, checkout sheet, order feed | copy `includes/services-config.example.php` → `services-config.local.php` |
| n8n webhooks (wheel winners, referral claims, Meta CAPI) | copy `includes/n8n-config.example.php` → `n8n-config.local.php` |
| Prices (EUR), refund days, promo cycle, wheel codes | `includes/data.php` (`pricing`, `wheel`) |
| "Straks live" events on the channel list (matches, races) | `includes/events.php` |
| Company number + address in the legal pages | `includes/content/{nl,fr}/legal-*.php` (marked "aan te vullen" / "à compléter") |
| Real customer reviews | `includes/content/{nl,fr}/reviews.php` (`wall`, `featured`) |

The Meta pixel stays off until it is configured. The tawk.to chat (bottom-right) and the activity
toasts (bottom-left) are **on** by default: the chat uses the owner's existing tawk property until a
Mickey TV one is created, and the toasts are generated — see "Notes on honesty" below. The chat loads
after the visitor answers the cookie banner (either answer — it is our own support channel); the
pixel needs an explicit "accept all".

The chat **button** is ours, drawn in the site style (cream disc, TV mark, hard shadow, unread badge,
online dot): tawk's own bubble is suppressed before it can be drawn (`onBeforeLoad` + a CSS rule on
`.tawk-min-container`, so it never flashes) and the button calls `Tawk_API.maximize()`. On phones the
purchase toasts and the prize dock stop short of that corner so the button always stays clear. The chat
**panel** that opens is a tawk iframe, so no stylesheet here can touch it — set its colours in the
tawk dashboard under *Administration → Chat Widget* (widget colour `#d32830`, dark text), and note
that doing so on the shared property also changes the primotv.ca widget.

## How it is organised

| Where | What |
|---|---|
| `public/index.php` | Front controller: security headers, API routes, redirects, robots/sitemap, language + page resolution, page cache |
| `public/assets/` | `css/site.css` (the whole design), `js/*.js` (site, posters, tv, pricing, channel-check, channels, wheel), fonts, icons, `data/channels-pack.js` |
| `includes/routes.php` | One entry per page: slug in each language + template |
| `includes/pages/*.php` | Templates (home, pricing, channels, tutorial, contact, faqs, about, reviews, referral, chat, blog, post, archive, search, legal, thankyou, 404) |
| `includes/content/nl/`, `includes/content/fr/` | All copy per language; `posts/*.php` are the blog articles (same file name in both languages = translations of each other) |
| `includes/layout.php`, `includes/bootstrap.php` | Page chrome (head, header, footer, consent, wheel) and helpers (`t()`, `url()`, `content()`, `posts()`, `poster()`) |
| `app/handlers/` | `ajax.php` forms, `channels.php` channel search API, `search.php` site search, `hook.php` n8n proxy |
| `app/data/` | Runtime: `submissions.jsonl` (every form/wheel/referral lead), page cache, throttle counters — never in git |
| `build/` | `channel-index.php` (rebuild the search index from the pack), `verify.php`, `package.php` (deploy zip) |

### Adding or editing content

- **A blog post**: add `includes/content/nl/posts/<key>.php` and `includes/content/fr/posts/<key>.php`
  (same key). Each returns `slug, title, date, minutes, topic, tags, excerpt, poster, body` (HTML).
  Tag archives, the sitemap, hreflang links and the search index follow automatically.
- **A page's copy**: edit `includes/content/<lang>/<page>.php`.
- **Colours**: tokens at the top of `site.css`. `--red`, `--green` and the dimmed text tokens are set
  where they are so every text/background pair clears WCAG AA (4.5:1); re-check contrast if you change
  them, or the accessibility score drops.
- **Upcoming events** ("Straks live" on the channel list): add entries to `includes/events.php` —
  start in Belgian time, duration, sport, title per language and the `source` you checked it against.
  Finished events disappear by themselves; `search` (optional) adds a "find the channel" link. Only
  list fixtures confirmed by an official source, and keep it topped up: an empty file hides the strip.
- **UI strings** (menu, buttons, form messages): `includes/content/<lang>/strings.php`, `js.php`.
- **Channel list**: replace `public/assets/data/channels-pack.js` (the `window.PTV_PACK / PTV_CATS / PTV_META`
  block) and run `php build/channel-index.php`. Both the home-page box and the channel-list page search
  the generated `app/channel-index.txt` through `/api/channels.php` (nothing is listed until the visitor
  types); the pack itself is never sent to the browser.
- **Channel logos** on the home page: `app/channel-logos.json` + `public/static/media/channels/`.

### Checkout

Order buttons look up `plan-months-devices` (e.g. `vip-3-1`) in the Google Sheet named by
`CHECKOUT_SHEET` (rows: `key | url`, https URLs on Shopify/Mollie/Stripe/PayPal or your own domain).
Until the sheet exists, the button opens WhatsApp with the chosen plan, term, devices and total
pre-filled, so orders work from day one. Point your checkout's "thank you" URL at `/bedankt/` (`/fr/merci/`).

### Deploying (Hostinger hPanel File Manager)

`php build/package.php` writes `build/mickeytv-deploy.zip` with the same flattened schema as
primotv.ca: `public/` becomes the docroot and `app/` + `includes/` sit inside it. The zip has no
top-level folder, so extracted in `public_html` it gives exactly this:

```
public_html/
├── .htaccess          routing + security headers; also denies app/, includes/, *.jsonl, *.local.php
├── index.php          front controller (finds app/ and includes/ next to it)
├── assets/            css/ js/ fonts/ img/ data/
├── static/media/      channel logos
├── app/               config.php, redirects.php, handlers/, channel-index.txt, cacert.pem
│   ├── .htaccess      Require all denied
│   └── data/          runtime: submissions.jsonl, rl_*.txt, pagecache/, search-*.json (PHP creates them)
└── includes/          bootstrap, layout, routes, pages/, content/nl|fr/, email-templates/, *.example.php
    └── .htaccess      Require all denied
```

Steps: hPanel → **Files → File Manager** → open `public_html` → **Upload** the zip →
right-click it → **Extract** (into `public_html`) → delete the zip. Then create
`includes/mail-config.local.php` from the `.example.php` next to it (and, if used,
`services-config.local.php`, `n8n-config.local.php`) — the `*.local.php` files are never in the zip,
so re-uploading a new zip never overwrites them. `app/data/` is likewise left alone on re-deploys.
Pages are gzipped by PHP itself, so compression does not depend on the host's mod_deflate.
Any Apache/LiteSpeed host with PHP 8.1+ works; `php build/verify.php https://your-domain` sweeps
every URL afterwards.

## Notes on honesty (Belgium / EU rules)

- The promo countdown restarts at `pricing.promo.session_seconds` (5d 07:52:35) for every browser
  session — a per-visit timer, asked for by the owner on 2026-09-16. Set it to `0` to run the real
  seven-day cycle (`cycle_days` + `anchor`) instead. A deadline that restarts per visitor while the
  copy calls it "this round" is a misleading commercial practice under Book VI of the Belgian Code
  of Economic Law.
- No struck-through "was" price is shown unless `reference_multiplier` is set — and then it must be a
  price actually charged in the previous 30 days (Belgian Code of Economic Law, book VI).
- Activity toasts ("X nam Elite VIP…", bottom-left) are **generated** — the site owner asked for them
  on 2026-09-16. Names, towns and plans come from `includes/content/{nl,fr}/activity.php`; a real
  order feed (`FOMO_FEED_URL`) always takes precedence, and `FOMO_SYNTHETIC = false` in
  `app/config.php` turns the invented ones off. Be aware that showing invented orders as if they were
  real is a misleading commercial practice under EU/Belgian consumer law (Book VI, Code of Economic
  Law) and is the kind of thing the FOD Economie inspection acts on.
- Review sections stay hidden until real reviews are added; the sample entries render only on localhost.
- The wheel uses honest weighted odds from the wedge weights; the rules line says so. Closing it
  without spinning raises a small pill at the top of the page that reopens it (`#wheel-reopen`),
  for the rest of the session or until the visitor dismisses the pill itself.
- Legal pages are drafts written for Belgian consumer law and GDPR — have a lawyer check them.
