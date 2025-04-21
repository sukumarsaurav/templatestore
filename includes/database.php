<?php
// Prevent direct access
if (!defined('STORE_PATH') && !defined('ADMIN_PATH')) {
    exit('Direct access not permitted');
}

/**
 * Database connection class for NeoWebX Template Store
 */
class Database {
    private $host = '193.203.184.121';
    private $username = 'u911550082_neowebx';
    private $password = 'nHR*GmF$0';
    private $database = 'u911550082_neowebx';
    private $conn;
    
    /**
     * Constructor - Connect to the database
     */
    public function __construct() {
        try {
            // Set timeout to avoid hanging
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
            
            // Set character set
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            // Log error instead of exposing details
            error_log("Database Connection Error: " . $e->getMessage());
            
            // For development only, uncomment if needed:
            // echo "Connection Error: " . $e->getMessage();
            $this->conn = null;
        }
    }
    
    /**
     * Get database connection
     * 
     * @return mysqli|null The database connection or null if connection failed
     */
    public function getConnection() {
        return $this->conn;
    }
    
    /**
     * Execute a query
     * 
     * @param string $query The SQL query
     * @param array $params Parameters for prepared statement
     * @param string $types Types of parameters (i: integer, s: string, d: double, b: blob)
     * @return mysqli_result|bool Result object or boolean
     */
    public function query($query, $params = [], $types = '') {
        try {
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->conn->error);
            }
            
            // Bind parameters if provided
            if (!empty($params) && !empty($types)) {
                $stmt->bind_param($types, ...$params);
            }
            
            // Execute the statement
            $stmt->execute();
            
            // Get result for SELECT queries
            if (strpos(strtoupper($query), 'SELECT') === 0) {
                return $stmt->get_result();
            }
            
            // Return affected rows for other queries
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Query Error: " . $e->getMessage());
            return false;
        }
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

/**
 * Firebase Authentication Handler for NeoWebX Template Store
 */
class FirebaseAuth {
    private $apiKey;
    private $projectId;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->apiKey = 'YOUR_FIREBASE_API_KEY';
        $this->projectId = 'YOUR_FIREBASE_PROJECT_ID';
    }
    
    /**
     * Initialize Firebase JS SDK in the frontend
     * 
     * @return string HTML code to initialize Firebase
     */
    public function initFirebaseJS() {
        return '<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
                <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
                <script>
                    const firebaseConfig = {
                        apiKey: "' . $this->apiKey . '",
                        projectId: "' . $this->projectId . '",
                        authDomain: "' . $this->projectId . '.firebaseapp.com",
                        storageBucket: "' . $this->projectId . '.appspot.com",
                        messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
                        appId: "YOUR_APP_ID"
                    };
                    
                    // Initialize Firebase
                    firebase.initializeApp(firebaseConfig);
                </script>';
    }
} 