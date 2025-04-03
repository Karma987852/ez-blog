<?php
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$viewDir = '/src/views/';
$controllerDir = '/src/controllers/';

if (str_starts_with($request, '/home')) {
    require __DIR__ . $viewDir . 'home.php';
    exit;
}

switch ($request) {
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case '/home':
        require __DIR__ . $viewDir . 'home.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}
?>
