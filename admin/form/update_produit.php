<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <!-- Icon sur onglet = favicon -->
  <link rel="icon" href="/assets/logo/LOGO_PLEZI_jaune.png" type="image/x-icon" />
  <link rel="apple_icon" href="/assets/logo/LOGO_PLEZI_jaune.png" />

  <link rel="stylesheet" href="../css/produitsForm.css" />
  <link rel="stylesheet" href="/assets/css/navbar.css">
  <title>Modifier Produit</title>
</head>

<body>

  <?php
  // Inclure le fichier de connexion à la base de données
  require_once("../../config/connx.php");
  include_once('../../src/navbar.php');
  if (!$db) {
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
  }

  if (!isset($_SESSION['Id_role']) || $_SESSION['Id_role'] != 1) {
    // Redirigez vers une page d'erreur ou une autre page
    header('Location: /index.php');
    exit;
  }

  // Vérifier si l'ID du produit à modifier a été envoyé via la requête GET
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_produit = $_GET['id'];
  }
  // Requête SQL pour récupérer les informations du produit à modifier
  $sql = "SELECT * FROM produits WHERE id = :id_produit";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(":id_produit", $id_produit);

  $stmt->execute();
  $produit = $stmt->fetch(PDO::FETCH_ASSOC);


  // Récupérer les valeurs du produit à partir de la base de données
  $titre_produit = $produit['titre_produit'];
  $id_categorie = $produit['Id_categorie'];
  $enonce_produit = $produit['enonce_produit'];
  $prix_produit = $produit['prix_produit'];
  $image_produit = $produit['image_produit'];

  ?>

  <form class="custom__form" action="../crud/newUpdate.php?id=<?= $_GET['id'] ?>" method="POST"
    enctype="multipart/form-data">
    <h1>Modifier un produit</h1>


    <!-- <p>Changer une image</p> et l'afficher -->

    <div class=" fileClasse " id="drop_category_logo">
      <div class="fileSousClasse ">Choisir image ou Glisser ici</div>
      <input type='file' name="image_produit" class="fileClasseInput">
    </div>

    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <br />

    <div class="form__controls">

      <input type="text" name="titre_produit" placeholder="<?= $titre_produit ?>" />
      <select name="nom_categorie" onchange="unselectOptions(this)">
        <?php

        $nomCatSelected = $_POST['nom_categorie'];
        $categories = [
          1 => ['Id_categorie' => 1, 'nom_categorie' => 'Starters'],
          2 => ['Id_categorie' => 2, 'nom_categorie' => 'Bokits'],
          3 => ['Id_categorie' => 3, 'nom_categorie' => 'Bowls'],
          4 => ['Id_categorie' => 4, 'nom_categorie' => 'Salades'],
          5 => ['Id_categorie' => 5, 'nom_categorie' => 'Sauces'],
          6 => ['Id_categorie' => 6, 'nom_categorie' => 'Desserts'],
          7 => ['Id_categorie' => 7, 'nom_categorie' => 'Boissons']
        ];

        foreach ($categories as $categorie => $nomCat) {
          $idCategorie = $nomCat['Id_categorie'];
          $selected = ($categorie == $nomCatSelected) ? 'selected' : '';
          echo "<option value=\"$categorie\" $selected>{$nomCat['nom_categorie']}</option>";
        }

        ?>
      </select>

      <textarea name="enonce_produit" placeholder="<?= $enonce_produit ?>"></textarea>
      <input type="text" name="prix_produit" placeholder="<?= $prix_produit ?>" />
      <button type="submit">Mise à jour</button>
    </div>
  </form>
  <div id="custom__print-files"></div>

  <script src="../../assets/js/nav.js"></script>
  <script>
    function unselectOptions(selectElement) {
      const options = selectElement.options;
      for (let i = 0; i < options.length; i++) {
        if (options[i] !== selectElement.selectedOptions[0]) {
          options[i].selected = false;
        }
      }
    }

    // pour afficher l'image sélectionnée 
    const categoryLogoDropper = document.getElementById("drop_category_logo");

    categoryLogoDropper.addEventListener("change", () => {
      console.log(window.URL.createObjectURL(categoryLogoDropper.querySelector("input").files[0])),
        categoryLogoDropper.style.backgroundImage = "url(" + window.URL.createObjectURL(categoryLogoDropper.querySelector("input").files[0]) + ")",
        console.log(categoryLogoDropper.style);
    })
  </script>

</body>

</html>