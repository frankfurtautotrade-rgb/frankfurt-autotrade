<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Supplier extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'suppliers';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'supplier_number',
        'company',
        'contact_person',
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
        'bank_name',
        'iban',
        'bic',
        'notes',
        'is_active',
    ];

    /**
     * Active suppliers.
     */
    public function active(): array
    {
        return $this->query()
            ->where('is_active', 1)
            ->orderBy('company')
            ->get();
    }

    /**
     * Find supplier by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('supplier_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Find supplier by email.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->query()
            ->where('email', strtolower(trim($email)))
            ->first();
    }

    /**
     * Activate supplier.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 1,
        ]);
    }

    /**
     * Deactivate supplier.
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, [
            'is_active' => 0,
        ]);
    }
}