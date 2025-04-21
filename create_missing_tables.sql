-- Create settings table
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_group` varchar(50) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`setting_id`),
  UNIQUE KEY `group_key_unique` (`setting_group`, `setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add display_order column to features table if it doesn't exist
ALTER TABLE `features` ADD COLUMN IF NOT EXISTS `display_order` int(11) DEFAULT 0;

-- Add role column to users table if it doesn't exist
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `role` enum('admin','customer') NOT NULL DEFAULT 'customer';

-- Add total_price column to order_items table if it doesn't exist
ALTER TABLE `order_items` ADD COLUMN IF NOT EXISTS `total_price` decimal(10,2) NOT NULL DEFAULT 0.00;

-- Create order_history table if it doesn't exist
CREATE TABLE IF NOT EXISTS `order_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`history_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 