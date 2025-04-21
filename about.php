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
$pageTitle = "About neowebx.store | Ready-Made Website Templates";
$pageDescription = "Learn about neowebx.store - your source for high-quality, ready-made website templates. Part of neowebx.com for complete web development solutions.";
$pageKeywords = "about, website templates, neowebx.store, ready-made templates, web development, neowebx";

// Define additional CSS files to include
$additionalCSS = ['assets/css/about.css'];

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>About Us</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>About</span>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2>Who We Are</h2>
                    <p class="lead">neowebx.store is a premier marketplace for high-quality, professionally designed website templates.</p>
                    <p>As part of <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a>, we've helped thousands of businesses establish their online presence with beautiful, responsive website templates. Our mission is to make professional web design accessible to everyone, regardless of technical skill level.</p>
                    <p>Our team consists of passionate designers, developers, and customer support specialists who are dedicated to creating exceptional templates and providing outstanding service to our customers. For custom web development services, visit our main site <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a>.</p>
                    
                    <div class="about-stats">
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Templates</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">10k+</div>
                            <div class="stat-label">Customers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">25+</div>
                            <div class="stat-label">Countries</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="assets/images/about/about-image.jpg" alt="About neowebx.store - Ready-Made Website Templates">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Our Mission</h2>
            <p>What drives us every day</p>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3>Quality Design</h3>
                    <p>We believe in creating templates that not only look beautiful but also provide an excellent user experience. Every template undergoes rigorous testing to ensure it meets our high standards.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Clean Code</h3>
                    <p>Our templates are built with clean, well-structured code that's easy to customize and maintain. We follow best practices to ensure optimal performance and compatibility.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Customer Success</h3>
                    <p>We're committed to helping our customers succeed online. From comprehensive documentation to responsive support, we're here to ensure you get the most out of your template.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section section-padding">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Team</h2>
            <p>The passionate people behind neowebx.store</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team/team1.jpg" alt="Rahul Sharma">
                    </div>
                    <div class="member-info">
                        <h3>Rahul Sharma</h3>
                        <p class="member-role">Founder & CEO</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team/team2.jpg" alt="Priya Patel">
                    </div>
                    <div class="member-info">
                        <h3>Priya Patel</h3>
                        <p class="member-role">Lead Designer</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-dribbble"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team/team3.jpg" alt="Vikram Singh">
                    </div>
                    <div class="member-info">
                        <h3>Vikram Singh</h3>
                        <p class="member-role">Lead Developer</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team-member">
                    <div class="member-image">
                        <img src="assets/images/team/team4.jpg" alt="Neha Gupta">
                    </div>
                    <div class="member-info">
                        <h3>Neha Gupta</h3>
                        <p class="member-role">Customer Support Lead</p>
                        <div class="member-social">
                            <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Hear from businesses that have used our templates</p>
        </div>
        <div class="testimonials-slider">
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
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Create Your Website?</h2>
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