<?php
// Defense in depth: if app/.htaccess is ever not honored, refuse direct web hits.
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

/**
 * Site configuration for MICKEY TV (Belgium).
 *
 * The site is domain-independent: every URL in a page is root-relative and the
 * canonical / og:url / sitemap use the request's Host when it is whitelisted
 * below, else DEFAULT_HOST. Put the real domain(s) here before going live.
 */

// Hosts allowed to be reflected into canonical tags / og:url / sitemap.
// Any other Host header falls back to DEFAULT_HOST.
const ALLOWED_HOSTS = ['mickeytv.be', 'www.mickeytv.be', 'localhost', '127.0.0.1'];
const DEFAULT_HOST  = 'mickeytv.be';

// Languages: the first one is the default and lives at "/", the others under "/<code>/".
const SITE_LANGS   = ['nl', 'fr'];
const DEFAULT_LANG = 'nl';

// Every submission is also appended to app/data/submissions.jsonl regardless of
// whether the email leaves the box, so no lead is ever lost.
define('DATA_DIR', __DIR__ . '/data');

// Mail settings: includes/mail-config.local.php (gitignored) overrides these
// committed defaults, so real SMTP credentials stay out of the repo.
// See includes/mail-config.example.php.
$__inc = defined('INCLUDES_DIR') ? INCLUDES_DIR : dirname(__DIR__) . '/includes';
if (is_file("$__inc/mail-config.local.php")) require "$__inc/mail-config.local.php";
if (!defined('MAIL_TO'))   define('MAIL_TO',   'contact@mickeytv.be');   // where forms are emailed  (TODO: real inbox)
if (!defined('MAIL_FROM')) define('MAIL_FROM', 'contact@mickeytv.be');   // mail hosts want From: to match the SMTP mailbox
if (!defined('SMTP_HOST')) define('SMTP_HOST', '');   // '' = use PHP mail()
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', '');
if (!defined('SMTP_PASS')) define('SMTP_PASS', '');

// n8n webhook settings: includes/n8n-config.local.php (gitignored) holds the
// shared secret /api/hook.php sends upstream. Absent, the proxy fails closed.
// See includes/n8n-config.example.php.
if (is_file("$__inc/n8n-config.local.php")) require "$__inc/n8n-config.local.php";

// Third-party ids. Empty = the integration is off (nothing is loaded).
// includes/services-config.local.php (gitignored) can override these too.
if (is_file("$__inc/services-config.local.php")) require "$__inc/services-config.local.php";
if (!defined('FB_PIXEL_ID'))     define('FB_PIXEL_ID', '');                 // Meta pixel id (loads only after cookie consent)
// tawk.to live chat, bottom-right on every page. These are the ids of the
// owner's existing tawk property (the one primotv.ca uses) so the widget works
// from day one; create a dedicated Mickey TV property in the tawk dashboard and
// override both in includes/services-config.local.php when it exists.
if (!defined('TAWK_PROPERTY'))   define('TAWK_PROPERTY', '685431cd176e70190bce3f5c');
if (!defined('TAWK_WIDGET'))     define('TAWK_WIDGET', '1iu4e5320');
if (!defined('TRUSTPILOT_URL'))  define('TRUSTPILOT_URL', '');              // public profile URL, once the brand has one
if (!defined('CHECKOUT_SHEET'))  define('CHECKOUT_SHEET', '');              // Google Sheet id holding "plan-months-devices | checkout url" rows
if (!defined('CHECKOUT_SHEET_GID')) define('CHECKOUT_SHEET_GID', '0');
if (!defined('FOMO_FEED_URL'))   define('FOMO_FEED_URL', '');               // JSON feed of REAL recent orders (see public/assets/js/wheel.js); it always wins over the generated pool below
// Activity toasts, bottom-left. TRUE makes the site show GENERATED purchases
// (names/cities/plans from includes/content/<lang>/activity.php) when no real
// feed is configured — asked for by the owner on 2026-09-16. Be aware that
// presenting invented orders as real is a misleading commercial practice under
// EU/Belgian consumer law (Book VI, Code of Economic Law); set it to false to
// show nothing unless FOMO_FEED_URL points at real orders.
if (!defined('FOMO_SYNTHETIC'))  define('FOMO_SYNTHETIC', true);
