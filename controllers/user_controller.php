<?php
session_start();
include_once '../models/user_model.php'; // Include UserModel for authentication

// Initialize error message variable
$error_message = "";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve username and password from POST data
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Create an instance of UserModel
    $userModel = new UserModel();
    
    // Attempt to log in the user
    $user = $userModel->loginUser($username, $password);

    // Check if user exists and has admin role
    if ($user && $user['role'] === 'admin') {
        // Set session variables for admin
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'admin'; // Set role to admin

        // Redirect to admin dashboard
        header("Location: index.php");
        exit();
    } else {
        // Set error message for invalid login
        $error_message = "Invalid username or password, or you do not have admin access.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>

<h2>Admin Login</h2>

<!-- Display error message if set -->
<?php if (!empty($error_message)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<!-- Login form -->
<form action="" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>
