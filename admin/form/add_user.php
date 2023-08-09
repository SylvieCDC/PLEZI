
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
      <!-- FontAwesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/produitsForm.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    

</head>
<body>

<?php
// Inclure le fichier de connexion à la base de données
require_once ("../../config/connx.php");
include_once ('../../src/navbar.php'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);


?>
    

    <form action="../crud/ttt_user.php" method="POST" class="custom__form">
          <h1>Ajouter un utilisateur</h1>

        <div class="form__controls">
          <input type="text" id="nom" name="nom" placeholder="Nom" />
      
          <input type="text" id="prenom" name="prenom" placeholder="Prénom" />
     
          <input type="email" id="email" name="email" placeholder="Email" />
      
          <input type="tel" id="phone" name="telephone" placeholder="Téléphone" pattern="[0-9]{10}"/>
       
          <input type="password" id="pass" name="pass" placeholder="Mot de passe" />
        
          <input type="password" id="passConfirm" name="passConfirm" placeholder="Confirmez Votre Mot de passe" />

        <select name="nom_role" onchange="unselectOptions(this)">
        <?php

        $nomRoleSelected = $_POST['nom_role'];
        $roles = [
          2 => ['Id_role' => 2, 'nom_role' => 'Users'],
          1 => ['Id_role' => 1, 'nom_role' => 'Admin']
          
        ];

        foreach ($roles as $role => $nomRole) {
          $idRole = $nomRole['Id_role'];
          $selected = ($role == $nomRoleSelected) ? 'selected' : '';
          echo "<option value=\"$role\" $selected>{$nomRole['nom_role']}</option>";
        }

        ?>
      </select>

      
          <button type="submit" >AJOUTER</button>

        </div>
    </form>



<script src="../assets/js/nav.js"></script>

<script>
    function unselectOptions(selectElement) {
      const options = selectElement.options;
      for (let i = 0; i < options.length; i++) {
        if (options[i] !== selectElement.selectedOptions[0]) {
          options[i].selected = false;
        }
      }
    }
  </script>
</body>
</html>