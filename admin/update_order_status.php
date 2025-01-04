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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = isset($_POST['status']) ? htmlspecialchars($_POST['status']) : '';

    // Validate input
    if (empty($order_id) || empty($status)) {
        echo "<p>Order ID and status are required.</p>";
        exit();
    }

    // Update order status using the model method
    if ($orderModel->updateOrderStatus($order_id, $status)) {
        header("Location: manage_orders.php"); // Redirect back after updating
        exit();
    } else {
        echo "<p>Error occurred during order status update.</p>";
    }
}

// Close connection (optional, as the connection will close when the script ends)
$orderModel->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Order Status</title>
   <link rel="stylesheet" href="../resources/css/admin_styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="update-order-container">
       <h2>Update Order Status</h2>
       <form action="" method="POST">
           <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
           <select name="status" required>
               <option value="Pending">Pending</option>
               <option value="Processing">Processing</option>
               <option value="Shipped">Shipped</option>
               <option value="Delivered">Delivered</option>
               <option value="Cancelled">Cancelled</option>
           </select>
           <button type="submit">Update Status</button>
       </form>
       <p><a href="manage_orders.php">Back to Manage Orders</a></p> <!-- Link back -->
   </div>
</body>
</html>
