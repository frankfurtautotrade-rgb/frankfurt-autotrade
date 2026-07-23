<?php

declare(strict_types=1);

namespace App\Core;

use App\Database\Database;
use App\Database\QueryBuilder;
use BadMethodCallException;
use PDO;

abstract class Model
{
    /**
     * Database connection.
     */
    protected PDO $db;

    /**
     * Database table.
     */
    protected string $table = '';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Fillable columns.
     */
    protected array $fillable = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Create a new query builder instance.
     */
    public function query(): QueryBuilder
    {
        return new QueryBuilder(
            $this->db,
            $this->table
        );
    }

    /**
     * Get all records.
     */
    public function all(): Collection
    {
        return new Collection(
            $this->query()->get()
        );
    }

    /**
     * Find record by primary key.
     */
    public function find(int|string $id): ?array
    {
        return $this->query()
            ->where($this->primaryKey, $id)
            ->first();
    }

    /**
     * Create a record.
     */
    public function create(array $data): bool
    {
        $data = $this->filterFillable($data);

        return $this->query()->insert($data);
    }

    /**
     * Update a record.
     */
    public function update(int|string $id, array $data): bool
    {
        $data = $this->filterFillable($data);

        return $this->query()
            ->where($this->primaryKey, $id)
            ->update($data);
    }

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool
    {
        return $this->query()
            ->where($this->primaryKey, $id)
            ->delete();
    }

    /**
     * Count records.
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * Check whether a record exists.
     */
    public function exists(int|string $id): bool
    {
        return $this->query()
            ->where($this->primaryKey, $id)
            ->exists();
    }

    /**
     * Get the first matching record.
     */
    public function first(): ?array
    {
        return $this->query()->first();
    }

    /**
     * Paginate records.
     */
    public function paginate(
        int $page = 1,
        int $perPage = 20
    ): Collection {
        return new Collection(
            $this->query()->paginate($page, $perPage)
        );
    }

    /**
     * Filter fillable fields.
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key(
            $data,
            array_flip($this->fillable)
        );
    }

    /**
     * Get last inserted ID.
     */
    public function lastInsertId(): string
    {
        return $this->db->lastInsertId();
    }

    /**
     * Get table name.
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get primary key.
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Forward unknown calls to QueryBuilder.
     */
    public function __call(string $method, array $arguments): mixed
    {
        $builder = $this->query();

        if (is_callable([$builder, $method])) {
            return $builder->$method(...$arguments);
        }

        throw new BadMethodCallException(
            "Method {$method} does not exist on " . static::class
        );
    }
}