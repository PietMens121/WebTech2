<?php

use App\Routing\Router;
use App\Service\DotEnv;

// Autoload classes
define('BASE_PATH', realpath(dirname('../../')));

spl_autoload_register(function ($class) {
    $filename = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    include($filename);
});

//Autoload the psr classes
require BASE_PATH . "/vendor/autoload.php";

session_start();

(new DotEnv(BASE_PATH . '/.env'))->load();

//Router
require_once BASE_PATH . '/routes/web.php';
Router::getInstance()->handleRequest();