<?php
// Define base path
define('STORE_PATH', true);

// Include common functions
include_once 'includes/functions.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

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

// Get user data from session
$userName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
$userEmail = $_SESSION['email'];
$userInitials = strtoupper(substr($_SESSION['first_name'], 0, 1) . substr($_SESSION['last_name'], 0, 1));
?>

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
                            <span><?php echo $userInitials; ?></span>
                        </div>
                        <div class="user-details">
                            <h4><?php echo htmlspecialchars($userName); ?></h4>
                            <p class="user-email"><?php echo htmlspecialchars($userEmail); ?></p>
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
                                <p>Hello <strong><?php echo htmlspecialchars($_SESSION['first_name']); ?></strong>, welcome to your account dashboard!</p>
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
                                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
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
                                                    <td>$<?php echo number_format($order['total'], 2); ?></td>
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
                                                <p class="item-price">$<?php echo number_format($item['price'], 2); ?></p>
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
                                        <div class="form-group col-md-6">
                                            <label for="first_name">First Name</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="user_email">Email Address</label>
                                            <input type="email" id="user_email" name="user_email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" disabled>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Handle profile form submission
    if (document.getElementById('profile-form')) {
        const profileForm = document.getElementById('profile-form');
        
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const phone = document.getElementById('phone').value;
            const street = document.getElementById('street').value;
            const city = document.getElementById('city').value;
            const state = document.getElementById('state').value;
            const postalCode = document.getElementById('postal_code').value;
            const country = document.getElementById('country').value;
            
            // Clear previous messages
            document.getElementById('profile-error').style.display = 'none';
            document.getElementById('profile-success').style.display = 'none';
            
            // Create form data
            const formData = new FormData();
            formData.append('action', 'update_profile');
            formData.append('first_name', firstName);
            formData.append('last_name', lastName);
            formData.append('phone', phone);
            formData.append('street', street);
            formData.append('city', city);
            formData.append('state', state);
            formData.append('postal_code', postalCode);
            formData.append('country', country);
            
            // Send request
            fetch('api/profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profile-success').textContent = 'Profile updated successfully!';
                    document.getElementById('profile-success').style.display = 'block';
                    
                    // Update user info in header
                    const userNameElement = document.querySelector('.user-details h4');
                    if (userNameElement) {
                        userNameElement.textContent = firstName + ' ' + lastName;
                    }
                    
                    // Update user initials
                    const userInitialsElement = document.querySelector('.user-avatar span');
                    if (userInitialsElement) {
                        userInitialsElement.textContent = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
                    }
                } else {
                    document.getElementById('profile-error').textContent = data.message || 'Error updating profile';
                    document.getElementById('profile-error').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('profile-error').textContent = 'An error occurred. Please try again.';
                document.getElementById('profile-error').style.display = 'block';
            });
        });
    }
    
    // Handle password form submission
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
            
            // Create form data
            const formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            
            // Send request
            fetch('api/profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('password-success').textContent = 'Password updated successfully!';
                    document.getElementById('password-success').style.display = 'block';
                    passwordForm.reset();
                } else {
                    document.getElementById('password-error').textContent = data.message || 'Error updating password';
                    document.getElementById('password-error').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('password-error').textContent = 'An error occurred. Please try again.';
                document.getElementById('password-error').style.display = 'block';
            });
        });
    }
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 