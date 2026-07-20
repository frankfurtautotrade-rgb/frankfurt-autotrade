<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    /**
     * Singleton PDO instance.
     */
    private static ?PDO $connection = null;

    /**
     * Get database connection.
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

                // Never expose database errors in production.
                die('Database connection failed.');

            }

        }

        return self::$connection;
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
}