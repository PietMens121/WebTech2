<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;
use src\models\Role;

/**
 * @property int $id
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
    public function role(): Model
    {
        return $this->hasOne(Role::class);
    }

    public function coins(): array
    {
        return $this->hasMany(Coin::class);
    }
}