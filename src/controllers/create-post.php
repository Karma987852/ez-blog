<?php
require_once __DIR__ . '/../config/database.php';
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

// Check for AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

// Set response content type
header('Content-Type: application/json');

try {
    // Connect to database
    $database = new Database();
    $db = $database->connect();

    // Sanitize inputs
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $imageName = null;

    // Validate required fields
    if (empty($title) || empty($content)) {
        throw new Exception('Title and content are required.');
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
        $uniqueName = time() . '_' . $safeName;
        $targetFile = $uploadDir . $uniqueName;

        if (!move_uploaded_file($fileTmp, $targetFile)) {
            throw new Exception('Failed to upload image.');
        }

        $imageName = $uniqueName;
    }

    // Insert blog post
    $query = "INSERT INTO blog_posts (title, content, user_id, category_id, img_url) 
              VALUES (:title, :content, :user_id, :category_id, :img_url)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':category_id', $category_id, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindParam(':img_url', $imageName);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post created successfully!']);
    } else {
        throw new Exception('Failed to create post.');
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
