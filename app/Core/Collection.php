<?php

declare(strict_types=1);

namespace App\Core;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, Countable
{
    /**
     * Collection items.
     *
     * @var array<int, mixed>
     */
    protected array $items = [];

    /**
     * Constructor.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Return all items.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get first item.
     */
    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get last item.
     */
    public function last(): mixed
    {
        return empty($this->items)
            ? null
            : $this->items[array_key_last($this->items)];
    }

    /**
     * Number of items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Is collection empty?
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Iterator.
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}