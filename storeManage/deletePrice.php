<html>
<body>  
<?php

$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}



$query= $con->prepare("DELETE FROM `pricingplan` WHERE `serialNum` = ?");

//please remember to put ' ; '
$serialNum = $_POST['iserial'];


$query->bind_param('i', $serialNum); //bind the parameters     that "ssssss" is string strings string
if ($query->execute()){  //execute query
  echo "Query executed.";

}else{
  echo "Error executing query.";
}

header('Location: viewPrice.php');  /* Redirect browser */
exit();
?>
</body>
</html>