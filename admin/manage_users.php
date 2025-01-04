<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../models/user_model.php'; // Include UserModel for fetching users

// Create an instance of UserModel
$userModel = new UserModel();

// Fetch all users
try {
    $users = $userModel->getAllUsers(); // Fetch users from the model
} catch (Exception $e) {
    die("Error fetching users: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../resources/css/manageuser.css"> 
    <style>
    

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* User Management Container Styles */
.user-management-container {
    max-width: 900px; /* Maximum width for the container */
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

/* Link Styles */
a {
    color: #007BFF; /* Blue color for links */
    text-decoration: none; /* Remove underline from links */
}

a:hover {
    text-decoration: underline; /* Underline on hover for better visibility */
}

/* Table Styles */
table {
    width: 100%; /* Full width table */
    border-collapse: collapse; /* Collapse borders between cells */
    margin-top: 20px; /* Space above table */
}

th, td {
    padding: 12px; /* Padding inside table cells */
    text-align: left; /* Left align text in cells */
    border-bottom: 1px solid #ddd; /* Light grey border below each row */
}

th {
    background-color: #007BFF; /* Blue background for table headers */
    color: white; /* White text color for headers */
}

tr:hover {
    background-color: #f1f1f1; /* Light grey background on row hover */
}

/* No Users Found Message Styles */
td[colspan="5"] {
    text-align: center; /* Center align message when no users are found */
    font-style: italic; /* Italicize message text */
}

    </style><!-- Link to your CSS -->
</head>
<body>
    <div class="user-management-container">
        <h2>Manage Users</h2>
        <!-- Link to add new user -->
        <p><a href="add_user.php">Add New User/Admin</a></p>
        <a href="index.php">Dashboard</a>
        
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php if ($users): ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">Edit</a> |
                        <a href="delete_user.php?id=<?php echo htmlspecialchars($user['user_id']); ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr> <!-- Message when no users are available -->
            <?php endif; ?>
        </table>

    </div>

    <!-- Optional footer -->
</body>
</html>

