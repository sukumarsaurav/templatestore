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

// Page metadata
$pageTitle = "neowebx.store | Ready-Made Professional Website Templates";
$pageDescription = "Browse our collection of professional, ready-made, and customizable website templates for various industries. Find the perfect template for your business needs at neowebx.store, part of neowebx.com.";
$pageKeywords = "website templates, web templates, HTML templates, responsive templates, professional templates, business templates, ready-made templates, neowebx";

// Set default currency and language if not set
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'USD';
}
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en';
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize wishlist if not set
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get featured templates from database
$featuredTemplatesQuery = "SELECT t.template_id, t.template_name, t.base_price, t.discount_price, 
                          t.thumbnail, ct.category_name, t.template_slug
                          FROM templates t
                          LEFT JOIN categories c ON t.category_id = c.category_id
                          LEFT JOIN category_translations ct ON c.category_id = ct.category_id AND ct.language_code = 'en'
                          WHERE t.is_featured = 1 AND t.is_active = 1
                          ORDER BY t.created_at DESC
                          LIMIT 4";
$featuredTemplatesResult = $db->query($featuredTemplatesQuery);

// Prepare featured templates array
$featuredTemplates = [];
while ($template = $featuredTemplatesResult->fetch_assoc()) {
    $featuredTemplates[] = [
        'id' => $template['template_id'],
        'name' => $template['template_name'],
        'category' => $template['category_name'],
        'price' => $template['base_price'],
        'sale_price' => $template['discount_price'],
        'image' => $template['thumbnail'] ?: 'assets/images/templates/placeholder.jpg',
        'slug' => $template['template_slug']
    ];
}

// If no featured templates found, use some defaults
if (empty($featuredTemplates)) {
    $featuredTemplates = [
        [
            'id' => 1,
            'name' => 'Estate Pro',
            'category' => 'Real Estate',
            'price' => 49.99,
            'sale_price' => 39.99,
            'image' => 'assets/images/templates/real-estate-1.jpg',
            'slug' => 'estate-pro'
        ],
        [
            'id' => 2,
            'name' => 'MediCare',
            'category' => 'Medical & Doctors',
            'price' => 59.99,
            'sale_price' => null,
            'image' => 'assets/images/templates/medical-1.jpg',
            'slug' => 'medicare'
        ],
        [
            'id' => 3,
            'name' => 'ShopMaster',
            'category' => 'E-commerce',
            'price' => 69.99,
            'sale_price' => 54.99,
            'image' => 'assets/images/templates/ecommerce-1.jpg',
            'slug' => 'shopmaster'
        ],
        [
            'id' => 4,
            'name' => 'EduLearn',
            'category' => 'School & Education',
            'price' => 44.99,
            'sale_price' => null,
            'image' => 'assets/images/templates/education-1.jpg',
            'slug' => 'edulearn'
        ]
    ];
}

// Get template categories from database with template counts
$categoriesQuery = "SELECT c.category_id, c.category_slug, ct.category_name,
                   (SELECT COUNT(*) FROM templates WHERE category_id = c.category_id AND is_active = 1) as template_count
                   FROM categories c
                   LEFT JOIN category_translations ct ON c.category_id = ct.category_id AND ct.language_code = 'en'
                   WHERE c.is_active = 1
                   ORDER BY c.category_order, ct.category_name";
$categoriesResult = $db->query($categoriesQuery);

// Prepare categories array
$categories = [];
while ($category = $categoriesResult->fetch_assoc()) {
    // Determine icon based on category name or slug
    $icon = 'fas fa-folder'; // Default icon
    
    // Map category names to icons
    $iconMap = [
        'real estate' => 'fas fa-home',
        'jobs' => 'fas fa-briefcase',
        'recruiters' => 'fas fa-briefcase',
        'matrimonial' => 'fas fa-heart',
        'medical' => 'fas fa-stethoscope',
        'doctors' => 'fas fa-stethoscope',
        'b2b' => 'fas fa-handshake',
        'ecommerce' => 'fas fa-shopping-cart',
        'shop' => 'fas fa-shopping-cart',
        'banking' => 'fas fa-university',
        'finance' => 'fas fa-university',
        'health' => 'fas fa-spa',
        'beauty' => 'fas fa-spa',
        'food' => 'fas fa-utensils',
        'beverage' => 'fas fa-utensils',
        'education' => 'fas fa-graduation-cap',
        'school' => 'fas fa-graduation-cap',
        'tours' => 'fas fa-plane',
        'travel' => 'fas fa-plane',
        'b2c' => 'fas fa-store'
    ];
    
    $categoryNameLower = strtolower($category['category_name']);
    foreach ($iconMap as $keyword => $iconClass) {
        if (strpos($categoryNameLower, $keyword) !== false) {
            $icon = $iconClass;
            break;
        }
    }
    
    $categories[] = [
        'id' => $category['category_slug'],
        'name' => $category['category_name'],
        'icon' => $icon,
        'count' => $category['template_count']
    ];
}

// If no categories found, use some defaults
if (empty($categories)) {
    $categories = [
        [
            'id' => 'real-estate',
            'name' => 'Real Estate',
            'icon' => 'fas fa-home',
            'count' => 12
        ],
        [
            'id' => 'jobs-recruiters',
            'name' => 'Jobs & Recruiters',
            'icon' => 'fas fa-briefcase',
            'count' => 8
        ],
        [
            'id' => 'matrimonial',
            'name' => 'Matrimonial',
            'icon' => 'fas fa-heart',
            'count' => 6
        ],
        [
            'id' => 'medical-doctors',
            'name' => 'Medical & Doctors',
            'icon' => 'fas fa-stethoscope',
            'count' => 10
        ],
        [
            'id' => 'ecommerce',
            'name' => 'E-commerce',
            'icon' => 'fas fa-shopping-cart',
            'count' => 15
        ],
        [
            'id' => 'education',
            'name' => 'School & Education',
            'icon' => 'fas fa-graduation-cap',
            'count' => 13
        ]
    ];
}

// Include header
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>Find the Perfect Ready-Made Template for Your Business</h1>
            <p class="lead">Browse our collection of professionally designed, fully responsive website templates at neowebx.store. Part of <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a></p>
            <div class="hero-search">
                <form action="templates-marketplace.php" method="get">
                    <div class="search-container">
                        <input type="text" name="search" placeholder="Search templates..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="hero-tags">
                <span>Popular:</span>
                <a href="templates-marketplace.php?tag=responsive">Responsive</a>
                <a href="templates-marketplace.php?tag=ecommerce">E-commerce</a>
                <a href="templates-marketplace.php?tag=portfolio">Portfolio</a>
                <a href="templates-marketplace.php?tag=business">Business</a>
            </div>
        </div>
    </div>
    <div class="hero-bg">
        <div class="hero-shape-1"></div>
        <div class="hero-shape-2"></div>
    </div>
</section>
<!-- Categories Section -->
<section class="categories-section section-padding bg-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Browse by Category</h2>
            <p>Find templates tailored to your specific industry</p>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
                <a href="templates-marketplace.php?category=<?php echo $category['id']; ?>" class="category-card" data-aos="fade-up">
                    <div class="category-icon">
                        <i class="<?php echo $category['icon']; ?>"></i>
                    </div>
                    <h3><?php echo $category['name']; ?></h3>
                    <span class="template-count"><?php echo $category['count']; ?> Templates</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Featured Templates -->
<section class="featured-templates section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Featured Templates</h2>
            <p>Hand-picked designs to kickstart your online presence</p>
        </div>
        <div class="template-grid">
            <?php foreach ($featuredTemplates as $template): ?>
                <div class="template-card" data-aos="fade-up">
                    <div class="template-image">
                        <img src="<?php echo $template['image']; ?>" alt="<?php echo $template['name']; ?> Template">
                        <?php if ($template['sale_price']): ?>
                            <div class="template-badge sale">Sale</div>
                        <?php endif; ?>
                    </div>
                    <div class="template-info">
                        <div class="template-title-row">
                            <h3><?php echo $template['name']; ?></h3>
                            <div class="template-price">
                                <?php if ($template['sale_price']): ?>
                                    <span class="old-price"><?php echo formatCurrency($template['price'], $_SESSION['currency']); ?></span>
                                    <span class="current-price"><?php echo formatCurrency($template['sale_price'], $_SESSION['currency']); ?></span>
                                <?php else: ?>
                                    <span class="current-price"><?php echo formatCurrency($template['price'], $_SESSION['currency']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="template-category"><?php echo $template['category']; ?></div>
                                                    <div class="template-actions">
                                <a href="template-details.php?id=<?php echo $template['id']; ?>&slug=<?php echo $template['slug'] ?? ''; ?>" class="add-to-cart-btn">View Details</a>
                                <a href="cart.php?action=add&id=<?php echo $template['id']; ?>" class="details-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 20a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"></path>
                                        <path d="M20 20a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"></path>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </a>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="view-all-btn" data-aos="fade-up">
            <a href="templates-marketplace.php" class="btn btn-primary">View All Templates</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Why Choose Our Templates?</h2>
            <p>Professional designs with powerful features to boost your online presence</p>
        </div>
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Fully Responsive</h3>
                <p>Our templates look great on all devices, from desktop to mobile phones.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Clean Code</h3>
                <p>Well-structured, commented code that's easy to customize and maintain.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Fast Loading</h3>
                <p>Optimized for speed to provide an excellent user experience.</p>
            </div>
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Easy Customization</h3>
                <p>Intuitive structure makes it simple to adapt to your brand needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section section-padding bg-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Additional Services</h2>
            <p>Take your website to the next level with our premium services</p>
        </div>
        <div class="services-grid">
            <div class="service-card" data-aos="fade-up">
                <div class="service-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Content Customization</h3>
                <p>Let our expert team customize your template content to match your brand voice and messaging.</p>
                <a href="services.php#content" class="btn-link">Learn More</a>
            </div>
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fas fa-server"></i>
                </div>
                <h3>Hosting Services</h3>
                <p>Reliable, high-performance hosting solutions to keep your website running smoothly.</p>
                <a href="services.php#hosting" class="btn-link">Learn More</a>
            </div>
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3>Custom Design</h3>
                <p>Need something unique? Our designers can create a custom template just for you.</p>
                <a href="services.php#design" class="btn-link">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials-section section-padding">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>What Our Customers Say</h2>
            <p>Hear from businesses that have used our templates</p>
        </div>
        <div class="testimonials-slider" data-aos="fade-up">
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <p>"The template I purchased was exactly what my business needed. Easy to customize and looks professional on all devices. Highly recommended!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/images/testimonials/user1.jpg" alt="John Smith">
                    </div>
                    <div class="author-info">
                        <h4>John Smith</h4>
                        <p>Real Estate Agent</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <p>"I was able to launch my online store in just a few days thanks to the e-commerce template. The support team was very helpful with my questions."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/images/testimonials/user2.jpg" alt="Sarah Johnson">
                    </div>
                    <div class="author-info">
                        <h4>Sarah Johnson</h4>
                        <p>Boutique Owner</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-item">
                <div class="testimonial-content">
                    <p>"The school template had all the features we needed for our university website. Clean design and excellent documentation made setup a breeze."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-image">
                        <img src="assets/images/testimonials/user3.jpg" alt="Michael Brown">
                    </div>
                    <div class="author-info">
                        <h4>Michael Brown</h4>
                        <p>University Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2>Ready to Get Started?</h2>
            <p>Browse our collection of premium templates and find the perfect design for your business</p>
            <div class="cta-buttons">
                <a href="templates-marketplace.php" class="btn btn-primary">View All Templates</a>
                <a href="contact.php" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'includes/footer.php';
?>
