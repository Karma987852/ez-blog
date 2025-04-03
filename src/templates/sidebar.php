<div class="sidebar">
    <div class="sidebar-section">
        <h3>Profile</h3>
        <div class="profile">
            <img src="../../public/assets/img/profile.jpg" />    
            <p>Username</p> 
            <p>Motivated Ruby on Rails Developer eager to expand my knowledge and enhance my skills for continuous personal and professional growth.</p>
        </div>
    </div>
                
    <div class="sidebar-section">
        <h3>Top Contributors</h3>
        <div class="people-list">
            <?php
                $people = [
                   [
                    'name' => 'Coinbase',
                    'description' => 'Our mission is to increase economic freedom in the...',
                    'avatar' => 'coinbase-avatar.jpg',
                    'verified' => false
                    ],
                    [
                    'name' => 'Fast Company',
                    'description' => 'Official Medium account for the Fast Company business...',
                    'avatar' => 'fastcompany-avatar.jpg',
                    'verified' => false
                    ],
                    [
                    'name' => 'Cory Doctorow',
                    'description' => 'Writer, blogger, activist. Blog: https://pluralistic.net; Mailing...',
                    'avatar' => 'cory-avatar.jpg',
                    'verified' => true
                    ]
                ];

                foreach ($people as $person): ?>
                    <div class="person">
                        <div class="person-info">
                            <img src="../../public/assets/img/profile.jpg" 
                                alt="<?= htmlspecialchars($person['name']) ?>" 
                                class="person-avatar">
                            <div class="person-details">
                                <div class="person-name">
                                    <?= htmlspecialchars($person['name']) ?>
                                </div>
                                <div class="person-description"><?= htmlspecialchars($person['description']) ?></div>
                            </div>
                        </div>
                        <button class="follow-btn">View</button>
                    </div>
                <?php endforeach; ?>
        </div>
    </div>
</div>
