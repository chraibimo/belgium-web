<?php
/** Referral page (Dutch). */
if (!defined('PRIMO_APP')) exit;
return [
    'title'       => 'Vertel het verder — jij en je vriend krijgen een maand gratis',
    'description' => 'Breng een vriend aan bij Mickey TV: jullie krijgen allebei een extra maand. Geen code nodig, vul het formulier in na de bestelling.',
    'search'      => ['title' => 'Vertel het verder', 'excerpt' => 'Breng een vriend aan en jullie krijgen allebei een extra maand gratis.', 'text' => 'vriend aanbrengen referral gratis maand'],
    'hero' => ['badge' => 'Vrienden aanbrengen', 'title' => 'Vertel het<br>verder', 'lede' => 'Bestel gewoon zoals altijd — er is geen code nodig. Zeg ons wie je stuurde en we voegen de beloning toe aan beide accounts zodra we je bestelling gekoppeld hebben.'],
    'perks' => [['Jij krijgt', 'Eén extra maand'], ['Je vriend krijgt', 'Ook een maand']],
    'how' => ['title' => 'Zo werkt het', 'steps' => [['Je vriend bestelt', 'Hij of zij kiest een formule en betaalt zoals iedereen. Geen code, geen korting aan de kassa.'], ['Vul dit formulier in', 'Met je eigen gegevens, het bestelnummer van je vriend en zijn of haar e-mailadres.'], ['Wij koppelen', 'Een mens kijkt de bestelling na en voegt bij allebei een maand toe. Je krijgt een bevestiging per e-mail.']], 'note' => 'Beide maanden worden toegevoegd na controle van de bestelling. Er wordt niets afgetrokken bij het afrekenen.'],
    'form' => ['title' => 'Vraag je maand aan', 'you' => 'Jouw gegevens (de nieuwe klant)', 'friend' => 'Wie heeft je aangebracht?', 'name' => 'Volledige naam', 'email' => 'E-mail van je bestelling', 'order' => 'Bestelnummer', 'order_hint' => 'bv. #BE-10492', 'ref_name' => 'Naam van je vriend', 'ref_email' => 'E-mail van je vriend', 'send' => 'Verstuur de aanvraag'],
    'vault' => ['title' => 'Na het versturen', 'items' => [['Elke aanvraag wordt door een mens nagekeken', 'We koppelen het bestelnummer aan de account van je vriend.'], ['Hoe lang duurt het?', 'Meestal binnen 24 uur.'], ['Waar komt de maand terecht?', 'Bij het lopende abonnement van jullie allebei; je ziet de nieuwe einddatum in je bevestiging.'], ['Als iets niet klopt', 'We mailen je vóór we een aanvraag weigeren.'], ['Tip', 'Kopieer de e-mailadressen uit de bestelbevestiging; een typfout is de meest voorkomende reden voor vertraging.']]],
];
