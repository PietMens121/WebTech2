<?php

namespace App\Database\Relations;

use App\Database\Model;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

class Relation
{
    /**
     * @param ReflectionObject $model
     * @param string $relation_model
     * @param string $foreign_key
     *
     * @return Model
     *
     */
    public static function hasOne(Model $model, string $relation_model, string $foreign_key = ''): Model|null
    {
        $relation_model = new ($relation_model)();

        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            $foreign_key = strtolower($reflection->getShortName()) . '_id';
        }

        $primary_key = $model->primary_key;
        return $relation_model->whereOne($foreign_key, $model->{$primary_key});
    }

    /**
     * @param Model $model
     * @param string $relation_model
     * @param string $foreign_key
     * @return array
     *
     */
    public static function hasMany(Model $model, string $relation_model, string $foreign_key = ''): array
    {
        $relation_model = new ($relation_model)();

        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            $foreign_key = strtolower($reflection->getShortName()) . '_id';
        }

        $primary_key = $model->primary_key;

        return $relation_model->where($foreign_key, $model->{$primary_key});
    }
}