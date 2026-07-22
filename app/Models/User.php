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
        'role_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'language',
        'is_active',
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
        $data['email'] = strtolower(trim($data['email']));

        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        $data['phone'] ??= null;
        $data['language'] ??= 'de';
        $data['is_active'] ??= true;

        return $this->create($data);
    }

    /**
     * Update the last login timestamp.
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
            ORDER BY first_name, last_name
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
     * Change a user's password.
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
     * Update user status.
     */
    private function setStatus(int $id, bool $active): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET is_active = :active
            WHERE {$this->primaryKey} = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'     => $id,
            'active' => $active,
        ]);
    }
}