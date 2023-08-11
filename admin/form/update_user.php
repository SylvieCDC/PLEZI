<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />

  <link rel="stylesheet" href="../css/produitsForm.css" />
  <link rel="stylesheet" href="/assets/css/navbar.css">
  <title>update user</title>
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
  // Vérifier si l'ID du user à modifier a été envoyé via la requête GET
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_user = $_GET['id'];
  }

  // Requête SQL pour récupérer les informations du user à modifier
  $sql = "SELECT * FROM users WHERE Id_user = :id_user";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(":id_user", $id_user);

  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);


  // Récupérer les valeurs du user à partir de la base de données
  $id = $user['Id_user'];
  $nom = $user['nom'];
  $prenom = $user['prenom'];
  $email = $user['email'];
  $phone = $user['telephone'];
  $roleId = $user['Id_role'];

  ?>

  <form class="custom__form" action="../crud/userUpdate.php?id=<?= $id ?>" method="POST">
    <h1>Modifier un utilisateur</h1>

    <input type="hidden" name="Id_user" value="<?php echo $id ?>">
    <br />

    <div class="form__controls">

      <input type="text" name="nom" placeholder="<?= $nom ?>" />
      <input type="text" name="prenom" placeholder="<?= $prenom ?>" />
      <input type="email" name="email" placeholder="<?= $email ?>" />
      <input type="tel" name="telephone" placeholder= "<?= $phone ?>" />
      <select name="nom_role" onchange="unselectOptions(this)">
        <?php

        $nomRoleSelected = $_POST['nom_role'];
        $roles = [
          1 => ['Id_role' => 1, 'nom_role' => 'Admin'],
          2 => ['Id_role' => 2, 'nom_role' => 'User'],
        ];

    

        foreach ($roles as $role => $nomRole) {
          $idRole = $nomRole['Id_role'];
          $selected = ($role == $nomRoleSelected) ? 'selected' : '';
          echo "<option value=\"$role\" $selected>{$nomRole['nom_role']}</option>";
        }

        ?>
      </select>
      <button type="submit">Mise à jour</button>
    </div>
  </form>



</body>

</html>