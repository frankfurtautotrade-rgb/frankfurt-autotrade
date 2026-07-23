<?php

declare(strict_types=1);

namespace App\Core;

use DateTime;

final class Logger
{
    /**
     * Log file path.
     */
    private const LOG_FILE = APP_PATH . '/../storage/logs/application.log';

    /**
     * Write a log entry.
     */
    private static function write(string $level, string $message): void
    {
        $directory = dirname(self::LOG_FILE);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $date = (new DateTime())->format('Y-m-d H:i:s');

        $line = sprintf(
            "[%s] %s: %s%s",
            $date,
            strtoupper($level),
            $message,
            PHP_EOL
        );

        file_put_contents(
            self::LOG_FILE,
            $line,
            FILE_APPEND | LOCK_EX
        );
    }

    public static function debug(string $message): void
    {
        self::write('debug', $message);
    }

    public static function info(string $message): void
    {
        self::write('info', $message);
    }

    public static function warning(string $message): void
    {
        self::write('warning', $message);
    }

    public static function error(string $message): void
    {
        self::write('error', $message);
    }

    public static function critical(string $message): void
    {
        self::write('critical', $message);
    }
}