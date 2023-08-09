<?php
session_start();
include_once "../../config/connx.php";

$response = ["success" => false];

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
        
        $response["success"] = true;
    }
}

echo json_encode($response);

?>

