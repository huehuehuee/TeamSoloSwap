<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>

<?php
session_start();
$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

$iserial = $_POST['iserial'];
$iquantity = $_POST['iquantity'];


$serialNum = $iserial;   //please remember to put ' ; '
$quantity = $iquantity;

if (in_array($serialNum, $_SESSION['cart_books'])) {
    exit("Please update from cart. We apologise for inconvenience caused.");
	
}

$query= $con->prepare("INSERT INTO `shoppingcart`(`cartID`, `custID`, `serialNum`, `price`, `quantity`, `amount`) VALUES 
(NULL, ?, ?, (SELECT `price` FROM `books` WHERE `serialNum` = $serialNum), ?, `quantity`*`price`)");



$query->bind_param('iii', $_SESSION['custID'], $serialNum, $quantity); //bind the parameters     that "ssssss" is string strings string
if ($query->execute()){  //execute query
  echo "Item added to cart";
}else{
  echo "Error executing query.";
}

echo "<br>";
echo "<a href='../index.html'>Homepage</a>";
/*
"INSERT INTO `shoppingcart`(`cartID`, `custID`, `serialNum`, `price`, `quantity`, `amount`) VALUES 
(NULL, ?, ?, (SELECT `price` FROM `books` WHERE `serialNum` = $serialNum), ?, `quantity`*`price`)"

$query= $con->prepare("INSERT INTO `shoppingCart` (`cartID`, `custID`, `serialNum`, `quantity`) VALUES
(NULL, ?,?,?)");
*/

header('Location: ../index.html');  /* Redirect browser */
exit();
?>
</body>
</html>


