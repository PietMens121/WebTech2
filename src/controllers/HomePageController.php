<?php

namespace src\controllers;


use App\Templating\Render;
use Couchbase\View;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = new User();
        $user = $user->find(1);
        print($user['username']);
    }
}