<?php
include("db_connect.php");

if (!isset($_SESSION['email'])) {
    echo "You must be logged in to like or dislike.";
    exit;
}

$email = $_SESSION['email'];
$post_id = (int) $_POST['post_id'];
$action = $_POST['action']; // 'like' or 'dislike'

// Get user ID
$sql = "SELECT id FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$user_id = (int) $row['id'];

// Check existing vote
$sql = "SELECT type FROM likes WHERE user_id = $user_id AND post_id = $post_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $existing = mysqli_fetch_assoc($result)['type'];

    if ($existing === $action) {
        // User clicked same vote again ➝ remove it (unlike or undislike)
        $sql = "DELETE FROM likes WHERE user_id = $user_id AND post_id = $post_id";
        mysqli_query($conn, $sql);
    } else {
        // Change vote (like ➝ dislike or vice versa)
        $sql = "UPDATE likes SET type = '$action' WHERE user_id = $user_id AND post_id = $post_id";
        mysqli_query($conn, $sql);
    }
} else {
    // No vote before ➝ insert new one
    $sql = "INSERT INTO likes (user_id, post_id, type) VALUES ($user_id, $post_id, '$action')";
    mysqli_query($conn, $sql);
}

// Return updated stats
$sql = "SELECT 
    SUM(type = 'like') AS likes,
    SUM(type = 'dislike') AS dislikes 
    FROM likes 
    WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Return current user vote again
$sql = "SELECT type FROM likes WHERE user_id = $user_id AND post_id = $post_id LIMIT 1";
$result = mysqli_query($conn, $sql);
$userVote = '';
if ($result && mysqli_num_rows($result) > 0) {
    $userVote = mysqli_fetch_assoc($result)['type'];
}

echo json_encode([
    'likes' => (int)$row['likes'],
    'dislikes' => (int)$row['dislikes'],
    'userVote' => $userVote
]);
?>
    