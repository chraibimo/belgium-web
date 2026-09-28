<?php
/**
 * "Straks live / Bientôt en direct" on the channel list page: the big matches
 * and races of the coming weeks, entered by hand.
 *
 * - start: Belgian local time (Europe/Brussels), 'YYYY-MM-DD HH:MM'.
 * - minutes: how long it runs; the card shows "live" during that time and
 *   disappears by itself afterwards. The strip hides when nothing is left.
 * - sport: football | cycling | racing | tennis | other (icon + TV scene).
 * - title / short / competition: per language; short is the label on the TV screen.
 * - search (optional): what "Zoek de zender" types into the channel search -
 *   only fill it in when you know which channel carries the event.
 * - source: where the date and time were checked. Only list real fixtures
 *   confirmed by an official source (README "Notes on honesty"); no logos, no
 *   crests, names only.
 *
 * Checked 2026-09-16. Add the Pro League once its kick-off times are fixed.
 */
if (!defined('PRIMO_APP')) exit;

return [
    [
        'start' => '2026-09-20 15:00', 'minutes' => 140, 'sport' => 'cycling',
        'title' => ['nl' => 'WK tijdrit elitevrouwen', 'fr' => 'Mondiaux : chrono élite dames'],
        'short' => ['nl' => 'WK tijdrit', 'fr' => 'Mondiaux chrono'],
        'competition' => ['nl' => 'WK wielrennen · Montréal', 'fr' => 'Mondiaux de cyclisme · Montréal'],
        'source' => 'https://en.wikipedia.org/wiki/2026_UCI_Road_World_Championships (09:00 EDT)',
    ],
    [
        'start' => '2026-09-20 18:45', 'minutes' => 150, 'sport' => 'cycling',
        'title' => ['nl' => 'WK tijdrit elitemannen', 'fr' => 'Mondiaux : chrono élite messieurs'],
        'short' => ['nl' => 'WK tijdrit', 'fr' => 'Mondiaux chrono'],
        'competition' => ['nl' => 'WK wielrennen · Montréal', 'fr' => 'Mondiaux de cyclisme · Montréal'],
        'source' => 'https://en.wikipedia.org/wiki/2026_UCI_Road_World_Championships (12:45 EDT)',
    ],
    [
        'start' => '2026-09-25 20:45', 'minutes' => 110, 'sport' => 'football',
        'title' => ['nl' => 'Italië – België', 'fr' => 'Italie – Belgique'],
        'short' => ['nl' => 'ITA – BEL', 'fr' => 'ITA – BEL'],
        'competition' => ['nl' => 'Nations League · Rome', 'fr' => 'Ligue des nations · Rome'],
        'source' => 'https://en.wikipedia.org/wiki/2026%E2%80%9327_UEFA_Nations_League_A + https://www.goal.com/en-us/news/italy-belgium-tickets/bltb16c79a86d22b714',
    ],
    [
        'start' => '2026-09-26 13:00', 'minutes' => 120, 'sport' => 'racing',
        'title' => ['nl' => 'Grote Prijs van Azerbeidzjan', 'fr' => 'Grand Prix d’Azerbaïdjan'],
        'short' => ['nl' => 'GP Bakoe', 'fr' => 'GP Bakou'],
        'competition' => ['nl' => 'Formule 1 · Bakoe', 'fr' => 'Formule 1 · Bakou'],
        'source' => 'https://www.formula1.com/en/racing/2026/azerbaijan (11:00 UTC) + https://f1calendar.com/',
    ],
    [
        'start' => '2026-09-26 15:00', 'minutes' => 310, 'sport' => 'cycling',
        'title' => ['nl' => 'WK wegrit elitevrouwen', 'fr' => 'Mondiaux : course en ligne élite dames'],
        'short' => ['nl' => 'WK wegrit', 'fr' => 'Mondiaux route'],
        'competition' => ['nl' => 'WK wielrennen · Montréal', 'fr' => 'Mondiaux de cyclisme · Montréal'],
        'source' => 'https://en.wikipedia.org/wiki/2026_UCI_Road_World_Championships (09:00 EDT)',
    ],
    [
        'start' => '2026-09-27 15:00', 'minutes' => 400, 'sport' => 'cycling',
        'title' => ['nl' => 'WK wegrit elitemannen', 'fr' => 'Mondiaux : course en ligne élite messieurs'],
        'short' => ['nl' => 'WK wegrit', 'fr' => 'Mondiaux route'],
        'competition' => ['nl' => 'WK wielrennen · Montréal', 'fr' => 'Mondiaux de cyclisme · Montréal'],
        'source' => 'https://en.wikipedia.org/wiki/2026_UCI_Road_World_Championships (09:00 EDT)',
    ],
    [
        'start' => '2026-09-28 20:45', 'minutes' => 110, 'sport' => 'football',
        'title' => ['nl' => 'België – Frankrijk', 'fr' => 'Belgique – France'],
        'short' => ['nl' => 'BEL – FRA', 'fr' => 'BEL – FRA'],
        'competition' => ['nl' => 'Nations League · Brussel', 'fr' => 'Ligue des nations · Bruxelles'],
        'source' => 'https://www.brussels.be/football-belgium-france',
    ],
    [
        'start' => '2026-10-02 20:45', 'minutes' => 110, 'sport' => 'football',
        'title' => ['nl' => 'België – Turkije', 'fr' => 'Belgique – Turquie'],
        'short' => ['nl' => 'BEL – TUR', 'fr' => 'BEL – TUR'],
        'competition' => ['nl' => 'Nations League · Luik', 'fr' => 'Ligue des nations · Liège'],
        'source' => 'https://www.goal.com/en-om/news/belgium-turkiye-tickets/bltc094b1ca1604208d + Wikipedia Nations League A',
    ],
    [
        'start' => '2026-10-11 14:00', 'minutes' => 120, 'sport' => 'racing',
        'title' => ['nl' => 'Grote Prijs van Singapore', 'fr' => 'Grand Prix de Singapour'],
        'short' => ['nl' => 'GP Singapore', 'fr' => 'GP Singapour'],
        'competition' => ['nl' => 'Formule 1 · Singapore', 'fr' => 'Formule 1 · Singapour'],
        'source' => 'https://f1calendar.com/ (13:00 Europe/London)',
    ],
    [
        'start' => '2026-10-13 21:00', 'minutes' => 110, 'sport' => 'football',
        'title' => ['nl' => 'Inter – Club Brugge', 'fr' => 'Inter – Club Bruges'],
        'short' => ['nl' => 'INT – BRU', 'fr' => 'INT – BRU'],
        'competition' => ['nl' => 'Champions League · Milaan', 'fr' => 'Ligue des champions · Milan'],
        'source' => 'https://www.uefa.com/uefachampionsleague/news/02a8-2174c9e9019d-f909a77bd77a-1000--2026-27-champions-league-all-the-league-phase-fixtures/',
    ],
];
