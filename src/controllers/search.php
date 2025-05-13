<?php
require_once __DIR__ . '/../config/database.php';

class SearchController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function search($query) {
        if (empty($query)) {
            return ['posts' => [], 'users' => []];
        }

        try {
            $connection = $this->db->connect();
            $searchQuery = "%{$query}%";
            
            // Search posts
            $postStmt = $connection->prepare(
                "SELECT p.id, p.title, p.created_at, u.username 
                FROM blog_posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.title LIKE :query 
                OR p.content LIKE :query 
                ORDER BY p.created_at DESC 
                LIMIT 5"
            );
            
            $postStmt->execute(['query' => $searchQuery]);
            $posts = $postStmt->fetchAll();

            // Search users
            $userStmt = $connection->prepare(
                "SELECT id, username, email, bio 
                FROM users 
                WHERE username LIKE :query 
                OR email LIKE :query 
                OR bio LIKE :query 
                ORDER BY username ASC 
                LIMIT 5"
            );

            $userStmt->execute(['query' => $searchQuery]);
            $users = $userStmt->fetchAll();

            return [
                'posts' => $posts,
                'users' => $users
            ];

        } catch (PDOException $e) {
            error_log('Search error: ' . $e->getMessage());
            return [];
        }
    }
}