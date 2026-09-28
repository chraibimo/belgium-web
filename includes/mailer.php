<?php
/**
 * Minimal zero-dependency mailer.
 * Every submission is first appended to app/data/submissions.jsonl (never lose a lead),
 * then emailed via SMTP when configured in config.php, else via PHP mail().
 */
require_once __DIR__ . '/client-ip.php';

function log_submission(string $kind, array $fields): void {
    @mkdir(DATA_DIR, 0700, true);   // holds PII (names/emails/phones/IPs) - owner-only
    // Hard ceiling so a sustained POST flood can never exhaust the host's disk.
    // Legitimate lead volume stays far below this; above it we log and drop.
    $fp = DATA_DIR . '/submissions.jsonl';
    if (is_file($fp) && @filesize($fp) > 50 * 1024 * 1024) {
        error_log('primo: submissions.jsonl over 50MB cap, dropping record');
        return;
    }
    $line = json_encode([
        'time'   => date('c'),
        'kind'   => $kind,
        'ip'     => client_ip(),
        'page'   => $_POST['referrer'] ?? ($_SERVER['HTTP_REFERER'] ?? ''),
        'fields' => $fields,
        // INVALID_UTF8_SUBSTITUTE: a malformed byte in any field must not make
        // json_encode() return false and silently drop the whole record.
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($line === false
        || @file_put_contents($fp, $line . "\n", FILE_APPEND | LOCK_EX) === false) {
        error_log('primo: failed to append submission to ' . $fp);
    }
}

/**
 * Per-IP form throttle. Returns false once an address exceeds $max submissions
 * within $win seconds, so a scripted flood from one IP can't spam the inbox or
 * grow submissions.jsonl. Generous by design — a real visitor never hits it.
 * Uses REMOTE_ADDR only (X-Forwarded-For is client-spoofable; we're not behind a
 * trusted proxy). State is a tiny per-IP file under DATA_DIR, pruned opportunistically.
 */
function form_throttle_ok(string $ip, int $max = 8, int $win = 600, string $ns = ''): bool {
    if ($ip === '') return true;                 // don't hard-fail if REMOTE_ADDR is missing
    @mkdir(DATA_DIR, 0700, true);
    $now = time();
    if (mt_rand(1, 50) === 1) {                   // ~2% of calls: sweep stale counters so they can't pile up
        foreach (glob(DATA_DIR . '/rl_*.txt') ?: [] as $old) {
            if (@filemtime($old) < $now - $win) @unlink($old);
        }
    }
    // $ns keeps separate budgets apart (the forms, each n8n hook) while still
    // matching the rl_*.txt sweep above and the deploy/htaccess exclusions.
    $f = DATA_DIR . '/rl_' . preg_replace('/[^a-z0-9_]/', '', $ns) . hash('sha256', $ip) . '.txt';
    $hits = is_file($f)
        ? array_filter(array_map('intval', explode(',', (string)@file_get_contents($f))), fn($t) => $t > $now - $win)
        : [];
    if (count($hits) >= $max) return false;
    $hits[] = $now;
    @file_put_contents($f, implode(',', $hits), LOCK_EX);
    return true;
}

/**
 * Send one notification.
 *
 * $html is optional. Given, the message goes out as multipart/alternative:
 * $body stays as the text part and $html is offered alongside it, so a client
 * that blocks HTML - or a person reading on a watch - still gets the same
 * information. Callers that pass no $html behave exactly as before.
 *
 * Both parts are base64-encoded rather than sent raw. An HTML table has lines
 * far longer than SMTP's 998-character limit and would otherwise be wrapped
 * mid-tag by some relay, and base64 has no leading dot to stuff.
 */
function send_mail(string $subject, string $body, string $replyTo = '', string $html = ''): bool {
    // Strip CR/LF from the subject: mail() does not sanitize it, and callers build
    // it from POST data (contact "Subject" field, raw form_id) - a newline would
    // otherwise inject extra headers (Bcc:) and turn the form into a spam relay.
    // No-op for legitimate single-line subjects; SMTP path base64-encodes anyway.
    $subject = preg_replace('~[\r\n]+~', ' ', $subject);
    $headers = "From: " . MAIL_FROM . "\r\n" .
               ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL) ? "Reply-To: $replyTo\r\n" : '') .
               "MIME-Version: 1.0\r\n";

    if ($html === '') {
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $payload  = $body;
    } else {
        $b = '=_primo_' . bin2hex(random_bytes(8));
        $headers .= "Content-Type: multipart/alternative; boundary=\"$b\"\r\n";
        $payload  = "--$b\r\n"
                  . "Content-Type: text/plain; charset=UTF-8\r\n"
                  . "Content-Transfer-Encoding: base64\r\n\r\n"
                  . chunk_split(base64_encode($body), 76, "\r\n")
                  . "--$b\r\n"
                  . "Content-Type: text/html; charset=UTF-8\r\n"
                  . "Content-Transfer-Encoding: base64\r\n\r\n"
                  . chunk_split(base64_encode($html), 76, "\r\n")
                  . "--$b--";
    }

    if (SMTP_HOST === '') {
        return @mail(MAIL_TO, $subject, $payload, $headers);
    }

    // Minimal SMTP client (AUTH LOGIN over TLS).
    // Port 465 is implicit TLS - the server expects the handshake immediately and
    // never speaks plaintext, so STARTTLS must NOT be sent. Every other port
    // connects in the clear and upgrades. Hosting panels hand out both (Hostinger
    // and WP Mail SMTP offer 465/SSL and 587/TLS), so support both.
    $implicitTls = ((int)SMTP_PORT === 465);
    $scheme      = $implicitTls ? 'ssl://' : 'tcp://';

    // Diagnostics: the caller reports success either way (the lead is already in
    // submissions.jsonl), so the server error log is the only place a wrong host,
    // port or password shows up. Never log the credentials themselves.
    $fail = function (string $why): bool {
        error_log('primo mail: SMTP send failed - ' . $why);
        return false;
    };

    $fp = @stream_socket_client($scheme . SMTP_HOST . ':' . SMTP_PORT, $errno, $errstr, 15);
    if (!$fp) return $fail('cannot connect to ' . $scheme . SMTP_HOST . ':' . SMTP_PORT . ' (' . $errstr . ')');
    stream_set_timeout($fp, 15);   // a silent server must not hang the request
    $say = function (string $cmd) use ($fp): string {
        if ($cmd !== '') fwrite($fp, $cmd . "\r\n");
        $resp = '';
        while ($line = fgets($fp, 515)) {
            $resp .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') break;
        }
        return $resp;
    };
    try {
        if (!str_starts_with($say(''), '220')) return $fail('no 220 greeting (wrong port, or TLS expected?)');
        $say('EHLO ' . DEFAULT_HOST);
        $tls = $implicitTls;
        if (!$tls && str_starts_with($say('STARTTLS'), '220')) {
            if (stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT) !== true) {
                return $fail('STARTTLS handshake failed');
            }
            $tls = true;
            $say('EHLO ' . DEFAULT_HOST);
        }
        if (SMTP_USER !== '') {
            if (!$tls) return $fail('server offers no TLS - refusing to send the password in the clear');
            if (!str_starts_with($say('AUTH LOGIN'), '334')) return $fail('server rejected AUTH LOGIN');
            $say(base64_encode(SMTP_USER));
            if (!str_starts_with($say(base64_encode(SMTP_PASS)), '235')) return $fail('username or password rejected');
        }
        if (!str_starts_with($say('MAIL FROM:<' . MAIL_FROM . '>'), '250')) {
            return $fail('sender ' . MAIL_FROM . ' rejected (must usually match the SMTP account)');
        }
        if (!str_starts_with($say('RCPT TO:<' . MAIL_TO . '>'), '25')) return $fail('recipient ' . MAIL_TO . ' rejected');
        if (!str_starts_with($say('DATA'), '354')) return $fail('server refused DATA');
        $msg = "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n" .
               "To: " . MAIL_TO . "\r\n" . $headers . "\r\n" .
               preg_replace('~^\.~m', '..', $payload) . "\r\n.";
        $ok = str_starts_with($say($msg), '250');
        if (!$ok) $fail('server did not accept the message body');
        $say('QUIT');
        return $ok;
    } finally {
        fclose($fp);
    }
}
