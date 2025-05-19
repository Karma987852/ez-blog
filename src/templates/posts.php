<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';
?>

<link rel="stylesheet" href="/public/assets/css/modal.css">
<?php include 'comment-modal.php'; ?>

<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<script>
const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

function showCommentModal(postId) {
    if (!isLoggedIn) {
        window.location.href = '/home?dialog=login';
        return;
    }
    
    const modal = document.getElementById('comment-modal');
    modal.style.display = 'block';
    modal.setAttribute('data-post-id', postId);
    modal.querySelector('.error-message').style.display = 'none';
    fetchComments(postId);
}

function formatCommentDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        year: 'numeric'
    });
}

function handleLike(postId, type) {
    if (!isLoggedIn) {
        window.location.href = '/home?dialog=login';
        return;
    }

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
            const articleElement = document.querySelector(`[data-post-id="${postId}"]`).closest('.article');
            const likesCountEl = articleElement.querySelector('.likes-count');
            const dislikesCountEl = articleElement.querySelector('.dislikes-count');

            if (likesCountEl) likesCountEl.textContent = data.likes_count;
            if (dislikesCountEl) dislikesCountEl.textContent = data.dislikes_count;
                
            if (countElement) {
                countElement.textContent = data.count;
            }
        }
    });
}

document.querySelector('.close-modal').onclick = function() {
    document.getElementById('comment-modal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('comment-modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function fetchComments(postId) {
    const modal = document.getElementById('comment-modal');
    const commentsContainer = modal.querySelector('.comments-container');
    
    commentsContainer.innerHTML = '<p>Loading comments...</p>';
    
    fetch(`/src/controllers/get-comments.php?post_id=${postId}`)
        .then(response => response.json())
        .then(data => {
            console.log("Fetched comments:", data);
            commentsContainer.innerHTML = '';
            
            if (data.success && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentItem = document.createElement('div');
                    commentItem.className = 'comment-item';
                    commentItem.innerHTML = `
                        <img src="${comment.profile_pic || '/public/assets/img/profile.jpg'}" 
                             alt="Profile" class="comment-user-avatar">
                        <div class="comment-content">
                            <div class="comment-user-name">${comment.username}</div>
                            <div class="comment-text">${comment.comment}</div>
                            <div class="comment-date">${formatCommentDate(comment.created_at)}</div>
                        </div>
                    `;
                    commentsContainer.appendChild(commentItem);

                    commentItem.scrollIntoView({ behavior: 'smooth' });
                });
            } else {
                commentsContainer.innerHTML = '<p style="text-align: center; color: #777;">No comments yet. Be the first to comment!</p>';
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            commentsContainer.innerHTML = '<p style="color: #f00;">Error loading comments. Please try again.</p>';
        });
}

document.getElementById('comment-form').onsubmit = function(e) {
    e.preventDefault();
    
    const modal = document.getElementById('comment-modal');
    const postId = modal.getAttribute('data-post-id');
    const content = modal.querySelector('.comment-textarea').value.trim();
    
    if (!content) {
        modal.querySelector('.error-message').textContent = 'Please enter a comment';
        modal.querySelector('.error-message').style.display = 'block';
        return;
    }
    
    modal.querySelector('.error-message').style.display = 'none';
    
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
                const currentCount = parseInt(countElement.textContent) || 0;
                countElement.textContent = currentCount + 1;
            }
            
            modal.querySelector('.comment-textarea').value = '';
            
            fetchComments(postId);
        } else {
            modal.querySelector('.error-message').textContent = data.message || 'Failed to post comment';
            modal.querySelector('.error-message').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error posting comment:', error);
        modal.querySelector('.error-message').textContent = 'Failed to post comment. Please try again.';
        modal.querySelector('.error-message').style.display = 'block';
    });
};
</script>
<?php

$post = new Post($conn);
$result = $post->read();
$posts = $result->fetchAll(PDO::FETCH_ASSOC);

function getLikesCount($conn, $post_id, $type) {
    $query = "SELECT COUNT(*) as count FROM likes WHERE blog_post_id = ? AND type = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$post_id, $type]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

function getCommentsCount($conn, $post_id) {
    $query = "SELECT COUNT(*) as count FROM comments WHERE blog_post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$post_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

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
                
                <a href="/post?id=<?= $post['id']?>"><h2 class="article-title"><?= htmlspecialchars($post['title']) ?></h2></a>
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
                    <img src="/public/uploads/<?= htmlspecialchars($post['img_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php else: ?>
                    <img src="../../../public/uploads/post1.jpeg" alt="Default post image">
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
