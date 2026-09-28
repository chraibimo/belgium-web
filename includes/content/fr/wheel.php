<?php
/** The promo wheel (French). Same codes as nl/wheel.php. */
if (!defined('PRIMO_APP')) exit;

return [
    'kicker'   => 'Un tour · un code',
    'title'    => 'Tournez pour des mois offerts ou 15 % de remise',
    'sub'      => 'Faites tourner la roue — jusqu’à <b>6 mois offerts</b>. Votre code apparaît tout de suite et reste valable 5 minutes.',
    'email_label' => 'Où pouvons-nous envoyer votre code ?',
    'optional' => 'facultatif',
    'email_placeholder' => 'vous@e-mail.be',
    'consent'  => 'Laissez vide si vous préférez — votre code s’affiche de toute façon à l’écran. Indiquez-le et nous vous envoyons une copie. Pas de spam.',
    'spin'     => 'Tourner pour ma remise',
    'decline'  => 'Non merci, je paie le prix plein',
    'rules'    => 'Jeu promotionnel — chaque tour donne un prix ; les chances par case figurent dans le règlement. Un code par client, à utiliser au paiement.',
    'won'      => 'Vous avez gagné',
    'copy'     => 'Copier le code',
    'holds'    => 'Encore valable',
    'claim'    => 'Utiliser mon code',
    'claim_short' => 'Utiliser',
    'apply'    => 'Saisissez le code au paiement. Un code par client.',
    'expires'  => 'Expire dans',
    'again_kicker' => 'Presque',
    'again_title'  => 'Encore un tour !',
    'again_sub'    => 'Cette case vous offre un tour supplémentaire.',
    // La petite pastille en haut, affichée quand la roue est fermée sans tour.
    'reopen'     => 'Votre tour vous attend',
    'reopen_sub' => 'Jusqu’à 6 mois offerts',
    'reopen_cta' => 'Ouvrir la roue',
    'reopen_hide'=> 'Masquer le rappel',
    'again_button' => 'Retourner',
    'slices' => [
        ['label' => 'ENCORE', 'sub' => 'tour en plus', 'weight' => 26, 'code' => '',          'title' => '',                 'desc' => ''],
        ['label' => '15 % REMISE', 'sub' => 'toute formule', 'weight' => 30, 'code' => 'DEAL15',  'title' => '15 % de remise', 'desc' => '15 % de remise sur toute formule Mickey TV.'],
        ['label' => '+1 MOIS', 'sub' => 'toute formule',  'weight' => 24, 'code' => 'MTV1MFREE', 'title' => '1 mois offert', 'desc' => 'Un mois supplémentaire offert sur l’abonnement que vous prenez.'],
        ['label' => '+3 MOIS', 'sub' => 'sur 6 mois', 'weight' => 14, 'code' => 'MTV3MDEAL', 'title' => '3 mois offerts', 'desc' => 'Trois mois offerts en plus de l’abonnement de 6 mois.'],
        ['label' => '+6 MOIS', 'sub' => 'sur 12 mois', 'weight' => 6, 'code' => 'MTV6MDEAL', 'title' => '6 mois offerts', 'desc' => 'Six mois offerts en plus de l’abonnement de 12 mois.'],
    ],
];
