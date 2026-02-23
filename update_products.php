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


if (isset($_GET['delete_meal'])) {
	$delet_meal_id = $_GET['delete_meal'];

	$delete_item_query = mysqli_query($conn, "DELETE FROM products WHERE id = '$delet_meal_id'");
	header("location: update_products.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="update_products.css">
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

		<div class="add-button-container">
		    <a href="add_products.php" class="btn btn-add-product"><i class="fas fa-plus"></i> Add New Meal</a>
		</div>		
		<!-- ================================================================================================== -->	
		<!-- Products Section -->		

		<section class="products" id="products">
				<div class="products-container">
				    <?php
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
				                <a href="edit_meal.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
				                <a href="update_products.php?delete_meal=<?= $row['id'] ?>" name="delete_meal" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
				            </div>	
				        </div>
				    <?php endwhile; ?>
				</div>
		</section>		
	</div>


</body>
</html>
