<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'blog_sphere');
define('DB_USER', 'blog_user');
define('DB_PASS', 'password');
define('DB_DSN', "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME);

try {
    $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>
