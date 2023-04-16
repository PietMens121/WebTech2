<?php

namespace App\Database;

use AllowDynamicProperties;
use App\Database\Builder\QueryBuilder;
use App\Database\Relations\HasOne;
use App\Database\Relations\Relation;
use PDO;
use PDOException;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

#[AllowDynamicProperties]
abstract class Model
{
    protected string $table;
    protected array $fillable;
    private string $primary_key = 'id';
    private PDO $conn;

    public function __construct()
    {
        $sql = new MySQL();
        $this->conn = $sql->connect();
    }

    public function __isset(string $name): bool
    {
        return false;
    }

    /**
     * find record based on id
     *
     * @param $id
     * @return array|false
     */
    public function find($id): null|Model
    {
        $query = sprintf('SELECT * FROM %s WHERE id = %s', $this->table, $id);

        return $this->fetchOne($query);
    }

    /**
     * retrieve all record from model
     *
     * @return array|false
     */
    public function all(): bool|array
    {
        $query = sprintf('SELECT * FROM %s', $this->table);
        return $this->fetchAll($query);
    }

    /**
     * finds records based on the searched columns and values
     *
     * @param $column
     * @param $value
     * @param $operator
     * @return array|false
     */
    public function where($column, $value, $operator = '='): false|array
    {
        $query = $this->whereQuery($column, $value, $operator);
        return $this->fetchAll($query);
    }

    public function whereOne($column, $value, $operator = '=')
    {
        $query = $this->whereQuery($column, $value, $operator);
        return $this->fetchOne($query);
    }

    public function whereQuery($column, $value, $operator): string
    {
        return sprintf('SELECT * FROM %s WHERE %s %s "%s"', $this->table, $column, $operator, $value);
    }

    /**
     * Save the new/updated model in the database
     *
     * @return bool
     */
    public function save(): bool
    {
        $columns = $this->formatProperties();
        $values = $this->getValues();

        return $this->pushDb($this->table, $columns, $values);
    }

    private function getValues(): array
    {
        $reflection = new ReflectionObject($this);
        $values = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $values[] = $this->{$property->getName()};
        }

        return $values;
    }

    private function formatProperties(): string
    {
        $reflection = new ReflectionObject($this);

        $columns = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $columns[] = '`' . $property->getName() . '` = ?';
        }

        $columns = implode(',', $columns);

        return $columns;
    }

    public function updateSpecific($table, $columns, $values, $condition): bool
    {
        $columns = implode(' = ?, ', $columns) . ' = ?';

        $query = sprintf('UPDATE %s SET %s WHERE %s', $table, $columns, $condition);

        try {
            $pdo = $this->conn->prepare($query)->execute($values);
        } catch (PDOException $e) {
            print('something went wrong inserting/updating the user' . $e);
            $pdo = false;
        }

        return $pdo;
    }

    public function update(): bool
    {
        $columns = $this->formatProperties();
        $values = $this->getValues();
        $condition = 'id = '. $this->{$this->primary_key};

        return $this->updateModel($columns, $values, $condition);
    }

    private function updateModel($columns, $values, $condition): bool
    {
        $query = sprintf('UPDATE %s SET %s WHERE %s', $this->table, $columns, $condition);

        return $this->prepareAndExecute($query, $values);
    }

    public function pushDb(string $table, string $columns, array $values): bool
    {

        $query = sprintf('INSERT INTO %s SET %s ', $table, $columns);

        try {
            $pdo = $this->conn->prepare($query)->execute($values);
        } catch (PDOException $e) {
            print('something went wrong inserting/updating the user ' . $e);
            $pdo = false;
        }

        return $pdo;
    }

    private function prepareQuery($query): \PDOStatement
    {
        return $this->conn->prepare($query);
    }

    private function executeQuery(\PDOStatement $pdo, $values): bool
    {
        try {
            $pdo = $pdo->execute($values);
        } catch (PDOException $e) {
            print('something went wrong inserting/updating the user ' . $e);
            $pdo = false;
        }

        return $pdo;
    }

    private function prepareAndExecute($query, $values): bool
    {
        $pdo = $this->prepareQuery($query);
        return $this->executeQuery($pdo, $values);
    }

    /**
     * fetch a single model
     *
     * @param $query
     * @return Model
     */
    private function fetchOne(string $query): Model|null
    {
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        $columns = $pdo->fetch(PDO::FETCH_ASSOC);
        if (!$columns) {
            return null;
        }
        foreach ($columns as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * fetch multiple models
     *
     * @param $query
     * @return array
     */
    private function fetchAll(string $query): array
    {
        // prepare and fetch all results
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);

//        In case no results found return this empty array
        $models = array();

//        Go through all results
        foreach ($result as $model) {
//            Create new models
            $reflect = (new ReflectionClass($this))->getName();
            $new_model = new $reflect();
            foreach ($model as $key => $value) {
//                Assign properties to the models
                $new_model->{$key} = $value;
            }
//            Push the models to array
            $models[] = $new_model;
        }
//          return the models or return empty array
        return $models;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getShortName(): string
    {
        $reflection = new ReflectionClass($this);
        return $reflection->getShortName();
    }

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function getFormattedFillables(): array
    {
        $array = [];
        foreach ($this->fillable as $key => $value) {
            $array[] = $this->table . '.' . $value;
        }
        return $array;
    }

    public function attach(string $relation, int $id, string $pivot = ''): bool
    {
        return Relation::attach($relation, $id, $this, $pivot);
    }

    public function detach(string $relation, $id): bool
    {
        return Relation::detach();
    }

    public function delete(): bool
    {
        return true;
    }

    public function deleteRow($table, $condition): bool
    {
        $query = sprintf('DELETE FROM %s WHERE %s', $table, $condition);

        $pdo = $this->prepareQuery($query);
        return true;
    }

    public function updatePivot(string $relation, int $id,array $inserts, $pivot = ''): bool
    {
        return Relation::updatePivot($relation, $id, $this, $inserts, $pivot);
    }

    public function pivot(string $relation, string $pivot = ''): array
    {
        return Relation::getPivot($relation, $this, $pivot);
    }

    public function withPivot(string $relation, string $pivot = ''): array
    {
        return Relation::getWithPivot($relation, $this, $pivot);
    }

    public function getPrimaryKey(): string
    {
        return $this->primary_key;
    }

}