<?php

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set default timezone
date_default_timezone_set('UTC');

// Database settings
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));

// WooCommerce settings
define('WC_SITE_URL', getenv('WC_SITE_URL'));
define('WC_CONSUMER_KEY', getenv('WC_CONSUMER_KEY'));
define('WC_CONSUMER_SECRET', getenv('WC_CONSUMER_SECRET'));

// JWT settings
define('JWT_SECRET', getenv('JWT_SECRET'));
define('JWT_EXPIRATION_TIME', getenv('JWT_EXPIRATION_TIME'));

// Error reporting settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle errors
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo json_encode([
        'error' => [
            'code' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
        ]
    ]);
});

// Autoload dependencies
require_once __DIR__ . '/vendor/autoload.php';
