<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
  <!-- Icon sur onglet = favicon -->
  <link rel="icon" href="/assets/logo/LOGO_PLEZI_jaune.png" type="image/x-icon" />
  <link rel="apple_icon" href="/assets/logo/LOGO_PLEZI_jaune.png" />
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
  require_once("../config/connx.php");

  include_once('../src/navbar.php');

  ?>


  <section class="section-3" id="contact">
    <div class="contact_form">
      <h2>Connexion</h2>
      <form action="../ttt/login_ttt.php" method="post">

        <div>
          <input type="email" id="email" name="email" placeholder="Email" />
        </div>
        <div>
          <input type="password" id="pass" name="pass" placeholder="Mot de passe" />
        </div>

        <div class="submit_contact">
          <input type="submit" value="ENVOYER" />
        </div>
      </form>
      <div class="forgot">
        <!-- <p><a href="forgotPwd.php"> Mot de passe oublié ?</a></p> -->
        <p><a href="signUp.php"> Créer un compte</a></p>
      </div>
    </div>
  </section>


  <script src="/assets/js/nav.js"></script>
</body>

</html>