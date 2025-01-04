<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../models/product_model.php'; // Include ProductModel

// Create an instance of ProductModel
$productModel = new ProductModel();

// Get product ID from query string and sanitize input
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the current product's image URL before deletion
$product = $productModel->getProductById($product_id);
if (!$product) {
    echo "<p>No such product found.</p>";
    exit();
}

// Attempt to delete the product
if ($productModel->deleteProduct($product_id)) {
    // Remove the associated image from the server (if it exists)
    if (!empty($product['image_url'])) {
        $image_path = "../resources/images/products/" . htmlspecialchars($product['image_url']);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    header("Location: manage_products.php"); // Redirect back after deletion
    exit();
} else {
    echo "<p>Error occurred during product deletion.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Delete Product</title>
   <link rel="stylesheet" href="../resources/css/admin_styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="delete-product-container">
       <h2>Delete Product</h2>
       <p>Product deletion process completed.</p>
       <p><a href="manage_products.php">Back to Manage Products</a></p> <!-- Link back -->
   </div>
</body>
</html>
