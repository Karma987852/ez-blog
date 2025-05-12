<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

$post = new Post($conn);
$titles = $post->getAllTitles();
?>

<div class="topics-container">
    <?php
    if (empty($titles)) {
        echo '<p>No topics found</p>';
    } else {
        foreach ($titles as $title) {
            echo '<div class="topics-tag">' . htmlspecialchars($title) . '</div>';
        }
    }
    ?>
</div>
