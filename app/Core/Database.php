<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    /**
     * Singleton PDO instance.
     */
    private static ?PDO $connection = null;

    /**
     * Get the database connection.
     */
    public static function connection(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    DB_DSN,
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new RuntimeException(
                    'Database connection failed.',
                    0,
                    $e
                );
            }
        }

        return self::$connection;
    }

    /**
     * Begin a database transaction.
     */
    public static function beginTransaction(): bool
    {
        return self::connection()->beginTransaction();
    }

    /**
     * Commit the current transaction.
     */
    public static function commit(): bool
    {
        return self::connection()->commit();
    }

    /**
     * Roll back the current transaction.
     */
    public static function rollBack(): bool
    {
        return self::connection()->rollBack();
    }

    /**
     * Determine whether a transaction is active.
     */
    public static function inTransaction(): bool
    {
        return self::connection()->inTransaction();
    }

    /**
     * Prevent creating instances.
     */
    private function __construct()
    {
    }

    /**
     * Prevent cloning.
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserialization.
     */
    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize Database.');
    }

    /**
     * Prevent serialization.
     */
    public function __serialize(): array
    {
        throw new RuntimeException('Cannot serialize Database.');
    }

    /**
     * Prevent unserialization (PHP 8.1+).
     */
    public function __unserialize(array $data): void
    {
        throw new RuntimeException('Cannot unserialize Database.');
    }
}