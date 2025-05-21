<div id="comment-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3 class="modal-title">Comments</h3>
        <div class="error-message"></div>
        
        <!-- Scrollable Comments Container -->
        <div class="comments-scroll-container">
            <div class="comments-container">
                <!-- Comments will be loaded here dynamically -->
            </div>
        </div>
        
        <!-- Fixed Comment Form -->
        <form id="comment-form" onsubmit="return false;" class="add-comment-form">
            <div class="comment-input-container">
                <?php if ($isLoggedIn): ?>
                    <img src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? '/ez-blog/public/assets/img/profile.jpg') ?>" 
                         alt="Profile" class="comment-user-avatar">
                <?php endif; ?>
                <textarea class="comment-textarea" placeholder="Write your comment here..." required></textarea>
            </div>
            <br>
            <button type="submit" class="submit-comment">Post Comment</button>
        </form>
    </div>
</div>
