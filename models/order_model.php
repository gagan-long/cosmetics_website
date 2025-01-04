<?php
class OrderModel {
    private $conn;

    private function getDatabaseConnection() {
        static $pdo = null;

        if ($pdo === null) {
            try {
                // Database connection parameters
                $host = 'localhost';
                $db = 'cosmetics_db';
                $user = 'root';
                $pass = '';
                $charset = 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                // Create a new PDO instance
                $pdo = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return $pdo;
    }

    public function __construct() {
        $this->conn = $this->getDatabaseConnection(); 
        if ($this->conn === null) {
            throw new Exception("Database connection failed."); 
        }
    }

    // Function to create a new order
    public function createOrder($user_id, $total_amount) {
        $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES (?, NOW(), ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, floatval($total_amount), PDO::PARAM_STR); // Assuming total_amount is a decimal or float
            if ($stmt->execute()) {
                return $this->conn->lastInsertId(); // Return the ID of the newly created order
            }
        }
        return false; // Order creation failed
    }

    // Function to fetch an order by ID
    public function getOrderById($order_id) {
        if (empty($order_id)) return null;

        $sql = "SELECT * FROM orders WHERE order_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, intval($order_id), PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC); // Return order data
            }
        }
        return null; // Order not found
    }

    // Function to fetch all orders for a user
    public function getUserOrders($user_id) {
        if (empty($user_id)) return []; // Return empty array if user ID is invalid

        $sql = "SELECT * FROM orders WHERE user_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, intval($user_id), PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return result set of orders
            }
        }
        return []; // No orders found or query failed
    }

    // Function to update an existing order (e.g., status)
    public function updateOrderStatus($order_id, $status) {
        if (empty($order_id)) return false;

        $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, htmlspecialchars($status), PDO::PARAM_STR);
            $stmt->bindValue(2, intval($order_id), PDO::PARAM_INT);
            return $stmt->execute(); // Return true on success
        }
        return false; // Update failed
    }

    // Function to fetch all orders with user information
    public function getAllOrders() {
        $sql = "SELECT o.order_id, o.order_date, o.status, o.total_amount, u.username 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id"; 

        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute()) { 
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all order records
            }
        }
        return []; // Return an empty array if no records found or query fails
    }

    // Function to delete an order by ID
    public function deleteOrder($order_id) {
        if (empty($order_id)) return false;

        $sql = "DELETE FROM orders WHERE order_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$order_id]); // Return true on success
        }
        return false; // Deletion failed
    }

    // Close the database connection when done
    public function closeConnection() {
      	if ($this->conn) { 
          	$this->conn=null; 
      	} 
  	} 
}
?>
