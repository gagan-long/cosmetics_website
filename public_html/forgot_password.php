<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? ''; // Use null coalescing operator to avoid undefined index notice

    // Validate email input
    if (empty($email)) {
        echo "<p>Please enter your email address.</p>";
    } else {
        // Prepare SQL statement to check if the email exists
        $sql = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $token = bin2hex(random_bytes(50)); // Generate a random token
                $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

                // Store token and expiry in the database
                $sql_update = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE user_id = ?";
                if ($stmt_update = $conn->prepare($sql_update)) {
                    $stmt_update->execute([$token, $expiry, $user['user_id']]);
                    
                    // Send password reset email
                    $reset_link = "http://localhost/cosmetics_website/public_html/reset_password.php?token=$token";
                    if (mail($email, "Password Reset Request", "Click this link to reset your password: $reset_link")) {
                        echo "<p>A password reset link has been sent to your email address.</p>";
                    } else {
                        echo "<p>Failed to send the reset email. Please try again later.</p>";
                    }
                } else {
                    echo "<p>Failed to generate reset token.</p>";
                }
            } else {
                echo "<p>No account found with that email address.</p>";
            }
        } else {
            echo "<p>Error preparing SQL statement.</p>";
        }
    }

    // Close connection
    $conn = null; // Close PDO connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
