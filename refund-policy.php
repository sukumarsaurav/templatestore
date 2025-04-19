<?php
// Set page title
$pageTitle = "Refund Policy - NeoWebX Template Store";
$pageDescription = "Our refund policy outlines when and how you can request a refund for purchased templates.";
$pageKeywords = "refund policy, money back, returns, digital products, template refund";

// Additional CSS
$additionalCSS = [
    "assets/css/policy.css"
];

// Define the path to the store root
define('STORE_PATH', dirname(__FILE__));

// Include header
require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>Refund Policy</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Refund Policy</span>
        </div>
    </div>
</section>

<!-- Refund Policy Content -->
<section class="policy-content section-padding">
    <div class="container">
        <div class="policy-wrapper">
            <div class="last-updated">
                <p>Last Updated: June 15, 2023</p>
            </div>
            
            <div class="policy-section">
                <h2>Introduction</h2>
                <p>At NeoWebX Template Store, we want you to be completely satisfied with your purchase. This Refund Policy outlines our procedures and guidelines regarding refunds for templates and other digital products purchased from our website.</p>
                <p>We encourage you to review this policy carefully before making a purchase, as it forms part of the terms and conditions governing your use of our services.</p>
            </div>
            
            <div class="policy-section">
                <h2>Digital Nature of Products</h2>
                <p>Please note that we sell digital products that are delivered electronically. Once you have access to download a digital product, it cannot be returned in the traditional sense. Due to the nature of digital products, we generally do not offer refunds simply because you have changed your mind or made an incorrect purchase.</p>
            </div>
            
            <div class="policy-section">
                <h2>Refund Eligibility</h2>
                <p>We may issue refunds in the following circumstances:</p>
                
                <h3>Technical Issues</h3>
                <p>If you experience significant technical issues with a template that:</p>
                <ul>
                    <li>Prevent the template from functioning as described in its documentation</li>
                    <li>Cannot be resolved by our support team within a reasonable timeframe</li>
                    <li>Are not caused by third-party software, plugins, or modifications you have made</li>
                </ul>
                
                <h3>Duplicate Purchases</h3>
                <p>If you accidentally purchased the same template twice within a short period, and you have not downloaded both copies, we may refund the duplicate purchase.</p>
                
                <h3>Non-Delivery</h3>
                <p>If you did not receive the download link or cannot access your purchase despite our verification that payment was completed successfully.</p>
            </div>
            
            <div class="policy-section">
                <h2>Refund Ineligibility</h2>
                <p>Refunds are not available in the following situations:</p>
                <ul>
                    <li>You simply changed your mind about the purchase</li>
                    <li>You purchased the wrong template by mistake</li>
                    <li>You find that the template does not meet your specific needs or expectations (we encourage you to thoroughly review template descriptions, demos, and features before purchase)</li>
                    <li>You lack the technical knowledge to use the template (our support team can assist with basic template usage questions)</li>
                    <li>You claim the template is not compatible with a specific feature or third-party software not explicitly listed as compatible in the template description</li>
                    <li>The refund is requested after the refund period has expired</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2>Refund Request Process</h2>
                <p>To request a refund, please follow these steps:</p>
                <ol>
                    <li>Contact our support team at <a href="mailto:support@neowebx.com">support@neowebx.com</a> within 14 days of your purchase.</li>
                    <li>Include your order number, the name of the template purchased, and a detailed description of the issue you're experiencing.</li>
                    <li>If you're experiencing technical issues, please provide:
                        <ul>
                            <li>A detailed description of the problem</li>
                            <li>Screenshots or screen recordings that demonstrate the issue</li>
                            <li>Your system environment (operating system, browser version, etc.)</li>
                            <li>Steps you've already taken to try to resolve the issue</li>
                        </ul>
                    </li>
                </ol>
                <p>Our support team will review your request and may attempt to resolve any technical issues before processing a refund. We aim to respond to all refund requests within 3 business days.</p>
            </div>
            
            <div class="policy-section">
                <h2>Refund Processing</h2>
                <p>If your refund request is approved:</p>
                <ul>
                    <li>The refund will be processed using the same payment method used for the purchase</li>
                    <li>Refunds typically appear in your account within 5-10 business days, depending on your payment provider</li>
                    <li>Your access to the refunded template will be revoked</li>
                </ul>
                <p>Please note that any payment processing fees or currency conversion fees are non-refundable.</p>
            </div>
            
            <div class="policy-section">
                <h2>Support Before Refund</h2>
                <p>Before requesting a refund for technical issues, we encourage you to:</p>
                <ul>
                    <li>Check our <a href="#">Documentation</a> for solutions to common problems</li>
                    <li>Contact our support team for assistance with technical difficulties</li>
                    <li>Review the template requirements to ensure your system meets the necessary specifications</li>
                </ul>
                <p>Our goal is to help you successfully use our templates, and in many cases, technical issues can be resolved with proper guidance.</p>
            </div>
            
            <div class="policy-section">
                <h2>Exceptions</h2>
                <p>NeoWebX Template Store reserves the right to make exceptions to this policy at its sole discretion. Any exceptions made are done on a case-by-case basis and do not constitute a waiver of our right to enforce this policy in the future.</p>
            </div>
            
            <div class="policy-section">
                <h2>Changes to This Policy</h2>
                <p>We may update our Refund Policy from time to time. Any changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically for any changes.</p>
            </div>
            
            <div class="policy-section">
                <h2>Contact Us</h2>
                <p>If you have any questions about our Refund Policy, please contact us:</p>
                <ul class="contact-info">
                    <li><i class="fas fa-envelope"></i> Email: <a href="mailto:support@neowebx.com">support@neowebx.com</a></li>
                    <li><i class="fas fa-phone"></i> Phone: <a href="tel:+11234567890">+1 (123) 456-7890</a></li>
                    <li><i class="fas fa-map-marker-alt"></i> Address: 123 Template St, San Francisco, CA 94107</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?>
