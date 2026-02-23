<?php
include("db_connect.php");

if (isset($_POST['update_profile'])) {
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password = $_POST['password'];
    $current_email = $_SESSION['email'];

    $username = $firstName . ' ' . $lastName;

    $sql = "UPDATE users SET username='$username', email='$email' WHERE email='$current_email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        header("Location: profile.php?success=1");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}




?>
