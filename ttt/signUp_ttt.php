<?php

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


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs du formulaire
        $nom = htmlspecialchars($_POST["nom"]);
        $prenom = htmlspecialchars($_POST["prenom"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $password = htmlspecialchars($_POST["pass"]);
        $passwordVerif = htmlspecialchars($_POST["passConfirm"]);

        // Le reste de votre code reste inchangé ...
    
        if (isset($nom, $prenom, $email, $password, $phone, $passwordVerif) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($phone) && !empty($password) && !empty($passwordVerif)) {
            // ici on verifie que c'est une vrai adresse mail
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // On vérifie que l'email n'existe pas dans la bdd
                $sql = "SELECT * FROM users WHERE email = :email";
                $stmt = $db->prepare($sql);
                $stmt->execute(['email' => $email]);
                $rowCount = $stmt->rowCount(); // nombre de lignes affectées
                if ($rowCount == 0) {
                    // ici on verifie que les 2 mots de passe sont identiques
                    if ($password == $passwordVerif) {
                        // Hashage du mot de passe
                        $hashed_motdepasse = password_hash($password, PASSWORD_DEFAULT);

                        // Générer un token hexadécimal de 32 octets (256 bits)
                        $token = bin2hex(random_bytes(32));

                        // Insertion des données dans la base de données, y compris le token
                        $inscription = $db->prepare('INSERT INTO users (nom, prenom, telephone, email, pass, token, inscription_date, Id_role) VALUES(:nom, :prenom, :phone, :email, :pass, :token, NOW(), 2)');
                        $inscription->execute(
                            array(
                                ':nom' => $nom,
                                ':prenom' => $prenom,
                                ':phone' => $phone,
                                ':email' => $email,
                                ':pass' => $hashed_motdepasse,
                                ':token' => $token
                            )
                        );

                        echo "<div class='mess_inscription'>Félicitations, votre compte est créé. Vous pouvez vous connecter.<br><br>
                        <a href='../form/login.php' class='inscription_lien'>Je me connecte</a></div>";
                    } else {
                        echo "<div class='mess_inscription'>Les mots de passe ne correspondent pas<br><br>
                        <a href='../form/signUp.php' class='inscription_lien'>Je m'inscris</a>
                        </div>";
                    }
                } else {
                    echo "<div class='mess_inscription'>L'adresse mail existe déjà<br> <br>
                  <a href='../form/login.php' class='inscription_lien'>Je me connecte</a>
                  </div>";
                }
            } else {
                echo "<div class='mess_inscription'>L'adresse mail n'est pas valide<br><br>
            <a href='../form/signUp.php' class='inscription_lien'>Je m'inscris</a></div>";
            }
        } else {
            echo "<div class='mess_inscription'>Merci de remplir tous les champs<br><br>
      <a href='../form/signUp.php' class='inscription_lien'>Je m'inscris</a></div>";
        }
    }

    ?>
</body>

</html>