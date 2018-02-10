<!DOCTYPE html>
<html lang="en" class="display">
<head>
  <title>Mori no Kuni ya</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap.css"> <!-- Change here -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <?php
  include __DIR__."/includes/functions.php";
  date_default_timezone_set("Asia/Singapore");
  //24hr clock 
  if(date('H') >= 9 && date('H') <= 23)  //IF time > 9am && time < 5pm, operate as usual. Else 404
  {
	 ; //do nothing
  }
  else  
  {
	header('HTTP/1.1 404 Not Found');
	include('../404.php');
	exit();
  }



  ?>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10" style="background-color: #404040">
      <a href="../index.php" class="deco">Mori no Kuni ya</a>
    </div>
    <div class="col-lg-1" style="background-color: #404040">
      <a href="#" class="deco">Cart</a>
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
                echo "<li><a href='#'>Account</a></li>";
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
<?php if(checkOTP()) : ?>
  <p style="text-align: center;">Please dont refresh the page</p>
  <p style="text-align: center;">Check your email for OTP and enter it here</p>
  <form action="includes/actions.php" method="post" style="text-align: center;">
    <input type="hidden" name="ioperation" value="otp" style="text-align: center;">
    <input type="number" name="iotp" min="0" max="999999" step="1" placeholder="otp" style="text-align: center;" required>
    <input type="submit" name="submit" value="submit" style="text-align: center;">
  </form>
  <?php 
  if (isset($_SESSION['otpErr']))
  {
    echo "<p>".$_SESSION['otpErr']." </p>";
    unset($_SESSION['otpErr']);
  }
   ?>
<?php else : ?>
<div class="container-fluid">
  <div class="col-lg-2">
      <form class="default" action="includes/actions.php" method="post">
        <input type='hidden' name='ioperation' value='login' />
        <p>Staff Only:</p>
        Username: <input type="text" name="iusername" placeholder="username" >
        Password: <input type="password" name="ipassword" placeholder="password" >
        <div class="g-recaptcha" data-sitekey="6Leb3kEUAAAAAPjomzCq0VdtIgCX249uDUMdX89S" required></div>
        <input type="submit" value="Login">
      </form>
  </div>
</div>
<?php
if (isset($_SESSION['LoginError']))
{
	echo "<p style='color: red'>".$_SESSION['LoginError']."</p>";
	unset($_SESSION['LoginError']);
}
?>
<?php endif; ?>
</body>
</html>