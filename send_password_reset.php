<?php
include("db_connect.php");
require "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



if (isset($_POST['submit'])) {
	
	// Check if email exists
	$email = $_POST['reset-email'];
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
		$email = $_POST['reset-email'];
		$token = bin2hex(random_bytes(16));
		$tokenHash = hash("sha256", $token);
		$expiry = date("Y-m-d H:i:s",time() + 60*30);

		$sql =  "UPDATE users SET reset_token_hash = '$tokenHash', reset_token_expire_at = '$expiry' where email = '$email'";
		mysqli_query($conn, $sql);

		$mail = new PHPMailer(true);

		$mail->isSMTP();
		$mail->SMTPAuth = true;

		$mail->Host = "smtp.gmail.com";
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;
		$mail->Username = "johndoehackerproman@gmail.com";
		$mail->Password = "lbpf noag fjyu sqfh";

		$mail->isHTML(true);



		$mail->setFrom("w4zndd438x@mrotzis.com");
		$mail->addAddress($email);
		$mail->Subject = 'Password Reset';
		$mail->Body = 'Click <a href="http://127.0.0.1/VWPTA//reset_password.php?token=' . $token . '">here</a> to reset your password.';

		try {
			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message couldn't be sent. Mailer error: {$mail->ErrorInfo}";
		}		
	}
}



?>