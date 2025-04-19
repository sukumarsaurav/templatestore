<?php
// Define base path
define('STORE_PATH', dirname(__FILE__));

// Include common functions and database
require_once 'includes/functions.php';
require_once 'includes/database.php';

// Initialize database connection
$db = new Database();
$firebaseAuth = new FirebaseAuth();

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

// Get redirect URL if set
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

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
                                <input type="checkbox" id="rememberMe" name="remember">
                                <label for="rememberMe">Remember me</label>
                            </div>
                            <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn-primary auth-btn">Login to Your Account</button>
                    </div>
                </form>
                
                <div class="auth-divider">
                    <span>or continue with</span>
                </div>
                
                <div class="social-auth">
                    <button type="button" id="googleLoginBtn" class="social-btn google-btn">
                        <img src="assets/images/icons/google.svg" alt="Google">
                        <span>Google</span>
                    </button>
                    <button type="button" id="facebookLoginBtn" class="social-btn facebook-btn">
                        <img src="assets/images/icons/facebook.svg" alt="Facebook">
                        <span>Facebook</span>
                    </button>
                </div>
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
                            <label for="newsletterOpt">Subscribe to our newsletter for updates on new templates and offers</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary auth-btn">Create Account</button>
                </form>
                
                <div class="auth-divider">
                    <span>or sign up with</span>
                </div>
                
                <div class="social-auth">
                    <button type="button" id="googleRegisterBtn" class="social-btn google-btn">
                        <img src="assets/images/icons/google.svg" alt="Google">
                        <span>Google</span>
                    </button>
                    <button type="button" id="facebookRegisterBtn" class="social-btn facebook-btn">
                        <img src="assets/images/icons/facebook.svg" alt="Facebook">
                        <span>Facebook</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Pass redirect URL to client-side script
const redirectUrl = "<?php echo htmlspecialchars($redirect); ?>";
</script>

<!-- Firebase Authentication Code -->
<?php echo $firebaseAuth->initFirebaseJS(); ?>

<?php
// Include footer
require_once 'includes/footer.php';
?> 