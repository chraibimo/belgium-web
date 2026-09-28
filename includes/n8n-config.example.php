<?php
/**
 * Copy to includes/n8n-config.local.php (gitignored). /api/hook.php?t=<name>
 * forwards the browser's POST to the matching n8n webhook with the shared
 * secret in the N8N_HEADER header; make the n8n workflows require it.
 *
 *   wheel     promo-wheel winners (email, prize, code)
 *   capi      Meta Conversions API events (needs FB_PIXEL_ID in services config)
 *   referral  "tell a friend" claims from the referral page
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

const N8N_SECRET = 'change-me-long-random-string';
const N8N_HEADER = 'X-Primo-Key';
const N8N_HOOKS  = [
    'wheel'    => 'https://n8n.example.com/webhook/mickeytv-wheel-lead',
    'capi'     => 'https://n8n.example.com/webhook/fb-capi',
    'referral' => 'https://n8n.example.com/webhook/mickeytv-referral',
];
