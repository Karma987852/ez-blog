<div class="sidebar">
                <div class="sidebar-section">
                    <h3>Topics matching c</h3>
                    <div class="topic-tags">
                        <?php
                        $topics = ['C', 'Cryptocurrency', 'Crypto', 'Culture', 'Creativity', 'Covid-19'];
                        foreach ($topics as $topic) {
                            echo '<a href="#" class="topic-tag">' . $topic . '</a>';
                        }
                        ?>
                        <a href="#" class="see-all">See all</a>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h3>People matching c</h3>
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

                        foreach ($people as $person) {
                            echo '<div class="person">';
                            echo '<div class="person-info">';
                            echo '<img src="' . $person['avatar'] . '" alt="' . $person['name'] . '" class="person-avatar">';
                            echo '<div class="person-details">';
                            echo '<div class="person-name">' . $person['name'];
                            if ($person['verified']) {
                                echo ' <img src="verified-icon.svg" alt="Verified" class="verified-icon">';
                            }
                            echo '</div>';
                            echo '<div class="person-description">' . $person['description'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<button class="follow-btn">Follow</button>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>