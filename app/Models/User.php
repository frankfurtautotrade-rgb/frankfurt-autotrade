<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

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
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'remember_token',
        'last_login',
    ];

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => strtolower(trim($email)),
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): bool
    {
        $data['name'] = trim($data['name']);
        $data['email'] = strtolower(trim($data['email']));
        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        $data['role'] ??= 'sales';
        $data['is_active'] ??= 1;
        $data['remember_token'] ??= null;
        $data['last_login'] ??= null;

        return $this->create($data);
    }

    /**
     * Update last login.
     */
    public function updateLastLogin(int $id): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET last_login = NOW()
            WHERE {$this->primaryKey} = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
        ]);
    }

    /**
     * Return all active users.
     */
    public function active(): array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE is_active = 1
            ORDER BY name ASC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Activate a user.
     */
    public function activate(int $id): bool
    {
        return $this->setStatus($id, true);
    }

    /**
     * Deactivate a user.
     */
    public function deactivate(int $id): bool
    {
        return $this->setStatus($id, false);
    }

    /**
     * Change password.
     */
    public function changePassword(int $id, string $password): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET password = :password
            WHERE {$this->primaryKey} = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'       => $id,
            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
        ]);
    }

    /**
     * Update remember token.
     */
    public function updateRememberToken(
        int $id,
        ?string $token
    ): bool {
        $sql = "
            UPDATE {$this->table}
            SET remember_token = :token
            WHERE {$this->primaryKey} = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'    => $id,
            'token' => $token,
        ]);
    }

    /**
     * Find user by remember token.
     */
    public function findByRememberToken(
        string $token
    ): ?array {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE remember_token = :token
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'token' => $token,
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Update active status.
     */
    private function setStatus(
        int $id,
        bool $active
    ): bool {
        $sql = "
            UPDATE {$this->table}
            SET is_active = :active
            WHERE {$this->primaryKey} = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
            'active' => $active ? 1 : 0,
        ]);
    }
}