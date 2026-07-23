<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use PDOException;

final class QueryBuilder
{
    /**
     * Table name.
     */
    private string $table = '';

    /**
     * Columns.
     *
     * @var string[]
     */
    private array $columns = ['*'];

    /**
     * WHERE clauses.
     *
     * @var array<int,array<string,mixed>>
     */
    private array $wheres = [];

    /**
     * Query bindings.
     *
     * @var array<int,mixed>
     */
    private array $bindings = [];

    /**
     * ORDER BY clauses.
     *
     * @var string[]
     */
    private array $orders = [];

    /**
     * LIMIT.
     */
    private ?int $limit = null;

    /**
     * OFFSET.
     */
    private ?int $offset = null;

    /**
     * Constructor.
     */
    private function __construct()
    {
    }

    /**
     * Start a query.
     */
    public static function table(string $table): self
    {
        $builder = new self();

        $builder->table = trim($table);

        if ($builder->table === '') {
            throw new InvalidArgumentException(
                'Table name cannot be empty.'
            );
        }

        return $builder;
    }

    /**
     * Select columns.
     */
    public function select(string ...$columns): self
    {
        if ($columns !== []) {
            $this->columns = $columns;
        }

        return $this;
    }

    /**
     * Reset selected columns.
     */
    public function selectAll(): self
    {
        $this->columns = ['*'];

        return $this;
    }

    /**
     * Add WHERE clause.
     */
    public function where(
        string $column,
        mixed $value,
        string $operator = '='
    ): self {

        $this->wheres[] = [
            'boolean' => 'AND',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];

        $this->bindings[] = $value;

        return $this;
    }

    /**
     * Add OR WHERE clause.
     */
    public function orWhere(
        string $column,
        mixed $value,
        string $operator = '='
    ): self {

        $this->wheres[] = [
            'boolean' => 'OR',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];

        $this->bindings[] = $value;

        return $this;
    }

        /**
     * WHERE IN.
     */
    public function whereIn(string $column, array $values): self
    {
        if ($values === []) {
            throw new InvalidArgumentException(
                'whereIn() requires at least one value.'
            );
        }

        $placeholders = implode(
            ',',
            array_fill(0, count($values), '?')
        );

        $this->wheres[] = [
            'boolean' => 'AND',
            'raw' => "{$column} IN ({$placeholders})",
        ];

        foreach ($values as $value) {
            $this->bindings[] = $value;
        }

        return $this;
    }

    /**
     * WHERE NULL.
     */
    public function whereNull(string $column): self
    {
        $this->wheres[] = [
            'boolean' => 'AND',
            'raw' => "{$column} IS NULL",
        ];

        return $this;
    }

    /**
     * WHERE NOT NULL.
     */
    public function whereNotNull(string $column): self
    {
        $this->wheres[] = [
            'boolean' => 'AND',
            'raw' => "{$column} IS NOT NULL",
        ];

        return $this;
    }

    /**
     * ORDER BY.
     */
    public function orderBy(
        string $column,
        string $direction = 'ASC'
    ): self {

        $direction = strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            throw new InvalidArgumentException(
                'Invalid ORDER BY direction.'
            );
        }

        $this->orders[] = "{$column} {$direction}";

        return $this;
    }

    /**
     * LIMIT.
     */
    public function limit(int $limit): self
    {
        if ($limit < 0) {
            throw new InvalidArgumentException(
                'Limit cannot be negative.'
            );
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * OFFSET.
     */
    public function offset(int $offset): self
    {
        if ($offset < 0) {
            throw new InvalidArgumentException(
                'Offset cannot be negative.'
            );
        }

        $this->offset = $offset;

        return $this;
    }

        /**
     * Build SELECT SQL.
     */
    private function compileSelect(): string
    {
        $sql = sprintf(
            'SELECT %s FROM %s',
            implode(', ', $this->columns),
            $this->table
        );

        if ($this->wheres !== []) {

            $sql .= ' WHERE ';

            foreach ($this->wheres as $index => $where) {

                if ($index > 0) {
                    $sql .= ' ' . $where['boolean'] . ' ';
                }

                if (isset($where['raw'])) {
                    $sql .= $where['raw'];
                    continue;
                }

                $sql .= sprintf(
                    '%s %s ?',
                    $where['column'],
                    $where['operator']
                );
            }
        }

        if ($this->orders !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }

    /**
     * Get all rows.
     *
     * @return array<int,array<string,mixed>>
     */
    public function get(): array
    {
        $sql = $this->compileSelect();

        try {
            return Database::fetchAll(
                $sql,
                $this->bindings
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Get first row.
     *
     * @return array<string,mixed>|null
     */
    public function first(): ?array
    {
        $this->limit(1);

        $result = $this->get();

        return $result[0] ?? null;
    }

        /**
     * Get a single column value.
     */
    public function value(string $column): mixed
    {
        $this->select($column);

        $row = $this->first();

        if ($row === null) {
            return null;
        }

        return $row[$column] ?? null;
    }

    /**
     * Count records.
     */
    public function count(): int
    {
        $originalColumns = $this->columns;

        $this->columns = ['COUNT(*) AS total'];

        $row = $this->first();

        $this->columns = $originalColumns;

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Determine if records exist.
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Pluck a column.
     *
     * @return array<int,mixed>
     */
    public function pluck(string $column): array
    {
        $this->select($column);

        $rows = $this->get();

        return array_column($rows, $column);
    }

    /**
     * Execute raw SQL.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function raw(
        string $sql,
        array $bindings = []
    ): array {
        return Database::fetchAll($sql, $bindings);
    }

        /**
     * Insert record.
     */
    public function insert(array $data): bool
    {
        if ($data === []) {
            throw new InvalidArgumentException(
                'Insert data cannot be empty.'
            );
        }

        $columns = array_keys($data);

        $placeholders = implode(
            ', ',
            array_fill(0, count($columns), '?')
        );

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            $placeholders
        );

        return Database::statement(
            $sql,
            array_values($data)
        );
    }

    /**
     * Update records.
     */
    public function update(array $data): bool
    {
        if ($data === []) {
            throw new InvalidArgumentException(
                'Update data cannot be empty.'
            );
        }

        $set = [];

        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
        }

        $sql = sprintf(
            'UPDATE %s SET %s',
            $this->table,
            implode(', ', $set)
        );

        if ($this->wheres !== []) {

            $sql .= ' WHERE ';

            foreach ($this->wheres as $index => $where) {

                if ($index > 0) {
                    $sql .= ' ' . $where['boolean'] . ' ';
                }

                if (isset($where['raw'])) {
                    $sql .= $where['raw'];
                    continue;
                }

                $sql .= sprintf(
                    '%s %s ?',
                    $where['column'],
                    $where['operator']
                );
            }
        }

        return Database::statement(
            $sql,
            array_merge(
                array_values($data),
                $this->bindings
            )
        );
    }

        /**
     * Delete records.
     */
    public function delete(): bool
    {
        $sql = sprintf(
            'DELETE FROM %s',
            $this->table
        );

        if ($this->wheres !== []) {

            $sql .= ' WHERE ';

            foreach ($this->wheres as $index => $where) {

                if ($index > 0) {
                    $sql .= ' ' . $where['boolean'] . ' ';
                }

                if (isset($where['raw'])) {
                    $sql .= $where['raw'];
                    continue;
                }

                $sql .= sprintf(
                    '%s %s ?',
                    $where['column'],
                    $where['operator']
                );
            }
        }

        return Database::statement(
            $sql,
            $this->bindings
        );
    }

    /**
     * Increment a numeric column.
     */
    public function increment(
        string $column,
        int $amount = 1
    ): bool {

        $sql = sprintf(
            'UPDATE %s SET %s = %s + ?',
            $this->table,
            $column,
            $column
        );

        $bindings = [$amount];

        if ($this->wheres !== []) {

            $sql .= ' WHERE ';

            foreach ($this->wheres as $index => $where) {

                if ($index > 0) {
                    $sql .= ' ' . $where['boolean'] . ' ';
                }

                if (isset($where['raw'])) {
                    $sql .= $where['raw'];
                    continue;
                }

                $sql .= sprintf(
                    '%s %s ?',
                    $where['column'],
                    $where['operator']
                );
            }

            $bindings = array_merge(
                $bindings,
                $this->bindings
            );
        }

        return Database::statement(
            $sql,
            $bindings
        );
    }

        /**
     * Decrement a numeric column.
     */
    public function decrement(
        string $column,
        int $amount = 1
    ): bool {

        $sql = sprintf(
            'UPDATE %s SET %s = %s - ?',
            $this->table,
            $column,
            $column
        );

        $bindings = [$amount];

        if ($this->wheres !== []) {

            $sql .= ' WHERE ';

            foreach ($this->wheres as $index => $where) {

                if ($index > 0) {
                    $sql .= ' ' . $where['boolean'] . ' ';
                }

                if (isset($where['raw'])) {
                    $sql .= $where['raw'];
                    continue;
                }

                $sql .= sprintf(
                    '%s %s ?',
                    $where['column'],
                    $where['operator']
                );
            }

            $bindings = array_merge(
                $bindings,
                $this->bindings
            );
        }

        return Database::statement(
            $sql,
            $bindings
        );
    }

    /**
     * Get last inserted ID.
     */
    public function lastInsertId(): string
    {
        return Database::lastInsertId();
    }

    /**
     * Get generated SQL.
     */
    public function toSql(): string
    {
        return $this->compileSelect();
    }

    /**
     * Get current bindings.
     *
     * @return array<int,mixed>
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * Reset the builder.
     */
    public function reset(): self
    {
        $this->columns = ['*'];
        $this->wheres = [];
        $this->bindings = [];
        $this->orders = [];
        $this->limit = null;
        $this->offset = null;

        return $this;
    }

        /**
     * Clone builder.
     */
    public function copy(): self
    {
        return clone $this;
    }

    /**
     * String representation.
     */
    public function __toString(): string
    {
        return $this->toSql();
    }
}