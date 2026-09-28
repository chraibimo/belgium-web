<?php
/**
 * Copy to includes/mail-config.local.php (gitignored) and fill in the real
 * values. Anything left undefined falls back to the defaults in app/config.php.
 *
 * With SMTP_HOST empty the site uses PHP's mail(); most hosts deliver that
 * poorly, so use the mailbox's SMTP details (Hostinger: smtp.hostinger.com,
 * port 587, the mailbox as user, its password). MAIL_FROM must be that mailbox.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

define('MAIL_TO',   'contact@mickeytv.be');
define('MAIL_FROM', 'contact@mickeytv.be');
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'contact@mickeytv.be');
define('SMTP_PASS', 'change-me');
