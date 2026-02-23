<?php 

	include("db_connect.php");
	$_SESSION = [];
	session_unset();
	session_destroy();
	header("Location: main.php");


?>