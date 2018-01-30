<?php
session_start();

$_SESSION = array();
session_destroy();

header("Location: /swap_model/index.php");
exit();


	
?>