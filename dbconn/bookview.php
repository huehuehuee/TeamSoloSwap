<?php
$servername = "localhost";
$username = "bookview";
$password = "zah6pgrQzkNg2Tss";
$dbname = "bookstore";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set Error exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
