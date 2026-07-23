<?php

declare(strict_types=1);

namespace App\Core;

final class Blueprint
{
    /**
     * Table name.
     */
    private string $table;

    /**
     * Column definitions.
     */
    private array $columns = [];

    /**
     * Constructor.
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Auto increment primary key.
     */
    public function id(): self
    {
        $this->columns[] =
            '`id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY';

        return $this;
    }

    /**
     * VARCHAR column.
     */
    public function string(
        string $name,
        int $length = 255
    ): self {
        $this->columns[] =
            sprintf(
                '`%s` VARCHAR(%d) NOT NULL',
                $name,
                $length
            );

        return $this;
    }

    /**
     * TEXT column.
     */
    public function text(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` TEXT NOT NULL',
                $name
            );

        return $this;
    }

    /**
     * INTEGER column.
     */
    public function integer(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` INT NOT NULL',
                $name
            );

        return $this;
    }

    /**
     * BIGINT column.
     */
    public function bigInteger(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` BIGINT NOT NULL',
                $name
            );

        return $this;
    }

    /**
     * BOOLEAN column.
     */
    public function boolean(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` TINYINT(1) NOT NULL DEFAULT 0',
                $name
            );

        return $this;
    }

    /**
     * DECIMAL column.
     */
    public function decimal(
        string $name,
        int $precision = 10,
        int $scale = 2
    ): self {
        $this->columns[] =
            sprintf(
                '`%s` DECIMAL(%d,%d) NOT NULL',
                $name,
                $precision,
                $scale
            );

        return $this;
    }

    /**
     * DATE column.
     */
    public function date(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` DATE NULL',
                $name
            );

        return $this;
    }

    /**
     * DATETIME column.
     */
    public function dateTime(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` DATETIME NULL',
                $name
            );

        return $this;
    }

    /**
     * TIMESTAMP column.
     */
    public function timestamp(string $name): self
    {
        $this->columns[] =
            sprintf(
                '`%s` TIMESTAMP NULL',
                $name
            );

        return $this;
    }

    /**
     * created_at + updated_at
     */
    public function timestamps(): self
    {
        $this->columns[] =
            '`created_at` TIMESTAMP NULL DEFAULT NULL';

        $this->columns[] =
            '`updated_at` TIMESTAMP NULL DEFAULT NULL';

        return $this;
    }

    /**
     * Build CREATE TABLE SQL.
     */
    public function toSql(): string
    {
        return sprintf(
            "CREATE TABLE IF NOT EXISTS `%s` (\n%s\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
            $this->table,
            implode(",\n", $this->columns)
        );
    }
}