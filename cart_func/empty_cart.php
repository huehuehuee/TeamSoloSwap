<?php
session_start();

$_SESSION['cart'] = array(); 

header('Location: view_cart.php');  /* Redirect browser */
exit();
?>