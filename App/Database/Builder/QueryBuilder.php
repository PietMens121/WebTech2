<?php

namespace App\Database\Builder;

use App\Database\Model;
use App\Database\MySQL;
use PDO;

class QueryBuilder
{
    private array $fields = [];

    private array $conditions = [];

    private array $from = [];

    private array $joins = [];

    public string $table;

    public function __toString(): string
    {
        if (!$this->fields) {
            $this->fields = ['*'];
        }
        $where = $this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions);
        return 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . ' ' . implode('', $this->joins)
            . $where;
    }

    public function select(string ...$select): self
    {
        $this->fields = $select;
        return $this;
    }

    public function where(string ...$where): self
    {
        foreach ($where as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }

    public function from(string $table, ?string $alias = null): self
    {
        if ($alias === null) {
            $this->from[] = $table;
        } else {
            $this->from[] = "{$table} AS {$alias}";
        }
        return $this;
    }

    public function join(string $table, string $constraint, string $operator, $constraint2): self
    {
        $this->joins[] = new JoinClause('INNER', $table, $constraint, $operator, $constraint2);

        return $this;
    }

    public function get(): array
    {
        $sql = new MySQL();
        $sql = $sql->connect()->prepare($this);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach($results as $result){
            echo '<pre>' . var_dump($result['name']) . '</pre>';
        }

        return $results;
//        // prepare and fetch all results
//        $pdo = $this->conn->prepare($query);
//        $pdo->execute();
//        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
//
////        In case no results found return this empty array
//        $models = array();
//
////        Go through all results
//        foreach($result as $model){
////            Create new models
//            $reflect = (new ReflectionClass($this))->getName();
//            $new_model = new $reflect();
//            foreach($model as $key => $value){
////                Assign properties to the models
//                $new_model->{$key} = $value;
//            }
////            Push the models to array
//            $models[] = $new_model;
//        }
////          return the models or return empty array
//        return $models;
    }
}