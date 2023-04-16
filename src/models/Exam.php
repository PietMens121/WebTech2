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
        'weight',
        'abbreviation',
        'assignee_id',
    ];

    public function Users(): array
    {
        return Relation::BelongsToMany($this, User::class);
    }

    public function Assignee(): Model
    {
        return Relation::BelongsTo($this, User::class, 'Assignee_id');
    }

}