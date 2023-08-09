<?php
session_start();
require_once "../../config/connx.php";

header('Content-Type: application/json');

if (!$db) {
    echo json_encode(['error' => 'Erreur de connexion à la base de données. Veuillez réessayer plus tard.']);
    exit;
}

// Check if the product ID and new quantity are provided
if (isset($_GET['id']) && isset($_POST['quantity']) && intval($_POST['quantity']) > 0) {
    $product_id = $_GET['id'];
    $new_quantity = intval($_POST['quantity']);

    // Update the quantity in the session
    $_SESSION['panier'][$product_id] = $new_quantity;

    // Calculate the new total price
    $total = 0;
    $ids = array_keys($_SESSION['panier']);

    if (!empty($ids)) {
        $inClause = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT * FROM produits WHERE id IN ($inClause)";
        $stmt = $db->prepare($query);

        if ($stmt->execute($ids)) {
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) {
                $product_id = $product['id'];
                $product_quantity = isset($_SESSION['panier'][$product_id]) ? $_SESSION['panier'][$product_id] : 0;
                $total += $product['prix_produit'] * $product_quantity;
            }
        } else {
            echo json_encode(['error' => 'Error fetching products from the database.']);
            exit;
        }
    }
    // Formater le total avec deux décimales
    $total_formatted = number_format($total, 2, ',', ' ');

    // Return the new total and quantities as JSON data
    $response = [
        'total' => $total_formatted,
        'quantities' => $_SESSION['panier']
    ];

    echo json_encode($response);
} else {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Invalid request parameters.']);
}
?>
