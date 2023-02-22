<?php

// Autoload all classes
define('BASE_PATH', realpath(dirname(__FILE__)));

spl_autoload_register(function ($class) {
    $filename = BASE_PATH .'/'. str_replace('\\', '/', $class) . '.php';
    include($filename);
});

//Autoload the psr classes
require "vendor/autoload.php";

session_start();

use App\Routing\Router;


require "App/config.php";

$router = new Router;
$router->setContainer($DIcontainer);

require_once "src/routes/web.php";
