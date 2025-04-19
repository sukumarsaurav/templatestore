<?php
// Define base path
define('STORE_PATH', true);

// Initialize session
session_start();

// Include common functions
include_once 'includes/functions.php';

// Define additional CSS files to include
$additionalCSS = ['assets/css/checkout.css'];

// Page title
$pageTitle = "Checkout";

// Check if cart is empty, redirect to cart page if it is
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php?error=empty');
    exit();
}

// Calculate order totals
$subtotal = 0;
$tax = 0;
$shipping = 0;
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Default shipping method if not set
if (!isset($_SESSION['shipping_method']) || empty($_SESSION['shipping_method'])) {
    $_SESSION['shipping_method'] = 'standard';
}

// Calculate shipping cost based on method
switch ($_SESSION['shipping_method']) {
    case 'express':
        $shipping = 14.99;
        break;
    case 'priority':
        $shipping = 9.99;
        break;
    case 'standard':
    default:
        $shipping = 4.99;
        break;
}

// Calculate tax (assuming 8.5% tax rate)
$tax = $subtotal * 0.085;

// Calculate total
$total = $subtotal + $tax + $shipping;

// Process checkout form submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Validate personal information
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    
    if (empty($firstName)) {
        $errors['first_name'] = 'First name is required';
    }
    
    if (empty($lastName)) {
        $errors['last_name'] = 'Last name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    
    // Validate billing address
    $billingAddress = sanitizeInput($_POST['billing_address'] ?? '');
    $billingCity = sanitizeInput($_POST['billing_city'] ?? '');
    $billingState = sanitizeInput($_POST['billing_state'] ?? '');
    $billingZip = sanitizeInput($_POST['billing_zip'] ?? '');
    $billingCountry = sanitizeInput($_POST['billing_country'] ?? '');
    
    if (empty($billingAddress)) {
        $errors['billing_address'] = 'Address is required';
    }
    
    if (empty($billingCity)) {
        $errors['billing_city'] = 'City is required';
    }
    
    if (empty($billingState)) {
        $errors['billing_state'] = 'State is required';
    }
    
    if (empty($billingZip)) {
        $errors['billing_zip'] = 'ZIP code is required';
    }
    
    if (empty($billingCountry)) {
        $errors['billing_country'] = 'Country is required';
    }
    
    // Check if shipping is same as billing
    $shippingSameAsBilling = isset($_POST['shipping_same_as_billing']);
    
    if (!$shippingSameAsBilling) {
        // Validate shipping address
        $shippingAddress = sanitizeInput($_POST['shipping_address'] ?? '');
        $shippingCity = sanitizeInput($_POST['shipping_city'] ?? '');
        $shippingState = sanitizeInput($_POST['shipping_state'] ?? '');
        $shippingZip = sanitizeInput($_POST['shipping_zip'] ?? '');
        $shippingCountry = sanitizeInput($_POST['shipping_country'] ?? '');
        
        if (empty($shippingAddress)) {
            $errors['shipping_address'] = 'Address is required';
        }
        
        if (empty($shippingCity)) {
            $errors['shipping_city'] = 'City is required';
        }
        
        if (empty($shippingState)) {
            $errors['shipping_state'] = 'State is required';
        }
        
        if (empty($shippingZip)) {
            $errors['shipping_zip'] = 'ZIP code is required';
        }
        
        if (empty($shippingCountry)) {
            $errors['shipping_country'] = 'Country is required';
        }
    } else {
        // Copy billing address to shipping address
        $shippingAddress = $billingAddress;
        $shippingCity = $billingCity;
        $shippingState = $billingState;
        $shippingZip = $billingZip;
        $shippingCountry = $billingCountry;
    }
    
    // Validate payment details
    $cardNumber = sanitizeInput($_POST['card_number'] ?? '');
    $cardName = sanitizeInput($_POST['card_name'] ?? '');
    $cardExpiry = sanitizeInput($_POST['card_expiry'] ?? '');
    $cardCvv = sanitizeInput($_POST['card_cvv'] ?? '');
    
    if (empty($cardNumber)) {
        $errors['card_number'] = 'Card number is required';
    } elseif (!preg_match('/^\d{16}$/', preg_replace('/\s+/', '', $cardNumber))) {
        $errors['card_number'] = 'Invalid card number';
    }
    
    if (empty($cardName)) {
        $errors['card_name'] = 'Name on card is required';
    }
    
    if (empty($cardExpiry)) {
        $errors['card_expiry'] = 'Expiry date is required';
    } elseif (!preg_match('/^\d{2}\/\d{2}$/', $cardExpiry)) {
        $errors['card_expiry'] = 'Invalid expiry date format (MM/YY)';
    }
    
    if (empty($cardCvv)) {
        $errors['card_cvv'] = 'CVV is required';
    } elseif (!preg_match('/^\d{3,4}$/', $cardCvv)) {
        $errors['card_cvv'] = 'Invalid CVV';
    }
    
    // If there are no errors, process the order
    if (empty($errors)) {
        // Generate an order ID
        $orderId = 'ORD-' . strtoupper(uniqid());
        
        // In a real application, you would save the order to the database
        // and process the payment through a payment gateway
        
        // For this example, we'll just simulate a successful order
        
        // Store order information in session for the confirmation page
        $_SESSION['order'] = [
            'id' => $orderId,
            'date' => date('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'items' => $_SESSION['cart'],
            'shipping_method' => $_SESSION['shipping_method'],
            'customer' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone
            ],
            'billing' => [
                'address' => $billingAddress,
                'city' => $billingCity,
                'state' => $billingState,
                'zip' => $billingZip,
                'country' => $billingCountry
            ],
            'shipping' => [
                'address' => $shippingAddress,
                'city' => $shippingCity,
                'state' => $shippingState,
                'zip' => $shippingZip,
                'country' => $shippingCountry
            ]
        ];
        
        // Clear the cart
        $_SESSION['cart'] = [];
        
        // Redirect to order confirmation page
        header('Location: order-confirmation.php?order=' . $orderId);
        exit();
    }
}

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <a href="cart.php">Cart</a> / <span>Checkout</span>
        </div>
    </div>
</section>

<!-- Checkout Section -->
<section class="checkout-section section-padding">
    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <p><strong>Please fix the following errors:</strong></p>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form id="checkout-form" class="checkout-form" method="post" action="checkout.php">
            <div class="checkout-container">
                <div class="checkout-left">
                    <div class="checkout-block">
                        <h2>Personal Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="required">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo $_POST['first_name'] ?? ''; ?>" required>
                                <?php if (isset($errors['first_name'])): ?>
                                    <span class="form-error"><?php echo $errors['first_name']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name <span class="required">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo $_POST['last_name'] ?? ''; ?>" required>
                                <?php if (isset($errors['last_name'])): ?>
                                    <span class="form-error"><?php echo $errors['last_name']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <span class="form-error"><?php echo $errors['email']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" value="<?php echo $_POST['phone'] ?? ''; ?>" required>
                                <?php if (isset($errors['phone'])): ?>
                                    <span class="form-error"><?php echo $errors['phone']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-block">
                        <h2>Billing Address</h2>
                        <div class="form-group">
                            <label for="billing_address">Address <span class="required">*</span></label>
                            <input type="text" id="billing_address" name="billing_address" value="<?php echo $_POST['billing_address'] ?? ''; ?>" required>
                            <?php if (isset($errors['billing_address'])): ?>
                                <span class="form-error"><?php echo $errors['billing_address']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="billing_city">City <span class="required">*</span></label>
                                <input type="text" id="billing_city" name="billing_city" value="<?php echo $_POST['billing_city'] ?? ''; ?>" required>
                                <?php if (isset($errors['billing_city'])): ?>
                                    <span class="form-error"><?php echo $errors['billing_city']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="billing_state">State <span class="required">*</span></label>
                                <input type="text" id="billing_state" name="billing_state" value="<?php echo $_POST['billing_state'] ?? ''; ?>" required>
                                <?php if (isset($errors['billing_state'])): ?>
                                    <span class="form-error"><?php echo $errors['billing_state']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="billing_zip">ZIP Code <span class="required">*</span></label>
                                <input type="text" id="billing_zip" name="billing_zip" value="<?php echo $_POST['billing_zip'] ?? ''; ?>" required>
                                <?php if (isset($errors['billing_zip'])): ?>
                                    <span class="form-error"><?php echo $errors['billing_zip']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="billing_country">Country <span class="required">*</span></label>
                                <select id="billing_country" name="billing_country" required>
                                    <option value="">Select Country</option>
                                    <option value="US" <?php echo (isset($_POST['billing_country']) && $_POST['billing_country'] === 'US') ? 'selected' : ''; ?>>United States</option>
                                    <option value="CA" <?php echo (isset($_POST['billing_country']) && $_POST['billing_country'] === 'CA') ? 'selected' : ''; ?>>Canada</option>
                                    <option value="UK" <?php echo (isset($_POST['billing_country']) && $_POST['billing_country'] === 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                                    <option value="AU" <?php echo (isset($_POST['billing_country']) && $_POST['billing_country'] === 'AU') ? 'selected' : ''; ?>>Australia</option>
                                </select>
                                <?php if (isset($errors['billing_country'])): ?>
                                    <span class="form-error"><?php echo $errors['billing_country']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-block">
                        <div class="form-checkbox">
                            <input type="checkbox" id="shipping_same_as_billing" name="shipping_same_as_billing" <?php echo (isset($_POST['shipping_same_as_billing'])) ? 'checked' : ''; ?>>
                            <label for="shipping_same_as_billing">Shipping address is the same as billing</label>
                        </div>
                        
                        <div id="shipping-address-container" class="<?php echo (isset($_POST['shipping_same_as_billing'])) ? 'hidden' : ''; ?>">
                            <h2>Shipping Address</h2>
                            <div class="form-group">
                                <label for="shipping_address">Address <span class="required">*</span></label>
                                <input type="text" id="shipping_address" name="shipping_address" value="<?php echo $_POST['shipping_address'] ?? ''; ?>">
                                <?php if (isset($errors['shipping_address'])): ?>
                                    <span class="form-error"><?php echo $errors['shipping_address']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping_city">City <span class="required">*</span></label>
                                    <input type="text" id="shipping_city" name="shipping_city" value="<?php echo $_POST['shipping_city'] ?? ''; ?>">
                                    <?php if (isset($errors['shipping_city'])): ?>
                                        <span class="form-error"><?php echo $errors['shipping_city']; ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="shipping_state">State <span class="required">*</span></label>
                                    <input type="text" id="shipping_state" name="shipping_state" value="<?php echo $_POST['shipping_state'] ?? ''; ?>">
                                    <?php if (isset($errors['shipping_state'])): ?>
                                        <span class="form-error"><?php echo $errors['shipping_state']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping_zip">ZIP Code <span class="required">*</span></label>
                                    <input type="text" id="shipping_zip" name="shipping_zip" value="<?php echo $_POST['shipping_zip'] ?? ''; ?>">
                                    <?php if (isset($errors['shipping_zip'])): ?>
                                        <span class="form-error"><?php echo $errors['shipping_zip']; ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="shipping_country">Country <span class="required">*</span></label>
                                    <select id="shipping_country" name="shipping_country">
                                        <option value="">Select Country</option>
                                        <option value="US" <?php echo (isset($_POST['shipping_country']) && $_POST['shipping_country'] === 'US') ? 'selected' : ''; ?>>United States</option>
                                        <option value="CA" <?php echo (isset($_POST['shipping_country']) && $_POST['shipping_country'] === 'CA') ? 'selected' : ''; ?>>Canada</option>
                                        <option value="UK" <?php echo (isset($_POST['shipping_country']) && $_POST['shipping_country'] === 'UK') ? 'selected' : ''; ?>>United Kingdom</option>
                                        <option value="AU" <?php echo (isset($_POST['shipping_country']) && $_POST['shipping_country'] === 'AU') ? 'selected' : ''; ?>>Australia</option>
                                    </select>
                                    <?php if (isset($errors['shipping_country'])): ?>
                                        <span class="form-error"><?php echo $errors['shipping_country']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-block">
                        <h2>Payment Information</h2>
                        <div class="payment-icons">
                            <span class="payment-icon visa">Visa</span>
                            <span class="payment-icon mastercard">MasterCard</span>
                            <span class="payment-icon amex">American Express</span>
                            <span class="payment-icon discover">Discover</span>
                        </div>
                        <div class="form-group">
                            <label for="card_name">Name on Card <span class="required">*</span></label>
                            <input type="text" id="card_name" name="card_name" value="<?php echo $_POST['card_name'] ?? ''; ?>" required>
                            <?php if (isset($errors['card_name'])): ?>
                                <span class="form-error"><?php echo $errors['card_name']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="card_number">Card Number <span class="required">*</span></label>
                            <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" value="<?php echo $_POST['card_number'] ?? ''; ?>" required>
                            <?php if (isset($errors['card_number'])): ?>
                                <span class="form-error"><?php echo $errors['card_number']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry">Expiry Date <span class="required">*</span></label>
                                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" value="<?php echo $_POST['card_expiry'] ?? ''; ?>" required>
                                <?php if (isset($errors['card_expiry'])): ?>
                                    <span class="form-error"><?php echo $errors['card_expiry']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="card_cvv">CVV <span class="required">*</span></label>
                                <input type="text" id="card_cvv" name="card_cvv" placeholder="XXX" value="<?php echo $_POST['card_cvv'] ?? ''; ?>" required>
                                <?php if (isset($errors['card_cvv'])): ?>
                                    <span class="form-error"><?php echo $errors['card_cvv']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="checkout-right">
                    <div class="checkout-block order-summary">
                        <h2>Order Summary</h2>
                        <div class="order-items">
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    </div>
                                    <div class="item-details">
                                        <h3><?php echo $item['name']; ?></h3>
                                        <p class="item-price"><?php echo formatCurrency($item['price'], $_SESSION['currency'] ?? 'USD'); ?> × <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="item-total">
                                        <?php echo formatCurrency($item['price'] * $item['quantity'], $_SESSION['currency'] ?? 'USD'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal</span>
                                <span><?php echo formatCurrency($subtotal, $_SESSION['currency'] ?? 'USD'); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Tax</span>
                                <span><?php echo formatCurrency($tax, $_SESSION['currency'] ?? 'USD'); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Shipping</span>
                                <span><?php echo formatCurrency($shipping, $_SESSION['currency'] ?? 'USD'); ?></span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total</span>
                                <span><?php echo formatCurrency($total, $_SESSION['currency'] ?? 'USD'); ?></span>
                            </div>
                        </div>
                        
                        <div class="shipping-method">
                            <h3>Shipping Method</h3>
                            <div class="shipping-options">
                                <div class="shipping-option">
                                    <input type="radio" id="shipping_standard" name="shipping_method" value="standard" <?php echo ($_SESSION['shipping_method'] === 'standard') ? 'checked' : ''; ?> disabled>
                                    <label for="shipping_standard">
                                        <span class="shipping-name">Standard Shipping</span>
                                        <span class="shipping-price"><?php echo formatCurrency(4.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                                        <span class="shipping-info">5-7 business days</span>
                                    </label>
                                </div>
                                <div class="shipping-option">
                                    <input type="radio" id="shipping_priority" name="shipping_method" value="priority" <?php echo ($_SESSION['shipping_method'] === 'priority') ? 'checked' : ''; ?> disabled>
                                    <label for="shipping_priority">
                                        <span class="shipping-name">Priority Shipping</span>
                                        <span class="shipping-price"><?php echo formatCurrency(9.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                                        <span class="shipping-info">2-3 business days</span>
                                    </label>
                                </div>
                                <div class="shipping-option">
                                    <input type="radio" id="shipping_express" name="shipping_method" value="express" <?php echo ($_SESSION['shipping_method'] === 'express') ? 'checked' : ''; ?> disabled>
                                    <label for="shipping_express">
                                        <span class="shipping-name">Express Shipping</span>
                                        <span class="shipping-price"><?php echo formatCurrency(14.99, $_SESSION['currency'] ?? 'USD'); ?></span>
                                        <span class="shipping-info">1-2 business days</span>
                                    </label>
                                </div>
                            </div>
                            <p class="shipping-note">You can change shipping method on the <a href="cart.php">Cart page</a></p>
                        </div>
                        
                        <button type="submit" name="checkout" class="btn btn-primary btn-block">Complete Order</button>
                        <p class="terms-agreement">
                            By clicking "Complete Order", you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle shipping address form based on checkbox
    const shippingSameAsBillingCheckbox = document.getElementById('shipping_same_as_billing');
    const shippingAddressContainer = document.getElementById('shipping-address-container');
    
    shippingSameAsBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            shippingAddressContainer.classList.add('hidden');
            
            // Disable shipping address fields when not needed
            const shippingFields = shippingAddressContainer.querySelectorAll('input, select');
            shippingFields.forEach(field => {
                field.disabled = true;
            });
        } else {
            shippingAddressContainer.classList.remove('hidden');
            
            // Re-enable shipping address fields when needed
            const shippingFields = shippingAddressContainer.querySelectorAll('input, select');
            shippingFields.forEach(field => {
                field.disabled = false;
            });
        }
    });
    
    // Trigger the change event to initialize the form correctly
    shippingSameAsBillingCheckbox.dispatchEvent(new Event('change'));
    
    // Format card number with spaces
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '').substring(0, 16);
        let formattedValue = '';
        
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
    
    // Format expiry date with slash
    const cardExpiryInput = document.getElementById('card_expiry');
    cardExpiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '').substring(0, 4);
        let formattedValue = '';
        
        if (value.length > 2) {
            formattedValue = value.substring(0, 2) + '/' + value.substring(2);
        } else {
            formattedValue = value;
        }
        
        e.target.value = formattedValue;
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>
</code_block_to_apply_changes_from>
</rewritten_file>