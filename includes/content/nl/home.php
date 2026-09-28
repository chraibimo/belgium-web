<?php
/** Home page copy (Dutch). */
if (!defined('PRIMO_APP')) exit;

$d = site_data();
$s = $d['stats'];

return [
    'title'       => 'Mickey TV — live tv, sport, films en series in 4K in België',
    'description' => 'Mickey TV: meer dan ' . nf_plus($s['channels']) . ' live zenders, ' . nf_plus($s['vod']) . ' films en series, sport in 4K. Werkt op elk toestel, geen contract, geld-terug-garantie. Nederlands en Frans.',

    'hero' => [
        'badge'  => '4K',
        'line1'  => 'Mickey',
        'line2'  => 'TV',
        'lead'   => 'Alle zenders van België en de wereld, elke match, elke film — op de tv, de laptop en de gsm die je al hebt. Geen decoder, geen technieker, geen contract.',
        'pills'  => [nf_plus($s['channels']) . ' zenders', nf_plus($s['vod']) . ' films & series', 'NL & FR', 'HD · 4K'],
        'cta1'   => 'Bekijk de formules',
        'cta2'   => 'Gratis proef via WhatsApp',
        'foot'   => 'Live tv · sport · films · series',
        'now'    => 'Nu op kanaal',
        'hint'   => 'Draai aan de knop — of tik op de tv als het beeld hapert.',
        'channels' => [
            ['id' => 'sport',  'name' => 'Sport',   'meta' => 'Pro League · Champions League · F1 · wielrennen', 'link' => 'channels'],
            ['id' => 'film',   'name' => 'Film',    'meta' => nf_plus($s['movies']) . ' filmzenders + on demand',       'link' => 'pricing'],
            ['id' => 'series', 'name' => 'Series',  'meta' => 'Hele seizoenen, geen wachttijd',                          'link' => 'pricing'],
            ['id' => 'kids',   'name' => 'Kids',    'meta' => 'Ketnet, Nickelodeon, Disney Channel, Cartoon Network',    'link' => 'channels'],
            ['id' => 'news',   'name' => 'Nieuws',  'meta' => 'VRT NWS, RTBF, LN24, BBC, CNN, Al Jazeera',                'link' => 'channels'],
            ['id' => 'music',  'name' => 'Muziek',  'meta' => 'MTV, Qmusic, Stingray, Mezzo',                              'link' => 'channels'],
            ['id' => 'docu',   'name' => 'Docu',    'meta' => 'National Geographic, Discovery, Canvas, Arte',               'link' => 'channels'],
        ],
    ],

    'ready' => [
        'eyebrow' => 'Klaar om te kijken?',
        'title'   => 'Alles wat je wil zien. <em>Op één plek.</em>',
        'lede'    => 'Je Mickey TV-abonnement is meer dan een zenderlijst: het is een pas voor de hele tv-wereld. De nieuwste series bingen, een blockbuster ontdekken of een klassieker herbekijken — in HD en 4K, wanneer jij wil.',
        'checks'  => [nf_plus($s['channels']) . ' zenders uit ' . $s['countries'] . ' landen', nf_plus($s['vod']) . ' films en series on demand', 'Alle Belgische zenders in het Nederlands en het Frans'],
        'button'  => 'Bekijk de zenderlijst',
    ],

    'stats' => [
        'eyebrow' => 'In cijfers',
        'title'   => 'Groot genoeg voor de hele familie',
        'items'   => [
            ['num' => $s['channels'],  'suffix' => '+', 'label' => 'Zenders',         'sub' => 'Live, uit ' . $s['countries'] . ' landen en regio’s'],
            ['num' => $s['vod'],       'suffix' => '+', 'label' => 'Films & series',  'sub' => 'On demand, wekelijks aangevuld'],
            ['num' => $s['sports'],    'suffix' => '+', 'label' => 'Sportzenders',    'sub' => 'Voetbal, wielrennen, tennis, F1, PPV'],
            ['num' => 99.9,            'suffix' => '%', 'label' => 'Uptime',          'sub' => 'Anti-freeze servers, geen zwart scherm'],
        ],
    ],

    'who' => [
        'eyebrow' => 'Wie we zijn',
        'title'   => 'Gebouwd voor een <em>4K-ervaring</em> zonder gedoe',
        'lede'    => 'Bij Mickey TV zijn we bezeten door kwaliteit en stabiliteit. Onze dienst is van de grond af gebouwd om een vlekkeloze 4K-stream te leveren, zodat jij naar je favoriete programma’s kijkt zonder bufferen of vertraging.',
        'claims'  => ['Meerdere toestellen tegelijk', 'Werkt op elke internetverbinding vanaf 20 Mbps', 'Support in het Nederlands en het Frans, elke dag'],
        'button1' => 'Abonneer je nu',
        'button2' => 'Zo werkt de installatie →',
        'osd'     => 'LIVE · 4K UHD · ' . nf_plus($s['channels']) . ' zenders',
    ],

    'cc' => [
        'eyebrow'  => 'Voor wie de kabel opzegt',
        'title'    => 'Alles waar je nu voor betaalt, <em>en meer</em>',
        'lede'     => 'Elke zender die je vandaag bij Telenet of Proximus hebt, plus de sportzenders, de films en de series waar je al jaren naar kijkt — voor minder dan één streamingdienst.',
        'button'   => 'Bekijk de prijzen',
        'pill'     => 'Elite VIP-aanbod',
        'kicker'   => 'Zendercheck',
        'h3'       => 'Staat jouw zender op Mickey TV?',
        'placeholders' => ['VRT 1', 'Play Sports', 'Eleven Sports', 'RTL-TVI', 'VTM', 'La Une', 'Canvas', 'DAZN', 'Ketnet', 'Sky Sports', 'Canal+', 'beIN Sports'],
        'note'     => 'Dit is het Elite VIP-aanbod: elke zender die je hier vindt, zit in de Elite VIP-formule. De Essential-formule heeft een kleinere selectie.',
        'stats'    => [
            ['num' => $s['channels'], 'label' => 'live zenders'],
            ['num' => $s['sports'],   'label' => 'sport'],
            ['num' => $s['vod'],      'label' => 'films & series'],
            ['num' => $s['kids'],     'label' => 'kids'],
            ['num' => $s['countries'],'label' => 'landen', 'step' => 1],
            ['num' => $s['news'],     'label' => 'nieuws'],
        ],
    ],

    'features' => [
        'eyebrow' => 'Wat ons anders maakt',
        'title'   => 'Kijken zoals het <em>hoort</em>',
        'items'   => [
            ['icon' => 'phone',   'title' => 'Overal mee',       'text' => 'Mis geen minuut. Kijk live tv en pay-per-view op je gsm of tablet, in de trein, op vakantie of in de tuin.'],
            ['icon' => 'film',    'title' => 'Uitgekozen inhoud','text' => 'Blockbusters, de series waar iedereen over praat en klassiekers — alles op één plek, met één klik.'],
            ['icon' => 'bolt',    'title' => 'Live sport',       'text' => 'Pro League, Champions League, de klassiekers, tennis en F1 — live en in HD, zonder onderbrekingen.'],
        ],
        'compare_title' => 'Het verschil met <em>de rest</em>',
        'compare_head'  => ['Vergelijk', 'Andere aanbieders', 'Mickey TV'],
        'compare' => [
            ['Live zenders',           '12.000+',  nf_plus($s['channels']), 28, 100],
            ['Films & series',         '40.000+',  nf_plus($s['vod']),      33, 100],
            ['Support na aankoop',     'Beperkt',  'Onbeperkt, NL & FR',    30, 100],
            ['Anti-freeze technologie','✖',        '✔',                     0,  100],
            ['Programmagids (EPG)',    '✖',        '✔',                     0,  100],
            ['99,9% uptime-garantie',  '✖',        '✔',                     0,  100],
        ],
    ],

    'faq' => [
        'eyebrow' => 'Antwoorden',
        'title'   => 'Veelgestelde <em>vragen</em>',
        'lede'    => 'Betalen, toestellen, installatie en snelheid: wat mensen ons vragen vóór ze zich abonneren. Staat jouw vraag er niet bij? Ons team is één bericht ver.',
        'side_title' => 'Echte hulp, elke dag',
        'cta_title'  => 'Nog een vraag?',
        'cta_text'   => 'Ons team antwoordt in het Nederlands en het Frans, voor én na je abonnement.',
        'cta_button' => 'Contacteer de support',
        'items' => [
            ['q' => 'Welke betaalmethodes aanvaarden jullie?', 'a' => 'Bancontact, kredietkaart (Visa, Mastercard, American Express) en cryptomunten. De betaling is versleuteld en je logingegevens worden verstuurd zodra de bestelling bevestigd is.'],
            ['q' => 'Hoe krijg ik mijn login na de betaling?', 'a' => 'Meteen. Zodra je betaling binnen is, sturen we je M3U-link en Xtream Codes-login per e-mail, of via WhatsApp als je dat liever hebt. Er wordt niets opgestuurd en je hoeft geen toestel te kopen.'],
            ['q' => 'Hoe installeer ik Mickey TV op mijn toestel?', 'a' => 'Op twee manieren. Volg de stap-voor-stap gidsen op onze <a href="' . url('tutorial') . '">installatiepagina</a>, of laat het ons samen doen: onze support begeleidt je één-op-één op je eigen toestel tot het beeld speelt.'],
            ['q' => 'Kan ik één verbinding op meerdere toestellen gebruiken?', 'a' => 'Je mag je login op zoveel toestellen installeren als je wil, maar er kan er maar één tegelijk streamen. Willen twee schermen tegelijk kijken? Voeg dan een tweede toestel toe aan je formule.'],
            ['q' => 'Welke internetsnelheid heb ik nodig voor 4K?', 'a' => 'Voor een vlotte en stabiele 4K-stream raden we een verbinding van minstens 30 tot 40 Mbps aan. Voor HD volstaat 15 Mbps meestal. Elke Belgische kabel- of glasvezelverbinding haalt dat ruim; een stabiele wifi is belangrijker dan een snelle.'],
            ['q' => 'Zenden alle zenders uit in 4K?', 'a' => 'Niet allemaal. Een groot deel van onze premium sport- en filmzenders is in 4K, maar veel zenders zenden zelf nog in HD uit, dus dat is de feed die wij krijgen. We geven altijd de beste kwaliteit door die een zender uitzendt, en de 4K-lijst groeit elke maand.'],
            ['q' => 'Op welke toestellen werkt Mickey TV?', 'a' => '<p>Onze dienst werkt op zowat elk modern toestel:</p><ul><li>Smart tv’s (Samsung, LG, Sony, Philips, TCL …)</li><li>Amazon Fire TV Stick en Fire TV Cube</li><li>Android TV-boxen en Google TV</li><li>NVIDIA Shield en Apple TV</li><li>Smartphones en tablets (iOS en Android)</li><li>Pc en Mac</li></ul>'],
        ],
    ],

    'guarantee' => [
        'title' => 'Zonder risico: <em>geld terug</em> als het niet bevalt',
        'text'  => 'Tevreden of je geld terug. Probeer Mickey TV zonder risico dankzij onze geld-terug-garantie: 7 dagen op Essential, 15 dagen op Elite VIP. Niet helemaal tevreden? Dan betalen we je terug, zonder discussie.',
        'button'=> 'Lees het terugbetalingsbeleid',
    ],
];
