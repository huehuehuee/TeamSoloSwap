<html>
<body>
<?php
$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
	}
session_start();

$ids = join(",", array_keys($_SESSION['cart'])); //Get all the keys (serial numbers) in Cart Session, save it as a string with a comma between each key

$tempCart = $_SESSION['cart']; //duplicate cart for usage instead of calling for session directly
$serialNumbers = "'" . str_replace(",", "','", $ids) . "'"; //append '' over each key (serial number), and 

$query=$con->prepare("SELECT books.serialNum, books.title, pricingplan.salePrice FROM books INNER JOIN pricingplan ON books.serialNum=pricingplan.serialNum WHERE books.serialNum IN ($serialNumbers)");
$query->execute();
$query->bind_result($serialNum, $title, $price);

if (isset($_SESSION['warning']))  //Errors message for quantity inputs totaling more than 10
{
	echo "<h1>".$_SESSION['warning']."<h1>";
	unset ($_SESSION['warning']);
}

if (isset($_SESSION['TypeError'])) //Intentional misconfiguration? by user detected. Wrong data type recevied.
{
	echo "<h1>".$_SESSION['TypeError']."<h1>";
	unset ($_SESSION['TypeError']);
}

echo "<table align='center' border='1'>";
echo "<tr>";
echo "<th>serialNum</th>";
echo "<th>title</th>";
echo "<th>quantity</th>";
echo "<th>amount</th>";
echo "</tr>";

while($query->fetch())
{
	echo "<tr>";
	echo "<td>".$serialNum."</td>";
	echo "<td>".$title."</td>";
	echo "<td><form action='update_cart.php' method='post'><input type='hidden' name='iserial' value=$serialNum /><input type='number' min='0' name='iquantity' value='$tempCart[$serialNum]'/></td>";
	$total = $tempCart[$serialNum] * $price; //quantity multiply salePrice
	echo "<td>$total</td>";
	echo "<td><input type='submit' value='update' /></form></td>"; 
	echo "</tr>";	
	
}

echo "</table>";


?>
	<form action = "empty_cart.php">
	<input type = "Submit" value = "Empty Cart" onclick="return confirm('Are you sure?')">
	</form>

</body>
</html>