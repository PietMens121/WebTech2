<?php

use App\Routing\Route;
use App\Templating\Render;
use src\controllers\HomePageController;

// Setup routes here

Route::get('/', [new HomePageController, 'index']);

Route::get('/test', function () {
    Render::view('test.html');
});

Route::get('/segment/{parameter}/segment/{par}', function ($parameter, $par) {
   var_dump($parameter, $par);
});