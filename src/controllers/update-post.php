<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get post ID
$postId = $_POST['post_id'] ?? null;
if (!$postId || !is_numeric($postId)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
    exit;
}

try {
    $database = new Database();
    $db = $database->connect();

    $post_id = $postId;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $user_id = $_SESSION['user_id'];
    $imageName = null;

    if (empty($post_id) || empty($title) || empty($content)) {
        throw new Exception('Post ID, title, and content are required.');
    }

    // Check if post exists and belongs to user
    $checkStmt = $db->prepare("SELECT img_url FROM blog_posts WHERE id = :id AND user_id = :user_id");
    $checkStmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->execute();

    $existingPost = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingPost) {
        throw new Exception('Post not found or unauthorized.');
    }

    $imageName = $existingPost['img_url']; // Keep old image by default

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/ez-blog/public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
        $targetFile = $uploadDir . $uniqueName;

        if (move_uploaded_file($fileTmp, $targetFile)) {
            $imageName = $uniqueName;
        } else {
            throw new Exception('Failed to upload image.');
        }
    }

    // Update the post
    $updateQuery = "UPDATE blog_posts 
                    SET title = :title, content = :content, category_id = :category_id, img_url = :img_url
                    WHERE id = :id AND user_id = :user_id";
    $stmt = $db->prepare($updateQuery);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':category_id', $category_id, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindParam(':img_url', $imageName);
    $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post updated successfully!']);
    } else {
        throw new Exception('Failed to update post.');
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
