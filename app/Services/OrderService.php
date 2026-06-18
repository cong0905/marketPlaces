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
    public function placeOrder(User $buyer, Product $product, array $shippingData): Order
    {
        return DB::transaction(function () use ($buyer, $product, $shippingData) {
            $order = Order::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $product->user_id,
                'total_amount' => $product->price,
                'status' => OrderStatus::PENDING,
                'payment_method' => $shippingData['payment_method'] ?? PaymentMethod::COD,
                'shipping_address' => $shippingData['address'],
                'shipping_name' => $shippingData['name'],
                'shipping_phone' => $shippingData['phone'],
                'note' => $shippingData['note'] ?? null,
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'price' => $product->price,
            ]);

            // Create pending payment record
            $order->payments()->create([
                'method' => $shippingData['payment_method'] ?? PaymentMethod::COD,
                'amount' => $product->price,
                'status' => PaymentStatus::PENDING,
            ]);

            // Decrement quantity and mark as sold if out of stock
            $product->quantity -= 1;
            if ($product->quantity <= 0) {
                $product->status = ProductStatus::SOLD;
            }
            $product->save();

            $order->load(['items.product', 'buyer', 'seller']);

            // Notify the seller
            $order->seller->notify(new \App\Notifications\OrderPlacedNotification($order));

            return $order;
        });
    }

    public function confirmOrder(Order $order): bool
    {
        return $order->confirm();
    }

    public function shipOrder(Order $order): bool
    {
        return $order->ship();
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
        }

        return $result;
    }
}
