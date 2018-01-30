
<?php


$con = mysqli_connect("localhost","root","","bookstore"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}


//$serial = $_POST['iserial'];

$query=$con->prepare("select * from `pricingplan`");
$query->execute();
$query->bind_result($serialNum, $costPrice, $markUp, $salePrice);
echo "<table align='center' border='1'>";
echo "<tr>";
echo "<th>serialCode</th>";
echo "<th>costPrice</th>";
echo "<th>markUp %</th>";
echo "<th>salePrice</th>";
echo "<th></th>";
echo "</tr>";
while($query->fetch())
{
	echo "<tr>";
	echo "<td>".$serialNum."</td>";
	echo "<td>".$costPrice."</td>";
	echo "<td><form action='updatePrice.php' method='post'><input type='hidden' name='iserial' value='$serialNum' /><input type='hidden' step='0.01' name='icost' value='$costPrice' /><input type='number' step='0.01' name='imark' value='$markUp' /></form></td>";
	echo "<td>".$salePrice."</td>";
	echo "<td><input type='submit' value='edit' /></td>";
	echo "<td><form action='deletePrice.php' method='post'><input type='hidden' name='iserial' value='$serialNum' /><input type='submit' value='delete' /></form></td>";
	echo "</tr>";	
	
}
echo "</table>";




?>


