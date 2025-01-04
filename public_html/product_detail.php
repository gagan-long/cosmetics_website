<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Include ProductModel
include_once '../models/product_model.php'; // Include ProductModel
include_once '../models/user_model.php'; // Include UserModel

$productModel = new ProductModel();
$userModel = new UserModel(); // Instantiate UserModel

// Get product ID from query string and validate it
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details from the database using ProductModel
$product = $productModel->getProductById($product_id); // Fetch product by ID

if ($product === null) {
    echo "<p>Product not found.</p>";
    exit();
}

// Handle adding the product to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or update product in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

   header("Location: cart.php"); // Redirect to cart page after adding
   exit();
}

// Handle form submission to add a new review
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
   $user_id = $_SESSION['user_id'];
   $review_text = $_POST['review_text'] ?? '';
   $rating = $_POST['rating'] ?? 0;

   // Prepare SQL statement to insert the new review
   $sql_insert = "INSERT INTO reviews (product_id, user_id, review_text, rating) VALUES (?, ?, ?, ?)";
   $stmt_insert = $userModel->getConnection()->prepare($sql_insert);
    
   if ($stmt_insert->execute([$product_id, $user_id, htmlspecialchars($review_text), intval($rating)])) {
       echo "<p>Review submitted successfully!</p>";
   } else {
       echo "<p>Error occurred while submitting your review.</p>";
   }
}

// Fetch reviews for the product
$sql_reviews = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ?";
$stmt_reviews = $userModel->getConnection()->prepare($sql_reviews);
$stmt_reviews->execute([$product_id]);
$result_reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($product['name']); ?></title>
   <link rel="stylesheet" href="../resources/css/productdetail.css"> <!-- Link to your CSS -->
</head>
<body>

   <?php include '../includes/header.php'; ?> <!-- Include the header -->

   <main>
       <h2><?php echo htmlspecialchars($product['name']); ?></h2>
       <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
       <p><?php echo htmlspecialchars($product['description']); ?></p>
       <p>Price: $<?php echo number_format($product['price'], 2); ?></p>

       <form action="" method="POST">
           <input type="number" name="quantity" value="1" min="1"> <!-- Quantity input -->
           <button type="submit" name="add_to_cart">Add to Cart</button> <!-- Button to add to cart -->
       </form>

       <section class="reviews">
           <h3>Reviews</h3>
           <?php if (count($result_reviews) > 0): ?>
               <ul>
                   <?php foreach ($result_reviews as $review): ?>
                       <li>
                           <strong><?php echo htmlspecialchars($review['username']); ?></strong>: 
                           <span><?php echo htmlspecialchars($review['review_text']); ?></span>
                           <br>
                           <em>Rating: <?php echo htmlspecialchars($review['rating']); ?>/5</em>
                       </li>
                   <?php endforeach; ?>
               </ul>
           <?php else: ?>
               <p>No reviews yet. Be the first to review this product!</p>
           <?php endif; ?>
       </section>

       <?php if (isset($_SESSION['user_id'])): ?>
           <section class="submit-review">
               <h3>Submit Your Review</h3>
               <form action="" method="POST">
                   <textarea name="review_text" placeholder="Write your review..." required></textarea>
                   <br>
                   <label for="rating">Rating:</label>
                   <select name="rating" required>
                       <option value="1">1 Star</option>
                       <option value="2">2 Stars</option>
                       <option value="3">3 Stars</option>
                       <option value="4">4 Stars</option>
                       <option value="5">5 Stars</option>
                   </select>
                   <br>
                   <button type="submit" name="submit_review">Submit Review</button> <!-- Button to submit review -->
               </form>
           </section>
       <?php else: ?>
           <p>You must be logged in to submit a review.</p>
           <a href="login.php" class="btn">Login</a> <!-- Link to login page -->
       <?php endif; ?>
       
   </main>

   <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
$conn = null; // Close PDO connection
?>
