<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSphere</title>
    <link rel="stylesheet" href="../../public/assets/css/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>
    <main>
        <div class="container">
            <div class="content">
                <div class="tabs">
                    <a href="#" class="tab active">Stories</a>
                    <a href="#" class="tab">People</a>
                    <a href="#" class="tab">Publications</a>
                    <a href="#" class="tab">Topics</a>
                    <a href="#" class="tab">Lists</a>
                </div>
                
                <div class="search-results">
                    <?php
                    $articles = [
                        [
                            'project' => 'In The Sunhead Project',
                            'author' => 'N\'Delamiko Bey',
                            'title' => 'Captureland: A Measured Meditation',
                            'excerpt' => '"Carry we go home, Carry we go home. And bring we gone a east. Cause man a rasta man. And rasta nuh live pon no capture land."...',
                            'date' => 'Jan 11, 2030',
                            'claps' => '1.6K',
                            'comments' => '34',
                            'image' => 'captureland.jpg'
                        ],
                        [
                            'author' => 'Dylan Combellick',
                            'title' => 'Ukraine Update April 1',
                            'excerpt' => '2025—No Fools',
                            'date' => '23h ago',
                            'claps' => '3.5K',
                            'comments' => '17',
                            'image' => 'ukraine.jpg',
                            'featured' => true
                        ]
                    ];

                    foreach ($articles as $article) {
                        echo '<div class="article">';
                        
                        echo '<div class="article-content">';
                        
                        if (isset($article['project'])) {
                            echo '<div class="article-project">';
                            echo '<img src="project-icon.svg" alt="Project" class="project-icon">';
                            echo '<span>' . $article['project'] . ' by ' . $article['author'] . '</span>';
                            echo '</div>';
                        } else {
                            echo '<div class="article-author">';
                            echo '<img src="author-avatar.jpg" alt="Author" class="author-avatar">';
                            echo '<span>' . $article['author'] . '</span>';
                            echo '</div>';
                        }
                        
                        echo '<h2 class="article-title">' . $article['title'] . '</h2>';
                        echo '<p class="article-excerpt">' . $article['excerpt'] . '</p>';
                        
                        echo '<div class="article-meta">';
                        
                        if (isset($article['featured']) && $article['featured']) {
                            echo '<span class="featured-indicator">★</span>';
                        }
                        
                        echo '<span class="article-date">' . $article['date'] . '</span>';
                        
                        echo '<span class="article-stats">';
                        echo '<img src="clap-icon.svg" alt="Claps" class="clap-icon">';
                        echo '<span>' . $article['claps'] . '</span>';
                        echo '</span>';
                        
                        echo '<span class="article-stats">';
                        echo '<img src="comment-icon.svg" alt="Comments" class="comment-icon">';
                        echo '<span>' . $article['comments'] . '</span>';
                        echo '</span>';
                        
                        echo '<div class="article-actions">';
                        echo '<button class="save-btn"><img src="save-icon.svg" alt="Save"></button>';
                        echo '<button class="more-btn"><img src="more-icon.svg" alt="More"></button>';
                        echo '</div>';
                        
                        echo '</div>'; // End article-meta
                        echo '</div>'; // End article-content
                        
                        echo '<div class="article-image">';
                        echo '<img src="' . $article['image'] . '" alt="' . $article['title'] . '">';
                        echo '</div>';
                        
                        echo '</div>'; // End article
                    }
                    ?>
                </div>
            </div>
            
            <?php include __DIR__ . '/../templates/sidebar.php'; ?>
        </div>
    </main>
</body>
</html>
