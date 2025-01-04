<?php
session_start();

// Include database connection function
include_once '../includes/db_connection.php'; // Include database connection

// Create a PDO instance
$conn = getDatabaseConnection(); // Assuming getDatabaseConnection() is defined in db_connection.php

// Function to calculate total amount from cart items
function calculateTotalAmount() {
    global $conn; // Use the global connection variable

    $total = 0.00;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Fetch product price from the database
            $sql = "SELECT price FROM products WHERE product_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindValue(1, intval($product_id), PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($product) {
                        $total += floatval($product['price']) * intval($quantity); // Calculate total price
                    }
                }
            }
        }
    }
    return number_format($total, 2); // Return formatted total amount as string with 2 decimal places
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Handle form submission for placing an order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    // Get selected payment method
    $payment_method = htmlspecialchars($_POST['payment_method']); 

    // Process order
    $user_id = $_SESSION['user_id'];
    $total_amount = calculateTotalAmount(); 

    // Insert order into orders table using OrderModel class    
    include_once '../models/order_model.php'; 
    include_once '../models/payment_model.php'; 

    $orderModel = new OrderModel();
    
    if ($order_id = $orderModel->createOrder($user_id, floatval($total_amount))) {        
        // Process payment using PaymentModel class        
        $paymentModel = new PaymentModel();
        
        if ($paymentModel->processPayment($user_id, intval($order_id), floatval($total_amount), htmlspecialchars($payment_method))) {            
            echo "<p class='success' style='  align-items: center;        justify-content: center;        max-width: 600px; background-color: #d4edda;  color: #155724;  border: 1px solid #c3e6cb;  padding: 15px;  margin: 0 auto; margin-top: 30vh;  border-radius: 5px;'>Order placed successfully!</p>";
            echo "<p class='success' style='  align-items: center;
        justify-content: center;
        max-width: 600px;  background-color: #d4edda;  color: #155724;  border: 1px solid #c3e6cb;  padding: 15px;  margin: 0 auto; margin-top: 4vh; border-radius: 5px;>Thank you for your purchase!</p>";
            echo "<p class='success'> <a href='products.php'>Continue shopping</a></p>";
            unset($_SESSION['cart']);  // Clear cart after successful order placement.
            unset($_SESSION['shipping_address']); // Clear shipping address after order placement.
            exit();  // Optionally redirect or show confirmation here.
        } else {
            echo "<p class='error' style' background-color: #f8d7da; color: #721c24;  border: 1px solid #f5c6cb; padding: 15px; margin: 0 auto; margin-top: 35vh;border-radius: 5px;'>Error recording payment details.</p>";
        }
    } else {
        echo "<p class='error' style=' background-color: #f8d7da; color: #721c24;  border: 1px solid #f5c6cb;  padding: 15px ;  margin: 0 auto; margin-top: 5vh; border-radius: 5px;'>Error placing order.</p>";
    }
    
}

$total_amount = calculateTotalAmount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <style>
      .success {
        align-items: center;
        justify-content: center;
        max-width: 600px;
    background-color: #d4edda; /* Light green background */
    color: #155724; /* Dark green text */
    border: 1px solid #c3e6cb; /* Green border */
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    }

    .error {
    background-color: #f8d7da; /* Light red background */
    color: #721c24; /* Dark red text */
    border: 1px solid #f5c6cb; /* Red border */
    padding: 15px;
    margin: 0 auto;
    border-radius: 5px;
    }
       body {
           font-family: Arial, sans-serif;
           background-color: #f4f4f4;
           margin: 0;
           padding: 20px;
           height: 100%;
           width: 100%;
           display: flex;
           align-items: center;
        justify-content: center;
        /* max-width: 600px; */
       }

       .checkout-container {
           max-width: 600px;
           margin: auto;
           background: white;
           padding: 20px;
           border-radius: 8px;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
       }

       h2 {
           text-align: center;
           color: #333;
       }

       h3 {
           color: #555;
       }

       p {
           color: #666;
           line-height: 1.6;
       }

       label {
           display: block;
           margin-bottom: 10px;
           cursor: pointer;
       }

       input[type="radio"] {
           margin-right: 10px;
       }

       button {
           background-color: #28a745; /* Bootstrap success color */
           color: white;
           border: none;
           padding: 10px 15px;
           border-radius: 5px;
           cursor: pointer;
           width: 100%;
           font-size: 16px;
       }

       button:hover {
           background-color: #218838; /* Darker green on hover */
       }

       a {
           display: inline-block;
           margin-top: 20px;
           color: #007bff; /* Bootstrap primary color */
           text-decoration: none;
       }

       a:hover {
           text-decoration: underline;
       }
     

   </style>
</head>
<body>
   <div class="checkout-container">
       <h2>Checkout</h2>

       <?php if (isset($_SESSION['shipping_address'])): ?>
           <h3>Shipping Address</h3>
           <p><?php echo htmlspecialchars($_SESSION['shipping_address']['name']); ?></p>
           <p><?php echo htmlspecialchars($_SESSION['shipping_address']['address']); ?></p>
           <p><?php echo htmlspecialchars($_SESSION['shipping_address']['city']); ?>, <?php echo htmlspecialchars($_SESSION['shipping_address']['state']); ?> <?php echo htmlspecialchars($_SESSION['shipping_address']['zip']); ?></p>
           <p><?php echo htmlspecialchars($_SESSION['shipping_address']['country']); ?></p>
       <?php else: ?>
           <p>No shipping address found. Please add one before proceeding.</p>
           echo "<a href='shipping_address.php'>Add Shipping Address</a>";
       <?php endif; ?>

       <form action="" method="POST">
           <h3>Select Payment Method</h3>
           <label>
               <input type="radio" name="payment_method" value="Cash on Delivery" required> Cash on Delivery
           </label><br>
           <label>
               <input type="radio" name="payment_method" value="Credit Card"> Credit Card
           </label><br>
           <label>
               <input type="radio" name="payment_method" value="PayPal"> PayPal
           </label><br>
           <label>
               <input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer
           </label><br>

           <button type="submit">Place Order</button>
       </form>
   </div>

   <?php 
   // Close connection (optional)
   $conn = null; 
   ?>
</body>
</html>
