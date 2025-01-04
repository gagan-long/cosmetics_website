<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // Redirect to login if not authenticated
    exit();
}

// Include necessary models for fetching statistics
include_once '../models/product_model.php'; // Include ProductModel
include_once '../models/order_model.php';   // Include OrderModel
include_once '../models/user_model.php';    // Include UserModel

// Create instances of models
$productModel = new ProductModel();
$orderModel = new OrderModel();
$userModel = new UserModel();

// Fetch statistics
$totalProducts = count($productModel->getAllProducts());
$totalOrders = count($orderModel->getAllOrders());
$totalUsers = count($userModel->getAllUsers());

// Include header from the includes directory
include_once '../includes/admin_header.php'; // Adjusted path for header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- <link rel="stylesheet" href="../resources/css/header.css"> -->
    <link rel="stylesheet" href="../resources/css/adminindex.css">
    
    </head>
<body>

<main>
    <h2>Welcome, Admin!</h2>
    <p>Select an option from the menu to manage the application.</p>

    <!-- Statistics Overview -->
    <section class="stats">
        <h3>Statistics Overview</h3>
        <ul>
            <li>Total Products: <?php echo $totalProducts; ?></li>
            <li>Total Orders: <?php echo $totalOrders; ?></li>
            <li>Total Users: <?php echo $totalUsers; ?></li>
        </ul>
    </section>

</main>

<?php include_once '../includes/admin_footer.php'; // Adjusted path for footer ?>

</body>
</html>
