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

    // Requête de suppression
    $deleteSql = "DELETE FROM users WHERE Id_user = :id";
    $deleteStmt = $db->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $deleteId);
    $deleteStmt->execute();

    $_SESSION['message'] = `L'utilisateur' a été supprimé avec succès.`;
    header('Location: gestion_users.php');
    exit();
}


?>