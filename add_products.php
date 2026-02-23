<?php
include("db_connect.php");

$loggedIn = false;
$username = "";
$profileImagePath = "uploads/default.png"; // default image

if (isset($_SESSION['email'])) {
  $loggedIn = true;
  $email = $_SESSION['email'];

  $sql = "SELECT firstname, lastname, username, profile_image FROM users WHERE email = 'john.smith@gmail.com'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];    
    $username = $row['username'];
    $profileImagePath = 'site_images/profile_images/' . $row['profile_image'];
  }
}

if (isset($_POST['add_meal'])) {
	$product_name = $_POST['product_name'];
	$product_price = $_POST['product_price'];
	$product_details = $_POST['product_details'];			
	$imageName = $_FILES['product_image']['name'];
	$imageTmp = $_FILES['product_image']['tmp_name'];
	$imagePath = "site_images/shop_products/" . $imageName;
	move_uploaded_file($imageTmp, $imagePath);

	$insert_query = "INSERT INTO products (product_name, product_price, product_image, product_details) VALUES ('$product_name', '$product_price', '$imagePath', '$product_details')";
	
	mysqli_query($conn, $insert_query);

	header("Location: update_products.php");
	exit();	
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="add_products.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="main.js"></script>
	<title>VWPTA - Admin Products</title>
</head>
<body>

	<!-- Header Section -->
	<div class="sidebar">
		<h2><a href="main.php"><img class="logo" src="site_images/logo.png" alt="logo"></a></h2>
		<ul>
			<li class="active"><a href="admin.php">Dashboard</a></li>
			<li><a href="update_products.php">Menu</a></li>
			<li><a href="#">Food Order</a></li>
			<li><a href="#">Reviews</a></li>
			<li><a href="profile.php">Settings</a></li>
		</ul>
		<div class="upgrade-box">
			<p>Upgrade your Account to get more benefits</p>
			<button>Upgrade</button>
		</div>
	</div>

	<div class="main-content">
		<div class="top-bar">
			<h1>Dashboard</h1>
			<div class="user-info">
				<h4><?php echo $firstname . ' ' . $lastname ?></h4>
				<img src="<?php echo $profileImagePath ?>" class="avatar" alt="Avatar">
			</div>
		</div>
		<!-- ================================================================================================== -->	
		<!-- add_meal.php -->	
		<section class="add-product-section">
		  <h2 class="form-heading"><i class="fas fa-plus-circle"></i> Add New Meal</h2>
		  <form action="add_products.php" method="POST" enctype="multipart/form-data" class="add-product-form">
		    <input type="text" name="product_name" placeholder="Meal Name" required>
		    <input type="number" name="product_price" placeholder="Price ($)" step="0.01" required>
		    <textarea name="product_details" placeholder="Meal Description..." required></textarea>
		    <input type="file" name="product_image" accept="image/*" required>
		    <button type="submit" name="add_meal" class="btn btn-submit">Add Meal</button>
		  </form>
		</section>
	
	</div>
	<div class="back-button-wrapper">
	  <a href="update_products.php" class="btn btn-back">
	    <i class="fas fa-arrow-left"></i> Back to Menu
	  </a>
	</div>


</body>
</html>
