<?php

define('ROOT_PATH', dirname(__FILE__));
require_once 'vendor/autoload.php';

$route = new Route($_SERVER['REQUEST_URI']);
$response = $route->redirect($_SERVER['REQUEST_METHOD']);

if ($response) {
    echo json_encode($response);
} else {
    http_response_code(404);
    die();
}
