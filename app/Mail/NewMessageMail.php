<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Message $messageData)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bạn có tin nhắn mới từ ' . $this->messageData->sender->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.messages.new',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
