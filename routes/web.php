<?php

use App\Routing\Route;
use src\controllers\HomePageController;
use src\controllers\ExamController;
use src\controllers\UserController;

// Setup routes here

Route::get('/', [new HomePageController(), 'index'])->middleware('auth');

//Exams
Route::get('/exams', [new ExamController(), 'index'])->middleware('auth');
Route::get('/exams/{id}', [new ExamController(), 'show'])->middleware('auth');
Route::post('/exam/{id}', [new ExamController(), 'attach'])->middleware('auth');
Route::post('/exam/{id}/{user_id}', [new ExamController(), 'updateGrade'])->middleware('auth');

//Login
Route::get('/login', [new UserController(), 'showLogin']);
Route::get('/login/failed', [new UserController(), 'showLoginFailed']);
Route::post('/login', [new UserController(), 'login']);
Route::get('/register', [new UserController(), 'showRegister'])->middleware('admin');
Route::get('/register/{errorMessage}', [new UserController(), 'showRegister'])->middleware('admin');
Route::post('/register', [new UserController(), 'register'])->middleware('admin');
Route::post('/logout', [new UserController(), 'logout'])->middleware('auth');

