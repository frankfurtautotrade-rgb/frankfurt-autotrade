<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Media extends Model
{
    /**
     * Database table.
     */
    protected string $table = 'media';

    /**
     * Primary key.
     */
    protected string $primaryKey = 'id';

    /**
     * Mass assignable fields.
     */
    protected array $fillable = [
        'vehicle_id',
        'document_id',
        'media_type',
        'title',
        'description',
        'file_name',
        'original_name',
        'file_extension',
        'mime_type',
        'file_size',
        'width',
        'height',
        'duration',
        'storage_path',
        'thumbnail_path',
        'sort_order',
        'is_featured',
        'uploaded_by',
        'uploaded_at',
        'status',
    ];

    /**
     * Get media for a vehicle.
     */
    public function byVehicle(int $vehicleId): array
    {
        return $this->query()
            ->where('vehicle_id', $vehicleId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get photos only.
     */
    public function photos(int $vehicleId): array
    {
        return $this->query()
            ->where('vehicle_id', $vehicleId)
            ->where('media_type', 'photo')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get videos only.
     */
    public function videos(int $vehicleId): array
    {
        return $this->query()
            ->where('vehicle_id', $vehicleId)
            ->where('media_type', 'video')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get featured image.
     */
    public function featured(int $vehicleId): ?array
    {
        return $this->query()
            ->where('vehicle_id', $vehicleId)
            ->where('is_featured', 1)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Set featured image.
     */
    public function setFeatured(int $mediaId, int $vehicleId): bool
    {
        $this->query()
            ->where('vehicle_id', $vehicleId)
            ->update([
                'is_featured' => 0,
            ]);

        return $this->update($mediaId, [
            'is_featured' => 1,
        ]);
    }

    /**
     * Update display order.
     */
    public function setOrder(int $mediaId, int $order): bool
    {
        return $this->update($mediaId, [
            'sort_order' => $order,
        ]);
    }

    /**
     * Active media.
     */
    public function active(): array
    {
        return $this->query()
            ->where('status', 'active')
            ->orderBy('uploaded_at', 'DESC')
            ->get();
    }

    /**
     * Archive media.
     */
    public function archive(int $id): bool
    {
        return $this->update($id, [
            'status' => 'archived',
        ]);
    }

    /**
     * Restore media.
     */
    public function restore(int $id): bool
    {
        return $this->update($id, [
            'status' => 'active',
        ]);
    }
}