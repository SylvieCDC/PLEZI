<?php
session_start();
require_once "../config/connx.php";

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Check if the product ID and new quantity are provided
if (isset($_GET['id']) && isset($_POST['quantity'])) {
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
                $product_quantity = $_SESSION['panier'][$product_id];
                $total += $product['prix_produit'] * $product_quantity;
            }
        } else {
            die("Error fetching products from the database.");
        }
    }
            // Formater le total avec deux décimales
            $total_formatted = number_format($total, 2, ',', ' ');
    // Return the new total and quantities as JSON data
    $response = array(
        'total' => $total_formatted,
        'quantities' => $_SESSION['panier']
    );

    echo json_encode($response);
} else {
    // Handle the case when product ID and new quantity are not provided
    http_response_code(400); // Bad request
}
?>

