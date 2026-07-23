<?php

declare(strict_types=1);

namespace App\Core;

class Attributes
{
    /**
     * Current attribute values.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Original values loaded from the database.
     *
     * @var array<string, mixed>
     */
    protected array $original = [];

    /**
     * Constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->syncOriginal();
    }

    /**
     * Fill attributes.
     */
    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Get an attribute.
     */
    public function get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set an attribute.
     */
    public function set(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Determine if an attribute exists.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * Return all attributes.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->attributes;
    }

    /**
     * Convert to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Sync current values as original.
     */
    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    /**
     * Get original values.
     *
     * @return array<string, mixed>
     */
    public function getOriginal(): array
    {
        return $this->original;
    }

    /**
     * Determine if any attribute has changed.
     */
    public function isDirty(): bool
    {
        return $this->attributes !== $this->original;
    }

    /**
     * Get changed attributes only.
     *
     * @return array<string, mixed>
     */
    public function getDirty(): array
    {
        $dirty = [];

        foreach ($this->attributes as $key => $value) {
            if (
                !array_key_exists($key, $this->original)
                || $this->original[$key] !== $value
            ) {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Magic getter.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Magic setter.
     */
    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Magic isset().
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }
}