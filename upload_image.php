<?php
include("db_connect.php");
session_start(); // Make sure session is started

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['upload']) && isset($_FILES['profile_image'])) {
    $imageName = $_FILES['profile_image']['name'];
    $tmpFile = $_FILES['profile_image']['tmp_name'];
    $imageSize = $_FILES['profile_image']['size'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    $allowedExtensions = array('jpeg', 'png', 'jpg');

    if (in_array($imageExtension, $allowedExtensions)) {
        if ($imageSize < 2 * 1024 * 1024) { // 2MB

            $uploadDir = 'site_images/profile_images/';
            $newFileName = $imageName . '.' . $imageExtension;
            $imagePath = $uploadDir . $newFileName;

            if (move_uploaded_file($tmpFile, $imagePath)) {
                $email = $_SESSION['email'];
                $sql = "UPDATE users SET profile_image = '$newFileName' WHERE email = '$email'";
                if ($conn->query($sql) === TRUE) {
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Database error: " . $conn->error;
                }
            } else {
                echo "Failed to move uploaded file.";
            }

        } else {
            echo "File too large (max 2MB).";
        }
    } else {
        echo "Invalid file type. Allowed: jpg, jpeg, png.";
    }
} else {
    echo "No file uploaded or an error occurred.";
}
?>
