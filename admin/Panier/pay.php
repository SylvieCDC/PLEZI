<?php
session_start();
include_once "../../config/connx.php";
require_once '../../vendor/autoload.php'; // Include the Stripe PHP SDK

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51NXfGuEngKRgzXPTdYJwSElWj5verJiUON1ntMzwoAofv61eJv85AjeociWpMnZeW7ibSYOwOiOVM6kjypI42G4T00nKwqu6VC');

//Set la version de l'API
\Stripe\Stripe::setApiVersion('2022-11-15');

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Vérifier si le panier est vide ou non initialisé, puis initialiser le panier
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

// Sanitize and validate the 'del' parameter to prevent SQL injection
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $product_id = intval($_GET['del']); // Convert to integer

    // Vérifier si le produit existe dans le panier
    if (isset($_SESSION['panier'][$product_id])) {
        // Diminuer la quantité du produit dans le panier
        $_SESSION['panier'][$product_id] -= 1;

        // Si la quantité atteint zéro, supprimer le produit du panier
        if ($_SESSION['panier'][$product_id] <= 0) {
            unset($_SESSION['panier'][$product_id]);
        }
    }
}
?>


<?php

// Calculer le total des produits dans le panier
$total = 0;

// Récupérer les clés du tableau session
$ids = array_keys($_SESSION['panier']);

// Array to store line items data
$lineItems = array();

// Vérifier s'il y a des produits dans le panier
if (!empty($ids)) {
    // Utiliser une requête préparée pour récupérer les produits du panier
    $inClause = implode(',', array_fill(0, count($ids), '?'));
    $query = "SELECT * FROM produits WHERE id IN ($inClause)";
    $stmt = $db->prepare($query);

    // Exécuter la requête en liant les valeurs des clés $ids à la requête préparée
    foreach ($ids as $index => $id) {
        $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    if ($stmt->execute()) {
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculer le total des produits dans le panier et collecter les données des lignes de produits
        foreach ($products as $product) {
            $product_id = $product['id'];
            $product_quantity = $_SESSION['panier'][$product_id];

            // Ajouter le prix unitaire du produit multiplié par la quantité au total
            $total += $product['prix_produit'] * $product_quantity;

            // Ajouter les données de la ligne de produit à l'array $lineItems
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['prix_produit'] * 100,
                    // Stripe requires the amount in cents
                    'product_data' => [
                        'name' => $product['titre_produit'],
                        // Replace with your product name
                    ],
                ],
                'quantity' => $product_quantity,
            ];
        }
    } else {
        // Handle the error appropriately (e.g., log the error or display a user-friendly message)
        die("Error fetching products from the database.");
    }
}

// Create a Payment session with Stripe

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => 'http://plezi/admin/Panier/success.php?session_id={CHECKOUT_SESSION_ID}&Id_user=' . $userId,
        'cancel_url' => 'http://plezi/admin/Panier/cancel.html',
    ]);

    // Redirect the user to the payment checkout page
    header("Location: " . $session->url);
    exit();
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle any errors that occur during Stripe session creation
    echo "Error: " . $e->getMessage();
    // Optionally, you can redirect the user to an error page here
    header("Location: ../../messages/error-page.php");
    exit();
}


?>