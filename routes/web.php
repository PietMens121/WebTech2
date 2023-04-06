<?php

use App\Routing\Route;
use App\Templating\Render;
use src\controllers\HomePageController;

// Setup routes here

Route::get('/', [new HomePageController, 'index']);

Route::get('/test', function () {
    Render::view('test.html', ['user' => 'Piet']);
    var_dump($_SERVER['REQUEST_METHOD']);
});

Route::post('/test', function () {
    var_dump($_SERVER['REQUEST_METHOD']);
});

Route::get('/kaas/{parameter}', function ($parameter) {
   var_dump($parameter);
});