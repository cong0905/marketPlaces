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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getUrlOriginalAttribute(): string
    {
        return asset('storage/' . str_replace(basename($this->path), 'original_' . basename($this->path), $this->path));
    }

    public function getUrlMediumAttribute(): string
    {
        return asset('storage/' . str_replace(basename($this->path), 'medium_' . basename($this->path), $this->path));
    }

    public function getUrlThumbAttribute(): string
    {
        return asset('storage/' . str_replace(basename($this->path), 'thumb_' . basename($this->path), $this->path));
    }
}
