<?php
// Define base path
define('STORE_PATH', true);

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/auth.css'];

// Page title
$pageTitle = "Set New Password";

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Set New Password</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Set New Password</span>
        </div>
    </div>
</section>

<!-- New Password Section -->
<section class="auth-section section-padding">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Create New Password</h2>
                <p>Please enter your new password</p>
            </div>
            
            <div id="password-error" class="alert alert-danger" style="display: none;"></div>
            <div id="password-success" class="alert alert-success" style="display: none;"></div>
            
            <div id="loading" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Verifying your reset link...</p>
            </div>
            
            <form id="new-password-form" class="auth-form" style="display: none;">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required pattern=".{8,}" title="Password must be at least 8 characters">
                        <button type="button" class="password-toggle" aria-label="Toggle Password Visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-meter-fill" style="width: 0%"></div>
                        </div>
                        <div class="strength-text">Password should be at least 8 characters with letters and numbers</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <button type="button" class="password-toggle" aria-label="Toggle Password Visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Set New Password</button>
                </div>
            </form>
            
            <div class="auth-footer" id="auth-footer" style="display: none;">
                <p>Remember your password? <a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const newPasswordForm = document.getElementById('new-password-form');
    const errorDiv = document.getElementById('password-error');
    const successDiv = document.getElementById('password-success');
    const loadingDiv = document.getElementById('loading');
    const authFooter = document.getElementById('auth-footer');
    
    // Toggle password visibility
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Password strength meter
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.querySelector('.strength-meter-fill');
    const strengthText = document.querySelector('.strength-text');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Calculate password strength
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
        if (password.match(/\d/)) strength += 1;
        if (password.match(/[^a-zA-Z\d]/)) strength += 1;
        
        // Update meter
        switch (strength) {
            case 0:
                strengthMeter.style.width = '0%';
                strengthMeter.style.backgroundColor = '#eee';
                strengthText.textContent = 'Password should be at least 8 characters with letters and numbers';
                break;
            case 1:
                strengthMeter.style.width = '33%';
                strengthMeter.style.backgroundColor = '#ff4d4d';
                strengthText.textContent = 'Weak';
                break;
            case 2:
            case 3:
                strengthMeter.style.width = '66%';
                strengthMeter.style.backgroundColor = '#ffa700';
                strengthText.textContent = 'Medium';
                break;
            case 4:
                strengthMeter.style.width = '100%';
                strengthMeter.style.backgroundColor = '#32a852';
                strengthText.textContent = 'Strong';
                break;
        }
    });
    
    // Get the action code from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const actionCode = urlParams.get('oobCode');
    
    if (!actionCode) {
        // No action code found in URL
        errorDiv.textContent = 'Invalid or expired password reset link. Please request a new password reset link.';
        errorDiv.style.display = 'block';
        loadingDiv.style.display = 'none';
        authFooter.style.display = 'block';
        return;
    }
    
    // Show loading while we verify the action code
    loadingDiv.style.display = 'block';
    
    // Verify the password reset code
    firebase.auth().verifyPasswordResetCode(actionCode)
        .then(() => {
            // Action code is valid, show password form
            loadingDiv.style.display = 'none';
            newPasswordForm.style.display = 'block';
            authFooter.style.display = 'block';
        })
        .catch((error) => {
            // Invalid or expired action code
            console.error('Invalid reset code:', error);
            errorDiv.textContent = 'Invalid or expired password reset link. Please request a new password reset link.';
            errorDiv.style.display = 'block';
            loadingDiv.style.display = 'none';
            authFooter.style.display = 'block';
        });
    
    // Handle form submission
    newPasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get password values
        const password = passwordInput.value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Clear previous messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Basic validation
        if (!password || !confirmPassword) {
            errorDiv.textContent = 'Please enter your new password and confirm it.';
            errorDiv.style.display = 'block';
            return;
        }
        
        if (password.length < 8) {
            errorDiv.textContent = 'Password must be at least 8 characters long.';
            errorDiv.style.display = 'block';
            return;
        }
        
        if (password !== confirmPassword) {
            errorDiv.textContent = 'Passwords do not match.';
            errorDiv.style.display = 'block';
            return;
        }
        
        // Show loading while we complete the password reset
        loadingDiv.style.display = 'block';
        newPasswordForm.style.display = 'none';
        
        // Complete the password reset
        firebase.auth().confirmPasswordReset(actionCode, password)
            .then(() => {
                // Password reset successful
                loadingDiv.style.display = 'none';
                successDiv.innerHTML = 'Your password has been reset successfully! You can now <a href="login.php">log in</a> with your new password.';
                successDiv.style.display = 'block';
                
                // Redirect to login after 3 seconds
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            })
            .catch((error) => {
                // Error completing password reset
                console.error('Error completing password reset:', error);
                
                loadingDiv.style.display = 'none';
                newPasswordForm.style.display = 'block';
                
                let errorMessage = 'An error occurred while resetting your password. Please try again.';
                
                if (error.code === 'auth/weak-password') {
                    errorMessage = 'The password is too weak. Please choose a stronger password.';
                } else if (error.code === 'auth/expired-action-code') {
                    errorMessage = 'This reset link has expired. Please request a new password reset link.';
                } else if (error.code === 'auth/invalid-action-code') {
                    errorMessage = 'Invalid reset link. Please request a new password reset link.';
                } else {
                    errorMessage = 'Error: ' + error.message;
                }
                
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';
            });
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 