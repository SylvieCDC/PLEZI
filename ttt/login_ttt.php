<?php
// Démarre la session
session_start();

require_once('../config/connx.php');

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

$message = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["pass"])) {
    // Récupérer et nettoyer les valeurs du formulaire
    $email = trim(strip_tags(htmlspecialchars($_POST["email"])));
    $password = trim(strip_tags(htmlspecialchars($_POST["pass"])));

    // Vérification des informations d'identification
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Utilisateur trouvé dans la base de données
        $row = $stmt->fetch();
        $hashedPassword = $row["pass"];

        // Vérifier si le mot de passe correspond au hachage stocké
        if (password_verify($password, $hashedPassword)) {
            // Mot de passe correct, rediriger vers une page de succès

            // Stocker les informations de l'utilisateur dans les variables de session
            $_SESSION['Id_user'] = $row["Id_user"];
            $_SESSION['nom'] = $row["nom"];
            $_SESSION['prenom'] = $row["prenom"];
            $_SESSION['email'] = $row["email"];
            $_SESSION['telephone'] = $row["telephone"];
            $_SESSION['Id_role'] = $row["Id_role"];
            $_SESSION['token'] = $row["token"];
            $_SESSION['inscription_date'] = $row["inscription_date"];

            header("Location: ../index.php");
            exit();
        } else {
            // Mot de passe incorrect
            $message = "<div class='mess_inscription'>Identifiants incorrects.<br><a href='../form/login.php' class='inscription_lien'>Je me connecte</a></div>";
        }
    } else {
        // Message générique pour éviter de fournir des informations trop précises sur l'échec de la connexion.
        $message = "<div class='mess_inscription'>Identifiants incorrects.<br><a href='../form/login.php' class='inscription_lien'>Je me connecte</a></div>";
    }
}

// Reste de la structure HTML avec le message affiché le cas échéant
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/messages.css">
    <style>
        @font-face {
            font-family: 'Cocogoose';
            src: url("../font/Cocogoose\ Pro\ Semilight-trial.ttf") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cocogoose';
            src: url("../font/Cocogoose\ Pro-trial.ttf") format('truetype');
            font-weight: bold;
            font-style: normal;
        }
    </style>
</head>

<body>
    <?php echo $message; ?>
</body>

</html>
