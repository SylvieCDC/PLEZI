<?php
session_start();
require('../../config/connx.php');

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
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
    // $infosImg = array();
    
    $extension = '';
    $nomImage = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_FILES['image_produit']['name'])) {
            $extension = pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION);

            // Test getimagesize on the uploaded file
            $imageSizeInfo = getimagesize($_FILES['image_produit']['tmp_name']);
            if ($imageSizeInfo === false) {
                die("Error getting image size.");
            }


            if (in_array(strtolower($extension), $tabExt)) {
                $infosImg = getimagesize($_FILES['image_produit']['tmp_name']);

                if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {
                    if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['image_produit']['tmp_name']) <= MAX_SIZE)) {
                        if (isset($_FILES['image_produit']['error']) && UPLOAD_ERR_OK === $_FILES['image_produit']['error']) {
                            $nomImage = md5(uniqid()) . '.' . $extension;

                            if (move_uploaded_file($_FILES['image_produit']['tmp_name'], TARGET . '/' . $nomImage)) {
                                chmod(TARGET . '/' . $nomImage, 0644);

                                $nomProduit = htmlspecialchars($_POST['titre_produit']);
                                $nomCatSelected = intval($_POST['nom_categorie']);
                                $description = htmlspecialchars($_POST['enonce_produit']);
                                $prix = htmlspecialchars($_POST['prix_produit']);
                                $cheminImage = '../upload_images/' . $nomImage;

                                // Récupération de l'ID de catégorie correspondant au nom de catégorie
                                $reqCategorie = 'SELECT Id_categorie FROM categories WHERE Id_categorie = :idCategorie';
                                $connCategorie = $db->prepare($reqCategorie);
                                $connCategorie->bindParam(':idCategorie', $nomCatSelected);
                                $connCategorie->execute();
                                $rowCategorie = $connCategorie->fetch(PDO::FETCH_ASSOC);

                                if (!$rowCategorie) {
                                    echo "La catégorie spécifiée n'a pas été trouvée.";
                                    exit;
                                }

                                $idCategorie = $rowCategorie['Id_categorie'];

                                // Insertion des données dans la base de données
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
                                    echo 'Une erreur est survenue lors de l\'ajout du produit.';
                                }
                            } else {
                                echo 'Une erreur est survenue lors du téléchargement de l\'image.';
                            }
                        } else {
                            echo 'Une erreur est survenue lors du téléchargement de l\'image.';
                        }
                    } else {
                        echo 'L\'image dépasse les dimensions ou la taille maximale autorisée.';
                    }
                } else {
                    echo 'Le fichier téléchargé n\'est pas une image valide.';
                }
            } else {
                echo 'L\'extension du fichier n\'est pas autorisée.';
            }
        } else {
            echo 'Veuillez sélectionner une image à télécharger.';
        }
    }


    ?>