<?php

namespace Models;

use Core\Model;

class Vehicle extends Model
{
    protected string $table = 'vehicles';

    public function create(array $data): bool
    {
        // Insert vehicle
        $sql = "INSERT INTO vehicles
                (make, model, year, mileage, price)
                VALUES
                (:make, :model, :year, :mileage, :price)";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            'make'     => $data['make'],
            'model'    => $data['model'],
            'year'     => $data['year'],
            'mileage'  => $data['mileage'],
            'price'    => $data['price'],
        ]);

        if (!$success) {
            return false;
        }

        // Get the new vehicle ID
        $id = (int) $this->db->lastInsertId();

        // Generate stock number
        $stockNumber = 'FAT-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);

        // Save stock number
        $update = $this->db->prepare("
            UPDATE vehicles
            SET stock_number = :stock_number
            WHERE id = :id
        ");

        $update->execute([
            'stock_number' => $stockNumber,
            'id' => $id
        ]);

        return true;
    }

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM vehicles
            ORDER BY id DESC
        ");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM vehicles
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}