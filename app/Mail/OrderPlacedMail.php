<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Order $order, public string $recipientType)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'buyer' 
            ? 'Xác nhận Đơn hàng #' . $this->order->order_number . ' tại Amber'
            : 'Đơn hàng mới #' . $this->order->order_number . ' từ Amber';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.placed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
