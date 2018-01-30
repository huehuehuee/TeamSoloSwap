<!DOCTYPE html>
<html lang="en">
<head>
  <title>Mori no Kuni ya</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
  <?php include __DIR__."/admin/includes/functions.php"; ?>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10" style="background-color: #404040">
      <a href="#.php" class="deco">Mori no Kuni ya</a>
    </div>
    <div class="col-lg-1" style="background-color: #404040">
      <a href="#" class="deco">Cart</a> <!-- function disabled -->
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
              if($_SESSION['userID'] > 100 && $_SESSION['userID'] < 200) //range of staff
           	  {
                echo "<li><a href='admin/admin.php'>Management</a></li>";
              }
        	  else
        	  {
				echo "<li><a href='user/view_info.php'>Account</a></li>";
        	  }

                echo "<li><a href='#'>Change Password</a></li>"; //Not Implemented
                echo "<li><a href='loginSystem/logout.php'>Logout</a></li>";
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
<!-- Modal -->
<div class="modal fade" id="LoginPortal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
        <div class="loginmodal-container">
          <h1>Login to Your Account</h1><br>
          <form action="loginSystem/secure_login.php" method="post">
          <input type="text" name="email" placeholder="Email">
          <input type="password" name="password" placeholder="Password">
          <!-- Regex Test -->
          <?php
          if (isset($_SESSION['LoginError']))
            {
              echo "<p class='help-block' style='color:red'>".$_SESSION['LoginError']."</p>";
              unset($_SESSION['LoginError']);
            }
          ?>
          <div class="g-recaptcha" data-sitekey="6Leb3kEUAAAAAPjomzCq0VdtIgCX249uDUMdX89S" required></div>
          <input type="submit" class="login loginmodal-submit" value="Login">
          </form>
          
          <div class="login-help">
          <a href="loginSystem/register.php">Register</a> -- <a href="#">Forgot Password</a> - <a href="admin/login.php">-</a>
          </div>
        </div>
      </div>
      </div>

  <div class="container">
    <h2>Best sellers of 2017</h2>
    <br>           
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ISBN</th>
          <th>Title</th>
          <th>Author</th>
          <th>Summary</th>
          <th>Pages</th>
          <th>Publisher</th>
          <th>Languages</th>
          <th>Price</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          displaybooks();
          ?>
        </tr>
      </tbody>
    </table>
  </div>

</body>
</html>