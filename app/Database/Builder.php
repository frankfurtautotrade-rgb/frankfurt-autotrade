<?php

declare(strict_types=1);

namespace App\Database;

final class Builder
{
    /**
     * Table name.
     */
    protected string $table = '';

    /**
     * Selected columns.
     */
    protected array $columns = ['*'];

    /**
     * WHERE clauses.
     */
    protected array $where = [];

    /**
     * Bindings.
     */
    protected array $bindings = [];

    /**
     * ORDER BY clauses.
     */
    protected array $orderBy = [];

    /**
     * GROUP BY clauses.
     */
    protected array $groupBy = [];

    /**
     * HAVING clauses.
     */
    protected array $having = [];

    /**
     * LIMIT.
     */
    protected ?int $limit = null;

    /**
     * OFFSET.
     */
    protected ?int $offset = null;

    /**
     * Constructor.
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Select columns.
     */
    public function select(array|string $columns = '*'): static
    {
        $this->columns = is_array($columns)
            ? $columns
            : [$columns];

        return $this;
    }

    /**
     * Add WHERE clause.
     */
    public function where(
        string $column,
        mixed $value,
        string $operator = '='
    ): static {
        $placeholder = ':w' . count($this->bindings);

        $this->where[] = "{$column} {$operator} {$placeholder}";

        $this->bindings[$placeholder] = $value;

        return $this;
    }

    /**
     * Add OR WHERE clause.
     */
    public function orWhere(
        string $column,
        mixed $value,
        string $operator = '='
    ): static {
        $placeholder = ':w' . count($this->bindings);

        if (empty($this->where)) {
            return $this->where($column, $value, $operator);
        }

        $this->where[] = "OR {$column} {$operator} {$placeholder}";

        $this->bindings[$placeholder] = $value;

        return $this;
    }

    /**
     * ORDER BY.
     */
    public function orderBy(
        string $column,
        string $direction = 'ASC'
    ): static {
        $direction = strtoupper($direction);

        $direction = $direction === 'DESC'
            ? 'DESC'
            : 'ASC';

        $this->orderBy[] = "{$column} {$direction}";

        return $this;
    }

    /**
     * GROUP BY.
     */
    public function groupBy(string $column): static
    {
        $this->groupBy[] = $column;

        return $this;
    }

    /**
     * HAVING.
     */
    public function having(
        string $expression
    ): static {
        $this->having[] = $expression;

        return $this;
    }

    /**
     * LIMIT.
     */
    public function limit(int $limit): static
    {
        $this->limit = max(0, $limit);

        return $this;
    }

    /**
     * OFFSET.
     */
    public function offset(int $offset): static
    {
        $this->offset = max(0, $offset);

        return $this;
    }

    /**
     * Return SQL.
     */
    public function toSql(): string
    {
        $sql = 'SELECT ';

        $sql .= implode(', ', $this->columns);

        $sql .= " FROM {$this->table}";

        if ($this->where) {
            $sql .= ' WHERE ';

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

        if ($this->groupBy) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        if ($this->having) {
            $sql .= ' HAVING ' . implode(' AND ', $this->having);
        }

        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
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
     * Return bindings.
     */
    public function bindings(): array
    {
        return $this->bindings;
    }

    /**
     * Reset builder.
     */
    public function reset(): static
    {
        $this->columns = ['*'];
        $this->where = [];
        $this->bindings = [];
        $this->orderBy = [];
        $this->groupBy = [];
        $this->having = [];
        $this->limit = null;
        $this->offset = null;

        return $this;
    }
}