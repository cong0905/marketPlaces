<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected ProductRepositoryInterface $productRepo,
        protected CategoryRepositoryInterface $categoryRepo,
    ) {}

    /**
     * Browse products with filters
     */
    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'category_id', 'min_price', 'max_price', 'province', 'condition_min', 'sort']);
        $products = $this->productService->search($filters);
        $categories = $this->categoryRepo->getActiveWithChildren();
        $provinces = \App\Models\Province::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'provinces', 'filters'));
    }

    /**
     * Show single product
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findBySlug($slug);

        if (!$product) {
            abort(404);
        }

        if (!in_array($product->status->value, ['active', 'sold'])) {
            if ($product->user_id !== auth()->id()) {
                abort(404);
            }
        }

        $product->incrementViewCount();
        $product->load(['user', 'category', 'images']);

        $reviews = $product->user->reviewsReceived()->with('reviewer')->latest()->get();

        $relatedProducts = $this->productRepo->getRelatedProducts($product->id, $product->category_id, 4);

        return view('products.show', compact('product', 'relatedProducts', 'reviews'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = $this->categoryRepo->getActiveWithChildren();
        $categoryOptions = $categories->map(function ($cat) {
            return [
                'label' => $cat->name,
                'options' => $cat->children->map(fn($child) => ['value' => $child->id, 'label' => $child->name])->toArray()
            ];
        })->toArray();
        $provinces = \App\Models\Province::orderBy('name')->get();
        $provinceOptions = $provinces->map(fn($p) => ['value' => $p->id, 'label' => $p->name])->toArray();

        return view('products.create', compact('categories', 'categoryOptions', 'provinceOptions'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:1000',
            'quantity' => 'required|integer|min:1',
            'is_negotiable' => 'boolean',
            'condition_percent' => 'required|integer|min:0|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'video_url' => 'nullable|url|max:255',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $images = $request->file('images', []);
        unset($validated['images']);
        $validated['user_id'] = auth()->id();
        $validated['description'] = Purifier::clean($validated['description']);

        $product = $this->productService->createProduct($validated, $images);

        return redirect()->route('products.show', $product->slug)
            ->with('success', 'Tin đăng đã được tạo và đang chờ duyệt!');
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = $this->categoryRepo->getActiveWithChildren();
        $categoryOptions = $categories->map(function ($cat) {
            return [
                'label' => $cat->name,
                'options' => $cat->children->map(fn($child) => ['value' => $child->id, 'label' => $child->name])->toArray()
            ];
        })->toArray();
        $provinces = \App\Models\Province::orderBy('name')->get();
        $provinceOptions = $provinces->map(fn($p) => ['value' => $p->id, 'label' => $p->name])->toArray();

        return view('products.edit', compact('product', 'categories', 'categoryOptions', 'provinceOptions'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:1000',
            'quantity' => 'required|integer|min:0',
            'is_negotiable' => 'boolean',
            'condition_percent' => 'required|integer|min:0|max:100',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'video_url' => 'nullable|url|max:255',
            'new_images' => 'nullable|array|max:10',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer',
        ]);

        $newImages = $request->file('new_images', []);
        $deleteImageIds = $validated['delete_images'] ?? [];
        unset($validated['new_images'], $validated['delete_images']);

        $validated['description'] = Purifier::clean($validated['description']);

        $this->productService->updateProduct($product->id, $validated, $newImages, $deleteImageIds);

        return redirect()->route('products.show', $product->slug)
            ->with('success', 'Tin đăng đã được cập nhật!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->productService->deleteProduct($product->id);

        return redirect()->route('dashboard')
            ->with('success', 'Tin đăng đã được xóa!');
    }
}
