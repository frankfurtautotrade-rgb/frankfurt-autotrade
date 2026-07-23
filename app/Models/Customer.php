<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Customer extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'customers';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'customer_number',
        'company',
        'salutation',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'street',
        'house_number',
        'postal_code',
        'city',
        'country',
        'vat_number',
        'tax_number',
        'date_of_birth',
        'notes',
        'is_active',
    ];

    /**
     * Active customers.
     */
    public function active(): array
    {
        return $this->query()
            ->where('is_active', 1)
            ->orderBy('last_name')
            ->get();
    }

    /**
     * Find customer by email.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->query()
            ->where('email', strtolower(trim($email)))
            ->first();
    }

    /**
     * Find customer by customer number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('customer_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Activate customer.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 1,
        ]);
    }

    /**
     * Deactivate customer.
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 0,
        ]);
    }
}