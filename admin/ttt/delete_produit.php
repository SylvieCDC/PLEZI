<?php
session_start();

// Vérifier si l'ID du produit à supprimer a été envoyé via la requête GET
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Vérifier si le produit existe dans le panier (dans la session)
    if (isset($_SESSION['panier'][$product_id])) {
        // Vérifier la quantité du produit dans le panier
        if ($_SESSION['panier'][$product_id] > 1) {
            // Décrémenter la quantité du produit
            $_SESSION['panier'][$product_id]--;

        } else {
            // Si la quantité est égale à 1, supprimer complètement le produit du panier en utilisant unset()
            unset($_SESSION['panier'][$product_id]);
        }
    }
}

// Rediriger l'utilisateur vers la page du panier (panier.php)
header('Location: ../Panier/produits.php');
exit();
?>
