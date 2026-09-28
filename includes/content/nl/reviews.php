<?php
/**
 * Reviews (Dutch). Mickey TV is a new brand and has no reviews yet, so 'wall'
 * and 'featured' are EMPTY on the live site: the review sections then hide
 * themselves. The 'samples' below only render on localhost, badged as
 * examples, so the layout can be checked. Never publish an invented review.
 * When real reviews exist (e.g. from the brand's own Trustpilot profile), add
 * them to 'wall' / 'featured' verbatim and set TRUSTPILOT_URL in the config.
 */
if (!defined('PRIMO_APP')) exit;

$samples = [
    ['name' => 'Voorbeeld', 'date' => 'sep 2026', 'rating' => 5, 'quote' => 'Dit is een voorbeeldreview. Echte klantenreviews verschijnen hier zodra ze er zijn.', 'sample' => true],
    ['name' => 'Voorbeeld', 'date' => 'sep 2026', 'rating' => 5, 'quote' => 'Voorbeeldtekst: snelle installatie, duidelijke uitleg via WhatsApp.', 'sample' => true],
    ['name' => 'Voorbeeld', 'date' => 'sep 2026', 'rating' => 4, 'quote' => 'Voorbeeldtekst: alle Belgische zenders, ook in het Frans.', 'sample' => true],
    ['name' => 'Voorbeeld', 'date' => 'sep 2026', 'rating' => 5, 'quote' => 'Voorbeeldtekst: werkt op de tv én op de gsm.', 'sample' => true],
];
$dev = is_dev();

return [
    'title'       => 'Reviews — wat klanten over Mickey TV zeggen',
    'description' => 'Lees wat klanten van Mickey TV vinden: installatie, beeldkwaliteit, support en het aanbod aan Belgische zenders.',
    'search'      => ['title' => 'Reviews van klanten', 'excerpt' => 'Wat klanten over Mickey TV zeggen: installatie, beeldkwaliteit, support en zenders.', 'text' => 'review beoordeling ervaring klanten'],
    'sample'      => 'voorbeeld',

    'home_eyebrow' => 'Van de community',
    'home_title'   => 'Wat kijkers <em>zeggen</em>',
    'home_lede'    => 'Echte reviews van klanten, in hun eigen woorden. Lees ze allemaal, ook de kritische, vóór je beslist.',

    'wall'     => $dev ? $samples : [],
    'featured' => $dev ? array_slice($samples, 0, 3) : [],

    'hero' => [
        'badge' => 'Reviews',
        'title' => 'Meer dan een <em>review</em>',
        'lede'  => 'Mickey TV is nieuw in België. Elke review op deze pagina komt van een echte klant en wordt woordelijk overgenomen — de goede en de minder goede. Is er nog niets te lezen? Dan zijn we gewoon nog aan het starten.',
    ],
    'empty' => [
        'title' => 'Jouw review komt hier',
        'text'  => 'We publiceren alleen echte reviews van klanten. Ben je klant? Stuur ons je ervaring via WhatsApp of e-mail en ze verschijnt hier, woordelijk.',
    ],
    'tabs' => [
        ['id' => 'stable',  'label' => 'Stabiele streams', 'chips' => ['Anti-freeze servers', '99,9% uptime', 'HD & 4K'], 'text' => 'Onze servers zijn gebouwd voor de piekmomenten: een topper in de Pro League, de finale van de Champions League, de aankomst van de Ronde. Dan moet het beeld staan.'],
        ['id' => 'sport',   'label' => 'Live sport',       'chips' => ['DAZN & Eleven', 'Play Sports', 'PPV-evenementen'], 'text' => 'Alle Belgische sportzenders, plus de grote internationale — Sky Sports, beIN, Canal+ — voor wie geen enkele match wil missen.'],
        ['id' => 'vod',     'label' => 'Films & series',   'chips' => ['120.000+ titels', 'Wekelijks nieuw', 'NL & FR ondertitels'], 'text' => 'De nieuwste films en volledige seizoenen van series, on demand, aangevuld elke week.'],
        ['id' => 'support', 'label' => 'Support 7/7',      'chips' => ['WhatsApp', 'Nederlands & Frans', '9u–23u'], 'text' => 'Een mens antwoordt, in je eigen taal. Vóór je koopt, tijdens de installatie en daarna.'],
    ],
    'story' => [
        'title' => 'Waarom <em>Mickey TV</em> bestaat',
        'chapters' => [
            ['Het begon met frustratie', 'Een decoder per tv, een abonnement per zender, en toch nog altijd niet de match die je wou zien. De tv-rekening werd elk jaar zwaarder, de keuze niet groter.'],
            ['We testten alles zelf', 'Maandenlang probeerden we elke aanbieder die we konden vinden: stabiliteit, beeldkwaliteit, support, prijs. De meeste vielen af op de eerste avond met een Belgische topmatch.'],
            ['Eén dienst, twee talen', 'België kijkt in het Nederlands én in het Frans, vaak in hetzelfde gezin. Daarom zit het hele Belgische aanbod in beide talen in één abonnement, met support in beide talen.'],
            ['Waar we nu staan', 'Mickey TV is jong. We groeien op basis van wat klanten ons vertellen — en publiceren wat ze zeggen, ook als het niet altijd lovend is.'],
        ],
    ],
    'tp' => [
        'title' => 'Wat mensen zeggen waar wij het niet kunnen aanpassen',
        'text'  => 'Zodra Mickey TV een profiel heeft op een onafhankelijk reviewplatform, linken we het hier — met de score, het aantal reviews en alle sterren, ook de lage.',
        'button'=> 'Lees de reviews',
    ],
];
