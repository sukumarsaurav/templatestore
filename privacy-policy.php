<?php
// Set page title
$pageTitle = "Privacy Policy - NeoWebX Template Store";
$pageDescription = "Our privacy policy explains how we collect, use, and protect your personal information.";
$pageKeywords = "privacy policy, data protection, personal information, privacy";

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
        <h1>Privacy Policy</h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span>Privacy Policy</span>
        </div>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="policy-content section-padding">
    <div class="container">
        <div class="policy-wrapper">
            <div class="last-updated">
                <p>Last Updated: June 15, 2023</p>
            </div>
            
            <div class="policy-section">
                <h2>Introduction</h2>
                <p>Welcome to NeoWebX Template Store ("we," "our," or "us"). We are committed to protecting your privacy and personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase from us.</p>
                <p>Please read this Privacy Policy carefully. By accessing or using our website, you acknowledge that you have read, understood, and agree to be bound by all the terms outlined in this policy. If you do not agree with our policies and practices, please do not use our website.</p>
            </div>
            
            <div class="policy-section">
                <h2>Information We Collect</h2>
                <p>We collect several types of information from and about users of our website, including:</p>
                
                <h3>Personal Information</h3>
                <ul>
                    <li><strong>Contact Information:</strong> Name, email address, mailing address, phone number.</li>
                    <li><strong>Account Information:</strong> Username, password, purchase history, and preferences.</li>
                    <li><strong>Payment Information:</strong> Credit card details, billing address, and other payment details (Note: we do not store full credit card information on our servers).</li>
                </ul>
                
                <h3>Automatically Collected Information</h3>
                <ul>
                    <li><strong>Usage Data:</strong> IP address, browser type, operating system, referring URLs, access times, and pages viewed.</li>
                    <li><strong>Cookie Data:</strong> Information collected through cookies, web beacons, and other tracking technologies.</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2>How We Use Your Information</h2>
                <p>We may use the information we collect about you for various purposes, including:</p>
                <ul>
                    <li>Providing, operating, and maintaining our website and services</li>
                    <li>Processing and fulfilling your orders and purchases</li>
                    <li>Sending you invoices, updates, security alerts, and support messages</li>
                    <li>Responding to your comments, questions, and requests</li>
                    <li>Improving our website, products, and services</li>
                    <li>Understanding how you interact with our website</li>
                    <li>Personalizing your experience and delivering content relevant to your interests</li>
                    <li>Sending you marketing and promotional communications (with your consent)</li>
                    <li>Detecting, preventing, and addressing fraud and security issues</li>
                    <li>Complying with legal obligations</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2>Disclosure of Your Information</h2>
                <p>We may disclose your personal information to the following categories of recipients:</p>
                <ul>
                    <li><strong>Service Providers:</strong> Third-party vendors who provide services on our behalf, such as payment processing, website hosting, data analysis, and customer service.</li>
                    <li><strong>Business Partners:</strong> Trusted third parties who assist us in offering products and services, marketing, or other business activities.</li>
                    <li><strong>Legal Requirements:</strong> When required by law or in response to legal process, to protect our rights, or to investigate fraud or other issues.</li>
                    <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of all or part of our business assets.</li>
                </ul>
            </div>
            
            <div class="policy-section">
                <h2>Cookies and Tracking Technologies</h2>
                <p>We use cookies and similar tracking technologies to track activity on our website and store certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device.</p>
                <p>We use cookies for essential functions, analytics, advertising, and user preferences. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our site.</p>
            </div>
            
            <div class="policy-section">
                <h2>Your Rights and Choices</h2>
                <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
                <ul>
                    <li>The right to access, update, or delete your personal information</li>
                    <li>The right to rectification if your information is inaccurate or incomplete</li>
                    <li>The right to object to our processing of your personal data</li>
                    <li>The right to request that we restrict the processing of your personal information</li>
                    <li>The right to data portability</li>
                    <li>The right to withdraw consent at any time</li>
                </ul>
                <p>To exercise any of these rights, please contact us using the information provided in the "Contact Us" section below.</p>
            </div>
            
            <div class="policy-section">
                <h2>Data Security</h2>
                <p>We have implemented appropriate technical and organizational security measures designed to protect the security of any personal information we process. However, please also remember that we cannot guarantee that the internet itself is 100% secure. Although we will do our best to protect your personal information, the transmission of personal information to and from our website is at your own risk.</p>
            </div>
            
            <div class="policy-section">
                <h2>Data Retention</h2>
                <p>We will retain your personal information only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use your information to the extent necessary to comply with our legal obligations, resolve disputes, and enforce our legal agreements and policies.</p>
            </div>
            
            <div class="policy-section">
                <h2>Children's Privacy</h2>
                <p>Our website is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us so that we may delete this information from our servers.</p>
            </div>
            
            <div class="policy-section">
                <h2>Changes to This Privacy Policy</h2>
                <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date at the top. You are advised to review this Privacy Policy periodically for any changes.</p>
            </div>
            
            <div class="policy-section">
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us:</p>
                <ul class="contact-info">
                    <li><i class="fas fa-envelope"></i> Email: <a href="mailto:privacy@neowebx.com">privacy@neowebx.com</a></li>
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
