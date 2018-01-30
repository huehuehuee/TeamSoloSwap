<?php
session_start();

include('db.php');
$inputEmail = $_POST['email'];
$inputPassword = $_POST['password'];

//Google reCaptcha

$recaptcha_secret = "6Leb3kEUAAAAAATPRYO8LC18mci-jD7wNCeLf2fe";

$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST['g-recaptcha-response']);

$response = json_decode($response, true);


if($response["success"] === true){

	echo "Form Submit Successfully."; 
	echo $recaptcha_secret."</p>";
	echo $_POST['g-recaptcha-response'];
}
else{

        echo "You are a robot";

}

if (!preg_match("/^([A-Za-z0-9 ])+([@])+([a-z])+([.])+([a-z])+$/", $inputEmail)) 
{
	$_SESSION['LoginError'] = "Invalid Credentials";
}

if (!strlen($inputPassword) < 8 && !preg_match("/.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/", $inputPassword))
{
	$_SESSION['LoginError']= "Invalid Credentials";
}

if (isset($_SESSION['LoginEmail']) || isset($_SESSION['LoginPwd']))
{
	header('Location: ../index.php');  
	exit();
}

try {

	//prepare sql and bind parameters
	$stmt = $conn->prepare("SELECT id, firstname, password FROM users WHERE email=:email");
	$stmt->bindParam(':email', $email);

	//Define parameters
	$email = $inputEmail;
	$stmt->execute();
	$result = $stmt->fetch();
	if ($result && password_verify($inputPassword, $result['password']))
	{

		$_SESSION['userID'] = $result['id'];
		$_SESSION['firstname'] = $result['firstname'];

	}
	else
	{
		$_SESSION['LoginError']= "Invalid Credentials";
	}
	header('Location: ../index.php');  
	exit();

}
catch(PDOException $e) 
{
	echo "System failure, try again later";
}

$conn = null;

?>
