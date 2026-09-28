<?php
/** About page (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data(); $s = $d['stats'];
return [
    'title'       => 'À propos — qui se cache derrière Mickey TV',
    'description' => 'Mickey TV est un service télé belge en français et en néerlandais : qui nous sommes, ce que nous proposons et pourquoi nous faisons les choses autrement.',
    'search'      => ['title' => 'À propos de Mickey TV', 'excerpt' => 'Qui nous sommes, ce que nous proposons et comment le service est construit.', 'text' => 'à propos qui sommes-nous société équipe belgique'],
    'hero' => ['badge' => 'À propos', 'title' => 'La télé comme<br>il se doit', 'lede' => 'Qui nous sommes, ce que nous proposons et comment le service est construit — sans détour.'],
    'intro' => [
        'title' => 'Qui nous <em>sommes</em>',
        'text'  => ['Mickey TV est un jeune service belge, lancé par des gens qui ont eux-mêmes payé trop longtemps trop cher pour trop peu de télé. Nous voulions un seul abonnement avec tout ce qu’une famille belge regarde — en français et en néerlandais — qui fonctionne sur les appareils que vous avez déjà, et où une personne décroche quand quelque chose ne va pas.', 'Nous sommes revendeur : les flux viennent de prestataires spécialisés, nous assurons la sélection, l’identifiant, l’installation et le support dans votre langue. Exactement la partie que les autres services négligent.'],
    ],
    'offer' => [
        'title' => 'Ce que nous <em>proposons</em>',
        'items' => [
            ['Un choix de chaînes énorme', nf_plus($s['channels']) . ' chaînes en direct de ' . $s['countries'] . ' pays et régions, avec toutes les chaînes belges dans les deux langues nationales.'],
            ['Un riche catalogue à la demande', nf_plus($s['vod']) . ' films et séries, renouvelés chaque semaine, avec sous-titres français et néerlandais quand ils existent.'],
            ['Une image sans saccades', 'HD, 4K et 8K là où la chaîne l’émet, sur des serveurs conçus pour les pics d’un grand match.'],
            ['La simplicité', 'Un identifiant, une application, trois minutes d’installation. Pas de décodeur, pas de technicien, pas d’engagement.'],
            ['Tous les appareils', 'Smart TV, Fire TV Stick, box Android, iPhone, Android, PC, Mac, MAG — jusqu’à cinq écrans en même temps.'],
            ['Un support dans votre langue', 'Français et néerlandais, tous les jours de 9h à 23h, par WhatsApp, e-mail ou formulaire — une personne, pas un robot.'],
        ],
    ],
    'why' => [
        'title' => 'Pourquoi <em>Mickey TV</em>',
        'items' => [
            ['Stabilité', $s['uptime'] . ' de disponibilité et technologie anti-freeze : pas d’écran noir au moment qui compte.'],
            ['Prix honnêtes', 'À partir de 18,99 € par mois. Plus vous choisissez long, moins vous payez. Pas de frais cachés.'],
            ['Support dans votre langue', 'Français et néerlandais, tous les jours de 9h à 23h, par WhatsApp, e-mail ou formulaire.'],
            ['Remboursé', '7 jours sur Essential, 15 sur Elite VIP. Essayez sans risque.'],
        ],
    ],
    'join' => ['title' => 'Rejoignez-nous', 'text' => 'Des questions ? Envoyez-nous un message sur WhatsApp ou par e-mail, ou demandez un essai gratuit de 24 heures.'],
];
