<?php
require_once __DIR__ . '/../config/database.php';

class SearchController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function search($query) {
        if (empty($query)) {
            return [];
        }

        try {
            $connection = $this->db->connect();
            $searchQuery = "%{$query}%";
            
            $postStmt = $connection->prepare(
                "SELECT p.id, p.title 
                FROM blog_posts p 
                WHERE p.title LIKE :query 
                ORDER BY p.created_at DESC 
                LIMIT 5"
            );
            
            $postStmt->execute(['query' => $searchQuery]);
            return $postStmt->fetchAll();

        } catch (PDOException $e) {
            error_log('Search error: ' . $e->getMessage());
            return [];
        }
    }
}