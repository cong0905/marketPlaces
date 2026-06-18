<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'buyer_id', 'seller_id', 'total_amount', 'status',
        'payment_method', 'shipping_address', 'shipping_name', 'shipping_phone',
        'note', 'confirmed_at', 'shipped_at', 'completed_at', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'total_amount' => 'decimal:0',
            'confirmed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8)) . '-' . time();
            }
        });
    }

    // ── Relationships ──────────────────────────────────

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // ── Accessors ──────────────────────────────────────

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }

    // ── Status Transitions ─────────────────────────────

    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        return $this->status->canTransitionTo($newStatus);
    }

    public function confirm(): bool
    {
        if (!$this->canTransitionTo(OrderStatus::CONFIRMED)) return false;
        return $this->update(['status' => OrderStatus::CONFIRMED, 'confirmed_at' => now()]);
    }

    public function ship(): bool
    {
        if (!$this->canTransitionTo(OrderStatus::SHIPPING)) return false;
        return $this->update(['status' => OrderStatus::SHIPPING, 'shipped_at' => now()]);
    }

    public function complete(): bool
    {
        if (!$this->canTransitionTo(OrderStatus::COMPLETED)) return false;
        return $this->update(['status' => OrderStatus::COMPLETED, 'completed_at' => now()]);
    }

    public function cancel(string $reason = null): bool
    {
        if (!$this->canTransitionTo(OrderStatus::CANCELLED)) return false;
        return $this->update([
            'status' => OrderStatus::CANCELLED,
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);
    }
}
