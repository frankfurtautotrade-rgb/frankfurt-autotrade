<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Sale extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'sales';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'sale_number',
        'vehicle_id',
        'customer_id',
        'salesperson_id',
        'purchase_price',
        'sale_price',
        'deposit',
        'balance_due',
        'payment_method',
        'finance_company',
        'finance_reference',
        'sale_date',
        'delivery_date',
        'invoice_number',
        'status',
        'notes',
    ];

    /**
     * Find by sale number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('sale_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Pending sales.
     */
    public function pending(): array
    {
        return $this->query()
            ->where('status', 'pending')
            ->orderBy('sale_date', 'DESC')
            ->get();
    }

    /**
     * Completed sales.
     */
    public function completed(): array
    {
        return $this->query()
            ->where('status', 'completed')
            ->orderBy('sale_date', 'DESC')
            ->get();
    }

    /**
     * Cancelled sales.
     */
    public function cancelled(): array
    {
        return $this->query()
            ->where('status', 'cancelled')
            ->orderBy('sale_date', 'DESC')
            ->get();
    }

    /**
     * Complete sale.
     */
    public function complete(int $id): bool
    {
        return $this->update($id, [
            'status' => 'completed',
        ]);
    }

    /**
     * Cancel sale.
     */
    public function cancel(int $id): bool
    {
        return $this->update($id, [
            'status' => 'cancelled',
        ]);
    }
}