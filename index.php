<?php
$request_uri = trim($_SERVER['REQUEST_URI'], '/');

$page = $request_uri === '' ? 'home' : $request_uri;

$allowed_pages = ['login', 'register', 'dashboard'];

if (in_array($page, $allowed_pages) && file_exists("./src/views/{$page}.php")) {
    require "./src/views/{$page}.php";
} else {
    require "./src/views/home.php";
}
?>