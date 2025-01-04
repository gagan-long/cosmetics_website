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
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

   <?php include '../includes/header.php'; ?> <!-- Include the header -->

   <main class="h-full w-full">
       <section class="h-screen w-full bg-cover bg-center text-white p-12 " style="background-image: url('banner.png');">
           <h1 class="text-4xl mb-2">Welcome to My Cosmetics Store</h1>
           <p class="text-lg">Your one-stop shop for all beauty needs!</p>
           <a href="products.php" class="bg-white text-red-500 py-2 px-4 rounded hover:bg-red-300">Shop Now</a> <!-- Link to products page -->
       </section>

       <section class="py-8 px-4">
           <h2 class="text-center text-gray-800 text-2xl mb-6">Featured Products</h2>
           <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
               <?php while ($product = $result->fetch(PDO::FETCH_ASSOC)): ?>
                   <div class="bg-white rounded-lg shadow p-4 text-center">
                       <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-auto mb-3">
                       <h3 class="text-lg font-semibold mb-1"><?php echo htmlspecialchars($product['name']); ?></h3>
                       <p class="mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                       <p class="mb-4">Price: $<?php echo number_format($product['price'], 2); ?></p>
                       <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="text-blue-500 hover:underline">View Details</a> <!-- Link to product detail page -->
                   </div>
               <?php endwhile; ?>
           </div>
       </section>

       <section class="text-center py-8">
           <h2 class="text-gray-800 text-xl mb-4">Get Started</h2>
           <?php if (isset($_SESSION['user_id'])): ?>
               <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
               <a href="logout.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-400">Logout</a> <!-- Logout link -->
           <?php else: ?>
               <p><a href="login.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-400">Login</a></p> <!-- Link to login page -->
               <p><a href="register.php" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-400">Register</a></p> <!-- Link to register page -->
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
