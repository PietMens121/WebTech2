<?php

use App\Routing\Router;
use App\Templating\Render;
$router = Router::getInstance();



// Setup routes here

$router->get('/', function () {
    Render::view('home.html');
});

$router->get('/test', function () {
    Render::view('test.html');
});