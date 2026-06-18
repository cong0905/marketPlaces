<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductApprovedNotification extends Notification
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
            'type' => 'product_approved',
            'icon' => 'check_circle',
            'title' => 'Tin đăng được duyệt',
            'message' => "Tin đăng \"{$this->product->title}\" của bạn đã được duyệt!",
            'product_id' => $this->product->id,
            'url' => route('products.show', $this->product->slug),
        ];
    }
}
