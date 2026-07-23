<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Permission extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'permissions';

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
        'module',
        'description',
        'is_system',
        'is_active',
    ];

    /**
     * Find permission by name.
     */
    public function findByName(string $name): ?array
    {
        return $this->query()
            ->where('name', strtolower(trim($name)))
            ->first();
    }

    /**
     * Permissions by module.
     */
    public function byModule(string $module): array
    {
        return $this->query()
            ->where('module', strtolower(trim($module)))
            ->where('is_active', 1)
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Active permissions.
     */
    public function active(): array
    {
        return $this->query()
            ->where('is_active', 1)
            ->orderBy('module')
            ->orderBy('display_name')
            ->get();
    }

    /**
     * System permissions.
     */
    public function systemPermissions(): array
    {
        return $this->query()
            ->where('is_system', 1)
            ->orderBy('module')
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Custom permissions.
     */
    public function customPermissions(): array
    {
        return $this->query()
            ->where('is_system', 0)
            ->orderBy('module')
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Activate permission.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 1,
        ]);
    }

    /**
     * Deactivate permission.
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 0,
        ]);
    }

    /**
     * Check if permission exists.
     */
    public function existsByName(string $name): bool
    {
        return $this->query()
            ->where('name', strtolower(trim($name)))
            ->exists();
    }

    /**
     * Count active permissions.
     */
    public function activeCount(): int
    {
        return $this->query()
            ->where('is_active', 1)
            ->count();
    }
}