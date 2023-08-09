
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
      <!-- FontAwesome  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/parallax.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    

</head>
<body>

<?php
// Inclure le fichier de connexion à la base de données
require_once ("../config/connx.php");
include_once ('../src/navbar.php'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);


?>
    

    <section class="section-3" id="contact">
    <div class="contact_form">
      <h2>Créer un compte</h2>
      <form action="../ttt/signUp_ttt.php" method="post">

        <div>
          <input type="text" id="nom" name="nom" placeholder="Nom" />
        </div>
        <div>
          <input type="text" id="prenom" name="prenom" placeholder="Prénom" />
        </div>
        <div>
          <input type="email" id="email" name="email" placeholder="Email" />
        </div>
        <div>
          <input type="tel" id="phone" name="phone" placeholder="Téléphone" pattern="[0-9]{10}"/>
        </div>
        <div>
          <input type="password" id="pass" name="pass" placeholder="Mot de passe" />
        </div>
        <div>
          <input type="password" id="passConfirm" name="passConfirm" placeholder="Confirmez Votre Mot de passe" />
        </div>

        <div class="submit_contact">
          <input type="submit" value="ENVOYER" />
        </div>
      </form>
      <div class="forgot">
        <p><a href="login.php">Vous avez déjà un compte ? Se connecter</a></p>
      </div>
    </div>
  </section>


<script src="/assets/js/nav.js"></script>
</body>
</html>