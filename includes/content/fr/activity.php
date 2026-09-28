<?php
/**
 * Activity toasts, bottom-left (French pool). Same structure as nl/activity.php.
 *
 * IMPORTANT — these are GENERATED, not real orders (see nl/activity.php for the
 * legal note). They only run while FOMO_SYNTHETIC is true in app/config.php.
 *
 * Cities are the same Belgian towns in their French spelling, so the French
 * site never shows "Bergen" or "Doornik".
 */
if (!defined('PRIMO_APP')) exit;

return [
    'names' => [
        // Wallonie / Bruxelles
        'Julien', 'Maxime', 'Antoine', 'Nicolas', 'Quentin', 'Florian', 'Damien', 'Thibaut', 'Loïc', 'Cédric',
        'Olivier', 'Benoît', 'Pierre', 'Ludovic', 'Sébastien', 'Guillaume', 'Adrien', 'Jérôme', 'Xavier', 'Romain',
        'Gaëtan', 'Corentin', 'Simon', 'Arnaud', 'Vincent', 'Laurent', 'Fabian', 'Grégory', 'Michaël', 'Étienne',
        'Camille', 'Céline', 'Manon', 'Aurélie', 'Émilie', 'Sophie', 'Élodie', 'Justine', 'Noémie', 'Delphine',
        'Laurence', 'Valérie', 'Charlotte', 'Marie', 'Chloé', 'Sarah', 'Julie', 'Océane', 'Amandine', 'Pauline',
        // Flandre
        'Jonas', 'Wout', 'Lars', 'Seppe', 'Bram', 'Thomas', 'Jasper', 'Milan', 'Robbe', 'Arne',
        'Stijn', 'Ruben', 'Niels', 'Koen', 'Bart', 'Dries', 'Ward', 'Senne', 'Kobe', 'Matthias',
        'Femke', 'Lotte', 'Lore', 'Margot', 'Elise', 'Fien', 'Hanne', 'Amber', 'Nele', 'Eva',
        // Voisins européens
        'Lukas', 'Matteo', 'Giulia', 'Elena', 'Sofia', 'Diego', 'Carlos', 'Lucía', 'Erik', 'Astrid',
        'Ingrid', 'Nils', 'Freya', 'Piotr', 'Katarzyna', 'Tomasz', 'Magda', 'Niamh', 'Declan', 'Fiona',
        'Klaus', 'Anneke', 'Sven', 'Dimitri', 'Eleni', 'Yannis', 'Petra', 'Marek', 'Nuno', 'Inês',
    ],
    // Initiale du nom de famille seulement — réaliste, et ne désigne personne.
    'initials' => ['D.', 'V.', 'P.', 'M.', 'J.', 'C.', 'W.', 'B.', 'L.', 'S.', 'G.', 'H.', 'R.', 'T.', 'K.', 'N.', 'A.', 'F.'],
    'cities' => [
        'Liège', 'Namur', 'Charleroi', 'Mons', 'Tournai', 'Wavre', 'Arlon', 'Verviers', 'La Louvière',
        'Seraing', 'Mouscron', 'Huy', 'Dinant', 'Nivelles', 'Eupen', 'Braine-l’Alleud', 'Soignies', 'Ath',
        'Waterloo', 'Marche-en-Famenne', 'Bastogne', 'Ottignies', 'Chimay', 'Spa', 'Malmedy',
        'Bruxelles', 'Anderlecht', 'Schaerbeek', 'Jette', 'Uccle', 'Ixelles', 'Woluwe-Saint-Lambert',
        'Anvers', 'Gand', 'Bruges', 'Louvain', 'Malines', 'Hasselt', 'Genk', 'Courtrai', 'Ostende',
        'Saint-Nicolas', 'Alost', 'Roulers', 'Turnhout', 'Termonde', 'Vilvorde', 'Hal', 'Tirlemont', 'Ypres', 'Lierre',
    ],
    'events' => [
        ['action' => 'a pris', 'plan' => 'Elite VIP — 12 mois', 'w' => 3],
        ['action' => 'a pris', 'plan' => 'Elite VIP — 6 mois',  'w' => 4],
        ['action' => 'a pris', 'plan' => 'Elite VIP — 3 mois',  'w' => 4],
        ['action' => 'a pris', 'plan' => 'Essential — 12 mois', 'w' => 3],
        ['action' => 'a pris', 'plan' => 'Essential — 6 mois',  'w' => 5],
        ['action' => 'a pris', 'plan' => 'Essential — 3 mois',  'w' => 5],
        ['action' => 'a prolongé', 'plan' => 'Elite VIP — 12 mois', 'w' => 2],
        ['action' => 'a prolongé', 'plan' => 'Essential — 6 mois',  'w' => 2],
        ['action' => 'est passé à', 'plan' => 'Elite VIP', 'w' => 2],
        ['action' => 'a lancé le test gratuit', 'plan' => 'Elite VIP', 'w' => 2],
    ],
];
