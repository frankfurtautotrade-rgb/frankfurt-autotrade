<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class UserRole extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'user_roles';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'user_id',
        'role_id',
    ];

    /**
     * Get all roles assigned to a user.
     */
    public function roles(int $userId): array
    {
        return $this->query()
            ->where('user_id', $userId)
            ->orderBy('role_id')
            ->get();
    }

    /**
     * Get all users assigned to a role.
     */
    public function users(int $roleId): array
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->orderBy('user_id')
            ->get();
    }

    /**
     * Assign role to user.
     */
    public function assign(int $userId, int $roleId): bool
    {
        if ($this->hasRole($userId, $roleId)) {
            return true;
        }

        return $this->create([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    /**
     * Remove role from user.
     */
    public function revoke(int $userId, int $roleId): bool
    {
        return $this->query()
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Check whether a user has a role.
     */
    public function hasRole(int $userId, int $roleId): bool
    {
        return $this->query()
            ->where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();
    }

    /**
     * Remove all roles from a user.
     */
    public function clearUser(int $userId): bool
    {
        return $this->query()
            ->where('user_id', $userId)
            ->delete();
    }

    /**
     * Remove all users from a role.
     */
    public function clearRole(int $roleId): bool
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Count roles assigned to a user.
     */
    public function countRoles(int $userId): int
    {
        return $this->query()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Count users assigned to a role.
     */
    public function countUsers(int $roleId): int
    {
        return $this->query()
            ->where('role_id', $roleId)
            ->count();
    }
}