<?php
session_start();

require_once '../../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51NXfGuEngKRgzXPTdYJwSElWj5verJiUON1ntMzwoAofv61eJv85AjeociWpMnZeW7ibSYOwOiOVM6kjypI42G4T00nKwqu6VC');

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Récupérez les détails de la session pour confirmer le paiement
    $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($checkout_session && $checkout_session->payment_status == 'paid') {
        // Le paiement a été réussi, videz le panier
        unset($_SESSION['panier']);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Thanks for your order!</title>
  <link rel="stylesheet" href="../../assets/css/messages.css">
  <!-- Redirect to index.php after a delay of 3 seconds -->
  <meta http-equiv="refresh" content="3;url=../../../../index.php">
</head>
<body>
<section class='mess_inscription'> 
    <p>Merci pour votre commande ! <br> <a href='/index.php'>Accueil</a></p>
</section>

<?php 
    } else {
        // Gérez les autres statuts de paiement ici (par exemple, `unpaid`, `canceled`, etc.)
?>
<section class='mess_inscription'> 
    <p>Il y a une erreur avec la transaction</p>
</section> 
</body>
</html>

<?php
    }
}
?>
