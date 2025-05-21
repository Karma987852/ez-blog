<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';
// session_start();

$isLoggedIn = isset($_SESSION['user_id']);

$postModel = new Post($conn);
$result = $postModel->read();
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
    return ($number >= 1000) ? round($number / 1000, 1) . 'K' : $number;
}
?>

<link rel="stylesheet" href="/ez-blog/public/assets/css/modal.css">
<?php include 'comment-modal.php'; ?>

<script>
const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

function showCommentModal(postId) {
    if (!isLoggedIn) {
        window.location.href = '/ez-blog/home?dialog=login';
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
    const diff = Math.floor((now - date) / 1000);

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
    if (diff < 604800) return `${Math.floor(diff / 86400)} days ago`;

    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function handleLike(postId, type) {
    if (!isLoggedIn) {
        window.location.href = '/ez-blog/home?dialog=login';
        return;
    }

    fetch(`/ez-blog/src/controllers/like-post.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId, type })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const article = document.querySelector(`[data-post-id="${postId}"]`).closest('.article');
            article.querySelector('.likes-count').textContent = data.likes_count;
            article.querySelector('.dislikes-count').textContent = data.dislikes_count;
        }
    });
}

document.querySelector('.close-modal').onclick = () => {
    document.getElementById('comment-modal').style.display = 'none';
};

window.onclick = event => {
    const modal = document.getElementById('comment-modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};

function fetchComments(postId) {
    const modal = document.getElementById('comment-modal');
    const container = modal.querySelector('.comments-container');

    container.innerHTML = '<p>Loading comments...</p>';

    fetch(`/ez-blog/src/controllers/get-comments.php?post_id=${postId}`)
    .then(res => res.json())
    .then(data => {
        container.innerHTML = '';
        if (data.success && data.comments.length > 0) {
            data.comments.forEach(comment => {
                const item = document.createElement('div');
                item.className = 'comment-item';
                item.innerHTML = `
                    <img src="${comment.profile_pic || '/ez-blog/public/assets/img/profile.jpg'}" class="comment-user-avatar" alt="User">
                    <div class="comment-content">
                        <div class="comment-user-name">${comment.username}</div>
                        <div class="comment-text">${comment.comment}</div>
                        <div class="comment-date">${formatCommentDate(comment.created_at)}</div>
                    </div>`;
                container.appendChild(item);
                item.scrollIntoView({ behavior: 'smooth' });
            });
        } else {
            container.innerHTML = '<p style="text-align: center; color: #777;">No comments yet. Be the first to comment!</p>';
        }
    })
    .catch(err => {
        console.error('Error:', err);
        container.innerHTML = '<p style="color:red;">Error loading comments.</p>';
    });
}

document.getElementById('comment-form').onsubmit = function (e) {
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

    fetch('/ez-blog/src/controllers/add-comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ post_id: postId, content })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const countEl = document.querySelector(`[data-post-id="${postId}"] .comments-count`);
            if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
            modal.querySelector('.comment-textarea').value = '';
            fetchComments(postId);
        } else {
            modal.querySelector('.error-message').textContent = data.message || 'Failed to post comment';
            modal.querySelector('.error-message').style.display = 'block';
        }
    })
    .catch(error => {
        console.error(error);
        modal.querySelector('.error-message').textContent = 'Failed to post comment.';
        modal.querySelector('.error-message').style.display = 'block';
    });
};
</script>

<?php foreach ($posts as $post): 
    $likes_count = formatNumber(getLikesCount($conn, $post['id'], 'like'));
    $dislikes_count = formatNumber(getLikesCount($conn, $post['id'], 'dislike'));
    $comments_count = formatNumber(getCommentsCount($conn, $post['id']));
    $post_date = date('M d, Y', strtotime($post['created_at']));
?>
    <div class="article">
        <div class="article-content">
            <div class="article-project">
                <img src="/ez-blog/public/assets/img/profile.jpg" alt="Profile" class="profile-icon">
                <span><?= htmlspecialchars($post['author_name']) ?></span>
            </div>

            <a href="/ez-blog/post?id=<?= $post['id'] ?>">
                <h2 class="article-title"><?= htmlspecialchars($post['title']) ?></h2>
            </a>
            <p class="article-des"><?= htmlspecialchars(substr($post['content'], 0, 200)) . '...' ?></p>

            <div class="article-meta">
                <span class="article-date"><?= $post_date ?></span>

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

        <div class="article-image">
            <?php if (!empty($post['img_url'])): ?>
                <img src="/ez-blog/public/uploads/<?= htmlspecialchars($post['img_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
            <?php else: ?>
                <img src="/ez-blog/public/uploads/post1.jpeg" alt="Default image">
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
