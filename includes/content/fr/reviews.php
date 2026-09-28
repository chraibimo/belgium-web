<?php
/** Reviews (French). Same rules as nl/reviews.php: empty on the live site until real reviews exist. */
if (!defined('PRIMO_APP')) exit;

$samples = [
    ['name' => 'Exemple', 'date' => 'sept. 2026', 'rating' => 5, 'quote' => 'Ceci est un avis d’exemple. Les vrais avis clients apparaîtront ici dès qu’il y en aura.', 'sample' => true],
    ['name' => 'Exemple', 'date' => 'sept. 2026', 'rating' => 5, 'quote' => 'Texte d’exemple : installation rapide, explications claires sur WhatsApp.', 'sample' => true],
    ['name' => 'Exemple', 'date' => 'sept. 2026', 'rating' => 4, 'quote' => 'Texte d’exemple : toutes les chaînes belges, aussi en néerlandais.', 'sample' => true],
    ['name' => 'Exemple', 'date' => 'sept. 2026', 'rating' => 5, 'quote' => 'Texte d’exemple : fonctionne sur la télé et sur le GSM.', 'sample' => true],
];
$dev = is_dev();

return [
    'title'       => 'Avis — ce que les clients disent de Mickey TV',
    'description' => 'Lisez ce que les clients pensent de Mickey TV : installation, qualité d’image, support et offre de chaînes belges.',
    'search'      => ['title' => 'Avis des clients', 'excerpt' => 'Ce que les clients disent de Mickey TV : installation, qualité d’image, support et chaînes.', 'text' => 'avis évaluation expérience clients'],
    'sample'      => 'exemple',

    'home_eyebrow' => 'De la communauté',
    'home_title'   => 'Ce que les spectateurs <em>disent</em>',
    'home_lede'    => 'De vrais avis de clients, dans leurs propres mots. Lisez-les tous, même les critiques, avant de décider.',

    'wall'     => $dev ? $samples : [],
    'featured' => $dev ? array_slice($samples, 0, 3) : [],

    'hero' => [
        'badge' => 'Avis',
        'title' => 'Plus qu’un <em>avis</em>',
        'lede'  => 'Mickey TV est nouveau en Belgique. Chaque avis sur cette page vient d’un vrai client et est repris mot pour mot — les bons et les moins bons. Rien à lire encore ? C’est que nous démarrons tout juste.',
    ],
    'empty' => [
        'title' => 'Votre avis ira ici',
        'text'  => 'Nous ne publions que de vrais avis de clients. Vous êtes client ? Envoyez-nous votre expérience sur WhatsApp ou par e-mail et elle apparaîtra ici, mot pour mot.',
    ],
    'tabs' => [
        ['id' => 'stable',  'label' => 'Flux stables',       'chips' => ['Serveurs anti-freeze', '99,9 % de disponibilité', 'HD & 4K'], 'text' => 'Nos serveurs sont conçus pour les pics : un choc de Pro League, la finale de la Ligue des champions, l’arrivée du Tour. L’image doit tenir.'],
        ['id' => 'sport',   'label' => 'Sport en direct',    'chips' => ['DAZN & Eleven', 'VOO Sport', 'Événements PPV'], 'text' => 'Toutes les chaînes sport belges, plus les grandes internationales — Sky Sports, beIN, Canal+ — pour ne rater aucun match.'],
        ['id' => 'vod',     'label' => 'Films & séries',     'chips' => ['120 000+ titres', 'Nouveautés chaque semaine', 'Sous-titres FR & NL'], 'text' => 'Les derniers films et des saisons complètes, à la demande, renouvelés chaque semaine.'],
        ['id' => 'support', 'label' => 'Support 7j/7',       'chips' => ['WhatsApp', 'Français & néerlandais', '9h–23h'], 'text' => 'Une personne répond, dans votre langue. Avant l’achat, pendant l’installation et après.'],
    ],
    'story' => [
        'title' => 'Pourquoi <em>Mickey TV</em> existe',
        'chapters' => [
            ['Ça a commencé par une frustration', 'Un décodeur par télé, un abonnement par chaîne, et toujours pas le match qu’on voulait voir. La facture télé grossissait chaque année, pas le choix.'],
            ['On a tout testé nous-mêmes', 'Pendant des mois, on a essayé chaque fournisseur trouvable : stabilité, image, support, prix. La plupart ont lâché dès le premier grand match belge.'],
            ['Un service, deux langues', 'La Belgique regarde en français et en néerlandais, souvent dans la même famille. C’est pourquoi toute l’offre belge est dans les deux langues, dans un seul abonnement, avec un support dans les deux langues.'],
            ['Où nous en sommes', 'Mickey TV est jeune. Nous grandissons grâce à ce que les clients nous disent — et nous publions ce qu’ils disent, même quand ce n’est pas toujours élogieux.'],
        ],
    ],
    'tp' => [
        'title' => 'Ce que les gens disent là où nous ne pouvons rien modifier',
        'text'  => 'Dès que Mickey TV aura un profil sur une plateforme d’avis indépendante, nous le lierons ici — avec la note, le nombre d’avis et toutes les étoiles, même les basses.',
        'button'=> 'Lire les avis',
    ],
];
