<?php

namespace App\Database\Relations;

use App\Database\Model;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

class Relation
{
    /**
     * @param ReflectionObject $model, string $relation_model
     * @throws ReflectionException
     * @return Model
     *
     */
    public static function hasOne(Model $model, string $relation_model, string $foreign_key)
    {
        $relation_model = new ($relation_model)();
        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            $foreign_key = strtolower($reflection->getShortName()) . '_id';
        }
        return $relation_model->whereOne($foreign_key, $model->id);
    }
}