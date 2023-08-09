<?php session_start() ?>
<?php

require_once("../../config/connx.php");

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}
if (isset($_GET)) {


    $id = $_GET['id'];


    // on prends les donnees de post pour les stockers.
    $update_nom = htmlspecialchars($_POST['nom']);
    $update_prenom = htmlspecialchars($_POST['prenom']);
    $update_email = htmlspecialchars($_POST['email']);
    $update_telephone = htmlspecialchars($_POST['telephone']);
    $update_role = htmlspecialchars($_POST['nom_role']);

    //Je recherche l'utilisateur' dans ma bdd
    $sql = "SELECT * FROM users WHERE Id_user = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $nom = $user['nom'];
    $prenom = $user['prenom'];
    $email = $user['email'];
    $telephone = $user['telephone'];
    $roleId = $user['Id_role'];


    $updateData = array();

    if (!empty($update_nom)) {
        $updateData['nom'] = $update_nom;
    } else {
        $updateData['nom'] = $nom;
    }
    if (!empty($update_prenom)) {
        $updateData['prenom'] = $update_prenom;
    } else {
        $updateData['prenom'] = $prenom;
    }
    if (!empty($update_telephone)) {
        $updateData['telephone'] = $update_telephone;
    } else {
        $updateData['telephone'] = $telephone;
    }
    if (!empty($update_email)) {
        $updateData['email'] = $update_email;
    } else {
        $updateData['email'] = $email;
    }
    if (!empty($update_role)) {
        $updateData['Id_role'] = $update_role;
    } else {
        $updateData['Id_role'] = $roleId;
    }
}

$sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone , Id_role = :id_role WHERE Id_user = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(":nom", $updateData['nom']);
$stmt->bindParam(":prenom", $updateData['prenom']);
$stmt->bindParam(":email", $updateData['email']);
$stmt->bindParam(":telephone", $updateData['telephone']);
$stmt->bindParam(":id_role", $updateData['Id_role']);
$stmt->bindParam(":id", $id);

try {
    $stmt->execute();
    $_SESSION['message'] = "Utilisateur mis à jour avec succès.";
    header('Location: gestion_users.php');
} catch (PDOException $e) {
    $_SESSION['erreur'] = "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
}

?>