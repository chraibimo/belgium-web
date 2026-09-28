<?php
/** Pricing copy (Dutch): the plan cards (shared with the home page) and the pricing page bands. */
if (!defined('PRIMO_APP')) exit;

$d = site_data();
$s = $d['stats'];

return [
    'title'       => 'Prijzen — formules vanaf € 18,99 per maand',
    'description' => 'Mickey TV-formules: Essential en Elite VIP, van 1 tot 12 maanden, op 1 tot 5 toestellen tegelijk. Geen contract, betaling in euro, 7 of 15 dagen geld-terug-garantie.',
    'search'      => ['title' => 'Prijzen en formules', 'excerpt' => 'Essential en Elite VIP, van 1 tot 12 maanden, op 1 tot 5 toestellen tegelijk. Geen contract, geld-terug-garantie.', 'text' => 'prijs prijzen abonnement formule kosten euro bancontact maand jaar'],

    'block' => [
        'eyebrow'   => 'Promoweek',
        'title'     => 'Geen decoder. Geen technieker. <em>Geen contract.</em>',
        'lede'      => 'Hou de tv en de wifi die je al betaalt — wij sturen je alleen een login. Niets te installeren, niets af te koppelen, niets terug te sturen. Toch niet jouw ding? Volledige terugbetaling: 7 dagen op Essential, 15 op Elite.',
        'timer'     => 'Deze promoronde sluit over',
        'trust'     => ['HD · 4K · 8K', nf_plus($s['channels']) . ' live zenders', 'Geld terug, 7 & 15 dagen', 'Support via WhatsApp'],
        'plans' => [
            'vip' => [
                'name'     => 'Elite VIP',
                'band'     => 'Elite-pakket',
                'flag'     => 'Meest gekozen',
                'tag'      => 'Als je replay, PPV en de app wil',
                'features' => [
                    'Meer dan ' . nf_plus($s['channels']) . ' live zenders',
                    nf_plus($s['vod']) . ' films & series (wekelijks aangevuld)',
                    'Alle Belgische zenders, NL en FR',
                    'Alle sportzenders + PPV-evenementen',
                    '4K, UHD, FHD en HD',
                    'Volledige programmagids (EPG)',
                    '24u replay op live tv',
                    'Anti-freeze technologie',
                    'IBO Pro Player-app inbegrepen',
                    'Support 7/7 in het NL en FR',
                    '15 dagen geld-terug-garantie',
                ],
            ],
            'essential' => [
                'name'     => 'Essential',
                'band'     => 'Basispakket',
                'flag'     => '',
                'tag'      => 'Als één tv genoeg is',
                'features' => [
                    'Meer dan 30.000 live zenders',
                    '90.000+ films & series',
                    'Belgische zenders, NL en FR',
                    'Alle sportzenders',
                    'UHD, FHD en HD',
                    'Beperkte programmagids (EPG)',
                    'Stabiele servers, 99% uptime',
                    'Support 7/7 in het NL en FR',
                    'Soms is een VPN nodig',
                    '7 dagen geld-terug-garantie',
                ],
            ],
        ],
    ],

    'compare' => [
        'title' => 'Essential en Elite, <em>regel per regel</em>',
        'lede'  => 'Dezelfde servers, dezelfde support, dezelfde terugbetalingsbelofte. Het verschil zit in de grootte van de bibliotheek, de beeldkwaliteit en wat er gebeurt nadat de match is afgelopen.',
        'head'  => ['Kenmerk', 'Essential', 'Elite VIP'],
        'rows'  => [
            ['Live zenders', '30.000+', nf_plus($s['channels'])],
            ['Films en series on demand', '90.000+', nf_plus($s['vod']) . ', wekelijks aangevuld'],
            ['Beeldkwaliteit', 'UHD, FHD, HD', '4K, UHD, FHD, HD'],
            ['Programmagids (EPG)', 'Beperkt', 'Volledig'],
            ['Sportzenders', 'Inbegrepen', 'Inbegrepen'],
            ['PPV en sportevenementen', '—', 'Inbegrepen'],
            ['24u replay', '—', 'Volledig'],
            ['Anti-freeze streams', '—', 'Inbegrepen'],
            ['IBO Pro Player-app', '—', 'Gratis'],
            ['Eigen app', '—', 'Inbegrepen'],
            ['Aanpasbare categorieën', 'Inbegrepen', 'Inbegrepen'],
            ['Uptime servers', '99%', '99,9%'],
            ['Support', '7/7 via WhatsApp, NL & FR', '7/7 via WhatsApp, NL & FR'],
            ['Looptijden', '1, 3, 6, 12 maanden', '1, 3, 6, 12 maanden'],
            ['Toestellen tegelijk', '1 tot 5', '1 tot 5'],
            ['Geld-terug-periode', '7 dagen', '15 dagen'],
        ],
    ],

    'how' => [
        'title' => 'Zo <em>werkt</em> het',
        'lede'  => 'Drie stappen, en bij geen enkele komt een technieker, een decoder of een levering kijken.',
        'steps' => [
            ['Kies je formule', 'Essential of Elite, van 1 tot 12 maanden, op 1 tot 5 toestellen tegelijk. Elke combinatie staat geprijsd op het ticket — en hoe langer de looptijd, hoe minder je per maand betaalt.'],
            ['Betaal', 'Je betaalt één keer voor de looptijd die je koos, met Bancontact, kaart of crypto. Er is geen contract en niets verlengt vanzelf.'],
            ['Log in en kijk', 'We sturen je één login via WhatsApp of e-mail. Je geeft die in in de app die je toestel al gebruikt, en dat is de hele installatie.'],
        ],
    ],

    'devices' => [
        'title' => 'Op de tv die je <em>al hebt</em>',
        'lede'  => 'Je houdt je televisie en de wifi waar je al voor betaalt. Er wordt niets geïnstalleerd, niets afgekoppeld en niets opgestuurd.',
        'items' => [
            ['1 tot 5 toestellen', 'Eén login voor de tv in de living, de tablet en de gsm. Kies hoeveel schermen er tegelijk mogen kijken.'],
            ['Elite brengt zijn eigen apps mee', 'Elite bevat de IBO Pro Player gratis en onze eigen app. Essential werkt met de speler die je toestel al heeft.'],
            ['Vraag het vóór je koopt', 'Zeg ons waarop je kijkt en we zeggen eerlijk of het werkt. Sommige internetproviders remmen streaming af — dan lost een VPN het op en stellen we die samen met jou in.'],
        ],
    ],

    'refund' => [
        'title' => 'Probeer het. Niet goed? <em>Geld terug.</em>',
        'text'  => '7 dagen op Essential, 15 dagen op Elite. Zonder vragen. Er is niets af te koppelen, niets terug te brengen en niets op te sturen — het enige wat je kan verliezen is de tijd die het kostte om het te proberen.',
    ],

    'faq' => [
        'title' => 'Vragen die mensen <em>écht stellen</em>',
        'items' => [
            ['q' => 'Wat krijg ik na de betaling?', 'a' => 'Eén login (M3U-link en Xtream Codes) via WhatsApp of e-mail, meestal binnen enkele minuten. Die geef je in in de app op je toestel en je kijkt.'],
            ['q' => 'Is er een contract?', 'a' => 'Nee. Je betaalt één keer voor de looptijd die je kiest. Niets verlengt vanzelf en je hoeft niets op te zeggen.'],
            ['q' => 'Kan ik mijn geld terugkrijgen?', 'a' => 'Ja. 7 dagen op Essential, 15 dagen op Elite, zonder vragen. Stuur ons gewoon een bericht met je bestelnummer.'],
            ['q' => 'Op hoeveel toestellen kan ik kijken?', 'a' => 'Tot vijf tegelijk, afhankelijk van de formule die je kiest. Installeren mag op zoveel toestellen als je wil.'],
            ['q' => 'Heb ik een VPN nodig?', 'a' => 'Meestal niet. Bij sommige providers of op sommige toestellen helpt een VPN om storingen te vermijden; als dat bij jou zo is, stellen we die samen in.'],
            ['q' => 'Wat is het verschil tussen Essential en Elite?', 'a' => 'Elite heeft de grootste bibliotheek, 4K, de volledige programmagids, 24u replay, PPV-evenementen, anti-freeze streams en de apps inbegrepen. Essential is de compacte formule voor wie één tv en de basis wil.'],
            ['q' => 'Wat kan ik precies kijken?', 'a' => 'Bekijk de <a href="' . url('channels') . '">volledige zenderlijst</a>: alle Belgische zenders in het Nederlands en het Frans, de Nederlandse en Franse zenders, sport, films, kids en meer dan 70 landen.'],
            ['q' => 'Mijn login is niet aangekomen. Wat nu?', 'a' => 'Stuur een WhatsApp naar ' . $d['brand']['phone'] . '. Een mens antwoordt, elke dag van 9u tot 23u.'],
        ],
    ],

    'deciding' => [
        'title' => 'Nog aan het <em>twijfelen?</em>',
        'lede'  => 'Vertel ons wat je kijkt en op welk toestel. We zeggen je welke formule past — of dat we niet de juiste dienst voor jou zijn, als dat het eerlijke antwoord is.',
        'wa'    => 'Stuur ons een WhatsApp',
        'list'  => 'Bekijk de zenderlijst',
    ],
];
