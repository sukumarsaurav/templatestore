/**
 * Authentication JavaScript for NeoWebX Template Store
 * Handles login, registration, and session management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Element references
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authTabs = document.querySelectorAll('.auth-tab');
    const loginContent = document.getElementById('loginContent');
    const registerContent = document.getElementById('registerContent');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    const registerPassword = document.getElementById('registerPassword');
    const strengthMeter = document.querySelector('.strength-meter-fill');
    const strengthText = document.querySelector('.strength-text span');

    // Tab switching
    authTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs and content
            authTabs.forEach(t => t.classList.remove('active'));
            loginContent.classList.remove('active');
            registerContent.classList.remove('active');
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            const tabName = this.getAttribute('data-tab');
            
            if (tabName === 'login') {
                loginContent.classList.add('active');
            } else {
                registerContent.classList.add('active');
            }
        });
    });

    // Password visibility toggles
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordField = this.closest('.password-field').querySelector('input');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Password strength meter
    if (registerPassword) {
        registerPassword.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            strengthMeter.setAttribute('data-strength', strength.score);
            strengthText.textContent = strength.label;
        });
    }

    // Login form submission
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const rememberMe = document.getElementById('rememberMe').checked;
            const messageDiv = loginForm.querySelector('.auth-message');
            
            // Clear previous messages
            messageDiv.className = 'auth-message';
            messageDiv.style.display = 'none';
            
            // Validate form
            if (!email || !password) {
                showMessage(messageDiv, 'error', 'Please enter both email and password.');
                return;
            }
            
            // Show loading state
            const loginBtn = loginForm.querySelector('button[type="submit"]');
            const originalText = loginBtn.textContent;
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            
            try {
                // Create form data
                const formData = new FormData();
                formData.append('action', 'login');
                formData.append('email', email);
                formData.append('password', password);
                formData.append('remember', rememberMe ? '1' : '0');
                
                // Send login request
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage(messageDiv, 'success', 'Login successful! Redirecting...');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Invalid email or password');
                }
            } catch (error) {
                console.error('Login error:', error);
                showMessage(messageDiv, 'error', error.message || 'An error occurred. Please try again.');
                loginBtn.disabled = false;
                loginBtn.textContent = originalText;
            }
        });
    }

    // Registration form submission
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            const firstName = document.getElementById('registerFirstName').value;
            const lastName = document.getElementById('registerLastName').value;
            const termsAgreement = document.getElementById('termsAgreement').checked;
            const newsletterOptIn = document.getElementById('newsletterOpt').checked;
            const messageDiv = registerForm.querySelector('.auth-message');
            
            // Clear previous messages
            messageDiv.className = 'auth-message';
            messageDiv.style.display = 'none';
            
            // Validate form
            if (!email || !password || !confirmPassword || !firstName || !lastName) {
                showMessage(messageDiv, 'error', 'Please fill in all required fields.');
                return;
            }
            
            if (!termsAgreement) {
                showMessage(messageDiv, 'error', 'You must agree to the Terms of Service and Privacy Policy.');
                return;
            }
            
            if (password !== confirmPassword) {
                showMessage(messageDiv, 'error', 'Passwords do not match.');
                return;
            }
            
            // Check password strength
            const strength = calculatePasswordStrength(password);
            if (strength.score < 2) {
                showMessage(messageDiv, 'error', 'Please choose a stronger password.');
                return;
            }
            
            // Show loading state
            const registerBtn = registerForm.querySelector('button[type="submit"]');
            const originalText = registerBtn.textContent;
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
            
            try {
                // Create form data
                const formData = new FormData();
                formData.append('action', 'register');
                formData.append('email', email);
                formData.append('password', password);
                formData.append('confirm_password', confirmPassword);
                formData.append('first_name', firstName);
                formData.append('last_name', lastName);
                formData.append('newsletter_opt_in', newsletterOptIn ? '1' : '0');
                
                // Send registration request
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage(messageDiv, 'success', 'Account created successfully! Redirecting...');
                    setTimeout(() => {
                        window.location.href = 'account.php?welcome=1';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Error creating account');
                }
            } catch (error) {
                console.error('Registration error:', error);
                showMessage(messageDiv, 'error', error.message || 'An error occurred. Please try again.');
                registerBtn.disabled = false;
                registerBtn.textContent = originalText;
            }
        });
    }

    // Helper functions
    function showMessage(element, type, message) {
        element.textContent = message;
        element.className = 'auth-message ' + type;
        element.style.display = 'block';
    }
    
    function calculatePasswordStrength(password) {
        // Basic password strength calculation
        let score = 0;
        let label = 'Poor';
        
        // Length check
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        
        // Complexity checks
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        // Adjust score based on length
        if (password.length < 6) score = 0;
        
        // Cap score at 4
        score = Math.min(score, 4);
        
        // Set label based on score
        switch (score) {
            case 0:
                label = 'Poor';
                break;
            case 1:
                label = 'Weak';
                break;
            case 2:
                label = 'Fair';
                break;
            case 3:
                label = 'Good';
                break;
            case 4:
                label = 'Strong';
                break;
        }
        
        return { score, label };
    }
}); 