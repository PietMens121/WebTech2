<?php

namespace src\controllers;


use App\Database\Builder;
use App\Http\Response;
use App\Service\dd;
use App\Templating\Render;
use Couchbase\View;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = new User();
        $user = $user->find(1);

        Render::view('test.html', [
            'user' => $user
        ]);
    }
}