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

      // Ajouter le prix unitaire du produit multiplié par la quantité au total
      $total += $product['prix_produit'] * $product_quantity;
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
          <a href="/plezi/index.php" class="logo">
            <img src="/plezi/assets/logo/LOGO_PLEZI_jaune.png" alt="logo PLEZI" />
          </a>

        </div>
      </div>

      <div class="secondary_nav" id="secondary_nav">
        <a href="/plezi/index.php#header"><span class="original">Accueil </span> <span class="traduction">Bèl
            Bonjou</span></a>
        <a href="/plezi/admin/Panier/produits.php"><span class="original">Nos Menus </span><span class="traduction">Ti
            Plézi'w</span></a>
        <a href="/plezi/presentation.php"><span class="original">Notre Histoire </span><span class="traduction">Origin
            nou</span></a>
        <a href="/plezi/index.php#contact"><span class="original">Contact </span><span
            class="traduction">Kontak</span></a>
        <a href="/plezi/admin/Panier/produits.php" class="link">Panier<span class="notif">
            <?php if (empty($ids)) {
              echo "";
            } else {
              echo array_sum($_SESSION['panier']);
            } ?>
          </span>
          <!-- <span class="total"><?= $total ?> €</span> -->
        </a>

        <?php
        // Vérifier si l'utilisateur est connecté
        if ($role == 1) { ?>
          <ul>
            <li class="dropdown secondary_nav">

              <!-- Afficher différents menus déroulants en fonction du rôle de l'utilisateur -->

              <a>Bonjour
                <?php
                echo $prenom; ?>&nbsp;!
              </a>
              <ul class="dropdown-content">
                <li><a href="/plezi/admin/form/add_produit_form.php">Ajouter un produit</a></li>
                <li><a href="/plezi/admin/crud/gestion_produits.php">Gestion des produits</a></li>
                <li><a href="/plezi/admin/crud/gestion_utilisateurs.php">Gestion des utilisateurs</a></li>
                <li><a href="/plezi/admin/crud/gestion_commandes.php">Gestion des commandes</a></li>
                <li><a href="/plezi/admin/ttt/deconnexion.php">Déconnexion</a></li>
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
                <li><a href="/plezi/mon_compte.php">Gestion du Compte</a></li>
                <li><a href="/plezi/mes_commandes.php">Mes commandes</a></li>
                <li><a href="/plezi/admin/ttt/deconnexion.php">Déconnexion</a></li>
              </ul>

            </li>
          </ul>

        <?php } else {

          echo '<a href="/plezi/admin/form/login.php"><i class="fa-solid fa-user"></i></a>';
        }
        ?>

      </div>

      <div class="toggle">
        <button class="mobile_menu_button">
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
      <a href="/plezi/index.php #header"><span class="original">Accueil </span> <span class="traduction">Bèl
          Bonjou</span></a>
      <a href="/plezi/admin/Panier/produits.php"><span class="original">Nos Menus </span><span class="traduction">Ti
          Plézi'w</span></a>
      <a href="/plezi/presentation.php"><span class="original">Notre Histoire </span><span class="traduction">Origin
          nou</span></a>
      <a href="/plezi/index.php #contact"><span class="original">Contact </span><span
          class="traduction">Kontak</span></a>
      <a href="/plezi/admin/Panier/produits.php" class="original">Panier</a>

      <?php
      // Vérifier si l'utilisateur est connecté
      if ($role == 1) { ?>

        <a>Bonjour
          <?php
          echo $prenom; ?>&nbsp;!
        </a>
        <ul class="dropdown-content">
          <li><a href="/plezi/admin/form/add_produit_form.php">Ajouter un produit</a></li>
          <li><a href="/plezi/admin/crud/gestion_produits.php">Gestion des produits</a></li>
          <li><a href="/plezi/admin/crud/gestion_utilisateurs.php">Gestion des utilisateurs</a></li>
          <li><a href="/plezi/admin/crud/gestion_commandes.php">Gestion des commandes</a></li>
          <li><a href="/plezi/admin/ttt/deconnexion.php">Déconnexion</a></li>
        </ul>
      <?php } elseif ($role == 2) { // Utilisateur connecté mais pas admin ?>
        <ul>
          <li class="dropdown secondary_nav">
            <a>Bonjour
              <?php echo $prenom; ?>&nbsp;!
            </a>
            <ul class="dropdown-content">
              <li><a href="/plezi/mon_compte.php">Gestion du Compte</a></li>
              <li><a href="/plezi/mes_commandes.php">Mes commandes</a></li>
              <li><a href="/plezi/admin/ttt/deconnexion.php">Déconnexion</a></li>
            </ul>

          <?php } else {

        echo '<a href="/plezi/admin/form/login.php"><i class="fa-solid fa-user"></i></a>';
      }
      ?>
    </div>
  </div>
</nav>