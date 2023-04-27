<?php

namespace Framework\Database;

class Database
{
    public \PDO $connection;
    protected $statement;
    private static ?Database $instance = null;

    public static function getInstance($config = null): self
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function __construct(array $config)
    {
        $dsn = "mysql:host=localhost;port=3306;dbname=myapp";

        $this->connection = new \PDO($dsn, 'root', '', [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this->statement;
    }

}