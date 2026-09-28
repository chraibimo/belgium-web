<?php
/** About page (Dutch). */
if (!defined('PRIMO_APP')) exit;
$d = site_data(); $s = $d['stats'];
return [
    'title'       => 'Over ons — wie er achter Mickey TV zit',
    'description' => 'Mickey TV is een Belgische tv-dienst in het Nederlands en het Frans: wie we zijn, wat we aanbieden en waarom we het anders doen.',
    'search'      => ['title' => 'Over Mickey TV', 'excerpt' => 'Wie we zijn, wat we aanbieden en hoe de dienst in elkaar zit.', 'text' => 'over ons wie zijn we bedrijf team belgië'],
    'hero' => ['badge' => 'Over ons', 'title' => 'Tv zoals<br>het hoort', 'lede' => 'Wie we zijn, wat we aanbieden en hoe de dienst in elkaar zit — zonder omwegen.'],
    'intro' => [
        'title' => 'Wie we <em>zijn</em>',
        'text'  => ['Mickey TV is een jonge Belgische dienst, opgezet door mensen die zelf jarenlang te veel betaalden voor te weinig tv. We wilden één abonnement dat alles heeft wat een Belgisch gezin kijkt — in het Nederlands én in het Frans — dat werkt op de toestellen die je al hebt, en waarbij een mens de telefoon opneemt als er iets misgaat.', 'We zijn een wederverkoper: de streams komen van gespecialiseerde aanbieders, wij zorgen voor de selectie, de login, de installatie en de support in jouw taal. Dat is precies het stuk waar andere diensten het laten afweten.'],
    ],
    'offer' => [
        'title' => 'Wat we <em>aanbieden</em>',
        'items' => [
            ['Een enorme zenderkeuze', nf_plus($s['channels']) . ' live zenders uit ' . $s['countries'] . ' landen en regio’s, met alle Belgische zenders in beide landstalen.'],
            ['Een rijke on-demand bibliotheek', nf_plus($s['vod']) . ' films en series, wekelijks aangevuld, met Nederlandse en Franse ondertitels waar beschikbaar.'],
            ['Beeld zonder haperen', 'HD, 4K en 8K waar de zender het uitzendt, op servers gebouwd voor de piekmomenten van een topmatch.'],
            ['Eenvoud', 'Eén login, één app, drie minuten installatie. Geen decoder, geen technieker, geen contract.'],
            ['Elk toestel', 'Smart tv, Fire TV Stick, Android-box, iPhone, Android, pc, Mac, MAG — tot vijf schermen tegelijk.'],
            ['Support in je taal', 'Nederlands en Frans, elke dag van 9u tot 23u, via WhatsApp, e-mail of het formulier — een mens, geen robot.'],
        ],
    ],
    'why' => [
        'title' => 'Waarom <em>Mickey TV</em>',
        'items' => [
            ['Stabiliteit', $s['uptime'] . ' uptime en anti-freeze technologie: geen zwart scherm op het moment dat het telt.'],
            ['Eerlijke prijzen', 'Van € 18,99 per maand. Hoe langer je kiest, hoe minder je betaalt. Geen verborgen kosten.'],
            ['Support in je taal', 'Nederlands en Frans, elke dag van 9u tot 23u, via WhatsApp, e-mail of het formulier.'],
            ['Geld terug', '7 dagen op Essential, 15 op Elite VIP. Probeer het zonder risico.'],
        ],
    ],
    'join' => ['title' => 'Doe mee', 'text' => 'Vragen? Stuur ons een berichtje via WhatsApp of e-mail, of vraag een gratis proefperiode van 24 uur.'],
];
