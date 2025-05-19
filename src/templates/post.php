<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="/public/assets/css/post.css">
</head>
<body>
    <div class="post-container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p><em>Published on <?= date('F j, Y', strtotime($post['created_at'])) ?></em></p>
        <p><em>Published by <?= htmlspecialchars($post['username']) ?></em></p>
        <p><em>Category: <?= htmlspecialchars($post['name']) ?></em></p>
        <?php if (!empty($post['img_url'])): ?>
            <img src="/public/uploads/<?= htmlspecialchars($post['img_url']) ?>" alt="Post image">
        <?php endif; ?>
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <a href="/">← Back to Home</a>
    </div>
</body>
</html>
