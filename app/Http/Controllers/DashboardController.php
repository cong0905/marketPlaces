<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'products');
        $user = auth()->user();

        $myProducts = collect();
        $purchases = collect();
        $sales = collect();
        $favorites = collect();

        if ($tab === 'products') {
            $myProducts = Product::where('user_id', $user->id)
                ->with(['category', 'images'])
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'purchases') {
            $purchases = Order::where('buyer_id', $user->id)
                ->with(['items.product.images', 'seller'])
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'sales') {
            $sales = Order::where('seller_id', $user->id)
                ->with(['items.product.images', 'buyer'])
                ->latest()
                ->paginate(10);
        } elseif ($tab === 'favorites') {
            $favorites = $user->favoriteProducts()
                ->with(['images', 'province'])
                ->latest('favorites.created_at')
                ->paginate(12);
        }

        return view('dashboard', compact('tab', 'myProducts', 'purchases', 'sales', 'favorites'));
    }
}
