<?php

use App\Routing\Route;
use App\Templating\Render;
use src\controllers\HomePageController;

// Setup routes here

Route::get('/', [new HomePageController, 'index']);

Route::get('/test', function () {
    Render::view('test.html', ['user' => 'Piet']);
});

Route::get('/kaas/{parameter}', function ($parameter) {
   var_dump($parameter);
});