<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Ensure this file contains the getDatabaseConnection() function

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? ''; // Use null coalescing operator to avoid undefined index notice
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username = ? AND role = 'user'";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindValue(1, $username, PDO::PARAM_STR);
            if ($stmt->execute()) { 
                if ($stmt->rowCount() > 0) { 
                    // Fetch user data
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $user['password'])) {
                        // Successful login
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['username'] = $user['username'];
                        header("Location: index.php"); // Redirect to user dashboard or homepage
                        exit();
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "No such user found.";
                }
            }
            // Close statement
            // $stmt->close();
        }
    }

    // Close connection (optional, as the connection will close when the script ends)
    $conn = null; // Close PDO connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="../resources/css/userlogin.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="register.php">Don't have an account? Register here.</a></p> <!-- Link to register page -->
        <p><a href="../admin/login.php">Login as Admin .</a></p> <!-- Link to register page -->
        <p><a href="../public_html/index.php">Go to Website</a></p> <!-- Optional link back to website -->
    </div>
</body>
</html>
