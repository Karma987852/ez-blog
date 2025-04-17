<?php
require_once __DIR__ . '/../config/database.php';
session_start();

// Initialize variables
$categories = [];
$success = '';
$error = '';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /home?dialog=login');
    exit();
}

$database = new Database();
$db = $database->connect();

// Fetch categories for dropdown (for both GET and POST requests)
try {
    $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching categories: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="/public/assets/css/post.css">
<div class="overlay">
    <div class="create-post-container">
        <button class="close-btn" onclick="closeDialog()">×</button>
        <h1>Create New Blog Post</h1>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form id="createPostForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required maxlength="255" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Category (Optional)</label>
                <select id="category_id" name="category_id">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['id']); ?>" 
                            <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Attach Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="submit-btn">Create Post</button>
        </form>
        <div id="responseMessage"></div>
    </div>
</div>

<script>
document.getElementById('createPostForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';
    
    const messageBox = document.getElementById('responseMessage');
    messageBox.innerHTML = '';

    try {
        const response = await fetch('/src/controllers/create-posts.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            messageBox.innerHTML = `<div class="alert success">${result.success}</div>`;
            setTimeout(() => {
                closeDialog();
                window.location.reload();
            }, 1500);
        } else if (result.error) {
            messageBox.innerHTML = `<div class="alert error">${result.error}</div>`;
        }
    } catch (error) {
        console.error('Error:', error);
        messageBox.innerHTML = `<div class="alert error">Failed to submit: ${error.message}</div>`;
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Post';
    }
});

function closeDialog() {
    const overlay = document.querySelector('.overlay');
    overlay.style.display = 'none';
    // Optional: Clear form when closing
    document.getElementById('createPostForm').reset();
}
</script>
</body>
</html>
