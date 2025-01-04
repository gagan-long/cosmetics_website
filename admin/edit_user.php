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

// Get user ID from query string
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch user details for editing
$user = $userModel->getUserById($user_id);

if (!$user) {
    echo "<p>User not found.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated form data and sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role']; // 'user' or 'admin'

    // Prepare SQL statement for updating the user
    if ($userModel->updateUser($user_id, $username, $email, $role)) {
        echo "<p>User updated successfully!</p>";
        header("Location: manage_users.php"); // Redirect back after updating
        exit();
    } else {
        echo "<p>Error occurred during user update.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit User</title>
   
   <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .edit-user-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    input[type="text"],
    input[type="email"],
    select {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    select:focus {
        border-color: #007BFF; /* Bootstrap primary color */
        outline: none;
    }

    button {
        padding: 10px;
        background-color: #007BFF; /* Bootstrap primary color */
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3; /* Darker shade for hover effect */
    }

    p {
        text-align: center;
        margin-top: 20px;
    }

    a {
        color: #007BFF; /* Link color */
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline; /* Underline on hover */
    }
</style>

   
</head>
<body>
   <div class="edit-user-container">
       <h2>Edit User</h2>
       <form action="" method="POST">
           <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required placeholder="Username">
           <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required placeholder="Email">
           <select name="role" required>
               <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
               <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
           </select>
           <button type="submit">Update User</button>
       </form>
       <p><a href="manage_users.php">Back to Manage Users</a></p> <!-- Link back -->
   </div>
</body>
</html>
