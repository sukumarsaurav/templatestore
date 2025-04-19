<?php
// Define base path
define('STORE_PATH', true);

// Initialize session
session_start();

// Include common functions
include_once 'includes/functions.php';

// Include database connection
include_once 'includes/database.php';

// Initialize database connection
$db = new Database();

// Define additional CSS files to include
$additionalCSS = ['assets/css/template-details.css'];

// Include header
include 'includes/header.php';

// Get template details (in production, this would come from a database)
$template = [
    'id' => 1,
    'name' => 'PropertyPro',
    'category' => 'Real Estate',
    'price' => 6566,
    'sale_price' => 0,
    'description' => 'A professional template designed specifically for real estate agents and property management companies. Showcase your properties with stunning galleries and detailed information pages.',
    'features' => [
        'Fully responsive design',
        'Property listing and search functionality',
        'Virtual tour integration',
        'Contact forms and lead capture',
        'Google Maps integration',
        'SEO optimized structure'
    ],
    'image' => 'assets/images/templates/template1.jpg',
    'gallery' => [
        'assets/images/templates/template1-1.jpg',
        'assets/images/templates/template1-2.jpg',
        'assets/images/templates/template1-3.jpg',
    ],
    'demo_url' => 'demos/propertypro/',
    'release_date' => '2023-06-15',
    'last_update' => '2023-11-10',
    'version' => '1.2.0',
    'compatibility' => [
        'Bootstrap 5',
        'Chrome',
        'Firefox',
        'Safari',
        'Edge'
    ],
    'tags' => ['real estate', 'property', 'responsive', 'business'],
    'rating' => 4.8,
    'reviews' => 24
];
?>

<!-- Template Details Header -->
<section class="template-header section-padding">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <a href="templates.php">Templates</a> / <span><?php echo $template['name']; ?></span>
        </div>
        <div class="template-header-content">
            <div class="row">
                <div class="col-lg-7">
                    <div class="template-preview">
                        <img src="<?php echo $template['image']; ?>" alt="<?php echo $template['name']; ?>" class="main-preview">
                        <div class="template-gallery">
                            <?php foreach ($template['gallery'] as $image): ?>
                                <div class="gallery-item">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $template['name']; ?> preview">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="template-info-card">
                        <h1><?php echo $template['name']; ?></h1>
                        <div class="template-meta">
                            <span class="category"><?php echo $template['category']; ?></span>
                            <div class="rating">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= floor($template['rating'])): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i - 0.5 <= $template['rating']): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span><?php echo $template['rating']; ?> (<?php echo $template['reviews']; ?> reviews)</span>
                            </div>
                        </div>
                        <div class="template-price">
                            <?php if ($template['sale_price']): ?>
                                <span class="old-price"><?php echo formatCurrency($template['price'], $_SESSION['currency']); ?></span>
                                <span class="current-price"><?php echo formatCurrency($template['sale_price'], $_SESSION['currency']); ?></span>
                            <?php else: ?>
                                <span class="current-price"><?php echo formatCurrency($template['price'], $_SESSION['currency']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="template-actions">
                            <a href="cart.php?action=add&id=<?php echo $template['id']; ?>" class="btn btn-primary btn-lg">Add to Cart</a>
                            <a href="<?php echo $template['demo_url']; ?>" class="btn btn-outline btn-lg" target="_blank">Live Preview</a>
                            <button class="wishlist-btn" data-id="<?php echo $template['id']; ?>">
                                <i class="far fa-heart"></i> Add to Wishlist
                            </button>
                        </div>
                        <div class="template-details">
                            <div class="detail-item">
                                <span class="label">Last Update:</span>
                                <span class="value"><?php echo date('F j, Y', strtotime($template['last_update'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Released:</span>
                                <span class="value"><?php echo date('F j, Y', strtotime($template['release_date'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Version:</span>
                                <span class="value"><?php echo $template['version']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Tags:</span>
                                <div class="tags">
                                    <?php foreach ($template['tags'] as $tag): ?>
                                        <a href="templates.php?tag=<?php echo urlencode($tag); ?>" class="tag"><?php echo $tag; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Template Description -->
<section class="template-description section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="description-card">
                    <h2>Description</h2>
                    <div class="description-content">
                        <p><?php echo $template['description']; ?></p>
                        <p>This template is perfect for real estate agencies, property management companies, and individual agents looking to showcase their property listings online. With a clean and professional design, PropertyPro helps you present your properties in the best light.</p>
                        <p>The template comes with complete documentation and is easy to customize to match your brand needs.</p>
                    </div>
                </div>
                
                <div class="description-card">
                    <h2>Features</h2>
                    <ul class="features-list">
                        <?php foreach ($template['features'] as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="description-card">
                    <h2>Customer Reviews</h2>
                    <div class="reviews-summary">
                        <div class="average-rating">
                            <div class="rating-value"><?php echo $template['rating']; ?></div>
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= floor($template['rating'])): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif ($i - 0.5 <= $template['rating']): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="reviews-count"><?php echo $template['reviews']; ?> reviews</div>
                        </div>
                    </div>
                    
                    <div class="reviews-list">
                        <!-- Sample reviews - in production these would come from a database -->
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer">
                                    <img src="assets/images/testimonials/user1.jpg" alt="John Smith" class="reviewer-img">
                                    <div class="reviewer-info">
                                        <h4>John Smith</h4>
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-date">June 28, 2023</div>
                            </div>
                            <div class="review-content">
                                <p>This template has everything I needed for my real estate business. The property listing feature is excellent and the mobile responsiveness works perfectly. Highly recommended!</p>
                            </div>
                        </div>
                        
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer">
                                    <img src="assets/images/testimonials/user2.jpg" alt="Sarah Johnson" class="reviewer-img">
                                    <div class="reviewer-info">
                                        <h4>Sarah Johnson</h4>
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-date">July 15, 2023</div>
                            </div>
                            <div class="review-content">
                                <p>Great design and functionality. I had a few questions about customization and the support team was very helpful. Would buy from this developer again.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <h3>What's Included</h3>
                    <ul class="included-list">
                        <li><i class="fas fa-file-code"></i> HTML5, CSS3, JavaScript files</li>
                        <li><i class="fas fa-mobile-alt"></i> Responsive design</li>
                        <li><i class="fas fa-images"></i> Demo images</li>
                        <li><i class="fas fa-book"></i> Documentation</li>
                        <li><i class="fas fa-headset"></i> 6 months support</li>
                        <li><i class="fas fa-sync"></i> Future updates</li>
                    </ul>
                </div>
                
                <div class="sidebar-card">
                    <h3>Compatibility</h3>
                    <ul class="compatibility-list">
                        <?php foreach ($template['compatibility'] as $item): ?>
                            <li><i class="fas fa-check-circle"></i> <?php echo $item; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="sidebar-card">
                    <h3>Need Customization?</h3>
                    <p>We can help you customize this template to meet your specific requirements.</p>
                    <a href="services.php#customization" class="btn btn-outline btn-block">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Templates -->
<section class="related-templates section-padding">
    <div class="container">
        <div class="section-header">
            <h2>You May Also Like</h2>
        </div>
        <div class="template-grid">
            <!-- Sample related templates - in production these would be dynamically generated -->
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template2.jpg" alt="MediCare Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=2" class="template-preview-btn">View Details</a>
                        <button class="template-wishlist-btn" data-id="2">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=2">MediCare</a></h3>
                    <div class="template-category">Medical & Doctors</div>
                    <div class="template-price">
                        <span class="current-price">₹7398</span>
                    </div>
                </div>
            </div>
            
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template3.jpg" alt="EduLearn Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=3" class="template-preview-btn">View Details</a>
                        <button class="template-wishlist-btn" data-id="3">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=3">EduLearn</a></h3>
                    <div class="template-category">School & Education</div>
                    <div class="template-price">
                        <span class="current-price">₹5735</span>
                    </div>
                </div>
            </div>
            
            <div class="template-card">
                <div class="template-image">
                    <img src="assets/images/templates/template4.jpg" alt="ShopEase Template">
                    <div class="template-overlay">
                        <a href="template-details.php?id=4" class="template-preview-btn">View Details</a>
                        <button class="template-wishlist-btn" data-id="4">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="template-info">
                    <h3><a href="template-details.php?id=4">ShopEase</a></h3>
                    <div class="template-category">E-commerce</div>
                    <div class="template-price">
                        <span class="current-price">₹8229</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery image functionality
    const mainPreview = document.querySelector('.main-preview');
    const galleryItems = document.querySelectorAll('.gallery-item img');
    
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            // Update main preview with this image
            const newSrc = this.getAttribute('src');
            mainPreview.setAttribute('src', newSrc);
            
            // Add active class to this item and remove from others
            galleryItems.forEach(img => img.parentElement.classList.remove('active'));
            this.parentElement.classList.add('active');
        });
    });
    
    // Wishlist functionality
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const templateId = this.getAttribute('data-id');
            
            // Toggle wishlist icon
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.innerHTML = '<i class="fas fa-heart"></i> Added to Wishlist';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
            }
            
            // Send AJAX request to add/remove from wishlist (in a real implementation)
            fetch('wishlist.php?action=toggle&id=' + templateId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Wishlist updated:', data);
            })
            .catch(error => {
                console.error('Error updating wishlist:', error);
            });
        });
    }
    
    // Related template wishlist buttons
    const relatedWishlistBtns = document.querySelectorAll('.template-wishlist-btn');
    relatedWishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const templateId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            
            // Toggle heart icon
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
            
            // Send AJAX request (in a real implementation)
            fetch('wishlist.php?action=toggle&id=' + templateId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Wishlist updated:', data);
            })
            .catch(error => {
                console.error('Error updating wishlist:', error);
            });
        });
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 