<?php
include("db_connect.php");

$token = $_POST["token"] ?? '';
$password = $_POST["password"] ?? '';
$password_confirmation = $_POST["password_confirmation"] ?? '';

// Validate passwords match
if ($password !== $password_confirmation) {
    die("Passwords do not match.");
}

// Hash the token again
$token_hash = hash("sha256", $token);

// Fetch the user with matching token
$query = "SELECT * FROM users WHERE reset_token_hash = '$token_hash'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Invalid or expired token.");
}

$user = mysqli_fetch_assoc($result);
$email = $user['email'];

// Hash the new password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Update password and clear token
$sql = "UPDATE users 
        SET password = '$passwordHash', reset_token_hash = NULL, reset_token_expire_at = NULL 
        WHERE email = '$email'";

if (mysqli_query($conn, $sql)) {
    echo '
        <link rel="stylesheet" type="text/css" href="login_register.css">
        <div class="message">
            <img src="images/true_sign.png">
            <p style="font-weight: 800; font-size: 16px;">Password Reseted Successfully</p>
            <br>
            <a href="login.php"><button class="button">Back to login page</button></a>
        </div>';
} else {
    echo '
        <link rel="stylesheet" type="text/css" href="login_register.css">
        <div class="message">
            <p>Error updating password: ' . mysqli_error($conn);
}

?>
