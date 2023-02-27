<?php

namespace src\models;

use App\Database\Model;

class User extends Model
{
    protected string $table = 'Users';

    public int $id;

    public string $username;
    public string $password;
}