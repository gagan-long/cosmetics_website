<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

include_once '../models/payment_model.php'; // Include PaymentModel

// Create an instance of PaymentModel
$paymentModel = new PaymentModel();

// Fetch all payments using the model method.
$payments = [];
try {
    $payments = $paymentModel->getPaymentHistory($_SESSION['user_id']); // Fetch payment history for the logged-in user
} catch (Exception $e) {
    die("Error fetching payments: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <style>
    /* manage_payments_styles.css */

/* General Body Styles */
body {
    font-family: Arial, sans-serif; /* Clean sans-serif font */
    background-color: #f4f4f4; /* Light grey background */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Padding around the body */
}

/* Payment Management Container Styles */
.payment-management-container {
    max-width: 900px; /* Maximum width for the container */
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

/* Link Styles */
a {
    color: #007BFF; /* Blue color for links */
    text-decoration: none; /* Remove underline from links */
}

a:hover {
    text-decoration: underline; /* Underline on hover for better visibility */
}

/* Table Styles */
table {
    width: 100%; /* Full width table */
    border-collapse: collapse; /* Collapse borders between cells */
    margin-top: 20px; /* Space above table */
}

th, td {
    padding: 12px; /* Padding inside table cells */
    text-align: left; /* Left align text in cells */
    border-bottom: 1px solid #ddd; /* Light grey border below each row */
}

th {
    background-color: #007BFF; /* Blue background for table headers */
    color: white; /* White text color for headers */
}

tr:hover {
    background-color: #f1f1f1; /* Light grey background on row hover */
}

/* No Payments Found Message Styles */
td[colspan="7"] {
    text-align: center; /* Center align message when no payments are found */
    font-style: italic; /* Italicize message text */
}

    </style>
</head>
<body>
    <div class="payment-management-container">
        <h2>Manage Payments</h2>
        <a href="index.php">Dashboard</a>

        <table>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($payment['order_date']); ?></td>
                    <td><?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($payment['status']); ?></td>
                    <td><a href="refund_payment.php?id=<?php echo htmlspecialchars($payment['payment_id']); ?>">Refund</a></td> <!-- Refund link -->
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No payments found.</td></tr> <!-- Message when no payments are available -->
            <?php endif; ?>
        </table>

    </div>

    <!-- Optional footer -->
</body>
</html>
