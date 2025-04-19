<?php
// Set page title
$pageTitle = "Templates Marketplace - NeoWebX Template Store";
$pageDescription = "Browse our collection of professionally designed website templates for various industries and purposes.";
$pageKeywords = "website templates, web templates, responsive templates, HTML templates, marketplace, premium templates";

// Additional CSS
$additionalCSS = [
    "assets/css/templates-marketplace.css"
];

// Additional JS
$additionalScripts = [
    "assets/js/templates-marketplace.js"
];

// Define the path to the store root
define('STORE_PATH', dirname(__FILE__));

// Include common functions and database
require_once 'includes/functions.php';
require_once 'includes/database.php';

// Initialize database connection
$db = new Database();

// Start or resume session
if (!isset($_SESSION)) {
    session_start();
}

// Set default language and currency from session or cookies
if (!isset($_SESSION['language'])) {
    // Detect browser language or use default
    $browserLang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : '';
    
    // Check if the browser language is supported
    $langQuery = "SELECT language_code FROM languages WHERE language_code = ? AND is_active = 1";
    $langResult = $db->query($langQuery, [$browserLang], 's');
    
    if ($langResult && $langResult->num_rows > 0) {
        $_SESSION['language'] = $browserLang;
    } else {
        $_SESSION['language'] = 'en'; // Default to English
    }
}

if (!isset($_SESSION['currency'])) {
    // Default currency is USD
    $_SESSION['currency'] = 'USD';
    
    // TODO: Implement IP-based currency detection for production
}

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$features = isset($_GET['features']) ? (is_array($_GET['features']) ? $_GET['features'] : explode(',', $_GET['features'])) : [];
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'popular';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : '';

// Get all active languages
$languagesQuery = "SELECT language_code, language_name, flag_icon FROM languages WHERE is_active = 1 ORDER BY language_name";
$languagesResult = $db->query($languagesQuery);
$languages = [];

if ($languagesResult) {
    while ($row = $languagesResult->fetch_assoc()) {
        $languages[] = $row;
    }
}

// Include header
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Website Templates</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Templates</span>
        </div>
    </div>
</section>

<!-- Templates Marketplace Section -->
<section class="templates-marketplace section-padding">
    <div class="container">
        <!-- Mobile Filter Toggle -->
        <div class="mobile-filter-bar" id="mobileFilterBar">
            <button class="filter-toggle" id="filterToggle">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="sort-toggle" id="sortToggle">
                <i class="fas fa-sort"></i> Sort By
            </button>
        </div>
        
        <div class="marketplace-wrapper">
            <!-- Filters Sidebar -->
            <div class="filters-sidebar" id="filtersSidebar">
                <div class="filters-header">
                    <h3>Filters</h3>
                    <button class="close-filters" id="closeFilters"><i class="fas fa-times"></i></button>
                </div>
                
                <form action="templates-marketplace.php" method="get" id="filtersForm">
                    <!-- Search Bar -->
                    <div class="filter-section">
                        <h4>Search</h4>
                        <div class="search-box">
                            <input type="text" name="search" placeholder="Search templates..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="filter-section">
                        <h4>Categories</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="category" value="" <?php echo $category === '' ? 'checked' : ''; ?>>
                                <span>All Categories</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="real-estate" <?php echo $category === 'real-estate' ? 'checked' : ''; ?>>
                                <span>Real Estate</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="ecommerce" <?php echo $category === 'ecommerce' ? 'checked' : ''; ?>>
                                <span>E-commerce</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="school-education" <?php echo $category === 'school-education' ? 'checked' : ''; ?>>
                                <span>School & Education</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="medical-doctors" <?php echo $category === 'medical-doctors' ? 'checked' : ''; ?>>
                                <span>Medical & Doctors</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="food-beverage" <?php echo $category === 'food-beverage' ? 'checked' : ''; ?>>
                                <span>Food & Beverage</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="category" value="tours-travel" <?php echo $category === 'tours-travel' ? 'checked' : ''; ?>>
                                <span>Tours & Travel</span>
                            </label>
                        </div>
                        <button type="button" class="show-more-btn">Show more</button>
                    </div>
                    
                    <!-- Languages (NEW SECTION) -->
                    <div class="filter-section">
                        <h4>Template Languages</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="language" value="" <?php echo $language === '' ? 'checked' : ''; ?>>
                                <span>All Languages</span>
                            </label>
                            <?php foreach ($languages as $lang): ?>
                            <label class="filter-option">
                                <input type="radio" name="language" value="<?php echo $lang['language_code']; ?>" <?php echo $language === $lang['language_code'] ? 'checked' : ''; ?>>
                                <span>
                                    <?php if ($lang['flag_icon']): ?>
                                    <img src="<?php echo $lang['flag_icon']; ?>" alt="<?php echo $lang['language_name']; ?>" class="flag-icon">
                                    <?php endif; ?>
                                    <?php echo $lang['language_name']; ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="show-more-btn">Show more</button>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="filter-section">
                        <h4>Price Range</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="price_range" value="" <?php echo $priceRange === '' ? 'checked' : ''; ?>>
                                <span>All Prices</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price_range" value="0-50" <?php echo $priceRange === '0-50' ? 'checked' : ''; ?>>
                                <span><?php echo formatCurrency(0, $_SESSION['currency']); ?> - <?php echo formatCurrency(50, $_SESSION['currency']); ?></span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price_range" value="50-100" <?php echo $priceRange === '50-100' ? 'checked' : ''; ?>>
                                <span><?php echo formatCurrency(50, $_SESSION['currency']); ?> - <?php echo formatCurrency(100, $_SESSION['currency']); ?></span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price_range" value="100-200" <?php echo $priceRange === '100-200' ? 'checked' : ''; ?>>
                                <span><?php echo formatCurrency(100, $_SESSION['currency']); ?> - <?php echo formatCurrency(200, $_SESSION['currency']); ?></span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price_range" value="200+" <?php echo $priceRange === '200+' ? 'checked' : ''; ?>>
                                <span><?php echo formatCurrency(200, $_SESSION['currency']); ?>+</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Features -->
                    <div class="filter-section">
                        <h4>Features</h4>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="responsive" <?php echo in_array('responsive', $features) ? 'checked' : ''; ?>>
                                <span>Responsive</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="bootstrap" <?php echo in_array('bootstrap', $features) ? 'checked' : ''; ?>>
                                <span>Bootstrap</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="wordpress" <?php echo in_array('wordpress', $features) ? 'checked' : ''; ?>>
                                <span>WordPress</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="seo-friendly" <?php echo in_array('seo-friendly', $features) ? 'checked' : ''; ?>>
                                <span>SEO Friendly</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="mobile-friendly" <?php echo in_array('mobile-friendly', $features) ? 'checked' : ''; ?>>
                                <span>Mobile Friendly</span>
                            </label>
                            <label class="filter-option">
                                <input type="checkbox" name="features[]" value="dark-mode" <?php echo in_array('dark-mode', $features) ? 'checked' : ''; ?>>
                                <span>Dark Mode</span>
                            </label>
                        </div>
                        <button type="button" class="show-more-btn">Show more</button>
                    </div>
                    
                    <!-- Apply Filters Button - Only visible on mobile -->
                    <div class="mobile-filter-actions" id="mobileFilterActions">
                        <button type="button" class="reset-btn">Reset Filters</button>
                        <button type="submit" class="apply-btn">Apply Filters</button>
                    </div>
                </form>
            </div>
            
            <!-- Templates Grid -->
            <div class="templates-container">
                <div class="templates-header">
                    <div class="results-count">
                        <span>248 templates found</span>
                    </div>
                    <div class="sort-container">
                        <label for="sortSelect">Sort by:</label>
                        <select id="sortSelect" name="sort_by" form="filtersForm">
                            <option value="popular" <?php echo $sortBy === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                            <option value="newest" <?php echo $sortBy === 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="price_low" <?php echo $sortBy === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sortBy === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="best_rated" <?php echo $sortBy === 'best_rated' ? 'selected' : ''; ?>>Best Rated</option>
                        </select>
                    </div>
                </div>
                
                <!-- Templates Grid -->
                <div class="template-grid">
                    <?php
                    // TODO: Replace with actual database query
                    // This is a placeholder - in production this would be a DB query with all filters applied
                    $templates = [
                        [
                            'id' => 1,
                            'name' => 'Modern Real Estate',
                            'slug' => 'modern-real-estate',
                            'category' => 'real-estate',
                            'price' => 79.99,
                            'old_price' => 129.99,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'is_new' => true,
                            'is_sale' => true,
                            'supported_languages' => ['en', 'fr', 'de', 'es']
                        ],
                        [
                            'id' => 2,
                            'name' => 'E-Shop Pro',
                            'slug' => 'eshop-pro',
                            'category' => 'ecommerce',
                            'price' => 99.99,
                            'old_price' => null,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'is_trending' => true,
                            'supported_languages' => ['en', 'fr', 'es', 'zh']
                        ],
                        [
                            'id' => 3,
                            'name' => 'Medical Center',
                            'slug' => 'medical-center',
                            'category' => 'medical-doctors',
                            'price' => 69.99,
                            'old_price' => 89.99,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'supported_languages' => ['en', 'de', 'ru']
                        ],
                        [
                            'id' => 4,
                            'name' => 'Learn Online LMS',
                            'slug' => 'learn-online-lms',
                            'category' => 'school-education',
                            'price' => 149.99,
                            'old_price' => null,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'is_new' => true,
                            'supported_languages' => ['en', 'fr', 'ar', 'hi']
                        ],
                        [
                            'id' => 5,
                            'name' => 'Food Delivery App',
                            'slug' => 'food-delivery-app',
                            'category' => 'food-beverage',
                            'price' => 89.99,
                            'old_price' => 119.99,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'is_sale' => true,
                            'supported_languages' => ['en', 'zh', 'ja', 'es']
                        ],
                        [
                            'id' => 6,
                            'name' => 'Travel Explorer',
                            'slug' => 'travel-explorer',
                            'category' => 'tours-travel',
                            'price' => 59.99,
                            'old_price' => null,
                            'thumbnail' => '/placeholder.svg?height=450&width=800',
                            'is_trending' => true,
                            'supported_languages' => ['en', 'fr', 'de', 'it', 'es']
                        ]
                    ];
                    
                    // Filter templates based on selected criteria
                    $filteredTemplates = array_filter($templates, function($template) use ($category, $features, $priceRange, $language) {
                        // Filter by category
                        if (!empty($category) && $template['category'] !== $category) {
                            return false;
                        }
                        
                        // Filter by language
                        if (!empty($language) && !in_array($language, $template['supported_languages'])) {
                            return false;
                        }
                        
                        // Filter by price range
                        if (!empty($priceRange)) {
                            list($min, $max) = explode('-', $priceRange . '+');
                            $price = $template['price'];
                            
                            if ($max === '+') {
                                if ($price < (float)$min) {
                                    return false;
                                }
                            } else {
                                if ($price < (float)$min || $price > (float)$max) {
                                    return false;
                                }
                            }
                        }
                        
                        return true;
                    });
                    
                    // Sort templates
                    if (!empty($filteredTemplates)) {
                        usort($filteredTemplates, function($a, $b) use ($sortBy) {
                            switch ($sortBy) {
                                case 'price_low':
                                    return $a['price'] <=> $b['price'];
                                case 'price_high':
                                    return $b['price'] <=> $a['price'];
                                case 'newest':
                                    // In a real implementation, this would use release date
                                    return $b['id'] <=> $a['id'];
                                case 'best_rated':
                                    // In a real implementation, this would use ratings
                                    return 0; // No sorting
                                default: // popular
                                    // In a real implementation, this would use view count or downloads
                                    return 0; // No sorting
                            }
                        });
                    }
                    
                    if (empty($filteredTemplates)) {
                        echo '<div class="no-results">No templates found matching your criteria. Try broadening your filters.</div>';
                    } else {
                        // Display the templates
                        foreach ($filteredTemplates as $template):
                    ?>
                    <div class="template-card">
                        <div class="template-image">
                            <?php if (isset($template['is_new']) && $template['is_new']): ?>
                            <span class="template-badge new">New</span>
                            <?php endif; ?>
                            <?php if (isset($template['is_trending']) && $template['is_trending']): ?>
                            <span class="template-badge trending">Trending</span>
                            <?php endif; ?>
                            <?php if (isset($template['is_sale']) && $template['is_sale']): ?>
                            <span class="template-badge sale">Sale</span>
                            <?php endif; ?>
                            <img src="<?php echo $template['thumbnail']; ?>" alt="<?php echo $template['name']; ?>">
                            <div class="template-actions">
                                <a href="template-details.php?slug=<?php echo $template['slug']; ?>" class="details-btn">View Details</a>
                                <button class="add-to-cart-btn" data-template-id="<?php echo $template['id']; ?>">Add to Cart</button>
                            </div>
                        </div>
                        <div class="template-info">
                            <div class="template-title-row">
                                <h3><a href="template-details.php?slug=<?php echo $template['slug']; ?>"><?php echo $template['name']; ?></a></h3>
                                <button class="wishlist-btn" data-template-id="<?php echo $template['id']; ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                            <div class="template-category">
                                <a href="templates-marketplace.php?category=<?php echo $template['category']; ?>"><?php echo ucwords(str_replace('-', ' ', $template['category'])); ?></a>
                            </div>
                            <div class="template-price">
                                <span class="current-price"><?php echo formatCurrency($template['price'], $_SESSION['currency']); ?></span>
                                <?php if (isset($template['old_price']) && $template['old_price'] !== null): ?>
                                <span class="old-price"><?php echo formatCurrency($template['old_price'], $_SESSION['currency']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="template-languages">
                                <?php foreach($template['supported_languages'] as $lang): ?>
                                    <?php 
                                    $langName = '';
                                    foreach($languages as $l) {
                                        if ($l['language_code'] === $lang) {
                                            $langName = $l['language_name'];
                                            break;
                                        }
                                    }
                                    ?>
                                    <span class="language-tag" title="<?php echo $langName; ?>"><?php echo strtoupper($lang); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    }
                    ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <a href="#">&laquo;</a>
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <a href="#">&raquo;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Overlay Background -->
<div class="overlay-bg" id="overlayBg"></div>

<!-- See More Information Button - Only visible on small screens -->
<div class="see-more-container">
    <button id="seeMoreBtn" class="see-more-btn">
        <i class="fas fa-info-circle"></i> See More Information
    </button>
</div>

<!-- Footer container - hidden on small screens only -->
<div id="footerContainer" class="footer-container">
    <?php
    // Include footer inside a container that can be shown/hidden on mobile
    require_once 'includes/footer.php';
    ?>
</div>

<!-- Sort Overlay -->
<div class="sort-overlay" id="sortOverlay">
    <div class="sort-sheet">
        <div class="sheet-header">
            <h3>Sort By</h3>
            <button class="close-sheet" id="closeSortSheet"><i class="fas fa-times"></i></button>
        </div>
        
        <div class="sort-options">
            <label class="sort-option">
                <input type="radio" name="mobile_sort" value="popular" <?php echo $sortBy === 'popular' ? 'checked' : ''; ?>>
                <span>Most Popular</span>
            </label>
            <label class="sort-option">
                <input type="radio" name="mobile_sort" value="newest" <?php echo $sortBy === 'newest' ? 'checked' : ''; ?>>
                <span>Newest</span>
            </label>
            <label class="sort-option">
                <input type="radio" name="mobile_sort" value="price_low" <?php echo $sortBy === 'price_low' ? 'checked' : ''; ?>>
                <span>Price: Low to High</span>
            </label>
            <label class="sort-option">
                <input type="radio" name="mobile_sort" value="price_high" <?php echo $sortBy === 'price_high' ? 'checked' : ''; ?>>
                <span>Price: High to Low</span>
            </label>
            <label class="sort-option">
                <input type="radio" name="mobile_sort" value="best_rated" <?php echo $sortBy === 'best_rated' ? 'checked' : ''; ?>>
                <span>Best Rated</span>
            </label>
        </div>
        
        <div class="sheet-actions">
            <button class="apply-sort-btn" id="applySortBtn">Apply</button>
        </div>
    </div>
</div>

<script>
// Update currency and price display
document.addEventListener('DOMContentLoaded', function() {
    // Footer toggle functionality - only on mobile
    const footerContainer = document.getElementById('footerContainer');
    const seeMoreBtn = document.getElementById('seeMoreBtn');
    
    function updateFooterVisibility() {
        if (window.innerWidth <= 768) {
            seeMoreBtn.style.display = 'flex';
            footerContainer.classList.add('hidden-mobile');
        } else {
            seeMoreBtn.style.display = 'none';
            footerContainer.classList.remove('hidden-mobile');
            footerContainer.classList.remove('active-mobile');
        }
    }
    
    // Run on page load
    updateFooterVisibility();
    
    // Run when window is resized
    window.addEventListener('resize', updateFooterVisibility);
    
    // Toggle footer visibility when the button is clicked
    if (seeMoreBtn) {
        seeMoreBtn.addEventListener('click', function() {
            if (footerContainer.classList.contains('active-mobile')) {
                footerContainer.classList.remove('active-mobile');
                this.innerHTML = '<i class="fas fa-info-circle"></i> See More Information';
            } else {
                footerContainer.classList.add('active-mobile');
                this.innerHTML = '<i class="fas fa-times-circle"></i> Hide Information';
                
                // Scroll to the footer
                footerContainer.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
});
</script>

<?php
// Close database connection
$db->close();
?>
