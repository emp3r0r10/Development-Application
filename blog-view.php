<?php
include("db_connect.php");
$loggedIn = false;
$username = "";
$profileImagePath = "uploads/default.png"; // default image

// Retrieve username and profile_image from the login session
if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $email = $_SESSION['email'];

    $sql = "SELECT id,username, profile_image FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $username = $row['username'];
        $profileImagePath = 'site_images/profile_images/' . $row['profile_image'];
    }
}

// Check if post_id is found
if (!isset($_GET['post_id'])) {
    echo "<p style='padding: 20px;'>No blog post specified.</p>";
    exit;
}

// Retrieve Data from post_id
$post_id = $_GET['post_id'];
$query = "SELECT posts.*, users.username 
          FROM posts 
          LEFT JOIN users ON posts.author_id = users.id 
          WHERE posts.id = $post_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<p style='padding: 20px;'>Blog post not found.</p>";
    exit;
}

$post = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="blog-view.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/labs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=search" />
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<script src="main.js"></script>
	<script src="Theme.js"></script>
	<script>
		function vote(action, postId) {
		    fetch('like.php', {
		        method: 'POST',
		        headers: {
		            'Content-Type': 'application/x-www-form-urlencoded'
		        },
		        body: `action=${action}&post_id=${postId}`
		    })
		    .then(res => res.json())
		    .then(data => {
		        // Update counts
		        document.getElementById(`like-count-${postId}`).innerText = data.likes;
		        document.getElementById(`dislike-count-${postId}`).innerText = data.dislikes;

		        // Reset styles
		        document.querySelector(`#like-btn-${postId} img`).style.filter = '';
		        document.querySelector(`#dislike-btn-${postId} img`).style.filter = '';

		        // Re-apply current user's vote style
		        if (data.userVote === 'like') {
		            document.querySelector(`#like-btn-${postId} img`).style.filter = 'brightness(0.5)';
		        } else if (data.userVote === 'dislike') {
		            document.querySelector(`#dislike-btn-${postId} img`).style.filter = 'brightness(0.5)';
		        }
		    });
		}
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
				<a href="blog.php?category=Web Development">Web Development</a>
				<a href="blog.php?category=Cyber Security">Cyber Security</a>
				<a href="blog.php?category=DevOps">DevOps</a>
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
	<!-- ============================================================================= -->
	<!-- Blog Post Content -->
	<div class="page-wrapper">
		<section class="products" id="products">
		    <div class="posts-container">
		        <div class="blog-box">
		            <div class="image">
		                <img src="<?= htmlspecialchars($post['image']) ?>" alt="Blog Image">
		            </div>
		            <div class="content">
		                <h2><?= htmlspecialchars($post['title']) ?></h2>
		                <div class="meta">By <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></div>
		                <p><?= nl2br($post['content']) ?> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		                proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		                proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		                proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
		            </div>
		        </div>
		       	<hr>
				<!-- ============================================================================= -->
		       	<!-- Like and Dislike -->
		       	<?php
					// Fetch current vote
					$userVote = '';
					if ($loggedIn) {
					    $voteQuery = "SELECT type FROM likes WHERE user_id = $user_id AND post_id = $post_id";
					    $voteResult = mysqli_query($conn, $voteQuery);
					    if ($voteResult && mysqli_num_rows($voteResult) > 0) {
					        $userVote = mysqli_fetch_assoc($voteResult)['type'];
					    }
					}

					// Count likes/dislikes
					$countQuery = "SELECT 
					    SUM(type = 'like') AS likes,
					    SUM(type = 'dislike') AS dislikes 
					    FROM likes 
					    WHERE post_id = $post_id";
					$countResult = mysqli_query($conn, $countQuery);
					$countRow = mysqli_fetch_assoc($countResult);
$likeCount = $countRow['likes'] !== null ? $countRow['likes'] : 0;
$dislikeCount = $countRow['dislikes'] !== null ? $countRow['dislikes'] : 0;
					?>

					<div>
					    <button 
					        onclick="vote('like', <?= $post_id ?>)" 
					        style="background:none; border:none; cursor:pointer;"
					        id="like-btn-<?= $post_id ?>" >
					        <img src="site_images/like.png" alt="like" width="30px"
					             style="<?= $userVote === 'like' ? 'filter: brightness(0.5);' : '' ?>">
					        <span id="like-count-<?= $post_id ?>"><?= $likeCount ?></span>
					    </button>

					    <button 
					        onclick="vote('dislike', <?= $post_id ?>)" 
					        style="background:none; border:none; cursor:pointer; margin-left:10px;"
					        id="dislike-btn-<?= $post_id ?>" >
					        <img src="site_images/dislike.png" alt="dislike" width="30px"
					             style="<?= $userVote === 'dislike' ? 'filter: brightness(0.5);' : '' ?>">
					        <span id="dislike-count-<?= $post_id ?>"><?= $dislikeCount ?></span>
					    </button>
					</div>
				<!-- ============================================================================= -->				
	       		<!-- Display the comment -->
		       	<div class="comment-container">
			       	<h3>Comments</h3>
					<?php
					// Handle comment submission
					if (isset($_POST['submit']) && $loggedIn) {
					    $comment = $_POST['comment-content'];
					    $user_email = $_SESSION['email'];
					    $insert_comment = "INSERT INTO comments (post_id, user_email, content) VALUES ($post_id, '$user_email', '$comment')";
					    mysqli_query($conn, $insert_comment);
					}

					// Fetch and display comments
					$fetch_comment = "SELECT comments.*, users.username, users.profile_image 
					                  FROM comments 
					                  LEFT JOIN users ON users.email = comments.user_email 
					                  WHERE post_id = $post_id 
					                  ORDER BY created_at DESC";

					$comments_result = mysqli_query($conn, $fetch_comment);

					while ($comment = mysqli_fetch_assoc($comments_result)) {
					    $userImage = !empty($comment['profile_image']) ? 'uploads/' . $comment['profile_image'] : 'uploads/default.png';
					    echo '<div class="single-comment">';
					    echo '<img src="' . $userImage . '" alt="User" class="comment-avatar">';
					    echo '<div class="comment-body">';
					    echo '<strong>' . $comment['username'] . '</strong> ';
					    echo '<span class="comment-time">' . date("Y/m/d | h:i A", strtotime($comment['created_at'])) . '</span>';
					    echo '<p>' . $comment['content'] . '</p>';
					    echo '</div>';
					    echo '</div>';
					}
					?>
		    		<!-- Add a comment -->
					<h3>Add a comment</h3>
					<form method="POST" action="">
					    <textarea rows="5" cols="90" placeholder="Add your comment..." name="comment-content" required></textarea>
					    <button class="submit_btn" type="submit" name="submit">Submit</button>
					</form>
		    	</div>
		    </div>		    
		</section>
		<!-- ============================================================================= -->
	    <!-- Right Side -->
	    <aside class="right-side">
	        <h3>Categories</h3>
	        <ul>
	            <li><a href="blog.php?category=Web Development">Web Development</a></li>
	            <hr>
	            <li><a href="blog.php?category=Cyber Security">Cyber Security</a></li>
	            <hr>
	            <li><a href="blog.php?category=DevOps">DevOps</a></li>
	        </ul>
	    </aside>
	</div>
</body>
</html>