<?php

namespace App\Database;

use PDO;

#[\AllowDynamicProperties]
abstract class Model
{
    protected string $table;
    protected array $fillable;
    protected PDO $conn;
    protected $primaryKey = 'id';

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
    public function find($id): bool|array
    {
        $query = sprintf('SELECT * FROM %s WHERE id = %s', $this->table, $id);
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        return $pdo->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * retrieve all record from model
     *
     * @return array|false
     */
    public function all(): bool|array
    {
        $query = sprintf('SELECT * FROM %s', $this->table);
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
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
        $query = sprintf('SELECT * FROM %s WHERE %s %s "%s"', $this->table, $column, $operator, $value);
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
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

        var_dump($query);

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
     * @param Model $model
     * @return array|false
     */
    public function hasOne(Model $model)
    {
        $reflection = new \ReflectionObject($this);
        $query = sprintf('SELECT * FROM %s WHERE %s "%s"', $model->table, $reflection->getName() . '.id', $reflection->getProperty('primaryKey'));
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        return $pdo->fetchAll(PDO::FETCH_ASSOC);
    }
}