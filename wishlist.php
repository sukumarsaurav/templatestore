<?php
// Define base path
define('STORE_PATH', true);

// Initialize session
session_start();

// Include common functions
include_once 'includes/functions.php';

// Include database and initialize connection
include_once 'includes/database.php';
$db = new Database();

// Define additional CSS files to include
$additionalCSS = ['assets/css/wishlist.css'];

// Page title
$pageTitle = "My Wishlist";

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['temp_user_id'])) {
    // Create a temporary user ID for non-logged in users
    $_SESSION['temp_user_id'] = uniqid('temp_', true);
}

// Initialize wishlist if it doesn't exist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Process wishlist actions (add, remove)
if (isset($_GET['action'])) {
    $action = sanitizeInput($_GET['action']);
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    switch ($action) {
        case 'add':
            // In a real application, you would fetch the product from the database
            // For demo, we'll use hardcoded data
            if ($id > 0) {
                $template = getTemplateById($id);
                if ($template && !isset($_SESSION['wishlist'][$id])) {
                    $_SESSION['wishlist'][$id] = [
                        'id' => $template['id'],
                        'name' => $template['name'],
                        'price' => $template['price'],
                        'image' => $template['image'],
                        'category' => $template['category']
                    ];
                    
                    // Redirect to prevent form resubmission
                    header('Location: wishlist.php?action=added');
                    exit();
                }
            }
            break;
            
        case 'remove':
            if ($id > 0 && isset($_SESSION['wishlist'][$id])) {
                unset($_SESSION['wishlist'][$id]);
                
                // Redirect to prevent form resubmission
                header('Location: wishlist.php?action=removed');
                exit();
            }
            break;
            
        case 'clear':
            $_SESSION['wishlist'] = [];
            
            // Redirect to prevent form resubmission
            header('Location: wishlist.php?action=cleared');
            exit();
            break;
            
        case 'move_to_cart':
            if ($id > 0 && isset($_SESSION['wishlist'][$id])) {
                // Initialize cart if it doesn't exist
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                
                // Check if item already exists in cart
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity']++;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $_SESSION['wishlist'][$id]['id'],
                        'name' => $_SESSION['wishlist'][$id]['name'],
                        'price' => $_SESSION['wishlist'][$id]['price'],
                        'image' => $_SESSION['wishlist'][$id]['image'],
                        'quantity' => 1
                    ];
                }
                
                // Remove from wishlist
                unset($_SESSION['wishlist'][$id]);
                
                // Redirect to prevent form resubmission
                header('Location: wishlist.php?action=moved_to_cart');
                exit();
            }
            break;
    }
}

// Include header
include 'includes/header.php';

// Function to get template by id (in a real application, this would fetch from database)
function getTemplateById($id) {
    // Sample template data
    $templates = [
        1 => [
            'id' => 1,
            'name' => 'ProBusiness',
            'price' => 59.99,
            'image' => 'assets/images/templates/template1.jpg',
            'category' => 'Business'
        ],
        2 => [
            'id' => 2,
            'name' => 'CreativePortfolio',
            'price' => 49.99,
            'image' => 'assets/images/templates/template2.jpg',
            'category' => 'Portfolio'
        ],
        3 => [
            'id' => 3,
            'name' => 'EduLearn',
            'price' => 39.99,
            'image' => 'assets/images/templates/template3.jpg',
            'category' => 'Education'
        ],
        4 => [
            'id' => 4,
            'name' => 'ShopEase',
            'price' => 69.99,
            'image' => 'assets/images/templates/template4.jpg',
            'category' => 'E-commerce'
        ],
        5 => [
            'id' => 5,
            'name' => 'MediCare',
            'price' => 59.99,
            'image' => 'assets/images/templates/template5.jpg',
            'category' => 'Medical'
        ],
        6 => [
            'id' => 6,
            'name' => 'FoodieWeb',
            'price' => 49.99,
            'image' => 'assets/images/templates/template6.jpg',
            'category' => 'Food & Restaurant'
        ],
        7 => [
            'id' => 7,
            'name' => 'EstateLux',
            'price' => 59.99,
            'image' => 'assets/images/templates/template7.jpg',
            'category' => 'Real Estate'
        ],
        8 => [
            'id' => 8,
            'name' => 'TravelJoy',
            'price' => 44.99,
            'image' => 'assets/images/templates/template8.jpg',
            'category' => 'Travel'
        ]
    ];
    
    return isset($templates[$id]) ? $templates[$id] : null;
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>My Wishlist</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Wishlist</span>
        </div>
    </div>
</section>

<!-- Wishlist Section -->
<section class="wishlist-section section-padding">
    <div class="container">
        <?php if (isset($_GET['action']) && in_array($_GET['action'], ['added', 'removed', 'cleared', 'moved_to_cart'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['action']) {
                    case 'added':
                        echo 'Template has been added to your wishlist.';
                        break;
                    case 'removed':
                        echo 'Item has been removed from your wishlist.';
                        break;
                    case 'cleared':
                        echo 'Your wishlist has been cleared.';
                        break;
                    case 'moved_to_cart':
                        echo 'Item has been moved to your cart.';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="wishlist-container">
            <?php if (empty($_SESSION['wishlist'])): ?>
                <!-- Empty Wishlist -->
                <div class="empty-wishlist">
                    <div class="empty-wishlist-icon">
                        <i class="far fa-heart"></i>
                    </div>
                    <h2>Your wishlist is empty</h2>
                    <p>Save your favorite templates to come back to them later.</p>
                    <a href="templates.php" class="btn btn-primary">Browse Templates</a>
                </div>
            <?php else: ?>
                <!-- Wishlist Content -->
                <div class="wishlist-content">
                    <div class="wishlist-header">
                        <h2>Saved Templates</h2>
                        <a href="wishlist.php?action=clear" class="btn-clear" onclick="return confirm('Are you sure you want to clear your wishlist?');">Clear Wishlist</a>
                    </div>
                    
                    <div class="wishlist-grid">
                        <?php foreach ($_SESSION['wishlist'] as $id => $item): ?>
                            <div class="wishlist-item">
                                <div class="wishlist-item-image">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    <div class="item-actions">
                                        <a href="template-details.php?id=<?php echo $item['id']; ?>" class="btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="wishlist.php?action=remove&id=<?php echo $id; ?>" class="btn-remove" title="Remove from Wishlist" onclick="return confirm('Are you sure you want to remove this item?');">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="wishlist-item-info">
                                    <h3><a href="template-details.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                                    <p class="item-category"><?php echo $item['category']; ?></p>
                                    <p class="item-price"><?php echo formatCurrency($item['price'], $_SESSION['currency'] ?? 'USD'); ?></p>
                                    <div class="item-buttons">
                                        <a href="cart.php?action=add&id=<?php echo $id; ?>" class="btn-add-to-cart">Add to Cart</a>
                                        <a href="wishlist.php?action=move_to_cart&id=<?php echo $id; ?>" class="btn-move-to-cart">Move to Cart</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- You May Also Like Section -->
<section class="related-templates section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>You May Also Like</h2>
            <p>Browse these popular templates</p>
        </div>
        
        <div class="template-grid">
            <?php
            // Filter out templates that are already in the wishlist
            $availableTemplates = array_filter([1, 2, 3, 4, 5, 6, 7, 8], function($id) {
                return !isset($_SESSION['wishlist'][$id]);
            });
            
            // Shuffle and take up to 4
            shuffle($availableTemplates);
            $suggestedTemplates = array_slice($availableTemplates, 0, 4);
            
            foreach ($suggestedTemplates as $id):
                $template = getTemplateById($id);
                if ($template):
            ?>
                <div class="template-card">
                    <div class="template-image">
                        <img src="<?php echo $template['image']; ?>" alt="<?php echo $template['name']; ?> Template">
                        <div class="template-overlay">
                            <a href="template-details.php?id=<?php echo $id; ?>" class="template-preview-btn">View Details</a>
                            <a href="wishlist.php?action=add&id=<?php echo $id; ?>" class="template-wishlist-btn" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="template-info">
                        <h3><a href="template-details.php?id=<?php echo $id; ?>"><?php echo $template['name']; ?></a></h3>
                        <p class="template-category"><?php echo $template['category']; ?></p>
                        <div class="template-price">
                            <span class="current-price"><?php echo formatCurrency($template['price'], $_SESSION['currency'] ?? 'USD'); ?></span>
                        </div>
                        <div class="template-actions">
                            <a href="cart.php?action=add&id=<?php echo $id; ?>" class="add-to-cart-btn">Add to Cart</a>
                            <a href="template-details.php?id=<?php echo $id; ?>" class="details-btn">Details</a>
                        </div>
                    </div>
                </div>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Get Started?</h2>
            <p>Browse our collection of premium templates and find the perfect design for your business</p>
            <div class="cta-buttons">
                <a href="templates.php" class="btn btn-primary">View All Templates</a>
                <a href="contact.php" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 