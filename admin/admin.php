<?php
  error_reporting(0);
	include __DIR__."/includes/functions.php";
	restrictAccess(100, 300); //range of staff IDs
	$time = $_SERVER['REQUEST_TIME'];
	$timeout_duration = 600;
	if (isset($_SESSION['LAST_ACTIVITY']) && 
   ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    logout();
}
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
<!DOCTYPE html>
<html lang="en" class="display">
<head>
  <title>Mori no Kuni ya</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap.css"> <!-- Change here -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-10" style="background-color: #404040">
      <a href="../index.php" class="deco">Mori no Kuni ya</a>
    </div>
    <div class="col-lg-2" style="background-color: #404040">
      <!-- Directs to user update screen if logged in -->
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
 
<?php
//Staff account creation
if(restrictManager()) : 
//allows only Manager role to see User Account Management tables
//Create staff accounts
?>
  <div class="container-fluid">
    <div class="col-lg-2">
      <table class="table">
        <form action="includes/actions.php" method="post">
          <input type='hidden' name='ioperation' value='create' />
          <th>Create Staff Account:</th>
          <tr>
            <td>
              <div class="form-group"> 
                <div class="col-lg-10">
                      Username: <input type="text" class="form-control"  placeholder="username" name="iusername" />
                      <p class="help-block">Username (no whitespace)</p>                    
                    </div>
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                      Password: <input type="password" class="form-control"  placeholder="password" name="ipassword" />
                      <p class="help-block">at least 8 characters long, 1 upper, lowercase character and 1 number</p>
                    </div>
                  </div>
                  <div class="col-lg-10">
                    Confirm Password: <input type="password" class="form-control"  placeholder="confirm-password" name="ipasswordconfirm" />
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                      Email: <input type="text" class="form-control"  placeholder="email" name="iemail" />
                      <p class="help-block">Email of User</p>
                    </div>
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                      Name: <input type="text" class="form-control"  placeholder="name" name="iname" />
                      <p class="help-block">Name of user (no whitespace)</p>
                    </div>
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                      Contact: <input type="number" class="form-control"  placeholder="contact" name="icontact" min=0 />
                      <p class="help-block">Contact Number</p>
                    </div>
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                  Role:
                      <select name="irole">
                        <option value="auditor">Auditor</option>
                        <option value="manager">Manager</option>
                      </select>
                      <p class="help-block">Select user role</p>
                    </div>
                  </div>
                  <div class="form-group"> 
                <div class="col-lg-10">
                  Status:
                      <select name="istatus">
                        <option value="Activated">Active</option>
                        <option value="Disabled">Disabled</option>
                      </select>
                      <p class="help-block">Activate or Disable Account</p>
                    </div>
                  </div>
                  <div class="form-group">        
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-default">Create</button>
                  </div>
                </div>
                <div class="form-group">        
                  <div class="col-lg-offset-2 col-lg-10">
                <?php
              if (isset($_SESSION['staffregister']))
              {
                echo "<p style='color: red'>".$_SESSION['staffregister']."</p>";
                unset($_SESSION['staffregister']);
              }                   
              ?>
              </div>
            </div>
            </td>
          </tr>
        </form>
      </table>    
    </div>  
 <?php //Update Staff status ?>
	<div class="col-lg-2">
		<table class="table">
			<form action="includes/actions.php" method="post">
        <input type='hidden' name='ioperation' value='update' />
				<th>Update Staff Account:</th>
				<tr>
					<td>
						<div class="form-group"> 
							<div class="col-lg-10">
	        					Username: <input type="text" class="form-control"  placeholder="username" name="iusername" />
	        					<p class="help-block">Username</p>
	        				</div>
	        			</div>
	        			<div class="form-group"> 
							<div class="col-lg-10">
								Status:
	        					<select name="istatus">
	        						<option value="Activated">Active</option>
	        						<option value="Disabled">Disabled</option>
	        					</select>
	        					<p class="help-block">Activate or Disable Account</p>
	        				</div>
	        			</div>
	        			<div class="form-group">        
					      <div class="col-lg-offset-2 col-lg-10">
					        <button type="submit" class="btn btn-default">Update</button>
					      </div>
					    </div>
					</td>
				</tr>
      </form>
		</table> 
	</div>
  <div class="col-lg-2">
    <table class="table">
      <form action="includes/actions.php" method="post">
        <input type='hidden' name='ioperation' value='password' />
        <th>Reset Staff Password:</th>
        <tr>
          <td>
            <div class="form-group"> 
              <div class="col-lg-10">
                    Username: <input type="text" class="form-control"  placeholder="username" name="iusername" />
                    <p class="help-block">Username</p>
              </div>
            </div>
            <div class="form-group"> 
              <div class="col-lg-10">
                    New Password: <input type="password" class="form-control"  placeholder="password" name="ipassword" />
                    <p class="help-block">at least 8 characters long, 1 upper, lowercase character and 1 number</p>
              </div>
            </div>
            <div class="form-group"> 
            <div class="col-lg-10">
              Confirm Password: <input type="password" class="form-control"  placeholder="password-confirm" name="ipasswordconfirm" />
            </div>
            </div>
            <div class="form-group">        
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-default">Reset</button>
            </div>
            </div>
          </td>
        </tr>
      </form>
    </table> 
  </div>
  <div class="col-lg-2">
    <table class="table">
      <form action="includes/actions.php" method="post">
        <input type='hidden' name='ioperation' value='delete' />
        <th>Delete Staff Account:</th>
        <tr>
          <td>
            <div class="form-group"> 
              <div class="col-lg-10">
                    Username: <input type="text" class="form-control"  placeholder="username" name="iusername" />
                    <p class="help-block">Username</p>
                  </div>
                </div>
                <div class="col-lg-10">
                  Role:
                      <select name="irole">
                        <option value="manager">Manager</option>
                      </select>
                      <p class="help-block">Select user role</p>
                    </div>
                <div class="form-group">        
                <div class="col-lg-offset-2 col-lg-10">
                  <button type="submit" class="btn btn-default">Delete</button>
                </div>
              </div>
          </td>
        </tr>
      </form>
    </table> 
  </div>
</div>
<div class="container-fluid">
    <div class="container">
    <h2>Staff Accounts</h2>
    <br>           
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Role</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
	        viewstaff();                      
          ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="container-fluid">
    <div class="container">
    <h2>Books Sales Information</h2>
    <br>           
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ISBN</th>
          <th>title</th>
          <th>author</th>
          <th>costPrice</th>
          <th>markUp</th>
          <th>salePrice</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          viewbooks();                      
          ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php elseif($_SESSION['staffRole'] === 'auditor') : ?> <!-- Auditor view! -->
  <div class="container-fluid">
    <div class="col-lg-3">
      <table class="table">
        <form action="includes/actions.php" method="post">
          <input type='hidden' name='ioperation' value='comment' />
          <th>Update comment for logID:</th>
          <tr>
            <td>
              <div class="form-group"> 
                <div class="col-lg-10">
                      LogID: <input type="number" min="0" step="1" class="form-control"  placeholder="LogID" name="ilogID" />
                      <p class="help-block"><?php if (isset($_SESSION['logsIDErr']))
                              {
                                echo "<p style='color: red'>".$_SESSION['logsIDErr']."</p>";
                                unset($_SESSION['logsIDErr']);
                              }
                              else
                              {
                                echo "LogID";
                              }
                        ?></p>                    
                    </div>
                  </div>
              <div class="form-group"> 
                <div class="col-lg-10">
                      Comment: <input type="text" class="form-control"  placeholder="comment" name="icomment" />
                      <p class="help-block">Comment</p>                    
                    </div>
                  </div>
                  <div class="form-group">        
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
            </div>
                </td>
              </tr>
            </form>
          </table>
        </div>
      </div>
  <div class="container-fluid">
    <div class="container">
    <h2>Logs of Mori no Kuni ya</h2>
    <br>           
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>LogID</th>
          <th>Account ID</th>
          <th>Name</th>
          <th>Role</th>
          <th>Status</th>
          <th>Description</th>
          <th>Timestamp</th>
          <th>Auditor_ID</th>
          <th>Comment</th>
          <th>Timestamp</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          viewlogs();                      
          ?>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

</table>
</body>
</html>