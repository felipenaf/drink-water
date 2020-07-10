<?php

require_once 'vendor/autoload.php';

$route = new Route($_SERVER['REQUEST_URI']);
$response = $route->redirect($_SERVER['REQUEST_METHOD']);

if ($response[0]) {
    echo json_encode([
        'status' => $response[1],
        'data' => $response[0]
    ]);
}

http_response_code($response[1]);
