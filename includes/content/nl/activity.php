<?php
/**
 * Activity toasts, bottom-left (Dutch pool).
 *
 * IMPORTANT — these are GENERATED, not real orders. The site owner asked for
 * them on 2026-09-16; they are shown only while FOMO_SYNTHETIC is true in
 * app/config.php. A real order feed (FOMO_FEED_URL) always wins over this pool.
 * Presenting invented purchases as real is a misleading commercial practice
 * under EU/Belgian consumer law (Book VI, Code of Economic Law) — switch
 * FOMO_SYNTHETIC off to stop them.
 *
 * Names are Belgian + European (Flemish, Walloon, and neighbouring countries),
 * cities are Belgian in their Dutch spelling. Keep both lists long enough that
 * a visitor never sees the same pair twice in a session.
 */
if (!defined('PRIMO_APP')) exit;

return [
    'names' => [
        // Vlaanderen
        'Jonas', 'Wout', 'Lars', 'Seppe', 'Bram', 'Thomas', 'Jasper', 'Milan', 'Robbe', 'Arne',
        'Stijn', 'Ruben', 'Niels', 'Vincent', 'Koen', 'Bart', 'Dries', 'Ward', 'Senne', 'Tuur',
        'Kobe', 'Matthias', 'Pieter', 'Joris', 'Glenn', 'Kevin', 'Dieter', 'Sander', 'Nathan', 'Gilles',
        'Femke', 'Lotte', 'Lore', 'Margot', 'Elise', 'Fien', 'Hanne', 'Marie', 'Amber', 'Sarah',
        'Julie', 'Nele', 'Eva', 'Charlotte', 'Jana', 'Inge', 'Griet', 'Katrien', 'Veerle', 'Lien',
        // Wallonië / Brussel
        'Julien', 'Maxime', 'Antoine', 'Nicolas', 'Quentin', 'Florian', 'Damien', 'Thibaut', 'Loïc', 'Cédric',
        'Olivier', 'Benoît', 'Pierre', 'Ludovic', 'Sébastien', 'Guillaume', 'Adrien', 'Jérôme', 'Xavier', 'Romain',
        'Camille', 'Céline', 'Manon', 'Aurélie', 'Émilie', 'Sophie', 'Élodie', 'Justine', 'Noémie', 'Delphine',
        // Europese buren
        'Lukas', 'Matteo', 'Giulia', 'Elena', 'Sofia', 'Diego', 'Carlos', 'Lucía', 'Erik', 'Astrid',
        'Ingrid', 'Nils', 'Freya', 'Piotr', 'Katarzyna', 'Tomasz', 'Magda', 'Niamh', 'Declan', 'Fiona',
        'Klaus', 'Anneke', 'Sven', 'Dimitri', 'Eleni', 'Yannis', 'Petra', 'Marek', 'Nuno', 'Inês',
    ],
    // Surname initial only — realistic, and nothing that points at a real person.
    'initials' => ['D.', 'V.', 'P.', 'M.', 'J.', 'C.', 'W.', 'B.', 'L.', 'S.', 'G.', 'H.', 'R.', 'T.', 'K.', 'N.', 'A.', 'F.'],
    'cities' => [
        'Antwerpen', 'Gent', 'Brugge', 'Leuven', 'Mechelen', 'Hasselt', 'Genk', 'Kortrijk', 'Oostende',
        'Sint-Niklaas', 'Aalst', 'Roeselare', 'Turnhout', 'Dendermonde', 'Beveren', 'Lokeren', 'Waregem',
        'Knokke-Heist', 'Deinze', 'Vilvoorde', 'Halle', 'Tienen', 'Ieper', 'Diest', 'Lier', 'Mol', 'Geel',
        'Brasschaat', 'Schoten', 'Maasmechelen', 'Sint-Truiden', 'Tongeren', 'Wevelgem', 'Menen', 'Harelbeke',
        'Brussel', 'Anderlecht', 'Schaarbeek', 'Jette', 'Ukkel',
        'Luik', 'Namen', 'Charleroi', 'Bergen', 'Doornik', 'Waver', 'Aarlen', 'Verviers', 'La Louvière',
        'Seraing', 'Moeskroen', 'Hoei', 'Dinant', 'Nijvel', 'Eupen',
    ],
    // What the toast says happened. Weights drive how often each one shows.
    'events' => [
        ['action' => 'nam', 'plan' => 'Elite VIP — 12 maanden', 'w' => 3],
        ['action' => 'nam', 'plan' => 'Elite VIP — 6 maanden',  'w' => 4],
        ['action' => 'nam', 'plan' => 'Elite VIP — 3 maanden',  'w' => 4],
        ['action' => 'nam', 'plan' => 'Essential — 12 maanden', 'w' => 3],
        ['action' => 'nam', 'plan' => 'Essential — 6 maanden',  'w' => 5],
        ['action' => 'nam', 'plan' => 'Essential — 3 maanden',  'w' => 5],
        ['action' => 'verlengde', 'plan' => 'Elite VIP — 12 maanden', 'w' => 2],
        ['action' => 'verlengde', 'plan' => 'Essential — 6 maanden',  'w' => 2],
        ['action' => 'ging over naar', 'plan' => 'Elite VIP', 'w' => 2],
        ['action' => 'startte de gratis test van', 'plan' => 'Elite VIP', 'w' => 2],
    ],
];
