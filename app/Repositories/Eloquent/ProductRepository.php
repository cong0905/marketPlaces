<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereIn('status', [\App\Enums\ProductStatus::ACTIVE, \App\Enums\ProductStatus::SOLD])
            ->with(['user', 'category', 'images'])
            ->withCount('favorites')
            ->latest()
            ->paginate($perPage);
    }

    public function getPendingProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->pending()
            ->with(['user', 'category', 'images'])
            ->oldest()
            ->paginate($perPage);
    }

    public function getByUser(int $userId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)
            ->with(['category', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereIn('status', [\App\Enums\ProductStatus::ACTIVE, \App\Enums\ProductStatus::SOLD])
            ->byCategory($categoryId)
            ->with(['user', 'images'])
            ->latest()
            ->paginate($perPage);
    }

    public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->model->whereIn('status', [\App\Enums\ProductStatus::ACTIVE, \App\Enums\ProductStatus::SOLD])->with(['user', 'category', 'images'])->withCount('favorites');

        if (!empty($filters['keyword'])) {
            $query->search($filters['keyword']);
        }

        if (!empty($filters['category_id'])) {
            $category = \App\Models\Category::find($filters['category_id']);
            if ($category) {
                $categoryIds = array_merge([$category->id], $category->getAllDescendantIds());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $query->priceRange($filters['min_price'] ?? null, $filters['max_price'] ?? null);
        }

        if (!empty($filters['province'])) {
            $query->byProvince($filters['province']);
        }

        if (!empty($filters['condition_min'])) {
            $query->where('condition_percent', '>=', $filters['condition_min']);
        }

        // Sort
        $sort = $filters['sort'] ?? 'latest';
        $query = match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'views' => $query->orderBy('view_count', 'desc'),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    public function getRelatedProducts(int $productId, int $categoryId, int $limit = 8): mixed
    {
        return $this->model->whereIn('status', [\App\Enums\ProductStatus::ACTIVE, \App\Enums\ProductStatus::SOLD])
            ->where('id', '!=', $productId)
            ->where('category_id', $categoryId)
            ->with(['user', 'images'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
