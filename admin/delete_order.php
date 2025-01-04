<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../models/order_model.php'; // Include OrderModel

// Create an instance of OrderModel
$orderModel = new OrderModel();

// Get order ID from query string and sanitize input
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if order ID is valid
if ($order_id <= 0) {
    echo "<p>Invalid order ID.</p>";
    exit();
}

// Attempt to delete the order
if ($orderModel->deleteOrder($order_id)) { // Correct method name here
    header("Location: manage_orders.php?message=Order deleted successfully."); // Redirect back after deletion with a success message
    exit();
} else {
    echo "<p>Error occurred during order deletion. Please try again.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Delete Order</title>
   <link rel="stylesheet" href="../resources/css/admin_styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="delete-order-container">
       <h2>Delete Order</h2>
       <p>Order deletion process completed.</p>
       <p><a href="manage_orders.php">Back to Manage Orders</a></p> <!-- Link back -->
   </div>
</body>
</html>
