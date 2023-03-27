<?php

use App\Routing\Router;

$router = new Router();

$router->get('/', function () {
    var_dump("Hallo!");
});