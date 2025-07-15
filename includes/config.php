<?php
/**
 * Configuration File for NeoWebX Template Store
 * Contains database credentials and application settings
 */

// Database Configuration
define('DB_HOST', '153.92.15.82');
define('DB_USERNAME', 'u277468165_neowebxstore');
define('DB_PASSWORD', 'Milk@sdk14');
define('DB_DATABASE', 'u277468165_neowebxstore');

// Application Settings
define('APP_NAME', 'NeoWebX Template Store');
define('APP_URL', 'https://neowebx.store');
define('APP_ENV', 'production'); // 'production' or 'development'

// Email Configuration
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'info@neowebx.store');
define('MAIL_PASSWORD', 'your_mail_password');
define('MAIL_FROM', 'info@neowebx.store');
define('MAIL_FROM_NAME', 'NeoWebX Template Store');

// Currency and Language Defaults
define('DEFAULT_CURRENCY', 'USD');
define('DEFAULT_LANGUAGE', 'en');

// File Upload Paths
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');
define('TEMPLATE_UPLOAD_PATH', UPLOAD_PATH . 'templates/');
define('IMAGE_UPLOAD_PATH', UPLOAD_PATH . 'images/');

// Create upload directories if they don't exist
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
if (!file_exists(TEMPLATE_UPLOAD_PATH)) {
    mkdir(TEMPLATE_UPLOAD_PATH, 0755, true);
}
if (!file_exists(IMAGE_UPLOAD_PATH)) {
    mkdir(IMAGE_UPLOAD_PATH, 0755, true);
}

// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
} 