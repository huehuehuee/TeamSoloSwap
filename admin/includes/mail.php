<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__.'/vendor/autoload.php';

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */
//Import PHPMailer classes into the global namespace

function mailOTP($email, $otp)
{
	//Create a new PHPMailer instance
	$mail = new PHPMailer;

	$mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = "testmyswaphere@gmail.com";                 // SMTP username
    $mail->Password = "verycomplicateded";                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;  
	
	$mail->setFrom('tan.junsheng.tony@gmail.com', 'Tony');
    $mail->addAddress($email, 'Client');     // Add a recipient
	$mail->addReplyTo('tan.junsheng.tony@gmail.com', 'Tony');

	$mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Mori no Kuni ya login OTP';
    $mail->Body    = "Hello! Your OTP is ".$otp." ! Please enter it on the login screen. The code expires in a 10 minutes";
    $mail->AltBody = "Hello! Your OTP is ".$otp." ! Please enter it on the login screen. The code expires in a 10 minutes";
		
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	if (!$mail->send()) {
		$success = 0;
		return $success;
	} else {
		$success = 1;
		return $success;
	}
}

?>