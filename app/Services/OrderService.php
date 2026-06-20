<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(User $buyer, Product $product, array $shippingData, ?\App\Models\Coupon $coupon = null): Order
    {
        return DB::transaction(function () use ($buyer, $product, $shippingData, $coupon) {
            
            $discountAmount = 0;
            if ($coupon) {
                $discountAmount = $coupon->calculateDiscount($product->price);
                if ($discountAmount > 0) {
                    $coupon->increment('used_count');
                }
            }
            
            $totalAmount = max(0, $product->price - $discountAmount);

            $order = Order::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $product->user_id,
                'total_amount' => $totalAmount,
                'status' => OrderStatus::PENDING,
                'payment_method' => $shippingData['payment_method'] ?? PaymentMethod::COD,
                'shipping_address' => $shippingData['address'],
                'shipping_name' => $shippingData['name'],
                'shipping_phone' => $shippingData['phone'],
                'note' => $shippingData['note'] ?? null,
                'coupon_id' => $coupon ? $coupon->id : null,
                'discount_amount' => $discountAmount,
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'price' => $product->price,
            ]);

            // Create pending payment record
            $order->payments()->create([
                'method' => $shippingData['payment_method'] ?? PaymentMethod::COD,
                'amount' => $totalAmount,
                'status' => PaymentStatus::PENDING,
            ]);

            // Decrement quantity and mark as sold if out of stock
            $product->quantity -= 1;
            if ($product->quantity <= 0) {
                $product->status = ProductStatus::SOLD;
            }
            $product->save();

            $order->load(['items.product', 'buyer', 'seller']);

            // Send Notifications & Emails (Bọc try/catch để tránh lỗi 500)
            try {
                // Notify the seller (Database)
                if ($order->seller) {
                    $order->seller->notify(new \App\Notifications\OrderPlacedNotification($order));
                }

                // Gửi email
                \Illuminate\Support\Facades\Mail::to($buyer->email)->send(new \App\Mail\OrderPlacedMail($order, 'buyer'));
                if ($product->user) {
                    \Illuminate\Support\Facades\Mail::to($product->user->email)->send(new \App\Mail\OrderPlacedMail($order, 'seller'));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email đặt hàng: ' . $e->getMessage());
            }

            return $order;
        });
    }

    public function confirmOrder(Order $order): bool
    {
        $result = $order->confirm();
        if ($result) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->buyer->email)->send(new \App\Mail\OrderStatusChangedMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email xác nhận: ' . $e->getMessage());
            }
        }
        return $result;
    }

    public function shipOrder(Order $order): bool
    {
        $result = $order->ship();
        if ($result) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->buyer->email)->send(new \App\Mail\OrderStatusChangedMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email giao hàng: ' . $e->getMessage());
            }
        }
        return $result;
    }

    public function completeOrder(Order $order): bool
    {
        $result = $order->complete();

        if ($result) {
            // Mark payment as successful for COD
            if ($order->payment_method === PaymentMethod::COD) {
                $order->payments()->update(['status' => PaymentStatus::SUCCESS]);
            }

            // Update transaction counts
            $order->buyer->increment('total_transactions');
            $order->seller->increment('total_transactions');
            
            try {
                \Illuminate\Support\Facades\Mail::to($order->seller->email)->send(new \App\Mail\OrderStatusChangedMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email hoàn thành: ' . $e->getMessage());
            }
        }

        return $result;
    }

    public function cancelOrder(Order $order, string $reason = null): bool
    {
        $result = $order->cancel($reason);

        if ($result) {
            // Restore product status and quantity
            foreach ($order->items as $item) {
                $item->product->quantity += 1;
                $item->product->status = ProductStatus::ACTIVE;
                $item->product->sold_at = null;
                $item->product->save();
            }
            
            $recipient = $order->buyer_id === auth()->id() ? $order->seller : $order->buyer;
            try {
                \Illuminate\Support\Facades\Mail::to($recipient->email)->send(new \App\Mail\OrderStatusChangedMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email hủy đơn: ' . $e->getMessage());
            }
        }

        return $result;
    }
}
