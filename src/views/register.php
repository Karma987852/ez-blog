<link rel="stylesheet" href="../../public/assets/css/register.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="dialog-container" id="dialog-container" style="display: block;">
    <div class="register-container">
        <div class="close-btn">
            <a href="/home" class="close-icon">&times;</a>
        </div>
        <h2>Register to BlogSphere</h2>
        <form id="registerForm" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself..." required></textarea>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" id="registerBtn">Register</button>
            <p>Already have an account? <a href="/home?dialog=login">Login here</a></p>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        
        $('#registerBtn').prop('disabled', true).html('Registering...');

        const formData = $(this).serialize();

        $.ajax({
            url: '/src/controllers/register.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                const result = JSON.parse(response);
                
                if (result.success) {
                    toastr.success(result.message);
                    
                    setTimeout(function() {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    toastr.error(result.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error("❌ An error occurred. Please try again.");
            },
            complete: function() {
                $('#registerBtn').prop('disabled', false).html('Register');
            }
        });
    });
});
</script>
