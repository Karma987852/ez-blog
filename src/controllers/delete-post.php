<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get post ID from POST data
$postId = $_POST['id'] ?? null;
error_log(print_r($postId, TRUE)); 
if (!$postId || !is_numeric($postId)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    exit;
}

try {
    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $post->id = $postId;

    // First verify the post exists and belongs to the user
    $postData = $post->read_single();
    if (!$postData || $postData['user_id'] !== $_SESSION['user_id']) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Post not found or unauthorized']);
        exit;
    }
    
    // Delete the post
    if ($post->delete()) {
        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete post']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
