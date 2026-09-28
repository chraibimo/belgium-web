<?php
/** FAQ page (Dutch). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
return [
    'title'       => 'Veelgestelde vragen over Mickey TV',
    'description' => 'Wat is IPTV, is het legaal, hoe betaal ik, welke toestellen werken, welke internetsnelheid heb ik nodig, hoe installeer ik het? De antwoorden op één pagina.',
    'search'      => ['title' => 'Veelgestelde vragen', 'excerpt' => 'Formules, toestellen, installatie en betaling — de vragen die de support het vaakst krijgt.', 'text' => 'faq vragen iptv legaal betalen toestellen snelheid vpn reseller'],
    'hero' => ['badge' => 'FAQ', 'title' => 'Veelgestelde<br>vragen', 'lede' => 'Formules, toestellen, installatie en betaling — de vragen die onze support het vaakst krijgt. Staat de jouwe er niet bij? Eén bericht en we antwoorden.'],
    'filter_placeholder' => 'Zoek in de vragen…',
    'groups' => [
        ['Over de dienst', [
            ['q' => 'Wat is IPTV?', 'a' => 'IPTV staat voor Internet Protocol Television: tv kijken via je internetverbinding in plaats van via kabel of schotel. Je hebt geen decoder nodig; een app op je tv, gsm, tablet of computer volstaat. Mickey TV is een wederverkoper van zo’n dienst en levert je de login en de support.'],
            ['q' => 'Is IPTV legaal?', 'a' => 'De technologie zelf (tv via internet) is volkomen legaal en wordt ook door de grote Belgische operatoren gebruikt. Of een bepaald aanbod legaal is, hangt af van de rechten van de zenders die erin zitten en van de wetgeving waar je kijkt. Mickey TV levert de inhoud van externe aanbieders; als abonnee ben je zelf verantwoordelijk voor de naleving van de wetgeving die op jou van toepassing is. Lees onze <a href="' . url('terms') . '">algemene voorwaarden</a>.'],
            ['q' => 'Wat is het verschil tussen Essential en Elite VIP?', 'a' => 'Elite VIP heeft de grootste bibliotheek, 4K, de volledige programmagids, 24u replay, PPV-evenementen, anti-freeze streams en de apps inbegrepen, met 15 dagen geld-terug-garantie. Essential is de compacte formule voor wie één tv en de basis wil, met 7 dagen garantie. Vergelijk ze regel per regel op de <a href="' . url('pricing') . '">prijzenpagina</a>.'],
            ['q' => 'Kan ik een gratis proefperiode krijgen?', 'a' => 'Ja. Stuur ons een WhatsApp met het toestel waarop je wil kijken en we sturen je een proeflogin van 24 uur.'],
        ]],
        ['Betalen & bestellen', [
            ['q' => 'Welke betaalmethodes aanvaarden jullie?', 'a' => 'Bancontact, kredietkaart (Visa, Mastercard, American Express) en cryptomunten. De betaling verloopt via een beveiligde checkout.'],
            ['q' => 'Hoe begin ik?', 'a' => 'Kies een formule, rond de betaling af en je ontvangt je logingegevens (M3U-link en Xtream Codes) via e-mail of WhatsApp — meestal binnen enkele minuten. Daarna volg je de <a href="' . url('tutorial') . '">installatiegids</a> voor je toestel.'],
            ['q' => 'Is er een contract of automatische verlenging?', 'a' => 'Nee. Je betaalt één keer voor de looptijd die je kiest (1, 3, 6 of 12 maanden). Niets verlengt vanzelf; we sturen je een herinnering vóór je abonnement afloopt.'],
            ['q' => 'Kan ik mijn geld terugkrijgen?', 'a' => 'Ja: 7 dagen op Essential, 15 dagen op Elite VIP. Lees het <a href="' . url('refund') . '">terugbetalingsbeleid</a>.'],
        ]],
        ['Toestellen & installatie', [
            ['q' => 'Op welke toestellen werkt Mickey TV?', 'a' => 'Smart tv’s (Samsung, LG, Sony, Philips, TCL…), Android TV-boxen, Fire TV Stick, Apple TV, iPhone en iPad, Android-gsm’s en -tablets, Windows- en Mac-computers en MAG/Enigma2-boxen.'],
            ['q' => 'Hoe installeer ik mijn abonnement?', 'a' => 'Volg de stappen voor jouw toestel op de <a href="' . url('tutorial') . '">installatiepagina</a>. Lukt het niet? Onze support doet het samen met jou via WhatsApp, tot het beeld speelt.'],
            ['q' => 'Kan ik één login op meerdere toestellen gebruiken?', 'a' => 'Installeren mag op zoveel toestellen als je wil, maar er kan er maar één tegelijk streamen per “toestel” in je formule. Wil je op twee schermen tegelijk kijken? Kies dan 2 toestellen bij het bestellen.'],
            ['q' => 'Welke internetsnelheid heb ik nodig?', 'a' => 'Vanaf 20 Mbps kijk je vlot in HD; voor 4K raden we 30 tot 40 Mbps aan. Elke Belgische kabel- of glasvezelverbinding haalt dat. Een stabiele wifi (of een kabel naar je tv) is belangrijker dan een supersnelle lijn.'],
            ['q' => 'Kan ik een VPN gebruiken?', 'a' => 'Ja. Meestal is het niet nodig, maar bij sommige providers of op reis helpt een VPN om storingen te vermijden. Wij helpen je hem instellen.'],
        ]],
        ['Support & wederverkoop', [
            ['q' => 'In welke talen helpen jullie?', 'a' => 'In het Nederlands en het Frans, elke dag van 9u tot 23u, via WhatsApp, e-mail of het <a href="' . url('contact') . '">contactformulier</a>.'],
            ['q' => 'Hoe word ik wederverkoper?', 'a' => 'Contacteer ons via het <a href="' . url('contact') . '">contactformulier</a> met het onderwerp “Iets anders” en vermeld dat je wederverkoper wil worden. We nemen contact op met de voorwaarden.'],
            ['q' => 'Ik heb een vriend aangebracht. Hoe krijg ik mijn gratis maand?', 'a' => 'Via de pagina <a href="' . url('referral') . '">Vertel het verder</a>: vul het bestelnummer van je vriend in en jullie krijgen allebei een extra maand.'],
        ]],
    ],
    'cta' => ['title' => 'Nog een vraag?', 'text' => 'Ons team antwoordt in het Nederlands en het Frans, elke dag.', 'button' => 'Contacteer de support'],
];
