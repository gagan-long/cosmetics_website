<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Fetch all products from the database using ProductModel
include_once '../models/product_model.php'; // Include ProductModel
$productModel = new ProductModel();
$products = $productModel->getAllProducts(); // Fetch all products

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="../resources/css/products.css"> <!-- Link to your CSS -->
</head>
<body>

    <?php include '../includes/header.php'; ?> <!-- Include the header -->

    <main>
        <h2>Our Products</h2>
        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card" >
                        <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product_detail.php?id=<?php echo $product['product_id']; ?>">View Details</a> <!-- Link to product detail page -->
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available at this time.</p> <!-- Message when no products are available -->
            <?php endif; ?>
        </div>
    </main>

 

</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
$conn = null; // Close PDO connection
?>
