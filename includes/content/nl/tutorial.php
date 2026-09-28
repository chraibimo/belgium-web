<?php
/** Installation assistant (Dutch): one step list per device. */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
$code = '4738580';   // AFTVnews Downloader shortcut to the IBO Pro Player APK
$login = 'Na je betaling sturen we je logingegevens via WhatsApp of e-mail: een <b>Xtream Codes</b>-login (server, gebruikersnaam, wachtwoord) en een <b>M3U-link</b>. De meeste apps aanvaarden beide.';
$xtream = ['Geef je login in', 'Kies <b>Xtream Codes</b> (of “Login with Xtream Codes API”), vul de server-URL, je gebruikersnaam en je wachtwoord in zoals je ze kreeg, en bevestig. De zenderlijst laadt in een paar seconden.'];
$done = ['Klaar — kijk', 'Open <b>Live TV</b> voor de zenders, <b>Films</b> en <b>Series</b> voor on demand. Werkt het niet meteen? Stuur ons een screenshot via WhatsApp, we kijken live met je mee.'];

return [
    'title'       => 'Installatie — Mickey TV op elk toestel in 3 tot 6 minuten',
    'description' => 'Kies je toestel en volg de stappen: Fire TV Stick, Android TV, Samsung en LG smart tv, iPhone en iPad, Windows, Mac, Chromecast, MAG-box. Met code ' . $code . ' voor de Downloader-app.',
    'search'      => ['title' => 'Installatie op elk toestel', 'excerpt' => 'Stap-voor-stap gidsen voor Fire TV Stick, Android TV, Samsung, LG, iPhone, Windows, Mac en meer.', 'text' => 'installeren installatie firestick android smart tv samsung lg iphone ipad windows mac chromecast mag downloader ibo smarters m3u xtream'],
    'hero' => [
        'badge' => 'Installatiehulp',
        'title' => 'Mickey TV op<br>elk toestel',
        'lede'  => 'Kies je toestel en we tonen de exacte stappen. Waar het kan, gebruiken we de app <b>Downloader</b> met code <b>' . $code . '</b> om de IBO Pro Player te installeren. Voor toestellen zonder APK’s sturen we je naar de juiste app in de store.',
        'time'  => 'Meestal klaar in 3 tot 6 minuten',
        'help'  => 'Hulp nodig? Chat met ons',
    ],
    'code'   => $code,
    'code_label' => 'Downloader-code',
    'pick'   => 'Waarop installeer je?',
    'show'   => 'Toon mijn stappen',
    'steps_title' => 'Stappen voor %s',
    'devices' => [
        ['id' => 'firetv', 'icon' => 'stick', 'name' => 'Amazon Fire TV Stick / Cube', 'steps' => [
            ['Sta onbekende apps toe', 'Ga naar <b>Instellingen › Mijn Fire TV › Opties voor ontwikkelaars</b> en zet <b>Apps van onbekende bronnen</b> aan. Zie je de ontwikkelaarsopties niet? Tik dan 7 keer op <b>Over › Fire TV Stick</b>.'],
            ['Installeer Downloader', 'Zoek in de Amazon-appstore naar <b>Downloader</b> (AFTVnews) en installeer de app. Open ze en geef toegang tot bestanden.'],
            ['Geef de code in', 'Typ in het adresveld de code <b>' . $code . '</b> en druk op Go. De IBO Pro Player wordt gedownload en de installatie start. Kies <b>Installeren</b>, daarna <b>Openen</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'androidtv', 'icon' => 'tv', 'name' => 'Android TV / Google TV (Sony, TCL, Philips, Hisense, Shield)', 'steps' => [
            ['Installeer een speler uit de Play Store', 'Zoek op je tv in de <b>Google Play Store</b> naar <b>IBO Pro Player</b> of <b>IPTV Smarters Pro</b> en installeer. Vind je ze niet? Installeer dan <b>Downloader</b> en gebruik code <b>' . $code . '</b>.'],
            ['Open de app', 'Bij de eerste start kan de app een activatie op het scherm tonen; volg de instructie of stuur ons het toestel-ID via WhatsApp, dan activeren wij.'],
            $xtream, $done,
        ]],
        ['id' => 'android', 'icon' => 'android', 'name' => 'Android-smartphone of -tablet', 'steps' => [
            ['Installeer IPTV Smarters Pro', 'Open de <b>Play Store</b>, zoek <b>IPTV Smarters Pro</b> (of IBO Pro Player) en installeer.'],
            ['Kies Xtream Codes', 'Open de app en kies <b>Login with Xtream Codes API</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'chromecast', 'icon' => 'cast', 'name' => 'Chromecast met Google TV', 'steps' => [
            ['Installeer een speler', 'Zoek in de <b>Play Store</b> op de Chromecast naar <b>IBO Pro Player</b> of <b>IPTV Smarters Pro</b>. Of installeer <b>Downloader</b> en gebruik code <b>' . $code . '</b>.'],
            ['Open de app en kies Xtream Codes', 'Kies bij het opstarten de Xtream Codes-login.'],
            $xtream, $done,
        ]],
        ['id' => 'ios', 'icon' => 'apple', 'name' => 'iPhone / iPad / Apple TV', 'steps' => [
            ['Installeer een speler uit de App Store', 'Zoek <b>IPTV Smarters</b> of <b>IBO Pro Player</b> in de <b>App Store</b> en installeer.'],
            ['Kies Xtream Codes', 'Open de app en kies de Xtream Codes-login (of “Add playlist” met je M3U-link).'],
            $xtream, $done,
        ]],
        ['id' => 'samsung', 'icon' => 'tv', 'name' => 'Samsung Smart TV (Tizen)', 'steps' => [
            ['Installeer een speler uit de Samsung-appstore', 'Zoek op je tv naar <b>IBO Player</b>, <b>Smart IPTV</b> of <b>Hot Player</b> en installeer. Sommige van deze apps vragen een eenmalige activatiekost aan de maker van de app.'],
            ['Noteer het toestel-ID', 'De app toont bij het opstarten een <b>MAC-adres</b> of <b>toestel-ID</b> en soms een sleutel. Stuur die via WhatsApp naar ons, dan koppelen wij je abonnement aan de app.'],
            ['Herstart de app', 'Sluit en heropen de app: de zenderlijst verschijnt. Bij Smart IPTV plak je zelf je M3U-link in op de website van de app.'],
            $done,
        ]],
        ['id' => 'lg', 'icon' => 'tv', 'name' => 'LG Smart TV (webOS)', 'steps' => [
            ['Installeer een speler uit de LG Content Store', 'Zoek naar <b>IBO Player</b>, <b>Smart IPTV</b> of <b>Hot Player</b> en installeer.'],
            ['Stuur ons het toestel-ID', 'Noteer het <b>MAC-adres</b> of <b>toestel-ID</b> dat de app toont en stuur het via WhatsApp. Wij koppelen je abonnement.'],
            ['Herstart de app', 'Sluit en heropen de app: de zenderlijst laadt.'],
            $done,
        ]],
        ['id' => 'windows', 'icon' => 'windows', 'name' => 'Windows-pc', 'steps' => [
            ['Installeer een speler', 'Download <b>IPTV Smarters Pro</b> voor Windows (op de site van de maker) of installeer <b>VLC media player</b>.'],
            ['Smarters: kies Xtream Codes', 'Open Smarters en kies <b>Login with Xtream Codes API</b>, vul je gegevens in.'],
            ['VLC: open je M3U-link', 'In VLC: <b>Media › Netwerkstream openen</b>, plak je M3U-link en speel af. Je krijgt de zenderlijst in de afspeellijst.'],
            $done,
        ]],
        ['id' => 'mac', 'icon' => 'mac', 'name' => 'Mac (macOS)', 'steps' => [
            ['Installeer een speler', 'Download <b>IBO Pro Player</b> of <b>IPTV Smarters</b> uit de Mac App Store, of installeer <b>VLC</b>.'],
            ['Kies Xtream Codes of M3U', 'In IBO/Smarters kies je de Xtream Codes-login; in VLC open je de M3U-link via <b>Bestand › Netwerk openen</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'mag', 'icon' => 'box', 'name' => 'MAG-box / Enigma2 / STB-emulator', 'steps' => [
            ['Zoek het MAC-adres', 'Op een MAG: <b>Instellingen › Systeeminfo</b>. In STBemu: <b>Instellingen › Profiel › MAC-adres</b>.'],
            ['Stuur ons het MAC-adres', 'Via WhatsApp. Wij maken je portaal aan en sturen je de <b>portaal-URL</b>.'],
            ['Geef het portaal in', 'Ga naar <b>Instellingen › Servers › Portalen</b>, plak de portaal-URL en herstart de box.'],
            $done,
        ]],
        ['id' => 'other', 'icon' => 'list', 'name' => 'Ander toestel of alleen de M3U-link', 'steps' => [
            ['Gebruik de M3U-link', 'Bijna elke speler (VLC, Kodi, TiviMate, Perfect Player…) aanvaardt een M3U-afspeellijst. Plak de link die je van ons kreeg bij “playlist” of “netwerkstream”.'],
            ['Xtream Codes als alternatief', 'Ondersteunt je app Xtream Codes? Gebruik dan liever die login: je krijgt automatisch de programmagids en de VOD-bibliotheek erbij.'],
            $done,
        ]],
    ],
    'side' => [
        'title' => 'Waar vind ik mijn login?',
        'text'  => $login,
        'items' => ['Xtream Codes — server-URL, gebruikersnaam, wachtwoord', 'M3U-afspeellijst — een lange link die eindigt op .m3u of m3u_plus', 'Toestel-ID / MAC-adres — voor Samsung, LG en MAG koppelen wij het zelf'],
        'tip'   => 'Je formule bepaalt hoeveel toestellen tegelijk mogen kijken. Installeren mag overal; streamen kan op 1 tot 5 schermen tegelijk.',
    ],
    'trouble' => [
        'title' => 'Lukt het installeren van de app niet?',
        'a' => 'Optie A — Downloader + code ' . $code . ' (Fire TV, Android TV): de snelste weg naar de IBO Pro Player.',
        'b' => 'Optie B — een speler uit de store van je toestel (Play Store, App Store, Samsung, LG).',
        'c' => 'Optie C — VLC met je M3U-link: werkt op elke computer, zonder installatie van een IPTV-app.',
    ],
    'stuck' => [
        'title' => 'Nog altijd vast?',
        'text'  => 'We lossen het live met je op — Fire TV Stick, smart tv, gsm, wat je ook hebt. Stuur een screenshot of een filmpje, dat helpt het meest.',
        'wa'    => 'Open de support via WhatsApp',
        'mail'  => 'Mail ' . $d['brand']['email'],
    ],
    'faq' => [
        ['q' => 'Werkt Mickey TV op mijn oude tv zonder internet-apps?', 'a' => 'Ja, met een klein toestel in de HDMI-poort: een Fire TV Stick (± 40 euro) of een Android TV-box. Volg dan de stappen voor dat toestel.'],
        ['q' => 'Moet ik iets betalen voor de app?', 'a' => 'IBO Pro Player en IPTV Smarters zijn gratis bij Elite; sommige tv-apps van Samsung en LG (zoals Smart IPTV) vragen een kleine eenmalige activatiekost aan de maker van de app. Wij zeggen je vooraf welke app gratis is voor jouw tv.'],
        ['q' => 'Hoeveel toestellen kan ik installeren?', 'a' => 'Zoveel je wil. Het aantal dat tegelijk kan kijken, hangt af van je formule (1 tot 5).'],
    ],
];
