<?php
/**
 * /api/hook.php - the site's own front door for the n8n webhooks.
 *
 * The browser posts here; this forwards to n8n with the shared secret from
 * includes/n8n-config.local.php. The point is that the secret is added on the
 * server: a token shipped to the browser is not a secret, it is a published
 * password, so the only way to authenticate these calls is to stop the browser
 * making them. Three things follow from that:
 *
 *   - the n8n URLs are no longer in the page source of every visitor
 *   - requests are rate limited and size capped before they cost an n8n run
 *   - n8n can require the header and refuse everything else
 *
 * It is NOT an open forwarder: ?t= is matched against the N8N_HOOKS whitelist,
 * so a visitor cannot point it at a URL of their own.
 *
 * Failure is quiet on purpose. These calls sit behind a popup, a claim form and
 * a tracking beacon; a visitor who trips the throttle or arrives without the
 * config in place gets the same shaped JSON as a success, because telling an
 * attacker which of those happened is the only thing a loud error achieves.
 * The one exception is an unknown ?t=, which is a coding mistake, not traffic.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

require_once INCLUDES_DIR . '/client-ip.php';
require_once INCLUDES_DIR . '/mailer.php';          // form_throttle_ok()

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

/** Answer and stop. Shape matches what the three callers already expect. */
function hook_out(int $code, array $body): never {
    http_response_code($code);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') hook_out(405, ['ok' => false]);

$target = (string)($_GET['t'] ?? '');
$known  = ['wheel', 'capi', 'referral'];
$hooks  = defined('N8N_HOOKS') ? N8N_HOOKS : array_fill_keys($known, '');
if ($target === '' || !isset($hooks[$target])) hook_out(404, ['ok' => false]);

$body = file_get_contents('php://input', false, null, 0, 32768 + 1);   // 32 KB
if ($body === false) $body = '';
if (strlen($body) > 32768) hook_out(413, ['ok' => false]);

/* Leads never depend on n8n being up: a referral claim or a wheel winner is
   appended to app/data/submissions.jsonl first (and a referral is mailed), so
   the site works fully before the automation exists. Throttled like the forms. */
$ip = client_ip();
if ($target !== 'capi') {
    $data = json_decode($body, true);
    if (is_array($data) && form_throttle_ok($ip, 12, 600, 'lead_' . $target . '_')) {
        $fields = array_map(fn($v) => is_scalar($v) ? mb_substr(trim((string)$v), 0, 2000) : json_encode($v), array_slice($data, 0, 30, true));
        log_submission($target, $fields);
        if ($target === 'referral') {
            require_once INCLUDES_DIR . '/email_render.php';
            $note = email_notification('Referral claim', 'New "tell a friend" claim', [
                'New customer'    => ($fields['fullName'] ?? '') . ' <' . ($fields['email'] ?? '') . '>',
                'Order number'    => $fields['orderNumber'] ?? '',
                'Referred by'     => ($fields['referrerFullName'] ?? '') . ' <' . ($fields['referrerEmail'] ?? '') . '>',
                'Language'        => $fields['lang'] ?? '',
            ], '', ['Page' => $fields['pageUrl'] ?? '', 'From IP' => $ip, 'Received' => date('c')]);
            send_mail(site_data()['brand']['name'] . ' referral claim: ' . ($fields['orderNumber'] ?? ''), $note['text'], (string)($fields['email'] ?? ''), $note['html']);
        }
    }
}

/* Not configured yet (no includes/n8n-config.local.php): fail closed rather
   than posting to n8n without the header it is about to start requiring. */
if (!defined('N8N_SECRET') || N8N_SECRET === '' || $hooks[$target] === '') hook_out(200, ['ok' => true]);

/* Same-origin only. Browsers attach Origin to cross-origin POSTs, so a foreign
   page's fetch is refused here; a request with no Origin at all (curl, a
   beacon on some engines) still has to get past the throttle and the secret. */
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    $host = parse_url($origin, PHP_URL_HOST) ?: '';
    if (!in_array($host, ALLOWED_HOSTS, true)) hook_out(403, ['ok' => false]);
}

/* Per-IP budgets, in their own counter namespace so a page full of tracking
   beacons can never eat the contact form's allowance. The tracking endpoint
   fires several times per visit by design; the two lead endpoints should not. */
$budget = $target === 'capi' ? 120 : 12;
if (!form_throttle_ok($ip, $budget, 600, 'hook_' . $target . '_')) {
    hook_out(200, ['ok' => true]);      // byte-identical to a success, like the forms
}

$url = $hooks[$target];
$ct  = $_SERVER['CONTENT_TYPE'] ?? 'application/json';
if (!preg_match('~^[A-Za-z0-9!#$&^_.+-]+/[A-Za-z0-9!#$&^_.+-]+~', $ct)) $ct = 'application/json';

$headers = [
    'Content-Type: ' . $ct,
    N8N_HEADER . ': ' . N8N_SECRET,
    'X-Primo-Origin: ' . (defined('DEFAULT_HOST') ? DEFAULT_HOST : ''),
    'X-Forwarded-For: ' . $ip,          // n8n still sees who sent it
];

$status = 0;
$out    = '';

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_TIMEOUT        => 8,     // bounded: a slow n8n must not hold a PHP worker
        CURLOPT_FOLLOWLOCATION => false,
        // Certificate verification stays ON. Turning it off here would hand the
        // secret to anyone able to intercept the hop - the opposite of the job.
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $res    = curl_exec($ch);
    $errno  = curl_errno($ch);

    /* Some PHP builds ship with no CA bundle configured at all (curl.cainfo
       unset), and every HTTPS call then fails with errno 60. Rather than lose
       the lead, retry once against the Mozilla roots kept in app/cacert.pem.
       Verification stays on either way - this changes WHICH trust store is
       used, never whether the certificate is checked. Refresh that file when
       roots change: curl -o app/cacert.pem https://curl.se/ca/cacert.pem */
    if (($errno === 60 || $errno === 77) && is_file(APP_DIR . '/cacert.pem')) {
        curl_setopt($ch, CURLOPT_CAINFO, APP_DIR . '/cacert.pem');
        $res   = curl_exec($ch);
        $errno = curl_errno($ch);
    }

    $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    if ($res !== false) $out = (string)$res;
    curl_close($ch);
} else {
    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => implode("\r\n", $headers),
            'content'       => $body,
            'timeout'       => 8,
            'ignore_errors' => true,
        ],
        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true]
                 + (is_file(APP_DIR . '/cacert.pem') ? ['cafile' => APP_DIR . '/cacert.pem'] : []),
    ]);
    $res = @file_get_contents($url, false, $ctx);
    if ($res !== false) $out = (string)$res;
    foreach ($http_response_header ?? [] as $h) {
        if (preg_match('~^HTTP/\S+\s+(\d{3})~', $h, $m)) $status = (int)$m[1];
    }
}

/* n8n unreachable, or answered with something that is not JSON: keep the shape
   the callers expect rather than leaking the upstream's body or its URL. */
if ($status === 0)                       hook_out(502, ['ok' => false]);
if ($out === '' || $out[0] !== '{' && $out[0] !== '[') hook_out($status, ['ok' => $status < 400]);

http_response_code($status);
echo $out;
