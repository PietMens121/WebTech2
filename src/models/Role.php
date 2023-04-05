<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;

/**
 * @property string $name
 */

class User extends Model
{
    protected string $table = 'Roles';

    protected array $fillable = [
        'name',
    ];
}