<?php

// Create a class named Operations for handling database operations
class Operations {
    // Private properties to store database connection details
    private $host = "localhost"; 
    private $username = "root"; 
    private $password = ""; 
    private $dbname = "ngClass"; 
    
    // Public method for establishing a database connection
    public function dbConnection() {
        try {
            // Create a new PDO (PHP Data Objects) connection to the database
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);

            // Set the PDO error mode to throw exceptions on errors
            //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Return the established database connection
            return $conn;
        } catch (PDOException $e) {
            // and display an error message, then return null to indicate failure
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
