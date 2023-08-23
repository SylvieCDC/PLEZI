<?php
require_once('config/connx.php'); // Include the database connection
// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51NXfGuEngKRgzXPTdYJwSElWj5verJiUON1ntMzwoAofv61eJv85AjeociWpMnZeW7ibSYOwOiOVM6kjypI42G4T00nKwqu6VC'); // Replace with your Stripe secret key

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents('php://input');
$event = json_decode($input);

// Verify the event by fetching it from Stripe
try {
    $event = \Stripe\Event::retrieve($event->id);
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

// Handle the payment success event
if ($event->type === 'checkout.session.completed') {
    // Extract relevant data from the event payload
    $paymentIntentId = $event->data->object->payment_intent;
    $totalAmount = $event->data->object->amount_total;

    // Retrieve user data (replace this with your user authentication method)
    $userId = 1; // Replace with the actual user ID

    // Insert data into the 'commandes' table
    $dateCommande = date('Y-m-d H:i:s');
    $query = "INSERT INTO commandes (date_commande, prix_commande, Id_user) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$dateCommande, $totalAmount, $userId]);
    $orderId = $db->lastInsertId(); // Get the ID of the newly inserted order

    // Insert data into the 'composer' table for each product in the order
    foreach ($_SESSION['panier'] as $productId => $quantity) {
        $query = "INSERT INTO composer (Id_produit, Id_commande) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$productId, $orderId]);
    }

    // Clear the shopping cart (you may want to do this only after a successful order)
    $_SESSION['panier'] = array();

    // Respond with a success status to Stripe
    http_response_code(200);
}