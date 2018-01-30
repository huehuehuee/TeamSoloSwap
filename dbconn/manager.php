<?php

$servername = "localhost";
$username = "management";
$password = "JAeg6gFxZ8tKM5bQ";
$dbname = "bookstore";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set Error exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
