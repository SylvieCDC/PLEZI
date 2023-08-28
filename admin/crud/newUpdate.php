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

// je vérifie si des paramètres GET ont été envoyés
if (isset($_GET)) {


    // je récupère la valeur de 'id' depuis les paramètres GET par sa superglobale $_GET
    $id = $_GET['id'];

    // Je récupère et sécurise les données envoyées via POST
    $update_titre_produit = htmlspecialchars($_POST['titre_produit']);
    $update_Id_categorie = htmlspecialchars($_POST['nom_categorie']);
    $update_enonce_produit = htmlspecialchars($_POST['enonce_produit']);
    $update_prix_produit = htmlspecialchars($_POST['prix_produit']);

    // Je recherche le produit dans ma bdd
    $sql = "SELECT * FROM produits WHERE id = :id";
    // Je prépare la requête SQL pour sélectionner le produit en fonction de l'ID
    $stmt = $db->prepare($sql);
    // je lie le paramètre :id à la valeur de $id 
    $stmt->bindParam(":id", $id);
    // J'éxécute la requête
    $stmt->execute();
    // je récupère le résultat sous forme de tableau associatif
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupère les données du produit depuis le résultat
    $titre_produit = $produit['titre_produit'];
    $id_categorie = $produit['Id_categorie'];
    $enonce_produit = $produit['enonce_produit'];
    $prix_produit = $produit['prix_produit'];
    $image_produit = $produit['image_produit'];




    // Je crée un tableau vide qui va se remplir avec les données modifiées et celles non modifiées
    $updateData = array();

    if (!empty($update_titre_produit)) {
        // Si $update_titre_produit n'est pas vide, utilisez cette valeur
        $updateData['titre_produit'] = $update_titre_produit;
    } else {
        // Sinon, utilisez la valeur existante précédente
        $updateData['titre_produit'] = $titre_produit;
    }

    if (!empty($update_Id_categorie)) {
        // Si $update_Id_categorie n'est pas vide, utilisez cette valeur
        $updateData['Id_categorie'] = $update_Id_categorie;
    } else {
        // Sinon, utilisez la valeur existante précédente
        $updateData['Id_categorie'] = $id_categorie;
    }

    if (!empty($update_enonce_produit)) {
        // Si $update_enonce_produit n'est pas vide, utilisez cette valeur
        $updateData['enonce_produit'] = $update_enonce_produit;
    } else {
        // Sinon, utilisez la valeur existante précédente
        $updateData['enonce_produit'] = $enonce_produit;
    }

    if (!empty($update_prix_produit)) {
        // Si $update_prix_produit n'est pas vide, utilisez cette valeur
        $updateData['prix_produit'] = $update_prix_produit;
    } else {
        // Sinon, utilisez la valeur existante précédente
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

            // Vérification de la validité du nom de fichier et éviter deux extensions par ex 
            //on compte le nombre de "." si c'est plus que 1 c'est invalide et peut être 
            //un script malveillant déguisé 
            $extension = pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION);
            $extensionCount = substr_count($filename, '.');
            if ($extensionCount > 1) {
                sendErrorResponse('Le nom du fichier est invalide.');
            }
            // Test getimagesize on the uploaded file
            $imageSizeInfo = getimagesize($_FILES['image_produit']['tmp_name']);
            if ($imageSizeInfo === false) {
                die("L\'image dépasse les dimensions ou la taille maximale autorisée.");
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


    // Je prépare la requête SQL d'une mise à jour des données du produit avec des paramètres nommés pour éviter les injections SQL
    $sql = "UPDATE produits SET titre_produit = :titre, Id_categorie = :categorie, enonce_produit = :enonce, image_produit = :image_produit, prix_produit = :prix WHERE id = :id";

    // Prépare la requête SQL
    $stmt = $db->prepare($sql);

    // Lie les paramètres aux valeur concernées
    $stmt->bindParam(":titre", $updateData['titre_produit']);
    $stmt->bindParam(":categorie", $updateData['Id_categorie']);
    $stmt->bindParam(":enonce", $updateData['enonce_produit']);
    $stmt->bindParam(":prix", $updateData['prix_produit']);
    $stmt->bindParam(":image_produit", $updateData['image_produit']);
    $stmt->bindParam(":id", $id);

    // on gère les erreurs potentielles qui peuvent se produire lors de l'exécution du code à l'intérieur du bloc try
    // et on les récupère dans catch
    try {
        // Exécute la requête de mise à jour
        $stmt->execute();
        // Stocke un message de succès dans la session et l'affiche
        $_SESSION['message'] = "Produit mis à jour avec succès.";
        // Redirige l'utilisateur vers la page de gestion des produits
        header('Location: gestion_produits.php');
    } catch (PDOException $e) {
        // En cas d'erreur, stocke un message d'erreur dans la session et l'affiche
        $_SESSION['erreur'] = "Erreur lors de la mise à jour du produit : " . $e->getMessage();
    }



}

?>