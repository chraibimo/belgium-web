<?php
/**
 * Optional geo/IP blocking. OFF by default (allows everyone) — the site serves
 * identical content worldwide. Enable by defining GEOBLOCK_DENY_IPS (array of
 * IPs/CIDRs) in config or mail-config.local.php; no external GeoIP dependency.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

require_once __DIR__ . '/client-ip.php';

/** True if the current visitor is allowed (default: always). */
function geoblock_allowed(): bool {
    if (!defined('GEOBLOCK_DENY_IPS') || !GEOBLOCK_DENY_IPS) return true;
    $ip = client_ip();
    foreach (GEOBLOCK_DENY_IPS as $rule) {
        if (ip_in_range($ip, $rule)) return false;
    }
    return true;
}

/** IP-in-CIDR test (IPv4). Exact match if no "/". */
function ip_in_range(string $ip, string $rule): bool {
    if (!str_contains($rule, '/')) return $ip === $rule;
    [$net, $bits] = explode('/', $rule, 2);
    $ipl = ip2long($ip); $netl = ip2long($net);
    if ($ipl === false || $netl === false) return false;
    $mask = -1 << (32 - (int)$bits);
    return ($ipl & $mask) === ($netl & $mask);
}
