<?php

namespace App\Database\Relations;

use App\Database\Model;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

class Relation
{

    private static function formatForeignKey(Model $model, string $foreign_key): string
    {
        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            return strtolower($reflection->getShortName()) . '_id';
        }
        return $foreign_key;
    }

    public static function formatPivotTable(Model $model, Model $relation_model, string $pivot_table): string
    {
        if ($pivot_table === '') {
            $reflection = new ReflectionClass($model);
            $reflection_relation = new ReflectionClass($relation_model);

            $order_array = [];
            array_push($order_array, $reflection->getShortName(), $reflection_relation->getShortName());
            sort($order_array);

//            $pivot_table = strtolower($order_array[0]) . '_' . strtolower($order_array[1]);

            $pivot_table = implode('_', $order_array);
            $pivot_table = strtolower($pivot_table);
        }

        return $pivot_table;
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

        $foreign_key = self::formatForeignKey($model, $foreign_key);

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

        $foreign_key = self::formatForeignKey($model, $foreign_key);

        $primary_key = $model->primary_key;

        return $relation_model->where($foreign_key, $model->{$primary_key});
    }

    public static function BelongsTo(Model $model, string $relation_model, string $foreign_key = ''): Model|null
    {
        $relation_model = new ($relation_model)();

        $foreign_key = self::formatForeignKey($relation_model, $foreign_key);

        return $relation_model->find($model->{$foreign_key});
    }

    public static function BelongsToMany(Model $model, string $relation_model, string $pivot_table = ''): array
    {
        $relation_model = new ($relation_model)();

        $pivot_table = self::formatPivotTable($model, $relation_model, $pivot_table);

//        return $relation_model->join();

        return array();

//        echo '<pre>' , var_dump($pivot_table) , '</pre>';

    }
}