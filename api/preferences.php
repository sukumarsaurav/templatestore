<?php
// Start session
session_start();

// Define base path
define('STORE_PATH', dirname(dirname(__FILE__)));

// Include common functions and database
require_once STORE_PATH . '/includes/functions.php';
require_once STORE_PATH . '/includes/database.php';

// Initialize database
$db = new Database();

// Get JSON data from request body
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Check for valid JSON
if ($data === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Get action parameter
$action = isset($data['action']) ? $data['action'] : '';

if ($action === 'update_preferences') {
    // Update language preference
    if (isset($data['language'])) {
        $language = $data['language'];
        
        // Validate language code
        $langQuery = "SELECT language_code FROM languages WHERE language_code = ? AND is_active = 1";
        $langResult = $db->query($langQuery, [$language], 's');
        
        if ($langResult && $langResult->num_rows > 0) {
            $_SESSION['language'] = $language;
        }
    }
    
    // Update currency preference
    if (isset($data['currency'])) {
        $currency = $data['currency'];
        
        // Validate currency code
        $currQuery = "SELECT currency_code FROM currencies WHERE currency_code = ? AND is_active = 1";
        $currResult = $db->query($currQuery, [$currency], 's');
        
        if ($currResult && $currResult->num_rows > 0) {
            $_SESSION['currency'] = $currency;
        }
    }
    
    // If user is logged in, update preferences in database
    if (isset($_SESSION['user_id'])) {
        $updates = [];
        $params = [];
        $types = '';
        
        if (isset($data['language'])) {
            $updates[] = "language_code = ?";
            $params[] = $language;
            $types .= 's';
        }
        
        if (isset($data['currency'])) {
            $updates[] = "currency_code = ?";
            $params[] = $currency;
            $types .= 's';
        }
        
        if (!empty($updates)) {
            // Add user_id to params
            $params[] = $_SESSION['user_id'];
            $types .= 'i';
            
            // Update user preferences
            $updateQuery = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
            $db->query($updateQuery, $params, $types);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Preferences updated successfully',
        'preferences' => [
            'language' => $_SESSION['language'],
            'currency' => $_SESSION['currency']
        ]
    ]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

// Close database connection
$db->close(); 