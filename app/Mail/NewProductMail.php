<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewProductMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Product $product) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Arrival: '.$this->product->name.' — Aurachell');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-product');
    }
}
