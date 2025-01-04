<?php
class PaymentModel {
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

    // Function to process a payment
    public function processPayment($user_id, $order_id, $amount, $payment_method) {
        $sql = "INSERT INTO payments (user_id, order_id, amount, payment_method, payment_date) VALUES (?, ?, ?, ?, NOW())";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $order_id, PDO::PARAM_INT);
            $stmt->bindValue(3, floatval($amount), PDO::PARAM_STR); // Assuming amount is a decimal or float
            $stmt->bindValue(4, htmlspecialchars($payment_method), PDO::PARAM_STR);
            return $stmt->execute(); // Return true on success
        }
        return false; // Payment processing failed
    }

    // Function to fetch payment history for a user
    public function getPaymentHistory($user_id) {
        if (empty($user_id)) return []; // Return empty array if user ID is invalid

        $sql = "SELECT p.*, o.order_date FROM payments p JOIN orders o ON p.order_id = o.order_id WHERE p.user_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return payment history data
            }
        }
        return []; // No payment history found or query failed
    }

    // Function to fetch payment details by payment ID
    public function getPaymentById($payment_id) {
        if (empty($payment_id)) return null;

        $sql = "SELECT * FROM payments WHERE payment_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            // Use bindValue for better handling of data types
            $stmt->bindValue(1, $payment_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC); // Return payment data
            }
        }
        return null; // Payment not found
    }

    // Close the database connection when done
    public function closeConnection() {
        if ($this->conn) {
            // For PDO, setting it to null will close the connection.
            $this->conn = null; 
        }
    }

    
}
?>
