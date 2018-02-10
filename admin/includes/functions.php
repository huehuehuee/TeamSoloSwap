<?php
session_start();

function my_simple_crypt( $string, $action) //basic encyyption 
{
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

function logout() //Logs out user and destroy session
{
	$_SESSION['Log'] = $_SESSION['staffUser']." has logged out";
	generateLog();

	session_destroy();
	header("Location: /swap_model/index.php");
	exit();
}

function loginOTP($inputUsername, $inputPassword) //Generate and email with OTP to users email on login
{
	try{
		include __DIR__."/../../dbconn/otp.php";
		$stmt = $conn->prepare("SELECT username, password, email, status, otpID FROM staff WHERE username = :username");
		$stmt->bindParam(':username', $username);		
		$username = $inputUsername;
		$stmt->execute();		
		
		$result = $stmt->fetch();
		if (count($result) > 1 && password_verify($inputPassword, $result['password']) && $result['status'] === 'Activated')
		{
			$_SESSION['username'] = $result['username']; //Identify who to login
			$_SESSION['uniqueID'] = $result['otpID']; //Identify who owns which OTP
			$email = $result['email'];
			$otpgen = rand(100000,999999);
			require_once("mail.php");
			$mail_status = mailOTP($email, $otpgen);
			
			if($mail_status == 1)
			{
				try{
				$stmt = $conn->prepare("INSERT INTO otp_expiry (otp, expired, creation_time, otpID) VALUES (:otp, :expired, :creation_time, :otpID)");
				$stmt->bindParam(':otp', $otp);
				$stmt->bindParam(':expired', $expired);
				$stmt->bindParam(':creation_time', $creation_time);
				$stmt->bindParam(':otpID', $otpID);
				
				//Define parameters
				date_default_timezone_set("Asia/Singapore");
				$otp = $otpgen;
				$expired = 0;
				$creation_time = date("Y-m-d H:i:s"); 
				$otpID = $_SESSION['uniqueID']; //Sticks the OTP database the account's otpID
				$stmt->execute();
				$_SESSION['otp_try'] = 1; //Toggles OTP view in login page
				header("Location: ../login.php");
				exit();
				}
				catch(PDOException $e) //If error message caught, rollback both update password and log query
				{		
					return $e->getMessage();
					
				}				
			}
			else
			{
				$_SESSION['LoginError'] = "Invalid Credentials. Or the gmail SMTP hit max limit :/";
				header("Location: ../login.php");
				exit();
			}
		}
		else
		{
			$_SESSION['LoginError'] = "Invalid Credentials";
			$_SESSION['Log'] = $inputUsername." has attempted but failed to login to the staff page.";
			$_SESSION['userLoggedID'] = 0;
			generateLog();
			header("Location: ../login.php");
			exit();
		}
	}
	catch(PDOException $e) //If error message caught, rollback both update password and log query
	{		
		echo $e->getMessage();
		
	}
}

function loginuser ($inputOtp) //Logs in the user 
{	
	try{
		
		include __DIR__."/../../dbconn/otp.php";
		$stmt = $conn->prepare("SELECT otp, expired, creation_time, otpID FROM otp_expiry WHERE otp = :otp AND expired != 1 AND NOW() <= DATE_ADD(creation_time, INTERVAL 10 MINUTE) AND otpID = :otpID");
		$stmt->bindParam(':otp', $otp);
		$stmt->bindParam(':otpID', $otpID);
		$otp = $inputOtp;
		$otpID = $_SESSION['uniqueID'];
		$stmt->execute();				
		$result = $stmt->fetch();
		if(count($result) > 1)
		{
			$stmt = $conn->prepare("UPDATE otp_expiry SET expired = 1 WHERE otp = :otp AND otpID = :otpID");
			$stmt->bindParam(':otp', $otp);
			$stmt->bindParam(':otpID', $otpID);
			
			$otp = $inputOtp;
			$otpID = $_SESSION['uniqueID'];
			$stmt->execute();
			
			$stmt = $conn->prepare("SELECT id, username, name, role, status FROM staff WHERE username = :username");
			$stmt->bindParam(':username', $username);		
			$username = $_SESSION['username'];		
			unset($_SESSION['username']);
			$stmt->execute();		
			$result = $stmt->fetch();
			if (count($result) > 1 && $result['status'] === 'Activated')
			{
				$_SESSION['userID'] = $result['id']; //changed to fit website design
				$_SESSION['userLoggedID'] = $_SESSION['userID'];
				$_SESSION['staffUser'] = $result['username'];
				$_SESSION['firstname'] = $result['name']; //as above
				$_SESSION['staffRole'] = $result['role'];
				//Logging Action
				$_SESSION['Log'] = $_SESSION['staffUser']." has logged in to the staff page.";
				generateLog();			
				header("Location: ../admin.php");
				exit();
			}
		}
		else
		{			
			$_SESSION['otpErr'] = "Invalid OTP! ";
			header("Location: ../login.php");
			exit();
		}
	}
	catch(PDOException $e) //If error message caught, rollback both update password and log query
	{		
		echo $e->getMessage();
		
	}
}

function expireOTP() //Renders OTP unusable 
{
	try{
	include __DIR__."/../../dbconn/otp.php";
	$stmt = $conn->prepare("UPDATE otp_expiry SET expired = 1 WHERE otpID = :otpID");
	$stmt->bindParam(':otpID', $otpID);
	$otpID = $_SESSION['uniqueID'];
	$stmt->execute();
	}
	catch(PDOException $e) //If error message caught, rollback both update password and log query
	{		
		echo $e->getMessage();
		
	}
	
}
function restrictAccess($start, $end) //configure range of staff IDs to restrict
{
	if($_SESSION['userID'] > $end || $_SESSION['userID'] < $start) //Define range for staff accounts
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function restrictManager() //configure admin and manager access
{
	if($_SESSION['staffRole'] == 'manager' || $_SESSION['staffRole'] == 'admin')
	{
		return true;
	}
	else
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function restrictAuditor() //configure auditor access
{
	if($_SESSION['staffRole'] !== 'auditor')
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function relax() //does nothing
{
    ;
}
function viewstaff() //works
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	try{
		$stmt = $conn->prepare("SELECT username, email, name, contact_no, role, status FROM staff WHERE id >= 100");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row)
		{
		  echo "<tr>";
		  echo "<td>".htmlspecialchars($row['username'])."</td>";
		  echo "<td>".htmlspecialchars($row['email'])."</td>";
		  echo "<td>".htmlspecialchars($row['name'])."</td>";
		  $contact = my_simple_crypt($row['contact_no'], 'd');
		  echo "<td>".htmlspecialchars($contact)."</td>";
		  echo "<td>".htmlspecialchars($row['role'])."</td>";
		  echo "<td>".htmlspecialchars($row['status'])."</td>";
		}
	}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
	{
	echo "Records failed to retrieve";
	}
	
}
function viewbooks() //Admin view
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	try{
		$stmt = $conn->prepare("SELECT * FROM bookprices ");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row)
		{
		  echo "<tr>";
		  echo "<td>".htmlspecialchars($row['serialNum'])."</td>";
		  echo "<td>".htmlspecialchars($row['title'])."</td>";
		  echo "<td>".htmlspecialchars($row['author'])."</td>";		  
		  echo "<td>".htmlspecialchars($row['costPrice'])."</td>";
		  echo "<td>".htmlspecialchars($row['markUp'])."</td>";
		  echo "<td>".htmlspecialchars($row['salePrice'])."</td>";
		}
	}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
	{
	echo "Records failed to retrieve";
	}
	
}
function displaybooks() //Customer view
{
	include __DIR__."/../../dbconn/manager.php";
	try{
		$stmt = $conn->prepare("SELECT * FROM display ");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row)
		{
		  echo "<tr>";
		  echo "<td>".htmlspecialchars($row['serialNum'])."</td>";
		  echo "<td>".htmlspecialchars($row['title'])."</td>";
		  echo "<td>".htmlspecialchars($row['author'])."</td>";
		  echo "<td>".htmlspecialchars($row['summary'])."</td>";
		  echo "<td>".htmlspecialchars($row['pages'])."</td>";
		  echo "<td>".htmlspecialchars($row['publisher'])."</td>";
		  echo "<td>".htmlspecialchars($row['language'])."</td>";
		  echo "<td>$".htmlspecialchars($row['salePrice'])."</td>";
		  ?>
		  <td>
                <form class="form-inline" action="#" method="post">
                  <div class="form-group">
                    <label for="quantity"></label>
                    <input type="number" min="1" max="10" class="form-control" placeholder="Quantity" name="iquantity">
                    <input type="hidden" name="iserial" value="<?php echo htmlspecialchars($row['serialNum']); ?>" />
                    <input type="submit" value="Add to cart" onclick="return confirm('Add to cart?')">
                 </div>
                </form>
              </td>
			  <?php
            }          
		}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
	{
	echo "Records failed to retrieve";
	}
	
}
function addstaff ($inputUsername, $inputPassword, $inputEmail, $inputName, $inputContact, $inputRole, $inputStatus) //Staff creation
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	try {
		$hashPass = password_hash($inputPassword, PASSWORD_BCRYPT);
		//prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO staff (username, password, email, name, contact_no, role, status, otpID) VALUES (:username, :password, :email, :name, :contact_no, :role, :status, :otpID)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':contact_no', $contact_no);
		$stmt->bindParam(':role', $role);
		$stmt->bindParam(':status', $status);
		$stmt->bindParam(':otpID', $otpID);
		
		//Define parameters
		$contact = my_simple_crypt($inputContact,'e' );
		
		$username = $inputUsername;
		$password = $hashPass;
		$email = $inputEmail;
		$name = $inputName;
		$contact_no = $contact;
		$role = $inputRole;
		$status = $inputStatus;
		$otpID = rand(100000000, 999999999);
		$stmt->execute();
		//Logging Action
		$_SESSION['Log'] = $_SESSION['staffUser']." has created the account Username: ".$inputUsername." Role: ".$inputRole." Status: ".$inputStatus." .";
		generateLog();
		header("Location: ../admin.php");
		exit();
		}
	catch(PDOException $e) //If error message caught, rollback both creation and log query
		{
		$_SESSION['staffregister'] = "Email or username already in use"; 
		header("Location: ../admin.php");
		exit();
		}
		
		
$conn = null;
}

function updatestatus ($inputUsername, $inputStatus) //Activate or disable staff account
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	try {
		$stmt = $conn->prepare("UPDATE staff SET status = :status WHERE username = :username");
		$stmt->bindParam(':status', $status);
		$stmt->bindParam(':username', $username);
		
		$status = $inputStatus;
		$username = $inputUsername;
		$stmt->execute();
		//Logging Action
		$_SESSION['Log'] = $_SESSION['staffUser']." has ".$inputStatus." the account Username: ".$inputUsername." .";
		generateLog();
		header("Location: ../admin.php");
		exit();
		}
	catch(PDOException $e) //If error message caught, rollback both update and log query
		{		
		$_SESSION['staffregister'] = "Some error has occurred"; 
		header("Location: ../admin.php");
		exit();		
		}
$conn = null;
}

function deletestaff($inputUsername) //Remove staff account. Only works for account with 'manager' role.
{
	restrictManager(); 
	include __DIR__."/../../dbconn/manager.php";
	try {
		//prepare sql and bind parameters
		$stmt = $conn->prepare("DELETE FROM staff WHERE username = :username AND role = :role");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':role', $role);
		$username = $inputUsername;
		$role = 'manager';
		$stmt->execute();
		$_SESSION['Log'] = $_SESSION['staffUser']." has deleted the account Username: ".$inputUsername."";
		generateLog();
		header("Location: ../admin.php");
		exit();
	}
	catch(PDOException $e) //If error message caught, rollback both creation and log query
		{
		$_SESSION['staffregister'] = "Failed. "; 
		header("Location: ../admin.php");
		exit();
		}
	
}

function updatepwd ($inputUsername, $inputPassword) //Change the password of any staff account
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	$hashPass = password_hash($inputPassword, PASSWORD_BCRYPT);
	try {
		$stmt = $conn->prepare("UPDATE staff SET password = :password WHERE username = :username");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':username', $username);
		
		$password = $hashPass;
		$username = $inputUsername;
		$stmt->execute();
		//Logging Action
		$_SESSION['Log'] = $_SESSION['staffUser']." updated the password for the account Username: ".$inputUsername." .";
		//Commit function ran 
		generateLog();
		header("Location: ../admin.php");
		exit();
		}
	catch(PDOException $e) //If error message caught, rollback both update password and log query
		{		
		$_SESSION['staffregister'] = "Some error has occurred"; 
		header("Location: ../admin.php");
		exit();	
		}
$conn = null;
	
}

function checkOTP() //Performs a match of user input OTP against generated OTP
{
	if(isset($_SESSION['otp_try']) && $_SESSION['otp_try'])
	{
		return true;
	}
	else
	{
		return false;
	}
}

function viewlogs() // Restricted to auditor, user activity logs.
{
	restrictAuditor();
	include __DIR__."/../../dbconn/auditor.php";
	try{
		$stmt = $conn->prepare("SELECT * FROM logsview ");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row)
		{
		  echo "<tr>";
		  echo "<td>".htmlspecialchars($row['logID'])."</td>";
		  echo "<td>".htmlspecialchars($row['account_id'])."</td>";
		  echo "<td>".htmlspecialchars($row['name'])."</td>";
		  echo "<td>".htmlspecialchars($row['role'])."</td>";
		  echo "<td>".htmlspecialchars($row['status'])."</td>";
		  echo "<td>".htmlspecialchars($row['content'])."</td>";
		  echo "<td>".htmlspecialchars($row['timestamp'])."</td>";
		  echo "<td>".htmlspecialchars($row['auditor_id'])."</td>";
		  echo "<td>".htmlspecialchars($row['comment'])."</td>";
		  echo "<td>".htmlspecialchars($row['time'])."</td>";
		}
	}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
	{
	echo "Records failed to retrieve";
	}
	
}

function comment($inputID, $inputComment, $inputLogID) // Restricted to auditor. Append comments to a log entry.
{
	restrictAuditor();
	include __DIR__."/../../dbconn/auditor.php";
	try {
		$stmt = $conn->prepare("UPDATE logsview SET auditor_id=:auditor_id, comment=:comment WHERE logID = :logID");
		$stmt->bindParam(':auditor_id', $auditor_id);
		$stmt->bindParam(':comment', $comment);
		$stmt->bindParam(':logID', $logID);
		
		$auditor_id = $inputID;
		$comment = $inputComment;
		$logID = $inputLogID;
		$stmt->execute();
		$_SESSION['Log'] = $_SESSION['staffUser']." has updated the comment for LogID: ".$inputLogID." .";

		generateLog();

		header("Location: ../admin.php");
		exit();
		}
	catch(PDOException $e) //If error message caught, rollback both update and log query
		{		
		echo $e->getMessage(); 		
		}
$conn = null;
}

function generateLog() //Generates a log entry when called
{
	include __DIR__."/../../dbconn/logger.php";
	$log = $_SESSION['Log'];
	unset($_SESSION['Log']);

	if(isset($_SESSION['userLoggedID']))
	{
		$userID = $_SESSION['userLoggedID'];
		unset($_SESSION['userLoggedID']);
	}
	else
	{
		$userID = $_SESSION['userID'];
	}
	
	try{
		//prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO logs (content, account_id) VALUES (:content, :account_id)");
		$stmt->bindParam(':content', $content);		
		$stmt->bindParam(':account_id', $account_id);	
		//Define parameters
		$content = $log;
		$account_id = $userID;
		$stmt->execute();
	}
	catch(PDOException $e) 
	{
	echo $e->getMessage();
	}
	
}
?>