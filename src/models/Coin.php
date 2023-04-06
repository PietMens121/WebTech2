<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;

/**
 * @property string $name
 */

class Coin extends Model
{
    protected string $table = 'Coins';

    protected array $fillable = [
        'user_id',
        'name',
    ];
}