<?php
session_start();
include_once '../includes/db_connection.php'; // Include database connection
include_once '../models/user_model.php'; // Include UserModel

// Instantiate UserModel to access database methods
$userModel = new UserModel();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);

// Fetch user's order history (if applicable)
$sql_orders = "SELECT * FROM orders WHERE user_id = ?";
$stmt_orders = $userModel->getConnection()->prepare($sql_orders);
$stmt_orders->execute([$user_id]);
$result_orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

// Fetch user's shipping address from session
$shipping_address = isset($_SESSION['shipping_address']) ? $_SESSION['shipping_address'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../resources/css/user_dashboard.css"> <!-- Link to your CSS -->
</head>
<body>

    <?php include '../includes/header.php'; ?> <!-- Include the header -->

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>

        <section class="profile">
            <h3>Your Profile</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <!-- You can add more profile fields here -->
        </section>

        <section class="shipping-address">
            <h3>Your Shipping Address</h3>
            <?php if ($shipping_address): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($shipping_address['name']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($shipping_address['address']); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($shipping_address['city']); ?></p>
                <p><strong>State:</strong> <?php echo htmlspecialchars($shipping_address['state']); ?></p>
                <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($shipping_address['zip']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($shipping_address['country']); ?></p>
                <a href="shipping_address.php" class="btn">Update Shipping Address</a> <!-- Link to update address -->
            <?php else: ?>
                <p>You have not set a shipping address yet.</p>
                <a href="shipping_address.php" class="btn">Add Shipping Address</a> <!-- Link to add address -->
            <?php endif; ?>
        </section>

        <section class="order-history">
            <h3>Your Order History</h3>
            <?php if (count($result_orders) > 0): ?>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($result_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>You have no orders yet.</p>
            <?php endif; ?>
        </section>

        <section class="logout">
            <a href="logout.php" class="btn">Logout</a> <!-- Logout link -->
        </section>

    </main>

    <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>
