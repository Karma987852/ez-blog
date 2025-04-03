<?php
    $articles = [
        [
            'author' => 'N\'Delamiko Bey',
            'title' => 'Captureland: A Measured Meditation',
            'description' => '"Carry we go home, Carry we go home. And bring we gone a east. Cause man a rasta man. And rasta nuh live pon no capture land."...',
            'date' => 'Jan 11, 2030',
            'likes' => '1.6K',
            'dislikes' => '12',
            'comments' => '34',
            'image' => 'post1.jpeg'
        ],
        [
            'author' => 'N\'Delamiko Bey',
            'title' => 'Captureland: A Measured Meditation',
            'description' => '"Carry we go home, Carry we go home. And bring we gone a east. Cause man a rasta man. And rasta nuh live pon no capture land."...',
            'date' => 'Jan 11, 2030',
            'likes' => '1.6K',
            'dislikes' => '12',
            'comments' => '34',
            'image' => 'post1.jpeg'
        ]
    ];

    foreach ($articles as $article): ?>
        <div class="article">
            <div class="article-content">
                <div class="article-project">
                    <img src="../../public/assets/img/profile.jpg" alt="Profile" class="profile-icon">
                    <span><?= htmlspecialchars($article['author']) ?></span>
                </div>
                
                <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
                <p class="article-des"><?= htmlspecialchars($article['description']) ?></p>
                
                <div class="article-meta">
                    <span class="article-date"><?= htmlspecialchars($article['date']) ?></span>
                    
                    <span class="article-stats">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                        <span><?= htmlspecialchars($article['likes']) ?></span>
                    </span>
                    
                    <span class="article-stats">
                        <i class="bi bi-hand-thumbs-down-fill"></i>
                        <span><?= htmlspecialchars($article['dislikes']) ?></span>
                    </span>

                    <span class="article-stats">
                        <i class="bi bi-chat-square-fill"></i>
                        <span><?= htmlspecialchars($article['comments']) ?></span>
                    </span>
                    
                    <div class="article-actions">
                        <i class="bi bi-three-dots"></i>
                    </div>
                </div>
            </div>
            
            <div class="article-image">
                <img src="../../public/uploads/<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
            </div>
        </div>
    <?php endforeach; ?>
