<?php
/** Pricing copy (French). */
if (!defined('PRIMO_APP')) exit;

$d = site_data();
$s = $d['stats'];

return [
    'title'       => 'Tarifs — formules à partir de 18,99 € par mois',
    'description' => 'Formules Mickey TV : Essential et Elite VIP, de 1 à 12 mois, sur 1 à 5 appareils en simultané. Sans engagement, paiement en euros, 7 ou 15 jours satisfait ou remboursé.',
    'search'      => ['title' => 'Tarifs et formules', 'excerpt' => 'Essential et Elite VIP, de 1 à 12 mois, sur 1 à 5 appareils en simultané. Sans engagement, satisfait ou remboursé.', 'text' => 'prix tarifs abonnement formule coût euro bancontact mois an'],

    'block' => [
        'eyebrow'   => 'Semaine promo',
        'title'     => 'Pas de décodeur. Pas de technicien. <em>Pas d’engagement.</em>',
        'lede'      => 'Gardez la télé et le wifi que vous payez déjà — on vous envoie simplement un identifiant. Rien à installer, rien à débrancher, rien à renvoyer. Pas pour vous finalement ? Remboursement intégral : 7 jours sur Essential, 15 sur Elite.',
        'timer'     => 'Cette promo se termine dans',
        'trust'     => ['HD · 4K · 8K', nf_plus($s['channels']) . ' chaînes en direct', 'Remboursé, 7 & 15 jours', 'Support sur WhatsApp'],
        'plans' => [
            'vip' => [
                'name'     => 'Elite VIP',
                'band'     => 'Pack Elite',
                'flag'     => 'Le plus choisi',
                'tag'      => 'Si vous voulez le replay, le PPV et l’app',
                'features' => [
                    'Plus de ' . nf_plus($s['channels']) . ' chaînes en direct',
                    nf_plus($s['vod']) . ' films & séries (renouvelés chaque semaine)',
                    'Toutes les chaînes belges, FR et NL',
                    'Toutes les chaînes sport + événements PPV',
                    '4K, UHD, FHD et HD',
                    'Guide des programmes complet (EPG)',
                    'Replay 24 h sur le direct',
                    'Technologie anti-freeze',
                    'Application IBO Pro Player incluse',
                    'Support 7j/7 en FR et NL',
                    '15 jours satisfait ou remboursé',
                ],
            ],
            'essential' => [
                'name'     => 'Essential',
                'band'     => 'Pack de base',
                'flag'     => '',
                'tag'      => 'Si une seule télé suffit',
                'features' => [
                    'Plus de 30 000 chaînes en direct',
                    '90 000+ films & séries',
                    'Chaînes belges, FR et NL',
                    'Toutes les chaînes sport',
                    'UHD, FHD et HD',
                    'Guide des programmes limité (EPG)',
                    'Serveurs stables, 99 % de disponibilité',
                    'Support 7j/7 en FR et NL',
                    'Un VPN est parfois nécessaire',
                    '7 jours satisfait ou remboursé',
                ],
            ],
        ],
    ],

    'compare' => [
        'title' => 'Essential et Elite, <em>ligne par ligne</em>',
        'lede'  => 'Mêmes serveurs, même support, même promesse de remboursement. La différence tient à la taille du catalogue, à la qualité d’image et à ce qui se passe une fois le match terminé.',
        'head'  => ['Caractéristique', 'Essential', 'Elite VIP'],
        'rows'  => [
            ['Chaînes en direct', '30 000+', nf_plus($s['channels'])],
            ['Films et séries à la demande', '90 000+', nf_plus($s['vod']) . ', renouvelés chaque semaine'],
            ['Qualité d’image', 'UHD, FHD, HD', '4K, UHD, FHD, HD'],
            ['Guide des programmes (EPG)', 'Limité', 'Complet'],
            ['Chaînes sport', 'Incluses', 'Incluses'],
            ['PPV et événements sportifs', '—', 'Inclus'],
            ['Replay 24 h', '—', 'Complet'],
            ['Flux anti-freeze', '—', 'Inclus'],
            ['Application IBO Pro Player', '—', 'Offerte'],
            ['Application dédiée', '—', 'Incluse'],
            ['Catégories personnalisables', 'Incluses', 'Incluses'],
            ['Disponibilité des serveurs', '99 %', '99,9 %'],
            ['Support', '7j/7 sur WhatsApp, FR & NL', '7j/7 sur WhatsApp, FR & NL'],
            ['Durées', '1, 3, 6, 12 mois', '1, 3, 6, 12 mois'],
            ['Appareils en simultané', '1 à 5', '1 à 5'],
            ['Délai de remboursement', '7 jours', '15 jours'],
        ],
    ],

    'how' => [
        'title' => 'Comment ça <em>marche</em>',
        'lede'  => 'Trois étapes, et aucune n’implique un technicien, un décodeur ou une livraison.',
        'steps' => [
            ['Choisissez votre formule', 'Essential ou Elite, de 1 à 12 mois, sur 1 à 5 appareils en simultané. Chaque combinaison est affichée sur le ticket — et plus la durée est longue, moins vous payez par mois.'],
            ['Payez', 'Vous payez une fois pour la durée choisie, par Bancontact, carte ou crypto. Pas d’engagement et rien ne se renouvelle tout seul.'],
            ['Connectez-vous et regardez', 'Nous vous envoyons un identifiant par WhatsApp ou e-mail. Vous l’entrez dans l’application que votre appareil utilise déjà, et l’installation est terminée.'],
        ],
    ],

    'devices' => [
        'title' => 'Sur la télé que vous <em>avez déjà</em>',
        'lede'  => 'Vous gardez votre téléviseur et le wifi que vous payez déjà. Rien n’est installé, rien n’est débranché, rien n’est expédié.',
        'items' => [
            ['1 à 5 appareils', 'Un identifiant pour la télé du salon, la tablette et le GSM. Choisissez combien d’écrans peuvent regarder en même temps.'],
            ['Elite apporte ses propres apps', 'Elite inclut IBO Pro Player gratuitement et notre propre application. Essential fonctionne avec le lecteur que votre appareil possède déjà.'],
            ['Demandez avant d’acheter', 'Dites-nous sur quoi vous regardez et nous vous dirons franchement si ça fonctionne. Certains fournisseurs d’accès freinent le streaming — un VPN règle le problème et nous le configurons avec vous.'],
        ],
    ],

    'refund' => [
        'title' => 'Essayez. Pas convaincu ? <em>Remboursé.</em>',
        'text'  => '7 jours sur Essential, 15 jours sur Elite. Sans question. Rien à débrancher, rien à rapporter, rien à renvoyer — la seule chose que vous pouvez perdre, c’est le temps qu’il a fallu pour essayer.',
    ],

    'faq' => [
        'title' => 'Les questions qu’on nous pose <em>vraiment</em>',
        'items' => [
            ['q' => 'Que reçois-je après le paiement ?', 'a' => 'Un identifiant (lien M3U et Xtream Codes) par WhatsApp ou e-mail, généralement en quelques minutes. Vous l’entrez dans l’application de votre appareil et vous regardez.'],
            ['q' => 'Y a-t-il un engagement ?', 'a' => 'Non. Vous payez une fois pour la durée que vous choisissez. Rien ne se renouvelle tout seul et vous n’avez rien à résilier.'],
            ['q' => 'Puis-je être remboursé ?', 'a' => 'Oui. 7 jours sur Essential, 15 jours sur Elite, sans question. Envoyez-nous simplement un message avec votre numéro de commande.'],
            ['q' => 'Sur combien d’appareils puis-je regarder ?', 'a' => 'Jusqu’à cinq en même temps, selon la formule choisie. L’installation est possible sur autant d’appareils que vous voulez.'],
            ['q' => 'Ai-je besoin d’un VPN ?', 'a' => 'Généralement non. Chez certains fournisseurs ou sur certains appareils, un VPN aide à éviter les coupures ; si c’est votre cas, nous le configurons ensemble.'],
            ['q' => 'Quelle est la différence entre Essential et Elite ?', 'a' => 'Elite offre le plus grand catalogue, la 4K, le guide complet, le replay 24 h, les événements PPV, les flux anti-freeze et les applications incluses. Essential est la formule compacte pour une seule télé et l’essentiel.'],
            ['q' => 'Que puis-je regarder exactement ?', 'a' => 'Consultez la <a href="' . url('channels') . '">liste complète des chaînes</a> : toutes les chaînes belges en français et en néerlandais, les chaînes françaises et néerlandaises, le sport, le cinéma, les enfants et plus de 70 pays.'],
            ['q' => 'Mes accès ne sont pas arrivés. Que faire ?', 'a' => 'Envoyez un WhatsApp au ' . $d['brand']['phone'] . '. Une personne répond, tous les jours de 9h à 23h.'],
        ],
    ],

    'deciding' => [
        'title' => 'Vous <em>hésitez</em> encore ?',
        'lede'  => 'Dites-nous ce que vous regardez et sur quel appareil. Nous vous dirons quelle formule convient — ou que nous ne sommes pas le bon service pour vous, si c’est la réponse honnête.',
        'wa'    => 'Écrivez-nous sur WhatsApp',
        'list'  => 'Voir la liste des chaînes',
    ],
];
