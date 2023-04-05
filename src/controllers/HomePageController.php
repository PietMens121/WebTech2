<?php

namespace src\controllers;


use App\Http\Response;
use App\Service\dd;
use App\Templating\Render;
use Couchbase\View;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $users = new User();
        $users = $users->all();

        Render::view('test.html', [
            'users' => $users
        ]);
    }
}