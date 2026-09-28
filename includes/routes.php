<?php
/**
 * Page registry: one key per page, its slug in every language and the
 * template under includes/pages/ that renders it. Blog posts and tag archives
 * are dynamic (see includes/content/<lang>/posts/) and handled by the router.
 *
 * URLs: the default language lives at "/", the others under "/<lang>/".
 */
if (!defined('PRIMO_APP')) { http_response_code(403); exit('Forbidden'); }

return [
    'home'      => ['nl' => '',                        'fr' => '',                               'tpl' => 'home'],
    'pricing'   => ['nl' => 'prijzen',                 'fr' => 'tarifs',                         'tpl' => 'pricing'],
    'channels'  => ['nl' => 'zenderlijst',             'fr' => 'liste-des-chaines',              'tpl' => 'channels'],
    'tutorial'  => ['nl' => 'installatie',             'fr' => 'installation',                   'tpl' => 'tutorial'],
    'contact'   => ['nl' => 'contact',                 'fr' => 'contact',                        'tpl' => 'contact'],
    'about'     => ['nl' => 'over-ons',                'fr' => 'a-propos',                       'tpl' => 'about'],
    'faqs'      => ['nl' => 'veelgestelde-vragen',     'fr' => 'faq',                            'tpl' => 'faqs'],
    'reviews'   => ['nl' => 'reviews',                 'fr' => 'avis',                           'tpl' => 'reviews'],
    'referral'  => ['nl' => 'vertel-het-verder',       'fr' => 'parrainage',                     'tpl' => 'referral'],
    'chat'      => ['nl' => 'chat',                    'fr' => 'chat',                           'tpl' => 'chat',     'noindex' => true],   // redirects to the chat provider when tawk is configured
    'blog'      => ['nl' => 'blog',                    'fr' => 'blog',                           'tpl' => 'blog'],
    'thankyou'  => ['nl' => 'bedankt',                 'fr' => 'merci',                          'tpl' => 'thankyou', 'noindex' => true],   // noindex in the template; keep it out of the sitemap too
    'privacy'   => ['nl' => 'privacybeleid',           'fr' => 'politique-de-confidentialite',   'tpl' => 'legal'],
    'refund'    => ['nl' => 'terugbetalingsbeleid',    'fr' => 'politique-de-remboursement',     'tpl' => 'legal'],
    'terms'     => ['nl' => 'algemene-voorwaarden',    'fr' => 'conditions-generales',           'tpl' => 'legal'],
    'search'    => ['nl' => 'zoeken',                  'fr' => 'recherche',                      'tpl' => 'search', 'noindex' => true],
];
