<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\MigrateCommand;

final class Console
{
    public function run(array $argv): int
    {
        echo PHP_EOL;
        echo "======================================" . PHP_EOL;
        echo " Frankfurt AutoTrade Framework CLI" . PHP_EOL;
        echo " Version 1.1.0" . PHP_EOL;
        echo "======================================" . PHP_EOL;
        echo PHP_EOL;

        if (!isset($argv[1])) {
            $this->showHelp();
            return 0;
        }

        switch ($argv[1]) {

            case 'migrate':
                return (new MigrateCommand())->handle();

            case 'seed':
                echo "Running seeders..." . PHP_EOL;
                return 0;

            default:
                echo "Unknown command: {$argv[1]}" . PHP_EOL;
                echo PHP_EOL;
                $this->showHelp();
                return 1;
        }
    }

    private function showHelp(): void
    {
        echo "Available Commands" . PHP_EOL;
        echo "------------------" . PHP_EOL;
        echo "migrate    Run database migrations" . PHP_EOL;
        echo "seed       Run database seeders" . PHP_EOL;
        echo PHP_EOL;
    }
}