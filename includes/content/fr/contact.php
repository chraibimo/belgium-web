<?php
/** Contact page copy (French). */
if (!defined('PRIMO_APP')) exit;
$d = site_data();
return [
    'title'       => 'Contact — parlez à une personne, pas à un robot',
    'description' => 'Contactez Mickey TV par WhatsApp, e-mail ou formulaire. Une vraie personne répond, en français ou en néerlandais, généralement en quelques heures.',
    'search'      => ['title' => 'Contact', 'excerpt' => 'Parlez à une personne par WhatsApp, e-mail ou formulaire — en français ou en néerlandais.', 'text' => 'contact support aide whatsapp e-mail téléphone question'],
    'hero' => [
        'badge' => 'Contact',
        'title' => 'Parlez à une personne.<br>Pas à un robot.',
        'lede'  => 'Vous pensez à un abonnement, vous bloquez sur une chaîne, ou vous êtes déjà client et quelque chose cloche ? Écrivez-nous ici et une vraie personne s’en occupe. Pas de tickets, pas de numéros de file.',
        'reply' => 'Réponse généralement en quelques heures',
    ],
    'ways' => [
        'title' => 'Trois voies, <em>à vous de choisir</em>',
        'items' => [
            ['icon' => 'whatsapp', 'title' => 'WhatsApp', 'text' => 'La ligne la plus rapide. Envoyez une capture ou un enregistrement d’écran et on règle ça généralement sur-le-champ.', 'link' => $d['brand']['whatsapp'], 'label' => $d['brand']['phone']],
            ['icon' => 'mail', 'title' => 'E-mail', 'text' => 'Idéal pour tout ce qui demande du détail — factures, une liste de chaînes, une explication plus longue.', 'link' => 'mailto:' . $d['brand']['email'], 'label' => $d['brand']['email']],
            ['icon' => 'form', 'title' => 'Ce formulaire', 'text' => 'Pas besoin d’application mail. Votre message arrive directement dans notre boîte, avec vos coordonnées.', 'link' => '#form', 'label' => 'Écrivez-nous un message'],
        ],
    ],
    'form' => [
        'title'   => 'Écrivez-nous un message',
        'topics'  => ['Nouvel abonnement', 'Essai gratuit', 'Aide technique', 'Chaînes & contenus', 'Facturation', 'Autre chose'],
        'name'    => 'Votre nom',
        'email'   => 'E-mail',
        'phone'   => 'Numéro WhatsApp',
        'phone_hint' => 'facultatif — pour une réponse plus rapide',
        'subject' => 'Sujet',
        'message' => 'Votre message',
        'send'    => 'Envoyer',
        'privacy' => 'Nous utilisons vos données uniquement pour vous répondre. Voir la politique de confidentialité.',
    ],
    'after' => [
        'title' => 'Ce qui se passe ensuite',
        'items' => ['Votre message arrive immédiatement dans notre boîte, avec une copie dans notre journal pour que rien ne se perde.', 'Nous répondons dans l’ordre d’arrivée, généralement en quelques heures, tous les jours de 9h à 23h.', 'Déjà client ? Indiquez l’e-mail de votre commande, nous vous retrouvons tout de suite.'],
    ],
    'hours' => [
        'title' => 'De vrais horaires. <em>De vraies personnes.</em>',
        'items' => [['2 h', 'Temps de réponse moyen', 'Souvent plus rapide sur WhatsApp.'], ['7j/7', 'Ouvert', 'Tous les jours de 9h à 23h, heure belge. Journée plus courte les jours fériés.'], ['FR & NL', 'Deux langues', 'Écrivez dans la langue que vous voulez ; nous répondons dans la même.']],
    ],
    'faq' => [
        ['q' => 'En combien de temps quelqu’un répond-il ?', 'a' => 'Généralement en quelques heures, tous les jours de 9h à 23h. Souvent plus vite sur WhatsApp.'],
        ['q' => 'Quelque chose ne se lit pas. Que dois-je vous envoyer ?', 'a' => 'Une capture d’écran ou une courte vidéo de l’écran, le nom de la chaîne et l’appareil utilisé. Avec ça, on trouve généralement le problème du premier coup.'],
        ['q' => 'Je suis déjà client — avez-vous besoin de mes données ?', 'a' => 'Seulement l’e-mail ou le numéro WhatsApp de votre commande. N’envoyez jamais votre mot de passe dans un message.'],
        ['q' => 'Une chaîne que je veux n’est pas dans la liste.', 'a' => 'Cherchez d’abord dans le <a href="' . url('channels') . '">guide des chaînes</a>. Elle n’y est pas ? Demandez-nous — si elle existe, on peut généralement l’ajouter.'],
        ['q' => 'Que devient ce que j’écris ici ?', 'a' => 'C’est envoyé par e-mail à notre équipe support et conservé dans notre journal pour vous aider. Nous ne le partageons avec personne et ne l’utilisons pas pour de la publicité. Voir la <a href="' . url('privacy') . '">politique de confidentialité</a>.'],
    ],
];
