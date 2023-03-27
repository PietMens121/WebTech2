<?php

use App\Routing\Router;

Router::get('/', function () {
    var_dump("Hallo!");
});