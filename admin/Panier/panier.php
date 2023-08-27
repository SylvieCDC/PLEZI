<?php


// Inclure le fichier de connexion à la base de données
require_once("../../config/connx.php");

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Vérifier si le panier est vide ou non initialisé, puis initialiser le panier
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

// Calculer le total des produits dans le panier
$total = 0;
$ids = array_keys($_SESSION['panier']);

// Vérifier s'il y a des produits dans le panier
if (!empty($ids)) {
    // Utiliser une requête préparée pour récupérer les produits du panier
    $inClause = implode(',', array_fill(0, count($ids), '?'));
    $query = "SELECT id, titre_produit, prix_produit, image_produit, enonce_produit, id_categorie FROM produits WHERE id IN ($inClause)";
    $stmt = $db->prepare($query);

    // Exécuter la requête en liant les valeurs des clés $ids à la requête préparée
    if ($stmt->execute($ids)) {
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculer le total des produits dans le panier
        foreach ($products as $product) {
            $product_id = $product['id'];
            $product_quantity = $_SESSION['panier'][$product_id];

            // Ajouter le prix unitaire du produit multiplié par la quantité au total
            $total += $product['prix_produit'] * $product_quantity;
        }
    } else {
        // Handle the error appropriately (e.g., log the error or display a user-friendly message)
        die("Error fetching products from the database.");
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma commande Plézi</title>
    <link rel="stylesheet" href="test.css">
    <link rel="stylesheet" href="../../assets/css/navbar.css">
    <link rel="stylesheet" href="../../assets/css/footer.css">
    <!-- Icon sur onglet = favicon -->
    <link rel="icon" href="../../assets/logo/LOGO_PLEZI_jaune.png" type="image/x-icon" />
    <link rel="apple_icon" href="assets/logo/LOGO_PLEZI_jaune.png" />

    <!-- FontAwesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .transparente {
            color: transparent;
        }
    </style>
</head>

<body>

    <?php

    // Inclure la navBar
    include_once "../../src/navbar.php";

    ?>

    <section class="display_none">
        <?php

        // Récupérer les catégories depuis la base de données
        $req = $db->query("SELECT * FROM categories");
        $categories = $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categories as $cat) {
            $idCat = $cat['Id_categorie']; ?>
            <div class="categorie_title">
                <h2>
                    <?= $cat['nom_categorie'] ?>
                </h2>
            </div>

            <?php
            if ($idCat === 1) {
                // Afficher la liste des produits
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 1");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';

                }
            }

            if ($idCat === 2) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 2");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

            if ($idCat === 3) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 3");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

            if ($idCat === 4) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 4");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

            if ($idCat === 5) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 5");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

            if ($idCat === 6) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 6");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

            if ($idCat === 7) {
                $stmt = $db->query("SELECT id, titre_produit, prix_produit, image_produit, enonce_produit,  id_categorie FROM produits WHERE Id_categorie = 7");
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($products as $row) {
                    include 'card_panier.php';
                }

            }

        }
        ?>


    </section>


    <section class="panier_small_screen">

        <?php
        include_once('load_cart_sidebar.php');
        ?>


    </section>



    <hr>



    <?php
    // Inclure le footer et js
    include_once "../../src/footer.php";

    ?>
    <script src="../../assets/js/nav.js"></script>




    <!-- Script ajax pour supprimer tout dans le panier d'un coup -->
    <script>
        function removeAllFromCart() {
            // Create a new XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Set up the request
            xhr.open('POST', 'remove_all_from_cart.php', true);

            // Set the request header (optional, but good practice)
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Handle the response from the server
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // The cart was cleared successfully
                        // Reload the page to reflect the changes
                        window.location.reload();
                    } else {
                        // There was an error clearing the cart
                        // Handle the error appropriately (e.g., display an error message)
                    }
                }
            };

            // Send the request
            xhr.send();
        }

        function updateQuantity(productId, newQuantity) {
            fetch(`update_quantity.php?id=${productId}`, {
                method: 'POST',
                body: new URLSearchParams({ quantity: newQuantity }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to update quantity.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Mets à jour le total et les quantités sur la page
                    const totalElement = document.querySelector('.total div');
                    totalElement.textContent = `Total : ${data.total} €`;
                })
                .catch(error => {
                    console.error(error);
                });
        }

    </script>


</body>

</html>