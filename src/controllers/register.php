<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$conn = new PDO(DB_DSN, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($fullname)) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Please fill in all fields!"
        ]);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Passwords do not match!"
        ]);
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'message' => "❌ Email already registered!"
            ]);
            exit;
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, bio) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$username, $email, $hashed_password, $fullname, $bio])) {
            echo json_encode([
                'success' => true,
                'message' => "✅ Registration successful! Please login.",
                'redirect' => '/home?dialog=login'
            ]);
            exit;
        } else {
            echo json_encode([
                'success' => false,
                'message' => "❌ Registration failed!"
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Database error: " . $e->getMessage()
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Error: " . $e->getMessage()
        ]);
        exit;
    }
}

// If not a POST request, just show the form
header('Location: /home?dialog=register');
exit;
?>
