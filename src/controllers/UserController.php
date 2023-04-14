<?php

namespace src\controllers;

use App\Database\Auth;
use App\Http\Response;
use App\Templating\Render;
use Psr\Http\Message\ResponseInterface;
use src\models\User;

class UserController extends Controller
{
    public function showLogin(): ResponseInterface
    {
        return Render::view('user/login.html');
    }

    public function showRegister(): ResponseInterface
    {
        return Render::view('user/register.html');
    }

    public function login()
    {
        $postData = request()->getParsedBody();
        $loggedIn = Auth::login($postData['username'], $postData['password']);

        if ($loggedIn) {
            return Response::redirect("/");
        }

        return Response::redirect("/login");
    }
}