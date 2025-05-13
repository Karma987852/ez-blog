<?php
header('Content-Type: application/json');

require_once 'search.php';

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode([]);
    exit;
}

try {
    $searchController = new SearchController();
    $results = $searchController->search(trim($_GET['q']));
    
    echo json_encode($results);
} catch (Exception $e) {
    error_log('Search error: ' . $e->getMessage());
    echo json_encode([]);
}