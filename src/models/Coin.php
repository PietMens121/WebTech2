<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;
use App\Database\Relations\Relation;

/**
 * @property string $name
 */

class Coin extends Model
{
    protected string $table = 'Coins';

    protected array $fillable = [
        'user_id',
        'name',
        'value'
    ];

    public function User(): Model|null
    {
        return Relation::BelongsTo($this, User::class);
    }
}