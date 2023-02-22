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
use App\Templating\Render;


//
//$router = new Router;
//$router->setContainer($DIcontainer);

Render::view('home.html');

require_once "src/routes/web.php";
