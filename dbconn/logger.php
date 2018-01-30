<?php

$servername = "localhost";
$username = "logGen";
$password = "NDZlO8LKCtLq1COe";
$dbname = "bookstore";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set Error exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
