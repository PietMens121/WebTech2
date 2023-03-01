<?php

namespace src\models;

use App\Database\Model;

/**
 * @property string $username
 * @property string $password
 */

class User extends Model
{
    protected string $table = 'Users';

    protected array $fillable = [
        'username',
        'password'
    ];
}