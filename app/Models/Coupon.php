<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order_value', 'max_discount',
        'usage_limit', 'used_count', 'is_active', 'starts_at', 'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderTotal)
    {
        if ($this->min_order_value && $orderTotal < $this->min_order_value) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderTotal);
        }

        if ($this->type === 'percent') {
            $discount = ($this->value / 100) * $orderTotal;
            if ($this->max_discount) {
                return min($discount, $this->max_discount);
            }
            return $discount;
        }

        return 0;
    }
}
