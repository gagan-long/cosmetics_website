<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get new password and hash it
                $new_password = $_POST['password'] ?? ''; // Use null coalescing operator to avoid undefined index notice

                if (empty($new_password)) {
                    echo "<p>Please enter a new password.</p>";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update user's password and clear the reset token
                    $sql_update = "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
                    if ($stmt_update = $conn->prepare($sql_update)) {
                        $stmt_update->execute([$hashed_password, $token]);
                        echo "<p>Password has been successfully reset. You can now <a href='login.php'>login</a>.</p>";
                    } else {
                        echo "<p>Failed to reset password.</p>";
                    }
                }
            }
        } else {
            echo "<p>This token is invalid or has expired.</p>";
        }
    } else {
        echo "<p>Failed to prepare SQL statement.</p>";
    }
} else {
    echo "<p>No token provided.</p>";
}

// Close connection
$conn = null; // Close PDO connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password</title>
   <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="reset-password-container">
       <h2>Reset Password</h2>
       <form action="" method="POST">
           <input type="password" name="password" placeholder="Enter new password" required>
           <button type="submit">Reset Password</button>
       </form>
   </div>
</body>
</html>
