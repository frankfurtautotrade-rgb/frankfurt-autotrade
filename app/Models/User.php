<?php

namespace App\Models;

use Core\Database;
use PDO;

class User
{
    /**
     * Database connection.
     */
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Find user by ID.
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
            'id' => $id
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find user by email.
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
            'email' => $email
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update last login timestamp.
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
            'id' => $id
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
            'role_id'    => $data['role_id'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone'      => $data['phone'] ?? null,
            'language'   => $data['language'] ?? 'de',
            'is_active'  => $data['is_active'] ?? true,
        ]);
    }
}