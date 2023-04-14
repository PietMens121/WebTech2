<?php

namespace src\controllers;


use App\Templating\Render;
use src\models\Exam;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = new User();
        $user = $user->find(1);

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