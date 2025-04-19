<?php
// Prevent direct access unless making an API request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Define base path
define('STORE_PATH', dirname(dirname(__FILE__)));

// Include common functions and database
require_once STORE_PATH . '/includes/functions.php';
require_once STORE_PATH . '/includes/database.php';

// Initialize database
$db = new Database();

// Start or resume session
if (!isset($_SESSION)) {
    session_start();
}

// Get JSON data from request body
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Check for valid JSON
if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Get action parameter
$action = isset($data['action']) ? $data['action'] : '';

// Process based on action
switch ($action) {
    case 'add':
        addToCart($db, $data);
        break;
        
    case 'remove':
        removeFromCart($db, $data);
        break;
        
    case 'update':
        updateCartItem($db, $data);
        break;
        
    case 'get':
        getCart($db);
        break;
        
    case 'clear':
        clearCart($db);
        break;
        
    default:
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid action specified']);
        break;
}

/**
 * Add an item to the cart
 * 
 * @param Database $db Database connection
 * @param array $data Cart item data
 */
function addToCart($db, $data) {
    // Required fields
    if (!isset($data['template_id']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $templateId = (int)$data['template_id'];
    $price = (float)$data['price'];
    $licenseType = isset($data['license_type']) ? sanitizeInput($data['license_type']) : 'standard';
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    
    // Validate license type
    if (!in_array($licenseType, ['standard', 'extended'])) {
        $licenseType = 'standard';
    }
    
    // Verify template exists
    $templateQuery = "SELECT template_id, template_slug, base_price, discount_price 
                      FROM templates WHERE template_id = ? AND is_active = 1";
    $templateResult = $db->query($templateQuery, [$templateId], 'i');
    
    if (!$templateResult || $templateResult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Template not found']);
        return;
    }
    
    $template = $templateResult->fetch_assoc();
    
    // Convert price to database currency (USD)
    // In a real app, you'd need to handle currency conversion here
    $currentCurrency = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'USD';
    $dbPrice = $price; // For demo, we're assuming price is already in USD
    
    if ($userId) {
        // Logged in user - store in database
        
        // Check if user has a cart
        $cartQuery = "SELECT cart_id FROM carts WHERE user_id = ?";
        $cartResult = $db->query($cartQuery, [$userId], 'i');
        
        if ($cartResult && $cartResult->num_rows > 0) {
            // Cart exists
            $cart = $cartResult->fetch_assoc();
            $cartId = $cart['cart_id'];
        } else {
            // Create new cart
            $createCartQuery = "INSERT INTO carts (user_id, created_at) VALUES (?, NOW())";
            $db->query($createCartQuery, [$userId], 'i');
            $cartId = $db->getConnection()->insert_id;
        }
        
        // Check if item already exists in cart
        $checkItemQuery = "SELECT cart_item_id, quantity FROM cart_items 
                          WHERE cart_id = ? AND template_id = ? AND license_type = ?";
        $checkItemResult = $db->query($checkItemQuery, [$cartId, $templateId, $licenseType], 'iis');
        
        if ($checkItemResult && $checkItemResult->num_rows > 0) {
            // Item exists, update quantity
            $item = $checkItemResult->fetch_assoc();
            $newQuantity = $item['quantity'] + 1;
            
            $updateQuery = "UPDATE cart_items SET quantity = ?, price = ?, updated_at = NOW() 
                            WHERE cart_item_id = ?";
            $db->query($updateQuery, [$newQuantity, $dbPrice, $item['cart_item_id']], 'idi');
            
            echo json_encode([
                'success' => true, 
                'message' => 'Cart item quantity updated',
                'cart_item_id' => $item['cart_item_id'],
                'quantity' => $newQuantity
            ]);
        } else {
            // Add new item to cart
            $insertQuery = "INSERT INTO cart_items (cart_id, template_id, license_type, quantity, price, created_at) 
                            VALUES (?, ?, ?, 1, ?, NOW())";
            $db->query($insertQuery, [$cartId, $templateId, $licenseType, $dbPrice], 'issd');
            
            $cartItemId = $db->getConnection()->insert_id;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Item added to cart',
                'cart_item_id' => $cartItemId,
                'quantity' => 1
            ]);
        }
    } else {
        // Guest user - store in session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Generate a unique cart item ID for the session
        $cartItemId = uniqid('cart_');
        
        // Check if item already exists in session cart
        $itemExists = false;
        
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['template_id'] === $templateId && $item['license_type'] === $licenseType) {
                // Update quantity
                $_SESSION['cart'][$key]['quantity']++;
                $cartItemId = $key;
                $itemExists = true;
                break;
            }
        }
        
        if (!$itemExists) {
            // Add new item
            $_SESSION['cart'][$cartItemId] = [
                'template_id' => $templateId,
                'template_slug' => $template['template_slug'],
                'license_type' => $licenseType,
                'quantity' => 1,
                'price' => $price,
                'currency' => $currentCurrency,
                'added_at' => date('Y-m-d H:i:s')
            ];
        }
        
        echo json_encode([
            'success' => true, 
            'message' => $itemExists ? 'Cart item quantity updated' : 'Item added to cart',
            'cart_item_id' => $cartItemId,
            'quantity' => $_SESSION['cart'][$cartItemId]['quantity']
        ]);
    }
}

/**
 * Remove an item from the cart
 * 
 * @param Database $db Database connection
 * @param array $data Cart item data
 */
function removeFromCart($db, $data) {
    // Required fields
    if (!isset($data['cart_item_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $cartItemId = $data['cart_item_id'];
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    
    if ($userId) {
        // Logged in user - remove from database
        $removeQuery = "DELETE FROM cart_items WHERE cart_item_id = ? AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = ?)";
        $result = $db->query($removeQuery, [(int)$cartItemId, $userId], 'ii');
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Item removed from cart'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cart item not found']);
        }
    } else {
        // Guest user - remove from session
        if (isset($_SESSION['cart'][$cartItemId])) {
            unset($_SESSION['cart'][$cartItemId]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Item removed from cart'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cart item not found']);
        }
    }
}

/**
 * Update a cart item
 * 
 * @param Database $db Database connection
 * @param array $data Cart item data
 */
function updateCartItem($db, $data) {
    // Required fields
    if (!isset($data['cart_item_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $cartItemId = $data['cart_item_id'];
    $quantity = (int)$data['quantity'];
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    
    // Validate quantity
    if ($quantity < 1) {
        // If quantity < 1, remove the item
        $data['cart_item_id'] = $cartItemId;
        removeFromCart($db, $data);
        return;
    }
    
    if ($userId) {
        // Logged in user - update in database
        $updateQuery = "UPDATE cart_items SET quantity = ?, updated_at = NOW() 
                        WHERE cart_item_id = ? AND cart_id IN (SELECT cart_id FROM carts WHERE user_id = ?)";
        $result = $db->query($updateQuery, [$quantity, (int)$cartItemId, $userId], 'iii');
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Cart item updated',
                'quantity' => $quantity
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cart item not found']);
        }
    } else {
        // Guest user - update in session
        if (isset($_SESSION['cart'][$cartItemId])) {
            $_SESSION['cart'][$cartItemId]['quantity'] = $quantity;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Cart item updated',
                'quantity' => $quantity
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Cart item not found']);
        }
    }
}

/**
 * Get cart contents
 * 
 * @param Database $db Database connection
 */
function getCart($db) {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $currentCurrency = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'USD';
    
    if ($userId) {
        // Logged in user - get from database
        $cartQuery = "SELECT c.cart_id, ci.cart_item_id, ci.template_id, ci.license_type, 
                      ci.quantity, ci.price, t.template_slug, tt.template_name
                      FROM carts c
                      JOIN cart_items ci ON c.cart_id = ci.cart_id
                      JOIN templates t ON ci.template_id = t.template_id
                      LEFT JOIN template_translations tt ON t.template_id = tt.template_id AND tt.language_code = ?
                      WHERE c.user_id = ?
                      ORDER BY ci.created_at DESC";
        
        $cartResult = $db->query($cartQuery, [$_SESSION['language'], $userId], 'si');
        
        if ($cartResult && $cartResult->num_rows > 0) {
            $items = [];
            $totalItems = 0;
            $subtotal = 0;
            
            while ($item = $cartResult->fetch_assoc()) {
                // Get template image
                $imageQuery = "SELECT image_url FROM template_images 
                               WHERE template_id = ? AND is_main = 1 LIMIT 1";
                $imageResult = $db->query($imageQuery, [$item['template_id']], 'i');
                
                $imageUrl = '/placeholder.svg?height=450&width=800'; // Default image
                if ($imageResult && $imageResult->num_rows > 0) {
                    $image = $imageResult->fetch_assoc();
                    $imageUrl = $image['image_url'];
                }
                
                // Calculate item total
                $itemTotal = (float)$item['price'] * (int)$item['quantity'];
                $subtotal += $itemTotal;
                $totalItems += (int)$item['quantity'];
                
                // Add to items array
                $items[] = [
                    'cart_item_id' => $item['cart_item_id'],
                    'template_id' => $item['template_id'],
                    'template_slug' => $item['template_slug'],
                    'template_name' => $item['template_name'],
                    'license_type' => $item['license_type'],
                    'quantity' => (int)$item['quantity'],
                    'price' => (float)$item['price'],
                    'formatted_price' => formatCurrency((float)$item['price'], $currentCurrency),
                    'total' => $itemTotal,
                    'formatted_total' => formatCurrency($itemTotal, $currentCurrency),
                    'image_url' => $imageUrl
                ];
            }
            
            echo json_encode([
                'success' => true,
                'cart' => [
                    'items' => $items,
                    'total_items' => $totalItems,
                    'subtotal' => $subtotal,
                    'formatted_subtotal' => formatCurrency($subtotal, $currentCurrency),
                    'currency' => $currentCurrency
                ]
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'cart' => [
                    'items' => [],
                    'total_items' => 0,
                    'subtotal' => 0,
                    'formatted_subtotal' => formatCurrency(0, $currentCurrency),
                    'currency' => $currentCurrency
                ]
            ]);
        }
    } else {
        // Guest user - get from session
        $items = [];
        $totalItems = 0;
        $subtotal = 0;
        
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            foreach ($_SESSION['cart'] as $cartItemId => $item) {
                // Calculate item total
                $itemTotal = (float)$item['price'] * (int)$item['quantity'];
                $subtotal += $itemTotal;
                $totalItems += (int)$item['quantity'];
                
                // Get template details
                $templateQuery = "SELECT t.template_id, t.template_slug, tt.template_name 
                                 FROM templates t
                                 LEFT JOIN template_translations tt ON t.template_id = tt.template_id 
                                     AND tt.language_code = ?
                                 WHERE t.template_id = ?";
                $templateResult = $db->query($templateQuery, [$_SESSION['language'], $item['template_id']], 'si');
                
                $templateName = "Template #" . $item['template_id'];
                $templateSlug = $item['template_slug'] ?? 'template-' . $item['template_id'];
                
                if ($templateResult && $templateResult->num_rows > 0) {
                    $template = $templateResult->fetch_assoc();
                    $templateName = $template['template_name'];
                    $templateSlug = $template['template_slug'];
                }
                
                // Get template image
                $imageQuery = "SELECT image_url FROM template_images 
                               WHERE template_id = ? AND is_main = 1 LIMIT 1";
                $imageResult = $db->query($imageQuery, [$item['template_id']], 'i');
                
                $imageUrl = '/placeholder.svg?height=450&width=800'; // Default image
                if ($imageResult && $imageResult->num_rows > 0) {
                    $image = $imageResult->fetch_assoc();
                    $imageUrl = $image['image_url'];
                }
                
                // Add to items array
                $items[] = [
                    'cart_item_id' => $cartItemId,
                    'template_id' => $item['template_id'],
                    'template_slug' => $templateSlug,
                    'template_name' => $templateName,
                    'license_type' => $item['license_type'],
                    'quantity' => (int)$item['quantity'],
                    'price' => (float)$item['price'],
                    'formatted_price' => formatCurrency((float)$item['price'], $currentCurrency),
                    'total' => $itemTotal,
                    'formatted_total' => formatCurrency($itemTotal, $currentCurrency),
                    'image_url' => $imageUrl
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'cart' => [
                'items' => $items,
                'total_items' => $totalItems,
                'subtotal' => $subtotal,
                'formatted_subtotal' => formatCurrency($subtotal, $currentCurrency),
                'currency' => $currentCurrency
            ]
        ]);
    }
}

/**
 * Clear the entire cart
 * 
 * @param Database $db Database connection
 */
function clearCart($db) {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    
    if ($userId) {
        // Logged in user - clear from database
        $clearQuery = "DELETE FROM cart_items WHERE cart_id IN (SELECT cart_id FROM carts WHERE user_id = ?)";
        $db->query($clearQuery, [$userId], 'i');
        
        echo json_encode([
            'success' => true, 
            'message' => 'Cart cleared'
        ]);
    } else {
        // Guest user - clear from session
        $_SESSION['cart'] = [];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Cart cleared'
        ]);
    }
}

// Close database connection
$db->close();
?> 