// Handle authentication-based interactions
class AuthDialog {
    constructor() {
        this.dialogHTML = `
            <div class="auth-dialog" id="authDialog">
                <div class="auth-dialog-content">
                    <h2>Login Required</h2>
                    <p>Please login to continue</p>
                    <div class="auth-dialog-buttons">
                        <button onclick="window.location.href='/ez-blog/login'" class="btn-primary">Login</button>
                        <button onclick="authDialog.close()" class="btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        `;
        this.init();
    }

    init() {
        if (!document.getElementById('authDialog')) {
            document.body.insertAdjacentHTML('beforeend', this.dialogHTML);
        }
        this.dialog = document.getElementById('authDialog');

        // Handle unauthorized interactions
        document.addEventListener('click', (e) => {
            if (!window.isLoggedIn) {
                if (e.target.matches('.write-btn, .like-btn, .comment-btn, .comment-form *')) {
                    e.preventDefault();
                    this.show();
                }
            }
        });
    }

    show() {
        this.dialog.style.display = 'flex';
    }

    close() {
        this.dialog.style.display = 'none';
    }
}

// Initialize auth dialog
const authDialog = new AuthDialog();

// Add styles
const styles = `
    .auth-dialog {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .auth-dialog-content {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        max-width: 400px;
        width: 90%;
    }

    .auth-dialog-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .btn-primary {
        background: #007bff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = styles;
document.head.appendChild(styleSheet);