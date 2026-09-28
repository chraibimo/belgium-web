<?php
if (!defined('PRIMO_APP')) exit;
$d = site_data();
return [
    'title'       => 'Bedankt! Je bericht is verstuurd',
    'description' => 'Bedankt voor je bericht of bestelling bij Mickey TV. Dit gebeurt er nu.',
    'badge'       => 'Bedankt',
    'title_html'  => 'Bedankt!<br>We zijn ermee bezig',
    'lede'        => 'Heb je besteld? Dan verwerken we je betaling en sturen we je login binnen enkele minuten via WhatsApp of e-mail. Heb je ons geschreven? Dan pikt een mens je bericht zo op.',
    'chips'       => ['Snelle levering', 'Beveiligde betaling', 'Support beschikbaar'],
    'next_title'  => 'Wat er nu gebeurt',
    'next'        => [['Kijk in je mailbox', 'Je logingegevens komen per e-mail (kijk ook bij spam of promoties) of via WhatsApp.'], ['Installeer en kijk', 'Volg de stappen voor je toestel op de installatiepagina en geef je Xtream Codes-login in.'], ['Hulp nodig?', 'Stuur ons een WhatsApp met je bestelnummer en het toestel waarop je kijkt.']],
    'back'        => 'Terug naar Mickey TV',
    'tutorial'    => 'Naar de installatiegids',
];
