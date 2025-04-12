<link rel="stylesheet" href="../../public/assets/css/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="login-container">
    <div class="close-btn">
        <a href="/home" class="close-icon">&times;</a>
    </div>
    <h2>Login to BlogSphere</h2>
    <form id="loginForm" action="../controllers/login.php" method="POST">
        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="button-group">
            <button type="submit" class="login-btn">Login</button>
            <a href="/home?dialog=register" class="register-btn">Register</a>
        </div>
    </form>
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

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        $('.login-btn').prop('disabled', true).html('Logging in...');

        const formData = $(this).serialize();

        $.ajax({
            url: '/src/controllers/login.php',
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
                $('.login-btn').prop('disabled', false).html('Login');
            }
        });
    });
});
</script>
