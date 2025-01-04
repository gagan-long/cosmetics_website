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

// Initialize error message variable
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Handle file upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../resources/images/products/"; // Directory to save images
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $error_message = "File is not an image.";
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Move uploaded file to target directory
        if (empty($error_message) && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = basename($_FILES["image"]["name"]); // Store only the filename
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }

    // Add product using the model method
    if (empty($error_message)) {
        if ($productModel->addProduct($name, $description, $price, $image_url)) {
            $success_message = "Product added successfully!";
            // Optionally redirect or clear form fields here
        } else {
            $error_message = "Error occurred during product addition.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
   <style>
   /* add_product_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Add Product Container Styles */
.add-product-container {
    max-width: 500px; /* Maximum width for the container */
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

/* Message Styles */
p {
    margin: 10px 0; /* Margin above and below messages */
}

/* Input and Textarea Styles */
input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%; /* Full width inputs and textarea */
    padding: 10px; /* Padding inside inputs/textarea */
    margin-bottom: 15px; /* Space below inputs/textarea */
    border: 1px solid #ccc; /* Light grey border */
    border-radius: 4px; /* Rounded corners for inputs/textarea */
}

/* Textarea Specific Styles */
textarea {
    resize: vertical; /* Allow vertical resizing only */
}

/* Button Styles */
button {
    background-color: #007BFF; /* Blue background for button */
    color: white; /* White text color for button */
    border: none; /* Remove default border */
    padding: 10px; /* Padding inside button */
    border-radius: 5px; /* Rounded corners for button */
    cursor: pointer; /* Pointer cursor on hover */
    width: 100%; /* Full width button */
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover for button */
}

/* Link Styles */
a {
    color: #007BFF; /* Blue color for links */
    text-decoration: none; /* Remove underline from links */
}

a:hover {
    text-decoration: underline; /* Underline on hover for better visibility */
}

   </style>
<body>
    <div class="add-product-container">
        <h2>Add New Product</h2>

        <!-- Display success or error message -->
        <?php if (!empty($success_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data"> <!-- Add enctype for file uploads -->
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" placeholder="Stock Quantity" required min="0">
            <input type="file" name="image" accept="image/*" required> <!-- File input for image -->
            <button type="submit">Add Product</button>
        </form>
        
        <p><a href="manage_products.php">Back to Manage Products</a></p> <!-- Link back -->
    </div>
</body>
</html>
