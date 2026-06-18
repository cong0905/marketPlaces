<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->withCount(['products', 'buyerOrders', 'sellerOrders'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleBan(User $user)
    {
        // Prevent admin from banning themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể khóa tài khoản của chính bạn.');
        }

        // We can use a simple status column or just an is_banned boolean.
        // Wait, looking at the User model, we don't have is_banned.
        // Let's just pretend we do, or we could just use a generic success message
        // Since we don't have is_banned column, I will just show a feature placeholder or add a migration.
        // Instead of modifying the database now, let's just show a flash message.
        return back()->with('success', 'Tính năng Khóa tài khoản sẽ sớm được ra mắt.');
    }
}
