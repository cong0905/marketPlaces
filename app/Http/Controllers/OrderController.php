<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Seller confirms the order
     */
    public function confirm(Order $order)
    {
        if ($order->seller_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($order->confirm()) {
            return back()->with('success', 'Đơn hàng đã được xác nhận thành công.');
        }

        return back()->with('error', 'Không thể xác nhận đơn hàng lúc này.');
    }

    /**
     * Seller marks the order as shipped
     */
    public function ship(Order $order)
    {
        if ($order->seller_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($order->ship()) {
            return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái đang giao.');
        }

        return back()->with('error', 'Không thể cập nhật trạng thái đơn hàng lúc này.');
    }

    /**
     * Buyer marks the order as completed/received
     */
    public function complete(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        if ($order->complete()) {
            return back()->with('success', 'Đã xác nhận nhận hàng thành công. Hãy đánh giá người bán nhé!');
        }

        return back()->with('error', 'Không thể đánh dấu hoàn thành lúc này.');
    }

    /**
     * Cancel the order (by buyer or seller)
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->seller_id !== auth()->id() && $order->buyer_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $reason = $request->input('reason', 'Đã hủy bởi người dùng.');

        if ($order->cancel($reason)) {
            return back()->with('success', 'Đơn hàng đã được hủy.');
        }

        return back()->with('error', 'Không thể hủy đơn hàng lúc này.');
    }
}
