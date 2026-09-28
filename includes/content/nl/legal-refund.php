<?php
/** Refund policy (Dutch). Belgian consumer law + the brand's own money-back promise. Lawyer review before launch. */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
$b = $d['brand'];
$r = $d['pricing']['refund_days'];
return [
    'title'       => 'Terugbetalingsbeleid',
    'description' => 'De geld-terug-garantie van Mickey TV: ' . $r['essential'] . ' dagen op Essential, ' . $r['vip'] . ' dagen op Elite VIP. Hoe je een terugbetaling aanvraagt en wat niet in aanmerking komt.',
    'badge'       => 'Juridisch',
    'h1'          => 'Terugbetalings­beleid',
    'lede'        => 'Tevreden of je geld terug: ' . $r['essential'] . ' dagen op Essential, ' . $r['vip'] . ' dagen op Elite VIP. Zo werkt het.',
    'meta'        => 'Van kracht sinds 15 september 2026',
    'toc'         => 'Inhoud',
    'intro'       => 'Dit beleid maakt deel uit van de <a href="' . url('terms') . '">algemene voorwaarden</a> van <b>' . e($b['legal']) . '</b> en geldt voor elke aankoop op ' . e($b['domain']) . '.',
    'sections' => [
        ['garantie', 'Onze geld-terug-garantie', '<p>Bovenop je wettelijke rechten geven we een eigen garantie: je kan je abonnement laten terugbetalen, zonder reden op te geven, binnen <b>' . $r['essential'] . ' kalenderdagen</b> na activatie voor <b>Essential</b> en binnen <b>' . $r['vip'] . ' kalenderdagen</b> voor <b>Elite VIP</b>. De activatie is het moment waarop we je logingegevens versturen.</p>'],
        ['herroeping', 'Je wettelijk herroepingsrecht', '<p>Als consument heb je bij een aankoop op afstand een wettelijk herroepingsrecht van 14 dagen (Wetboek van economisch recht, boek VI). Omdat ons abonnement een digitale dienst is die meteen na betaling start, vragen we bij het bestellen je uitdrukkelijke toestemming om de dienst onmiddellijk te leveren; je erkent daarbij dat je je herroepingsrecht verliest zodra de dienst volledig is uitgevoerd. Onze eigen garantie hierboven is ruimer dan die wettelijke regel en geldt altijd, ook na die toestemming.</p>'],
        ['voorwaarden', 'Wat in aanmerking komt', '<ul><li>Een terugbetaling aangevraagd binnen de garantieperiode van je formule.</li><li>Een dienst die meer dan 48 uur na elkaar niet werkt door een fout aan onze kant, ook buiten de garantieperiode: dan verlengen we je abonnement of betalen we het niet-gebruikte deel terug, naar keuze.</li><li>Een dubbele betaling of een verkeerd aangerekend bedrag: altijd, volledig.</li></ul>'],
        ['uitsluitingen', 'Wat niet in aanmerking komt', '<ul><li>Aanvragen na het verstrijken van de garantieperiode (behalve bij een storing aan onze kant, zie hierboven).</li><li>Problemen die aan jouw kant liggen en die we je hebben helpen oplossen of aangeboden hebben op te lossen: een te trage of onstabiele internetverbinding, een toestel dat de app niet ondersteunt, een provider die streaming afremt zonder VPN.</li><li>Gedeeltelijk gebruik na de garantieperiode (bv. stoppen na 4 van de 6 maanden).</li><li>Een abonnement dat wegens misbruik of schending van de voorwaarden is stopgezet (bv. het delen of doorverkopen van je login).</li><li>Klachten over de afwezigheid van zenders die niet in onze zenderlijst staan.</li></ul>'],
        ['aanvragen', 'Hoe vraag je een terugbetaling aan?', '<ol><li>Stuur een bericht naar <a href="mailto:' . e($b['email']) . '">' . e($b['email']) . '</a> of via WhatsApp naar ' . e($b['phone']) . ' met je bestelnummer en het e-mailadres van je bestelling.</li><li>Zeg gerust waarom, dat helpt ons — maar het hoeft niet.</li><li>We bevestigen je aanvraag binnen 2 werkdagen.</li></ol>'],
        ['verwerking', 'Verwerking', '<p>Een goedgekeurde terugbetaling gebeurt via dezelfde betaalmethode als je aankoop, binnen 14 dagen na de bevestiging. Bij Bancontact of kaart kan je bank er nog enkele werkdagen over doen om het bedrag te tonen. Bij betaling in cryptomunten betalen we de tegenwaarde in euro terug op een rekening naar keuze, of in dezelfde munt op het adres waarvan betaald werd. Je toegang stopt op het moment van terugbetaling.</p>'],
        ['wijzigingen', 'Wijzigingen', '<p>We kunnen dit beleid aanpassen; de versie die gold op het moment van je aankoop is de versie die voor jou telt.</p>'],
        ['contact', 'Contact', '<p>' . e($b['legal']) . ' — <a href="mailto:' . e($b['email']) . '">' . e($b['email']) . '</a> — ' . e($b['phone']) . ' — ' . e($b['hours_nl']) . '</p>'],
    ],
];
