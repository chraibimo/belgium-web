<?php
/** Channels page copy (Dutch). */
if (!defined('PRIMO_APP')) exit;
$d = site_data(); $s = $d['stats'];
return [
    'title'       => 'Zenderlijst — alle zenders van België en de wereld',
    'description' => 'Doorzoek de volledige zenderlijst van Mickey TV: ' . nf_plus($s['channels']) . ' zenders uit ' . $s['countries'] . ' landen en regio’s. VRT, VTM, Play, RTBF, RTL, DAZN, Play Sports, Canal+, Sky Sports en meer.',
    'search'      => ['title' => 'Zenderlijst', 'excerpt' => 'Alle zenders van België en de wereld, doorzoekbaar op naam.', 'text' => 'zenders zenderlijst vrt vtm play rtbf rtl dazn eleven canal+ sky sport kids nieuws'],
    'hero' => [
        'badge' => 'Zenderlijst',
        'title' => 'Elke zender.<br>Elk land.',
        'lede'  => nf_plus($s['channels']) . ' live zenders uit ' . $s['countries'] . ' landen en regio’s: België in het Nederlands en het Frans, Nederland, Frankrijk, het VK, Europa en de Arabische wereld, in HD, 4K en 8K. Doorzoek de hele lijst op naam.',
        'search_placeholder' => 'Zoek een zender — VRT 1, Play Sports, La Une, Sky Sports…',
        'chips' => ['HD · 4K · 8K', $s['countries'] . ' landen & regio’s', '24u replay (Elite)', nf_plus($s['vod']) . ' films & series'],
        'cta1'  => 'Bekijk de formules',
    ],
    'belgium' => [
        'eyebrow' => 'Voor België',
        'title'   => 'Alle Belgische zenders, <em>NL en FR</em>',
        'lede'    => 'Van Één en VTM tot La Une en RTL-TVI, van Play Sports en DAZN tot Ketnet en Tipik: het volledige Belgische aanbod in beide landstalen, plus de Nederlandse en Franse zenders die elk gezin hier kijkt.',
        'groups'  => [
            ['Vlaanderen', ['VRT 1', 'VRT Canvas', 'Ketnet', 'VTM', 'VTM 2', 'VTM 3', 'VTM 4', 'VTM Gold', 'Play 4', 'Play 5', 'Play 6', 'Play 7', 'Kanaal Z', 'Njam!', 'ATV', 'TV Oost', 'Bruzz']],
            ['Wallonie & Bruxelles', ['La Une', 'Tipik', 'La Trois', 'RTL-TVI', 'Club RTL', 'Plug RTL', 'LN24', 'BX1', 'Canal Z', 'AB3', 'ABXplore', 'Télé MB', 'Notélé', 'TV Lux', 'Canal Zoom']],
            ['Sport', ['Play Sports 1–4', 'DAZN Pro League', 'Eleven Sports 1–2', 'VOO Sport World 1–3', 'Eurosport 1–2', 'Canal+ Sport', 'Sky Sports', 'beIN Sports', 'Infosport+', 'Sport 10']],
            ['Kids & film', ['Ketnet Junior', 'Nickelodeon', 'Nick Jr', 'Disney Channel', 'Cartoon Network', 'Boomerang', 'Baby TV', 'Canal+ Cinéma', 'Ciné+', 'TCM Cinéma', 'Warner TV', 'Star Channel']],
        ],
        'note' => 'Dit is een greep uit de lijst. Klik op een naam om te controleren of hij erin zit, of zoek hierboven in de volledige lijst.',
    ],
    'guide' => [
        'hint'  => 'Typ minstens twee letters. De resultaten verschijnen terwijl je typt.',
        'note'  => 'Dit is het Elite VIP-aanbod: elke zender die je hier vindt, zit in de Elite VIP-formule. De Essential-formule heeft een kleinere selectie.',
    ],
    'tv' => [
        'kicker' => 'Mickey kijkt mee',
        'name'   => 'Welke zender zoek je?',
        'meta'   => 'Typ een naam, dan stem ik af.',
    ],
    'events' => [
        'title'  => 'Straks live',
        'lede'   => 'Grote wedstrijden en koersen van de komende weken, in Belgische tijd.',
        'find'   => 'Zoek de zender',
        'days'   => ['zo', 'ma', 'di', 'wo', 'do', 'vr', 'za'],
        'months' => ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'],
    ],
    'included' => [
        'title' => 'Eén abonnement. <em>Alles erin.</em>',
        'items' => [
            [nf_plus($s['channels']), 'Live zenders', 'Uit ' . $s['countries'] . ' landen en regio’s, in HD, 4K en 8K.'],
            ['4K & 8K', 'Beeldkwaliteit', 'De beste feed die elke zender uitzendt.'],
            ['24u', 'Replay', 'Gemiste uitzending? Terugkijken tot 24 uur later (Elite).'],
            [nf_plus($s['vod']), 'On demand', 'Films en volledige seizoenen, wekelijks aangevuld.'],
            ['NL & FR', 'Twee talen', 'Het hele Belgische aanbod in beide landstalen.'],
            ['Vraag', 'Zender mist?', 'Zoek eerst in de gids, en contacteer ons dan. Als hij bestaat, kunnen we hem meestal toevoegen.'],
        ],
    ],
];
