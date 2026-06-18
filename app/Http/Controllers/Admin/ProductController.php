<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending'); // pending, active, rejected
        
        $products = Product::where('status', $status)
            ->with(['user', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products', 'status'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'category', 'images']);
        return view('admin.products.show', compact('product'));
    }

    public function approve(Product $product)
    {
        $this->productService->approveProduct($product->id);

        // Notify the product owner
        $product->refresh();
        $product->user->notify(new \App\Notifications\ProductApprovedNotification($product));
        
        return redirect()->route('admin.products.index', ['status' => 'pending'])
            ->with('success', "Đã duyệt sản phẩm: {$product->title}");
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $this->productService->rejectProduct($product->id, $request->rejection_reason);

        // Notify the product owner
        $product->refresh();
        $product->user->notify(new \App\Notifications\ProductRejectedNotification($product));

        return redirect()->route('admin.products.index', ['status' => 'pending'])
            ->with('success', "Đã từ chối sản phẩm: {$product->title}");
    }
}
