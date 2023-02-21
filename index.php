<?php

define('BASE_PATH', realpath(dirname(__FILE__)));

//spl_autoload_register( function($class)
//{
//    $filename = BASE_PATH .'/'. str_replace('\\', '/', $class) . '.php';
//    print($class);
//
//    include($filename);
//});

include_once "App/Routing/Router.php";

session_start();

use App\Routing\Router;
use App\Container\DependencyContainer;


$router = new Router();
$router->setContainer(new DependencyContainer([]));

require_once "src/routes/web.php";
