<?php
// Prevent direct access to this file
if (!defined('STORE_PATH')) {
    define('STORE_PATH', true);
}

// Suppress error display but log them
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');
error_log("Register.php started");

// Start output buffering immediately
ob_start();

try {
    // Include common functions and database
    require_once 'includes/functions.php';
    require_once 'includes/database.php';

    // Initialize database connection
    $db = new Database();

    // Start session
    session_start();

    // Clear any previous output and set JSON header
    ob_clean();
    header('Content-Type: application/json');

    // Log request data
    error_log("Register.php - Request Method: " . $_SERVER['REQUEST_METHOD']);
    error_log("Register.php - POST data: " . print_r($_POST, true));

    // If already logged in, return JSON response
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Already logged in']);
        exit();
    }

    // Handle registration form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $firstName = isset($_POST['first_name']) ? trim($db->escape($_POST['first_name'])) : '';
        $lastName = isset($_POST['last_name']) ? trim($db->escape($_POST['last_name'])) : '';
        $email = isset($_POST['email']) ? trim($db->escape($_POST['email'])) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $newsletterOptIn = isset($_POST['newsletter_opt_in']) ? 1 : 0;
        
        // Log received data (excluding password)
        error_log("Register.php - Received data: " . json_encode([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'newsletter_opt_in' => $newsletterOptIn
        ]));
        
        // Validate input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long.');
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception('Passwords do not match.');
        }
        
        // Check if email already exists
        $result = $db->query("SELECT user_id FROM users WHERE email = '$email' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            throw new Exception('This email address is already registered.');
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Start transaction
        $db->begin_transaction();
        
        try {
            // Insert user
            $query = "INSERT INTO users (
                email, password, first_name, last_name,
                newsletter_opt_in, is_active, role
            ) VALUES (
                '$email',
                '$hashedPassword',
                '$firstName',
                '$lastName',
                $newsletterOptIn,
                1,
                'customer'
            )";
            
            error_log("Register.php - Executing query: " . preg_replace('/password\s*=\s*\'[^\']+\'/', 'password=\'***\'', $query));
            
            $db->query($query);
            
            $userId = $db->insert_id;
            error_log("Register.php - New user ID: " . $userId);
            
            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'customer';
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;
            
            // Commit transaction
            $db->commit();
            
            // Return success response
            echo json_encode(['success' => true]);
            exit();
            
        } catch (Exception $e) {
            // Rollback on error
            $db->rollback();
            error_log("Register.php - Database error: " . $e->getMessage());
            throw new Exception('Error creating account: ' . $e->getMessage());
        }
    }
    
    // If we get here, it's not a POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    
} catch (Exception $e) {
    error_log("Register.php - Error: " . $e->getMessage());
    error_log("Register.php - Stack trace: " . $e->getTraceAsString());
    
    // Ensure clean output buffer
    if (ob_get_length()) ob_clean();
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
} finally {
    // End output buffering and send response
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
}
?> 