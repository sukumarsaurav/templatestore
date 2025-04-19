<?php
// Define base path
define('STORE_PATH', true);

// Initialize session
session_start();

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/order-confirmation.css'];

// Page title
$pageTitle = "Order Confirmation";

// Check if order exists in session
if (!isset($_SESSION['order']) || empty($_SESSION['order'])) {
    // Redirect to home page if no order information
    header('Location: index.php');
    exit();
}

// Get order information
$order = $_SESSION['order'];

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Order Confirmation</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Order Confirmation</span>
        </div>
    </div>
</section>

<!-- Confirmation Section -->
<section class="confirmation-section section-padding">
    <div class="container">
        <div class="confirmation-container">
            <div class="confirmation-header">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Thank You for Your Order!</h2>
                <p>Your order has been received and is now being processed.</p>
            </div>
            
            <div class="order-info">
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Order Number</span>
                        <span class="info-value"><?php echo $order['id']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value"><?php echo date('F j, Y', strtotime($order['date'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total</span>
                        <span class="info-value"><?php echo formatCurrency($order['total'], $_SESSION['currency'] ?? 'USD'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Payment Method</span>
                        <span class="info-value">Credit Card</span>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-details">
                <div class="confirmation-block order-details">
                    <h3>Order Details</h3>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td class="product-cell">
                                        <div class="product-image">
                                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="product-name">
                                            <?php echo $item['name']; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo formatCurrency($item['price'], $_SESSION['currency'] ?? 'USD'); ?></td>
                                    <td><?php echo formatCurrency($item['price'] * $item['quantity'], $_SESSION['currency'] ?? 'USD'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="right">Subtotal</td>
                                <td><?php echo formatCurrency($order['subtotal'], $_SESSION['currency'] ?? 'USD'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="right">Tax</td>
                                <td><?php echo formatCurrency($order['tax'], $_SESSION['currency'] ?? 'USD'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="right">Shipping (<?php echo ucfirst($order['shipping_method']); ?>)</td>
                                <td><?php echo formatCurrency($order['shipping'], $_SESSION['currency'] ?? 'USD'); ?></td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3" class="right">Total</td>
                                <td><?php echo formatCurrency($order['total'], $_SESSION['currency'] ?? 'USD'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="confirmation-block customer-details">
                    <h3>Customer Information</h3>
                    <div class="customer-info">
                        <div class="customer-name">
                            <?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?>
                        </div>
                        <div class="customer-contact">
                            <p><strong>Email:</strong> <?php echo $order['customer']['email']; ?></p>
                            <p><strong>Phone:</strong> <?php echo $order['customer']['phone']; ?></p>
                        </div>
                    </div>
                    
                    <div class="addresses">
                        <div class="address-block">
                            <h4>Billing Address</h4>
                            <address>
                                <?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?><br>
                                <?php echo $order['billing']['address']; ?><br>
                                <?php echo $order['billing']['city'] . ', ' . $order['billing']['state'] . ' ' . $order['billing']['zip']; ?><br>
                                <?php 
                                    $countries = [
                                        'US' => 'United States',
                                        'CA' => 'Canada',
                                        'UK' => 'United Kingdom',
                                        'AU' => 'Australia'
                                    ];
                                    echo $countries[$order['billing']['country']] ?? $order['billing']['country']; 
                                ?>
                            </address>
                        </div>
                        
                        <div class="address-block">
                            <h4>Shipping Address</h4>
                            <address>
                                <?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?><br>
                                <?php echo $order['shipping']['address']; ?><br>
                                <?php echo $order['shipping']['city'] . ', ' . $order['shipping']['state'] . ' ' . $order['shipping']['zip']; ?><br>
                                <?php 
                                    echo $countries[$order['shipping']['country']] ?? $order['shipping']['country']; 
                                ?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-actions">
                <a href="account.php?tab=orders" class="btn btn-primary">View Your Orders</a>
                <a href="templates.php" class="btn btn-outline">Continue Shopping</a>
            </div>
            
            <div class="confirmation-notes">
                <p>
                    <i class="fas fa-envelope"></i> 
                    A confirmation email has been sent to <strong><?php echo $order['customer']['email']; ?></strong>
                </p>
                <p>
                    <i class="fas fa-question-circle"></i>
                    If you have any questions about your order, please <a href="contact.php">contact us</a>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Related Templates Section -->
<section class="related-templates section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>You May Also Like</h2>
            <p>Explore more of our premium templates</p>
        </div>
        
        <div class="template-grid">
            <?php
            // Sample template data for recommendations
            $recommendedTemplates = [
                [
                    'id' => 1,
                    'name' => 'ProBusiness',
                    'price' => 59.99,
                    'image' => 'assets/images/templates/template1.jpg',
                    'category' => 'Business'
                ],
                [
                    'id' => 4,
                    'name' => 'ShopEase',
                    'price' => 69.99,
                    'image' => 'assets/images/templates/template4.jpg',
                    'category' => 'E-commerce'
                ],
                [
                    'id' => 7,
                    'name' => 'EstateLux',
                    'price' => 59.99,
                    'image' => 'assets/images/templates/template7.jpg',
                    'category' => 'Real Estate'
                ],
                [
                    'id' => 8,
                    'name' => 'TravelJoy',
                    'price' => 44.99,
                    'image' => 'assets/images/templates/template8.jpg',
                    'category' => 'Travel'
                ]
            ];
            
            foreach ($recommendedTemplates as $template):
            ?>
                <div class="template-card">
                    <div class="template-image">
                        <img src="<?php echo $template['image']; ?>" alt="<?php echo $template['name']; ?> Template">
                        <div class="template-overlay">
                            <a href="template-details.php?id=<?php echo $template['id']; ?>" class="template-preview-btn">View Details</a>
                            <a href="wishlist.php?action=add&id=<?php echo $template['id']; ?>" class="template-wishlist-btn" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="template-info">
                        <h3><a href="template-details.php?id=<?php echo $template['id']; ?>"><?php echo $template['name']; ?></a></h3>
                        <p class="template-category"><?php echo $template['category']; ?></p>
                        <div class="template-price">
                            <span class="current-price"><?php echo formatCurrency($template['price'], $_SESSION['currency'] ?? 'USD'); ?></span>
                        </div>
                        <div class="template-actions">
                            <a href="cart.php?action=add&id=<?php echo $template['id']; ?>" class="add-to-cart-btn">Add to Cart</a>
                            <a href="template-details.php?id=<?php echo $template['id']; ?>" class="details-btn">Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Create Your Next Project?</h2>
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

// Clear the order information from session after displaying it
// This prevents the user from refreshing the page and seeing the same order
// In real applications, you would save the order in a database and fetch it by ID when needed
unset($_SESSION['order']);
?> 