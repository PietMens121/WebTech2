<?php

use App\container\Container;
use App\Http\RequestHandler;
use App\Routing\Router;
use App\Service\DotEnv;
use App\Http\ServerRequest;

// Define the base path
define('BASE_PATH', realpath(__DIR__ . '/../'));

// Autoload classes
spl_autoload_register(function ($class) {
    $filename = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    include $filename;
});

// Load dependencies
require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/App/Helpers/helpers.php';
(new DotEnv(BASE_PATH . '/.env'))->load();

// Set up dependency container
$container = new Container([
    Router::class => new App\Routing\Router(),
]);
Container::setInstance($container);

// Start session
session_start();

// Load routes
require_once BASE_PATH . '/routes/web.php';

// Handle the request
$requestHandler = new RequestHandler($container);
$request = ServerRequest::createFromGlobals();
$response = $requestHandler->handle($request);

// Send the response
$response->send();