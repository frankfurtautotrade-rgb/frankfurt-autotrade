<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Purchase extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'purchases';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'purchase_number',
        'vehicle_id',
        'supplier_id',
        'purchase_price',
        'purchase_date',
        'invoice_number',
        'payment_method',
        'status',
        'notes',
    ];

    /**
     * Find purchase by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('purchase_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Pending purchases.
     */
    public function pending(): array
    {
        return $this->query()
            ->where('status', 'pending')
            ->orderBy('purchase_date', 'DESC')
            ->get();
    }

    /**
     * Completed purchases.
     */
    public function completed(): array
    {
        return $this->query()
            ->where('status', 'completed')
            ->orderBy('purchase_date', 'DESC')
            ->get();
    }

    /**
     * Cancelled purchases.
     */
    public function cancelled(): array
    {
        return $this->query()
            ->where('status', 'cancelled')
            ->orderBy('purchase_date', 'DESC')
            ->get();
    }

    /**
     * Complete purchase.
     */
    public function complete(int $id): bool
    {
        return $this->update($id, [
            'status' => 'completed',
        ]);
    }

    /**
     * Cancel purchase.
     */
    public function cancel(int $id): bool
    {
        return $this->update($id, [
            'status' => 'cancelled',
        ]);
    }
}