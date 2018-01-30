<!DOCTYPE html>
<html lang="en">
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
        <?php 
        session_start();  
          if (isset($_SESSION['userID']))
          {
            echo $_SESSION['firstname'];
          }
          else
          {
          	echo "<a href='#.php' class='deco' data-toggle='modal' data-target='#LoginPortal'>Login</a>";
          }
        ?>
    </div>
  </div>
</div>
<div class="container">
<form class="form-horizontal" action='secure_register.php' method="post">
  <h2>Registration</h2>
      
      <!-- FirstName -->
    <div class="form-group">
      <label class="control-label col-lg-2">First Name</label>
      <div class="col-lg-10">
        <input type="text" class="form-control"  placeholder="First name" name="firstname">
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
        <input type="text" class="form-control"  placeholder="Last name" name="lastname">
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
        <input type="text" class="form-control"  placeholder="Address" name="address">
        <p class="help-block"> Delivery Address</p>
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

     
     <!-- Email -->
     <div class="form-group">
      <label class="control-label col-lg-2">E-mail</label>
      <div class="col-lg-10">
        <input type="text" class="form-control"  placeholder="Email" name="email">
        <p class="help-block">E-mail will be used as Login</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['EmailErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['EmailErr']."</p>";
        		unset($_SESSION['EmailErr']);
        	}
		?>
      </div>
    </div>

     <!-- Password -->
     <div class="form-group">
      <label class="control-label col-lg-2">Password</label>
      <div class="col-lg-10">
        <input type="password" class="form-control"  placeholder="Password" name="password">
        <p class="help-block">Password should be at least 8 characters long, containg 1 upper and lowercase character and 1 number</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['PwdErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['PwdErr']."</p>";
		        unset($_SESSION['PwdErr']);
        	}
		?>
      </div>
    </div>

     <!-- Password (Confirmation) -->
     <div class="form-group">
      <label class="control-label col-lg-2">Password (Confirm)</label>
      <div class="col-lg-10">
        <input type="password" class="form-control"  placeholder="Password confirmation" name="password_confirm">
        <p class="help-block">Confirm password</p>
        <!-- Regex Test -->
        <?php
        if (isset($_SESSION['PwdMismatch']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['PwdMismatch']."</p>";
        		unset($_SESSION['PwdMismatch']);
        	}

        else if (isset($_SESSION['PwdErr']))
        	{
        		echo "<p class='help-block' style='color:red'>".$_SESSION['PwdErr']."</p>";
        		unset($_SESSION['PwdErr']);
        	}        

		?>

      </div>

      <div class="form-group">        
      <div class="col-lg-offset-2 col-lg-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
</form>
</div>

<!-- Modal -->
<div class="modal fade" id="LoginPortal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
        <div class="loginmodal-container">
          <h1>Login to Your Account</h1><br>
          <form action="secure_login.php" method="post">
          <input type="text" name="email" placeholder="Email">
          <input type="password" name="password" placeholder="Password">
          <input type="submit" class="login loginmodal-submit" value="Login">
          </form>
          
          <div class="login-help">
          <a href="#">Register</a> - <a href="#">Forgot Password</a>
          </div>
        </div>
      </div>
      </div>

</body>
</html>