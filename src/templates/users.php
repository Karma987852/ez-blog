<?php
// Dummy users data array
$users = [
    [
        'name' => 'Sonam Wangmo (Peggy)',
        'bio' => 'I am a first-generation immigrant, writer, and advocate for social justice. Living in NYC, I explore themes of race, immigration, and identity through my work.',
        'avatar' => '../../public/assets/img/profile.jpg'
    ],
    [
        'name' => 'Alex Johnson',
        'bio' => 'Tech enthusiast and web developer with a passion for creating accessible digital experiences. Based in San Francisco.',
        'avatar' => '../../public/assets/img/profile.jpg'
    ],
    [
        'name' => 'Maria Garcia',
        'bio' => 'Environmental scientist focused on sustainable urban development. Loves hiking and photography in my free time.',
        'avatar' => '../../public/assets/img/profile.jpg'
    ],
    [
        'name' => 'James Wilson',
        'bio' => 'High school teacher specializing in history and political science. Believer in the power of education to change lives.',
        'avatar' => '../../public/assets/img/profile.jpg'
    ],
    [
        'name' => 'Fatima Ahmed',
        'bio' => 'Medical researcher working on infectious diseases. Passionate about global health equity and science communication.',
        'avatar' => '../../public/assets/img/profile.jpg'
    ]
];
?>

<div class="users-class-container">
    <?php foreach ($users as $user): ?>
        <div class="users-class-user-card">
            <div class="users-class-user-info">
                <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="users-class-avatar">
                <div class="users-class-user-details">
                    <h2 class="users-class-user-name"><?= htmlspecialchars($user['name']) ?></h2>
                    <p class="users-class-user-bio"><?= htmlspecialchars($user['bio']) ?></p>
                </div>
            </div>
            <button class="users-class-follow-btn">View</button>
        </div>
    <?php endforeach; ?>
</div>
