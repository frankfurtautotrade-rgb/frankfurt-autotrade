<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Finance extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'finances';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'finance_number',
        'sale_id',
        'customer_id',
        'vehicle_id',
        'finance_company',
        'contract_number',
        'loan_amount',
        'down_payment',
        'interest_rate',
        'monthly_payment',
        'term_months',
        'balloon_payment',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    /**
     * Find finance by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('finance_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Active finance contracts.
     */
    public function active(): array
    {
        return $this->query()
            ->where('status', 'active')
            ->orderBy('start_date', 'DESC')
            ->get();
    }

    /**
     * Pending finance contracts.
     */
    public function pending(): array
    {
        return $this->query()
            ->where('status', 'pending')
            ->orderBy('start_date', 'DESC')
            ->get();
    }

    /**
     * Completed finance contracts.
     */
    public function completed(): array
    {
        return $this->query()
            ->where('status', 'completed')
            ->orderBy('end_date', 'DESC')
            ->get();
    }

    /**
     * Cancel finance contract.
     */
    public function cancel(int $id): bool
    {
        return $this->update($id, [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Activate finance contract.
     */
    public function activate(int $id): bool
    {
        return $this->update($id, [
            'status' => 'active',
        ]);
    }

    /**
     * Complete finance contract.
     */
    public function complete(int $id): bool
    {
        return $this->update($id, [
            'status' => 'completed',
        ]);
    }
}