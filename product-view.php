<?php
include("db_connect.php");

if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT id, username, profile_image FROM users WHERE email = '$email'";
    $userResult = mysqli_query($conn, $sql);
}

// Validate productid
if (!isset($_GET['productid'])) {
    echo "<p style='padding: 20px;'>No product specified.</p>";
    exit;
}

$product_id = intval($_GET['productid']);
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<p style='padding: 20px;'>Product not found.</p>";
    exit;
}

$product = mysqli_fetch_assoc($result);

// Handle Add to Cart
if (isset($_POST['add-to-cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price   = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;

    $query_select_cart = "SELECT * FROM cart WHERE name = '$product_name'";
    $select_cart = mysqli_query($conn, $query_select_cart);

    if (mysqli_num_rows($select_cart) > 0) {
    	$message[] = "product already added to cart";
    } else {
    	$query_insert_product = "INSERT INTO cart (product_id, name, price, image, quantity) VALUES ('$product_id', '$product_name', '$product_price', '$product_image', '$product_quantity')";
    	$insert_product = mysqli_query($conn, $query_insert_product);
    	$message[] = "product added sueccefully.";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="product-view.css">
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
            <div class="cart-icon-container">
				<a href="cart.php">            	
					<img src="site_images/cart.png" id="cart-btn" class="cart">
				</a>
				<?php
				    $select_rows = mysqli_query($conn, "SELECT * FROM cart");
				    $row_count = mysqli_num_rows($select_rows);

				?>
				<span class="cart-count" id="cart-count"><?php echo $row_count ?></span>
			</div>
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
	<!-- Product View Section-->
	<div class="product-details">
	    <form action="" method="POST">
	        <div class="product-view">
	            <div class="product-image">
	                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image">
	            </div>
	            <div class="product-info">
					<h2>
						<?php
							$productName = $product['product_name'];
							$productName = preg_replace('/{{(.*?)}}/', '<?php echo $1; ?>', $productName);
							eval("?>$productName");
						?>
					</h2>
	                <p class="description"><?= htmlspecialchars($product['product_details']) ?></p>
	                <p class="price">$<?= htmlspecialchars($product['product_price']) ?></p>

	                <div class="quantity-counter">
	                    <button type="button" class="decrement">-</button>
	                    <input type="number" class="value" id="counter-value" name="quantity" value="1" min="1">
	                    <button type="button" class="increment">+</button>
	                </div>

	                <!-- Hidden fields -->
	                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
	                <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">
	                <input type="hidden" name="product_price" value="<?= $product['product_price'] ?>">
	                <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['product_image']) ?>">

	                <input type="submit" class="add-to-cart" id="add-to-cart" name="add-to-cart" value="Add to Cart">
	                <br>
					<?php
						if (isset($message)) {
							foreach($message as $message) {
								echo "<p style='color:purple; font-size:20px;'>". $message . "</p>";
							};
						};
					?>	                
	            </div>
	        </div>
	    </form>
	</div>

	<!-- ================================================================================================== -->	
	<!-- Products Section -->	
	<section class="products" id="products">
		<h1 class="heading">You may also like</h1>
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
