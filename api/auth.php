<?php
// Prevent direct access unless making an API request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Define base path
define('STORE_PATH', dirname(dirname(__FILE__)));

// Include common functions and database
require_once STORE_PATH . '/includes/functions.php';
require_once STORE_PATH . '/includes/database.php';

// Initialize database
$db = new Database();

// Start or resume session
if (!isset($_SESSION)) {
    session_start();
}

// Get JSON data from request body
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Check for valid JSON
if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Get action parameter
$action = isset($data['action']) ? $data['action'] : '';

// Process based on action
switch ($action) {
    case 'login':
        handleLogin($db, $data);
        break;
        
    case 'register':
        handleRegistration($db, $data);
        break;
        
    case 'logout':
        handleLogout($db, $data);
        break;
        
    case 'update_preferences':
        handleUpdatePreferences($db, $data);
        break;
        
    case 'store_session':
        handleStoreSession($db, $data);
        break;
        
    case 'clear_session':
        handleClearSession($db, $data);
        break;
        
    default:
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid action specified']);
        break;
}

/**
 * Handle user login
 * 
 * @param Database $db Database connection
 * @param array $data User data
 */
function handleLogin($db, $data) {
    // Required fields
    if (!isset($data['firebase_uid']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $firebaseUid = sanitizeInput($data['firebase_uid']);
    $email = sanitizeInput($data['email']);
    $displayName = isset($data['display_name']) ? sanitizeInput($data['display_name']) : null;
    
    // Check if user exists
    $userQuery = "SELECT user_id, email, first_name, last_name, language_code, currency_code 
                  FROM users WHERE firebase_uid = ?";
    $userResult = $db->query($userQuery, [$firebaseUid], 's');
    
    if ($userResult && $userResult->num_rows > 0) {
        // User exists, update last login time
        $user = $userResult->fetch_assoc();
        
        $updateQuery = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
        $db->query($updateQuery, [$user['user_id']], 'i');
        
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        
        // Set language and currency preferences
        if ($user['language_code']) {
            $_SESSION['language'] = $user['language_code'];
        }
        
        if ($user['currency_code']) {
            $_SESSION['currency'] = $user['currency_code'];
        }
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'user' => [
                'user_id' => $user['user_id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'language' => $_SESSION['language'],
                'currency' => $_SESSION['currency']
            ]
        ]);
    } else {
        // User doesn't exist in our database yet, but has a Firebase account
        // This can happen with social logins or if DB insert failed during registration
        
        // Create user in our database
        $insertQuery = "INSERT INTO users (firebase_uid, email, first_name, last_name, registration_date, last_login) 
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
        
        // Parse name from display_name or email
        $firstName = '';
        $lastName = '';
        
        if ($displayName) {
            $nameParts = explode(' ', $displayName, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        } else {
            // Use email username as first name
            $emailParts = explode('@', $email);
            $firstName = $emailParts[0];
        }
        
        $db->query($insertQuery, [$firebaseUid, $email, $firstName, $lastName], 'ssss');
        
        // Get the newly created user ID
        $userId = $db->getConnection()->insert_id;
        
        // Set session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'New user account created',
            'user' => [
                'user_id' => $userId,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'language' => $_SESSION['language'],
                'currency' => $_SESSION['currency']
            ]
        ]);
    }
}

/**
 * Handle user registration
 * 
 * @param Database $db Database connection
 * @param array $data User data
 */
function handleRegistration($db, $data) {
    // Required fields
    if (!isset($data['firebase_uid']) || !isset($data['email']) || 
        !isset($data['first_name']) || !isset($data['last_name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $firebaseUid = sanitizeInput($data['firebase_uid']);
    $email = sanitizeInput($data['email']);
    $firstName = sanitizeInput($data['first_name']);
    $lastName = sanitizeInput($data['last_name']);
    $newsletterOptIn = isset($data['newsletter_opt_in']) ? (int)$data['newsletter_opt_in'] : 0;
    
    // Check if user already exists
    $checkQuery = "SELECT user_id FROM users WHERE firebase_uid = ? OR email = ?";
    $checkResult = $db->query($checkQuery, [$firebaseUid, $email], 'ss');
    
    if ($checkResult && $checkResult->num_rows > 0) {
        // User already exists
        $userData = $checkResult->fetch_assoc();
        
        // Update user data
        $updateQuery = "UPDATE users SET 
                        first_name = ?, 
                        last_name = ?, 
                        newsletter_opt_in = ?,
                        last_login = NOW() 
                        WHERE user_id = ?";
        
        $db->query($updateQuery, [$firstName, $lastName, $newsletterOptIn, $userData['user_id']], 'ssii');
        
        // Set session variables
        $_SESSION['user_id'] = $userData['user_id'];
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'User data updated',
            'user_id' => $userData['user_id']
        ]);
    } else {
        // Create new user
        $insertQuery = "INSERT INTO users (
                        firebase_uid, 
                        email, 
                        first_name, 
                        last_name, 
                        newsletter_opt_in,
                        registration_date,
                        last_login
                    ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        
        $db->query($insertQuery, [
            $firebaseUid, 
            $email, 
            $firstName, 
            $lastName, 
            $newsletterOptIn
        ], 'ssssi');
        
        // Get the newly created user ID
        $userId = $db->getConnection()->insert_id;
        
        // Set session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'User registered successfully',
            'user_id' => $userId
        ]);
    }
}

/**
 * Handle user logout
 * 
 * @param Database $db Database connection
 * @param array $data User data
 */
function handleLogout($db, $data) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Success response
    echo json_encode([
        'success' => true, 
        'message' => 'User logged out successfully'
    ]);
}

/**
 * Handle updating user preferences
 * 
 * @param Database $db Database connection
 * @param array $data User preferences data
 */
function handleUpdatePreferences($db, $data) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        // User not logged in, but we can still update session preferences
        if (isset($data['language'])) {
            $_SESSION['language'] = sanitizeInput($data['language']);
        }
        
        if (isset($data['currency'])) {
            $_SESSION['currency'] = sanitizeInput($data['currency']);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Session preferences updated'
        ]);
        return;
    }
    
    // User is logged in, update preferences in database
    $userId = $_SESSION['user_id'];
    $updates = [];
    $params = [];
    $types = '';
    
    if (isset($data['language'])) {
        $language = sanitizeInput($data['language']);
        $updates[] = "language_code = ?";
        $params[] = $language;
        $types .= 's';
        $_SESSION['language'] = $language;
    }
    
    if (isset($data['currency'])) {
        $currency = sanitizeInput($data['currency']);
        $updates[] = "currency_code = ?";
        $params[] = $currency;
        $types .= 's';
        $_SESSION['currency'] = $currency;
    }
    
    if (empty($updates)) {
        echo json_encode([
            'success' => false, 
            'message' => 'No preferences to update'
        ]);
        return;
    }
    
    // Add user_id to params
    $params[] = $userId;
    $types .= 'i';
    
    // Update user preferences
    $updateQuery = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = ?";
    $result = $db->query($updateQuery, $params, $types);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'User preferences updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to update user preferences'
        ]);
    }
}

/**
 * Handle storing session data from Firebase authentication
 * 
 * @param Database $db Database connection
 * @param array $data User data from Firebase
 */
function handleStoreSession($db, $data) {
    // Required fields
    if (!isset($data['user_id']) || !isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $firebaseUid = sanitizeInput($data['user_id']);
    $email = sanitizeInput($data['email']);
    $displayName = isset($data['display_name']) ? sanitizeInput($data['display_name']) : '';
    
    // Check if user exists in database
    $userQuery = "SELECT user_id, email, first_name, last_name, language_code, currency_code 
                  FROM users WHERE firebase_uid = ?";
    $userResult = $db->query($userQuery, [$firebaseUid], 's');
    
    if ($userResult && $userResult->num_rows > 0) {
        // User exists, update last login time
        $user = $userResult->fetch_assoc();
        
        $updateQuery = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
        $db->query($updateQuery, [$user['user_id']], 'i');
        
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        
        // Set language and currency preferences
        if ($user['language_code']) {
            $_SESSION['language'] = $user['language_code'];
        }
        
        if ($user['currency_code']) {
            $_SESSION['currency'] = $user['currency_code'];
        }
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'Session stored successfully'
        ]);
    } else {
        // User doesn't exist in database, create new user
        $firstName = '';
        $lastName = '';
        
        if ($displayName) {
            $nameParts = explode(' ', $displayName, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        } else {
            // Use email username as first name
            $emailParts = explode('@', $email);
            $firstName = $emailParts[0];
        }
        
        $insertQuery = "INSERT INTO users (firebase_uid, email, first_name, last_name, registration_date, last_login) 
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
        
        $db->query($insertQuery, [$firebaseUid, $email, $firstName, $lastName], 'ssss');
        
        // Get the newly created user ID
        $userId = $db->getConnection()->insert_id;
        
        // Set session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['firebase_uid'] = $firebaseUid;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'New user created and session stored'
        ]);
    }
}

/**
 * Handle clearing session data when user signs out
 * 
 * @param Database $db Database connection
 * @param array $data Not used, but kept for consistency
 */
function handleClearSession($db, $data) {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Success response
    echo json_encode([
        'success' => true, 
        'message' => 'Session cleared successfully'
    ]);
}

// Close database connection
$db->close();
?> 