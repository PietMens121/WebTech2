<?php

use App\Routing\Route;
use src\controllers\HomePageController;
use src\controllers\ExamController;

// Setup routes here

Route::get('/', [new HomePageController, 'index']);

Route::get('/exams', [new ExamController, 'index']);

Route::get('/exams/{id}', [new ExamController, 'show']);

Route::post('/exams/{id}', [new ExamController, 'attach']);

Route::get('/kaas/{parameter}', function ($parameter) {
    var_dump($parameter);
});