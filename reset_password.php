<?php
include("db_connect.php");

$token = $_GET["token"] ?? '';
$token_hash = hash("sha256", $token);

// Correct expiry comparison using PHP's current date-time
$currentDateTime = date("Y-m-d H:i:s");
$query = "SELECT * FROM users WHERE reset_token_hash = '$token_hash' AND reset_token_expire_at >= '$currentDateTime'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);


if (mysqli_num_rows($result) === 0) {
    die("Invalid or token not found");
}

// The system rejects reused tokens after they're no longer valid.
// if (strtotime($row["reset_token_expire_at"] <= time())) {
// 	die("token has expired");
// }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="login_register.css">
	<title>VWPTA - Set New Password</title>
</head>
<body>

	<div class="login">
		<div class="box form-box">
			<header>Password Reset</header>
			<form method="POST" action="process-reset-password.php">
				<div class="field input">
					<input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
				</div>
				<div class="field input">
			        <label for="password">New password</label>
			        <input type="password" id="password" name="password">
				</div>
				<div class="field input">
				    <label for="password_confirmation">Repeat password</label>
				    <input type="password" id="password_confirmation" name="password_confirmation">
				</div>				

				<div class="field">
					<input type="submit" name="submit" value="Submit" class="button">
				</div>
			</form>
		</div>	
	</div>

    </form>

</body>
</html>
