<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSphere</title>
    <link rel="stylesheet" href="/ez-blog/public/assets/css/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include __DIR__ . '/../templates/header.php'; ?>
    <main>
        <?php
            
            $currentDialog = $_GET['dialog'] ?? '';
            $currentTab = $_GET['tab'] ?? 'posts';
            
        ?>
        
        <div class="dialog-container" id="dialog-container" style="<?= $currentDialog ? 'display: block;' : 'display: none;' ?>">
            <?php
                switch ($currentDialog) {
                    case 'login':
                        include __DIR__ . '/../views/login.php';
                        break;
                    case 'register':
                        include __DIR__ . '/../views/register.php';
                        break;
                    case 'user':
                        include __DIR__ . '/../templates/user.php';
                        break;
                    case 'user':
                        if (isset($_GET['id'])) {
                            include __DIR__ . '/../templates/user.php';
                        } else {
                            header('Location: /home');
                            exit;
                        }
                        break;
                    case 'createPost':
                        include __DIR__ . '/../templates/create-post.php';
                        break;
                    default:
                        // No dialog or unknown dialog
                }
            ?>
        </div>

        <div class="container">
            <div class="content">
                <div class="tabs">
                    <?php
                    $tabs = array(
                        'posts' => 'Posts',
                        'my-posts' => 'My Posts', 
                        'users' => 'Users',
                        'topics' => 'Topics'
                    );
                    
                    foreach ($tabs as $tabId => $tabTitle): ?>
                        <a href="/ez-blog/home?tab=<?= $tabId ?>"
                           class="tab <?= $currentTab === $tabId ? 'active' : '' ?>">
                            <?= htmlspecialchars($tabTitle) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="search-results">
                    <?php
                        switch ($currentTab) {
                            case 'posts':
                                include __DIR__ . '/../templates/posts.php';
                                break;
                            case 'my-posts':
                                include __DIR__ . '/../templates/my-posts.php';
                                break;
                            case 'users':
                                include __DIR__ . '/../templates/users.php';
                                break;
                            case 'topics':
                                include __DIR__ . '/../templates/topics.php';
                                break;
                            case 'contact':
                                include __DIR__ . '/../templates/contact.php';
                                break;
                            case 'login':
                                include __DIR__ . '/../views/login.php';
                                break;
                            default:
                                include __DIR__ . '/../views/404.php';
                        }
                    ?>
                </div>   
            </div>
            <?php include __DIR__ . '/../templates/sidebar.php'; ?>
        </div>
    </main>
</body>
</html>
