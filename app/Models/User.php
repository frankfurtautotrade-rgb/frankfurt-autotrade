<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'users';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'role',
        'phone',
        'avatar',
        'language',
        'timezone',
        'last_login',
        'is_active',
    ];

    /**
     * Find user by ID.
     */
    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->query()
            ->where('email', strtolower(trim($email)))
            ->first();
    }

    /**
     * Find user by username.
     */
    public function findByUsername(string $username): ?array
    {
        return $this->query()
            ->where('username', trim($username))
            ->first();
    }

    /**
     * Active users.
     */
    public function active(): array
    {
        return $this->query()
            ->where('is_active', 1)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    /**
     * Users by role.
     */
    public function byRole(string $role): array
    {
        return $this->query()
            ->where('role', $role)
            ->where('is_active', 1)
            ->orderBy('last_name')
            ->get();
    }

    /**
     * Update password.
     */
    public function updatePassword(int $id, string $password): bool
    {
        return $this->update($id, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    /**
     * Update last login.
     */
    public function updateLastLogin(int $id): bool
    {
        return $this->update($id, [
            'last_login' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Activate user.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 1,
        ]);
    }

    /**
     * Deactivate user.
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 0,
        ]);
    }

    /**
     * Verify password.
     */
    public function verifyPassword(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!(bool) $user['is_active']) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        $this->updateLastLogin((int) $user['id']);

        return $user;
    }
}