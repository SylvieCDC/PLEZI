<?php
session_start();
require('../../config/connx.php');


// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Suppression d'un produit
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    // 1. Récupérer le nom de l'image associée au produit
    $selectSql = "SELECT image_produit FROM produits WHERE id = :id";
    $selectStmt = $db->prepare($selectSql);
    $selectStmt->bindParam(':id', $deleteId);
    $selectStmt->execute();
    $product = $selectStmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['image_produit']) {
        $imageName = $product['image_produit'];

        // 2. Supprimer l'image du serveur
        $imagePath = "../upload_images/" . $imageName;  // Remplacez "chemin_vers_dossier_upload_images" par le chemin réel vers votre dossier d'images
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // 3. Requête de suppression du produit de la base de données
    $deleteSql = "DELETE FROM produits WHERE id = :id";
    $deleteStmt = $db->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $deleteId);
    $deleteStmt->execute();

    $_SESSION['message'] = 'Le produit a été supprimé avec succès.';
    header('Location: gestion_produits.php');
    exit();
}


?>