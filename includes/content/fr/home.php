<?php
/** Home page copy (French). */
if (!defined('PRIMO_APP')) exit;

$d = site_data();
$s = $d['stats'];

return [
    'title'       => 'Mickey TV — télé en direct, sport, films et séries en 4K en Belgique',
    'description' => 'Mickey TV : plus de ' . nf_plus($s['channels']) . ' chaînes en direct, ' . nf_plus($s['vod']) . ' films et séries, le sport en 4K. Sur tous vos appareils, sans engagement, satisfait ou remboursé. Français et néerlandais.',

    'hero' => [
        'badge'  => '4K',
        'line1'  => 'Mickey',
        'line2'  => 'TV',
        'lead'   => 'Toutes les chaînes de Belgique et du monde, chaque match, chaque film — sur la télé, le portable et le GSM que vous avez déjà. Sans décodeur, sans technicien, sans engagement.',
        'pills'  => [nf_plus($s['channels']) . ' chaînes', nf_plus($s['vod']) . ' films & séries', 'FR & NL', 'HD · 4K'],
        'cta1'   => 'Voir les formules',
        'cta2'   => 'Essai gratuit via WhatsApp',
        'foot'   => 'Télé en direct · sport · films · séries',
        'now'    => 'En ce moment sur la chaîne',
        'hint'   => 'Tournez le bouton — ou tapez sur la télé si l’image saute.',
        'channels' => [
            ['id' => 'sport',  'name' => 'Sport',   'meta' => 'Pro League · Ligue des champions · F1 · cyclisme', 'link' => 'channels'],
            ['id' => 'film',   'name' => 'Film',    'meta' => nf_plus($s['movies']) . ' chaînes cinéma + à la demande',       'link' => 'pricing'],
            ['id' => 'series', 'name' => 'Séries',  'meta' => 'Des saisons entières, sans attendre',                          'link' => 'pricing'],
            ['id' => 'kids',   'name' => 'Kids',    'meta' => 'Ouftivi, Nickelodeon, Disney Channel, Gulli',                  'link' => 'channels'],
            ['id' => 'news',   'name' => 'Infos',   'meta' => 'RTBF, LN24, VRT NWS, France 24, BBC, CNN',                    'link' => 'channels'],
            ['id' => 'music',  'name' => 'Musique', 'meta' => 'MTV, NRJ Hits, Stingray, Mezzo',                                'link' => 'channels'],
            ['id' => 'docu',   'name' => 'Docu',    'meta' => 'National Geographic, Discovery, Arte, La Trois',                'link' => 'channels'],
        ],
    ],

    'ready' => [
        'eyebrow' => 'Prêt à regarder ?',
        'title'   => 'Tout ce que vous voulez voir. <em>Au même endroit.</em>',
        'lede'    => 'Votre abonnement Mickey TV est bien plus qu’une liste de chaînes : c’est un pass pour tout l’univers télé. Enchaîner les dernières séries, découvrir un blockbuster ou revoir un classique — en HD et 4K, quand vous voulez.',
        'checks'  => [nf_plus($s['channels']) . ' chaînes de ' . $s['countries'] . ' pays', nf_plus($s['vod']) . ' films et séries à la demande', 'Toutes les chaînes belges en français et en néerlandais'],
        'button'  => 'Voir la liste des chaînes',
    ],

    'stats' => [
        'eyebrow' => 'En chiffres',
        'title'   => 'Assez grand pour toute la famille',
        'items'   => [
            ['num' => $s['channels'],  'suffix' => '+', 'label' => 'Chaînes',         'sub' => 'En direct, de ' . $s['countries'] . ' pays et régions'],
            ['num' => $s['vod'],       'suffix' => '+', 'label' => 'Films & séries',  'sub' => 'À la demande, renouvelés chaque semaine'],
            ['num' => $s['sports'],    'suffix' => '+', 'label' => 'Chaînes sport',   'sub' => 'Foot, cyclisme, tennis, F1, PPV'],
            ['num' => 99.9,            'suffix' => '%', 'label' => 'Disponibilité',   'sub' => 'Serveurs anti-freeze, jamais d’écran noir'],
        ],
    ],

    'who' => [
        'eyebrow' => 'Qui nous sommes',
        'title'   => 'Conçu pour une <em>expérience 4K</em> sans prise de tête',
        'lede'    => 'Chez Mickey TV, on est obsédés par la qualité et la stabilité. Notre service est construit de A à Z pour livrer un flux 4K impeccable, pour que vous regardiez vos programmes préférés sans mise en mémoire tampon ni décalage.',
        'claims'  => ['Plusieurs appareils en même temps', 'Fonctionne sur toute connexion dès 20 Mbps', 'Support en français et en néerlandais, tous les jours'],
        'button1' => 'S’abonner',
        'button2' => 'Comment se passe l’installation →',
        'osd'     => 'LIVE · 4K UHD · ' . nf_plus($s['channels']) . ' chaînes',
    ],

    'cc' => [
        'eyebrow'  => 'Pour ceux qui coupent le câble',
        'title'    => 'Tout ce que vous payez aujourd’hui, <em>et plus</em>',
        'lede'     => 'Chaque chaîne que vous avez chez VOO, Proximus ou Telenet, plus les chaînes sport, les films et les séries que vous regardez depuis des années — pour moins qu’un seul service de streaming.',
        'button'   => 'Voir les tarifs',
        'pill'     => 'Offre Elite VIP',
        'kicker'   => 'Vérification',
        'h3'       => 'Votre chaîne est-elle sur Mickey TV ?',
        'placeholders' => ['La Une', 'RTL-TVI', 'DAZN', 'Eleven Sports', 'Tipik', 'VRT 1', 'Canal+', 'beIN Sports', 'TF1', 'Play Sports', 'Ouftivi', 'Sky Sports'],
        'note'     => 'Il s’agit de l’offre Elite VIP : chaque chaîne trouvée ici est incluse dans la formule Elite VIP. La formule Essential propose une sélection plus réduite.',
        'stats'    => [
            ['num' => $s['channels'], 'label' => 'chaînes live'],
            ['num' => $s['sports'],   'label' => 'sport'],
            ['num' => $s['vod'],      'label' => 'films & séries'],
            ['num' => $s['kids'],     'label' => 'kids'],
            ['num' => $s['countries'],'label' => 'pays', 'step' => 1],
            ['num' => $s['news'],     'label' => 'infos'],
        ],
    ],

    'features' => [
        'eyebrow' => 'Ce qui nous distingue',
        'title'   => 'Regarder comme <em>il se doit</em>',
        'items'   => [
            ['icon' => 'phone',   'title' => 'Partout avec vous', 'text' => 'Ne ratez rien. Télé en direct et pay-per-view sur votre GSM ou votre tablette, dans le train, en vacances ou au jardin.'],
            ['icon' => 'film',    'title' => 'Contenus choisis',  'text' => 'Blockbusters, les séries dont tout le monde parle et les classiques — au même endroit, en un clic.'],
            ['icon' => 'bolt',    'title' => 'Sport en direct',   'text' => 'Pro League, Ligue des champions, les classiques, le tennis et la F1 — en direct et en HD, sans interruption.'],
        ],
        'compare_title' => 'La différence avec <em>les autres</em>',
        'compare_head'  => ['Comparez', 'Autres fournisseurs', 'Mickey TV'],
        'compare' => [
            ['Chaînes en direct',       '12 000+',  nf_plus($s['channels']), 28, 100],
            ['Films & séries',          '40 000+',  nf_plus($s['vod']),      33, 100],
            ['Support après achat',     'Limité',   'Illimité, FR & NL',     30, 100],
            ['Technologie anti-freeze', '✖',        '✔',                     0,  100],
            ['Guide des programmes (EPG)', '✖',     '✔',                     0,  100],
            ['Disponibilité garantie 99,9 %', '✖',  '✔',                     0,  100],
        ],
    ],

    'faq' => [
        'eyebrow' => 'Réponses',
        'title'   => 'Questions <em>fréquentes</em>',
        'lede'    => 'Paiement, appareils, installation et débit : ce qu’on nous demande avant de s’abonner. Votre question n’y est pas ? Notre équipe est à un message.',
        'side_title' => 'Une vraie aide, tous les jours',
        'cta_title'  => 'Encore une question ?',
        'cta_text'   => 'Notre équipe répond en français et en néerlandais, avant et après votre abonnement.',
        'cta_button' => 'Contacter le support',
        'items' => [
            ['q' => 'Quels moyens de paiement acceptez-vous ?', 'a' => 'Bancontact, carte de crédit (Visa, Mastercard, American Express) et cryptomonnaies. Le paiement est chiffré et vos accès partent dès que la commande est confirmée.'],
            ['q' => 'Comment reçois-je mes accès après le paiement ?', 'a' => 'Immédiatement. Dès que votre paiement est validé, nous envoyons votre lien M3U et votre identifiant Xtream Codes par e-mail, ou par WhatsApp si vous préférez. Rien n’est expédié et il n’y a aucun appareil à acheter.'],
            ['q' => 'Comment installer Mickey TV sur mon appareil ?', 'a' => 'De deux façons. Suivez les guides pas à pas de notre <a href="' . url('tutorial') . '">page d’installation</a>, ou faisons-le ensemble : notre support vous accompagne en tête-à-tête sur votre propre appareil jusqu’à ce que l’image s’affiche.'],
            ['q' => 'Puis-je utiliser une connexion sur plusieurs appareils ?', 'a' => 'Vous pouvez installer vos accès sur autant d’appareils que vous voulez, mais un seul peut regarder à la fois. Deux écrans en même temps ? Ajoutez un deuxième appareil à votre formule.'],
            ['q' => 'Quel débit internet faut-il pour la 4K ?', 'a' => 'Pour un flux 4K fluide et stable, nous recommandons une connexion d’au moins 30 à 40 Mbps. En HD, 15 Mbps suffisent généralement. Toute connexion câble ou fibre belge dépasse largement ces valeurs ; un wifi stable compte plus qu’un wifi rapide.'],
            ['q' => 'Toutes les chaînes sont-elles en 4K ?', 'a' => 'Pas toutes. Une grande partie de nos chaînes sport et cinéma premium est en 4K, mais beaucoup de chaînes émettent encore en HD, et c’est le flux qu’elles nous fournissent. Nous relayons la meilleure qualité disponible pour chaque chaîne, et la liste 4K grandit chaque mois.'],
            ['q' => 'Sur quels appareils fonctionne Mickey TV ?', 'a' => '<p>Notre service fonctionne sur pratiquement tous les appareils récents :</p><ul><li>Smart TV (Samsung, LG, Sony, Philips, TCL…)</li><li>Amazon Fire TV Stick et Fire TV Cube</li><li>Box Android TV et Google TV</li><li>NVIDIA Shield et Apple TV</li><li>Smartphones et tablettes (iOS et Android)</li><li>PC et Mac</li></ul>'],
        ],
    ],

    'guarantee' => [
        'title' => 'Sans risque : <em>remboursé</em> si ça ne vous plaît pas',
        'text'  => 'Satisfait ou remboursé. Essayez Mickey TV sans risque grâce à notre garantie : 7 jours sur Essential, 15 jours sur Elite VIP. Pas entièrement convaincu ? Nous vous remboursons, sans discussion.',
        'button'=> 'Lire la politique de remboursement',
    ],
];
