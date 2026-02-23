<?php
include("db_connect.php");

if (isset($_SESSION['email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['email']);
    $sql = "SELECT id, username, profile_image FROM users WHERE email = '$email'";
    $userResult = mysqli_query($conn, $sql);
}

$grand_total = 0;
// $discount_percent = 0;
// $applied_coupon = '';

// $select_cart = mysqli_query($conn, "SELECT * FROM cart");
// $cart_items = [];

// while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
//     $item_total = $fetch_cart['price'] * $fetch_cart['quantity'];
//     $grand_total += $item_total;
//     $cart_items[] = $fetch_cart;
// }

// // Handle coupon submission
// if (isset($_POST['apply_coupon'])) {
//     $promoCode = mysqli_real_escape_string($conn, $_POST['coupon_code']);
//     $current_time = date("Y-m-d H:i:s");

//     $coupon_query = mysqli_query($conn, "
//         SELECT * FROM coupons 
//         WHERE code = '$promoCode' 
//         AND expires_at > '$current_time'
//         LIMIT 1
//     ");

//     if (mysqli_num_rows($coupon_query) > 0) {
//         $coupon_data = mysqli_fetch_assoc($coupon_query);
//         $discount_percent = (float)$coupon_data['discount'];
//         $applied_coupon = $coupon_data['code'];
//     } else {
//         $error_message = "Invalid or expired coupon code.";
//     }
// }
// ==== Apply stacked discount from session (vulnerable) ====
$discount_percent = isset($_SESSION['discount_total']) ? $_SESSION['discount_total'] : 0;
$applied_coupon   = isset($_SESSION['last_coupon']) ? $_SESSION['last_coupon'] : '';

$discount_amount = ($discount_percent / 100) * $grand_total;
$final_total = $grand_total - $discount_amount;

// Optional: Handle coupon submission on checkout page too
if (isset($_POST['apply_coupon'])) {
    $promoCode = mysqli_real_escape_string($conn, $_POST['coupon_code']);
    $current_time = date("Y-m-d H:i:s");

    $coupon_query = mysqli_query($conn, "
        SELECT * FROM coupons 
        WHERE code = '$promoCode' 
        AND expires_at > '$current_time'
        LIMIT 1
    ");

    if (mysqli_num_rows($coupon_query) > 0) {
        $coupon_data = mysqli_fetch_assoc($coupon_query);

        // Stack discounts without locking (vulnerable)
        if (!isset($_SESSION['discount_total'])) {
            $_SESSION['discount_total'] = 0;
        }
        $_SESSION['discount_total'] += (float)$coupon_data['discount'];
        $_SESSION['last_coupon'] = $coupon_data['code'];

        $discount_percent = $_SESSION['discount_total'];
        $discount_amount = ($discount_percent / 100) * $grand_total;
        $final_total = $grand_total - $discount_amount;
    } else {
        $error_message = "Invalid or expired coupon code.";
    }
}

// Calculate final total with coupon
$discount_amount = ($discount_percent / 100) * $grand_total;
$final_total = $grand_total - $discount_amount;

if (isset($_POST['place_order'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $name = $fname . ' ' . $lname;
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['country'];
    $payment_method = $_POST['payment_method'];

    $cart_query = mysqli_query($conn, "SELECT * FROM cart");
    $product_name = [];

    if (mysqli_num_rows($cart_query) > 0) {
        while($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['name'] . ' (' . $product_item['quantity'] . ' )';
        }
    }
    $total_product = implode(', ', $product_name);

    $detail_query = mysqli_query($conn, "INSERT INTO orders 
    (fname, lname, phone, email, method, country, city, state, zip_code, total_meals, total_price, coupon_code, discount_percent) 
    VALUES 
    ('$fname', '$lname', '$phone', '$email', '$payment_method', '$country', '$city', '$state', '$zip_code', '$total_product', '$final_total', '$applied_coupon', '$discount_percent')");

    if ($cart_query && $detail_query) {
        mysqli_query($conn, "DELETE FROM cart");

        echo '
            <div class="order-message-container">
                <div class="message-container">
                    <div style="font-size: 50px; color: #27ae60; margin-bottom: 20px;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <h3>Thank you for shopping!</h3>
                    <div class="order-detail">
                        <span class="total">Total: $' . number_format($final_total, 2) . '</span>				
                    </div>
                    <div class="customer-details">
                        <p>Your name: <span>' . $name . '</span></p>
                        <p>Your email: <span>' . $email . '</span></p>
                        <p>Your phone: <span>' . $phone . '</span></p>
                        <p>Your address: <span>' . $address . '</span></p>
                        <p>Your payment method: <span>' . $payment_method . '</span></p>											
                    </div>
                    <a href="shop.php" class="btn">Continue Shopping</a>
                </div>
            </div>
        ';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="checkout.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/labs/font-awesome/5.15.4/css/all.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
	<script src="main.js"></script>
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
	<!-- Checkout Section -->
<section class="checkout">
  <h2 class="checkout-heading">Checkout</h2>
  <form method="POST" class="checkout-container">

    <!-- Left: Billing -->
    <div class="left-section">
      <h3>Billing Details</h3>
      <div class="inputBox"><input type="text" name="first_name" placeholder="First Name*" required></div>
      <div class="inputBox"><input type="text" name="last_name" placeholder="Last Name*" required></div>
      <div class="inputBox"><input type="text" name="company" placeholder="Company Name (optional)"></div>
      <div class="inputBox">
        <select name="country" required>
          <option value="">Country / Region*</option>
          <option value="Egypt">Egypt</option>
          <option value="USA">USA</option>
        </select>
      </div>
      <div class="inputBox"><input type="text" name="address" placeholder="Street Address*" required></div>
      <div class="inputBox"><input type="text" name="city" placeholder="Town / City*" required></div>
      <div class="inputBox">
        <select name="state" required>
          <option value="">State*</option>
          <option value="Cairo">Cairo</option>
          <option value="Giza">Giza</option>
        </select>
      </div>
      <div class="inputBox"><input type="text" name="zip_code" placeholder="ZIP*" required></div>
      <div class="inputBox"><input type="text" name="phone" placeholder="Phone*" required></div>
      <div class="inputBox"><input type="email" name="email" placeholder="Email Address*" required></div>
    </div>

    <!-- Right: Order + Payment -->

    <div class="right-section">
      <div class="order-summary">
        <h3>Your Order</h3>
        <table>
			<?php
			$select_cart = mysqli_query($conn, "SELECT * FROM cart");
			$grand_total = 0;

			while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
			    $item_total = $fetch_cart['price'] * $fetch_cart['quantity'];
			    $grand_total += $item_total;
			?>
<tr>
    <td>
        <img src="<?php echo $fetch_cart['image']; ?>" width="50px" height="50px" style="vertical-align: middle;">
        <span class="product_name" style="vertical-align: middle;"><?php echo $fetch_cart['name']; ?> x<?php echo $fetch_cart['quantity']; ?></span>
    </td>
    <td></td>    
    <td>$<?php echo number_format($item_total, 2); ?></td>
</tr>
			<?php
			}

			// Start with total
			$final_total = $grand_total;

			// Apply discount if coupon is set (vulnerable to reuse / race condition)
			if (isset($_SESSION['discount_percent'])) {
			    $discount_amount = ($grand_total * $_SESSION['discount_percent']) / 100;
			    $final_total -= $discount_amount;
			    ?>
			    <tr>
			        <td><strong>Discount (<?php echo $_SESSION['discount_percent']; ?>%)</strong></td>
			        <td></td>
			        <td>$<?php echo number_format($discount_amount, 2); ?></td>
			    </tr>
			    <?php
			}
			?>

			<tr>
			    <td><strong>Total</strong></td>
			    <td></td>
			    <td><strong>$<?php echo number_format($final_total, 2); ?></strong></td>
			</tr>
        </table>
      </div>

      <div class="payment-method">
        <h3>Payment Method</h3>
        <label class="payment-option">
          <input type="radio" name="payment_method" value="Credit Card" checked>
          <img src="site_images/atm-card.png"> Credit Card
        </label>
        <label class="payment-option"><input type="radio" name="payment_method" value="Paypal"><img src="site_images/paypal.png"> Paypal</label>
        <label class="payment-option"><input type="radio" name="payment_method" value="Google Pay"><img src="site_images/master_card.png"> Master Card</label>
        <label class="payment-option"><input type="radio" name="payment_method" value="Apple Pay"><img src="site_images/visa.png"> Visa</label>
      </div>

      <p class="privacy-note">
        Your personal data will be used to process your order, support your experience throughout this website, and for other purposes.
      </p>

      <button type="submit" class="btn" name="place_order">Place Order →</button>
    </div>
  </form>
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

</html>