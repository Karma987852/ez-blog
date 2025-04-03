<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSphere</title>
    <link rel="stylesheet" href="../../public/assets/css/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>
    <main>
        <div class="container">
            <div class="content">
                <div class="tabs">
                    <a href="#" class="tab active">Posts</a>
                    <a href="#" class="tab">My Posts</a>
                    <a href="#" class="tab">Users</a>
                    <a href="#" class="tab">Topics</a>
                    <a href="#" class="tab">Contact Us</a>
                </div>
                
                 <div class="search-results">
                    <!-- Show base on the tab -->
                    <?php include __DIR__ . '/../templates/posts.php'; ?>
                </div>   
            </div>
            <?php include __DIR__ . '/../templates/sidebar.php'; ?>
        </div>
    </main>
</body>
</html>
