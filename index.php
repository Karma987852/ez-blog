<?php
$basePath = '/ez-blog';
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

// Remove base path from request
if (strpos($request, $basePath) === 0) {
    $request = substr($request, strlen($basePath));
}

$viewDir = __DIR__ . '/src/views/';
$controllerDir = __DIR__ . '/src/controllers/';

if (str_starts_with($request, '/home')) {
    require $viewDir . 'home.php';
    exit;
}

switch ($request) {
    case '/':
    case '/home':
        require $viewDir . 'home.php';
        break;

    case '/post':
        require $controllerDir . 'get-post.php';
        break;

    default:
        http_response_code(404);
        require $viewDir . '404.php';
}
?>