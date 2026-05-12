<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public const STATUS_READY_TO_SEND = 'ready_to_send';
    public const STATUS_PENDING_INVOICE_REVIEW = 'pending_invoice_review';
    public const STATUS_NEEDS_REVIEW = 'needs_review';
    public const STATUS_INVOICE_APPROVED = 'invoice_approved';
    public const STATUS_SHIP_REQUESTED = 'ship_requested';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    public const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'client_id',
        'tracking_number',
        'width',
        'height',
        'length',
        'weight',
        'contents_description',
        'status',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
        ];
    }

    public static function labels(): array
    {
        return [
            self::STATUS_READY_TO_SEND => 'Ready to Send',
            self::STATUS_PENDING_INVOICE_REVIEW => 'Pending Invoice Review',
            self::STATUS_NEEDS_REVIEW => 'Needs Review',
            self::STATUS_INVOICE_APPROVED => 'Invoice Approved',
            self::STATUS_SHIP_REQUESTED => 'Ship Requested',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_READY_FOR_PICKUP => 'Ready for Pickup',
            self::STATUS_DELIVERED => 'Delivered',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::labels()[$this->status] ?? $this->status;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function shipRequests(): BelongsToMany
    {
        return $this->belongsToMany(ShipRequest::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(PackageStatusHistory::class);
    }

    public function transitionTo(string $newStatus, ?int $changedBy = null): void
    {
        $allowed = [
            self::STATUS_READY_TO_SEND => [self::STATUS_PENDING_INVOICE_REVIEW],
            self::STATUS_PENDING_INVOICE_REVIEW => [self::STATUS_INVOICE_APPROVED, self::STATUS_NEEDS_REVIEW],
            self::STATUS_NEEDS_REVIEW => [self::STATUS_PENDING_INVOICE_REVIEW],
            self::STATUS_INVOICE_APPROVED => [self::STATUS_SHIP_REQUESTED],
            self::STATUS_SHIP_REQUESTED => [self::STATUS_SHIPPED],
            self::STATUS_SHIPPED => [self::STATUS_READY_FOR_PICKUP, self::STATUS_DELIVERED],
            self::STATUS_READY_FOR_PICKUP => [self::STATUS_DELIVERED],
            self::STATUS_DELIVERED => [],
        ];

        $current = $this->status;
        if (! in_array($newStatus, $allowed[$current] ?? [], true)) {
            abort(422, "Invalid status transition from {$current} to {$newStatus}");
        }

        $this->update(['status' => $newStatus]);

        $this->statusHistory()->create([
            'old_status' => $current,
            'new_status' => $newStatus,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]);
    }
}
