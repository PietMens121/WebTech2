<?php

// Autoload classes
define('BASE_PATH', realpath(dirname(__FILE__)));

spl_autoload_register(function ($class) {
    $filename = '../'. str_replace('\\', '/', $class) . '.php';
    include($filename);
});

//Autoload the psr classes
require "../vendor/autoload.php";

session_start();

use App\Routing\Router;
use App\Service\DotEnv;

(new DotEnv('../.env'))->load();

//Router
require_once '../routes/web.php';
Router::getInstance()->handleRequest();