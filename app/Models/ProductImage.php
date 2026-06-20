<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'is_primary', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            // \Illuminate\Support\Facades\Cache::forget('home.latest_products');
        });

        static::deleted(function () {
            // \Illuminate\Support\Facades\Cache::forget('home.latest_products');
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'cloudinary://')) {
            $publicId = str_replace('cloudinary://', '', $this->path);
            return cloudinary()->getUrl($publicId);
        }
        return asset('storage/' . $this->path);
    }

    public function getUrlOriginalAttribute(): string
    {
        if (str_starts_with($this->path, 'cloudinary://')) {
            $publicId = str_replace('cloudinary://', '', $this->path);
            return cloudinary()->getUrl($publicId);
        }
        return asset('storage/' . str_replace(basename($this->path), 'original_' . basename($this->path), $this->path));
    }

    public function getUrlMediumAttribute(): string
    {
        if (str_starts_with($this->path, 'cloudinary://')) {
            $publicId = str_replace('cloudinary://', '', $this->path);
            // Use Cloudinary transformation for medium size
            return cloudinary()->getUrl($publicId, ['width' => 800, 'crop' => 'scale']);
        }
        return asset('storage/' . str_replace(basename($this->path), 'medium_' . basename($this->path), $this->path));
    }

    public function getUrlThumbAttribute(): string
    {
        if (str_starts_with($this->path, 'cloudinary://')) {
            $publicId = str_replace('cloudinary://', '', $this->path);
            // Use Cloudinary transformation for thumb size
            return cloudinary()->getUrl($publicId, ['width' => 300, 'crop' => 'scale']);
        }
        return asset('storage/' . str_replace(basename($this->path), 'thumb_' . basename($this->path), $this->path));
    }
}
