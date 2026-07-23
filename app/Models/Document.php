<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Document extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'documents';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'document_number',
        'document_type',
        'entity_type',
        'entity_id',
        'title',
        'description',
        'file_name',
        'original_name',
        'file_extension',
        'mime_type',
        'file_size',
        'storage_path',
        'uploaded_by',
        'uploaded_at',
        'status',
        'notes',
    ];

    /**
     * Find document by number.
     */
    public function findByNumber(string $number): ?array
    {
        return $this->query()
            ->where('document_number', strtoupper(trim($number)))
            ->first();
    }

    /**
     * Get documents for an entity.
     */
    public function byEntity(
        string $entityType,
        int $entityId
    ): array {
        return $this->query()
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('uploaded_at', 'DESC')
            ->get();
    }

    /**
     * Get documents by type.
     */
    public function byType(string $type): array
    {
        return $this->query()
            ->where('document_type', $type)
            ->orderBy('uploaded_at', 'DESC')
            ->get();
    }

    /**
     * Active documents.
     */
    public function active(): array
    {
        return $this->query()
            ->where('status', 'active')
            ->orderBy('uploaded_at', 'DESC')
            ->get();
    }

    /**
     * Archive document.
     */
    public function archive(int $id): bool
    {
        return $this->update($id, [
            'status' => 'archived',
        ]);
    }

    /**
     * Restore document.
     */
    public function restore(int $id): bool
    {
        return $this->update($id, [
            'status' => 'active',
        ]);
    }
}