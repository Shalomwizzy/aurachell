<?php

namespace App\Mail;

use App\Models\ProductRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductRequestFulfilledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ProductRequest $productRequest) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Great news! Your requested product is now available — Aurachell',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-request-fulfilled',
        );
    }
}
