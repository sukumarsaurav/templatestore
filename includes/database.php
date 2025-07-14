<?php
/**
 * Database Connection Handler for NeoWebX Template Store
 */
class Database {
    private $conn;
    
    /**
     * Constructor
     */
    public function __construct() {
        $host = 'localhost';
        $username = 'u277468165_neowebxstore';
        $password = 'Milk@sdk14';
        $database = 'u277468165_neowebxstore';
        
        // Create connection
        $this->conn = new mysqli($host, $username, $password, $database);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        
        // Set charset to utf8mb4
        $this->conn->set_charset("utf8mb4");
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Escape string for SQL query
     */
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
    
    /**
     * Begin a transaction
     */
    public function begin_transaction() {
        return $this->conn->begin_transaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        return $this->conn->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback() {
        return $this->conn->rollback();
    }
    
    /**
     * Get last insert ID
     */
    public function insert_id() {
        return $this->conn->insert_id;
    }
    
    /**
     * Execute a prepared statement query
     * 
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @param string $types Types of parameters (i: integer, s: string, d: double, b: blob)
     * @return mixed Result set for SELECT, boolean for other queries
     */
    public function query($query, $params = [], $types = '') {
        try {
            // For simple queries without parameters
            if (empty($params)) {
                $result = $this->conn->query($query);
                if ($result === false) {
                    error_log("Query Error: " . $this->conn->error);
                    return false;
                }
                return $result;
            }
            
            // For prepared statements
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                error_log("Query preparation failed: " . $this->conn->error);
                return false;
            }
            
            // Bind parameters if provided
            if (!empty($params) && !empty($types)) {
                $stmt->bind_param($types, ...$params);
            }
            
            // Execute the statement
            if (!$stmt->execute()) {
                error_log("Query execution failed: " . $stmt->error);
                return false;
            }
            
            // Get result for SELECT queries
            if (strpos(strtoupper($query), 'SELECT') === 0) {
                $result = $stmt->get_result();
                $stmt->close();
                return $result;
            }
            
            // Get affected rows for other queries
            $affected = $stmt->affected_rows;
            $stmt->close();
            return $affected > 0;
            
        } catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Execute a query and fetch all results
     * 
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @param string $types Types of parameters
     * @return array|false Array of results or false on failure
     */
    public function fetchAll($query, $params = [], $types = '') {
        $result = $this->query($query, $params, $types);
        if ($result === false) {
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Execute a query and fetch a single row
     * 
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @param string $types Types of parameters
     * @return array|false Array with row data or false on failure
     */
    public function fetchOne($query, $params = [], $types = '') {
        $result = $this->query($query, $params, $types);
        if ($result === false) {
            return false;
        }
        return $result->fetch_assoc();
    }
    
    /**
     * Execute a query and fetch a single value
     * 
     * @param string $query SQL query
     * @param array $params Parameters for prepared statement
     * @param string $types Types of parameters
     * @return mixed|false The value or false on failure
     */
    public function fetchValue($query, $params = [], $types = '') {
        $result = $this->fetchOne($query, $params, $types);
        if ($result === false) {
            return false;
        }
        return array_values($result)[0];
    }
    
    /**
     * Close the database connection
     */
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
} 