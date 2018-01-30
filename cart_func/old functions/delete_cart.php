<html>
<body>  
<?php
session_start();
$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}



$query= $con->prepare("DELETE FROM `shoppingCart` WHERE `custID` = ?");

//please remember to put ' ; '
$cust = $_SESSION['custID'];


$query->bind_param('i', $cust); //bind the parameters     that "ssssss" is string strings string
if ($query->execute()){  //execute query
  echo "Query executed.";

}else{
  echo "Error executing query.";
}

header('Location: view_cart.php');  /* Redirect browser */
exit();
?>
</body>
</html>