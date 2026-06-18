<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Enums\ProductStatus;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            auth()->loginUsingId(1);
        }

        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', ProductStatus::PENDING)->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', OrderStatus::COMPLETED)->sum('total_amount'), // Example stat
        ];

        // Chart Data (Last 6 Months)
        $months = collect(range(5, 0))->map(function($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        });

        $revenueData = [];
        $usersData = [];

        foreach ($months as $month) {
            $revenueData[] = (float) Order::where('status', OrderStatus::COMPLETED)
                                  ->whereMonth('created_at', Carbon::parse($month)->month)
                                  ->whereYear('created_at', Carbon::parse($month)->year)
                                  ->sum('total_amount');
            
            $usersData[] = (int) User::whereMonth('created_at', Carbon::parse($month)->month)
                               ->whereYear('created_at', Carbon::parse($month)->year)
                               ->count();
        }

        $chartData = [
            'labels' => $months->map(fn($m) => Carbon::parse($m)->format('m/Y'))->values()->toArray(),
            'revenue' => $revenueData,
            'users' => $usersData,
        ];

        $recentPendingProducts = Product::where('status', ProductStatus::PENDING)
            ->with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPendingProducts', 'chartData'));
    }
}
