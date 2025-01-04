<?php
session_start();
include_once '../models/payment_model.php'; // Include the PaymentModel

class PaymentController {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = new PaymentModel(); // Instantiate the PaymentModel
    }

    // Method to process a payment
    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (!isset($_SESSION['user_id'])) throw new Exception("User not logged in.");
                
                $user_id = $_SESSION['user_id']; // Get user ID from session
                
                if (!isset($_POST['order_id'], $_POST['amount'], $_POST['payment_method'])) {
                    throw new Exception("All fields are required.");
                }
                
                $order_id = intval($_POST['order_id']);
                $amount = floatval($_POST['amount']);
                $payment_method = htmlspecialchars($_POST['payment_method']);

                // Process the payment and store it in the database
                if ($this->paymentModel->processPayment($user_id, $order_id, $amount, $payment_method)) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Payment processed successfully!'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error occurred while processing your payment.'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => htmlspecialchars($e->getMessage()),
                ]);
            }
        }
    }

    // Method to view payment history for a user
    public function viewPaymentHistory($user_id) {
        try {
            if (empty($user_id)) throw new Exception("Invalid user ID.");

            $payments = 	$this->paymentModel->getPaymentHistory($user_id); 
            
          	if ($payments) { 
              	echo json_encode([ 
                  	'status' 	=> 	'success', 
                  	'data' 	=> 	$payments 
              	]); 
          	} else { 
              	echo json_encode([ 
                  	'status' 	=> 	'error', 
                  	'message' 	=> 	'No payment history found for this user.' 
              	]); 
          	} 
       } catch (Exception 	$e) { 
          	echo json_encode([ 
              	'status' 	=> 	'error', 
              	'message' 	=> htmlspecialchars($e->getMessage()), 
          	]); 
       } 
   }

   // Method to view payment details by payment ID
   public function viewPayment($payment_id) {
       try {
           if (empty($payment_id)) throw new Exception("Invalid payment ID.");

           $payment = 	$this->paymentModel->getPaymentById($payment_id); 

          	if ($payment) { 
              	echo json_encode([ 
                  	'status' 	=> 	'success', 
                  	'data' 	=> 	$payment 
              	]); 
          	} else { 
              	echo json_encode([ 
                  	'status' 	=> 	'error', 
                  	'message' 	=> 	'Payment not found.' 
              	]); 
          	} 
       } catch (Exception 	$e) { 
          	echo json_encode([ 
              	'status' 	=> 	'error', 
              	'message' 	=> htmlspecialchars($e->getMessage()), 
          	]); 
       }  
   }

   // Close the database connection when done
   public function close() { 
       $this->paymentModel->closeConnection();  
   }  
}

// Example usage based on request type

$paymentController = new PaymentController();

if (isset($_GET['action'])) { 
   switch ($_GET['action']) { 
       case 'process':
           $paymentController->processPayment();  
           break; 

       case 'view_history':
           if (isset($_SESSION['user_id'])) {$paymentController->viewPaymentHistory($_SESSION['user_id']);}
           break; 

       case 'view':
           if (isset($_GET['id'])) {$paymentController->viewPayment($_GET['id']);}
           break; 

   } 

}

$paymentController->close(); // Close the connection at the end

