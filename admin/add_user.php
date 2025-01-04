<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

include_once '../models/user_model.php'; // Include UserModel

// Create an instance of UserModel
$userModel = new UserModel();

// Initialize error message variable
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $role = $_POST['role']; // 'user' or 'admin'

    // Attempt to register the new user
    if ($userModel-> registerUserRole($username, $password, $email, $role)) {
        $success_message = "User added successfully!";
        // Optionally redirect or clear form fields here
    } else {
        $error_message = "Error occurred during user addition.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
    /* add_user_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Add User Container Styles */
.add-user-container {
    max-width: 500px; /* Maximum width for the container */
    margin: 0 auto; /* Center the container */
    padding: 20px; /* Padding inside the container */
    background-color: white; /* White background for the container */
    border-radius: 8px; /* Rounded corners for the container */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Heading Styles */
h2 {
    color: #333; /* Darker text color for headings */
    margin-bottom: 15px; /* Space below heading */
}

/* Message Styles */
p {
    margin: 10px 0; /* Margin above and below messages */
}

/* Input and Select Styles */
input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%; /* Full width inputs and select */
    padding: 10px; /* Padding inside inputs/selects */
    margin-bottom: 15px; /* Space below inputs/selects */
    border: 1px solid #ccc; /* Light grey border */
    border-radius: 4px; /* Rounded corners for inputs/selects */
}

/* Button Styles */
button {
    background-color: #007BFF; /* Blue background for button */
    color: white; /* White text color for button */
    border: none; /* Remove default border */
    padding: 10px; /* Padding inside button */
    border-radius: 5px; /* Rounded corners for button */
    cursor: pointer; /* Pointer cursor on hover */
    width: 100%; /* Full width button */
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover for button */
}

/* Link Styles */
a {
    color: #007BFF; /* Blue color for links */
    text-decoration: none; /* Remove underline from links */
}

a:hover {
    text-decoration: underline; /* Underline on hover for better visibility */
}

    </style>
    
</head>
<body>
    <div class="add-user-container">
        <h2>Add New User/Admin</h2>

        <!-- Display success or error message -->
        <?php if (!empty($success_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Add User</button>
        </form>

        <p><a href="manage_users.php">Back to User Management</a></p> <!-- Link back -->
    </div>
</body>
</html>
