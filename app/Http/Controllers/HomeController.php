<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo,
        protected CategoryRepositoryInterface $categoryRepo,
    ) {}

    public function index()
    {
        $products = $this->productRepo->getActiveProducts(12);
        $categories = $this->categoryRepo->getActiveWithChildren();

        $flashSales = \App\Models\FlashSale::with(['product.images', 'product.province'])
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->get()
            ->filter(function ($fs) {
                return $fs->product && $fs->product->status->value === 'active';
            });

        return view('home', compact('products', 'categories', 'flashSales'));
    }
}
