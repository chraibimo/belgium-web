<?php
/** FAQ page (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
return [
    'title'       => 'Questions fréquentes sur Mickey TV',
    'description' => 'Qu’est-ce que l’IPTV, est-ce légal, comment payer, quels appareils fonctionnent, quel débit faut-il, comment installer ? Les réponses sur une seule page.',
    'search'      => ['title' => 'Questions fréquentes', 'excerpt' => 'Formules, appareils, installation et paiement — les questions que le support reçoit le plus souvent.', 'text' => 'faq questions iptv légal payer appareils débit vpn revendeur'],
    'hero' => ['badge' => 'FAQ', 'title' => 'Questions<br>fréquentes', 'lede' => 'Formules, appareils, installation et paiement — les questions que notre support reçoit le plus. La vôtre n’y est pas ? Un message et nous répondons.'],
    'filter_placeholder' => 'Chercher dans les questions…',
    'groups' => [
        ['Le service', [
            ['q' => 'Qu’est-ce que l’IPTV ?', 'a' => 'IPTV signifie Internet Protocol Television : regarder la télé via votre connexion internet au lieu du câble ou du satellite. Pas besoin de décodeur ; une application sur votre télé, GSM, tablette ou ordinateur suffit. Mickey TV est revendeur d’un tel service et vous fournit l’identifiant et le support.'],
            ['q' => 'L’IPTV est-elle légale ?', 'a' => 'La technologie elle-même (la télé via internet) est parfaitement légale et utilisée par les grands opérateurs belges. La légalité d’une offre donnée dépend des droits des chaînes qu’elle contient et de la législation là où vous regardez. Mickey TV fournit le contenu de prestataires tiers ; en tant qu’abonné, vous êtes responsable du respect de la législation qui vous est applicable. Lisez nos <a href="' . url('terms') . '">conditions générales</a>.'],
            ['q' => 'Quelle est la différence entre Essential et Elite VIP ?', 'a' => 'Elite VIP offre le plus grand catalogue, la 4K, le guide complet, le replay 24 h, les événements PPV, les flux anti-freeze et les applications incluses, avec 15 jours satisfait ou remboursé. Essential est la formule compacte pour une seule télé et l’essentiel, avec 7 jours de garantie. Comparez-les ligne par ligne sur la <a href="' . url('pricing') . '">page des tarifs</a>.'],
            ['q' => 'Puis-je avoir un essai gratuit ?', 'a' => 'Oui. Envoyez-nous un WhatsApp avec l’appareil sur lequel vous voulez regarder et nous vous envoyons un accès d’essai de 24 heures.'],
        ]],
        ['Paiement & commande', [
            ['q' => 'Quels moyens de paiement acceptez-vous ?', 'a' => 'Bancontact, carte de crédit (Visa, Mastercard, American Express) et cryptomonnaies. Le paiement passe par une page sécurisée.'],
            ['q' => 'Comment commencer ?', 'a' => 'Choisissez une formule, finalisez le paiement et recevez vos accès (lien M3U et Xtream Codes) par e-mail ou WhatsApp — généralement en quelques minutes. Suivez ensuite le <a href="' . url('tutorial') . '">guide d’installation</a> de votre appareil.'],
            ['q' => 'Y a-t-il un engagement ou un renouvellement automatique ?', 'a' => 'Non. Vous payez une fois pour la durée choisie (1, 3, 6 ou 12 mois). Rien ne se renouvelle tout seul ; nous vous envoyons un rappel avant la fin de votre abonnement.'],
            ['q' => 'Puis-je être remboursé ?', 'a' => 'Oui : 7 jours sur Essential, 15 jours sur Elite VIP. Lisez la <a href="' . url('refund') . '">politique de remboursement</a>.'],
        ]],
        ['Appareils & installation', [
            ['q' => 'Sur quels appareils fonctionne Mickey TV ?', 'a' => 'Smart TV (Samsung, LG, Sony, Philips, TCL…), box Android TV, Fire TV Stick, Apple TV, iPhone et iPad, GSM et tablettes Android, ordinateurs Windows et Mac et box MAG/Enigma2.'],
            ['q' => 'Comment installer mon abonnement ?', 'a' => 'Suivez les étapes de votre appareil sur la <a href="' . url('tutorial') . '">page d’installation</a>. Ça ne marche pas ? Notre support le fait avec vous sur WhatsApp, jusqu’à ce que l’image s’affiche.'],
            ['q' => 'Puis-je utiliser un identifiant sur plusieurs appareils ?', 'a' => 'L’installation est possible sur autant d’appareils que vous voulez, mais un seul peut regarder à la fois par « appareil » de votre formule. Deux écrans en même temps ? Choisissez 2 appareils à la commande.'],
            ['q' => 'Quel débit internet me faut-il ?', 'a' => 'Dès 20 Mbps, la HD est fluide ; pour la 4K, nous conseillons 30 à 40 Mbps. Toute connexion câble ou fibre belge y arrive. Un wifi stable (ou un câble vers la télé) compte plus qu’une ligne ultra rapide.'],
            ['q' => 'Puis-je utiliser un VPN ?', 'a' => 'Oui. Ce n’est généralement pas nécessaire, mais chez certains fournisseurs ou en voyage, un VPN aide à éviter les coupures. Nous vous aidons à le configurer.'],
        ]],
        ['Support & revente', [
            ['q' => 'Dans quelles langues aidez-vous ?', 'a' => 'En français et en néerlandais, tous les jours de 9h à 23h, par WhatsApp, e-mail ou le <a href="' . url('contact') . '">formulaire de contact</a>.'],
            ['q' => 'Comment devenir revendeur ?', 'a' => 'Contactez-nous via le <a href="' . url('contact') . '">formulaire</a> avec le sujet « Autre chose » en précisant que vous souhaitez devenir revendeur. Nous vous recontactons avec les conditions.'],
            ['q' => 'J’ai parrainé un ami. Comment obtenir mon mois offert ?', 'a' => 'Via la page <a href="' . url('referral') . '">Parrainage</a> : indiquez le numéro de commande de votre ami et vous recevez chacun un mois supplémentaire.'],
        ]],
    ],
    'cta' => ['title' => 'Encore une question ?', 'text' => 'Notre équipe répond en français et en néerlandais, tous les jours.', 'button' => 'Contacter le support'],
];
