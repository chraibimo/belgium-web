<?php
/** Installation assistant (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
$code = '4738580';
$login = 'Après votre paiement, nous envoyons vos accès par WhatsApp ou e-mail : un identifiant <b>Xtream Codes</b> (serveur, nom d’utilisateur, mot de passe) et un <b>lien M3U</b>. La plupart des applications acceptent les deux.';
$xtream = ['Entrez vos accès', 'Choisissez <b>Xtream Codes</b> (ou « Login with Xtream Codes API »), saisissez l’URL du serveur, votre nom d’utilisateur et votre mot de passe tels que reçus, puis validez. La liste des chaînes se charge en quelques secondes.'];
$done = ['C’est prêt — regardez', 'Ouvrez <b>Live TV</b> pour les chaînes, <b>Films</b> et <b>Séries</b> pour la demande. Ça ne marche pas tout de suite ? Envoyez-nous une capture d’écran sur WhatsApp, on regarde avec vous en direct.'];

return [
    'title'       => 'Installation — Mickey TV sur tous les appareils en 3 à 6 minutes',
    'description' => 'Choisissez votre appareil et suivez les étapes : Fire TV Stick, Android TV, Smart TV Samsung et LG, iPhone et iPad, Windows, Mac, Chromecast, box MAG. Avec le code ' . $code . ' pour l’app Downloader.',
    'search'      => ['title' => 'Installation sur tous les appareils', 'excerpt' => 'Guides pas à pas pour Fire TV Stick, Android TV, Samsung, LG, iPhone, Windows, Mac et plus.', 'text' => 'installer installation firestick android smart tv samsung lg iphone ipad windows mac chromecast mag downloader ibo smarters m3u xtream'],
    'hero' => [
        'badge' => 'Assistant d’installation',
        'title' => 'Mickey TV sur<br>tous les appareils',
        'lede'  => 'Choisissez votre appareil et nous affichons les étapes exactes. Quand c’est possible, nous utilisons l’app <b>Downloader</b> avec le code <b>' . $code . '</b> pour installer IBO Pro Player. Pour les appareils sans APK, nous vous orientons vers la bonne application du store.',
        'time'  => 'Généralement prêt en 3 à 6 minutes',
        'help'  => 'Besoin d’aide ? Chattez avec nous',
    ],
    'code'   => $code,
    'code_label' => 'Code Downloader',
    'pick'   => 'Sur quoi installez-vous ?',
    'show'   => 'Afficher mes étapes',
    'steps_title' => 'Étapes pour %s',
    'devices' => [
        ['id' => 'firetv', 'icon' => 'stick', 'name' => 'Amazon Fire TV Stick / Cube', 'steps' => [
            ['Autorisez les apps inconnues', 'Allez dans <b>Paramètres › Ma Fire TV › Options pour les développeurs</b> et activez <b>Applications de sources inconnues</b>. Vous ne voyez pas les options développeur ? Appuyez 7 fois sur <b>À propos › Fire TV Stick</b>.'],
            ['Installez Downloader', 'Cherchez <b>Downloader</b> (AFTVnews) dans l’Appstore Amazon et installez-le. Ouvrez-le et autorisez l’accès aux fichiers.'],
            ['Entrez le code', 'Dans la barre d’adresse, tapez le code <b>' . $code . '</b> et appuyez sur Go. IBO Pro Player se télécharge et l’installation démarre. Choisissez <b>Installer</b>, puis <b>Ouvrir</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'androidtv', 'icon' => 'tv', 'name' => 'Android TV / Google TV (Sony, TCL, Philips, Hisense, Shield)', 'steps' => [
            ['Installez un lecteur depuis le Play Store', 'Sur votre télé, cherchez <b>IBO Pro Player</b> ou <b>IPTV Smarters Pro</b> dans le <b>Google Play Store</b> et installez. Introuvable ? Installez <b>Downloader</b> et utilisez le code <b>' . $code . '</b>.'],
            ['Ouvrez l’application', 'Au premier lancement, l’app peut afficher une activation à l’écran ; suivez l’instruction ou envoyez-nous l’ID de l’appareil sur WhatsApp, nous activons.'],
            $xtream, $done,
        ]],
        ['id' => 'android', 'icon' => 'android', 'name' => 'Smartphone ou tablette Android', 'steps' => [
            ['Installez IPTV Smarters Pro', 'Ouvrez le <b>Play Store</b>, cherchez <b>IPTV Smarters Pro</b> (ou IBO Pro Player) et installez.'],
            ['Choisissez Xtream Codes', 'Ouvrez l’app et choisissez <b>Login with Xtream Codes API</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'chromecast', 'icon' => 'cast', 'name' => 'Chromecast avec Google TV', 'steps' => [
            ['Installez un lecteur', 'Cherchez <b>IBO Pro Player</b> ou <b>IPTV Smarters Pro</b> dans le <b>Play Store</b> du Chromecast. Ou installez <b>Downloader</b> et utilisez le code <b>' . $code . '</b>.'],
            ['Ouvrez l’app et choisissez Xtream Codes', 'Au démarrage, choisissez la connexion Xtream Codes.'],
            $xtream, $done,
        ]],
        ['id' => 'ios', 'icon' => 'apple', 'name' => 'iPhone / iPad / Apple TV', 'steps' => [
            ['Installez un lecteur depuis l’App Store', 'Cherchez <b>IPTV Smarters</b> ou <b>IBO Pro Player</b> dans l’<b>App Store</b> et installez.'],
            ['Choisissez Xtream Codes', 'Ouvrez l’app et choisissez la connexion Xtream Codes (ou « Add playlist » avec votre lien M3U).'],
            $xtream, $done,
        ]],
        ['id' => 'samsung', 'icon' => 'tv', 'name' => 'Smart TV Samsung (Tizen)', 'steps' => [
            ['Installez un lecteur depuis le store Samsung', 'Cherchez <b>IBO Player</b>, <b>Smart IPTV</b> ou <b>Hot Player</b> sur votre télé et installez. Certaines de ces applications demandent des frais d’activation uniques à leur éditeur.'],
            ['Notez l’ID de l’appareil', 'Au démarrage, l’app affiche une <b>adresse MAC</b> ou un <b>ID d’appareil</b>, parfois une clé. Envoyez-les-nous sur WhatsApp : nous lions votre abonnement à l’app.'],
            ['Redémarrez l’app', 'Fermez et rouvrez l’app : la liste des chaînes apparaît. Avec Smart IPTV, vous collez vous-même votre lien M3U sur le site de l’app.'],
            $done,
        ]],
        ['id' => 'lg', 'icon' => 'tv', 'name' => 'Smart TV LG (webOS)', 'steps' => [
            ['Installez un lecteur depuis le LG Content Store', 'Cherchez <b>IBO Player</b>, <b>Smart IPTV</b> ou <b>Hot Player</b> et installez.'],
            ['Envoyez-nous l’ID de l’appareil', 'Notez l’<b>adresse MAC</b> ou l’<b>ID d’appareil</b> affiché par l’app et envoyez-le sur WhatsApp. Nous lions votre abonnement.'],
            ['Redémarrez l’app', 'Fermez et rouvrez l’app : la liste des chaînes se charge.'],
            $done,
        ]],
        ['id' => 'windows', 'icon' => 'windows', 'name' => 'PC Windows', 'steps' => [
            ['Installez un lecteur', 'Téléchargez <b>IPTV Smarters Pro</b> pour Windows (sur le site de l’éditeur) ou installez <b>VLC media player</b>.'],
            ['Smarters : choisissez Xtream Codes', 'Ouvrez Smarters, choisissez <b>Login with Xtream Codes API</b> et saisissez vos accès.'],
            ['VLC : ouvrez votre lien M3U', 'Dans VLC : <b>Média › Ouvrir un flux réseau</b>, collez votre lien M3U et lancez la lecture. La liste des chaînes apparaît dans la liste de lecture.'],
            $done,
        ]],
        ['id' => 'mac', 'icon' => 'mac', 'name' => 'Mac (macOS)', 'steps' => [
            ['Installez un lecteur', 'Téléchargez <b>IBO Pro Player</b> ou <b>IPTV Smarters</b> sur le Mac App Store, ou installez <b>VLC</b>.'],
            ['Choisissez Xtream Codes ou M3U', 'Dans IBO/Smarters, choisissez la connexion Xtream Codes ; dans VLC, ouvrez le lien M3U via <b>Fichier › Ouvrir un flux réseau</b>.'],
            $xtream, $done,
        ]],
        ['id' => 'mag', 'icon' => 'box', 'name' => 'Box MAG / Enigma2 / émulateur STB', 'steps' => [
            ['Trouvez l’adresse MAC', 'Sur un MAG : <b>Paramètres › Infos système</b>. Dans STBemu : <b>Paramètres › Profil › Adresse MAC</b>.'],
            ['Envoyez-nous l’adresse MAC', 'Sur WhatsApp. Nous créons votre portail et vous envoyons l’<b>URL du portail</b>.'],
            ['Entrez le portail', 'Allez dans <b>Paramètres › Serveurs › Portails</b>, collez l’URL du portail et redémarrez la box.'],
            $done,
        ]],
        ['id' => 'other', 'icon' => 'list', 'name' => 'Autre appareil ou juste le lien M3U', 'steps' => [
            ['Utilisez le lien M3U', 'Presque tous les lecteurs (VLC, Kodi, TiviMate, Perfect Player…) acceptent une liste M3U. Collez le lien reçu dans « playlist » ou « flux réseau ».'],
            ['Xtream Codes en alternative', 'Votre app gère Xtream Codes ? Préférez cet identifiant : vous obtenez automatiquement le guide des programmes et le catalogue VOD.'],
            $done,
        ]],
    ],
    'side' => [
        'title' => 'Où trouver mes accès ?',
        'text'  => $login,
        'items' => ['Xtream Codes — URL du serveur, nom d’utilisateur, mot de passe', 'Liste M3U — un long lien qui se termine par .m3u ou m3u_plus', 'ID d’appareil / adresse MAC — pour Samsung, LG et MAG, nous faisons la liaison nous-mêmes'],
        'tip'   => 'Votre formule détermine combien d’appareils peuvent regarder en même temps. L’installation est possible partout ; le streaming, sur 1 à 5 écrans simultanés.',
    ],
    'trouble' => [
        'title' => 'L’installation de l’app ne fonctionne pas ?',
        'a' => 'Option A — Downloader + code ' . $code . ' (Fire TV, Android TV) : le chemin le plus rapide vers IBO Pro Player.',
        'b' => 'Option B — un lecteur depuis le store de votre appareil (Play Store, App Store, Samsung, LG).',
        'c' => 'Option C — VLC avec votre lien M3U : fonctionne sur tout ordinateur, sans installer d’app IPTV.',
    ],
    'stuck' => [
        'title' => 'Toujours bloqué ?',
        'text'  => 'On règle ça en direct avec vous — Fire TV Stick, smart TV, GSM, ce que vous avez. Envoyez une capture ou une petite vidéo, c’est ce qui aide le plus.',
        'wa'    => 'Ouvrir le support sur WhatsApp',
        'mail'  => 'Écrire à ' . $d['brand']['email'],
    ],
    'faq' => [
        ['q' => 'Mickey TV fonctionne-t-il sur ma vieille télé sans applications ?', 'a' => 'Oui, avec un petit appareil dans le port HDMI : un Fire TV Stick (± 40 euros) ou une box Android TV. Suivez ensuite les étapes de cet appareil.'],
        ['q' => 'Dois-je payer pour l’application ?', 'a' => 'IBO Pro Player et IPTV Smarters sont gratuits avec Elite ; certaines apps de télé Samsung et LG (comme Smart IPTV) demandent de petits frais d’activation uniques à leur éditeur. Nous vous disons à l’avance quelle app est gratuite pour votre télé.'],
        ['q' => 'Sur combien d’appareils puis-je installer ?', 'a' => 'Autant que vous voulez. Le nombre d’appareils qui regardent en même temps dépend de votre formule (1 à 5).'],
    ],
];
