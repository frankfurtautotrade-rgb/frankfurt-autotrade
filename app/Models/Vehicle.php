<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Vehicle extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'vehicles';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'stock_number',
        'vin',
        'brand',
        'model',
        'variant',
        'body_type',
        'fuel_type',
        'transmission',
        'engine_size',
        'power_hp',
        'color',
        'interior_color',
        'first_registration',
        'mileage',
        'price',
        'vat_type',
        'location',
        'description',
        'status',
        'featured',
    ];

    /**
     * Available vehicles.
     */
    public function available(): array
    {
        return $this->query()
            ->where('status', 'available')
            ->orderBy('price')
            ->get();
    }

    /**
     * Sold vehicles.
     */
    public function sold(): array
    {
        return $this->query()
            ->where('status', 'sold')
            ->orderBy('first_registration', 'DESC')
            ->get();
    }

    /**
     * Find by VIN.
     */
    public function findByVin(string $vin): ?array
    {
        return $this->query()
            ->where('vin', strtoupper(trim($vin)))
            ->first();
    }

    /**
     * Find by stock number.
     */
    public function findByStockNumber(string $stockNumber): ?array
    {
        return $this->query()
            ->where('stock_number', strtoupper(trim($stockNumber)))
            ->first();
    }

    /**
     * Mark as sold.
     */
    public function markAsSold(int $id): bool
    {
        return $this->update($id, [
            'status' => 'sold',
        ]);
    }

    /**
     * Mark as available.
     */
    public function markAsAvailable(int $id): bool
    {
        return $this->update($id, [
            'status' => 'available',
        ]);
    }

    /**
     * Feature vehicle.
     */
    public function feature(int $id): bool
    {
        return $this->update($id, [
            'featured' => 1,
        ]);
    }

    /**
     * Remove featured flag.
     */
    public function unfeature(int $id): bool
    {
        return $this->update($id, [
            'featured' => 0,
        ]);
    }
}