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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => "❌ Please fill in all fields!"
        ]);
        exit;
    }

    try {
        // Find user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => "❌ Invalid email or password!"
            ]);
            exit;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                'success' => false,
                'message' => "❌ Invalid email or password!"
            ]);
            exit;
        }

        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['fullname'] = $user['fullname'];
        
        echo json_encode([
            'success' => true,
            'message' => "✅ Login successful!",
            'redirect' => '/ez-blog/home'
        ]);
        exit;
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

// If not a POST request, redirect to home
header('Location: /ez-blog/home');
exit;
?>
