<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $product = $this->order->items->first()?->product;

        return [
            'type' => 'order_placed',
            'icon' => 'shopping_bag',
            'title' => 'Bạn có đơn hàng mới',
            'message' => "Đơn hàng mới từ {$this->order->shipping_name} cho sản phẩm \"{$product?->title}\"",
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'url' => route('dashboard', ['tab' => 'sales']),
        ];
    }
}
