<?php
require_once __DIR__ . '/../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');

    try {
        $database = new Database();
        $db = $database->connect();

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        $imageName = null;

        if (empty($title) || empty($content)) {
            throw new Exception('Title and content are required');
        }

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '../../public/uploads/';
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

        $query = "INSERT INTO blog_posts (title, content, user_id, category_id, img_url) 
                  VALUES (:title, :content, :user_id, :category_id, :img_url)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindParam(':img_url', $imageName);

        if ($stmt->execute()) {
            echo json_encode(['success' => "Blog post created successfully!"]);
        } else {
            throw new Exception('Failed to create post.');
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => "Database error: " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
