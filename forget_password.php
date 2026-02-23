<?php
include("db_connect.php");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="login_register.css">
	<title>VWPTA - Password Reset</title>
</head>
<body>
	<div class="login">
		<div class="box form-box">
			<header>Password Reset</header>
			<form method="POST" action="send_password_reset.php">
				<div class="field input">
					<label>Email</label>
					<input type="email" name="reset-email" placeholder="john@example.com">
				</div>
				<div class="field">
					<input type="submit" name="submit" value="Reset Password" class="button">
				</div>
			</form>
		</div>	
	</div>
</body>
</html>