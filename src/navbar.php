<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Vérifier si la connexion à la base de données a été établie
if (!$db) {
  die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Set a default value if 'prenom' is not set
$prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '';

// Initialiser la variable $role avec une valeur par défaut (0) si la clé 'role' n'est pas présente dans $_SESSION
$role = isset($_SESSION['Id_role']) ? $_SESSION['Id_role'] : 0;



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
  $query = "SELECT * FROM produits WHERE id IN ($inClause)";
  $stmt = $db->prepare($query);
  // Exécuter la requête en liant les valeurs des clés $ids à la requête préparée
  if ($stmt->execute($ids)) {
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculer le total des produits dans le panier
    foreach ($products as $product) {
      $product_id = $product['id'];
      $product_quantity = $_SESSION['panier'][$product_id];

      // Convertir le prix du produit en entier
      $prix_produit = intval($product['prix_produit']);

      // Ajouter le prix unitaire du produit multiplié par la quantité au total
      $total += $prix_produit * $product_quantity;
    }
  } else {
    // Handle the error appropriately (e.g., log the error or display a user-friendly message)
    die("Error fetching products from the database.");
  }
}

?>

<nav>
  <div class="navbar" id="navbar">
    <div class="flex">
      <div class="left_menu">
        <!-- logo -->
        <div class="logo_content">
          <a href="/index.php" class="logo">
            <img src="/assets/logo/LOGO_PLEZI_jaune.png" alt="logo PLEZI" />
          </a>

        </div>
      </div>

      <div class="secondary_nav" id="secondary_nav">
        <a href="/Accueil"><span class="original">Accueil </span> <span class="traduction">Bèl
            Bonjou</span></a>
        <ul>
          <li class="dropdown secondary_nav">

            <!-- Afficher différents menus déroulants en fonction du rôle de l'utilisateur -->

            <a href="/admin/Panier/produits.php"><span class="original">Nos Menus </span><span class="traduction">Ti
                Plézi'w</span></a>
            <ul class="dropdown-content">
              <li><a href="/admin/Panier/produits.php">Tous nos menus</a></li>
              <li>
                <?php
                // Récupérer les catégories depuis la base de données
                $req = $db->query("SELECT * FROM categories");
                $categories = $req->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $cat) {
                  $idCat = $cat['Id_categorie'];
                  $nomCategorie = $cat['nom_categorie'];
                  // Exclure la catégorie avec id_categorie = 5
                  if ($idCat === 5) {
                    continue;
                  }
                  ?>
                  <a href="/admin/Panier/produits.php#<?= $nomCategorie ?>">
                    <?= $nomCategorie ?>
                  </a>
                <?php } ?>
              </li>
            </ul>
          </li>
        </ul>
        <a href="/presentation.php"><span class="original">Notre Histoire </span><span class="traduction">Origin
            nou</span></a>
        <a href="/index.php#contact"><span class="original">Contact </span><span class="traduction">Kontak</span></a>
        <a href="/admin/Panier/panier.php" class="link">Panier </a>

        <?php
        // Vérifier si l'utilisateur est connecté
        if ($role == 1) { ?>
          <ul>
            <li class="dropdown secondary_nav">

              <!-- Afficher différents menus déroulants en fonction du rôle de l'utilisateur -->

              <div class="xxx">Bonjour
                <?php
                echo $prenom; ?>&nbsp;!
              </div>
              <ul class="dropdown-content">
                <li><a href="/admin/form/add_produit_form.php">Ajouter un produit</a></li>
                <li><a href="/admin/crud/gestion_produits.php">Gestion des produits</a></li>
                <li><a href="/admin/crud/gestion_users.php">Gestion des utilisateurs</a></li>
                <li><a href="/ttt/deconnexion.php">Déconnexion</a></li>
              </ul>
            </li>
          </ul>
        <?php } elseif ($role == 2) { // Utilisateur connecté mais pas admin ?>
          <ul>
            <li class="dropdown secondary_nav">
              <a>Bonjour
                <?php echo $prenom; ?>&nbsp;!
              </a>
              <ul class="dropdown-content">
                <li><a href="/../admin/form/account.php">Gestion du Compte</a></li>
                <li><a href="/ttt/deconnexion.php">Déconnexion</a></li>
              </ul>

            </li>
          </ul>

        <?php } else {

          echo '<a href="/form/login.php"><i class="fa-solid fa-user"></i></a>';
        }
        ?>

      </div>

      <div class="toggle">
        <button id='clickToggle' class="mobile_menu_button">
          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
            <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <style>
              svg {
                fill: #fcb045;
              }
            </style>
            <path
              d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" />
          </svg>
        </button>
      </div>
    </div>

    <div class="mobile_menu hidden">
      <?php
      // Vérifier si l'utilisateur est connecté
      if ($role === 1) {
        ?> <a class="dropdown-content-mobile"><?php  echo "<a>Bonjour $prenom&nbsp;!</a>";?></a><?php
       ?><ul class="dropdown-content-mobile"><?php
       ?><li><a href="/admin/form/add_produit_form.php">Ajouter un produit</a></li><?php
       ?><li><a href="/admin/crud/gestion_produits.php">Gestion des produits</a></li><?php
       ?><li><a href="/admin/crud/gestion_users.php">Gestion des utilisateurs</a></li><?php
       ?><li><a href="/ttt/deconnexion.php">Déconnexion</a></li>';
       ?></ul><?php
      } elseif ($role === 2) {
      ?><a><?php  echo "<a>Bonjour $prenom&nbsp;!</a>";?></a><?php
       ?><ul class="dropdown-content-mobile"><?php
       ?><li><a href="/admin/form/account.php">Gestion du Compte</a></li><?php
       ?><li><a href="/ttt/deconnexion.php">Déconnexion</a></li><?php
       ?></ul><?php
      } else {
       ?><a href="/form/login.php"><i class="fa-solid fa-user"></i></a><?php
      }
      ?>
      <a href="/Accueil"><span class="original">Accueil </span><span class="traduction">Bèl
          Bonjou</span></a>
      <a href="/admin/Panier/produits.php"><span class="original">Nos Menus </span><span class="traduction">Ti
          Plézi'w</span></a>
      <a href="/presentation.php"><span class="original">Notre Histoire </span><span class="traduction">Origin
          nou</span></a>
      <a href="/index.php#contact"><span class="original">Contact </span><span class="traduction">Kontak</span></a>
      <a href="/admin/Panier/panier.php" class="original">Panier</a>

    </div>
  </div>

</nav>