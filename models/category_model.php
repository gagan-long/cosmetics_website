<?php
class CategoryModel {
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

    // Function to fetch all categories
    public function getAllCategories() {
        $sql = "SELECT * FROM categories"; // Query to select all categories
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute(); // Execute the query
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all category records
        }
        return []; // Return an empty array if no records found or query fails
    }

    // Function to fetch a category by ID
    public function getCategoryById($category_id) {
        if (empty($category_id)) return null; // Validate input

        // Prepare statement to prevent SQL injection
        $sql = "SELECT * FROM categories WHERE category_id = ?";
        
        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute([$category_id])) { 
                return $stmt->fetch(PDO::FETCH_ASSOC); // Return category data
            }
        }
        
        return null; // Category not found
    }

    // Function to add a new category
    public function addCategory($name, $description) {
        if (empty($name) || empty($description)) return false; // Validate input

        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([htmlspecialchars($name), htmlspecialchars($description)]); // Return true on success
        }
        return false; // Insertion failed
    }

    // Function to update an existing category
    public function updateCategory($category_id, $name, $description) {
        if (empty($category_id) || empty($name) || empty($description)) return false; // Validate input

        $sql = "UPDATE categories SET name = ?, description = ? WHERE category_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([htmlspecialchars($name), htmlspecialchars($description), intval($category_id)]); // Return true on success
        }
        return false; // Update failed
    }

    // Function to delete a category
    public function deleteCategory($category_id) {
        if (empty($category_id)) return false; // Validate input

        $sql = "DELETE FROM categories WHERE category_id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$category_id]); // Return true on success
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
