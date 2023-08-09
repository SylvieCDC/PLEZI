<?php

include_once ('connx.php');

// Fonction pour récupérer un produit par son ID
function findProduitById($id)
{
    global $db;

    $req = 'SELECT * FROM produits WHERE id = :id';
    $stmt = $db->prepare($req);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer l'ID de catégorie correspondant au nom de catégorie
function getIdCategorieByNom($nomCatSelected)
{
    global $db;

    $reqCategorie = 'SELECT Id_categorie FROM categories WHERE nom_categorie = :nomCat';
    $stmtCategorie = $db->prepare($reqCategorie);
    $stmtCategorie->bindParam(':nomCat', $nomCatSelected);
    $stmtCategorie->execute();
    $rowCategorie = $stmtCategorie->fetch(PDO::FETCH_ASSOC);

    if ($rowCategorie) {
        return $rowCategorie['Id_categorie'];
    } else {
        return false;
    }
}

// Fonction pour insérer un produit
function insertProduit($nomProduit, $nomCatSelected, $description, $prix, $cheminImage)
{
    global $db;

    $idCategorie = getIdCategorieByNom($nomCatSelected);

    if (!$idCategorie) {
        return "La catégorie spécifiée n'a pas été trouvée.";
    }

    // Insertion des données dans la base de données
    $reqInsert = 'INSERT INTO produits ( titre_produit, enonce_produit, prix_produit, image_produit) VALUES (:nomProduit, :idCategorie, :description, :prix, :cheminImage)';
    $stmtInsert = $db->prepare($reqInsert);
    $stmtInsert->bindParam(':nomProduit', $nomProduit);
    $stmtInsert->bindParam(':idCategorie', $idCategorie);
    $stmtInsert->bindParam(':description', $description);
    $stmtInsert->bindParam(':prix', $prix);
    $stmtInsert->bindParam(':cheminImage', $cheminImage);

    if ($stmtInsert->execute()) {
        $message = 'Le produit a été ajouté avec succès.';
        $message .= '<br><br><a href="../admin/form/add_produit_form.php">Retour au formulaire d\'ajout de produits</a>';
        return $message;
    } else {
        return 'Une erreur est survenue lors de l\'ajout du produit.';
    }
}

// Fonction pour supprimer un produit par son ID
function deleteProduit($id)
{
    global $db;

    $req = 'DELETE FROM produits WHERE id = :id';
    $stmt = $db->prepare($req);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        return 'Le produit a été supprimé avec succès.';
    } else {
        return 'Une erreur est survenue lors de la suppression du produit.';
    }
}

// Fonction pour mettre à jour un produit
function updateProduit($id, $nomProduit, $nomCatSelected, $description, $prix, $cheminImage)
{
    global $db;

    $idCategorie = getIdCategorieByNom($nomCatSelected);

    if (!$idCategorie) {
        return "La catégorie spécifiée n'a pas été trouvée.";
    }

    // Mise à jour des données dans la base de données
    $reqUpdate = 'UPDATE produits SET  titre_produit  = :nomProduit, Id_categorie = :idCategorie, description_produit = :description, prix = :prix, image_produit = :cheminImage WHERE id = :id';
    $stmtUpdate = $db->prepare($reqUpdate);
    $stmtUpdate->bindParam(':id', $id);
    $stmtUpdate->bindParam(':nomProduit', $nomProduit);
    $stmtUpdate->bindParam(':idCategorie', $idCategorie);
    $stmtUpdate->bindParam(':description', $description);
    $stmtUpdate->bindParam(':prix', $prix);
    $stmtUpdate->bindParam(':cheminImage', $cheminImage);

    if ($stmtUpdate->execute()) {
        return 'Le produit a été mis à jour avec succès.';
    } else {
        return 'Une erreur est survenue lors de la mise à jour du produit.';
    }
}


function reDirect($url, $message) {
    global $db;

    // Sauvegarder le message dans une variable de session
    $_SESSION['redirect_message'] = $message;

    // Rediriger vers l'URL spécifiée
    header("Location: " . $url);
    exit();
}


?>


