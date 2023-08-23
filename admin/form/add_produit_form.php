<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <!-- Icon sur onglet = favicon -->
  <link rel="icon" href="/assets/logo/LOGO_PLEZI_jaune.png" type="image/x-icon" />
  <link rel="apple_icon" href="/assets/logo/LOGO_PLEZI_jaune.png" />

  <link rel="stylesheet" href="../css/produitsForm.css" />
  <link rel="stylesheet" href="/assets/css/navbar.css">
  <title>Image input</title>
</head>


<body>

  <?php
  require_once('../../ttt/middleware.php');
  // Inclure le fichier de connexion à la base de données
  require_once("../../config/connx.php");
  include_once('../../src/navbar.php');

  if (!isset($_SESSION['Id_role']) || $_SESSION['Id_role'] != 1) {
    // Redirigez vers une page d'erreur ou une autre page
    header('Location: /index.php');
    exit;
  }

  ?>




  <form class="custom__form" action="../crud/traitement.php" method="POST" enctype="multipart/form-data">
    <h1>Ajouter des produits</h1>
    <!-- <p>Ajoutez une image</p> -->

    <div class=" fileClasse " id="drop_category_logo">
      <div class="fileSousClasse ">Choisir image ou Glisser ici</div>
      <input type='file' name="image_produit" class="fileClasseInput">
    </div>

    <div class="form__controls">
      <input type="text" name="titre_produit" placeholder="Nom du produit" />
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

      <textarea name="enonce_produit" placeholder="Description du produit"></textarea>
      <input type="text" name="prix_produit" placeholder="Prix" />
      <button type="submit">AJOUTER</button>
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