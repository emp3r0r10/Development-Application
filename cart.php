<?php
include("db_connect.php");

if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT id, username, profile_image FROM users WHERE email = '$email'";
    $userResult = mysqli_query($conn, $sql);
}

if (isset($_POST['update_btn'])) {
	$update_value = $_POST['update_quantity'];
	$update_id = $_POST['update_quantity_id'];
	$update_quantity_query = mysqli_query($conn, "UPDATE cart SET quantity = '$update_value' WHERE id = '$update_id'");
	if ($update_quantity_query) {
		header("location: cart.php");
	}
}

if (isset($_GET['remove'])) {
	$remove_id = $_GET['remove'];

	$remove_item_query = mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id'");
	header("location: cart.php");
}

if (isset($_GET['delete_all'])) {
	mysqli_query($conn, "DELETE FROM cart");
	header("location: cart.php");	
}

// $discount_percent = 0;
// $discount_amount = 0;
// $final_total = 0;
// $applied_coupon = '';
// if (isset($_POST['apply_coupon'])) {
    // $entered_coupon = strtoupper(trim($_POST['coupon_code']));
    // $current_time = date("Y-m-d H:i:s");

    // $coupon_query = mysqli_query($conn, "
    //     SELECT * FROM coupons 
    //     WHERE code = '$entered_coupon' 
    //     AND expires_at > '$current_time'
    //     LIMIT 1
    // ");

//     if (mysqli_num_rows($coupon_query) > 0) {
//         $coupon_data = mysqli_fetch_assoc($coupon_query);
//         $discount_percent = (float)$coupon_data['discount'];
//         $applied_coupon = $coupon_data['code'];
//     } else {
//         $error_message = "Invalid or expired coupon code.";
//     }
// }


// Coupon variables
$discount_percent = 0;
$applied_coupon = '';
$error_message = '';

if (isset($_POST['apply_coupon'])) {
    $entered_coupon = strtoupper(trim($_POST['coupon_code']));
    $current_time = date("Y-m-d H:i:s");

    // Check if coupon exists and is not expired
    $coupon_query = mysqli_query($conn, "
        SELECT * FROM coupons 
        WHERE code = '$entered_coupon' 
        AND expires_at > '$current_time'
        LIMIT 1
    ");

    if (mysqli_num_rows($coupon_query) > 0) {
        $coupon_data = mysqli_fetch_assoc($coupon_query);
        $discount_percent = (float)$coupon_data['discount'];
        $applied_coupon = $coupon_data['code'];
        $_SESSION['discount_percent'] = $discount_percent;
        $_SESSION['applied_coupon'] = $applied_coupon;
    } else {
        $error_message = "Invalid or expired coupon code.";
        unset($_SESSION['discount_percent'], $_SESSION['applied_coupon']);
    }
}




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="cart.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/labs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="main.js"></script>
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
	<script type="text/javascript" src="Theme.js"></script>
	<title>VWPTA - Cart</title>
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
<!-- Product View Section-->
<table>
    <thead>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Action</th>
    </thead>
    <tbody>
        <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM cart");
            $grand_total = 0;
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $subtotal = ($fetch_cart['price'] * $fetch_cart['quantity']);
                    $grand_total += $subtotal;
        ?>
        <tr>
            <td><img src="<?php echo $fetch_cart['image']; ?>" alt="Product Image"></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td>$<?php echo $fetch_cart['price']; ?></td>
            <td>
                <form action="" method="POST">
                    <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
                    <input type="number" name="update_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
                    <input type="submit" name="update_btn" value="update" class="edit_btn">
                </form>
            </td>
            <td><?php echo $subtotal; ?></td>
            <td>
                <a href="cart.php?remove=<?php echo $fetch_cart['id'] ?>" 
                   onclick="return confirm('remove item from cart?')" 
                   class="remove_btn">
                   <img src="site_images/rubbish-bin.png" width="35px" height="35px">
                </a>
            </td>
        </tr>
        <?php
                }
            }

            // Vulnerable coupon application logic
            if (!isset($_SESSION['discount_percent'])) {
                $_SESSION['discount_percent'] = 0;
            }

            if (isset($_POST['apply_coupon'])) {
			    $coupon = strtoupper(trim($_POST['coupon_code']));
			    $current_time = date("Y-m-d H:i:s");

			    // Simulate slow processing so parallel requests can slip in
			    sleep(3); // <-- This is key for race condition testing

			    $check_coupon = mysqli_query($conn, "
			        SELECT * FROM coupons 
			        WHERE code='$coupon' 
			        AND expires_at > '$current_time'
			        LIMIT 1
			    ");

			    if (mysqli_num_rows($check_coupon) > 0) {
			        $coupon_data = mysqli_fetch_assoc($check_coupon);

			        // Add the discount each time without preventing reuse
			        $_SESSION['discount_percent'] += (float)$coupon_data['discount'];

			        echo "<p style='color:green;'>Coupon {$coupon} applied! Total discount: {$_SESSION['discount_percent']}%</p>";
			    } else {
			        echo "<p style='color:red;'>Invalid or expired coupon code!</p>";
			    }
			}
        ?>
		<?php
if (!isset($_SESSION['discount_percent'])) {
    $_SESSION['discount_percent'] = 0;
}

$final_total = $grand_total;

if ($_SESSION['discount_percent'] > 0) {
    $discount_amount = ($grand_total * $_SESSION['discount_percent']) / 100;
    $final_total = $grand_total - $discount_amount;
}
		?>        
        <tr>
            <td><a href="shop.php" class="btn">Continue Shopping</a></td>
            <td colspan="3">Grand Total </td>
            <td>
                <?php
                    if ($_SESSION['discount_percent'] > 0) {
                        echo "<span style='text-decoration: line-through; color: #888;'>$$grand_total</span> ";
                        echo "<strong>$$final_total</strong>";
                    } else {
                        echo "$$grand_total";
                    }
                ?>
            </td>
            <td>
                <a href="cart.php?delete_all" 
                   onclick="return confirm('Delete all items from cart?')" 
                   class="delete_btn">Delete All</a>
            </td>
        </tr>
    </tbody>
</table>

<div class="coupon-section">
    <form method="POST" action="">
        <input type="text" name="coupon_code" placeholder="Enter Coupon Code" required>
        <input type="submit" name="apply_coupon" value="Apply Coupon" class="btn">
    </form>
    <?php echo "<p style='color:green;'>Coupon applied! Current discount: ".$_SESSION['discount_percent']."%</p>"; ?>
</div>

<div class="checkout_btn">
    <a href="checkout.php" class="btn <?= ($final_total > 1) ? '' : 'disabled'; ?>">Proceed to checkout</a>
</div>

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

</html>