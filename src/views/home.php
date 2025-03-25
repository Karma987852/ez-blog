<?php 
include './src/controllers/get-posts.php';
include './templates/header.php'; 
?>

<style>
    .blog-posts {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .blog-post {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
    }

    .blog-post:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .post-title {
        font-size: 1.5rem;
        color: #0C68F4;
        margin-bottom: 10px;
    }

    .post-meta {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 10px;
    }

    .post-content {
        font-size: 1rem;
        color: #333;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .read-more {
        display: inline-block;
        text-decoration: none;
        color: white;
        background-color: #F45E0C;
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .read-more:hover {
        background-color: #d04e0b;
    }
</style>

<main>
    <div class="container">
        <h1>All Blog Posts</h1>

        <?php if (!empty($posts)): ?>
            <div class="blog-posts">
                <?php foreach ($posts as $post): ?>
                    <div class="blog-post">
                        <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="post-meta">
                            By <strong><?php echo htmlspecialchars($post['author']); ?></strong> |
                            Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?> | 
                            Category: <?php echo htmlspecialchars($post['category']); ?>
                        </p>
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
