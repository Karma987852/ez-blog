<?php
require_once __DIR__ . '/../config/database.php';

$postId = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($postId) {
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    die("❌ Post ID not found.");
}

include __DIR__ . '/../templates/post.php';
