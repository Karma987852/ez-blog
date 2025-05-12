<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Handle logout if requested
if (isset($_GET['logout'])) {
    session_unset();     // Clear all session variables
    session_destroy();   // Destroy the session
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?')); // Redirect to same page without query params
    exit();
}
?>
<script>
    window.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
</script>
<script src="/public/assets/js/auth-dialog.js"></script>
<header>
    <div class="header-container">
        <img src="../../public/assets/img/logo.png" class="header-logo">
        <div class="search-container">
            <form action="" method="GET">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search Blog Sphere">
                </div>
            </form>
        </div>
        <div class="header-actions">
            <a href="/home?dialog=createPost" onclick="if(!window.isLoggedIn) { window.location.href='/home?dialog=login'; return false; }" class="write-btn">
                <i class="bi bi-pencil-square" style="font-size: 1.3rem; margin-right: 10px"></i>
                Write
            </a>
            <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <a href="/profile" class="profile-link">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <a href="?logout=1" class="logout-btn"> <!-- Updated logout link -->
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            <?php else: ?>
                <a href="/home?dialog=login" class="login-btn">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
