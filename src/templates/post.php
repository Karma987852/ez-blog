<!-- /home/rma/ezblog/src/views/post.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p><em>Published on <?= date('F j, Y', strtotime($post['created_at'])) ?></em></p>
        <div>
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <a href="/">← Back to Home</a>
    </div>
</body>
</html>
