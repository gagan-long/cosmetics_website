<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Fetch featured products from the database (you can modify the query to suit your needs)
$sql = "SELECT * FROM products LIMIT 8"; // Fetching first 8 products for display
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Welcome to My Cosmetics Store</title>
   <link rel="stylesheet" href="../resources/css/indexhome.css"> <!-- Link to your CSS -->
</head>
<body>

   <?php include '../includes/header.php'; ?> <!-- Include the header -->

   <main>
       <section class="hero">
           <h1>Welcome to My Cosmetics Store</h1>
           <p>Your one-stop shop for all beauty needs!</p>
           <a href="products.php" class="btn">Shop Now</a> <!-- Link to products page -->
       </section>

       <section class="featured-products">
           <h2>Featured Products</h2>
           <div class="product-grid">
               <?php while ($product = $result->fetch(PDO::FETCH_ASSOC)): ?>
                   <div class="product-card">
                       <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                       <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                       <p><?php echo htmlspecialchars($product['description']); ?></p>
                       <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                       <a href="product_detail.php?id=<?php echo $product['product_id']; ?>">View Details</a> <!-- Link to product detail page -->
                   </div>
               <?php endwhile; ?>
           </div>
       </section>

       <section class="user-actions">
           <h2>Get Started</h2>
           <?php if (isset($_SESSION['user_id'])): ?>
               <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
               <a href="logout.php" class="btn">Logout</a> <!-- Logout link -->
           <?php else: ?>
               <p><a href="login.php" class="btn">Login</a></p> <!-- Link to login page -->
               <p><a href="register.php" class="btn">Register</a></p> <!-- Link to register page -->
           <?php endif; ?>
       </section>

   </main>

   
   <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
$conn = null; // Close PDO connection
?>

