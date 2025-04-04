<div class="topics-container">
        <?php
        $names = [
            "Sonam",
            "Sonam Kapoor",
            "Sonam Bajwa",
            "Sonam Wangchuk",
            "Sonamarg",
            "Sonambulismo",
            "Sonamhotphotoshoot",
            "Sonamhotphotos",
            "Sonambralessphotosshoot",
            "Sonam Makeup Studio",
            "Sonam Kapoor Hot Images",
            "Sonam Kapoor Bikni Photos",
            "Sonam Kapoor Hot Photos",
            "Sonam Kapoor Bikni Images",
            "Sonam Gupta",
            "Sonam Bajwa Biography",
            "Sonamwangchuck",
            "Sonamasoori",
            "Sonámbula",
            "Sonamlotus",
            "Sonamiller",
            "Sonambulo",
            "Sonambient"
        ];

        foreach ($names as $name) {
            echo '<div class="topics-tag">' . htmlspecialchars($name) . '</div>';
        }
        ?>
    </div>
