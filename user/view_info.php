<!DOCTYPE html>
<html lang="en" class="display">
<head>
  <title>Mori no Kuni ya</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
	session_start();
  include('../loginSystem/db.php');
  if(!isset($_SESSION['userID']))
  {
	header('Location: ../error.php');  
	exit();
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10" style="background-color: #404040">
      <a href="../index.php" class="deco">Mori no Kuni ya</a>
    </div>
    <div class="col-lg-1" style="background-color: #404040">
      <a href="../cart_func/view_cart.php" class="deco">Cart</a>
    </div>
    <!-- Below code Toggles a window popup modal login form -->
    <div class="col-lg-1" style="background-color: #404040">
      <!-- Redirect to user update screen if logged in -->
		<?php 
        if (isset($_SESSION['userID']))
          {
            echo "<div class='dropdown'>";
            echo "<button class='btn deco dropdown-toggle' type='button' data-toggle='dropdown'>".$_SESSION['firstname']."";
              echo "<span class='caret'></span></button>";
              echo "<ul class='dropdown-menu'>";
                echo "<li><a href='user/update_info.php'>Account</a></li>";
                echo "<li><a href='../loginSystem/logout.php'>Logout</a></li>";
              echo "</ul>";
            echo "</div>";
          } 
        else 
          {
            echo "<a href='#' class='deco' data-toggle='modal' data-target='#LoginPortal'>Login</a>";
          }

        ?>
        </a>
    </div>
  </div>
</div>
<?php
//PDO 

$stmt = $conn->prepare("SELECT firstname, lastname, address, email FROM users WHERE id=:id ");
			$stmt->bindParam(':id', $id);

			//Define parameters
			$id = $_SESSION['userID'];
            $stmt->execute();
            $result = $stmt->fetchAll();
			
			//Bind the data retrieved into variables
			foreach($result as $data)
			{
				$firstname = $data['firstname'];
				$lastname = $data['lastname'];
				$address = $data['address'];
				$email = $data['email'];
			}
?>

<div class="container">
<form class="form-horizontal" action="/swap_model/validation.php" method="post">
  <h2>Account Details</h2>
      <?php
	  //Source File to redirect validation results to upon error
	  $_SESSION['Source'] = "/swap_model/user/view_info.php";
	  //File to redirect to after Input validation complete
	  $_SESSION['Destination'] = "/swap_model/user/update_info.php";
	  ?>
      <!-- FirstName -->
    <div class="form-group">
      <label class="control-label col-lg-2">First Name</label>
      <div class="col-lg-10">
        <input type="text" class="form-control"  value="<?php echo $firstname ?>" name="firstname">
        <p class="help-block">Given Name</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['FirstErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['FirstErr']."</p>";
        		unset($_SESSION['FirstErr']);
        	}       
		?>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-lg-2">Last Name</label>
      <div class="col-lg-10">
        <input type="text" class="form-control"  value="<?php echo $lastname ?>" name="lastname">
        <p class="help-block">Family Name</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['LastErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['LastErr']."</p>";
        		unset($_SESSION['LastErr']);
        	}        
		?>
      </div>
    </div>
     <!-- Address -->
     <div class="form-group">
      <label class="control-label col-lg-2">Address</label>
      <div class="col-lg-10">
        <input type="text" class="form-control"  value="<?php echo $address ?>" name="address">
        <p class="help-block">Please enter the Delivery Address</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['AddrErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['AddrErr']."</p>";
        		unset($_SESSION['AddrErr']);
        	}       
		?>
      </div>
    </div>

      <div class="form-group">        
      <div class="col-lg-offset-2 col-lg-10">
        <button type="submit" class="btn btn-default">Update</button>
      </div>
    </div>
</form>
</body>
</html>