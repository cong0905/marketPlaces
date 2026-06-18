<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function storeProductReport(Request $request, Product $product)
    {
        $request->validate([
            'reason' => 'required|string',
            'description' => 'required|string|max:1000',
        ]);

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => Product::class,
            'reportable_id' => $product->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Cảm ơn bạn đã báo cáo. Quản trị viên sẽ xem xét và xử lý sớm nhất.');
    }

    public function storeUserReport(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string',
            'description' => 'required|string|max:1000',
        ]);

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => User::class,
            'reportable_id' => $user->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đã báo cáo người dùng thành công.');
    }
}
