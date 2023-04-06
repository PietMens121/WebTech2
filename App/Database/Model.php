<?php

namespace App\Database;

use App\Database\Relations\HasOne;
use App\Database\Relations\Relation;
use PDO;
use ReflectionClass;

#[\AllowDynamicProperties]
abstract class Model
{
    protected string $table;
    protected array $fillable;
    protected PDO $conn;
    protected static string $primary_key = 'id';

    public function __construct()
    {
        $sql = new MySQL();
        $this->conn = $sql->connect();
    }

    /**
     * find record based on id
     *
     * @param $id
     * @return array|false
     */
    public function find($id): bool|Model
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
    public function where($column, $value, $operator = '=')
    {
        $query = $this->whereQuery($column, $value, $operator);
        return $this->fetchAll($query);
    }

    public function whereOne($column, $value, $operator = '=')
    {
        $query = $this->whereQuery($column, $value, $operator);
        return $this->fetchOne($query);
    }

    public function whereQuery($column, $value, $operator)
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
        $reflection = new \ReflectionObject($this);

        $columns = [];
        $values = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $columns[] = '`' . $property->getName() . '` = ?';
            $values[] = $this->{$property->getName()};
        }

        $columns = implode(',', $columns);

        $query = sprintf('INSERT INTO %s SET %s ', $this->table, $columns);


        try {
            $pdo = $this->conn->prepare($query)->execute($values);
        } catch (\PDOException $e) {
            print('something went wrong inserting/updating the user ' . $e);
            $pdo = false;
        }

        return $pdo;
    }

    /**
     * find model based on id returns ModelNotFound exception if no model is found
     *
     * @param
     * @return Model|false
     */
    public function hasOne(string $relation_model, string $foreign_key = '')
    {
        return Relation::hasOne($this, $relation_model, $foreign_key);
    }

    /**
     * fetch a single model
     *
     * @param $query
     * @return Model
     */
    private function fetchOne($query): Model
    {
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        $columns = $pdo->fetch(PDO::FETCH_ASSOC);

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
    private function fetchAll($query): array
    {
        echo '<pre>' , var_dump($query) , '</pre>';
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>' , var_dump($result) , '</pre>';
        $models = array();
        foreach($result as $model){
            $reflect = (new ReflectionClass($this))->getName();
            $new_model = new $reflect();
            foreach($model as $key => $value){
                $new_model->{$key} = $value;
            }
            $models[] = $new_model;
        }

        return $models;
    }
}