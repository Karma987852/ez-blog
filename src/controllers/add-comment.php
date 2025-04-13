<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User must be logged in to comment']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? null;
$content = $data['content'] ?? null;

if (!$post_id || !$content) {
    http_response_code(400);
    echo json_encode(['error' => 'Post ID and content are required']);
    exit();
}

try {
    // Check if blog post exists
    $check_post = "SELECT id FROM blog_posts WHERE id = ?";
    $check_stmt = $conn->prepare($check_post);
    $check_stmt->execute([$post_id]);
    
    if (!$check_stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Blog post not found']);
        exit();
    }

    // Insert new comment
    $query = "INSERT INTO comments (blog_post_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->execute([$post_id, $_SESSION['user_id'], $content]);
    
    // Get the inserted comment's details including username
    $select_query = "SELECT c.*, u.username 
                     FROM comments c 
                     JOIN users u ON c.user_id = u.id 
                     WHERE c.id = ?";
    $select_stmt = $conn->prepare($select_query);
    $select_stmt->execute([$conn->lastInsertId()]);
    $comment = $select_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get updated comment count
    $count_query = "SELECT COUNT(*) as count FROM comments WHERE blog_post_id = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->execute([$post_id]);
    $comments_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'comment' => $comment,
        'comments_count' => $comments_count
    ]);
    exit();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred']);
    exit();
}