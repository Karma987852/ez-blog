<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /home?dialog=login');
    exit();
}

$database = new Database();
$db = $database->connect();
$post = new Post($db);
$result = $post->readByUserId($_SESSION['user_id']);
?>

<link rel="stylesheet" href="/public/assets/css/my-posts.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="my-post-container">    
    <?php if($result->rowCount() > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-6 mb-5" data-post-id="<?= $row['id'] ?>">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="card-text"><?= substr(htmlspecialchars($row['content']), 0, 150) . '...' ?></p>
                            <div class="text-muted small">
                                <span>Category: <?= htmlspecialchars($row['category_name']) ?></span><br>
                                <span>Posted: <?= date('F j, Y', strtotime($row['created_at'])) ?></span><br><br>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/post?id=<?= $row['id'] ?>" class="btn btn-more btn-sm">Read More</a>
                            <a href="/home?dialog=createPost&edit=<?= $row['id'] ?>" class="btn btn-edit btn-sm">Edit</a>
                            <button class="btn btn-delete btn-sm delete-btn" data-post-id="<?= $row['id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You haven't created any posts yet. <a href="/create-post.php">Create your first post!</a>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this post? This action cannot be undone.</p>
        <div class="modal-actions">
            <button id="confirmDelete" class="btn btn-delete">Delete</button>
            <button id="cancelDelete" class="btn btn-edit">Cancel</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    const closeModal = document.querySelector('.close-modal');
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "100",
        "timeOut": "1000",
        "extendedTimeOut": "100",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    
    let postIdToDelete = null;

    // Show modal when delete button is clicked
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            postIdToDelete = this.getAttribute('data-post-id');
            modal.style.display = 'block';
        });
    });

    // Handle delete confirmation
    confirmBtn.addEventListener('click', function() {
        if (postIdToDelete) {
            fetch('/src/controllers/delete-post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                id: postIdToDelete
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-post-id="${postIdToDelete}"]`).remove();
                    toastr.success('✅ Post deleted successfully!');
                } else {
                    toastr.error('❌ ' + data.message);
                }
                modal.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                modal.style.display = 'none';
            });
        }
    });

    // Close modal handlers
    cancelBtn.addEventListener('click', () => modal.style.display = 'none');
    closeModal.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>
