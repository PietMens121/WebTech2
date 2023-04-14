<?php

use App\Routing\Route;
use src\controllers\HomePageController;
use src\controllers\ExamController;
use src\controllers\UserController;

// Setup routes here

Route::get('/', [new HomePageController(), 'index'])->middleware('auth');

Route::get('/exams', [new ExamController(), 'index'])->middleware('auth')->middleware('lecturer');

Route::get('/exams/{id}', [new ExamController(), 'show']);

Route::post('/exam/{id}', [new ExamController(), 'attach']);

Route::get('/login', [new UserController(), 'showLogin']);

Route::get('/login/failed', [new UserController(), 'showLoginFailed']);

Route::get('/logout', [new UserController(), 'logout']);

Route::get('/register', [new UserController(), 'showRegister']);

Route::post('/login', [new UserController(), 'login']);
