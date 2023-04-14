<?php

namespace src\controllers;

use App\Database\Auth;
use App\Exceptions\Database\Auth\AlreadyLoggedInException;
use App\Exceptions\Database\Auth\LoginException;
use App\Http\Response;
use App\Templating\Render;
use Psr\Http\Message\ResponseInterface;

class UserController extends Controller
{
    public function showLogin(): ResponseInterface
    {
        if (!is_null(user())) {
            return Render::view("user/loggedIn.html");
        }

        return Render::view('user/login.html');
    }

    public function showLoginFailed(): ResponseInterface
    {
        return Render::view('user/login.html', ['errorMessage' => "Verkeerde gebruikersnaam of wachtwoord!"]);
    }

    public function showRegister(): ResponseInterface
    {
        return Render::view('user/register.html');
    }

    public function login()
    {
        $postData = request()->getParsedBody();
        try {
            Auth::login($postData['username'], $postData['password']);
        } catch (LoginException) {
            return Response::redirect("/login/failed");
        }

        return Response::redirect("/");
    }

    public function logout()
    {
        session_destroy();
        return Response::redirect("/login");
    }
}