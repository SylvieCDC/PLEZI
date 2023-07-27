<?php
session_start();
require_once("../config/connx.php");

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

define('TARGET', dirname(__FILE__) . '../../upload_images');
define('MAX_SIZE', 5000000);
define('WIDTH_MAX', 1920);
define('HEIGHT_MAX', 1080);

$tabExt = array('jpg', 'gif', 'png', 'jpeg');
$infosImg = array();
$extension = '';
$nomImage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image_produit']['name'])) {
        $error = $_FILES['image_produit']['error'];
        if ($error === UPLOAD_ERR_OK) {
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
                        $nomImage = md5(uniqid()) . '.' . $extension;

                        if (move_uploaded_file($_FILES['image_produit']['tmp_name'], TARGET . '/' . $nomImage)) {
                            chmod(TARGET . '/' . $nomImage, 0644);
                            // Récupérer les données du formulaire
                            $id_produit = $_POST["id"];
                            $titre_produit = htmlspecialchars($_POST["titre_produit"]);
                            $id_categorie = htmlspecialchars($_POST["nom_categorie"]);
                            $enonce_produit = htmlspecialchars($_POST["enonce_produit"]);
                            $prix_produit = htmlspecialchars($_POST["prix_produit"]);
                            
                            $cheminImage = '../upload_images/' . $nomImage;

                            // Requête SQL pour mettre à jour le produit
                            $sql = "UPDATE produits SET titre_produit = :titre, Id_categorie = :categorie, enonce_produit = :enonce, prix_produit = :prix, image_produit = :image WHERE id = :id";

                            $stmt = $db->prepare($sql);
                            $stmt->bindParam(":titre", $titre_produit);
                            $stmt->bindParam(":categorie", $id_categorie);
                            $stmt->bindParam(":enonce", $enonce_produit);
                            $stmt->bindParam(":prix", $prix_produit);
                            $stmt->bindParam(":image", $cheminImage);
                            $stmt->bindParam(":id", $id_produit);

                            try {
                                $stmt->execute();
                                $_SESSION['message'] = "Produit mis à jour avec succès.";
                            } catch (PDOException $e) {
                                $_SESSION['erreur'] = "Erreur lors de la mise à jour du produit : " . $e->getMessage();
                            }

                            // Redirection vers la page de gestion des produits après la mise à jour
                            header("Location: gestion_produits.php");
                            exit;
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
            if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
                echo 'Le fichier dépasse la taille maximale autorisée.';
            } elseif ($error === UPLOAD_ERR_PARTIAL) {
                echo 'Le fichier n\'a été que partiellement téléchargé.';
            } elseif ($error === UPLOAD_ERR_NO_FILE) {
                echo 'Veuillez sélectionner une image à télécharger.';
            } else {
                echo 'Une erreur est survenue lors du téléchargement de l\'image.';
            }
        }
    } else {
        echo 'Veuillez sélectionner une image à télécharger.';
    }
}
?>
