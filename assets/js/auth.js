/**
 * Authentication JavaScript for NeoWebX Template Store
 * Handles login, registration, and Firebase integration
 */

document.addEventListener('DOMContentLoaded', function() {
    // Element references
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authTabs = document.querySelectorAll('.auth-tab');
    const loginContent = document.getElementById('loginContent');
    const registerContent = document.getElementById('registerContent');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    const googleLoginBtn = document.getElementById('googleLoginBtn');
    const facebookLoginBtn = document.getElementById('facebookLoginBtn');
    const googleRegisterBtn = document.getElementById('googleRegisterBtn');
    const facebookRegisterBtn = document.getElementById('facebookRegisterBtn');
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
        loginForm.addEventListener('submit', function(e) {
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
            
            // Set persistence based on remember me checkbox
            const persistence = rememberMe ? 
                firebase.auth.Auth.Persistence.LOCAL : 
                firebase.auth.Auth.Persistence.SESSION;
            
            firebase.auth().setPersistence(persistence)
                .then(() => {
                    // Show loading state
                    const loginBtn = loginForm.querySelector('button[type="submit"]');
                    const originalText = loginBtn.textContent;
                    loginBtn.disabled = true;
                    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
                    
                    // Sign in with email and password
                    return firebase.auth().signInWithEmailAndPassword(email, password);
                })
                .then(userCredential => {
                    // Login successful - show success message
                    showMessage(messageDiv, 'success', 'Login successful! Redirecting...');
                    
                    // Store user data in database
                    return storeUserLoginData(userCredential.user);
                })
                .then(() => {
                    // Redirect after successful login
                    setTimeout(() => {
                        window.location.href = redirectUrl || 'index.php';
                    }, 1500);
                })
                .catch(error => {
                    console.error('Login error:', error);
                    
                    // Reset button state
                    const loginBtn = loginForm.querySelector('button[type="submit"]');
                    loginBtn.disabled = false;
                    loginBtn.textContent = 'Login to Your Account';
                    
                    // Display user-friendly error message
                    let errorMessage = 'Invalid email or password. Please try again.';
                    
                    if (error.code === 'auth/user-not-found' || error.code === 'auth/wrong-password') {
                        errorMessage = 'Invalid email or password. Please try again.';
                    } else if (error.code === 'auth/too-many-requests') {
                        errorMessage = 'Too many unsuccessful login attempts. Please try again later or reset your password.';
                    } else if (error.code === 'auth/user-disabled') {
                        errorMessage = 'This account has been disabled. Please contact support.';
                    } else {
                        errorMessage = 'Error signing in: ' + error.message;
                    }
                    
                    showMessage(messageDiv, 'error', errorMessage);
                });
        });
    }

    // Registration form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
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
            
            // Create user with email and password
            firebase.auth().createUserWithEmailAndPassword(email, password)
                .then(userCredential => {
                    // Add display name to user profile
                    return userCredential.user.updateProfile({
                        displayName: `${firstName} ${lastName}`
                    }).then(() => userCredential);
                })
                .then(userCredential => {
                    // Save additional user data to database
                    const userData = {
                        firebase_uid: userCredential.user.uid,
                        email: email,
                        first_name: firstName,
                        last_name: lastName,
                        newsletter_opt_in: newsletterOptIn ? 1 : 0,
                        registration_date: new Date().toISOString()
                    };
                    
                    return storeUserRegistrationData(userData);
                })
                .then(() => {
                    // Registration successful - show success message
                    showMessage(messageDiv, 'success', 'Account created successfully! Redirecting...');
                    
                    // Redirect after successful registration
                    setTimeout(() => {
                        window.location.href = redirectUrl || 'index.php';
                    }, 1500);
                })
                .catch(error => {
                    console.error('Registration error:', error);
                    
                    // Reset button state
                    registerBtn.disabled = false;
                    registerBtn.textContent = 'Create Account';
                    
                    // Display user-friendly error message
                    let errorMessage = 'Error creating account. Please try again.';
                    
                    if (error.code === 'auth/email-already-in-use') {
                        errorMessage = 'This email address is already in use. Please try logging in or use a different email.';
                    } else if (error.code === 'auth/invalid-email') {
                        errorMessage = 'Please enter a valid email address.';
                    } else if (error.code === 'auth/weak-password') {
                        errorMessage = 'Password is too weak. Please choose a stronger password.';
                    } else {
                        errorMessage = 'Error creating account: ' + error.message;
                    }
                    
                    showMessage(messageDiv, 'error', errorMessage);
                });
        });
    }

    // Social sign-in buttons
    if (googleLoginBtn) {
        googleLoginBtn.addEventListener('click', function() {
            signInWithGoogle(loginForm.querySelector('.auth-message'));
        });
    }
    
    if (facebookLoginBtn) {
        facebookLoginBtn.addEventListener('click', function() {
            signInWithFacebook(loginForm.querySelector('.auth-message'));
        });
    }
    
    if (googleRegisterBtn) {
        googleRegisterBtn.addEventListener('click', function() {
            signInWithGoogle(registerForm.querySelector('.auth-message'));
        });
    }
    
    if (facebookRegisterBtn) {
        facebookRegisterBtn.addEventListener('click', function() {
            signInWithFacebook(registerForm.querySelector('.auth-message'));
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
    
    function signInWithGoogle(messageElement) {
        const provider = new firebase.auth.GoogleAuthProvider();
        
        firebase.auth().signInWithPopup(provider)
            .then(result => {
                // Store user data in database
                return storeUserLoginData(result.user);
            })
            .then(() => {
                // Redirect after successful login
                window.location.href = redirectUrl || 'index.php';
            })
            .catch(error => {
                console.error('Google sign-in error:', error);
                showMessage(messageElement, 'error', 'Error signing in with Google: ' + error.message);
            });
    }
    
    function signInWithFacebook(messageElement) {
        const provider = new firebase.auth.FacebookAuthProvider();
        
        firebase.auth().signInWithPopup(provider)
            .then(result => {
                // Store user data in database
                return storeUserLoginData(result.user);
            })
            .then(() => {
                // Redirect after successful login
                window.location.href = redirectUrl || 'index.php';
            })
            .catch(error => {
                console.error('Facebook sign-in error:', error);
                showMessage(messageElement, 'error', 'Error signing in with Facebook: ' + error.message);
            });
    }
    
    function storeUserLoginData(user) {
        // Make an AJAX call to store user session data
        return fetch('/api/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'store_session',
                user_id: user.uid,
                email: user.email,
                display_name: user.displayName || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error storing user session:', data.message || 'Unknown error');
                return Promise.reject(new Error(data.message || 'Unknown error'));
            }
            return data;
        });
    }
    
    function storeUserRegistrationData(userData) {
        // Make an AJAX call to store user registration data
        return fetch('/api/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'store_session',
                user_id: userData.user.uid,
                email: userData.user.email,
                display_name: userData.user.displayName || '',
                first_name: userData.firstName || '',
                last_name: userData.lastName || '',
                newsletter_opt_in: userData.newsletterOptIn ? 1 : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Error storing user data:', data.message || 'Unknown error');
                return Promise.reject(new Error(data.message || 'Unknown error'));
            }
            return data;
        });
    }

    // Check if user is already logged in
    firebase.auth().onAuthStateChanged(function(user) {
        if (user && window.location.pathname.includes('login.php')) {
            // Check if the page has a redirect parameter before redirecting
            if (!window.location.search.includes('redirect=')) {
                window.location.href = 'index.php';
            }
        }
    });
}); 