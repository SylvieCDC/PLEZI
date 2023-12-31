<?php
session_start();

// Charger les dépendances nécessaires
require('../../config/connx.php');
require('../../vendor/autoload.php');

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Fonction pour gérer les erreurs et répondre avec un message
function sendErrorResponse($message = 'Une erreur s\'est produite.')
{
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

    // Constantes pour le téléchargement d'images
    define('TARGET', dirname(__FILE__) . '../../upload_images');
    define('MAX_SIZE', 5000000);
    define('WIDTH_MAX', 1920);
    define('HEIGHT_MAX', 1080);

    $tabExt = array('jpg', 'gif', 'png', 'jpeg');
    $tabMimes = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');

    use PHPExif\Reader\Reader;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Vérification des champs obligatoires
    
        if (empty($_POST['titre_produit'])) {
            sendErrorResponse('Veuillez fournir un titre pour le produit.');
        }

        if (empty($_POST['nom_categorie']) || !is_numeric($_POST['nom_categorie'])) {
            sendErrorResponse('Veuillez sélectionner une catégorie valide.');
        }

        if (empty($_POST['enonce_produit'])) {
            sendErrorResponse('Veuillez fournir une description pour le produit.');
        }

        if (empty($_POST['prix_produit']) || !is_numeric($_POST['prix_produit'])) {
            sendErrorResponse('Veuillez fournir un prix valide pour le produit.');
        }
        
        if (empty($_FILES['image_produit']['name'])) {
            sendErrorResponse('Veuillez sélectionner une image à télécharger.');
        }

        if ($_FILES['image_produit']['error'] !== UPLOAD_ERR_OK) {
            sendErrorResponse('Une erreur est survenue lors du téléchargement du fichier.');
        }


        // Vérification de la validité du nom de fichier et éviter deux extensions par ex on compte le nombre de "." si c'est plus que 1 c'est invalide et peut être un script malveillant déguisé 
        $filename = $_FILES['image_produit']['name'];
        $extensionCount = substr_count($filename, '.');
        if ($extensionCount > 1) {
            sendErrorResponse('Le nom du fichier est invalide.');
        }

        // Vérification de l'extension du fichier
        $extension = pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $tabExt)) {
            sendErrorResponse('L\'extension du fichier n\'est pas autorisée.');
        }

        // Vérification du type MIME du fichier
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image_produit']['tmp_name']);
        if (!in_array($mime, $tabMimes)) {
            sendErrorResponse('Le type du fichier n\'est pas autorisé.');
        }

        // Vérification de la taille et des dimensions de l'image
        $infosImg = getimagesize($_FILES['image_produit']['tmp_name']);
        if (!$infosImg || $infosImg[0] > WIDTH_MAX || $infosImg[1] > HEIGHT_MAX || filesize($_FILES['image_produit']['tmp_name']) > MAX_SIZE) {
            sendErrorResponse('L\'image dépasse les dimensions ou la taille maximale autorisée.');
        }

        // Vérification des métadonnées de l'image pour des chaînes suspectes (quand on modifie les métadonnées avec un script malveillant par exemple)
        try {
            $reader = Reader::factory(Reader::TYPE_NATIVE);
            $exif = $reader->read($_FILES['image_produit']['tmp_name']);

            if (!$exif) {
                throw new Exception('Les métadonnées de l\'image ne peuvent pas être lues ou sont absentes.');
            }

            $data = $exif->getData();
            foreach ($data as $value) {
                if (is_string($value)) {
                    // Rechercher le contenu PHP malveillant
                    if (strpos($value, '<?php') !== false) {
                        sendErrorResponse('Les métadonnées de l\'image sont suspectes (contenu PHP détecté).');
                    }

                    // Rechercher les scripts JavaScript malveillants
                    if (strpos(strtolower($value), '<script') !== false) {
                        sendErrorResponse('Les métadonnées de l\'image sont suspectes (script JS détecté).');
                    }

                    // Rechercher le contenu Python malveillant
                    if (strpos($value, 'import os') !== false || strpos($value, 'import sys') !== false) {
                        sendErrorResponse('Les métadonnées de l\'image sont suspectes (code Python détecté).');
                    }

                }
            }
        } catch (Exception $e) {
            sendErrorResponse('Une erreur est survenue lors de la vérification des métadonnées de l\'image: ' . $e->getMessage());
        }


        $nomImage = md5(uniqid()) . '.' . $extension;
        if (!move_uploaded_file($_FILES['image_produit']['tmp_name'], TARGET . '/' . $nomImage)) {
            sendErrorResponse('Une erreur est survenue lors du téléchargement de l\'image.');
        }
        chmod(TARGET . '/' . $nomImage, 0644);

        $nomProduit = trim(strip_tags($_POST['titre_produit']));
        $nomCatSelected = intval($_POST['nom_categorie']);
        $description = trim(strip_tags($_POST['enonce_produit']));
        $prix = $_POST['prix_produit'];

        if (!is_numeric($prix) || $prix < 0) {
            sendErrorResponse('Prix non valide.');
        }


        $cheminImage = '../upload_images/' . $nomImage;



        // Préparation de la requête pour récupérer l'Id de la catégorie sélectionnée
        $reqCategorie = 'SELECT Id_categorie FROM categories WHERE Id_categorie = :idCategorie';

        // Prépare la requête SQL pour éviter les injections SQL
        $connCategorie = $db->prepare($reqCategorie);
        // Lie le paramètre nommé à la valeur de $nomCatSelected
        $connCategorie->bindParam(':idCategorie', $nomCatSelected);
        // Exécute la requête préparée
        $connCategorie->execute();
        // Récupère la première ligne de résultat sous forme de tableau associatif
        $rowCategorie = $connCategorie->fetch(PDO::FETCH_ASSOC);

        // Vérifie si la catégorie existe, sinon affiche une erreur
        if (!$rowCategorie) {
            sendErrorResponse("La catégorie spécifiée n'a pas été trouvée.");
        }

        // Récupère l'Id de la catégorie
        $idCategorie = $rowCategorie['Id_categorie'];

        // Préparation de la requête pour insérer un nouveau produit
        $reqInsert = 'INSERT INTO produits (titre_produit, Id_categorie, enonce_produit, prix_produit, image_produit) VALUES (:nomProduit, :idCategorie, :description, :prix, :cheminImage)';

        // Prépare la requête SQL d'insertion
        $connInsert = $db->prepare($reqInsert);
        // Lie les paramètres nommés aux valeurs correspondantes
        $connInsert->bindParam(':nomProduit', $nomProduit);
        $connInsert->bindParam(':idCategorie', $idCategorie);
        $connInsert->bindParam(':description', $description);
        $connInsert->bindParam(':prix', $prix);
        $connInsert->bindParam(':cheminImage', $cheminImage);

        // Exécute la requête d'insertion et affiche un message en cas de succès ou d'échec
        // Vérifie si l'insertion s'est bien déroulée
        if ($connInsert->execute()) {
            // Affiche un message de succès avec des liens: Ajouter un nouveau produit ou Retour à la page gestion des produits
            echo "<div class='mess_inscription'>Le produit a été ajouté avec succès.<br> <br>
    <a href='../form/add_produit_form.php' class='inscription_lien'>Ajouter un nouveau Produit</a><br><br>
    <a href='gestion_produits.php' class='inscription_lien'>Retour</a>
    </div>";
        } else {
            // Affiche un message d'erreur
            sendErrorResponse('Une erreur est survenue lors de l\'ajout du produit.');
        }

    }
    ?>