<?php

// inclus la bdd
require_once('config/connx.php'); 
// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// on met la clé secrete Stripe
\Stripe\Stripe::setApiKey('sk_test_51NXfGuEngKRgzXPTdYJwSElWj5verJiUON1ntMzwoAofv61eJv85AjeociWpMnZeW7ibSYOwOiOVM6kjypI42G4T00nKwqu6VC'); // Replace with your Stripe secret key

//  Récupère le corps de la requête et le parse en tant que JSON
$input = @file_get_contents('php://input');
// Convertit les données JSON en un objet ou un tableau en utilisant la fonction json_decode()
$event = json_decode($input);

// Vérifie l'événement en le récupérant depuis Stripe
try {
    //Tente de récupérer l'événement depuis Stripe en utilisant son identifiant
    $event = \Stripe\Event::retrieve($event->id);
    // Répond avec un code d'état HTTP 400 en cas d'erreur de vérification de la signature
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// Gérer l'événement de paiement réussi
if ($event->type === 'checkout.session.completed') {
    //  Extraire les données pertinentes de la charge utile de l'événement : Identifiant de l'intention de paiement
    $paymentIntentId = $event->data->object->payment_intent;
    // Montant total de la commande
    $totalAmount = $event->data->object->amount_total;

    // Récupérer les données de l'utilisateur (notre méthode d'authentification utilisateur)
    $userId = 1; //  l'ID réel de l'utilisateur

    // Insérer les données dans la table 'commandes'
    $dateCommande = date('Y-m-d H:i:s');
    $query = "INSERT INTO commandes (date_commande, prix_commande, Id_user) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$dateCommande, $totalAmount, $userId]);
    // Obtenir l'ID de la commande nouvellement insérée
    $orderId = $db->lastInsertId();

    // Insérer les données dans la table 'composer' pour chaque produit de la commande
    foreach ($_SESSION['panier'] as $productId => $quantity) {
        $query = "INSERT INTO composer (Id_produit, Id_commande) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$productId, $orderId]);
    }

    // Vider le panier d'achats (vous voudrez peut-être le faire uniquement après une commande réussie)
    $_SESSION['panier'] = array();

    // Répondre à Stripe avec un statut de succès
    http_response_code(200);
}