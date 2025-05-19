<?php
require_once __DIR__ . '/../config/database.php';

$postId = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($postId) {
    $stmt = $conn->prepare("SELECT a.id, a.title, a.content, b.username,c.name, a.img_url FROM blog_posts a LEFT JOIN users b ON a.user_id = b.id LEFT JOIN categories c ON a.category_id = c
.id WHERE a.id = :id LIMIT 1;");
    $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die("❌ Post ID not found.");
}

include __DIR__ . '/../templates/post.php';
