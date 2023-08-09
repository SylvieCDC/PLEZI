<?php

// ici on définit des constantes d'environnement
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "bdd_plezi");

try {
    // DSN de connexion
    $dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;

    // on se connecte à la base de données
    $options = array(
        PDO::ATTR_EMULATE_PREPARES => false, // Désactive l'émulation des requêtes préparées
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active le mode d'erreur PDOException
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Définit le mode de récupération par défaut en tableau associatif
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" // Définit le jeu de caractères en UTF-8
        
    );

    // on instancie PDO avec les options
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérification de la connexion à la base de données
// if ($db) {
//     echo "Connexion à la base de données réussie !";
// } else {
//     echo "Erreur de connexion à la base de données.";
// }


?>
