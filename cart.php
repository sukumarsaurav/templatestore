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
$additionalCSS = ['assets/css/cart.css'];

// Page title
$pageTitle = "Shopping Cart";

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Process cart actions (add, remove, update)
if (isset($_GET['action'])) {
    $action = sanitizeInput($_GET['action']);
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    switch ($action) {
        case 'add':
            // In a real application, you would fetch the product from the database
            // For demo, we'll use hardcoded data
            if ($id > 0) {
                $template = getTemplateById($id);
                if ($template) {
                    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                    
                    // Check if item already exists in cart
                    if (isset($_SESSION['cart'][$id])) {
                        $_SESSION['cart'][$id]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$id] = [
                            'id' => $template['id'],
                            'name' => $template['name'],
                            'price' => $template['price'],
                            'image' => $template['image'],
                            'quantity' => $quantity
                        ];
                    }
                    
                    // Redirect to prevent form resubmission
                    header('Location: cart.php?action=added');
                    exit();
                }
            }
            break;
            
        case 'update':
            if ($id > 0 && isset($_SESSION['cart'][$id])) {
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                
                if ($quantity > 0) {
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                } else {
                    unset($_SESSION['cart'][$id]);
                }
                
                // Redirect to prevent form resubmission
                header('Location: cart.php?action=updated');
                exit();
            }
            break;
            
        case 'remove':
            if ($id > 0 && isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                
                // Redirect to prevent form resubmission
                header('Location: cart.php?action=removed');
                exit();
            }
            break;
            
        case 'clear':
            $_SESSION['cart'] = [];
            
            // Redirect to prevent form resubmission
            header('Location: cart.php?action=cleared');
            exit();
            break;
    }
}

// Calculate cart totals
$cartTotal = 0;
$cartItemCount = 0;

foreach ($_SESSION['cart'] as $item) {
    $cartTotal += $item['price'] * $item['quantity'];
    $cartItemCount += $item['quantity'];
}

// Sample shipping options
$shippingOptions = [
    ['id' => 'free', 'name' => 'Standard Download', 'price' => 0],
    ['id' => 'extended', 'name' => 'Extended Support (6 months)', 'price' => 19.99],
    ['id' => 'premium', 'name' => 'Premium Support (12 months)', 'price' => 39.99]
];

// Get selected shipping method
$selectedShipping = isset($_GET['shipping']) ? sanitizeInput($_GET['shipping']) : 'free';
$shippingCost = 0;

foreach ($shippingOptions as $option) {
    if ($option['id'] === $selectedShipping) {
        $shippingCost = $option['price'];
        break;
    }
}

// Calculate order total
$orderTotal = $cartTotal + $shippingCost;

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
            'image' => 'assets/images/templates/template1.jpg'
        ],
        2 => [
            'id' => 2,
            'name' => 'CreativePortfolio',
            'price' => 49.99,
            'image' => 'assets/images/templates/template2.jpg'
        ],
        3 => [
            'id' => 3,
            'name' => 'EduLearn',
            'price' => 39.99,
            'image' => 'assets/images/templates/template3.jpg'
        ],
        4 => [
            'id' => 4,
            'name' => 'ShopEase',
            'price' => 69.99,
            'image' => 'assets/images/templates/template4.jpg'
        ],
        5 => [
            'id' => 5,
            'name' => 'MediCare',
            'price' => 59.99,
            'image' => 'assets/images/templates/template5.jpg'
        ],
        6 => [
            'id' => 6,
            'name' => 'FoodieWeb',
            'price' => 49.99,
            'image' => 'assets/images/templates/template6.jpg'
        ],
        7 => [
            'id' => 7,
            'name' => 'EstateLux',
            'price' => 59.99,
            'image' => 'assets/images/templates/template7.jpg'
        ],
        8 => [
            'id' => 8,
            'name' => 'TravelJoy',
            'price' => 44.99,
            'image' => 'assets/images/templates/template8.jpg'
        ]
    ];
    
    return isset($templates[$id]) ? $templates[$id] : null;
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Cart</span>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="cart-section section-padding">
    <div class="container">
        <?php if (isset($_GET['action']) && in_array($_GET['action'], ['added', 'updated', 'removed', 'cleared'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['action']) {
                    case 'added':
                        echo 'Template has been added to your cart.';
                        break;
                    case 'updated':
                        echo 'Cart has been updated.';
                        break;
                    case 'removed':
                        echo 'Item has been removed from your cart.';
                        break;
                    case 'cleared':
                        echo 'Your cart has been cleared.';
                        break;
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="cart-container">
            <?php if (empty($_SESSION['cart'])): ?>
                <!-- Empty Cart -->
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any templates to your cart yet.</p>
                    <a href="templates.php" class="btn btn-primary">Browse Templates</a>
                </div>
            <?php else: ?>
                <!-- Cart Content -->
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-header">
                            <h2>Cart Items (<?php echo $cartItemCount; ?>)</h2>
                            <a href="cart.php?action=clear" class="btn-clear" onclick="return confirm('Are you sure you want to clear your cart?');">Clear Cart</a>
                        </div>
                        
                        <div class="cart-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="item-col">Item</th>
                                        <th class="price-col">Price</th>
                                        <th class="quantity-col">Quantity</th>
                                        <th class="total-col">Total</th>
                                        <th class="action-col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                        <tr>
                                            <td class="item-col">
                                                <div class="cart-item">
                                                    <div class="item-image">
                                                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                                    </div>
                                                    <div class="item-details">
                                                        <h3><a href="template-details.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h3>
                                                        <p class="item-type">Website Template</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price-col">
                                                <?php echo formatCurrency($item['price'], $_SESSION['currency'] ?? 'USD'); ?>
                                            </td>
                                            <td class="quantity-col">
                                                <form action="cart.php?action=update&id=<?php echo $id; ?>" method="post" class="quantity-form">
                                                    <div class="quantity-input">
                                                        <button type="button" class="quantity-btn quantity-btn-minus" data-id="<?php echo $id; ?>" aria-label="Decrease quantity">-</button>
                                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity" data-id="<?php echo $id; ?>">
                                                        <button type="button" class="quantity-btn quantity-btn-plus" data-id="<?php echo $id; ?>" aria-label="Increase quantity">+</button>
                                                    </div>
                                                    <button type="submit" class="update-btn">Update</button>
                                                </form>
                                            </td>
                                            <td class="total-col">
                                                <?php echo formatCurrency($item['price'] * $item['quantity'], $_SESSION['currency'] ?? 'USD'); ?>
                                            </td>
                                            <td class="action-col">
                                                <a href="cart.php?action=remove&id=<?php echo $id; ?>" class="remove-btn" title="Remove item" onclick="return confirm('Are you sure you want to remove this item?');">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        
                        <div class="summary-item">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value"><?php echo formatCurrency($cartTotal, $_SESSION['currency'] ?? 'USD'); ?></span>
                        </div>
                        
                        <div class="summary-item shipping-section">
                            <span class="summary-label">Support Options:</span>
                            <div class="shipping-options">
                                <?php foreach ($shippingOptions as $option): ?>
                                    <div class="shipping-option">
                                        <input type="radio" id="shipping-<?php echo $option['id']; ?>" name="shipping" 
                                               value="<?php echo $option['id']; ?>" 
                                               <?php echo $selectedShipping === $option['id'] ? 'checked' : ''; ?>
                                               data-price="<?php echo $option['price']; ?>"
                                               onchange="updateShipping('<?php echo $option['id']; ?>')">
                                        <label for="shipping-<?php echo $option['id']; ?>">
                                            <span class="shipping-name"><?php echo $option['name']; ?></span>
                                            <span class="shipping-price"><?php echo formatCurrency($option['price'], $_SESSION['currency'] ?? 'USD'); ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="summary-item coupon-section">
                            <div class="coupon-form">
                                <input type="text" placeholder="Enter coupon code" class="coupon-input">
                                <button type="button" class="coupon-btn">Apply</button>
                            </div>
                        </div>
                        
                        <div class="summary-total">
                            <span class="total-label">Total:</span>
                            <span class="total-value" id="cart-total"><?php echo formatCurrency($orderTotal, $_SESSION['currency'] ?? 'USD'); ?></span>
                        </div>
                        
                        <div class="checkout-actions">
                            <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                            <a href="templates.php" class="btn btn-outline btn-block">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Related Templates Section -->
<section class="related-templates section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>You May Also Like</h2>
            <p>Browse these popular templates</p>
        </div>
        
        <div class="template-grid">
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template5.jpg" alt="MediCare Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=5" class="template-preview-btn">View Details</a>
                        <a href="wishlist.php?action=add&id=5" class="template-wishlist-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </a>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=5">MediCare</a></h3>
                    <p class="template-category">Medical</p>
                    <div class="template-price">
                        <span class="current-price"><?php echo formatCurrency(59.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                    </div>
                    <div class="template-actions">
                        <a href="cart.php?action=add&id=5" class="add-to-cart-btn">Add to Cart</a>
                        <a href="template-details.php?id=5" class="details-btn">Details</a>
                    </div>
                </div>
            </div>
            
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template6.jpg" alt="FoodieWeb Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=6" class="template-preview-btn">View Details</a>
                        <a href="wishlist.php?action=add&id=6" class="template-wishlist-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </a>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=6">FoodieWeb</a></h3>
                    <p class="template-category">Food & Restaurant</p>
                    <div class="template-price">
                        <span class="current-price"><?php echo formatCurrency(49.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                    </div>
                    <div class="template-actions">
                        <a href="cart.php?action=add&id=6" class="add-to-cart-btn">Add to Cart</a>
                        <a href="template-details.php?id=6" class="details-btn">Details</a>
                    </div>
                </div>
            </div>
            
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template7.jpg" alt="EstateLux Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=7" class="template-preview-btn">View Details</a>
                        <a href="wishlist.php?action=add&id=7" class="template-wishlist-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </a>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=7">EstateLux</a></h3>
                    <p class="template-category">Real Estate</p>
                    <div class="template-price">
                        <span class="current-price"><?php echo formatCurrency(59.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                    </div>
                    <div class="template-actions">
                        <a href="cart.php?action=add&id=7" class="add-to-cart-btn">Add to Cart</a>
                        <a href="template-details.php?id=7" class="details-btn">Details</a>
                    </div>
                </div>
            </div>
            
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template8.jpg" alt="TravelJoy Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=8" class="template-preview-btn">View Details</a>
                        <a href="wishlist.php?action=add&id=8" class="template-wishlist-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </a>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=8">TravelJoy</a></h3>
                    <p class="template-category">Travel</p>
                    <div class="template-price">
                        <span class="current-price"><?php echo formatCurrency(44.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                    </div>
                    <div class="template-actions">
                        <a href="cart.php?action=add&id=8" class="add-to-cart-btn">Add to Cart</a>
                        <a href="template-details.php?id=8" class="details-btn">Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cart Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons functionality
    const minusButtons = document.querySelectorAll('.quantity-btn-minus');
    const plusButtons = document.querySelectorAll('.quantity-btn-plus');
    const quantityInputs = document.querySelectorAll('.quantity');
    
    minusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`.quantity[data-id="${id}"]`);
            let value = parseInt(input.value);
            
            if (value > 1) {
                value--;
                input.value = value;
                updateQuantity(id, value);
            }
        });
    });
    
    plusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`.quantity[data-id="${id}"]`);
            let value = parseInt(input.value);
            
            value++;
            input.value = value;
            updateQuantity(id, value);
        });
    });
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            let value = parseInt(this.value);
            
            if (value < 1 || isNaN(value)) {
                value = 1;
                this.value = value;
            }
            
            updateQuantity(id, value);
        });
    });
    
    // Function to update quantity (submits the form)
    function updateQuantity(id, value) {
        const form = document.querySelector(`.quantity-form input[data-id="${id}"]`).closest('form');
        form.querySelector('input[name="quantity"]').value = value;
        // Uncomment the line below to auto-submit when changing quantity
        // form.submit();
    }
});

// Function to update shipping method
function updateShipping(shippingMethod) {
    window.location.href = 'cart.php?shipping=' + shippingMethod;
}
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 