<?php
$user = [
    "username" => "Sonam Dorji",
    "email" => "sonam@gmail.com",
    "bio" => "A sample bio can vary depending on its purpose, but generally, it should introduce the individual, highlight their relevant skills and experience, and optionally include personal interests or goals.",
    "created_at" => "24-12-2024",
    "profile_img" => "../../public/assets/img/profile.jpg"
];
?>

<link rel="stylesheet" href="/public/assets/css/user.css">
<div class="profile-container">        
    <div id="profileModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <div class="profile-header">
                <img src="<?php echo $user['profile_img']; ?>" alt="Profile Image" class="profile-img">
                <h2><?php echo $user['username']; ?></h2>
                <p class="email"><?php echo $user['email']; ?></p>
            </div>
            <div class="profile-body">
                <div class="bio-section">
                    <h3>About</h3>
                    <p><?php echo $user['bio']; ?></p>
                </div>
                <div class="meta-section">
                    <p><strong>Member since:</strong> <?php echo $user['created_at']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Get the modal
    const modal = document.getElementById("profileModal");
    const span = document.getElementsByClassName("close-btn")[0];

    // When the user clicks on (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
