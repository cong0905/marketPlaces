<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected VNPayService $vnpayService
    ) {}

    /**
     * Show the checkout form
     */
    public function create(Product $product)
    {
        // Only active products can be bought
        if ($product->status->value !== 'active') {
            return redirect()->back()->with('error', 'Sản phẩm này không còn bán nữa.');
        }

        // Cannot buy your own product
        if ($product->user_id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể tự mua sản phẩm của chính mình.');
        }

        return view('checkout.create', compact('product'));
    }

    /**
     * Process the checkout
     */
    public function store(Request $request, Product $product)
    {
        if ($product->status->value !== 'active') {
            return redirect()->route('products.show', $product->slug)->with('error', 'Sản phẩm này đã được bán cho người khác.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,vnpay', // App\Enums\PaymentMethod values
            'note' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        try {
            $coupon = null;
            if (!empty($validated['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', $validated['coupon_code'])->first();
                if (!$coupon || !$coupon->isValid()) {
                    return redirect()->back()->withInput()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết hạn.');
                }
            }

            $order = $this->orderService->placeOrder(auth()->user(), $product, $validated, $coupon);

            // Simple COD simulation
            if ($validated['payment_method'] === 'cod') {
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Đặt hàng thành công!');
            }

            // VNPay flow
            $vnpayUrl = $this->vnpayService->generatePaymentUrl($order, $request);
            return redirect()->away($vnpayUrl);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show success page
     */
    public function success(Order $order)
    {
        // Authorization: only buyer can view their order success page
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product.user', 'items.product.images']);

        return view('checkout.success', compact('order'));
    }
}
