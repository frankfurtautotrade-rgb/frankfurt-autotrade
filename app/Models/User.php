<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDO;

final class User extends Model
{
    /**
     * Find a user by ID.
     */
    public function findById(int $id): array|false
    {
        $sql = "
            SELECT *
            FROM users
            WHERE id = :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find a user by email.
     */
    public function findByEmail(string $email): array|false
    {
        $sql = "
            SELECT *
            FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => strtolower(trim($email)),
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update the user's last login timestamp.
     */
    public function updateLastLogin(int $id): bool
    {
        $sql = "
            UPDATE users
            SET last_login = NOW()
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
        ]);
    }

    /**
     * Create a new user.
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO users (
                role_id,
                first_name,
                last_name,
                email,
                password,
                phone,
                language,
                is_active
            )
            VALUES (
                :role_id,
                :first_name,
                :last_name,
                :email,
                :password,
                :phone,
                :language,
                :is_active
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'role_id'    => (int) $data['role_id'],
            'first_name' => trim($data['first_name']),
            'last_name'  => trim($data['last_name']),
            'email'      => strtolower(trim($data['email'])),
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone'      => $data['phone'] ?? null,
            'language'   => $data['language'] ?? 'de',
            'is_active'  => (bool) ($data['is_active'] ?? true),
        ]);
    }
}