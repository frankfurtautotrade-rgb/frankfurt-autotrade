<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Role extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'roles';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'name',
        'display_name',
        'description',
        'is_system',
        'is_active',
    ];

    /**
     * Find role by name.
     */
    public function findByName(string $name): ?array
    {
        return $this->query()
            ->where('name', strtolower(trim($name)))
            ->first();
    }

    /**
     * Get active roles.
     */
    public function active(): array
    {
        return $this->query()
            ->where('is_active', 1)
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Get system roles.
     */
    public function systemRoles(): array
    {
        return $this->query()
            ->where('is_system', 1)
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Get custom roles.
     */
    public function customRoles(): array
    {
        return $this->query()
            ->where('is_system', 0)
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Activate role.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 1,
        ]);
    }

    /**
     * Deactivate role.
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 0,
        ]);
    }

    /**
     * Check if role exists.
     */
    public function existsByName(string $name): bool
    {
        return $this->query()
            ->where('name', strtolower(trim($name)))
            ->exists();
    }

    /**
     * Count active roles.
     */
    public function activeCount(): int
    {
        return $this->query()
            ->where('is_active', 1)
            ->count();
    }
}