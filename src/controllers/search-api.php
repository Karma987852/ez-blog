<?php
header('Content-Type: application/json');

require_once 'search.php';

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$searchController = new SearchController();
$results = $searchController->search($_GET['q']);

echo json_encode($results);