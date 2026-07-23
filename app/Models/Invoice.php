<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Invoice extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'invoices';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'invoice_number',
        'sale_id',
        'customer_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'paid_amount',
        'balance_due',
        'payment_status',
        'payment_method',
        'notes',
    ];

    /**
     * Find invoice by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('invoice_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Paid invoices.
     */
    public function paid(): array
    {
        return $this->query()
            ->where('payment_status', 'paid')
            ->orderBy('invoice_date', 'DESC')
            ->get();
    }

    /**
     * Unpaid invoices.
     */
    public function unpaid(): array
    {
        return $this->query()
            ->where('payment_status', 'unpaid')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Overdue invoices.
     */
    public function overdue(): array
    {
        return $this->query()
            ->where('payment_status', 'unpaid')
            ->where('due_date', date('Y-m-d'), '<')
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(int $id): bool
    {
        return $this->update($id, [
            'payment_status' => 'paid',
            'balance_due'    => 0,
        ]);
    }

    /**
     * Mark invoice as unpaid.
     */
    public function markAsUnpaid(int $id): bool
    {
        return $this->update($id, [
            'payment_status' => 'unpaid',
        ]);
    }
}