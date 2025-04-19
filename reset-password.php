<?php
// Define base path
define('STORE_PATH', true);

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/auth.css'];

// Page title
$pageTitle = "Reset Password";

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Reset Password</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Reset Password</span>
        </div>
    </div>
</section>

<!-- Reset Password Section -->
<section class="auth-section section-padding">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Reset Your Password</h2>
                <p>Enter your email address to receive a password reset link</p>
            </div>
            
            <div id="reset-error" class="alert alert-danger" style="display: none;"></div>
            <div id="reset-success" class="alert alert-success" style="display: none;"></div>
            
            <form id="reset-form" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>Remember your password? <a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const resetForm = document.getElementById('reset-form');
    const errorDiv = document.getElementById('reset-error');
    const successDiv = document.getElementById('reset-success');
    
    resetForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get email value
        const email = document.getElementById('email').value;
        
        // Clear previous messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Basic validation
        if (!email) {
            errorDiv.textContent = 'Please enter your email address.';
            errorDiv.style.display = 'block';
            return;
        }
        
        // Call Firebase password reset function
        resetPassword(email)
            .then(() => {
                // Success
                successDiv.innerHTML = 'Password reset email sent! Please check your inbox and follow the instructions in the email.<br>You may need to check your spam folder if you don\'t see it in your inbox.';
                successDiv.style.display = 'block';
                resetForm.reset();
            })
            .catch((error) => {
                // Handle errors
                console.error('Password reset error:', error);
                
                let errorMessage = 'Error sending password reset email.';
                
                if (error.code === 'auth/user-not-found') {
                    errorMessage = 'No account found with this email address.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Please provide a valid email address.';
                } else if (error.code === 'auth/too-many-requests') {
                    errorMessage = 'Too many reset attempts. Please try again later.';
                } else {
                    errorMessage = 'Error: ' + error.message;
                }
                
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';
            });
    });
    
    // Check if user is already logged in
    firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
            // User is already signed in, show a message
            errorDiv.textContent = 'You are already logged in. If you need to reset your password, please log out first.';
            errorDiv.style.display = 'block';
            document.getElementById('reset-form').style.display = 'none';
        }
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 