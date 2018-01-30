<!DOCTYPE html>
<html lang="en" class="display">
<head>
  <title>Mori no Kuni ya</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap.css"> <!-- Change here -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
  session_start();
  ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10" style="background-color: #404040">
      <a href="../bootstrap.php" class="deco">Mori no Kuni ya</a>
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


</body>
</html>