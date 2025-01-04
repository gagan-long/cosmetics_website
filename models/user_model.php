<?php
include_once '../includes/db_connection.php'; // Include database connection

class UserModel {
    private $conn;

    private function getDatabaseConnection() {
        static $pdo = null;

        if ($pdo === null) {
            try {
                // Database connection logic here...
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

                $pdo = new PDO($dsn, $user, $pass, $options); // Create a new PDO instance
            } catch (\PDOException $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return $pdo;
    }
    public function getConnection() {
        return $this->conn; // Return the PDO connection
    }
    

    public function __construct() {
        $this->conn = $this->getDatabaseConnection(); 
        if ($this->conn === null) {
            throw new Exception("Database connection failed."); 
        }
    }

    // Function to register a new user
    public function registerUser($username, $password, $email) {
        if (empty($username) || empty($password) || empty($email)) return false;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')";
        
        if ($stmt = $this->conn->prepare($sql)) {
            return $stmt->execute([$username, $hashed_password, $email]);
        }
        
        return false; // Registration failed
    }

    // Function to login a user
    public function loginUser($username, $password) {
        if (empty($username) || empty($password)) return false;

        // Prepare statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username = ? AND role IN ('admin', 'user')";
        
        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute([$username])) { 
                if ($stmt->rowCount() > 0) { 
                    // Fetch user data
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $user['password'])) { 
                        return $user; // Return user data on successful login
                    }
                }
            }
        }
        
        return false; // Login failed
    }

    // Function to fetch user details by ID
    public function getUserById($user_id) {
        if (empty($user_id)) return null;

        // Prepare statement to prevent SQL injection
        $sql = "SELECT * FROM users WHERE user_id = ?";
        
        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute([$user_id])) { 
                return $stmt->fetch(PDO::FETCH_ASSOC); // Return user data
            }
        }
        
        return null; // User not found
    }

    // Close the database connection when done
    public function closeConnection() {
        if ($this->conn) {
            // For PDO, setting it to null will close the connection.
            $this->conn = null; 
        }
    }

    // Function to fetch all users
public function getAllUsers() {
    $sql = "SELECT * FROM users"; // Query to select all users

    if ($stmt = $this->conn->prepare($sql)) {
        $stmt->execute(); // Execute the query
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all user records
    }

    return []; // Return an empty array if no records found or query fails
}

// Function to register a new user with role
public function registerUserRole($username, $password, $email, $role) {
    if (empty($username) || empty($password) || empty($email) || empty($role)) return false;

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
    
    if ($stmt = $this->conn->prepare($sql)) {
        return $stmt->execute([$username, $hashed_password, $email, $role]);
    }
    
    return false; // Registration failed
}


// Function to update a user's details
public function updateUser($user_id, $username, $email, $role) {
    if (empty($user_id) || empty($username) || empty($email) || empty($role)) return false;

    // Prepare SQL statement for updating the user
    $sql = "UPDATE users SET username=?, email=?, role=? WHERE user_id=?";
    
    if ($stmt = $this->conn->prepare($sql)) {
        return $stmt->execute([$username, $email, $role, $user_id]);
    }
    
    return false; // Update failed
}

// Function to delete a user by ID
public function deleteUser($user_id) {
    if (empty($user_id)) return false;

    // Prepare SQL statement for deleting the user
    $sql = "DELETE FROM users WHERE user_id = ?";

    if ($stmt = $this->conn->prepare($sql)) {
        return $stmt->execute([$user_id]); // Execute the statement with the user ID
    }

    return false; // Deletion failed
}

}
?>
