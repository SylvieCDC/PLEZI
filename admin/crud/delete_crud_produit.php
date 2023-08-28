<?php
session_start();
require('../../config/connx.php');

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Suppression d'un produit: vérifie si le paramètre 'delete_id' est présent dans les paramètres GET
if (isset($_GET['delete_id'])) {

    // Récupère la valeur de 'delete_id' depuis les paramètres GET
    $deleteId = $_GET['delete_id'];


    // 1. Récupérer le nom de l'image associée au produit
    $selectSql = "SELECT image_produit FROM produits WHERE id = :id";
    // Prépare la requête SQL pour sélectionner le nom de l'image du produit en fonction de son ID
    $selectStmt = $db->prepare($selectSql);
    // on va lier le paramètre :id à la valeur de $deleteId
    $selectStmt->bindParam(':id', $deleteId);
    // on exécute la requête
    $selectStmt->execute();
    // on récupère le résultat sous forme de tableau associatif
    $product = $selectStmt->fetch(PDO::FETCH_ASSOC);

    // Si le produit existe et a une image associée
    if ($product && $product['image_produit']) {

        // on récup le nom de l'image du produit
        $imageName = $product['image_produit'];


        // on supprime l'image dans le dossier upload_images en construisant le chemin complet de l'image + le préfixe du répertoire
        $imagePath = "../upload_images/" . $imageName;
        // Si le fichier image existe physiquement on le supprime avec la fonction unlink
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    //  requête de suppression du produit de la base de données
    $deleteSql = "DELETE FROM produits WHERE id = :id";
    // on prépare la requête SQL pour supprimer le produit en fonction de son ID
    $deleteStmt = $db->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $deleteId);
    // on exécute la requête
    $deleteStmt->execute();

    // on stocke un message de succès dans la session et on l'affiche + redirection vers gestion des produits
    $_SESSION['message'] = 'Le produit a été supprimé avec succès.';
    header('Location: gestion_produits.php');
    exit();
}



?>