<?php
/** Referral page (French). */
if (!defined('PRIMO_APP')) exit;
return [
    'title'       => 'Parrainage — vous et votre ami recevez un mois offert',
    'description' => 'Parrainez un ami chez Mickey TV : vous recevez chacun un mois supplémentaire. Pas de code, remplissez le formulaire après la commande.',
    'search'      => ['title' => 'Parrainage', 'excerpt' => 'Parrainez un ami et recevez chacun un mois offert.', 'text' => 'parrainer ami parrainage mois offert'],
    'hero' => ['badge' => 'Parrainage', 'title' => 'Faites passer<br>le mot', 'lede' => 'Commandez comme d’habitude — aucun code à entrer. Dites-nous qui vous a envoyé et nous ajoutons la récompense aux deux comptes dès que la commande est vérifiée.'],
    'perks' => [['Vous recevez', 'Un mois de plus'], ['Votre ami reçoit', 'Un mois aussi']],
    'how' => ['title' => 'Comment ça marche', 'steps' => [['Votre ami commande', 'Il ou elle choisit une formule et paie comme tout le monde. Pas de code, pas de remise à la caisse.'], ['Remplissez ce formulaire', 'Avec vos coordonnées, le numéro de commande de votre ami et son adresse e-mail.'], ['Nous faisons le lien', 'Une personne vérifie la commande et ajoute un mois à chacun. Vous recevez une confirmation par e-mail.']], 'note' => 'Les deux mois sont ajoutés après vérification de la commande. Rien n’est déduit au paiement.'],
    'form' => ['title' => 'Demandez votre mois', 'you' => 'Vos coordonnées (le nouveau client)', 'friend' => 'Qui vous a parrainé ?', 'name' => 'Nom complet', 'email' => 'E-mail de votre commande', 'order' => 'Numéro de commande', 'order_hint' => 'ex. #BE-10492', 'ref_name' => 'Nom de votre ami', 'ref_email' => 'E-mail de votre ami', 'send' => 'Envoyer la demande'],
    'vault' => ['title' => 'Après l’envoi', 'items' => [['Chaque demande est vérifiée par une personne', 'Nous lions le numéro de commande au compte de votre ami.'], ['Combien de temps ?', 'Généralement sous 24 heures.'], ['Où va le mois ?', 'Sur l’abonnement en cours de chacun ; la nouvelle date de fin figure dans votre confirmation.'], ['Si quelque chose cloche', 'Nous vous écrivons avant de refuser une demande.'], ['Astuce', 'Copiez les adresses e-mail depuis la confirmation de commande ; une faute de frappe est la première cause de retard.']]],
];
