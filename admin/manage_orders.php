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

// Fetch all orders using the model method.
$orders = [];
try {
    $orders = $orderModel->getAllOrders(); // Fetch all orders
} catch (Exception $e) {
    die("Error fetching orders: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
    /* manage_orders_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Order Management Container Styles */
.order-management-container {
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

/* Select Styles in Table Cells */
td form select {
    padding: 5px; /* Padding inside select dropdowns */
}

/* No Orders Found Message Styles */
td[colspan="6"] {
    text-align: center; /* Center align message when no orders are found */
    font-style: italic; /* Italicize message text */
}

    </style>
</head>
<body>
    <div class="order-management-container">
    <a href="index.php">Dashboard</a>
        <h2>Manage Orders</h2>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo number_format((float)$order['total_amount'], 2); ?></td>
                    <td>
                        <form action="update_order_status.php" method="POST">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                        </form>
                    </td>
                    <td><a href="delete_order.php?id=<?php echo htmlspecialchars($order['order_id']); ?>">Delete</a></td> <!-- Delete link -->
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No orders found.</td></tr> <!-- Message when no orders are available -->
            <?php endif; ?>
        </table>

        <!-- Optionally add a link to create a new order -->
        <!-- <p><a href="add_order.php">Add New Order</a></p> -->
    </div>

    <!-- Optional footer -->
</body>
</html>

