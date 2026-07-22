<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOStatement;

abstract class Model
{
    /**
     * Database connection.
     */
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Prepare and execute a query.
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->db->prepare($sql);

        $statement->execute($params);

        return $statement;
    }

    /**
     * Fetch a single row.
     */
    protected function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();

        return $result === false ? null : $result;
    }

    /**
     * Fetch multiple rows.
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Execute an INSERT, UPDATE or DELETE statement.
     */
    protected function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)->rowCount() > 0;
    }

    /**
     * Return the last inserted ID.
     */
    protected function lastInsertId(): int
    {
        return (int) $this->db->lastInsertId();
    }

    /**
     * Begin a transaction.
     */
    protected function beginTransaction(): bool
    {
        return Database::beginTransaction();
    }

    /**
     * Commit a transaction.
     */
    protected function commit(): bool
    {
        return Database::commit();
    }

    /**
     * Roll back a transaction.
     */
    protected function rollBack(): bool
    {
        return Database::rollBack();
    }

    /**
     * Determine whether a transaction is active.
     */
    protected function inTransaction(): bool
    {
        return Database::inTransaction();
    }
}