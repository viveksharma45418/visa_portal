<?php
/**
 * Visa Vista Global — Database Configuration
 * Uses PDO with prepared statements for maximum security.
 */

define('DB_HOST',     'localhost');
define('DB_NAME',     'visa_portal');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_CHARSET',  'utf8mb4');

// Absolute path to project root
define('BASE_PATH',   dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'applications');
define('UPLOAD_URL',  'uploads/applications');

// Maximum upload size in bytes (10 MB)
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);

// Allowed MIME types for file uploads
define('ALLOWED_MIME_TYPES', [
    'application/pdf',
    'image/jpeg',
    'image/jpg',
    'image/png',
]);

define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);

/**
 * Returns a singleton PDO connection instance.
 */
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
        }
    }
    return $pdo;
}
