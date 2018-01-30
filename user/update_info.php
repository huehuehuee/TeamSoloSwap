<?php
session_start();
include('../db.php'); //Subject to CHANGE

//Check if Session variables are set to avoid errors

$inputFirstname = $_SESSION['validFirstname'];
unset($_SESSION['validFirstname']);

$inputLastname = $_SESSION['validLastname'];
unset($_SESSION['validLastname']);

$inputAddress = $_SESSION['validAddress'];
unset($_SESSION['validAddress']);

$MAX_RETRIES = 100; //Rerun add user incase duplicate ID generated
$RETRY = 0;


//PDO
do{ 
	try {
		$stmt = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, address = :address WHERE id = :id");
		$stmt->bindParam(':firstname', $firstname);
		$stmt->bindParam(':lastname', $lastname);
		$stmt->bindParam(':address', $address);
		$stmt->bindParam(':id', $id);
		
		$firstname = $inputFirstname;
		$lastname = $inputLastname;
		$address = $inputAddress;
		$id = $_SESSION['userID'];
		$stmt->execute();
		
		header("Location: /swap_model/user/view_info.php");
		exit();
		}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
		{
		$RETRY++;
		sleep(1);
		continue;
		}
	break;
}while ($RETRY < $MAX_RETRIES);

if (!$RETRY < $MAX_RETRIES)
{
	header("Location: /swap_model/error.php");
	exit();
}	





?>