<?php
session_start();
include_once '../models/order_model.php'; // Include the OrderModel

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel(); // Instantiate the OrderModel
    }

    // Method to create a new order
    public function createOrder() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if (!isset($_SESSION['user_id'])) throw new Exception("User not logged in.");
                
                $user_id = $_SESSION['user_id']; // Get user ID from session
                
                if (!isset($_POST['total_amount'])) throw new Exception("Total amount is required.");
                
                $total_amount = floatval($_POST['total_amount']); // Get total amount from POST data

                // Create a new order and get its ID
                if ($order_id = $this->orderModel->createOrder($user_id, $total_amount)) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Order created successfully!',
                        'order_id' => intval($order_id)
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error occurred while creating your order.'
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

    // Method to view an order by ID
    public function viewOrder($order_id) {
        try {
            if (empty($order_id)) throw new Exception("Invalid order ID.");

            $order = 	$this->orderModel->getOrderById($order_id); 
            
          	if ($order) { 
              	echo json_encode([ 
                  	'status' 	=> 	'success', 
                  	'data' 	=> 	$order 
              	]); 
          	} else { 
              	echo json_encode([ 
                  	'status' 	=> 	'error', 
                  	'message' 	=> 	'Order not found.' 
              	]); 
          	} 
       } catch (Exception 	$e) { 
          	echo json_encode([ 
              	'status' 	=> 	'error', 
              	'message' 	=> htmlspecialchars($e->getMessage()), 
          	]); 
       } 
   }

   // Method to view all orders for a user
   public function viewUserOrders($user_id) {
       try {
           if (empty($user_id)) throw new Exception("Invalid user ID.");

           $orders = 	$this->orderModel->getUserOrders($user_id); 
            
          	if ($orders) { 
              	echo json_encode([ 
                  	'status' 	=> 	'success', 
                  	'data' 	=> 	$orders 
              	]); 
          	} else { 
              	echo json_encode([ 
                  	'status' 	=> 	'error', 
                  	'message' 	=> 	'No orders found for this user.' 
              	]); 
          	} 
       } catch (Exception 	$e) { 
          	echo json_encode([ 
              	'status' 	=> 	'error', 
              	'message' 	=> htmlspecialchars($e->getMessage()), 
          	]); 
       } 
   }

   // Method to update an order status
   public function updateOrderStatus($order_id, $status) {
       try {  
           if ($this->orderModel->updateOrderStatus($order_id, htmlspecialchars($status))) {  
               echo json_encode([  
                   'status' => 'success',  
                   'message' => 'Order status updated successfully!'  
               ]);  
           } else {  
               echo json_encode([  
                   'status' => 'error',  
                   'message' => 'Error occurred while updating the order status.'  
               ]);  
           }  
       } catch (Exception 	$e) {  
           echo json_encode([  
               'status' => 'error',  
               'message' => htmlspecialchars($e->getMessage()),  
           ]);  
       }  
   }

   // Close the database connection when done
   public function close() { 
       $this->orderModel->closeConnection(); 
   } 

}


