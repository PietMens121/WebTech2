<?php

namespace src\controllers;


use App\Database\Auth;
use App\Templating\Render;
use src\models\Exam;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $exams = new Exam;
        $exams = $exams->all();

        $grades = $user->withPivot(Exam::class);

        return Render::view('home.html', [
            'user' => $user,
            'exams' => $exams,
            'grades' => $grades
        ]);
    }
}