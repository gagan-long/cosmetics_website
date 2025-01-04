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

// Fetch product details for editing
$product = $productModel->getProductById($product_id);
if (!$product) {
    echo "<p>Product not found.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated form data and sanitize inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Handle file upload (optional)
    $image_url = $product['image_url']; // Keep existing image URL by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Handle new image upload
        $target_dir = "../resources/images/products/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<p>File is not an image.</p>";
            exit();
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
            exit();
        }

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update image URL to the new file name
            $image_url = basename($_FILES["image"]["name"]);
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
            exit();
        }
    }

    // Update product using the model method
    if ($productModel->updateProduct($product_id, $name, $description, $price, $image_url)) {
        echo "<p>Product updated successfully!</p>";
        header("Location: manage_products.php"); // Redirect back after updating
        exit();
    } else {
        echo "<p>Error occurred during product update.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Product</title>
   <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .edit-product-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    input[type="file"] {
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    textarea {
        resize: vertical; /* Allow vertical resizing */
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    textarea:focus {
        border-color: #007BFF; /* Bootstrap primary color */
        outline: none;
    }

    button {
        padding: 10px;
        background-color: #007BFF; /* Bootstrap primary color */
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #0056b3; /* Darker shade for hover effect */
    }

    h3 {
        margin-top: 20px; /* Space above current image heading */
    }

    img {
        max-width: 100%; /* Responsive image */
        height: auto; /* Maintain aspect ratio */
        border-radius: 4px; /* Rounded corners for images */
    }

    p {
        text-align: center;
        margin-top: 20px;
    }

    a {
        color: #007BFF; /* Link color */
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline; /* Underline on hover */
    }
</style>

</head>
<body>
   <div class="edit-product-container">
       <h2>Edit Product</h2>
       <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype for file uploads -->
           <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required placeholder="Product Name">
           <textarea name="description" required placeholder="Product Description"><?php echo htmlspecialchars($product['description']); ?></textarea>
           <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required placeholder="Price">
           <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required min="0" placeholder='Stock Quantity'>
           <input type="file" name="image" accept="image/*"> <!-- File input for new image (optional) -->
           <button type="submit">Update Product</button>
       </form>

       <!-- Display current image -->
       <?php if ($product['image_url']): ?>
           <h3>Current Image:</h3>
           <img src="../resources/images/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100px; height: auto;">
       <?php endif; ?>

       <p><a href="manage_products.php">Back to Manage Products</a></p> <!-- Link back -->
   </div>
</body>
</html>
