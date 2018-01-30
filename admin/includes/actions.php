<?php
session_start();
include __DIR__."/functions.php";
include __DIR__."/validate.php";
include __DIR__."/cleanData.php";


$operation = cleanString($_POST['ioperation']);
$user = cleanString($_POST['iusername']);
$pass = cleanString($_POST['ipassword']);
$passconfirm = cleanString($_POST['ipasswordconfirm']);
$email = cleanString($_POST['iemail']);
$name = cleanString($_POST['iname']);
$role = cleanString($_POST['irole']);
$status = cleanString($_POST['istatus']);
$otp = cleanInt($_POST['iotp']);
$auditor_id = cleanInt($_SESSION['userID']); 
$logID = cleanInt($_POST['ilogID']);
$comment = cleanString($_POST['icomment']);

if(validateOps($operation)) //If error generated, redirect
{
	$_SESSION['Error'] = "An Invalid Operation was almost called. Good thing we caught it right :D";
	header("Location: http://127.0.0.1/swap_model/error.php");
	exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'create') //Logged
{
	if(validateUsers($user) && validatePwd($pass) && validatePwd($passconfirm) && validateEmail($email) && validateName($name) && validateRole($role)  && validateStatus($status))
	{
		if($pass === $passconfirm)
		{
			addstaff($user, $pass, $email, $name, $role, $status);
		}
		else
		{
			$_SESSION['staffregister'] = "Invalid inputs. Password mistmatch";
			header("Location: ../admin.php");
			exit();
		}
	}
	else
	{
		header("Location: ../admin.php");
		exit();
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'update') //Logged
{
	if(validateUsers($user) && validateStatus($status))
	{	
		updatestatus($user, $status);
	}
	else
	{
		$_SESSION['staffregister'] = "Invalid inputs. Try again";
		header("Location: ../admin.php");
		exit();
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'password') //Logged
{
	if(validateUsers($user) && validatePwd($pass) && validatePwd($passconfirm))
	{	
		if($pass === $passconfirm)
		{
			updatepwd($user, $pass);
		}
		else
		{
			$_SESSION['staffregister'] = "Invalid inputs. User or password mistmatch";
			header("Location: ../admin.php");
			exit();
		}
	}
	
	else
	{
		$_SESSION['staffregister'] = "Invalid inputs. User or password mistmatch";
		header("Location: ../admin.php");
		exit();
	}
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'login') //Logged
{
	
	$recaptcha_secret = "6Leb3kEUAAAAAATPRYO8LC18mci-jD7wNCeLf2fe";
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);
	$response = json_decode($response, true);
	if($response["success"] === true && validateUsers($user) && validatePwd($pass))
	{
		loginOTP($user, $pass);
		
	}
	else
	{
		$_SESSION['LoginError'] = "Invalid Credentials. ";
		header("Location: ../login.php");
		exit();
	}
	
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'otp') //Logged
{
	$_SESSION['otp_try'] ++;
	if( validateID($otp) && $_SESSION['otp_try'] < 4)
	{		
		loginuser($otp);		
	}
	else
	{
		unset($_SESSION['otp_try']);
		expireOTP();
		unset($_SESSION['username']);
		unset($_SESSION['uniqueID']);
		$_SESSION['LoginError'] = "Invalid Credentials. ";
		header("Location: ../login.php");
		exit();
	}
	
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'comment') //Logged
{
	if(validateID($logID) && validateComment($comment)) 
	{	
		comment($auditor_id, $comment, $logID);
	}
	else
	{
		header("Location: ../admin.php");
		exit();
	}
}



?>