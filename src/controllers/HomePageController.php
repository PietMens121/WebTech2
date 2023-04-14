<?php

namespace src\controllers;


use App\Database\Auth;
use App\Templating\Render;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $exams = $user->Exams();

        return Render::view('home.html', [
            'user' => $user,
            'exams' => $exams
        ]);
    }
}