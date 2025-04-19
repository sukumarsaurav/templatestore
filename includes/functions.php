<?php
// Prevent direct access
if (!defined('STORE_PATH')) {
    exit('Direct access not permitted');
}

/**
 * Format currency based on selected currency type
 * 
 * @param float $amount The amount to format
 * @param string $currency The currency code (USD, EUR, INR, CAD)
 * @return string Formatted currency string
 */
function formatCurrency($amount, $currency = 'USD') {
    switch ($currency) {
        case 'USD':
            return '$' . number_format($amount, 2);
        case 'EUR':
            return '€' . number_format($amount, 2);
        case 'INR':
            return '₹' . number_format($amount, 2);
        case 'CAD':
            return 'C$' . number_format($amount, 2);
        default:
            return '$' . number_format($amount, 2);
    }
}

/**
 * Truncate text to a specified length and append ellipsis
 * 
 * @param string $text The text to truncate
 * @param int $length The maximum length
 * @param string $append The string to append if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 150, $append = '...') {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length) . $append;
    }
    return $text;
}

/**
 * Sanitize user input
 * 
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
} 