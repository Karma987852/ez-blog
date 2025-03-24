<?php
require_once 'config/database.php';

echo "✅ Database connected successfully!";

$query = $pdo->query("SHOW TABLES");
$tables = $query->fetchAll();

echo "<pre>";
print_r($tables);
echo "</pre>";
?>