<?php
$host = '127.0.0.1';
$dbname = 'blog_sphere';
$username = 'blog_user';
$password = 'password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>
