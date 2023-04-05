<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;
use ;

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
    public function role(): false|array
    {
        return $this->hasOne(Role::class);
    }
}