<?php
function getDatabaseConnection() {
    static $pdo = null; // Static variable to hold the PDO instance

    if ($pdo === null) { // Check if the connection has already been established
        $host = 'localhost'; // Your database host
        $db = 'cosmetics_db'; // Your database name
        $user = 'root'; // Your database username
        $pass = ''; // Your database password
        $charset = 'utf8mb4'; // Character set

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // Data Source Name
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options); // Create a new PDO instance
        } catch (\PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    return $pdo; // Return the PDO instance
}
?>
