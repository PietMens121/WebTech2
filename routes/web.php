<?php

use App\Routing\Route;
use App\Templating\Render;


// Setup routes here

Route::get('/', function () {
    Render::view('home.html');
});

Route::get('/test', function () {
    Render::view('test.html');
});