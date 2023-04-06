<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;

/**
 * @property string $name
 */

class Role extends Model
{
    protected string $table = 'Roles';

    protected array $fillable = [
        'user_id',
        'name',
    ];
}