<?php 
include './src/controllers/get-posts.php';
include './templates/header.php'; 
?>

<main>
    <div class="container">
        <h1>All Blog Posts</h1>

        <?php if (!empty($posts)): ?>
            <div class="blog-posts">
                <?php foreach ($posts as $post): ?>
                    <div class="blog-post">
                        <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="post-meta">Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?> | Category: <?php echo htmlspecialchars($post['category']); ?></p>
                        <p class="post-content"><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>
                        <a href="single-post.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No blog posts available.</p>
        <?php endif; ?>
    </div>
</main>

<?php include './templates/footer.php'; ?>
