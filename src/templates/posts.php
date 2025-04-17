<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';
?>

<link rel="stylesheet" href="/public/assets/css/modal.css">
<style>
.like-btn, .dislike-btn, .comment-btn {
    cursor: pointer;
}

.like-btn:hover, .dislike-btn:hover, .comment-btn:hover {
    opacity: 0.8;
}
</style>

<!-- Comment Modal -->
<div id="comment-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3 class="modal-title">Add Comment</h3>
        <div class="error-message"></div>
        <form id="comment-form" onsubmit="return false;">
            <textarea class="comment-textarea" placeholder="Write your comment here..."></textarea>
            <button type="submit" class="submit-comment">Post Comment</button>
        </form>
    </div>
</div>

<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<script>
const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

function handleLike(postId, type) {
    if (!isLoggedIn) {
        window.location.href = '/home?dialog=login';
        return;
    }
    // Call the existing like handling function
    fetch(`/src/controllers/like-post.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ post_id: postId, type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const countElement = type === 'like' ? 
                document.querySelector(`[data-post-id="${postId}"] .likes-count`) :
                document.querySelector(`[data-post-id="${postId}"] .dislikes-count`);
            if (countElement) {
                countElement.textContent = data.count;
            }
        }
    });
}

function showCommentModal(postId) {
    if (!isLoggedIn) {
        window.location.href = '/home?dialog=login';
        return;
    }
    const modal = document.getElementById('comment-modal');
    modal.style.display = 'block';
    modal.setAttribute('data-post-id', postId);
}

// Close modal when clicking the close button or outside the modal
document.querySelector('.close-modal').onclick = function() {
    document.getElementById('comment-modal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('comment-modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Handle comment submission
document.getElementById('comment-form').onsubmit = function() {
    const modal = document.getElementById('comment-modal');
    const postId = modal.getAttribute('data-post-id');
    const content = modal.querySelector('.comment-textarea').value;

    fetch('/src/controllers/add-comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ post_id: postId, content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const countElement = document.querySelector(`[data-post-id="${postId}"] .comments-count`);
            if (countElement) {
                countElement.textContent = data.count;
            }
            modal.style.display = 'none';
            modal.querySelector('.comment-textarea').value = '';
        } else {
            modal.querySelector('.error-message').textContent = data.message;
        }
    });
}
</script>

<?php

// Initialize Post model
$post = new Post($conn);
$result = $post->read();
$posts = $result->fetchAll(PDO::FETCH_ASSOC);

// Function to count likes/dislikes
function getLikesCount($conn, $post_id, $type) {
    $query = "SELECT COUNT(*) as count FROM likes WHERE blog_post_id = ? AND type = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$post_id, $type]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Function to count comments
function getCommentsCount($conn, $post_id) {
    $query = "SELECT COUNT(*) as count FROM comments WHERE blog_post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$post_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

// Function to format numbers
function formatNumber($number) {
    if ($number >= 1000) {
        return round($number/1000, 1) . 'K';
    }
    return $number;
}

    foreach ($posts as $post): 
        $likes_count = formatNumber(getLikesCount($conn, $post['id'], 'like'));
        $dislikes_count = formatNumber(getLikesCount($conn, $post['id'], 'dislike'));
        $comments_count = formatNumber(getCommentsCount($conn, $post['id']));
        $post_date = date('M d, Y', strtotime($post['created_at']));
    ?>
        <div class="article">
            <div class="article-content">
                <div class="article-project">
                    <img src="../../public/assets/img/profile.jpg" alt="Profile" class="profile-icon">
                    <span><?= htmlspecialchars($post['author_name']) ?></span>
                </div>
                
                <h2 class="article-title"><?= htmlspecialchars($post['title']) ?></h2>
                <p class="article-des"><?= htmlspecialchars(substr($post['content'], 0, 200)) . '...' ?></p>
                
                <div class="article-meta">
                    <span class="article-date"><?= htmlspecialchars($post_date) ?></span>
                    
                    <span class="article-stats like-btn" onclick="handleLike(<?= $post['id'] ?>, 'like')" data-post-id="<?= $post['id'] ?>">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                        <span class="likes-count"><?= $likes_count ?></span>
                    </span>
                    
                    <span class="article-stats dislike-btn" onclick="handleLike(<?= $post['id'] ?>, 'dislike')" data-post-id="<?= $post['id'] ?>">
                        <i class="bi bi-hand-thumbs-down-fill"></i>
                        <span class="dislikes-count"><?= $dislikes_count ?></span>
                    </span>

                    <span class="article-stats comment-btn" onclick="showCommentModal(<?= $post['id'] ?>)" data-post-id="<?= $post['id'] ?>">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span class="comments-count"><?= $comments_count ?></span>
                    </span>
                    
                    <div class="article-actions">
                        <i class="bi bi-three-dots"></i>
                    </div>
                </div>
            </div>
            
            <div class="comment-form-container" id="comment-form-<?= $post['id'] ?>" style="display: none;">
                <form onsubmit="handleComment(event, <?= $post['id'] ?>)" class="comment-form">
                    <textarea name="content" placeholder="Write your comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            </div>
            
            <div class="article-image">
                <?php if (isset($post['img_url'])): ?>
                    <img src="../public/uploads/<?= htmlspecialchars($post['img_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php else: ?>
                    <img src="../../../public/uploads/post1.jpeg" alt="Default post image">
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
