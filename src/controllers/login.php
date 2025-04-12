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
        $_SESSION['message'] = "❌ Please fill in all fields!";
        $_SESSION['message_type'] = 'error';
        header('Location: /home?dialog=login');
        exit;
    }

    try {
        // Find user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $_SESSION['message'] = "❌ Invalid email or password!";
            $_SESSION['message_type'] = 'error';
            header('Location: /home?dialog=login');
            exit;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $_SESSION['message'] = "❌ Invalid email or password!";
            $_SESSION['message_type'] = 'error';
            header('Location: /home?dialog=login');
            exit;
        }

        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['fullname'] = $user['fullname'];
        
        // Set success message
        $_SESSION['message'] = "✅ Login successful!";
        $_SESSION['message_type'] = 'success';
        
        // Redirect to dashboard
        header('Location: /home');
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = "❌ Database error: " . $e->getMessage();
        $_SESSION['message_type'] = 'error';
        header('Location: /home?dialog=login');
        exit;
    } catch (Exception $e) {
        $_SESSION['message'] = "❌ Error: " . $e->getMessage();
        $_SESSION['message_type'] = 'error';
        header('Location: /home?dialog=login');
        exit;
    }
}

// If not a POST request, redirect to home
header('Location: /home');
exit;
?>
