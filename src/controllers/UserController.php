<?php

namespace src\controllers;

use App\Database\Auth;
use App\Database\Builder\QueryBuilder;
use App\Exceptions\Database\Auth\AlreadyLoggedInException;
use App\Exceptions\Database\Auth\LoginException;
use App\Exceptions\Database\Auth\UserAlreadyExistsException;
use App\Http\Response;
use App\Templating\Render;
use Psr\Http\Message\ResponseInterface;
use src\models\Exam;
use src\models\Role;
use src\models\User;

class UserController extends Controller
{
    public function index(): ResponseInterface
    {
        $users = new User();
        $users = $users->all();

        return Render::view('user/users.html', [
            'users' => $users,
        ]);
    }

    public function show($id): ResponseInterface
    {
        $user = new User();
        $user = $user->find($id);

        $exams = $this->getExams($id);

        $roles = new Role();
        $roles = $roles->all();

        return Render::view('/user/user.html', [
            'user' => $user,
            'exams' => $exams,
            'roles' => $roles,
        ]);
    }

    private function getExams($id): array
    {
        $query = new QueryBuilder();
        $query->select('exams.*')
            ->from('exams')
            ->leftOuterJoin('exam_user', 'exams.id', '=', 'exam_user.exam_id')
            ->where('exam_user.exam_id IS NULL OR exam_user.user_id != ' . $id);

        return $query->get();
    }

    public function update($id): void
    {
        $request = request()->getParsedBody();

        $user = new User();
        $user = $user->find($id);
        $user->username = $request['username'];
        $user->role_id = $request['role'];
        if($request['exam']) {
            $user->attach(Exam::class, $request['exam']);
        }
        $user->update();

        redirect('/user/' .$id);
    }

    public function detach($id): void
    {
        $user = new User();
        $user = $user->find($id);

        $request = request()->getParsedBody();
    }

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

    public function showRegister($errorMessage = null): ResponseInterface
    {
        $roles = new Role();
        $roles = $roles->all();
        if ($errorMessage) $errorMessage = htmlspecialchars(str_replace('%20', ' ', $errorMessage));
        return Render::view('user/register.html', [
            "errorMessage" => $errorMessage,
            'roles' => $roles,
        ]);
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

    public function register()
    {
        $postData = request()->getParsedBody();
        try {
            Auth::register($postData['username'], $postData['password'], $postData['role']);
        } catch (UserAlreadyExistsException) {
            return Response::redirect("/register/Gebruikersnaam al in gebruik!");
        }

        return Response::redirect("/"); // TODO: add user added page
    }

    public function logout()
    {
        session_destroy();
        return Response::redirect("/login");
    }
}