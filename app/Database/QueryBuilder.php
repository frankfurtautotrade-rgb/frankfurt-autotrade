<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOStatement;

final class QueryBuilder extends Builder
{
    /**
     * Database connection.
     */
    private PDO $db;

    /**
     * Constructor.
     */
    public function __construct(PDO $db, string $table)
    {
        parent::__construct($table);

        $this->db = $db;
    }

    /**
     * Execute a prepared statement.
     */
    private function execute(string $sql, array $bindings = []): PDOStatement
    {
        $statement = $this->db->prepare($sql);

        foreach ($bindings as $key => $value) {
            $statement->bindValue($key, $value);
        }

        $statement->execute();

        return $statement;
    }

    /**
     * Get all rows.
     */
    public function get(): array
    {
        $statement = $this->execute(
            $this->toSql(),
            $this->bindings()
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get first row.
     */
    public function first(): ?array
    {
        $this->limit(1);

        $statement = $this->execute(
            $this->toSql(),
            $this->bindings()
        );

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Count rows.
     */
    public function count(): int
    {
        $sql = str_replace(
            'SELECT *',
            'SELECT COUNT(*)',
            $this->toSql()
        );

        return (int) $this->execute(
            $sql,
            $this->bindings()
        )->fetchColumn();
    }

    /**
     * Determine whether records exist.
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Insert a record.
     */
    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));

        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "
            INSERT INTO {$this->table}
            ({$columns})
            VALUES
            ({$placeholders})
        ";

        return $this->execute($sql, $data)->rowCount() > 0;
    }

    /**
     * Update matching records.
     */
    public function update(array $data): bool
    {
        $set = [];

        foreach ($data as $column => $value) {
            $set[] = "{$column} = :{$column}";
        }

        $sql = "
            UPDATE {$this->table}
            SET " . implode(', ', $set);

        if ($this->where) {
            $sql .= " WHERE ";

            $first = true;

            foreach ($this->where as $condition) {

                if (!$first && !str_starts_with($condition, 'OR ')) {
                    $sql .= ' AND ';
                }

                if (str_starts_with($condition, 'OR ')) {
                    $sql .= ' OR ';
                    $condition = substr($condition, 3);
                }

                $sql .= $condition;

                $first = false;
            }
        }

        $bindings = array_merge(
            $data,
            $this->bindings()
        );

        return $this->execute(
            $sql,
            $bindings
        )->rowCount() > 0;
    }

    /**
     * Delete matching records.
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        if ($this->where) {

            $sql .= " WHERE ";

            $first = true;

            foreach ($this->where as $condition) {

                if (!$first && !str_starts_with($condition, 'OR ')) {
                    $sql .= ' AND ';
                }

                if (str_starts_with($condition, 'OR ')) {
                    $sql .= ' OR ';
                    $condition = substr($condition, 3);
                }

                $sql .= $condition;

                $first = false;
            }
        }

        return $this->execute(
            $sql,
            $this->bindings()
        )->rowCount() > 0;
    }

    /**
     * Return the last inserted ID.
     */
    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    /**
     * Paginate results.
     */
    public function paginate(
        int $page = 1,
        int $perPage = 20
    ): array {
        $page = max(1, $page);

        $this->limit($perPage);

        $this->offset(
            ($page - 1) * $perPage
        );

        return $this->get();
    }
}