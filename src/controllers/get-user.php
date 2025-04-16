<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid or missing user ID']);
    http_response_code(400);
    exit();
}

$database = new Database();
$db = $database->connect();

$user = new User($db);
$user->id = intval($_GET['id']);

$stmt = $user->read_single();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    echo json_encode([
        'id' => $data['id'],
        'username' => $data['username'],
        'email' => $data['email'],
        'bio' => $data['bio'],
        'created_at' => $data['created_at'],
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
    http_response_code(404);
}
