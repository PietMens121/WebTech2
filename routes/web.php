<?php

use App\Http\Response;
use App\Routing\Route;
use App\Templating\Render;
use src\controllers\HomePageController;

// Setup routes here

Route::get('/', [new HomePageController, 'index']);

Route::get('/kaas/{parameter}', function ($parameter) {
    var_dump($parameter);
});