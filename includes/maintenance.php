<?php
/**
 * Maintenance mode. OFF by default. Turn it on by creating the flag file
 * app/data/maintenance.flag (its contents, if any, are shown as the message).
 * The front controller can call maintenance_gate() early to short-circuit.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

function maintenance_active(): bool {
    return defined('DATA_DIR') && is_file(DATA_DIR . '/maintenance.flag');
}

/** If maintenance is on, emit a 503 page and exit. No-op otherwise. */
function maintenance_gate(): void {
    if (!maintenance_active()) return;
    $msg = trim((string)@file_get_contents(DATA_DIR . '/maintenance.flag'))
        ?: 'We are performing scheduled maintenance and will be back shortly.';
    http_response_code(503);
    header('Retry-After: 3600');
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!doctype html><meta charset="utf-8"><title>Be right back</title>'
       . '<div style="font-family:system-ui;max-width:520px;margin:18vh auto;text-align:center;color:#333">'
       . '<h1>Be right back</h1><p>' . htmlspecialchars($msg, ENT_QUOTES) . '</p></div>';
    exit;
}
