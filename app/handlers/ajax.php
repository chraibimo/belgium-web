<?php
/**
 * Form endpoint: POST /api/forms.php (also /api/ajax.php)
 *
 *   form=contact     name, email, phone?, topic?, subject, message, company (honeypot), started (ms)
 *   form=newsletter  email
 *   form=lead        name?, phone, email            (blog lead form)
 *
 * Answers {"ok":true,"message":"…","redirect":"…"?} or
 *         {"ok":false,"message":"…","errors":{field:"…"}}.
 *
 * Every submission is appended to app/data/submissions.jsonl before mailing,
 * so a mail outage never loses a lead. Per-IP throttle: 8 per 10 minutes,
 * tripping it returns a byte-identical success. Unknown forms are logged only.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

require_once INCLUDES_DIR . '/mailer.php';
require_once INCLUDES_DIR . '/email_render.php';

header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { http_response_code(405); echo '{"ok":false}'; exit; }

set_lang(in_array($_POST['lang'] ?? '', SITE_LANGS, true) ? $_POST['lang'] : DEFAULT_LANG);

$form   = preg_replace('/[^a-z_-]/', '', (string)($_POST['form'] ?? ''));
$fields = $_POST;
unset($fields['form'], $fields['lang']);
// Bound the payload: defeats multi-MB emails / JSONL records without truncating
// any legitimate submission.
if (count($fields) > 50) $fields = array_slice($fields, 0, 50, true);
$fields = array_map(fn($v) => is_string($v) ? mb_substr(trim($v), 0, 5000) : '', $fields);

$fail = function (array $errors, ?string $msg = null): never {
    echo json_encode(['ok' => false, 'message' => $msg ?? t('form.fix'), 'errors' => $errors], JSON_UNESCAPED_UNICODE);
    exit;
};
$ok = function (string $msg, ?string $redirect = null): never {
    $out = ['ok' => true, 'message' => $msg];
    if ($redirect !== null) $out['redirect'] = $redirect;
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
};
$validEmail = fn(string $v) => filter_var($v, FILTER_VALIDATE_EMAIL) !== false;

$brand = site_data()['brand']['name'];
$meta = [
    'Page'     => (string)($fields['page'] ?? ($_SERVER['HTTP_REFERER'] ?? '')),
    'Language' => lang(),
    'From IP'  => client_ip(),
    'Received' => (new DateTime('now', new DateTimeZone('Europe/Brussels')))->format('D, j M Y \a\t H:i T'),
];

// Per-IP flood control (before any send_mail/log_submission). On trip, return
// a byte-identical success so an abuser gets no signal.
if (!form_throttle_ok(client_ip())) $ok(t('form.thanks'));

switch ($form) {

    case 'contact':
        // Honeypot + minimum fill time: byte-identical success so bots learn nothing.
        $started = (int)($fields['started'] ?? 0);
        if (($fields['company'] ?? '') !== '' || ($started > 0 && (int)(microtime(true) * 1000) - $started < 2500)) {
            $ok(t('form.contact.sent'), url('thankyou'));
        }
        $errors = [];
        if (($fields['name'] ?? '') === '')      $errors['name'] = t('form.required');
        if (!$validEmail($fields['email'] ?? '')) $errors['email'] = t('form.email_invalid');
        if (($fields['subject'] ?? '') === '')   $errors['subject'] = t('form.required');
        if (($fields['message'] ?? '') === '')   $errors['message'] = t('form.required');
        if ($errors) $fail($errors);
        log_submission('contact', $fields);
        $note = email_notification('Contact form', 'New message from the website', [
            'Name'    => $fields['name'],
            'Email'   => $fields['email'],
            'Phone'   => $fields['phone'] ?? '',
            'Topic'   => $fields['topic'] ?? '',
            'Subject' => $fields['subject'],
        ], $fields['message'], $meta);
        send_mail($brand . ' contact: ' . mb_substr($fields['subject'], 0, 120), $note['text'], $fields['email'], $note['html']);
        $ok(t('form.contact.sent'), url('thankyou'));

    case 'newsletter':
        if (!$validEmail($fields['email'] ?? '')) $fail(['email' => t('form.email_invalid')]);
        log_submission('newsletter', $fields);
        $note = email_notification('Newsletter', 'New subscriber', ['Email' => $fields['email']], '', $meta);
        send_mail($brand . ' newsletter signup', $note['text'], $fields['email'], $note['html']);
        $ok(t('form.newsletter.done'));

    case 'lead':
        $errors = [];
        if (($fields['phone'] ?? '') === '')     $errors['phone'] = t('form.required');
        if (!$validEmail($fields['email'] ?? '')) $errors['email'] = t('form.email_invalid');
        if ($errors) $fail($errors);
        log_submission('lead', $fields);
        $note = email_notification('Blog lead', 'New lead from the blog', [
            'Name'  => $fields['name'] ?? '',
            'Phone' => $fields['phone'],
            'Email' => $fields['email'],
        ], '', $meta);
        send_mail($brand . ' lead from blog', $note['text'], $fields['email'], $note['html']);
        $ok(t('form.lead.done'));

    default:
        // Unknown form: log only, generic thanks - never relay attacker-chosen content by mail.
        log_submission('form-' . substr($form, 0, 32), $fields);
        $ok(t('form.thanks'));
}
