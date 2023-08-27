<?php

require_once('../../config/connx.php');

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
    <style>
        @font-face {
            font-family: 'Cocogoose';
            src: url("../../font/Cocogoose\ Pro\ Semilight-trial.ttf") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cocogoose';
            src: url("../../font/Cocogoose\ Pro-trial.ttf") format('truetype');
            font-weight: bold;
            font-style: normal;
        }
    </style>
</head>

<body>


    <?php

    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs du formulaire
        $nom = htmlspecialchars($_POST["nom"]);
        $prenom = htmlspecialchars($_POST["prenom"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["telephone"]);
        $password = htmlspecialchars($_POST["pass"]);
        $passwordVerif = htmlspecialchars($_POST["passConfirm"]);
        $nomRoleSelected = intval($_POST['nom_role']);

        // Récupération de l'ID de catégorie correspondant au nom de catégorie
        $reqRole = 'SELECT Id_role FROM role WHERE Id_role = :id_role';
        $connRole = $db->prepare($reqRole);
        $connRole->bindParam(':id_role', $nomRoleSelected);
        $connRole->execute();
        $rowRole = $connRole->fetch(PDO::FETCH_ASSOC);

        if (!$rowRole) {
            echo "Le rôle spécifiée n'a pas été trouvée.";
            exit;
        }

        $idRole = $rowRole['Id_role'];

        if (!isset($nom, $prenom, $email, $password, $phone, $passwordVerif, $rowRole) || empty($nom) || empty($prenom) || empty($email) || empty($phone) || empty($password) || empty($passwordVerif) || empty($idRole)) {
            echo "<div class='mess_inscription'>Merci de remplir tous les champs<br><br>
            <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<div class='mess_inscription'>L'adresse mail n'est pas valide<br><br>
            <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
        } else {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute(['email' => $email]);
            $rowCount = $stmt->rowCount();

            if ($rowCount > 0) {
                echo "<div class='mess_inscription'>L'adresse mail existe déjà<br> <br>
                <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
            } elseif ($password !== $passwordVerif) {
                echo "<div class='mess_inscription'>Les mots de passe ne correspondent pas<br><br>
                <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
            } elseif (!preg_match($pattern, $password)) {
                echo "<div class='mess_inscription'>Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre, un caractère spécial et avoir une longueur minimale de 8 caractères.<br><br>
                <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
            } else {

                // Hashage du mot de passe
                $hashed_motdepasse = password_hash($password, PASSWORD_DEFAULT);


                // Générer un token hexadécimal de 32 octets (256 bits)
                $token = bin2hex(random_bytes(32));

                // Insertion des données dans la base de données, y 
    
                $reqInsert = 'INSERT INTO users (nom, prenom, telephone, email, pass, token, inscription_date, Id_role) 
                        VALUES(:nom, :prenom, :phone, :email, :pass, :token, NOW(), :id_role)';
                $inscription = $db->prepare($reqInsert);
                $inscription->bindParam(':nom', $nom);
                $inscription->bindParam(':prenom', $prenom);
                $inscription->bindParam(':phone', $phone);
                $inscription->bindParam(':email', $email);
                $inscription->bindParam(':pass', $hashed_motdepasse);
                $inscription->bindParam(':token', $token);
                $inscription->bindParam(':id_role', $idRole);



                if ($inscription->execute()) {
                    echo "<div class='mess_inscription'>Utilisateur crée.<br><br>
                            <a href='gestion_users.php' class='inscription_lien'>Retour</a></div>";
                } else {
                    echo "<div class='mess_inscription'>Erreur lors de l'inscription.<br><br>
                            <a href='../form/add_user.php' class='inscription_lien'>Retour</a></div>";
                }
            }
        }
    }


    ?>
</body>

</html>