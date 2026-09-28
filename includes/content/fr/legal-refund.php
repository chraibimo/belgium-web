<?php
/** Refund policy (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
$b = $d['brand'];
$r = $d['pricing']['refund_days'];
return [
    'title'       => 'Politique de remboursement',
    'description' => 'La garantie satisfait ou remboursé de Mickey TV : ' . $r['essential'] . ' jours sur Essential, ' . $r['vip'] . ' jours sur Elite VIP. Comment demander un remboursement et ce qui n’est pas couvert.',
    'badge'       => 'Juridique',
    'h1'          => 'Politique de remboursement',
    'lede'        => 'Satisfait ou remboursé : ' . $r['essential'] . ' jours sur Essential, ' . $r['vip'] . ' jours sur Elite VIP. Voici comment ça marche.',
    'meta'        => 'En vigueur depuis le 15 septembre 2026',
    'toc'         => 'Sommaire',
    'intro'       => 'Cette politique fait partie des <a href="' . url('terms') . '">conditions générales</a> de <b>' . e($b['legal']) . '</b> et s’applique à tout achat sur ' . e($b['domain']) . '.',
    'sections' => [
        ['garantie', 'Notre garantie satisfait ou remboursé', '<p>En plus de vos droits légaux, nous offrons notre propre garantie : vous pouvez vous faire rembourser votre abonnement, sans donner de raison, dans les <b>' . $r['essential'] . ' jours calendrier</b> suivant l’activation pour <b>Essential</b> et dans les <b>' . $r['vip'] . ' jours calendrier</b> pour <b>Elite VIP</b>. L’activation est le moment où nous envoyons vos accès.</p>'],
        ['retractation', 'Votre droit légal de rétractation', '<p>En tant que consommateur, vous disposez d’un droit de rétractation légal de 14 jours pour un achat à distance (Code de droit économique, livre VI). Comme notre abonnement est un service numérique qui démarre dès le paiement, nous vous demandons à la commande votre accord exprès pour une fourniture immédiate ; vous reconnaissez alors perdre votre droit de rétractation une fois le service entièrement exécuté. Notre garantie ci-dessus est plus large que cette règle légale et s’applique toujours, même après cet accord.</p>'],
        ['conditions', 'Ce qui est couvert', '<ul><li>Un remboursement demandé pendant la période de garantie de votre formule.</li><li>Un service qui ne fonctionne pas plus de 48 heures d’affilée par une faute de notre côté, même hors garantie : nous prolongeons alors votre abonnement ou remboursons la partie non utilisée, à votre choix.</li><li>Un double paiement ou un montant facturé par erreur : toujours, intégralement.</li></ul>'],
        ['exclusions', 'Ce qui n’est pas couvert', '<ul><li>Les demandes après la période de garantie (sauf panne de notre côté, voir ci-dessus).</li><li>Les problèmes de votre côté que nous avons aidé ou proposé de résoudre : connexion trop lente ou instable, appareil ne supportant pas l’application, fournisseur d’accès freinant le streaming sans VPN.</li><li>L’usage partiel après la période de garantie (par ex. arrêter après 4 mois sur 6).</li><li>Un abonnement suspendu pour abus ou violation des conditions (par ex. partage ou revente de vos accès).</li><li>Les réclamations concernant l’absence de chaînes qui ne figurent pas dans notre liste.</li></ul>'],
        ['demande', 'Comment demander un remboursement ?', '<ol><li>Envoyez un message à <a href="mailto:' . e($b['email']) . '">' . e($b['email']) . '</a> ou sur WhatsApp au ' . e($b['phone']) . ' avec votre numéro de commande et l’e-mail de votre commande.</li><li>Dites-nous pourquoi si vous voulez, ça nous aide — mais ce n’est pas obligatoire.</li><li>Nous confirmons votre demande sous 2 jours ouvrables.</li></ol>'],
        ['traitement', 'Traitement', '<p>Un remboursement approuvé est effectué par le même moyen de paiement que votre achat, dans les 14 jours suivant la confirmation. Avec Bancontact ou une carte, votre banque peut mettre quelques jours ouvrables à afficher le montant. En cas de paiement en cryptomonnaies, nous remboursons la contre-valeur en euros sur un compte de votre choix, ou dans la même monnaie à l’adresse d’origine. Votre accès s’arrête au moment du remboursement.</p>'],
        ['modifications', 'Modifications', '<p>Nous pouvons adapter cette politique ; la version en vigueur au moment de votre achat est celle qui vous est applicable.</p>'],
        ['contact', 'Contact', '<p>' . e($b['legal']) . ' — <a href="mailto:' . e($b['email']) . '">' . e($b['email']) . '</a> — ' . e($b['phone']) . ' — ' . e($b['hours_fr']) . '</p>'],
    ],
];
