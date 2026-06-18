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
        $products = Cache::remember('home.latest_products', 600, function () {
            return $this->productRepo->getActiveProducts(12);
        });
        $categories = $this->categoryRepo->getActiveWithChildren();

        return view('home', compact('products', 'categories'));
    }
}
