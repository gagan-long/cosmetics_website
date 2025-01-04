<?php
session_start();
include_once '../includes/db_connection.php'; // Include database connection
include_once '../models/user_model.php'; // Include UserModel
include_once '../models/product_model.php'; // Include ProductModel

// Instantiate models
$userModel = new UserModel();
$productModel = new ProductModel();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle form submission to update cart quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity); // Ensure quantity is an integer
        if ($quantity <= 0) {
            // Remove product from cart if quantity is 0 or less
            unset($_SESSION['cart'][$product_id]);
        } else {
            // Update quantity in cart
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}

// Fetch products in the cart from the database
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    
    // Use PDO for executing the query safely (prepared statement)
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    
    // Use the public method to get the database connection
    $stmt = $userModel->getConnection()->prepare($sql);
    $stmt->execute();
    
    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product['quantity'] = $_SESSION['cart'][$product['product_id']];
        $cart_items[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../resources/css/cart.css"> <!-- Link to your CSS -->
</head>
<body>

    <?php include '../includes/header.php'; ?> <!-- Include the header -->

    <main>
        <h2>Your Shopping Cart</h2>

        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty. <a href="products.php">Continue shopping</a>.</p>
        <?php else: ?>
            <form action="" method="POST">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['product_id']; ?>]" value="<?php echo htmlspecialchars($_SESSION['cart'][$item['product_id']]); ?>" min="0">
                            </td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>$<?php echo number_format($item['price'] * $_SESSION['cart'][$item['product_id']], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <button type="submit">Update Cart</button> <!-- Button to update quantities -->
            </form>

            <div class="checkout">
                <h3>Checkout</h3>

                <?php if (isset($_SESSION['shipping_address'])): ?>
                    <p><a href="checkout.php" class="btn">Proceed to Checkout</a></p> <!-- Link to checkout page -->
                <?php else: ?>
                    <p>You need to add a shipping address before checking out.</p>
                    <p><a href="shipping_address.php" class="btn">Add Shipping Address</a></p> <!-- Link to add shipping address -->
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
// No need for this line as PDO connections are automatically closed at the end of the script.
$conn = null; 
?>
