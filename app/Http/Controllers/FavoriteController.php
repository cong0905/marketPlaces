<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for a product.
     */
    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->hasFavorited($product)) {
            $user->favorites()->where('product_id', $product->id)->delete();
            $isFavorited = false;
            $message = 'Đã bỏ yêu thích.';
        } else {
            $user->favorites()->create(['product_id' => $product->id]);
            $isFavorited = true;
            $message = 'Đã thêm vào yêu thích!';
        }

        // Return JSON for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'is_favorited' => $isFavorited,
                'favorites_count' => $product->favorites()->count(),
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
