<?php
/**
 * Copy to includes/services-config.local.php (gitignored). Every id left empty
 * switches that integration off - nothing is loaded and no consent is asked
 * for it. See app/config.php for the defaults.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

define('FB_PIXEL_ID',       '');            // Meta pixel; loads only after the visitor accepts marketing cookies
// tawk.to live chat (bottom-right). app/config.php already defaults to the
// owner's existing property; set these only to point the widget at a dedicated
// Mickey TV property. '' switches the chat off entirely.
define('TAWK_PROPERTY',     '685431cd176e70190bce3f5c');  // tawk.to property id (the /chat/ page redirects to the direct chat link)
define('TAWK_WIDGET',       '1iu4e5320');                 // tawk.to widget id
define('TRUSTPILOT_URL',    '');            // e.g. https://www.trustpilot.com/review/mickeytv.be
define('CHECKOUT_SHEET',    '');            // Google Sheet id (published to the web) with rows: key | url, key = plan-months-devices, e.g. vip-3-1
define('CHECKOUT_SHEET_GID','0');
define('FOMO_FEED_URL',     '');            // JSON feed of real recent orders for the activity toasts

// Activity toasts: false = show nothing unless FOMO_FEED_URL has real orders.
// true = generate them from includes/content/<lang>/activity.php (see the note
// in app/config.php about EU/Belgian consumer law before leaving this on).
define('FOMO_SYNTHETIC',    true);
