<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Kiểm tra quyền: Chỉ người mua mới được review
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Kiểm tra trạng thái đơn hàng (chỉ review khi completed)
        if ($order->status->value !== 'completed') {
            return back()->with('error', 'Chỉ có thể đánh giá khi đơn hàng đã hoàn tất.');
        }

        // Kiểm tra xem đã review chưa
        if ($order->review) {
            return back()->with('error', 'Bạn đã đánh giá đơn hàng này rồi.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'order_id' => $order->id,
            'reviewer_id' => auth()->id(),
            'reviewed_user_id' => $order->seller_id,
            'rating' => $request->rating,
            'comment' => Purifier::clean($request->comment),
        ]);

        // Cập nhật lại điểm đánh giá trung bình của người bán
        $order->seller->updateRating();

        return back()->with('success', 'Đánh giá của bạn đã được ghi nhận.');
    }
}
