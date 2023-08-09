<?php
session_start();

// Vérifier si l'ID du produit à supprimer a été envoyé via la requête GET
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Vérifier si le produit existe dans le panier (dans la session)
    if (isset($_SESSION['panier'][$product_id])) {
        // Vérifier la quantité du produit dans le panier
        if ($_SESSION['panier'][$product_id] > 1) {
            // Décrémenter la quantité du produit
            $_SESSION['panier'][$product_id]--;

        } else {
            // Si la quantité est égale à 1, supprimer complètement le produit du panier en utilisant unset()
            unset($_SESSION['panier'][$product_id]);
        }
    }
    
}


// Vérifier le referer pour déterminer d'où vient l'utilisateur
$referer = $_SERVER['HTTP_REFERER'];

if (strpos($referer, "produits.php") !== false) {
    // Rediriger l'utilisateur vers produits.php
    header('Location: produits.php');
} else {
    // Rediriger l'utilisateur vers panier.php
    header('Location: panier.php');
}
exit();  // Toujours mettre un exit() après un header de redirection pour arrêter le script

// Si basename($_SERVER['PHP_SELF']) ne fonctionne pas comme prévu, essayons une autre approche en utilisant la variable superglobale $_SERVER['HTTP_REFERER'] pour déterminer la page d'origine de l'utilisateur. Cependant, gardez à l'esprit que cette méthode n'est pas toujours fiable à 100 %, car tous les navigateurs ne définissent pas toujours cette valeur.

?>