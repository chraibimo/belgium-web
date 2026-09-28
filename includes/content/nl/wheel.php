<?php
/**
 * The promo wheel (Dutch). Every visitor who spins gets a code; the codes must
 * exist in the checkout (see 'wheel' in includes/data.php). Weights drive the
 * odds; "again" wedges give a second spin.
 */
if (!defined('PRIMO_APP')) exit;

return [
    'kicker'   => 'Eén draai · één code',
    'title'    => 'Draai voor gratis maanden of 15% korting',
    'sub'      => 'Draai aan het rad — tot <b>6 maanden gratis</b>. Je code verschijnt meteen en blijft 5 minuten geldig.',
    'email_label' => 'Waar mogen we je code naartoe sturen?',
    'optional' => 'optioneel',
    'email_placeholder' => 'jij@e-mail.be',
    'consent'  => 'Laat het leeg als je wil — je code verschijnt sowieso op het scherm. Vul het in en we mailen een kopie. Geen spam.',
    'spin'     => 'Draai voor mijn korting',
    'decline'  => 'Nee bedankt, ik betaal de volle prijs',
    'rules'    => 'Promotiespel — elke draai eindigt met een prijs; de kansen per vak staan in de spelregels. Eén code per klant, te gebruiken bij het afrekenen.',
    'won'      => 'Je hebt gewonnen',
    'copy'     => 'Code kopiëren',
    'holds'    => 'Nog geldig',
    'claim'    => 'Gebruik mijn code',
    'claim_short' => 'Gebruik',
    'apply'    => 'Geef de code in bij het afrekenen. Eén code per klant.',
    'expires'  => 'Vervalt over',
    'again_kicker' => 'Bijna',
    'again_title'  => 'Nog een keer!',
    'again_sub'    => 'Dat vak geeft je een extra draai aan het rad.',
    'again_button' => 'Draai opnieuw',
    // The little pill at the top that appears after the wheel is closed unspun.
    'reopen'     => 'Je draai wacht nog',
    'reopen_sub' => 'Tot 6 maanden gratis',
    'reopen_cta' => 'Open het rad',
    'reopen_hide'=> 'Herinnering verbergen',
    'slices' => [
        ['label' => 'NOG EENS', 'sub' => 'extra draai',  'weight' => 26, 'code' => '',          'title' => '',                 'desc' => ''],
        ['label' => '15% KORTING', 'sub' => 'elke formule', 'weight' => 30, 'code' => 'DEAL15',  'title' => '15% korting',      'desc' => '15% korting op elke Mickey TV-formule.'],
        ['label' => '+1 MAAND', 'sub' => 'elke formule',  'weight' => 24, 'code' => 'MTV1MFREE', 'title' => '1 maand gratis',  'desc' => 'Eén extra maand gratis bovenop elk abonnement dat je neemt.'],
        ['label' => '+3 MAANDEN', 'sub' => 'bij 6 maanden', 'weight' => 14, 'code' => 'MTV3MDEAL', 'title' => '3 maanden gratis', 'desc' => 'Drie maanden gratis bovenop het abonnement van 6 maanden.'],
        ['label' => '+6 MAANDEN', 'sub' => 'bij 12 maanden', 'weight' => 6, 'code' => 'MTV6MDEAL', 'title' => '6 maanden gratis', 'desc' => 'Zes maanden gratis bovenop het abonnement van 12 maanden.'],
    ],
];
