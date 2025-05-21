<?php
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
// session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /ez-blog/home?dialog=login');
    exit();
}

// Connect to database
$database = new Database();
$db = $database->connect();

// Fetch all users
$stmt = $db->prepare("SELECT id, fullname AS name, bio FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="users-class-container">
    <?php foreach ($users as $user): ?>
        <div class="users-class-user-card">
            <div class="users-class-user-info">
                <img src="/ez-blog/public/assets/img/profile.jpg" alt="<?= htmlspecialchars($user['name']) ?>" class="users-class-avatar">
                <div class="users-class-user-details">
                    <h2 class="users-class-user-name"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="users-class-user-bio"><?= htmlspecialchars($user['bio']) ?></p>
                </div>
            </div>
            <a href="/ez-blog/home?dialog=user" class="users-class-view-btn">
              View
            </a>
        </div>
    <?php endforeach; ?>
</div>
