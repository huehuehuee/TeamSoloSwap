<?php
$servername = "localhost";
$username = "otp";
$password = "4scT6eK9BOX7Ck4P";
$dbname = "bookstore";
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set Error exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>