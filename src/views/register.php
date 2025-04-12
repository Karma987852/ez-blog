<link rel="stylesheet" href="../../public/assets/css/register.css">
    <div class="dialog-container" id="dialog-container" style="display: block;">
        <div class="register-container">
            <div class="close-btn">
                <a href="/home" class="close-icon">&times;</a>
            </div>
            <h2>Register to BlogSphere</h2>
            <form action="../controllers/register.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
                <p>Already have an account? <a href="/home?dialog=login">Login here</a></p>
            </form>
        </div>
    </div>
