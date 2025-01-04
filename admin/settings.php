<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Include the database connection function
include_once '../includes/db_connection.php';

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Fetch current settings from the database (assuming you have a settings table)
$sql = "SELECT * FROM settings"; // Adjust this query based on your actual settings table structure
$stmt = $conn->prepare($sql);
$stmt->execute();
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$settings_array = [];
foreach ($settings as $row) {
    $settings_array[$row['setting_key']] = $row['setting_value'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update settings based on form input
    foreach ($_POST as $key => $value) {
        $sql_update = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
        if ($stmt = $conn->prepare($sql_update)) {
            $stmt->bindValue(1, htmlspecialchars($value), PDO::PARAM_STR);
            $stmt->bindValue(2, htmlspecialchars($key), PDO::PARAM_STR);
            if ($stmt->execute()) {
                echo "<p>Settings updated successfully!</p>";
            } else {
                echo "<p>Error updating setting for {$key}.</p>";
            }
            // $stmt->close();
        }
    }
}

// Close connection (optional)
$conn = null; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>
    <style>
    /* site_settings_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Settings Container Styles */
.settings-container {
    max-width: 600px; /* Maximum width for the container */
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

/* Label Styles */
label {
    display: block; /* Block display for labels to stack vertically */
    margin-bottom: 5px; /* Space below labels */
    font-weight: bold; /* Bold text for labels */
}

/* Input Styles */
input[type="text"],
input[type="email"] {
    width: 100%; /* Full width inputs */
    padding: 10px; /* Padding inside inputs */
    margin-bottom: 15px; /* Space below inputs */
    border: 1px solid #ccc; /* Light grey border */
    border-radius: 4px; /* Rounded corners for inputs */
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

/* Message Styles (for success/error messages) */
p {
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="settings-container">
        <h2>Site Settings</h2>
        <form action="" method="POST">
            <label for="site_name">Site Name:</label>
            <input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($settings_array['site_name'] ?? ''); ?>" required>

            <label for="admin_email">Admin Email:</label>
            <input type="email" name="admin_email" id="admin_email" value="<?php echo htmlspecialchars($settings_array['admin_email'] ?? ''); ?>" required>

            <label for="default_currency">Default Currency:</label>
            <input type="text" name="default_currency" id="default_currency" value="<?php echo htmlspecialchars($settings_array['default_currency'] ?? ''); ?>" required>

            <!-- Add more settings fields as needed -->

            <button type="submit">Save Settings</button>
        </form>
        <p><a href="index.php">Back to Dashboard</a></p> <!-- Link back to dashboard -->
    </div>
</body>
</html>
