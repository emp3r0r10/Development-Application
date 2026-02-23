<?php
include("db_connect.php");
$loggedIn = false;
$username = "";
$profileImagePath = "uploads/default.png"; // default image

$isAdmin = false;
if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $email = $_SESSION['email'];

    $sql = "SELECT username, profile_image FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['username'];
        $profileImagePath = 'site_images/profile_images/' . $row['profile_image'];     
    }
    if ($username == 'admin') {
    	$isAdmin = true;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script src="main.js"></script>
	<script>
	(function() {
		const savedTheme = localStorage.getItem("theme");
		if (savedTheme) {
			document.documentElement.setAttribute("data-theme", savedTheme);
		} else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
			document.documentElement.setAttribute("data-theme", "dark");
		}
	})();
	</script>
	<title>Vulnerabe Web App</title>
</head>
<body>

	<!-- Header Section -->
	<header>
		<a href="main.php"><img class="logo" src="site_images/logo.png" alt="logo"></a>
		<nav class="navbar">
				<a href="shop.php">Shop</a>
				<a href="blog.php">Blog</a>				
				<a href="#service">Services</a>
				<a href="#about">About</a>
				<a href="#contact">Contact</a>
				<?php if ($loggedIn && $isAdmin): ?>
					<a href="admin.php">Admin Dashboard</a>
				<?php endif ?>
		</nav>
		<div class="right_links">
		    <?php if ($loggedIn): ?>
		        <span class="welcome-user">
		            <img src="<?php echo $profileImagePath; ?>" alt="Profile" style="width:30px; height:30px; border-radius:50%; vertical-align:middle;">
		            <span>Hello, <?php echo ($username); ?></span>
		            <a href="logout.php" class="logout-btn" style="margin-left: 15px;">Logout</a>
		        </span>
		    <?php else: ?>
		        <a class="login" href="login.php">Login</a>
		        <a class="register" href="register.php">Register</a>
		    <?php endif; ?>
			<button id="theme-toggle" class="theme-toggle-btn">🌞</button>
		</div>
	</header>

	<!-- Home Section -->
	<section class="home">			
	  <div class="home_text">
	    <img src="site_images/logo.png">
	    <div class="home_text_content">
	      <h2>VWPTA</h2>
	      <p>Vulnerable Web Pentesting App (VWPTA) is a vulnerable web application to test your skills. VWPTA contains a lot of vulnerabilities and your mission is to exploit it. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
	      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
	      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
	      consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
	      cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
	      proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	    </div>
	  </div>
	</section>
	<!-- Service Section -->
	<section id="service">
	 	<div class="service_text">
			<h1>What We Provide?</h1>
			<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos quis consequatur iste illo libero repellat natus sapiente beatae ipsum.</p>
		</div>
		<div class="service_container">
			<div class="box">
				<img src="site_images/main_images/Service_1.png">
				<h3>Defensive</h3>
			</div>
			<div class="box">
				<img src="site_images/main_images/Service_2.png">
				<h3>Offensive</h3>
			</div>
			<div class="box">
				<img src="site_images/main_images/Service_3.jpg">
				<h3>Malware Analysis</h3>
			</div>
		</div>
	</section>

	<!-- about Section -->
	<section id="about">
		<h1>About Us</h1>
		<div class="row">
			<img src="site_images/main_images/Service_3.jpg" class="about-img">
			<div class="about_text">
				
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<p>
				<br>	
				<a href="#" class="about-btn">Learn More</a>
			</div>
		</div>
		<!-- <div>
		<img src="images/AI_1.png" class="AI">
			<img src="images/AI_2.jpeg" class="AI">
			<img src="images/AI_3.png" class="AI">
			<img src="images/AI_4.jpg" class="AI">
		</div> -->
	</section>	

	<!-- Contact Section -->
	<?php
		require "vendor/autoload.php";
		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\SMTP;
		use PHPMailer\PHPMailer\Exception;

		$error = [];

		if (isset($_POST['send_msg'])) {
		    $name    = $_POST['name'];
		    $email   = $_POST['email'];
		    $subject = $_POST['subject'];
		    $message = $_POST['message'];

		    // Validate input
		    if (empty($name))     $error[] = "Name is required.";
		    if (empty($email))    $error[] = "Email is required.";
		    if (empty($subject))  $error[] = "Subject is required.";
		    if (empty($message))  $error[] = "Message is required.";

		    if (empty($error)) {
		        $to = "johndoehackerproman@gmail.com"; // Replace with your contact receiver email
		        $mail = new PHPMailer(true);

		        try {
		            // Server settings
		            $mail->isSMTP();
		            $mail->SMTPAuth   = true;
		            $mail->Host       = "smtp.gmail.com";
		            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		            $mail->Port       = 587;
		            $mail->Username   = "johndoehackerproman@gmail.com"; // Your SMTP username
		            $mail->Password   = "lbpf noag fjyu sqfh";            // Your SMTP password

		            // Email content
		            $mail->setFrom("johndoehackerproman@gmail.com", $name);
		            $mail->addAddress($to);
		            $mail->Subject = "Contact Form: " . $subject;
		            $mail->isHTML(true);
		            $mail->Body    = "
		                <h3>New Contact Message</h3>
		                <p><strong>Name:</strong> $name</p>
		                <p><strong>Email:</strong> $email</p>
		                <p><strong>Message:</strong><br>$message</p>
		            ";
	                $mail->send();

	                // Insert into DB after email is successfully sent
	                $query = "INSERT INTO contact_messages (name, email, subject, message) 
	                          VALUES ('$name', '$email', '$subject', '$message')";

	                if (mysqli_query($conn, $query)) {
	                    $success_fail_message = "Message received and stored sucessfully.";
	                } else {
	                    echo "Database error: " . mysqli_error($conn);
	                }	            
		        } catch (Exception $e) {
		            echo "Message couldn't be sent. Mailer Error: {$mail->ErrorInfo}";
		        }
		    } else {
		        foreach ($error as $err) {
		            $success_fail_message = $err;
		        }
		    }
		}
	?>

	<section id="contact">
		<h1>Contact With Us</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
		<div class="contact-box">
			<div class="contact-form">
				<form action="" method="POST">
					<h3 class="form-title">Get In Touch</h3>
					<div class="form-fields">
						<input type="text" name="name" class="contact-inputs" placeholder="Name" required>
						<input type="email" name="email" class="contact-inputs" placeholder="Email" required>
						<input type="text" name="subject" class="contact-inputs" placeholder="Subject" required>
						<textarea name="message" class="message contact-inputs" placeholder="Message" cols="30" rows="10" required></textarea>
					</div>
					<button type="submit" class="send_btn" name="send_msg">Submit</button>
				</form>
			</div>
			<div class="contact-info">
				<h3>Contact Us</h3>
				<ul class="info-details">
					<li>
						<i class="fa fa-map-marker"></i>
						<span>Address: 198 West 21th Street, Suite 721 New York NY 10016</span>
					</li>					
					<li>
						<i class="fa fa-phone"></i>
						<span>Phone: </span> <a href="tel:+012345678">+01 234 5678</a>
					</li>
					<li>
						<i class="fa fa-paper-plane"></i>
						<span>Email: </span> <a href="mailto:support@example.com">support@example.com</a>
					</li>
					<li>
						<i class="fa fa-globe"></i>
						<span>Website: </span> <a href="https://example.com">example.com</a>
					</li>
				</ul>				
			</div>
		</div>
		<br>
		<p style="color:red; font-size: 20px;"><?php $success_fail_message; ?></p>
	</section>
	<div class="bottom-footer">
		<p>Follow Us</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do</p>
		<a href="https://x.com/vwpta"><img src="site_images/twitter.png" width="30px" height="30px"></a>
		<a href="#"><img src="site_images/instagram.png" width="30px" height="30px"></a>
		<a href="#"><img src="site_images/facebook.png" width="30px" height="30px"></a>	
		<hr>
	</div>



</body>
</html>