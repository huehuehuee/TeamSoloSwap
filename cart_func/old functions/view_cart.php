<!DOCTYPE html>
<html>
<head>
	<title>Mori no Kuni ya</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
	<header id="main-header">
		<div class="container">
			<h1><a href="../index.html">Mori no Kuni ya</a></h1>
		</div>
	</header>

	<nav id="navbar">
		<div class="container">
			<ul>
				<li><a href="../book.html">Fiction</a></li>
				<li><a href="#">Non-Fiction</a></li>
				<li><a href="#">Cooking Books</a></li>
				<li><a href="#">Biography</a></li>
				<li><a href="#">Light Novels</a></li>
			</ul>
		</div>
	</nav>
	<div class="container">
	<p>Best Sellers of 2017 </p>
	</div>
	<section id="showcase">
		<div>
<b><center>Cart View</center></b>
<?php
$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}
session_start();

$cust = $_SESSION['custID'];  //taken from login session
$query=$con->prepare("select `cartID`, `serialNum`, `price`, `quantity`, `amount` from shoppingcart where `custID` = $cust");
$query->execute();
$query->bind_result($cartID, $serialNum, $price, $quantity, $amount);

$books = array();
echo "<table align='center' border='1'>";
echo "<tr>";
echo "<th>serialNum</th>";
echo "<th>price</th>";
echo "<th>quantity</th>";
echo "<th>amount</th>";
echo "</tr>";
while($query->fetch())
{
	array_push($books, $serialNum);
	echo "<tr>";
	echo "<td>".$serialNum."</td>";
	echo "<td>".$price."</td>";
	echo "<td><form action='update_cart.php' method='post'><input type='hidden' name='icart' value='$cartID' /><input type='number' min='0' name='iquantity' value='$quantity'/></td>";
	echo "<td>".$amount."</td>";
	echo "<td><input type='submit' value='update' /></form></td>"; //onClick=\"javascript: return confirm('Please confirm deletion');\" <-- ask for confirmation be4 update
	echo "</tr>";	
	
}
echo "</table>";
$_SESSION['cart_books'] = $books; //tracking the books already in cart.


?>
		</div>
		<form action = "delete_cart.php">
		<input type = "Submit" value = "Empty Cart" onclick="return confirm('Are you sure?')">
		</form>
	</section>

	<div class="container">
		<section id="main">
			<h1>Welcome</h1>
		</section>
		<aside id="sidebar">
			<a href="cart_func/view_cart.php"><IMG SRC = "../images/cart.jpg" WIDTH=45 HEIGHT=50></a>
		</aside>
	</div>

	<footer id="main-footer">
		<p>Copyright &copy; 2017 My Website</p>
	</footer>
</body>
</html>