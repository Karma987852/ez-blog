<?php
require_once __DIR__ . '/../config/database.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /home?dialog=login');
    exit();
}

$database = new Database();
$db = $database->connect();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
        $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
        
        $query = "INSERT INTO blog_posts (title, content, user_id, category_id) 
                  VALUES (:title, :content, :user_id, :category_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => "Blog post created successfully!"]);
        } else {
            echo json_encode(["error" => "Failed to create post"]);
        }
    } catch(PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }
    exit();
}

// Fetch categories for dropdown
try {
    $categories = $db->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
} catch(PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<link rel="stylesheet" href="/public/assets/css/post.css">

<div class="overlay">
<div class="create-post-container">
<button class="close-btn" onclick="closeDialog()">×</button>
    <h1>Create New Blog Post</h1>
    
    <?php if (isset($success)): ?>
        <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form id="createPostForm">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required maxlength="255">
    </div>
    
    <div class="form-group">
        <label for="content">Content</label>
        <textarea id="content" name="content" rows="10" required></textarea>
    </div>

    <div class="form-group">
        <label for="category_id">Category (Optional)</label>
        <select id="category_id" name="category_id">
            <option value="">No Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
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

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        const messageBox = document.getElementById('responseMessage');
        if (result.success) {
            messageBox.innerHTML = `<div class="alert success">${result.success}</div>`;
            setTimeout(closeDialog, 1500);
        } else {
            messageBox.innerHTML = `<div class="alert error">${result.error}</div>`;
        }
    } catch (error) {
        alert('Failed to submit: ' + error);
    }
});

function closeDialog() {
    const dialog = document.querySelector('.create-post-container');
    dialog.style.display = 'none';
}
</script>
