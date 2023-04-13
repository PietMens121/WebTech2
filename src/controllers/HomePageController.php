<?php

namespace src\controllers;


use App\Templating\Render;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        var_dump(request()->getQueryParams());

        $user = new User();
        $user = $user->find(1);

        $exams = $user->Exams();

        return Render::view('home.html', [
            'user' => $user,
            'exams' => $exams
        ]);
    }
}