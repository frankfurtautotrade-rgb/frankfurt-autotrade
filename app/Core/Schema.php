<?php

declare(strict_types=1);

namespace App\Core;

final class Schema
{
    /**
     * Create a table.
     */
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);

        $callback($blueprint);

        Database::statement(
            $blueprint->toSql()
        );
    }

    /**
     * Drop a table.
     */
    public static function drop(string $table): void
    {
        Database::statement(
            "DROP TABLE IF EXISTS {$table}"
        );
    }

    /**
     * Determine whether a table exists.
     */
    public static function hasTable(string $table): bool
    {
        $statement = Database::prepare(
            "SHOW TABLES LIKE ?"
        );

        $statement->execute([$table]);

        return $statement->fetch() !== false;
    }
}