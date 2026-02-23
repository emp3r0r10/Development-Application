<?php
include("db_connect.php");
include("header.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$user = $_SESSION['username'];

$sql = "SELECT profile_image FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profileImage = 'site_images/profile_images/' . $row['profile_image'];
} else {
    $profileImage = 'uploads/default.png';
}

if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $email           = $_SESSION['email'];

    if ($newPassword !== $confirmPassword) {
        echo "<p style='color:red;'>New passwords do not match.</p>";
        exit();
    }

    // Get current plain text password from DB
    $sql = "SELECT password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['password'];

        // Compare plain text passwords directly
        if ($currentPassword === $storedPassword) {
            $updateSql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
            if (mysqli_query($conn, $updateSql)) {
                echo "<p style='color:green;'>Password changed successfully.</p>";
            } else {
                echo "<p style='color:red;'>Failed to change password.</p>";
            }
        } else {
            echo "<p style='color:red;'>Incorrect current password.</p>";
        }
    } else {
        echo "<p style='color:red;'>User not found.</p>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <script type="text/javascript" src="profile.js"></script>
    <script type="text/javascript" src="Theme.js"></script>
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
		</nav>		
		<div class="right_links">
			<a class="login" href="logout.php">Logout</a>
		</div>
	</header>

    <!-- profile data -->
 	<div class="container">
 		<h1>Welcome, <?php echo $_SESSION['username'] ?></h1>
 		<div class="card-left">
 			<img src="<?php echo $profileImage; ?>" alt="Profile Image" width="150">
 			<br>
			<form action="upload_image.php" method="POST" enctype="multipart/form-data">
			    <input type="file" name="profile_image" required>
			    <button class="upload_img" type="submit" name="upload">Upload</button>
			</form>
 			<h3><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?></h3>
			<ul>
				<li>About</li>
				<li>Settings</li>
				<li><a href="https://x.com/vwpta" class="broken_link">Twitter</a></li>			  			  
			</ul>
 			<button class="links_btn">Add Links</button>
 		</div>

		<div class="card-right">
		    <h3>User Info</h3>	

		    <!-- Profile Content -->
			<form method="POST" action="">
			    <label>First Name</label>
			    <div class="editable-field">
			        <input type="text" name="firstName" id="firstName" value="<?php echo $_SESSION['firstname']; ?>" readonly>
			        <button class="edit_btn" type="button" onclick="toggleEdit('firstName')">Edit</button>
			    </div>

			    <label>Last Name</label>
			    <div class="editable-field">
			        <input type="text" name="lastName" id="lastName" value="<?php echo $_SESSION['lastname']; ?>" readonly>
			        <button class="edit_btn" type="button" onclick="toggleEdit('lastName')">Edit</button>
			    </div>

			    <label>Email</label>
			    <div class="editable-field">
			        <input type="email" name="email" id="email" value="<?php echo $_SESSION['email']; ?>" readonly>
			        <button class="edit_btn" type="button" onclick="toggleEdit('email')">Edit</button>
			    </div>

			    <div style="margin-top: 20px;">
			        <button type="submit" name="update_profile" class="save_btn">Save All</button>
			    </div>
			</form>

			<!-- Password Change -->
		    <h3>Change Password</h3>
			<form method="POST" action="">
			    <div class="editable-field">
			        <input type="password" placeholder="Current Password" name="current_password">		        
			    </div>
			    <div class="editable-field">
			        <input type="password" placeholder="New Password" name="new_password">
			    </div>
				<div class="editable-field">
				    <input type="password" placeholder="Confirm New Password" name="confirm_password" required>
				</div>			    
			    <button class="change_pass" name="change_password">Change Password</button>
			</form>
		</div>	
 	</div>


</body>
</html>
