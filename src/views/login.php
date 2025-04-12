<link rel="stylesheet" href="../../public/assets/css/login.css">

<div class="login-container">
    <div class="close-btn">
        <a href="/home" class="close-icon">&times;</a>
    </div>
    <h2>Login to BlogSphere</h2>
    <form action="../controllers/login.php" method="POST">
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="button-group">
            <button type="submit" class="login-btn">Login</button>
            <a href="/home?dialog=register" class="register-btn">Register</a>
        </div>
    </form>
</div>
