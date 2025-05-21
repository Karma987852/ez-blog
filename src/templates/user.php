<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

// Get user ID from URL
$userId = $_GET['id'] ?? null;

if ($userId) {
    $userModel = new User($conn);
    $userModel->id = $userId;
    $result = $userModel->read_single();
    $user = $result->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // Handle case where user doesn't exist
        header('Location: /ez-blog/home');
        exit;
    }
} else {
    // Handle case where no ID is provided
    header('Location: /ez-blog/home');
    exit;
}
?>

<link rel="stylesheet" href="/ez-blog/public/assets/css/user.css">
<div class="profile-container">        
    <div id="profileModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="profile-header">
                <img src="<?php echo $user['profile_img'] ?? '/ez-blog/public/assets/img/profile.jpg'; ?>" alt="Profile Image" class="profile-img">
                <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
                <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="profile-body">
                <div class="bio-section">
                    <h3>About</h3>
                    <p><?php echo htmlspecialchars($user['bio'] ?? 'No bio available'); ?></p>
                </div>
                <div class="meta-section">
                    <p><strong>Member since:</strong> <?php echo date('d-m-Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Close modal functionality
    document.querySelector('.close-btn').onclick = function() {
        window.location.href = '/ez-blog/home'; // Redirect back to home
    }

    // Close when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById("profileModal");
        if (event.target == modal) {
            window.location.href = '/ez-blog/home';
        }
    }
</script>
