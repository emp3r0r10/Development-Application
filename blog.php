<?php
include("db_connect.php");
$loggedIn = false;
$username = "";
$profileImagePath = "uploads/default.png"; // default image

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
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="blog.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/labs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="main.js"></script>
	<script src="Theme.js"></script>
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
				<a href="?category=Web Development">Web Development</a>
				<a href="?category=Cyber Security">Cyber Security</a>
				<a href="?category=DevOps">DevOps</a>
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
		</div>
	</header>
	<!-- ================================================================================================== -->
	<!-- Search Section -->	
	<div class="search-section">
		<h1>Search for a Blog</h1>
		<form method="GET" action="">
			<div class="search">
				<span class="material-symbols-outlined search-icon">search</span>
				<input type="search" name="q" class="search-input" id="search-box" placeholder="Search Here..."  value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"
				>
			</div>
			<?php 
				if (isset($_GET['q'])) {
					echo "<p style='margin-top: 20px; font-size: 18px;'>You searched for: <strong>" . $_GET['q'] . "</strong></p>";
				}
			?>  
		</form>	
	</div>
	<!-- ================================================================================================== -->	
	<!-- Blog Posts Section -->	
	<section class="products" id="products">
		<h1 class="heading">Latest Blog Posts</h1>
		<div class="posts-container">
			<?php
				$searchTerm = "";
				if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
				    $searchTerm = mysqli_real_escape_string($conn, $_GET['q']);
				    $query = "SELECT posts.*, users.username, COUNT(comments.id) AS comment_count
				              FROM posts 
				              LEFT JOIN users ON posts.author_id = users.id 
				              LEFT JOIN comments ON posts.id = comments.post_id
				              WHERE posts.title LIKE '%$searchTerm%' 
				                 OR posts.content LIKE '%$searchTerm%' 
				              GROUP BY posts.id
				              ORDER BY posts.created_at DESC";
				} else if (isset($_GET['category'])) {
				    $categoryFilter = mysqli_real_escape_string($conn, $_GET['category']);
				    $query = "SELECT posts.*, users.username, COUNT(comments.id) AS comment_count
				              FROM posts 
				              LEFT JOIN users ON posts.author_id = users.id 
				              LEFT JOIN comments ON posts.id = comments.post_id
				              WHERE posts.category = '$categoryFilter'
				              GROUP BY posts.id
				              ORDER BY posts.created_at DESC";				
          		} else {
				    $query = "SELECT posts.*, users.username, COUNT(comments.id) AS comment_count
				              FROM posts 
				              LEFT JOIN users ON posts.author_id = users.id 
				              LEFT JOIN comments ON posts.id = comments.post_id
				              GROUP BY posts.id
				              ORDER BY posts.created_at DESC";
				}
				$result = mysqli_query($conn, $query);
				while ($posts = mysqli_fetch_assoc($result)):
			?>
			<div class="blog-box">
				<div class="image">
					<img src="<?= $posts['image'] ?>" alt="Blog_Image">
				</div>
				<div class="content">
					<h3><?= $posts['title'] ?></h3>
					<div class="meta">By <?= $posts['username'] ?> | <?= $posts['created_at'] ?></div>
					<p><?= nl2br($posts['content']) ?></p>
				</div>
				<div class="blog-footer">
					<a href="blog-view.php?post_id=<?php echo $posts['id']; ?>" class="read_btn">Read More</a>	
					<div class="comment_like">
					    <span style="margin-left: 5px; font-weight: bold;" class="comment-count"><?= $posts['comment_count'] ?></span>
					    <img src="site_images/comments.png" alt="comment_icon" width="30px">
					</div>
				</div>		
			</div>

			<?php endwhile; ?>
		</div>
	</section>

</body>
</html>