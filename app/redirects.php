<?php
/**
 * Legacy / convenience URLs -> 301 targets (no trailing slash on the keys).
 * Add short links here, e.g. '/promo' => '/prijzen/'.
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

return [
    '/pricing'          => '/prijzen/',
    '/prices'           => '/prijzen/',
    '/plans'            => '/prijzen/',
    '/checkout'         => '/prijzen/',
    '/channels'         => '/zenderlijst/',
    '/channels-list'    => '/zenderlijst/',
    '/tutorial'         => '/installatie/',
    '/setup'            => '/installatie/',
    '/faq'              => '/veelgestelde-vragen/',
    '/faqs'             => '/veelgestelde-vragen/',
    '/about'            => '/over-ons/',
    '/about-us'         => '/over-ons/',
    '/privacy'          => '/privacybeleid/',
    '/privacy-policy'   => '/privacybeleid/',
    '/refund'           => '/terugbetalingsbeleid/',
    '/refund-policy'    => '/terugbetalingsbeleid/',
    '/terms'            => '/algemene-voorwaarden/',
    '/terms-and-conditions' => '/algemene-voorwaarden/',
    '/thank-you'        => '/bedankt/',
    '/spread-the-word'  => '/vertel-het-verder/',
    '/community-reviews'=> '/reviews/',
    '/fr/pricing'       => '/fr/tarifs/',
    '/fr/channels'      => '/fr/liste-des-chaines/',
    '/fr/tutorial'      => '/fr/installation/',
    '/fr/faqs'          => '/fr/faq/',
    '/fr/about'         => '/fr/a-propos/',
    '/nl'               => '/',
];
