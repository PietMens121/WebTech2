<?php

namespace App\Database\Relations;

use App\Database\Builder\QueryBuilder;
use App\Database\Model;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use src\models\User;

class Relation
{
    private static Model $model;
    private static Model $relation_model;

    private static function formatForeignKey(Model $model, string $foreign_key = ''): string
    {
        if ($foreign_key === '') {
            $reflection = new ReflectionClass($model);
            return strtolower($reflection->getShortName()) . '_id';
        }
        return $foreign_key;
    }

    public static function formatPivotTable(string $pivot_table): string
    {
        if ($pivot_table === '') {
            $order_array = [];

            array_push($order_array, self::$model->getShortName(), self::$relation_model->getShortName());
            sort($order_array);

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
        self::$model = $model;
        self::$relation_model = new ($relation_model)();

        $results = self::prepareRelationJoin($pivot_table);

        return $results;
    }

    private static function prepareRelationJoin($pivot_table): array
    {
        $pivot_table = self::formatPivotTable($pivot_table);

        $query = new QueryBuilder();
        $query->select(implode(', ', self::$relation_model->getFormattedFillables()));
        $query->from(self::$model->getTable());

        $query = self::prepareFirstJoin($query, $pivot_table);
        $query = self::prepareSecondJoin($query, $pivot_table);

        $query->where(self::formatWhereClause());

        $results = $query->get();

        return $results;
    }

    private static function formatWhereClause(): string
    {
        $table = self::$model->getTable();
        $primary_key = self::$model->{self::$model->primary_key};
        return $table . '.' . self::$model->primary_key . ' = ' . $primary_key;
    }

    private static function prepareFirstJoin(QueryBuilder $query, $pivot): QueryBuilder
    {
        $constraint = self::$model->getTable() . '.id';


        return $query->join($pivot, $constraint, '=', $pivot . '.' . self::$model->getShortName() . '_id');
    }

    private static function prepareSecondJoin(QueryBuilder $query, string $pivot): QueryBuilder
    {
        $constraint = $pivot . '.' . self::$relation_model->getShortName() . '_id';

        return $query->join(
            self::$relation_model->getTable(),
            $constraint,
            '=',
            self::$relation_model->getTable() . '.id'
        );
    }

    public static function attach(string $relation, int $id, Model $model, $pivot = ''): bool
    {
        self::$model = $model;
        self::$relation_model = new ($relation)();

        $pivot = self::formatPivotTable($pivot);

        $columns = self::formatForeignKey(self::$model) . ' = ? , ' . self::formatForeignKey(self::$relation_model) . ' = ?';
        $values = [];

        var_dump($pivot);

        array_push($values, self::$model->{self::$model->primary_key}, $id);

        return self::$model->pushDb($pivot, $columns, $values);
    }
}