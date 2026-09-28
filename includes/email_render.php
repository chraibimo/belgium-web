<?php
/**
 * Email body composition.
 *
 * Two layers:
 *   email_render()       - fill {{key}} placeholders in a template file, verbatim.
 *   email_notification() - build the text AND html parts of one form notification
 *                          from a single array, so the two can never drift apart.
 *
 * Templates live in includes/email-templates/.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

/**
 * Fill {{key}} placeholders. Values are inserted VERBATIM - this function does
 * no escaping, so anything user-supplied must already be safe for the template's
 * medium (email_notification() below escapes before it calls in here).
 */
function email_render(string $template, array $vars = []): string {
    $file = __DIR__ . '/email-templates/' . preg_replace('~[^a-z0-9._-]~i', '', $template);
    $body = is_file($file) ? file_get_contents($file) : '';
    foreach ($vars as $k => $v) {
        $body = str_replace('{{' . $k . '}}', is_scalar($v) ? (string)$v : '', $body);
    }
    // drop any unfilled placeholders
    return preg_replace('~\{\{[a-z0-9_]+\}\}~i', '', $body);
}

/** HTML-escape one value from a form. ENT_SUBSTITUTE so a bad byte can't blank it. */
function email_esc(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Turn a field value into escaped HTML, linking it when the support team would
 * want to act on it: an address becomes mailto:, a phone number tel:, a URL a
 * link. Anything else is plain escaped text.
 *
 * $rich = false links URLs only. The footer facts go through that mode: an IPv4
 * address matches any reasonable phone pattern, and "call 203.0.113.44" is not
 * a thing.
 */
function email_value_html(string $v, bool $rich = true): string {
    $link = function (string $href, string $text) {
        return '<a href="' . email_esc($href) . '" style="color:#c00000;text-decoration:none;border-bottom:1px solid #f0c9c9;">'
             . email_esc($text) . '</a>';
    };
    if (preg_match('~^https?://~i', $v) && filter_var($v, FILTER_VALIDATE_URL)) {
        // Long URLs must wrap or they blow the 600px card open in most clients.
        return '<span style="word-break:break-all;">' . $link($v, $v) . '</span>';
    }
    if (!$rich) return email_esc($v);
    if (filter_var($v, FILTER_VALIDATE_EMAIL)) return $link('mailto:' . $v, $v);
    if (preg_match('~^[+0-9][0-9 ()./-]{6,24}$~', $v)) {
        return $link('tel:' . preg_replace('~[^+0-9]~', '', $v), $v);
    }
    return email_esc($v);
}

/**
 * Compose one notification as both parts of a multipart/alternative message.
 *
 * $rows and $meta are label => value; empty values are dropped so an optional
 * field never leaves a blank line. Returns ['text' => ..., 'html' => ...] for
 * send_mail($subject, $text, $replyTo, $html).
 */
function email_notification(string $kicker, string $title, array $rows, string $message = '', array $meta = []): array {
    $rows    = array_filter(array_map(fn($v) => trim((string)$v), $rows), fn($v) => $v !== '');
    $meta    = array_filter(array_map(fn($v) => trim((string)$v), $meta),  fn($v) => $v !== '');
    $message = trim($message);

    /* ---- text part: what a watch, a terminal or an HTML-blocking client shows */
    $text = $title . "\n" . str_repeat('=', mb_strlen($title)) . "\n\n";
    foreach ($rows as $label => $value) $text .= $label . ': ' . $value . "\n";
    if ($message !== '') $text .= "\n" . $message . "\n";
    if ($meta) {
        $text .= "\n" . str_repeat('-', 34) . "\n";
        foreach ($meta as $label => $value) $text .= $label . ': ' . $value . "\n";
    }

    /* ---- html part */
    $f = "'Segoe UI',Roboto,Helvetica,Arial,sans-serif";
    $rowsHtml = '';
    foreach ($rows as $label => $value) {
        $rowsHtml .= '<tr>'
          . '<td valign="top" style="padding:0 16px 13px 0;font:600 11px/1.9 ' . $f . ';color:#8a7d7a;'
          . 'letter-spacing:.07em;text-transform:uppercase;white-space:nowrap;">' . email_esc($label) . '</td>'
          . '<td valign="top" width="100%" style="padding:0 0 13px;font:400 15px/1.6 ' . $f . ';color:#1C1311;">'
          . email_value_html($value) . '</td>'
          . '</tr>';
    }

    $messageHtml = '';
    if ($message !== '') {
        $messageHtml =
            '<tr><td style="padding:10px 26px 0;">'
          . '<div style="font:600 11px/1.9 ' . $f . ';color:#8a7d7a;letter-spacing:.07em;text-transform:uppercase;">Message</div>'
          . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:6px;">'
          . '<tr><td style="background:#faf7f6;border:1px solid #ece6e4;border-radius:10px;padding:16px 18px;'
          . 'font:400 15px/1.7 ' . $f . ';color:#1C1311;">'
          . nl2br(email_esc($message))
          . '</td></tr></table></td></tr>';
    }

    $metaHtml = '';
    foreach ($meta as $label => $value) {
        $metaHtml .= '<div><span style="color:#b0a5a2;">' . email_esc($label) . ':</span> ' . email_value_html($value, false) . '</div>';
    }

    $html = email_render('form-notification.html', [
        'kicker'  => email_esc($kicker),
        'title'   => email_esc($title),
        'rows'    => $rowsHtml,
        'message' => $messageHtml,
        'meta'    => $metaHtml,
    ]);

    return ['text' => $text, 'html' => $html];
}
