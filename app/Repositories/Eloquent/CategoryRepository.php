<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getRootCategories(): Collection
    {
        return $this->model->roots()->active()->ordered()->get();
    }

    public function getActiveWithChildren(): Collection
    {
        return Cache::remember('categories.active_with_children', 3600, function () {
            return $this->model->roots()
                ->active()
                ->ordered()
                ->with(['children' => fn($q) => $q->active()->ordered()])
                ->get();
        });
    }

    public function getCategoryTree(): Collection
    {
        return Cache::remember('categories.tree', 3600, function () {
            return $this->model->roots()
                ->ordered()
                ->with('allChildren')
                ->get();
        });
    }
}
