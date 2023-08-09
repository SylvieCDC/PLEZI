<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../../config/connx.php");

if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['Id_user'])) {
    $id = $_SESSION['Id_user'];

    // Requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM users WHERE Id_user = :id_user";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":id_user", $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nom = $user['nom'];
        $prenom = $user['prenom'];
        $email = $user['email'];
        $telephone = $user['telephone'];

        $update_nom = isset($_POST['nom']) ? $_POST['nom'] : $nom;
        $update_prenom = isset($_POST['prenom']) ? $_POST['prenom'] : $prenom;
        $update_email = isset($_POST['email']) ? $_POST['email'] : $email;
        $update_telephone = isset($_POST['telephone']) ? $_POST['telephone'] : $telephone;

        if ($update_nom !== $nom || $update_prenom !== $prenom || $update_email !== $email || $update_telephone !== $telephone) {
            $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone  WHERE Id_user = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":nom", $update_nom);
            $stmt->bindParam(":prenom", $update_prenom);
            $stmt->bindParam(":email", $update_email);
            $stmt->bindParam(":telephone", $update_telephone);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION['message'] = "Utilisateur mis à jour avec succès.";
            } catch (PDOException $e) {
                $_SESSION['erreur'] = "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
            }
        } else {
            $_SESSION['message'] = "Aucune modification détectée.";
        }

        header('Location: /admin/form/account.php?id=' . $id);
    }
}

///////// Suppression du compte Utilisateur /////////

if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    if (isset($_SESSION['Id_user'])) {
        $id = $_SESSION['Id_user'];

        // Supprimez l'utilisateur de la base de données
        $sql = "DELETE FROM users WHERE Id_user = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id);

        try {
            $stmt->execute();
            session_destroy(); // Détruisez la session car l'utilisateur est maintenant supprimé
            header('Location: /path_to_your_login_page'); // Redirigez vers la page de connexion ou la page d'accueil
            exit;
        } catch (PDOException $e) {
            $_SESSION['erreur'] = "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
            header('Location: /admin/form/account.php?id=' . $id);
            exit;
        }
    }
}
?>