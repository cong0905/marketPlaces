<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Product $product
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'product_rejected',
            'icon' => 'cancel',
            'title' => 'Tin đăng bị từ chối',
            'message' => "Tin đăng \"{$this->product->title}\" đã bị từ chối. Lý do: {$this->product->rejection_reason}",
            'product_id' => $this->product->id,
            'url' => route('dashboard', ['tab' => 'products']),
        ];
    }
}
