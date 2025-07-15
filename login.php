<?php
// Define base path
define('STORE_PATH', dirname(__FILE__));

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any unexpected output
ob_start();

// Include common functions and database
require_once 'includes/functions.php';
require_once 'includes/database.php';

// Initialize database connection
$db = new Database();

// Start session
session_start();

// Log request data
error_log("Login.php - Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Login.php - POST data: " . print_r($_POST, true));

try {
    // If already logged in, redirect to appropriate page
    if (isset($_SESSION['user_id'])) {
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
        header('Location: ' . $redirect);
        exit();
    }

    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        header('Content-Type: application/json');
        ob_clean(); // Clear any previous output
        
        if ($_POST['action'] === 'login') {
            error_log("Login.php - Processing login action");
            
            $email = $db->escape($_POST['email']);
            $password = $_POST['password'];
            
            error_log("Login.php - Attempting login for email: " . $email);
            
            // Get user from database
            $result = $db->query("SELECT * FROM users WHERE email = '$email' AND is_active = 1 LIMIT 1");
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                error_log("Login.php - User found with ID: " . $user['user_id']);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    error_log("Login.php - Password verified successfully");
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    
                    // Update last login
                    $db->query("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = {$user['user_id']}");
                    
                    // Set remember me cookie if requested
                    if (isset($_POST['remember']) && $_POST['remember'] == 1) {
                        $token = bin2hex(random_bytes(32));
                        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
                        
                        $db->query("UPDATE users SET remember_token = '$token' WHERE user_id = {$user['user_id']}");
                        setcookie('remember_token', $token, $expiry, '/', '', true, true);
                        error_log("Login.php - Remember me token set");
                    }
                    
                    // Determine redirect based on user role
                    $redirect = 'index.php';
                    if ($user['role'] === 'admin') {
                        $redirect = 'admin/index.php';
                    } else if (isset($_GET['redirect'])) {
                        $redirect = $_GET['redirect'];
                    }
                    
                    // Return success response for AJAX
                    echo json_encode([
                        'success' => true,
                        'redirect' => $redirect
                    ]);
                    exit();
                } else {
                    error_log("Login.php - Password verification failed");
                }
            } else {
                error_log("Login.php - No user found with email: " . $email);
            }
            
            // Return error response for AJAX
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
            exit();
        }
    }

} catch (Exception $e) {
    error_log("Login.php - Error: " . $e->getMessage());
    error_log("Login.php - Stack trace: " . $e->getTraceAsString());
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred',
            'debug' => [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ]);
        exit();
    }
} finally {
    // If this is not a POST request, or if we haven't exited yet, continue with page rendering
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // End output buffering for non-POST requests
        ob_end_clean();
    }
}

// Set page title and meta data
$pageTitle = "Login & Register - NeoWebX Template Store";
$pageDescription = "Sign in to your account or create a new one to access your downloads, manage orders, and more.";
$pageKeywords = "login, register, account, sign in, user account, template store";

// Additional CSS
$additionalCSS = [
    "assets/css/auth.css"
];

// Additional Scripts
$additionalScripts = [
    "assets/js/auth.js"
];

// Include header
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Login & Register</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Account</span>
        </div>
    </div>
</section>

<!-- Authentication Section -->
<section class="auth-section section-padding">
    <div class="container">
        <div class="auth-container">
            <!-- Tabs Navigation -->
            <div class="auth-tabs">
                <button class="auth-tab active" data-tab="login">Login</button>
                <button class="auth-tab" data-tab="register">Register</button>
            </div>
            
            <!-- Login Form -->
            <div class="auth-content login-content active" id="loginContent">
                <form id="loginForm" class="auth-form">
                    <div class="auth-message"></div>
                    
                    <div class="form-group">
                        <label for="loginEmail">Email Address</label>
                        <input type="email" id="loginEmail" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <div class="password-field">
                            <input type="password" id="loginPassword" name="password" class="form-control" required>
                            <button type="button" class="password-toggle" title="Show/Hide Password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input type="checkbox" id="rememberMe" name="remember" value="1">
                                <label for="rememberMe">Remember me</label>
                            </div>
                            <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn-primary auth-btn">Login to Your Account</button>
                    </div>
                </form>
            </div>
            
            <!-- Register Form -->
            <div class="auth-content register-content" id="registerContent">
                <form id="registerForm" class="auth-form">
                    <div class="auth-message"></div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="registerFirstName">First Name</label>
                            <input type="text" id="registerFirstName" name="first_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="registerLastName">Last Name</label>
                            <input type="text" id="registerLastName" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="registerEmail">Email Address</label>
                        <input type="email" id="registerEmail" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="registerPassword">Password</label>
                        <div class="password-field">
                            <input type="password" id="registerPassword" name="password" class="form-control" required minlength="8">
                            <button type="button" class="password-toggle" title="Show/Hide Password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-meter">
                                <div class="strength-meter-fill" data-strength="0"></div>
                            </div>
                            <div class="strength-text">Password strength: <span>Poor</span></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="registerConfirmPassword">Confirm Password</label>
                        <div class="password-field">
                            <input type="password" id="registerConfirmPassword" name="confirm_password" class="form-control" required>
                            <button type="button" class="password-toggle" title="Show/Hide Password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="termsAgreement" name="terms_agreement" required>
                            <label for="termsAgreement">I agree to the <a href="terms-of-service.php" target="_blank">Terms of Service</a> and <a href="privacy-policy.php" target="_blank">Privacy Policy</a></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="newsletterOpt" name="newsletter_opt_in">
                            <label for="newsletterOpt">Subscribe to our newsletter for updates and offers</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary auth-btn">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?>
