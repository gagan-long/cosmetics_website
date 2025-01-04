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

// Handle form submission to add a new review
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // Prepare SQL statement to insert the new review
    $sql_insert = "INSERT INTO reviews (product_id, user_id, review_text, rating) VALUES (?, ?, ?, ?)";
    $stmt_insert = $user_model->getDatabaseConnection()->prepare($sql_insert);
    
    if ($stmt_insert->execute([$product_id, $user_id, $review_text, $rating])) {
        echo "<p>Review submitted successfully!</p>";
    } else {
        echo "<p>Error occurred while submitting your review.</p>";
    }
}

// Fetch product ID from query string
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch product details (for display purposes)
$sql_product = "SELECT * FROM products WHERE product_id = ?";
$stmt_product = $user_model->getDatabaseConnection()->prepare($sql_product);
$stmt_product->execute([$product_id]);
$result_product = $stmt_product->fetch(PDO::FETCH_ASSOC);

if (!$result_product) {
    echo "<p>Product not found.</p>";
    exit();
}

// Fetch reviews for the product
$sql_reviews = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ?";
$stmt_reviews = $user_model->getDatabaseConnection()->prepare($sql_reviews);
$stmt_reviews->execute([$product_id]);
$result_reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?php echo htmlspecialchars($result_product['name']); ?></title>
    <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>

    <?php include '../includes/header.php'; ?> <!-- Include the header -->

    <main>
        <h2>Reviews for <?php echo htmlspecialchars($result_product['name']); ?></h2>

        <section class="reviews">
            <h3>Existing Reviews</h3>
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

        <section class="submit-review">
            <h3>Submit Your Review</h3>
            <form action="" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
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
                <button type="submit">Submit Review</button> <!-- Button to submit review -->
            </form>
        </section>

    </main>

    <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>
