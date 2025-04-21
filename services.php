<?php
// Set page title
$pageTitle = "Services - neowebx.store | Ready-Made Website Templates";
$pageDescription = "Enhance your website with our professional services at neowebx.store. We offer a range of services to help you get the most out of your ready-made website template.";
$pageKeywords = "website services, template customization, hosting services, technical support, custom development, web maintenance, neowebx, ready-made templates";

// Additional CSS
$additionalCSS = [
    "assets/css/services.css"
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

<main>
    <div class="container py-12">
        <div class="mb-8 text-center">
            <h1>Our Services</h1>
            <p class="text-light">
                Enhance your website with our professional services. We offer a range of services to help you get the most out
                of your website template. For custom web development services, visit <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a>.
            </p>
        </div>

        <div class="service-tabs mb-8">
            <div class="flex justify-center">
                <div class="tabs">
                    <button class="tab active" data-tab="all">All Services</button>
                    <button class="tab" data-tab="customization">Customization</button>
                    <button class="tab" data-tab="hosting">Hosting</button>
                    <button class="tab" data-tab="support">Support</button>
                </div>
            </div>
        </div>

        <div class="tab-content" id="all">
            <div class="grid grid-cols-4">
                <!-- Content Customization -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h3 class="service-title">Content Customization</h3>
                        <p class="service-description text-light">
                            We'll update your website's content to match your specific requirements and branding.
                        </p>
                        <ul class="service-features">
                            <li>Custom text and images</li>
                            <li>Brand color implementation</li>
                            <li>Logo integration</li>
                            <li>Content organization</li>
                            <li>SEO optimization</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$99</span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <!-- Hosting Services -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <h3 class="service-title">Hosting Services</h3>
                        <p class="service-description text-light">
                            Reliable and fast hosting for your website with 99.9% uptime guarantee.
                        </p>
                        <ul class="service-features">
                            <li>High-performance servers</li>
                            <li>Free SSL certificate</li>
                            <li>Daily backups</li>
                            <li>CDN integration</li>
                            <li>24/7 monitoring</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$49<span class="price-period">/year</span></span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <!-- Technical Support -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="service-title">Technical Support</h3>
                        <p class="service-description text-light">
                            Get expert help with setting up and maintaining your website.
                        </p>
                        <ul class="service-features">
                            <li>24/7 customer support</li>
                            <li>Setup assistance</li>
                            <li>Regular maintenance</li>
                            <li>Security updates</li>
                            <li>Performance optimization</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$79<span class="price-period">/year</span></span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <!-- Custom Development -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="service-title">Custom Development</h3>
                        <p class="service-description text-light">
                            Custom features and functionality tailored to your specific business needs. For more extensive development, visit <a href="https://neowebx.com" target="_blank" rel="dofollow">neowebx.com</a>.
                        </p>
                        <ul class="service-features">
                            <li>Custom functionality</li>
                            <li>Third-party integrations</li>
                            <li>Payment gateway setup</li>
                            <li>Custom forms and workflows</li>
                            <li>API development</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$199</span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="customization" style="display: none;">
            <div class="grid grid-cols-2">
                <!-- Content Customization -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h3 class="service-title">Content Customization</h3>
                        <p class="service-description text-light">
                            We'll update your website's content to match your specific requirements and branding.
                        </p>
                        <ul class="service-features">
                            <li>Custom text and images</li>
                            <li>Brand color implementation</li>
                            <li>Logo integration</li>
                            <li>Content organization</li>
                            <li>SEO optimization</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$99</span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>

                <!-- Custom Development -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="service-title">Custom Development</h3>
                        <p class="service-description text-light">
                            Custom features and functionality tailored to your specific business needs.
                        </p>
                        <ul class="service-features">
                            <li>Custom functionality</li>
                            <li>Third-party integrations</li>
                            <li>Payment gateway setup</li>
                            <li>Custom forms and workflows</li>
                            <li>API development</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$199</span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="hosting" style="display: none;">
            <div class="grid grid-cols-1" style="max-width: 500px; margin: 0 auto;">
                <!-- Hosting Services -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <h3 class="service-title">Hosting Services</h3>
                        <p class="service-description text-light">
                            Reliable and fast hosting for your website with 99.9% uptime guarantee.
                        </p>
                        <ul class="service-features">
                            <li>High-performance servers</li>
                            <li>Free SSL certificate</li>
                            <li>Daily backups</li>
                            <li>CDN integration</li>
                            <li>24/7 monitoring</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$49<span class="price-period">/year</span></span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="support" style="display: none;">
            <div class="grid grid-cols-1" style="max-width: 500px; margin: 0 auto;">
                <!-- Technical Support -->
                <div class="service-card card">
                    <div class="card-content">
                        <div class="service-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="service-title">Technical Support</h3>
                        <p class="service-description text-light">
                            Get expert help with setting up and maintaining your website.
                        </p>
                        <ul class="service-features">
                            <li>24/7 customer support</li>
                            <li>Setup assistance</li>
                            <li>Regular maintenance</li>
                            <li>Security updates</li>
                            <li>Performance optimization</li>
                        </ul>
                        <div class="flex items-center justify-between">
                            <span class="price">$79<span class="price-period">/year</span></span>
                            <a href="#" class="btn btn-primary">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <section class="mt-4">
            <div class="mb-8 text-center">
                <h2>How It Works</h2>
                <p class="text-light">
                    Our simple process to help you get started with our services
                </p>
            </div>
            <div class="grid grid-cols-3">
                <div class="text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white text-xl font-bold mx-auto">
                        1
                    </div>
                    <h3 class="mt-4">Choose a Service</h3>
                    <p class="text-light">Browse our services and select the one that best fits your needs.</p>
                </div>
                <div class="text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white text-xl font-bold mx-auto">
                        2
                    </div>
                    <h3 class="mt-4">Place Your Order</h3>
                    <p class="text-light">Complete the checkout process and provide the necessary details.</p>
                </div>
                <div class="text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white text-xl font-bold mx-auto">
                        3
                    </div>
                    <h3 class="mt-4">Receive Your Service</h3>
                    <p class="text-light">Our team will deliver the service according to the agreed timeline.</p>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="mt-4 bg-light py-12 rounded">
            <div class="mb-8 text-center">
                <h2>What Our Clients Say</h2>
                <p class="text-light">
                    Hear from businesses that have used our services
                </p>
            </div>
            <div class="grid grid-cols-3">
                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold">Sarah Johnson</h4>
                                <p class="text-sm text-light">Real Estate Agent</p>
                            </div>
                        </div>
                        <p class="text-light">
                            "The content customization service was exactly what I needed. The team understood my requirements
                            perfectly and delivered a website that truly represents my brand."
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold">Dr. Michael Chen</h4>
                                <p class="text-sm text-light">Medical Practitioner</p>
                            </div>
                        </div>
                        <p class="text-light">
                            "The hosting service has been reliable and fast. My website loads quickly, and I've had no downtime. The
                            technical support team is also very responsive."
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold">Emily Rodriguez</h4>
                                <p class="text-sm text-light">E-commerce Store Owner</p>
                            </div>
                        </div>
                        <p class="text-light">
                            "The custom development service helped me add unique features to my online store. The team was
                            professional and delivered the project on time and within budget."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="mt-4 bg-primary text-white py-12 rounded">
            <div class="text-center">
                <h2>Ready to Get Started?</h2>
                <p class="mb-4">
                    Choose a service and take your website to the next level. Our team is ready to help you achieve your goals.
                </p>
                <div class="flex justify-center">
                    <a href="#" class="btn btn-secondary mr-4">Browse Services</a>
                    <a href="contact.php" class="btn btn-outline-light">Contact Us</a>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Service tabs functionality
        const tabs = document.querySelectorAll('.service-tabs .tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.style.display = 'none';
                });
                
                // Show the selected tab content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).style.display = 'block';
            });
        });
    });
</script>

<?php
// Include footer
require_once 'includes/footer.php';
?> 