<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Expense extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'expenses';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'expense_number',
        'vehicle_id',
        'supplier_id',
        'category',
        'description',
        'invoice_number',
        'expense_date',
        'amount',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'payment_method',
        'status',
        'notes',
    ];

    /**
     * Find expense by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('expense_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Paid expenses.
     */
    public function paid(): array
    {
        return $this->query()
            ->where('status', 'paid')
            ->orderBy('expense_date', 'DESC')
            ->get();
    }

    /**
     * Pending expenses.
     */
    public function pending(): array
    {
        return $this->query()
            ->where('status', 'pending')
            ->orderBy('expense_date', 'DESC')
            ->get();
    }

    /**
     * Mark expense as paid.
     */
    public function markAsPaid(int $id): bool
    {
        return $this->update($id, [
            'status' => 'paid',
        ]);
    }

    /**
     * Mark expense as pending.
     */
    public function markAsPending(int $id): bool
    {
        return $this->update($id, [
            'status' => 'pending',
        ]);
    }

    /**
     * Vehicle expenses.
     */
    public function byVehicle(int $vehicleId): array
    {
        return $this->query()
            ->where('vehicle_id', $vehicleId)
            ->orderBy('expense_date', 'DESC')
            ->get();
    }

    /**
     * Expenses by category.
     */
    public function byCategory(string $category): array
    {
        return $this->query()
            ->where('category', $category)
            ->orderBy('expense_date', 'DESC')
            ->get();
    }
}