<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class Migration
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function createTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function hasRun(string $migration): bool
    {
        $statement = $this->pdo->prepare(
            "SELECT COUNT(*) FROM migrations WHERE migration = ?"
        );

        $statement->execute([$migration]);

        return (bool) $statement->fetchColumn();
    }

    public function markAsRun(string $migration): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO migrations (migration) VALUES (?)"
        );

        $statement->execute([$migration]);
    }
}