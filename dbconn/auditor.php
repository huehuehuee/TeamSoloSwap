<?php

$servername = "localhost";
$username = "auditor";
$password = "PAzX1SNwIyLclD2t";
$dbname = "bookstore";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set Error exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
