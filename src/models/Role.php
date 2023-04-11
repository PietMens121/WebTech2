<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;
use App\Database\Relations\Relation;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 */

class Role extends Model
{
    protected string $table = 'Roles';

    protected array $fillable = [
        'name'
    ];

    public function Users(): array
    {
        return Relation::hasMany($this, User::class);
    }
}