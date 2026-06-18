<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Load active products of the user
        $products = Product::where('user_id', $user->id)
            ->active()
            ->with(['category', 'province', 'district', 'images'])
            ->latest()
            ->paginate(12);
            
        // Load reviews for the user as a seller
        $reviews = $user->reviewsReceived()->with('reviewer')->latest()->take(10)->get();
        
        return view('users.show', compact('user', 'products', 'reviews'));
    }
}
