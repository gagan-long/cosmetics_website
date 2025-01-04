<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Include the CategoryModel
include_once '../models/category_model.php'; 

// Create an instance of CategoryModel
$categoryModel = new CategoryModel();

// Handle form submission for adding a new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    // Validate input
    if (!empty($name) && !empty($description)) {
        if ($categoryModel->addCategory($name, $description)) {
            echo "<p>Category added successfully!</p>";
        } else {
            echo "<p>Error occurred while adding the category.</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}

// Fetch all categories for display
$categories = $categoryModel->getAllCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>

<h2>Manage Categories</h2>

<!-- Form to add a new category -->
<form action="" method="POST">
    <h3>Add New Category</h3>
    <label for="name">Category Name:</label>
    <input type="text" name="name" required>
    
    <label for="description">Description:</label>
    <textarea name="description" required></textarea>
    
    <button type="submit" name="add_category">Add Category</button>
</form>

<!-- Display existing categories -->
<h3>Existing Categories</h3>
<table>
    <tr>
        <th>Category ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    
    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo htmlspecialchars($category['category_id']); ?></td>
            <td><?php echo htmlspecialchars($category['name']); ?></td>
            <td><?php echo htmlspecialchars($category['description']); ?></td>
            <td>
                <a href="edit_category.php?id=<?php echo htmlspecialchars($category['category_id']); ?>">Edit</a> |
                <a href="delete_category.php?id=<?php echo htmlspecialchars($category['category_id']); ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No categories found.</td></tr> <!-- Message when no categories are available -->
    <?php endif; ?>
</table>

</body>
</html>

<?php
// Close connection (optional, as the connection will close when the script ends)
$categoryModel->closeConnection(); // Close the connection if you have a close method in your model
?>
