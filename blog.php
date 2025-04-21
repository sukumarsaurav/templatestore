<?php
// Set page title
$pageTitle = "Blog - neowebx.store | Web Design & Development Resources";
$pageDescription = "Stay updated with the latest trends, tips, and insights in web design and development. Resources from neowebx.store, part of neowebx.com.";
$pageKeywords = "blog, web design, web development, UI/UX, e-commerce, performance, trends, templates, neowebx, ready-made templates";

// Additional CSS
$additionalCSS = [
    "assets/css/blog.css"
];

// Define the path to the store root
define('STORE_PATH', dirname(__FILE__));

// Include common functions and database
require_once 'includes/functions.php';
require_once 'includes/database.php';

// Initialize database connection
$db = new Database();

// Include header
require_once 'includes/header.php';
?>

<!-- Blog Hero Section -->
<section class="blog-hero">
    <div class="container">
        <h1>Our Blog</h1>
        <p>Stay updated with the latest trends, tips, and insights in web design and development. Visit <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a> for custom development services.</p>
    </div>
</section>

<!-- Blog Content -->
<section class="py-12">
    <div class="container">
        <!-- Featured Post -->
        <div class="featured-post">
            <img src="/placeholder.svg?height=400&width=1200" alt="Featured Post">
            <div class="featured-post-content">
                <span class="post-category">Web Design</span>
                <h2>10 Web Design Trends to Watch in 2023</h2>
                <div class="post-meta">
                    <span><i class="fas fa-user"></i> John Doe</span>
                    <span><i class="fas fa-calendar"></i> April 10, 2023</span>
                    <span><i class="fas fa-comments"></i> 24 Comments</span>
                </div>
                <a href="#" class="btn btn-primary">Read More</a>
            </div>
        </div>

        <div class="blog-grid">
            <!-- Main Content -->
            <div class="blog-main">
                <h2 class="mb-4">Latest Articles</h2>
                
                <div class="grid grid-cols-2">
                    <!-- Post 1 -->
                    <div class="post-card">
                        <img src="/placeholder.svg?height=200&width=400" alt="Post 1">
                        <div class="post-card-content">
                            <span class="post-category">Development</span>
                            <h3>How to Choose the Right Tech Stack for Your Project</h3>
                            <div class="post-meta">
                                <span><i class="fas fa-calendar"></i> April 5, 2023</span>
                                <span><i class="fas fa-comments"></i> 12 Comments</span>
                            </div>
                            <p>Selecting the right technology stack is crucial for the success of your web project. Learn how to make the best choice based on your specific needs.</p>
                            <a href="#" class="btn btn-outline">Read More</a>
                        </div>
                    </div>

                    <!-- Post 2 -->
                    <div class="post-card">
                        <img src="/placeholder.svg?height=200&width=400" alt="Post 2">
                        <div class="post-card-content">
                            <span class="post-category">UI/UX</span>
                            <h3>The Psychology of Color in Web Design</h3>
                            <div class="post-meta">
                                <span><i class="fas fa-calendar"></i> April 2, 2023</span>
                                <span><i class="fas fa-comments"></i> 8 Comments</span>
                            </div>
                            <p>Colors have a profound impact on user behavior and perception. Discover how to use color psychology to create more effective websites.</p>
                            <a href="#" class="btn btn-outline">Read More</a>
                        </div>
                    </div>

                    <!-- Post 3 -->
                    <div class="post-card">
                        <img src="/placeholder.svg?height=200&width=400" alt="Post 3">
                        <div class="post-card-content">
                            <span class="post-category">E-commerce</span>
                            <h3>Essential Features for a Successful Online Store</h3>
                            <div class="post-meta">
                                <span><i class="fas fa-calendar"></i> March 28, 2023</span>
                                <span><i class="fas fa-comments"></i> 15 Comments</span>
                            </div>
                            <p>Building an e-commerce website? Learn about the must-have features that will help you convert visitors into customers and boost sales.</p>
                            <a href="#" class="btn btn-outline">Read More</a>
                        </div>
                    </div>

                    <!-- Post 4 -->
                    <div class="post-card">
                        <img src="/placeholder.svg?height=200&width=400" alt="Post 4">
                        <div class="post-card-content">
                            <span class="post-category">Performance</span>
                            <h3>Speed Optimization Techniques for Modern Websites</h3>
                            <div class="post-meta">
                                <span><i class="fas fa-calendar"></i> March 25, 2023</span>
                                <span><i class="fas fa-comments"></i> 10 Comments</span>
                            </div>
                            <p>Website speed is critical for user experience and SEO. Explore proven techniques to make your website load faster and perform better.</p>
                            <a href="#" class="btn btn-outline">Read More</a>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#"><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="blog-sidebar">
                <!-- Search Widget -->
                <div class="sidebar-widget">
                    <h3>Search</h3>
                    <form class="search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- Categories Widget -->
                <div class="sidebar-widget">
                    <h3>Categories</h3>
                    <ul class="mega-menu-list">
                        <li><a href="#">Web Design (15)</a></li>
                        <li><a href="#">Development (23)</a></li>
                        <li><a href="#">UI/UX (8)</a></li>
                        <li><a href="#">E-commerce (12)</a></li>
                        <li><a href="#">Performance (7)</a></li>
                        <li><a href="#">SEO (9)</a></li>
                    </ul>
                </div>

                <!-- Recent Posts Widget -->
                <div class="sidebar-widget">
                    <h3>Recent Posts</h3>
                    <div class="recent-post">
                        <img src="/placeholder.svg?height=70&width=70" alt="Recent Post 1">
                        <div class="recent-post-content">
                            <h4><a href="#">The Rise of AI in Web Development</a></h4>
                            <span class="post-date">April 8, 2023</span>
                        </div>
                    </div>
                    <div class="recent-post">
                        <img src="/placeholder.svg?height=70&width=70" alt="Recent Post 2">
                        <div class="recent-post-content">
                            <h4><a href="#">5 Ways to Improve Your Website's Accessibility</a></h4>
                            <span class="post-date">April 6, 2023</span>
                        </div>
                    </div>
                    <div class="recent-post">
                        <img src="/placeholder.svg?height=70&width=70" alt="Recent Post 3">
                        <div class="recent-post-content">
                            <h4><a href="#">The Complete Guide to Responsive Design</a></h4>
                            <span class="post-date">April 3, 2023</span>
                        </div>
                    </div>
                </div>

                <!-- Tags Widget -->
                <div class="sidebar-widget">
                    <h3>Tags</h3>
                    <div class="tag-cloud">
                        <a href="#" class="tag">Web Design</a>
                        <a href="#" class="tag">Development</a>
                        <a href="#" class="tag">UI/UX</a>
                        <a href="#" class="tag">Responsive</a>
                        <a href="#" class="tag">E-commerce</a>
                        <a href="#" class="tag">WordPress</a>
                        <a href="#" class="tag">Performance</a>
                        <a href="#" class="tag">SEO</a>
                        <a href="#" class="tag">Mobile</a>
                        <a href="#" class="tag">Accessibility</a>
                    </div>
                </div>

                <!-- Newsletter Widget -->
                <div class="sidebar-widget">
                    <h3>Newsletter</h3>
                    <p>Subscribe to our newsletter to get the latest updates directly in your inbox.</p>
                    <form>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 