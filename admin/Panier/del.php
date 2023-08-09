<?php
session_start();
include_once "../../config/connx.php";

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Vérifier si le panier est vide ou non initialisé, puis initialiser le panier
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

// Supprimer le produit du panier si l'ID est spécifié dans l'URL
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $product_id = intval($_GET['del']); // Valider que c'est un entier

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
