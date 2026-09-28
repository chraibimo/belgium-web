<?php
/**
 * Shared brand data: name, contact details, plan prices, stats.
 * Everything a page quotes about the business comes from here, so a phone
 * number or a price is changed in one place.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

function site_data(): array {
    static $d = null;
    if ($d !== null) return $d;

    $phoneE164 = '+32400000000';                       // TODO: the real Belgian number (E.164, no spaces)
    $stats = json_decode((string)@file_get_contents(APP_DIR . '/channel-stats.json'), true) ?: [];

    return $d = [
        'brand' => [
            'name'      => 'Mickey TV',
            'legal'     => 'Mickey TV',                // legal entity on the © line and in the terms  (TODO)
            'domain'    => DEFAULT_HOST,
            'phone'     => '+32 400 00 00 00',         // TODO: displayed form of the number
            'phone_tel' => $phoneE164,
            'whatsapp'  => 'https://wa.me/' . ltrim($phoneE164, '+'),
            'email'     => 'contact@mickeytv.be',      // TODO: real inbox (also MAIL_TO in app/config.php)
            'address'   => 'België / Belgique',
            'hours_nl'  => 'Elke dag van 9u tot 23u (Belgische tijd).',
            'hours_fr'  => 'Tous les jours de 9h à 23h (heure belge).',
        ],
        // Service figures shown on the site. Channel counts come from the
        // generated channel-stats.json so the copy never drifts from the data.
        'stats' => [
            'channels'  => (int)($stats['channels'] ?? 43000),
            'names'     => (int)($stats['names'] ?? 30000),
            'countries' => 73,                        // groups in the channel pack
            'vod'       => 120000,
            'sports'    => (int)($stats['genres']['SPORTS'] ?? 14000),
            'kids'      => (int)($stats['genres']['KIDS'] ?? 1300),
            'news'      => (int)($stats['genres']['NEWS'] ?? 1200),
            'movies'    => (int)($stats['genres']['MOVIES'] ?? 6000),
            'uptime'    => '99.9%',
        ],
        // Plans. Prices in EUR, per term (months) and simultaneous devices -
        // the same figures as the primotv.ca table, charged in euros.
        // The promo block: a real seven-day cycle anchored on a Monday 00:00 UTC.
        // reference_multiplier draws the crossed-out "was" price (price / multiplier);
        // under EU price-indication rules a struck price must be a price you really
        // charged in the previous 30 days - set it to null to show no struck price.
        'pricing' => [
            'currency' => 'EUR',
            'plans'    => ['vip' => 'Elite VIP', 'essential' => 'Essential'],
            'terms'    => [1, 3, 6, 12],
            'devices'  => [1, 2, 3, 4, 5],
            'prices'   => [
                'essential' => [
                    1  => [1 => 18.99,  2 => 32.99,  3 => 37.99,  4 => 52.99,  5 => 57.99],
                    3  => [1 => 37.99,  2 => 47.99,  3 => 57.99,  4 => 72.99,  5 => 87.99],
                    6  => [1 => 47.99,  2 => 67.99,  3 => 87.99,  4 => 107.99, 5 => 122.99],
                    12 => [1 => 67.99,  2 => 102.99, 3 => 132.99, 4 => 157.99, 5 => 182.99],
                ],
                'vip' => [
                    1  => [1 => 26.99,  2 => 38.99,  3 => 47.99,  4 => 57.99,  5 => 67.99],
                    3  => [1 => 47.99,  2 => 67.99,  3 => 92.99,  4 => 112.99, 5 => 127.99],
                    6  => [1 => 67.99,  2 => 102.99, 3 => 132.99, 4 => 157.99, 5 => 177.99],
                    12 => [1 => 102.99, 2 => 162.99, 3 => 212.99, 4 => 252.99, 5 => 292.99],
                ],
            ],
            'refund_days' => ['essential' => 7, 'vip' => 15],
            'promo' => [
                'enabled'              => true,
                'cycle_days'           => 7,
                'anchor'               => '2026-01-05T00:00:00Z',
                'reference_multiplier' => null,
                // Countdown shown on the tickets. > 0 makes it start at that many
                // seconds for every new browser session (460355 = 5d 07:52:35, the
                // number the primotv.ca page starts from) instead of running the real
                // cycle above - asked for by the owner on 2026-09-16. Note that a timer
                // restarting per visitor is a per-visit timer: presenting it as a
                // deadline that applies to everyone is a misleading commercial practice
                // under EU/Belgian consumer law (Book VI). 0 = run the real cycle.
                'session_seconds'      => 460355,
            ],
        ],
        // Coupon codes the promo wheel hands out. They must exist in the checkout.
        'wheel' => [
            'codes' => ['DEAL15' => 15, 'MTV1MFREE' => 1, 'MTV3MDEAL' => 3, 'MTV6MDEAL' => 6],
            'hold_minutes' => 5,
        ],
    ];
}
