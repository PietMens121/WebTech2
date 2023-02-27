<?php

namespace App\Database;

use PDO;

class MySQL
{
    private string $servername;
    private string $database;
    private string $username;
    private string $password;
    private string $port;

    private static PDO $connection;

    public function __construct()
    {
        $this->servername = getenv('DB_HOST');
        $this->database = getenv('DB_DATABASE');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->port = getenv('DB_PORT');

        self::$connection = $this->connect();
    }


    /**
     * establish the connection with database
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        return new PDO(
            sprintf('mysql:host=%s;dbname=%s', $this->servername, $this->database),
            $this->username,
            $this->password
        );
    }
}