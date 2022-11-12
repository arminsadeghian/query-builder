<?php

namespace App\QueryBuilder;

use App\Contracts\DatabaseConnectionInterface;
use PDO;
use PDOStatement;

class PDOQueryBuilder
{
    private PDO $connection;
    private string $table;
    private PDOStatement $statement;
    private array $values = [];
    private $conditions;

    public function __construct(DatabaseConnectionInterface $connection)
    {
        $this->connection = $connection->getConnection();
    }

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $column, string $value)
    {
        if (is_null($this->conditions)) {
            $this->conditions = " {$column} = ? ";
        } else {
            $this->conditions .= " AND {$column} = ? ";
        }
        $this->values[] = $value;
        return $this;
    }

    public function whereNot(string $column, string $value)
    {
        if (is_null($this->conditions)) {
            $this->conditions = " NOT {$column} = ? ";
        } else {
            $this->conditions .= " AND NOT {$column} = ? ";
        }
        $this->values[] = $value;
        return $this;
    }

    public function whereLike(string $column, string $likeOperator, array $columns = ['*'])
    {
        $columns = implode(',', $columns);
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$column} LIKE '{$likeOperator}'";
        $this->execute($sql);
        return $this->statement->fetchAll($this->connection::FETCH_OBJ);
    }

    public function create(array $data)
    {
        $placeholder = [];

        foreach ($data as $column => $value) {
            $placeholder[] = '?';
        }

        $fields = implode(',', array_keys($data));
        $placeholder = implode(',', $placeholder);
        $this->values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";
        $this->execute($sql);
        return (int)$this->connection->lastInsertId();
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        $this->execute($sql);
        return $this->statement->fetchAll($this->connection::FETCH_OBJ);
    }

    public function get(array $columns = ['*'])
    {
        $columns = implode(',', $columns);
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->conditions}";
        $this->execute($sql);
        return $this->statement->fetchAll();
    }

    public function first(array $columns = ['*'])
    {
        $data = $this->get($columns);
        return empty($data) ? null : $data[0];
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        $this->execute($sql);
        return $this->statement->fetch($this->connection::FETCH_OBJ);
    }

    public function findBy(string $column, string $value)
    {
        return $this->where($column, $value)->first();
    }

    public function orderBy(string $column, string $orderBy = 'ASC')
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$column} {$orderBy} ";
        $this->execute($sql);
        return $this->statement->fetch($this->connection::FETCH_OBJ);
    }

    public function between(string $column, array $data)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} BETWEEN {$data[0]} AND {$data[1]}";
        $this->execute($sql);
        return $this->statement->fetch($this->connection::FETCH_OBJ);
    }

    public function update(array $data)
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $fields[] = "{$column} = '{$value}' ";
        }
        $fields = implode(',', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->conditions}";
        $this->execute($sql);
        return $this->statement->rowCount();
    }

    public function deleteById(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->execute($sql);
        return $this->statement->rowCount();
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->conditions}";
        $this->execute($sql);
        return $this->statement->rowCount();
    }

    public function truncate()
    {
        $sql = "TRUNCATE TABLE {$this->table}";
        $this->execute($sql);
    }

    private function execute(string $sql)
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($this->values);
        $this->values = [];
        return $this;
    }

}