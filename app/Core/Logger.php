<?php

declare(strict_types=1);

namespace Core;

use DateTime;

final class Logger
{
    private const LOG_DIRECTORY = STORAGE_PATH . '/logs';

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        if (!is_dir(self::LOG_DIRECTORY)) {
            mkdir(self::LOG_DIRECTORY, 0755, true);
        }

        $file = self::LOG_DIRECTORY . '/' . date('Y-m-d') . '.log';

        $date = new DateTime();

        $entry = sprintf(
            "[%s] [%s] %s %s%s",
            $date->format('Y-m-d H:i:s'),
            $level,
            $message,
            empty($context) ? '' : json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            PHP_EOL
        );

        file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }
}