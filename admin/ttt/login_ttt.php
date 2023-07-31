<?php
// Démarre la session
session_start();

require_once('../config/connx.php');

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
    <link rel="stylesheet" href="../css/messages.css">
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


    <?php

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs du formulaire
        $email = $_POST["email"];
        $password = $_POST["pass"];

        // Vérification des informations d'identification
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // Utilisateur trouvé dans la base de données
            $row = $stmt->fetch();
            $hashedPassword = $row["pass"];
            $prenom = $row["prenom"];
            $role = $row["Id_role"]; // Récupérer le prénom de l'utilisateur
    
            // Vérifier si le mot de passe correspond au hachage stocké
            if (password_verify($password, $hashedPassword)) {
                // Mot de passe correct, rediriger vers une page de succès
    
                // Stocker le prenom de l'user dans la variable de session
                $_SESSION['prenom'] = $prenom;
                $_SESSION['Id_role'] = $role;


                header("Location: ../../index.php");
                exit();
            } else {
                // Mot de passe incorrect
                echo "<div class='mess_inscription'>Mot de passe incorrect<br><br>
        <a href='../form/login.php' class='inscription_lien'>Je m'inscris</a></div>";
            }
        } else {
            // Utilisateur non trouvé dans la base de données
            echo "<div class='mess_inscription'>Utilisateur non trouvé dans la base de données<br><br>
    <a href='../form/signUp.php' class='inscription_lien'>Je m'inscris</a></div>";
        }
    }
    ?>

</body>

</html>