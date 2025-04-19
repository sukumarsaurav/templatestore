<?php
// Set page title
$pageTitle = "Pricing - NeoWebX Template Store";
$pageDescription = "Explore our simple, transparent pricing plans. Choose the perfect plan for your business needs.";
$pageKeywords = "pricing, plans, subscription, website templates, pricing comparison";

// Additional CSS
$additionalCSS = [
    "assets/css/pricing.css"
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
            <h1>Simple, Transparent Pricing</h1>
            <p class="text-light">
                Choose the perfect plan for your business needs. All plans include access to our template library.
            </p>
        </div>

        <div class="pricing-tabs mb-8">
            <div class="flex justify-center">
                <div class="tabs">
                    <button class="tab active" data-tab="monthly">Monthly</button>
                    <button class="tab" data-tab="yearly">Yearly (Save 20%)</button>
                </div>
            </div>
        </div>

        <div class="tab-content" id="monthly">
            <div class="grid grid-cols-3">
                <!-- Basic Plan -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3>Basic</h3>
                        <p>Essential features for small businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$29</div>
                        <div class="price-period">/month</div>
                        <ul class="feature-list">
                            <li class="included">1 Template</li>
                            <li class="included">Basic Customization</li>
                            <li class="included">Email Support</li>
                            <li class="included">3 Months Updates</li>
                            <li class="not-included">Content Customization</li>
                            <li class="not-included">Hosting Services</li>
                            <li class="not-included">Premium Support</li>
                            <li class="not-included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-outline">Get Started</a>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div class="pricing-card featured">
                    <div class="pricing-card-header">
                        <h3>Professional</h3>
                        <p>Advanced features for growing businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$79</div>
                        <div class="price-period">/month</div>
                        <ul class="feature-list">
                            <li class="included">3 Templates</li>
                            <li class="included">Advanced Customization</li>
                            <li class="included">Priority Email Support</li>
                            <li class="included">12 Months Updates</li>
                            <li class="included">Content Customization</li>
                            <li class="included">Hosting Services</li>
                            <li class="not-included">Premium Support</li>
                            <li class="not-included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-primary">Get Started</a>
                    </div>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3>Enterprise</h3>
                        <p>Complete solution for large businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$149</div>
                        <div class="price-period">/month</div>
                        <ul class="feature-list">
                            <li class="included">Unlimited Templates</li>
                            <li class="included">Full Customization</li>
                            <li class="included">24/7 Phone Support</li>
                            <li class="included">Lifetime Updates</li>
                            <li class="included">Content Customization</li>
                            <li class="included">Hosting Services</li>
                            <li class="included">Premium Support</li>
                            <li class="included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-outline">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="yearly" style="display: none;">
            <div class="grid grid-cols-3">
                <!-- Basic Plan (Yearly) -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3>Basic</h3>
                        <p>Essential features for small businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$290</div>
                        <div class="price-period">/year</div>
                        <ul class="feature-list">
                            <li class="included">1 Template</li>
                            <li class="included">Basic Customization</li>
                            <li class="included">Email Support</li>
                            <li class="included">3 Months Updates</li>
                            <li class="not-included">Content Customization</li>
                            <li class="not-included">Hosting Services</li>
                            <li class="not-included">Premium Support</li>
                            <li class="not-included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-outline">Get Started</a>
                    </div>
                </div>

                <!-- Professional Plan (Yearly) -->
                <div class="pricing-card featured">
                    <div class="pricing-card-header">
                        <h3>Professional</h3>
                        <p>Advanced features for growing businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$790</div>
                        <div class="price-period">/year</div>
                        <ul class="feature-list">
                            <li class="included">3 Templates</li>
                            <li class="included">Advanced Customization</li>
                            <li class="included">Priority Email Support</li>
                            <li class="included">12 Months Updates</li>
                            <li class="included">Content Customization</li>
                            <li class="included">Hosting Services</li>
                            <li class="not-included">Premium Support</li>
                            <li class="not-included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-primary">Get Started</a>
                    </div>
                </div>

                <!-- Enterprise Plan (Yearly) -->
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3>Enterprise</h3>
                        <p>Complete solution for large businesses</p>
                    </div>
                    <div class="card-content">
                        <div class="price">$1490</div>
                        <div class="price-period">/year</div>
                        <ul class="feature-list">
                            <li class="included">Unlimited Templates</li>
                            <li class="included">Full Customization</li>
                            <li class="included">24/7 Phone Support</li>
                            <li class="included">Lifetime Updates</li>
                            <li class="included">Content Customization</li>
                            <li class="included">Hosting Services</li>
                            <li class="included">Premium Support</li>
                            <li class="included">Custom Development</li>
                        </ul>
                        <a href="#" class="btn btn-outline">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <section class="mt-4">
            <div class="text-center mb-4">
                <h2>Frequently Asked Questions</h2>
                <p class="text-light">
                    Find answers to common questions about our pricing and plans
                </p>
            </div>
            <div class="grid grid-cols-2">
                <div class="card">
                    <div class="card-content">
                        <h3>Can I upgrade my plan later?</h3>
                        <p class="text-light">
                            Yes, you can upgrade your plan at any time. The price difference will be prorated for the remaining
                            period.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3>Do you offer refunds?</h3>
                        <p class="text-light">
                            We offer a 14-day money-back guarantee for all plans. If you're not satisfied, please contact our
                            support team for assistance.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3>What payment methods do you accept?</h3>
                        <p class="text-light">
                            We accept all major credit cards, PayPal, and bank transfers. All payments are processed securely
                            through Razorpay.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <h3>Can I use the templates for multiple websites?</h3>
                        <p class="text-light">
                            The Basic plan allows for one website, the Professional plan for up to three websites, and the
                            Enterprise plan for unlimited websites.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enterprise Section -->
        <section class="mt-4">
            <div class="grid grid-cols-2 items-center">
                <div>
                    <h2>Need a Custom Solution?</h2>
                    <p class="text-light mb-4">
                        Our Enterprise plan offers a complete solution for large businesses with complex requirements. Contact our
                        sales team to discuss your specific needs.
                    </p>
                    <ul class="service-features">
                        <li>Custom template development</li>
                        <li>Dedicated account manager</li>
                        <li>Priority feature requests</li>
                        <li>Custom integrations</li>
                    </ul>
                    <a href="#" class="btn btn-primary">Contact Sales</a>
                </div>
                <div class="card bg-light">
                    <div class="card-content">
                        <h3>Enterprise Features</h3>
                        <ul class="service-features">
                            <li>
                                <strong>Unlimited Templates</strong><br>
                                <span class="text-light">Access to all templates in our library, including premium ones.</span>
                            </li>
                            <li>
                                <strong>Custom Development</strong><br>
                                <span class="text-light">Custom features and functionality tailored to your specific business needs.</span>
                            </li>
                            <li>
                                <strong>24/7 Support</strong><br>
                                <span class="text-light">Round-the-clock support via phone, email, and chat.</span>
                            </li>
                            <li>
                                <strong>Dedicated Account Manager</strong><br>
                                <span class="text-light">A dedicated point of contact for all your needs.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pricing tabs functionality
        const tabs = document.querySelectorAll('.pricing-tabs .tab');
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