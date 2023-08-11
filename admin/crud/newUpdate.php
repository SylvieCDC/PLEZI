<?php session_start() ?>
<?php

require_once("../../config/connx.php");

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

if (!isset($_SESSION['Id_role']) || $_SESSION['Id_role'] != 1) {
    // Redirigez vers une page d'erreur ou une autre page
    header('Location: /index.php');
    exit;
}

if (isset($_GET)) {


    $id = $_GET['id'];


    // on prends les donnees de post pour les stockers.
    $update_titre_produit = htmlspecialchars($_POST['titre_produit']);
    $update_Id_categorie = htmlspecialchars($_POST['nom_categorie']);
    $update_enonce_produit = htmlspecialchars($_POST['enonce_produit']);
    $update_prix_produit = htmlspecialchars($_POST['prix_produit']);

    //Je recherche le produit dans ma bdd
    $sql = "SELECT * FROM produits WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    $titre_produit = $produit['titre_produit'];
    $id_categorie = $produit['Id_categorie'];
    $enonce_produit = $produit['enonce_produit'];
    $prix_produit = $produit['prix_produit'];
    $image_produit = $produit['image_produit'];



    $updateData = array();

    if (!empty($update_titre_produit)) {
        $updateData['titre_produit'] = $update_titre_produit;
    } else {
        $updateData['titre_produit'] = $titre_produit;
    }
    if (!empty($update_Id_categorie)) {
        $updateData['Id_categorie'] = $update_Id_categorie;
    } else {
        $updateData['Id_categorie'] = $id_categorie;
    }
    if (!empty($update_enonce_produit)) {
        $updateData['enonce_produit'] = $update_enonce_produit;
    } else {
        $updateData['enonce_produit'] = $enonce_produit;
    }
    if (!empty($update_prix_produit)) {
        $updateData['prix_produit'] = $update_prix_produit;
    } else {
        $updateData['prix_produit'] = $prix_produit;
    }

    if (!empty($_FILES['image_produit']['name'])) {
        define('TARGET', dirname(__FILE__) . '../../upload_images');
        define('MAX_SIZE', 50000000);
        define('WIDTH_MAX', 1920);
        define('HEIGHT_MAX', 1080);


        $tabExt = array('jpg', 'gif', 'png', 'jpeg');
        $infosImg = array();
        $extension = '';
        $nomImage = '';


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

                            $updateData['image_produit'] = '../upload_images/' . $nomImage;

                        }
                    }
                }
            }
        }


    } else {
        $updateData['image_produit'] = $image_produit;
    }
    ;

    $sql = "UPDATE produits SET titre_produit = :titre, Id_categorie = :categorie, enonce_produit = :enonce, image_produit = :image_produit, prix_produit = :prix WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":titre", $updateData['titre_produit']);
    $stmt->bindParam(":categorie", $updateData['Id_categorie']);
    $stmt->bindParam(":enonce", $updateData['enonce_produit']);
    $stmt->bindParam(":prix", $updateData['prix_produit']);
    $stmt->bindParam(":image_produit", $updateData['image_produit']);
    $stmt->bindParam(":id", $id);

    try {
        $stmt->execute();
        $_SESSION['message'] = "Produit mis à jour avec succès.";
        header('Location: gestion_produits.php');
    } catch (PDOException $e) {
        $_SESSION['erreur'] = "Erreur lors de la mise à jour du produit : " . $e->getMessage();
    }


}

?>