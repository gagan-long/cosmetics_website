<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Handle form submission for saving shipping address
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_address'])) {
    // Save shipping address to session
    $_SESSION['shipping_address'] = [
        'name' => htmlspecialchars($_POST['name']),
        'address' => htmlspecialchars($_POST['address']),
        'city' => htmlspecialchars($_POST['city']),
        'state' => htmlspecialchars($_POST['state']),
        'zip' => htmlspecialchars($_POST['zip']),
        'country' => htmlspecialchars($_POST['country'])
    ];

    // Redirect to checkout page after saving address
    header("Location: checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Address</title>
    <link rel="stylesheet" href="../resources/css/styles.css"> <!-- Link to your CSS -->
</head>
<body>

    <?php include '../includes/header.php'; ?> <!-- Include the header -->

    <main>
        <h2>Enter Shipping Address</h2>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="address">Address:</label>
            <input type="text" name="address" required><br>

            <label for="city">City:</label>
            <input type="text" name="city" required><br>

            <label for="state">State:</label>
            <input type="text" name="state" required><br>

            <label for="zip">Zip Code:</label>
            <input type="text" name="zip" required><br>

            <label for="country">Country:</label>
            <input type="text" name="country" required><br>

            <button type="submit" name="submit_address">Save Address</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?> <!-- Include the footer -->

</body>
</html>
