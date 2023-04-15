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


    public static function initRelation(Model $model, string $relation): void
    {
        self::$model = $model;
        self::$relation_model = new ($relation)();
    }
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

        $primary_key = $model->getPrimaryKey();
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

        $primary_key = $model->getPrimaryKey();

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
        self::initRelation($model, $relation_model);

        $results = self::prepareRelationJoin($pivot_table);

        return $results;
    }

    private static function prepareRelationJoin($pivot_table, $with_pivot = false): array
    {
        $pivot_table = self::formatPivotTable($pivot_table);

        $query = new QueryBuilder();

        if(!$with_pivot)
        {
            $query->select(implode(', ', self::$relation_model->getFormattedFillables()));
        } else {
            $query->select(implode(', ', self::$relation_model->getFormattedFillables()) . ', ' . $pivot_table . '.*', $pivot_table . '.id AS ' . $pivot_table . '_id');
        }

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
        $primary_key = self::$model->{self::$model->getPrimaryKey()};
        return $table . '.' . self::$model->getPrimaryKey() . ' = ' . $primary_key;
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
        self::initRelation($model, $relation);

        $pivot = self::formatPivotTable($pivot);

        $columns = self::formatForeignKey(self::$model) . ' = ? , ' . self::formatForeignKey(self::$relation_model) . ' = ?';
        $values = [];

        array_push($values, self::$model->{self::$model->getPrimaryKey()}, $id);

        return self::$model->pushDb($pivot, $columns, $values);
    }

    public static function updatePivot(string $relation, int $id,  Model $model, array $values, $pivot = ''): bool
    {
        self::initRelation($model, $relation);

        $pivot = self::formatPivotTable($pivot);

        $foreign_key = self::formatForeignKey(self::$model);
        $relation_key = self::formatForeignKey(self::$relation_model);

        $where = $foreign_key . ' = '. self::$model->{self::$model->getPrimaryKey()};
        $and = ' AND '. $relation_key .' = ' . $id;

        $condition = $where . $and;

        return self::$model->updateSpecific($pivot, array_keys($values), array_values($values), $condition);
    }

    public static function getPivot(string $relation, Model $model, $pivot = ''): array
    {
        self::initRelation($model, $relation);

        $pivot = self::formatPivotTable($pivot);

        $query = new QueryBuilder();

        $query->from($pivot)
            ->where(self::formatForeignKey(self::$model) . ' = ' . self::$model->{self::$model->getPrimaryKey()});

        echo $query;

        return $query->get();
    }

    public static function getWithPivot(string $relation, Model $model, $pivot = ''): array
    {
        self::initRelation($model, $relation);

        return self::prepareRelationJoin($pivot, true);
    }
}