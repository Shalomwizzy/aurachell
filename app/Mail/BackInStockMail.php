<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackInStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Product $product) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "{$this->product->name} is back — your wishlist item is available again");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.back-in-stock');
    }
}
