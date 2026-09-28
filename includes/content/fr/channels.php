<?php
/** Channels page copy (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data(); $s = $d['stats'];
return [
    'title'       => 'Liste des chaînes — toutes les chaînes de Belgique et du monde',
    'description' => 'Parcourez la liste complète des chaînes Mickey TV : ' . nf_plus($s['channels']) . ' chaînes de ' . $s['countries'] . ' pays et régions. RTBF, RTL, VRT, VTM, DAZN, Eleven, Canal+, Sky Sports et plus.',
    'search'      => ['title' => 'Liste des chaînes', 'excerpt' => 'Toutes les chaînes de Belgique et du monde, à chercher par nom.', 'text' => 'chaînes liste rtbf rtl vrt vtm dazn eleven canal+ sky sport kids infos'],
    'hero' => [
        'badge' => 'Liste des chaînes',
        'title' => 'Chaque chaîne.<br>Chaque pays.',
        'lede'  => nf_plus($s['channels']) . ' chaînes en direct de ' . $s['countries'] . ' pays et régions : la Belgique en français et en néerlandais, la France, les Pays-Bas, le Royaume-Uni, l’Europe et le monde arabe, en HD, 4K et 8K. Cherchez toute la liste par nom.',
        'search_placeholder' => 'Chercher une chaîne — La Une, RTL-TVI, DAZN, Sky Sports…',
        'chips' => ['HD · 4K · 8K', $s['countries'] . ' pays & régions', 'Replay 24 h (Elite)', nf_plus($s['vod']) . ' films & séries'],
        'cta1'  => 'Voir les formules',
    ],
    'belgium' => [
        'eyebrow' => 'Pour la Belgique',
        'title'   => 'Toutes les chaînes belges, <em>FR et NL</em>',
        'lede'    => 'De La Une et RTL-TVI à Één et VTM, de DAZN et Eleven à Ouftivi et Ketnet : toute l’offre belge dans les deux langues nationales, plus les chaînes françaises et néerlandaises que chaque famille regarde ici.',
        'groups'  => [
            ['Wallonie & Bruxelles', ['La Une', 'Tipik', 'La Trois', 'RTL-TVI', 'Club RTL', 'Plug RTL', 'LN24', 'BX1', 'Canal Z', 'AB3', 'ABXplore', 'Télé MB', 'Notélé', 'TV Lux', 'Canal Zoom', 'RTC Télé Liège']],
            ['Flandre', ['VRT 1', 'VRT Canvas', 'Ketnet', 'VTM', 'VTM 2', 'VTM 3', 'VTM 4', 'Play 4', 'Play 5', 'Play 6', 'Play 7', 'Kanaal Z', 'ATV', 'TV Oost', 'Bruzz']],
            ['Sport', ['DAZN Pro League', 'Eleven Sports 1–2', 'VOO Sport World 1–3', 'Play Sports 1–4', 'Eurosport 1–2', 'Canal+ Sport', 'Sky Sports', 'beIN Sports', 'Infosport+', 'Sport 10']],
            ['Kids & cinéma', ['Ouftivi', 'Gulli', 'Piwi+', 'Nickelodeon', 'Nick Jr', 'Disney Channel', 'Cartoon Network', 'Boomerang', 'Canal+ Cinéma', 'Ciné+', 'TCM Cinéma', 'Warner TV']],
        ],
        'note' => 'Ceci n’est qu’un aperçu. Cliquez sur un nom pour vérifier qu’elle est incluse, ou cherchez ci-dessus dans la liste complète.',
    ],
    'guide' => [
        'hint'  => 'Tapez au moins deux lettres. Les résultats s’affichent pendant que vous écrivez.',
        'note'  => 'Il s’agit de l’offre Elite VIP : chaque chaîne trouvée ici est incluse dans la formule Elite VIP. La formule Essential propose une sélection plus réduite.',
    ],
    'tv' => [
        'kicker' => 'Mickey regarde avec vous',
        'name'   => 'Quelle chaîne cherchez-vous ?',
        'meta'   => 'Tapez un nom, je me règle dessus.',
    ],
    'events' => [
        'title'  => 'Bientôt en direct',
        'lede'   => 'Les grands matchs et courses des prochaines semaines, à l’heure belge.',
        'find'   => 'Trouver la chaîne',
        'days'   => ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
        'months' => ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'],
    ],
    'included' => [
        'title' => 'Un abonnement. <em>Tout compris.</em>',
        'items' => [
            [nf_plus($s['channels']), 'Chaînes en direct', 'De ' . $s['countries'] . ' pays et régions, en HD, 4K et 8K.'],
            ['4K & 8K', 'Qualité d’image', 'Le meilleur flux diffusé par chaque chaîne.'],
            ['24 h', 'Replay', 'Une émission ratée ? Revoyez-la jusqu’à 24 heures après (Elite).'],
            [nf_plus($s['vod']), 'À la demande', 'Films et saisons complètes, renouvelés chaque semaine.'],
            ['FR & NL', 'Deux langues', 'Toute l’offre belge dans les deux langues nationales.'],
            ['Demandez', 'Une chaîne manque ?', 'Cherchez d’abord dans le guide, puis contactez-nous. Si elle existe, on peut généralement l’ajouter.'],
        ],
    ],
];
