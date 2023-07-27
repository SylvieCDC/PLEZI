<?php
// Start or resume the session
session_start();

// Empty the cart by resetting the $_SESSION['panier'] array
$_SESSION['panier'] = array();

// Respond with a status HTTP 200 to indicate success
http_response_code(200);
?>
