<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

final class Database
{
    /**
     * Singleton PDO connection.
     */
    private static ?PDO $connection = null;

    /**
     * Get the PDO connection.
     */
    public static function connection(): PDO
    {
        if (self::$connection === null) {

            $driver   = Config::get('database.driver', 'mysql');
            $host     = Config::get('database.host', '127.0.0.1');
            $port     = Config::get('database.port', 3306);
            $database = Config::get('database.database');
            $username = Config::get('database.username');
            $password = Config::get('database.password');
            $charset  = Config::get('database.charset', 'utf8mb4');

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $driver,
                $host,
                $port,
                $database,
                $charset
            );

            try {

                self::$connection = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );

            } catch (PDOException $e) {

                Logger::critical(
                    'Database connection failed: ' . $e->getMessage()
                );

                throw new RuntimeException(
                    'Unable to connect to the database.',
                    0,
                    $e
                );
            }
        }

        return self::$connection;
    }

    /**
     * Get PDO instance.
     */
    public static function pdo(): PDO
    {
        return self::connection();
    }

    /**
     * Disconnect from the database.
     */
    public static function disconnect(): void
    {
        self::$connection = null;
    }

    /**
     * Reconnect to the database.
     */
    public static function reconnect(): PDO
    {
        self::disconnect();

        return self::connection();
    }

    /**
     * Begin transaction.
     */
    public static function beginTransaction(): bool
    {
        return self::connection()->beginTransaction();
    }

    /**
     * Commit transaction.
     */
    public static function commit(): bool
    {
        return self::connection()->commit();
    }

    /**
     * Roll back transaction.
     */
    public static function rollBack(): bool
    {
        return self::connection()->rollBack();
    }

    /**
     * Check if currently inside a transaction.
     */
    public static function inTransaction(): bool
    {
        return self::connection()->inTransaction();
    }

    /**
     * Execute a raw SQL statement.
     */
    public static function statement(string $sql): bool
    {
        return self::connection()->exec($sql) !== false;
    }

    /**
     * Prepare a SQL statement.
     */
    public static function prepare(string $sql): PDOStatement
    {
        return self::connection()->prepare($sql);
    }

    /**
     * Execute a prepared query.
     */
    public static function query(
        string $sql,
        array $params = []
    ): PDOStatement {
        $statement = self::prepare($sql);

        $statement->execute($params);

        return $statement;
    }

    /**
     * Fetch a single row.
     */
    public static function fetch(
        string $sql,
        array $params = []
    ): array|false {
        return self::query($sql, $params)->fetch();
    }

    /**
     * Fetch all rows.
     */
    public static function fetchAll(
        string $sql,
        array $params = []
    ): array {
        return self::query($sql, $params)->fetchAll();
    }

    /**
     * Get the last inserted ID.
     */
    public static function lastInsertId(): string
    {
        return self::connection()->lastInsertId();
    }

    /**
     * Prevent instantiation.
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
     * Prevent serialization.
     */
    public function __serialize(): array
    {
        throw new RuntimeException(
            'Database cannot be serialized.'
        );
    }

    /**
     * Prevent unserialization.
     */
    public function __unserialize(array $data): void
    {
        throw new RuntimeException(
            'Database cannot be unserialized.'
        );
    }

    /**
     * Prevent wakeup.
     */
    public function __wakeup(): void
    {
        throw new RuntimeException(
            'Database cannot be unserialized.'
        );
    }
}