<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\Relation;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 */

class Exam extends Model
{
    protected string $table = 'Exams';

    protected array $fillable = [
        'name',
    ];

    public function Users(): array
    {
        return Relation::BelongsToMany($this, User::class);
    }

}