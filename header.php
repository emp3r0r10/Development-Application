<?php
	if (isset($_POST['submit'])) {
		include("db_connect.php");
		$firstname = $_POST['firstname'];		
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];		
		$email = $_POST['email'];
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		
		$sql = "SELECT * FROM users WHERE username='$username'";
		$result = mysqli_query($conn, $sql);
		$count_user = mysqli_num_rows($result);

		$sql = "SELECT * FROM users WHERE email='$email'";
		$result = mysqli_query($conn, $sql);
		$count_email = mysqli_num_rows($result);

		if ($count_user == 0 || $count_email == 0) {
			if ($password == $cpassword) {
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO users (firstname, lastname, username, email, password) 
        VALUES ('$firstname', '$lastname', '$username', '$email', '$hash')";
				$result = mysqli_query($conn, $sql);
				if ($result) {
					echo '
						<link rel="stylesheet" type="text/css" href="login_register.css">
						<div class="message">
							<p>Registeration Successfully</p>
							<br>
							<a href="login.php"><button class="button">Login</button></a>
						</div>';
				}
			}
			else {
				header("Location: register.php?error=Password Does Not Match");
			}
		} else {
			if ($count_user > 0) {
				header("Location: register.php?error=Username has already been taken");
			}
			if ($count_email > 0) {
				header("Location: register.php?error=Email has already been taken");
			}
		}
	}
?>