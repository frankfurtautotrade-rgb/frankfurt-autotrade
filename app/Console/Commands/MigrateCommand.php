<?php

declare(strict_types=1);

namespace App\Console\Commands;

final class MigrateCommand
{
    public function handle(): int
    {
        echo PHP_EOL;
        echo "Running migrations..." . PHP_EOL;
        echo PHP_EOL;

        $migrationPath = dirname(__DIR__, 3) . '/database/migrations';

        if (!is_dir($migrationPath)) {
            echo "Migration directory not found." . PHP_EOL;
            return 1;
        }

        $files = glob($migrationPath . '/*.sql');

        if ($files === false || count($files) === 0) {
            echo "No migration files found." . PHP_EOL;
            return 0;
        }

        sort($files);

        echo "Found " . count($files) . " migration(s):" . PHP_EOL;
        echo PHP_EOL;

        foreach ($files as $file) {
            echo " - " . basename($file) . PHP_EOL;
        }

        echo PHP_EOL;
        echo "Ready to execute migrations." . PHP_EOL;

        return 0;
    }
}