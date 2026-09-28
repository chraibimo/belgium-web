<?php
/** Contact page copy (Dutch). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
return [
    'title'       => 'Contact — praat met een mens, niet met een robot',
    'description' => 'Contacteer Mickey TV via WhatsApp, e-mail of het formulier. Een echt persoon antwoordt, in het Nederlands of het Frans, meestal binnen een paar uur.',
    'search'      => ['title' => 'Contact', 'excerpt' => 'Praat met een mens via WhatsApp, e-mail of het formulier — in het Nederlands of het Frans.', 'text' => 'contact support hulp whatsapp e-mail telefoon vraag'],
    'hero' => [
        'badge' => 'Contact',
        'title' => 'Praat met een mens.<br>Niet met een robot.',
        'lede'  => 'Denk je aan een abonnement, zit je vast bij een zender, of ben je al klant en klopt er iets niet? Schrijf ons hier en een echt persoon pikt het op. Geen tickets, geen wachtrijnummers.',
        'reply' => 'Meestal beantwoord binnen een paar uur',
    ],
    'ways' => [
        'title' => 'Drie manieren, <em>kies zelf</em>',
        'items' => [
            ['icon' => 'whatsapp', 'title' => 'WhatsApp', 'text' => 'De snelste lijn. Stuur een screenshot of een schermopname en we lossen het meestal meteen op.', 'link' => $d['brand']['whatsapp'], 'label' => $d['brand']['phone']],
            ['icon' => 'mail', 'title' => 'E-mail', 'text' => 'Het best voor alles met wat meer detail — facturen, een lijst zenders, een langere uitleg.', 'link' => 'mailto:' . $d['brand']['email'], 'label' => $d['brand']['email']],
            ['icon' => 'form', 'title' => 'Dit formulier', 'text' => 'Geen mailapp nodig. Je bericht komt rechtstreeks in onze inbox, met je gegevens erbij.', 'link' => '#form', 'label' => 'Schrijf ons een bericht'],
        ],
    ],
    'form' => [
        'title'   => 'Schrijf ons een bericht',
        'topics'  => ['Nieuw abonnement', 'Gratis proef', 'Technische hulp', 'Zenders & inhoud', 'Facturatie', 'Iets anders'],
        'name'    => 'Je naam',
        'email'   => 'E-mail',
        'phone'   => 'WhatsApp-nummer',
        'phone_hint' => 'optioneel — voor een sneller antwoord',
        'subject' => 'Onderwerp',
        'message' => 'Je bericht',
        'send'    => 'Verstuur',
        'privacy' => 'We gebruiken je gegevens alleen om je te antwoorden. Zie het privacybeleid.',
    ],
    'after' => [
        'title' => 'Wat er daarna gebeurt',
        'items' => ['Je bericht komt meteen in onze inbox, met een kopie in ons logboek zodat er nooit iets verloren gaat.', 'We antwoorden in de volgorde waarin berichten binnenkomen, meestal binnen een paar uur, elke dag van 9u tot 23u.', 'Al klant? Vermeld je e-mailadres van de bestelling, dan vinden we je meteen terug.'],
    ],
    'hours' => [
        'title' => 'Echte uren. <em>Echte mensen.</em>',
        'items' => [['2u', 'Gemiddelde antwoordtijd', 'Via WhatsApp vaak sneller.'], ['7/7', 'Open', 'Elke dag van 9u tot 23u, Belgische tijd. Op feestdagen een kortere dag.'], ['NL & FR', 'Twee talen', 'Schrijf in de taal die je wil; we antwoorden in dezelfde taal.']],
    ],
    'faq' => [
        ['q' => 'Hoe snel antwoordt iemand?', 'a' => 'Meestal binnen een paar uur, elke dag van 9u tot 23u. Via WhatsApp vaak sneller.'],
        ['q' => 'Iets speelt niet af. Wat stuur ik jullie best?', 'a' => 'Een screenshot of een kort filmpje van het scherm, de naam van de zender en het toestel waarop je kijkt. Daarmee vinden we het probleem meestal in één keer.'],
        ['q' => 'Ik ben al klant — hebben jullie mijn gegevens nodig?', 'a' => 'Alleen het e-mailadres of het WhatsApp-nummer van je bestelling. Stuur nooit je wachtwoord in een bericht.'],
        ['q' => 'Een zender die ik wil, staat niet in de lijst.', 'a' => 'Zoek eerst in de <a href="' . url('channels') . '">zendergids</a>. Staat hij er niet? Vraag het ons — als hij bestaat, kunnen we hem meestal toevoegen.'],
        ['q' => 'Wat gebeurt er met wat ik hier schrijf?', 'a' => 'Het wordt gemaild naar ons supportteam en bewaard in ons logboek om je te helpen. We delen het met niemand en gebruiken het niet voor reclame. Zie het <a href="' . url('privacy') . '">privacybeleid</a>.'],
    ],
];
