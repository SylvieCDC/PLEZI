<?php
require_once __DIR__ . '/../config/connx.php';


// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}



$message = '';

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") 
    {
    $nom = trim(strip_tags(htmlspecialchars($_POST["nom"])));
    $prenom = trim(strip_tags(htmlspecialchars($_POST["prenom"])));
    // $email = trim(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));
    $email = $_POST["email"];
    $phone = trim(strip_tags(htmlspecialchars($_POST["phone"])));
    $password = trim(strip_tags($_POST["pass"]));
    $passwordVerif = trim(strip_tags($_POST["passConfirm"]));

    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    $forbidden_pattern = '/\.(exe|js|php|bat|cmd|sh|py)$/i';

    if (empty($nom) || empty($prenom) || empty($phone) || empty($password) || empty($passwordVerif)) {
        $message = "Merci de remplir tous les champs.";
    } elseif (!preg_match("/^[\p{L}\s]+$/u", $nom) || !preg_match("/^[\p{L}\s]+$/u", $prenom)) {
        $message = "Le nom et le prénom doivent seulement contenir des lettres et des accents.";
    } elseif (!$email) {
        $message = "L'adresse mail n'est pas valide.";
    } elseif ($password !== $passwordVerif) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (preg_match($forbidden_pattern, $password)) {
        $message = "Votre mot de passe contient des caractères ou des motifs non autorisés.";
    } elseif (!preg_match($pattern, $password)) {
        $message = "Le mot de passe doit comporter au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $message = "L'adresse mail existe déjà.";
        } else {
            $hashed_motdepasse = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));

            $inscription = $db->prepare('INSERT INTO users (nom, prenom, telephone, email, pass, token, inscription_date, Id_role) VALUES(:nom, :prenom, :phone, :email, :pass, :token, NOW(), 2)');
            $inscription->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':phone' => $phone,
                ':email' => $email,
                ':pass' => $hashed_motdepasse,
                ':token' => $token
            ]);

            $message = "Félicitations, votre compte est créé.";
        }
    }
} else {
    $message = "Veuillez accéder à cette page via le formulaire d'inscription.";
}

$message = "<div class='mess_inscription'>$message<br><a href='../form/login.php' class='inscription_lien'>Vous pouvez vous connecter</a></div>";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte</title>
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