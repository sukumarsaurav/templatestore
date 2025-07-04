<?php
// Prevent direct access
if (!defined('STORE_PATH')) {
    exit('Direct access not permitted');
}

// Count cart items
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Count wishlist items
$wishlistCount = isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;

// Current page
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_SESSION['language']) ? $_SESSION['language'] : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'neowebx.store - Professional Ready-Made Website Templates'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'website templates, web templates, responsive templates, ready-made templates, neowebx'; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'neowebx.store'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'neowebx.store - Professional Ready-Made Website Templates'; ?>">
    <meta property="og:url" content="https://neowebx.store/">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'neowebx.store'; ?>">
    <meta name="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'neowebx.store - Professional Ready-Made Website Templates'; ?>">
    <meta name="author" content="neowebx.com">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://neowebx.store<?php echo $_SERVER['REQUEST_URI']; ?>">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'neowebx.store | Ready-Made Website Templates'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <meta name="theme-color" content="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/hero.css">
    <link rel="stylesheet" href="/assets/css/categories.css">
    <link rel="stylesheet" href="/assets/css/featured-templates.css">
    <link rel="stylesheet" href="/assets/css/additional-services.css">
    <link rel="stylesheet" href="/assets/css/testimonials.css">
    <link rel="stylesheet" href="/assets/css/why-choose-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <!-- Additional CSS -->
    <?php if(isset($additionalCSS)): ?>
        <?php foreach($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Script for dynamic language/currency preferences -->
    <script>
    // User preferences functions
    async function getUserPreferences() {
        // Get preferences from localStorage or session
        const language = localStorage.getItem('language') || '<?php echo $_SESSION['language']; ?>';
        const currency = localStorage.getItem('currency') || '<?php echo $_SESSION['currency']; ?>';
        
        return {
            preferredLanguage: language,
            preferredCurrency: currency
        };
    }

    async function updateUserPreferences(preferences) {
        // Store in localStorage
        if (preferences.preferredLanguage) {
            localStorage.setItem('language', preferences.preferredLanguage);
        }
        if (preferences.preferredCurrency) {
            localStorage.setItem('currency', preferences.preferredCurrency);
        }
        
        // Update server-side preferences
        try {
            const response = await fetch('/api/preferences.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'update_preferences',
                    language: preferences.preferredLanguage,
                    currency: preferences.preferredCurrency
                })
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating preferences:', error);
            return { success: false, error: 'Failed to update preferences' };
        }
    }

    function updateLanguageDisplay(language) {
        const languageSelector = document.querySelector('.language-selector .dropdown-toggle span');
        if (languageSelector) {
            const languages = <?php echo json_encode($langNames); ?>;
            languageSelector.textContent = languages[language] || language;
        }
    }

    function updateCurrencyDisplay(currency) {
        const currencySelector = document.querySelector('.currency-selector .dropdown-toggle span');
        if (currencySelector) {
            const currencies = <?php echo json_encode($currencySymbols); ?>;
            const symbol = currencies[currency] || '$';
            currencySelector.textContent = `${symbol} ${currency}`;
        }
    }

    document.addEventListener('DOMContentLoaded', async function() {
        // Load user preferences
        const prefs = await getUserPreferences();
        
        // Update displayed language and currency
        updateLanguageDisplay(prefs.preferredLanguage);
        updateCurrencyDisplay(prefs.preferredCurrency);
        
        // Setup language selectors
        const languageLinks = document.querySelectorAll('.language-selector .dropdown-menu a');
        languageLinks.forEach(link => {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                const lang = this.getAttribute('href').split('=')[1];
                
                // Update preferences
                await updateUserPreferences({
                    preferredLanguage: lang
                });
                
                // Reload page
                window.location.reload();
            });
        });
        
        // Setup currency selectors
        const currencyLinks = document.querySelectorAll('.currency-selector .dropdown-menu a');
        currencyLinks.forEach(link => {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                const currency = this.getAttribute('href').split('=')[1];
                
                // Update preferences
                await updateUserPreferences({
                    preferredCurrency: currency
                });
                
                // Reload page
                window.location.reload();
            });
        });
    });
    </script>
</head>
<body>
    <!-- Pre-header with Language and Currency Options -->
    <div class="pre-header">
        <div class="container">
            <div class="pre-header-content">
                <div class="header-contact-info">
                    <a href="tel:+11234567890"><i class="fas fa-phone-alt"></i> +1 (123) 456-7890</a>
                    <a href="mailto:info@neowebx.com"><i class="fas fa-envelope"></i> info@neowebx.com</a>
                    <a href="https://neowebx.com" target="_blank" rel="dofollow"><i class="fas fa-globe"></i> Visit neowebx.com</a>
                </div>
                <div class="user-options">
                    <!-- Language Selector -->
                    <div class="dropdown language-selector">
                        <button class="dropdown-toggle">
                            <?php
                            $currentLang = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';
                            $langNames = [
                                'en' => 'English',
                                'fr' => 'Français',
                                'de' => 'Deutsch',
                                'es' => 'Español',
                                'hi' => 'हिंदी',
                                'zh' => '中文',
                                'ja' => '日本語',
                                'ar' => 'العربية',
                                'ru' => 'Русский',
                            ];
                            $currentLangName = $langNames[$currentLang] ?? 'English';
                            ?>
                            <?php if (file_exists("assets/images/flags/{$currentLang}.png")): ?>
                            <img src="assets/images/flags/<?php echo $currentLang; ?>.png" alt="<?php echo $currentLangName; ?>" class="flag-icon">
                            <?php endif; ?>
                            <span><?php echo $currentLangName; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            // Get all active languages
                            $langQuery = "SELECT language_code, language_name, flag_icon FROM languages WHERE is_active = 1 ORDER BY language_name";
                            $langResult = $db->query($langQuery);
                            
                            if ($langResult && $langResult->num_rows > 0):
                                while ($lang = $langResult->fetch_assoc()):
                                    $isActive = $currentLang === $lang['language_code'] ? 'active' : '';
                            ?>
                            <a href="?language=<?php echo $lang['language_code']; ?>" class="<?php echo $isActive; ?>">
                                <?php if ($lang['flag_icon']): ?>
                                <img src="<?php echo $lang['flag_icon']; ?>" alt="<?php echo $lang['language_name']; ?>" class="flag-icon">
                                <?php endif; ?>
                                <?php echo $lang['language_name']; ?>
                            </a>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>
                    
                    <!-- Currency Selector -->
                    <div class="dropdown currency-selector">
                        <button class="dropdown-toggle">
                            <?php
                            $currentCurrency = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'USD';
                            $currencySymbols = [
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                                'INR' => '₹',
                                'CAD' => 'C$',
                                'AUD' => 'A$',
                                'JPY' => '¥',
                                'CNY' => '¥',
                            ];
                            $currentSymbol = $currencySymbols[$currentCurrency] ?? '$';
                            ?>
                            <span><?php echo $currentSymbol; ?> <?php echo $currentCurrency; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            // Get all active currencies
                            $currQuery = "SELECT currency_code, currency_name, currency_symbol FROM currencies WHERE is_active = 1 ORDER BY currency_name";
                            $currResult = $db->query($currQuery);
                            
                            if ($currResult && $currResult->num_rows > 0):
                                while ($curr = $currResult->fetch_assoc()):
                                    $isActive = $currentCurrency === $curr['currency_code'] ? 'active' : '';
                            ?>
                            <a href="?currency=<?php echo $curr['currency_code']; ?>" class="<?php echo $isActive; ?>">
                                <?php echo $curr['currency_symbol']; ?> <?php echo $curr['currency_code']; ?> - <?php echo $curr['currency_name']; ?>
                            </a>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="index.php">
                        <img src="assets/images/logo.svg" alt="neowebx.store - Ready-Made Website Templates">
                        
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="main-nav">
                    <ul class="nav-menu">
                      <!-- Inside the main-nav section of your header, modify the Templates menu item to look like this: -->
<li class="<?php echo $currentPage === 'templates.php' ? 'active' : ''; ?>">
    <a href="templates-marketplace.php">Templates</a>
    <div class="dropdown-menu category-dropdown">
        <div class="dropdown-grid">
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=real-estate">
                    <h4>Real Estate</h4>
                    <p>Premium Real Estate website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=jobs-recruiters">
                    <h4>Jobs & Recruiters</h4>
                    <p>Premium Jobs & Recruiters website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=matrimonial">
                    <h4>Matrimonial</h4>
                    <p>Premium Matrimonial website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=medical-doctors">
                    <h4>Medical & Doctors</h4>
                    <p>Premium Medical & Doctors website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=b2b">
                    <h4>B2B</h4>
                    <p>Premium B2B website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=ecommerce">
                    <h4>E-commerce</h4>
                    <p>Premium E-commerce website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=banking-finance">
                    <h4>Banking & Finance</h4>
                    <p>Premium Banking & Finance website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=health-beauty">
                    <h4>Health & Beauty</h4>
                    <p>Premium Health & Beauty website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=food-beverage">
                    <h4>Food & Beverage</h4>
                    <p>Premium Food & Beverage website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=school-education">
                    <h4>School & Education</h4>
                    <p>Premium School & Education website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=tours-travel">
                    <h4>Tours & Travel</h4>
                    <p>Premium Tours & Travel website templates</p>
                </a>
            </div>
            <div class="dropdown-item">
                <a href="templates-marketplace.php?category=b2c">
                    <h4>B2C</h4>
                    <p>Premium B2C website templates</p>
                </a>
            </div>
        </div>
    </div>
</li>
                        <li class="<?php echo $currentPage === 'services.php' ? 'active' : ''; ?>">
                            <a href="services.php">Services</a>
                        </li>
                        <li class="<?php echo $currentPage === 'pricing.php' ? 'active' : ''; ?>">
                            <a href="pricing.php">Pricing</a>
                        </li>
                        <li class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">
                            <a href="about.php">About</a>
                        </li>
                        <li class="<?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>">
                            <a href="contact.php">Contact</a>
                        </li>
                    </ul>
                </nav>
                
                <!-- User Actions -->
                <div class="user-actions">
                    <!-- Search Button -->
                    <div class="search-icon">
                        <button id="searchToggle"><i class="fas fa-search"></i></button>
                    </div>
                    
                    <!-- Wishlist Button -->
                    <div class="wishlist-icon">
                        <a href="wishlist.php">
                            <i class="far fa-heart"></i>
                            <?php if ($wishlistCount > 0): ?>
                                <span class="count-badge"><?php echo $wishlistCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <!-- Cart Button -->
                    <div class="cart-icon">
                        <a href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="count-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <!-- Account Button with PHP Session Check -->
                    <div class="account-icon">
                        <button class="dropdown-toggle" id="accountDropdownToggle">
                            <i class="fas fa-user"></i>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="accountDropdownMenu">
                            <!-- This content will be dynamically updated by PHP session check -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <div id="userSignedIn" style="display: block;">
                                    <div class="user-welcome">Hello, <?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></div>
                                    <a href="account.php">My Account</a>
                                    <a href="account.php?tab=orders">My Orders</a>
                                    <a href="account.php?tab=downloads">Downloads</a>
                                    <a href="account.php?tab=profile">Profile</a>
                                    <a href="logout.php">Logout</a>
                                </div>
                            <?php else: ?>
                                <div id="userSignedOut">
                                    <a href="login.php">Login</a>
                                    <a href="register.php">Register</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <div class="mobile-toggle">
                        <button id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Navigation -->
    <div class="mobile-nav">
        <div class="mobile-nav-header">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/logo.svg" alt="NeoWebX Templates">
                    <span>Template Store</span>
                </a>
            </div>
            <button class="mobile-nav-close"><i class="fas fa-times"></i></button>
        </div>
        <ul class="mobile-menu">
            <li class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                <a href="index.php">Home</a>
            </li>
           <!-- Update the mobile menu part in your PHP file -->
<li class="<?php echo $currentPage === 'templates.php' ? 'active' : ''; ?>">
    <a href="javascript:void(0);" class="mobile-submenu-toggle">Templates <i class="fas fa-chevron-down"></i></a>
    <ul class="mobile-submenu">
        <li><a href="templates-marketplace.php?category=real-estate">Real Estate</a></li>
        <li><a href="templates-marketplace.php?category=jobs-recruiters">Jobs & Recruiters</a></li>
        <li><a href="templates-marketplace.php?category=matrimonial">Matrimonial</a></li>
        <li><a href="templates-marketplace.php?category=medical-doctors">Medical & Doctors</a></li>
        <li><a href="templates-marketplace.php?category=b2b">B2B</a></li>
        <li><a href="templates-marketplace.php?category=ecommerce">E-commerce</a></li>
        <li><a href="templates-marketplace.php?category=banking-finance">Banking & Finance</a></li>
        <li><a href="templates-marketplace.php?category=health-beauty">Health & Beauty</a></li>
        <li><a href="templates-marketplace.php?category=food-beverage">Food & Beverage</a></li>
        <li><a href="templates-marketplace.php?category=school-education">School & Education</a></li>
        <li><a href="templates-marketplace.php?category=tours-travel">Tours & Travel</a></li>
        <li><a href="templates-marketplace.php?category=b2c">B2C</a></li>
    </ul>
</li>
            <li class="<?php echo $currentPage === 'services.php' ? 'active' : ''; ?>">
                <a href="services.php">Services</a>
            </li>
            <li class="<?php echo $currentPage === 'pricing.php' ? 'active' : ''; ?>">
                <a href="pricing.php">Pricing</a>
            </li>
            <li class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">
                <a href="about.php">About</a>
            </li>
            <li class="<?php echo $currentPage === 'contact.php' ? 'active' : ''; ?>">
                <a href="contact.php">Contact</a>
            </li>
            <li><a href="wishlist.php">Wishlist <?php if ($wishlistCount > 0): ?>(<?php echo $wishlistCount; ?>)<?php endif; ?></a></li>
            <li><a href="cart.php">Cart <?php if ($cartCount > 0): ?>(<?php echo $cartCount; ?>)<?php endif; ?></a></li>
            <!-- Authentication links will be dynamically updated by PHP session check -->
            <li id="mobileAuthLinks">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login / Register</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>

    
    <!-- Main Content Container -->
    <main class="main-content"> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dropdowns in the pre-header
    const dropdownToggles = document.querySelectorAll('.pre-header .dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the dropdown menu
            const dropdownMenu = this.nextElementSibling;
            
            // Check if it's already open
            const isOpen = dropdownMenu.style.display === 'block';
            
            // Close all dropdowns first
            document.querySelectorAll('.pre-header .dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
            
            // Toggle this dropdown
            if (!isOpen) {
                dropdownMenu.style.display = 'block';
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.pre-header .dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
});
</script>
</body>
</html>