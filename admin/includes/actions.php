<?php
session_start();
include __DIR__."/functions.php";
include __DIR__."/validate.php";
include __DIR__."/cleanData.php";

$time = $_SERVER['REQUEST_TIME'];
$timeout_duration = 600;
$operation = substr(cleanString($_POST['ioperation']), 0, 20);
$user = substr(cleanString($_POST['iusername']), 0, 50);
$pass = substr($_POST['ipassword'], 0, 50); 
$passconfirm = substr($_POST['ipasswordconfirm'], 0, 50);
$email = substr(cleanString($_POST['iemail']), 0, 100);
$name = substr(cleanString($_POST['iname']), 0, 50);
$contact = substr(cleanInt($_POST['icontact']), 0, 8); 
$role = substr(cleanString($_POST['irole']), 0, 20);
$status = substr(cleanString($_POST['istatus']), 0, 20);
$otp = substr(cleanInt($_POST['iotp']), 0, 10);
$auditor_id = substr(cleanInt($_SESSION['userID']), 0, 20); 
$logID = substr(cleanInt($_POST['ilogID']), 0, 10);
$comment = substr($_POST['icomment'], 0, 500); //Not cleaned so comment can be posted as it is. Echoed with htmlspecialchars


if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_start();
}

if(validateOps($operation)) //If error generated, redirect
{
	$_SESSION['Error'] = "An Invalid Operation was almost called. Good thing we caught it right :D";
	header("Location: http://127.0.0.1/swap_model/error.php");
	exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'create') //Logged
{
	$_SESSION['LAST_ACTIVITY'] = $time;
	if(validateUsers($user) && validatePwd($pass) && validatePwd($passconfirm) && validateEmail($email) && validateName($name) && validateID($contact) && validateRole($role)  && validateStatus($status))
	{
		if($pass === $passconfirm)
		{
			addstaff($user, $pass, $email, $name, $contact, $role, $status);
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
	$_SESSION['LAST_ACTIVITY'] = $time;
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

if($_SERVER['REQUEST_METHOD'] === 'POST' && $operation === 'delete')
{

	if(validateUsers($user))
	{
		deletestaff($user);
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
	$_SESSION['LAST_ACTIVITY'] = $time;
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
	$_SESSION['LAST_ACTIVITY'] = $time;
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
	$_SESSION['LAST_ACTIVITY'] = $time;
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
	$_SESSION['LAST_ACTIVITY'] = $time;
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