<?php
session_start();

include __DIR__."/../admin/includes/functions.php";
$_SESSION['Log'] = $_SESSION['staffUser']." has logged out";
generateLog();
session_unset();
session_destroy();

header("Location: /swap_model/index.php");
exit();


	
?>