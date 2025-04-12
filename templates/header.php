<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSphere</title>
    <link rel="stylesheet" href="public/assets/css/header.css"> 
</head>
<body>
<header>
    <nav class="navbar">
        <!-- Logo Section -->
        <div class="logo">
            <a href="/">
                <img src="public/assets/img/logo.png" alt="BlogSphere Logo">
            </a>
        </div>

        <!-- Search Bar Section -->
        <div class="search-bar">
            <input type="text" placeholder="Search..." id="search">
            <button type="submit">🔍</button>
        </div>

        <!-- Login/Write Button Section -->
        <div class="login">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/write" class="btn">Write</a>
                <a href="/profile" class="btn">Profile</a>
                <a href="/logout" class="btn">Logout</a>
            <?php else: ?>
                <a href="/home?dialog=login" class="btn">Login</a>
                <a href="/home?dialog=register" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.querySelector('.search-bar button');
    const searchInput = document.querySelector('.search-bar input');

    searchButton.addEventListener('click', function() {
        const searchTerm = searchInput.value;
        if (searchTerm) {
            window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
        }
    });
});
</script>
