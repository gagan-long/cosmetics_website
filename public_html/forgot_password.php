<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Include PHPMailer classes
require '../resources/PHPMailer/src/PHPMailer.php';
require '../resources/PHPMailer/src/SMTP.php';
require '../resources/PHPMailer/src/Exception.php';

// Use PHPMailer's namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
                    
                    // Send password reset email using PHPMailer
                    $mail = new PHPMailer();

                    try {
                        // SMTP Configuration
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Your SMTP server (e.g., smtp.mailtrap.io)
                        $mail->SMTPAuth = true;
                        $mail->Username = 'privatexyz2@gmail.com'; // Your SMTP username
                        $mail->Password = 'vijju@8475'; // Your SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable STARTTLS
                        $mail->Port = 587; // Use port 587 for STARTTLS

                        // Sender and recipient settings
                        $mail->setFrom('privatexyz2@gmail.com', 'meri site'); // Update with your name and email
                        $mail->addAddress($email); // Add recipient's email

                        // Email content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $reset_link = "http://localhost/cosmetics_website/public_html/reset_password.php?token=$token";
                        $mail->Body = "Click this link to reset your password: <a href='$reset_link'>$reset_link</a>";
                        
                        // Send the email
                        if ($mail->send()) {
                            echo "<p>A password reset link has been sent to your email address.</p>";
                        } else {
                            echo "<p>Failed to send the reset email. Please try again later.</p>";
                        }
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
   <style>
   /* Reset some default styles */
body, h2, p {
    margin: 0;
    padding: 0;
}

/* Set the body background color and font */
body {
    background-color: #f4f4f4;
    font-family: Arial, sans-serif;
    color: #333;
}

/* Center the container */
.forgot-password-container {
    max-width: 400px;
    margin: 100px auto; /* Center vertically and horizontally */
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Style the heading */
.forgot-password-container h2 {
    text-align: center;
    margin-bottom: 20px;
}

/* Style the form inputs */
.forgot-password-container input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Style the submit button */
.forgot-password-container button {
    width: 100%;
    padding: 10px;
    background-color: #007bff; /* Bootstrap primary color */
    color: white;
    border: none;
    border-radius: 4px;
}

.forgot-password-container button:hover {
    background-color: #0056b3; /* Darker shade of blue */
}

/* Style the link back to login */
.forgot-password-container p {
    text-align: center;
}

.forgot-password-container a {
    color: #007bff; /* Link color */
    text-decoration: none; /* Remove underline */
}

.forgot-password-container a:hover {
    text-decoration: underline; /* Underline on hover */
}

   </style>
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
