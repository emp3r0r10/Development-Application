<?php
include("db_connect.php");

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABLE TO SQL INJECTION
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION["login"] = true;
        $_SESSION["username"] = $row["username"];
        $_SESSION["email"] = $row["email"];
        $_SESSION["userid"] = $row["id"];
        $_SESSION["firstname"] = $row["firstname"];
        $_SESSION["lastname"] = $row["lastname"];

        header("Location: profile.php");
        exit();
    } else {
        header("Location: login.php?error=Invalid Username or Password");
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="login_register.css">
	<title>VWPTA - Login</title>
</head>
<body>
	<div class="login">
		<div class="box form-box">
			<header>Login</header>
			<form method="POST" action="">
				<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?php echo $_GET['error']; ?></p>
				<?php } ?>
				<div class="field input">
					<label>Username</label>
					<input type="username" name="username">
				</div>
				<div class="field input">
					<label>Password</label>
					<input type="password" name="password">
				</div>
				<div>
					<a href="forget_password.php" class="reset_password_link">Forget my password?</a>
				</div>					
				<div class="field">
					<input type="submit" name="submit" value="Login" class="button">
				</div>
				<div class="reg_link_div">
					Don't have an account? <a href="register.php" class="reg_link">Register</a>
				</div>			
			</form>
		</div>	
	</div>
</body>
</html>