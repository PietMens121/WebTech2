<?php

// Autoload classes
define('BASE_PATH', realpath(dirname(__FILE__)));

spl_autoload_register(function ($class) {
    $filename = BASE_PATH .'/'. str_replace('\\', '/', $class) . '.php';
    include($filename);
});

//Autoload the psr classes
require BASE_PATH."/vendor/autoload.php";

session_start();

use App\Routing\Router;
use App\Service\DotEnv;

(new DotEnv(__DIR__ . '/.env'))->load();

//Router
require_once BASE_PATH . '/routes/web.php';
Router::getInstance()->handleRequest();