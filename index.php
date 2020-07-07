<?php

define('ROOT_PATH', dirname(__FILE__));
require_once 'vendor/autoload.php';


$uc = new UserController();

$t = $uc->getAll();

var_dump($t);
