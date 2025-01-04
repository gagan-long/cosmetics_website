<?php
include_once '../includes/db_connection.php'; // Include database connection

class ProductModel {
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

    // Function to fetch all products
    public function getAllProducts() {
        $sql = "SELECT * FROM products"; // Query to select all products
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute(); // Execute the query
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all product records
        }
        return []; // Return an empty array if no records found or query fails
    }

    // Function to fetch a product by ID
    public function getProductById($product_id) {
        if (empty($product_id)) return null;

        // Prepare statement to prevent SQL injection
        $sql = "SELECT * FROM products WHERE product_id = ?";
        
        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute([$product_id])) { 
                return $stmt->fetch(PDO::FETCH_ASSOC); // Return product data
            }
        }
        
        return null; // Product not found
    }

    // Function to add a new product
    public function addProduct($name, $description, $price, $image_url) {
        $sql = "INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$name, $description, floatval($price), htmlspecialchars($image_url)]); // Return true on success
        }
        return false; // Insertion failed
    }

    // Function to update an existing product
    public function updateProduct($product_id, $name, $description, $price, $image_url) {
        if (empty($product_id)) return false;

        $sql = "UPDATE products SET name=?, description=?, price=?, image_url=? WHERE product_id=?";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$name, $description, floatval($price), htmlspecialchars($image_url), intval($product_id)]); // Return true on success
        }
        return false; // Update failed
    }

    // Function to delete a product
    public function deleteProduct($product_id) {
        if (empty($product_id)) return false;

        $sql = "DELETE FROM products WHERE product_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$product_id]); // Return true on success
        }
        return false; // Deletion failed
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
