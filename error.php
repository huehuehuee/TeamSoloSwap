<?php
session_start();

if (isset($_SESSION['staffErr']))
{
	echo $_SESSION['staffErr'];
	unset($_SESSION['staffErr']);
}

else if(isset($_SESSION['Error']))
{
	echo $_SESSION['Error'];
	unset($_SESSION['Error']);
}

else
{
	echo "Hmm. An uncaught error?";
}

?>