<?php

session_start();
				

$serialNum = (int)$_POST['iserial']; //sets the data type to integer, as it should be the only accepted type
$quantity = (int)($_POST['iquantity']);

if ( filter_var($quantity, FILTER_VALIDATE_INT) == false)  { //Ensure variable is integer
	$_SESSION['TypeError'] = "Input value was not a integer. Please do not mess with the system";
	$quantity = 1;
}

if ( filter_var($serialNum, FILTER_VALIDATE_INT) == false)  { 
	exit("Book's serial number registered as string data type. Please report this error to the company, details in 'Contact Us' tab and the bottom of the website");
}

if ($quantity > 10){
	$quantity = 10;
	$_SESSION['warning'] = "For book serialcode: ".$serialNum.", the quantity is greater than 10. It is considered a bulk order. Please call 6868 3232 to place the order.";
}




if (array_key_exists($serialNum, $_SESSION['cart']) && !isset($_SESSION['warning'])){ //If same serial exists, increase the quantity instead of adding a new key-value

	$value = $_SESSION['cart'][$serialNum]; //get current quantity
	$value += $quantity; 
	if ($value > 10){ //if current quantity exceeds 10, set quantity to 10
		$_SESSION['warning'] = "For book serialcode: ".$serialNum.", the quantity is greater than 10. It is considered a bulk order. Please call 6868 3232 to place the order.";
		$value = 10;
	}
	$_SESSION['cart'][$serialNum] = $value; //increased quantity
}
else{ 
	$_SESSION['cart'][$serialNum] = $quantity;
}



header('Location: ../index.html');  /* Redirect browser */
exit();

?>