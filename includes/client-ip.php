<?php
/**
 * Resolve the client IP. Uses REMOTE_ADDR only by default (unspoofable on a
 * non-proxied host). If the site is deployed behind a trusted proxy/CDN, set
 * TRUSTED_PROXY = true in config to honour the left-most X-Forwarded-For hop.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

function client_ip(): string {
    $remote = $_SERVER['REMOTE_ADDR'] ?? '';
    if (defined('TRUSTED_PROXY') && TRUSTED_PROXY && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $first = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        if (filter_var($first, FILTER_VALIDATE_IP)) return $first;
    }
    return $remote;
}
