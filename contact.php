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
$additionalCSS = ['assets/css/contact.css'];

// Include header
include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Contact</span>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    <p>Have questions about our templates or services? We're here to help. Fill out the form or use our contact information below to reach us.</p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="method-details">
                                <h3>Our Office</h3>
                                <p>123 Template Street, Design City<br>Web State, 12345</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="method-details">
                                <h3>Phone Number</h3>
                                <p>+91 123 456 7890</p>
                                <p>Monday-Friday, 9am-6pm IST</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="method-details">
                                <h3>Email Address</h3>
                                <p>support@templatehub.com</p>
                                <p>We respond within 24 hours</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="contact-form-wrapper">
                    <h2>Send Us a Message</h2>
                    
                    <form id="contactForm" action="process-contact.php" method="post" class="contact-form">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->


<!-- FAQ Section -->
<section class="faq-section section-padding bg-light">
    <div class="container">
        <div class="section-header">
            <h2>Frequently Asked Questions</h2>
            <p>Find quick answers to common questions</p>
        </div>
        
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h3>What payment methods do you accept?</h3>
                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>We accept all major credit and debit cards, including Visa, MasterCard, and American Express. We also support payments through PayPal and UPI for Indian customers.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Do your templates include support?</h3>
                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>Yes, all of our templates come with 6 months of standard support. Support includes bug fixes, basic template usage questions, and compatibility with the latest browsers. You can also purchase extended support if needed.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Can I request a refund?</h3>
                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>We offer a 14-day money-back guarantee if the template doesn't work as described or if you encounter significant issues that we cannot resolve. Please contact our support team if you would like to request a refund.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h3>Can I use your templates for multiple websites?</h3>
                    <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                </div>
                <div class="faq-answer">
                    <p>Our standard license allows you to use a template for a single end product (website). If you need to use the template for multiple websites, you will need to purchase additional licenses or contact us for a multi-site license.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Initialize FAQ Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'includes/footer.php';
?> 