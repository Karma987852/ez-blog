<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Get user's posts
$database = new Database();
$db = $database->connect();
$post = new Post($db);
$result = $post->readByUserId($_SESSION['user_id']);
?>

<div class="container my-4">
    <h2 class="mb-4">My Blog Posts</h2>
    
    <?php if($result->rowCount() > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($row['content']), 0, 150) . '...'; ?></p>
                            <div class="text-muted small">
                                <span>Category: <?php echo htmlspecialchars($row['category_name']); ?></span><br>
                                <span>Posted: <?php echo date('F j, Y', strtotime($row['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                            <a href="/edit-post.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You haven't created any posts yet. <a href="/create-post.php">Create your first post!</a>
        </div>
    <?php endif; ?>
</div>
