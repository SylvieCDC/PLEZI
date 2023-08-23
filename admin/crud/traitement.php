<?php
session_start();
require('../../config/connx.php');

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

function sendErrorResponse($message = 'Une erreur s\'est produite.') {
    echo $message;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte</title>
    <link rel="stylesheet" href="../../assets/css/messages.css">
</head>

<body>
    <?php

    define('TARGET', dirname(__FILE__) . '../../upload_images');
    define('MAX_SIZE', 5000000);
    define('WIDTH_MAX', 1920);
    define('HEIGHT_MAX', 1080);

    $tabExt = array('jpg', 'gif', 'png', 'jpeg');
    $tabMimes = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_FILES['image_produit']['name'])) {
            sendErrorResponse('Veuillez sélectionner une image à télécharger.');
        }

        $extension = pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $tabExt)) {
            sendErrorResponse('L\'extension du fichier n\'est pas autorisée.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image_produit']['tmp_name']);
        if (!in_array($mime, $tabMimes)) {
            sendErrorResponse('Le type du fichier n\'est pas autorisé.');
        }

        $infosImg = getimagesize($_FILES['image_produit']['tmp_name']);
        if (!$infosImg || $infosImg[0] > WIDTH_MAX || $infosImg[1] > HEIGHT_MAX || filesize($_FILES['image_produit']['tmp_name']) > MAX_SIZE) {
            sendErrorResponse('L\'image dépasse les dimensions ou la taille maximale autorisée.');
        }

        $nomImage = md5(uniqid()) . '.' . $extension;
        if (!move_uploaded_file($_FILES['image_produit']['tmp_name'], TARGET . '/' . $nomImage)) {
            sendErrorResponse('Une erreur est survenue lors du téléchargement de l\'image.');
        }
        chmod(TARGET . '/' . $nomImage, 0644);

        $nomProduit = htmlspecialchars($_POST['titre_produit']);
        $nomCatSelected = intval($_POST['nom_categorie']);
        $description = htmlspecialchars($_POST['enonce_produit']);
        $prix = htmlspecialchars($_POST['prix_produit']);
        if (!is_numeric($prix) || $prix < 0) {
            sendErrorResponse('Prix non valide.');
        }

        $cheminImage = '../upload_images/' . $nomImage;
        
        $reqCategorie = 'SELECT Id_categorie FROM categories WHERE Id_categorie = :idCategorie';
        $connCategorie = $db->prepare($reqCategorie);
        $connCategorie->bindParam(':idCategorie', $nomCatSelected);
        $connCategorie->execute();
        $rowCategorie = $connCategorie->fetch(PDO::FETCH_ASSOC);

        if (!$rowCategorie) {
            sendErrorResponse("La catégorie spécifiée n'a pas été trouvée.");
        }

        $idCategorie = $rowCategorie['Id_categorie'];

        $reqInsert = 'INSERT INTO produits (titre_produit, Id_categorie, enonce_produit, prix_produit, image_produit) VALUES (:nomProduit, :idCategorie, :description, :prix, :cheminImage)';
        $connInsert = $db->prepare($reqInsert);
        $connInsert->bindParam(':nomProduit', $nomProduit);
        $connInsert->bindParam(':idCategorie', $idCategorie);
        $connInsert->bindParam(':description', $description);
        $connInsert->bindParam(':prix', $prix);
        $connInsert->bindParam(':cheminImage', $cheminImage);

        if ($connInsert->execute()) {
            echo "<div class='mess_inscription'>Le produit a été ajouté avec succès.<br> <br>
            <a href='../form/add_produit_form.php' class='inscription_lien'>Ajouter un nouveau Produit</a><br><br>
            <a href='gestion_produits.php' class='inscription_lien'>Retour</a>
            </div>";
        } else {
            sendErrorResponse('Une erreur est survenue lors de l\'ajout du produit.');
        }
    }
    ?>
