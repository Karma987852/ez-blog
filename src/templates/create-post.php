<?php
require_once __DIR__ . '/../config/database.php';
session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: /home?dialog=login');
    exit();
}

$database = new Database();
$db = $database->connect();

// Initialize variables
$categories = [];
$postData = [];
$isEditMode = false;
$pageTitle = 'Create New Blog Post';

// Check if we're in edit mode
if (isset($_GET['edit'])) {
    $postId = (int)$_GET['edit'];
    $isEditMode = true;
    $pageTitle = 'Edit Blog Post';
    
    // Fetch existing post data
    try {
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$postId, $_SESSION['user_id']]);
        $postData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$postData) {
            header('Location: /home');
            exit();
        }
    } catch(PDOException $e) {
        $error = "Error fetching post: " . $e->getMessage();
    }
}

// Fetch categories
try {
    $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching categories: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="/public/assets/css/post.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<div class="overlay">
    <div class="create-post-container">
        <button class="close-btn" onclick="closeDialog()">×</button>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        
        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form id="createPostForm" enctype="multipart/form-data">
            <!-- Hidden field for edit mode -->
            <?php if ($isEditMode): ?>
                <input type="hidden" name="post_id" value="<?= $postData['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required maxlength="255" 
                       value="<?= htmlspecialchars($postData['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Category (Optional)</label>
                <select id="category_id" name="category_id">
                    <option value="">No Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" 
                            <?= (isset($postData['category_id']) && $postData['category_id'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($postData['content'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Attach Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" class="submit-btn">
                <?= $isEditMode ? 'Update Post' : 'Create Post' ?>
            </button>
        </form>
    </div>
</div>

<script>
// Update your JavaScript to handle both create and update
document.getElementById('createPostForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "100",
        "timeOut": "1000",
        "extendedTimeOut": "100",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    try {
        const endpoint = formData.has('post_id') ? 
            '/src/controllers/update-post.php' : 
            '/src/controllers/create-post.php';
            
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        console.log("end point", endpoint);
        console.log("response", response);
        const responseText = await response.text();
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse JSON:', responseText);
            toastr.error('Invalid server response');
            return;
        }
        
        if (result.success) {
            toastr.success(`✅ ${result.message || 'Success!'}`);
            setTimeout(() => {
                if (formData.has('post_id')) {
                    // Redirect to 'My Posts' tab if it's an edit
                    window.location.href = 'http://localhost:8000/home?tab=my-posts';
                } else {
                    // Optionally redirect after creating a post too
                    window.location.href = 'http://localhost:8000/home?tab=my-posts';
                }
            }, 1000);
        } else {
            toastr.error('❌ An error occurred');
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = formData.has('post_id') ? 'Update Post' : 'Create Post';
    }
});

function closeDialog() {
    const overlay = document.querySelector('.overlay');
    overlay.style.display = 'none';
    // Optional: Clear form when closing
    document.getElementById('createPostForm').reset();
}
</script>
