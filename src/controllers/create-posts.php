<?php
require_once __DIR__ . '/../config/database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized access. Please log in.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {

    $database = new Database();
    $db = $database->connect();

    try {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

        if (empty($title) || empty($content)) {
            throw new Exception('Title and content are required');
        }

        $query = "INSERT INTO blog_posts (title, content, user_id, category_id) 
                  VALUES (:title, :content, :user_id, :category_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);

        if ($stmt->execute()) {
            echo json_encode(['success' => "Blog post created successfully!"]);
        } else {
            throw new Exception('Failed to create post');
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => "Database error: " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
exit();
