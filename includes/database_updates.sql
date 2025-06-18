-- Remove firebase_uid column and add password column
ALTER TABLE users 
    DROP COLUMN firebase_uid,
    ADD COLUMN password VARCHAR(255) NOT NULL AFTER email,
    ADD COLUMN remember_token VARCHAR(100) NULL AFTER password;

-- Update role enum to include all necessary roles
ALTER TABLE users 
    MODIFY COLUMN role ENUM('admin', 'customer', 'vendor') NOT NULL DEFAULT 'customer';

-- Add indexes for faster lookups
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_remember_token ON users(remember_token);

-- Insert default languages
INSERT INTO languages (language_code, language_name, flag_icon, is_active) VALUES
('en', 'English', 'assets/images/flags/en.png', 1),
('es', 'Spanish', 'assets/images/flags/es.png', 1),
('fr', 'French', 'assets/images/flags/fr.png', 1),
('de', 'German', 'assets/images/flags/de.png', 1),
('it', 'Italian', 'assets/images/flags/it.png', 1),
('pt', 'Portuguese', 'assets/images/flags/pt.png', 1),
('ar', 'Arabic', 'assets/images/flags/ar.png', 1),
('zh', 'Chinese', 'assets/images/flags/zh.png', 1),
('ja', 'Japanese', 'assets/images/flags/ja.png', 1),
('ru', 'Russian', 'assets/images/flags/ru.png', 1);

-- Insert default currencies
INSERT INTO currencies (currency_code, currency_name, currency_symbol, exchange_rate, is_default, is_active) VALUES
('USD', 'US Dollar', '$', 1.0000, 1, 1),
('EUR', 'Euro', '€', 0.85000, 0, 1),
('GBP', 'British Pound', '£', 0.78000, 0, 1),
('JPY', 'Japanese Yen', '¥', 110.0000, 0, 1),
('AUD', 'Australian Dollar', 'A$', 1.48000, 0, 1),
('CAD', 'Canadian Dollar', 'C$', 1.35000, 0, 1),
('CHF', 'Swiss Franc', 'CHF', 0.92000, 0, 1),
('CNY', 'Chinese Yuan', '¥', 6.45000, 0, 1),
('INR', 'Indian Rupee', '₹', 74.5000, 0, 1);

-- Create initial admin user (password: admin123)
INSERT INTO users (
    email, 
    password, 
    first_name, 
    last_name, 
    role, 
    is_active,
    language_code,
    currency_code
) VALUES (
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Admin',
    'User',
    'admin',
    1,
    'en',
    'USD'
); 