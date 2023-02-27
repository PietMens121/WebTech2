<?php

namespace App\Database;

use App\Service\DotEnv;
use App\Database\MySQL;
use PDO;

abstract class Model
{
    protected string $table;
    protected PDO $conn;

    public function __construct()
    {
        $sql = new MySQL();
        $this->conn = $sql->connect();
    }

    /**
     * find record on id
     *
     * @param $id
     * @return mixed
     */
    public function find($id): mixed
    {
        $query = sprintf('SELECT * FROM %s WHERE id = %s', $this->table, $id);
        $pdo = $this->conn->prepare($query);
        $pdo->execute();
        return $pdo->fetch(PDO::FETCH_ASSOC);
    }
}