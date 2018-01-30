<?php
$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

$serialNum = $_POST['iserial'];
$costPrice = $_POST['icost'];
$markUp = $_POST['imark']; //percentage value

$markCalculate = $markUp + 100; //add in the base 100% to x% of markup

$salePrice = $costPrice * ($markCalculate / 100);

echo 'heelo there <br>';

echo $serialNum.'<br>';
echo $costPrice.'<br>';
echo $markUp.'<br>';
echo $salePrice.'<br>';



$query= $con->prepare("INSERT INTO `pricingplan` (`serialNum`, `costPrice`,`markUp`, `salePrice`) VALUES
(?,?,?,?)");

$query->bind_param('iddd', $serialNum, $costPrice, $markUp, $salePrice); //bind the parameters
if ($query->execute()){  //execute query
  echo "Query executed.";
}else{
  echo "Error executing query.";
}



?>