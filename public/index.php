<?php

use App\Http\RequestHandler;
use App\Routing\Router;
use App\Service\DotEnv;
use App\Http\ServerRequest;

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

$requestHandler = new RequestHandler();
$requestHandler->handle(ServerRequest::createFromGlobals());