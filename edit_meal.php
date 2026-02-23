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


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: update_products.php");
    exit();
}

$meal_id = intval($_GET['id']);
$message = "";

// Fetch existing meal data
$query = "SELECT * FROM products WHERE id = $meal_id";
$result = mysqli_query($conn, $query);
$meal = mysqli_fetch_assoc($result);

if (!$meal) {
    $message = "Meal not found.";
}

// Handle update form
if (isset($_POST['update_meal'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_details = $_POST['product_details'];
    
    // Handle image upload
    $imagePath = $meal['product_image']; // default to old image
    if (!empty($_FILES['product_image']['name'])) {
        $imageName = $_FILES['product_image']['name'];
        $imageTmp = $_FILES['product_image']['tmp_name'];
        $imagePath = "site_images/shop_products/" . $imageName;
        move_uploaded_file($imageTmp, $imagePath);
    }

    $updateQuery = "UPDATE products SET 
                    product_name = '$product_name', 
                    product_price = '$product_price', 
                    product_details = '$product_details', 
                    product_image = '$imagePath'
                    WHERE id = $meal_id";

    mysqli_query($conn, $updateQuery);
    header("Location: update_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="edit_meal.css">
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
			<li><a href="blog_updates.php">Blog Posts</a></li>
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
		<!-- Products Edit -->		
		<div class="main-content" style="margin-left: 240px; padding: 40px;">

		    <div class="back-button-wrapper">
		        <a href="update_products.php" class="btn btn-back">
		            <i class="fas fa-arrow-left"></i> Back to Menu
		        </a>
		    </div>

		    <h2 style="margin-bottom: 30px; color: var(--text-dark);">Edit Meal</h2>

		    <?php if ($message): ?>
		        <p style="color: red;"><?php echo $message; ?></p>
		    <?php endif; ?>

		    <form action="" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
		        <div style="margin-bottom: 15px;">
		            <label>Meal Name</label><br>
		            <input type="text" name="product_name" value="<?php echo $meal['product_name']; ?>" required class="form-input">
		        </div>

		        <div style="margin-bottom: 15px;">
		            <label>Price ($)</label><br>
		            <input type="number" name="product_price" value="<?php echo $meal['product_price']; ?>" required class="form-input">
		        </div>

		        <div style="margin-bottom: 15px;">
		            <label>Details</label><br>
		            <textarea name="product_details" required class="form-input" rows="4"><?php echo $meal['product_details']; ?></textarea>
		        </div>

		        <div style="margin-bottom: 15px;">
		            <label>Image</label><br>
		            <input type="file" name="product_image" class="form-input">
		            <div style="margin-top: 10px;">
		                <img src="<?php echo $meal['product_image']; ?>" alt="Meal Image" style="width: 200px; border-radius: 8px;">
		            </div>
		        </div>

		        <button type="submit" name="update_meal" class="btn">Update Meal</button>
		    </form>

		</div>		
	</div>


</body>
</html>
