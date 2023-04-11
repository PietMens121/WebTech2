<?php

namespace App\Database\Relations;

use App\Database\Model;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

class Relation
{

    private static function refactorForeignKey(Model $model, string $foreign_key): string
    {
        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            return strtolower($reflection->getShortName()) . '_id';
        }
        return $foreign_key;
    }

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

        $foreign_key = self::refactorForeignKey($model, $foreign_key);

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

        $foreign_key = self::refactorForeignKey($model, $foreign_key);

        $primary_key = $model->primary_key;

        return $relation_model->where($foreign_key, $model->{$primary_key});
    }

    public static function BelongsTo(Model $model, string $relation_model, string $foreign_key = ''): Model|null
    {
        $relation_model = new ($relation_model)();

        $foreign_key = self::refactorForeignKey($relation_model, $foreign_key);

        return $relation_model->find($model->{$foreign_key});
    }
}