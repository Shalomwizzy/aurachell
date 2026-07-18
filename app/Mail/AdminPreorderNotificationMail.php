<?php

namespace App\Mail;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPreorderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Preorder $preorder) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 New Pre-order — '.$this->preorder->product->name.' × '.$this->preorder->quantity,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-preorder-notification',
        );
    }
}
