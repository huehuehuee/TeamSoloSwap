<?php


$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

echo "Update Page";
echo "</p>";

$serialNum = $_POST['iserial'];
$costPrice = $_POST['icost'];
$markUp = $_POST['imark']; //new markUp value

$markCalculate = $markUp + 100; //add in the base 100% to x% of markup
$salePrice = $costPrice * ($markCalculate / 100);


$update= $con->prepare("UPDATE `pricingplan` SET `markUp` = ?, `salePrice` = ? WHERE `serialNum` = ?");


$update->bind_param('ddi', $markUp, $salePrice, $serialNum); //bind the parameters     s=string i=integer d=double 
if ($update->execute()){  //execute query
  echo "Query executed.";
}else{
  echo "Error executing query.";
}

header('Location: viewPrice.php');  /* Redirect browser */
exit();
?>

