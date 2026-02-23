<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="login_register.css">
	<title>VWPTA - Login</title>
</head>
<body>
	<div class="register">
		<div class="box form-box">

			<header>Sign Up</header>
			<form name="form" method="POST" action="header.php">
				<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?php echo $_GET['error']; ?></p>
				<?php } ?>
				<div class="field input">
					<label>Firstname</label>
					<input type="text" name="firstname" required>
				</div>
				<div class="field input">
					<label>Lastname</label>
					<input type="text" name="lastname" required>
				</div>								
				<div class="field input">
					<label>Username</label>
					<input type="text" name="username" required>
				</div>
				<div class="field input">
					<label>Email</label>
					<input type="text" name="email" required>
				</div>				
				<div class="field input">
					<label>Password</label>
					<input type="password" name="password" required>
				</div>
				<div class="field input">
					<label>Confirm Password</label>
					<input type="password" name="cpassword" required>
				</div>				
				<div class="field">
					<input type="submit" name="submit" value="Register" class="button">
				</div>
				<div class="log_link">
					Already have an account? <a href="login.php">Login</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>