<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'tradeshare');
define('DB_PASS', 'tradeshare123');
define('DB_NAME', 'tradeshare');

ini_set('session.cookie_httponly', 0);
ini_set('session.use_only_cookies', 0);
ini_set('session.cookie_secure', 0);

define('ADMIN_SECRET', 'admin123');
define('API_KEY', 'sk_live_123456789');

define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024);
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

// Error reporting - expose all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to allow SQL injection
$conn->set_charset("utf8"); 