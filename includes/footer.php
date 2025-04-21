<?php
// Prevent direct access
if (!defined('STORE_PATH')) {
    exit('Direct access not permitted');
}
?>
  
    
    <!-- Footer -->
    <footer class="site-footer">
     
        <!-- Main Footer Content -->
        <div class="footer-content">
            <div class="container">
                <div class="footer-widgets">
                    <!-- Company Info -->
                    <div class="footer-widget about-widget">
                        <div class="widget-title">
                            <h4>About neowebx.store</h4>
                        </div>
                        <div class="widget-content">
                            <div class="footer-logo">
                                <img src="assets/images/logo-light.svg" alt="neowebx.store - Ready-Made Website Templates">
                            </div>
                            <p>We provide high-quality, responsive website templates designed to help your business succeed online. All our templates are fully customizable and easy to use. Visit our parent company <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a> for custom web development services.</p>
                            <div class="social-links">
                                <a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                                <a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                                <a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" target="_blank" rel="noopener noreferrer"><i class="fab fa-pinterest-p"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-widget links-widget">
                        <div class="widget-title">
                            <h4>Quick Links</h4>
                        </div>
                        <div class="widget-content">
                            <ul class="footer-links">
                                <li><a href="templates-marketplace.php">Browse Templates</a></li>
                                <li><a href="services.php">Our Services</a></li>
                                <li><a href="pricing.php">Pricing</a></li>
                                <li><a href="about.php">About Us</a></li>
                                <li><a href="contact.php">Contact Us</a></li>
                                <li><a href="blog.php">Blog</a></li>
                                <li><a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="footer-widget links-widget">
                        <div class="widget-title">
                            <h4>Template Categories</h4>
                        </div>
                        <div class="widget-content">
                            <ul class="footer-links">
                                <li><a href="templates-marketplace.php?category=real-estate">Real Estate</a></li>
                                <li><a href="templates-marketplace.php?category=ecommerce">E-commerce</a></li>
                                <li><a href="templates-marketplace.php?category=education">Education</a></li>
                                <li><a href="templates-marketplace.php?category=medical-doctors">Medical & Doctors</a></li>
                                <li><a href="templates-marketplace.php?category=food-beverage">Food & Beverage</a></li>
                                <li><a href="templates-marketplace.php">View All Categories</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-widget contact-widget">
                        <div class="widget-title">
                            <h4>Contact Us</h4>
                        </div>
                        <div class="widget-content">
                            <ul class="contact-info">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>123 Template St, San Francisco, CA 94107</span>
                                </li>
                                <li>
                                    <i class="fas fa-phone-alt"></i>
                                    <span><a href="tel:+11234567890">+1 (123) 456-7890</a></span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span><a href="mailto:templates@neowebx.com">templates@neowebx.com</a></span>
                                </li>
                                <li>
                                    <i class="fas fa-globe"></i>
                                    <span><a href="https://neowebx.com" target="_blank" rel="dofollow">Visit neowebx.com</a></span>
                                </li>
                                <li>
                                    <i class="fas fa-clock"></i>
                                    <span>Monday - Friday: 9am - 6pm</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="bottom-content">
                    <div class="copyright">
                        <p>&copy; <?php echo date('Y'); ?> neowebx.store. All Rights Reserved. Part of <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a></p>
                    </div>
                    <div class="footer-bottom-links">
                        <ul>
                            <li><a href="privacy-policy.php">Privacy Policy</a></li>
                            <li><a href="terms-of-service.php">Terms of Service</a></li>
                            <li><a href="refund-policy.php">Refund Policy</a></li>
                        </ul>
                    </div>
                    <div class="payment-methods">
                        <img src="assets/images/payment-methods.png" alt="Payment Methods">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="/assets/js/script.js"></script>
    
    <!-- Additional Scripts -->
    <?php if(isset($additionalScripts)): ?>
        <?php foreach($additionalScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Initialize AOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: false
            });
            
            // Handle language and currency changes
            const handlePreferenceChange = (type, value) => {
                // Store in localStorage
                localStorage.setItem(type, value);
                
                // Get current URL
                const url = new URL(window.location.href);
                
                // Set query parameter
                url.searchParams.set(type, value);
                
                // Reload page with new parameter
                window.location.href = url.toString();
            };
            
            // Store user preferences in localStorage
            if (localStorage.getItem('language') === null) {
                localStorage.setItem('language', '<?php echo $_SESSION['language']; ?>');
            }
            
            if (localStorage.getItem('currency') === null) {
                localStorage.setItem('currency', '<?php echo $_SESSION['currency']; ?>');
            }
        });
    </script>
</body>
</html> 