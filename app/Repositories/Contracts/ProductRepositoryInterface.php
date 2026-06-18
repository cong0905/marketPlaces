<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getActiveProducts(int $perPage = 12): LengthAwarePaginator;
    public function getPendingProducts(int $perPage = 15): LengthAwarePaginator;
    public function getByUser(int $userId, int $perPage = 12): LengthAwarePaginator;
    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator;
    public function search(array $filters, int $perPage = 12): LengthAwarePaginator;
    public function getRelatedProducts(int $productId, int $categoryId, int $limit = 8): mixed;
}
