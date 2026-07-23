<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class RolePermission extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'role_permissions';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * Get all permissions for a role.
     */
    public function permissions(int $roleId): array
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->orderBy('permission_id')
            ->get();
    }

    /**
     * Get all roles for a permission.
     */
    public function roles(int $permissionId): array
    {
        return $this->query()
            ->where('permission_id', $permissionId)
            ->orderBy('role_id')
            ->get();
    }

    /**
     * Assign permission to role.
     */
    public function assign(int $roleId, int $permissionId): bool
    {
        if ($this->hasPermission($roleId, $permissionId)) {
            return true;
        }

        return $this->create([
            'role_id'       => $roleId,
            'permission_id' => $permissionId,
        ]);
    }

    /**
     * Remove permission from role.
     */
    public function revoke(int $roleId, int $permissionId): bool
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    /**
     * Check if role has permission.
     */
    public function hasPermission(int $roleId, int $permissionId): bool
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->exists();
    }

    /**
     * Remove all permissions from a role.
     */
    public function clearRole(int $roleId): bool
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Remove permission from every role.
     */
    public function clearPermission(int $permissionId): bool
    {
        return $this->query()
            ->where('permission_id', $permissionId)
            ->delete();
    }

    /**
     * Count permissions assigned to a role.
     */
    public function countPermissions(int $roleId): int
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->count();
    }

    /**
     * Count roles using a permission.
     */
    public function countRoles(int $permissionId): int
    {
        return $this->query()
            ->where('permission_id', $permissionId)
            ->count();
    }
}