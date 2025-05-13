<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

if (isset($_GET['logout'])) {
    session_unset();   
    session_destroy();   
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}
?>
<script>
    window.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
</script>
<script src="/public/assets/js/auth-dialog.js"></script>
<link rel="stylesheet" href="/public/assets/css/search.css">

<header>
    <div class="header-container">
        <img src="../../public/assets/img/logo.png" class="header-logo">
        <div class="search-container" style="position: relative;">
            <form action="/src/controllers/search-api.php" method="GET" id="search">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search..." autocomplete="off">
                    <div id="search-results" class="search-results-dropdown" style="display: none;"></div>
                </div>
            </form>
            <script src="/public/assets/js/search.js"></script>
        </div>
        <div class="header-actions">
            <a href="/home?dialog=createPost" onclick="if(!window.isLoggedIn) { window.location.href='/home?dialog=login'; return false; }" class="write-btn">
                <i class="bi bi-pencil-square" style="font-size: 0.8rem; margin-right: 5px"></i>
                Write
            </a>
            <?php if ($isLoggedIn): ?>
                <div class="user-menu">
                    <a href="?logout=1" class="logout-btn">
                        <i class="bi bi-box-arrow-right" style="font-size: 0.8rem margin-right: 5px;"></i> Logout
                    </a>
                     <a href="/profile" class="profile-link">
                        <i class="bi bi-person-circle" style="font-size: 1.9rem"></i>
                    </a>
                </div>
            <?php else: ?>
                <a href="/home?dialog=login" class="header-login-btn">
                    <i class="bi bi-box-arrow-in-right" style="font-size: 0.8rem; margin-right: 5px;"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
