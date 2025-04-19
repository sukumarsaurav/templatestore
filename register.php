<?php
// Define base path
define('STORE_PATH', true);

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/auth.css'];

// Page title
$pageTitle = "Register";

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Create Account</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Register</span>
        </div>
    </div>
</section>

<!-- Register Section -->
<section class="auth-section section-padding">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Join TemplateHub</h2>
                <p>Create an account to browse and purchase templates</p>
            </div>
            
            <div id="register-error" class="alert alert-danger" style="display: none;"></div>
            <div id="register-success" class="alert alert-success" style="display: none;"></div>
            
            <form id="register-form" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
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
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        <button type="button" class="password-toggle" aria-label="Toggle Password Visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group terms-check">
                    <input type="checkbox" id="agree_terms" name="agree_terms" required>
                    <label for="agree_terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
            
            <div class="auth-divider">
                <span>or register with</span>
            </div>
            
            <div class="social-login">
                <button id="google-register" class="social-btn google-btn">
                    <i class="fab fa-google"></i> Google
                </button>
                <button id="facebook-register" class="social-btn facebook-btn">
                    <i class="fab fa-facebook-f"></i> Facebook
                </button>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility for both password fields
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
    
    // Handle registration form submission
    const registerForm = document.getElementById('register-form');
    const errorDiv = document.getElementById('register-error');
    const successDiv = document.getElementById('register-success');
    
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const agreeTerms = document.getElementById('agree_terms').checked;
        
        // Clear previous error messages
        errorDiv.style.display = 'none';
        successDiv.style.display = 'none';
        
        // Basic validation
        if (!name || !email || !password || !confirmPassword) {
            errorDiv.textContent = 'Please fill in all fields.';
            errorDiv.style.display = 'block';
            return;
        }
        
        if (password !== confirmPassword) {
            errorDiv.textContent = 'Passwords do not match.';
            errorDiv.style.display = 'block';
            return;
        }
        
        if (password.length < 8) {
            errorDiv.textContent = 'Password must be at least 8 characters long.';
            errorDiv.style.display = 'block';
            return;
        }
        
        if (!agreeTerms) {
            errorDiv.textContent = 'You must agree to the Terms of Service and Privacy Policy.';
            errorDiv.style.display = 'block';
            return;
        }
        
        // Register the user using Firebase
        handleRegistration(email, password, name)
            .then(() => {
                // Registration successful
                successDiv.textContent = 'Registration successful! Redirecting to your account...';
                successDiv.style.display = 'block';
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = 'account.php';
                }, 2000);
            })
            .catch((error) => {
                // Handle registration errors
                console.error('Registration error:', error);
                
                let errorMessage = 'Error creating account. Please try again.';
                
                if (error.code === 'auth/email-already-in-use') {
                    errorMessage = 'This email is already in use. Please try logging in or use a different email.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Please provide a valid email address.';
                } else if (error.code === 'auth/weak-password') {
                    errorMessage = 'The password is too weak. Please choose a stronger password.';
                } else {
                    errorMessage = 'Error: ' + error.message;
                }
                
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';
            });
    });
    
    // Google Sign Up
    const googleBtn = document.getElementById('google-register');
    googleBtn.addEventListener('click', function() {
        signInWithGoogle()
            .then(() => {
                // Redirect after registration
                window.location.href = 'account.php';
            })
            .catch((error) => {
                console.error('Google sign-up error:', error);
                errorDiv.textContent = 'Error registering with Google: ' + error.message;
                errorDiv.style.display = 'block';
            });
    });
    
    // Facebook Sign Up
    const facebookBtn = document.getElementById('facebook-register');
    facebookBtn.addEventListener('click', function() {
        signInWithFacebook()
            .then(() => {
                // Redirect after registration
                window.location.href = 'account.php';
            })
            .catch((error) => {
                console.error('Facebook sign-up error:', error);
                errorDiv.textContent = 'Error registering with Facebook: ' + error.message;
                errorDiv.style.display = 'block';
            });
    });
    
    // Check if user is already logged in
    firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
            // User is already signed in, redirect to account page
            window.location.href = 'account.php';
        }
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 