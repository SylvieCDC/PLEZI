<?php
session_start();
include_once "../../config/connx.php";

$response = ["success" => false, "total" => 0];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Vérifier si le produit existe dans la base de données
    $stmt = $db->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Ajouter le produit au panier (utilisation de la quantité 1 pour cet exemple)
        if (isset($_SESSION['panier'][$product_id])) {
            $_SESSION['panier'][$product_id] += 1;
        } else {
            $_SESSION['panier'][$product_id] = 1;
        }

        // recalculer le total à chaque ajout de produit dans le panier
        $total = 0;
        foreach ($_SESSION['panier'] as $productId => $quantity) {
            $stmt = $db->prepare("SELECT prix_produit FROM produits WHERE id = ?");
            $stmt->execute([$productId]);
            $productPrice = $stmt->fetchColumn();
            $total += $productPrice * $quantity;
        }

        $response["success"] = true;
        $response["total"] = $total;
    }
}

echo json_encode($response);


?>