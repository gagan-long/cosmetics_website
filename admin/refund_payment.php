<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../includes/db_connection.php';

// Get payment ID from query string
$payment_id = $_GET['id'];

// Fetch payment details before processing refund
$sql = "SELECT * FROM payments WHERE payment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>Payment not found.</p>";
    exit();
}

$payment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process refund logic here (e.g., update payment status)
    $sql_update = "UPDATE payments SET status = 'Refunded' WHERE payment_id = ?";
    
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("i", $payment_id);
        
        if ($stmt_update->execute()) {
            echo "<p>Payment refunded successfully!</p>";
            header("Location: manage_payments.php"); // Redirect back after refund
            exit();
        } else {
            echo "<p>Error occurred during refund processing.</p>";
        }
        
        // Close statement
        $stmt_update->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Refund Payment</title>
   <link rel="stylesheet" href="../resources/css/admin_styles.css"> <!-- Link to your CSS -->
</head>
<body>
   <div class="refund-payment-container">
       <h2>Refund Payment</h2>
       <p>Are you sure you want to refund this payment?</p>
       <form action="" method="POST">
           <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
           <button type="submit">Confirm Refund</button>
       </form>
       <p><a href="manage_payments.php">Back to Manage Payments</a></p> <!-- Link back -->
   </div>
</body>
</html>

