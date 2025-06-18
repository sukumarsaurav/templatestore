<?php
// Simple test endpoint for registration
header('Content-Type: application/json');

// Suppress error display
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

// Start output buffering
ob_start();

try {
    // Log the request
    error_log("Test-register.php - Request Method: " . $_SERVER['REQUEST_METHOD']);
    error_log("Test-register.php - POST data: " . print_r($_POST, true));
    
    // Clear any output and set headers
    ob_clean();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Simulate successful registration
        echo json_encode([
            'success' => true,
            'message' => 'Test registration successful',
            'debug' => [
                'post_data' => $_POST,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method',
            'debug' => [
                'method' => $_SERVER['REQUEST_METHOD'],
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }
    
} catch (Exception $e) {
    error_log("Test-register.php - Error: " . $e->getMessage());
    
    // Ensure clean output
    if (ob_get_length()) ob_clean();
    
    echo json_encode([
        'success' => false,
        'message' => 'Test error occurred',
        'debug' => [
            'error' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
} finally {
    // End output buffering
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
}
?> 