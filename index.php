<?php

define('ROOT_PATH', dirname(__FILE__));
require_once 'vendor/autoload.php';

$uc = new UserController();
$resultado = $uc->getAll();

$endpoints = array('/drink-water/users', '/drink-water/drink');

if (in_array($_SERVER['REQUEST_URI'], $endpoints)) {
    var_dump($resultado);
} else {
    http_response_code(204);
    die();
}
