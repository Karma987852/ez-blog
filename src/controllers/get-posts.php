<?php
require_once __DIR__ . '/../config/database.php';

if (!$conn) {
    die("❌ Database connection failed: Connection is null.");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("
            SELECT 
                blog_posts.id, 
                blog_posts.title, 
                blog_posts.content, 
                blog_posts.created_at, 
                COALESCE(users.fullname, 'Unknown Author') AS author, 
                COALESCE(categories.name, 'Uncategorized') AS category
            FROM blog_posts
            LEFT JOIN users ON blog_posts.user_id = users.id
            LEFT JOIN categories ON blog_posts.category_id = categories.id
            ORDER BY blog_posts.created_at DESC
        ");
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $posts = [];
        error_log("Database error: " . $e->getMessage());
    }
}

include './views/home.php';
?>
