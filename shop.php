<?php
include("db_connect.php");
// Retrieve username and profile_image from the login session
if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $email = $_SESSION['email'];

    $sql = "SELECT id,username, profile_image FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="shop.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/labs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="main.js"></script>
	<script type="text/javascript" src="Theme.js"></script>
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
	<title>VWPTA - Shop</title>
</head>
<body>


	<!-- Header Section -->
	<header>
		<a href="main.php"><img class="logo" src="site_images/logo.png" alt="logo"></a>
		<nav class="navbar">
				<a href="shop.php">Shop</a>
				<a href="#products">Products</a>
				<a href="#about">About</a>
				<a href="#contact">Contact</a>
		</nav>
		<div class="icons">
            <!-- <div class="fa fa-search" id="search-btn" class="search"></div> -->
            <img src="site_images/search.png" id="search-btn" class="search">
            <img src="site_images/cart.png" id="cart-btn" class="cart">
            <!-- <div class="fa fa-shopping-cart" id="cart-btn"></div> -->
			<div class="fa fa-bars" id="menu-btn"></div>
			<div class="user-dropdown">
				<img src="site_images/user-logged-in.png" class="logged-in-img" id="user-icon">
				<div class="dropdown-menu" id="dropdown-menu">
					<?php if (isset($_SESSION['email'])): ?>
						<a href="profile.php">Profile</a>
						<a href="logout.php">Logout</a>
					<?php else: ?>
						<a href="login.php">Login</a>
						<a href="register.php">Register</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<form class="search-form" action="shop.php" method="GET">
		    <input type="search" id="search-box" name="q" placeholder="Search Here..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
		    <button type="submit" style="display: none;"></button> <!-- optional if hitting Enter works -->
		</form>
	</header>
	<!-- ================================================================================================== -->	
	<!-- Home Section -->
	<section class="home" id="about">			
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
	<!-- ================================================================================================== -->	
	<!-- Products Section -->	
	<section class="products" id="products">
		<h1 class="heading">Our Products</h1>
		<div class="products-container">
			<?php
				// $stmt = $conn->prepare("SELECT * FROM products");
				// $stmt->execute();
				// $result = $stmt->get_result();
				// while($row = $result->fetch_assoc()):
				
				$searchTerm = "";
				if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
				    $searchTerm = mysqli_real_escape_string($conn, $_GET['q']);
				    $query = "SELECT * FROM products WHERE 
				              product_name LIKE '%$searchTerm%' 
				              OR product_details LIKE '%$searchTerm%'";
				} else {
				    $query = "SELECT * FROM products";
				}

				$result = mysqli_query($conn, $query);
			?>
			<?php while($row = mysqli_fetch_assoc($result)): ?>
			    <div class="product-box">
			        <div class="image">
			            <img src="<?= htmlspecialchars($row['product_image']) ?>">
			        </div>
			        <div class="content">
			            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
			            <div class="price">$<?= htmlspecialchars($row['product_price']) ?></div>
			            <a href="product-view.php?productid=<?= $row['id'] ?>" class="btn">View</a>
			        </div>
			    </div>
			<?php endwhile; ?>


		</div>	
	</section>
	<!-- ================================================================================================== -->		
	<!-- Contact Section -->
	<div class="top-footer" id="contact">
		<h2>Sign Up for Newslatter</h2>
		<div class="subscribe-input">
			<input type="text" placeholder="Email Address" class="email">
			<input type="submit" value="Subscribe" class="btn">
		</div>
	</div>
	<footer>
		<div class="footer-content">
			<div class="inner-footer">
				<div class="card">
					<h3>About us</h3>
					<a href="#">About us</a>
					<a href="#">Our Difference</a>
					<a href="#">Press</a>
					<a href="#">Blog</a>
				</div>
				<div class="card">
					<h3>Service</h3>
					<a href="#">Help</a>
					<a href="#">Order</a>
					<a href="#">Shipping</a>
					<a href="#">Term Of Use</a>
					<a href="#">Details</a>
				</div>
				<div class="card">
					<h3>Local</h3>
					<a href="#">local</a>
					<a href="#">local</a>
					<a href="#">local</a>
					<a href="#">local</a>
					<a href="#">local</a>
				</div>
				<div class="card">
					<h3>Countries</h3>
					<a href="#">United Stats</a>
					<a href="#">England</a>
					<a href="#">Canada</a>
					<a href="#">Italy</a>
					<a href="#">Japan</a>
				</div>
			</div>
		</div>
	</footer>
	<div class="bottom-footer">
		<p>copyright @ 2024 <span>By Mr. Robot</span></p>
	</div>

</body>
