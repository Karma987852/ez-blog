<?php
require './config/database.php';  // Include database.php

// Check if $conn is initialized
if (!$conn) {
    die("❌ Database connection failed: Connection is null.");
}

// Fetch all blog posts from the database
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC");
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all posts as an associative array
    } catch (PDOException $e) {
        // Handle database errors gracefully
        $posts = []; // Set posts to an empty array in case of an error
        error_log("Database error: " . $e->getMessage());
    }
}

// Include the View file
include './views/home.php';
?>
