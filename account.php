<?php
// Define base path
define('STORE_PATH', true);

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/account.css'];

// Page title
$pageTitle = "My Account";

// Get active tab
$activeTab = isset($_GET['tab']) ? sanitizeInput($_GET['tab']) : 'dashboard';

// Include header
include 'includes/header.php';

// Sample orders data - in a real application, this would come from a database
$orders = [
    [
        'id' => 'ORD-10045',
        'date' => '2023-05-20',
        'status' => 'Completed',
        'items' => 2,
        'total' => 128.99
    ],
    [
        'id' => 'ORD-10032',
        'date' => '2023-04-12',
        'status' => 'Completed',
        'items' => 1,
        'total' => 59.99
    ],
    [
        'id' => 'ORD-10018',
        'date' => '2023-03-05',
        'status' => 'Completed',
        'items' => 3,
        'total' => 199.97
    ]
];

// Sample downloads data - in a real application, this would come from a database
$downloads = [
    [
        'id' => 'DWN-45678',
        'name' => 'PropertyPro Template',
        'date' => '2023-05-20',
        'expires' => '2024-05-20',
        'downloads' => 2,
        'file' => 'propertypro-v1.2.0.zip'
    ],
    [
        'id' => 'DWN-45623',
        'name' => 'MediCare Template',
        'date' => '2023-04-12',
        'expires' => '2024-04-12',
        'downloads' => 1,
        'file' => 'medicare-v2.0.1.zip'
    ],
    [
        'id' => 'DWN-45591',
        'name' => 'EduLearn Template',
        'date' => '2023-03-05',
        'expires' => '2024-03-05',
        'downloads' => 3,
        'file' => 'edulearn-v1.5.2.zip'
    ]
];

// Sample wishlist data - in a real application, this would come from a database
$wishlist = [
    [
        'id' => 4,
        'name' => 'ShopEase',
        'category' => 'E-commerce',
        'price' => 69.99,
        'image' => 'assets/images/templates/template4.jpg'
    ],
    [
        'id' => 7,
        'name' => 'EstateLux',
        'category' => 'Real Estate',
        'price' => 59.99,
        'image' => 'assets/images/templates/template7.jpg'
    ],
    [
        'id' => 9,
        'name' => 'FoodieWeb',
        'category' => 'Food & Beverage',
        'price' => 49.99,
        'image' => 'assets/images/templates/template9.jpg'
    ]
];
?>

<!-- Auth Check -->
<div id="auth-check" style="display: none;">
    <div class="container section-padding text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-3">Checking authentication...</p>
    </div>
</div>

<!-- Account Content -->
<div id="account-content" style="display: none;">
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>My Account</h1>
            <div class="breadcrumb">
                <a href="index.php">Home</a> / <span>My Account</span>
            </div>
        </div>
    </section>

    <!-- Account Section -->
    <section class="account-section section-padding">
        <div class="container">
            <div class="row">
                <!-- Account Sidebar -->
                <div class="col-lg-3">
                    <div class="account-sidebar">
                        <div class="user-info">
                            <div class="user-avatar">
                                <span id="user-initials"></span>
                            </div>
                            <div class="user-details">
                                <h4 id="user-name"></h4>
                                <p id="user-email" class="user-email"></p>
                            </div>
                        </div>
                        
                        <ul class="account-menu">
                            <li class="<?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>">
                                <a href="account.php?tab=dashboard">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="<?php echo $activeTab === 'orders' ? 'active' : ''; ?>">
                                <a href="account.php?tab=orders">
                                    <i class="fas fa-shopping-bag"></i> My Orders
                                </a>
                            </li>
                            <li class="<?php echo $activeTab === 'downloads' ? 'active' : ''; ?>">
                                <a href="account.php?tab=downloads">
                                    <i class="fas fa-download"></i> Downloads
                                </a>
                            </li>
                            <li class="<?php echo $activeTab === 'wishlist' ? 'active' : ''; ?>">
                                <a href="account.php?tab=wishlist">
                                    <i class="far fa-heart"></i> Wishlist
                                </a>
                            </li>
                            <li class="<?php echo $activeTab === 'profile' ? 'active' : ''; ?>">
                                <a href="account.php?tab=profile">
                                    <i class="fas fa-user"></i> Profile Details
                                </a>
                            </li>
                            <li class="<?php echo $activeTab === 'change-password' ? 'active' : ''; ?>">
                                <a href="account.php?tab=change-password">
                                    <i class="fas fa-lock"></i> Change Password
                                </a>
                            </li>
                            <li>
                                <a href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Account Content -->
                <div class="col-lg-9">
                    <div class="account-content">
                        <?php if ($activeTab === 'dashboard'): ?>
                            <!-- Dashboard Tab -->
                            <div class="dashboard-tab">
                                <h2>Dashboard</h2>
                                
                                <div class="welcome-message">
                                    <p>Hello <strong id="dashboard-name"></strong>, welcome to your account dashboard!</p>
                                    <p>From here you can view your recent orders, download your templates, manage your wishlist, and update your profile information.</p>
                                </div>
                                
                                <div class="dashboard-stats">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <div class="stat-details">
                                            <h3><?php echo count($orders); ?></h3>
                                            <p>Orders</p>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-download"></i>
                                        </div>
                                        <div class="stat-details">
                                            <h3><?php echo count($downloads); ?></h3>
                                            <p>Downloads</p>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="far fa-heart"></i>
                                        </div>
                                        <div class="stat-details">
                                            <h3><?php echo count($wishlist); ?></h3>
                                            <p>Wishlist</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="recent-orders">
                                    <div class="section-header">
                                        <h3>Recent Orders</h3>
                                        <a href="account.php?tab=orders" class="view-all">View All</a>
                                    </div>
                                    
                                    <div class="data-table">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach (array_slice($orders, 0, 3) as $order): ?>
                                                    <tr>
                                                        <td><?php echo $order['id']; ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($order['date'])); ?></td>
                                                        <td><span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></td>
                                                        <td class="order-currency" data-price="<?php echo $order['total']; ?>"></td>
                                                        <td><a href="account.php?tab=orders&order=<?php echo $order['id']; ?>" class="btn-sm">View</a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                        <?php elseif ($activeTab === 'orders'): ?>
                            <!-- Orders Tab -->
                            <div class="orders-tab">
                                <h2>My Orders</h2>
                                
                                <div class="data-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Items</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($orders) > 0): ?>
                                                <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td><?php echo $order['id']; ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($order['date'])); ?></td>
                                                        <td><?php echo $order['items']; ?></td>
                                                        <td><span class="status-badge status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span></td>
                                                        <td class="order-currency" data-price="<?php echo $order['total']; ?>"></td>
                                                        <td><a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn-sm">View</a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="no-data">You haven't placed any orders yet.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                        <?php elseif ($activeTab === 'downloads'): ?>
                            <!-- Downloads Tab -->
                            <div class="downloads-tab">
                                <h2>Downloads</h2>
                                
                                <div class="data-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Date</th>
                                                <th>Expires</th>
                                                <th>Downloads</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($downloads) > 0): ?>
                                                <?php foreach ($downloads as $download): ?>
                                                    <tr>
                                                        <td><?php echo $download['name']; ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($download['date'])); ?></td>
                                                        <td><?php echo date('M j, Y', strtotime($download['expires'])); ?></td>
                                                        <td><?php echo $download['downloads']; ?> / 5</td>
                                                        <td><a href="downloads/<?php echo $download['file']; ?>" class="btn-sm btn-download">Download</a></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="no-data">You don't have any downloadable items.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                        <?php elseif ($activeTab === 'wishlist'): ?>
                            <!-- Wishlist Tab -->
                            <div class="wishlist-tab">
                                <h2>My Wishlist</h2>
                                
                                <?php if (count($wishlist) > 0): ?>
                                    <div class="wishlist-items">
                                        <?php foreach ($wishlist as $item): ?>
                                            <div class="wishlist-item">
                                                <div class="item-image">
                                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                                </div>
                                                <div class="item-info">
                                                    <h3><a href="template-details.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                                                    <p class="item-category"><?php echo $item['category']; ?></p>
                                                    <p class="item-price" data-price="<?php echo $item['price']; ?>"></p>
                                                </div>
                                                <div class="item-actions">
                                                    <a href="cart.php?action=add&id=<?php echo $item['id']; ?>" class="btn-sm">Add to Cart</a>
                                                    <a href="wishlist.php?action=remove&id=<?php echo $item['id']; ?>" class="remove-btn" title="Remove from Wishlist">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-items">
                                        <p>Your wishlist is currently empty.</p>
                                        <a href="templates.php" class="btn">Browse Templates</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        
                        <?php elseif ($activeTab === 'profile'): ?>
                            <!-- Profile Tab -->
                            <div class="profile-tab">
                                <h2>Profile Details</h2>
                                
                                <div id="profile-error" class="alert alert-danger" style="display: none;"></div>
                                <div id="profile-success" class="alert alert-success" style="display: none;"></div>
                                
                                <form id="profile-form" class="profile-form">
                                    <div class="form-section">
                                        <h3>Personal Information</h3>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="display_name">Display Name</label>
                                                <input type="text" id="display_name" name="display_name" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="user_email">Email Address</label>
                                                <input type="email" id="user_email" name="user_email" class="form-control" disabled>
                                                <small class="form-text text-muted">Email cannot be changed</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="phone">Phone Number</label>
                                                <input type="tel" id="phone" name="phone" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-section">
                                        <h3>Address Information</h3>
                                        
                                        <div class="form-group">
                                            <label for="street">Street Address</label>
                                            <input type="text" id="street" name="street" class="form-control">
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="city">City</label>
                                                <input type="text" id="city" name="city" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="state">State/Province</label>
                                                <input type="text" id="state" name="state" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="postal_code">Postal Code</label>
                                                <input type="text" id="postal_code" name="postal_code" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="country">Country</label>
                                                <input type="text" id="country" name="country" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        
                        <?php elseif ($activeTab === 'change-password'): ?>
                            <!-- Change Password Tab -->
                            <div class="change-password-tab">
                                <h2>Change Password</h2>
                                
                                <div id="password-error" class="alert alert-danger" style="display: none;"></div>
                                <div id="password-success" class="alert alert-success" style="display: none;"></div>
                                
                                <form id="password-form" class="password-form">
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                                            <button type="button" class="password-toggle" aria-label="Toggle Password Visibility">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" id="new_password" name="new_password" class="form-control" required pattern=".{8,}" title="Password must be at least 8 characters">
                                            <button type="button" class="password-toggle" aria-label="Toggle Password Visibility">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">Password must be at least 8 characters with letters and numbers</small>
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
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading initially
    document.getElementById('auth-check').style.display = 'block';
    
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
    
    // Check if user is logged in
    firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
            // User is signed in, show account content
            document.getElementById('auth-check').style.display = 'none';
            document.getElementById('account-content').style.display = 'block';
            
            // Populate user information
            const displayName = user.displayName || 'User';
            const email = user.email;
            
            // Set user name and email
            document.getElementById('user-name').textContent = displayName;
            document.getElementById('user-email').textContent = email;
            document.getElementById('dashboard-name').textContent = displayName.split(' ')[0]; // First name only
            
            // Set user initials
            const nameParts = displayName.split(' ');
            let initials = '';
            if (nameParts.length > 0) {
                initials += nameParts[0].charAt(0).toUpperCase();
                if (nameParts.length > 1) {
                    initials += nameParts[nameParts.length - 1].charAt(0).toUpperCase();
                }
            } else {
                initials = 'U';
            }
            document.getElementById('user-initials').textContent = initials;
            
            // Format prices based on user's currency preference
            getCurrentCurrency().then(currency => {
                // Format order prices
                const orderPrices = document.querySelectorAll('.order-currency');
                orderPrices.forEach(price => {
                    const priceValue = parseFloat(price.dataset.price);
                    price.textContent = formatPrice(priceValue, currency);
                });
                
                // Format wishlist prices
                const wishlistPrices = document.querySelectorAll('.item-price');
                wishlistPrices.forEach(price => {
                    const priceValue = parseFloat(price.dataset.price);
                    price.textContent = formatPrice(priceValue, currency);
                });
            });
            
            // If on profile tab, populate profile form
            if (document.getElementById('profile-form')) {
                const profileForm = document.getElementById('profile-form');
                
                // Populate profile form
                document.getElementById('display_name').value = displayName;
                document.getElementById('user_email').value = email;
                
                // Get additional user data from Firestore
                const db = firebase.firestore();
                db.collection('users').doc(user.uid).get()
                    .then((doc) => {
                        if (doc.exists) {
                            const userData = doc.data();
                            
                            // Populate address fields if data exists
                            if (userData.phone) document.getElementById('phone').value = userData.phone;
                            if (userData.address) {
                                document.getElementById('street').value = userData.address.street || '';
                                document.getElementById('city').value = userData.address.city || '';
                                document.getElementById('state').value = userData.address.state || '';
                                document.getElementById('postal_code').value = userData.address.postalCode || '';
                                document.getElementById('country').value = userData.address.country || '';
                            }
                        }
                    })
                    .catch((error) => {
                        console.error('Error getting user data:', error);
                    });
                
                // Handle profile form submission
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const displayName = document.getElementById('display_name').value;
                    const phone = document.getElementById('phone').value;
                    const street = document.getElementById('street').value;
                    const city = document.getElementById('city').value;
                    const state = document.getElementById('state').value;
                    const postalCode = document.getElementById('postal_code').value;
                    const country = document.getElementById('country').value;
                    
                    // Clear previous messages
                    document.getElementById('profile-error').style.display = 'none';
                    document.getElementById('profile-success').style.display = 'none';
                    
                    // Update display name in Firebase Auth
                    user.updateProfile({
                        displayName: displayName
                    }).then(() => {
                        // Update additional info in Firestore
                        return db.collection('users').doc(user.uid).set({
                            displayName: displayName,
                            phone: phone,
                            address: {
                                street: street,
                                city: city,
                                state: state,
                                postalCode: postalCode,
                                country: country
                            },
                            updatedAt: firebase.firestore.FieldValue.serverTimestamp()
                        }, { merge: true });
                    }).then(() => {
                        // Success
                        document.getElementById('profile-success').textContent = 'Profile updated successfully!';
                        document.getElementById('profile-success').style.display = 'block';
                        document.getElementById('user-name').textContent = displayName;
                        document.getElementById('dashboard-name').textContent = displayName.split(' ')[0];
                        
                        // Update initials
                        const nameParts = displayName.split(' ');
                        let initials = '';
                        if (nameParts.length > 0) {
                            initials += nameParts[0].charAt(0).toUpperCase();
                            if (nameParts.length > 1) {
                                initials += nameParts[nameParts.length - 1].charAt(0).toUpperCase();
                            }
                        } else {
                            initials = 'U';
                        }
                        document.getElementById('user-initials').textContent = initials;
                    }).catch((error) => {
                        // Error
                        console.error('Error updating profile:', error);
                        document.getElementById('profile-error').textContent = 'Error updating profile: ' + error.message;
                        document.getElementById('profile-error').style.display = 'block';
                    });
                });
            }
            
            // If on change password tab, handle password form
            if (document.getElementById('password-form')) {
                const passwordForm = document.getElementById('password-form');
                
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const currentPassword = document.getElementById('current_password').value;
                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;
                    
                    // Clear previous messages
                    document.getElementById('password-error').style.display = 'none';
                    document.getElementById('password-success').style.display = 'none';
                    
                    // Validate password match
                    if (newPassword !== confirmPassword) {
                        document.getElementById('password-error').textContent = 'New passwords do not match.';
                        document.getElementById('password-error').style.display = 'block';
                        return;
                    }
                    
                    // Validate password strength
                    if (newPassword.length < 8) {
                        document.getElementById('password-error').textContent = 'New password must be at least 8 characters long.';
                        document.getElementById('password-error').style.display = 'block';
                        return;
                    }
                    
                    // Reauthenticate user and update password
                    const credential = firebase.auth.EmailAuthProvider.credential(user.email, currentPassword);
                    
                    user.reauthenticateWithCredential(credential)
                        .then(() => {
                            // User reauthenticated, update password
                            return user.updatePassword(newPassword);
                        })
                        .then(() => {
                            // Password updated successfully
                            document.getElementById('password-success').textContent = 'Password updated successfully!';
                            document.getElementById('password-success').style.display = 'block';
                            passwordForm.reset();
                        })
                        .catch((error) => {
                            // Error updating password
                            console.error('Error updating password:', error);
                            
                            let errorMessage = 'Error updating password.';
                            
                            if (error.code === 'auth/wrong-password') {
                                errorMessage = 'Current password is incorrect.';
                            } else if (error.code === 'auth/weak-password') {
                                errorMessage = 'New password is too weak. Please use a stronger password.';
                            } else {
                                errorMessage = 'Error: ' + error.message;
                            }
                            
                            document.getElementById('password-error').textContent = errorMessage;
                            document.getElementById('password-error').style.display = 'block';
                        });
                });
            }
        } else {
            // User is not signed in, redirect to login
            window.location.href = 'login.php';
        }
    });
    
    // Helper function to format price based on currency
    function formatPrice(price, currency) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(price);
    }
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 