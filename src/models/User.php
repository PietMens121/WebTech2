<?php

namespace src\models;

use App\Database\Model;
use App\Database\Relations\HasOne;
use App\Database\Relations\Relation;
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
        'role_id',
    ];

    public function Role(): Model|null
    {
        return Relation::BelongsTo($this, Role::class);
    }

    public function Exams(): array
    {
        return Relation::BelongsToMany($this, Exam::class);
    }

    public function ExamAdmin(): array
    {
        return Relation::hasMany($this, Exam::class, 'Assignee_id');
    }
}