<?php

namespace App\Mail;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreorderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Preorder $preorder) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pre-order received — '.$this->preorder->product->name.' — Aurachell',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.preorder-confirmation',
        );
    }
}
