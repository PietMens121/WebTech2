<?php

namespace src\controllers;

use App\Templating\Render;

class UserController extends Controller
{
    public function showLogin()
    {
        return Render::view('user/login.html');
    }

    public function showRegister()
    {
        return Render::view('user/register.html');
    }
}