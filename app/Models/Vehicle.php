<?php

namespace App\Models;

use Core\Model;
use PDO;

class Vehicle extends Model
{
    protected string $table = 'vehicles';

    /**
     * Create a new vehicle.
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO vehicles
            (
                make,
                model,
                year,
                mileage,
                price
            )
            VALUES
            (
                :make,
                :model,
                :year,
                :mileage,
                :price
            )
        ";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            'make'     => trim($data['make'] ?? ''),
            'model'    => trim($data['model'] ?? ''),
            'year'     => (int) ($data['year'] ?? 0),
            'mileage'  => (int) ($data['mileage'] ?? 0),
            'price'    => (float) ($data['price'] ?? 0),
        ]);

        if (!$success) {
            return false;
        }

        $id = (int) $this->db->lastInsertId();

        $stockNumber = 'FAT-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);

        $update = $this->db->prepare("
            UPDATE vehicles
            SET stock_number = :stock_number
            WHERE id = :id
        ");

        $update->execute([
            'stock_number' => $stockNumber,
            'id'           => $id,
        ]);

        return true;
    }

    /**
     * Get all vehicles.
     */
    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM vehicles
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find one vehicle.
     */
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vehicles
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}