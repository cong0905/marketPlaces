<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'price', 'is_negotiable', 'condition_percent', 'brand', 'model',
        'location_province', 'location_district', 'province_id', 'district_id', 'status', 'rejection_reason',
        'video_url', 'view_count', 'approved_at', 'sold_at', 'quantity',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
            'price' => 'decimal:0',
            'is_negotiable' => 'boolean',
            'condition_percent' => 'integer',
            'quantity' => 'integer',
            'view_count' => 'integer',
            'approved_at' => 'datetime',
            'sold_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title) . '-' . Str::random(6);
            }
        });
    }

    // ── Relationships ──────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    // ── Scopes ─────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', ProductStatus::ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', ProductStatus::PENDING);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

    public function scopeByProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%")
              ->orWhere('brand', 'LIKE', "%{$keyword}%");
        });
    }

    // ── Accessors ──────────────────────────────────────

    public function getPrimaryImageAttribute()
    {
        return $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $img = $this->primary_image;
        return $img ? $img->url_original : asset('images/no-image.png');
    }

    public function getPrimaryImageMediumUrlAttribute(): string
    {
        $img = $this->primary_image;
        return $img ? $img->url_medium : asset('images/no-image.png');
    }

    public function getPrimaryImageThumbUrlAttribute(): string
    {
        $img = $this->primary_image;
        return $img ? $img->url_thumb : asset('images/no-image.png');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    public function getFavoritesCountAttribute(): int
    {
        if (array_key_exists('favorites_count', $this->attributes)) {
            return (int) $this->attributes['favorites_count'];
        }
        return $this->favorites()->count();
    }

    public function getConditionLabelAttribute(): string
    {
        return match (true) {
            $this->condition_percent >= 95 => 'Như mới',
            $this->condition_percent >= 80 => 'Rất tốt',
            $this->condition_percent >= 60 => 'Tốt',
            $this->condition_percent >= 40 => 'Trung bình',
            default => 'Cần sửa chữa',
        };
    }

    // ── Methods ────────────────────────────────────────

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function approve(): void
    {
        $this->update([
            'status' => ProductStatus::ACTIVE,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => ProductStatus::REJECTED,
            'rejection_reason' => $reason,
        ]);
    }

    public function markAsSold(): void
    {
        $this->update([
            'status' => ProductStatus::SOLD,
            'sold_at' => now(),
        ]);
    }
}
