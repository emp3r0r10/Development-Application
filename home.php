<?php 
	include("db_connect.php");
	if (!empty($_SESSION['id'])) {
		$id = $_SESSION['id'];
		$sql = "SELECT * FROM users WHERE id='$id'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
	}
	else {
		header("Location: login.php");
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
	<title>Vulnerabe Web App</title>
</head>
<body>

	<!-- Header Section -->
	<header>
		<a href="main.php"><img class="logo" src="images/logo.png" alt="logo"></a>
		<nav class="navbar">
				<a href="market.php">market</a>
				<a href="#services">services</a>
				<a href="#about">About</a>
				<a href="#contact">Contact</a>
		</nav>
		<h3 class="welcome">Hello, <?php echo $row["username"]; ?></h3> 
		<div class="right_links">
			<a class="logout" href="main.php">Logout</a>
			<div class="fa fa-bars" id="menu-btn"></div>
		</div>
	</header>

	<!-- Home Section -->
	<section class="home">			
			<div class="home_text">
				<h2>VWPTA</h2>
				<p>Vulnerable Web Pentesting App (VWPTA) is a vulnerable web application to test your skills. VWPTA contains a lot of vulnerabilities and your mission is to exploit it.</p>
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
				<img src="images/Service_1.png">
				<h3>Defensive</h3>
			</div>
			<div class="box">
				<img src="images/Service_2.png">
				<h3>Offensive</h3>
			</div>
			<div class="box">
				<img src="images/Service_3.jpg">
				<h3>Malware Analysis</h3>
			</div>
		</div>
	</section>

	<!-- about Section -->
	<section id="about">
		<div class="about_text">
			<h1>About Us</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<p>
			<img src="images/AI_1.png" class="AI">
			<img src="images/AI_2.jpeg" class="AI">
			<img src="images/AI_3.png" class="AI">
			<img src="images/AI_4.jpg" class="AI">
		</div>
	</section>	

	<!-- Contact Section -->
	<section id="contact">
	<h1>Contact With Us</h1>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
		<div class="contact-box">
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
						<span>Phone: </span> <a href="mailto:support@example.com">support@example.com</a>
					</li>
					<li>
						<i class="fa fa-globe"></i>
						<span>Website: </span> <a href="https://example.com">example.com</a>
					</li>
				</ul>				
			</div>
			<div class="contact-form">
				<form action="">
					<h3 class="form-title">Get In Touch</h3>
					<div class="form-fields">
						<div class="form-group">
							<input type="text" class="name" placeholder="Name">
						</div>
						<div class="form-group">
							<input type="text" class="email" placeholder="Email">
						</div>
						<div class="form-group">
							<input type="text" class="subject" placeholder="Subject">
						</div>
						<div class="form-group">
							<textarea name="Message" class="message" placeholder="Message" cols="30" rows="10"></textarea>
						</div>
					</div>
					<input type="submit" value="Send" class="send">
				</form>
			</div>
		</div>
	</section>

</body>
