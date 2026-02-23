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

if (isset($_POST['add_post'])) {
	$title = $_POST['title'];
	$content = $_POST['content'];			
	$imageName = $_FILES['image']['name'];
	$imageTmp = $_FILES['image']['tmp_name'];
	$imagePath = 'site_images/posts_images/' . $imageName;
	move_uploaded_file($imageTmp, $imagePath);

	$insert_query = "INSERT INTO posts (title, image, content) VALUES ('$title', '$imagePath', '$content')";
	
	mysqli_query($conn, $insert_query);

	header("Location: blog_updates.php");
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
		<!-- add_post.php -->	
		<section class="add-product-section">
		  <h2 class="form-heading"><i class="fas fa-plus-circle"></i> Add New Blog Post</h2>
		  <form action="" method="POST" enctype="multipart/form-data" class="add-product-form">
		    <input type="text" name="title" placeholder="Meal Name" required>
		    <textarea name="content" placeholder="Meal Description..." required></textarea>
		    <input type="file" name="image" accept="image/*" required>
		    <button type="submit" name="add_post" class="btn btn-submit">Add Post</button>
		  </form>
		</section>
	
	</div>
	<div class="back-button-wrapper">
	  <a href="blog_updates.php" class="btn btn-back">
	    <i class="fas fa-arrow-left"></i> Back to blog posts
	  </a>
	</div>


</body>
</html>
