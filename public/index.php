<?php

use App\container\Container;
use App\Http\RequestHandler;
use App\Routing\Route;
use App\Routing\Router;
use App\Service\DotEnv;
use App\Http\ServerRequest;

// Define BASE_PATH
define('BASE_PATH', realpath(dirname('../../')));

// Autoload classes
spl_autoload_register(function ($class) {
    $filename = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    include($filename);
});

//Autoload the psr classes
require BASE_PATH . "/vendor/autoload.php";

// Load .env
(new DotEnv(BASE_PATH . '/.env'))->load();

// Initialise Dependency container
$services = [
    Router::class => new Router(),
];

$container = new Container($services);

Route::setContainer($container);

// Start session
session_start();

//Router
require_once BASE_PATH . '/routes/web.php';

$requestHandler = new RequestHandler($container);
$response = $requestHandler->handle(ServerRequest::createFromGlobals());

// Send response
$response->send();