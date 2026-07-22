<?php

declare(strict_types=1);

namespace Core;

final class Language
{
    /**
     * Default application language.
     */
    private const DEFAULT_LANGUAGE = 'de';

    /**
     * Get the current language.
     */
    public static function current(): string
    {
        return Session::get('language', self::DEFAULT_LANGUAGE);
    }

    /**
     * Set the current language.
     */
    public static function set(string $language): void
    {
        Session::set('language', strtolower($language));
    }

    /**
     * Check if the current language matches.
     */
    public static function is(string $language): bool
    {
        return self::current() === strtolower($language);
    }

    /**
     * Return the default language.
     */
    public static function default(): string
    {
        return self::DEFAULT_LANGUAGE;
    }
}