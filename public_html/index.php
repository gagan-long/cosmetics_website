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
   <link rel="stylesheet" href="../resources/css/indexhome.css">    

</head>
<body class="body">
    
    
    <main class="main">
       <?php include '../includes/header.php'; ?> <!-- Include the header -->
       
     <section class="banner">     
           <div class="inner-banner">
               <h3>new release</h3>
                <h1>merallics <br><em>Shine </em> on</h1>
                <p>Get to know our new eyeshaow palettes with a glossy finish, smooth lightweight feel and 10 hour stay-on </p>
                <a href="products.php" class="shop-now">Shop Now</a> <!-- Link to products page -->
            </div>
    </section>

    <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php while ($product = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="product-card">
                        <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="view-details">View Details</a> <!-- Link to product detail page -->
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        
    <section id="sec2">
            <div id="sec2-d1">
                <div  id="p3">
                <h3>hot & spicy</h3>
                <h1>  Most <em> Vibrant</em> <br>Lips in Town</h1>
                <p>I'm a paragraph. Click here to add your own text and edit me. I’m a great place for you to tell a story and let your users know a little more about you. </p>
                
                <a href="products.php" >Shop Now</a>
            </div>
            </div>
            <!-- <div id="sec2-d2"> -->
                <div id="p3-img">
                    <img src="../resources/images/sec2.jpg" alt="">
                </div>
            <!-- </div> -->
    </section>
    <section class="featured-products">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php while ($product = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="product-card">
                        <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" class="view-details">View Details</a> <!-- Link to product detail page -->
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
     <section id="sec4">
            <div id="sec4d1">
                <h1>Discover </h1> <p id="sec4-i"> more</p>
            </div>
            <div id="sec4d2"></div>
            <div class="sec4-img" id="sec4-img1"><div class="sec4-in-text">
                <h3>Shop</h3>
                <h1>apna</h1>
            </div></div>
            <div class="sec4-img" id="sec4-img2"><div class="sec4-in-text">
                <h3>Shop</h3>
                <h1>time</h1>
            </div></div>
            <div class="sec4-img" id="sec4-img3"><div class="sec4-in-text">
                <h3>Shop</h3>
                <h1>Aayega</h1>
            </div></div>
        </section>

    <section id="sec5">
            <div id="sec51"></div>
            <div id="sec52">
               <p > THIS WEEKEND ONLY</p>
                
                <h2>15% Off Sitewide</h2>
               <p id="sec5p2"> I'm a paragraph. Click here to add your own text and edit me. I’m a great place for you to tell a story and let your users know a little more about you. </p>
                
                <h4>Use code LOVE15 at checkout</h4>
                
                <a href="products.php" class="shop-now">Shop Now</a>
            </div>
        </section>

     <section id="sec6">
            <div id="sec61">
                <p>WE BELIEVE</p>

                <h3>Your<h3 id="sec6p2"> Skin</h3></h3> 
                <h3>Comes First</h3>
            </div>
    <div id="sec62">
               <p> I'm a paragraph. Click here to add your own text and edit me. It’s easy. Just click “Edit Text” or double click me to add your own content and make changes to the font. Feel free to drag and drop me anywhere you like on your page. I’m a great place for you to tell a story and let your users know a little more about you.</p>



                <p>This is a great space to write a long text about your company and your services. You can use this space to go into a little more detail about your company. Talk about your team and what services you provide. Tell your visitors the story of how you came up with the idea for your business and what makes you different from your competitors. Make your company stand out and show your visitors who you are.
            </p>
        <a href="#">Join Noelle's Club</a>
            </div>
        </section>

    <section id="sec7">
            <h1>Follow Us</h1>
            <p>@beauty.store</p>
            <div class="scroller">
                <img src="../resources/images/img/pexels-pixabay-458766.jpg" alt="">
                <img src="../resources/images/img/pexels-anastasiya-gepp-654466-1462637.jpg" alt="">
                <img src="../resources/images/img/pexels-designecologist-1367225.jpg" alt="">
                <img src="../resources/images/img/banner.JPG" alt="">
                <img src="../resources/images/img/pexels-pixabay-458766.jpg" alt="">
            </div>
        </section>

       <!-- <section class="get-started">
           <h2>Get Started</h2>
           <?php if (isset($_SESSION['user_id'])): ?>
               <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
               <a href="logout.php" class="logout">Logout</a> 
           <?php else: ?>
               <p><a href="login.php" class="login">Login</a></p> 
               <p><a href="register.php" class="register">Register</a></p> 
           <?php endif; ?>
       </section> -->

       <?php include '../includes/footer.php'; ?> <!-- Include the footer -->
   </main>

   
</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
$conn = null; // Close PDO connection
?>
