<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

// Check if database connection is valid
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Only accept GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Check if post_id parameter is provided
if (!isset($_GET['post_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Post ID is required'
    ]);
    exit;
}

$postId = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

// Validate post_id
if (!$postId || $postId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid Post ID'
    ]);
    exit;
}

try {
    // Prepare and execute the query
    $query = "SELECT 
                c.id,
                c.comment,
                c.created_at,
                c.updated_at,
                u.id as user_id,
                u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.blog_post_id = :post_id
            ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->execute();
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the comments
    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in get-comments.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to retrieve comments',
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Error in get-comments.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred'
    ]);
}
