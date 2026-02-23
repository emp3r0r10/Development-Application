<?php
	session_start();
	$host= "localhost";
	$username = "root";
	$password = "";
	$db_name = "VWPTA";
	$charset = 'utf8mb4';
	$conn = mysqli_connect($host, $username, $password, $db_name);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>