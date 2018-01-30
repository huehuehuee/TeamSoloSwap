<html>
<body>  
<?php

$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

$quantity = $_POST['iquantity'];
$cart = $_POST['icart'];
//if ($quantity == 0) do a delete

if ($quantity == 0){
	$query= $con->prepare("DELETE FROM `shoppingCart` WHERE `cartID` = ?");

	//please remember to put ' ; '
	$query->bind_param('i', $cart); //bind the parameters     that "ssssss" is string strings string
	$query->execute();  //execute query
	header('Location: view_cart.php');  /* Redirect browser */
	exit();
	
}

$query= $con->prepare("UPDATE `shoppingCart` SET `quantity` = ? WHERE `cartID` = ?");

//please remember to put ' ; '


$query->bind_param('ii', $quantity, $cart); //bind the parameters    
if ($query->execute()){  //execute query
  echo "Query executed.";
}else{
  echo "Error executing query.";
}
$query= $con->prepare("UPDATE `shoppingCart` SET `amount` = `quantity`*`price` WHERE `cartID` = ?");
$query->bind_param('i', $cart);

if ($query->execute()){  //execute query
  echo "Price updated.";
}else{
  echo "Error in price update.";
}

header('Location: view_cart.php');  /* Redirect browser */
exit();
?>
</body>
</html>