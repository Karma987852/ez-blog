// Function to handle likes and dislikes
async function handleLike(postId, type) {
    try {
        const response = await fetch('/src/controllers/like-post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: postId, type: type })
        });

        const data = await response.json();
        
        if (data.error) {
            if (response.status === 401) {
                alert('Please log in to like posts');
            } else {
                alert(data.error);
            }
            return;
        }

        // Update the like/dislike counts
        const articleElement = document.querySelector(`[data-post-id="${postId}"]`).closest('.article');
        articleElement.querySelector('.likes-count').textContent = data.likes_count;
        articleElement.querySelector('.dislikes-count').textContent = data.dislikes_count;

    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request');
    }
}

// Modal functionality
const modal = document.getElementById('comment-modal');
const closeBtn = document.querySelector('.close-modal');
let currentPostId = null;

// Close modal when clicking the close button or outside the modal
closeBtn.onclick = () => modal.style.display = 'none';
window.onclick = (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

// Function to show comment modal
function showCommentModal(postId) {
    currentPostId = postId;
    modal.style.display = 'block';
    document.querySelector('.comment-textarea').value = '';
    document.querySelector('.error-message').style.display = 'none';
}

// Function to handle comment submission
async function handleComment(event) {
    event.preventDefault();
    
    const textarea = document.querySelector('.comment-textarea');
    const content = textarea.value.trim();
    const errorMessage = document.querySelector('.error-message');

    if (!content) {
        errorMessage.textContent = 'Please write a comment first';
        errorMessage.style.display = 'block';
        return;
    }

    try {
        const response = await fetch('/src/controllers/add-comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ post_id: currentPostId, content: content })
        });

        const data = await response.json();

        if (data.error) {
            errorMessage.textContent = data.error;
            errorMessage.style.display = 'block';
            if (response.status === 401) {
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 2000);
            }
            return;
        }

        // Update the comment count
        const articleElement = document.querySelector(`[data-post-id="${currentPostId}"]`).closest('.article');
        articleElement.querySelector('.comments-count').textContent = data.comments_count;

        // Clear the form and close modal
        textarea.value = '';
        modal.style.display = 'none';

        // Show success message
        alert('Comment posted successfully!');

    } catch (error) {
        console.error('Error:', error);
        errorMessage.textContent = 'An error occurred while posting your comment';
        errorMessage.style.display = 'block';
    }
}

// Add event listener to comment form
document.getElementById('comment-form').addEventListener('submit', handleComment);