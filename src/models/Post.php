<?php
class Post {
    private $conn;
    private $table = 'blog_posts';

    public $id;
    public $title;
    public $content;
    public $user_id;
    public $category_id;
    public $img_url;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Posts
    public function read() {
        $query = "SELECT 
                    p.id,
                    p.title,
                    p.content,
                    p.img_url,
                    p.user_id,
                    p.category_id,
                    p.created_at,
                    p.updated_at,
                    u.username as author_name,
                    c.name as category_name
                FROM " . $this->table . " p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getAllTitles() {
        $query = "SELECT DISTINCT title, created_at FROM " . $this->table . " ORDER BY created_at DESC LIMIT 20";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Fetch only the title column
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Get Posts by User ID
    public function readByUserId($userId) {
        $query = "SELECT 
                    p.id,
                    p.title,
                    p.content,
                    p.user_id,
                    p.category_id,
                    p.created_at,
                    p.updated_at,
                    u.username as author_name,
                    c.name as category_name
                FROM " . $this->table . " p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt;
    }

    // Get Single Post
    public function read_single() {
        $query = "SELECT 
                    p.id,
                    p.title,
                    p.content,
                    p.user_id,
                    p.category_id,
                    p.created_at,
                    p.updated_at,
                    u.username as author_name,
                    c.name as category_name
                FROM " . $this->table . " p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->user_id = $row['user_id'];
        $this->category_id = $row['category_id'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
        return $row;
    }

    public function getPostComments($postId) {
        $query = "SELECT 
                    c.id,
                    c.comment,
                    c.created_at,
                    c.username
                  FROM comments c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.blog_post_id = :post_id
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create Post
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                SET 
                    title = :title, 
                    content = :content, 
                    author_id = :author_id, 
                    category_id = :category_id";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":author_id", $this->author_id);
        $stmt->bindParam(":category_id", $this->category_id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Update Post
    public function update() {
        $query = "UPDATE " . $this->table . " 
                SET 
                    title = :title, 
                    content = :content, 
                    category_id = :category_id 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
