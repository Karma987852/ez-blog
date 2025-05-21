<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';

// session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Get user details if logged in
$user = null;
if ($isLoggedIn) {
    $user = new User($conn);
    $user->id = $_SESSION['user_id'];
    $result = $user->read_single();
    $user_data = $result->fetch(PDO::FETCH_ASSOC);
}

// Get top contributors
$query = "SELECT u.id, u.username, COUNT(p.id) as post_count 
          FROM users u 
          LEFT JOIN blog_posts p ON u.id = p.user_id 
          GROUP BY u.id 
          ORDER BY post_count DESC 
          LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->execute();
$top_contributors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="sidebar">
    <?php if ($isLoggedIn && $user_data): ?>
    <div class="sidebar-section">
        <h3>My Profile</h3>
        <div class="profile">
            <img src="/ez-blog/public/assets/img/profile.jpg" alt="Profile Picture" class="profile-avatar" />
            <p class="profile-name"><?= htmlspecialchars($user_data['username']) ?></p>
            <p class="profile-email"><?= htmlspecialchars($user_data['email']) ?></p>
            <p class="profile-email"><?= htmlspecialchars($user_data['bio']) ?></p>
            <div class="profile-stats">
                <?php
                $post = new Post($conn);
                $post_count_query = "SELECT COUNT(*) as count FROM blog_posts WHERE user_id = ?";
                $stmt = $conn->prepare($post_count_query);
                $stmt->execute([$user_data['id']]);
                $post_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                ?>
                <span>Posts: <?= $post_count ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="sidebar-section">
        <h3>Top Contributors</h3>
        <div class="people-list">
            <?php foreach ($top_contributors as $contributor): ?>
                <div class="person">
                    <div class="person-info">
                        <img src="/ez-blog/public/assets/img/profile.jpg" 
                            alt="<?= htmlspecialchars($contributor['username']) ?>" 
                            class="person-avatar">
                        <div class="person-details">
                            <div class="person-name">
                                <?= htmlspecialchars($contributor['username']) ?>
                            </div>
                            <div class="person-description">
                                Posts: <?= $contributor['post_count'] ?>
                            </div>
                        </div>
                    </div>
                    <a href="/ez-blog/home?dialog=user&id=<?= $contributor['id'] ?>" class="view-btn">
                        View
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.profile-name {
    font-size: 1.2em;
    font-weight: bold;
    margin: 5px 0;
}

.profile-email {
    color: #666;
    font-size: 0.9em;
    margin: 5px 0;
}

.profile-stats {
    margin-top: 10px;
    padding: 10px;
    background: #f5f5f5;
    border-radius: 5px;
}

.person {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.person-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.person-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.person-details {
    flex-grow: 1;
}

.person-name {
    font-weight: bold;
}

.person-description {
    font-size: 0.9em;
    color: #666;
}

.view-btn {
    padding: 5px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.view-btn:hover {
    background: #0056b3;
}
</style>
