<?php
session_start();
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to prevent SQL injection
    $sql_check = "SELECT * FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$username]);
    $result_check = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

    if (count($result_check) > 0) {
        echo "<p style='color:red;'>Username already exists. Please choose another one.</p>";
    } else {
        // Insert new user into the database
        $sql_insert = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')";
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            if ($stmt_insert->execute([$username, $hashed_password, $email])) {
                echo "<p>Registration successful! You can now log in.</p>";
            } else {
                echo "<p>Error occurred during registration.</p>";
            }
        }
    }

    // Close statements (optional for PDO)
    $stmt_check = null;
    $stmt_insert = null;
}

// Close connection (optional)
$conn = null; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../resources/css/userregister.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="registration-container">
        <h2>User Registration</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Already have an account? Login here.</a></p> <!-- Link to register page -->
        <p><a href="../public_html/index.php">Go to Website</a></p> <!-- Optional link back to website -->
    </div>
</body>
</html>

