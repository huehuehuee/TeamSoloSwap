<?php
session_start();

//Change database user
function loginOTP($inputUsername, $inputPassword){
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

function loginuser ($inputOtp)
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

function expireOTP()
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
function restrictAccess($start, $end)
{
	if($_SESSION['userID'] > $end || $_SESSION['userID'] < $start) //Define range for staff accounts
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function restrictManager()
{
	if($_SESSION['staffRole'] !== 'manager')
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function restrictAuditor()
{
	if($_SESSION['staffRole'] !== 'auditor')
	{
		header('HTTP/1.1 404 Not Found');
		include('../404.php');
		exit();
	}
}

function viewstaff() //works
{
	restrictManager();
	include __DIR__."/../../dbconn/manager.php";
	try{
		$stmt = $conn->prepare("SELECT username, email, name, role, status FROM staff WHERE id >= 100");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row)
		{
		  echo "<tr>";
		  echo "<td>".$row['username']."</td>";
		  echo "<td>".$row['email']."</td>";
		  echo "<td>".$row['name']."</td>";		  
		  echo "<td>".$row['role']."</td>";
		  echo "<td>".$row['status']."</td>";
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
		  echo "<td>".$row['serialNum']."</td>";
		  echo "<td>".$row['title']."</td>";
		  echo "<td>".$row['author']."</td>";		  
		  echo "<td>".$row['costPrice']."</td>";
		  echo "<td>".$row['markUp']."</td>";
		  echo "<td>".$row['salePrice']."</td>";
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
		  echo "<td>".$row['serialNum']."</td>";
		  echo "<td>".$row['title']."</td>";
		  echo "<td>".$row['author']."</td>";
		  echo "<td>".$row['summary']."</td>";
		  echo "<td>".$row['pages']."</td>";
		  echo "<td>".$row['publisher']."</td>";
		  echo "<td>".$row['language']."</td>";
		  echo "<td>$".$row['salePrice']."</td>";
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
function addstaff ($inputUsername, $inputPassword, $inputEmail, $inputName, $inputRole, $inputStatus) //Logged
{
	restrictManager(); 
	include __DIR__."/../../dbconn/manager.php";
	try {
		$hashPass = password_hash($inputPassword, PASSWORD_BCRYPT);
		//prepare sql and bind parameters
		$stmt = $conn->prepare("INSERT INTO staff (username, password, email, name, role, status, otpID) VALUES (:username, :password, :email, :name, :role, :status, :otpID)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':role', $role);
		$stmt->bindParam(':status', $status);
		$stmt->bindParam(':otpID', $otpID);
		
		//Define parameters
		$username = $inputUsername;
		$password = $hashPass;
		$email = $inputEmail;
		$name = $inputName;
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

function updatestatus ($inputUsername, $inputStatus) //logged
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

function updatepwd ($inputUsername, $inputPassword) //logged
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

function checkOTP()
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

function viewlogs() // Restricted to auditor
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
		  echo "<td>".$row['logID']."</td>";
		  echo "<td>".$row['account_id']."</td>";
		  echo "<td>".$row['name']."</td>";
		  echo "<td>".$row['role']."</td>";
		  echo "<td>".$row['status']."</td>";
		  echo "<td>".$row['content']."</td>";
		  echo "<td>".$row['timestamp']."</td>";
		  echo "<td>".$row['auditor_id']."</td>";
		  echo "<td>".$row['comment']."</td>";
		  echo "<td>".$row['time']."</td>";
		}
	}
	catch(PDOException $e) //If error message caught, expected to be duplicate Primary Key Errors
	{
	echo "Records failed to retrieve";
	}
	
}

function comment($inputID, $inputComment, $inputLogID) // Restricted to auditor. logged
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

function generateLog() 
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