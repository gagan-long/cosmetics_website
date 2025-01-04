<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../models/user_model.php'; // Include UserModel

// Create an instance of UserModel
$userModel = new UserModel();

// Get user ID from query string and sanitize input
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Attempt to delete the user
if ($user_id > 0) {
    if ($userModel->deleteUser($user_id)) {
        header("Location: manage_users.php"); // Redirect back after deletion
        exit();
    } else {
        echo "<p>Error occurred during user deletion.</p>";
    }
} else {
    echo "<p>Invalid user ID.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Delete User</title>
   <link rel="stylesheet" href="../resources/css/admin_styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="delete-user-container">
       <h2>Delete User</h2>
       <p>User deletion process completed.</p>
       <p><a href="manage_users.php">Back to Manage Users</a></p> <!-- Link back -->
   </div>
</body>
</html>
