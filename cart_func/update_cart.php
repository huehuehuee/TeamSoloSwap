<?php
session_start();

$serial = (int)$_POST['iserial']; //Convert data type to integer
$newQuantity = (int)$_POST['iquantity'];

if ( filter_var($newQuantity, FILTER_VALIDATE_INT) == false && $newQuantity !== 0 )  { //Ensure variable successfully converted to integer
	$_SESSION['TypeError'] = "Input value was not a integer. Please do not mess with the system";
	$quantity = 1;
}

if ( filter_var($serial, FILTER_VALIDATE_INT) == false)  { 
	exit("Book's serial number registered as string data type. Please report this error to the company, details in 'Contact Us' tab and the bottom of the website");
}


if ($newQuantity == 0)
{
	unset($_SESSION['cart'][$serial]);
}

else if ($newQuantity >= 11)
{
	$_SESSION['warning'] = "For quantities above 10 for each book, it is considered a bulk order. Please call 6868 3232 to place the order.";
	$_SESSION['cart'][$serial] = 10;
	header('Location: view_cart.php');  /* Redirect browser */
	exit();
}

else
{
	$_SESSION['cart'][$serial] = $newQuantity;
}

header('Location: view_cart.php');  /* Redirect browser */
exit();



?>