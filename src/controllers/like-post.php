<?php
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User must be logged in to like posts']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? null;
$type = $data['type'] ?? 'like'; // 'like' or 'dislike'

if (!$post_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Post ID is required']);
    exit();
}

try {
    // Check if user has already liked/disliked this post
    $check_query = "SELECT id, type FROM likes WHERE blog_post_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->execute([$post_id, $_SESSION['user_id']]);
    $existing_like = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_like) {
        if ($existing_like['type'] === $type) {
            // Remove the like/dislike if clicking the same button
            $delete_query = "DELETE FROM likes WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->execute([$existing_like['id']]);
            $action = 'removed';
        } else {
            // Update the existing like to the opposite type
            $update_query = "UPDATE likes SET type = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->execute([$type, $existing_like['id']]);
            $action = 'updated';
        }
    } else {
        // Insert new like
        $insert_query = "INSERT INTO likes (blog_post_id, user_id, type) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->execute([$post_id, $_SESSION['user_id'], $type]);
        $action = 'added';
    }

    // Get updated counts
    $likes_query = "SELECT COUNT(*) as count FROM likes WHERE blog_post_id = ? AND type = 'like'";
    $likes_stmt = $conn->prepare($likes_query);
    $likes_stmt->execute([$post_id]);
    $likes_count = $likes_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    $dislikes_query = "SELECT COUNT(*) as count FROM likes WHERE blog_post_id = ? AND type = 'dislike'";
    $dislikes_stmt = $conn->prepare($dislikes_query);
    $dislikes_stmt->execute([$post_id]);
    $dislikes_count = $dislikes_stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode([
        'success' => true,
        'action' => $action,
        'likes_count' => $likes_count,
        'dislikes_count' => $dislikes_count
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    exit();
}