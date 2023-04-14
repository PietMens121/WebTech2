<?php

use App\Routing\Route;
use src\controllers\HomePageController;
use src\controllers\ExamController;
use src\controllers\UserController;

// Setup routes here

Route::get('/', [new HomePageController(), 'index']);

Route::get('/exams', [new ExamController(), 'index']);

Route::get('/exams/{id}', [new ExamController(), 'show']);

Route::post('/exam/{id}', [new ExamController(), 'attach']);

Route::get('/login', [new UserController(), 'showLogin']);
Route::get('/register', [new UserController(), 'showRegister']);

Route::post('/login', [new UserController(), 'login']);