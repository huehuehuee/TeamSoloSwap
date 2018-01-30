<?php
require_once('CryptoLib-master/src/CryptoLib.php');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
use IcyApril\CryptoLib;
include('../db.php');

//echo CryptoLib::randomInt(100000000, 999999999);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";

$MAX_RETRIES = 100; //Rerun add user incase duplicate ID generated
$RETRY = 0;

$inputFirstname = $_POST['firstname'];
$inputLastname = $_POST['lastname'];
$inputAddress = $_POST['address'];
$inputEmail = $_POST['email'];
$inputPassword = $_POST['password'];
$inputPasswordConfirm = $_POST['password_confirm'];

//matches for characters only
if (!preg_match("/^([A-Za-z ])+$/", $inputFirstname)) 
{
	$_SESSION['FirstErr'] = "Only letters are allowed for First Name</p>";
}

//matches for characters only
if (!preg_match("/^([A-Za-z ])+$/", $inputLastname)) 
{
	$_SESSION['LastErr'] = "Only letters are allowed for Last Name</p>";
}

//Limit Address to characters, numbers, hashtag and spacing e.g. Blk 123 #10-10 Test Drive 
if (!preg_match("/^([A-Za-z0-9\-# ])+$/", $inputAddress)) 
{
	$_SESSION['AddrErr'] = "Please enter a valid address using characters(a-zA-Z), numbers, hashtag and spacing</p>";
}

//Ensure Email in the format of sample@email.eg
if (!preg_match("/^([A-Za-z0-9 ])+([@])+([a-z])+([.])+([a-z])+$/", $inputEmail)) 
{
	$_SESSION['EmailErr'] = "Please enter a valid email</p>";
}

//Ensure 
if (!strlen($inputPassword) < 8 && !preg_match("/.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/", $inputPassword))
{
	$_SESSION['PwdErr']= "Password complexity not met ";
}

if (strcmp($inputPassword, $inputPasswordConfirm) !== 0)
{
	$_SESSION['PwdMismatch'] = "Passwords do not match, please enter again";
}

if (!strlen($inputPasswordConfirm) < 8 && !preg_match("/.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/", $inputPasswordConfirm))
{
	$_SESSION['PwdErr']= "Password complexity not met ";
}

//If any error was triggered
if (isset($_SESSION['FirstErr']) || isset($_SESSION['LastErr']) || 
isset($_SESSION['AddrErr']) || isset($_SESSION['EmailErr']) || 
isset($_SESSION['PwdErr']) || isset($_SESSION['PwdMismatch']))
{
	header('Location: register.php');  
	exit();
}

do {
	try {
		//Generate random userID
		$randomID = CryptoLib::randomInt(100000000, 999999999);			
		$hashPass = password_hash($inputPassword, PASSWORD_BCRYPT);
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set Error exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO users (id, firstname, lastname, address, email, password) VALUES (:id, :firstname, :lastname, :address, :email, :password)");
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':firstname', $firstname);
		$stmt->bindParam(':lastname', $lastname);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		
		//Define parameters
		$id = $randomID;
		$firstname = $inputFirstname;
		$lastname = $inputLastname;
		$address = $inputAddress;
		$email = $inputEmail;
		$password = $hashPass;
		$stmt->execute();
		
		//Define another set of parameters
		echo "New records created";
		}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
		{
		$RETRY++;
		sleep(1);
		continue;
		}
	break;
}while ($RETRY < $MAX_RETRIES);

$conn = null;



?>