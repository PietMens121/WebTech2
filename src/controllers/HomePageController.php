<?php

namespace src\controllers;


use App\Database\Builder;
use App\Http\Response;
use App\Service\dd;
use App\Templating\Render;
use Couchbase\View;
use src\models\Coin;
use src\models\User;

class HomePageController extends Controller
{
    public function index()
    {
        $user = new User();
        $user = $user->find(1);

        $coins = $user->Coins();

        $role = $user->role()->whereOne('name', 'Admin');

        $coin = new Coin();
        $coin = $coin->find(3);
        var_dump($coin->user()->username);
        var_dump($coin->name);
        Render::view('test.html', [
            'user' => $user,
            'coins' => $coins,
            'role'=> $role
        ]);
    }
}