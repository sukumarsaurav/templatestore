<?php
// Define base path
define('STORE_PATH', dirname(__FILE__));

// Start session
session_start();

// Include common functions and database
require_once 'includes/functions.php';
require_once 'includes/database.php';

// Initialize database connection
$db = new Database();

// Get redirect URL
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

// Clear remember me token if exists
if (isset($_SESSION['user_id'])) {
    $db->query("UPDATE users SET remember_token = NULL WHERE user_id = {$_SESSION['user_id']}");
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ' . $redirect);
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - NeoWebX Template Store</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="../favicon/favicon.svg">
    <link rel="shortcut icon" href="../favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .logout-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .logout-icon {
            font-size: 48px;
            color: var(--yinmn-blue);
            margin-bottom: 20px;
        }
        
        .logout-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .logout-message {
            color: #666;
            margin-bottom: 30px;
        }
        
        .loading {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 3px solid rgba(35, 64, 110, 0.3);
            border-radius: 50%;
            border-top-color: var(--yinmn-blue);
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Firebase SDKs -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <?php echo $firebaseAuth->initFirebaseJS(); ?>
</head>
<body>
    <div class="logout-container">
        <div class="loading"></div>
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h1 class="logout-title">Logging Out</h1>
        <p class="logout-message">Please wait while we sign you out...</p>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sign out from Firebase
            firebase.auth().signOut()
                .then(() => {
                    console.log('User signed out successfully');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = '<?php echo htmlspecialchars($redirect); ?>';
                    }, 1500);
                })
                .catch((error) => {
                    console.error('Sign out error:', error);
                    
                    // Redirect anyway
                    setTimeout(() => {
                        window.location.href = '<?php echo htmlspecialchars($redirect); ?>';
                    }, 1500);
                });
        });
    </script>
</body>
</html>
<?php
// Close database connection
$db->close();
?> 