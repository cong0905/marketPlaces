<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    public function getRootCategories(): Collection;
    public function getActiveWithChildren(): Collection;
    public function getCategoryTree(): Collection;
}
