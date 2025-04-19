-- NeoWebX Template Store Database Schema

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS neowebx_store DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE neowebx_store;

-- Languages Table
CREATE TABLE IF NOT EXISTS languages (
    language_id INT AUTO_INCREMENT PRIMARY KEY,
    language_code VARCHAR(10) NOT NULL UNIQUE,
    language_name VARCHAR(100) NOT NULL,
    flag_icon VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Currencies Table
CREATE TABLE IF NOT EXISTS currencies (
    currency_id INT AUTO_INCREMENT PRIMARY KEY,
    currency_code VARCHAR(10) NOT NULL UNIQUE,
    currency_name VARCHAR(100) NOT NULL,
    currency_symbol VARCHAR(10) NOT NULL,
    exchange_rate DECIMAL(10, 4) DEFAULT 1.0000,
    is_default TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_slug VARCHAR(100) NOT NULL UNIQUE,
    parent_id INT NULL DEFAULT NULL,
    category_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(category_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Category Translations Table
CREATE TABLE IF NOT EXISTS category_translations (
    translation_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    category_description TEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY category_language (category_id, language_code),
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Features Table
CREATE TABLE IF NOT EXISTS features (
    feature_id INT AUTO_INCREMENT PRIMARY KEY,
    feature_slug VARCHAR(100) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Feature Translations Table
CREATE TABLE IF NOT EXISTS feature_translations (
    translation_id INT AUTO_INCREMENT PRIMARY KEY,
    feature_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    feature_name VARCHAR(100) NOT NULL,
    feature_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY feature_language (feature_id, language_code),
    FOREIGN KEY (feature_id) REFERENCES features(feature_id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Templates Table
CREATE TABLE IF NOT EXISTS templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    template_slug VARCHAR(100) NOT NULL UNIQUE,
    category_id INT NULL,
    base_price DECIMAL(10, 2) NOT NULL,
    discount_price DECIMAL(10, 2) NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_new TINYINT(1) DEFAULT 0,
    is_trending TINYINT(1) DEFAULT 0,
    is_sale TINYINT(1) DEFAULT 0,
    thumbnail VARCHAR(255) NULL,
    preview_url VARCHAR(255) NULL,
    download_file VARCHAR(255) NULL,
    download_size INT NULL COMMENT 'Size in KB',
    release_date DATE NULL,
    last_update DATE NULL,
    version VARCHAR(20) NULL,
    views INT DEFAULT 0,
    downloads INT DEFAULT 0,
    rating DECIMAL(3, 2) DEFAULT 0.00,
    total_ratings INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Template Translations Table
CREATE TABLE IF NOT EXISTS template_translations (
    translation_id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    template_name VARCHAR(255) NOT NULL,
    template_description TEXT NULL,
    template_features TEXT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY template_language (template_id, language_code),
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Template Features Junction Table
CREATE TABLE IF NOT EXISTS template_features (
    template_id INT NOT NULL,
    feature_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (template_id, feature_id),
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES features(feature_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Template Languages Support Table (which languages the template itself supports)
CREATE TABLE IF NOT EXISTS template_languages (
    template_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (template_id, language_code),
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Template Images Table
CREATE TABLE IF NOT EXISTS template_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_main TINYINT(1) DEFAULT 0,
    image_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    firebase_uid VARCHAR(128) UNIQUE NOT NULL COMMENT 'Firebase User ID',
    username VARCHAR(50) NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    profile_image VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    address_line1 VARCHAR(255) NULL,
    address_line2 VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    postal_code VARCHAR(20) NULL,
    country VARCHAR(100) NULL,
    language_code VARCHAR(10) NULL DEFAULT 'en',
    currency_code VARCHAR(10) NULL DEFAULT 'USD',
    newsletter_opt_in TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (language_code) REFERENCES languages(language_code) ON DELETE SET NULL,
    FOREIGN KEY (currency_code) REFERENCES currencies(currency_code) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Wishlists Table
CREATE TABLE IF NOT EXISTS wishlists (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    template_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_template (user_id, template_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Carts Table
CREATE TABLE IF NOT EXISTS carts (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Cart Items Table
CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    template_id INT NOT NULL,
    license_type ENUM('standard', 'extended') DEFAULT 'standard',
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY cart_template_license (cart_id, template_id, license_type),
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    order_status ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    payment_transaction_id VARCHAR(255) NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    discount DECIMAL(10, 2) DEFAULT 0.00,
    tax DECIMAL(10, 2) DEFAULT 0.00,
    total DECIMAL(10, 2) NOT NULL,
    currency_code VARCHAR(10) NOT NULL,
    exchange_rate DECIMAL(10, 4) DEFAULT 1.0000,
    billing_first_name VARCHAR(100) NULL,
    billing_last_name VARCHAR(100) NULL,
    billing_email VARCHAR(255) NULL,
    billing_phone VARCHAR(20) NULL,
    billing_address_line1 VARCHAR(255) NULL,
    billing_address_line2 VARCHAR(255) NULL,
    billing_city VARCHAR(100) NULL,
    billing_state VARCHAR(100) NULL,
    billing_postal_code VARCHAR(20) NULL,
    billing_country VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (currency_code) REFERENCES currencies(currency_code) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    template_id INT NOT NULL,
    template_name VARCHAR(255) NOT NULL,
    license_type ENUM('standard', 'extended') DEFAULT 'standard',
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_title VARCHAR(255) NULL,
    review_text TEXT NULL,
    is_verified_purchase TINYINT(1) DEFAULT 0,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Website Settings Table
CREATE TABLE IF NOT EXISTS settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_type ENUM('text', 'number', 'boolean', 'json', 'file', 'color') DEFAULT 'text',
    is_public TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Initial data for Languages
INSERT INTO languages (language_code, language_name, flag_icon, is_active) VALUES
('en', 'English', 'assets/images/flags/en.png', 1),
('fr', 'French', 'assets/images/flags/fr.png', 1),
('de', 'German', 'assets/images/flags/de.png', 1),
('es', 'Spanish', 'assets/images/flags/es.png', 1),
('it', 'Italian', 'assets/images/flags/it.png', 1),
('pt', 'Portuguese', 'assets/images/flags/pt.png', 1),
('ar', 'Arabic', 'assets/images/flags/ar.png', 1),
('zh', 'Chinese', 'assets/images/flags/zh.png', 1),
('ja', 'Japanese', 'assets/images/flags/ja.png', 1),
('ru', 'Russian', 'assets/images/flags/ru.png', 1),
('hi', 'Hindi', 'assets/images/flags/in.png', 1);

-- Initial data for Currencies
INSERT INTO currencies (currency_code, currency_name, currency_symbol, exchange_rate, is_default, is_active) VALUES
('USD', 'US Dollar', '$', 1.0000, 1, 1),
('EUR', 'Euro', '€', 0.8500, 0, 1),
('GBP', 'British Pound', '£', 0.7800, 0, 1),
('INR', 'Indian Rupee', '₹', 82.5000, 0, 1),
('CAD', 'Canadian Dollar', 'C$', 1.3500, 0, 1),
('AUD', 'Australian Dollar', 'A$', 1.4800, 0, 1),
('JPY', 'Japanese Yen', '¥', 147.5000, 0, 1),
('CNY', 'Chinese Yuan', '¥', 7.2000, 0, 1); 