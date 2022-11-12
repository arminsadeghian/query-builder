<?php

namespace App\Databases;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\ConfigNotValidException;
use App\Exceptions\DatabaseConnectionException;
use PDO;

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    protected PDO $connection;
    protected array $config;

    private const REQUIRED_CONFIG_KEYS = [
        'host',
        'database',
        'db_user',
        'db_password'
    ];

    /**
     * @throws ConfigNotValidException
     */
    public function __construct(array $config)
    {
        if (!$this->isConfigValid($config)) {
            throw new ConfigNotValidException();
        }

        $this->config = $config;
    }

    public function connect(): static
    {
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['database']}";

        try {
            $this->connection = new PDO($dsn, $this->config['db_user'], $this->config['db_password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (DatabaseConnectionException $e) {
            throw new DatabaseConnectionException($e->getMessage());
        }

        return $this;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function isConfigValid(array $config): bool
    {
        $matches = array_intersect(self::REQUIRED_CONFIG_KEYS, array_keys($config));
        return count($matches) === count(self::REQUIRED_CONFIG_KEYS);
    }

}