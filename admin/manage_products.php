<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

include_once '../models/product_model.php'; // Include ProductModel

// Create an instance of ProductModel
$productModel = new ProductModel();

// Fetch all products using the model method.
$products = [];
try {
    $products = $productModel->getAllProducts(); // Corrected method call
} catch (Exception $e) { 
    die("Error fetching products: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
    /* product_management_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Product Management Container Styles */
.product-management-container {
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

/* Image Styles in Table Cells */
td img {
    max-width: 100px; /* Maximum width for product images */
    height: auto; /* Maintain aspect ratio of images */
}

/* No Products Found Message Styles */
td[colspan="7"] {
    text-align: center; /* Center align message when no products are found */
    font-style: italic; /* Italicize message text */
}

    </style>
</head>
<body>
<div class="product-management-container">
<h2>Manage Products</h2>

<a href="index.php">Dashboard</a>
<!-- Link to add new product -->
<p><a href="add_product.php">Add New Product</a></p>

<table>
<tr>
<th>Product ID</th>
<th>Name</th>
<th>Description</th>
<th>Price</th>
<th>Stock</th>
<th>Image</th> <!-- New column for images -->
<th>Actions</th>
</tr>

<?php if (!empty($products)): ?>
    <?php foreach ($products as $product): ?>
    <tr>
        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
        <td><?php echo htmlspecialchars($product['name']); ?></td>
        <td><?php echo htmlspecialchars($product['description']); ?></td>
        <td><?php echo number_format((float)$product['price'], 2); ?></td>
        <td><?php echo htmlspecialchars($product['stock']); ?></td>
        <td><img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100px; height: auto;"></td> <!-- Display product image -->
        <td>
            <a href="edit_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Edit</a> |
            <a href="delete_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="7">No products found.</td></tr> <!-- Message when no products are available -->
<?php endif; ?>

</table>

</div>

<!-- Optional footer -->
</body>
</html>
