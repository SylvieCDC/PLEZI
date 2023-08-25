<?php
require_once __DIR__ . '/../../config/connx.php';


// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}


// Simuler une requête POST
$_POST["nom"] = " ' OR 1=1"; // tentative d'injection SQL
$_POST["prenom"] = "<script>alert('John')</script>"; // tentative de XSS
$_POST["email"] = "john.doe@example.com <script>alert('XSS')</script>"; // tentative de XSS dans l'email
$_POST["phone"] = "01234<script>56789</script>"; // autre tentative de XSS
$_POST["pass"] = "Password1@";
$_POST["passConfirm"] = "Password1@";

$message = '';

if (true) { // Pour forcer l'exécution du bloc
    $nom = trim(strip_tags(htmlspecialchars($_POST["nom"])));
    var_dump($nom);
    $prenom = trim(strip_tags(htmlspecialchars($_POST["prenom"])));
    var_dump($prenom);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    var_dump($email);
    $phone = trim(strip_tags(htmlspecialchars($_POST["phone"])));
    var_dump($phone);
    $password = trim(strip_tags($_POST["pass"]));
    var_dump($password);
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
        // Ici, je vais juste vérifier que l'injection SQL n'a pas fonctionné
        if ($nom !== "' OR '1'='1") {
            $message = "Test réussi: Injection SQL nettoyée.";
        } else {
            $message = "Test échoué: Injection SQL non nettoyée.";
        }
    }
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test d'Inscription</title>
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