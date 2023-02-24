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

use App\Factories\ServerRequestFactory;
use App\Http\sendResponse;
use App\Routing\Router;
use App\Templating\Render;
use App\Http\Kernel;

require_once 'App/config.php';

$router = new Router();
$router->setContainer($DIcontainer);
$kernel = new Kernel($router);

//Render::view('/layouts/layout.html');

require_once "routes/web.php";

$serverRequest = ServerRequestFactory::createServerRequest();

$response = $kernel->handle($serverRequest);

sendResponse::execute($response);
