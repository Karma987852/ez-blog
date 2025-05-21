<!DOCTYPE html>
<html>
<head>
    <title><?= isset($post['title']) ? htmlspecialchars($post['title']) : 'Untitled Post' ?></title>
    <link rel="stylesheet" href="/ez-blog/public/assets/css/post.css">
</head>
<body>
    <div class="post-container">
        <h1><?= isset($post['title']) ? htmlspecialchars($post['title']) : 'Untitled Post' ?></h1>

        <p><em>Published on 
            <?php 
                if (!empty($post['created_at'])) {
                    echo date('F j, Y', strtotime($post['created_at']));
                } else {
                    echo "Unknown date";
                }
            ?>
        </em></p>

        <p><em>Published by <?= isset($post['username']) ? htmlspecialchars($post['username']) : 'Unknown author' ?></em></p>

        <p><em>Category: <?= isset($post['name']) ? htmlspecialchars($post['name']) : 'Uncategorized' ?></em></p>

        <div class="post-content">
            <?= isset($post['content']) ? nl2br(htmlspecialchars($post['content'])) : '<em>No content available.</em>' ?>
        </div>

        <?php if (!empty($post['img_url'])): ?>
            <img src="/ez-blog/public/uploads/<?= htmlspecialchars($post['img_url']) ?>" alt="Post image">
        <?php endif; ?>

        <a href="/ez-blog/home">← Back to Home</a>
    </div>
</body>
</html>
