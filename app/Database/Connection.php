<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

require_once dirname(__DIR__, 2) . '/config/database.php';

final class Connection
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        try {

            self::$pdo = new PDO(
                DB_DSN,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return self::$pdo;

        } catch (PDOException $e) {

            die(
                'Database connection failed: ' .
                $e->getMessage()
            );
        }
    }

    private function __construct()
    {
    }
}